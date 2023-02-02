@php
$addDesignationPermission = user()->permission('add_designation');
$addDepartmentPermission = user()->permission('add_department');
@endphp

<link rel="stylesheet" href="{{ asset('vendor/css/tagify.css') }}">
<style>
    .tagify_tags .height-35 {
        height: auto !important;
    }

</style>
@include('interview_candidates.ajax.create_interview_schedule',array('candidate'=>$employee))

<div class="modal fade edit_interview_schedule_modal" id="edit_schedule_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display:none;">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-dynamic-content"></div>
</div>
</div>

<div class="row">
    <div class="col-sm-12">
        <x-form id="save-data-form" method="PUT">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                @lang('modules.interview-candidate.editCandidateDetail')</h4>
                @include('sections.password-autocomplete-hide')
                <div class="row p-20">
                    <div class="col-lg-9 col-xl-10">
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text fieldId="name" :fieldLabel="__('modules.employees.employeeName')"
                                    fieldName="name" :fieldValue="$employee->name" fieldRequired="true"
                                    :fieldPlaceholder="__('placeholders.name')">
                                </x-forms.text>
                            </div>
                            <div class="col-md-4">
                                <x-forms.text fieldId="email" :fieldLabel="__('modules.employees.employeeEmail')"
                                    fieldName="email" fieldRequired="true" :fieldValue="$employee->email"
                                    :fieldPlaceholder="__('placeholders.email')">
                                </x-forms.text>
                            </div>
                            <div class="col-md-4">
                                <x-forms.select fieldId="gender" :fieldLabel="__('modules.employees.gender')"
                                    fieldName="gender" fieldRequired="true">
                                    <option value="">--</option>
                                    <option @if ($employee->gender == 'male') selected @endif value="male">@lang('app.male')</option>
                                    <option @if ($employee->gender == 'female') selected @endif value="female">@lang('app.female')</option>
                                    <option @if ($employee->gender == 'others') selected @endif value="others">@lang('app.others')</option>
                                </x-forms.select>
                            </div>
                            <div class="col-md-4">
                                <x-forms.label class="my-3" fieldId="designation"
                                    :fieldLabel="__('app.designation')" fieldRequired="true">
                                </x-forms.label>
                                <x-forms.input-group>
                                    <select class="form-control select-picker" name="designation"
                                        id="employee_designation" data-live-search="true">
                                        <option value="">--</option>
                                        @foreach ($designations as $designation)
                                            <option @if ($employee->designation_id == $designation->id) selected @endif value="{{ $designation->id }}">
                                                {{ $designation->name }}</option>
                                        @endforeach
                                    </select>

                                    @if ($addDesignationPermission == 'all' || $addDesignationPermission == 'added')
                                        <x-slot name="append">
                                            <button id="designation-setting" type="button"
                                                class="btn btn-outline-secondary border-grey">@lang('app.add')</button>
                                        </x-slot>
                                    @endif
                                </x-forms.input-group>
                            </div>
                            <div class="col-md-4">
                                <x-forms.label class="my-3" fieldId="department"
                                    :fieldLabel="__('app.department')" fieldRequired="true">
                                </x-forms.label>
                                <x-forms.input-group>
                                    <select class="form-control select-picker" name="department"
                                        id="employee_department" data-live-search="true">
                                        <option value="">--</option>
                                        @foreach ($teams as $team)
                                            <option @if ($employee->department_id == $team->id) selected @endif value="{{ $team->id }}">
                                                {{ $team->team_name }}</option>
                                        @endforeach
                                    </select>

                                    @if ($addDepartmentPermission == 'all' || $addDepartmentPermission == 'added')
                                        <x-slot name="append">
                                            <button id="department-setting" type="button"
                                                class="btn btn-outline-secondary border-grey">@lang('app.add')</button>
                                        </x-slot>
                                    @endif
                                </x-forms.input-group>
                            </div>
                            <div class="col-md-4">
                                <label class="f-14 text-dark-grey mb-12 mt-3" data-label="1" for="status">@lang('app.status')
                                    <sup class="f-14 mr-1">*</sup>
                                </label>
                                <div class="dropdown bootstrap-select form-control select-picker">
                                    <select class="form-control select-picker" name="status" id="status" tabindex="null">
                                        <!-- <option value="pending" data-content="<i class='fa fa-circle mr-2 text-yellow'></i> Pending ">Pending</option> -->
                                        <option @if ($employee->status == '1') selected @endif value="1" data-content="<i class='fa fa-circle mr-2 text-blue'></i> @lang('modules.interview-candidate.approached') ">@lang('modules.interview-candidate.approached')</option>

                                        <option @if ($employee->status == '2') selected @endif value="2" data-content="<i class='fa fa-circle mr-2 text-yellow'></i> @lang('modules.interview-candidate.interviewschedule') ">@lang('modules.interview-candidate.interviewschedule')</option>

                                        <option @if ($employee->status == '3') selected @endif value="3" data-content="<i class='fa fa-circle mr-2 text-info'></i> @lang('modules.interview-candidate.interviewreschedule') ">@lang('modules.interview-candidate.interviewreschedule')</option>

                                        <option @if ($employee->status == '4') selected @endif value="4" data-content="<i class='fa fa-circle mr-2 text-blue'></i> @lang('modules.interview-candidate.offerplaced') ">@lang('modules.interview-candidate.offerplaced')</option>

                                        <option @if ($employee->status == '5') selected @endif value="5" data-content="<i class='fa fa-circle mr-2 text-red'></i> @lang('modules.interview-candidate.rejected') ">@lang('modules.interview-candidate.rejected')</option>

                                        <option @if ($employee->status == '8') selected @endif value="5" data-content="<i class='fa fa-circle mr-2 text-dark-green'></i> @lang('modules.interview-candidate.selected') ">@lang('modules.interview-candidate.selected')</option>
                                        
                                        <option @if ($employee->status == '6') selected @endif value="6" data-content="<i class='fa fa-circle mr-2 text-dark-green'></i> @lang('modules.interview-candidate.offeraccepted') ">@lang('modules.interview-candidate.offeraccepted')</option>

                                        <option @if ($employee->status == '7') selected @endif value="7" data-content="<i class='fa fa-circle mr-2 text-red'></i> @lang('modules.interview-candidate.offerrejected') ">@lang('modules.interview-candidate.offerrejected')</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xl-2">
                        @php
                            $userImage = asset('img/avatar.png');
                        @endphp
                        <x-forms.file allowedFileExtensions="pdf" class="mr-0 mr-lg-2 mr-md-2"
                            :fieldLabel="__('Resume')"
                            :fieldValue="($employee->resume ? $employee->resume_url : $userImage)" fieldName="image"
                            fieldId="image" fieldHeight="119" />
                            <a href="{{$employee->resume_url}}">@lang('app.download')</a>
                    </div>
                    <div class="col-md-3">
                        <x-forms.text fieldId="city" :fieldLabel="__('modules.interview-candidate.city')"
                                    fieldName="city" :fieldValue="$employee->city" fieldRequired="true"
                                    :fieldPlaceholder="__('placeholders.city')">
                        </x-forms.text>
                    </div>
                    <div class="col-md-3">
                        <x-forms.tel fieldId="mobile" :fieldLabel="__('app.mobile')" fieldName="mobile"
                            :fieldValue="$employee->mobile_no" fieldPlaceholder="e.g. 987654321"></x-forms.tel>
                    </div>
                    <div class="col-md-3">
                        <x-forms.tel fieldId="experience" fieldRequired="true" :fieldLabel="__('modules.interview-candidate.experience')" fieldName="experience"
                        :fieldValue="$employee->experience" fieldPlaceholder="e.g. 2.0"></x-forms.tel>
                    </div>
                    <div class="col-md-3">
                        <x-forms.tel fieldId="notice_period" :fieldLabel="__('Notice Period(In days)')" fieldName="notice_period"
                            :fieldValue="$employee->notice_period" fieldPlaceholder="e.g. 10" fieldRequired="true"></x-forms.tel>
                    </div>
                    <div class="col-md-3">
                        <x-forms.tel fieldId="current_ctc" :fieldLabel="__('Current CTC(Per month)')" fieldName="current_ctc"
                            :fieldValue="$employee->current_ctc" fieldPlaceholder="e.g. 10000" fieldRequired="true"></x-forms.tel>
                    </div>
                    <div class="col-md-3">
                        <x-forms.tel fieldId="expected_ctc" :fieldLabel="__('Expected CTC(Per month)')" fieldName="expected_ctc"
                            :fieldValue="$employee->expected_ctc" fieldPlaceholder="e.g. 20000" fieldRequired="true"></x-forms.tel>
                    </div>
                    <div class="col-md-3">
                        <x-forms.tel fieldId="offered_amount" :fieldLabel="__('Offered CTC(Per month)')" fieldName="offered_amount"
                            :fieldValue="$employee->offered_amount" fieldPlaceholder="e.g. 20000" fieldRequired="true"></x-forms.tel>
                    </div>
                    <div class="col-md-3">
                            @if(!empty($employee->joining_date) && $employee->joining_date != '0000-00-00')
                            <x-forms.datepicker fieldId="joining_date"
                            :fieldValue="date('d-m-Y',strtotime($employee->joining_date))"
                            :fieldLabel="__('modules.interview-candidate.joining_date')" fieldName="joining_date"
                            :fieldPlaceholder="__('placeholders.date')" />
                            @else
                            <x-forms.datepicker fieldId="joining_date" 
                            :fieldLabel="__('modules.interview-candidate.joining_date')" fieldName="joining_date"
                            :fieldPlaceholder="__('placeholders.date')" />
                            @endif
                    </div>
                   
                    <div class="col-md-4">
                        <x-forms.text class="tagify_tags" fieldId="tags" :fieldLabel="__('Primary Skills')"
                            fieldName="tags" :fieldPlaceholder="__('placeholders.skills')"
                            :fieldValue="implode(',', $employee->skills())" fieldRequired="true"/>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('app.address')"
                                :fieldValue="$employee->address" fieldName="address" fieldId="address"
                                :fieldPlaceholder="__('placeholders.address')">
                            </x-forms.textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('app.notes')"
                                :fieldValue="$employee->notes" fieldName="notes" fieldId="notes"
                                :fieldPlaceholder="__('placeholders.notes')">
                            </x-forms.textarea>
                        </div>
                    </div>
                </div>

                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-top-grey">
                    @lang('modules.interview-candidate.interviewschedule')</h4>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-outline-info border-blue department-setting" data-toggle="modal" data-target="#create_schedule_form"><svg class="svg-inline--fa fa-plus fa-w-14 mr-1" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg>Add Schedule</button>
                    </div>
                        <x-table>
                            <tr>
                                <th>@lang('app.date')</th>
                                <th>@lang('app.time')</th>
                                <th>@lang('modules.interview-candidate.interviewername')</th>
                                <th>@lang('modules.interview-candidate.interviewtype')</th>
                                <th>@lang('modules.interview-candidate.pass')</th>
                                <th>@lang('app.action')</th>
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
                                    @if($schedule_data->passed == "No")
                                    <span class="badge badge-success" style="background-color:#DB1313">{{ $schedule_data->passed }}</span>
                                    @else
                                    <span class="badge badge-success" style="background-color:#16813D">{{ $schedule_data->passed }}</span>
                                    @endif
                                </td>
                                <td>
                                <div class="task_view">
                                    <div class="dropdown">
                                        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-options-vertical icons"></i>
                                        </a>
                                        <!-- Dropdown - User Information -->
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" tabindex="0" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(23px, 26px, 0px);">
                                            <a data-toggle="modal" data-id="{{ $schedule_data }}" data-target="#edit_schedule_form" class="dropdown-item leave-action edit_interview_schedule_btn" data-leave-id="18" data-leave-action="approved" href="javascript:;">
                                            <svg class="svg-inline--fa fa-edit fa-w-18 mr-2" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="edit" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z"></path></svg>
                                                @lang('app.edit')
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6" class="shadow-none">
                                        <x-cards.no-record icon="calendar" :message="__('messages.noRecordFound')" s />
                                </td>
                            </tr>                        
                            @endif
                        </x-table>
                </div>
                <x-form-actions>
                    <x-forms.button-primary id="save-form" class="mr-3" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('interview-candidates.index')" class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>
            </div>
        </x-form>

    </div>
</div>

<script src="{{ asset('vendor/jquery/tagify.min.js') }}"></script>
<script>
    $(document).ready(function() {

        $('.edit_interview_schedule_btn').click(function() {
            var data = $(this).data("id");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        const url = {!! json_encode(url('/')) !!}+'/account/interview-candidates-schedule/editschedulePage/'+data.id;
        $.ajax({
                type: 'GET',
                url: url,
                dataType: 'HTML',
                success: function (data) {

                },
            }).then(data => {
                $('.modal-dynamic-content').html(data);
                $('.edit_interview_schedule_modal').modal("show");
            })
            .catch(error => {
                var xhr = $.ajax();
                console.log(xhr);
                console.log(error);
            })

        });

        const dp1 = datepicker('#joining_date', {
            position: 'bl',
            onSelect: (instance, date) => {
                if (typeof dp2.dateSelected !== 'undefined' && dp2.dateSelected.getTime() < date
                    .getTime()) {
                    dp2.setDate(date, true)
                }
                if (typeof dp2.dateSelected === 'undefined') {
                    dp2.setDate(date, true)
                }
                dp2.setMin(date);
                //calculateTime();
            },
            ...datepickerConfig
        });

       

        $('#start_time, #end_time').timepicker({
            @if ($global->time_format == 'H:i')
                showMeridian: false,
            @endif
        }).on('hide.timepicker', function(e) {
            //calculateTime();
        });

        if ($('.custom-date-picker').length > 0) {
            datepicker('.custom-date-picker', {
                position: 'bl',
                ...datepickerConfig
            });
        }

        var input = document.querySelector('input[name=tags]'),
            // init Tagify script on the above inputs
            tagify = new Tagify(input, {
                whitelist: {!! json_encode($skills) !!},
            });

        $('#save-form').click(function() {
            const url = "{{ route('interview-candidates.update', $employee->id) }}";
            $.easyAjax({
                url: url,
                container: '#save-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-form",
                file: true,
                data: $('#save-data-form').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        window.location.href = response.redirectUrl;
                    }
                }
            });
        });

        $('#add-schedule-form').click(function() {
            const url = "{{ route('interview-candidates-schedule.store') }}"+'?_token=' + '{{ csrf_token() }}';
            $.easyAjax({
                url: url,
                container: '#add-schedule-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#add-schedule-form",
                file: true,
                data: $('#add-schedule-data-form').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        window.location.href = response.redirectUrl;
                    }
                }
            });
        });

        $('#edit-schedule-form').click(function() {
            var schedule_id = $('#interview_schedule_id').val();
            const url = {!! json_encode(url('/')) !!}+'/account/interview-candidates-schedule/editschedule?_token='+ '{{ csrf_token() }}';
            $.easyAjax({
                url: url,
                container: '#update-schedule-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#edit-schedule-form",
                file: true,
                data: $('#update-schedule-data-form').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        window.location.href = response.redirectUrl;
                    }
                }
            });
        });

        $('#random_password').click(function() {
            const randPassword = Math.random().toString(36).substr(2, 8);

            $('#password').val(randPassword);
        });

        $('#designation-setting').click(function() {
            const url = "{{ route('designations.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        })

        $('#department-setting').click(function() {
            const url = "{{ route('departments.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        
        init(RIGHT_MODAL);
    });

    function checkboxChange(parentClass, id) {
        var checkedData = '';
        $('.' + parentClass).find("input[type= 'checkbox']:checked").each(function() {
            checkedData = (checkedData !== '') ? checkedData + ', ' + $(this).val() : $(this).val();
        });
        $('#' + id).val(checkedData);
    }
</script>
