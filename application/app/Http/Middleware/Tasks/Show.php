<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [show] precheck processes for tasks
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Tasks;
use App\Models\Task;
use App\Permissions\TaskPermissions;
use App\Repositories\TaskRepository;
use Closure;
use Log;

class Show {

    protected $taskpermissions;
    protected $taskrepo;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(TaskRepository $taskrepo, TaskPermissions $taskpermissions) {

        //task permissions repo
        $this->taskpermissions = $taskpermissions;
        $this->taskrepo = $taskrepo;

    }

    /**
     * Check user permissions to edit a task
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //validate module status
        if (!config('visibility.modules.tasks')) {
            abort(404, __('lang.the_requested_service_not_found'));
            return $next($request);
        }

        //task id
        $task_id = $request->route('task');

        //does the task exist
        if ($task_id == '' || !$task = \App\Models\Task::Where('task_id', $task_id)->first()) {
            Log::error("task could not be found", ['process' => '[permissions][tasks][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'task id' => $task_id ?? '']);
            abort(404);
        }

        //dependency
        $this->dependency($task_id);

        //fronend
        $this->fronteEnd($task);

        //permission on each one
        if ($this->taskpermissions->check('view', $task_id)) {
            return $next($request);
        }

        //no items were passed with this request
        Log::error("permission denied", ['process' => '[permissions][tasks][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'task id' => $task_id ?? '']);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd($task = '') {

        //default visibilities
        config([
            'visibility.tasks_card_assigned' => true,
            'visibility.tasks_standard_features' => true, //things to show for tasks linked to project (not templates)
        ]);

        /**
         * shorten resource type and id (for easy appending in blade templates)
         * [usage]
         *   replace the usual url('task') with urlResource('task'), in blade templated
         * */
        if (request('taskresource_type') != '' || is_numeric(request('taskresource_id'))) {
            request()->merge([
                'resource_query' => 'ref=list&taskresource_type=' . request('taskresource_type') . '&taskresource_id=' . request('taskresource_id'),
            ]);
        } else {
            request()->merge([
                'resource_query' => 'ref=list',
            ]);
        }

        //show toggle archived tasks button
        if (auth()->user()->is_team) {
            config([
                'visibility.archived_tasks_toggle_button' => true,
            ]);
        }

        //permission on each one
        if ($this->taskpermissions->check('edit', $task)) {
            config([
                'visibility.task_editing_buttons' => true,
            ]);
        }

        //set
        if (auth()->user()->pref_filter_show_archived_tasks == 'yes') {
            request()->merge(['filter_show_archived_tasks' => 'yes']);
        }

        //hide elements for tasks linked to project templates
        if (is_numeric(request('taskresource_id')) && request('taskresource_id') < 0) {
            config([
                'visibility.tasks_standard_features' => false,
                'visibility.tasks_card_assigned' => false,
            ]);
        }

    }

    /*
     * various frontend and visibility settings
     */
    private function dependency($task_id = '') {

        //get the task with counts etc
        $tasks = $this->taskrepo->search($task_id);
        $task = $tasks->first();

        config([
            'visibility.task_is_locked' => false,
            'visibility.task_is_open' => true,
            'permission.manage_dependency' => false,
        ]);

        //task is locked from editing
        if ($task->count_dependency_cannot_start > 0) {
            config([
                'visibility.task_is_locked' => true,
                'visibility.task_is_open' => false,
            ]);
        }

        //permission to manage dependencies
        if ($this->taskpermissions->check('manage-dependencies', $task)) {
            config([
                'permission.manage_dependency' => true,
            ]);
        }

    }

}
