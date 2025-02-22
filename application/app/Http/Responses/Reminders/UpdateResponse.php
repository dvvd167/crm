<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the reminder
 * controller
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Reminders;
use Illuminate\Contracts\Support\Responsable;

class UpdateResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for reminder members
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //replace the row of this record
        $html = view('pages/reminders/components/table/ajax', compact('reminders'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => "#reminder_" . $request->input('id'),
            'action' => 'replace-with',
            'value' => $html);

        //for own profile, replace user name in top nav
        if ($request->input('id') == auth()->id()) {
            $jsondata['dom_html'][] = array(
                'selector' => "#topnav_username",
                'action' => 'replace',
                'value' => safestr($request->input('first_name')));
        }

        //close modal
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);

    }

}
