<!--dropdown-->
<div class="form-group row">
    <label class="col-12 text-left control-label col-form-label {{ runtimeWebformRequiredBold($payload['required']) }}">
        {{ $payload['label'] }}{{ runtimeWebformRequiredAsterix($payload['required']) }} @if($payload['tooltip'] != '')
        <span class="align-middle text-default font-16" data-toggle="tooltip" title="{{ $payload['tooltip'] }}"
            data-placement="top"><i class="ti-info-alt"></i></span>
        @endif</label>
    <div class="col-12">
        <select class="select2-basic {{ $payload['class'] }} select2-preselected" id="{{ $payload['name'] }}"
            placeholder="{{ $payload['placeholder'] }}" name="{{ $payload['name'] }}">
            <option></option>
            {!! _clean($payload['options']) !!}
        </select>
    </div>
</div>