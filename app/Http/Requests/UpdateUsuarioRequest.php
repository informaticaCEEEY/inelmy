<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUsuarioRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $usuario = $this->route('usuario');

        $rules = [
            'nombre' => ['required', 'regex:/^[a-zA-ZÀ-ÖØ-öø-ÿ]+(?:(?: |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+)*$/', 'min:2', 'max:100'],
            'primer_apellido' => ['required', 'regex:/^[a-zA-ZÀ-ÖØ-öø-ÿ]+(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+)*$/', 'min:2', 'max:100'],
            'segundo_apellido' => ['nullable', 'regex:/^[a-zA-ZÀ-ÖØ-öø-ÿ]+(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+)*$/', 'min:2', 'max:100'],
            'curp' => ['nullable', 'uppercase', 'size:18', 'regex:/^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/'],
            'email' => ['required', 'lowercase', 'string', 'email', 'max:255', Rule::unique('usuario', 'email')->ignore($usuario->id)],
            'celular' => ['required', 'numeric', 'digits:10'],
            'password' => ['nullable', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            'role_id' => ['required', 'numeric', 'exists:roles,id'],
        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'Este campo es requerido.',
            'regex' => 'Este campo tiene caracteres inválidos.',
            'curp.regex' => 'La CURP tiene caracteres inválidos, verifiquela.',
            'numeric' => 'Solo se permiten números.',
            'celular.digits' => 'El celular estar conformado por :digits dígitos.',
            'curp.size' => 'La CURP debe estar conformada por :size caracteres.',
            'min' => 'Este campo debe tener al menos :min caracteres.',
            'max' => 'Este campo no debe exceder los :max caracteres.',
            'email.email' => 'Ingrese un correo electrónico valido.',
            'lowercase' => 'Este campo solo permite el uso de minúsculas.',
            'uppercase' => 'Este campo solo permite el uso de mayúsculas.',
            'email.unique' => 'Este correo ya se encuentra registrado.',
        ];
    }
}
