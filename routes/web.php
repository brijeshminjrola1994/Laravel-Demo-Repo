<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\SubTaskController;
use App\Http\Controllers\TaskFileController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\InterviewCandidateController;


Route::group(['middleware' => 'auth', 'prefix' => 'account'], function () {
    Route::post('image/upload', [ImageController::class, 'store'])->name('image.store');

    Route::get('account-unverified', [DashboardController::class, 'accountUnverified'])->name('account_unverified');
    Route::get('checklist', [DashboardController::class, 'checklist'])->name('checklist');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard-advanced', [DashboardController::class, 'advancedDashboard'])->name('dashboard.advanced');
    Route::post('dashboard/widget/{dashboardType}', [DashboardController::class, 'widget'])->name('dashboard.widget');
    Route::post('dashboard/week-timelog', [DashboardController::class, 'weekTimelog'])->name('dashboard.week_timelog');

    /* Interview candidate */

    Route::group(['prefix' => 'appreciations'],function () {
        Route::post('interview-candidates-schedule/apply-quick-action', [InterviewCandidateController::class, 'applyQuickAction'])->name('interview-candidates-schedule.apply_quick_action');
        Route::post('interview-candidates/moveCandidateData', [InterviewCandidateController::class,'moveCandidateData'])->name('interview-candidates.moveCandidateData');
        Route::resource('interview-candidates', InterviewCandidateController::class);
        Route::post('interview-candidates-schedule/editschedule', [InterviewCandidateScheduleController::class,'editschedule']);
        Route::get('interview-candidates-schedule/editschedulePage/{id}', [InterviewCandidateScheduleController::class,'editschedulePage']);
        Route::resource('interview-candidates-schedule', InterviewCandidateScheduleController::class);
    });

    /* User Appreciation */
    Route::group(['prefix' => 'appreciations'],function () {
        Route::post('awards/apply-quick-action', [AwardController::class, 'applyQuickAction'])->name('awards.apply_quick_action');
        Route::post('awards/change-status/{id?}', [AwardController::class, 'changeStatus'])->name('awards.change-status');
        Route::get('awards/quick-create', [AwardController::class, 'quickCreate'])->name('awards.quick-create');
        Route::post('awards/quick-store', [AwardController::class, 'quickStore'])->name('awards.quick-store');
        Route::resource('awards', AwardController::class);
    });
    

    /* Tasks */
    Route::group(['prefix' => 'tasks'], function () {

        Route::resource('task-label', TaskLabelController::class);
        Route::resource('taskCategory', TaskCategoryController::class);
        Route::resource('taskComment', TaskCommentController::class);
        Route::resource('task-note', TaskNoteController::class);

        // task files routes
        Route::get('task-files/download/{id}', [TaskFileController::class, 'download'])->name('task_files.download');
        Route::resource('task-files', TaskFileController::class);

        // Sub task routes
        Route::post('sub-task/change-status', [SubTaskController::class, 'changeStatus'])->name('sub_tasks.change_status');
        Route::resource('sub-tasks', SubTaskController::class);

        // Task files routes
        Route::get('sub-task-files/download/{id}', [SubTaskFileController::class, 'download'])->name('sub-task-files.download');
        Route::resource('sub-task-files', SubTaskFileController::class);

        // Taskboard routes
        Route::post('taskboards/collapseColumn', [TaskBoardController::class, 'collapseColumn'])->name('taskboards.collapse_column');
        Route::post('taskboards/updateIndex', [TaskBoardController::class, 'updateIndex'])->name('taskboards.update_index');
        Route::get('taskboards/loadMore', [TaskBoardController::class, 'loadMore'])->name('taskboards.load_more');
        Route::resource('taskboards', TaskBoardController::class);

        Route::resource('task-calendar', TaskCalendarController::class);
    });

    /* Follow Ups */

    Route::group(['prefix' => 'follow-ups'], function () {
        Route::get('follow-ups',[FollowUpController::class,'index'])->name('follow-ups');

        Route::get('follow-ups/show-client/{id}', [FollowUpController::class, 'showClient'])->name('follow-ups.showClient');
        Route::get('follow-ups/show-lead/{id}', [FollowUpController::class, 'showLead'])->name('follow-ups.showLead');

        Route::delete('follow-ups/delete-client/{id}', [FollowUpController::class, 'client_destroy'])->name('follow-ups.client_destroy');
        Route::delete('follow-ups/delete-lead/{id}', [FollowUpController::class, 'lead_destroy'])->name('follow-ups.lead_destroy');

        Route::post('follow-ups/client-update', [FollowUpController::class, 'clientUpdateFollow'])->name('follow-ups.clientUpdateFollow');
        Route::post('follow-ups/lead-update', [FollowUpController::class, 'leadUpdateFollow'])->name('follow-ups.leadUpdateFollow');

        Route::resource('follow-ups', FollowUpController::class);
    });

});


