<?php
/*
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\ExceptionHandler\Handler;

use Throwable;

interface HandlerInterface
{
    /**
     * Set throwable instance.
     *
     * @param Throwable $throwable
     *
     * @return  void
     */
    public function setThrowable(Throwable $throwable): void;

    /**
     * Set debug mode for limiting output.
     *
     * @param bool $debug
     *
     * @return  void
     */
    public function setDebugMode(bool $debug): void;

    /**
     * Set root path to remove it from filenames.
     *
     * @param string $path
     *
     * @return  void
     */
    public function setRootPath(string $path): void;

    /**
     * Render output.
     *
     * @return  void
     */
    public function render(): void;
}