<?php
    require_once "./App/Models/UsuariosModel.php";
    require_once "./App/Models/DireccionesModel.php";
    require_once "./App/Config/JsonWebToken.php";

    class UsuariosController{
        private $jwt;
        private $model;
        private $direccionesModel;
        private $jsonResponse = array("status" => 500, "data" => null, "message" => null);

        public function __construct(){
            $this->jwt = new JsonWebToken();
            $this->model = new UsuariosModel();
            $this->direccionesModel = new DireccionesModel();
        }

        public function login($req, $res){
            if(
                isset($req->body['correo']) &&
                isset($req->body['clave'])
            ){
                $correo = htmlentities($req->body['correo']);
                $clave = htmlentities($req->body['clave']);

                if(preg_match("/(.*?)\@(.*?)\.(.*?)/im", $correo)){
                    $usuario = $this->model->buscarUsuario($correo);
                    if($usuario != false){
                        if(password_verify($clave, $usuario['claveUsuario'])){
                            unset($usuario['claveUsuario']);
                            $this->jsonResponse['status'] = 200;
                            $this->jsonResponse['data'] = $usuario;
                            $this->jsonResponse['token'] = $this->jwt->createToken($usuario);
                            $this->jsonResponse['message'] = "Has Iniciado Sesión";
                        }else{
                            $this->jsonResponse['status'] = 403;
                            $this->jsonResponse['message'] = "Los datos no coinciden";
                        }
                    }else{
                        $this->jsonResponse['status'] = 404;
                        $this->jsonResponse['message'] = "No existe el usuario";
                    }
                }else{
                    $this->jsonResponse['status'] = 403;
                    $this->jsonResponse['message'] = "No es un correo Valido";

                }
            }else{
                $this->jsonResponse['status'] = 403;
                $this->jsonResponse['message'] = "No se han enviado todos los parametros necesarios";
            }

            $res->status($this->jsonResponse['status'])->send($this->jsonResponse);
        }

        public function register($req, $res){
            if(
                isset($req->body['nombres']) &&
                isset($req->body['apellidos']) &&
                isset($req->body['correo']) &&
                isset($req->body['telefono']) &&
                isset($req->body['clave'])
            ){
                $nombres = htmlentities($req->body['nombres']);
                $apellidos = htmlentities($req->body['apellidos']);
                $correo = htmlentities($req->body['correo']);
                $telefono = htmlentities($req->body['telefono']);
                $clave = htmlentities($req->body['clave']);

                if(preg_match("/(.*?)\@(.*?)\.(.*?)/im", $correo)){
                    $filtered_phone_number = filter_var($telefono, FILTER_SANITIZE_NUMBER_INT);
                    if(strlen($filtered_phone_number) == 8){
                        $usuario = $this->model->buscarUsuario($correo);
                        if($usuario != false){
                            $this->jsonResponse['status'] = 403;
                            $this->jsonResponse['message'] = "Ya existe el usuario, por lo que no puedes crear otro con el mismo correo";
                        }else{
                            $integrado = $this->model->ingresarUsuario($nombres, $apellidos, $correo, $telefono, password_hash($clave, PASSWORD_BCRYPT), 1);
                            if($integrado != false){
                                /*$this->model->asignarRol($integrado, 5);*/
                                $usuario = $this->model->buscarUsuario($correo);

                                if($usuario != false){
                                    
                                    if(password_verify($clave, $usuario['claveUsuario'])){
                                        unset($usuario['claveUsuario']);
                                        $this->jsonResponse['status'] = 200;
                                        $this->jsonResponse['data'] = $usuario;
                                        $this->jsonResponse['token'] = $this->jwt->createToken($usuario);
                                        $this->jsonResponse['message'] = "Te has registrado correctamente";
                                    }else{
                                        $this->jsonResponse['status'] = 403;
                                        $this->jsonResponse['message'] = "Los datos no coinciden";
                                    }
                                }else{
                                    $this->jsonResponse['status'] = 404;
                                    $this->jsonResponse['message'] = "No existe el usuario";
                                }
                            }else{
                                $this->jsonResponse['status'] = 500;
                                $this->jsonResponse['message'] = "Error al integrase el usuario";
                            }
                        }
                    }else{
                        $this->jsonResponse['status'] = 403;
                        $this->jsonResponse['message'] = "No es un telefono valido";
                    }
                }else{
                    $this->jsonResponse['status'] = 403;
                    $this->jsonResponse['message'] = "No es un correo valido";
                }
            }else{
                $this->jsonResponse['status'] = 403;
                $this->jsonResponse['message'] = "No se han enviado todos los parametros necesarios";
            }

            $res->status($this->jsonResponse['status'])->send($this->jsonResponse);
        }

        public function listMyDirections($req, $res){
            if(isset($req->headers['Authorization'])){
                $token = $this->jwt->getToken($req->headers['Authorization']);

                if($token && $token->data->idUsuario == $req->params['idUsuario']){
                    if(intval($req->params['idUsuario'])){
                        $this->jsonResponse['status'] = 200;
                        $this->jsonResponse['data'] = $this->direccionesModel->getDirectionsByUser($req->params['idUsuario']);
                        $this->jsonResponse['message'] = "Se han listado tus direcciones";
                    }else{
                        $this->jsonResponse['status'] = 403;
                        $this->jsonResponse['message'] = "El parametro enviado no puede ser otro valor diferente a un entero";
                    }
                }else{
                    $this->jsonResponse['status'] = 403;
                    $this->jsonResponse['message'] = "El token que has enviado ha expirado o no es valido";
                }
            }else{
                $this->jsonResponse['status'] = 403;
                $this->jsonResponse['message'] = "Necesitas enviar el token";
            }

            $res->status($this->jsonResponse['status'])->send($this->jsonResponse);
        }

        public function update($req){
            var_dump($req);
        }

        public function delete($idUsuario){
            echo "Eliminar ".$idUsuario;
        }
    }