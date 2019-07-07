<?php

namespace App\Http\Controllers;

use App\Maximum;
use App\Vacation;
use App\User;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Transformers\Json;

class VacationController extends Controller
{
	public function index(Request $request)
	{
		$vacation = Vacation::query();

		if($request->has('user_id')) {
			$vacation->where('user_id',$request->user_id);
		}

		if($request->has('year')) {
			$vacation->where('year',$request->year);
		}

		if($request->has('status')) {
			$vacation->where('status',$request->status);
		}

		$vacation = $vacation->get();

		return json::response($vacation);
	}

    public function setMaximum(Request $request)
    {
    	$now = Carbon::now();

    	$max = new Maximum;
    	$max->total = $request->total;
    	$max->year = $now->year;
    	$max->save();

    	return json::response($max);
    }

    public function requestVacation(Request $request)
    {
    	$now = Carbon::now();

    	$start = Carbon::parse($request->start_at);
    	$end = Carbon::parse($request->end_at);

    	$total = $start->diffInDays($end);

    	$check = Vacation::where('year',$now->year)->where('status',0)->first();

    	$maximum_req = User::findOrFail($request->user_id);

    	if(!$maximum_req->sisa) {
    		$diff = $maximum_req->sisa-$total;

    		if($diff<0) {
    			return json::exception('Maaf jatah cuti anda sudah habis');
    		}
    	}

    	if($check) {
    		return json::exception('Tidak bisa melakukan request cuti lagi');
    	}

    	$vacation = new Vacation;
    	$vacation->user_id = $request->user_id;
    	$vacation->year = $now->year;
    	$vacation->total = $total;
    	$vacation->status = 0;
    	$vacation->save();
    }

    public function approveVacation(Request $request, $id)
    {

    	$vacation = Vacation::findOrFail($id);

    	$now = Carbon::now();
    	$user = User::findOrFail($vacation->user_id);

    	if(!$user->sisa) {
    		$max = Maximum::where('year', $now->year)->orderBy('created_at','desc')->first();
    		$user->sisa = $max->total-$vacation->total;
    	} else {
    		$user->sisa-= $vacation->total;
    	}

    	$vacation->status = 1;
    	$vacation->save();

    	$user->save();

    	return json::response($user);

    }
}
