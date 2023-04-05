@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
<!-- Name Field -->
<div class="form-group row ">
  {!! Form::label('name', trans("lang.field_name"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.field_name_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.field_name_help") }}
    </div>
  </div>
</div>
    <!-- Charge Field -->
<div class="form-group row ">
  {!! Form::label('charge', trans("lang.field_charge"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::text('charge', null,  ['class' => 'form-control','placeholder'=>  trans("lang.field_charge_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.field_charge_help") }}
    </div>
  </div>
</div>

  <div class="form-group row ">
    {!! Form::label('base_distance', 'Distance (KM)', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
      {!! Form::text('base_distance', null,  ['class' => 'form-control','placeholder'=>  'Enter the base distance']) !!}
      <div class="form-text text-muted">
        Enter the base distance
      </div>
    </div>
  </div>

  <div class="form-group row ">
    {!! Form::label('additional_amount', 'Additional Amount', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
      {!! Form::text('additional_amount', null,  ['class' => 'form-control','placeholder'=>  'Enter the additional amount']) !!}
      <div class="form-text text-muted">
        Enter the additional amount
      </div>
    </div>
  </div>

</div>
@if($customFields)
<div class="clearfix"></div>
<div class="col-12 custom-field-container">
  <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
  {!! $customFields !!}
</div>
@endif
<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.delivery_type')}}</button>
  <a href="{!! route('deliveryTypes.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>