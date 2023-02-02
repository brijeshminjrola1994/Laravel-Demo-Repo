<?php

namespace App\Http\Controllers;

use App\DataTables\InterviewCandidateDataTable;
use App\Helper\Files;
use App\Models\Country;
use App\Models\User;
use App\Models\Designation;
use App\Models\InterviewCandidate;
use App\Models\InterviewCandidateSchedule;
use App\Models\Skill;
use App\Models\Team;
use App\Helper\Reply;
use App\Models\EmployeeDetails;
use App\Models\Role;
use App\Http\Requests\Admin\Interview\StoreRequest;
use App\Http\Requests\Admin\Interview\UpdateRequest;
use App\Models\InterviewCandidatePrimarySkill;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InterviewCandidateController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.interview_candidate';
    }

    public function index(InterviewCandidateDataTable $dataTable)
    {
        if (!request()->ajax()) {
            $this->candidates = InterviewCandidate::allCandidates();
            $this->skills = Skill::all();
            $this->departments = Team::all();
            $this->designations = Designation::allDesignations();
            $this->totalCandidates = count($this->candidates);
            $this->startDate  = (request('startDate') != '') ? Carbon::createFromFormat($this->global->date_format, request('startDate')) : now($this->global->timezone)->startOfMonth();
            $this->endDate = (request('endDate') != '') ? Carbon::createFromFormat($this->global->date_format, request('endDate')) : now($this->global->timezone)->endOfMonth();
        }
        return $dataTable->render('interview_candidates.index', $this->data);

    }
    public function create()
    {
        $this->pageTitle = __('app.add') . ' ' . __('app.candidate');
        $this->skills = Skill::all()->pluck('name')->toArray();
        $this->teams = Team::allDepartments();
        $this->designations = Designation::allDesignations();
        $this->countries = Country::all();
        if (request()->ajax()) {
            $html = view('interview_candidates.ajax.create', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        $this->view = 'interview_candidates.ajax.create';
        return view('interview_candidates.create', $this->data);
    }

    /**
     *
     * @param CreateRequest $request
     * @return void
     */
    public function store(StoreRequest $request)
    {
        $user = new InterviewCandidate();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile_no = !empty($request->mobile) ? $request->mobile : '';
        $user->experience = !empty($request->experience) ? $request->experience : '';
        $user->country_id = !empty($request->country) ? $request->country : '';
        $user->city = !empty($request->city) ? $request->city : '';
        $user->joining_date = !empty($request->joining_date) ? date("Y-m-d", strtotime($request->joining_date)) : '';
        $user->gender = $request->gender;
        $user->designation_id = $request->designation;
        $user->department_id = $request->department;
        $user->address = $request->address;
        $user->notice_period = $request->notice_period;
        $user->current_ctc = $request->current_ctc;
        $user->expected_ctc = $request->expected_ctc;
        $user->address = $request->address;
        $user->notes = !empty($request->notes) ? $request->notes : '';
        $user->added_by = user()->id;

        if ($request->image_delete == 'yes') {
            Files::deleteFile($user->resume, 'resume');
            $user->resume = null;
        }

        if ($request->hasFile('resume')) {

            Files::deleteFile($user->resume, 'resume');
            $user->resume = Files::uploadLocalOrS3($request->resume, 'resume');
        }

        $user->save();

        $tags = json_decode($request->tags);

        if (!empty($tags)) {
            InterviewCandidatePrimarySkill::where('interview_candidate_id', $user->id)->delete();

            foreach ($tags as $tag) {
        // Check or store skills
                $skillData = Skill::firstOrCreate(['name' => strtolower($tag->value)]);

        // Store user skills
                $skill = new InterviewCandidatePrimarySkill();
                $skill->interview_candidate_id = $user->id;
                $skill->skill_id = $skillData->id;
                $skill->save();
            }
        }

        return Reply::successWithData(__('messages.addSuccess'), ['redirectUrl' => route('interview-candidates.index')]);
    }


    public function show($id)
    {
        $this->employee = InterviewCandidate::findOrFail($id);        
        $this->pageTitle = ucfirst($this->employee->name);
        $this->designations = Designation::findOrFail($this->employee->designation_id);
        $this->teams = Team::findOrFail($this->employee->department_id);
        $this->schedule = InterviewCandidateSchedule::allScheduleOfCandidate($id);
        if (request()->ajax()) {
            $html = view('holiday.ajax.show', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'interview_candidates.show';

        return view('interview_candidates.create', $this->data);

    }

    public function edit($id)
    {
        $this->employee = InterviewCandidate::findOrFail($id);
        $this->allemployees = User::allEmployees();
        $this->pageTitle = __('app.update') . ' ' . __('app.candidate');
        $this->skills = Skill::all()->pluck('name')->toArray();
        $this->teams = Team::allDepartments();
        $this->designations = Designation::allDesignations();
        $this->countries = Country::all();
        
        $this->schedule = InterviewCandidateSchedule::allScheduleOfCandidate($id);
        if (request()->ajax()) {
            $html = view('interview_candidates.ajax.edit', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'interview_candidates.ajax.edit';

        return view('interview_candidates.create', $this->data);

    }

    public function update(UpdateRequest $request, $id)
    {
        $user = InterviewCandidate::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile_no = !empty($request->mobile) ? $request->mobile : '';
        $user->experience = !empty($request->experience) ? $request->experience : '';
        $user->country_id = !empty($request->country) ? $request->country : '';
        $user->city = !empty($request->city) ? $request->city : '';
        $user->gender = $request->gender;
        $user->designation_id = $request->designation;
        $user->department_id = $request->department;
        $user->address = $request->address;
        $user->joining_date = !empty($request->joining_date) ? date("Y-m-d", strtotime($request->joining_date)) : '';
        $user->notice_period = $request->notice_period;
        $user->current_ctc = $request->current_ctc;
        $user->expected_ctc = $request->expected_ctc;
        $user->offered_amount = $request->offered_amount;
        $user->address = $request->address;
        $user->notes = !empty($request->notes) ? $request->notes : '';
        $user->status = $request->status;
        $user->added_by = user()->id;
        
        if ($request->image_delete == 'yes') {
            Files::deleteFile($user->resume, 'resume');
            $user->resume = null;
        }

        if ($request->hasFile('image')) {

            Files::deleteFile($user->resume, 'resume');
            $user->resume = Files::uploadLocalOrS3($request->image, 'resume');
        }

        $user->save();

        $candidate = InterviewCandidate::findOrFail($id);
        $candidate->status = $request->status;
        $candidate->save();

        $tags = json_decode($request->tags);

        if (!empty($tags)) {
            InterviewCandidatePrimarySkill::where('interview_candidate_id', $user->id)->delete();

            foreach ($tags as $tag) {
                // Check or store skills
                $skillData = Skill::firstOrCreate(['name' => strtolower($tag->value)]);

                // Store user skills
                $skill = new InterviewCandidatePrimarySkill();
                $skill->interview_candidate_id = $user->id;
                $skill->skill_id = $skillData->id;
                $skill->save();
            }
        }

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => route('interview-candidates.index')]);
    }

    //Move Interview candidate data to employee
    public function moveCandidateData(Request $request){
        $candidate = InterviewCandidate::findOrFail($request->id);
        $user = new User();
        $user->name = $candidate->name;
        $user->email = $candidate->email;
        $user->password = bcrypt('123456');
        $user->mobile = !empty($candidate->mobile_no) ? $candidate->mobile_no : '';
        $user->gender = $candidate->gender;
        $user->experience = $candidate->experience;
        $user->save();

        $employee = new EmployeeDetails();
        $employee->user_id = $user->id;
        $employee->added_by = $candidate->added_by;
        $this->employeeData($user, $employee,$candidate);
        $employee->save();
        $employeeRole = Role::where('name', 'employee')->first();
        $user->attachRole($employeeRole);
		$user->assignUserRolePermission($employeeRole->id);

        $candidate->move_candidate = '1';
        $candidate->save();

        $emp = EmployeeDetails::findOrFail($employee->id);
        $emp->added_by = $candidate->added_by;
        $emp->save();

        return Reply::success(__('messages.candidateStatusUpdate'));
    }

    public function employeeData($request, $employee, $candidate): void
    {
        $employee->employee_id = $request->employee_id;
        $employee->address = $candidate->address;
        $employee->hourly_rate = $request->hourly_rate;
        $employee->slack_username = $request->slack_username;
        $employee->department_id = $candidate->department_id;
        $employee->designation_id = $candidate->designation_id;
        $employee->joining_date =date('Y-m-d H:i:s',strtotime($candidate->joining_date));
    }

     /**
     * @param Request $request
     * @return array
     */
    public function applyQuickAction(Request $request)
    {
        switch ($request->action_type) {
        case 'delete':
            $this->deleteRecords($request);
                return Reply::success(__('messages.deleteSuccess'));
        case 'change-status':
            $this->changeStatus($request);
                return Reply::success(__('messages.statusUpdatedSuccessfully'));
        default:
                return Reply::error(__('messages.selectAction'));
        }
    }

    protected function deleteRecords($request)
    {
        abort_403(user()->permission('delete_interview_candidates') != 'all');

        InterviewCandidate::whereIn('id', explode(',', $request->row_ids))->delete();
        InterviewCandidateSchedule::whereIn('interview_candidate_id', explode(',', $request->row_ids))->delete();
        InterviewCandidatePrimarySkill::whereIn('interview_candidate_id', explode(',', $request->row_ids))->delete();
    }
}
