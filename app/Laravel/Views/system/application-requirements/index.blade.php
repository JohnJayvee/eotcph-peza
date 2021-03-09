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
        <p class="text-dim  float-right">EOR-PHP Processor Portal / Application Requirements</p>
      </div>
    </div>
  
  </div>

  <div class="col-12 ">
    <form>
      <div class="row">
        <div class="col-md-3">
          <label>Keywords</label>
          <div class="form-group has-search">
            <span class="fa fa-search form-control-feedback"></span>
            <input type="text" class="form-control mb-2 mr-sm-2" id="input_keyword" name="keyword" value="{{$keyword}}" placeholder="Requirement name">
          </div>
        </div>
        <div class="col-md-3 mt-4 p-1">
          <button class="btn btn-primary btn-sm p-2" type="submit">Filter</button>
          <a href="{{route('system.application_requirements.index')}}" class="btn btn-primary btn-sm p-2">Clear</a>
        </div>
      </div>
    </form>
  </div>
  <div class="col-md-12">
    <h4 class="pb-4">Record Data
      <span class="float-right">
        <a href="{{route('system.application_requirements.upload')}}" class="btn btn-sm btn-primary">Bulk Upload</a>
        <a href="{{route('system.application_requirements.create')}}" class="btn btn-sm btn-primary">Add New</a>
      </span>
    </h4>
    <div class="shadow fs-15 table-responsive">
      <table class="table table-striped table-wrap">
        <thead>
          <tr>
            <th class="text-title fs-15 fs-500 p-3">Name</th>
            <th class="text-title fs-15 fs-500 p-3">Is Required</th>
            <th class="text-title fs-15 fs-500 p-3">Created At</th>
            <th class="text-title fs-15 fs-500 p-3">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($application_requirements as $application_requirement)
          <tr >
            <td style="text-transform: capitalize;">{{ $application_requirement->name}}</td>
            <td style="text-transform: capitalize;">{{ $application_requirement->is_required}}</td>
            <td>{{ Helper::date_format($application_requirement->created_at)}}</th>
            <td >
              <button type="button" class="btn btn-sm p-0" data-toggle="dropdown" style="background-color: transparent;"> <i class="mdi mdi-dots-horizontal" style="font-size: 30px"></i></button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton2">
                <a class="dropdown-item" href="{{route('system.application_requirements.edit',[$application_requirement->id])}}">Edit Application</a>
                <!-- <a class="dropdown-item action-delete"  data-url="{{route('system.department.destroy',[$application_requirement->id])}}" data-toggle="modal" data-target="#confirm-delete">Remove Record</a> -->
              </div>
            </td>
          </tr>
          @empty
          <tr>
           <td colspan="4" class="text-center"><i>No Application Requirements Records Available.</i></td>
          </tr>
          @endforelse
          
        </tbody>
      </table>
    </div>
    @if($application_requirements->total() > 0)
      <nav class="mt-2">
       <!--  <p>Showing <strong>{{$application_requirements->firstItem()}}</strong> to <strong>{{$application_requirements->lastItem()}}</strong> of <strong>{{$application_requirements->total()}}</strong> entries</p> -->
        {!!$application_requirements->appends(request()->query())->render()!!}
        </ul>
      </nav>
    @endif
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