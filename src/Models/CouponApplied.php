<?php

namespace Tasmidur\Coupon\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CouponApplied extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coupon_id',
        'model',
        'model_type',
        'net_price',
        'applied_at',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('coupon.coupon_applied_table', 'coupon_applied');
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'applied_at'
    ];
}
