<?php

namespace App\Controllers;

use App\Filters\Filter;
use App\Helpers\Errores;
use App\Helpers\Email;
use App\Models\User;
use App\Models\Library;
use App\Helpers\TokenSession;
use CodeIgniter\CLI\Console;

class UserController extends BaseController{

    function existsEmail(){

        $validation = service('validation');
        $validation->setRules([
            "email" => "is_unique[users.email]"
        ]);

        $email = [ "email" => $this->request->getVar('email') ];
        
        $this->response->setContentType("application/json");
        
        if( !$validation->run($email) ){
            return $this->response->setJSON(["exists" => true,
                                             "infErr" => Errores::getMjError(-5)]);
        }else{
            return $this->response->setJSON(["exists" => false, 
                                             "infErr" =>""]);
        }

    }

    function updateUser(){

        if(!Filter::verifyAuth($this->request, $this->response)){ 

            $this->response->setContentType("application/json");
            return $this->response->setJSON(["stateResult" =>false, 
                                             "inf" =>  '',
                                             "url" => BASE_URL."api/1.0/views/login",
                                            ]); 

        }

        $permisos = $this->request->getCookie("permiso");
        $Session = TokenSession::decodeToken($permisos);
        $email = $Session["email"];
        $idAccount = $Session["id"];

        $validated = true;            

        $result = Filter::validateDatesUserUpdate($this->request);
        $nroErrores = count($result["validation"]->getErrors());

        if(
            !$result["success"] && 
            $result["validation"]->hasError('email') && 
            $nroErrores === 1 &&
            $email === $this->request->getJSON()->email
        ){
            $result["success"] = true;
        }

        if($email === $this->request->getJSON()->email){

            $result["inf"]["email"] = '';
            
        }

      
       if($result["success"]){

            if( !$result["validation"]->hasError('email') ){

                $validated = false; 
                Email::sendEmailValidAccount($this->request->getJSON()->email, $idAccount);
                $this->response->setCookie("permiso", "", time() - 3600);

            }

            //$hash = password_hash( "".$this->request->getJSON()->password, PASSWORD_BCRYPT );
            $birthdate = $this->request->getJSON()->birthdate; 

            $dateUpdate = [
                /* "password" => $hash, */
                'email'   =>  $this->request->getJSON()->email, 
                'username' => $this->request->getJSON()->username,
                'name' => $this->request->getJSON()->name,
                'lastname'=> $this->request->getJSON()->lastname,
                'address' => $this->request->getJSON()->address,
                'gender' => $this->request->getJSON()->sex,
                'phonenumber'=> $this->request->getJSON()->phonenumber,
                'birthdate' => !empty($birthdate) ? $birthdate : null,
                'validated' => $validated,
            ];
 
            $user = new User();
            $user->update($email, $dateUpdate);

            $inf = "Actualizacion realizada con exito";

        }else{
            $inf =$result["inf"]["email"]
            ." ".$result["inf"]["username"]
            /* ." ".$result["inf"]["password"] */
            ." ".$result["inf"]["name"]
            ." ".$result["inf"]["lastname"]
            ." ".$result["inf"]["address"]
            ." ".$result["inf"]["sex"]
            ." ".$result["inf"]["phonenumber"]
            ." ".$result["inf"]["birthdate"];
        }

        $this->response->setContentType("application/json");
        return $this->response->setJSON(["stateResult" => $result["success"], 
                                         "inf" =>  $inf,
                                        ]);
    }

    function addMovieInLibrary(){

        if(!Filter::verifyAuth($this->request, $this->response)){ 

            $this->response->setContentType("application/json");
            return $this->response->setJSON(["stateResult" =>false, "inf" =>  '', "url" => BASE_URL."api/1.0/views/login"]); 

        }

        log_message('info',$this->request->getJSON()->idMovie);

        $permisos = $this->request->getCookie("permiso");
        $Session = TokenSession::decodeToken($permisos);
        
        $idMovie = $this->request->getJSON()->idMovie;
        $idUser = $Session["id"];

        $library = new Library();
        
        if($library->existenceOfCombination($idMovie,$idUser) == 0){

            $library->insert([
                'idmovie' => $idMovie,
                'iduser'  => $idUser,
            ]);

            $this->response->setContentType("application/json");
            return $this->response->setJSON(["stateResult" => true, "inf" =>  'Pelicula añadida con exito', "url" => '']);
        }


        $this->response->setContentType("application/json");
        return $this->response->setJSON(["stateResult" => false, "inf" =>  Errores::getMjError(-19), "url" => '']);
        
    }

    //TODO: Funcionalidad no finalizada
    function removeMovieInLibrary(){

        if(!Filter::verifyAuth($this->request, $this->response)){ 

            $this->response->setContentType("application/json");
            return $this->response->setJSON(["stateResult" =>false, "inf" =>  '', "url" => BASE_URL."api/1.0/views/login"]); 
            
        }

        $permisos = $this->request->getCookie("permiso");
        $Session = TokenSession::decodeToken($permisos);
        $idAccount = $Session["id"];

        $movie = $this->request->getVar("idMovie");

        $library = new Library();
        $library->where('iduser', $idAccount)->where('idmovie', $movie)->delete();

        $this->response->setContentType("application/json");
        return $this->response->setJSON(["stateResult" => true, "inf" =>  "", "url" => '']);

    }

    function listMoviesInLibrary(){

        if(!Filter::verifyAuth($this->request, $this->response)){ 

            $this->response->setContentType("application/json");
            return $this->response->setJSON(["stateResult" =>false, 
                                             "inf" =>  '',
                                             "url" => BASE_URL."api/1.0/views/login",
                                            ]); 

        }

        $permisos = $this->request->getCookie("permiso");
        $Session = TokenSession::decodeToken($permisos);
        $idAccount = $Session["id"];
        
        $library = new Library();
        $result = $library->getIdMovies($idAccount);
        $movies = [];

        foreach($result as $id){
            //log_message('info',$id->idmovie);
            $url = 'https://api.trakt.tv/movies/'.urlencode($id->idmovie)."?extended=full";
            array_push( $movies, $this->findMovie($url)['data'] );
            
        }

        $stateResult = false;
        
        if(count($movies) > 0){
            $stateResult = true;
        }

        $this->response->setContentType("application/json");
        return $this->response->setJSON( [ "stateResult" => $stateResult, "data" =>  $movies, 'inf' => '' ] );

    }

    function findMovieById(){
       
        $movie = $this->request->getVar("idMovie");
        $url = 'https://api.trakt.tv/movies/'.urlencode($movie)."?extended=full";
       
        $this->response->setContentType("application/json");
        return $this->response->setJSON($this->findMovie($url));

    }

    function findCommentsMovieById(){
        $movie = $this->request->getVar("idMovie");
        $url = 'https://api.trakt.tv/movies/'.urlencode($movie).'/comments/highest';
       
        $this->response->setContentType("application/json");
        return $this->response->setJSON($this->findMovie($url));
    }

    function findMovieByName(){

        $movie = $this->request->getVar("movie");
        $url = 'https://api.trakt.tv/search/movie?query=' . urlencode($movie)."&extended=full";

        $this->response->setContentType("application/json");
        return $this->response->setJSON($this->findMovie($url));
        

    }

    function findMovieByCategory(){
        $genre = $this->request->getVar("genre");
        $url = 'https://api.trakt.tv/movies/popular?genres='.$genre.'&limit=20&extended=full';
       // https://api.trakt.tv/movies/popular?genres={genre}&limit={limit}
        $this->response->setContentType("application/json");
        return $this->response->setJSON($this->findMovie($url));
    }

    function findMovie($url) {

        /* $movie = $this->request->getVar("movie");
        $url = 'https://api.trakt.tv/search/movie?query=' . urlencode($movie)."&extended=full"; */

        $headers = [
            'Content-Type: application/json',
            'trakt-api-version: 2',
            'trakt-api-key: 6f5823570b9049aab755f63b0981f7496f31efb73a7db68cbf756c8de5594762'
        ];
    
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
        $response = curl_exec($curl);
        $error = curl_error($curl);
    
        curl_close($curl);
        
        $inf = "";
        $statusResult = false;

        if ($error) {
            $statusResult = false;
            $inf = "Error: " . $error;
        } else {
            
            $data = json_decode($response, true);
            
            if (!empty($data)) {

                $statusResult = true;
            
            } else {

                $statusResult = false;
                $inf = Errores::getMjError(-18);
        
            }
        }

        /* $this->response->setContentType("application/json");
        return $this->response->setJSON( [ "stateResult" => $statusResult, "data" =>  $data, 'inf' => $inf ] ); */
        return [ "stateResult" => $statusResult, "data" =>  $data, 'inf' => $inf ];
    }

    //GET https://api.trakt.tv/movies/popular?genres=action&limit=10&page=2  --> para obtener otras 10 peliculas distintas
    function recommendedMovies(){
        $movie = $this->request->getVar("movie");
        $url = 'https://api.trakt.tv/movies/trending?extended=full&&limit=12';

        $this->response->setContentType("application/json");
        return $this->response->setJSON($this->findMovie($url));
    }

}