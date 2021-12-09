<?php
    require_once "./App/Models/DireccionesModel.php";
    require_once "./App/Config/ImgBB.php";
    require_once "./App/Config/JsonWebToken.php";

    class DireccionesController{
        private $jwt;
        private $model;
        private $imgbb;
        private $jsonResponse = array("status" => 500, "data" => null, "message" => null);

        public function generateRandomString($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        public function __construct(){
            $this->jwt = new JsonWebToken();
            $this->model = new DireccionesModel();
        }

        public function addDirection($req, $res){
            if(isset($req->headers['Authorization'])){
                $token = $this->jwt->getToken($req->headers['Authorization']);

                if($token && $req->params['idUsuario'] == $token->data->idUsuario){
                    if(
                        isset($req->body['nombreDireccion']) &&
                        isset($req->files['imagenDireccion']) &&
                        isset($req->body['descripcionDireccion']) &&
                        isset($req->body['publicaDireccion'])
                    ){
                        $this->imgbb = new ImgBB($req->files['imagenDireccion']);
                        $img = $this->imgbb->getJson();
                        
                        if(isset($img['data']['url'])){
                            if($this->model->addDirection(
                                $req->params['idUsuario'],
                                $req->body['nombreDireccion'],
                                $img['data']['url'],
                                $req->body['descripcionDireccion'],
                                $req->body['publicaDireccion'],
                                $this->generateRandomString(50)
                            )){
                                $this->jsonResponse['status'] = 200;
                                $this->jsonResponse['message'] = "Se agregó correctamente";
                            }else{
                                $this->jsonResponse['status'] = 500;
                                $this->jsonResponse['message'] = "No has subido un formato de imagen aceptable";
                            }
                        }else{
                            $this->jsonResponse['status'] = 500;
                            $this->jsonResponse['message'] = "No has subido un formato de imagen aceptable";
                        }
                    }else{
                        $this->jsonResponse['status'] = 403;
                        $this->jsonResponse['message'] = "No has enviado los datos necesarios";
                    }
                }else{
                    $this->jsonResponse['status'] = 402;
                    $this->jsonResponse['message'] = "No tienes permisos para poder agregar";
                }
            }else{
                $this->jsonResponse['status'] = 403;
                $this->jsonResponse['message'] = "Necesitas enviar el token";
            }

            $res->status($this->jsonResponse['status'])->send($this->jsonResponse);
        }

        public function delete($idUsuario){
            echo "Eliminar ".$idUsuario;
        }
    }