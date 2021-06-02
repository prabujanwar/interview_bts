<?php

namespace App\Http\Controllers;

use App\Events\FetchDataEvent;
use App\Events\PushDataEvent;
use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class JWTAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:2,100',
            'email' => 'required|email|unique:users|max:50',
            'password' => 'required|string|min:6',
            'role_id' => 'required',
        ]);

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'Successfully registered',
            'user' => $user,
        ], 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'email' => 'required|email',
        //     'password' => 'required|string|min:6',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }

        // if (!$token = auth()->attempt($validator->validated())) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        $credentials = $request->only('email', 'password', 'role_id');
        if (!$token = auth()->attempt($credentials)) {
            return $this->respondNotAuthorised();
        }
        return $this->createNewToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        // return response()->json(auth()->user());

        return response()->json(['user' => Auth::user()], 200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function fetchPoint()
    {
        $point = User::select('saldo', 'point')->where('id', Auth::user()->id)->first();

        return response()->json([
            'point' => $point,
        ]);
    }

    public function fetchWithdraw()
    {
        $withdraw = Withdraw::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->with('user')->get();

        return response()->json([
            'withdraw' => $withdraw,
        ]);
    }

    public function fetchWithdraws()
    {
        $withdraw = Withdraw::where('status', 0)->orderBy('created_at', 'asc')->with('user')->get();

        return response()->json([
            'withdraws' => $withdraw,
        ]);
    }

    public function confirm(Request $request)
    {
        $saldo = $request->point / 10;
        $saldoAwal = User::where('id', $request->id_user)->pluck('saldo')->first();
        $saldoFinal = $saldoAwal + $saldo;
        $check = User::where('id', $request->id_user)->update(['saldo' => $saldoFinal]);

        if ($check) {
            Withdraw::where('id', $request->id)->update(['status' => 1]);
            event(new PushDataEvent("Point telah dikonfirmasi silahkan Cek Saldo dan Histori Penukaran"));
            return response()->json([
                'status' => true,
            ]);
        }

    }

    public function reject(Request $request)
    {
        Withdraw::where('id', $request->id)->update(['status' => 2]);
        event(new PushDataEvent("Point telah dikonfirmasi silahkan Cek Saldo dan Histori Penukaran"));
        return response()->json([
            'status' => true,
        ]);
    }

    public function point(Request $request)
    {
        // dd($request->point);
        $point = User::where('id', Auth::user()->id)->update(['point' => $request->point]);

        return response()->json([
            'point' => $point,
        ]);
    }

    public function tukar(Request $request)
    {
        $name = User::where('id', Auth::user()->id)->pluck('name')->first();
        $point = User::where('id', Auth::user()->id)->pluck('point')->first();
        $pointFinal = $point - $request->point;
        $tukar = Withdraw::create(['user_id' => Auth::user()->id, 'num_point' => $request->point, 'status' => 0]);
        User::where('id', Auth::user()->id)->update(['point' => $pointFinal]);
        event(new FetchDataEvent($name));

        return response()->json([
            'point' => $tukar,
        ]);
    }
}
