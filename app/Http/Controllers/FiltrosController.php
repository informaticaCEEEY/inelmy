<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FiltrosController extends Controller
{
    public function roles(Request $request)
    {
        $search = $request->search;

        $query = DB::table('roles')
            ->when($search, function ($query) use ($search) {
                $query->where('roles.name', 'like', "%{$search}%");
            })
            ->select('roles.id', 'roles.name as text')
            ->where('roles.id', '!=', 2)
            ->orderBy('text');

        $roles = $query->distinct()->paginate(10);

        return response()->json([
            'roles' => $roles->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->text
                ];
            }),
            'more' => $roles->hasMorePages()
        ]);
    }

    public function permisos(Request $request)
    {
        $search = $request->search;

        $query = DB::table('permissions')
            ->when($search, function ($query) use ($search) {
                $query->where('permissions.name', 'like', "%{$search}%");
            })
            ->select('permissions.id', 'permissions.name as text')
            ->orderBy('text');

        $permisos = $query->distinct()->paginate(10);

        return response()->json([
            'permisos' => $permisos->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->text
                ];
            }),
            'more' => $permisos->hasMorePages()
        ]);
    }
}
