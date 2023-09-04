<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class TokenSession {

    public static $algorithm ='HS256'; 
    private static $keySecret = 'myKey';

    public static function generateToken($user, $exp) {

        // Configura los datos del token
        $payload = array(
            'id' => $user['id'],
            'email' => $user['email'],
            'exp' => $exp // El token expira en una hora (3600 segundos)
            //rol
        );

        // Genera el token utilizando la clave secreta
        $token = JWT::encode($payload, self::$keySecret , self::$algorithm);

        return $token;
    }

    public static function verifyToken($token){
        try{

            JWT::decode( $token, new Key(self::$keySecret, self::$algorithm) );
            return true;

        }catch(\Exception $e){
            return false;
        }
    }

    public static function decodeToken($token){
        try{
            
            $decoded = JWT::decode( $token, new Key(self::$keySecret, self::$algorithm) );
            return (array)$decoded;

        }catch (ExpiredException $e) {
            // El token ha expirado
            log_message("info","Token expirado");
            return null;
        }catch (\Exception $e) {
            // Otras excepciones
            log_message("info","Token inválido");
            return null;
        }
    }

}

/*
$resultado = TokenSession->verificarToken($token, $claveSecreta);

if ($resultado !== false) {
    // Token válido, se puede acceder al contenido decodificado
    echo "Sujeto: " . $resultado->sub . "<br>";
    echo "Nombre: " . $resultado->name . "<br>";
    echo "Fecha de emisión: " . $resultado->iat . "<br>";
    // Otros claims personalizados
    echo "Rol: " . $resultado->role . "<br>";
} else {
    // Token inválido
    echo "Token inválido";
} 
*/