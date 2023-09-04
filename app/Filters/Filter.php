<?php

namespace App\Filters;

use App\Controllers\BaseController;
use App\Helpers\TokenSession;
use App\Helpers\Errores;
use Error;

class Filter extends BaseController{
    
    static function verifyAuth($request, $response){

            $permisos = $request->getCookie("permiso");
            //$result = ["success" => true , "urlRedir" => BASE_URL."api/1.0/views/login"];

            if( !(isset($permisos) && TokenSession::verifyToken($permisos))){
                
                //$response->redirect(BASE_URL."api/1.0/views/login");
                return false;
            }

            return true;
    }

    static public $rules = [ 
                                'passwordRegister' => ['password' => 'required|min_length[8]|matches[repeated-password]'],
                                'passwordUpdate' => ['password' => 'min_length[8]'],

                                'account' => [
                                    'email'    => 'required|valid_email|is_unique[users.email]',
                                    'username' => 'required|max_length[50]',
                                    'name' => 'permit_empty|alpha_space|max_length[50]',
                                    'lastname' => 'permit_empty|alpha_space|max_length[50]',
                                    'address' => 'permit_empty|alpha_numeric_space|max_length[100]',
                                    'sex' => 'permit_empty|alpha|max_length[1]',
                                    'phonenumber'=>'permit_empty|integer',
                                    'birthdate' => 'permit_empty|valid_date',
                                ],

                           ];

    static function validateDatesUser($request,$rules){

        $validation = service('validation');
        $validation->setRules($rules);

        $validationResult = $validation->withRequest($request)->run();

        $result = ["success" => $validationResult,
                   "validation" => $validation,
            
                    "inf" => [
                        "email" => $validation->hasError('email') ? Errores::getMjError(-5) :  "" ,
                        "password" => $validation->hasError('password') ? Errores::getMjError(-12) : "",
                        "username" => $validation->hasError('username') ? Errores::getMjError(-6) : "",
                        "name" => $validation->hasError('name') ? Errores::getMjError(-7) : "",
                        "lastname" => $validation->hasError('lastname') ? Errores::getMjError(-16) : "",
                        "address" => $validation->hasError('address') ? Errores::getMjError(-9) : "",
                        "sex" => $validation->hasError('sex') ? Errores::getMjError(-12) : "",
                        "phonenumber" => $validation->hasError('phonenumber') ? Errores::getMjError(-17) : "",
                        "birthdate" => $validation->hasError('birthdate') ? Errores::getMjError(-12) : "",
                    ],
                ];

        return $result;
    } 

    static function validateDatesUserUpdate($request){

        $validationResult = Filter::validateDatesUser($request, Filter::$rules['account']);

        return $validationResult;
    }

    //TODO : Ajustar para solo utilizar validateDatesUser
   static function validateDatesUserRegister($request, $response){

        $rulesRegister = array_merge( Filter::$rules['passwordRegister'], Filter::$rules['account'] );
        $validationResult = Filter::validateDatesUser($request, $rulesRegister);
     
        if(!$validationResult["success"]){
            //dd($validation->getErrors());//Permite debugear mostrando en pagina reglas que fallan y por que cuando se esta en modo desarrollo.

            $validation = $validationResult["validation"];

            $query = "?";
            //                   condicion                 true     false
            $query .= $validation->hasError('email') ? "email=-5&" :  "" ;
            $query .=$validation->hasError('password') ? "password=-12&" : "";
            $query .=$validation->hasError('username') ? "username=-6&" : "";
            $query .=$validation->hasError('name') ? "name=-7&" : "";
            $query .=$validation->hasError('lastname') ? "lastname=-7&" : "";
            $query .=$validation->hasError('address') ? "address=-9&" : "";
            $query .=$validation->hasError('sex') ? "sex=-12&" : "";
            $query .=$validation->hasError('phonenumber') ? "phonenumber=-11&" : "";
            $query .=$validation->hasError('birthdate') ? "birthdate=-12&" : "";
            // Eliminar el último '&' si no hay errores adicionales
            $query = rtrim($query, '&'); 
        
            $response->redirect(BASE_URL."api/1.0/views/register".$query);

            return false;
        }

        return true;

    }

}