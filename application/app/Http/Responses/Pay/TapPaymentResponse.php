<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [tap] process for the pay
 * controller
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Pay;
use Illuminate\Contracts\Support\Responsable;

class TapPaymentResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for invoices
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //generate paynow button
        $html = view('pages/pay/tap', compact('tap', 'invoice'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#invoice-paynow-buttons-container',
            'action' => 'replace',
            'value' => $html);

        //response
        return response()->json($jsondata);
    }

}
