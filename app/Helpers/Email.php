<?php

namespace App\Helpers;

use App\Helpers\TokenSession;
use App\Models\User;

class Email{

    static function sendEmailValidAccount($email, $idAccount){
           
            $emailConfig = [
                'protocol' => 'smtp',
                'SMTPHost' => 'sandbox.smtp.mailtrap.io',
                'SMTPPort' => 2525,
                'SMTPUser' => 'dd567f783f0744',
                'SMTPPass' => '8e98b61b756067',
                'mailType' => 'html',
                'charset' => 'UTF-8',
                'SMTPCrypto' => '',
                'wordWrap' => true,
                'newline' => "\r\n"
            ];  
            
            // TODO : Crear clase email y urilizar sus metodos aqui para la validacion de cuenta => Si valida actualizar el estado en BD de validado = true
            $emailSend = \Config\Services::email($emailConfig);

            $emailSend->setFrom('kaecheleaxel@gmail.com', 'axelx33 kaechele');// Mi correo
            $emailSend->setTo($email);//Destinatario
            
            $emailSend->setSubject('Validacion de cuanta');
            //Esto con JWT
            $token = TokenSession::generateToken( ["email" =>$email, "id" => $idAccount] , time() + 3600*24 );
            
            $dataView = [
               "linkValidCuenta" => "".BASE_URL."api/1.0/auth/validateAccount?tokenValid=".$token,
            ];

            $emailSend->setMessage(view("emailValidationCuenta",$dataView));

            return $emailSend->send();
    
    }

}