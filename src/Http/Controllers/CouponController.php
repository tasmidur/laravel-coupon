<?php

namespace Tasmidur\Coupon\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tasmidur\Coupon\Facades\Coupons;
use Tasmidur\Coupon\Models\Coupon;
use Tasmidur\Coupon\Services\ValidationServices;


class CouponController extends Controller
{
    public ValidationServices $validationServices;

    public function __construct(ValidationServices $validationServices)
    {
        $this->validationServices = $validationServices;
    }

    public function index()
    {
        return view('coupon::index');
    }

    public function getList(Request $request): JsonResponse
    {
        $pageSize = $request->get('page') ?? 10;
        $limit = $request->get('limit') ?? 10;

        $tableName = config('coupon.table');
        $couponBuilder = Coupon::select([
            "$tableName.id",
            "$tableName.coupon_code",
            "$tableName.coupon_type",
            "$tableName.price",
            "$tableName.status",
            "$tableName.expired_at",
            "$tableName.created_at",
            "$tableName.updated_at",
        ])->orderBy("$tableName.id", "DESC");

        $response = [
            "data" => $couponBuilder->get(),
            "statusCode" => ResponseAlias::HTTP_OK,
            "message" => "Successfully created the coupon"
        ];
        return Response::json($response, $response['statusCode']);
    }

    public function store(Request $request): JsonResponse
    {
        $response = [];
        try {
            $validation = $this->validationServices->couponCodeValidator($request);
            if (!$validation->failed()) {

                $validationData = $validation->validate();
                $couponType = $validationData['coupon_type'];
                $couponPrice = $validationData['coupon_price'];
                $expiredAt = $validationData['expired_at'] ? Carbon::create($validationData['expired_at']) : null;
                $coupons = Coupons::createCoupon($couponType, $couponPrice, $expiredAt);

                $response = [
                    "data" => $coupons,
                    "statusCode" => ResponseAlias::HTTP_CREATED,
                    "message" => "Successfully created the coupon"
                ];

            } else {
                $response = [
                    "errors" => $validation->errors(),
                    "statusCode" => ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
                    "message" => "Validation Errors"
                ];

            }

        } catch (\Exception $exception) {
            $response = [
                "statusCode" => $exception->getCode() !== 0 ? $exception->getCode() : ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                "message" => $exception->getMessage()
            ];
        }
        return Response::json($response, $response['statusCode']);
    }
}
