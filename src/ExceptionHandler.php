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

use OpxCore\ExceptionHandler\Exceptions\ErrorException;
use OpxCore\App\Interfaces\AppInterface;
use OpxCore\ExceptionHandler\Formatter\FormatterInterface;
use OpxCore\ExceptionHandler\Formatter\Console\Formatter as ConsoleFormatter;
use OpxCore\ExceptionHandler\Formatter\Html\Formatter as HtmlFormatter;
use OpxCore\ExceptionHandler\Formatter\Json\Formatter as JsonFormatter;
use OpxCore\ExceptionHandler\Formatter\Soap\Formatter as SoapFormatter;
use OpxCore\ExceptionHandler\Formatter\Xml\Formatter as XmlFormatter;
use OpxCore\ExceptionHandler\Output\Console\Output as ConsoleOutput;
use OpxCore\ExceptionHandler\Output\Html\Output as HtmlOutput;
use OpxCore\ExceptionHandler\Output\Json\Output as JsonOutput;
use OpxCore\ExceptionHandler\Output\OutputInterface;
use OpxCore\ExceptionHandler\Output\Soap\Output as SoapOutput;
use OpxCore\ExceptionHandler\Output\Xml\Output as XmlOutput;
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
        // TODO processing

        $this->render($e);
    }

    /**
     * Render throwable for application selected output.
     *
     * @param Throwable $e
     */
    protected function render(Throwable $e): void
    {
        switch ($this->app->outputMode()) {
            case AppInterface::APP_OUTPUT_XML:
                $formatter = new ConsoleFormatter();
                $output = new ConsoleOutput();
                break;
            case AppInterface::APP_OUTPUT_SOAP:
                $formatter = new HtmlFormatter();
                $output = new HtmlOutput();
                break;
            case AppInterface::APP_OUTPUT_CONSOLE:
                $formatter = new JsonFormatter();
                $output = new JsonOutput();
                break;
            case AppInterface::APP_OUTPUT_JSON:
                $formatter = new SoapFormatter();
                $output = new SoapOutput();
                break;
            case AppInterface::APP_OUTPUT_HTTP:
            default:
                $formatter = new XmlFormatter();
                $output = new XmlOutput();
                break;
        }

        /** @var FormatterInterface $content */
        $content = $formatter->format($e);

        /** @var OutputInterface $output */
        $output->output($content);
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