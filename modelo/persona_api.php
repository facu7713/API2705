<?php
require_once 'Persona.php';
header('Content-Type: application/json; charset=utf-8');

// Identificar la acción solicitada
$action = $_REQUEST['action'] ?? 'listar';

switch ($action) {
    case 'listar':
        // Listar todas las personas
        $objPersona = new Persona();
        $personas = $objPersona->getPersonas();
        echo json_encode($personas);
        break;

    case 'ver':
        // Ver una persona por ID
        $persona = new Persona();
        if (isset($_GET['id'])) {
            // Obtener una persona por ID (para ver o editar)
            $data = $persona->getPersonaById($_GET['id']);
            echo json_encode($data);
        } else {
            // Listar todas las personas
            $data = $persona->getPersonas();
            echo json_encode($data);
        }
        break;

    case 'guardar':
        // Guardar (crear o editar)
        $persona = new Persona();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $persona->save($_POST);
            echo json_encode(['success' => true, 'id' => $id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
        break;

        case 'eliminar':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $idPersona = $_POST['id'];
                // Primero eliminar los alumnos asociados (si existen)
                require_once 'Alumno.php';
                $objAlumno = new Alumno();
                $objAlumno->deleteByPersonaId($idPersona);
                // Luego eliminar la persona
                $objPersona = new Persona();
                $ok = $objPersona->delete($idPersona);
                echo json_encode(['success' => $ok]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Acción no reconocida']);
        break;
}
