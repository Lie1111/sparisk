<?php
namespace App\Http\Controllers\Api;

use App\Mail\ProfileCreation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'error' => 'Invalid credentials',
                'data' => null
            ], 401);
        }
    }

    public function register(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response()->json([
                'message' => 'User Phone No. already exists for other account',
                'data' => $user
            ], 409);
        }

        $email = User::where('email', $request->email)->first();
        if ($email) {
            return response()->json([
                'message' => 'User Email address already exists for other account',
                'data' => $email
            ], 409);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $role = Role::findByName('Public');
        if ($role)
            $user->assignRole($role->name);

        Mail::to($user->email)->send(new ProfileCreation($user->email, 'Your account has been created successfully!', $user->email));

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'data' => $user
        ], 201);
    }
}