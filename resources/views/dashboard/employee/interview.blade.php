@extends('layouts.app')

@push('styles')
    
        <link rel="stylesheet" href="{{ asset('vendor/full-calendar/main.min.css') }}">
  
     @include('sections.daterange_css')
@endpush

        <link rel="stylesheet" href="{{ asset('vendor/full-calendar/main.min.css') }}">
        <script src="{{ asset('vendor/jquery/frappe-charts.min.iife.js') }}"></script>

        <script src="{{ asset('vendor/jquery/Chart.min.js') }}"></script>
@section('filter-section')

<!-- FILTER START -->
<!-- DASHBOARD HEADER START -->
<div class="d-flex filter-box project-header bg-white dashboard-header">

    <div class="mobile-close-overlay w-100 h-100" id="close-client-overlay"></div>
    <div class="project-menu d-lg-flex" id="mob-client-detail">

        <a class="d-none close-it" href="javascript:;" id="close-client-detail">
            <i class="fa fa-times"></i>
        </a>

       @if ($viewOverviewDashboard == 'all')
            <x-tab :href="route('dashboard').'?tab=overview'" :text="__('modules.projects.overview')"
                class="overview" ajax="false" />
        @endif

        @if ($viewProjectDashboard == 'all')
            <x-tab :href="route('dashboard').'?tab=project'" :text="__('app.project')" class="project"
                ajax="false" />
        @endif

        @if ($viewClientDashboard == 'all')
            <x-tab :href="route('dashboard').'?tab=client'" :text="__('app.client')" class="client"
                ajax="false" />
        @endif

        @if ($viewHRDashboard == 'all')
            <x-tab :href="route('dashboard').'?tab=hr'" :text="__('app.menu.hr')" class="hr" ajax="false" />
        @endif

        @if ($viewTicketDashboard == 'all')
            <!-- <x-tab :href="route('dashboard').'?tab=ticket'" :text="__('app.menu.ticket')" class="ticket"
                ajax="false" /> -->
        @endif

        @if ($viewFinanceDashboard == 'all')
            <x-tab :href="route('dashboard').'?tab=finance'" :text="__('app.menu.finance')" class="finance"
                ajax="false" />
        @endif

         @if (in_array('admin', user_roles()))
             <x-tab :href="route('dashboard').'?tab=interview'" :text="__('app.menu.interview')"
                class="interview" ajax="false" />
                <x-tab :href="route('dashboard').'?tab=recruitment'" :text="__('app.menu.recruitment')" class="recruitment"
            ajax="false" />
        @endif
        <!--  CUSTOM BA ROLE-->
         @if (in_array('BA', user_roles()))
        <x-tab :href="route('dashboard').'?tab=overview'" :text="__('modules.projects.overview')"
                class="overview" ajax="false" />
                <x-tab :href="route('dashboard').'?tab=interview'" :text="__('app.menu.interview')"
                class="interview" ajax="false" />
                
        @endif
    </div>


    <div class="ml-auto d-flex align-items-center justify-content-center ">

        <!-- DATE START -->
        <div
            class="{{ (request('tab') == 'overview' || request('tab') == '') && !in_array('HR', user_roles()) ? 'd-none' : 'd-flex' }} align-items-center border-left-grey border-left-grey-sm-0 h-100 pl-4">
            <i class="fa fa-calendar-alt mr-2 f-14 text-dark-grey"></i>
            <div class="select-status">
                <input type="text" class="position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500"
                    id="datatableRange2" placeholder="@lang('placeholders.dateRange')">
            </div>
        </div>
        <!-- DATE END -->
        
    </div>

    <a class="mb-0 d-block d-lg-none text-dark-grey mr-2 border-left-grey border-bottom-0"
        onclick="openClientDetailSidebar()"><i class="fa fa-ellipsis-v"></i></a>

</div>
<!-- FILTER END -->
<!-- DASHBOARD HEADER END -->

@endsection

@section('content')
<div class="admin-dashboard">
<div class="modal fade edit_interview_schedule_modal" id="edit_schedule_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display:none;">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-dynamic-content"></div>
    </div>
</div>

    <!-- CONTENT WRAPPER START -->
    <div class="px-4 py-2 border-top-0 ">
     
        <!-- WELOCOME END -->
        <!-- EMPLOYEE DASHBOARD DETAIL START -->
        <div class="row emp-dash-detail">
             <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <a href="javascript:;" id="total-candidate-joining">
                    <x-cards.widget :title="__('app.total')" :value="$totalInterview"
                :info="__('app.total')" icon="users" />
                </a>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <a href="javascript:;" id="total-candidate-joining">
                    <x-cards.widget :title="__('app.menu.passed')" :value="$passedInterview"
                :info="__('app.menu.passed')" icon="users" />
                </a>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <a href="javascript:;" id="total-candidate-joining">
                    <x-cards.widget :title="__('app.menu.failed')" :value="$failedInterview"
                :info="__('app.menu.failed')" icon="users" />
                </a>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <a href="javascript:;" id="total-candidate-joining">
                    <x-cards.widget :title="__('app.menu.awaiting_for_feedback')" :value="$awaitingInterview"
                :info="__('app.menu.awaiting_for_feedback')" icon="users" />
                </a>
            </div> 
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <a href="javascript:;" id="total-candidate-joining">
                    <x-cards.widget :title="__('app.menu.scheduled')" :value="$scheduled"
                :info="__('app.menu.scheduled')" icon="users" />
                </a>
            </div>    

            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <a href="javascript:;" id="total-candidate-joining">
                    <x-cards.widget :title="__('app.menu.canceled')" :value="$cancelledInterview"
                :info="__('app.menu.canceled')" icon="users" />
                </a>
            </div>
            
            
            
            <!-- EMP DASHBOARD TASKS PROJECTS EVENTS END -->
        </div>
        <div class="row">
             <div class="col-sm-12 col-lg-6 mt-3">
                <x-cards.data :title="__('modules.dashboard.skilltWiseInterview')">
                    <x-pie-chart id="task-chart3" :labels="$skillWiseChartOfCandidate['labels']"
                        :values="$skillWiseChartOfCandidate['values']" :colors="$skillWiseChartOfCandidate['colors'] ?? null" height="300" width="300" />
                </x-cards.data>
            </div>
            <div class="col-sm-12 col-lg-6 mt-3">
                <x-cards.data :title="__('modules.dashboard.developertWiseInterview')">
                    <x-pie-chart id="task-chart2" :labels="$developerWiseChartOfCandidate['labels']"
                        :values="$developerWiseChartOfCandidate['values']" :colors="$developerWiseChartOfCandidate['colors'] ?? null" height="300" width="300" />
                </x-cards.data>
            </div>
        </div>

        
        <!-- EMPLOYEE DASHBOARD DETAIL END -->
    </div>
    <!-- CONTENT WRAPPER END -->
    </div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/full-calendar/main.min.js') }}"></script>
        <script src="{{ asset('vendor/full-calendar/locales-all.min.js') }}"></script>
<script src="{{ asset('vendor/jquery/daterangepicker.min.js') }}"></script>
<script type="text/javascript">
    $(function() {
        var format = '{{ company()->date_format }}';
        var startDate = "{{ $startDate->format(company()->date_format) }}";
        var endDate = "{{ $endDate->format(company()->date_format) }}";
        var picker = $('#datatableRange2');
        var start = moment(startDate, format);
        var end = moment(endDate, format);
        console.log(end);

        function cb(start, end) {
            $('#datatableRange2').val(start.format('{{ company()->moment_date_format }}') +
                ' @lang("app.to") ' + end.format(
                    '{{ company()->moment_date_format }}'));
            $('#reset-filters').removeClass('d-none');
        }

        $('#datatableRange2').daterangepicker({
            locale: daterangeLocale,
            linkedCalendars: false,
            startDate: start,
            endDate: end,
            ranges: daterangeConfig,
            opens: 'left',
            parentEl: '.dashboard-header'
        }, cb);


        $('#datatableRange2').on('apply.daterangepicker', function(ev, picker) {
            
            showTable();
        });

    });
</script>


<script>
    $(".dashboard-header").on("click", ".ajax-tab", function(event) {
        event.preventDefault();

        $('.project-menu .p-sub-menu').removeClass('active');
        $(this).addClass('active');

        // var dateRangePicker = $('#datatableRange2').data('daterangepicker');
        // var startDate = $('#datatableRange').val();

        // if (startDate == '') {
        //     startDate = null;
        //     endDate = null;
        // } else {
        //     startDate = dateRangePicker.startDate.format('{{ company()->moment_date_format }}');
        //     endDate = dateRangePicker.endDate.format('{{ company()->moment_date_format }}');
        // }

        const requestUrl = this.href;

        $.easyAjax({
            url: requestUrl,
            blockUI: true,
            container: ".admin-dashboard",
            historyPush: true,
            data: {
                startDate: startDate,
                endDate: endDate
            },
            blockUI: true,
            success: function(response) {
                if (response.status == "success") {
                    $('.admin-dashboard').html(response.html);
                    init('.admin-dashboard');
                }
            }
        });
    });

    $('.keep-open .dropdown-menu').on({
        "click": function(e) {
            e.stopPropagation();
        }
    });

    function showTable() {
        // var dateRangePicker = $('#datatableRange2').data('daterangepicker');
        // var startDate = $('#datatableRange').val();

        // if (startDate == '') {
        //     startDate = null;
        //     endDate = null;
        // } else {
        //     startDate = dateRangePicker.startDate.format('{{ company()->moment_date_format }}');
        //     endDate = dateRangePicker.endDate.format('{{ company()->moment_date_format }}');
        // }

        const requestUrl = '{{url("/account/dashboard?tab=interview")}}';
     
      $.ajax({
        url: requestUrl,
        type:"get", 
        data: {
                startDate: startDate,
                endDate: endDate
            },
        success: function(result){
        $('.admin-dashboard').html(result);
      }});
        
    }
</script>
<script>
    const activeTab = "intervew";
    $('.project-menu .' + activeTab).addClass('active');
</script>
   
@endpush
