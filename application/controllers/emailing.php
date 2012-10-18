<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emailing extends CI_Controller {

	var $url_site; //http://{}
	var $url_base; //http://url_site/{}
        public function __construct()
        {
                parent::__construct();
                $this->load->library('session');
                $this->load->library('twig');
                $this->load->library('parser');
                $this->load->helper('form');
		$this->load->helper('file');
		$this->load->helper('url');
		$this->load->helper('download');
                $this->load->model('m_aplicacion');
		$this->url_site = base_url();
		$this->url_base = 'emailing';
        }

	public function index($uri = null)
	{
		//$this->prueba();
		$this->loginSite();
	}

	function navegacion($uri = null, $uri2 = null, $uri3 = null){
		
		if(!$uri)
			$this->loginSite();
		else{
			if ($uri != 'login' && $uri != 'logout' && !$this->compruebaSesion()){
				$this->loginSite();
				return;
			}
			switch($uri){
				case 'login':
					$this->validaIngreso();
					break;	
				case 'logout':
					$this->cerrarSesion();
					break;
				case 'lista_correo':
					$this->listaCorreoCsv($uri2);
					break;	
				case 'cliente':
					$this->leeCliente();
					break;
				case 'show_rec':
					$this->muestraRegistro($uri2);
					break;
				case 'plantilla_txt':
					$this->descargaPlantilla();
					break;
				default:
                                	$this->portalHome();
					break;				
			 }
		}
	}

	/*
	* Bloque Login 
	* Funciones: loginsite - compruebaSesion - validaIngreso - errorIngreso - cerrarSesion
	*/
	function loginSite(){
		//$this->twig->render('base.html', array('url' => $this->url_site.$this->url_base));
                $vista_parser = array(
                                        'titulo'        => 'PCFactory Online',
                	                'titulo_barra'  => 'Ingreso de Datos',
                                        'action_frm'    => $this->url_site.$this->url_base.'/login',
                                        'url'    	=> $this->url_site.$this->url_base
                                );

                $body = $this->parser->parse('login',$vista_parser, TRUE);
		$this->twig->render('login.html', $vista_parser);
                //$this->output->set_output($body);
	}

	function compruebaSesion(){
		if (!($this->session->userdata('id')))
			return false;
		else
			return true;
	}

	function validaIngreso(){
		$usuario = $_POST['user'];	
		$passwd = $_POST['user_pass'];
		$usuario = $this->m_aplicacion->valida_usuario($usuario, $passwd); 
		if(!empty($usuario)){
			$this->session->set_userdata($usuario[0]);
			//$this->guarda_historia('LOGIN'); para guardar historial usuario 
			//$this->arma_pagina($this->pagina_inicio());
			$this->portalHome();
		}
		else{
			$this->errorIngreso();
		}
	}

	function errorIngreso(){
		$parser = array('volver' => $this->url_site.$this->url_base);
		$html = $this->parser->parse('error_login',$parser, TRUE);
		$this->output->set_output($html);	
	}	

	function cerrarSesion(){
		$this->session->sess_destroy();
		header('Location: '.$this->url_site.$this->url_base);
		//$this->guarda_historia('LOGOUT'); para guardar historial usuario 
	}

	// fin bloque Login
	

	/*
	* Bloque opción lista_correo 
	* Funciones: listaCorreoCsv - generaUpload - procesaArchivo - descargaPlantilla
	*/
	function listaCorreoCsv($enviado = null)
	{
		if($enviado){
			$graba_version = $this->m_aplicacion->grabaVersion();
			if($graba_version) 
				$mensaje = $this->procesaArchivo();
			else
				$mensaje = 'Error tabla Versión'; 
			//log_message('error','[C] files: '.print_r($_FILES, true));
		}
		else
			$mensaje = '';
                $datos = array(
                                'upload' => $this->generaUpload('up_file'),
                                'url' => $this->url_site.$this->url_base,
				'mensaje' => $mensaje	
                        );
		$this->twig->render('listacsv.html', $datos);
	}

        function generaUpload($nombre)
	{
                $data = array(
                        'id'    => $nombre,
                        'name'  => $nombre,
                        'value' => '',
                        'size'  => '50'
                                );
                return form_upload($data);
        }

        function procesaArchivo(){
		$salida = 'Error en la carga';
		$reg_bueno = 0;
		$reg_malo = 0;
		$rango = 100;
                $tipo = explode("/", $_FILES['up_file']['type']);
                $ext  = substr($_FILES['up_file']['name'], -4);
                //log_message('error','[C] files: '.print_r($_FILES, true)); 
                //log_message('error','[C] ext: '.$ext); 
		if($ext == '.csv' || $ext == '.txt'){
			if($tipo[1] == 'plain' || $tipo[1] == 'csv'){
				$lineas = file($_FILES['up_file']['tmp_name']);
				$total_reg = count($lineas);
               			//log_message('error','[C] count lineas: '.count($lineas));
				if($total_reg < $rango){ 
					for($i = 0; $i < $total_reg; $i++){
               					//log_message('error','[C] lineas: '.print_r($lineas, true)); 
						$separa = explode(";", $lineas[$i]);
						if(count($separa) == 4){
							$reg_bueno ++;
							$registro[] = "('".$separa[0]."','".$separa[1]."','".$separa[2]."','".$separa[3]."', 1)";
                                                	       /*	$registro[] = array(
											'rut'	=> $separa[0],
											'nombre' => $separa[1],
											'correo' => $separa[2],
											'observacion' => $separa[3]	
										); */
						}
						else{
							$reg_malo ++;	
						}
			
					}
					$nuevo_reg = implode(",", $registro);
					$graba = $this->m_aplicacion->almacenaRegistro($nuevo_reg);
				}
				else{
					$resto = ($total_reg % $rango);
					$veces = (int) ($total_reg/$rango);
					$j = 0;
					while($j < $veces){
						for($i = ($j * $rango); $i < (($j+1)*100); $i++){
                                        		$separa = explode(";", $lineas[$i]);
                                                	if(count($separa) == 4){
                                                		$reg_bueno ++;
                                                	       	$registro[] = "('".$separa[0]."','".$separa[1]."','".$separa[2]."','".$separa[3]."', 1)";
                                                	}
                                                	else
                                                	       	$reg_malo ++;
						}
						$j++;
						$nuevo_reg = implode(",", $registro);
						$graba = $this->m_aplicacion->almacenaRegistro($nuevo_reg);
					}
					if($resto > 0){
						for($i = ($j * $rango); $i < $total_reg; $i++){
							$separa = explode(";", $lineas[$i]);
							if(count($separa) == 4){
								$reg_bueno ++;
								$registro[] = "('".$separa[0]."','".$separa[1]."','".$separa[2]."','".$separa[3]."', 1)";
							}
							else
								$reg_malo ++;
						}
						$nuevo_reg = implode(",", $registro);		
						$graba = $this->m_aplicacion->almacenaRegistro($nuevo_reg);
					}
				}
				$salida = 'Regstros insertados: '.$reg_bueno."</br>";
				$salida .= 'Registros con error: '.$reg_malo;
               			//log_message('error','[C] texto: '.print_r($registro, true)); 
				//$nuevo_reg = implode(",", $registro);
               			//log_message('error','[C] nuevo: '.print_r($nuevo_reg, true)); 
				//$salida .= print_r($texto);
			}
			else
				$salida = 'Tipo archivo no soportado';
			
		}
		else
			$salida = 'Extensión de archivo no soportada';
                //log_message('error','[C] files: '.print_r($ext, true)); 
                return $salida;

        }
			
	function descargaPlantilla()
	{
		$datos = file_get_contents("data/plantilla.txt");
		$this->twig->render('descarga_plantilla.html', array('descarga' => force_download('plantilla.txt', $datos)));
	}
	// fin bloque opción lista_correo

        /*
        * Bloque opción cliente 
        * Funciones: leeCliente 
        */
	function leeCliente()
	{
		;	
	}

	function portalHome()
	{
		$array_parser = array('url'  => $this->url_site.$this->url_base);
					
		$this->twig->render('inicio.html', $array_parser);
	} 

}
/* Fin clase */
