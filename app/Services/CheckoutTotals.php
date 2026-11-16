<?php

namespace App\Services;

class CheckoutTotals
{
    /**
     * @return array{subtotal: float, item_discount: float, transaction_discount: float, discount_total: float, taxable_total: float, ppn_rate: float, ppn_amount: float, total: float}
     */
    public static function calculate(float $subtotal, float $itemDiscount, float $discountPercent, float $ppnRate): array
    {
        $subtotal = round(max(0, $subtotal), 2);
        $itemDiscount = round(min($subtotal, max(0, $itemDiscount)), 2);
        $discountableSubtotal = max(0, $subtotal - $itemDiscount);
        $transactionDiscount = round($discountableSubtotal * ($discountPercent / 100), 2);
        $discountTotal = min($subtotal, $itemDiscount + $transactionDiscount);
        $taxableTotal = max(0, $subtotal - $discountTotal);
        $ppnAmount = round($taxableTotal * ($ppnRate / 100), 2);

        return [
            'subtotal' => $subtotal,
            'item_discount' => $itemDiscount,
            'transaction_discount' => $transactionDiscount,
            'discount_total' => $discountTotal,
            'taxable_total' => $taxableTotal,
            'ppn_rate' => $ppnRate,
            'ppn_amount' => $ppnAmount,
            'total' => $taxableTotal + $ppnAmount,
        ];
    }
}
