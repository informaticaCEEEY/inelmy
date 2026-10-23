<?php

namespace App\Http\Controllers;

use App\Models\RecursoVersion;
use Illuminate\Http\Request;

class RecursoVersionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validacion = $request->validate([
            'recurso_id' => ['required', 'numeric', 'exists:recurso,id'],
            'tipo_recurso_id' => ['required', 'numeric', 'exists:tipo_recurso,id'],
            'estatus_version_id' => ['required', 'numeric', 'exists:estatus_version,id'],
            'clave' => ['required', 'string', 'max:200'],
            'version' => ['required', 'int'],
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

        $estatus = RecursoVersion::create([
            'nombre' => $request->clave
        ]);

        return response()->json([
            'status' => 200,
        ]);
    }
}
