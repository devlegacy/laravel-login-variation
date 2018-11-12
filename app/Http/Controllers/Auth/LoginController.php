<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->reconfigure('admin');
    }

     /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function reconfigure($type) {
        if($type=== 'admin') {
            Config::set('auth.defaults.passwords', 'customadmin');
            Config::set('auth.guards.web.provider', 'customadmin');
        }
        else if($type=== 'cliente'){
            Config::set('auth.defaults.passwords', 'customclient');
            Config::set('auth.guards.web.provider', 'customclient');
        }
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        if($request->post('login-for') === 'admin' || $request->post('login-for') === 'cliente')
        {
            $request->validate([
                'user' => 'required|string',
                'password' => 'required|string',
            ]);
        } else {

            $request->validate([
                $this->username() => 'required|string',
                'password' => 'required|string',
            ]);
        }
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if($request->post('login-for') === 'admin' || $request->post('login-for') === 'cliente')
        {
            return $request->only('user', 'password');
        }
        return $request->only($this->username(), 'password');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        // return 'email';
        return 'user';
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        if($request->post('login-for') === 'admin')
        {
            return $this->guard('webcustomadmin')->attempt(
                $request->only('user', 'password'), $request->filled('remember')
            );
        } else if($request->post('login-for') === 'cliente'){
            return $this->guard('webcustomclient')->attempt(
                $request->only('user', 'password'), $request->filled('remember')
            );
        }
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

}
