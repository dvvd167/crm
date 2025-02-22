<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for general settings
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Settings;
use App\Http\Controllers\Controller;
use App\Http\Responses\Settings\General\IndexResponse;
use App\Http\Responses\Settings\General\UpdateResponse;
use App\Repositories\SettingsRepository;
use App\Rules\NoTags;
use Illuminate\Http\Request;
use Validator;

class General extends Controller {

    /**
     * The settings repository instance.
     */
    protected $settingsrepo;

    public function __construct(SettingsRepository $settingsrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //settings general
        $this->middleware('settingsMiddlewareIndex');

        $this->settingsrepo = $settingsrepo;

    }

    /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        //crumbs, page data & stats
        $page = $this->pageSettings();

        $settings = \App\Models\Settings::find(1);

        //reponse payload
        $payload = [
            'page' => $page,
            'timezone' => $settings->settings_system_timezone,
            'settings' => $settings,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update() {

        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'settings_purchase_code' => [
                'nullable',
                new NoTags,
            ],
            'settings_system_date_format' => [
                'required',
                new NoTags,
            ],
            'settings_system_datepicker_format' => [
                'required',
                new NoTags,
            ],
            'settings_system_default_leftmenu' => [
                'required',
                new NoTags,
            ],
            'settings_system_default_statspanel' => [
                'required',
                new NoTags,
            ],
            'settings_system_pagination_limits' => 'required|numeric',
            'settings_system_kanban_pagination_limits' => 'required|numeric',
            'settings_system_close_modals_body_click' => [
                'required',
                new NoTags,
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

        //update
        if (!$this->settingsrepo->updateGeneral()) {
            abort(409);
        }

        //reset languages (if we are preventing users from changing)
        if (request('settings_system_language_allow_users_to_change') == 'no') {
            \App\Models\User::where('id', '>', 0)
                ->update(['pref_language' => request('settings_system_language_default')]);
        }

        //reponse payload
        $payload = [];

        //generate a response
        return new UpdateResponse($payload);
    }
    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        $page = [
            'crumbs' => [
                __('lang.settings'),
                __('lang.general_settings'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page' => 'settings',
            'meta_title' => __('lang.settings'),
            'heading' => __('lang.settings'),
            'settingsmenu_main' => 'active',
            'submenu_main_general' => 'active',
        ];
        return $page;
    }

}
