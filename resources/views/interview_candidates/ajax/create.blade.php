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

<div class="row">
    <div class="col-sm-12">
        <x-form id="save-data-form" method="post">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('modules.interview-candidate.addCandidateDetail')</h4>
                @include('sections.password-autocomplete-hide')
                <div class="row p-20">
                    <div class="col-lg-9 col-xl-10">
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text fieldId="name" :fieldLabel="__('modules.employees.employeeName')"
                                    fieldName="name" fieldRequired="true"
                                    :fieldPlaceholder="__('placeholders.name')">
                                </x-forms.text>
                            </div>
                            <div class="col-md-4">
                                <x-forms.text fieldId="email" :fieldLabel="__('modules.employees.employeeEmail')"
                                    fieldName="email" fieldRequired="true" :fieldPlaceholder="__('placeholders.email')">
                                </x-forms.text>
                            </div>
                            <div class="col-md-4">
                                <x-forms.select fieldId="gender" :fieldLabel="__('modules.employees.gender')"
                                    fieldName="gender" fieldRequired="true">
                                    <option value="">--</option>
                                    <option value="male">@lang('app.male')</option>
                                    <option value="female">@lang('app.female')</option>
                                    <option value="others">@lang('app.others')</option>
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
                                            <option value="{{ $designation->id }}">
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
                                            <option value="{{ $team->id }}">
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
                            <div class="col-md-4 interview-candidate-status-lable">
                                    <span class="unpaid text-primary  border-primary rounded f-15">@lang('modules.interview-candidate.approached')</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xl-2">
                        @php
                            $userImage = asset('img/avatar.png');
                        @endphp
                        <x-forms.file allowedFileExtensions="pdf" class="mr-0 mr-lg-2 mr-md-2"
                            :fieldLabel="__('Resume')"
                            fieldName="resume"
                            fieldId="resume" fieldHeight="119" />
                    </div>
                    <div class="col-md-3">
                        <x-forms.text fieldId="city" :fieldLabel="__('modules.interview-candidate.city')"
                                    fieldName="city" fieldRequired="true"
                                    :fieldPlaceholder="__('placeholders.city')">
                        </x-forms.text>
                    </div>
                    <div class="col-md-3">
                        <x-forms.tel fieldId="mobile" :fieldLabel="__('app.mobile')" fieldName="mobile"
                            fieldPlaceholder="e.g. 987654321"></x-forms.tel>
                    </div>
                    <div class="col-md-3">
                        <x-forms.tel fieldId="experience" fieldRequired="true" :fieldLabel="__('modules.interview-candidate.experience')" fieldName="experience"
                            fieldPlaceholder="e.g. 2.0"></x-forms.tel>
                    </div>
                    <div class="col-md-3">
                        <x-forms.tel fieldId="notice_period" fieldRequired="true" :fieldLabel="__('Notice Period (In days)')" fieldName="notice_period"
                            fieldPlaceholder="e.g. 10"></x-forms.tel>
                    </div>
                    <div class="col-md-4">
                        <x-forms.tel fieldId="current_ctc" fieldRequired="true" :fieldLabel="__('Current CTC (Per month)')" fieldName="current_ctc"
                            fieldPlaceholder="e.g. 10000"></x-forms.tel>
                    </div>
                    <div class="col-md-4">
                        <x-forms.tel fieldId="expected_ctc" fieldRequired="true" :fieldLabel="__('Expected CTC (Per month)')" fieldName="expected_ctc"
                            fieldPlaceholder="e.g. 20000"></x-forms.tel>
                    </div>
                   
                    <div class="col-md-4">
                        <x-forms.text class="tagify_tags" fieldRequired="true" fieldId="tags" :fieldLabel="__('Primary Skills')"
                            fieldName="tags" :fieldPlaceholder="__('placeholders.skills')"
                            />
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('app.address')"
                                fieldName="address" fieldId="address"
                                :fieldPlaceholder="__('placeholders.address')">
                            </x-forms.textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('app.notes')"
                                fieldName="notes" fieldId="notes"
                                :fieldPlaceholder="__('placeholders.notes')">
                            </x-forms.textarea>
                        </div>
                    </div>
                </div>
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
            const url = "{{ route('interview-candidates.store') }}";
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
