<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * RF02 / RF03 - Apenas mentores com credenciais verificadas (ou admins) podem publicar ofertas.
     */
    public function authorize(): bool
    {
        /** @var User|null $user */
        $user = $this->user();

        if ($user === null) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return $user->isMentor() && $user->hasVerifiedCredentials();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:180'],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'benefits' => ['nullable', 'string', 'max:2000'],
            'workload_hours' => ['required', 'integer', 'min:1', 'max:1000'],
            'language' => ['required', 'string', Rule::in(['pt-BR', 'en', 'es', 'fr'])],
            'category' => ['required', 'string', 'max:100'],
            'max_students' => ['required', 'integer', 'min:1', 'max:500'],
            'meeting_link' => ['nullable', 'url:https', 'max:500'],
            'status' => ['sometimes', 'string', Rule::in(['draft', 'published', 'closed'])],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'O título do curso/mentoria é obrigatório.',
            'description.min' => 'A descrição deve conter ao menos 20 caracteres.',
            'workload_hours.min' => 'A carga horária mínima é de 1 hora.',
            'meeting_link.url' => 'O link da sessão virtual (Meet/Zoom) deve ser uma URL HTTPS válida.',
        ];
    }
}
