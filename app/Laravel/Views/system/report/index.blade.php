@extends('system._layouts.main')

@section('content')
  <div class="row p-3">
    <div class="col-12">
      @include('system._components.notifications')
      <div class="row ">
        <div class="col-md-6">
          <h5 class="text-title text-uppercase">{{ $page_title }}</h5>
        </div>
        <div class="col-md-6 ">
          <p class="text-dim  float-right">EOR-PHP Processor Portal / Report</p>
        </div>
      </div>

    </div>

    <div class="col-12">
      <form>
        <div class="row">
          <div class="col-md-4 pb-2 mb-2">
            <label>Peza Unit</label>
            @if (Auth::user()->type == 'super_user' || Auth::user()->type == 'admin')
              {!! Form::select('department_id', $department, $selected_department_id, ['id' => 'input_department_id', 'class' => 'custom-select']) !!}
            @elseif(Auth::user()->type == "office_head" || Auth::user()->type ==
              "processor")
              <input type="text" class="form-control mr-sm-2"
                value="{{ Auth::user()->department ? Auth::user()->department->name : 'N/A' }}"
                readonly>
              <input type="hidden" name="selected_department_id"
                value="{{ $selected_department_id }}">
            @endif
          </div>
          <div class="col-md-4 pb-2 mb-2">
            <label>Application Type</label>
            {!! Form::select('application_id', $applications, $selected_application_id, ['id' => 'input_application_id', 'class' => 'custom-select']) !!}
          </div>
          <div class="col-md-4 pb-2 mb-2">
            <label>Application Amount Status</label>
            {!! Form::select('application_amount_status', $status, $selected_application_amount_status, ['id' => 'input_application_amount_status', 'class' => 'custom-select']) !!}
          </div>
        </div>
        <div class="row">
          <div class="col-md-3 pb-2 mb-2">
            <label class="d-none d-md-block">&nbsp;</label>
            <div class="input-group input-daterange d-flex align-items-center">
              <input type="text" class="form-control mr-sm-2"
                value="{{ $start_date }}" readonly="readonly" name="start_date">
              <div class="input-group-addon mx-2">to</div>
              <input type="text" class="form-control mr-sm-2"
                value="{{ $end_date }}" readonly="readonly" name="end_date">
            </div>
          </div>
          <div class="col-md-3 pb-2 mb-2">
            <label class="d-none d-md-block">&nbsp;</label>
            <div class="form-group has-search mb-0">
              <span class="fa fa-search form-control-feedback"></span>
              <input type="text" class="form-control mr-sm-2"
                id="input_keyword" name="keyword" value="{{ $keyword }}"
                placeholder="Keyword">
            </div>
          </div>
          <div class="col-md-3 pb-2 mb-2">
            <label>Type</label>
            {!! Form::select('type', $types, $selected_type, ['id' => 'input_type', 'class' => 'custom-select']) !!}
          </div>
          <div class="col-md-3 pb-2 mb-2">
            <label>Processing Fee Status</label>
            {!! Form::select('processing_fee_status', $status, $selected_processing_fee_status, ['id' => 'input_processing_fee_status', 'class' => 'custom-select']) !!}
          </div>
        </div>
        <div class="row justify-content-between px-2">
          <div class="pb-2">
            <button class="btn btn-primary btn-sm p-2"
              type="submit">Filter</button>
            <a href="{{ route('system.report.index') }}"
              class="btn btn-primary btn-sm p-2">Clear</a>
          </div>
          <div class="">
            <div class="row justify-content-sm-end mx-0">
              <div class="pb-2 pr-1">
                <a href="{{ route('system.report.pdf') }}?{{ collect(request()->query())->mapWithKeys(function($value, $key){
                    return [$key => $key . '=' . $value];
                  })->implode('&') }}"
                  class="btn btn-primary btn-sm p-2">Export
                  PDF</a>
                <a href="{{ route('system.report.export') }}?{{ collect(request()->query())->mapWithKeys(function($value, $key){
                    return [$key => $key . '=' . $value];
                  })->implode('&') }}"
                  class="btn btn-primary btn-sm p-2">Export Excel</a>
              </div>
              <div class="pb-2">
                <a href="{{ route('system.report.export_paid') }}"
                  class="btn btn-primary btn-sm p-2">Export Paid
                  Transactions</a>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="col-md-12">
      <div class="table-responsive shadow fs-15">
        <table class="table table-striped table-wrap">
          <thead>
            <tr class="text-center ">
              <th class="text-title p-3">Transaction Date</th>
              <th class="text-title p-3">Submitted By/Company Name</th>
              <th class="text-title p-3">Peza Unit</th>
              <th class="text-title p-3">Application Type</th>
              <th class="text-title p-3">Pre Processing Code</th>
              <th class="text-title p-3">Pre Processing Cost</th>
              <th class="text-title p-3">Pre Processing Code</th>
              <th class="text-title p-3">Post Processing Cost</th>
              <th class="text-title p-3">Processor/Status</th>
              <th class="text-title p-3">Is Validated ?</th>
              <th class="text-title p-3">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($transactions as $transaction)
              <tr class="text-center">
                <td>{{ Helper::date_format($transaction->created_at) }}</td>
                <td>
                  {{ $transaction->customer ? $transaction->customer->full_name : $transaction->customer_name }}/<br>{{ str::title($transaction->company_name) }}
                </td>
                <td>{{ $transaction->department->name }}</td>
                <td>
                  {{ $transaction->type ? Strtoupper($transaction->type->name) : 'N/A' }}<br>
                  {{ $transaction->code }}</td>
                <td>
                  {{ $transaction->type ? $transaction->type->pre_process->code : '---' }}
                </td>
                <td>
                  <div>
                    {{ Helper::money_format($transaction->processing_fee) ?: 0 }}
                  </div>
                  <div><small><span
                        class="badge badge-pill badge-{{ Helper::status_badge($transaction->payment_status) }} p-2">{{ Str::upper($transaction->payment_status) }}</span></small>
                  </div>
                  <div><small><span
                        class="badge badge-pill badge-{{ Helper::status_badge($transaction->transaction_status) }} p-2 mt-1">{{ Str::upper($transaction->transaction_status) }}</span></small>
                  </div>
                </td>
                <td>
                  {{ $transaction->type ? $transaction->type->post_process->code : '---' }}
                </td>
                <td>
                  <div>{{ Helper::money_format($transaction->amount) ?: '---' }}
                  </div>
                  <div><small><span
                        class="badge badge-pill badge-{{ Helper::status_badge($transaction->application_payment_status) }} p-2">{{ Str::upper($transaction->application_payment_status) }}</span></small>
                  </div>
                  <div><small><span
                        class="badge badge-pill badge-{{ Helper::status_badge($transaction->application_transaction_status) }} p-2 mt-1">{{ Str::upper($transaction->application_transaction_status) }}</span></small>
                  </div>
                </td>
                <td>
                  <div>
                    <span
                      class="badge badge-pill badge-{{ Helper::status_badge($transaction->status == 'DECLINED' && $transaction->is_resent == 1 ? 'pending' : $transaction->status) }} p-2">{{ Str::upper($transaction->status == 'DECLINED' && $transaction->is_resent == 1 ? 'RESENT' : $transaction->status) }}</span>
                  </div>
                  @if ($transaction->status == 'APPROVED')
                    <div class="mt-1">
                      <p>
                        {{ $transaction->admin ? $transaction->admin->full_name : '---' }}
                      </p>
                    </div>
                  @endif
                </td>
                <td>{{ $transaction->is_validated == '0' ? 'No' : 'Yes' }}</td>
                <td>
                  <button type="button" class="btn btn-sm p-0"
                    data-toggle="dropdown" style="background-color: transparent;">
                    <i class="mdi mdi-dots-horizontal"
                      style="font-size: 30px"></i></button>
                  <div class="dropdown-menu"
                    aria-labelledby="dropdownMenuSplitButton2">
                    <a class="dropdown-item"
                      href="{{ route('system.transaction.show', [$transaction->id]) }}">View
                      transaction</a>
                    <!--  <a class="dropdown-item action-delete"  data-url="#" data-toggle="modal" data-target="#confirm-delete">Remove Record</a> -->
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="11" class="text-center"><i>No transaction Records
                    Available.</i></td>
              </tr>
            @endforelse
          </tbody>
          <tfoot>
            <tr>
              <td colspan="11" class="text-center">
                <div class="d-flex justify-content-md-center">
                  {{ $transactions->appends(request()->query())->links() }}
                </div>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@stop


@section('page-styles')
<link rel="stylesheet"
  href="{{ asset('system/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<style type="text/css">
  .input-daterange input {
    background: #fff !important;
  }

  .btn-sm {
    border-radius: 10px;
  }

</style>

@stop

@section('page-scripts')
<script
  src="{{ asset('system/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}">
</script>
<script type="text/javascript">
  $.fn.get_application_type = function(department_id, input_purpose, selected) {
    $(input_purpose).empty().prop('disabled', true)
    $(input_purpose).append($('<option>', {
      value: "",
      text: "Loading Content..."
    }));
    $.getJSON("{{ route('web.get_application_type') }}?department_id=" +
      department_id,
      function(result) {
        $(input_purpose).empty().prop('disabled', true)
        $.each(result.data, function(index, value) {
          // console.log(index+value)
          $(input_purpose).append($('<option>', {
            value: index,
            text: value
          }));
        })

        $(input_purpose).prop('disabled', false)
        $(input_purpose).prepend($('<option>', {
          value: "",
          text: "--Choose Application Type--"
        }))

        if (selected.length > 0) {
          $(input_purpose).val($(input_purpose + " option[value=" +
            selected + "]").val());

        } else {
          $(input_purpose).val($(input_purpose + " option:first").val());
          //$(this).get_extra(selected)
        }
      });
    // return result;
  };

  $(function() {
    $('.input-daterange').datepicker({
      format: "yyyy-mm-dd"
    });

    $(".action-delete").on("click", function() {
      var btn = $(this);
      $("#btn-confirm-delete").attr({
        "href": btn.data('url')
      });
    });

    $("#input_department_id").on("change", function() {
      var department_id = $(this).val()
      var _text = $("#input_department_id option:selected").text();
      $(this).get_application_type(department_id,
        "#input_application_id", "")
      $('#input_department_name').val(_text);
    })


  })

</script>
@stop
