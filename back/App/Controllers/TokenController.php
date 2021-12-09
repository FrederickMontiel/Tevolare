<?php
    require_once "./App/Config/JsonWebToken.php";

    class TokenController{
        private $jwt;
        private $jsonResponse = array("status" => 500, "data" => null, "message" => null);

        public function __construct(){
            $this->jwt = new JsonWebToken();
        }

        public function update($req, $res){
            if(isset($req->body['token'])){
                try{
                    $token = $this->jwt->getToken($req->body['token']);

                    $this->jsonResponse['status'] = 200;
                    $this->jsonResponse['data'] = $this->jwt->createToken(json_decode(json_encode($token->data), true));
                    $this->jsonResponse['message'] = "El token se actualizó";
                    
                    $res->status($this->jsonResponse['status'])->send($this->jsonResponse);
                }catch (Throwable $e){
                    $this->jsonResponse['status'] = 403;
                    $this->jsonResponse['message'] = "El token ya no es valido, inicia sesión denuevo";
                    
                    $res->status($this->jsonResponse['status'])->send($this->jsonResponse);
                }
            }else{
                $this->jsonResponse['status'] = 403;
                $this->jsonResponse['message'] = "Se necesita enviar el token";
                
                $res->status($this->jsonResponse['status'])->send($this->jsonResponse);
            }
        }
    }