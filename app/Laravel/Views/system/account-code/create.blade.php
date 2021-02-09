@extends('system._layouts.main')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.department.index')}}">Account Code Management</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add New Account Code</li>
  </ol>
</nav>
@stop

@section('content')
<div class="col-md-8 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Account Code Create Form</h4>
      <form class="create-form" method="POST" enctype="multipart/form-data">
        @include('system._components.notifications')
        {!!csrf_field()!!}
        <div class="form-group">
          <label for="input_title">Code</label>
          <input type="text" class="form-control {{$errors->first('code') ? 'is-invalid' : NULL}}" id="input_title" name="code" placeholder="Code" value="{{old('code')}}">
          @if($errors->first('code'))
          <p class="mt-1 text-danger">{!!$errors->first('code')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_title">Description</label>
          <input type="text" class="form-control {{$errors->first('description') ? 'is-invalid' : NULL}}" id="input_title" name="description" placeholder="Description" value="{{old('description')}}">
          @if($errors->first('description'))
          <p class="mt-1 text-danger">{!!$errors->first('description')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_title">Alias</label>
          <input type="text" class="form-control {{$errors->first('alias') ? 'is-invalid' : NULL}}" id="input_title" name="alias" placeholder="Alias" value="{{old('alias')}}">
          @if($errors->first('alias'))
          <p class="mt-1 text-danger">{!!$errors->first('alias')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_title">Default Cost <code style="font-size: 12px;"><i>Note: Input 0 If there is no default cost</i></code></label>
          <input type="text" class="form-control {{$errors->first('default_cost') ? 'is-invalid' : NULL}}" id="input_title" name="default_cost" placeholder="Default Cost" value="{{old('default_cost')}}">
          @if($errors->first('default_cost'))
          <p class="mt-1 text-danger">{!!$errors->first('default_cost')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_title">NGA'S Code</label>
          <input type="text" class="form-control {{$errors->first('ngas_code') ? 'is-invalid' : NULL}}" id="input_title" name="ngas_code" placeholder="NGA'S Code" value="{{old('ngas_code')}}">
          @if($errors->first('ngas_code'))
          <p class="mt-1 text-danger">{!!$errors->first('ngas_code')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_title">Assigned to unit</label>
          <input type="text" class="form-control {{$errors->first('assigned_to_unit') ? 'is-invalid' : NULL}}" id="input_title" name="assigned_to_unit" placeholder="Assigned to Unit" value="{{old('assigned_to_unit')}}">
          @if($errors->first('assigned_to_unit'))
          <p class="mt-1 text-danger">{!!$errors->first('assigned_to_unit')!!}</p>
          @endif
        </div>

        <button type="submit" class="btn btn-primary mr-2">Create Record</button>
        <a href="{{route('system.department.index')}}" class="btn btn-light">Return to Account Code list</a>
      </form>
    </div>
  </div>
</div>
@stop

