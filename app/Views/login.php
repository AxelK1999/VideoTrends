<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="<?=BASE_URL?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=BASE_URL?>/css/estilos.css" rel="stylesheet">

    <title>Login</title>
</head>
<body>
    <script src="<?=BASE_URL?>/js/bootstrap.bundle.min.js"></script>

    <header>
        <div class="d-flex flex-row mlt-0">
            <div class="d-inline-flex p-2 flex-grow-1">
                <img src="<?=BASE_URL?>images/iconoMarca.png" class="mr-img-marca" alt="Icono marca" height="50" width="50">
                <a href="<?=BASE_URL?>login.php"><h1 class="mt-text-marca text-marca">VideoTrend</h1></a>
            </div>
            <div class="p-2 text-aditional-marca"><p>La <strong>biblioteca</strong> de tus pelis !!</p></div>
        </div>  
    </header>

    <nav>
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="nav-link active" href="<?=BASE_URL?>api/1.0/views/register">| Crear una cuenta  |</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">| Olvide mi contraseña  |</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="https://github.com/AxelK1999">| Acerca de nosotros |</a>
            </li>
        </ul>
    </nav>

    <section>

        <div class="d-flex flex-row justify-content-center login-content">

            <div class = " login-content-portada">
                <img src="<?=BASE_URL?>images/PortadaLoginV1.jpg" class="login-content-portada-img">
            </div>

            <div class='pb-5 shadow p-3 rounded login-content-form justify-content-center'> 

                <form action="<?=BASE_URL?>api/1.0/auth/login" method="post"> 

                    <div class="alert alert-danger <?=$stateAlert?> " role="alert">
                        <?=esc($mj)?>
                    </div>

                    <div class='form-group mt-3'> 
                        <label for='InputEmail1'><strong>Direccion de email</strong></label> 
                        <input type='email' name ="email" class='form-control' id='InputEmail1' aria-describedby='email'> 
                        <small id='Help' class='form-text text-muted invisible'>Tu correo estara protegido.</small> 
                    </div>

                    <div class='form-group'> 
                        <label for='InputPassword1'><strong>Contraseña</strong></label> 
                        <input type='password' name="password" class='form-control' id='InputPassword1'> 
                    </div>

                    <button type='submit' class='btn btn-primary btn-block mt-4'>Iniciar Secion</button>
                </form>

                <hr>
                
                <a href="<?=BASE_URL?>api/1.0/views/register" class="btn btn-secondary mt-2">Crear Cuenta</a>

            </div>

        </div>
    </section>

    <footer>
        <div class="d-flex justify-content-center bg-dark m-auto">
            <div class = "m-item-footer"><a href="https://trakt.docs.apiary.io/#reference" class="text-light">Trak API</a></div>
            <div class = "m-item-footer"><a href="https://ugd.edu.ar/es/" class="text-light">U.G.D.</a></div>
            <div class = "m-item-footer"><a href="https://campusvirtual.ugd.edu.ar/moodle/ambientizacion.php" class="text-light">Campus Virtual</a></div>
        </div>
    </footer>

</body>
</html>