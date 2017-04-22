<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing;

use Closure;

use Valkyrja\Contracts\Application as ApplicationContract;
use Valkyrja\Contracts\Http\Controller as ControllerContract;
use Valkyrja\Contracts\Http\Request as RequestContract;
use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Contracts\Routing\Annotations\RouteParser as RouteParserContract;
use Valkyrja\Contracts\Routing\Router as RouterContract;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Routing\Exceptions\InvalidControllerException;
use Valkyrja\Routing\Exceptions\InvalidRouteName;
use Valkyrja\Routing\Exceptions\NonExistentActionException;
use Valkyrja\Http\RequestMethod;
use Valkyrja\Http\ResponseCode;
use Valkyrja\Routing\Exceptions\InvalidHandlerException;
use Valkyrja\Routing\Models\Route;

/**
 * Class Router
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
class Router implements RouterContract
{
    /**
     * The static routes type.
     *
     * @constant string
     */
    protected const STATIC_ROUTES_TYPE = 'static';

    /**
     * The dynamic routes type.
     */
    protected const DYNAMIC_ROUTES_TYPE = 'dynamic';

    /**
     * The name routes type.
     */
    protected const NAME_ROUTES_TYPE = 'name';

    /**
     * Application.
     *
     * @var \Valkyrja\Contracts\Application
     */
    protected $app;

    /**
     * Application routes.
     *
     * @var array
     */
    protected $routes = [
        self::STATIC_ROUTES_TYPE  => [
            RequestMethod::GET    => [],
            RequestMethod::POST   => [],
            RequestMethod::PUT    => [],
            RequestMethod::PATCH  => [],
            RequestMethod::DELETE => [],
            RequestMethod::HEAD   => [],
        ],
        self::DYNAMIC_ROUTES_TYPE => [
            RequestMethod::GET    => [],
            RequestMethod::POST   => [],
            RequestMethod::PUT    => [],
            RequestMethod::PATCH  => [],
            RequestMethod::DELETE => [],
            RequestMethod::HEAD   => [],
        ],
        self::NAME_ROUTES_TYPE    => [],
    ];

    /**
     * Router constructor.
     *
     * @param \Valkyrja\Contracts\Application $application
     */
    public function __construct(ApplicationContract $application)
    {
        $this->app = $application;
    }

    /**
     * Set a single route.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return void
     *
     * @throws \Valkyrja\Routing\Exceptions\NonExistentActionException
     */
    public function addRoute(Route $route): void
    {
        // Let's check the action method is callable before proceeding
        if (null === $route->getHandler() && ! is_callable(
                [
                    $route->getController(),
                    $route->getAction(),
                ]
            )
        ) {
            throw new NonExistentActionException(
                'Action does not exist in controller for route : '
                . $route->getPath()
                . $route->getController()
                . '@'
                . $route->getAction()
            );
        }

        $path = $route->getPath();

        // If all routes should have a trailing slash
        // and the route doesn't already end with a slash
        if ($this->app->config()->routing->trailingSlash && false === strpos($path, '/', -1)) {
            // Add a trailing slash
            $path .= '/';
        }

        // If this is a dynamic route
        if ($route->getDynamic()) {
            // Get all matches for {paramName} and {paramName:(validator)} in the path
            preg_match_all(
                '/' . static::VARIABLE_REGEX . '/x',
                $path,
                $params
            );
            /** @var array[] $params */

            // Run through all matches
            foreach ($params[0] as $key => $param) {
                // Check if a global regex alias was used
                switch ($params[2][$key]) {
                    case 'num' :
                        $replacement = '(\d+)';
                        break;
                    case 'slug' :
                        $replacement = '([a-zA-Z0-9-]+)';
                        break;
                    case 'alpha' :
                        $replacement = '([a-zA-Z]+)';
                        break;
                    case 'alpha-lowercase' :
                        $replacement = '([a-z]+)';
                        break;
                    case 'alpha-uppercase' :
                        $replacement = '([A-Z]+)';
                        break;
                    case 'alpha-num' :
                        $replacement = '([a-zA-Z0-9]+)';
                        break;
                    case 'alpha-num-underscore' :
                        $replacement = '(\w+)';
                        break;
                    default :
                        // Check if a regex was set for this match, otherwise use a wildcard all
                        $replacement = $params[2][$key] ?: '(.*)';
                        break;
                }

                // Replace the matches with a regex
                $path = str_replace($param, $replacement, $path);
            }

            $path = str_replace('/', '\/', $path);
            $path = '/^' . $path . '$/';
            $route->setPath($path);
            $route->setParams($params);

            // Set it in the dynamic routes array
            $this->routes[static::DYNAMIC_ROUTES_TYPE][$route->getMethod()][$path] = $route;
        }
        // Otherwise set it in the static routes array
        else {
            $route->setPath($path);
            $this->routes[static::STATIC_ROUTES_TYPE][$route->getMethod()][$path] = $route;
        }

        if ($route->getName()) {
            $this->routes[static::NAME_ROUTES_TYPE][$route->getName()] = [
                $route->getDynamic() ? static::DYNAMIC_ROUTES_TYPE : static::STATIC_ROUTES_TYPE,
                $route->getMethod(),
                $path,
            ];
        }
    }

    /**
     * Helper function to set a GET addRoute.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return void
     *
     * @throws \Exception
     */
    public function get(Route $route): void
    {
        $route->setMethod(new RequestMethod(RequestMethod::GET));

        $this->addRoute($route);
    }

    /**
     * Helper function to set a POST addRoute.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return void
     *
     * @throws \Exception
     */
    public function post(Route $route): void
    {
        $route->setMethod(new RequestMethod(RequestMethod::POST));

        $this->addRoute($route);
    }

    /**
     * Helper function to set a PUT addRoute.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return void
     *
     * @throws \Exception
     */
    public function put(Route $route): void
    {
        $route->setMethod(new RequestMethod(RequestMethod::PUT));

        $this->addRoute($route);
    }

    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return void
     *
     * @throws \Exception
     */
    public function patch(Route $route): void
    {
        $route->setMethod(new RequestMethod(RequestMethod::PATCH));

        $this->addRoute($route);
    }

    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return void
     *
     * @throws \Exception
     */
    public function delete(Route $route): void
    {
        $route->setMethod(new RequestMethod(RequestMethod::DELETE));

        $this->addRoute($route);
    }

    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return void
     *
     * @throws \Exception
     */
    public function head(Route $route): void
    {
        $route->setMethod(new RequestMethod(RequestMethod::HEAD));

        $this->addRoute($route);
    }

    /**
     * Set routes from a given array of routes.
     *
     * @param array $routes The routes to set
     *
     * @return void
     */
    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    /**
     * Get all routes set by the application.
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Get routes by method type.
     *
     * @param string $method The method type of get
     * @param string $type   [optional] The type of routes (static/dynamic)
     *
     * @return \Valkyrja\Routing\Models\Route[]
     */
    protected function getRoutesByMethod(string $method, string $type = self::STATIC_ROUTES_TYPE): array
    {
        return $this->routes[$type][$method];
    }

    /**
     * Get a route from a request.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     *
     * @return \Valkyrja\Routing\Models\Route
     */
    public function getRouteFromRequest(RequestContract $request):? Route
    {
        $requestMethod = $request->getMethod();
        $requestUri = $request->getPathOnly();

        // Decode the request uri
        $requestUri = rawurldecode($requestUri);

        return $this->getRouteByPath($requestUri, $requestMethod);
    }

    /**
     * Get a route by path.
     *
     * @param string $path   The path
     * @param string $method [optional] The method type of get
     *
     * @return \Valkyrja\Routing\Models\Route
     */
    public function getRouteByPath(string $path, string $method = RequestMethod::GET):? Route
    {
        $route = null;

        // Let's check if the route is set in the static routes
        if (isset($this->routes[static::STATIC_ROUTES_TYPE][$method][$path])) {
            $route = $this->routes[static::STATIC_ROUTES_TYPE][$method][$path];
        }
        // If trailing slashes and non trailing are allowed check it too
        else if (
            $this->app->config()->routing->allowWithTrailingSlash &&
            isset($this->routes[static::STATIC_ROUTES_TYPE][$method][substr($path, 0, -1)])
        ) {
            $route = $this->routes[static::STATIC_ROUTES_TYPE][$method][substr($path, 0, -1)];
        }
        // Otherwise check dynamic routes for a match
        else {
            // Attempt to find a match using dynamic routes that are set
            foreach ($this->getRoutesByMethod($method, static::DYNAMIC_ROUTES_TYPE) as $pathIndex => $dynamicRoute) {
                // If the preg match is successful, we've found our route!
                if (preg_match($pathIndex, $path, $matches)) {
                    $route = $dynamicRoute;
                    $route->setMatches($matches);
                    break;
                }
            }
        }

        return $route;
    }

    /**
     * Determine whether a route name exists.
     *
     * @param string $name The name of the route
     *
     * @return bool
     */
    public function issetRouteName(string $name): bool
    {
        return isset($this->routes[static::NAME_ROUTES_TYPE][$name]);
    }

    /**
     * Get a route by name.
     *
     * @param string $name The name of the route to get
     *
     * @return \Valkyrja\Routing\Models\Route
     *
     * @throws \Valkyrja\Routing\Exceptions\InvalidRouteName
     */
    public function getRouteByName(string $name): Route
    {
        $route = null;

        // Let's check if the route is set in the static routes
        if (isset($this->routes[static::NAME_ROUTES_TYPE][$name])) {
            $route = $this->routes[static::NAME_ROUTES_TYPE][$name];
            $route = $this->routes[$route[0]][$route[1]][$route[2]];
        }

        // If no route was found
        if (! $route) {
            throw new InvalidRouteName($name);
        }

        return $route;
    }

    /**
     * Get a route url by name.
     *
     * @param string $name The name of the route to get
     * @param array  $data [optional] The route data if dynamic
     *
     * @return string
     *
     * @throws \Valkyrja\Routing\Exceptions\InvalidRouteName
     */
    public function getRouteUrlByName(string $name, array $data = []): string
    {
        // Get the matching route
        $route = $this->getRouteByName($name);

        // Set the path as the route's path
        $path = $route->getPath();

        // If there is data
        if ($data) {
            // Get the route's params
            /** @var array[] $params */
            $params = $route->getParams();

            // Iterate through all the prams
            foreach ($params[0] as $key => $param) {
                // Set the path by replacing the params with the data arguments
                $path = str_replace($param, $data[$params[1][$key]], $path);
            }

            return $path;
        }

        return $path;
    }

    /**
     * Get a route's arguments.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return array
     */
    public function getRouteArguments(Route $route): array
    {
        // Set the arguments to return
        $arguments = [];
        // Get the matches
        $matches = $route->getMatches();
        // The injectable objects
        $injectable = $route->getInjectables();

        // If there are injectable items defined for this route
        if ($injectable) {
            // Check for any injectable objects that have been set on the route
            foreach ($injectable as $injectableObject) {
                // Set these as the first set of arguments to pass to the action
                $arguments[] = $this->app->container()->get($injectableObject);
            }
        }

        // If there were matches from the dynamic route
        if ($matches) {
            // Iterate through the matches
            foreach ($matches as $index => $match) {
                // Disregard the first match (which is the route itself)
                if ($index === 0) {
                    continue;
                }

                // Set the remaining arguments to pass to the action with those matches
                $arguments[] = $match;
            }
        }

        return $arguments;
    }

    /**
     * Setup routes.
     *
     * @return void
     *
     * @throws \Valkyrja\Routing\Exceptions\NonExistentActionException
     */
    public function setupRoutes(): void
    {
        // If the application should use the routes cache file
        if ($this->app->config()->routing->useRoutesCacheFile) {
            // Set the application routes with said file
            $this->routes = require $this->app->config()->routing->routesCacheFile;

            // Then return out of routes setup
            return;
        }

        // If annotations are enabled and routing should use annotations
        if ($this->app->config()->routing->useAnnotations && $this->app->config()->annotations->enabled) {
            // Setup annotated routes
            $this->setupAnnotatedRoutes();

            // If only annotations should be used for routing
            if ($this->app->config()->routing->useAnnotationsExclusively) {
                // Return to avoid loading routes file
                return;
            }
        }

        // Include the routes file
        // NOTE: Included if annotations are set or not due to possibility of routes being defined
        // within the controllers as well as within the routes file
        require $this->app->config()->routing->routesFile;
    }

    /**
     * Setup annotated routes.
     *
     * @return void
     *
     * @throws \Valkyrja\Routing\Exceptions\NonExistentActionException
     */
    protected function setupAnnotatedRoutes(): void
    {
        // Routes array
        $routes = [];
        // The routes annotations parser
        /** @var RouteParserContract $parser */
        $parser = $this->app->container()->get(RouteParserContract::class);

        // Iterate through each controller
        foreach ($this->app->config()->routing->controllers as $controller) {
            // Get a reflection of the controller
            $reflection = new \ReflectionClass($controller);
            /** @var \Valkyrja\Routing\Models\Route[] $controllerRoutes */
            $controllerRoutes = $parser->getAnnotations($reflection->getDocComment());

            // Iterate through all the methods in the controller
            foreach ($reflection->getMethods() as $method) {
                // Get the @Route annotation for the method
                $actionRoutes = $parser->getAnnotations($method->getDocComment());

                // Ensure a route was defined
                if ($actionRoutes) {
                    // Setup to find any injectable objects through the service container
                    $injectable = [];

                    // Iterate through the method's parameters
                    foreach ($method->getParameters() as $parameter) {
                        // We only care for classes
                        if ($parameter->getClass()) {
                            // Set the injectable in the array
                            $injectable[] = $parameter->getClass()->getName();
                        }
                    }

                    /**
                     * Iterate through all the action's routes.
                     *
                     * @var \Valkyrja\Routing\Models\Route $route
                     */
                    foreach ($actionRoutes as $route) {
                        // Set the controller
                        $route->setController($controller);
                        // Set the action
                        $route->setAction($method->getName());
                        // Set the injectable objects
                        $route->setInjectables($injectable);

                        // If controller routes exist
                        if ($controllerRoutes) {
                            // Iterate through the controller defined base routes
                            foreach ($controllerRoutes as $controllerRoute) {
                                // Clone the route found for the method and begin applying the controller
                                // base route to it
                                $newRoute = clone $route;

                                // If there is a base path for this controller
                                if (null !== $controllerPath = $controllerRoute->getPath()) {
                                    // Get the route's path
                                    $path = $route->getPath();

                                    // If this is the index
                                    if ('/' === $path) {
                                        // Set to blank so the final path will be just the base path
                                        $path = '';
                                    }
                                    // If the controller route is the index
                                    else if ('/' === $controllerPath) {
                                        // Set to blank so the final path won't start with double slash
                                        $controllerPath = '';
                                    }

                                    // Set the path to the base path and route path
                                    $newRoute->setPath($controllerPath . $path);
                                }

                                // If there is a base name for this controller
                                if (null !== $controllerName = $controllerRoute->getName()) {
                                    $name = $controllerName . '.' . $route->getName();

                                    // Set the name to the base name and route name
                                    $newRoute->setName($name);
                                }

                                // If the base is dynamic
                                if (false !== $controllerRoute->getDynamic()) {
                                    // Set the route to dynamic
                                    $newRoute->setDynamic(true);
                                }

                                // If the base is secure
                                if (false !== $controllerRoute->getSecure()) {
                                    // Set the route to dynamic
                                    $newRoute->setSecure(true);
                                }

                                // Add the route to the array
                                $routes[] = $newRoute;
                            }
                        }
                        // Otherwise there was no route defined on the controller level
                        else {
                            // So just add the route to the list
                            $routes[] = $route;
                        }
                    }
                }
            }
        }

        // Iterate through the routes
        foreach ($routes as $route) {
            // Set the route
            $this->addRoute($route);
        }
    }

    /**
     * Dispatch the route and find a match.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @throws \Valkyrja\Contracts\Http\Exceptions\HttpException
     * @throws \Valkyrja\Routing\Exceptions\InvalidControllerException
     * @throws \Valkyrja\Routing\Exceptions\InvalidHandlerException
     */
    public function dispatch(RequestContract $request): ResponseContract
    {
        // Get the route from the request
        $route = $this->getRouteFromRequest($request);

        // If no route is found
        if (! $route) {
            // Throw the 404 and abort the app
            $this->app->abort(ResponseCode::HTTP_NOT_FOUND);
        }

        // If the route is secure and the current request is not secure
        if ($route->getSecure() && ! $request->isSecure()) {
            // Throw the redirect to the secure path
            return $this->app->redirect()->secure($request->getPath());
        }

        // If the route has an action and a controller
        if ($route->getAction() && $route->getController()) {
            return $this->dispatchAction($route);
        }

        return $this->dispatchHandler($route);
    }

    /**
     * Dispatch a route's handler.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @throws \Valkyrja\Routing\Exceptions\InvalidHandlerException
     */
    public function dispatchHandler(Route $route): ResponseContract
    {
        // Get the route's arguments
        $arguments = $this->getRouteArguments($route);
        // Set the action as the route's handler
        $action = $route->getHandler();

        // If this action is not a closure
        if (! $action instanceof Closure) {
            throw new InvalidHandlerException(
                'Invalid handler for route : '
                . $route->getPath()
                . ' Name -> '
                . $route->getName()
            );
        }

        // If there are arguments and they should be passed in individually
        if ($arguments) {
            // Call it and set it as our dispatch
            $dispatch = $action(...$arguments);
        }
        // Otherwise no arguments just call the action
        else {
            // Call it and set it as our dispatch
            $dispatch = $action();
        }

        // Get a response from the dispatch results
        return $this->getResponseFromDispatch($dispatch);
    }

    /**
     * Dispatch a route's action.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @throws \Valkyrja\Routing\Exceptions\InvalidControllerException
     */
    public function dispatchAction(Route $route): ResponseContract
    {
        // Get the route's arguments
        $arguments = $this->getRouteArguments($route);
        // Set the action as the route's handler
        $action = $route->getAction();
        // Set the controller through the container
        $controller = $this->app->container()->get($route->getController());

        // Let's make sure the controller is a controller
        if (! $controller instanceof ControllerContract) {
            throw new InvalidControllerException(
                'Invalid controller for route : '
                . $route->getPath()
                . ' Controller -> '
                . $route->getController()
            );
        }

        // If there are arguments
        if ($arguments) {
            // Set the dispatch as the controller action
            $dispatch = $controller->$action(...$arguments);
        }
        // Otherwise no arguments just call the action
        else {
            // Set the dispatch as the controller action
            $dispatch = $controller->$action();
        }

        // Call the controller's after method
        $controller->after();

        // Get a response from the dispatch results
        return $this->getResponseFromDispatch($dispatch);
    }

    /**
     * Get a response from a dispatch.
     *
     * @param mixed $dispatch The dispatch
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    protected function getResponseFromDispatch($dispatch): ResponseContract
    {
        // If the dispatch failed, 404
        if (! $dispatch) {
            $this->app->abort(ResponseCode::HTTP_NOT_FOUND);
        }

        // If the dispatch is a Response then simply return it
        if ($dispatch instanceof ResponseContract) {
            return $dispatch;
        }
        // If the dispatch is a View, render it then wrap it in a new response and return it
        if ($dispatch instanceof ViewContract) {
            return $this->app->response($dispatch->render());
        }

        // Otherwise its a string so wrap it in a new response and return it
        return $this->app->response((string) $dispatch);
    }
}
