<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Iniciar sesión</h2>
    <form method="post" action="/login/autenticar">
        <label>Usuario:</label><br>
        <input type="text" name="usuario" required><br><br>
        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Entrar</button>
    </form>

    <?php if (session()->getFlashdata('error')): ?>
        <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>
</body>
</html>
