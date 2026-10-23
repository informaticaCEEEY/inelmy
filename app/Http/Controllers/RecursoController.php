<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use App\Models\TipoRecurso;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\ValidationException;

class RecursoController extends Controller
{

    private function reglaArchivoPorTipo(TipoRecurso $tipo): File
    {
        $config = config("recursos.tipos.{$tipo->clave}");

        if (!is_array($config)) {
            throw ValidationException::withMessages([
                'tipo_recurso_id' => 'El tipo de recurso no está habilitado para cargar archivos.',
            ]);
        }

        $extensiones = $config['extensiones'];

        $regla = ($config['imagen'] ?? false)
            ? File::image()
            : File::types($extensiones);

        return $regla
            ->extensions($extensiones)
            ->max($config['max_kb']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validacion = $request->validate([
            'clave' => ['required', 'string', 'max:50', 'unique:'.Recurso::class],
        ],[
            'required' => 'Este campo es requerido.',
            'regex' => 'Este campo tiene caracteres inválidos.',
            'numeric' => 'Solo se permiten números.',
            'digits' => 'El campo debe estar conformado por :digits dígitos.',
            'min' => 'Este campo debe tener al menos :min caracteres.',
            'max' => 'Este campo no debe exceder los :max caracteres.',
            'lowercase' => 'Este campo solo permite el uso de minúsculas.',
            'uppercase' => 'Este campo solo permite el uso de mayúsculas.'
        ]);

        $estatus = Recurso::create([
            'clave' => $request->clave
        ]);

        return response()->json([
            'status' => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recurso $tipoPregunta)
    {
        $validated = $request->validate([
            'clave' => [
                'required', 'string', 'max:50',
                Rule::unique('recurso', 'clave')
                    ->ignore($tipoPregunta),
            ],
        ], [
            'required'    => 'Este campo es requerido.',
            'max'         => 'Este campo no debe exceder los :max caracteres.',
            'clave.unique' => 'El recurso ya ha sido registrado.',
        ]);

        $tipoPregunta->update($validated);

        return response()->json(['message' => 'Estatus actualizado']);
    }
}
