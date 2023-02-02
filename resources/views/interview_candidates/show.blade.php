<div id="holiday-detail-section">
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-white border-0 b-shadow-4">
                <div class="card-header bg-white  border-bottom-grey text-capitalize justify-content-between p-20">
                    <div class="row">
                        <div class="col-lg-10 col-10">
                            <h3 class="heading-h1 mb-3">@lang('app.menu.interview_candidate') @lang('app.details')</h3>
                        </div>
                        <div class="col-lg-2 col-2 text-right">
                            <div class="dropdown">
                                <button
                                    class="btn btn-lg f-14 px-2 py-1 text-dark-grey text-capitalize rounded  dropdown-toggle"
                                    type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right border-grey rounded b-shadow-4 p-0"
                                    aria-labelledby="dropdownMenuLink" tabindex="0">
                                    <a class="dropdown-item openRightModal"
                                        href="{{ route('interview-candidates.edit', $employee->id) }}">@lang('app.edit')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('modules.interview-candidate.candidate_name')" :value="$employee->name" html="true" />
                        </div>
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('modules.interview-candidate.email')" :value="$employee->email" html="true" />
                        </div>
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('modules.employees.gender')" :value="$employee->gender" html="true" />
                        </div>
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('app.designation')" :value="$designations->name" html="true" />
                        </div>
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('app.department')" :value="$teams->team_name" html="true" />
                        </div>
                        <?php
                        if($employee->status == '1'){
                            $status = 'modules.interview-candidate.approached';
                        }
                        else if($employee->status == '2'){
                            $status = 'modules.interview-candidate.interviewschedule';
                        }
                        else if($employee->status == '3'){
                            $status = 'modules.interview-candidate.interviewreschedule';
                        }
                        else if($employee->status == '4'){
                            $status = 'modules.interview-candidate.offerplaced';
                        }
                        else if($employee->status == '5'){
                            $status = 'modules.interview-candidate.rejected';
                        }
                        else if($employee->status == '6'){
                            $status = 'modules.interview-candidate.offeraccepted';
                        } 
                        else{
                            $status = 'modules.interview-candidate.offerrejected';
                        }
                        ?>
                        <div class="col-md-4">
                            <x-cards.data-row label="Status" :value="__($status)" html="true" />
                        </div>
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('app.mobile')" :value="$employee->mobile_no" html="true" />
                        </div>
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('modules.interview-candidate.city')" :value="$employee->city" html="true" />
                        </div>
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('modules.interview-candidate.experience')" :value="$employee->experience" html="true" />
                        </div>
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('Notice Period(In days)')" :value="$employee->notice_period" html="true" />
                        </div>
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('Current CTC(Per month)')" :value="$employee->current_ctc" html="true" />
                        </div>
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('Expected CTC(Per month)')" :value="$employee->current_ctc" html="true" />
                        </div>
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('Offered CTC(Per month)')" :value="$employee->offered_amount" html="true" />
                        </div>
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('modules.interview-candidate.joining_date')" :value="date('d-m-Y',strtotime($employee->joining_date))" html="true" />
                        </div>
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('Primary Skills')" :value="implode(',', $employee->skills())" html="true" />
                        </div>
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('app.address')" :value="$employee->address" html="true" />
                        </div>
                        <div class="col-md-4">
                            <x-cards.data-row :label="__('app.notes')" :value="$employee->notes" html="true" />
                        </div>
                        <div class="col-md-4">
                            <a href="{{$employee->resume_url}}" target="_blank">
                            <span class="unpaid text-info  border-info  rounded f-15">@lang('modules.interview-candidate.resume')</span></a>
                        </div>
                    </div>
                </div>
             
                <h4 class="mb-0 p-20 text-capitalize border-top-grey">
                    @lang('modules.interview-candidate.interviewschedule')</h4>
                        <x-table style="padding:20px;">
                            <tr>
                                <th>@lang('app.date')</th>
                                <th>@lang('app.time')</th>
                                <th>@lang('modules.interview-candidate.interviewername')</th>
                                <th>@lang('modules.interview-candidate.interviewtype')</th>
                                <th>@lang('modules.interview-candidate.communication')</th>
                                <th>@lang('modules.interview-candidate.technical')</th>
                                <th>@lang('modules.interview-candidate.attitude')</th>
                                <th>@lang('modules.interview-candidate.feedbackpoints')</th>
                                <th>@lang('modules.interview-candidate.pass')</th>
                            </tr>
                            @if(!$schedule->isEmpty())
                            @foreach ($schedule as $schedule_data)
                            <tr>
                                <td class="pl-20">
                                    {{ date('d-m-Y',strtotime($schedule_data->interview_date)) }}
                                </td>
                                <td class="pl-20">
                                    {{ $schedule_data->interview_time }}
                                </td>
                                <td class="pl-20">
                                    {{ $schedule_data->userName }}
                                </td>
                                <td class="pl-20">
                                    {{ $schedule_data->interview_type }}
                                </td>
                                <td class="pl-20">
                                    {{ $schedule_data->communication }}
                                </td>
                                <td class="pl-20">
                                    {{ $schedule_data->technical }}
                                </td>
                                <td class="pl-20">
                                    {{ $schedule_data->attitude }}
                                </td>
                                <td class="pl-20">
                                    {{ $schedule_data->feedback_points }}
                                </td>
                                <td class="pl-20">
                                    @if($schedule_data->passed == "No")
                                    <span class="badge badge-success" style="background-color:#DB1313">{{ $schedule_data->passed }}</span>
                                    @else
                                    <span class="badge badge-success" style="background-color:#16813D">{{ $schedule_data->passed }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" class="shadow-none">
                                        <x-cards.no-record icon="calendar" :message="__('messages.noRecordFound')" />
                                </td>
                            </tr>                        
                            @endif
                        </x-table>

            </div>
        </div>
    </div>
</div>
<script>
    
</script>
