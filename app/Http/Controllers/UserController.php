<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'email' => 'required|unique:users|max:255',
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'password' => 'required',
            'gender' => 'required|in:male,female',
            'birthdate' => 'required|date',
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'password' => \Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User created!'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return User::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $this->validate(request(), [
            'email' => 'required|max:255|unique:users,email,'. $id .'',
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'gender' => 'required|in:male,female',
            'birthdate' => 'required|date',
        ]);

        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
        ]);

        return response()->json(['message' => 'User updated!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted!'], 200);
    }
}
