<?php

namespace Modules\HRM\DataTables;

use Modules\HRM\Entities\Overtime;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OvertimesDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('employee_name', function ($data) {
                return $data->employee->name ?? 'N/A';
            })
            ->addColumn('rate_per_hour', function ($data) {
                return format_currency($data->rate_per_hour);
            })
            ->addColumn('amount', function ($data) {
                return format_currency($data->amount);
            })
            ->addColumn('action', function ($data) {
                return view('hrm::overtimes.partials.actions', compact('data'));
            });
    }

    public function query(Overtime $model) {
        return $model->newQuery()->with('employee');
    }

    public function html() {
        return $this->builder()
            ->setTableId('overtimes-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(5)
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

            Column::make('date')
                ->className('text-center align-middle'),

            Column::make('hours')
                ->className('text-center align-middle'),

            Column::computed('rate_per_hour')
                ->className('text-center align-middle'),

            Column::computed('amount')
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
        return 'Overtimes_' . date('YmdHis');
    }
}
