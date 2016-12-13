<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Support;

use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Config\Config;
use Valkyrja\Contracts\Config\Env;
use Valkyrja\Contracts\Container\Container;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\Http\ResponseBuilder;
use Valkyrja\Contracts\Http\Router;
use Valkyrja\Contracts\View\View;

/**
 * Interface Helpers
 *
 * @package Valkyrja\Contracts\Support
 *
 * @author  Melech Mizrachi
 */
interface Helpers
{
    /**
     * @deprecated
     *
     * Return the application.
     *
     * @return \Valkyrja\Contracts\Application
     */
    public static function app() : Application;

    /**
     * @deprecated
     *
     * Return the container.
     *
     * @return \Valkyrja\Contracts\Container\Container
     */
    public static function container() : Container;

    /**
     * @deprecated
     *
     * Get the config class instance.
     *
     * @return \Valkyrja\Contracts\Config\Config|\Valkyrja\Config\Config|\config\Config
     */
    public static function config() : Config;

    /**
     * @deprecated
     *
     * Get environment variables.
     *
     * @return \Valkyrja\Contracts\Config\Env|\Valkyrja\Config\Env||config|Env
     */
    public static function env() : Env;

    /**
     * @deprecated
     *
     * Return the router instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Router
     */
    public static function router() : Router;

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
    public static function abort(int $code, string $message = '', array $headers = [], string $view = null) : void;

    /**
     * Helper function to set a GET addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function get(string $path, $handler, bool $isDynamic = false) : void;

    /**
     * Helper function to set a POST addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function post(string $path, $handler, bool $isDynamic = false) : void;

    /**
     * Helper function to set a PUT addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function put(string $path, $handler, bool $isDynamic = false) : void;

    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function patch(string $path, $handler, bool $isDynamic = false) : void;

    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function delete(string $path, $handler, bool $isDynamic = false) : void;

    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function head(string $path, $handler, bool $isDynamic = false) : void;

    /**
     * Helper function to get base path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function basePath(string $path = null) : string;

    /**
     * Helper function to get app path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function appPath(string $path = null) : string;

    /**
     * Helper function to get cache path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function cachePath(string $path = null) : string;

    /**
     * Helper function to get config path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function configPath(string $path = null) : string;

    /**
     * Helper function to get framework path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function frameworkPath(string $path = null) : string;

    /**
     * Helper function to get public path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function publicPath(string $path = null) : string;

    /**
     * Helper function to get resources path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function resourcesPath(string $path = null) : string;

    /**
     * Helper function to get storage path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function storagePath(string $path = null) : string;

    /**
     * Helper function to get tests path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function testsPath(string $path = null) : string;

    /**
     * Helper function to get vendor path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function vendorPath(string $path = null) : string;

    /**
     * @deprecated
     *
     * Return a new response from the application.
     *
     * @param string $content [optional] The content to set
     * @param int    $status  [optional] The status code to set
     * @param array  $headers [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public static function response(string $content = '', int $status = 200, array $headers = []) : Response;

    /**
     * @deprecated
     *
     * Return a new response from the application.
     *
     * @return \Valkyrja\Contracts\Http\ResponseBuilder
     */
    public static function responseBuilder() : ResponseBuilder;

    /**
     * @deprecated
     *
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public static function view(string $template = '', array $variables = []) : View;
}
