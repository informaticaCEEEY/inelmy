<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesYPermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la caché de permisos.
        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();

        // Permisos disponibles en INELMY.
        $permisos = [
            'dashboard.ver',

            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',
            'permisos.administrar',
            'roles.administrar',

            'instrumentos.ver',
            'instrumentos.crear',
            'instrumentos.editar',
            'instrumentos.eliminar',
            'instrumentos.publicar',

            'recursos.ver',
            'recursos.crear',
            'recursos.editar',
            'recursos.eliminar',

            'aplicaciones.ver',
            'aplicaciones.crear',
            'aplicaciones.editar',
            'aplicaciones.eliminar',
            'aplicaciones.asignar',
            'aplicaciones.asignadas.contestar',
            'aplicaciones.asignadas.ver',

            'respuestas.pendientes.ver',
            'respuestas.calificar',

            'sustentantes.ver',
            'sustentantes.crear',
            'sustentantes.editar',

            'evaluadores.ver',
            'evaluadores.crear',
            'evaluadores.editar',

            'estadisticas.ver',
            'resultados.ver',
            'resultados.propios.ver',
            'resultados.propios.descargar',

            'reporte.sustentantes.ver',
            'reporte.sustentantes.descargar',

            'reporte.aplicacion.ver',
            'reporte.aplicacion.descargar',
        ];

        // Crear permisos sin duplicarlos.
        foreach ($permisos as $nombre) {
            Permission::firstOrCreate([
                'name' => $nombre,
                'guard_name' => 'web',
            ]);
        }

        // Permisos asignados a cada rol.
        $roles = [
            'administrador' => $permisos,

            'coordinador' => [
                'dashboard.ver',
                'usuarios.ver',

                'instrumentos.ver',
                'instrumentos.crear',
                'instrumentos.editar',
                'instrumentos.publicar',

                'recursos.ver',
                'recursos.crear',
                'recursos.editar',

                'aplicaciones.ver',
                'aplicaciones.crear',
                'aplicaciones.editar',
                'aplicaciones.asignar',

                'sustentantes.ver',
                'sustentantes.crear',
                'sustentantes.editar',

                'evaluadores.ver',
                'evaluadores.crear',
                'evaluadores.editar',

                'resultados.ver',
                'estadisticas.ver',
                'reporte.sustentantes.ver',
                'reporte.sustentantes.descargar',
                'reporte.aplicacion.ver',
                'reporte.aplicacion.descargar',
            ],

            'sustentante' => [
                'dashboard.ver',
                'aplicaciones.asignadas.ver',
                'aplicaciones.asignadas.contestar',
                'resultados.propios.ver',
                'resultados.propios.descargar',
            ],

            'evaluador' => [
                'dashboard.ver',
                'estadisticas.ver',
                'aplicaciones.asignadas.ver',
                'respuestas.pendientes.ver',
                'respuestas.calificar',
            ],

            'autoridad-educativa' => [
                'dashboard.ver',
                'estadisticas.ver',
                'resultados.ver',
                'reporte.sustentantes.ver',
                'reporte.sustentantes.descargar',
                'reporte.aplicacion.ver',
                'reporte.aplicacion.descargar',
            ],
        ];

        // Crear roles y sincronizar sus permisos.
        foreach ($roles as $nombreRol => $permisosRol) {
            $rol = Role::firstOrCreate([
                'name' => $nombreRol,
                'guard_name' => 'web',
            ]);

            $rol->syncPermissions($permisosRol);
        }

        // Actualizar la caché.
        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();
    }
}
