<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'wing' => ['required', 'string', 'in:auto,food,fitness'],
            'service_type' => ['required', 'string', 'max:100'],
            'client_name' => ['required', 'string', 'min:2', 'max:150'],
            'client_email' => ['required', 'email', 'max:150'],
            'client_phone' => ['required', 'string', 'min:6', 'max:30'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'metadata' => ['nullable', 'array'],
        ];
    }

    /**
     * Custom messages for validation errors in Romanian.
     */
    public function messages(): array
    {
        return [
            'wing.required' => 'Te rugăm să selectezi departamentul / aripa dorită.',
            'wing.in' => 'Aripa selectată nu este validă.',
            'service_type.required' => 'Te rugăm să selectezi serviciul dorit.',
            'client_name.required' => 'Numele complet este obligatoriu.',
            'client_name.min' => 'Numele trebuie să conțină cel puțin 2 caractere.',
            'client_email.required' => 'Adresa de email este obligatorie.',
            'client_email.email' => 'Te rugăm să introduci o adresă de email validă.',
            'client_phone.required' => 'Numărul de telefon este obligatoriu.',
            'scheduled_at.required' => 'Data și ora sunt obligatorii.',
            'scheduled_at.after' => 'Data și ora programării trebuie să fie în viitor.',
        ];
    }
}
