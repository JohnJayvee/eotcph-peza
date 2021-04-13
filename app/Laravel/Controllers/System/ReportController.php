<?php

namespace App\Laravel\Controllers\System;

/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;

use App\Laravel\Models\Exports\ReportTransactionExport;
use App\Laravel\Models\Exports\TransactionSummaryExport;


use App\Laravel\Models\{Transaction,Department,Application};

/* App Classes
 */
use Carbon,Auth,DB,Str,Helper,Excel,PDF;

class ReportController extends Controller
{
    protected $data;
	protected $per_page;

	public function __construct(){
		parent::__construct();
		array_merge($this->data, parent::get_data());
		if (Auth::user()) {
			if (Auth::user()->type == "super_user" || Auth::user()->type == "admin") {
				$this->data['department'] = ['' => "Choose Peza Unit"] + Department::pluck('name', 'id')->toArray();
			}elseif (Auth::user()->type == "office_head" || Auth::user()->type == "processor") {
				$this->data['department'] = ['' => "Choose Peza Unit"] + Department::where('id',Auth::user()->department_id)->pluck('name', 'id')->toArray();
			}
		}else{
			$this->data['department'] = ['' => "Choose Department"] + Department::pluck('name', 'id')->toArray();
		}
		$this->data['types'] = ['' => "Choose Type",'PENDING' => "New Submission" , 'APPROVED' => "Approved Applications",'DECLINED' => "Declined Applications",'resent' => "Resent Applications"];

		$this->data['status'] = ['' => "Choose Payment Status",'PAID' => "Paid" , 'UNPAID' => "Unpaid"];
		$this->data['payment_methods'] = ['' => "Choose Payment Method",'ONLINE' => "Online" , 'OTC' => "Over the Counter"];


		$this->per_page = env("DEFAULT_PER_PAGE",10);
	}

	public function  index(PageRequest $request){
			$this->data['page_title'] = "Reports";
			$auth = Auth::user();

			$first_record = Transaction::orderBy('created_at','ASC')->first();
			$start_date = $request->get('start_date',Carbon::now()->startOfMonth());

			if($first_record){
				$start_date = $request->get('start_date',$first_record->created_at->format("Y-m-d"));
			}
			$this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
			$this->data['end_date'] = Carbon::parse($request->get('end_date',Carbon::now()))->format("Y-m-d");

			$this->data['selected_department_id'] = $auth->type == "office_head" || $auth->type == "processor" ? $auth->department_id : $request->get('department_id');
			$this->data['selected_type'] = $request->get('type');
			$this->data['selected_application_id'] = $request->get('application_id');
			$this->data['selected_processing_fee_status'] = $request->get('processing_fee_status');
			$this->data['selected_application_amount_status'] = $request->get('application_amount_status');
			$this->data['keyword'] = Str::lower($request->get('keyword'));

			if ($auth->type == "office_head") {
				$this->data['applications'] = ['' => "Choose Applications"] + Application::where('department_id',$auth->department_id)->pluck('name', 'id')->toArray();
			}elseif ($auth->type == "processor") {
				$this->data['applications'] = ['' => "Choose Applications"] + Application::whereIn('id',explode(",", $auth->application_id))->pluck('name', 'id')->toArray();
			}else{
				$this->data['applications'] = ['' => "Choose Applications"] + Application::where('department_id',$request->get('department_id'))->pluck('name', 'id')->toArray();
			}

			$this->data['resent'] = NULL;
			if ($request->get('type') == "resent") {
				$this->data['resent'] = "1";
			}
			$this->data['transactions'] = Transaction::where(function($query){
				if(strlen($this->data['keyword']) > 0){
					return $query->WhereRaw("LOWER(company_name)  LIKE  '%{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(concat(fname,' ',lname))  LIKE  '%{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(code) LIKE  '%{$this->data['keyword']}%'");
					}
				})
				->where(function($query){
					if ($this->data['auth']->type == "office_head" || $this->data['auth']->type == "processor") {
						return $query->where('department_id',$this->data['auth']->department_id);
					}else{
						if(strlen($this->data['selected_department_id']) > 0){
							return $query->where('department_id',$this->data['selected_department_id']);
						}
					}
				})
				->where(function($query){
					if ($this->data['auth']->type == "processor") {
						if(strlen($this->data['selected_application_id']) > 0){
							return $query->where('application_id',$this->data['selected_application_id']);
						}else{
							return $query->whereIn('application_id',explode(",", $this->data['auth']->application_id));
						}

					}else{
						if(strlen($this->data['selected_application_id']) > 0){
							return $query->where('application_id',$this->data['selected_application_id']);
						}
					}

				})
				->where(function($query){
					if(strlen($this->data['selected_type']) > 0 and strlen($this->data['resent']) == 0){
						return $query->where('status',$this->data['selected_type']);
					}
				})
				->where(function($query){
					if(strlen($this->data['resent']) > 0){
						return $query->where('is_resent',$this->data['resent']);
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_processing_fee_status']) > 0){
						return $query->where('payment_status',$this->data['selected_processing_fee_status']);
					}
				})
				->where(function($query){
					if(strlen($this->data['selected_application_amount_status']) > 0){
						return $query->where('application_payment_status',$this->data['selected_application_amount_status']);
					}
				})
				->where(DB::raw("DATE(created_at)"),'>=',$this->data['start_date'])
				->where(DB::raw("DATE(created_at)"),'<=',$this->data['end_date'])
				->orderBy('created_at',"DESC")->paginate($this->per_page);

			return view('system.report.index',$this->data);


	}

	public function export(PageRequest $request){
 		$this->data['page_title'] = "Reports";
		$auth = Auth::user();

		$first_record = Transaction::orderBy('created_at','ASC')->first();
		$start_date = $request->get('start_date',Carbon::now()->startOfMonth());

		if($first_record){
			$start_date = $request->get('start_date',$first_record->created_at->format("Y-m-d"));
		}
		$this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
		$this->data['end_date'] = Carbon::parse($request->get('end_date',Carbon::now()))->format("Y-m-d");

		$this->data['selected_department_id'] = $auth->type == "office_head" || $auth->type == "processor" ? $auth->department_id : $request->get('department_id');
		$this->data['selected_type'] = $request->get('type');
		$this->data['selected_application_id'] = $request->get('application_id');
		$this->data['selected_processing_fee_status'] = $request->get('processing_fee_status');
		$this->data['selected_application_amount_status'] = $request->get('application_amount_status');
		$this->data['keyword'] = Str::lower($request->get('keyword'));

		if ($auth->type == "office_head") {
			$this->data['applications'] = ['' => "Choose Applications"] + Application::where('department_id',$auth->department_id)->pluck('name', 'id')->toArray();
		}elseif ($auth->type == "processor") {
			$this->data['applications'] = ['' => "Choose Applications"] + Application::whereIn('id',explode(",", $auth->application_id))->pluck('name', 'id')->toArray();
		}else{
			$this->data['applications'] = ['' => "Choose Applications"] + Application::where('department_id',$request->get('department_id'))->pluck('name', 'id')->toArray();
		}

		$this->data['resent'] = NULL;
		if ($request->get('type') == "resent") {
			$this->data['resent'] = "1";
		}

		$transactions = Transaction::where(function($query){
			if(strlen($this->data['keyword']) > 0){
				return $query->WhereRaw("LOWER(company_name)  LIKE  '%{$this->data['keyword']}%'")
						->orWhereRaw("LOWER(concat(fname,' ',lname))  LIKE  '%{$this->data['keyword']}%'")
						->orWhereRaw("LOWER(code) LIKE  '%{$this->data['keyword']}%'");
				}
			})
			->where(function($query){
				if ($this->data['auth']->type == "office_head" || $this->data['auth']->type == "processor") {
					return $query->where('department_id',$this->data['auth']->department_id);
				}else{
					if(strlen($this->data['selected_department_id']) > 0){
						return $query->where('department_id',$this->data['selected_department_id']);
					}
				}
			})
			->where(function($query){
				if ($this->data['auth']->type == "processor") {
					if(strlen($this->data['selected_application_id']) > 0){
						return $query->where('application_id',$this->data['selected_application_id']);
					}else{
						return $query->whereIn('application_id',explode(",", $this->data['auth']->application_id));
					}

				}else{
					if(strlen($this->data['selected_application_id']) > 0){
						return $query->where('application_id',$this->data['selected_application_id']);
					}
				}

			})
			->where(function($query){
				if(strlen($this->data['selected_type']) > 0 and strlen($this->data['resent']) == 0){
					return $query->where('status',$this->data['selected_type']);
				}
			})
			->where(function($query){
				if(strlen($this->data['resent']) > 0){
					return $query->where('is_resent',$this->data['resent']);
				}
			})
			->where(function($query){
				if(strlen($this->data['selected_processing_fee_status']) > 0){
					return $query->where('payment_status',$this->data['selected_processing_fee_status']);
				}
			})
			->where(function($query){
				if(strlen($this->data['selected_application_amount_status']) > 0){
					return $query->where('application_payment_status',$this->data['selected_application_amount_status']);
				}
			})
			->where(DB::raw("DATE(created_at)"),'>=',$this->data['start_date'])
			->where(DB::raw("DATE(created_at)"),'<=',$this->data['end_date'])
			->orderBy('created_at',"DESC")->get();

			$transaction_count = Transaction::where('transaction_status', "COMPLETED")->where(DB::raw("DATE(created_at)"),'>=',$this->data['start_date'])->where(DB::raw("DATE(created_at)"),'<=',$this->data['end_date'])->select(DB::raw('count(*) as count, DATE(created_at) as date'))
                            ->groupBy('date')
                            ->get();

       	return Excel::download(new ReportTransactionExport($transactions,$transaction_count), 'transaction-record'.Carbon::now()->format('Y-m-d').'.xlsx');
    }

    public function pdf (PageRequest $request){
    	$auth = Auth::user();

    	$first_record = Transaction::orderBy('created_at','ASC')->first();
		$start_date = $request->get('start_date',Carbon::now()->startOfMonth());

		if($first_record){
			$start_date = $request->get('start_date',$first_record->created_at->format("Y-m-d"));
		}
		$this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
		$this->data['end_date'] = Carbon::parse($request->get('end_date',Carbon::now()))->format("Y-m-d");

		$this->data['selected_department_id'] = $auth->type == "office_head" || $auth->type == "processor" ? $auth->department_id : $request->get('department_id');
		$this->data['selected_type'] = $request->get('type');
		$this->data['selected_application_id'] = $request->get('application_id');
        $this->data['selected_processing_fee_status'] = $request->get('processing_fee_status');
        $this->data['selected_application_amount_status'] = $request->get('application_amount_status');

		$this->data['keyword'] = Str::lower($request->get('keyword'));

		$this->data['resent'] = NULL;
		if ($request->get('type') == "resent") {
			$this->data['resent'] = "1";
		}

        $this->data['transactions'] = Transaction::where(function($query){
				if(strlen($this->data['keyword']) > 0){
					return $query->WhereRaw("LOWER(company_name)  LIKE  '%{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(concat(fname,' ',lname))  LIKE  '%{$this->data['keyword']}%'")
							->orWhereRaw("LOWER(code) LIKE  '%{$this->data['keyword']}%'");
					}
				})
                ->where(function($query){
					if ($this->data['auth']->type == "office_head" || $this->data['auth']->type == "processor") {
						return $query->where('department_id',$this->data['auth']->department_id);
					}else{
						if(strlen($this->data['selected_department_id']) > 0){
							return $query->where('department_id',$this->data['selected_department_id']);
						}
					}
				})
                ->where(function($query){
					if ($this->data['auth']->type == "processor") {
						if(strlen($this->data['selected_application_id']) > 0){
							return $query->where('application_id',$this->data['selected_application_id']);
						}else{
							return $query->whereIn('application_id',explode(",", $this->data['auth']->application_id));
						}

					}else{
						if(strlen($this->data['selected_application_id']) > 0){
							return $query->where('application_id',$this->data['selected_application_id']);
						}
					}

				})
				->where(function($query){
					if(strlen($this->data['selected_type']) > 0 and strlen($this->data['resent']) == 0){
						return $query->where('status',$this->data['selected_type']);
					}
				})
				->where(function($query){
					if(strlen($this->data['resent']) > 0){
						return $query->where('is_resent',$this->data['resent']);
					}
				})
                ->where(function($query){
					if(strlen($this->data['selected_processing_fee_status']) > 0){
						return $query->where('payment_status',$this->data['selected_processing_fee_status']);
					}
				})
                ->where(function($query){
					if(strlen($this->data['selected_application_amount_status']) > 0){
						return $query->where('application_payment_status',$this->data['selected_application_amount_status']);
					}
				})
				->where(DB::raw("DATE(created_at)"),'>=',$this->data['start_date'])
				->where(DB::raw("DATE(created_at)"),'<=',$this->data['end_date'])
				->orderBy('created_at',"DESC")->get();


		$pdf = PDF::loadView('pdf.report',$this->data)->setPaper('a4', 'landscape');;
		return $pdf->download('report-' . date('Y-m-d-H-i-s') . '.pdf');

    }


    public function export_paid(PageRequest $request){

    	$first_record = Transaction::orderBy('created_at','ASC')->first();
		$start_date = $request->get('start_date',Carbon::now()->startOfMonth());

		if($first_record){
			$start_date = $request->get('start_date',$first_record->created_at->format("Y-m-d"));
		}
		$this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
		$this->data['end_date'] = Carbon::parse($request->get('end_date',Carbon::now()))->format("Y-m-d");

		$transactions = Transaction::where('transaction_status', "COMPLETED")->where(DB::raw("DATE(created_at)"),'>=',$this->data['start_date'])->where(DB::raw("DATE(created_at)"),'<=',$this->data['end_date'])->orderBy('created_at',"ASC")->get();

    	$transaction_count = Transaction::where('transaction_status', "COMPLETED")->where(DB::raw("DATE(created_at)"),'>=',$this->data['start_date'])->where(DB::raw("DATE(created_at)"),'<=',$this->data['end_date'])->select(DB::raw('count(*) as count, DATE(created_at) as date'))
                            ->groupBy('date')
                            ->get();

       	return Excel::download(new TransactionSummaryExport($transactions,$transaction_count), 'transaction-record'.Carbon::now()->format('Y-m-d').'.xlsx');
    }

}
