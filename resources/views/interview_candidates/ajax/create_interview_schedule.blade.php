<div class="modal fade" id="create_schedule_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title" id="exampleModalLabel">@lang('modules.interview-candidate.createinterviewschedule')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="add-schedule-data-form" method="post">
        <div class="modal-body">
        <div class="row p-20">
                        <input type="hidden" name="interview_candidate_id" id="interview_candidate_id" value="{{ $candidate->id }}">

                        <div class="col-lg-3 col-md-6">
                            <x-forms.datepicker fieldId="start_date" fieldRequired="true"
                            :fieldLabel="__('modules.interview-candidate.date')" fieldName="interview_date"
                            :fieldValue="\Carbon\Carbon::now($global->timezone)->format($global->date_format)"
                            :fieldPlaceholder="__('placeholders.date')" />
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="bootstrap-timepicker timepicker">
                                <x-forms.text :fieldLabel="__('modules.interview-candidate.time')"
                                :fieldPlaceholder="__('placeholders.hours')" fieldName="interview_time"
                                fieldId="start_time" fieldRequired="true" />
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <x-forms.select fieldId="interviewer_id" :fieldLabel="__('modules.interview-candidate.interviewer')"
                                                    fieldName="interviewer_id" search="true" fieldRequired="true">
                                <option value="">--</option>
                                @foreach ($allemployees as $employee1)
                                    <option data-content="<div class='d-inline-block mr-1'><img class='taskEmployeeImg rounded-circle' src='{{ $employee1->image_url }}' ></div> {{ ucfirst($employee1->name) }}"
                                        value="{{ $employee1->id }}">{{ ucfirst($employee1->name) }}</option>
                                @endforeach
                            </x-forms.select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <x-forms.select fieldId="interview_type" :fieldLabel="__('modules.interview-candidate.interviewtype')"
                                                    fieldName="interview_type" search="true" fieldRequired="true">
                                <option value="">--</option>
                                <option value="Technical Round1">@lang('modules.interview-candidate.techniacalround1')</option>
                                <option value="Technical Round2">@lang('modules.interview-candidate.techniacalround2')</option>
                                <option value="Practical Round">@lang('modules.interview-candidate.practicalround')</option>
                                <option value="Final Round">@lang('modules.interview-candidate.finalround')</option>
                                <option value="HR Round">@lang('modules.interview-candidate.hrround')</option>
                                
                            </x-forms.select>
                        </div>

                    </div>
        </div>
        <div class="modal-footer border-top-0 d-flex justify-content-center">
        <x-form-actions>
            <x-forms.button-primary id="add-schedule-form" class="mr-3" icon="check">@lang('app.save')
            </x-forms.button-primary>
            <x-forms.button-cancel class="border-0 close" data-dismiss="modal" aria-label="Close">@lang('app.cancel')
            </x-forms.button-cancel>
        </x-form-actions>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        const dp2 = datepicker('#start_date', {
            position: 'bl',
            onSelect: (instance, date) => {
            },
            ...datepickerConfig
        });

    });
</script>