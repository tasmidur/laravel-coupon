<?php

namespace Tasmidur\Coupon\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tasmidur\Coupon\Models\Coupon;

class ValidationServices
{
    public function couponCodeValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        $rules = [
            'coupon_type' => [
                "required",
                "string",
                Rule::in(Coupon::COUPON_TYPE)
            ],
            "coupon_price" => [
                "required",
                "numeric",
                "gt:-1",
                Rule::when($request->get('coupon_type') == Coupon::COUPON_TYPE[1], [
                    Rule::in([0, 100])
                ])
            ],
            "expired_at" => [
                "nullable",
                "date"
            ]
        ];

        return Validator::make($request->all(), $rules);
    }
}
