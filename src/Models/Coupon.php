<?php

namespace Tasmidur\Coupon\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{

    const COUPON_TYPE = [
        "FIXED_PRICE",
        "DISCOUNT_PRICE"
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coupon_code',
        'coupon_type',
        'price',
        'status',
        'expired_at'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('coupon.table', 'coupons');
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expired_at'
    ];

    protected $casts=[
        "expired_at"=>"date"
    ];

    /**
     * Check if code is expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expired_at && Carbon::now()->gte($this->expires_at);
    }

    /**
     * Check if code is not expired.
     *
     * @return bool
     */
    public function isNotExpired(): bool
    {
        return !$this->isExpired();
    }
}
