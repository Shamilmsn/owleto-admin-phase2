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
        <h1 class="m-0 text-dark">{{trans('lang.drivers_payout_plural')}}
{{--          <small class="ml-3 mr-3">|</small><small>{{trans('lang.drivers_payout_desc')}}</small>--}}
        </h1>
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
        <li class="nav-item">
          <a class="nav-link" href="{{ route('driver-payout-requests.index') }}">Driver payout lists</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>Create driver payout</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      {!! Form::open(['route' => 'driversPayouts.store']) !!}
      <div class="row">
        @include('driver-payout-requests.fields')
      </div>
      {!! Form::close() !!}
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@include('layouts.media_modal')
@endsection
@push('scripts_lib')
<!-- iCheck -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<!-- select2 -->
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
{{--dropzone--}}
<script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    var dropzoneFields = [];
</script>
@endpush