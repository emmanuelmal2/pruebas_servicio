<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class Login extends Controller
{
    public function index()
    {
        return view('login'); // muestra el formulario
    }

    public function autenticar()
    {
        $usuario = $this->request->getPost('usuario');
        $password = $this->request->getPost('password');

        $db = Database::connect();
        $user = $db->table('usuarios')->where('usuario', $usuario)->get()->getRow();

        if (!$user || !password_verify($password, $user->contrasena_hash)) {
            return redirect()->back()->with('error', 'Usuario o contraseña incorrectos');
        }

        // Obtener roles
        $roles = $db->table('usuarios_roles')
            ->select('r.id, r.clave')
            ->join('roles r', 'r.id = usuarios_roles.rol_id')
            ->where('usuarios_roles.usuario_id', $user->id)
            ->get()
            ->getResultArray();

        // Obtener permisos de todos sus roles
        $permisos = [];
        foreach ($roles as $rol) {
            $res = $db->table('roles_permisos')
                ->select('p.clave')
                ->join('permisos p', 'p.id = roles_permisos.permiso_id')
                ->where('roles_permisos.rol_id', $rol['id'])
                ->get()
                ->getResultArray();

            foreach ($res as $perm) {
                $permisos[] = $perm['clave'];
            }
        }

        // Guardar datos en sesión
        session()->set([
            'usuario_id' => $user->id,
            'usuario'    => $user->usuario,
            'roles'      => array_column($roles, 'clave'),
            'permisos'   => array_unique($permisos),
            'isLoggedIn' => true
        ]);

        return redirect()->to('/panel');
    }

    public function salir()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
