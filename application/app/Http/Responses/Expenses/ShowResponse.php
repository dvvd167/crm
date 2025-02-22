<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [show] process for the expenses
 * controller
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Expenses;
use Illuminate\Contracts\Support\Responsable;

class ShowResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //render the form
        $html = view('pages/expenses/components/modals/expense', compact('expense', 'attachments'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#plainModalBody',
            'action' => 'replace',
            'value' => $html);

        //ajax response
        return response()->json($jsondata);

    }

}
