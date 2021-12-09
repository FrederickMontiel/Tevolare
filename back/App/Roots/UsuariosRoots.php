<?php
    require_once "./App/Controllers/UsuariosController.php";
    require_once "./App/Controllers/DireccionesController.php";

    class UsuariosRoots{
        public $controller;

        public function __construct($app){
            $this->controller = new UsuariosController();

            $app->post('/usuario/login', function($req, $res){
                $this->controller->login($req, $res);
            });

            $app->post('/usuario/register', function($req, $res){
                $this->controller->register($req, $res);
            });

            $app->put('/usuario/{idUsuario}', function($req, $res){
                $this->controller->update($req, $res);
            });

            $app->get('/usuario/{idUsuario}/direcciones', function($req, $res){
                $this->controller->listMyDirections($req, $res);
            });

            /*$app->delete('/usuario/{idUsuario}', function($idUsuario){
                $controller = new UsuariosController();
                $controller->delete($idUsuario);
            });*/
        }
    }