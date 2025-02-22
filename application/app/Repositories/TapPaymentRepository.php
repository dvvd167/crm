<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for tap payments
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;
use Illuminate\Support\Str;
use Log;

class TapPaymentRepository {

    /**
     * Inject dependecies
     */
    public function __construct() {

    }

    /** ----------------------------------------------------
     * [onetime payment]
     * Start the process for a single tap payment
     * @param array $data information payload
     * @return int session id
     * ---------------------------------------------------*/
    public function onetimePayment($data = []) {

        //validate
        if (!is_array($data)) {
            Log::error("invalid paymment payload data", ['process' => '[tap-payment]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $data]);
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //create a new payment session id (payment reference)
        $session_id = Str::random(30);

        //save session id in sessions database
        $payment_session = new \App\Models\PaymentSession();
        $payment_session->session_creatorid = auth()->id();
        $payment_session->session_creator_fullname = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $payment_session->session_creator_email = auth()->user()->email;
        $payment_session->session_gateway_name = 'tap';
        $payment_session->session_gateway_ref = $session_id;
        $payment_session->session_amount = $data['amount'];
        $payment_session->session_invoices = $data['invoice_id'];
        $payment_session->save();

        //return the session id
        return $session_id;

    }
}