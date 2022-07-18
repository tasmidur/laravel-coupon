<?php

namespace Tasmidur\Coupon\Services;

use Illuminate\Support\Str;

class CouponCodeGeneratorService
{
    protected string $characters;
    protected string $couponFormat;
    protected string|null $prefix;
    protected string|null $suffix;
    protected string $separator = '-';
    protected array $couponCodes = [];


    public function __construct(string $characters = '123456789ABCDEFGHJKLMNPQRSTUVWXYZ', string $couponFormat = '*****-*****')
    {
        $this->characters = $characters;
        $this->couponFormat = $couponFormat;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return !empty($this->prefix) ? $this->prefix . $this->separator : '';
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return !empty($this->suffix) ? $this->separator . $this->suffix : '';
    }

    /**
     * @param string|null $prefix
     */
    public function setPrefix(?string $prefix): void
    {
        $this->prefix = $prefix;
    }

    /**
     * @param string|null $suffix
     */
    public function setSuffix(?string $suffix): void
    {
        $this->suffix = $suffix;
    }

    /**
     * @return string
     */
    public function generateUniqueCoupon(): string
    {
        $couponCode = $this->generateCoupon();

        while (in_array($couponCode, $this->couponCodes) === true) {
            $couponCode = $this->generateCoupon();
        }

        $this->couponCodes[] = $couponCode;
        return $couponCode;
    }

    /**
     * @param string $separator
     */
    public function setSeparator(string $separator): void
    {
        $this->separator = $separator;
    }

    /**
     * @return string
     */
    public function generateCoupon(): string
    {
        $length = substr_count($this->couponFormat, '*');
        $couponCode = $this->getPrefix();
        $characters = collect(str_split($this->characters));
        $format = $this->couponFormat;
        for ($i = 0; $i < $length; $i++) {
            $format = Str::replaceFirst('*', $characters->random(1)->first(), $format);
        }
        $couponCode .= $format;
        $couponCode .= $this->getSuffix();
        return $couponCode;
    }


}
