<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Connexion
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche le formulaire de connexion.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Traite la tentative de connexion.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [], [
            'email'    => 'adresse email',
            'password' => 'mot de passe',
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Ces identifiants ne correspondent à aucun compte.'])
                ->onlyInput('email');
        }

        // Sécurité : régénère l'ID de session après connexion.
        $request->session()->regenerate();

        // Redirige vers la page demandée à l'origine, sinon le dashboard du rôle.
        return redirect()->intended(route('dashboard'));
    }

    /*
    |--------------------------------------------------------------------------
    | Inscription
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche le formulaire d'inscription.
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Crée un nouveau compte (visiteur ou taximan uniquement).
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            // On interdit la création d'un compte admin via l'inscription publique.
            'role'       => ['required', 'in:visiteur,taximan'],
            'password'   => ['required', 'confirmed', Password::min(8)],
        ], [], [
            'first_name' => 'prénom',
            'last_name'  => 'nom',
            'phone'      => 'téléphone',
            'email'      => 'adresse email',
            'role'       => 'type de compte',
            'password'   => 'mot de passe',
        ]);

        // Le cast 'password' => 'hashed' du modèle se charge du hachage.
        $user = User::create($data);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | Déconnexion
    |--------------------------------------------------------------------------
    */

    /**
     * Déconnecte l'utilisateur et invalide la session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
