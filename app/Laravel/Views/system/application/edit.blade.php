@extends('system._layouts.main')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.application.index')}}">Application Type Management</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Application Type</li>
  </ol>
</nav>
@stop

@section('content')
<div class="col-md-8 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Application Type Edit Form</h4>
      <form class="create-form" method="POST" enctype="multipart/form-data">
        @include('system._components.notifications')
        {!!csrf_field()!!}
        <div class="form-group">
          <label for="input_name">Application Name</label>
          <input type="text" class="form-control {{$errors->first('name') ? 'is-invalid' : NULL}}" id="input_name" name="name" placeholder="Application Name" value="{{old('name',$application->name)}}">
          @if($errors->first('name'))
          <p class="mt-1 text-danger">{!!$errors->first('name')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_suffix">Peza Unit</label>
          {!!Form::select("department_id", $department, old('department_id',$application->department_id), ['id' => "input_department_id", 'class' => "custom-select mb-2 mr-sm-2 ".($errors->first('department_id') ? 'is-invalid' : NULL)])!!}
          @if($errors->first('department_id'))
          <p class="mt-1 text-danger">{!!$errors->first('department_id')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_suffix">Pre-Processing Code</label>
          {!!Form::select("pre_processing_code", $account_codes, old('pre_processing_code',$application->pre_processing_code), ['id' => "input_pre_processing_code", 'class' => "custom-select mb-2 mr-sm-2 ".($errors->first('pre_processing_code') ? 'is-invalid' : NULL)])!!}
          @if($errors->first('pre_processing_code'))
          <p class="mt-1 text-danger">{!!$errors->first('pre_processing_code')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_pre_processing_description">Pre-Processing Description</label>
          <input type="text" class="form-control {{$errors->first('pre_processing_description') ? 'is-invalid' : NULL}}" id="input_pre_processing_description" name="pre_processing_description" placeholder="Pre-Processing Description" value="{{old('pre_processing_description',$application->pre_processing_description)}}">
          @if($errors->first('pre_processing_description'))
          <p class="mt-1 text-danger">{!!$errors->first('pre_processing_description')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_pre_processing_cost">Pre-Processing Cost <code style="font-size: 12px;"><i>Note: Input 0 If there is no processing Fee</i></code></label>
          <input type="text" class="form-control {{$errors->first('pre_processing_cost') ? 'is-invalid' : NULL}}" id="input_pre_processing_cost" name="pre_processing_cost" placeholder="Pre-Processing Cost" value="{{old('pre_processing_cost',$application->pre_processing_cost)}}">
          @if($errors->first('pre_processing_cost'))
          <p class="mt-1 text-danger">{!!$errors->first('pre_processing_cost')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_suffix">Post-Processing Code</label>
          {!!Form::select("post_processing_code", $account_codes, old('post_processing_code',$application->post_processing_code), ['id' => "input_post_processing_code", 'class' => "custom-select mb-2 mr-sm-2 ".($errors->first('post_processing_code') ? 'is-invalid' : NULL)])!!}
          @if($errors->first('post_processing_code'))
          <p class="mt-1 text-danger">{!!$errors->first('post_processing_code')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_post_processing_description">Post-Processing Description</label>
          <input type="text" class="form-control {{$errors->first('post_processing_description') ? 'is-invalid' : NULL}}" id="input_post_processing_description" name="post_processing_description" placeholder="Post-Processing Description" value="{{old('post_processing_description',$application->post_processing_description)}}">
          @if($errors->first('post_processing_description'))
          <p class="mt-1 text-danger">{!!$errors->first('post_processing_description')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_post_processing_cost">Post-Processing Cost <code style="font-size: 12px;"><i>Note: Input 0 If there is no processing Fee</i></code></label>
          <input type="text" class="form-control {{$errors->first('post_processing_cost') ? 'is-invalid' : NULL}}" id="input_post_processing_cost" name="post_processing_cost" placeholder="Post-Processing Cost" value="{{old('post_processing_cost',$application->post_processing_cost)}}">
          @if($errors->first('post_processing_cost'))
          <p class="mt-1 text-danger">{!!$errors->first('post_processing_cost')!!}</p>
          @endif
        </div>
       <!--  <div class="form-group">
          <label for="input_title">Partial Amount<code style="font-size: 12px;"><i>Note: Input 0 If there is no partial amount</i></code></label>
          <input type="text" class="form-control {{$errors->first('partial_amount') ? 'is-invalid' : NULL}}" id="input_title" name="partial_amount" placeholder="Partial Amount" value="{{old('partial_amount')}}">
          @if($errors->first('partial_amount'))
          <p class="mt-1 text-danger">{!!$errors->first('partial_amount')!!}</p>
          @endif
        </div> -->
        <!-- <div class="form-group">
          <label for="input_title">Processing Days</label>
          <input type="text" class="form-control {{$errors->first('processing_days') ? 'is-invalid' : NULL}}" id="input_processing_days" name="processing_days" placeholder="Processing Days" value="{{old('processing_days')}}">
          @if($errors->first('processing_days'))
          <p class="mt-1 text-danger">{!!$errors->first('processing_days')!!}</p>
          @endif
        </div> -->
        <div class="form-group">
          <label for="input_suffix">Application Requirements</label>
          {!!Form::select("requirements_id[]", $requirements, old('requirements_id',explode(",",$application->requirements_id)), ['id' => "input_requirements_id", 'multiple' => 'multiple','class' => "custom-select select2 mb-2 mr-sm-2 ".($errors->first('requirements_id') ? 'is-invalid' : NULL)])!!}
          @if($errors->first('requirements_id'))
          <p class="mt-1 text-danger">{!!$errors->first('requirements_id')!!}</p>
          @endif
        </div>
        <button type="submit" class="btn btn-primary mr-2">Update Record</button>
        <a href="{{route('system.application.index')}}" class="btn btn-light">Return to Application Type list</a>
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
</style>
@endsection

@section('page-scripts')
<script src="{{asset('system/vendors/select2/select2.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
      $('#input_requirements_id').select2({placeholder: "Select Requirements"});

    $.fn.get_pre_processing_amount = function(account_code_id){
      $.getJSON('/cost?type_id='+account_code_id, function(result){
          amount = parseFloat(result.data[0])
          desc = result.data[1]
          $('#input_pre_processing_cost').val(amount);
          $('#input_pre_processing_description').val(desc);

      });
        // return result;
    };

    $.fn.get_post_processing_amount = function(account_code_id){
      $.getJSON('/cost?type_id='+account_code_id, function(result){
          amount = parseFloat(result.data[0])
          desc = result.data[1]
          $('#input_post_processing_cost').val(amount);
          $('#input_post_processing_description').val(desc);

      });
        // return result;
    };

    $('#input_pre_processing_code').on("change",function(){
        var account_code_id = $(this).val()
        $(this).get_pre_processing_amount(account_code_id,"#input_application_id","")

    });
     $('#input_post_processing_code').on("change",function(){
        var account_code_id = $(this).val()
        $(this).get_post_processing_amount(account_code_id,"#input_application_id","")

    });


    });//document ready
</script>
@endsection