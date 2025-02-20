<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Log;

class Test extends Controller {

    public $razorpayrepo;

    public function __construct() {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

    }

    public function index() {

        //get all customers with a version less than the system version and are not in failed status
        $customers = \App\Models\Landlord\Tenant::on('landlord')
            ->where('tenant_updating_current_version', '<', '1.50')
            ->get();

        dd($customers);

    }

}