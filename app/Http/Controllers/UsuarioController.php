<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\Usuario;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UsuarioController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('name', 'asc')->get();
        return view('usuarios/index', compact('roles'));
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

            return response()->json([
                'status' => 200,
                'message' => 'Updated successfully'
            ]);
    }

    public function update_password(Request $request)
    {
        $user = Usuario::find($request->id);
        if($user){
            $validacion = $request->validate([
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ],[
                'required' => 'Este campo es requerido.',
                'regex' => 'Este campo tiene caracteres inválidos.',
                'curp.regex' => 'La CURP tiene caracteres inválidos, verifiquela.',
                'numeric' => 'Solo se permiten números.',
                'celular.digits' => 'El celular estar conformado por :digits dígitos.',
                'curp.size' => 'La CURP debe estar conformada por :size caracteres.',
                'min' => 'Este campo debe tener al menos :min caracteres.',
                'max' => 'Este campo no debe exceder los :max caracteres.',
                'email.email' => 'Ingrese un correo electrónico valido.',
                'lowercase' => 'Este campo solo permite el uso de minúsculas.',
                'uppercase' => 'Este campo solo permite el uso de mayúsculas.',
                'email.unique' => 'Este correo ya se encuentra registrado.'
            ]);

            $user->password = Hash::make($request->password, ['rounds' => 12,]);
            $user->save();

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
        $usuario = Usuario::find($request->id);
        try {
            if ($usuario && $usuario->delete()) {
                return response()->json(['status' => 200, 'message' => 'Deleted successfully.']);
            }
        } catch (Exception $e) {
            //Log::error("Error capturado:" . $e->getMessage());
            return response()->json(['status' => 400, 'message' => 'Failed to delete.']);
        }
    }
}
