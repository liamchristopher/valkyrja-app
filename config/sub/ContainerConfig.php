<?php

namespace config\sub;

use App\Controllers\HomeController;
use App\Providers\AppServiceProvider;
use Valkyrja\Config\Sub\ContainerConfig as ValkyrjaContainerConfig;
use Valkyrja\Contracts\Config\Env;

/**
 * Class ContainerConfig.
 */
class ContainerConfig extends ValkyrjaContainerConfig
{
    /**
     * ContainerConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        parent::__construct($env);

        $this->providers = $env::CONTAINER_PROVIDERS
            ?? [
                AppServiceProvider::class,
                // TwigServiceProvider::class,
            ];

        $this->services = [
            HomeController::class,
        ];

        $this->contextServices = [
            HomeController::class,
        ];
    }
}
