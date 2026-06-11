<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class DashboardController extends Controller
{
   public function index()  
   {
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
