<?php
require_once "Db.php";
require_once "Parameters.php";

class Alumno {
    private $conexion;
    private $table = "alumno";
    
    private $id;
    private $anio;
    private $materias_aprobadas;
    private $Persona_idPersona;

    public function __construct() {
        $dbObj = new Db(
            Parameters::VALOR_BD_HOST,
            Parameters::VALOR_BD_NAME,
            Parameters::VALOR_BD_USER,
            Parameters::VALOR_BD_PASS
        );
        $this->conexion = $dbObj->getConnection();
    }

    public function getAlumnos() {
        $sql = "SELECT * FROM $this->table";
        $ST = $this->conexion->prepare($sql);
        $ST->execute();
        return $ST->fetchAll(PDO::FETCH_ASSOC);
    }

    // Versión básica por ID
    public function getAlumnoById($id) {
        $sql = "SELECT * FROM $this->table WHERE idAlumno = ?";
        $ST = $this->conexion->prepare($sql);
        $ST->execute([$id]);
        return $ST->fetch(PDO::FETCH_ASSOC);
    }

    // Versión extendida: incluye datos de la persona
    public function getAlumnosConPersona() {
        $sql = "SELECT a.*, p.nombre, p.apellido, p.dni, p.email
                FROM alumno a
                INNER JOIN persona p ON a.Persona_idPersona = p.idPersona";
        $ST = $this->conexion->prepare($sql);
        $ST->execute();
        return $ST->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save($param) {
        require_once 'Sanitiza.class.php';
        $exists = false;
    
        if (isset($param['idAlumno']) && $param['idAlumno'] !== "") {
            $actual = $this->getAlumnoById($param['idAlumno']);
            if ($actual && isset($actual['idAlumno'])) {
                $exists = true;
                $this->id = $actual['idAlumno'];
            }
        }
    
        // Asignar y validar datos
        if (isset($param['anio'])) {
            $this->anio = $param['anio']; 
        }
    
        if (isset($param['materias_aprobadas'])) {
            $sanitizedMaterias = Sanitiza::MATERIAS_APROBADAS($param['materias_aprobadas']);
            if ($sanitizedMaterias === false) {
                throw new Exception("Materias aprobadas inválidas. Debe ser una lista separada por comas con solo letras.");
            }
            $this->materias_aprobadas = $sanitizedMaterias;
        }
    
        if (isset($param['Persona_idPersona'])) {
            $this->Persona_idPersona = $param['Persona_idPersona']; 
        }
    
        if ($exists) {
            $sql = "UPDATE $this->table 
                    SET anio = ?, materias_aprobadas = ?, Persona_idPersona = ? 
                    WHERE idAlumno = ?";
            $ST = $this->conexion->prepare($sql);
            $ST->execute([
                $this->anio,
                $this->materias_aprobadas,
                $this->Persona_idPersona,
                $this->id
            ]);
        } else {
            $sql = "INSERT INTO $this->table (anio, materias_aprobadas, Persona_idPersona) 
                    VALUES (?, ?, ?)";
            $ST = $this->conexion->prepare($sql);
            $ST->execute([
                $this->anio,
                $this->materias_aprobadas,
                $this->Persona_idPersona
            ]);
            $this->id = $this->conexion->lastInsertId();
        }
    
        return $this->id;
    }
    

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE idAlumno = ?";
        $ST = $this->conexion->prepare($sql);
        return $ST->execute([$id]);
    }

    public function deleteByPersonaId($idPersona){
        $sql = "DELETE FROM alumno WHERE Persona_idPersona = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$idPersona]);
    }
}
