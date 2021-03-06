<?php

namespace App\Laravel\Controllers\System;

/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\System\ZoneLocationRequest;
use App\Laravel\Requests\System\UploadRequest;

/*
 * Models
 */
use App\Laravel\Models\Imports\ZoneLocationImport;

use App\Laravel\Models\ZoneLocation;
use App\Laravel\Models\Region;
use App\Laravel\Models\City;
use App\Laravel\Models\Province;

/* App Classes
 */
use Carbon,Auth,DB,Str,Excel;
class ZoneLocationController extends Controller
{	
	protected $data;
	protected $per_page;
	
	public function __construct(){
		parent::__construct();
		array_merge($this->data, parent::get_data());
		// $this->data['regions'] = ['' => '--Select Region--'] + Region::pluck('regDesc', 'regCode')->toArray();
		$this->data['zone_types'] = ['' => "Choose Zone Type",'private_economic_zone' =>  "Private Economic Zone",'public_economic_zone' => "Public Economic Zone"];
		
		$this->per_page = env("DEFAULT_PER_PAGE",10);
	}	


    public function  index(PageRequest $request){
		$this->data['page_title'] = "Zone Location";

		$this->data['keyword'] = Str::lower($request->get('keyword'));
		$this->data['selected_type'] = $request->get('type');

		$this->data['zone_locations'] = ZoneLocation::orderBy('created_at',"DESC")->where(function($query){
		if(strlen($this->data['keyword']) > 0){
			return $query->WhereRaw("LOWER(code)  LIKE  '%{$this->data['keyword']}%'")
					->orWhereRaw("LOWER(ecozone) LIKE  '%{$this->data['keyword']}%'")
					->orWhereRaw("LOWER(nature) LIKE  '%{$this->data['keyword']}%'");
			}
		})
		->where(function($query){
			if(strlen($this->data['selected_type']) > 0){
				return $query->where('type',$this->data['selected_type']);
			}
		})
		->orderBy('created_at',"DESC")->paginate($this->per_page);
		

		return view('system.zone-location.index',$this->data);
	}

	public function  create(PageRequest $request){
		$this->data['page_title'] .= "Zone Location - Add new record";
		
		return view('system.zone-location.create',$this->data);
	}

	public function store(ZoneLocationRequest $request){
		DB::beginTransaction();
		try{
			$new_zone_location = new ZoneLocation;
			$new_zone_location->fill($request->all());
			$new_zone_location->save();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "New Zone Location has been added.");
			return redirect()->route('system.zone_location.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();

		}
	}

	public function edit(PageRequest $request,$id = NULL){
		$this->data['page_title'] .= "Zone Location - Edit record";
		$this->data['zone_location'] = $request->get('zone_location_data');

		return view('system.zone-location.edit',$this->data);
	}

	public function  update(ZoneLocationRequest $request,$id = NULL){
		DB::beginTransaction();
		try{

			$zone_location = $request->get('zone_location_data');
			$zone_location->fill($request->all());
			$zone_location->save();

			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Zone Location had been modified.");
			return redirect()->route('system.zone_location.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}
	public function  destroy(PageRequest $request,$id = NULL){
		$zone = $request->get('zone_location_data');
		DB::beginTransaction();
		try{
			$zone->delete();
			DB::commit();
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Zone Location removed successfully.");
			return redirect()->route('system.zone_location.index');
		}catch(\Exception $e){
			DB::rollback();
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
			return redirect()->back();
		}
	}

	public function get_region(PageRequest $request){
		$id = $request->get('region_code');
		$region = Region::pluck('regDesc', 'regCode');
	
		$response['msg'] = "List of Cities";
		$response['status_code'] = "PARAMETER_LIST";
		$response['data'] = $region;
		callback:

		return response()->json($response, 200);

	}


	public function get_provinces(PageRequest $request){
		$id = $request->get('region_code');
		$provinces = Province::where('regCode',$id)->pluck('provDesc', 'provCode');
	
		$response['msg'] = "List of Cities";
		$response['status_code'] = "PARAMETER_LIST";
		$response['data'] = $provinces;
		callback:

		return response()->json($response, 200);

	}

	public function get_municipalities(PageRequest $request){
		$id = $request->get('province_code');
		$cities = City::where('provCode',$id)->pluck('citymunDesc', 'citymunCode');
	
		$response['msg'] = "List of Cities";
		$response['status_code'] = "PARAMETER_LIST";
		$response['data'] = $cities;
		callback:
		
		return response()->json($response, 200);
	}

	public function  upload(PageRequest $request){
		$this->data['page_title'] .= " - Bulk Upload Zone Location";
		return view('system.zone-location.upload',$this->data);
	}

	public function upload_zone_location(UploadRequest $request) 
	{	
		try {
		    Excel::import(new ZoneLocationImport, request()->file('file'));

		    session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Importing data was successful.");
			return redirect()->route('system.zone_location.index');
		} catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
		     $failures = $e->failures();
		     
		     foreach ($failures as $failure) {
		         $failure->row(); // row that went wrong
		         $failure->attribute(); // either heading key (if using heading row concern) or column index
		         $failure->errors(); // Actual error messages from Laravel validator
		         $failure->values(); // The values of the row that has failed.
		     }
		    dd($failures);
		    session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Something went wrong.");
			return redirect()->route('system.zone_location.index');
		}
	    
	}
}
