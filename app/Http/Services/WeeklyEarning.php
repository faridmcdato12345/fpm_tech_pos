<?php

namespace App\Http\Services;

use Carbon\Carbon;
use App\Models\Sales;
use Illuminate\Support\Facades\DB;

class WeeklyEarning
{
    public function getEarnings()
    {
        $now = Carbon::now('Asia/Manila');
        // Get the current week start and end dates
        $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $now->copy()->endOfWeek(Carbon::SUNDAY);
        // Get the previous week start and end dates
        $startOfLastWeek = $now->copy()->subWeek()->startOfWeek(Carbon::MONDAY);
        $endOfLastWeek = $now->copy()->subWeek()->endOfWeek(Carbon::SUNDAY);
        // Calculate earnings for the current week
        $currentWeekEarnings = Sales::join('product_stocks', 'sales.product_id', '=', 'product_stocks.id')
                                    ->whereBetween('sales.created_at', [$startOfWeek, $endOfWeek])
                                    ->sum(DB::raw('sales.quantity * product_stocks.selling_price'));

        // Calculate earnings for the previous week
        $lastWeekEarnings = Sales::join('product_stocks', 'sales.product_id', '=', 'product_stocks.id')
                                ->whereBetween('sales.created_at', [$startOfLastWeek, $endOfLastWeek])
                                ->sum(DB::raw('sales.quantity * product_stocks.selling_price'));

        // Calculate the percentage change
        $percentageChange = $lastWeekEarnings ? (($currentWeekEarnings - $lastWeekEarnings) / $lastWeekEarnings) * 100 : 100;

        return [
            'current_week_earnings' => $currentWeekEarnings,
            'last_week_earnings' => $lastWeekEarnings,
            'percentage_change' => $percentageChange,
        ];

    }
}
