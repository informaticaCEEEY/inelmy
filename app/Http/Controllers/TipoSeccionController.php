<?php

namespace App\Http\Controllers;

use App\Models\TipoSeccion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class TipoSeccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tipo-seccion.index');
    }

    public function datatable()
    {
        $query = TipoSeccion::orderBy('nombre', 'asc');
        return DataTables::of($query)->toJson();
    }

    public function getById(Request $request)
    {
        $tipoSeccion = TipoSeccion::findOrFail($request->id);

        if($tipoSeccion && !empty($tipoSeccion)){
            return response()->json([
                'status' => 200,
                'tipoSeccion' => $tipoSeccion
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
            'clave' => ['required', 'string', 'max:30', 'unique:'.TipoSeccion::class],
            'nombre' => ['required', 'string', 'max:100'],
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

        $estatus = TipoSeccion::create([
            'clave' => $request->clave,
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'status' => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoSeccion $tipoSeccion)
    {
        $validated = $request->validate([
            'clave' => [
                'required', 'string', 'max:30',
                Rule::unique('tipo_seccion', 'clave')
                    ->ignore($tipoSeccion),
            ],
            'nombre' => ['required', 'string', 'size:100'],
        ], [
            'required'    => 'Este campo es requerido.',
            'max'         => 'Este campo no debe exceder los :max caracteres.',
            'clave.unique' => 'El tipo ya ha sido registrado.',
        ]);

        $tipoSeccion->update($validated);

        return response()->json(['message' => 'Estatus actualizado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     */
    public function destroy(TipoSeccion $tipoSeccion)
    {
        try {
            if ($tipoSeccion && $tipoSeccion->delete()) {
                return response()->json(['message' => 'Estatus eliminado.']);
            }
        } catch (Exception $e) {
            Log::error("Error capturado:" . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar.']);
        }

    }
}
