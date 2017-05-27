<?php

use Valkyrja\Support\Directory;

/*
 *-------------------------------------------------------------------------
 * Events Configuration
 *-------------------------------------------------------------------------
 *
 * Events are a nifty way to tie into certain happenings throughout the
 * application. Found here are all the configurations required to make
 * events work without a hitch.
 *
 */
return [
    /*
     *-------------------------------------------------------------------------
     * Events Use Annotations
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useAnnotations'            => env()::EVENTS_USE_ANNOTATIONS ?? false,

    /*
     *-------------------------------------------------------------------------
     * Events Use Annotations Exclusively
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useAnnotationsExclusively' => env()::EVENTS_USE_ANNOTATIONS_EXCLUSIVELY ?? false,

    /*
     *-------------------------------------------------------------------------
     * Events Annotation Classes
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'classes'                   => env()::EVENTS_CLASSES ?? [],

    /*
     *-------------------------------------------------------------------------
     * Events Bootstrap File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'filePath'                  => env()::EVENTS_FILE_PATH ?? Directory::bootstrapPath('events.php'),

    /*
     *-------------------------------------------------------------------------
     * Events Cache File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'cacheFilePath'             => env()::EVENTS_CACHE_FILE_PATH ?? Directory::cachePath('events.php'),

    /*
     *-------------------------------------------------------------------------
     * Events Use Cache File
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useCacheFile'              => env()::EVENTS_USE_CACHE_FILE ?? true,
];
