<?php
ini_set ('display_errors', '1');
class M_aplicacion extends CI_Model{

        var $conexion;
        public function __construct(){

                parent::__construct();
                //$this->load->database();
        }

        function valida_usuario($username, $clave){
                $this->load->database();
                $result = $this->db->query("select id, nombre from usuario where nombre = '".$username."' and password='".$clave."'");
                $result = $result->result_array();
                $this->db->close();
                return $result;
        }
	

  } //fin clase
?>
