<?php

namespace App\Http\Controllers;

use App\Models\Fungsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\OauthToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function login_index(){
        if(Auth::user()){
            return redirect('/pilih-cabang');
        }
        return view('login.index');
    }

    public function store_oauth($username, $from)
    {
        $encJson = Fungsi::getUsersDataFromApp($username);
        $time = $encJson['expiry_date'];
        Cookie::queue("username", $username, $time);
        Cookie::queue("from", $from, $time);

        return redirect("/");
    }

    public static function redirect()
    {
        if(Auth::user()){
            return redirect('/');
        }

        $query = http_build_query([
            'client_id' => env('ALKAYSAN_APP_ID'),
            'client_secret' => env('ALKAYSAN_CLIENT_SECRET_KEY'),
            'redirect_uri' => env('ALKAYSAN_REDIRECT_URI'),
            'response_type' => 'code',
            'scope' => ''
        ]);
    
        return redirect('https://account.alkaysan.co.id/oauth/authorize?'.$query);
    }

    public function logout()
    {
        // $token = Cookie::get('access_token');
        // $OauthToken = Http::withOptions([
        //     'verify' => false
        // ])->withHeaders([
        //     'Accept' => 'application/json',
        //     'Content-Type' => 'application/json',
        //     'Authorization' => 'Bearer ' . $token,
        // ])->get('https://account.alkaysan.co.id/oauth/logout', [
        //     'X-CSRF-TOKEN' => Cookie::get('XSRF-TOKEN')
        // ]);
        // $response = $OauthToken->json();

        Auth::logout();
        Cookie::queue(Cookie::forget('access_token'));
        Session::flush();
        return redirect('/');
    }

    public static function callback(Request $request)
    {
        if(Auth::user()){
            return redirect('/');
        }

        $code = $request->code;
        $token = Http::withOptions([
            'verify' => false
        ])->post('https://account.alkaysan.co.id/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => env('ALKAYSAN_APP_ID'),
            'client_secret' => env('ALKAYSAN_CLIENT_SECRET_KEY'),
            'redirect_uri' => env('ALKAYSAN_REDIRECT_URI'),
            'code' => $code
        ]);
        $token = $token->json();

        if(!isset($token['access_token'])){
            return redirect('/');
        }

        $OauthToken = Http::withOptions([
            'verify' => false
        ])->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token['access_token'],
        ])->get('https://account.alkaysan.co.id/api/user');

        $jsonResponse = [
            'status' => $OauthToken->status(),
            'info' => $OauthToken->json(),
            'access_token' => $token['access_token']
        ];

        Cookie::queue('access_token', $token['access_token'], env('SESSION_LIFETIME', time() + ( 365 * 24 * 60 * 60)));
        $data = $jsonResponse['info'];
        $msg = 'Kamu tidak memiliki izin masuk selain GM, Manager, CS, dan Kasir.';
        $success = false;

        if(in_array($data['jabatan_karyawan'], ['Customer Service', 'IT Support', 'Manager', 'CEO', 'General Manager', 'Finansial Dan Akutansi', 'Kasir'])){
            Auth::loginUsingId($data['id']);
            $success = true;
            $msg = 'logged in';
        }

        Session::put('login_message', $msg);

        $response = [
            'success' => $success,
            'message' => $msg
        ];

        return Inertia::render('Callback', $response);
    }

    public static function getUsers($params)
    {
        $users = Http::withOptions([
            'verify' => false
        ])->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->get('https://developers.alkaysan.co.id/api/v1/users?' . $params);

        return $users;
    }

    public function getBranch($params)
    {
        $branch = Http::withOptions([
            'verify' => false
        ])->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . Cookie::get('access_token'),
        ])->get('https://account.alkaysan.co.id/api/v1/branch?' . $params);

        return $branch->json();
    }

    public function login_auth()
    {
        return redirect('/pilih-cabang');
    }
}

