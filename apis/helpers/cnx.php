<?php 

Class Cnx{
    private $server = '127.0.0.1';
    private $usuario = 'root';
    private $pass = '';
    private $db = 'testdb';
    function connect(){
      $cnx = mysqli_connect($this->server,$this->usuario,$this->pass,$this->db);
      return $cnx;
    }
}