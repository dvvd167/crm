<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [destroy] process for the files
 * controller
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Files;
use Illuminate\Contracts\Support\Responsable;

class DestroyResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * remove the item from the view
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //hide and remove all deleted rows
        foreach ($allrows as $id) {
            $jsondata['dom_visibility'][] = array(
                'selector' => '#file_' . $id,
                'action' => 'slideup-slow-remove',
            );
        }

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);

    }

}
