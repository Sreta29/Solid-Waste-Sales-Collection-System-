<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('search');
        $users = User::query();

        if ($query) {
            $users = $users->where('name', 'like', '%' . $query . '%')->orWhere('username', 'like', '%' . $query . '%')->orWhere('email', 'like', '%' . $query . '%');
        }

        $users = $users->where('role', 'user')->get();

        return view('admin/Users/index', [
            'users' => $users,
            'search' => $query
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin/Users/create',[
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'nullable',
            'email' => 'required',
            'password' => 'nullable',
            'status' => 'nullable',
            'role' => 'nullable',
        ]);

        User::create($validated);

        return redirect()->route('configuration.users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        $users = User::find($id);
        $roles = Role::all();
        return view('admin/Users/edit',['user' => $users, 'roles' => $roles]);
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
            'name' => 'required',
            'username' => 'nullable',
            'email' => 'required',
            'password' => 'nullable',
            'status' => 'nullable',
            'role' => 'nullable'
        ]);

        $users = User::find($id);

        $users->update($validated);

        return redirect()->route('configuration.users.index')->with('success', 'User List updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::transaction(function() use ($id) {
            // Delete related records in order_lists table
            DB::table('order_lists')->where('user_id', $id)->delete();

            // Delete the user
            $user = User::find($id);
            $user->delete();
        });

        return redirect()->route('configuration.users.index')->with('success', 'User List deleted successfully');
    }


    public static function columnValue(){
        $usernames = DB::table('users')->pluck('username');

        foreach ($usernames as $username) {
            echo $username.', ';
        }
    }
}
