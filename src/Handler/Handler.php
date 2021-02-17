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

    /**
     * Remove part from full file name to project root.
     *
     * @param string $fullPath
     *
     * @return  string
     */
    public function removeRootPath(string $fullPath): string
    {
        if (strpos($fullPath, $this->rootPath) === 0) {
            $fullPath = substr($fullPath, strlen($this->rootPath));
        }

        return $fullPath;
    }

    /**
     * Get throwable message.
     *
     * @return  string
     */
    public function getMessage(): string
    {
        return $this->throwable->getMessage();
    }

    /**
     * Make frames for trace output
     *
     * @return  array
     */
    public function getFrames(): array
    {
        $trace = $this->throwable->getTrace();

        $trace = array_reverse($trace);

        foreach ($trace as $index => &$entry) {
            $file = $this->removeRootPath($entry['file']);
            $line = $entry['line'];
            $code = $this->getFileContent($entry['file'], $entry['line']);
            $entry = [
                'index' => $index,
                'file' => $file,
                'line' => $line,
                'code' => $code,
            ];
        }

        return array_reverse($trace);
    }

    public function getFileContent(string $file, int $line): ?array
    {
        if (!file_exists($file)) {
            return null;
        }

        $content = @file_get_contents($file);

        if ($content === false) {
            return null;
        }

        $content = explode("\n", $content);

        $start = max($line - 11, 0);
        $end = min($start + 20, count($content) - 1);

        $stack = [];

        for ($i = $start; $i <= $end; $i++) {
            $format = rtrim($content[$i], "\ \t\n\r\0\x0B");
            $format = htmlspecialchars($format);
            $format = str_replace([' ', "\t"], ['&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;'], $format);
            $stack[] = [
                'line' => $format,
                'number' => $i + 1,
                'error' => $i + 1 === $line,
            ];
        }

        return $stack;
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