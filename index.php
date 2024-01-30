<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
require 'flight/Flight.php';
// ``

Flight::register('db', 'PDO',array('mysql:host=localhost;dbname=api', 'root', ''));


Flight::route('GET /alumnos', function () {
     $sentencia = Flight::db()->prepare("SELECT * FROM `alumnos`");
     $sentencia->execute();
     $datos=$sentencia->fetchAll();
     Flight::json($datos);
});


//INSERTAR DATOS
Flight::route('POST /alumnos', function () {
       $nombre=(Flight::request()->data->nombre);
       $apellido=(Flight::request()->data->apellido);
       $correo=(Flight::request()->data->correo);

       $sql = "INSERT INTO alumnos (nombre, apellido, correo) VALUES(?,?,?)";

       $sentencia = Flight::db()->prepare($sql);
       $sentencia->bindParam(1,$nombre);
       $sentencia->bindParam(2,$apellido);
       $sentencia->bindParam(3,$correo);
       $sentencia->execute();

       Flight::jsonp(["Alumno agregado"]);
});

//ELIMINAR REGISTROS
Flight::route('DELETE /alumnos', function () {
    $id=(Flight::request()->data->id);
    $sql = "DELETE FROM alumnos WHERE id=?";
    $sentencia = Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$id);
    $sentencia->execute();
    Flight::jsonp(["Alumno eliminado"]);
});

//ACTUALIZAR REGISTROS
Flight::route('PUT /alumnos', function () {

    $id = Flight::request()->data->id;
    $nombre = Flight::request()->data->nombre;
    $apellido = Flight::request()->data->apellido;
    $correo = Flight::request()->data->correo;

    $sql = "UPDATE alumnos SET nombre=?, apellido=?, correo=? WHERE id=?";
    $sentencia = Flight::db()->prepare($sql);

    $sentencia->bindParam(1, $nombre);
    $sentencia->bindParam(2, $apellido);
    $sentencia->bindParam(3, $correo);
    $sentencia->bindParam(4, $id);

    $sentencia->execute();

    Flight::jsonp(["Alumno modificado"]);
});


//CONSULTAR UN DATO DE UNA TABLA.
Flight::route('GET /alumnos/@id', function ($id) {
    $sentencia = Flight::db()->prepare("SELECT * FROM `alumnos` WHERE id=?");
    $sentencia->bindParam(1, $id);
    $sentencia->execute();
    $datos=$sentencia->fetchAll();
    Flight::json($datos);
});

Flight::start();