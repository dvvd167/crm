<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for tax rate settings
 *
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Settings;
use App\Http\Controllers\Controller;
use App\Http\Responses\Settings\Taxrates\CreateResponse;
use App\Http\Responses\Settings\Taxrates\DestroyResponse;
use App\Http\Responses\Settings\Taxrates\EditResponse;
use App\Http\Responses\Settings\Taxrates\IndexResponse;
use App\Http\Responses\Settings\Taxrates\StoreResponse;
use App\Http\Responses\Settings\Taxrates\UpdateResponse;
use App\Repositories\TaxrateRepository;
use Validator;

class Taxrates extends Controller {

    /**
     * The settings repository instance.
     */
    protected $taxraterepo;

    public function __construct(TaxrateRepository $taxraterepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //settings general
        $this->middleware('settingsMiddlewareIndex');

        $this->taxraterepo = $taxraterepo;

    }

    /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        //crumbs, page data & stats
        $page = $this->pageSettings();

        $taxrates = $this->taxraterepo->search();

        //reponse payload
        $payload = [
            'page' => $page,
            'taxrates' => $taxrates,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create() {

        //page settings
        $page = $this->pageSettings('create');

        //reponse payload
        $payload = [
            'page' => $page,
        ];

        //show the form
        return new CreateResponse($payload);
    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\Response
     */
    public function store() {

        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'taxrate_name' => 'required',
            'taxrate_value' => [
                'required',
                'numeric',
            ],
        ], $messages);

        //errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }

        //create the taxrate
        if (!$taxrate_id = $this->taxraterepo->create()) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //get the taxrate object (friendly for rendering in blade template)
        $taxrates = $this->taxraterepo->search($taxrate_id);

        //counting rows
        $rows = $this->taxraterepo->search();
        $count = $rows->total();

        //reponse payload
        $payload = [
            'taxrates' => $taxrates,
            'count' => $count,
        ];

        //process reponse
        return new StoreResponse($payload);

    }

    /**
     * Show the form for editing the specified resource.
     * @url baseusr/items/1/edit
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        //page settings
        $page = $this->pageSettings('edit');

        //client leadsources
        $taxrates = $this->taxraterepo->search($id);

        //not found
        if (!$taxrate = $taxrates->first()) {
            abort(409, __('lang.error_loading_item'));
        }

        //reponse payload
        $payload = [
            'page' => $page,
            'taxrate' => $taxrate,
        ];

        //response
        return new EditResponse($payload);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function update($id) {

        //get the item
        if (!$taxrate = \App\Models\TaxRate::Where('taxrate_id', $id)->first()) {
            abort(404);
        }

        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'taxrate_name' => 'required',
            'taxrate_value' => 'required|numeric',
        ], $messages);

        //errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }

        //update
        $taxrate->taxrate_name = str_replace('|', '', request('taxrate_name')); //[sanity] remove a special character | that we need and are using in <js>
        $taxrate->taxrate_value = request('taxrate_value');
        $taxrate->taxrate_status = request('taxrate_status');
        $taxrate->save();

        //[sanity] - make sure the zero rated tax is not manipulated/changed
        if ($taxrate->taxrate_type == 'system') {
            $taxrate->taxrate_value = 0;
            $taxrate->taxrate_status = 'enabled';
            $taxrate->save();
        }

        //get the category object (friendly for rendering in blade template)
        $taxrates = $this->taxraterepo->search($id);

        //reponse payload
        $payload = [
            'taxrates' => $taxrates,
        ];

        //process reponse
        return new UpdateResponse($payload);

    }

    /**
     * Remove the specified resource from storage.
     * @url baseusr/sources/1
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        //get the source
        if (!$taxrates = $this->taxraterepo->search($id)) {
            abort(409);
        }

        //make sure it is not system default
        $taxrate = $taxrates->first();
        if ($taxrate->taxrate_type == 'system') {
            abort(409, __('lang.you_cannot_delete_system_default_item'));
        }

        //check if this tax rate is not being used by some bills
        if (\App\Models\Tax::Where('tax_taxrateid', $id)->exists()) {
            abort(409, __('lang.tax_rate_deleting_warning'));
        }

        //remove the source
        $taxrate->delete();

        //reponse payload
        $payload = [
            'taxrate_id' => $id,
        ];

        //process reponse
        return new DestroyResponse($payload);
    }
    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        $page = [
            'crumbs' => [
                __('lang.settings'),
                __('lang.sales'),
                __('lang.tax_rates'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page' => 'settings',
            'meta_title' => __('lang.settings'),
            'heading' => __('lang.settings'),
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => __('lang.add_taxrate'),
            'add_modal_create_url' => url('settings/taxrates/create'),
            'add_modal_action_url' => url('settings/taxrates'),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        config([
            //visibility - add project buttton
            'visibility.list_page_actions_add_button' => true,
        ]);

        return $page;
    }
}
