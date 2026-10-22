<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\Usuario;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UsuarioController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('name', 'asc')->get();
        return view('usuarios.index', compact('roles'));
    }

    public function getById(Request $request)
    {
        $usuario = Usuario::with('roles')->findOrFail($request->id);

        if($usuario && !empty($usuario)){
            return response()->json([
                'status' => 200,
                'usuario' => $usuario
            ]);
        }else {
            return response()->json([
                'status' => 404,
                'message' => 'Not found'
            ]);
        }
    }

    public function datatable(Request $request)
    {

        $query = DB::table('usuario')
            ->leftJoin('model_has_roles', 'usuario.id', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', 'roles.id')
            ->select('usuario.id', 'usuario.celular', 'usuario.email', 'usuario.estatus', 'roles.name as rolNombre', 'model_has_roles.role_id',
                DB::raw("CONCAT(COALESCE(usuario.primer_apellido, ''), ' ', COALESCE(usuario.segundo_apellido, ''), ' ', COALESCE(usuario.nombre, '')) as nombreCompleto"));

        if ($request->filled('rol')) {
            $query->where('model_has_roles.role_id', $request->rol);
        }

        return DataTables::of($query)
            ->filterColumn('nombreCompleto', function ($query, $keyword) {
                $words = explode(' ', trim($keyword));
                $query->where(function ($q) use ($words) {
                    foreach ($words as $word) {
                        $q->where(function ($sub) use ($word) {
                            $sub->where('usuario.nombre', 'like', "%{$word}%")
                                ->orWhere('usuario.primer_apellido', 'like', "%{$word}%")
                                ->orWhere('usuario.segundo_apellido', 'like', "%{$word}%");
                        });
                    }
                });
            })
            ->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsuarioRequest $request)
    {
        DB::beginTransaction();
        try {
            $usuario = Usuario::create([
                'nombre' => $request->nombre,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
                'curp' => $request->curp,
                'celular' => $request->celular,
                'email' => $request->email,
                'password' => Hash::make($request->password, ['rounds' => 12,]),
            ]);

            $rol = Role::findOrFail($request->role_id);
            $usuario->SyncRoles($rol->name);

            DB::commit();

            return response()->json(['status' => 200]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsuarioRequest $request, Usuario $usuario)
    {
        $usuario->nombre = $request->nombre;
        $usuario->primer_apellido = $request->primer_apellido;
        $usuario->segundo_apellido = $request->segundo_apellido;
        $usuario->curp = $request->curp;
        $usuario->celular = $request->celular;
        $usuario->email = $request->email;
        if($request->password != ''){
            $usuario->password = Hash::make($request->password, ['rounds' => 12,]);
        }
        $usuario->save();

        $rol = Role::findOrFail($request->role_id);
        $usuario->SyncRoles($rol->name);

        return response()->json(['message' => 'Usuario actualizado']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(UpdatePasswordRequest $request, Usuario $usuario)
    {
        $usuario->password = Hash::make($request->password, ['rounds' => 12]);
        $usuario->save();

        return response()->json(['message' => 'Contraseña actualizada']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuario $usuario)
    {
        try {
            if ($usuario && $usuario->delete()) {
                return response()->json(['message' => 'Usuario eliminado.']);
            }
        } catch (Exception $e) {
            Log::error("Error capturado:" . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar.']);
        }

    }
}
