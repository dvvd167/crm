<?php

namespace App\Exports;

use App\Repositories\InvoiceRepository;
use App\Repositories\CustomFieldsRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoicesExport implements FromCollection, WithHeadings, WithMapping {

    /**
     * The invoice repo repository
     */
    protected $invoicerepo;

    /**
     * The custom repo repository
     */
    protected $customrepo;

    public function __construct(InvoiceRepository $invoicerepo) {

        $this->invoicerepo = $invoicerepo;

    }

    //get the invoices
    public function collection() {
        //search
        request()->merge([
            'search_type' => 'exports',
        ]);
        $invoices = $this->invoicerepo->search();
        //return
        return $invoices;
    }

    //map the columns that we want
    public function map($invoices): array{

        $map = [];

        //standard fields - loop thorugh all post data
        if (is_array(request('standard_field'))) {
            foreach (request('standard_field') as $key => $value) {
                if ($value == 'on') {
                    switch ($key) {
                    case 'invoice_company_name':
                        $map[] = $invoices->invoice_company_name;
                        break;
                    case 'invoice_created':
                        $map[] = runtimeDate($invoices->invoice_created);
                        break;
                    case 'category':
                        $map[] = $invoices->category_name;
                        break;
                    case 'contact_name':
                        $map[] = $invoices->first_name . ' ' . $invoices->last_name;
                        break;
                    case 'contact_email':
                        $map[] = $invoices->email;
                        break;
                    case 'invoice_phone':
                        $map[] = $invoices->invoice_phone;
                        break;
                    case 'invoice_website':
                        $map[] = $invoices->invoice_website;
                        break;
                    case 'invoice_vat':
                        $map[] = $invoices->invoice_vat;
                        break;
                    case 'invoice_billing_street':
                        $map[] = $invoices->invoice_billing_street;
                        break;
                    case 'invoice_billing_city':
                        $map[] = $invoices->invoice_billing_city;
                        break;
                    case 'invoice_billing_state':
                        $map[] = $invoices->invoice_billing_state;
                        break;
                    case 'invoice_billing_zip':
                        $map[] = $invoices->invoice_billing_zip;
                        break;
                    case 'invoice_billing_country':
                        $map[] = $invoices->invoice_billing_country;
                        break;
                    case 'invoices':
                        $map[] = runtimeMoneyFormat($invoices->sum_invoices_all);
                        break;
                    case 'payments':
                        $map[] = runtimeMoneyFormat($invoices->sum_all_payments);
                        break;
                    case 'invoice_status':
                        $map[] = runtimeLang($invoices->invoice_status);
                        break;
                    default:
                        $map[] = $invoices->{$key};
                        break;
                    }
                }
            }
        }

        //custom fields - loop thorugh all post data
        if (is_array(request('custom_field'))) {
            foreach (request('custom_field') as $key => $value) {
                if ($value == 'on') {
                    if ($field = \App\Models\CustomField::Where('customfields_name', $key)->first()) {
                        switch ($field->customfields_datatype) {
                        case 'date':
                            $map[] = runtimeDate($invoices->{$key});
                            break;
                        case 'checkbox':
                            $map[] = ($invoices->{$key} == 'on') ? __('lang.checked_custom_fields') : '---';
                            break;
                        default:
                            $map[] = $invoices->{$key};
                            break;
                        }
                    } else {
                        $map[] = '';
                    }
                }
            }
        }

        return $map;
    }

    //create heading
    public function headings(): array
    {

        //headings
        $heading = [];

        //lang - standard fields
        $standard_lang = [
            'invoice_company_name' => __('lang.invoice_name'),
            'invoice_created' => __('lang.date_created'),
            'category' => __('lang.category'),
            'contact_name' => __('lang.contact_name'),
            'contact_email' => __('lang.contact_email'),
            'invoice_phone' => __('lang.telephone'),
            'invoice_website' => __('lang.website'),
            'invoice_vat' => __('lang.vat_tax_number'),
            'invoice_billing_street' => __('lang.street'),
            'invoice_billing_city' => __('lang.city'),
            'invoice_billing_state' => __('lang.state'),
            'invoice_billing_zip' => __('lang.zipcode'),
            'invoice_billing_country' => __('lang.country'),
            'invoices' => __('lang.invoices'),
            'payments' => __('lang.payments'),
            'invoice_status' => __('lang.status'),
        ];

        //lang - custom fields (i.e. field titles)
        $custom_lang = $this->customrepo->fieldTitles();

        //standard fields - loop thorugh all post data
        if (is_array(request('standard_field'))) {
            foreach (request('standard_field') as $key => $value) {
                if ($value == 'on') {
                    $heading[] = (isset($standard_lang[$key])) ? $standard_lang[$key] : $key;
                }
            }
        }

        //custom fields - loop thorugh all post data
        if (is_array(request('custom_field'))) {
            foreach (request('custom_field') as $key => $value) {
                if ($value == 'on') {
                    $heading[] = (isset($custom_lang[$key])) ? $custom_lang[$key] : $key;
                }
            }
        }

        //return full headings
        return $heading;
    }
}
