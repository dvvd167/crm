<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Landlord\Settings;

use App\Http\Controllers\Controller;
use App\Http\Responses\Landlord\Settings\Updates\CheckResponse;
use App\Http\Responses\Landlord\Settings\Updates\ShowResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;

class Updates extends Controller {

    public function __construct(
    ) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

    }
    /**
     * Display the dashboard home page
     * @return blade view | ajax view
     */
    public function show() {

        //get settings
        $settings = \App\Models\Landlord\Settings::Where('settings_id', 'default')->first();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('index'),
            'settings' => $settings,
            'section' => 'general',
        ];

        //show the form
        return new ShowResponse($payload);
    }

    /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUpdates() {

        //crumbs, page data & stats
        $page = $this->pageSettings();

        //fix updates URL (Oct 2023)
        $updates_server_url = str_replace('http://', 'https://', config('app.updates_server'));
        
        Log::error("starting to check for updates with server ($updates_server_url)", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'server_url' => $updates_server_url]);

        try {
            $response = Http::asForm()->post($updates_server_url, [
                'licence_key' => request('licence_key'),
                'ip_address' => request('ip_address'),
                'url' => request('url'),
                'current_version' => request('current_version'),
                'email' => request('email'),
                'name' => request('name'),
            ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $message = substr($e->getMessage(), 0, 150);
            //log
            Log::error("unable to connect to updates server", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'server_url' => $updates_server_url, 'error' => $message]);
            return new CheckResponse([
                'type' => 'server-error',
            ]);
        }

        //check if we got an error
        if ($response->failed() || $response->clientError() || $response->serverError() || !$response->successful()) {
            //log
            Log::error("unable to connect to updates server", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'server_url' => $updates_server_url, 'error' => $response]);
            return new CheckResponse([
                'type' => 'server-error',
            ]);
        }

        if (!request()->filled('licence_key')) {
            //log
            Log::error("purchase license key not provided", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return new CheckResponse([
                'type' => 'license-error',
            ]);
        }

        //get the result
        $result = $response->json();
        $body = $response->body();
        if (!is_array($result)) {
            Log::error("received an invalid orempty response from the server: (array) [$result] - (body) [$body]", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'response' => $result]);
            return new CheckResponse([
                'type' => 'server-error',
            ]);
        }

        //we have received an error from the update server
        if ($result['status'] == 'failed') {
            return new CheckResponse([
                'type' => 'generic-error',
                'dom' => $result['dom'],
            ]);
        }

        //we have received an error from the update server
        if ($result['status'] == 'success') {
            return new CheckResponse([
                'type' => 'success',
                'update_version' => $result['update_version'],
                'url' => $result['url'],
            ]);
        }
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
                __('lang.settings'),
                __('lang.updates'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'meta_title' => __('lang.settings'),
            'heading' => __('lang.settings'),
            'page' => 'landlord-settings',
            'mainmenu_updates' => 'active',
            'inner_menu_updates' => 'active',
        ];

        //show
        config(['visibility.left_inner_menu' => 'settings']);

        //return
        return $page;
    }
}