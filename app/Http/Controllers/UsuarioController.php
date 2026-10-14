<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class UsuarioController extends Controller
{
    public function index()
    {
        return view('usuarios/index');
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
}
