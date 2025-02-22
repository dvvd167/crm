<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [create] precheck processes for product proposals
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Proposals;
use Closure;
use Log;

class Create {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] proposals
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //frontend
        $this->fronteEnd();

        //permission: does user have permission create proposals
        if (auth()->user()->role->role_proposals >= 2) {
            
            return $next($request);
        }

        //NB: client db/repository (clientid filter merege) is applied in main controller.php

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][proposals][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //some settings
        config([
            'settings.proposal' => true,
        ]);
    }
}
