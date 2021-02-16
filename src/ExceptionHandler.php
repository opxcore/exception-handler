<?php
/*
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\ExceptionHandler;

use ErrorException;
use OpxCore\App\Interfaces\AppInterface;
use OpxCore\ExceptionHandler\Interfaces\ExceptionHandlerInterface;
use Throwable;

class ExceptionHandler implements ExceptionHandlerInterface
{
    /** @var AppInterface Application instance this handler running for */
    protected AppInterface $app;

    /**
     * ExceptionHandler constructor.
     *
     * @param AppInterface $app
     *
     * @return  void
     */
    public function __construct(AppInterface $app)
    {
        $this->app = $app;
    }

    /**
     * Register exceptions and error handlers
     *
     * @return  void
     */
    public function register(): void
    {
        set_exception_handler([$this, 'handle']);
        set_error_handler([$this, 'handleError']);
        register_shutdown_function([$this, 'handleShutdown']);
    }


    /**
     * Handle exceptions.
     *
     * @param Throwable $e
     *
     * @return  void
     */
    public function handle(Throwable $e): void
    {
        switch ($this->app->outputMode()) {
            case AppInterface::APP_OUTPUT_CONSOLE:
                $this->renderForConsole($e);
                break;
            case AppInterface::APP_OUTPUT_JSON:
                $this->renderForJson($e);
                break;
            case AppInterface::APP_OUTPUT_HTTP:
            default:
                $this->renderForHttp($e);
                break;
        }
    }

    /**
     * Render throwable for console output.
     *
     * @param Throwable $e
     */
    protected function renderForConsole(Throwable $e): void
    {
    }

    /**
     * Render throwable for json output.
     *
     * @param Throwable $e
     */
    protected function renderForJson(Throwable $e): void
    {
    }

    /**
     * Render throwable for http output.
     *
     * @param Throwable $e
     */
    protected function renderForHttp(Throwable $e): void
    {
    }

    /**
     * Handle errors. Convert error to ErrorException.
     *
     * @param int $level
     * @param string $message
     * @param string $file
     * @param int $line
     *
     * @return  void
     */
    public function handleError(int $level, string $message, $file = '', $line = 0): void
    {
        if (error_reporting() & $level) {
            $this->handle(new ErrorException($message, 0, $level, $file, $line));
            exit();
        }
    }

    /**
     * Handle the shutdown event. If there is unhandled error, catch it.
     *
     * @return  void
     */
    public function handleShutdown(): void
    {
        $error = error_get_last();

        if (!is_null($error) && $this->isFatal($error['type'])) {

            $this->handle(new ErrorException($error['message'], $error['type'], $error['file'], $error['line']));
        }
    }

    /**
     * Weather the error is fatal.
     *
     * @param int $type
     *
     * @return  bool
     */
    protected function isFatal(int $type): bool
    {
        return in_array($type, [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING], true);
    }
}