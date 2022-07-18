<?php

namespace Tasmidur\Coupon;

use Illuminate\Support\ServiceProvider;
use Tasmidur\Coupon\Services\CouponCodeGeneratorService;

class LaravelCouponServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('coupon.php'),
            ], 'config');

            if (!class_exists('CreateCouponsTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_coupons_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_coupons_table.php'),
                ], 'migrations');
            }

        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'coupon');

        $this->app->singleton('coupons', function ($app) {
            $generator = new CouponCodeGeneratorService(config('coupon.characters'), config('coupon.coupon_format'));
            $generator->setPrefix(config('coupon.prefix'));
            $generator->setSuffix(config('coupon.suffix'));
            $generator->setSeparator(config('coupon.separator'));
            return new CouponCode($generator);
        });
    }
}
