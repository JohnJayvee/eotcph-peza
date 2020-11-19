@extends('system._layouts.main')

@section('content')
<div class="row p-3">
  <div class="col-12">
    @include('system._components.notifications')
    <div class="row ">
      <div class="col-md-6">
        <h5 class="text-title text-uppercase">{{$page_title}}</h5>
      </div>
      <div class="col-md-6 ">
        <p class="text-dim  float-right">EOR-PHP Processor Portal / Zone Location</p>
      </div>
    </div>
  
  </div>

  <div class="col-12 ">
    <form>
      <div class="row">
        
        <div class="col-md-3 p-2">
          <div class="form-group has-search">
            <span class="fa fa-search form-control-feedback"></span>
            <input type="text" class="form-control form-control-lg" placeholder="Search">
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="col-md-12">
    <h4 class="pb-4">Record Data
      <span class="float-right">
        <a href="{{route('system.zone_location.create')}}" class="btn btn-sm btn-primary">Add New</a>
      </span>
    </h4>
    <div class="table-responsive shadow fs-15">
      <table class="table table-striped table-wrap" style="table-layout: fixed;">
        <thead>
          <tr>
            <th width="15%" class="text-title p-3">Zone Code</th>
            <th width="20%" class="text-title p-3">Ecozone</th>
            <th width="20%" class="text-title p-3">Zone Type</th>
            <th width="20%" class="text-title p-3">Zone Nature</th>
            <th width="15%" class="text-title p-3">Created At</th>
            <th width="10%" class="text-title p-3">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($zone_locations as $zone_location)
          <tr style="text-transform: capitalize;">
            <td>{{ $zone_location->code}}</td>
            <td>{{ $zone_location->ecozone}}</td>
            <td>{{ str_replace("_"," ",$zone_location->type)}}</td>
            <td>{{ $zone_location->nature}}</td>
            <td>{{ Helper::date_format($zone_location->created_at)}}</td>
            <td >
              <button type="button" class="btn btn-sm p-0" data-toggle="dropdown" style="background-color: transparent;"> <i class="mdi mdi-dots-horizontal" style="font-size: 30px"></i></button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton2">
                <a class="dropdown-item" href="{{route('system.zone_location.edit',[$zone_location->id])}}">Edit Details</a>
                <a class="dropdown-item action-delete"  data-url="{{route('system.zone_location.destroy',[$zone_location->id])}}" data-toggle="modal" data-target="#confirm-delete">Remove Record</a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center"><i>No Zone Location Records Available.</i></td>
          </tr>
          @endforelse
          
        </tbody>
      </table>
    </div>
  </div>
</div>
@stop

@section('page-modals')
<div id="confirm-delete" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm your action</h5>
      </div>
      <div class="modal-body">
        <h6 class="text-semibold">Deleting Record...</h6>
        <p>You are about to delete a record, this action can no longer be undone, are you sure you want to proceed?</p>
        <hr>
        <h6 class="text-semibold">What is this message?</h6>
        <p>This dialog appears everytime when the chosen action could hardly affect the system. Usually, it occurs when the system is issued a delete command.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
        <a href="#" class="btn btn-sm btn-danger" id="btn-confirm-delete">Delete</a>
      </div>
    </div>
  </div>
</div>
@stop

@section('page-scripts')
<script src="{{asset('system/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
  $(function(){
    $(".action-delete").on("click",function(){
      var btn = $(this);
      $("#btn-confirm-delete").attr({"href" : btn.data('url')});
    });
  })
</script>
@stop