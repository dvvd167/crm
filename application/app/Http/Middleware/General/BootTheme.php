<?php

/** ---------------------------------------------------------------------------------------------------------------
 * The purpose of this middleware it to set fallback config values
 * for older versions of Grow CRM that are upgrading. Reason being that new
 * values in the file /config/settings.php will not exist for them (as settings files in not included in updates)
 *
 *
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 * @revised    9 July 2021
 *--------------------------------------------------------------------------------------------------------------*/

namespace App\Http\Middleware\General;
use Closure;
use Illuminate\Support\Facades\Storage;
use Log;

class BootTheme {

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //do not run this for SETUP path
        if (env('SETUP_STATUS') != 'COMPLETED') {
            //set default theme
            config([
                'theme.selected_theme_css' => 'public/themes/default/css/style.css?v=1',
            ]);
            //skip this middlware
            return $next($request);
        }

        //get settings
        $settings = \App\Models\Settings::find(1);

        //get all directories in themes folder
        $directories = Storage::disk('root')->directories('public/themes');

        //clean up directory names
        array_walk($directories, function (&$value, $key) {
            $value = str_replace('public/themes/', '', $value);
        });

        //check if default theme exists
        if (!in_array($settings->settings_theme_name, $directories)) {
            Log::critical("The selected theme directory could not be found", ['process' => '[validating theme]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'Theme Directory: ' => '/public/themes/' . $settings->settings_theme_name]);
            abort(409, __('lang.default_theme_not_found') . ' [' . runtimeThemeName($settings->settings_theme_name) . ']');
        }

        //check if css file exists
        if (!is_file(BASE_DIR . '/public/themes/' . $settings->settings_theme_name . '/css/style.css')) {
            Log::critical("The selected theme does not seem to have a style.css files", ['process' => '[validating theme]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'Theme Directory: ' => '/public/themes/' . $settings->settings_theme_name]);
            abort(409, __('lang.selected_theme_is_invalid'));
        }

        //validate if the folders in the /public/themes/ directory have a style.css file
        $list = [];
        foreach ($directories as $directory) {
            if (is_file(BASE_DIR . "/public/themes/$directory/css/style.css")) {
                $list[] = $directory;
            }
        }

        //set global data
        config([
            'theme.list' => $list,
            'theme.selected_name' => $settings->settings_theme_name,
            //main css file
            'theme.selected_theme_css' => 'public/themes/' . $settings->settings_theme_name . '/css/style.css?v=' . $settings->settings_system_javascript_versioning,
            //invoice/estimate pdf (web preview)
            //[8 Aug 2021] all themes should now use the 'default' theme's bill-pdf.css file (public/themes/default/css/bill-pdf.css)
            'theme.selected_theme_pdf_css' => 'public/themes/default/css/bill-pdf.css?v=' . $settings->settings_system_javascript_versioning,
            //[MT]
            'theme.selected_theme_saas_css' => 'public/themes/' . $settings->settings_theme_name . '/css/saas.css?v=' . $settings->settings_system_javascript_versioning,
        ]);

        return $next($request);

    }

}