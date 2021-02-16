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

abstract class Handler implements HandlerInterface
{
    /** @var Throwable Throwable instance */
    protected Throwable $throwable;

    /** @var bool Is exception throwing in debug or production mode */
    protected bool $debugMode = false;

    /** @var string Path to application root for removing from file paths */
    protected string $rootPath = '';

    /**
     * Set throwable instance.
     *
     * @param Throwable $throwable
     *
     * @return  void
     */
    public function setThrowable(Throwable $throwable): void
    {
        $this->throwable = $throwable;
    }

    /**
     * Set debug mode for limiting output.
     *
     * @param bool $debug
     *
     * @return  void
     */
    public function setDebugMode(bool $debug): void
    {
        $this->debugMode = $debug;
    }

    /**
     * Set root path to remove it from filenames.
     *
     * @param string $path
     *
     * @return  void
     */
    public function setRootPath(string $path): void
    {
        $this->rootPath = $path;
    }

    public function getType(): string
    {
        return get_class($this->throwable);
    }

    public function getMessage(): string
    {
        return $this->throwable->getMessage();
    }

//    public function getCode(): int
//    {
//        $this->throwable->getCode();
//    }

//    public function getTrace()
//    {
//        $this->throwable->getTrace();
//    }
//
//    public function getLine()
//    {
//        $this->throwable->getLine();
//    }
//
//    public function getFile()
//    {
//        $this->throwable->getFile();
//    }

//
//    public function getPrevious()
//    {
//        $this->throwable->getPrevious();
//    }
    public function render(): void
    {
        echo "<h1>" . $this->getType() . "</h1>";
        echo "<h2>" . $this->getMessage() . "</h2>";
    }
}