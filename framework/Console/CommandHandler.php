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

use Valkyrja\Contracts\Console\CommandHandler as CommandHandlerContract;

/**
 * Abstract Class CommandHandler
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
abstract class CommandHandler implements CommandHandlerContract
{
    /**
     * The input.
     *
     * @var \Valkyrja\Console\Input
     */
    protected $input;

    /**
     * CommandHandler constructor.
     *
     * @param \Valkyrja\Console\Input $input The input
     */
    public function __construct(Input $input)
    {
        $this->input = $input;
    }

    /**
     * Run the command.
     *
     * @return int
     */
    abstract public function run(): int;

    /**
     * Help docs for this command.
     *
     * @return int
     */
    public function help(): int
    {
        return 1;
    }

    /**
     * Get the valid arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [];
    }

    /**
     * Get the valid options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [];
    }
}