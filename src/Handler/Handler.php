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

use OpxCore\ExceptionHandler\Exceptions\ErrorException;
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
        $type = get_class($this->throwable);

        // If class of throwable is OpxCore\ExceptionHandler\Exceptions\ErrorException
        // Error was caught. Change type to Error.
        if ($type === ErrorException::class) {
            $type = 'Error';
        }

        return $type;
    }

    public function getMessage(): string
    {
        return $this->throwable->getMessage();
    }

    public function getFrames(): array
    {
        $trace = $this->throwable->getTrace();

        foreach ($trace as $index => &$entry) {
            $file = $entry['file'];
            $line = $entry['line'];
            $code = [];
            $entry = [
                'file' => $file,
                'line' => $line,
                'code' => $code,
            ];
        }

        return $trace;
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

}