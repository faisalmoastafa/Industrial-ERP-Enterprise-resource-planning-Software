<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Modules\Expense\Entities\Expense;
use Modules\HRM\Entities\Payroll;
use Modules\Income\Entities\Income;
use Modules\Purchase\Entities\Purchase;
use Modules\PurchasesReturn\Entities\PurchaseReturn;
use Modules\Sale\Entities\Sale;
use Modules\SalesReturn\Entities\SaleReturn;

class FinancialMetricsService
{
    /**
     * Builds the shared business result numbers used by dashboard and reports.
     * Database money values are stored as cents, so sums are divided once here.
     */
    public function summary(?string $startDate = null, ?string $endDate = null): array
    {
        $sales = $this->completedAmount(Sale::query(), $startDate, $endDate);
        $saleReturns = $this->completedAmount(SaleReturn::query(), $startDate, $endDate);
        $purchases = $this->completedAmount(Purchase::query(), $startDate, $endDate);
        $purchaseReturns = $this->completedAmount(PurchaseReturn::query(), $startDate, $endDate);
        $expenses = $this->moneyAmount($this->dateRange(Expense::query(), $startDate, $endDate)->sum('amount'));
        $otherIncome = Schema::hasTable('incomes')
            ? $this->moneyAmount($this->dateRange(Income::query(), $startDate, $endDate)->sum('amount'))
            : 0;
        $payrollExpenses = Schema::hasTable('payrolls')
            ? $this->moneyAmount($this->periodRange(Payroll::query(), $startDate, $endDate)->sum('total_paid'))
            : 0;
        $totalExpenses = $expenses + $payrollExpenses;

        // FIX 3: Include production conversion cost in net result
        $conversionCost = $this->conversionCostAmount($startDate, $endDate);

        $netSales = $sales - $saleReturns;
        $netPurchases = $purchases - $purchaseReturns;
        $netResult = $netSales + $otherIncome - $netPurchases - $totalExpenses - $conversionCost;

        return [
            'total_sales' => $this->completedCount(Sale::query(), $startDate, $endDate),
            'sales_amount' => $sales,
            'total_sale_returns' => $this->completedCount(SaleReturn::query(), $startDate, $endDate),
            'sale_returns_amount' => $saleReturns,
            'net_sales_amount' => $netSales,

            'total_purchases' => $this->completedCount(Purchase::query(), $startDate, $endDate),
            'purchases_amount' => $purchases,
            'total_purchase_returns' => $this->completedCount(PurchaseReturn::query(), $startDate, $endDate),
            'purchase_returns_amount' => $purchaseReturns,
            'net_purchases_amount' => $netPurchases,

            'other_income_amount' => $otherIncome,
            'direct_expenses_amount' => $expenses,
            'payroll_expenses_amount' => $payrollExpenses,
            'expenses_amount' => $totalExpenses,
            'conversion_cost_amount' => $conversionCost,
            'net_result_amount' => $netResult,
        ];
    }

    private function completedAmount(Builder $query, ?string $startDate, ?string $endDate): float
    {
        return $this->moneyAmount(
            $this->dateRange($query->where('status', 'Completed'), $startDate, $endDate)->sum('total_amount')
        );
    }

    private function completedCount(Builder $query, ?string $startDate, ?string $endDate): int
    {
        return $this->dateRange($query->where('status', 'Completed'), $startDate, $endDate)->count();
    }

    private function dateRange(Builder $query, ?string $startDate, ?string $endDate): Builder
    {
        return $query
            ->when($startDate, fn (Builder $query) => $query->whereDate('date', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('date', '<=', $endDate));
    }

    private function periodRange(Builder $query, ?string $startDate, ?string $endDate): Builder
    {
        return $query
            ->when($startDate, fn (Builder $query) => $query->whereDate('period_end', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('period_end', '<=', $endDate));
    }

    private function moneyAmount(int|float $amount): float
    {
        return $amount / 100;
    }

    /**
     * FIX 3: Sum production batch conversion expenses for the given date range.
     * Reuses the production_batch_expenses table directly to stay consistent
     * with how PartyLedgerService::conversionCost() reads the same data.
     */
    private function conversionCostAmount(?string $startDate, ?string $endDate): float
    {
        if (!Schema::hasTable('production_batch_expenses')) {
            return 0;
        }

        $query = \Modules\Manufacturing\Entities\ProductionBatchExpense::query();

        if ($startDate && Schema::hasColumn('production_batch_expenses', 'date')) {
            $query->whereDate('date', '>=', $startDate);
        }

        if ($endDate && Schema::hasColumn('production_batch_expenses', 'date')) {
            $query->whereDate('date', '<=', $endDate);
        }

        return $this->moneyAmount((float) $query->sum('amount'));
    }
}
