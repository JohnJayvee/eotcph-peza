<?php

namespace App\Laravel\Controllers\System;

/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\System\ApplicationRequest;
/*
 * Models
 */
use App\Laravel\Models\Application;
use App\Laravel\Models\Department;
use App\Laravel\Models\AccountCode;
use App\Laravel\Models\ApplicationRequirements;

/* App Classes
 */
use Carbon,Auth,DB,Str,Helper;

class ApplicationController extends Controller
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
			if (Auth::user()->type == "admin" || Auth::user()->type == "office_head") {
				$this->data['user_type'] = ['' => "Choose Type",'office_head' => "Department Head",'processor' => "Processor"];
			}else {
				$this->data['user_type'] = ['' => "Choose Type",'admin' => "Admin",'office_head' => "Department Head",'processor' => "Processor"];
			}
		}else{
			$this->data['department'] = ['' => "Choose Peza Unit"] + Department::pluck('name', 'id')->toArray();
			$this->data['user_type'] = ['' => "Choose Type",'admin' => "Admin",'office_head' => "Department Head",'processor' => "Processor"];
		}

		$this->data['account_codes'] =  ['' => "Choose Account Code"] + AccountCode::pluck('code','id')->toArray();
		$this->data['requirements'] =  ApplicationRequirements::pluck('name','id')->toArray();
		$this->per_page = env("DEFAULT_PER_PAGE",10);
	}

	public function  index(PageRequest $request){
		$this->data['page_title'] = "Application";
		$auth = Auth::user();
		
		$this->data['keyword'] = Str::lower($request->get('keyword'));
		$this->data['selected_department_id'] = $auth->type == "office_head" ? $auth->department_id : $request->get('department_id');

		$this->data['applications'] = Application::orderBy('created_at',"DESC")->where(function($query){
		if(strlen($this->data['keyword']) > 0){
			return $query->WhereRaw("LOWER(name)  LIKE  '%{$this->data['keyword']}%'");
			}
		})
		->where(function($query){
			if ($this->data['auth']->type == "office_head") {
				return $query->where('department_id',$this->data['auth']->department_id);
			}else{
				if(strlen($this->data['selected_department_id']) > 0){
					return $query->where('department_id',$this->data['selected_department_id']);
				}
			}
		})->paginate($this->per_page);
		return view('system.application.index',$this->data);
	}

	public function  create(PageRequest $request){
		$this->data['page_title'] .= "Application - Add new record";
		return view('system.application.create',$this->data);
	}
	public function store(ApplicationRequest $request){
		DB::beginTransaction();
		try{
			
			$new_application = new Application;
			$new_application->department_id = $request->get('department_id');
			$new_application->name = $request->get('name');
			$new_application->pre_processing_code = $request->get('pre_processing_code');
			$new_application->pre_processing_description = $request->get('pre_processing_description');
			$new_application->pre_processing_cost = $request->get('pre_processing_cost') > 0 ? Helper::db_amount($request->get('pre_processing_cost')) : 0.00;

			$new_application->post_processing_code = $request->get('post_processing_code');
			$new_application->post_processing_description = $request->get('post_processing_description');
			$new_application->post_processing_cost = $request->get('post_processing_cost') > 0 ? Helper::db_amount($request->get('post_processing_cost')) : 0.00;
			$new_application->requirements_id = implode(",", $request->get('requirements_id'));
			$new_application->save();

			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "New Application has been added.");
			return redirect()->route('system.application.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}

	public function  edit(PageRequest $request,$id = NULL){
		$this->data['page_title'] .= " - Edit record";
		$this->data['application'] = $request->get('application_data');
		return view('system.application.edit',$this->data);
	}

	public function  update(ApplicationRequest $request,$id = NULL){
		DB::beginTransaction();
		try{	
			$application = $request->get('application_data');
			$application->department_id = $request->get('department_id');
			$application->name = $request->get('name');
			$application->pre_processing_code = $request->get('pre_processing_code');
			$application->pre_processing_description = $request->get('pre_processing_description');
			$application->pre_processing_cost = $request->get('pre_processing_cost') > 0 ? Helper::db_amount($request->get('pre_processing_cost')) : "0.00";
			$application->post_processing_code = $request->get('post_processing_code');
			$application->post_processing_description = $request->get('post_processing_description');
			$application->post_processing_cost = $request->get('post_processing_cost') > 0 ? Helper::db_amount($request->get('post_processing_cost')) : "0.00";
			$application->requirements_id = implode(",", $request->get('requirements_id'));
			$application->save();

			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Application had been modified.");
			return redirect()->route('system.application.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}

	

	public function  destroy(PageRequest $request,$id = NULL){
		$application = $request->get('application_data');
		DB::beginTransaction();
		try{
			$application->delete();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Application removed successfully.");
			return redirect()->route('system.application.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}
}
