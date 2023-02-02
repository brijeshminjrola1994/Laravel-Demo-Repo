<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\DashboardWidget;
use App\Models\InterviewCandidate;
use App\Traits\ClientDashboard;
use App\Traits\ClientPanelDashboard;
use App\Traits\CurrencyExchange;
use App\Traits\EmployeeDashboard;
use App\Traits\FinanceDashboard;
use App\Traits\RecruitmentDashboard;
use App\Traits\InterviewDashboard;
use App\Traits\HRDashboard;
use App\Traits\OverviewDashboard;
use App\Traits\ProjectDashboard;
use App\Traits\TicketDashboard;
use Froiden\Envato\Traits\AppBoot;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends AccountBaseController
{
    use AppBoot, CurrencyExchange, OverviewDashboard, EmployeeDashboard, ProjectDashboard, ClientDashboard, HRDashboard, TicketDashboard, FinanceDashboard, RecruitmentDashboard, InterviewDashboard ,  ClientPanelDashboard;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.dashboard';
        $this->middleware(function ($request, $next) {
            // /dd(user()->permission('view_overview_dashboard'));
            $this->viewOverviewDashboard = user()->permission('view_overview_dashboard');
            $this->viewProjectDashboard = user()->permission('view_project_dashboard');
            $this->viewClientDashboard = user()->permission('view_client_dashboard');
            $this->viewHRDashboard = user()->permission('view_hr_dashboard');
            $this->viewTicketDashboard = user()->permission('view_ticket_dashboard');
            $this->viewFinanceDashboard = user()->permission('view_finance_dashboard');
            $this->viewRecruitmentDashboard = user()->permission('view_recruitment_dashboard');
            return $next($request);
        });

    }

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response|mixed|void
     */
    public function index( Request $request)
    {  
        if (in_array('admin', user_roles()) || in_array('HR', user_roles()) || in_array('dashboards', user_modules())) {
            $this->isCheckScript();
            $tab = request('tab');
            
            if(in_array('HR', user_roles()) && empty($tab)){
                $tab = 'hr';
            }
           
            switch ($tab) {
            case 'project':
                $this->projectDashboard();
                break;
            case 'client':
                $this->clientDashboard();
                break;
            case 'hr':
                $this->hrDashboard();
                break;
            case 'ticket':
                $this->ticketDashboard();
                break;
            case 'finance':
                $this->financeDashboard();
                break;
            case 'recruitment':
                $this->recruitmentDashboard();
            break;
            case 'interview':
                return  $this->interviewDashboard($request);    
                break;
            break;
            
            default:
                $this->overviewDashboard();
                break;
            }

            if (request()->ajax()) {
                $html = view($this->view, $this->data)->render();
                return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
            }

            $this->activeTab = ($tab == '') ? 'overview' : $tab;
            
            // List Of candidate where Joining date is today
            $this->selectedCandidate = InterviewCandidate::allSelectedCandidates();
            return view('dashboard.admin', $this->data);
        }

        if (in_array('employee', user_roles())) {
            return $this->employeeDashboard();
        }
         if (in_array('BA', user_roles())) {
            $tab = request('tab');
             switch ($tab) {
            case 'interview':
                return  $this->interviewDashboard($request);    
                break;
            default:
                return $this->employeeDashboard();
                break;
            }
        // /    return $this->employeeDashboard();
        }

        if (in_array('project-manager', user_roles()) || in_array('Account', user_roles()) || in_array('BDM', user_roles()) || in_array('bde', user_roles()) || in_array('lead-generator', user_roles())) {
            return $this->employeeDashboard();
        }

        if (in_array('client', user_roles())) {
            return $this->clientPanelDashboard();
        }
    }

    public function widget(Request $request, $dashboardType)
    {
        $data = $request->all();
        unset($data['_token']);
        DashboardWidget::where('status', 1)->where('dashboard_type', $dashboardType)->update(['status' => 0]);

        foreach ($data as $key => $widget) {
            DashboardWidget::where('widget_name', $key)->where('dashboard_type', $dashboardType)->update(['status' => 1]);
        }

        return Reply::success(__('messages.updatedSuccessfully'));
    }

    public function checklist()
    {
        if (in_array('admin', user_roles())) {
            $this->isCheckScript();
            return view('dashboard.checklist', $this->data);
        }
    }

    /**
     * @return array|\Illuminate\Http\Response
     */
    public function memberDashboard()
    {
        // abort_403 (!in_array('employee', user_roles()));
        return $this->employeeDashboard();
    }
 
    public function accountUnverified()
    {
        return view('dashboard.unverified', $this->data);
    }

}
