<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
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
    use ThrottlesLogins;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    
    /**
     * Set maxAttempts & decayMinutes for base lockout
     *
     * @var int
     */
    protected $maxAttempts = 3;
    protected $decayMinutes = 10;

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

        $key = $this->throttleKey($request);
        $rateLimiter = $this->limiter();

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
                
                $attempts = $rateLimiter->attempts($key);

                $rateLimiter->clear($key.':timer');
            
                $reflection = new \ReflectionClass($rateLimiter);
                $property = $reflection->getProperty('cache');
                $property->setAccessible(true);
                $cache = $property->getValue($rateLimiter);

                $reflectionMethod = new \ReflectionMethod($rateLimiter, 'availableAt');
                $reflectionMethod->setAccessible(true);

                if($attempts < 3){
                    $blockMinutes=1;
                }
                else{
                    if($attempts < 5){
                        $blockMinutes=10;
                    }
                    else{
                        $blockMinutes=30;
                    }
                }
                
                $cache->add($key.':timer', $reflectionMethod->invoke($rateLimiter, $blockMinutes * 60), $blockMinutes);
                
                $added = $cache->add($key, 0, $blockMinutes);
                $hits = (int) $cache->increment($key, $attempts);
                if (! $added && $hits === 1) {
                    $cache->put($key, 1, $blockMinutes);
                }
            
                $reflectionMethod->setAccessible(false);
                $property->setAccessible(false);
            
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
}
