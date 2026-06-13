<?php

namespace Modules\HRM\DataTables;

use Modules\HRM\Entities\Attendance;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AttendancesDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('employee_name', function ($data) {
                return $data->employee->name ?? 'N/A';
            })
            ->addColumn('status', function ($data) {
                $map = [
                    'present' => '<span class="badge badge-success">Present</span>',
                    'absent' => '<span class="badge badge-danger">Absent</span>',
                    'leave' => '<span class="badge badge-warning">Leave</span>',
                ];
                return $map[$data->status] ?? $data->status;
            })
            ->addColumn('action', function ($data) {
                return view('hrm::attendances.partials.actions', compact('data'));
            })
            ->rawColumns(['status', 'action']);
    }

    public function query(Attendance $model) {
        return $model->newQuery()->with('employee');
    }

    public function html() {
        return $this->builder()
            ->setTableId('attendances-table')
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

            Column::computed('status')
                ->className('text-center align-middle'),

            Column::make('note')
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
        return 'Attendances_' . date('YmdHis');
    }
}
