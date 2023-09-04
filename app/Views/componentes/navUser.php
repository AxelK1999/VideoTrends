  
  <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand mx-sm-0 mx-md-4  align-items-center">
                <span class="h2"> 🎬 VideoTrends </span>
            </a>

            <div id="profile" class="me-4">

                <a href="#" class="d-flex navbar-brand align-items-center btn btn-outline-secondary" id="btn-profile" data-toggle="modal" data-target="#miModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-vcard-fill mr-1" viewBox="0 0 16 16">
                      <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5ZM9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8Zm1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5Zm-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96c.026-.163.04-.33.04-.5ZM7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0Z"></path>
                    </svg>
                    <span class="mx-2"><?=$username?></span>
                </a>
                  
                <!--Modal-->
                <div class="modal mt-5"  id="miModal" tabindex="-1">
                    <div class="modal-content modal-dialog">

                        <div class="modal-header">
                            <h5 class="modal-title">Datos de usuario</h5>
                            <button type="button" class="btn-close" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                        
                            <div class="container">
                                <div class="table-responsive mt-3">
    
                                  <table class="table table-bordered">
                                    <tbody>
                                      <tr>
                                        <th name = "email">Email</th>
                                        <td><input type="email" class='border-0' currentvalue = "<?=$email?>" value="<?=$email?>"></td>
                                      </tr>
                                      
                                      <tr>
                                        <th name = "username" >Username</th>
                                        <td><input type="text" class='border-0' currentvalue = "<?=$username?>" value="<?=$username?>"></td>
                                      </tr>

                                      <tr>
                                        <th name = "name" >Nombre</th>
                                        <td><input type="text " class='border-0' currentvalue = "<?=$name?>" value="<?=$name?>"></td>
                                      </tr>

                                      <tr>
                                        <th name = "lastname" >Apellido</th>
                                        <td ><input type="text" class='border-0' currentvalue = "<?=$lastname?>" value="<?=$lastname?>"></td>
                                      </tr>
                                      <tr>
                                        <th name = "phonenumber" >Número de Teléfono</th>
                                        <td ><input type="text" class='border-0' currentvalue = "<?=$phonenumber?>" value="<?=$phonenumber?>"></td>
                                      </tr>

                                      <tr>
                                        <th name = "sex" >Género</th>
                                        <td><input type="text" class='border-0' currentvalue = "<?=$sex?>" value="<?=$sex?>" maxlength="1" ></td>
                                      </tr>

                                      <tr>
                                        <th name = "birthdate" >Fecha de Nacimiento</th>
                                        <td ><input type="date" class='border-0' currentvalue = "<?=$birthdate?>" value="<?=$birthdate?>"></td>
                                      </tr>

                                      <tr>
                                        <th name = "address">Dirección</th>
                                        <td ><input type="text" class='border-0' currentvalue = "<?=$address?>" value="<?=$address?>"></td>
                                      </tr>

                                    </tbody>
                                  </table>
                                </div>
                                <label>Nota: al modificar el correo debera volver a verificarlo accediendo al mismo.</label>
                              </div>
                            
                        </div>

                        <div class="alert alert-danger visually-hidden me-3 mx-3" id="alert-nav-user" role="alert"></div>

                        <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                  var saveButton = document.querySelector('.save-btn');
                            
                                  saveButton.addEventListener('click', function() {
                                    var rows = Array.from(document.querySelectorAll('tr'));
                                    var userData = {};
                            
                                    rows.forEach(function(row) {
                                      var key = row.querySelector('th').getAttribute("name");//.textContent;
                                      var value = row.querySelector('td input').value;
                                      userData[key] = value;
                                    });
                                    
                                    console.log("Datos de usuario : ",userData);
                                    
                                    fetch('<?= BASE_URL ?>api/1.0/user/update', {
                                      method: 'PUT',
                                      headers: {
                                        'Content-Type': 'application/json'
                                      },

                                      body: JSON.stringify(userData)

                                    })
                                    .then(res => res.json())
                                    .then(data => {

                                      if(data.stateResult){
                                        enabelAlert(data.inf, true);
                                        setChanges();
                                      }else{

                                        if(data.url){
                                          location.href = data.url;
                                        }
                                        enabelAlert(data.inf);
                                      }    
                                    })
                                    .catch(function(error) {
                                       enabelAlert("Se a producido un error al guardar los cambios");
                                    });
                                  });
                                });

                                let alert = document.querySelector("#alert-nav-user");

                                function enabelAlert(inf, succes = false){

                                  if(succes){
                                    alert.classList.remove("alert-danger");
                                    alert.classList.add("alert-success");
                                  }else{
                                    alert.classList.add("alert-danger");
                                    alert.classList.remove("alert-success");
                                  }
                                  alert.classList.remove("visually-hidden");
                                  alert.textContent = inf;
                                }

                                function renudateChanges(){
                                  document.getElementById('miModal').setAttribute('style', 'display: none');
                                  var rows = Array.from(document.querySelectorAll('tr'));
                                  
                                  rows.forEach(function(row) {
                                    row.querySelector('td input').value = row.querySelector('td input').getAttribute("currentvalue");
                                  });

                                  alert.classList.add("visually-hidden");

                                }

                                function setChanges(){
                                  var rows = Array.from(document.querySelectorAll('tr'));
                                  
                                  rows.forEach(function(row) {
                                    row.querySelector('td input').setAttribute("currentvalue", row.querySelector('td input').value);
                                  });
                                }

                              </script>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick= 'renudateChanges()'>Close</button>
                            <button type="button" class="btn btn-primary save-btn">Save changes</button>
                        </div>

                    </div>
                </div>

                <script>
                    const miDiv = document.getElementById('btn-profile');
                    
                    // Agregar un controlador de eventos de clic al div
                    miDiv.addEventListener('click', function() {
                        const miModal = document.getElementById('miModal');
                        // Agregar la clase 'show' para mostrar el modal
                        miModal.classList.add('show');
                        // Quitar la clase 'fade' para evitar la animación
                        miModal.classList.remove('fade');
                        // Agregar el atributo 'style' para mostrar el modal
                        miModal.setAttribute('style', 'display: block');
                    });
                </script>

                <!--FIN Modal-->

            </div>
        </div>
    </nav>