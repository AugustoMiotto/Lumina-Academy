<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOerMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * RF08 - Publicar recurso educacional (OER)
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && ($user->isMentor() || $user->isAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
            'title' => ['required', 'string', 'min:3', 'max:180'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'file_path_or_url' => ['required', 'string', 'max:500'],
            'public' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'public' => $this->boolean('public', true),
        ]);
    }
}
