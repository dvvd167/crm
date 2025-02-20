<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [reply] precheck processes for tickets
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Tickets;
use Closure;
use Log;

class Reply {

    /**
     * This middleware does the following
     *   1. add various needed data into the request
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //validate module status
        if (!config('visibility.modules.tickets')) {
            abort(404, __('lang.the_requested_service_not_found'));
            return $next($request);
        }

        //ticket id
        $ticket_id = $request->route('ticket');

        //does the ticket exist
        if ($ticket_id == '' || !$ticket = \App\Models\Ticket::Where('ticket_id', $ticket_id)->first()) {
            Log::error("ticket could not be found", ['process' => '[permissions][tickets][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'ticket id' => $ticket_id ?? '']);
            abort(404);
        }

        //add ticket it to request
        request()->merge([
            'ticketreply_ticketid' => $request->route('ticket'),
        ]);

        //make sure it not on hold
        if ($ticket->ticket_status == 'on_hold') {
            abort(409, __('lang.ticket_is_on_hold'));
        }

        //update status - use the status that has been set by the admin
        if (auth()->user()->is_team) {
            if ($status = \App\Models\TicketStatus::Where('ticketstatus_use_for_team_replied', 'yes')->first()) {
                request()->merge([
                    'ticket_status' => $status->ticketstatus_id,
                ]);
            } else {
                //default to open status
                request()->merge([
                    'ticket_status' => 1,
                ]);
            }
        }

        //update status - use the status that has been set by the admin
        if (auth()->user()->is_client) {
            if ($status = \App\Models\TicketStatus::Where('ticketstatus_use_for_client_replied', 'yes')->first()) {
                request()->merge([
                    'ticket_status' => $status->ticketstatus_id,
                ]);
            } else {
                //default to open status
                request()->merge([
                    'ticket_status' => 1,
                ]);
            }
        }

        return $next($request);
    }
}
