@extends('system._layouts.main')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.zone_location.index')}}">Zone Location Management</a></li>
    <li class="breadcrumb-item active" aria-current="page">Update Zone Location</li>
  </ol>
</nav>
@stop

@section('content')
<div class="col-md-10 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Zone Location Update Form</h4>
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
              <input type="text" class="form-control {{$errors->first('code') ? 'is-invalid' : NULL}}" id="input_code" name="code" placeholder="Zone Code" value="{{old('code',$zone_location->code)}}">
              @if($errors->first('code'))
              <p class="mt-1 text-danger">{!!$errors->first('code')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Ecozone</label>
              <input type="text" class="form-control {{$errors->first('ecozone') ? 'is-invalid' : NULL}}" id="input_ecozone" name="ecozone" placeholder="Ecozone" value="{{old('ecozone',$zone_location->ecozone)}}">
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
              {!!Form::select("type", $zone_types, old('type',$zone_location->type), ['id' => "input_type", 'class' => "form-control ".($errors->first('type') ? 'border-red' : NULL)]) !!}
              @if($errors->first('type'))
              <p class="mt-1 text-danger">{!!$errors->first('type')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Zone Nature</label>
              <input type="text" class="form-control {{$errors->first('nature') ? 'is-invalid' : NULL}}" id="input_nature" name="nature" placeholder="Nature" value="{{old('nature',$zone_location->nature)}}">
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
              <input type="text" class="form-control {{$errors->first('address') ? 'is-invalid' : NULL}}" id="input_address" name="address" placeholder="Address" value="{{old('address',$zone_location->address)}}">
              @if($errors->first('address'))
              <p class="mt-1 text-danger">{!!$errors->first('address')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Developer</label>
              <input type="text" class="form-control {{$errors->first('developer') ? 'is-invalid' : NULL}}" id="input_developer" name="developer" placeholder="Developer" value="{{old('developer',$zone_location->developer)}}">
              @if($errors->first('developer'))
              <p class="mt-1 text-danger">{!!$errors->first('developer')!!}</p>
              @endif
            </div>
          </div>
        </div>
        <input type="hidden" class="form-control" name="region_name" id="input_region_name" value="{{old('region_name',$zone_location->region_name)}}">
        <input type="hidden" class="form-control" name="province_name" id="input_province_name" value="{{old('province_name',$zone_location->province_name)}}">
        <input type="hidden" class="form-control" name="city_name" id="input_city_name" value="{{old('city_name',$zone_location->city_name)}}">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Region</label>
               {!!Form::select('region',[],old('region',$zone_location->region),['id' => "input_region",'class' => "form-control ".($errors->first('region') ? 'border-red' : NULL)])!!}
                @if($errors->first('region'))
                <p class="mt-1 text-danger">{!!$errors->first('region')!!}</p>
                @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Region Code</label>
              <input type="text" class="form-control {{$errors->first('region_code') ? 'is-invalid' : NULL}}" id="input_region_code" name="region_code" placeholder="Region Code" value="{{old('region_code',$zone_location->region_code)}}">
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
              {!!Form::select('province',[],old('province',$zone_location->rovince),['id' => "input_province",'class' => "form-control ".($errors->first('city') ? 'border-red' : NULL)])!!}
              @if($errors->first('province'))
              <p class="mt-1 text-danger">{!!$errors->first('province')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">City</label>
              {!!Form::select('city',[],old('city'),['id' => "input_city",'class' => "form-control ".($errors->first('city') ? 'border-red' : NULL)])!!}
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
              <input type="text" class="form-control {{$errors->first('dev_comp_code') ? 'is-invalid' : NULL}}" id="input_dev_comp_code" name="dev_comp_code" placeholder="Dev comp code" value="{{old('dev_comp_code',$zone_location->dev_comp_code)}}">
              @if($errors->first('dev_comp_code'))
              <p class="mt-1 text-danger">{!!$errors->first('dev_comp_code')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Serial Number</label>
              <input type="text" class="form-control {{$errors->first('obo_cluster') ? 'is-invalid' : NULL}}" id="input_obo_cluster" name="obo_cluster" placeholder="Serial Number" value="{{old('obo_cluster',$zone_location->obo_cluster)}}">
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
              <input type="text" class="form-control {{$errors->first('income_cluster') ? 'is-invalid' : NULL}}" id="input_income_cluster" name="income_cluster" placeholder="Income Cluster" value="{{old('income_cluster',$zone_location->income_cluster)}}">
              @if($errors->first('income_cluster'))
              <p class="mt-1 text-danger">{!!$errors->first('income_cluster')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Serial</label>
              <input type="text" class="form-control {{$errors->first('serial') ? 'is-invalid' : NULL}}" id="input_serial" name="serial" placeholder="Obo cluster" value="{{old('serial',$zone_location->serial)}}">
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
@section('page-scripts')
<script type="text/javascript">
    $.fn.get_region = function(input_region,input_province,input_city,selected){
    
      $(input_province).empty().prop('disabled',true)
      $(input_city).empty().prop('disabled',true)

      $(input_region).append($('<option>', {
                value: "",
                text: "Loading Content..."
            }));
      $.getJSON("{{route('system.zone_location.get_region')}}", function( response ) {
          $(input_region).empty().prop('disabled',true)
          $.each(response.data,function(index,value){
            $(input_region).append($('<option>', {
                value: index,
                text: value
            }));
          })

          $(input_region).prop('disabled',false)
          $(input_region).prepend($('<option>',{value : "",text : "--Select Region--"}))
          if(selected.length > 0){
            $(input_region).val($(input_region+" option[value="+selected+"]").val());
          }else{
            $(input_region).val($(input_region+" option:first").val());
          }
      });
      // return result;
    };

   $.fn.get_province = function(reg_code,input_province,selected){
      $(input_city).empty().prop('disabled',true)
      $(input_province).append($('<option>', {
            value: "",
            text: "Loading Content..."
        }));
      $.getJSON("{{route('system.zone_location.get_provinces')}}?region_code="+reg_code, function( response ) {
          $(input_province).empty().prop('disabled',true)
          $.each(response.data,function(index,value){
              $(input_province).append($('<option>', {
                  value: index,
                  text: value
              }));
          })

          $(input_province).prop('disabled',false)
          $(input_province).prepend($('<option>',{value : "",text : "--SELECT MUNICIPALITY/CITY, PROVINCE--"}))
          if(selected.length > 0){
            $(input_province).val($(input_province+" option[value="+selected+"]").val());
          }else{
            $(input_province).val($(input_province+" option:first").val());
          }
      });
      // return result;
    };

    $.fn.get_city = function(prov_code,input_city,selected){
      $(input_city).empty().prop('disabled',true)
      $(input_city).append($('<option>', {
            value: "",
            text: "Loading Content..."
        }));
      $.getJSON("{{route('system.zone_location.get_municipalities')}}?province_code="+prov_code, function( response ) {
          $(input_city).empty().prop('disabled',true)
          $.each(response.data,function(index,value){
              $(input_city).append($('<option>', {
                  value: index,
                  text: value
              }));
          })

          $(input_city).prop('disabled',false)
          $(input_city).prepend($('<option>',{value : "",text : "--SELECT MUNICIPALITY/CITY, PROVINCE--"}))
          if(selected.length > 0){
            $(input_city).val($(input_city+" option[value="+selected+"]").val());
          }else{
            $(input_city).val($(input_city+" option:first").val());
          }
      });
      // return result;
    };
    $(function(){
      $(this).get_region("#input_region","#input_province","#input_city","{{old('region')}}")
      $("#input_region").on("change",function(){
        var _val = $(this).val();
        var _text = $("#input_region option:selected").text();
        $(this).get_province($("#input_region").val(), "#input_province", "{{old('province')}}");
        $('#input_region_name').val(_text);
      });

      $("#input_province").on("change",function(){
        var _val = $(this).val();
        var _text = $("#input_province option:selected").text();
        $(this).get_city($("#input_province").val(), "#input_city", "{{old('city')}}");
        $('#input_province_name').val(_text);
      });
        $(this).get_region("#input_region","#input_province","#input_city","{{old('region',$zone_location->region)}}")

        @if(strlen(old('region')) > 0 || strlen($zone_location->region))
          $(this).get_province("{{old('region',$zone_location->region)}}", "#input_province", "#input_city", "{{old('province',$zone_location->province)}}");
        @endif

        @if(strlen(old('province')) > 0 || strlen($zone_location->province))
          $(this).get_city("{{old('province',$zone_location->province)}}", "#input_city", "{{old('city',$zone_location->city)}}");
        @endif

      $("#input_city").on("change",function(){
        var _text = $("#input_city option:selected").text();
        $('#input_city_name').val(_text);
      });

    });
</script>
@endsection