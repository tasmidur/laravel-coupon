<?php

namespace Tasmidur\Coupon\Facades;

use Carbon\Carbon;
use Illuminate\Support\Facades\Facade;
/**
 * Class Coupons
 * @method static array createCoupon(string $couponType, float $price,Carbon $expiredAt = null, int $totalAmount = 1)
 *
 * @see \Tasmidur\Coupon\CouponCode
 */
class Coupons extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'coupons';
    }
}
