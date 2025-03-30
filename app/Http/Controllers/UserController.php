<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
//    public function signup(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'user_name' => 'required|string|min:4|max:255|unique:users,user_name',
//            'password' => 'required|string|min:6',
//
//        ]);
//
//        if ($validator->fails()) {
//            return response(['errors' => $validator->errors()->all()], 422);
//        }
//
//        $user = User::create([
//            'user_name' => $request->user_name,
//            'password' => bcrypt($request->password),
//            'type'=> 'Admin'
//        ]);
//
//        $accessToken = $user->createToken('authToken')->accessToken;
//
//        return response([
//            'message' => trans('messages.account_created'),
//            'user' => $user,
//            'access_token' => $accessToken,
//        ]);
//    }

    public function signup(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|min:4|max:255|unique:users,user_name',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $encryptedUserName = Crypt::encryptString($request->user_name);

        $user = User::create([
            'user_name' => $encryptedUserName,
            'password' => bcrypt($request->password),
            'type' => 'Admin',
        ]);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response([
            'message' => trans('messages.account_created'),
            'user' => $user,
            'access_token' => $accessToken,
        ]);
    }

//    public function login(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'user_name' => 'required|string|min:4|max:255',
//            'password' => 'required|string|min:6',
//        ]);
//
//        if ($validator->fails()) {
//            return response(['errors' => $validator->errors()->all()], 422);
//        }
//
//        $credentials = $request->only('user_name', 'password');
//
//        if (!auth()->attempt($credentials)) {
//            return response(['errors' => trans('messages.login_failed')], 422);
//        }
//
//        $user = auth()->user();
//        $token = $user->createToken('Personal Access Token')->accessToken;
//
//        return response([
//            'message' => trans('messages.login_success'),
//            'data' => $user,
//            'token' => $token,
//        ]);
//    }

    public function login(Request  $request)
{
    $validator = Validator::make($request->all(), [
        'user_name' => 'required|string|min:4|max:255',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response(['errors' => $validator->errors()->all()], 422);
    }

// الحصول على جميع المستخدمين (غير فعّال ولكن ضروري في هذه الحالة)
$users = User::all();

$foundUser = null;

foreach ($users as $user) {
    try {
        $decryptedUserName = Crypt::decryptString($user->user_name);
        if ($decryptedUserName === $request->user_name) {
            $foundUser = $user;
            break;
        }
    } catch (DecryptException $e) {
        continue;
    }
}

if (!$foundUser || !password_verify($request->password, $foundUser->password)) {
    return response(['errors' => trans('messages.login_failed')], 422);
}

$token = $foundUser->createToken('Personal Access Token')->accessToken;

return response([
    'message' => trans('messages.login_success'),
    'data' => $foundUser,
    'token' => $token,
]);
}


    public function profile()
    {
        $user_data = auth()->user();
        return response()->json([
            "message" => trans('messages.profile_success'),
            "data" => $user_data,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => trans('messages.logout_success'),
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'user_name' => 'sometimes|string|min:4|max:255',
            'password' => 'sometimes|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user->update([
            'user_name' => $request->input('user_name', $user->user_name),
            'password' => $request->input('password') ? bcrypt($request->input('password')) : $user->password,
        ]);

        return response()->json([
            'message' => trans('messages.update_success'),
            'user' => $user,
        ]);
    }
}
