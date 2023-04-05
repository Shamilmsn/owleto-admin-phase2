@extends('layouts.app')
@push('css_lib')
<link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">
@endpush
@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">{{trans('lang.pickup_order_plural')}}</h1>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="clearfix"></div>
    @include('flash::message')
    @include('adminlte-templates::common.errors')
    <div class="clearfix"></div>
    <div class="card">
      <div class="card-header">
        <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
          @can('pickup-delivery-orders.index')
          <li class="nav-item">
            <a class="nav-link" href="{!! route('pickup-delivery-orders.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.pickup_order_table')}}</a>
          </li>
          @endcan
          <li class="nav-item">
            <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-pencil mr-2"></i>{{trans('lang.edit_pickup_order_table')}}</a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        {!! Form::model($order, ['route' => ['pickup-delivery-orders.update', $order->id], 'method' => 'patch']) !!}
        <div class="row">
          @include('pickup_delivery_orders.fields')
        </div>
        {!! Form::close() !!}
        <div class="clearfix"></div>
      </div>
    </div>
  </div>

  @include('layouts.media_modal')

@endsection

@push('scripts_lib')
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
<script type="text/javascript">
  Dropzone.autoDiscover = false;
  var dropzoneFields = [];
</script>
@endpush