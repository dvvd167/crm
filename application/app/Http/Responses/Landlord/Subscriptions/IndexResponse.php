<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [index] process for the subscriptions
 * controller
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Landlord\Subscriptions;
use Illuminate\Contracts\Support\Responsable;

class IndexResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for subscriptions members
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //was this call made from an embedded page/ajax or directly on subscriptions page
        if (request('source') == 'ext' || request('action') == 'search' || request()->ajax()) {

            //template and dom - for additional ajax loading
            switch (request('action')) {

            //typically from the loadmore button
            case 'load':
                $template = 'landlord/subscriptions/table/ajax';
                $dom_container = '#subscriptions-td-container';
                $dom_action = 'append';
                break;

            //from the sorting links
            case 'sort':
                $template = 'landlord/subscriptions/table/ajax';
                $dom_container = '#subscriptions-td-container';
                $dom_action = 'replace';
                break;

            //from search box or filter panel
            case 'search':
                $template = 'landlord/subscriptions/table/table';
                $dom_container = '#subscriptions-table-wrapper';
                $dom_action = 'replace-with';
                break;

            //template and dom - for ajax initial loading
            default:
                $template = 'landlord/subscriptions/table/table';
                $dom_container = '#dynamic-content-container';
                $dom_action = 'replace';

                //replace list page actions
                $jsondata['dom_visibility'][] = [
                    'selector' => '.list-page-actions-containers',
                    'action' => 'hide',
                ];
                $jsondata['dom_visibility'][] = [
                    'selector' => '#list-page-actions-container-subscriptions',
                    'action' => 'show',
                ];
                break;
            }

            //load more button - change the page number and determine buttons visibility
            if ($subscriptions->currentPage() < $subscriptions->lastPage()) {
                $url = loadMoreButtonUrl($subscriptions->currentPage() + 1, request('source'));
                $jsondata['dom_attributes'][] = array(
                    'selector' => '#load-more-button',
                    'attr' => 'data-url',
                    'value' => $url);
                //load more - visible
                $jsondata['dom_visibility'][] = array('selector' => '.loadmore-button-container', 'action' => 'show');
                //load more: (intial load - sanity)
                $page['visibility_show_load_more'] = true;
                $page['url'] = $url;
            } else {
                $jsondata['dom_visibility'][] = array('selector' => '.loadmore-button-container', 'action' => 'hide');
            }

            //flip sorting url for this particular link - only is we clicked sort menu links
            if (request('action') == 'sort') {
                $sort_url = flipSortingUrl(request()->fullUrl(), request('sortorder'));
                $element_id = '#sort_' . request('orderby');
                $jsondata['dom_attributes'][] = array(
                    'selector' => $element_id,
                    'attr' => 'data-url',
                    'value' => $sort_url);
            }

            //render the view and save to json
            $html = view($template, compact('page', 'subscriptions'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => $dom_container,
                'action' => $dom_action,
                'value' => $html);

            //for embedded - change breadcrumb title
            $jsondata['dom_html'][] = [
                'selector' => '.active-bread-crumb',
                'action' => 'replace',
                'value' => strtoupper(__('lang.subscriptions')),
            ];

            //ajax response
            return response()->json($jsondata);

        } else {
            //standard view
            $page['url'] = loadMoreButtonUrl($subscriptions->currentPage() + 1, request('source'));
            $page['loading_target'] = 'subscriptions-td-container';
            $page['visibility_show_load_more'] = ($subscriptions->currentPage() < $subscriptions->lastPage()) ? true : false;
            return view('landlord/subscriptions/wrapper', compact('page', 'subscriptions'))->render();
        }

    }

}