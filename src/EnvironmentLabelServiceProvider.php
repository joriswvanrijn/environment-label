<?php

namespace joriswvanrijn\EnvironmentLabel;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Http\Events\RequestHandled;

class EnvironmentLabelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     * @return void
     */
    public function boot(EnvironmentLabel $environmentLabel)
    {
        if (App::environment('production') || !config('environment-label.enabled')) {
            return;
        }

        $this->registerResponseHandler($environmentLabel);

        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'environment-label'
        );
    }

    /**
     * Listen to the RequestHandled event to prepare the Response.
     * @param \joriswvanrijn\EnvironmentLabel\EnvironmentLabel $environmentLabel
     * @return void
     */
    private function registerResponseHandler(EnvironmentLabel $environmentLabel)
    {
        Event::listen(RequestHandled::class, function (RequestHandled $event) use ($environmentLabel) {
            try {
                $environmentLabel->modifyResponse($event->request, $event->response);
            } catch (\Throwable $e) {
                logger(
                    'Cannot load environment label: ' . $e->getMessage(),
                    ['exception' => $e]
                );
            }
        });
    }

    /**
     * Register any services.
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/environment-label.php',
            'environment-label'
        );

        $this->app->singleton(EnvironmentLabel::class);
    }
}
