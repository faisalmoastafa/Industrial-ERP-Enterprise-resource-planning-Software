<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\SessionCacheManager;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    protected function authenticated(Request $request, $user) {
        if ($user->is_active != 1) {
            app(SessionCacheManager::class)->destroyCurrentSession($request);

            return back()->with([
                'account_deactivated' => 'Your account is deactivated! Please contact with Super Admin.'
            ]);
        }

        session()->flash('login_success', true);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function logout(Request $request) {
        $sessions = app(SessionCacheManager::class);

        $sessions->destroyCurrentSession($request);

        return $sessions->hardenLogoutResponse(
            redirect()->route('login')
        );
    }
}
