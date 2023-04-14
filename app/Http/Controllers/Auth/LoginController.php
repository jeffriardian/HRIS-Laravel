<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;

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

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'name';
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $user->update([
            'api_token' => Str::random(User::API_TOKEN_LENGTH),
            'last_logged_at' => now(),
            'last_logged_ip' => $request->getClientIp()
        ]);
    }
	
	public function login(Request $request)
    {
        //VALIDASI EMAIL DAN PASSWORD YANG DIKIRIMKAN
        $this->validate($request, [
            'name' => 'required|exists:users,name',
            'password' => 'required'
        ]);

        //ADA 3 VALUE YANG DIKIRIMKAN, YAKNI: EMAIL, PASSWORD DAN REMEMBER_ME
        //AMBIL SEMUA REQUEST TERSEBUT KECUALI REMEMBER ME KARENA YANG DIBUTUHKAN 
        //UNTUK OTENTIKASI ADALAH EMAIL DAN PASSWORD
        $auth = $request->except(['remember_me']);
        
        //MELAKUKAN PROSES OTENTIKASI
        if (auth()->attempt($auth, $request->remember_me) && auth()->user()->is_active == 1) {
            //APABILA BERHASIL, GENERATE API_TOKEN MENGGUNAKAN STRING RANDOM
            auth()->user()->update([
                'api_token' => Str::random(User::API_TOKEN_LENGTH),
                'last_logged_at' => now(),
                'last_logged_ip' => $request->getClientIp()
            ]);
            //KEMUDIAN KIRIM RESPONSENYA KE CLIENT UNTUK DIPROSES LEBIH LANJUT
            return response()->json(['status' => 'success', 'data' => auth()->user()->api_token], 200);
        }
        //APABILA GAGAL, KIRIM RESPONSE LAGI KE BAHWA PERMINTAAN GAGAL
        return response()->json(['status' => 'failed']);
    }
}
