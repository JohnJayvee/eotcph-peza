<?php

namespace App\Laravel\Controllers\System;

// Request Validator
use App\Laravel\Models\Application;

use App\Laravel\Models\Department;
use App\Laravel\Models\Exports\ReportTransactionExport;

use App\Laravel\Models\Exports\TransactionSummaryExport;
use App\Laravel\Models\Transaction;
use App\Laravel\Requests\PageRequest;

// App Classes
use Auth;
use Carbon;
use DB;
use Excel;
use Helper;
use PDF;
use Str;

class ReportController extends Controller
{
    protected $data;

    protected $per_page;

    public function __construct()
    {
        parent::__construct();
        array_merge($this->data, parent::get_data());

        if (Auth::user()) {
            if ('super_user' == Auth::user()->type || 'admin' == Auth::user()->type) {
                $this->data['department'] = ['' => 'Choose Peza Unit'] + Department::pluck('name', 'id')->toArray();
            } elseif ('office_head' == Auth::user()->type || 'processor' == Auth::user()->type) {
                $this->data['department'] = ['' => 'Choose Peza Unit'] + Department::where('id', Auth::user()->department_id)->pluck('name', 'id')->toArray();
            }
        } else {
            $this->data['department'] = ['' => 'Choose Department'] + Department::pluck('name', 'id')->toArray();
        }
        $this->data['types'] = ['' => 'Choose Type', 'PENDING' => 'New Submission', 'APPROVED' => 'Approved Applications', 'DECLINED' => 'Declined Applications', 'resent' => 'Resent Applications'];

        $this->data['status'] = ['' => 'Choose Payment Status', 'PAID' => 'Paid', 'UNPAID' => 'Unpaid'];
        $this->data['payment_methods'] = ['' => 'Choose Payment Method', 'ONLINE' => 'Online', 'OTC' => 'Over the Counter'];

        $this->per_page = env('DEFAULT_PER_PAGE', 10);
    }

    public function index(PageRequest $request)
    {
        $this->data['page_title'] = 'Reports';

        $this->data['transactions'] = $this->query($request)->paginate($this->per_page);

        return view('system.report.index', $this->data);
    }

    public function export(PageRequest $request)
    {
        $this->data['page_title'] = 'Reports';

        $transactions = $this->query($request)->get();

        $transaction_count = Transaction::where('transaction_status', 'COMPLETED')->where(DB::raw('DATE(created_at)'), '>=', $this->data['start_date'])->where(DB::raw('DATE(created_at)'), '<=', $this->data['end_date'])->select(DB::raw('count(*) as count, DATE(created_at) as date'))
            ->groupBy('date')
            ->get();

        return Excel::download(new ReportTransactionExport($transactions, $transaction_count), 'transaction-record' . Carbon::now()->format('Y-m-d') . '.xlsx');
    }

    public function pdf(PageRequest $request)
    {
        $this->data['transactions'] = $this->query($request)->get();

        $pdf = PDF::loadView('pdf.report', $this->data)->setPaper('a4', 'landscape');

        return $pdf->download('report-' . date('Y-m-d-H-i-s') . '.pdf');
    }

    public function export_paid(PageRequest $request)
    {
        $first_record = Transaction::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', Carbon::now()->startOfMonth());

        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format('Y-m-d'));
        }
        $this->data['start_date'] = Carbon::parse($start_date)->format('Y-m-d');
        $this->data['end_date'] = Carbon::parse($request->get('end_date', Carbon::now()))->format('Y-m-d');

        $transactions = Transaction::where('transaction_status', 'COMPLETED')->where(DB::raw('DATE(created_at)'), '>=', $this->data['start_date'])->where(DB::raw('DATE(created_at)'), '<=', $this->data['end_date'])->orderBy('created_at', 'ASC')->get();

        $transaction_count = Transaction::where('transaction_status', 'COMPLETED')->where(DB::raw('DATE(created_at)'), '>=', $this->data['start_date'])->where(DB::raw('DATE(created_at)'), '<=', $this->data['end_date'])->select(DB::raw('count(*) as count, DATE(created_at) as date'))
            ->groupBy('date')
            ->get();

        return Excel::download(new TransactionSummaryExport($transactions, $transaction_count), 'transaction-record' . Carbon::now()->format('Y-m-d') . '.xlsx');
    }

    protected function query(PageRequest $request)
    {
        $auth = Auth::user();

        $first_record = Transaction::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', Carbon::now()->startOfMonth());

        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format('Y-m-d'));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format('Y-m-d');
        $this->data['end_date'] = Carbon::parse($request->get('end_date', Carbon::now()))->format('Y-m-d');

        $this->data['selected_department_id'] = 'office_head' == $auth->type || 'processor' == $auth->type ? $auth->department_id : $request->get('department_id');
        $this->data['selected_type'] = $request->get('type');
        $this->data['resent'] = 'resent' == $request->get('type') ? 1 : 0;
        $this->data['selected_application_id'] = $request->get('application_id');
        $this->data['selected_processing_fee_status'] = $request->get('processing_fee_status');
        $this->data['selected_application_amount_status'] = $request->get('application_amount_status');
        $this->data['keyword'] = Str::lower($request->get('keyword'));

        if ('office_head' == $auth->type) {
            $this->data['applications'] = ['' => 'Choose Applications'] + Application::where('department_id', $auth->department_id)->pluck('name', 'id')->toArray();
        } elseif ('processor' == $auth->type) {
            $this->data['applications'] = ['' => 'Choose Applications'] + Application::whereIn('id', explode(',', $auth->application_id))->pluck('name', 'id')->toArray();
        } else {
            $this->data['applications'] = ['' => 'Choose Applications'] + Application::where('department_id', $request->get('department_id'))->pluck('name', 'id')->toArray();
        }

        $query = Transaction::when($this->data['keyword'], function ($query) {
            return $query->WhereRaw("LOWER(company_name)  LIKE  '%{$this->data['keyword']}%'")
                ->orWhereRaw("LOWER(concat(fname,' ',lname))  LIKE  '%{$this->data['keyword']}%'")
                ->orWhereRaw("LOWER(code) LIKE  '%{$this->data['keyword']}%'");
        })->when($this->data['selected_department_id'], function ($query) {
            if (in_array($this->data['auth']->type, ['office_head', 'processor'])) {
                return $query->where('department_id', $this->data['auth']->department_id);
            } else {
                return $query->where('department_id', $this->data['selected_department_id']);
            }
        })->where(function ($query) {
            if ('processor' == $this->data['auth']->type) {
                if ($this->data['selected_application_id']) {
                    $query->where('application_id', $this->data['selected_application_id']);
                } else {
                    $query->whereIn('application_id', explode(',', $this->data['auth']->application_id));
                }
            } else {
                if ($this->data['selected_application_id']) {
                    $query->where('application_id', $this->data['selected_application_id']);
                }
            }
        })->where(function ($query) {
            if ($this->data['resent']) {
                $query->where('is_resent', 1)
                    ->where('status', 'DECLINED');
            } elseif ('DECLINED' == $this->data['selected_type']) {
                $query->where('is_resent', 0)
                    ->where('status', 'DECLINED');
            } elseif ($this->data['selected_type']) {
                $query->where('status', $this->data['selected_type']);
            }
        })->when($this->data['selected_processing_fee_status'], function ($query) {
            return $query->where('payment_status', $this->data['selected_processing_fee_status']);
        })->when($this->data['selected_application_amount_status'], function ($query) {
            return $query->where('application_payment_status', $this->data['selected_application_amount_status']);
        })
            ->where(DB::raw('DATE(created_at)'), '>=', $this->data['start_date'])
            ->where(DB::raw('DATE(created_at)'), '<=', $this->data['end_date'])
            ->orderBy('created_at', 'DESC');

        return $query;
    }
}
