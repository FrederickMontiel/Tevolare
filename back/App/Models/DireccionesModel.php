<?php
    require_once "./App/Config/Database.php";

    class DireccionesModel{
        private $conn;

        public function __construct(){
            $database = new Database;
            $this->conn = $database->getConnection();
        }

        public function getDirectionsByUser($idUsuario){
            $sql = "SELECT d.idDireccion, d.nombreDireccion, d.imagenDireccion, d.descripcionDireccion, d.creadorDireccion, d.publicaDireccion, d.invitacionDireccion FROM Direcciones d, Usuarios u WHERE d.creadorDireccion = :idUsuario AND u.idUsuario = d.creadorDireccion";

            $query = $this->conn->prepare($sql);
            $query->bindParam(":idUsuario", $idUsuario);
            $query->execute();

            $temp = array();

            while($fila = $query->fetch(PDO::FETCH_ASSOC)){
                $temp[] = $fila;
            }

            return $temp;
        }

        public function addDirection(
            $idUsuario, 
            $nombreDireccion, 
            $imagenDireccion, 
            $descripcionDireccion, 
            $publicaDireccion,
            $invitacionDireccion
        ){
            $sql = "INSERT INTO Direcciones (nombreDireccion, imagenDireccion, descripcionDireccion, creadorDireccion, publicaDireccion, invitacionDireccion) VALUES (:nombreDireccion, :imagenDireccion, :descripcionDireccion, :creadorDireccion, :publicaDireccion, :invitacionDireccion)";
            $query = $this->conn->prepare($sql);
            $query->bindParam(":nombreDireccion", $nombreDireccion);
            $query->bindParam(":imagenDireccion", $imagenDireccion);
            $query->bindParam(":descripcionDireccion", $descripcionDireccion);
            $query->bindParam(":creadorDireccion", $idUsuario);
            $query->bindParam(":publicaDireccion", $publicaDireccion);
            $query->bindParam(":invitacionDireccion", $invitacionDireccion);
            
            if($query->execute()){
                return true;
            }else{
                return false;
            }
        }

    }