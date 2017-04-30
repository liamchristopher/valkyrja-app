<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console;

use Throwable;

use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Console\Console;
use Valkyrja\Contracts\Console\Input;
use Valkyrja\Contracts\Console\Kernel as KernelContract;

/**
 * Class ConsoleKernel
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
class Kernel implements KernelContract
{
    /**
     * The application.
     *
     * @var \Valkyrja\Contracts\Application
     */
    protected $app;

    /**
     * The console.
     *
     * @var \Valkyrja\Contracts\Console\Console
     */
    protected $console;

    /**
     * Kernel constructor.
     *
     * @param \Valkyrja\Contracts\Application     $application The application
     * @param \Valkyrja\Contracts\Console\Console $console     The console
     */
    public function __construct(Application $application, Console $console)
    {
        $this->app = $application;
        $this->console = $console;
    }

    /**
     * Handle a console input.
     *
     * @param \Valkyrja\Contracts\Console\Input $input The input
     *
     * @return int
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     */
    public function handle(Input $input): int
    {
        $exitCode = 1;

        try {
            $exitCode = $this->console->dispatch($input);
        }
        catch (Throwable $exception) {
            // Show the exception
        }

        $this->app->events()->trigger('Console.Kernel.handled', [$input, $exitCode]);

        return $exitCode;
    }

    /**
     * Terminate the kernel request.
     *
     * @param \Valkyrja\Contracts\Console\Input $input    The input
     * @param int                               $exitCode The response
     *
     * @return void
     */
    public function terminate(Input $input, int $exitCode): void
    {
        $this->app->events()->trigger('Console.Kernel.terminate', [$input, $exitCode]);
    }

    /**
     * Run the kernel.
     *
     * @param \Valkyrja\Contracts\Console\Input $input The input
     *
     * @return void
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     */
    public function run(Input $input = null): void
    {
        // If no request was passed get the bootstrapped definition
        if (null === $input) {
            $input = $this->app->container()->get(Input::class);
        }

        // Handle the request and get the response
        $exitCode = $this->handle($input);

        // Terminate the application
        $this->terminate($input, $exitCode);

        exit($exitCode);
    }
}