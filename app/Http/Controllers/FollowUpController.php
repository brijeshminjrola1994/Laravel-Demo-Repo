<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LeadSource;
use Illuminate\Http\Request;
use App\Helper\Reply;
use App\Models\ClientDetails;
use App\Models\ClientFollowUp;
use App\Models\LeadFollowUp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\HeadingRowImport;
use App\DataTables\FollowUpDataTable;
use App\Models\ProjectTimeLog;
use Illuminate\Support\Facades\App;
use App\Models\GlobalSetting;
use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\UserChat;
use App\Models\TaskHistory;
use App\Models\UserActivity;
use App\Models\ProjectActivity;
use App\Traits\UniversalSearchTrait;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\ClientFollowUp\StoreRequest as FollowUpStoreRequest;



class FollowUpController extends AccountBaseController
{

   
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.follow_up';

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index(FollowUpDataTable $dataTable)
    {
        $viewPermission = user()->permission('view_follow_up');
        $this->inquirySource = LeadSource::all();
        $leads   = LeadFollowUp::with('lead')->get();
        $clients = ClientFollowUp::with('user')->get();
        $items = collect($leads)
        ->merge($clients);

        return $dataTable->render('follow-up.index', $this->data);
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showClient($id)
    {
        $this->client_follow_up = ClientFollowUp::with(['user'])->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client_follow_up->client_id)->first();
        return view('follow-up.client_show', $this->data);
    }
    public function showLead($id) 
    {
        $this->lead_follow_up = LeadFollowUp::with('lead')->findOrFail($id);
        return view('follow-up.lead_show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function client_destroy($id)
    {
        //client follow up
        $client = ClientFollowUp::findOrFail($id);
        ClientFollowUp::destroy($client->id);
        return Reply::success(__('messages.followup_deleted'));

    }
    public function lead_destroy($id)
    {
        $lead = LeadFollowUp::findOrFail($id);
        LeadFollowUp::destroy($lead->id);
        return Reply::success(__('messages.followup_deleted'));
    }

    public function clientUpdateFollow(FollowUpStoreRequest $request)
    {
        $this->client = User::findOrFail($request->client_id);
        $clientfollowUp = ClientFollowUp::findOrFail($request->id);
        $clientfollowUp->client_id = $request->client_id;
        $clientfollowUp->next_follow_up_date = Carbon::createFromFormat($this->company->date_format . ' ' . $this->company->time_format, $request->next_follow_up_date . ' ' . $request->start_time)->format('Y-m-d H:i:s');
        $clientfollowUp->remark = $request->remark;
        $clientfollowUp->send_reminder = $request->send_reminder;
        $clientfollowUp->remind_time = $request->remind_time;
        $clientfollowUp->remind_type = $request->remind_type;
        $clientfollowUp->status = 'seen';
        $clientfollowUp->save();
        return Reply::success(__('messages.clientFollowUpUpdatedSuccess'));

    }

    public function leadUpdateFollow(FollowUpStoreRequest $request)
    {
        $this->lead = Lead::findOrFail($request->lead_id);
        $leadFollowUp = LeadFollowUp::findOrFail($request->id);
        $leadFollowUp->lead_id = $request->lead_id;
        $leadFollowUp->next_follow_up_date = Carbon::createFromFormat($this->company->date_format . ' ' . $this->company->time_format, $request->next_follow_up_date . ' ' . $request->start_time)->format('Y-m-d H:i:s');
        $leadFollowUp->remark = $request->remark;
        $leadFollowUp->send_reminder = $request->send_reminder;
        $leadFollowUp->remind_time = $request->remind_time;
        $leadFollowUp->remind_type = $request->remind_type;
        $leadFollowUp = 'seen';
        $leadFollowUp->save();

        return Reply::success(__('messages.leadFollowUpUpdatedSuccess'));

    }

}
