@extends('system._layouts.main')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.zone_location.index')}}">Zone Location Management</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add New Zone Location</li>
  </ol>
</nav>
@stop

@section('content')
<div class="col-md-10 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Zone Location Create Form</h4>
      <p class="card-description">
        Fill up the <strong class="text-danger">* required</strong> fields.
      </p>
      <form class="create-form" method="POST" enctype="multipart/form-data">
        @include('system._components.notifications')
        {!!csrf_field()!!}
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Zone Code</label>
              <input type="text" class="form-control {{$errors->first('code') ? 'is-invalid' : NULL}}" id="input_code" name="code" placeholder="Zone Code" value="{{old('code')}}">
              @if($errors->first('code'))
              <p class="mt-1 text-danger">{!!$errors->first('code')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Ecozone</label>
              <input type="text" class="form-control {{$errors->first('ecozone') ? 'is-invalid' : NULL}}" id="input_ecozone" name="ecozone" placeholder="Ecozone" value="{{old('ecozone')}}">
              @if($errors->first('ecozone'))
              <p class="mt-1 text-danger">{!!$errors->first('ecozone')!!}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Zone Type</label>
              {!!Form::select("type", $zone_types, old('type'), ['id' => "input_type", 'class' => "form-control ".($errors->first('type') ? 'border-red' : NULL)]) !!}
              @if($errors->first('type'))
              <p class="mt-1 text-danger">{!!$errors->first('type')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Zone Nature</label>
              <input type="text" class="form-control {{$errors->first('nature') ? 'is-invalid' : NULL}}" id="input_nature" name="nature" placeholder="Nature" value="{{old('nature')}}">
              @if($errors->first('nature'))
              <p class="mt-1 text-danger">{!!$errors->first('nature')!!}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Address</label>
              <input type="text" class="form-control {{$errors->first('address') ? 'is-invalid' : NULL}}" id="input_address" name="address" placeholder="Address" value="{{old('address')}}">
              @if($errors->first('address'))
              <p class="mt-1 text-danger">{!!$errors->first('address')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Developer</label>
              <input type="text" class="form-control {{$errors->first('developer') ? 'is-invalid' : NULL}}" id="input_developer" name="developer" placeholder="Developer" value="{{old('developer')}}">
              @if($errors->first('developer'))
              <p class="mt-1 text-danger">{!!$errors->first('developer')!!}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Region</label>
              <input type="text" class="form-control {{$errors->first('region') ? 'is-invalid' : NULL}}" id="input_region" name="region" placeholder="Region" value="{{old('region')}}">
              @if($errors->first('region'))
                <p class="mt-1 text-danger">{!!$errors->first('region')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Region Code</label>
              <input type="text" class="form-control {{$errors->first('region_code') ? 'is-invalid' : NULL}}" id="input_region_code" name="region_code" placeholder="Region Code" value="{{old('region_code')}}">
              @if($errors->first('region_code'))
              <p class="mt-1 text-danger">{!!$errors->first('region_code')!!}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Province</label>
              <input type="text" class="form-control {{$errors->first('province') ? 'is-invalid' : NULL}}" id="input_province" name="province" placeholder="Province" value="{{old('province')}}">
              @if($errors->first('province'))
              <p class="mt-1 text-danger">{!!$errors->first('province')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">City</label>
              <input type="text" class="form-control {{$errors->first('city') ? 'is-invalid' : NULL}}" id="input_city" name="city" placeholder="City" value="{{old('city')}}">
              @if($errors->first('city'))
              <p class="mt-1 text-danger">{!!$errors->first('city')!!}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Dev Comp Code</label>
              <input type="text" class="form-control {{$errors->first('dev_comp_code') ? 'is-invalid' : NULL}}" id="input_dev_comp_code" name="dev_comp_code" placeholder="Dev comp code" value="{{old('dev_comp_code')}}">
              @if($errors->first('dev_comp_code'))
              <p class="mt-1 text-danger">{!!$errors->first('dev_comp_code')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Serial Number</label>
              <input type="text" class="form-control {{$errors->first('obo_cluster') ? 'is-invalid' : NULL}}" id="input_obo_cluster" name="obo_cluster" placeholder="Serial Number" value="{{old('obo_cluster')}}">
              @if($errors->first('obo_cluster'))
              <p class="mt-1 text-danger">{!!$errors->first('obo_cluster')!!}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Income Cluster</label>
              <input type="text" class="form-control {{$errors->first('income_cluster') ? 'is-invalid' : NULL}}" id="input_income_cluster" name="income_cluster" placeholder="Income Cluster" value="{{old('income_cluster')}}">
              @if($errors->first('income_cluster'))
              <p class="mt-1 text-danger">{!!$errors->first('income_cluster')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Serial</label>
              <input type="text" class="form-control {{$errors->first('serial') ? 'is-invalid' : NULL}}" id="input_serial" name="serial" placeholder="Obo cluster" value="{{old('serial')}}">
              @if($errors->first('serial'))
              <p class="mt-1 text-danger">{!!$errors->first('serial')!!}</p>
              @endif
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary mr-2">Create Record</button>
        <a href="{{route('system.zone_location.index')}}" class="btn btn-light">Return to Zone Location list</a>
      </form>
    </div>
  </div>
</div>
@stop
@section('page-styles')
<style type="text/css">
   .border-red{
        border:solid 2px #dc3545 !important;
    }
</style>
@endsection
