<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('catalogos/admin-permisos');
    }

    public function datatable()
    {
        $query = Permission::orderBy('name', 'asc');
        return DataTables::of($query)->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validacion = $request->validate([
            'name' => ['required', 'string', 'unique:'.Permission::class],
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

        $user = Permission::create([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $permiso = Permission::find($request->id);
        if($permiso){
            $validacion = $request->validate([
                'name' => ['required', 'string', Rule::unique('permissions')->ignore($permiso)],
            ],[
                'required' => 'Este campo es requerido.',
                'clave.unique' => 'La clave ya ha sido registrada.',
                'regex' => 'Este campo tiene caracteres inválidos.',
                'numeric' => 'Solo se permiten números.',
                'digits' => 'El campo debe estar conformado por :digits dígitos.',
                'min' => 'Este campo debe tener al menos :min caracteres.',
                'max' => 'Este campo no debe exceder los :max caracteres.',
                'lowercase' => 'Este campo solo permite el uso de minúsculas.',
                'uppercase' => 'Este campo solo permite el uso de mayúsculas.'
            ]);

            $permiso->name = $request->name;
            $permiso->save();
            return response()->json([
                'status' => 200,
                'message' => 'Updated successfully'
            ]);

        }else {
            return response()->json([
                'status' => 404,
                'message' => 'Not found'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $permiso = Permission::find($request->id);
        try {
            if ($permiso && $permiso->delete()) {
                return response()->json(['status' => 200, 'message' => 'Deleted successfully.']);
            }
        } catch (Exception $e) {
            Log::error("Error capturado:" . $e->getMessage());
            return response()->json(['status' => 400, 'message' => 'Failed to delete.']);
        }

    }
}
