<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoauthorshipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * RF13 - Registrar coautoria
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
            'coauthor_id' => ['required', 'integer', 'exists:users,id', 'different:mentor_id'],
            'contribution_description' => ['required', 'string', 'min:15', 'max:2000'],
        ];
    }
}
