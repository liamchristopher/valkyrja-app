<?php

/**
 * Valkyrja - PHP framework
 *
 * @package Valkyrja
 * @author  Melech Mizrachi
 */

// Set a global constant for when the application as a whole started
define('APP_START', microtime(true));

/*
 *-------------------------------------------------------------------------
 * Composer Auto Loader Assemble!
 *-------------------------------------------------------------------------
 *
 * Autoload all the application namespaces, dependencies, and files using
 * composer to both manage all dependencies as well as register everything
 * for us to use within the application.
 *
 */

require_once __DIR__ . '/../vendor/autoload.php';

/*
 *-------------------------------------------------------------------------
 * Setup The Application
 *-------------------------------------------------------------------------
 *
 * Let's setup the application by bootstrapping it. This will instantiate
 * the main application as well as add any required classes to the
 * service container, add environment variables, add config
 * variables, and add all the application routes.
 *
 */

/** @var \Valkyrja\Contracts\Application $app */
$app = require __DIR__ . '/../bootstrap/app.php';

/*
 *-------------------------------------------------------------------------
 * Run The Application
 *-------------------------------------------------------------------------
 *
 * Now that the application has been bootstrapped and setup correctly
 * with all our requirements lets run it!
 *
 */

$app->kernel()->run();
