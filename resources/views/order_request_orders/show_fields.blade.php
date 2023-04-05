<!-- Id Field -->
<div class="form-group row col-md-4 col-sm-12">
    {!! Form::label('id', trans('lang.order_id'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        <p>#{!! $order->id !!}</p>
    </div>

    {!! Form::label('order_client', trans('lang.order_client'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        <p>{!! $order->user->name !!}</p>
    </div>

    {!! Form::label('order_client_phone', trans('lang.order_client_phone'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
{{--        <p>{!! isset($order->user->custom_fields['phone']) ? $order->user->custom_fields['phone']['view'] : "" !!}</p>--}}
        <p>{!! $order->user->phone !!}</p>
    </div>

    {!! Form::label('delivery_address', trans('lang.delivery_address'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        <p>{!! $order->deliveryAddress ? $order->deliveryAddress->address : '' !!}</p>
    </div>

    {!! Form::label('order_date', trans('lang.order_date'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        <p>{!! $order->created_at !!}</p>
    </div>

    {{--    {!! Form::label('product_attributes', trans('lang.product_attributes'), ['class' => 'col-4 control-label']) !!}--}}
    {{--    <div class="col-8">--}}
    {{--        @foreach($productAttributes as  $key => $productAttribute)--}}
    {{--            <div class="row">--}}
    {{--                <p> {{ $productAttribute->attribute->name }}</p> : &nbsp; <p>  {{ $productAttribute->attributeOption->name }} </p>--}}
    {{--            </div>--}}

    {{--        @endforeach--}}

    {{--    </div>--}}


</div>

<!-- Order Status Id Field -->
<div class="form-group row col-md-4 col-sm-12">
    {!! Form::label('order_status_id', trans('lang.order_status_status'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        <p>{!! $order->orderStatus->status  !!}</p>
    </div>

    {!! Form::label('active', trans('lang.order_active'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        @if($order->active)
            <p><span class='badge badge-success'> {{trans('lang.yes')}}</span></p>
        @else
            <p><span class='badge badge-danger'>{{trans('lang.order_canceled')}}</span></p>
        @endif
    </div>

    {!! Form::label('payment_method', trans('lang.payment_method'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        <p>{!! isset($order->payment) ? $order->payment->method : ''  !!}</p>
    </div>

{{--    {!! Form::label('payment_status', trans('lang.payment_status'), ['class' => 'col-4 control-label']) !!}--}}
{{--    <div class="col-8">--}}
{{--        <p>{!! isset($order->payment) ? $order->payment->status : trans('lang.order_not_paid')  !!}</p>--}}
{{--    </div>--}}
    {!! Form::label('order_updated_date', trans('lang.order_updated_at'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        <p>{!! $order->updated_at !!}</p>
    </div>

</div>

<!-- Id Field -->
<div class="form-group row col-md-4 col-sm-12">
    {!! Form::label('market', trans('lang.market'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        <p>{!! $order->market->name !!}</p>

    </div>

    {!! Form::label('market_address', trans('lang.market_address'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
            <p>{!! $order->market->address !!}</p>

    </div>

    {!! Form::label('market_phone', trans('lang.market_phone'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">

        <p>{!! $order->market->phone !!}</p>

    </div>

    {!! Form::label('driver', trans('lang.driver'), ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        @if(isset($order->driver))
            <p>{!! $order->driver->name !!}</p>
        @else
            <p>{{trans('lang.order_driver_not_assigned')}}</p>
        @endif

    </div>
    @if($order->is_coupon_used)
        {!! Form::label('coupon_code', trans('lang.coupon_code_label'), ['class' => 'col-4 control-label']) !!}
        <div class="col-8">
            <p>{{ $order->coupon_code }} </p>
        </div>
    @endif

{{--    {!! Form::label('hint', 'Hint:', ['class' => 'col-4 control-label']) !!}--}}
{{--    <div class="col-8">--}}
{{--        <p>{!! $order->hint !!}</p>--}}
{{--    </div>--}}

</div>

{{--<!-- Tax Field -->--}}
{{--<div class="form-group row col-md-6 col-sm-12">--}}
{{--  {!! Form::label('tax', 'Tax:', ['class' => 'col-4 control-label']) !!}--}}
{{--  <div class="col-8">--}}
{{--    <p>{!! $order->tax !!}</p>--}}
{{--  </div>--}}
{{--</div>--}}


