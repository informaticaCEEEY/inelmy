<?php

namespace App\Http\Controllers;

use App\Models\EstatusVersion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class EstatusVersionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('estatus-version.index');
    }

    public function datatable()
    {
        $query = EstatusVersion::orderBy('nombre', 'asc');
        return DataTables::of($query)->toJson();
    }

    public function getById(Request $request)
    {
        $estatusVersion = EstatusVersion::findOrFail($request->id);

        if($estatusVersion && !empty($estatusVersion)){
            return response()->json([
                'status' => 200,
                'estatusVersion' => $estatusVersion
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
            'nombre' => ['required', 'string', 'max:100', 'unique:'.EstatusVersion::class],
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

        $estatus = EstatusVersion::create([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'status' => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EstatusVersion $estatusVersion)
    {
        $validated = $request->validate([
            'nombre' => [
                'required', 'string', 'max:100',
                Rule::unique('estatus_version', 'nombre')
                    ->ignore($estatusVersion),
            ],
        ], [
            'required'    => 'Este campo es requerido.',
            'max'         => 'Este campo no debe exceder los :max caracteres.',
            'nombre.unique' => 'El estatusVersion ya ha sido registrado.',
        ]);

        $estatusVersion->update($validated);

        return response()->json(['message' => 'Estatus actualizado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     */
    public function destroy(EstatusVersion $estatusVersion)
    {
        try {
            if ($estatusVersion && $estatusVersion->delete()) {
                return response()->json(['message' => 'Estatus eliminado.']);
            }
        } catch (Exception $e) {
            Log::error("Error capturado:" . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar.']);
        }

    }
}
