<?php

namespace App\Http\Controllers;

use App\Models\EstatusAplicacion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class EstatusAplicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('estatus-aplicacion.index');
    }

    public function datatable()
    {
        $query = EstatusAplicacion::orderBy('nombre', 'asc');
        return DataTables::of($query)->toJson();
    }

    public function getById(Request $request)
    {
        $estatusAplicacion = EstatusAplicacion::findOrFail($request->id);

        if($estatusAplicacion && !empty($estatusAplicacion)){
            return response()->json([
                'status' => 200,
                'estatusAplicacion' => $estatusAplicacion
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
            'nombre' => ['required', 'string', 'unique:'.EstatusAplicacion::class],
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

        $estatus = EstatusAplicacion::create([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'status' => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EstatusAplicacion $estatusAplicacion)
    {
        $validated = $request->validate([
            'nombre' => [
                'required', 'string', 'max:255',
                Rule::unique('estatus_aplicacion', 'nombre')
                    ->ignore($estatusAplicacion),
            ],
        ], [
            'required'    => 'Este campo es requerido.',
            'max'         => 'Este campo no debe exceder los :max caracteres.',
            'nombre.unique' => 'El estatusAplicacion ya ha sido registrado.',
        ]);

        $estatusAplicacion->update($validated);

        return response()->json(['message' => 'Estatus actualizado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     */
    public function destroy(EstatusAplicacion $estatusAplicacion)
    {
        try {
            if ($estatusAplicacion && $estatusAplicacion->delete()) {
                return response()->json(['message' => 'Estatus eliminado.']);
            }
        } catch (Exception $e) {
            Log::error("Error capturado:" . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar.']);
        }

    }
}
