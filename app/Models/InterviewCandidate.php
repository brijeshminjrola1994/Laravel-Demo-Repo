<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'designation_id',
        'department_id',
        'country_id',
        'mobile_no',
        'experience',
        'gender',
        'address',
        'resume',
        'notice_period',
        'current_ctc',
        'expected_ctc',
        'joining_date',
        'offered_amount',
        'status',
        'notes',
        'added_by',
    ];

    public static $status = [
        1 => 'Approached',
        2 => 'Interview Scheduled',
        3 => 'Interview Rescheduled',
        4 => 'Offer Placed',
        5 => 'Rejected',
        6 => 'Offer Accepted',
        7 => 'Offer Rejected',
    ];

    protected $appends = ['resume_url'];

    public function getResumeUrlAttribute()
    {
        return ($this->resume) ? asset_url('resume/' . $this->resume) : '';
    }

    public static function allCandidates()
    {
        $users = InterviewCandidate::leftJoin('designations', 'interview_candidates.designation_id', '=', 'designations.id')
            ->select(
                'interview_candidates.id',
                'interview_candidates.name',
                'interview_candidates.email',
                'interview_candidates.created_at',
                'interview_candidates.resume',
                'designations.name as designation_name',
                'interview_candidates.mobile_no',
                'interview_candidates.experience',
                'interview_candidates.country_id'
            );
        $users->where('move_candidate',0);
        // if(!empty(reporting_emp_ids())){
        //     $users->whereIn('interview_candidates.added_by', reporting_emp_ids());
        // }
        $users->orderBy('interview_candidates.name', 'asc');
        $users->groupBy('interview_candidates.id');
        return $users->get();
    }

    //List Of candidate where Joining date is today
    public static function allSelectedCandidates()
    {
        $users = InterviewCandidate::leftJoin('designations', 'interview_candidates.designation_id', '=', 'designations.id')
            ->select(
                'interview_candidates.id',
                'interview_candidates.name',
                'interview_candidates.email',
                'interview_candidates.created_at',
                'interview_candidates.resume',
                'interview_candidates.joining_date',
                'designations.name as designation_name',
                'interview_candidates.mobile_no',
                'interview_candidates.experience',
                'interview_candidates.country_id'
            );
        $users->where('move_candidate',0);
        $users->where('interview_candidates.joining_date', date('Y-m-d'));
        $users->where('interview_candidates.status', 6);
        return $users->get();
    }

    public function skills()
    {
        return InterviewCandidatePrimarySkill::select('skills.name')->join('skills', 'skills.id', 'interview_candidate_primary_skills.skill_id')->where('interview_candidate_id', $this->id)->pluck('name')->toArray();
    }

    public function department()
    {
        return $this->belongsTo(Team::class, 'department_id');
    }

    public function designantion()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
}
