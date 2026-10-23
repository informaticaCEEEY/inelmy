<?php

namespace App\Http\Controllers;

use App\Models\TipoRecurso;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class TipoRecursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tipo-recurso.index');
    }

    public function datatable()
    {
        $query = TipoRecurso::orderBy('nombre', 'asc');
        return DataTables::of($query)->toJson();
    }

    public function getById(Request $request)
    {
        $tipoRecurso = TipoRecurso::findOrFail($request->id);

        if($tipoRecurso && !empty($tipoRecurso)){
            return response()->json([
                'status' => 200,
                'tipoRecurso' => $tipoRecurso
            ]);
        }else {
            return response()->json([
                'status' => 404,
                'message' => 'Not found'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validacion = $request->validate([
            'nombre' => ['required', 'string', 'max:150', 'unique:'.TipoRecurso::class],
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

        $estatus = TipoRecurso::create([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'status' => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoRecurso $tipoRecurso)
    {
        $validated = $request->validate([
            'nombre' => [
                'required', 'string', 'max:150',
                Rule::unique('tipo_recurso', 'nombre')
                    ->ignore($tipoRecurso),
            ],
        ], [
            'required'    => 'Este campo es requerido.',
            'max'         => 'Este campo no debe exceder los :max caracteres.',
            'nombre.unique' => 'El tipoRecurso ya ha sido registrado.',
        ]);

        $tipoRecurso->update($validated);

        return response()->json(['message' => 'Estatus actualizado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     */
    public function destroy(TipoRecurso $tipoRecurso)
    {
        try {
            if ($tipoRecurso && $tipoRecurso->delete()) {
                return response()->json(['message' => 'Estatus eliminado.']);
            }
        } catch (Exception $e) {
            Log::error("Error capturado:" . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar.']);
        }

    }
}
