<?php
/**
 * CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace CodersStudio\Cart;

use CodersStudio\Cart\Http\Middleware\PaymentMethodMiddleware;
use CodersStudio\Cart\Interfaces\PaymentDriver;
use CodersStudio\Cart\Models\PaymentMethod;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'codersstudio');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'codersstudio');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->registerEloquentFactoriesFrom(__DIR__ . '/../database/factories');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        //Middleware
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', PaymentMethodMiddleware::class);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/cart.php', 'cart');

        // Register the service the package provides.
        $this->app->singleton('cart', function ($app) {
            return new Cart;
        });


        // register payment driver
        $this->app->singleton(PaymentDriver::class, function($app) {
            $request = request();
            if($request->has('payment_method_id')) {
                $pm = PaymentMethod::findOrFail($request->get('payment_method_id'));
                switch ($pm->name) {
                    case 'paypal':
                        $driver = config('cart.drivers.paypal');
                        return new $driver();
                        break;
                    case 'card':
                        $driver = config('cart.drivers.card');
                        return new $driver();
                        break;
                }
            }

        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['cart'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/cart.php' => config_path('cart.php'),
        ], 'cart.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/codersstudio'),
        ], 'cart.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/codersstudio'),
        ], 'cart.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/codersstudio'),
        ], 'cart.views');*/

        // Registering package commands.
        // $this->commands([]);
    }

    /**
     * Register factories.
     *
     * @param string $path
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function registerEloquentFactoriesFrom($path):void
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }
}
