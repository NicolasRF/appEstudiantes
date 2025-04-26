<?php

require_once CONFIG_PATH . '/database.php';

class Estudiante {
    private $conn;
    private $table_name = "estudiantes";

    public $id;
    public $dni;
    public $nombres;
    public $apellidos;
    public $fecha_nac;
    public $sexo;
    public $grado;
    public $carrera;
    public $jornada;
    public $seccion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear estudiante
    public function crear() {
        try {
            $this->conn->beginTransaction();
            
            $query = "INSERT INTO " . $this->table_name . " 
                      SET dni=:dni, nombres=:nombres, apellidos=:apellidos, 
                      fecha_nac=:fecha_nac, sexo=:sexo, grado=:grado, 
                      carrera=:carrera, jornada=:jornada, seccion=:seccion";
            
            $stmt = $this->conn->prepare($query);
            
            // Sanitizar datos
            $this->dni = htmlspecialchars(strip_tags($this->dni));
            $this->nombres = htmlspecialchars(strip_tags($this->nombres));
            $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
            $this->fecha_nac = htmlspecialchars(strip_tags($this->fecha_nac));
            $this->sexo = htmlspecialchars(strip_tags($this->sexo));
            $this->grado = htmlspecialchars(strip_tags($this->grado));
            $this->carrera = htmlspecialchars(strip_tags($this->carrera));
            $this->jornada = htmlspecialchars(strip_tags($this->jornada));
            $this->seccion = htmlspecialchars(strip_tags($this->seccion));
            
            // Vincular valores
            $stmt->bindParam(":dni", $this->dni);
            $stmt->bindParam(":nombres", $this->nombres);
            $stmt->bindParam(":apellidos", $this->apellidos);
            $stmt->bindParam(":fecha_nac", $this->fecha_nac);
            $stmt->bindParam(":sexo", $this->sexo);
            $stmt->bindParam(":grado", $this->grado);
            $stmt->bindParam(":carrera", $this->carrera);
            $stmt->bindParam(":jornada", $this->jornada);
            $stmt->bindParam(":seccion", $this->seccion);
            
            if($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            
            $this->conn->rollBack();
            return false;
            
        } catch(PDOException $exception) {
            $this->conn->rollBack();
            throw $exception;
        }
    }

    // Leer todos los estudiantes
    public function leer() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY apellidos, nombres";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function leerUno() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->dni = $row['dni'];
            $this->nombres = $row['nombres'];
            $this->apellidos = $row['apellidos'];
            $this->fecha_nac = $row['fecha_nac'];
            $this->sexo = $row['sexo'];
            $this->grado = $row['grado'];
            $this->carrera = $row['carrera'];
            $this->jornada = $row['jornada'];
            $this->seccion = $row['seccion'];
            
            return true;
        }
        
        return false;
    }
    

    // Actualizar estudiante
    public function actualizar() {
        try {
            $this->conn->beginTransaction();
            
            $query = "UPDATE " . $this->table_name . " 
                      SET dni=:dni, nombres=:nombres, apellidos=:apellidos, 
                      fecha_nac=:fecha_nac, sexo=:sexo, grado=:grado, 
                      carrera=:carrera, jornada=:jornada, seccion=:seccion
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            // Sanitizar datos
            $this->dni = htmlspecialchars(strip_tags($this->dni));
            $this->nombres = htmlspecialchars(strip_tags($this->nombres));
            $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
            $this->fecha_nac = htmlspecialchars(strip_tags($this->fecha_nac));
            $this->sexo = htmlspecialchars(strip_tags($this->sexo));
            $this->grado = htmlspecialchars(strip_tags($this->grado));
            $this->carrera = htmlspecialchars(strip_tags($this->carrera));
            $this->jornada = htmlspecialchars(strip_tags($this->jornada));
            $this->seccion = htmlspecialchars(strip_tags($this->seccion));
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            // Vincular valores
            $stmt->bindParam(":dni", $this->dni);
            $stmt->bindParam(":nombres", $this->nombres);
            $stmt->bindParam(":apellidos", $this->apellidos);
            $stmt->bindParam(":fecha_nac", $this->fecha_nac);
            $stmt->bindParam(":sexo", $this->sexo);
            $stmt->bindParam(":grado", $this->grado);
            $stmt->bindParam(":carrera", $this->carrera);
            $stmt->bindParam(":jornada", $this->jornada);
            $stmt->bindParam(":seccion", $this->seccion);
            $stmt->bindParam(":id", $this->id);
            
            if($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            
            $this->conn->rollBack();
            return false;
            
        } catch(PDOException $exception) {
            $this->conn->rollBack();
            throw $exception;
        }
    }

    // Eliminar estudiante
    public function eliminar() {
        try {
            $this->conn->beginTransaction();
            
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $this->id = htmlspecialchars(strip_tags($this->id));
            $stmt->bindParam(1, $this->id);
            
            if($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            
            $this->conn->rollBack();
            return false;
            
        } catch(PDOException $exception) {
            $this->conn->rollBack();
            throw $exception;
        }
    }
}
?>