<?php
try {
  $pdo = new PDO('pgsql:host=45.79.129.143;port=26257;dbname=defaultdb;sslmode=disable',
    'root', 'admin', array(
      PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_EMULATE_PREPARES => true,
      PDO::ATTR_PERSISTENT => true
    ));
} catch (PDOException $e) { // Catches errors thrown by sql
     throw new PDOException($e->getMessage(), (int)$e->getCode());
}