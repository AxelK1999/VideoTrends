<!DOCTYPE html>
<html>
<head>
    <title>Notificación de cuenta creada</title>
   
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="card mt-5">
            <div class="card-body">
                <h4 class="card-title">¡Cuenta creada con éxito!</h4>
                <p class="card-text">Se a enviado un correo con un enlace a su cuenta de email, para verificar aga click en la misma</p>
                <p>Una vez que hayas validado tu correo electrónico, podrás iniciar sesión en la aplicación.</p>
                <a href="<?=BASE_URL?>api/1.0/views/login" class="btn btn-secondary">Iniciar sesión</a>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
