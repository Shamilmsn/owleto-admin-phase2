@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.drivers_payout_plural')}}
{{--          <small class="ml-3 mr-3">|</small><small>{{trans('lang.drivers_payout_desc')}}</small>--}}
        </h1>
      </div><!-- /.col -->
{{--      <div class="col-sm-6">--}}
{{--        <ol class="breadcrumb float-sm-right">--}}
{{--          <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>--}}
{{--          <li class="breadcrumb-itema ctive"><a href="{!! route('driversPayouts.index') !!}">{{trans('lang.drivers_payout_plural')}}</a>--}}
{{--          </li>--}}
{{--        </ol>--}}
{{--      </div><!-- /.col -->--}}
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link" href="{!! route('driversPayouts.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.drivers_payout_table')}}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{!! route('driversPayouts.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.drivers_payout_create')}}</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <div class="row">
        @include('drivers_payouts.show_fields')

        <!-- Back Field -->
        <div class="form-group col-12 text-right">
          <a href="{!! route('driversPayouts.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.back')}}</a>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection
