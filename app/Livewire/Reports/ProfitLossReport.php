<?php

namespace App\Livewire\Reports;

use App\Services\FinancialMetricsService;
use App\Services\PartyLedgerService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Modules\Income\Entities\Income;
use Modules\Purchase\Entities\PurchasePayment;
use Modules\PurchasesReturn\Entities\PurchaseReturnPayment;
use Modules\Sale\Entities\SalePayment;
use Modules\SalesReturn\Entities\SaleReturnPayment;

class ProfitLossReport extends Component
{

    public $start_date;
    public $end_date;
    public $total_sales, $sales_amount;
    public $total_purchases, $purchases_amount;
    public $total_sale_returns, $sale_returns_amount;
    public $total_purchase_returns, $purchase_returns_amount;
    public $net_sales_amount, $net_purchases_amount;
    public $expenses_amount;
    public $other_income_amount;
    public $payroll_expenses_amount;
    public $profit_amount;
    public $payments_received_amount;
    public $payments_sent_amount;
    public $payments_net_amount;
    public $net_payable_amount;
    public $net_receivable_amount;
    public $conversion_cost_amount;

    protected $rules = [
        'start_date' => 'nullable|date|required_with:end_date|before_or_equal:end_date',
        'end_date'   => 'nullable|date|required_with:start_date|after_or_equal:start_date'
    ];

    public function mount() {
        $this->start_date = '';
        $this->end_date = '';
        $this->total_sales = 0;
        $this->sales_amount = 0;
        $this->total_sale_returns = 0;
        $this->sale_returns_amount = 0;
        $this->total_purchases = 0;
        $this->purchases_amount = 0;
        $this->total_purchase_returns = 0;
        $this->purchase_returns_amount = 0;
        $this->net_sales_amount = 0;
        $this->net_purchases_amount = 0;
        $this->expenses_amount = 0;
        $this->other_income_amount = 0;
        $this->payroll_expenses_amount = 0;
        $this->profit_amount = 0;
        $this->payments_received_amount = 0;
        $this->payments_sent_amount = 0;
        $this->payments_net_amount = 0;
        $this->net_payable_amount = 0;
        $this->net_receivable_amount = 0;
        $this->conversion_cost_amount = 0;
    }

    public function render() {
        $this->setValues();

        return view('livewire.reports.profit-loss-report');
    }

    public function generateReport() {
        $this->validate();
    }

    public function resetFilters() {
        $this->start_date = '';
        $this->end_date = '';
        $this->resetValidation();
        $this->setValues();
    }

    public function setValues() {
        $metrics = app(FinancialMetricsService::class)->summary(
            $this->start_date ?: null,
            $this->end_date ?: null
        );

        $this->total_sales = $metrics['total_sales'];
        $this->sales_amount = $metrics['sales_amount'];
        $this->total_sale_returns = $metrics['total_sale_returns'];
        $this->sale_returns_amount = $metrics['sale_returns_amount'];
        $this->net_sales_amount = $metrics['net_sales_amount'];
        $this->total_purchases = $metrics['total_purchases'];
        $this->purchases_amount = $metrics['purchases_amount'];
        $this->total_purchase_returns = $metrics['total_purchase_returns'];
        $this->purchase_returns_amount = $metrics['purchase_returns_amount'];
        $this->net_purchases_amount = $metrics['net_purchases_amount'];
        $this->expenses_amount = $metrics['expenses_amount'];
        $this->other_income_amount = $metrics['other_income_amount'];
        $this->payroll_expenses_amount = $metrics['payroll_expenses_amount'];
        $this->profit_amount = $metrics['net_result_amount'];

        $this->payments_received_amount = $this->calculatePaymentsReceived();

        $this->payments_sent_amount = $this->calculatePaymentsSent();

        $this->payments_net_amount = $this->payments_received_amount - $this->payments_sent_amount;

        $ledger = app(PartyLedgerService::class);
        $balances = $ledger->balances();
        $this->net_payable_amount = $balances['net_payable'];
        $this->net_receivable_amount = $balances['net_receivable'];
        $this->conversion_cost_amount = $ledger->conversionCost($this->start_date ?: null, $this->end_date ?: null);
    }

    public function calculatePaymentsReceived() {
        $sale_payments = $this->moneyAmount(
            $this->dateRange(SalePayment::where('payment_method', '!=', 'Prepay Balance'))->sum('amount')
        );

        $purchase_return_payments = $this->moneyAmount($this->dateRange(PurchaseReturnPayment::query())->sum('amount'));
        $other_income = Schema::hasTable('incomes')
            ? $this->moneyAmount($this->dateRange(Income::query())->sum('amount'))
            : 0;

        return $sale_payments + $purchase_return_payments + $other_income;
    }

    public function calculatePaymentsSent() {
        $purchase_payments = $this->moneyAmount(
            $this->dateRange(PurchasePayment::where('payment_method', '!=', 'Prepay Balance'))->sum('amount')
        );

        $sale_return_payments = $this->moneyAmount($this->dateRange(SaleReturnPayment::query())->sum('amount'));

        return $purchase_payments + $sale_return_payments + $this->expenses_amount;
    }

    private function dateRange(Builder $query): Builder {
        return $query
            ->when($this->start_date, fn (Builder $query) => $query->whereDate('date', '>=', $this->start_date))
            ->when($this->end_date, fn (Builder $query) => $query->whereDate('date', '<=', $this->end_date));
    }

    private function moneyAmount(int|float $amount): float {
        return $amount / 100;
    }
}
