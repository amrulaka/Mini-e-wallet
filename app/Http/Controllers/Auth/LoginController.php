<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $input = $request->all();
        $messages = [
            'required' => ':attribute harus di isi',
        ];
        $validator = Validator::make($input, [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'

        ], $messages);

        if ($validator->fails()) {
            return $this->validationHelper->response($validator);
        }

        $userByEmail = User::where('email', '=', $input['email']);
        if ($userByEmail->count() > 0) {
            $user = $userByEmail->first();  
        }else{
            return response()->json($this->responseHelper->errorCustom(204, 'Akun tidak ditemukan'), 200);
        }

        $checked_pin = \Hash::check($input['password'], $user->password);

        if ($checked_pin) {
            $credentials = array(
                'email' => $user->email,
                'password' => $input['password']
            );
            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json($this->responseHelper->errorCustom(403, 'email atau password salah'), 403);
                }
            }catch (JWTException $e) {
                return response()->json($this->responseHelper->errorCustom(500, 'Could not create token'), 500);
            }
            return response()->json($this->responseHelper->successWithData([
                'token' => $token,
                'expired_at' => $expired_at->format('Y-m-d H:i:s')
            ]), 200);

        } else {
            return response()->json($this->responseHelper->errorCustom(403, 'email atau password salah'), 403);
        }
    }
}
