<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Panel extends Controller
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        echo "<h2>Bienvenido, " . session('usuario') . "</h2>";
        echo "<p>Roles: " . implode(', ', session('roles')) . "</p>";
        echo "<p>Permisos: " . implode(', ', session('permisos')) . "</p>";
        echo '<p><a href="/salir">Cerrar sesión</a></p>';
    }
}
