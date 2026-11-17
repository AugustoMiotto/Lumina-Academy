<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login (RF01).
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Autentica o usuário (RF01).
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()
                ->intended(route('courses.index'))
                ->with('success', 'Bem-vindo(a) de volta à Lumina Academy!');
        }

        return back()
            ->withErrors(['email' => 'As credenciais informadas não conferem com nossos registros.'])
            ->onlyInput('email');
    }

    /**
     * Exibe o formulário de cadastro (RF01).
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Registra um novo usuário como aprendiz ou mentor (RF01).
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', 'string', Rule::in([User::ROLE_APRENDIZ, User::ROLE_MENTOR])],
            'education_level' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'education_level' => $validated['education_level'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'verified_credentials' => false,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Conta criada com sucesso na Lumina Academy!');
    }

    /**
     * Encerra a sessão do usuário autenticado (RF01).
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('oer.index')
            ->with('success', 'Sessão encerrada com segurança.');
    }

    /**
     * Solicita/atualiza dados institucionais para validação de credenciais de mentor (RF02).
     */
    public function submitMentorCredentials(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'education_level' => ['required', 'string', 'max:100'],
            'bio' => ['required', 'string', 'min:30', 'max:1500'],
        ]);

        $request->user()->update([
            'education_level' => $validated['education_level'],
            'bio' => $validated['bio'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Credenciais institucionais enviadas para validação!');
    }

    /**
     * Valida as credenciais de um mentor pela instituição/admin (RF02).
     */
    public function verifyMentorCredentials(User $mentor): RedirectResponse
    {
        if (! $mentor->isMentor()) {
            return redirect()
                ->back()
                ->with('error', 'O usuário selecionado não possui perfil de mentor.');
        }

        $mentor->update([
            'verified_credentials' => true,
        ]);

        return redirect()
            ->back()
            ->with('success', "Credenciais do mentor {$mentor->name} validadas com sucesso!");
    }
}
