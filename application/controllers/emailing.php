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
					$this->listaCorreoCsv();
					break;	
				case 'recibe_file':
					$this->recibeFile($uri2);
					break;
				case 'show_rec':
					$this->muestraRegistro($uri2);
					break;
				default:
                                	$this->proceso();
					break;				
			 }
		}
	}

	/*
	* Bloque Login de aplicación
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
	
	function portalHome()
	{
		$array_parser = array('url'  => $this->url_site.$this->url_base);
					
		$this->twig->render('inicio.html', $array_parser);
	} 

	function listaCorreoCsv()
	{
                $datos = array(
                                'upload' => $this->genera_upload('up_file'),
                                'url' => $this->url_site.$this->url_base,
                        );
		$this->twig->render('listacsv.html', $datos);
	}

        function genera_upload($nombre){
                $data = array(
                        'id'    => $nombre,
                        'name'  => $nombre,
                        'value' => '',
                        'size'  => '50'
                                );
                return form_upload($data);
        }
			
}
/* Fin clase */
