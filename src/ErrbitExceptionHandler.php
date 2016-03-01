<?php
namespace Mcarral\Errbit;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Foundation\Application;

class ErrbitExceptionHandler implements ExceptionHandler
{
    private $handler;
    private $app;

    public function __construct(ExceptionHandler $handler, Application $app)
    {
        $this->handler = $handler;
        $this->app = $app;
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if($this->handler->shouldReport($e)) {
            try {
                $this->app['errbit']->notify($e);
            } catch (Exception $eeb) { $this->app['log']->error('Errbit: ' . $eeb->getMessage()); }
        }

        $this->handler->report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        return $this->handler->render($request, $e);
    }

    /**
     * Render an exception to the console.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @param  \Exception $e
     * @return void
     */
    public function renderForConsole($output, Exception $e)
    {
        $this->handler->renderForConsole($output, $e);
    }
}