<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [edit] precheck processes for payments
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Payments;
use Closure;
use Log;

class BulkEdit {

    /**
     * This 'bulk actions' middleware does the following
     *   1. If the request was for a sinle item
     *         - single item actions must have a query string '?id=123'
     *         - this id will be merged into the expected 'ids' request array (just as if it was a bulk request)
     *   2. loop through all the 'ids' that are in the post request
     *
     * HTML for the checkbox is expected to be in this format:
     *   <input type="checkbox" name="ids[{{ $payment->payment_id }}]"
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //NOTE: F4 change [action-bar] to the action name e.g. [change-status]

        //for a single item request - merge into an $ids[x] array and set as if checkox is selected (on)
        if (is_numeric(request('id'))) {
            $ids[request('id')] = 'on';
            request()->merge([
                'ids' => $ids,
            ]);
        }

        //loop through each payment and check permissions
        if (is_array(request('ids'))) {

            //validate the each item in the list exists
            foreach (request('ids') as $id => $value) {
                if (!$payment = \App\Models\Payment::Where('payment_id', $id)->first()) {
                    abort(409, __('lang.one_of_the_selected_items_nolonger_exists'));
                }
            }

            //permission: does user have permission edit payments
            if (auth()->user()->is_team) {
                if (auth()->user()->role->role_invoices < 2) {
                    abort(403, __('lang.permission_denied_for_this_item')." - #$id");
                }
            }
            //client - no permissions
            if (auth()->user()->is_client) {
                abort(403);
            }
        } else {
            //no items were passed with this request
            Log::error("no items were sent with this request", ['process' => '[permissions][payments][action-bar]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payment id' => $payment_id ?? '']);
            abort(409);
        }

        //all is on - passed
        return $next($request);
    }
}
