<?php

namespace App\Http\Controllers;

use App\Models\OrderList;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class OrderListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('search');
        $user = Auth::user();

        $orderlistQuery = OrderList::query(); // Start a base query

        if ($user->role == 'user') {
            $orderlistQuery->where('user_id', $user->id);
        }

        $orderlist = $orderlistQuery->get();

        return view('admin/OrderList/index', [
            'orderlists' => $orderlist,
            'search' => $query
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function create()
    {
        $collectors = User::where('role','wastage_collector')->get();
        $users = User::where('role','user')->get();
        $wastages = Category::all();
        // dd($wastages);

        return view('admin/OrderList/create', [
            'collectors' => $collectors,
            'users' => $users,
            'wastages' => $wastages,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'quantity' => ['required', 'integer', 'between:1,50'],
            'order_date' => ['required', 'date', 'after_or_equal:today'],
            'collect_date' => ['nullable', 'date', 'after_or_equal:order_date'],
            'collector_id' => ['nullable', 'exists:users,id'],
            'status' => ['nullable', 'boolean'],
            'waste_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            // 'amount' => ['nullable', 'decimal:8,2'],
            'user_waste_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
        ]);

        $user = Auth::user();
        $imageName = $user->id.'-waste-'.time().'.'.$request->user_waste_image->extension();
        $request->user_waste_image->move(public_path('images'), $imageName);
        $validated['user_waste_image'] = 'images/'.$imageName;

        $validated["user_id"] = $user->id;

        // $validated['userid'] = $user->id;

        // $validated['orderid'] = OrderList::generateCustomOrderId();

        // OrderList::create($validated);
        $id = DB::table('order_lists')->insertGetId($validated);

        return redirect()->route('pay.create', $id)->with('success', 'Order List created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $orderlist = OrderList::find($id);
        $collectors = User::where('role','Wastage Collector')->get();
        return view('admin/OrderList/edit',['orderlist' => $orderlist, 'collectors' => $collectors]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'quantity' => ['nullable', 'integer', 'between:1,100'],
            'order_date' => ['nullable', 'date', 'after_or_equal:today'],
            'collect_date' => ['required', 'date', 'after_or_equal:order_date'],
            'collector_id' => ['nullable', 'exists:users,id'],
            'status' => ['nullable', 'boolean'],
            'waste_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'amount' => ['nullable', 'decimal:8,2']
        ]);

        $order = OrderList::find($id);
        $imageName = $order->id.'-'.time().'.'.$request->waste_image->extension();
        $request->waste_image->move(public_path('images'), $imageName);
        $validated['waste_image'] = 'images/'.$imageName;

        $validated['status'] = 1;
        $validated['collector_id'] = Auth::user()->id;

        $order->update($validated);

        return redirect()->route('configuration.orderlist.index')->with('success', 'Order List updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $orderlist = OrderList::find($id);
        $orderlist->delete();

        return redirect()->route('configuration.orderlist.index')->with('success', 'Order List deleted successfully');
    }
}
