<?php


use Modules\Base\Enum\DiscountTypeEnum;
use Modules\Base\Enum\TaxTypeEnum;


function calculatePriceAfterTax($price, $tax, $type): float|int
{
    if (filled($tax)) {
        if ($type == TaxTypeEnum::PERCENTAGE->value) {
            return $price + ($price * ($tax / 100));
        }
        return $price + $tax;
    }
    return $price;
}


function calculatePriceBeforeTax($price, $tax): float|int
{
    return $price - ($price * ($tax / 100));
}

function CalculateTax($taxPercentage, $price): float|int
{
    return $price * ($taxPercentage / 100);
}

function calculatePriceBeforeTaxFromPriceAfterTax($priceAfterTax, $taxPercentage): float|int
{
    return $priceAfterTax / (1 + ($taxPercentage / 100));
}

function calculatePriceAfterDiscount($price, $discount, $type): float|int
{
    if (filled($discount)) {
        if ($type == DiscountTypeEnum::PERCENTAGE->value) {
            return $price - ($price * ($discount / 100));
        }
        return $price - $discount;
    }
    return $price;

}
