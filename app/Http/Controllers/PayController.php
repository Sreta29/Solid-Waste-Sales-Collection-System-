<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderList;
use App\Models\Pays;
use Illuminate\Support\Facades\Auth;

class PayController extends Controller
{
    //
    public function create(Request $request, $id)
    {
        if (Auth::user()->role != 'user') {
            return redirect()->route('dashboard');
        }
        $order=OrderList::find($id);

        return view('admin/Pay/create', [
            'order' => $order,
        ]);
    }

    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:255'],
            'account_num' => ['required', 'string']
        ]);

        $order=OrderList::find($id);
        $total_pay = $order->quantity * $order->category->cost;
        $validated["order_id"] = $id;
        $validated["is_paid"] = 1;
        $validated["total_pay"] = $total_pay;
        Pays::create($validated);

        $order->amount = $total_pay;
        $order->save();

        return redirect('dashboard');
    }

}
