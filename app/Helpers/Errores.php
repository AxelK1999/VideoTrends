<?php

namespace App\Helpers;

class Errores{


    static private $listError = [

        -1 => "Contraseña o email ingresado no valido.",
        -2 => "Ingresar un email valido.",
        -3 => "Ingresar un contraseña valida.",
        -4 => "Las contraseñas no son iguales.",
        -5 => "Email ingresado ya se encuentra registrado, intenete otro.",
        -6 => "Nombre de ususario ya registrado, intente con otro.",
        -7 => "Nombre de ususario ingresado no valido.",
        -16 => "Apellido de ususario ingresado no valido.",
        -14 => "No valido, solo se aceptan lentras y espacios.",
        -8 => "Campo obligatorio.",
        -9 => "Direccion no valida, solo se aceptan letras, numeros y espacios.",
        -10 => "Longitud exedida de la permitida.",
        -11 => "Solo se aceptan numeros.",
        -12 => "Formato de datos no valido.",
        -13 => "Contraseña no valida.",
        -14 => "Cuanta no validada, ingrese al email de la cuanta para validarla mediante el correo de creacion notificado.",
        -15 => "Su cuenta no a podido ser validad, por expiracion de email de validacion.",
        -17 => 'Numero de telefono no valido.',
        -18 =>  "No se encontraron resultados de la búsqueda.",
        -19 => 'Movie ya existente en biblioteca',
        
        ];

        

    static function getMjError($id){
        try{

            if( !isset(self::$listError[$id])){
                return null;
            }
            
            return self::$listError[$id];

        }catch(\Exception $e){
            return null;
        }
    }

    

}