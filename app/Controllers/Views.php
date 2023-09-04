<?php

namespace App\Controllers;

use  App\Models\User;
use App\Filters\Filter;
use App\Helpers\Errores;
use App\Helpers\TokenSession;

class Views extends BaseController
{
    public function home(){

        if(!Filter::verifyAuth($this->request, $this->response)){ 
            $this->response->redirect(BASE_URL."api/1.0/views/login");
            return; 
        }

        $userModel = new User();
      
        $permisos = $this->request->getCookie("permiso");
        $user = $userModel->find(TokenSession::decodeToken($permisos)['email']);
        log_message("info","Fecha de nacimiento : ". $user['birthdate']);
        
        $userData = [
            'email' => $user['email'],
            'username' => $user['username'],
            'name' => $user['name'],
            'lastname' => $user['lastname'],
            'phonenumber' => $user['phonenumber'],
            'sex' => $user['gender'],
            'birthdate' => $user['birthdate'],
            'address' => $user['address'],
        ];

        return view('home',$userData);
     
    }

    public function login(){

        $codAlert = $this->request->getVar('infAlert');

       if(!isset($codAlert) && Errores::getMjError($codAlert) === null){

            $date =[
                "stateAlert" =>  "visually-hidden",
                "mj" => ""
            ]; 
        
       }else{
            $date =[
                "stateAlert" =>  "",
                "mj" => Errores::getMjError($codAlert),
            ]; 
       }

        return view('login', $date);
    }

    public function register(){

        $date = [
            //Mensajes de fallo de validacion lado cliente(control html)
            "alertEmail" => Errores::getMjError(-8).", ".Errores::getMjError(-2), 
            "alertPassword" =>Errores::getMjError(-8).", ".Errores::getMjError(-3), 
            "alertUserName" =>Errores::getMjError(-8).", ".Errores::getMjError(-7), 
            "alertName" => Errores::getMjError(-14), 
            "alertLastName" => Errores::getMjError(-14), 
            "alertAddress" =>Errores::getMjError(-9),  
            "alertPhonenumber" =>Errores::getMjError(-11), 
            "alertRepitPassword" => Errores::getMjError(-4),

            //Mensajes de fallo de validacion lado servidor.
            "fallValidationServer" =>[
                "mj"=> Errores::getMjError( $this->request->getVar('email') ). " "
                      .Errores::getMjError( $this->request->getVar('password') ). " "
                      .Errores::getMjError( $this->request->getVar('username') )." ",

                "visibleFallValidationServer" => Errores::getMjError($this->request->getVar('email')) == null &&
                                                 Errores::getMjError($this->request->getVar('password')) == null &&
                                                 Errores::getMjError( $this->request->getVar('username') ) == null ? "visually-hidden" : "",
            ],
        ];
        
        return view('registroUsuario', $date);
    }
}
