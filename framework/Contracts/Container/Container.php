<?php
/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Valkyrja\Contracts\Container;

/**
 * Interface Container
 *
 * @package Valkyrja\Contracts\Container
 *
 * @author Melech Mizrachi
 */
interface Container
{
    /**
     * Set the service container for dependency injection.
     *
     * @param array $serviceContainer The service container array to set
     *
     * @return void
     */
    public function setServiceContainer(array $serviceContainer);

    /**
     * Set the service container for dependency injection.
     *
     * @param string               $abstract The abstract to use as the key
     * @param \Closure|array|mixed $instance The instance to set
     *
     * @return void
     */
    public function instance($abstract, $instance);

    /**
     * Get an abstract from the container.
     *
     * @param string $abstract  The abstract to get
     * @param array  $arguments [optional] Arguments to pass
     *
     * @return mixed
     */
    public function container($abstract, array $arguments = []);
}