<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
   public function index()  
   {
        $last = User::findOrFail(Auth::user()->user_id);
        $last->last_login = now(); //last login timestamp
        $last->save();

        // $active_employee = User::where('archived', 'No')->take(5)->get();

        // greetings
            // $current_hour = Carbon::now()->format('H');

            // if($current_hour>=0 && $current_hour<=12)
            // {
            //     $greeting = '<img src="' . asset('img/icons/sunny.png') . '" alt="greetings" class="rounded" style="width: 35px;" />' . ' Good Morning';
            // }
            // elseif ($current_hour>=12 && $current_hour<=18)
            // {
            //     $greeting = '<img src="' . asset('img/icons/afternoon.png') . '" alt="greetings" class="rounded" style="width: 35px;" />' . ' Good Afternoon';
            // }
            // elseif ($current_hour>=18 && $current_hour<=24) 
            // {
            //     $greeting = '<img src="' . asset('img/icons/night.png') . '" alt="greetings" class="rounded" style="width: 35px;" />' . ' Good Evening';
            // }
            // else{
            //     $greeting = 'Hello!';
            // }
       return view('dashboard');
   }

}
