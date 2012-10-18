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

	function almacenaRegistro($registro){
		//log_message('error', 'datos_modelo: '.print_r($registro, true));
                $this->load->database();
		$result = $this->db->query("insert into lista_correo (rut, nombre, correo, observacion, origen_lista_id_origen) VALUES ".$registro);
		$this->db->close();
                if($result == FALSE)
                        return 0;
                else
                        return 1;
	}

	function grabaVersion(){
                $this->load->database();
		$result = $this->db->query("insert into version_correo (descripcion, fecha_inicio) VALUES ('".$_POST['ipt_descrip']."','".$_POST['fecha_inicio']."')");
		$this->db->close();
                if($result == FALSE)
                        return 0;
                else
                        return 1;
	}		

  } //fin clase
?>
