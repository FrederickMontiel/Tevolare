<?php
    use Psr\Http\Message\RequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;

    require_once "./App/Controllers/TokenController.php";

    class TokenRoots{
        public function __construct($app){
            $app->put('/token', function($req, $res){
                $controller = new TokenController();
                $controller->update($req, $res);
            });
        }
    }