@extends('web._layouts.main')


@section('content')



<!--team section start-->
<section class="px-120 pt-110 pb-80 gray-light-bg">
  <div class="container">
    <div class="row flex-row items-center px-4">
      <h5 class="text-title pb-2 mt-2"><i class="fa fa-file"></i> E<span class="text-title-two"> Transaction History</span></h5>
      <a href="{{route('web.transaction.history')}}" class="custom-btn badge-primary-2 text-white " style="float: right;margin-left: auto;">E-Submission</a>
    </div>
    <div class="card card-rounded shadow-sm">
      <div class="card-body" style="border-bottom: 3px dashed #E3E3E3;">
        <div class="row">
          <div class="col-md-11 d-flex">
            <p class="text-title fw-600 pt-3">Company: <span class="text-black">{{Str::title($transaction->company_name)}}</span></p>
            <p class="text-title fw-500 pl-3" style="padding-top: 15px;">|</p>
            <p class="text-title fw-600 pt-3 pl-3">Application Sent: <span class="text-black">{{ Helper::date_format($transaction->created_at)}}</span></p>
          </div>
        </div>
      </div>
      <div class="card-body" style="border-bottom: 3px dashed #E3E3E3;">
        <div class="row">
          <div class="col-md-6">
            <p class="text-title fw-600 m-0">Name: <span class="text-black">{{str::title($transaction->customer ? $transaction->customer->full_name : $transaction->customer_name)}}</span></p>
            <p class="text-title fw-600 m-0">Application: <span class="text-black">{{$transaction->type ? Str::title($transaction->type->name) : "N/A"}} [{{$transaction->code}}] </span></p>
            <p class="text-title fw-600 m-0">Email Address: <span class="text-black">{{$transaction->email}}</span></p>
            <p class="text-title fw-600 m-0">Processor Status: <span class="badge badge-{{Helper::status_badge($transaction->status == 'DECLINED' && $transaction->is_resent == 1 ? 'pending' : $transaction->status)}} p-2">{{Str::title($transaction->status == 'DECLINED' && $transaction->is_resent == 1 ? 'RESENT' : $transaction->status)}}</span></p>
          </div>
          <div class="col-md-6">
            <p class="text-title fw-600 m-0">Peza Unit: <span class="text-black">{{$transaction->department ? Str::title($transaction->department->name) : "N/A"}}</span></p>
            <p class="text-title fw-600 m-0">Contact Number: <span class="text-black">+63{{$transaction->contact_number}}</span></p>
            <p class="text-title fw-600 m-0">Customer Notes: <span class="text-black">{{ $transaction->notes ?? 'N/A' }}</span></p>
            {{-- @if($transaction->status == "DECLINED") --}}
              <p class="text-title fw-600 m-0">Processor Remarks: <span class="text-black">{{ $transaction->remarks ?? 'N/A'}}</span></p>
            {{-- @endif --}}
          </div>
          <div class="col-md-6 mt-4">
            <p class="text-title fw-600 m-0">Pre-Processing Details:</p>
            <p class="text-title fw-600 m-0">Status: <span class="badge  badge-{{Helper::status_badge($transaction->transaction_status)}} p-2">{{Str::title($transaction->transaction_status)}}</span></p>
            <p class="fw-600 m-0" style="color: #DC3C3B;">Pre-Processing Fee: Php {{Helper::money_format($transaction->processing_fee)}} [{{$transaction->processing_fee_code}}]</p>
            <p class="text-title fw-600 m-0">Payment Status: <span class="badge  badge-{{Helper::status_badge($transaction->payment_status)}} p-2">{{Str::title($transaction->payment_status)}}</span></p>
          </div>
          <div class="col-md-6 mt-4">
            <p class="text-title fw-600 m-0">Post-Processing Details:</p>
            <p class="text-title fw-600 m-0">Status: <span class="badge  badge-{{Helper::status_badge($transaction->application_transaction_status)}} p-2">{{Str::title($transaction->application_transaction_status)}}</span></p>
            <p class="fw-600 m-0" style="color: #DC3C3B;">Post-Processing Fee: Php {{Helper::money_format($transaction->amount ? $transaction->amount : "0.00")}} [{{$transaction->transaction_status == "COMPELTED" ? $transaction->transaction_code:"N/A"}}]</p>
            <p class="text-title fw-600 m-0">Payment Status: <span class="badge  badge-{{Helper::status_badge($transaction->application_payment_status)}} p-2">{{Str::title($transaction->application_payment_status)}}</span></p>
          </div>
        </div>
      </div>
      <div class="card-body d-flex">
        <button class="btn btn-transparent p-3" data-toggle="collapse" data-target="#collapseExample"><i class="fa fa-download" style="font-size: 1.5rem;"></i></button>
        <p class="text-title pt-4 pl-3 fw-500">Review Attached Requirements : {{$count_file}} Item/s</p>
      </div>
    </div>
    <div class="collapse pt-2" id="collapseExample">
      <div class="card card-body card-rounded">
        <div class="row justify-content-center">
          <table class="table table-striped">
            <thead>
              <th>FileName</th>
              <th>Date Submitted</th>
              <th>Status</th>
            </thead>
            <tbody>
            @forelse($attachments as $index => $attachment)
              <tr>
                <td><a href="{{$attachment->directory}}/{{$attachment->filename}}" target="_blank" class="fw-600">{{$attachment->original_name}}</a></td>
                <td>{{Helper::date_format($attachment->created_at)}}</td>
                <td><p class="btn-sm text-center fw-600 text-black {{Helper::status_color($attachment->status)}}">{{Str::title($attachment->status)}}</p></td>
              </tr>
            @empty
            <h5>No Items available.</h5>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
    @if($transaction->status == "APPROVED")
    <div class="card card-rounded shadow-sm mt-2">
      <div class="card-body">
        <h5 class="text-title pb-2 mt-2"><span class="text-title-two"> Attached Documents</span></h5>
        <div class="table-responsive">
          <table class="table  table-striped table-wrap" style="table-layout: fixed;">
            <thead>
              <th>File Name</th>
              <th>File</th>
            </thead>
            <tbody>
              @forelse($attached_docs as $attached_doc)
              <tr>
                <td>{{$attached_doc->name}}</td>
                <td><a href="{{$attached_doc->directory}}/{{$attached_doc->filename}}" target="_blank">{{$attached_doc->original_name}}</a></td>
              </tr>
              @empty

              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
    @endif
  </div>
</section>
<!--team section end-->


@stop
@section('page-styles')
<style type="text/css">
    .custom-btn{
        padding: 5px 10px;
        border-radius: 10px;
        height: 37px;
    }
    .custom-btn:hover{
        background-color: #7093DC !important;
        color: #fff !important;
    }
    .btn-status{
        text-align: center;
        border-radius: 10px;
    }
    .table-font th{
        font-size: 16px;
        font-weight: bold;
    }
    .table-font td{
        font-size: 13px;
        font-weight: bold;
    }
</style>
@endsection
