<?php
require_once 'Alumno.php';
header('Content-Type: application/json; charset=utf-8');

// Usamos $_REQUEST para capturar action tanto por GET como por POST
$action = $_REQUEST['action'] ?? 'listar_alumno';

switch ($action) {
    case 'crear_alumno':
        $objAlumno = new Alumno();
        $id = $objAlumno->save($_POST);
        echo json_encode(['success' => true, 'id' => $id]);
        break;

    case 'listar_alumno':
        // Listar alumnos con datos de la persona
        $objAlumno = new Alumno();
        $alumnos = $objAlumno->getAlumnosConPersona();
        echo json_encode($alumnos);
        break;

    case 'ver_alumno':
        $objAlumno = new Alumno();
        if (isset($_GET['id'])) {
            $data = $objAlumno->getAlumnoById($_GET['id']);
            echo json_encode($data);
        } else {
            echo json_encode(['success' => false, 'message' => 'Falta el ID']);
        }
        break;

    case 'guardar_alumno':
        $objAlumno = new Alumno();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $objAlumno->save($_POST);
            echo json_encode(['success' => true, 'id' => $id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
        break;

    case 'eliminar_alumno':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $objAlumno = new Alumno();
            $ok = $objAlumno->delete($_POST['id']);
            echo json_encode(['success' => $ok]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Acción no reconocida']);
        break;
}
