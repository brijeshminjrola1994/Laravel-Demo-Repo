@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
@endpush

@section('filter-section')

    @include('client-interview.filters')

@endsection

@php
$addLeadPermission = user()->permission('add_lead');
$addLeadCustomFormPermission = user()->permission('manage_lead_custom_forms');
@endphp

@section('content')
   
    <!-- CONTENT WRAPPER START -->
     @if (isset($data['chart']))
      <div class="chart-box">

       
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('modules.dashboard.skillWiseCandidate')">
                <x-pie-chart id="task-chart3" :labels="$data['chart']['labels']"
                    :values="$data['chart']['values']" :colors="$data['chart']['colors'] ?? null" height="300" width="300" />
            </x-cards.data>
        </div>
    
    </div>
    @endif
    <div class="content-wrapper" >
        <!-- Add Task Export Buttons Start -->
        <div class="d-block d-lg-flex d-md-flex justify-content-between action-bar">
            <div id="table-actions" class="flex-grow-1 align-items-center">
                    <x-forms.link-primary :link="route('client-interview.create')" class="mr-3 openRightModal float-left mb-2 mb-lg-0 mb-md-0" icon="plus">
                        @lang('app.add')
                        @lang('app.menu.client_interviews')
                    </x-forms.link-primary>
                
            </div>

            <x-datatable.actions>
                <div class="select-status mr-3 pl-3">
                    <select name="action_type" class="form-control select-picker" id="quick-action-type" disabled>
                        <option value="">@lang('app.selectAction')</option>
                        <!-- <option value="change-status">@lang('modules.tasks.changeStatus')</option> -->
                        <option value="delete">@lang('app.delete')</option>
                    </select>
                </div>
               
            </x-datatable.actions>
        </div>
       
        <!-- Add Task Export Buttons End -->
        <!-- Task Box Start -->
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">

            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}

        </div>
        <!-- Task Box End -->
    </div>
    <!-- CONTENT WRAPPER END -->

@endsection

@push('scripts')
    @include('sections.datatable_js')

    <script>
        $("svg.bold-row").parents('tr').css("color", "green");
        $('#client-interview-table').on('preXhr.dt', function(e, settings, data) {

            var dateRangePicker = $('#datatableRange').data('daterangepicker');
            var startDate = $('#datatableRange').val();

            if (startDate == '') {
                startDate = null;
                endDate = null;
            } else {
                // startDate = dateRangePicker.startDate.format('{{ $global->moment_date_format }}');
                // endDate = dateRangePicker.endDate.format('{{ $global->moment_date_format }}');
                startDate = dateRangePicker.startDate.format('{{ company()->moment_date_format }}');
                endDate = dateRangePicker.endDate.format('{{ company()->moment_date_format }}');
            }
            data['startDate'] = startDate;
            data['endDate'] = endDate;
            
            var searchText = $('#search-text-field').val();
            var technology = $('#technology').val();
            var status = $('#status').val();
            var country_id = $('#country_id').val();
            var schedule_by = $('#schedule_by').val();
            var developer_name = $('#developer_name').val();
            
            data['searchText'] = searchText;
            data['technology'] = technology;
            data['status'] = status;
            data['country_id'] = country_id;
            data['schedule_by'] = schedule_by;
            data['developer_name'] = developer_name;
        });

        const showTable = () => {
            window.LaravelDataTables["client-interview-table"].draw();
             var schedule_by =   $('#schedule_by').val() ; 
           
            var url         =   $('#shecduled_by_url').val();
            var token       =   "{{ csrf_token() }}";
            var dateRangePicker = $('#datatableRange').data('daterangepicker');
            var startDate = $('#datatableRange').val();

            if (startDate == '') {
                startDate = null;
                endDate = null;
            } else {
                startDate = dateRangePicker.startDate.format('{{ $global->moment_date_format }}');
                endDate = dateRangePicker.endDate.format('{{ $global->moment_date_format }}');
            }
            var searchText = $('#search-text-field').val();
            var technology = $('#technology').val();
            var status = $('#status').val();
            var country_id = $('#country_id').val();
            var developer_name  =   $('#developer_name').val() ;
            $.easyAjax({
                    type: 'POST',
                    url: url,
                    blockUI: true,
                    data: {
                        '_token': token,
                        'schedule_by': schedule_by,
                        'startDate' :startDate,
                        'endDate':endDate,
                        'searchText':searchText,
                        'technology':technology,
                        'status':status,
                        'country_id':country_id,
                        'developer_name': developer_name,
                    },
                    success: function(response) {
                        $('.scheduled-cleared').html(response.cleared);
                        $('.scheduled-rejected').html(response.rejected);
                        $('.scheduled-awaiting').html(response.awaiting);
                        $('.scheduled-scheduled').html(response.scheduled);
                        $('.scheduled-cancelled').html(response.cancelled);
                        $('.scheduled-total').html(response.total);
                    }
                });
            
        }

        $('#quick-action-type').change(function() {
            const actionValue = $(this).val();
            if (actionValue != '') {
                $('#quick-action-apply').removeAttr('disabled');

                if (actionValue == 'change-status') {
                    $('.quick-action-field').addClass('d-none');
                    $('#change-status-action').removeClass('d-none');
                } else {
                    $('.quick-action-field').addClass('d-none');
                }
            } else {
                $('#quick-action-apply').attr('disabled', true);
                $('.quick-action-field').addClass('d-none');
            }
        });

        $('#quick-action-apply').click(function() {
            const actionValue = $('#quick-action-type').val();
            if (actionValue == 'delete') {
                Swal.fire({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.recoverRecord')",
                    icon: 'warning',
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: "@lang('messages.confirmDelete')",
                    cancelButtonText: "@lang('app.cancel')",
                    customClass: {
                        confirmButton: 'btn btn-primary mr-3',
                        cancelButton: 'btn btn-secondary'
                    },
                    showClass: {
                        popup: 'swal2-noanimation',
                        backdrop: 'swal2-noanimation'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        applyQuickAction();
                    }
                });

            } else {
                applyQuickAction();
            }
        });

        $('body').on('click', '.delete-table-row', function() {
            var id = $(this).data('client-interview-id');
            Swal.fire({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.recoverRecord')",
                icon: 'warning',
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "@lang('messages.confirmDelete')",
                cancelButtonText: "@lang('app.cancel')",
                customClass: {
                    confirmButton: 'btn btn-primary mr-3',
                    cancelButton: 'btn btn-secondary'
                },
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('client-interview.destroy', ':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {
                            '_token': token,
                            '_method': 'DELETE'
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                showTable();
                            }
                        }
                    });
                }
            });
        });

        const applyQuickAction = () => {
            var rowdIds = $("#client-interview-table input:checkbox:checked").map(function() {
                return $(this).val();
            }).get();

            var url = "{{ route('client-interview.apply_quick_action') }}?row_ids=" + rowdIds;

            $.easyAjax({
                url: url,
                container: '#quick-action-form',
                type: "POST",
                disableButton: true,
                buttonSelector: "#quick-action-apply",
                data: $('#quick-action-form').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        showTable();
                        resetActionButtons();
                        deSelectAll();
                    }
                }
            })
        };

        // $('#client-interview-table').on('change', '.change-status', function() {
        //     var url = "{{ route('leads.change_status') }}";
        //     var token = "{{ csrf_token() }}";
        //     var id = $(this).data('task-id');
        //     var status = $(this).val();

        //     if (id != "" && status != "") {
        //         $.easyAjax({
        //             url: url,
        //             type: "POST",
        //             data: {
        //                 '_token': token,
        //                 taskId: id,
        //                 status: status,
        //                 sortBy: 'id'
        //             },
        //             success: function(data) {
        //                 showTable();
        //                 resetActionButtons();
        //                 deSelectAll();
        //             }
        //         });

        //     }
        // });

        function changeStatus(leadID, statusID) {

            var url = "{{ route('leads.change_status') }}";
            var token = "{{ csrf_token() }}";

            $.easyAjax({
                type: 'POST',
                url: url,
                data: {
                    '_token': token,
                    'leadID': leadID,
                    'statusID': statusID
                },
                success: function(response) {
                    if (response.status == "success") {
                        $.easyBlockUI('#client-interview-table');
                        $.easyUnblockUI('#client-interview-table');
                        showTable();
                        resetActionButtons();
                        deSelectAll();
                    }
                }
            });
        }

        function changeClientInterviewStatus(clientInterviewId) {
            var url = '{{ route('client-interview.changeStatus', ':id') }}';
            url = url.replace(':id', clientInterviewId);

            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        }

        $('body').on('click', '#add-lead', function() {
            window.location.href = "{{ route('lead-form.index') }}";
        });

        @if(!is_null(request('start')) && !is_null(request('end')))
            $('#datatableRange').val('{{ request("start") }}' +
                    ' @lang("app.to") ' + '{{ request("end") }}');
            showTable();
        @endif

    </script>
@endpush
