<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the tasks
 * controller
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Tasks;
use Illuminate\Contracts\Support\Responsable;

class ActivateResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for team members
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        $jsondata = [];

        //update initiated on a list page
        if (request('ref') == 'list' || request('ref') == '') {

            //table view
            $html = view('pages/tasks/components/table/ajax', compact('tasks'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => "#task_" . $tasks->first()->task_id,
                'action' => 'replace-with',
                'value' => $html);


            //hide and show buttons
            $jsondata['dom_classes'][] = [
                'selector' => ".card_archive_button_" . $tasks->first()->task_id,
                'action' => 'remove',
                'value' => 'hidden',
            ];
            $jsondata['dom_classes'][] = [
                'selector' => ".card_restore_button_" . $tasks->first()->task_id,
                'action' => 'add',
                'value' => 'hidden',
            ];
            $jsondata['dom_visibility'][] = [
                'selector' => "#archived_icon_" . $tasks->first()->task_id,
                'action' => 'hide',
            ];
            $jsondata['dom_visibility'][] = [
                'selector' => "#card_archived_notice_" . $tasks->first()->task_id,
                'action' => 'hide',
            ];
            

            //notice
            $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));
        }

        //editing from main page
        if (request('ref') == 'page') {
            //session
            request()->session()->flash('success-notification', __('lang.request_has_been_completed'));
            //redirect to task page
            $jsondata['redirect_url'] = url("tasks/" . $tasks->first()->task_id);
        }

        //response
        return response()->json($jsondata);
    }

}
