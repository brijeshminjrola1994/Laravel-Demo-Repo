<?php

namespace App\DataTables;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\FollowUp;
use App\Models\LeadFollowUp;
use App\Models\ClientFollowUp;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class FollowUpDataTable extends BaseDataTable
{

    private $editfollowUpPermission;
    private $deletefollowUpPermission;
    private $viewfollowUpPermission;

    public function __construct()
    {
        parent::__construct();
        $this->editfollowUpPermission = user()->permission('edit_follow_up');
        $this->viewfollowUpPermission = user()->permission('view_follow_up');
        $this->deletefollowUpPermission = user()->permission('delete_follow_up');
    }
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $label = $this->label;

        return datatables()
            ->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="select-table-row" id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
            })
            ->addColumn('type', function($row){
                if($row->lead_id){
                    return '<strong class="f-12">' . __('app.lead').'</strong>';
                } else {
                    return '<strong class="f-12">' . __('app.client').'</strong>';
                }
            })
            
            ->editcolumn('remark', function($row) {
                return ucfirst(str_limit($row->remark, 17, '...'));
            })
            ->editColumn('label', function($row) {
                if($row->label == 'interested') {
                    return ' <i class="fa fa-circle mr-1 text-success f-10"></i>' . __('app.interested');
                } else if($row->label == 'highlyinterested') {
                    return ' <i class="fa fa-circle mr-1 text-primary f-10"></i>' . __('app.highlyinterested');
                } else if($row->label == 'notinterested') {
                    return ' <i class="fa fa-circle mr-1 text-warning f-10"></i>' . __('app.notinterested');
                } else {
                    return ' <i class="fa fa-circle mr-1 text-danger f-10"></i>' . __('app.dropped');
                }
            })
            ->editColumn('created_at', function ($row) {
                return '<i class="fa fa-calendar"></i>&nbsp;'.date('d-M-Y',strtotime($row->created_at)) . '&nbsp<i class="fa fa-clock"></i>&nbsp;' .date('h:i a',strtotime($row->created_at));
            })
            ->editColumn('next_follow_up_date', function($row) {
                return '<i class="fa fa-calendar"></i>&nbsp;'. date('d-M-Y',strtotime($row->next_follow_up_date))  . '&nbsp<i class="fa fa-clock"></i>&nbsp;' .date('h:i a',strtotime($row->next_follow_up_date));
            })
            ->editColumn('source', function ($row) {
                if ($row->source) 
                {
                    return $row->source;
                } 
                return  '--';
            })
            ->editColumn('title', function($row) {
                if($row->lead_id) {
                    $label = '<label class="badge badge-secondary">' . __('app.lead') . '</label>';
                } else {
                    $label = '<label class="badge badge-secondary">' . __('app.client') . '</label>';
                }
                return '<p class="mb-0 f-12 text-dark-grey">' .$row->title. '</p><p class="mb-0">' . $label . '</p>';
            })
            ->addIndexColumn()
            ->setRowId(function ($row) {
                return  $row->id;
            })
            ->setRowAttr([
                'data-action-url' => function($row) {
                    return $row->lead_id ?  route('follow-ups.showLead', [$row->id]) : route('follow-ups.showClient', [$row->id]);

                }
            ])
            ->rawColumns(['title', 'check', 'created_at','type', 'id', 'remark', 'label', 'source', 'next_follow_up_date']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\FollowUp $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
 
    public function query()
    {
       
        $currentDate = Carbon::now(company()->timezone)->format('Y-m-d');
        $request = $this->request();
        //start date
        $startDate = Carbon::now()->format('Y-m-d');
        if ($this->request()->startDate !== null && $this->request()->startDate != 'null' && $this->request()->startDate != '') {
            $startDate = Carbon::createFromFormat($this->company->date_format, $this->request()->startDate)->toDateString();
        }
        
        //end date
        $endDate = Carbon::now()->format('Y-m-d');
        if ($this->request()->endDate !== null && $this->request()->endDate != 'null' && $this->request()->endDate != '') {
            $endDate = Carbon::createFromFormat($this->company->date_format, $this->request()->endDate)->toDateString();
        }

        if(($this->request()->startDate !== null && $this->request()->startDate != 'null' && $this->request()->startDate != '') 
            || $request->searchText != '' 
            || $this->request()->label != 'all' && $this->request()->label != '' 
            || $this->request()->type != 'all' && $this->request()->type != '' 
            || $this->request()->source != 'all' && $this->request()->source != '')
        {
            $leadData = LeadFollowUp::with('lead')->select(
                'lead_follow_up.id',
                'lead_follow_up.label',
                'lead_follow_up.lead_id',DB::raw('NULL as client_id'),
                'lead_follow_up.remark',
                'lead_follow_up.status',
                'lead_follow_up.next_follow_up_date', 
                'lead_follow_up.created_at',
                'leads.client_name as title',
                'lead_sources.type as source',
                
            )
            ->leftJoin('leads', 'leads.id', 'lead_follow_up.lead_id')
            ->leftJoin('lead_sources', 'lead_sources.id', 'leads.source_id')
            ->where([['next_follow_up_date','>=', $startDate.' 00:00:00'], ['next_follow_up_date','<=', $endDate.' 23:59:59']])
            ->Where('lead_follow_up.added_by', user()->id);

            $clientData = ClientFollowUp::with('user')->select(
                'client_follow_up.id',
                'client_follow_up.label',
                DB::raw('NULL as lead_id'),
                'client_follow_up.client_id', 
                'client_follow_up.remark', 
                'client_follow_up.status',
                'client_follow_up.next_follow_up_date', 
                'client_follow_up.created_at',
                'users.name as title',
                'leads.source_id as source'

            )
            ->leftjoin('users', 'users.id', 'client_follow_up.client_id')
            ->leftJoin('leads', 'leads.client_id', 'client_follow_up.client_id')

            ->where([['next_follow_up_date','>=', $startDate.' 00:00:00'], ['next_follow_up_date','<=', $endDate.' 23:59:59']])
            ->Where('client_follow_up.added_by', user()->id);


// ****************************************************START:label filter**************************************************

            if ($this->request()->label != 'all' && $this->request()->label != '') {

                $leadData->where('lead_follow_up.label', $request->label);                                      
                $clientData->where('client_follow_up.label', $request->label);                                      

            }

// ****************************************************END:label filter**************************************************

// ****************************************************START:type filter**************************************************

            if ($this->request()->type != 'all' && $this->request()->type != '') {

                //lead
                if ($this->request()->type == 'lead') {
                    $leadData = $leadData->whereNotNull('lead_id');
                    $clientData = $clientData->having('lead_id', '=', NULL);

                }
                //client
                else {
                    $clientData = $clientData->whereNotNull('client_follow_up.client_id');
                    $leadData = $leadData->having('client_id', '=', NULL);

                }
            }
// ****************************************************END:type filter**************************************************


// ****************************************************START:source filter**************************************************

            if ($this->request()->source != 'all' && $this->request()->source != '') {

                $leadData->where('lead_sources.type', $request->source);
                $clientData->where('leads.source_id', $request->source);

            }
            
// ****************************************************END:source filter**************************************************

// ****************************************************START:search filter**************************************************

            if ($request->searchText != '' ) {
                $followUpData = $leadData->where('remark', 'like', '%' . request('searchText') . '%')
                    ->orWhere('next_follow_up_date', 'like', '%'.request('searchText').'%')
                    ->orWhere('label', 'like', '%'.request('searchText').'%')
                    ->orWhere('leads.client_name', 'like', '%' .request('searchText'). '%');

                $followUpData = $clientData->where('remark', 'like', '%' . request('searchText') . '%')
                    ->orWhere('next_follow_up_date', 'like', '%'.request('searchText').'%')
                    ->orWhere('label', 'like', '%'.request('searchText').'%')
                    ->orWhere('users.name', 'like', '%' .request('searchText'). '%');
            }
    
// ****************************************************END:search filter**************************************************

            $followUpData = $leadData->unionAll($clientData)->orderBy('next_follow_up_date', 'desc');
            return $followUpData;

        } else { 
            // lead data
            $leadData = LeadFollowUp::with('lead')->select(
                'lead_follow_up.id',
                'lead_follow_up.label',
                'lead_follow_up.lead_id',DB::raw('NULL as client_id'),
                'lead_follow_up.remark',
                'lead_follow_up.status',
                'lead_follow_up.next_follow_up_date', 
                'lead_follow_up.created_at',
                'leads.client_name as title',
                'lead_sources.type as source',
            )
            ->leftJoin('leads', 'leads.id', 'lead_follow_up.lead_id')
            ->leftJoin('lead_sources', 'lead_sources.id', 'leads.source_id')
            ->leftJoin(DB::raw('(SELECT lead_follow_up.lead_id,MAX(lead_follow_up.next_follow_up_date) as max_next_follow_up_date FROM lead_follow_up GROUP BY lead_follow_up.lead_id
                ) as max_lead_follow_data'),
                function($join) {
                    $join->on('max_lead_follow_data.lead_id', 'lead_follow_up.lead_id');
            })
            ->join(DB::raw('
                (SELECT MAX(id) as max_id FROM lead_follow_up WHERE next_follow_up_date GROUP BY lead_id  
                ) as latest_lead_follow_up'), 
                function($join) {
                    $join->on('latest_lead_follow_up.max_id', 'lead_follow_up.id');
            })
            ->where([['next_follow_up_date','>=', $startDate.' 00:00:00'], ['next_follow_up_date','<=', $endDate.' 23:59:59']])
            ->orWhere('max_next_follow_up_date', '<', Carbon::now()->format('Y-m-d'))
            ->Where('lead_follow_up.added_by', user()->id)
            ->groupBy('lead_id');


        
            //client data
            $clientData = ClientFollowUp::with('user')->select(
                'client_follow_up.id',
                'client_follow_up.label',
                DB::raw('NULL as lead_id'),
                'client_follow_up.client_id', 
                'client_follow_up.remark', 
                'client_follow_up.status',
                'client_follow_up.next_follow_up_date', 
                'client_follow_up.created_at',
                'users.name as title',
                'leads.source_id as source',

            )
            ->leftjoin('users', 'users.id', 'client_follow_up.client_id')
            ->leftJoin('leads', 'leads.client_id', 'client_follow_up.client_id')

            ->leftJoin(DB::raw('(SELECT client_follow_up.client_id,MAX(client_follow_up.next_follow_up_date) as max_next_follow_up_date FROM client_follow_up GROUP BY client_follow_up.client_id
                ) as max_lead_follow_data'),
                function($join) {
                    $join->on('max_lead_follow_data.client_id', 'client_follow_up.client_id');
            })
            ->join(DB::raw('
                (SELECT MAX(id) as max_id FROM client_follow_up WHERE next_follow_up_date GROUP BY client_id  
                ) as latest_client_follow_up'), 
                function($join) {
                    $join->on('latest_client_follow_up.max_id', 'client_follow_up.id');
            })
            ->where([['next_follow_up_date','>=', $startDate.' 00:00:00'], ['next_follow_up_date','<=', $endDate.' 23:59:59']])
            ->orWhere('max_next_follow_up_date', '<', Carbon::now()->format('Y-m-d'))
            ->Where('client_follow_up.added_by', user()->id)
            ->groupBy('client_id');

       

// ****************************************************union data**************************************************
        
            $followUpData = $leadData->unionAll($clientData)->orderBy('next_follow_up_date', 'desc');

            return $followUpData;
        }
    }
    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('followup-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    // ->dom('Bfrtip')
                    ->minifiedAjax()
                    ->orderBy(2)
                    ->destroy(true)
                    ->responsive(true)
                    ->serverSide(true)
                    ->processing(true)
                    ->paging(false)
                    ->parameters([
                        'initComplete' => 'function () {
                        window.LaravelDataTables["followup-table"].buttons().container()
                            .appendTo("#table-actions")
                        }',
                        'fnDrawCallback' => 'function( oSettings ) {
                            $("body").tooltip({
                                selector: \'[data-toggle="tooltip"]\'
                            });
                            $(".statusChange").selectpicker();
                        }',
                    ])
                    ->language(__('app.datatable'));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
      
        return [
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'visible' => true],
            __('modules.follow_up.title') => ['data' => 'title', 'name' => 'modules.follow_up.title', 'orderable' => false,'title' => __('modules.follow_up.title')],
            // __('modules.follow_up.type') => ['data' => 'type', 'name' => 'modules.follow_up.type', 'orderable' => false,'title' => __('modules.follow_up.type')],
            __('modules.follow_up.inquiry_source') => ['data' => 'source', 'name' => 'modules.follow_up.inquiry_source', 'orderable' => false,'title' => __('modules.follow_up.inquiry_source')],
            __('modules.follow_up.label') => ['data' => 'label', 'name' => 'modules.follow_up.label', 'orderable' => false,'title' => __('modules.follow_up.label')],
            __('modules.follow_up.next_follow_up_date') => ['data' => 'next_follow_up_date', 'name' => 'modules.follow_up.next_follow_up_date', 'orderable' => false,'title' => __('modules.follow_up.next_follow_up_date')],
            __('modules.follow_up.remark') => ['data' => 'remark', 'name' => 'modules.follow_up.remark', 'orderable' => false,'title' => __('modules.follow_up.remark')],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'FollowUp_' . date('YmdHis');
    }
}
