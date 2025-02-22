<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Landlord\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\Landlord\FileRepository;
use Validator;

class Contact extends Controller {

    public function __construct() {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

    }

    /**
     * Display the dashboard home page
     * @return blade view | ajax view
     */
    public function edit() {

        $page = $this->pageSettings();

        //get section
        $section = \App\Models\Landlord\Frontend::Where('frontend_name', 'page-contact')->first();

        return view('landlord/frontend/contact/page', compact('page', 'section'))->render();

    }

    /**
     * show the form to create a new resource
     *
     * @return \Illuminate\Http\Response
     */
    public function update(FileRepository $filerepo) {

        //get the item
        $section = \App\Models\Landlord\Frontend::Where('frontend_name', 'page-contact')->first();

        //custom error messages
        $messages = [
            'frontend_data_1.required' => __('lang.heading') . ' - ' . __('lang.is_required'),
            'frontend_data_3.required' => __('lang.delivery_email_address') . ' - ' . __('lang.is_required'),
            'frontend_data_3.email' => __('lang.delivery_email_address') . ' - ' . __('lang.is_not_a_valid_email_address'),
            'frontend_data_4.required' => __('lang.submit_button_text') . ' - ' . __('lang.is_required'),
            'html_frontend_data_5.required' => __('lang.thank_you_message') . ' - ' . __('lang.is_required'),
        ];

        //validate
        $validator = Validator::make(request()->all(), [
            'frontend_data_1' => [
                'required',
            ],
            'frontend_data_3' => [
                'required',
                'email',
            ],
            'frontend_data_4' => [
                'required',
            ],
            'html_frontend_data_5' => [
                'required',
            ],
            'frontend_data_6' => [
                'required',
            ],
        ], $messages);

        //errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }
            abort(409, $messages);
        }

        //update record
        $section->frontend_data_1 = request('frontend_data_1');
        $section->frontend_data_2 = request('html_frontend_data_2');
        $section->frontend_data_3 = request('frontend_data_3');
        $section->frontend_data_4 = request('frontend_data_4');
        $section->frontend_data_5 = request('html_frontend_data_5');
        $section->save();

        //redirect back
        request()->session()->flash('success-notification', __('lang.request_has_been_completed'));
        $jsondata['redirect_url'] = url('app-admin/frontend/contact');

        //ajax response
        return response()->json($jsondata);

    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        //common settings
        $page = [
            'crumbs' => [
                __('lang.frontend'),
                __('lang.contact_us'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'meta_title' => __('lang.settings'),
            'heading' => __('lang.frontend'),
            'page' => 'landlord-settings',
            'mainmenu_frontend' => 'active',
            'inner_menu_contact' => 'active',
        ];

        //show
        config(['visibility.left_inner_menu' => 'frontend']);

        //return
        return $page;
    }
}