<?php

namespace App\Http\Controllers\Api\Admin; // <-- هنا فين كان الخطأ وتصحح

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all users where 'is_admin' is false
        $users = User::where('is_admin', false)->get();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage by an admin.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'is_admin' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validatedData = $validator->validated();

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'is_admin' => $validatedData['is_admin'] ?? false,
        ]);

        return response()->json([
            'message' => 'User created successfully!',
            'user' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user || $user->is_admin) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user || $user->is_admin) {
            return response()->json(['message' => 'User not found or cannot be deleted'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully!'], 200);
    }
}