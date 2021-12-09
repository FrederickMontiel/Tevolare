<?php
    class ImgBB{
        private $json;

        public function __construct($archivo){
            if(isset($archivo)){
                $host = "Tevolare"; //Escribe el nombre de tu empresa
                $llave = "d331d24463d39d6e0e35450efa4fe3b7"; //Ingresa la llave que te dió imgbb

                $bin = file_get_contents($archivo["tmp_name"]);
                $base64 = base64_encode($bin);

                //Si quieres que la imagen expire, puedes usar un parametro mas: "&expire=[Segundos]"
                $post = "key=".$llave."&name=imagen".$host."&image=".urlencode($base64);

                $ch =   curl_init();
                        curl_setopt($ch, CURLOPT_URL, "https://api.imgbb.com/1/upload");
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $resultado = curl_exec($ch);

                $this->json = json_decode($resultado, true);


            }
        }

        public function getJson(){
            return $this->json;
        }
    } 