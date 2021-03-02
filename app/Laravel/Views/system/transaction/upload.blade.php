@extends('system._layouts.main')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.transaction.approved')}}">Transaction Management</a></li>
    <li class="breadcrumb-item active" aria-current="page">Upload Documents for this transaction</li>
  </ol>
</nav>
@stop

@section('content')
<div class="col-md-6 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-3">Transaction Upload Form</h4>
      <form class="create-form" method="POST" enctype="multipart/form-data">
        @include('system._components.notifications')
        {!!csrf_field()!!}
        <div class="row">
          <div class="col-md-6">
            <p class="text-title fw-500">Name: <span class="text-black">{{ $transaction->customer ? $transaction->customer->full_name : $transaction->customer_name}}</span></p>
          </div>
          <div class="col-md-6">
            <p class="text-title fw-500">Application: <span class="text-black">{{$transaction->type ? Str::title($transaction->type->name) : "N/A"}} [{{$transaction->code}}] </span></p>
          </div>
        </div>
        <div class="row mt-3 mb-2">
          <div class="col-md-6">
            <a class="btn btn-light" id="repeater_add_file"><i class="fa fa-plus"></i></a>
          </div>
        </div>
        <div id="repeat_form">
          @foreach(range(1, old('name') ? count(old('name')) : 1 ) as $index => $value)
          <div class="row docs">
            <div class="col-md-5">
              <div class="form-group">
                <label for="input_title">File Name</label>
                <input type="text" class="form-control {{$errors->first("name.{$index}") ? 'is-invalid' : NULL}}" id="input_name" name="name[]" placeholder="File Name" value="{{old("name.{$index}")}}">
                @if($errors->first("name.{$index}"))
                <p class="mt-1 text-danger">{!!$errors->first("name.{$index}")!!}</p>
                @endif
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label for="input_title">File</label>
                <input type="file" class="form-control {{$errors->first("document.{$index}") ? 'is-invalid' : NULL}}" id="input_file" name="document[]">
                @if($errors->first("document.{$index}"))
                <p class="mt-1 text-danger">{!!$errors->first("document.{$index}")!!}</p>
                @endif
              </div>
            </div>
            @if($index > 0)
              <div class="col-md-2">
                <a style="margin-top: 28px;" class="btn btn-danger btn-remove text-white">Remove</a>
              </div>
            @endif
          </div>
          @endforeach
        </div>
        <button type="submit" class="btn btn-primary mr-2">Create Record</button>
        <a href="{{route('system.transaction.show',[$transaction->id])}}" class="btn btn-light">Return to Transaction</a>
      </form>
    </div>
  </div>
</div>
@stop

@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('system/vendors/select2/select2.min.css')}}"/>
<style type="text/css">
  .is-invalid{
    border: solid 2px;
  }
  .select2-container--default .select2-selection--multiple .select2-selection__choice{
    font-size: 18px;
  }
  span.select2.select2-container{
    width: 100% !important;
  }
  span.input-group-text{
    border-bottom-right-radius: 5px;
    border-top-right-radius: 5px;
  }
</style>
@endsection
@section('page-scripts')
<script src="{{asset('system/vendors/select2/select2.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">


     $("#repeat_form").delegate(".btn-remove","click",function(){
        var parent_div = $(this).parents(".docs");
        parent_div.fadeOut(500,function(){
          $(this).remove();
        })
    });

    $('#repeater_add_file').on('click', function(){
        var repeat_item = $("#repeat_form .row").eq(0).prop('outerHTML');
        var main_holder = $(this).parents(".row").parent();

    $("#repeat_form").append(repeat_item).find("div[class^=col]:last").parent().append('<div class="col-md-2"><a style="margin-top: 28px;" class="btn btn-danger btn-remove text-white">Remove</a></div>').find(".service-input").val('')
    });
</script>


@endsection