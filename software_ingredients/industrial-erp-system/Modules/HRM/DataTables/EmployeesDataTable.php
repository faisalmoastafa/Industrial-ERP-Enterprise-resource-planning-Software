<?php

namespace Modules\HRM\DataTables;

use Modules\HRM\Entities\Employee;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmployeesDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('base_salary', function ($data) {
                return format_currency($data->base_salary);
            })
            ->addColumn('status', function ($data) {
                return $data->status
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-danger">Inactive</span>';
            })
            ->addColumn('action', function ($data) {
                return view('hrm::employees.partials.actions', compact('data'));
            })
            ->rawColumns(['status', 'action']);
    }

    public function query(Employee $model) {
        return $model->newQuery();
    }

    public function html() {
        return $this->builder()
            ->setTableId('employees-table')
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
            Column::make('name')
                ->className('text-center align-middle'),

            Column::make('phone')
                ->className('text-center align-middle'),

            Column::make('designation')
                ->className('text-center align-middle'),

            Column::make('salary_type')
                ->className('text-center align-middle'),

            Column::computed('base_salary')
                ->className('text-center align-middle'),

            Column::computed('status')
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
        return 'Employees_' . date('YmdHis');
    }
}
