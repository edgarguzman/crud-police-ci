<?php

class ConexionDB2 {

    private $host;
    private $user;
    private $pass;
    private $db;
    private $error;

    public function __construct() {
        if ($_SERVER['SERVER_NAME'] == 'localhost') {
            $this->host = 'dev';
            $this->user = 'root';
            $this->pass = '';

        } elseif ($_SERVER['SERVER_NAME'] == 'localhost') {
            $this->host = 'qa';
            $this->user = 'root';
            $this->pass = '';

        } elseif ($_SERVER['SERVER_NAME'] == 'localhost') {
            $this->host = 'prod';
            $this->user = 'root';
            $this->pass = '';
        }
    }

    public function conectarDB() {
        $this->db = db2_connect($this->host, $this->user, $this->pass);

        if (!$this->db) {
            $this->error = 'No se puede conectar al servidor de base de datos utilizando la configuración proporcionada.';
            return ($this->error);
        } else {
            $this->error = '';
            return ($this->db);
        }
    }

    public function cerrarDB() {
        db2_close($this->db);
    }

}
