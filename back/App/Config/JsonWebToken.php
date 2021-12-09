<?php
    use Firebase\JWT\JWT;

    class JsonWebToken{
        private $key = 'N098y90n09N8N99ny809NYYnnyuNIuoiI';
        private $e;

        public function createToken($data, $cron = 60*60*24*31){
            $time = time();

            $token = array(
                'iat' => $time,
                'exp' => $time + ($cron),
                'data' => $data
            );

            return JWT::encode($token, $this->key);
        }

        public function getToken($jwt){
            try{
                $decoded = JWT::decode($jwt, $this->key, array('HS256'));
                return $decoded;
            }catch(Exception $e){
                $this->$e = $e;
                return false;
            }
        }

        public function getError(){ 
            return $this->e;
        }

        public function getMessageError(){
            return $this->e->getMessage();
        }
    }