<?php

/** --------------------------------------------------------------------------------
 * This service provider configures the applications theme
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigSystemServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        //do not run this for SETUP path
        if (env('SETUP_STATUS') != 'COMPLETED') {
            //skip this provider
            return;
        }

        //save system settings into config array
        $settings = \App\Models\Settings::leftJoin('settings2', 'settings2.settings2_id', '=', 'settings.settings_id')
            ->Where('settings_id', 1)
            ->first();

        //set timezone
        date_default_timezone_set($settings->settings_system_timezone);

        //currency symbol position setting
        if ($settings->settings_system_currency_position == 'left') {
            $settings['currency_symbol_left'] = $settings->settings_system_currency_symbol;
            $settings['currency_symbol_right'] = '';
        } else {
            $settings['currency_symbol_right'] = $settings->settings_system_currency_symbol;
            $settings['currency_symbol_left'] = '';
        }

        //lead statuses
        $settings['lead_statuses'] = [];
        foreach (\App\Models\LeadStatus::get() as $status) {
            $key = $status->leadstatus_id;
            $value = $status->leadstatus_color;
            $settings['lead_statuses'] += [
                $key => $value,
            ];
        }

        //Just a list of all payment geteways - used in dropdowns and filters
        $settings['gateways'] = [
            'Paypal',
            'Stripe',
            'Bank',
            'Cash',
        ];

        //cronjob path
        $settings['cronjob_path'] = '/usr/local/bin/php ' . BASE_DIR . '/application/artisan schedule:run >> /dev/null 2>&1';

        //all team members
        $settings['team_members'] = \App\Models\User::Where('type', 'team')->Where('status', 'active')->get();

        //javascript file versioning to avoid caching when making updates
        $settings['versioning'] = $settings->settings_system_javascript_versioning;

        //save once to config
        config(['system' => $settings]);

        $categories = \App\Models\Category::Where('category_type', 'project')->orderBy('category_name', 'asc')->get();
        config(['projects_categories' => $categories]);

        //recaptcha
        config([
            'recaptcha.api_site_key' => $settings->settings2_captcha_api_site_key,
            'recaptcha.api_secret_key' => $settings->settings2_captcha_api_secret_key,
        ]);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
