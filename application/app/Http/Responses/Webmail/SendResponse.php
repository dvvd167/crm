<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the emailing a client
 * controller
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Webmail;
use Illuminate\Contracts\Support\Responsable;

class SendResponse implements Responsable {

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

        //close modal
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //ajax response
        return response()->json($jsondata);
    }

}
