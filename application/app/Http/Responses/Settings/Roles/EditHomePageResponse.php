<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [edit] process for the roles settings
 * controller
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Settings\Roles;
use Illuminate\Contracts\Support\Responsable;

class EditHomePageResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for roles
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
        $html = view('pages/settings/sections/roles/modals/homepage', compact('page', 'role'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal barter
        $jsondata['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        // POSTRUN FUNCTIONS------
        $jsondata['postrun_functions'][] = [
            'value' => 'NXSettingsRolesHomePage',
        ];

        //ajax response
        return response()->json($jsondata);
    }

}
