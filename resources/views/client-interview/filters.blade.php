<style type="text/css">
    .select-box, .task-search {
  width: 30%;
}
.ml-0
{
    margin-left: 0px;
}
.full-width
{
    width: 100%;
}
.border-top-grey
{
    border-top: 1px solid #e8eef3;
}
.count-number
{
    margin-top: 4px;
    margin-left: 4px;
}
.dev-none
{
    display: none;
}
.schedule-none
{
    display: none;
}
.div-filter-width 
{
    width: 30% !important;
}
.div-heading
{
    width: 42% !important;
}
.redo-arrow
{
    margin-top: 4px;
    margin-left: 10px;
}
.chart-box
{
     width: 300px;
border: 2px solid gray;
padding: 50px;
margin: 20px;
height: 200px;
background-color: white;
border-radius: 10%;
top: 20px;
position: absolute;
right: 10px;
}
</style>
<x-filters.filter-box>
    <div class="row ml-0 full-width">
    <!-- DATE START -->
    <div class="select-box d-flex border-right-grey border-right-grey-sm-0">
        <p class="mb-0 pr-3 f-14 text-dark-grey d-flex align-items-center">@lang('app.date')</p>
        <div class="select-status d-flex">
            <input type="text" class="position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500"
                id="datatableRange" placeholder="@lang('placeholders.dateRange')">
        </div>
    </div>
    <!-- DATE END -->

    <!-- CLIENT START -->
    <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
        <p class="mb-0 pr-3 f-14 text-dark-grey d-flex align-items-center">@lang('app.menu.technology')</p>
        <div class="select-status">
            <select class="form-control select-picker" name="technology" id="technology" data-live-search="true"
                    data-size="8">
                <option value="all">@lang('modules.lead.all')</option>

                @foreach ($technology as $tech)
                    <option value="{{ $tech->id }}">{{ ucfirst($tech->name) }}</option>
                @endforeach

            </select>
        </div>
    </div>
    <!-- CLIENT END -->

    <!-- STATUS START -->
    <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
        <p class="mb-0 pr-3 f-14 text-dark-grey d-flex align-items-center">@lang('modules.client_interview.status')</p>
        <div class="select-status">
            <select class="form-control select-picker" name="status" id="status" data-live-search="true"
                    data-size="8">
                <option value="all">@lang('modules.lead.all')</option>
                <option value="Scheduled">@lang('modules.client_interview.Scheduled')</option>
                <option value="Cleared">@lang('modules.client_interview.cleared')</option>
                <option value="Cancelled">Cancelled</option>
                <option value="Rejected">@lang('modules.client_interview.Rejected')</option>
                <option value="Awaiting">@lang('modules.client_interview.awaiting')</option>

            </select>
        </div>
    </div>
    <!--  STATUS END -->

        <!--  SHECDULED START -->
       
        <!--  SHECDULED END -->
     <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
        <p class="mb-0 pr-3 f-14 text-dark-grey d-flex align-items-center">@lang('app.country')</p>                
        <div class="">
                    <div class="select-others">
                    <select class="form-control select-picker" name="country_id" id="country_id" data-live-search="true"
                    data-size="8">
                    <option value="all">@lang('app.all')</option>
                   @foreach ($countries as $item)
                                            <option data-tokens="{{ $item->iso3 }}" data-content="<span
                                            class='flag-icon flag-icon-{{ strtolower($item->iso) }} flag-icon-squared'></span>
                                        {{ $item->nicename }}" value="{{ $item->id }}">{{ $item->nicename }}</option>
                                        @endforeach
                </select>
                    </div>
                </div>
    </div>

    <!-- SEARCH BY TASK START -->
    <div class="task-search d-flex  py-1 px-lg-3 px-0 border-right-grey align-items-center">
        <form class="w-100 mr-1 mr-lg-0 mr-md-1 ml-md-1 ml-0 ml-lg-0">
            <div class="input-group bg-grey rounded">
                <div class="input-group-prepend">
                    <span class="input-group-text border-0 bg-additional-grey">
                        <i class="fa fa-search f-13 text-dark-grey"></i>
                    </span>
                </div>
                <input type="text" class="form-control f-14 p-1 border-additional-grey" id="search-text-field"
                    placeholder="@lang('app.search')">
            </div>
        </form>
    </div>
    <!-- SEARCH BY TASK END -->

    <!-- RESET START -->
    <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
        <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
            @lang('app.clearFilters')
        </x-forms.button-secondary>
    </div>
    <!-- RESET END -->

  </div>
  <br>
  <div class="row ml-0 full-width border-top-grey ">
       <!--  SHECDULED START -->
    <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0 div-filter-width ">
        <p class="mb-0 pr-3 f-14 text-dark-grey d-flex align-items-center div-heading">Developer Name</p>                
        <div class="">
                    <div class="select-others">
                    <select class="form-control select-picker" name="developer_name" id="developer_name" data-live-search="true"
                    data-size="8">
                    <option value="all">@lang('app.all')</option>
                   @foreach ($employees as $emp)
                        <option  value="{{ $emp->name }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
                    </div>
                </div>
                <div class="redo dev-clear redo-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/> <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/> </svg>
                </div>
    </div>
    <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0 div-filter-width ">
        <p class="mb-0 pr-3 f-14 text-dark-grey d-flex align-items-center div-heading">@lang('modules.client_interview.schedule_by')</p>                
        <div class="">
                    <div class="select-others">
                    <select class="form-control select-picker" name="schedule_by" id="schedule_by" data-live-search="true"
                    data-size="8">
                    <option value="all">@lang('app.all')</option>
                   @foreach ($schedule_by as $emp)
                        <option  value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                    <option value="0">Manektech</option>
                </select>
                    </div>
                </div>
                <div class="redo schedule-clear redo-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/> <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/> </svg>
                </div>
    </div>
  
   
   
  </div>
  <div class="row ml-0 full-width border-top-grey ">
       <!--  SHECDULED START -->
    
    <!--  SHECDULED END -->
    <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0 rejected">
        <p class="mb-0  f-14 text-dark-grey d-flex align-items-center">Awaiting : </p>                
        <div class="count-number scheduled-awaiting">
                    0
        </div>
    </div>
    <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0 rejected">
        <p class="mb-0  f-14 text-dark-grey d-flex align-items-center">Scheduled : </p>                
        <div class="count-number scheduled-scheduled">
                    0
        </div>
    </div>
    <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0 cleared ">
        <p class="mb-0  f-14 text-dark-grey d-flex align-items-center">Cleared : </p>                
        <div class="count-number scheduled-cleared">
                    0
        </div>
    </div>
    <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0 rejected">
        <p class="mb-0  f-14 text-dark-grey d-flex align-items-center">Rejected : </p>                
        <div class="count-number scheduled-rejected">
                    0
        </div>
    </div>
    <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0 rejected">
        <p class="mb-0  f-14 text-dark-grey d-flex align-items-center">Cancelled : </p>                
        <div class="count-number scheduled-cancelled">
                    0
        </div>
    </div>
    <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0 rejected">
        <p class="mb-0  f-14 text-dark-grey d-flex align-items-center">Total : </p>                
        <div class="count-number scheduled-total">
                    0
        </div>
    </div>
    
      
    <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
        <x-forms.button-secondary class="btn-xs  schedule-none" id="reset-filters" icon="times-circle">
            @lang('app.clearFilters')
        </x-forms.button-secondary>
    </div>
  </div>
  
  
</x-filters.filter-box>
<input type="hidden" id="shecduled_by_url" name="" value="{{url('/account/client-interview-count')}}">
@push('scripts')
    <script>
        
         $('.dev-clear').on('click ', function() {
            $('.filter-box #developer_name').val('all');
            $('.filter-box .select-picker').selectpicker("refresh");
           
             showTable();
         });
         $('.schedule-clear').on('click ', function() {
            $('.filter-box #schedule_by').val('all');
            $('.filter-box .select-picker').selectpicker("refresh");
           
             showTable();
         });
        $('#search-text-field, #technology,#status,#country_id,#schedule_by , #developer_name')
            .on('change keyup', function() {
                if ($('#search-text-field').val() != "") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#technology').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#status').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#country_id').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#developer_name').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#schedule_by').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                }else {
                    $('#reset-filters').addClass('d-none');
                    showTable();
                }
            });

            $('#developer_name').on('change ', function() {
            });

        $('#developer_name').on('change ', function() {
            var developer_name  =   $('#developer_name').val() ;
            if(developer_name == "all")
            {
                developer_name = null;
            }
            var url             =   $('#shecduled_by_url').val();
            var token           =   "{{ csrf_token() }}";
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
            if (  developer_name != "all") {
                $.easyAjax({
                    type: 'POST',
                    url: url,
                    blockUI: true,
                    data: {
                        '_token': token,
                        'developer_name': developer_name,
                        'startDate' :startDate,
                        'endDate':endDate,
                        'searchText':searchText,
                        'technology':technology,
                        'status':status,
                        'country_id':country_id
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
        });

        $('#schedule_by').on('change ', function() {
            var schedule_by =   $('#schedule_by').val() ; 
            if(schedule_by == "all")
            {
                schedule_by = null;
            }
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
                        'country_id':country_id
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
            
        });


        $('#reset-filters').click(function() {
            $('#filter-form')[0].reset();

            $('.filter-box #status').val('not finished');
            $('.filter-box .select-picker').selectpicker("refresh");
            $('#reset-filters').addClass('d-none');
            showTable();
        });

        $('#reset-filters-2').click(function() {
            $('#filter-form')[0].reset();

            $('.filter-box #status').val('not finished');
            $('.filter-box .select-picker').selectpicker("refresh");
            $('#reset-filters').addClass('d-none');
            showTable();
        });
    </script>
@endpush
