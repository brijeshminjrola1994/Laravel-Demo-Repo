
<div id="holiday-detail-section">
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-white border-0 b-shadow-4">
                <div class="card-header bg-white  border-bottom-grey text-capitalize justify-content-between p-20">
                    <div class="row">
                        <div class="col-lg-10 col-10">
                            <h3 class="heading-h1 mb-3">@lang('app.menu.client_interviews') @lang('app.details')</h3>
                        </div>
                        <div class="col-lg-2 col-2 text-right">
                            <div class="dropdown">
                                <button
                                    class="btn btn-lg f-14 px-2 py-1 text-dark-grey text-capitalize rounded  dropdown-toggle"
                                    type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="history.back();">
                                    <i class="fa fa-arrow-left"></i>
                                </button>

                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="float: left; width: 50%;" >
                    <x-cards.data-row :label="__('modules.client_interview.client_name')" :value="$clientInterview->client_name" html="true" />
                    <x-cards.data-row :label="__('modules.client_interview.company_name')" :value="$clientInterview->company_name" html="true" />
                    <x-cards.data-row :label="__('app.country')" @if(isset($country) &&  isset($country['name'])) :value="$country['name']" @endif html="true" />
                    <x-cards.data-row :label="__('modules.client_interview.developer_name')" :value="$clientInterview->developer_name" html="true" />
                    <x-cards.data-row :label="__('modules.client_interview.budget')" :value="$clientInterview->budget" html="true" />
                    <x-cards.data-row :label="__('modules.client_interview.status')" :value="$clientInterview->status" html="true" />
                    <x-cards.data-row :label="__('modules.client_interview.technology')" :value="$technology->name" html="true" />
                    <x-cards.data-row :label="__('modules.client_interview.schedule_by')" @if($employees == null)  :value="ManekTeck"  @else :value="$employees->name" @endif html="true"  />
                    <x-cards.data-row :label="__('modules.client_interview.feedback')" :value="$clientInterview->feedback" html="true" />
                    <x-cards.data-row :label="__('modules.client_interview.time')" :value="$clientInterview->time" html="true" />
                    <x-cards.data-row :label="__('modules.client_interview.date')" :value="date('d-m-Y',strtotime($clientInterview->date))" html="true" />
                    </div>
                    <div style="float: right;">
                        @foreach( $feedback as $feed)
                        <div style="float: left; margin-top:10px;" >
                         <div class="card" style="width: 18rem;">
                          <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Round {{$feed->round}}</h6>
                            <p class="card-text"> <span class="card-subtitle mb-2 text-muted ">Date : </span> {{$feed->date}}  </p>
                            <p class="card-text"> <span class="card-subtitle mb-2 text-muted ">Time : </span> {{$feed->time}}  </p>
                             <p class="card-text"> <span class="card-subtitle mb-2 text-muted "> Feedback : </span> {{$feed->feedback}}  </p> 
                             <p class="card-text"> <span class="card-subtitle mb-2 text-muted ">Internal Feedback : </span> {{$feed->internal_feedback}}  </p> 
                          </div>
                        </div>
                     </div>  
                     <br>
                        @endforeach
                    
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
    $('body').on('click', '.delete-client-interview', function() {
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
                var url = "{{ route('client-interview.destroy', $clientInterview->id) }}";

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
                            window.location.href = response.redirectUrl;
                        }
                    }
                });
            }
        });
    });
</script>
