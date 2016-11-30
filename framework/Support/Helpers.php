<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Support;

use config\Config as Config;

use Valkyrja\Config\Config as ValkyrjaConfig;
use Valkyrja\Contracts\Application as ApplicationContract;
use Valkyrja\Contracts\Config\Config as ConfigContract;
use Valkyrja\Contracts\Container\Container;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\Http\ResponseBuilder;
use Valkyrja\Contracts\Http\Router;
use Valkyrja\Contracts\Support\Helpers as HelpersContract;
use Valkyrja\Contracts\View\View;

/**
 * Class Helpers
 *
 * @package Valkyrja\Support
 *
 * @author  Melech Mizrachi
 */
class Helpers implements HelpersContract
{
    /**
     * Return the global $app variable.
     *
     * @return \Valkyrja\Contracts\Application
     */
    public static function app() : ApplicationContract
    {
        return static::container()->get(ApplicationContract::class);
    }

    /**
     * Return the global $app variable.
     *
     * @return \Valkyrja\Contracts\Container\Container
     */
    public static function container() : Container
    {
        global $container;

        return $container;
    }

    /**
     * Get the config class instance.
     *
     * @return \Valkyrja\Contracts\Config\Config|\Valkyrja\Config\Config|\config\Config
     */
    public static function config() : ConfigContract
    {
        return static::container()->get(ConfigContract::class);
    }

    /**
     * Get environment variable.
     *
     * @param string $key
     *
     * @return mixed
     */
    public static function env(string $key) // : mixed
    {
        return class_exists(Config::class)
            ? Config::env($key)
            : ValkyrjaConfig::env($key);
    }

    /**
     * Return the router instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Router
     */
    public static function router() : Router
    {
        return static::container()->get(Router::class);
    }

    /**
     * Throw an HttpException with the given data.
     *
     * @param int    $code    The status code to use
     * @param string $message [optional] The message or data content to use
     * @param array  $headers [optional] The headers to set
     * @param string $view    [optional] The view template name to use
     *
     * @return void
     *
     * @throws \Valkyrja\Contracts\Exceptions\HttpException
     */
    public static function abort($code, $message = '', array $headers = [], $view = null) // : void
    {
        static::app()->abort($code, $message, $headers, $view);
    }

    /**
     * Helper function to set a GET addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function get($path, $handler, $isDynamic = false) // : void
    {
        static::router()->get($path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a POST addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function post($path, $handler, $isDynamic = false) // : void
    {
        static::router()->post($path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a PUT addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function put($path, $handler, $isDynamic = false) // : void
    {
        static::router()->put($path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function patch($path, $handler, $isDynamic = false) // : void
    {
        static::router()->patch($path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function delete($path, $handler, $isDynamic = false) // : void
    {
        static::router()->delete($path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function head($path, $handler, $isDynamic = false) // : void
    {
        static::router()->head($path, $handler, $isDynamic);
    }

    /**
     * Helper function to get base path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function basePath($path = null) : string
    {
        return static::app()->basePath($path);
    }

    /**
     * Helper function to get app path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function appPath($path = null) : string
    {
        return static::app()->appPath($path);
    }

    /**
     * Helper function to get cache path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function cachePath($path = null) : string
    {
        return static::app()->cachePath($path);
    }

    /**
     * Helper function to get config path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function configPath($path = null) : string
    {
        return static::app()->configPath($path);
    }

    /**
     * Helper function to get framework path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function frameworkPath($path = null) : string
    {
        return static::app()->frameworkPath($path);
    }

    /**
     * Helper function to get public path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function publicPath($path = null) : string
    {
        return static::app()->publicPath($path);
    }

    /**
     * Helper function to get resources path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function resourcesPath($path = null) : string
    {
        return static::app()->resourcesPath($path);
    }

    /**
     * Helper function to get storage path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function storagePath($path = null) : string
    {
        return static::app()->storagePath($path);
    }

    /**
     * Helper function to get tests path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function testsPath($path = null) : string
    {
        return static::app()->testsPath($path);
    }

    /**
     * Helper function to get vendor path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function vendorPath($path = null) : string
    {
        return static::app()->vendorPath($path);
    }

    /**
     * Return a new response from the application.
     *
     * @param string $content [optional] The content to set
     * @param int    $status  [optional] The status code to set
     * @param array  $headers [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public static function response($content = '', $status = 200, array $headers = []) : Response
    {
        $factory = static::responseBuilder();

        return $factory->make($content, $status, $headers);
    }

    /**
     * Return a new response from the application.
     *
     * @return \Valkyrja\Contracts\Http\ResponseBuilder
     */
    public static function responseBuilder() : ResponseBuilder
    {
        return static::container()->get(ResponseBuilder::class);
    }

    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public static function view($template = '', array $variables = []) : View
    {
        return static::container()->get(
            View::class,
            [
                $template,
                $variables,
            ]
        );
    }

    /**
     * Throw an http exception.
     *
     * @param int        $statusCode The status code to use
     * @param string     $message    [optional] The Exception message to throw
     * @param \Exception $previous   [optional] The previous exception used for the exception chaining
     * @param array      $headers    [optional] The headers to send
     * @param string     $view       [optional] The view template name to use
     * @param int        $code       [optional] The Exception code
     *
     * @return void
     *
     * @throws \HttpException
     */
    public static function httpException(
        $statusCode,
        $message = null,
        \Exception $previous = null,
        array $headers = [],
        $view = null,
        $code = 0
    ) // : void
    {
        static::app()->httpException($statusCode, $message, $previous, $headers, $view, $code);
    }
}