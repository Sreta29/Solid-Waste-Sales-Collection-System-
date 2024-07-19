<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrderList;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        if ($user->role != 'user') {
            $users = User::where('role', 'user')->latest()->take(3)->get();
        } else {
            $users = null;
        }
        
        $pending_orders_query = OrderList::where('status', 0)->orderBy('id', 'desc');
        if ($user->role == 'user') {
            $pending_orders_query = $pending_orders_query->where('user_id', $user->id);
        }

        $pending_orders = $pending_orders_query->oldest()->limit(3)->get();
        
        $completed_orders_query = OrderList::where('status', 1)->orderBy('id', 'desc');
        if ($user->role == 'user') {
            $completed_orders_query = $completed_orders_query->where('user_id', $user->id);
        }
        
        $completed_orders = $completed_orders_query->oldest()->limit(3)->get();
        
        return view('dashboard', [
            'users' => $users,
            'pending_orders' => $pending_orders,
            'completed_orders' => $completed_orders,
        ]);
    }
}
