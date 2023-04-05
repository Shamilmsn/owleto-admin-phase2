@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.product_plural')}}
{{--          <small class="ml-3 mr-3">|</small><small>{{trans('lang.product_desc')}}</small>--}}
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
{{--        <ol class="breadcrumb float-sm-right">--}}
{{--          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>--}}
{{--          <li class="breadcrumb-item"><a href="{!! route('products.index') !!}">{{trans('lang.product_plural')}}</a>--}}
{{--          </li>--}}
{{--          <li class="breadcrumb-item active">{{trans('lang.product_table')}}</li>--}}
{{--        </ol>--}}
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.product_table')}}</a>
        </li>
        @can('products.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('products.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.product_create')}}</a>
        </li>
        @endcan
        @include('layouts.right_toolbar', compact('dataTable'))
      </ul>
    </div>
    <div class="card-body">
      @include('products.table')
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
  <script>
    $(function() {
      let $table = $('#product-table');

      $table.on('click', '.button-approve', function (e) {
        e.preventDefault();

        let confirmation = confirm("Do You Want to change status?");
        let url = $(this).attr('href');
        let approve = $(this).data('approve');
        if (confirmation) {
          $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
              "_token": "{{ csrf_token() }}",
              approve : approve
            },
            success: function(data) {
              if(data){

                if(data.is_approved ==1){
                  toastr.success("Product Approved Successfully");
                }
                else{
                  toastr.warning("Product Rejected Successfully");
                }

                $table.DataTable().draw();
              }
            }
          });
        }
      });
      $table.on('click', '.button-flash-sale-approve', function (e) {
        e.preventDefault();

        let confirmation = confirm("Do You Want to change status?");
        let url = $(this).attr('href');
        let approve = $(this).data('approve');
        if (confirmation) {
          $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
              "_token": "{{ csrf_token() }}",
              approve : approve
            },
            success: function(data) {
              if(data){

                if(data.is_flash_sale_approved ==1){
                  toastr.success("Flash Sale Approved Successfully");
                }
                else{
                  toastr.warning("Flash Sale Rejected Successfully");
                }

                $table.DataTable().draw();
              }
            }
          });
        }
      });

        $table.on('click', '.add-to-featured', function (e) {
            e.preventDefault();
            var productId = $(this).attr('data-id');

            $.ajax({
              url: '{{ url('add-to-featured-products') }}',
              method : "POST",
              dataType: 'json',
              data: {
                "_token": "{{ csrf_token() }}",
                'product_id' : productId
              },
              success: function(response) {
                if(response){
                  $table.DataTable().draw();
                }
              }
            });
        });

      $table.on('click', '.remove-from-featured', function (e) {
        e.preventDefault();
        var productId = $(this).attr('data-id');

        $.ajax({
          url: '{{ url('remove-from-featured-products') }}',
          method : "POST",
          dataType: 'json',
          data: {
            "_token": "{{ csrf_token() }}",
            'product_id' : productId
          },
          success: function(response) {
            if(response){
              $table.DataTable().draw();
            }
          }
        });
      });
    });
  </script>
@endpush
