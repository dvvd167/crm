<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [create] precheck processes for clients
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Clients;

use Closure;
use Log;

class Create {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] clients
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //permission: does user have permission view clients
        if (auth()->user()->role->role_clients >= 2) {
            return $next($request);
        }

        //NB: client db/repository (clientid filter merege) is applied in main controller.php

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][clients][index]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'user_id' => auth()->id()]);
        abort(403);
    }

}
