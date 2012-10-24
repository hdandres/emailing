<?php
ini_set ('display_errors', '1');
class M_aplicacion extends CI_Model{

        var $conexion;
        public function __construct()
	{

                parent::__construct();
                //$this->load->database();
        }

        function valida_usuario($username, $clave)
	{
                $this->load->database();
                $result = $this->db->query("select id, nombre from usuario where nombre = '".$username."' and password='".$clave."'");
                $result = $result->result_array();
                $this->db->close();
                return $result;
        }

	function almacenaRegistro($registro)
	{
		//log_message('error', 'datos_modelo: '.print_r($registro, true));
                $this->load->database();
		$result = $this->db->query("insert into lista_correo (rut, nombre, correo, observacion, origen_lista_id_origen, id_version) VALUES ".$registro);
		$this->db->close();
                if($result == FALSE)
                        return 0;
                else
                        return 1;
	}

	function grabaVersion()
	{
                $this->load->database();
		$result = $this->db->query("insert into version_correo (descripcion, fecha_inicio) VALUES ('".$_POST['ipt_descrip']."','".$_POST['fecha_inicio']."')");
                if($result == FALSE)
                        return 0;
                else
                        return $this->db->insert_id();
		$this->db->close();
	}

	function getClienteTotal()		
	{
		$this->load->database();
		$result = $this->db->query("SELECT count(*) as total from cliente;");
		$result = $result->result_array();
		$this->db->close();
		return $result[0]['total'];	
	}

	function getProdTotal()
	{
		$resp = 0;
        	$host="10.1.1.25";
        	$user="pcfactory";
        	$password="nmbPdSA2zf5ZNDdh";
        	$db = "pcfactory";
        	$tablename="cliente";

        	if (($link=@mysql_connect($host,$user,$password)))
                	if (mysql_select_db($db,$link))
                	{
				$query="SELECT count(*) FROM cliente where email REGEXP '(.*)@(.*)\.(.*)' AND enviar_publicidad <> 'NO';";
				$result = mysql_query($query, $link);
				$row = mysql_fetch_array ($result);
				$resp = $row[0];
                	}
        	mysql_free_result($result);
        	mysql_close($link);
				log_message('error', 'resp: '.$resp);
		return $resp;
	}

	function vaciarCliente()
	{
		$this->load->database();
		$this->db->truncate('cliente');
		$this->db->close();
		return;		
	}

	function generaLlave()
	{
		$this->load->database();
		$result = $this->db->query("UPDATE cliente set llave = MD5(concat(rut, email));");
		$this->db->close();
	}

        function vaciarSuscrito()
        {
                $this->load->database();
                $this->db->truncate('suscrito');
                $this->db->close();
                return;
        }
	
	function llaveSuscrito()
	{
		$this->load->database();
		$result = $this->db->query("UPDATE suscrito set llave = MD5(email);");
		$this->db->close();
	}

  } //fin clase
?>
