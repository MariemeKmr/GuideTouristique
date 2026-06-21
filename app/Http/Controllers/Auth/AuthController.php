<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
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

        $key = $this->throttleKey($request);

        // Trop de tentatives recentes : on bloque temporairement.
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $secondes = RateLimiter::availableIn($key);

            return back()
                ->withErrors(['email' => "Trop de tentatives de connexion. Reessayez dans {$secondes} secondes."])
                ->onlyInput('email');
        }

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            // Echec : on incremente le compteur (fenetre d'une minute).
            RateLimiter::hit($key, 60);

            return back()
                ->withErrors(['email' => 'Ces identifiants ne correspondent à aucun compte.'])
                ->onlyInput('email');
        }

        // Succes : on remet le compteur a zero.
        RateLimiter::clear($key);

        // Sécurité : régénère l'ID de session après connexion.
        $request->session()->regenerate();

        // Redirige vers la page demandée à l'origine, sinon le dashboard du rôle.
        return redirect()->intended(route('dashboard'));
    }

    /** Cle de limitation basee sur l'email et l'adresse IP. */
    private function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower((string) $request->input('email')) . '|' . $request->ip());
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
    | Mot de passe oublie / reinitialisation
    |--------------------------------------------------------------------------
    */

    /** Formulaire de demande de lien de reinitialisation. */
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    /** Envoie le lien de reinitialisation par email. */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']], [], ['email' => 'adresse email']);

        $status = PasswordBroker::sendResetLink($request->only('email'));

        if ($status === PasswordBroker::RESET_THROTTLED) {
            return back()
                ->withErrors(['email' => 'Veuillez patienter avant de redemander un lien.'])
                ->onlyInput('email');
        }

        // Message neutre, identique que le compte existe ou non (anti-enumeration).
        return back()->with('success', "Si un compte correspond a cette adresse, un lien de reinitialisation vient d'etre envoye.");
    }

    /** Formulaire de saisie du nouveau mot de passe. */
    public function showResetPassword(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /** Enregistre le nouveau mot de passe. */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [], [
            'email'    => 'adresse email',
            'password' => 'mot de passe',
        ]);

        $status = PasswordBroker::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                // Le cast 'password' => 'hashed' du modele se charge du hachage.
                $user->forceFill(['password' => $password])->save();
                $user->setRememberToken(Str::random(60));

                event(new PasswordReset($user));
            }
        );

        return $status === PasswordBroker::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Votre mot de passe a ete reinitialise, vous pouvez vous connecter.')
            : back()->withErrors(['email' => __($status)])->onlyInput('email');
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
