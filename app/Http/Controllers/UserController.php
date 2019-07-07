<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Maximum;
use App\Vacation;
use App\Transformers\Json;
use Hash;
use Auth;

use \Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $user = User::query();

        if($request->has('karyawan')) {
            $user->whereRoleIs('karyawan');
        }

        $user = $user->get();

        return json::response($user);
    }

    public function store(Request $request)
    {
        $now = Carbon::now();

        $max = Maximum::where('year', $now->year)->orderBy('created_at','desc')->first();
        
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->sisa = $max->total;
        $user->save();


        $user->attachRole('affiliate-cs');
    }

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('nApp')->accessToken;
            return response()->json(['success' => $success], 200);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
 }
