<?php
    require_once "./App/Config/Database.php";

    class UsuariosModel{
        private $conn;

        public function __construct(){
            $database = new Database;
            $this->conn = $database->getConnection();
        }

        public function buscarUsuario($correo){
            $sql = "SELECT * FROM Usuarios WHERE correoUsuario LIKE :correo LIMIT 1";

            $query = $this->conn->prepare($sql);
            $query->bindParam(":correo", $correo);
            $query->execute();

            $usuario = $query->fetch(PDO::FETCH_ASSOC);

            if(isset($usuario['idUsuario']) && strlen($usuario['idUsuario']) > 0){
                /*$idUsuario = $usuario['idUsuario'];

                $sql = "SELECT * FROM AsignacionesRolesUsuario WHERE usuarioARU = :usuario";

                $query = $this->conn->prepare($sql);
                $query->bindParam(":usuario", $idUsuario);
                $query->execute();

                while ($fila = $query->fetch(PDO::FETCH_ASSOC)){
                    $usuario['roles'][] = intval($fila['rolARU']);
                }*/

                return $usuario;
            }else{
                return false;
            }
        }

        public function ingresarUsuario($nombres, $apellidos, $correo, $telefono, $clave, $rol = 1, $plan = 1){
            $sql = "INSERT INTO Usuarios (nombresUsuario, apellidosUsuario, correoUsuario, telefonoUsuario, claveUsuario, rolUsuario, planUsuario) VALUES (:nombres, :apellidos, :correo, :telefono, :clave, :rol, :plan)";

            $query = $this->conn->prepare($sql);
            $query->bindParam(":nombres", $nombres);
            $query->bindParam(":apellidos", $apellidos);
            $query->bindParam(":correo", $correo);
            $query->bindParam(":telefono", $telefono);
            $query->bindParam(":clave", $clave);
            $query->bindParam(":rol", $rol);
            $query->bindParam(":plan", $plan);

            if($query->execute()){
                return $this->conn->lastInsertId();
            }else{
                return false;
            }
        }

        public function asignarRol($usuario, $rol){
            $sql = "INSERT INTO AsignacionesRolesUsuario (rolARU, usuarioARU) VALUES (:rolARU, :usuarioARU)";

            $query = $this->conn->prepare($sql);
            $query->bindParam(":rolARU", $rol);
            $query->bindParam(":usuarioARU", $usuario);

            if($query->execute()){
                return $this->conn->lastInsertId();
            }else{
                return false;
            }
        }

        /*public function asignarDirección($usuario, $direccion){
            $sql = "INSERT INTO AsignacionesRolesUsuario (rolARU, usuarioARU) VALUES (:rolARU, :usuarioARU)";

            $query = $this->conn->prepare($sql);
            $query->bindParam(":rolARU", $usuario);
            $query->bindParam(":usuarioARU", $rol);

            if($query->execute()){
                return $this->conn->lastInsertId();
            }else{
                return false;
            }
        }*/

        public function asignarEquipo($usuario, $equipo){
            $sql = "INSERT INTO AsignacionesEquiposUsuario (equipoAEU, usuarioAEU) VALUES (:equipoAEU, :usuarioAEU)";

            $query = $this->conn->prepare($sql);
            $query->bindParam(":equipoAEU", $usuario);
            $query->bindParam(":usuarioAEU", $equipo);

            if($query->execute()){
                return $this->conn->lastInsertId();
            }else{
                return false;
            }
        }
    }