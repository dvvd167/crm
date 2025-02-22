<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [show] process for the proposals
 * controller
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Documents;
use Illuminate\Contracts\Support\Responsable;

class ShowEditResponse implements Responsable {

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

        $payload = $this->payload;

        return view('pages/documents/editing/page', compact('page', 'document', 'payload', 'categories','customfields', 'estimate'))->render();
    }

}
