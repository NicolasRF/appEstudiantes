<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Estudiante.php';

$database = new Database();
$db = $database->getConnection();
$estudiante = new Estudiante($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        // Leer uno o todos
        if(isset($_GET['id'])) {
            $estudiante->id = $_GET['id'];
            if($estudiante->leerUno()) {
                http_response_code(200);
                echo json_encode($estudiante);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Estudiante no encontrado."));
            }
        } else {
            $stmt = $estudiante->leer();
            $num = $stmt->rowCount();
            
            if($num > 0) {
                $estudiantes_arr = array();
                $estudiantes_arr["records"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $estudiante_item = array(
                        "id" => $id,
                        "dni" => $dni,
                        "nombres" => $nombres,
                        "apellidos" => $apellidos,
                        "fecha_nac" => $fecha_nac,
                        "sexo" => $sexo,
                        "grado" => $grado,
                        "carrera" => $carrera,
                        "jornada" => $jornada,
                        "seccion" => $seccion
                    );
                    array_push($estudiantes_arr["records"], $estudiante_item);
                }
                
                http_response_code(200);
                echo json_encode($estudiantes_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No se encontraron estudiantes."));
            }
        }
        break;
        
    case 'POST':
        // Crear estudiante
        $data = json_decode(file_get_contents("php://input"));
        
        if(
            !empty($data->dni) &&
            !empty($data->nombres) &&
            !empty($data->apellidos) &&
            !empty($data->fecha_nac) &&
            !empty($data->sexo) &&
            !empty($data->grado) &&
            !empty($data->carrera) &&
            !empty($data->jornada) &&
            isset($data->seccion)
        ) {
            $estudiante->dni = $data->dni;
            $estudiante->nombres = $data->nombres;
            $estudiante->apellidos = $data->apellidos;
            $estudiante->fecha_nac = $data->fecha_nac;
            $estudiante->sexo = $data->sexo;
            $estudiante->grado = $data->grado;
            $estudiante->carrera = $data->carrera;
            $estudiante->jornada = $data->jornada;
            $estudiante->seccion = $data->seccion;
            
            try {
                if($estudiante->crear()) {
                    http_response_code(201);
                    echo json_encode(array("message" => "Estudiante creado correctamente."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "No se pudo crear el estudiante."));
                }
            } catch(PDOException $exception) {
                http_response_code(500);
                echo json_encode(array(
                    "message" => "Error al crear el estudiante.",
                    "error" => $exception->getMessage()
                ));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos."));
        }
        break;
        
    case 'PUT':
        // Actualizar estudiante
        $data = json_decode(file_get_contents("php://input"));
        
        if(
            !empty($data->id) &&
            !empty($data->dni) &&
            !empty($data->nombres) &&
            !empty($data->apellidos) &&
            !empty($data->fecha_nac) &&
            !empty($data->sexo) &&
            !empty($data->grado) &&
            !empty($data->carrera) &&
            !empty($data->jornada) &&
            isset($data->seccion)
        ) {
            $estudiante->id = $data->id;
            $estudiante->dni = $data->dni;
            $estudiante->nombres = $data->nombres;
            $estudiante->apellidos = $data->apellidos;
            $estudiante->fecha_nac = $data->fecha_nac;
            $estudiante->sexo = $data->sexo;
            $estudiante->grado = $data->grado;
            $estudiante->carrera = $data->carrera;
            $estudiante->jornada = $data->jornada;
            $estudiante->seccion = $data->seccion;
            
            try {
                if($estudiante->actualizar()) {
                    http_response_code(200);
                    echo json_encode(array("message" => "Estudiante actualizado correctamente."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "No se pudo actualizar el estudiante."));
                }
            } catch(PDOException $exception) {
                http_response_code(500);
                echo json_encode(array(
                    "message" => "Error al actualizar el estudiante.",
                    "error" => $exception->getMessage()
                ));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos."));
        }
        break;
        
    case 'DELETE':
        // Eliminar estudiante
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id)) {
            $estudiante->id = $data->id;
            
            try {
                if($estudiante->eliminar()) {
                    http_response_code(200);
                    echo json_encode(array("message" => "Estudiante eliminado correctamente."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "No se pudo eliminar el estudiante."));
                }
            } catch(PDOException $exception) {
                http_response_code(500);
                echo json_encode(array(
                    "message" => "Error al eliminar el estudiante.",
                    "error" => $exception->getMessage()
                ));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "No se proporcionó ID."));
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método no permitido."));
        break;
}
?>