<?php

namespace App\Controllers;

use  App\Models\User;
use App\Helpers\TokenSession;
use App\Filters\Filter;
use App\Helpers\Errores;
use App\Helpers\Email;
use CodeIgniter\Cookie\Cookie;
use DateTime;

class Auth extends BaseController{

    function login(){
       
       $userModel = new User();
       
       $email = $this->request->getPost("email");
       $password = $this->request->getPost("password");

        $validation = service('validation');
        $validation->setRules([
            'password' => 'required',
            'email'    => 'required|valid_email|is_not_unique[users.email]',
        ]);

        $validationResult = $validation->withRequest($this->request)->run();

        if(!$validationResult){
            //dd($validation->getErrors());//Permite debugear mostrando en pagina reglas que fallan y por que cuando se esta en modo desarrollo.
            //No hay ususario con el email ingresado
            return $this->response->redirect(BASE_URL."api/1.0/views/login?infAlert=-1");
        }

        $user = $userModel->find($email);

        if($user["validated"] === "f"){
            return $this->response->redirect(BASE_URL."api/1.0/views/login?infAlert=-14");
        }
        
        if( !password_verify("".$password , $user["password"]) ){
            //No coincide la contraseña en la BD correspondiete a ese ususario  especifico
            return $this->response->redirect(BASE_URL."api/1.0/views/login?infAlert=-1");
        }
        
        $permiso = TokenSession::generateToken($user, time() + 3600);
        //Cliente guarda la cookie automaticamnte --> enviada en posteriores solicitudes automaticamnete mientras la cookie este viva (req.cookies.nameCookie)
        /*
                'name' => 'authfff',
                'value' =>  $permiso,
                'expires'  => new DateTime('+2 hours'), //Tiempo de vida en seg
                'prefix'   => '',
                'path'     => '/',
                'domain'   => '',
                'secure'   => true, //IMPORTATE --> process.env.NODE_ENV === "production",  indicar si se quiere que una cookie sólo pueda ser utilizada en conexiones HTTPS (true).
                'httponly' => true, //indica si la cookie puede ser utilizada sólo por métodos http/s => no por extensiones o por codigo en el navegador por el cliente
                'samesite' => 'Strict'//Cookie::SAMESITE_STRICT,//Restringir desde que dominio(pagina) se puede acceder a la cookies (stric: solo desde mismo dominio (ayuda a evitar ataques CSRF cuando la cookie contiene credenciales como este caso) - lax: cualquier dominio ) 
        */
    
        $this->response->setCookie("permiso", $permiso,new DateTime('+1 hours'), "", "/", "", false, true, "Strict");
        $this->response->redirect(BASE_URL."api/1.0/views/home");
        
    }

    function lagout(){
        $this->response->setCookie("permiso","",time() - 3600,"","/","",false,true,"Strict");
    }

    function registerUser(){

            if( !Filter::validateDatesUserRegister($this->request, $this->response) ){ return; }
            //TODO ----
                /*$validationResult = Filter::validateDatesUserRegister($this->request, $this->response);
                if(!$validationResult["success"]){} */
            //-----

            $hash = password_hash( "".$this->request->getPost("password"), PASSWORD_BCRYPT );

            $birthdate = $this->request->getPost("birthdate");
            $email = $this->request->getPost("email");  

            $user = new User();
            $user->insert([
                "password" => $hash,
                'email'   => $email , 
                'username' => $this->request->getPost("username"),
                'name' => $this->request->getPost("name"),
                'lastname'=> $this->request->getPost("lastname"),
                'address' => $this->request->getPost("address"),
                'gender' => $this->request->getPost("sex"),
                'phonenumber'=> $this->request->getPost("phonenumber"),
                'birthdate' => !empty($birthdate) ? $birthdate : null,
                'validated' => false,
            ],false);
            
            Email::sendEmailValidAccount($this->request->getPost("email"), -200);
   
            return view("confirmEmail");
    }

    //validacion de la cuenta ya registrada
    function validateRegisteredAccount(){
        
        $user = new User();
        $permisos = $this->request->getVar('tokenValid');

        if( !(isset($permisos) && TokenSession::verifyToken($permisos) )){
            
            $this->response->redirect(BASE_URL."api/1.0/views/login?infAlert=".Errores::getMjError(-15));

        }else{
            //log_message("info", TokenSession::decodeToken($permisos)["email"]);
            $user->update(TokenSession::decodeToken($permisos)["email"],['validated' => true]); 
            $this->response->redirect(BASE_URL."api/1.0/views/login");
            //log_message("info",'cuenta validada');
        }

    }


}


/*

Método register(): Este método se utiliza para registrar nuevos usuarios en el sistema. Se encarga de validar los datos de registro, 
crear un nuevo usuario en la base de datos y realizar cualquier acción adicional necesaria, como enviar un correo electrónico de confirmación.

Método login(): Este método se utiliza para autenticar a los usuarios en el sistema. 
Verifica las credenciales proporcionadas por el usuario (como correo electrónico y contraseña), verifica si son válidas y autentica al usuario en caso afirmativo. 
También puede gestionar la creación de sesiones o tokens de autenticación.

Método logout(): Este método se utiliza para cerrar la sesión del usuario autenticado actualmente. 
Puede destruir la sesión, eliminar tokens de autenticación o cualquier otro mecanismo utilizado para mantener la autenticación del usuario.

Método forgotPassword(): Este método se utiliza para manejar el proceso de recuperación de contraseña. 
Permite que un usuario solicite un restablecimiento de contraseña enviando un correo electrónico con instrucciones o generando un token temporal de restablecimiento de contraseña.

Método resetPassword(): Este método se utiliza para restablecer la contraseña de un usuario después de solicitarlo mediante el método forgotPassword(). 
Verifica la validez del token de restablecimiento de contraseña y permite que el usuario establezca una nueva contraseña.

Método updateProfile(): Este método se utiliza para permitir a los usuarios autenticados actualizar su perfil de usuario. 

*/