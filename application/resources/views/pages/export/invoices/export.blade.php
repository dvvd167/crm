<!-- right-sidebar -->
<div class="right-sidebar right-sidebar-export sidebar-lg" id="sidepanel-export-clients">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i class="ti-export display-inline-block m-t--11 p-r-10"></i>{{ cleanLang(__('lang.export_clients')) }}
                <span>
                    <i class="ti-close js-toggle-side-panel" data-target="sidepanel-export-clients"></i>
                </span>
            </div>
            <!--title-->
            <!--body-->
            <div class="r-panel-body p-l-35 p-r-35">

                <!--standard fields-->
                <div class="">
                    <h5>@lang('lang.standard_fields')</h5>
                </div>
                <div class="line"></div>
                <div class="row">

                    <!--invoice_id-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[invoice_id]"
                                    name="standard_field[invoice_id]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[invoice_id]">@lang('lang.invoice_id')</label>
                            </div>
                        </div>
                    </div>

                    <!--invoice_status-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[invoice_status]"
                                    name="standard_field[invoice_status]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[invoice_status]">@lang('lang.status')</label>
                            </div>
                        </div>
                    </div>

                    <!--client_name-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[client_name]"
                                    name="standard_field[client_name]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[client_name]">@lang('lang.client_name')</label>
                            </div>
                        </div>
                    </div>


                    <!--project_id-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_id]" name="standard_field[project_id]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30" for="standard_field[project_id]">@lang('lang.project_id')</label>
                            </div>
                        </div>
                    </div>

                    <!--project_title-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[project_title]"
                                    name="standard_field[project_title]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[project_title]">@lang('lang.project_title')</label>
                            </div>
                        </div>
                    </div>

                    <!--date_created-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[date_created]"
                                    name="standard_field[date_created]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[date_created]">@lang('lang.date_created')</label>
                            </div>
                        </div>
                    </div>

                    <!--due_date-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[due_date]" name="standard_field[due_date]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30" for="standard_field[due_date]">@lang('lang.due_date')</label>
                            </div>
                        </div>
                    </div>


                    <!--discount_amount-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[discount_amount]"
                                    name="standard_field[discount_amount]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[discount_amount]">@lang('lang.discount_amount')</label>
                            </div>
                        </div>
                    </div>

                    <!--tax_amount-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[tax_amount]" name="standard_field[tax_amount]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30" for="standard_field[tax_amount]">@lang('lang.tax_amount')</label>
                            </div>
                        </div>
                    </div>

                    <!--adjustment_description-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[adjustment_description]"
                                    name="standard_field[adjustment_description]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[adjustment_description]">@lang('lang.adjustment_description')</label>
                            </div>
                        </div>
                    </div>

                    <!--adjustment_amount-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[adjustment_amount]"
                                    name="standard_field[adjustment_amount]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[adjustment_amount]">@lang('lang.adjustment_amount')</label>
                            </div>
                        </div>
                    </div>

                    <!--recurring-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[recurring]" name="standard_field[recurring]"
                                    class="filled-in chk-col-light-blue" checked="checked">
                                <label class="p-l-30" for="standard_field[recurring]">@lang('lang.recurring')</label>
                            </div>
                        </div>
                    </div>

                    <!--recurring_period-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[recurring_period]"
                                    name="standard_field[recurring_period]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[recurring_period]">@lang('lang.recurring_period')</label>
                            </div>
                        </div>
                    </div>


                    <!--recurring_cycles-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[recurring_cycles]"
                                    name="standard_field[recurring_cycles]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[recurring_cycles]">@lang('lang.recurring_cycles')</label>
                            </div>
                        </div>
                    </div>

                    <!--recurring_duration-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[recurring_duration]"
                                    name="standard_field[recurring_duration]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[recurring_duration]">@lang('lang.recurring_duration')</label>
                            </div>
                        </div>
                    </div>

                    <!--recurring_last-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[recurring_last]"
                                    name="standard_field[recurring_last]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[recurring_last]">@lang('lang.recurring_last')</label>
                            </div>
                        </div>
                    </div>

                    <!--recurring_next-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[recurring_next]"
                                    name="standard_field[recurring_next]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[recurring_next]">@lang('lang.recurring_next')</label>
                            </div>
                        </div>
                    </div>

                    <!--last_payment_date-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[last_payment_date]"
                                    name="standard_field[last_payment_date]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[last_payment_date]">@lang('lang.last_payment_date')</label>
                            </div>
                        </div>
                    </div>

                    <!--last_payment_amount-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[last_payment_amount]"
                                    name="standard_field[last_payment_amount]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[last_payment_amount]">@lang('lang.last_payment_amount')</label>
                            </div>
                        </div>
                    </div>

                    <!--payments_amount-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[payments_amount]"
                                    name="standard_field[payments_amount]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[payments_amount]">@lang('lang.payments_amount')</label>
                            </div>
                        </div>
                    </div>

                    <!--invoice_amount-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[invoice_amount]"
                                    name="standard_field[invoice_amount]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30" for="standard_field[invoice_amount]">@lang('lang.amount')</label>
                            </div>
                        </div>
                    </div>

                    <!--outstanding_balance-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group form-group-checkbox row">
                            <div class="col-12 p-t-5">
                                <input type="checkbox" id="standard_field[outstanding_balance]"
                                    name="standard_field[outstanding_balance]" class="filled-in chk-col-light-blue"
                                    checked="checked">
                                <label class="p-l-30"
                                    for="standard_field[outstanding_balance]">@lang('lang.outstanding_balance')</label>
                            </div>
                        </div>
                    </div>

                </div>

                <!--buttons-->
                <div class="buttons-block">

                    <button type="button" class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button"
                        data-url="{{ urlResource('/export/clients?') }}" data-type="form" data-ajax-type="POST"
                        data-button-loading-annimation="yes">@lang('lang.export')</button>
                </div>
            </div>
    </form>
</div>
<!--sidebar-->