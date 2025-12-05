<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class ResponsableLoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/entrepot/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Temporairement désactivé pour résoudre le problème de redirection
        // $this->middleware('guest')->except('logout');
    }

    /**
     * Show the Responsable login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.responsable-login');
    }

    /**
     * Handle a login request to the Responsable interface.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Vérifier que l'utilisateur a le rôle responsable
            if ($user->role === 'responsable' || $user->role_id === 3) {
                $request->session()->regenerate();
                
                return redirect()->intended($this->redirectTo);
            } else {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['Vous n\'avez pas les permissions pour accéder à l\'interface Responsable.'],
                ]);
            }
        }
        
        throw ValidationException::withMessages([
            'email' => ['Les identifiants fournis ne correspondent pas à nos enregistrements.'],
        ]);
    }

    /**
     * Log the user out of the Responsable interface.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/responsable/login');
    }
}
