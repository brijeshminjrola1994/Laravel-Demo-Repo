<?php

namespace App\DataTables;

use App\DataTables\BaseDataTable;
use App\Models\InterviewCandidate;
use App\Models\Role;
use App\Models\User;
use App\Models\InterviewCandidateSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class InterviewCandidateDataTable extends BaseDataTable
{

    private $editInterviewCandidate;
    private $deleteInterviewCandidate;
    private $viewInterviewCandidate;

    public function __construct()
    {
        parent::__construct();
        $per = sidebar_user_perms();
        $this->viewInterviewCandidate = $per['view_interview_candidates'];
    }


    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {

        return datatables()
            ->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="select-table-row" id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $action = '';
                $action.= '<a href="'.$row->resume_url.'" class="" target="_blank"><i class="fa fa-download mr-2"></i></a>';
                $action.= '<div class="task_view">

                    <div class="dropdown">
                        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                            id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-options-vertical icons"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">';

                $action .= '<a href="' . route('interview-candidates.show', [$row->id]) . '" class="dropdown-item"><i class="fa fa-eye mr-2"></i>' . __('app.view') . '</a>';

                $action .= '<a class="dropdown-item openRightModal" href="' . route('interview-candidates.edit', [$row->id]) . '">
                                <i class="fa fa-edit mr-2"></i>
                                ' . trans('app.edit') . '
                            </a>';

                $action .= '</div>
                    </div>
                </div>';

                return $action;
            })
            ->addColumn('employee_name', function ($row) {
                return $row->name;
            })
            ->editColumn(
                'created_at',
                function ($row) {
                    return Carbon::parse($row->created_at)->format($this->company->date_format);
                }
            )
            ->editColumn(
                'status',
                function ($row) {
                    return InterviewCandidate::$status[$row->status];
                }
            )
            
            ->editColumn('experience', function ($row) {
                    return $row->experience;
                }
            )
            ->addIndexColumn()
            ->setRowId(function ($row) {
                return 'row-' . $row->id;
            })
            ->rawColumns(['name', 'experience','action','role', 'status', 'check'])
            ->removeColumn('roleId')
            ->removeColumn('roleName')
            ->removeColumn('current_role');
    }

    /**
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(InterviewCandidate $model)
    {
        $request = $this->request();

        $userRoles = '';

        if ($request->role != 'all' && $request->role != '') {
            $userRoles = Role::findOrFail($request->role);
        }

        $users = $model
            ->leftJoin('designations', 'interview_candidates.designation_id', '=', 'designations.id')
            ->select('interview_candidates.id', 'interview_candidates.experience','interview_candidates.name', 'interview_candidates.email', 'interview_candidates.created_at', 'interview_candidates.resume', 'interview_candidates.status', 'designations.name as designation_name');
        
        if ($this->request()->startDate !== null && $this->request()->startDate != 'null' && $this->request()->startDate != '') {
            $startDate = Carbon::createFromFormat($this->company->date_format, $request->startDate)->toDateString();
            
            $users = $users->having(DB::raw('DATE(interview_candidates.`created_at`)'), '>=', $startDate);
        }

        if ($this->request()->endDate !== null && $this->request()->endDate != 'null' && $this->request()->endDate != '') {
            $endDate = Carbon::createFromFormat($this->company->date_format, $request->endDate)->toDateString();

            $users = $users->having(DB::raw('DATE(interview_candidates.`created_at`)'), '<=', $endDate);
        }

        if ($request->status != 'all' && $request->status != '') {
            $users = $users->where('interview_candidates.status', $request->status);
        }
      
        if ($request->interviewstatus != 'all' && $request->interviewstatus != '') {
            $users = $users->where('interview_candidates.status', $request->interviewstatus);
        }
        if ($request->employee != 'all' && $request->employee != '') {
            $users = $users->where('interview_candidates.id', $request->employee);
        }

        if ($request->designation != 'all' && $request->designation != '') {
            $users = $users->where('interview_candidates.designation_id', $request->designation);
        }

        if ($request->department != 'all' && $request->department != '') {
            $users = $users->where('interview_candidates.department_id', $request->department);
        }

        if ((is_array($request->skill) && $request->skill[0] != 'all') && $request->skill != '' && $request->skill != null && $request->skill != 'null') {
            $users = $users->join('employee_skills', 'employee_skills.user_id', '=', 'interview_candidates.id')
                ->whereIn('employee_skills.skill_id', $request->skill);
        }

        if ($request->searchText != '') {
            $users = $users->where(function ($query) {
                $query->where('interview_candidates.name', 'like', '%' . request('searchText') . '%')
                    ->orWhere('interview_candidates.email', 'like', '%' . request('searchText') . '%')
                    ->orWhere('designations.name', 'like', '%' . request('searchText') . '%')
                    ->orWhere('interview_candidates.experience', 'like', '%' . request('searchText') . '%');
            });
        }
       
        $users = $users->where('move_candidate',0);
        
        return $users->groupBy('interview_candidates.id');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('employees-table')
            ->columns($this->getColumns())
            ->minifiedAjax()

            ->destroy(true)
            ->orderBy(2)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(false)
            ->processing(true)
            ->language(__('app.datatable'))
            ->parameters([
                'initComplete' => 'function () {
                    window.LaravelDataTables["employees-table"].buttons().container()
                     .appendTo( "#table-actions")
                 }',
                'fnDrawCallback' => 'function( oSettings ) {
                   //
                   $(".select-picker").selectpicker();
                 }',
            ])
            ->buttons(Button::make(['extend' => 'excel', 'text' => '<i class="fa fa-file-export"></i> ' . trans('app.exportExcel')]));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'check' => [
                'title' => '<input type="checkbox" name="select_all_table" id="select-all-table" onclick="selectAllTable(this)">',
                'exportable' => false,
                'orderable' => false,
                'searchable' => false
            ],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'visible' => true],
            __('app.id') => ['data' => 'id', 'name' => 'id', 'title' => __('app.id'), 'visible' => false,'exportable' => false],
            __('app.name') => ['data' => 'name', 'name' => 'name', 'exportable' => false, 'title' => __('app.name')],
            __('app.designation_name') => ['data' => 'designation_name', 'name' => 'designation_name', 'title' => __('modules.employees.designation')],
            __('app.employee') => ['data' => 'employee_name', 'name' => 'name', 'visible' => false, 'title' => __('app.candidate')],
            __('app.email') => ['data' => 'email', 'name' => 'email', 'title' => __('app.email'),'visible' => false],
            __('app.experience') => ['data' => 'experience', 'name' => 'experience', 'title' => __('modules.interview-candidate.experience')],
            __('app.status') => ['data' => 'status', 'name' => 'status', 'title' => __('app.status')],
            __('app.date') => ['data' => 'created_at', 'name' => 'date', 'title' => __('app.date')],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(150)
                ->addClass('text-right pr-20')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'candidates_' . date('YmdHis');
    }

    public function pdf()
    {
        set_time_limit(0);

        if ('snappy' == config('datatables-buttons.pdf_generator', 'snappy')) {
            return $this->snappyPdf();
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('datatables::print', ['data' => $this->getDataForPrint()]);

        return $pdf->download($this->getFilename() . '.pdf');
    }
}
