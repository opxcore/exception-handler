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

use OpxCore\ExceptionHandler\Interfaces\ExceptionHandlerInterface;
use OpxCore\ExceptionHandler\Exceptions\ErrorException;
use OpxCore\App\Interfaces\AppInterface;
use OpxCore\ExceptionHandler\Handler\HandlerInterface;
use OpxCore\ExceptionHandler\Handler\ConsoleHandler;
use OpxCore\ExceptionHandler\Handler\HtmlHandler;
use OpxCore\ExceptionHandler\Handler\JsonHandler;
use OpxCore\ExceptionHandler\Handler\SoapHandler;
use OpxCore\ExceptionHandler\Handler\XmlHandler;
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
        // TODO processing

        $this->render($e);
    }

    /**
     * Render throwable for application selected output.
     *
     * @param Throwable $throwable
     */
    protected function render(Throwable $throwable): void
    {
        switch ($this->app->outputMode()) {
            case AppInterface::APP_OUTPUT_XML:
                $handler = new ConsoleHandler();
                break;
            case AppInterface::APP_OUTPUT_SOAP:
                $handler = new HtmlHandler();
                break;
            case AppInterface::APP_OUTPUT_CONSOLE:
                $handler = new JsonHandler();
                break;
            case AppInterface::APP_OUTPUT_JSON:
                $handler = new SoapHandler();
                break;
            case AppInterface::APP_OUTPUT_HTTP:
            default:
                $handler = new XmlHandler();
                break;
        }

        /** @var HandlerInterface $handler */
        $handler->setThrowable($throwable);
        $handler->setDebugMode($this->app->isDebugMode());
        $handler->setRootPath($this->app->path());

        $handler->render();
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