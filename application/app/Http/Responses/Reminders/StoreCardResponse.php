<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the reminder
 * controller
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Reminders;
use Illuminate\Contracts\Support\Responsable;

class StoreCardResponse implements Responsable {

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

        $payload = $this->payload;

        $html = view('pages/reminders/cards/wrapper', compact('payload', 'reminder'))->render();
        $jsondata['dom_html'][] = [
            'selector' => '#card-reminders-container',
            'action' => 'replace',
            'value' => $html,
        ];

        //add to kanban view
        $html = view('pages/reminders/cards/kanban', compact('payload', 'reminder'))->render();
        $jsondata['dom_html'][] = [
            'selector' => "#reminder_card_view_container_$resource_id",
            'action' => 'replace',
            'value' => $html,
        ];

        //response
        return response()->json($jsondata);

    }

}
