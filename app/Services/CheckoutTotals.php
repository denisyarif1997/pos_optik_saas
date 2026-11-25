<?php

namespace App\Services;

class CheckoutTotals
{
    /**
     * Aggregate calculation for callers that only know the combined item discount.
     * Delegates to the line aware calculation so both share one implementation.
     *
     * @return array{subtotal: float, item_discount: float, discount_percent: float, discount_amount: float, transaction_discount: float, discount_total: float, taxable_total: float, ppn_rate: float, ppn_amount: float, total: float}
     */
    public static function calculate(float $subtotal, float $itemDiscount, float $discountPercent, float $ppnRate, float $discountAmount = 0.0): array
    {
        $totals = self::calculateForLines([
            ['gross' => $subtotal, 'discount' => $itemDiscount, 'ppn_rate' => $ppnRate],
        ], $discountPercent, $discountAmount);

        unset($totals['lines']);

        return $totals;
    }

    /**
     * Line aware calculation: every item carries its own discount and optional PPN rate.
     *
     * The transaction level discount is spread across the lines proportionally to each
     * line's amount after its item discount, so each line's taxable base (and therefore
     * its PPN) stays correct when only some items are taxed.
     *
     * @param  array<int|string, array{gross: float|int|string, discount?: float|int|string|null, ppn_rate?: float|int|string|null}>  $lines
     * @return array{subtotal: float, item_discount: float, discount_percent: float, discount_amount: float, transaction_discount: float, discount_total: float, taxable_total: float, ppn_rate: float, ppn_amount: float, total: float, lines: array<int|string, array{gross: float, discount: float, net: float, transaction_discount: float, taxable: float, ppn_rate: float, ppn_amount: float, subtotal: float}>}
     */
    public static function calculateForLines(array $lines, float $discountPercent = 0.0, float $discountAmount = 0.0): array
    {
        $discountPercent = min(100.0, max(0.0, $discountPercent));
        $discountAmount = max(0.0, $discountAmount);
        $normalized = [];
        $subtotal = 0.0;
        $itemDiscount = 0.0;

        foreach ($lines as $index => $line) {
            $gross = round(max(0.0, (float) ($line['gross'] ?? 0)), 2);
            $discount = round(min($gross, max(0.0, (float) ($line['discount'] ?? 0))), 2);

            $subtotal += $gross;
            $itemDiscount += $discount;

            $normalized[$index] = [
                'gross' => $gross,
                'discount' => $discount,
                'net' => round($gross - $discount, 2),
                'ppn_rate' => round(max(0.0, (float) ($line['ppn_rate'] ?? 0)), 2),
            ];
        }

        $subtotal = round($subtotal, 2);
        $itemDiscount = round($itemDiscount, 2);
        $discountableSubtotal = round(max(0.0, $subtotal - $itemDiscount), 2);
        $discountAmount = round(min($discountableSubtotal, $discountAmount), 2);
        $transactionDiscount = round(min(
            $discountableSubtotal,
            round($discountableSubtotal * ($discountPercent / 100), 2) + $discountAmount,
        ), 2);

        $result = [];
        $remainingDiscount = $transactionDiscount;
        $taxableTotal = 0.0;
        $ppnAmount = 0.0;
        $ppnRate = 0.0;
        $lastIndex = array_key_last($normalized);

        foreach ($normalized as $index => $line) {
            $allocated = $index === $lastIndex
                ? min($line['net'], round(max(0.0, $remainingDiscount), 2))
                : ($discountableSubtotal > 0
                    ? min($line['net'], round($transactionDiscount * ($line['net'] / $discountableSubtotal), 2))
                    : 0.0);

            $remainingDiscount = round($remainingDiscount - $allocated, 2);

            $taxable = round(max(0.0, $line['net'] - $allocated), 2);
            $linePpn = round($taxable * ($line['ppn_rate'] / 100), 2);

            $taxableTotal += $taxable;
            $ppnAmount += $linePpn;
            $ppnRate = max($ppnRate, $line['ppn_rate']);

            $result[$index] = [
                'gross' => $line['gross'],
                'discount' => $line['discount'],
                'net' => $line['net'],
                'transaction_discount' => $allocated,
                'taxable' => $taxable,
                'ppn_rate' => $line['ppn_rate'],
                'ppn_amount' => $linePpn,
                'subtotal' => $line['net'],
            ];
        }

        $taxableTotal = round($taxableTotal, 2);
        $ppnAmount = round($ppnAmount, 2);

        return [
            'subtotal' => $subtotal,
            'item_discount' => $itemDiscount,
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'transaction_discount' => $transactionDiscount,
            'discount_total' => round(min($subtotal, $itemDiscount + $transactionDiscount), 2),
            'taxable_total' => $taxableTotal,
            'ppn_rate' => $ppnRate,
            'ppn_amount' => $ppnAmount,
            'total' => round($taxableTotal + $ppnAmount, 2),
            'lines' => $result,
        ];
    }
}
