<?php

namespace Mcarral\Errbit;

use Errbit\Errbit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ErrbitServiceProvider extends ServiceProvider
{

    /**
     * Catch all exceptions and send them to Errbit if enabled.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config.php' => config_path('errbit.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config.php', 'errbit'
        );

        $this->app->singleton(
            'errbit',
            function ($app)
            {
                $options = [
                    'api_key'           => $app['config']->get('errbit.api_key'),

                    'host'              => $app['config']->get('errbit.connection.host'),
                    'port'              => $app['config']->get('errbit.connection.port'),
                    'secure'            => $app['config']->get('errbit.connection.secure'),

                    'project_root'      => base_path(),
                    'environment_name'  => $app->environment(),
                    'url'               => $app['request']->url(),
                    'session_data'      => $app['session']->all(),

                    'user'              => $this->getUserOptions($app)
                ];

                return Errbit::instance()->configure($options);
            }
        );
        if ( ! $this->isEnabled())
        {
            return;
        }
        $handler = $this->app->make('Illuminate\Contracts\Debug\ExceptionHandler');
        $this->app->instance(
            'Illuminate\Contracts\Debug\ExceptionHandler',
            new ErrbitExceptionHandler($handler, $this->app)
        );
    }

    /**
     * User data send to errbit
     *
     * @param Application $app
     * @return array
     */
    protected function getUserOptions(Application $app)
    {
        if( ! $app['config']->get('errbit.user.enabled', true)) return [];

        $auth = $app['auth']->guard(null);
        if($auth->check()) {
            $attributes = $app['config']->get('errbit.user.attributes');
            $options = ( ! empty($attributes)) ?
                array_only($auth->user()->toArray(), $attributes) :
                $auth->user()->toArray();
        } else {
            $options = $app['config']->get('errbit.user.guest.data', []);
        }

        return $options;
    }

    /**
     * @return bool
     */
    protected function isEnabled()
    {
        $enabled = $this->app['config']->get('errbit.enabled', false);
        $ignored = $this->app['config']->get('errbit.ignore_environments', []);

        return $enabled && ! in_array($this->app->environment(), $ignored);
    }
}