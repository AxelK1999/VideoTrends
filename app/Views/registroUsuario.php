<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de usuario</title>
    <link href="<?=BASE_URL?>css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <script src="<?=BASE_URL?>js/bootstrap.bundle.min.js"></script>

    <style>
       @media (max-width: 900px) { 
            .content-banner{
                display: none;
            }  
        }
    </style>

    <header>
        <div class="d-flex justify-content-center">
            <h1 class="mt-3">Registro de usuarios</h1>
        </div>
    </header>

    <section class="row g-3">
      <form method="post" action="<?=BASE_URL?>/api/1.0/auth/register" class="needs-validation" novalidate>
        <div class="row">
            <div class="col-lg-6 col-sm-12">

                <h4>Datos de inicio de secion</h4>

                  <div class="mb-3 mx-4" id="username">
                    <label class="form-label ">Nombre de usuario*</label>     <!--is-invalid-->
                    <input type="text" required maxlength="50" name="username" class="form-control ">
                    <div class="invalid-feedback">
                     <?=$alertUserName?>
                    </div>
                  </div>

                  <div class="mb-3 mx-4" id="email">
                    <label class="form-label">E-mail*</label>
                    <input type="email" id="input-email" name ="email" required class="form-control">
                    <div class="invalid-feedback">
                      <?=$alertEmail?>
                    </div>
                  </div>

                  <div class="mb-3 mx-4" id="password">
                    <label class="form-label">Contraseña*</label>
                    <input type="password" id ="input-password" name ="password" minlength="8" required class="form-control">
                    <div class="form-text">(Longitud minima 8)</div>
                    <div class="invalid-feedback">
                    <?=$alertPassword?>
                    </div>
                  </div>

                  <div class="mb-3 mx-4"  id="confirm-password">
                    <label class="form-label">Repetir contraseña*</label>
                    <input type="password" id="input-confirm-password" name ="repeated-password" required class="form-control">
                    <div class="invalid-feedback">
                      <?=$alertRepitPassword?>
                    </div>
                  </div>

                  <script>
                        const passwordInput = document.getElementById('input-password');
                        const confirmPasswordInput = document.getElementById('input-confirm-password');
                        
                        confirmPasswordInput.addEventListener('input', function() {
                            if (passwordInput.value !== confirmPasswordInput.value) {
                               confirmPasswordInput.classList.add("is-invalid");
                            }else{
                              confirmPasswordInput.classList.remove("is-invalid");
                            }
                        });
                  </script>

                  <hr>

                    <h4>Datos personales</h4>
                
                    <div class="mb-3 mx-4" id="name">
                        <label class="form-label">Nombre</label>
                        <input type="text" id="input-name" maxlength="50" name ="name" class="form-control" maxlength="60">
                        <div class="invalid-feedback">
                          <?=$alertName?>
                        </div>
                      </div>
    
                      <div class="mb-3 mx-4" id = "lastname">
                        <label class="form-label">Apellido</label>
                        <input type="text"  id ="input-lastname" maxlength="50" name ="lastname" class="form-control" maxlength="60">
                        <div class="invalid-feedback">
                          <?=$alertLastName?>
                        </div>
                      </div>
    
                      <div class="mb-3 mx-4" id ="address">
                        <label class="form-label">Direccion</label>
                        <input type="text" id ="input-address" maxlength="100" name ="address" class="form-control" placeholder="Calle 60 chacra nro N">
                        <div class="invalid-feedback">
                          <?=$alertAddress?>
                        </div>
                      </div>
                 
                      <div class="form-check form-check-inline">
                        <label class="form-label d-block">Genero</label>
                        <input class="form-check-input mx-1" type="radio" name="sex" id="inlineRadio1" value="M">
                        <label class="form-check-label">Masculino</label>
                      </div>

                      <div class="form-check form-check-inline">
                        <input class="form-check-input mx-1" type="radio" name="sex" id="inlineRadio2" value="F">
                        <label class="form-check-label">Femenino</label>
                      </div>

                      <div class="mb-3 mx-4 mt-2">
                        <label class="form-label">Numero de Telefono</label>
                        <input type="tel" class="form-control" name="phonenumber">
                        <div class="invalid-feedback">
                          <?=$alertPhonenumber?>
                        </div>
                      </div>

                      <div class="mb-5 mx-4">
                        <label class="form-label">Fecha Nacimineto</label>
                        <input type="date" name="birthdate" class="form-control">
                      </div>

                      <div class="alert alert-danger mb-5 mx-3 me-3 <?=$fallValidationServer["visibleFallValidationServer"]?>" role="alert">
                        <?=$fallValidationServer["mj"]?>
                      </div>

                      <div class="text-center">
                        <hr>
                        <button class="btn btn-primary w-50 mb-5" id="btn-submit" type="submit">Crear Cuenta</button>
                      </div>

                      <script>

                           document.querySelector("form").addEventListener("submit", async (event) => {
                              event.preventDefault();

                              try {
                                const checkEmail = await validarEmailExisit();
                                const areInputsValid = checkInputsValid();

                                if (areInputsValid && checkEmail) {
                                  event.target.submit(); // Enviar el formulario
                                }
                              } catch (error) {
                                console.error(error);
                              }

                            });

                            function validarEmailExisit() {

                              return new Promise((resolve, reject) => {

                                  const email = document.getElementById('input-email').value;
                                  let containerEmail =  document.getElementById('email');

                                  if (email === '') {
                                    resolve(false); // El email no es válido
                                    return;
                                  }

                                  fetch("<?=BASE_URL?>/api/1.0/user/existsEmail?email=" + email, {
                                    method: "GET"
                                  })
                                    .then(res => res.json())
                                    .then(data => { 
                                      if (data.exists) {
                                        alertRemove("alertEmail", containerEmail);
                                        alertFeedbackAdd("alertEmail",data.infErr, containerEmail);

                                        resolve(false); // El email no es válido
                                      } else {
                                        alertRemove("alertEmail", containerEmail);
                                        resolve(true); // El email es válido
                                      }
                                    })
                                    .catch(err => {
                                      alert(err);
                                      reject(err); // Manejar el error
                                    });


                              });

                            }

                            function alertFeedbackAdd(id, mj, fatherElement){
                               if(fatherElement.querySelector("#"+id)){ return; }
                               
                                fatherElement.innerHTML  += 
                                '<div class="invalid-feedback" id='+id+'>'+ mj + '</div>';
                            }

                            function alertRemove(id, fatherElement){
                              if(fatherElement.querySelector("#"+id)){
                                fatherElement.querySelector("#"+id).remove();
                              }
                            }

                            function checkInputsValid(){
                              let inputs = document.querySelectorAll("input");
                              for (let i = 0; i < inputs.length; i++) {
                                if (!inputs[i].validity.valid) {
                                  return false;
                                }
                              }
                              return true;
                            }

                      </script>
            </div>
            <div class="col-lg-6 content-banner">
                <div class="d-flex flex-column position-fixed mt-2 mx-5 pt-5">
                  <img class="img-fluid w-75 mt-3 px-4" src="<?=BASE_URL?>images/portadaRegistro.webp" alt="">
                  <p class="text-start w-50 mx-5 mt-3">Al hacer click en "Crear mi cuenta", aceptas las condiciones y confirmas que leiste nuestra politica de datos, incluido el uso de cookies.</p>
                </div>
            </div>
            
           

          </form>

          <script>
            (() => {
              'use strict'
            
              // Fetch all the forms we want to apply custom Bootstrap validation styles to
              const forms = document.querySelectorAll('.needs-validation')
            
              // Loop over them and prevent submission
              Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                  if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                  }
            
                  form.classList.add('was-validated')
                }, false)
              })
            })()
            </script>

    </section>

    <footer>
      <div class="d-flex justify-content-center bg-dark m-auto">
          <div class = "mt-2 me-5 mb-2"><a href="https://trakt.docs.apiary.io/#reference" class="text-light">Trak API</a></div>
          <div class = "mt-2 mx-5 mb-2"><a href="https://ugd.edu.ar/es/" class="text-light">U.G.D.</a></div>
          <div class = "mt-2 mx-5 mb-2"><a href="https://campusvirtual.ugd.edu.ar/moodle/ambientizacion.php" class="text-light">Campus Virtual</a></div>
      </div>
  </footer>

</body>
</html>