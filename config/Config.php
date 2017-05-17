<?php

namespace config;

use config\sub\AnnotationsConfig;
use config\sub\AppConfig;
use config\sub\ContainerConfig;
use config\sub\EventsConfig;
use config\sub\LoggerConfig;
use config\sub\RoutingConfig;
use config\sub\SessionConfig;
use config\sub\StorageConfig;
use config\sub\ViewsConfig;
use Valkyrja\Config\Config as ValkyrjaConfig;
use Valkyrja\Contracts\Config\Env;

/**
 * Class Config.
 */
class Config extends ValkyrjaConfig
{
    /**
     * Config constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        parent::__construct($env);

        $this->app         = new AppConfig($env);
        $this->annotations = new AnnotationsConfig($env);
        $this->container   = new ContainerConfig($env);
        $this->events      = new EventsConfig($env);
        $this->logger      = new LoggerConfig($env);
        $this->routing     = new RoutingConfig($env);
        $this->session     = new SessionConfig($env);
        $this->storage     = new StorageConfig($env);
        $this->views       = new ViewsConfig($env);
    }
}
