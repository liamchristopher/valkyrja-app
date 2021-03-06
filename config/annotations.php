<?php

/*
 *-------------------------------------------------------------------------
 * Annotations Configuration
 *-------------------------------------------------------------------------
 *
 * Anything and everything to do with annotations and how they are
 * configured to work within the application can be found here.
 *
 */
return [
    /*
     *-------------------------------------------------------------------------
     * Annotations Enabled
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'enabled'  => env('ANNOTATIONS_ENABLED', false),

    /*
     *-------------------------------------------------------------------------
     * Annotations Cache Dir
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'cacheDir' => env('ANNOTATIONS_CACHE_DIR', storagePath('vendor/annotations')),

    /*
     *-------------------------------------------------------------------------
     * Annotations Map
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'map'      => env(
        'ANNOTATIONS_MAP',
        [
            'Command'        => Valkyrja\Console\Annotations\Command::class,
            'Listener'       => Valkyrja\Events\Annotations\Listener::class,
            'Route'          => Valkyrja\Routing\Annotations\Route::class,
            'Service'        => Valkyrja\Container\Annotations\Service::class,
            'ServiceAlias'   => Valkyrja\Container\Annotations\ServiceAlias::class,
            'ServiceContext' => Valkyrja\Container\Annotations\ServiceContext::class,
        ]
    ),
];
