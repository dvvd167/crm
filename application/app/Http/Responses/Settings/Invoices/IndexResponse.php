<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [index] process for the invoice settings
 * controller
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Settings\Invoices;
use Illuminate\Contracts\Support\Responsable;

class IndexResponse implements Responsable {

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

        $html = view('pages/settings/sections/invoices/page', compact('page', 'settings', 'settings2', 'next_id'))->render();

        $jsondata['dom_html'][] = array(
            'selector' => "#settings-wrapper",
            'action' => 'replace',
            'value' => $html);

        //left menu activate
        if (request('url_type') == 'dynamic') {
            $jsondata['dom_attributes'][] = [
                'selector' => '#settings-menu-billing',
                'attr' => 'aria-expanded',
                'value' => false,
            ];
            $jsondata['dom_action'][] = [
                'selector' => '#settings-menu-billing',
                'action' => 'trigger',
                'value' => 'click',
            ];
            $jsondata['dom_classes'][] = [
                'selector' => '#settings-menu-billing-invoice',
                'action' => 'add',
                'value' => 'active',
            ];
        }

        // POSTRUN FUNCTIONS------
        $jsondata['postrun_functions'][] = [
            'value' => 'NXSettingsInvoices',
        ];

        //ajax response
        return response()->json($jsondata);
    }
}
