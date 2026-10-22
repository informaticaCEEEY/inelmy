<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $permisos = Permission::orderBy('name')->get();

        return view('roles.index', compact('permisos'));
    }

    public function datatable()
    {

        // Subconsulta que calcula total de permisos por rol
        $sub = DB::table('role_has_permissions')
            ->select('role_id', DB::raw('COUNT(*) as total_permisos'))
            ->groupBy('role_id');

        $query = DB::table('roles')
            ->leftJoinSub($sub, 'p', function ($join) {
                $join->on('roles.id', '=', 'p.role_id');
            })
            ->select(
                'roles.id',
                'roles.name',
                DB::raw('COALESCE(p.total_permisos, 0) as total_permisos')
            );

        return DataTables::of($query)->toJson();
    }

    public function permisosPorRol($id)
    {
        $permisos = DB::table('role_has_permissions')
            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->where('role_has_permissions.role_id', $id)
            ->orderBy('permissions.name')
            ->pluck('permissions.name');

        return response()->json($permisos);
    }

    public function getById(Request $request)
    {
        $rol = Role::with('permissions')->findOrFail($request->role_id);

        return response()->json([
            'id' => $rol->id,
            'name' => $rol->name,
            'permissions' => $rol->permissions->map(function ($permiso) {
                return [
                    'id' => $permiso->id,
                    'text' => $permiso->name,
                ];
            })->values(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validacion = $request->validate([
            'name' => ['required', 'string', 'unique:'.Role::class],
            'permisos_id' => ['required', 'array'],
            'permisos_id.*' => ['required', 'numeric', 'exists:permissions,id'],
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
        $permisosId = $request->input('permisos_id', []);
        $role = Role::create([
            'name' => $request->name
        ]);
        $permissions = Permission::whereIn('id', $permisosId)->pluck('name')->toArray();
        $role->syncPermissions($permissions);

        return response()->json([
            'status' => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('roles', 'name')
                    ->where('guard_name', $role->guard_name)
                    ->ignore($role),
            ],
        ], [
            'required'    => 'Este campo es requerido.',
            'max'         => 'Este campo no debe exceder los :max caracteres.',
            'name.unique' => 'El rol ya ha sido registrado.',
        ]);

        $role->update($validated);

        $permisosId = $request->input('permisos_id', []);
        $permissions = Permission::whereIn('id', $permisosId)->pluck('name')->toArray();
        $role->syncPermissions($permissions);

        return response()->json(['message' => 'Rol actualizado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     */
    public function destroy(Role $role)
    {
        try {
            if ($role && $role->delete()) {
                return response()->json(['message' => 'Rol eliminado.']);
            }
        } catch (Exception $e) {
            Log::error("Error capturado:" . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar.']);
        }

    }
}
