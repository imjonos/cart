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
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

class CartServiceProvider extends ServiceProvider
{
    /**
     * @param Router $router
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot(Router $router)
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'codersstudio');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'codersstudio');
        if(app()->runningUnitTests() && config('testing.packdev')) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations/test');
        }
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->registerEloquentFactoriesFrom(__DIR__ . '/../database/factories');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

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
            $paymentMethod = $this->getPaymentMethod();

            // check db for payment methods
            $this->checkDbForPaymentMethods();

            if($paymentMethod) {
                $paymentMethod = PaymentMethod::findOrFail($paymentMethod);
                if(count(config('cart.drivers')) && $paymentMethod) {
                    foreach (config('cart.drivers') as $name => $driver) {
                        if($paymentMethod->name === $name) {
                            return new $driver();
                        }
                    }
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

        // Migrations publish
        $this->publishes([
            __DIR__.'/../database/migrations' => base_path('database/migrations'),
        ], 'cart.migrations');

        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/codersstudio'),
        ], 'cart.views');

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

    /**
     * Check database for payment methods
     */
    protected function checkDbForPaymentMethods(): void
    {
        if(Schema::hasTable('payment_methods')) {
            $dbMethods = PaymentMethod::select('name')->get();
            $configMethods = array_keys(config('cart.drivers'));

            foreach ($configMethods as $configName) {
                $searchCondition = $dbMethods->search(function ($dbMethod) use ($configName) {
                    return $dbMethod->name === $configName;
                });

                if ($searchCondition === false) {
                    // not found in db
                    PaymentMethod::create([
                        'name' => $configName
                    ]);
                }
            }
        }
    }

    /**
     * Payment Method Initialization from request
     * @return int
     */
    protected function getPaymentMethod(): int
    {
        $request = request();
        $paymentMethod = 0;

        if ($request->route()->named(['checkout.success', 'checkout.fail'])) {
            $paymentMethod = ((int) $request->route('payment_method_id')) ?? 0;
        } elseif ($request->has('payment_method_id')) {
            $paymentMethod = ((int) $request->get('payment_method_id')) ?? 0;
        }

        return (int) $paymentMethod;
    }
}
