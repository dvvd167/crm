<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [destroy] precheck processes for invoices
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Invoices;
use Closure;
use Log;

class Destroy {

    /**
     * This 'bulk actions' middleware does the following
     *   1. If the request was for a sinle item
     *         - single item actions must have a query string '?id=123'
     *         - this id will be merged into the expected 'ids' request array (just as if it was a bulk request)
     *   2. loop through all the 'ids' that are in the post request
     *
     * HTML for the checkbox is expected to be in this format:
     *   <input type="checkbox" name="ids[{{ $invoice->bill_invoiceid }}]"
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //validate module status
        if (!config('visibility.modules.invoices')) {
            abort(404, __('lang.the_requested_service_not_found'));
            return $next($request);
        }

        //for a single item request - merge into an $ids[x] array and set as if checkox is selected (on)
        if (is_numeric($request->route('invoice'))) {
            $ids[$request->route('invoice')] = 'on';
            request()->merge([
                'ids' => $ids,
            ]);
        }

        //loop through each invoice and check permissions
        if (is_array(request('ids'))) {

            //validate each item in the list exists
            foreach (request('ids') as $id => $value) {
                //only checked items
                if ($value == 'on') {
                    //validate
                    if (!$invoice = \App\Models\Invoice::Where('bill_invoiceid', $id)->first()) {
                        abort(409, __('lang.one_of_the_selected_items_nolonger_exists'));
                    }
                }
            }

            //permission: does user have permission edit invoices
            if (auth()->user()->is_team) {
                if (auth()->user()->role->role_invoices < 3) {
                    abort(403);
                }
            }
            //client - no permissions
            if (auth()->user()->is_client) {
                abort(403);
            }
        } else {
            //no items were passed with this request
            Log::error("no items were sent with this request", ['process' => '[permissions][invoices][change-category]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'invoice id' => $bill_invoiceid ?? '']);
            abort(409);
        }

        //all is on - passed
        return $next($request);
    }
}
