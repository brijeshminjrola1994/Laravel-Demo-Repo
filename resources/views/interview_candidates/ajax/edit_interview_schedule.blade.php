  <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title" id="exampleModalLabel">@lang('modules.interview-candidate.updateinterviewschedule')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="update-schedule-data-form" method="post">
        <div class="modal-body">
        <div class="row p-20">
                        <input type="hidden" name="id" id="interview_schedule_id" value="{{ $editdata->id }}">
                        <input type="hidden" name="interview_candidate_id" id="interview_candidate_id" value="{{ $editdata->interview_candidate_id }}">
                        <input type="hidden" name="redirectURl" id="redirectURl" value="">
                        <div class="col-lg-3 col-md-6">
                            <x-forms.datepicker fieldId="start_date1" fieldRequired="true"
                            :fieldValue="date('d-m-Y',strtotime($editdata->interview_date))"
                            :fieldLabel="__('modules.interview-candidate.date')" fieldName="interview_date"
                            :fieldPlaceholder="__('placeholders.date')" />
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="bootstrap-timepicker timepicker">
                                <x-forms.text :fieldLabel="__('modules.interview-candidate.time')"
                                :fieldPlaceholder="__('placeholders.hours')" fieldName="interview_time"
                                :fieldValue="$editdata->interview_time"
                                fieldId="start_time" fieldRequired="true" />
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <x-forms.select class="interviewer_id" fieldId="interviewer_id" :fieldLabel="__('modules.interview-candidate.interviewer')"
                                                    fieldName="interviewer_id" search="true" fieldRequired="true">
                                <option value="">--</option>
                                @foreach ($allemployees as $employee1)
                                    <option data-content="<div class='d-inline-block mr-1'><img class='taskEmployeeImg rounded-circle' src='{{ $employee1->image_url }}' ></div> {{ ucfirst($employee1->name) }}"
                                    @if ($editdata->interviewer_id == $employee1->id) selected @endif value="{{ $employee1->id }}">{{ ucfirst($employee1->name) }}</option>
                                @endforeach
                            </x-forms.select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <x-forms.select class="interview_type" fieldId="interview_type" :fieldLabel="__('modules.interview-candidate.interviewtype')"
                                                    fieldName="interview_type" search="true" fieldRequired="true">
                                <option value="">--</option>
                                <option @if ($editdata->interview_type == 'Technical Round1') selected @endif value="Technical Round1">@lang('modules.interview-candidate.techniacalround1')</option>
                                <option @if ($editdata->interview_type == 'Technical Round2') selected @endif value="Technical Round2">@lang('modules.interview-candidate.techniacalround2')</option>
                                <option @if ($editdata->interview_type == 'Practical Round') selected @endif value="Practical Round">@lang('modules.interview-candidate.practicalround')</option>
                                <option @if ($editdata->interview_type == 'Final Round') selected @endif value="Final Round">@lang('modules.interview-candidate.finalround')</option>
                                <option @if ($editdata->interview_type == 'HR Round') selected @endif value="HR Round">@lang('modules.interview-candidate.hrround')</option>
                            </x-forms.select>
                            
                        </div>
                        <div class="col-md-4">
                        <x-forms.tel fieldId="communication" :fieldLabel="__('Communication')" fieldName="communication"
                            :fieldPlaceholder="__('placeholders.communication')" :fieldValue="$editdata->communication"></x-forms.tel>
                    </div>
                    <div class="col-md-4">
                        <x-forms.tel fieldId="technical" :fieldLabel="__('Technical')" fieldName="technical"
                            :fieldPlaceholder="__('placeholders.communication')"
                            :fieldValue="$editdata->technical"
                            ></x-forms.tel>
                    </div>
                   
                    <div class="col-md-4">
                        <x-forms.tel fieldId="attitude" :fieldLabel="__('Attitude')" fieldName="attitude"
                            :fieldPlaceholder="__('placeholders.communication')"
                            :fieldValue="$editdata->attitude"
                            ></x-forms.tel>
                    </div>
                    
                    <div class="col-md-9">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('Feedback Points')"
                                fieldName="feedback_points" fieldId="feedback_points"
                                :fieldPlaceholder="__('Feedback Points')"
                                :fieldValue="$editdata->feedback_points">
                            </x-forms.textarea>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                            <x-forms.select class="passed" fieldId="passed" :fieldLabel="__('modules.interview-candidate.passed/failed')"
                                                    fieldName="passed" search="true">
                                <option value="">--</option>
                                <option @if ($editdata->passed == 'Yes') selected @endif  value="Yes">@lang('modules.interview-candidate.yes')</option>
                                <option @if ($editdata->passed == 'No') selected @endif value="No">@lang('modules.interview-candidate.no')</option>
                            </x-forms.select>
                        </div>

                    </div>
        </div>
        <div class="modal-footer border-top-0 d-flex justify-content-center">
        <x-form-actions>
            <x-forms.button-primary id="edit-schedule-form" class="mr-3" icon="check">@lang('app.save')
            </x-forms.button-primary>
            <x-forms.button-cancel class="border-0 close" data-dismiss="modal" aria-label="Close">@lang('app.cancel')
            </x-forms.button-cancel>
        </x-form-actions>
        </div>
      </form>
    </div>
<script>
    $(document).ready(function() {

        $(".select-picker").selectpicker();
        const dp2 = datepicker('#start_date1', {
            position: 'bl',
            dateSelected: new Date("{{ str_replace('-', '/', $editdata->interview_date) }}"),
            ...datepickerConfig
        });
        
        $('#start_time, #end_time').timepicker({
            @if ($global->time_format == 'H:i')
                showMeridian: false,
            @endif
        }).on('hide.timepicker', function(e) {
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

    });
</script>