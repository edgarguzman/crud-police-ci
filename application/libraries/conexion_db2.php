<?php

class ConexionDB2 {

    private $host;
    private $user;
    private $pass;
    private $db;
    private $error;

    public function __construct() {
        if ($_SERVER['SERVER_NAME'] == '192.168.100.13') {
            $this->host = 'NEWDESA';
            $this->user = 'rocamo01';
            $this->pass = 'rocamo01';

        } elseif ($_SERVER['SERVER_NAME'] == 'mexplota2') {
            // SQA
            $this->host = "SQA";
            $this->user = "UPCUW";
            $this->pass = "PWCUW";

        } elseif ($_SERVER['SERVER_NAME'] == 'mexplota') {
            // PRODUCCIÓN
            $this->host = "S1064BD0";
            $this->user = "UPCUW";
            $this->pass = "PWCUW";
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
