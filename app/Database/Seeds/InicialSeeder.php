<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InicialSeeder extends Seeder
{
    public function run()
    {
        $bd = \Config\Database::connect();
        $bd->transStart();

        // === ROLES ===
        $roles = [
            ['nombre' => 'Administrador', 'clave' => 'admin'],
            ['nombre' => 'Gerente',       'clave' => 'gerente'],
            ['nombre' => 'Analista',      'clave' => 'analista'],
            ['nombre' => 'Lector',        'clave' => 'lector'],
        ];
        $bd->table('roles')->insertBatch($roles);

        // === PERMISOS ===
        $permisos = [
            ['nombre' => 'Ver clientes',       'clave' => 'clientes.ver'],
            ['nombre' => 'Editar clientes',    'clave' => 'clientes.editar'],
            ['nombre' => 'Ver pedidos',        'clave' => 'pedidos.ver'],
            ['nombre' => 'Editar pedidos',     'clave' => 'pedidos.editar'],
            ['nombre' => 'Ver reportes',       'clave' => 'reportes.ver'],
            ['nombre' => 'Gestionar usuarios', 'clave' => 'usuarios.gestionar'],
        ];
        $bd->table('permisos')->insertBatch($permisos);

        // === USUARIOS ===
        $ahora = date('Y-m-d H:i:s');
        $usuarios = [
            [
                'usuario' => 'admin',
                'correo'  => 'admin@demo.com',
                'contrasena_hash' => password_hash('123456', PASSWORD_DEFAULT),
                'activo'  => 1,
                'creado_en' => $ahora,
            ],
            [
                'usuario' => 'sofia',
                'correo'  => 'sofia@demo.com',
                'contrasena_hash' => password_hash('123456', PASSWORD_DEFAULT),
                'activo'  => 1,
                'creado_en' => $ahora,
            ],
            [
                'usuario' => 'diego',
                'correo'  => 'diego@demo.com',
                'contrasena_hash' => password_hash('123456', PASSWORD_DEFAULT),
                'activo'  => 1,
                'creado_en' => $ahora,
            ],
            [
                'usuario' => 'luz',
                'correo'  => 'luz@demo.com',
                'contrasena_hash' => password_hash('123456', PASSWORD_DEFAULT),
                'activo'  => 1,
                'creado_en' => $ahora,
            ],
        ];
        $bd->table('usuarios')->insertBatch($usuarios);

        // === FUNCIONES AUXILIARES ===
        $idRol = fn($clave) => $bd->table('roles')->select('id')->where('clave', $clave)->get()->getRow()->id ?? null;
        $idPermiso = fn($clave) => $bd->table('permisos')->select('id')->where('clave', $clave)->get()->getRow()->id ?? null;
        $idUsuario = fn($nombre) => $bd->table('usuarios')->select('id')->where('usuario', $nombre)->get()->getRow()->id ?? null;

        // === ASIGNAR PERMISOS A ROLES ===
        $asignar = function ($rolClave, $clavesPermisos) use ($bd, $idRol, $idPermiso) {
            $rol = $idRol($rolClave);
            $filas = [];
            foreach ($clavesPermisos as $clave) {
                $permiso = $idPermiso($clave);
                if ($permiso) $filas[] = ['rol_id' => $rol, 'permiso_id' => $permiso];
            }
            if ($filas) $bd->table('roles_permisos')->insertBatch($filas);
        };

        // Asignaciones
        $asignar('admin', array_column($permisos, 'clave')); // todos
        $asignar('gerente', ['clientes.ver', 'clientes.editar', 'pedidos.ver', 'pedidos.editar', 'reportes.ver']);
        $asignar('analista', ['clientes.ver', 'pedidos.ver', 'reportes.ver']);
        $asignar('lector', ['clientes.ver', 'pedidos.ver']);

        // === ASIGNAR ROLES A USUARIOS ===
        $bd->table('usuarios_roles')->insertBatch([
            ['usuario_id' => $idUsuario('admin'),  'rol_id' => $idRol('admin')],
            ['usuario_id' => $idUsuario('sofia'),  'rol_id' => $idRol('gerente')],
            ['usuario_id' => $idUsuario('diego'),  'rol_id' => $idRol('analista')],
            ['usuario_id' => $idUsuario('luz'),    'rol_id' => $idRol('lector')],
        ]);

        $bd->transComplete();

        if ($bd->transStatus() === false) {
            throw new \RuntimeException('Error al ejecutar el seeder InicialSeeder.');
        }
    }
}
