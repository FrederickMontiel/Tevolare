<?php
    require_once "./App/Controllers/DireccionesController.php";

    class DireccionesRoots{
        public $controller;

        public function __construct($app){
            $this->controller = new DireccionesController;

            /*$app->get('/direccion/{idDireccion}', function($req, $res){
                $this->controller->getInfoDirection($req, $res);
            });*/

            $app->post('/direccion/usuario/{idUsuario}', function($req, $res){
                $this->controller->addDirection($req, $res);
            });
        }
    }