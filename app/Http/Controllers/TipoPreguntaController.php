<?php

namespace App\Http\Controllers;

use App\Models\TipoPregunta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class TipoPreguntaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tipo-pregunta.index');
    }

    public function datatable()
    {
        $query = TipoPregunta::orderBy('nombre', 'asc');
        return DataTables::of($query)->toJson();
    }

    public function getById(Request $request)
    {
        $tipoPregunta = TipoPregunta::findOrFail($request->id);

        if($tipoPregunta && !empty($tipoPregunta)){
            return response()->json([
                'status' => 200,
                'tipoPregunta' => $tipoPregunta
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
            'nombre' => ['required', 'string', 'max:100', 'unique:'.TipoPregunta::class],
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

        $estatus = TipoPregunta::create([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'status' => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoPregunta $tipoPregunta)
    {
        $validated = $request->validate([
            'nombre' => [
                'required', 'string', 'max:100',
                Rule::unique('tipo_pregunta', 'nombre')
                    ->ignore($tipoPregunta),
            ],
        ], [
            'required'    => 'Este campo es requerido.',
            'max'         => 'Este campo no debe exceder los :max caracteres.',
            'nombre.unique' => 'El tipoPregunta ya ha sido registrado.',
        ]);

        $tipoPregunta->update($validated);

        return response()->json(['message' => 'Estatus actualizado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     */
    public function destroy(TipoPregunta $tipoPregunta)
    {
        try {
            if ($tipoPregunta && $tipoPregunta->delete()) {
                return response()->json(['message' => 'Estatus eliminado.']);
            }
        } catch (Exception $e) {
            Log::error("Error capturado:" . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar.']);
        }

    }
}
