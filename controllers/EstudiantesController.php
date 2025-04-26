<?php

require_once CONFIG_PATH . '/database.php';
require_once MODELS_PATH . '/Estudiante.php';


class EstudiantesController
{
    private $model;

    public function __construct()
    {
        $database = new Database();
        $db = $database->getConnection();
        $this->model = new Estudiante($db);
    }

    public function index()
    {
        $estudiantes = $this->model->leer();
        require_once VIEWS_PATH. '/estudiantes/index.php';
    }

    public function mostrarFormulario($id = null)
    {
        if ($id) {
            $this->model->id = $id;
            if (!$this->model->leerUno()) {
                // Si no encuentra el estudiante, redirigir con mensaje de error
                $_SESSION['error'] = "Estudiante no encontrado";
                header("Location: index.php");
                exit();
            }
        }
        require_once VIEWS_PATH . '/estudiantes/_form.php';
    }

    // Método para procesar el formulario (crear/actualizar)
    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Asignar valores del formulario al modelo
            $this->model->dni = $_POST['dni'] ?? '';
            $this->model->nombres = $_POST['nombres'] ?? '';
            $this->model->apellidos = $_POST['apellidos'] ?? '';
            $this->model->fecha_nac = $_POST['fecha_nac'] ?? '';
            $this->model->sexo = $_POST['sexo'] ?? '';
            $this->model->grado = $_POST['grado'] ?? '';
            $this->model->carrera = $_POST['carrera'] ?? '';
            $this->model->jornada = $_POST['jornada'] ?? '';
            $this->model->seccion = $_POST['seccion'] ?? '';

            if (!empty($_POST['id'])) {
                // Actualizar estudiante existente
                $this->model->id = $_POST['id'];
                $resultado = $this->model->actualizar();
                $mensaje = $resultado ? "Estudiante actualizado correctamente" : "Error al actualizar estudiante";
            } else {
                // Crear nuevo estudiante
                $resultado = $this->model->crear();
                $mensaje = $resultado ? "Estudiante creado correctamente" : "Error al crear estudiante";
            }

            $_SESSION['mensaje'] = $mensaje;
            header("Location: index.php");
            exit();
        }
    }

    // Método para eliminar un estudiante
    public function eliminar($id)
    {
        if ($id) {
            $this->model->id = $id;
            $resultado = $this->model->eliminar();
            $_SESSION['mensaje'] = $resultado ? "Estudiante eliminado correctamente" : "Error al eliminar estudiante";
        } else {
            $_SESSION['error'] = "ID de estudiante no proporcionado";
        }

        header("Location: index.php");
        exit();
    }
}
