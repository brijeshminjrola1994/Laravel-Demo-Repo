<style type="text/css">
    .hide
    {
        display: none;
    }
    .show
    {
        display: show;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">@lang('app.add') @lang('modules.client_interview.changeStatus')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <x-form id="statusChangeForm" method="POST" class="ajax-form">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Round {{$clientInterview->mock_round}}</h4>
                    </div>
                    <div class="col-md-6">
                        <x-forms.label class="my-3" fieldId="status"
                            :fieldLabel="__('modules.client_interview.status')" fieldRequired="true">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker status-selector" name="status"
                                id="status" data-live-search="true">
                                 <option @if ($clientInterview->status == 'Scheduled') selected @endif value="Scheduled">Scheduled</option>
                                <option @if ($clientInterview->status == 'Cleared') selected @endif value="Cleared">@lang('modules.client_interview.cleared')</option>
                                <option @if ($clientInterview->status == 'Cancelled') selected @endif value="Cancelled">Cancelled</option>
                                <option @if ($clientInterview->status == 'Rejected') selected @endif value="Rejected">@lang('modules.client_interview.Rejected')</option>
                                <option @if ($clientInterview->status == 'Awaiting') selected @endif  value="Awaiting">@lang('modules.client_interview.awaiting')</option>
                            </select>
                        </x-forms.input-group>
                    </div>
                    <!-- <div class="col-md-6 round-dropdown hide">
                        <x-forms.label class="my-3" fieldId="round"
                            :fieldLabel="__('modules.client_interview.round')" fieldRequired="false">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker round-selector" name="round"
                                id="round" data-live-search="true">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="Final">Final</option>
                            </select>
                        </x-forms.input-group>
                    </div> -->
                    <input type="hidden" name="round" value="{{$clientInterview->mock_round}}">
                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" fieldLabel="Feedback Round {{$clientInterview->mock_round}}"
                                fieldName="feedback" fieldId="feedback" :fieldPlaceholder="__('modules.client_interview.feedback')"
                                >
                                <!-- :fieldValue="$clientInterview->feedback" -->
                            </x-forms.textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" fieldLabel="Internal Feedback Round {{$clientInterview->mock_round}}"
                                fieldName="internal_feedback" fieldId="internal_feedback" :fieldPlaceholder="__('modules.client_interview.internal_feedback')"
                                >
                                <!-- :fieldValue="$clientInterview->internal_feedback" -->
                            </x-forms.textarea>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="id" value="{{ $clientInterviewId }}">
        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.close')</x-forms.button-cancel>
    <x-forms.button-primary id="save-status" icon="check">@lang('app.save')</x-forms.button-primary>
</div>

<script>
    $(document).ready(function() {
    $('.select-picker').change(function() {
   
    var value =$('.status-selector :selected').val();
    if( value == "Cleared")
    {
        $('.round-dropdown').removeClass('hide');
     
    }
    else
    {
        $('.round-dropdown').addClass('hide');
    }
    });
        // save channel
        $('#save-status').click(function() {
            $.easyAjax({
                url: "{{ route('client-interview.status_store') }}",
                container: '#statusChangeForm',
                type: "POST",
                blockUI: true,
                data: $('#statusChangeForm').serialize(),
                success: function(response) {
                    if (response.status == "success") {
                        $('.btn-cancel').click();
                        $('#client-interview-table').DataTable().ajax.reload(null, false);;
                        //window.location.reload(null, true);
                    }
                }
            })
        });
    });
</script>
