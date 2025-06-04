<?php
require_once "Db.php";
Class Carrera{
    private $id;
    private $codigo;
    private $descripcion;
    private $habilitado;
    private $habilitacion_registro;
    private $conexion;
    public function getConection(){
        $dbObj=new Db();
        $this->conexion=$dbObj->conexion;
    }
    public function getCarreras(){
        $this->getConection();
        $sql="SELECT * FROM Carrera";
        $ST=$this->conexion->prepare($sql);
        $ST->execute();
        return $ST->fetchALL(PDO::FETCH_ASSOC);
    }
}
$dbCarrera=new Carrera();
var_dump($objCarrera->getCarreras());
?>