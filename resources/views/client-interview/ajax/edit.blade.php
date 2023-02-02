@php
$addDesignationPermission = user()->permission('add_designation');
$addDepartmentPermission = user()->permission('add_department');
@endphp

<link rel="stylesheet" href="{{ asset('vendor/css/tagify.css') }}">
<style>
    .tagify_tags .height-35 {
        height: auto !important;
    }
    .hide
    {display: none;}
    .flex 
    {
        display: flex;
    }
    .mt-5
    {
        margin-top: -5px;
    }
</style>

<div class="row">
    <div class="col-sm-12">
    <x-form id="save-data-form" method="PUT">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('modules.client_interview.updateClientInterviewDetail')</h4>
                @include('sections.password-autocomplete-hide')
                <div class="row p-20">
                <div class="col-lg-9 col-xl-10">
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.datepicker fieldId="date" 
                                :fieldLabel="__('modules.interview-candidate.date')" fieldName="date"
                                :fieldPlaceholder="__('placeholders.date')" fieldRequired="true" 
                                :fieldValue="date('d-m-Y',strtotime($clientInterview->date))"/>
                            </div>

                            <div class="col-md-4">
                                <div class="bootstrap-timepicker timepicker">
                                <x-forms.text fieldLabel="Time" :fieldPlaceholder="__('placeholders.hours')" fieldName="time" fieldId="start_time" fieldRequired="true" 
                                 :fieldValue="$clientInterview->time" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <x-forms.text fieldId="client_name" :fieldLabel="__('modules.client_interview.client_name')"
                                    fieldName="client_name" fieldRequired="true"
                                    :fieldPlaceholder="__('modules.client_interview.client_name')" :fieldValue="$clientInterview->client_name">
                                </x-forms.text>
                            </div>
                            <div class="col-md-4">
                                <x-forms.text fieldId="company_name" :fieldLabel="__('modules.client_interview.company_name')"
                                    fieldName="company_name" fieldRequired="true"
                                    :fieldPlaceholder="__('modules.client_interview.company_name')" :fieldValue="$clientInterview->company_name">
                                </x-forms.text>
                            </div>
                            <div class="col-md-4">
                                <x-forms.label class="my-3" fieldId="technology_id"
                                    :fieldLabel="__('modules.client_interview.technology')" fieldRequired="true">
                                </x-forms.label>
                                <x-forms.input-group>
                                    <select class="form-control select-picker" name="technology_id"
                                        id="technology_id" data-live-search="true">
                                        <option value="">--</option>
                                        @foreach ($technology as $tech)
                                            <option @if ($clientInterview->technology_id == $tech->id) selected @endif  value="{{ $tech->id }}">
                                                {{ $tech->name }}</option>
                                        @endforeach
                                    </select>
                                </x-forms.input-group>
                            </div>
                            <div class="col-md-4">
                                <x-forms.label class="my-3" fieldId="schedule_by"
                                    :fieldLabel="__('modules.client_interview.schedule_by')" fieldRequired="true">
                                </x-forms.label>
                                <x-forms.input-group>
                                    <select class="form-control select-picker" name="schedule_by"
                                        id="schedule_by" data-live-search="true">
                                        <option value="">--</option>
                                        <option @if ($clientInterview->schedule_by == 0) selected @endif value="0">ManekTeck</option>
                                        @foreach ($schedule_by as $sch)
                                            <option @if ($clientInterview->schedule_by == $sch->id) selected @endif  value="{{ $sch->id }}">{{ $sch->name }}</option>
                                        @endforeach
                                    </select>
                                </x-forms.input-group>
                            </div>
                            <div class="col-md-4">
                                <!-- <x-forms.text fieldId="location" :fieldLabel="__('modules.client_interview.location')"
                                        fieldName="location" fieldRequired="true" :fieldPlaceholder="__('modules.client_interview.location')" :fieldValue="$clientInterview->location">
                                    </x-forms.text> -->
                                    <x-forms.select fieldId="country_id" :fieldLabel="__('app.country')" fieldRequired="true" fieldName="country_id"
                                        search="true">
                                        <option value="NA">NA</option>
                                         <option @if ($clientInterview->country_id == "Overseas client") selected @endif value="Overseas client">Overseas client</option>
                                        @foreach ($countries as $item)
                                            <option @if ($clientInterview->country_id == $item->id) selected @endif data-tokens="{{ $item->iso3 }}" data-content="<span
                                            class='flag-icon flag-icon-{{ strtolower($item->iso) }} flag-icon-squared'></span>
                                        {{ $item->nicename }}" value="{{ $item->id }}">{{ $item->nicename }}</option>
                                        @endforeach
                                    </x-forms.select>
                            </div>
                            <div class="col-md-4">
                                <x-forms.label class="mt-3"
                                    fieldId="mockRound"
                                    :fieldLabel="__('modules.client_interview.mockRound')">
                                </x-forms.label>
                                <div class="flex">
                                    @if( $clientInterview->mock_call == "no" ) 
                                <x-forms.radio fieldId="toEmployee"
                                    :fieldLabel="__('modules.notices.no')" fieldName="mock_call"
                                    fieldValue="no" checked="true">
                                </x-forms.radio>
                                <x-forms.radio fieldId="toClient" :fieldLabel="__('modules.notices.yes')"
                                    fieldValue="yes" fieldName="mock_call"></x-forms.radio>
                                </div>
                                @else
                                 <x-forms.radio fieldId="toEmployee"
                                    :fieldLabel="__('modules.notices.no')" fieldName="mock_call"
                                    fieldValue="no" >
                                </x-forms.radio>
                                <x-forms.radio fieldId="toClient" :fieldLabel="__('modules.notices.yes')"
                                    fieldValue="yes" fieldName="mock_call" checked="true"></x-forms.radio>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <!-- <x-forms.text fieldId="location" :fieldLabel="__('modules.client_interview.location')"
                                        fieldName="location" fieldRequired="true" :fieldPlaceholder="__('modules.client_interview.location')">
                                    </x-forms.text> -->
                                    <x-forms.select fieldId="round"  :fieldLabel="__('modules.client_interview.round')" fieldRequired="false" fieldName="round" search="true">
                                        <option  @if ($clientInterview->mock_round == "1") selected @endif  value="1">1</option>
                                        <option @if ($clientInterview->mock_round == "2") selected @endif  value="2">2</option>
                                        <option @if ($clientInterview->mock_round == "3") selected @endif  value="3">3</option>
                                        <option @if ($clientInterview->mock_round == "Final") selected @endif  value="Final">Final</option>
                                    </x-forms.select>
                            </div>
                            <div class="col-md-4">
                                <x-forms.label class="mt-3"
                                    fieldId="inhouse"
                                    :fieldLabel="__('modules.client_interview.inhouse')">
                                </x-forms.label>
                                <div class="flex">
                                @if( $clientInterview->inhouse == "NO" ) 
                                <x-forms.radio fieldId="no"
                                    :fieldLabel="__('modules.notices.no')" fieldName="inhouse"
                                    fieldValue="NO" checked="true">
                                </x-forms.radio>
                                <x-forms.radio fieldId="yes" :fieldLabel="__('modules.notices.yes')"
                                    fieldValue="YES" fieldName="inhouse"></x-forms.radio>
                                </div>
                                @else
                                 <x-forms.radio fieldId="no"
                                    :fieldLabel="__('modules.notices.no')" fieldName="inhouse"
                                    fieldValue="NO" >
                                </x-forms.radio>
                                <x-forms.radio fieldId="yes" :fieldLabel="__('modules.notices.yes')"
                                    fieldValue="YES" fieldName="inhouse" checked="true"></x-forms.radio>
                                </div>
                                @endif
                                   
                                </div>
                            </div>
                            <div class="col-md-4 inhouse @if($clientInterview->inhouse == 'NO' )  hide @endif">
                                <x-forms.label class="my-3" fieldId="developer_name"
                                    :fieldLabel="__('modules.client_interview.developer_name')" fieldRequired="true">
                                </x-forms.label>
                                <x-forms.input-group>
                                    <select class="form-control select-picker" name="developer_name"
                                        id="developer_name" data-live-search="true">
                                        <option value="">-developer name-</option>
                                        @foreach ($employees as $emp)
                                            <option @if ($clientInterview->developer_name == $emp->name) selected @endif  value="{{ $emp->name }}">{{ $emp->name }}</option>
                                        @endforeach

                                        <!-- <option @if($clientInterview->is_outsider == 1 )  selected @endif value="Outsider">Outsider</option> -->
                                    </select>
                                </x-forms.input-group>
                            </div>
                            <div class="col-md-4 outsider_dev @if($clientInterview->inhouse == 'YES' )  hide @endif">
                                <x-forms.text fieldId="developer_name_outsider" :fieldLabel="__('modules.client_interview.developer_name')"
                                    fieldName="developer_name_outsider" fieldRequired="true" fieldValue="{{$clientInterview->developer_name}}">
                                </x-forms.text>
                            </div>
                            <div class="col-md-4">  
                                <x-forms.tel fieldId="budget"  :fieldLabel="__('modules.client_interview.budget')" fieldName="budget"
                                 fieldPlaceholder="0"  :fieldValue="$clientInterview->budget"></x-forms.tel>
                            </div>
                            
                        </div>
                    </div>
                </div>

                </div>
                <x-form-actions>
                    <x-forms.button-primary id="save-form" class="mr-3" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('client-interview.index')" class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>
            </div>
        </x-form>

    </div>
</div>

<script src="{{ asset('vendor/jquery/tagify.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#start_time').timepicker();
      

        if ($('.custom-date-picker').length > 0) {
            datepicker('.custom-date-picker', {
                position: 'bl',
                ...datepickerConfig
            });
        }

        const dp1 = datepicker('#date', {
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

         $('#developer_name').change(function() {
           var developer_name =  $('#developer_name :selected').text(); 
           if(developer_name == "Outsider")
           {
            $('.outsider').removeClass('hide');
           }
           else
           {
            $('.outsider').addClass('hide');
           }
        })

        $('#save-form').click(function() {
            const url = "{{ route('client-interview.update', $clientInterview->id) }}";
             var budget = $('#budget').val();
            if(budget == "")
            {
                $('#budget').val("0");
            }
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

       

       

        
        init(RIGHT_MODAL);
    });

    function checkboxChange(parentClass, id) {
        var checkedData = '';
        $('.' + parentClass).find("input[type= 'checkbox']:checked").each(function() {
            checkedData = (checkedData !== '') ? checkedData + ', ' + $(this).val() : $(this).val();
        });
        $('#' + id).val(checkedData);
    }
    $("input[type=radio]").on("change", function() {
        if($(this).val()==="NO")
        {
            $(".outsider_dev").show();
            $('#developer_name_outsider').val('');
            $(".inhouse").hide();
        }
        else
        {
            $(".outsider_dev").hide();
            $(".inhouse").show();
            
        }
    });
</script>
