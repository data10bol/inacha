<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Auth;
use Carbon\Carbon;
use Adldap\AdldapInterface;

//use App\User;

class LoginController extends Controller
{
  /**
   * @var Adldap
   */
  protected $ldap;

  /**
   * Constructor.
   *
   * @param AdldapInterface $adldap
   */
  public function __construct(AdldapInterface $ldap)
  {
    $this->middleware('guest')->except('logout');
    $this->ldap = $ldap;
  }
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
  //protected $redirectTo = '/home';
  public function showLoginForm()
  {
    return view('login');
  }

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function login(LoginRequest $request)
  {
    $credentials = request()->except(['_token']);
    if (Auth::attempt($credentials)){
      if(Auth::user()->employee == 0){
        $user = \App\User::find(Auth::user()->id);
        $user->status = 0;
        $user->save();
        Auth::logout();
        \Toastr::error("Error en el ingreso.",
        $title = 'ATENCIÓN',
        $options = [
          'closeButton' => 'true',
          'hideMethod' => 'slideUp',
          'closeEasing' => 'easeInBack',
        ]);
        return redirect()->back();
      }

      $user = Auth::user();
      $user->update([
        'login_at' => Carbon::now()->toDateTimeString(),
        'ip' => $request->getClientIp()
      ]);
      logrec('login', \Route::currentRouteName());
      return redirect()->route('dashboard');
    }

    \Toastr::error("Error en el ingreso",
      $title = 'ATENCIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]);
    logrec('error', \Route::currentRouteName());
    return back()
      ->withInput(request(['username']));

  }

  public function logon()
  {
    return redirect('/');
  }

  public function ldap($user, $pass)
  {

    $user = chekar('decifrar',$user);
    $pass = chekar('decifrar',$pass);

    $credentials = request()->except(['_token']);

    if(Auth::attempt(['username' => $user, 'password' => $pass])) {
      $user = Auth::user();
      $user->update([
        'login_at' => Carbon::now()->toDateTimeString(),
        'ip' => request()->ip()
      ]);
      logrec('login', \Route::currentRouteName());
      return redirect()->route('dashboard');
    }

    \Toastr::error("Error en el ingreso",
      $title = 'ATENCIÓN',
      $options = [
        'closeButton' => 'true',
        'hideMethod' => 'slideUp',
        'closeEasing' => 'easeInBack',
      ]);
    logrec('error', \Route::currentRouteName());
    return redirect('/');

  }


  public function logout(Request $request)
  {
    //$user = User::find(Aut):

    logrec('logout', \Route::currentRouteName());

    $this->guard()->logout();
    $request->session()->invalidate();
    return $this->loggedOut($request) ?: redirect('/');
  }


  
}

function chekar($action='cifrar',$string=false){
  $action = trim($action);
  $output = false;
  $myKey = 'LedZeppelin1970';
  $myIV = 'StairwayToHeaven';
  $encrypt_method = 'AES-256-CBC';
  $secret_key = hash('sha256',$myKey);
  $secret_iv = substr(hash('sha256',$myIV),0,16);
  if ( $action && ($action == 'cifrar' || $action == 'decifrar') && $string )
  {
    $string = trim(strval($string));
    if ( $action == 'cifrar' )
    {
      $output = openssl_encrypt($string, $encrypt_method, $secret_key, 0, $secret_iv);
      $output = str_replace("/", "_", $output);
      $output = str_replace("=", "-", $output);
    };

    if ( $action == 'decifrar' )
    {
      $string = str_replace("_", "/", $string);
      $string = str_replace("-", "=", $string);
      $output = openssl_decrypt($string, $encrypt_method, $secret_key, 0, $secret_iv);

    };
  };

  return $output;
};
