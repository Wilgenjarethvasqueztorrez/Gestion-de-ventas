<?php
  // Credenciales de la base de datos local
  $host = "localhost";
  $user = "root";
  $pass = "wilgen12345";
  $db = "gestion_de_ventas";

  // Credenciales de la base de datos remota en infinityfree
  // $host = "sql112.infinityfree.com";
  // $user = "rif0_40373614";
  // $pass = "uu626Ffw8LFU74";
  // $db = "if0_40373614_gestion_de_ventas";

  $conexion =new mysqli($host,$user,$pass,$db);

  if (!$conexion) {
    echo 'Conexion fallida';
  }