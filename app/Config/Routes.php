<?php

namespace Config;

use CodeIgniter\CLI\Console;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.


$routes->group('/api/1.0', function ($routes){
    
    $routes->group("views",function($routes){
        $routes->get('login', 'Views::login');
        $routes->get('register', 'Views::register');
        $routes->get('home', 'Views::home');

    });


    $routes->group("auth",function($routes){
        $routes->post('login', 'Auth::login');
        $routes->post('register', 'Auth::registerUser');
        $routes->get('validateAccount','Auth::validateRegisteredAccount');
    });

    $routes->group("user",function($routes){
        
        $routes->get('existsEmail', 'UserController::existsEmail');
        $routes->put('update', 'UserController::updateUser');

        $routes->post('addMovie', 'UserController::addMovieInLibrary');
        //$routes->post('removeMovie', 'UserController::updateUser');

        $routes->get('myListMovies', 'UserController::listMoviesInLibrary');
        $routes->get('findMovie', 'UserController::findMovieByName');
        $routes->get('findMovieByCategory', 'UserController::findMovieByCategory');

        $routes->get('findMovieId', 'UserController::findMovieById');
        $routes->get('findCommentsById', 'UserController::findCommentsMovieById');
        $routes->get('recommendedMovies', 'UserController::recommendedMovies');
        $routes->delete('deltedMovie', 'UserController::removeMovieInLibrary');
        

    });

});



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
