<?php

namespace Modules\HRM\DataTables;

use Modules\HRM\Entities\Payroll;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PayrollsDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('employee_name', function ($data) {
                return $data->employee->name ?? 'N/A';
            })
            ->addColumn('base_pay', function ($data) {
                return format_currency($data->base_pay);
            })
            ->addColumn('overtime_pay', function ($data) {
                return format_currency($data->overtime_pay);
            })
            ->addColumn('bonus_pay', function ($data) {
                return format_currency($data->bonus_pay);
            })
            ->addColumn('deductions', function ($data) {
                return format_currency($data->deductions);
            })
            ->addColumn('total_paid', function ($data) {
                return format_currency($data->total_paid);
            })
            ->addColumn('action', function ($data) {
                return view('hrm::payrolls.partials.actions', compact('data'));
            });
    }

    public function query(Payroll $model) {
        return $model->newQuery()->with('employee');
    }

    public function html() {
        return $this->builder()
            ->setTableId('payrolls-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(6)
            ->buttons(
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel-fill"></i> Excel'),
                Button::make('print')
                    ->text('<i class="bi bi-printer-fill"></i> Print'),
                Button::make('reset')
                    ->text('<i class="bi bi-x-circle"></i> Reset'),
                Button::make('reload')
                    ->text('<i class="bi bi-arrow-repeat"></i> Reload')
            );
    }

    protected function getColumns() {
        return [
            Column::computed('employee_name')
                ->title('Employee')
                ->className('text-center align-middle'),

            Column::make('period_start')
                ->className('text-center align-middle'),

            Column::make('period_end')
                ->className('text-center align-middle'),

            Column::computed('base_pay')
                ->className('text-center align-middle'),

            Column::computed('total_paid')
                ->className('text-center align-middle'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),

            Column::make('created_at')
                ->visible(false)
        ];
    }

    protected function filename(): string {
        return 'Payrolls_' . date('YmdHis');
    }
}
