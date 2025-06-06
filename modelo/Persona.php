<?php
require_once "Db.php";

class Persona {
    private $conexion;
    private $table = "persona";
    
    private $id;
    private $dni;
    private $nombre;
    private $apellido;
    private $email;
    private $telefono;

    public function __construct() {
        $dbObj = new Db(
            Parameters::VALOR_BD_HOST,
            Parameters::VALOR_BD_NAME,
            Parameters::VALOR_BD_USER,
            Parameters::VALOR_BD_PASS
        );
        $this->conexion = $dbObj->getConnection();
    }

    public function getPersonas() {
        $sql = "SELECT * FROM $this->table";
        $ST = $this->conexion->prepare($sql);
        $ST->execute();
        return $ST->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPersonaById($id) {
        $sql = "SELECT * FROM $this->table WHERE idPersona = ?";
        $ST = $this->conexion->prepare($sql);
        $ST->execute([$id]);
        return $ST->fetch(PDO::FETCH_ASSOC);
    }

    public function save($param) {
        require_once 'Sanitiza.class.php';
        $exists = false;
    
        if (isset($param['idPersona']) && $param['idPersona'] !== "") {
            $actual = $this->getPersonaById($param['idPersona']);
            if ($actual && isset($actual['idPersona'])) {
                $exists = true;
                $this->id = $actual['idPersona'];
            }
        }
    
        // Validar y sanitizar los datos
        $dni = Sanitiza::DNI($param['dni'] ?? '');
        $apellido = Sanitiza::APELLIDO($param['apellido'] ?? '');
        $nombre = Sanitiza::NOMBRE($param['nombre'] ?? '');
        $email = Sanitiza::EMAIL($param['email'] ?? '');
        $telefono = Sanitiza::TELEFONO($param['telefono'] ?? '');
    
        // Verificar si alguno falló
        if ($dni === false || $apellido === false || $nombre === false || $email === false || $telefono === false) {
            return false;
        }
    
        // Asignar valores ya validados
        $this->dni = $dni;
        $this->apellido = $apellido;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->telefono = $telefono;
    
        if ($exists) {
            $sql = "UPDATE $this->table SET dni = ?, apellido = ?, nombre = ?, email = ?, telefono = ? WHERE idPersona = ?";
            $ST = $this->conexion->prepare($sql);
            $ST->execute([$this->dni, $this->apellido, $this->nombre, $this->email, $this->telefono, $this->id]);
        } else {
            $sql = "INSERT INTO $this->table (dni, apellido, nombre, email, telefono) VALUES (?, ?, ?, ?, ?)";
            $ST = $this->conexion->prepare($sql);
            $ST->execute([$this->dni, $this->apellido, $this->nombre, $this->email, $this->telefono]);
            $this->id = $this->conexion->lastInsertId();
        }
    
        return $this->id;
    }
    

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE idPersona = ?";
        $ST = $this->conexion->prepare($sql);
        return $ST->execute([$id]);
    }
}

/* Pruebas
$objPersona = new Persona();

// Mostrar todas las personas
echo "<pre>";
print_r($objPersona->getPersonas());
echo "</pre>";

// Mostrar una por ID
$id = 1; // ejemplo de ID
echo "<pre>";
print_r($objPersona->getPersonaById($id));
echo "</pre>";*/
?>
