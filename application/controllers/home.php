<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		if (!isset($_SESSION)) {
			session_start();
		}
		if (!isset($_SESSION['rut'])) {
			session_destroy();
			show_error('Vuelve a iniciar sesi&oacute;n por Control de Acceso.',500,'Sesi&oacute;n Finalizada');
			exit();
		}
		$this->usuario = $_SESSION['rut'];
	}
	
	public function index() {
		$data = array('titulo' => 'Mantenedor de P&oacute;lizas');
		$this->load->view('mantenedor/head_view', $data);
		$this->load->view('mantenedor/header_view', $data);
		$this->load->view('mantenedor/content_view', $data);
		$this->load->view('mantenedor/footer_view', $data);
	}
	
	public function loadAntecedentes($tipo, $poliza) {

		if ($this->input->is_ajax_request()) {
			if ($tipo == '1') {
				$antecedentes = $this->componente_model->getAntecedentesPoliza($poliza);
				$data = array('antecedentes' => $antecedentes);
				$this->load->view('mantenedor/antecedentes_view', $data);
			} else {
				$conyuge = $this->componente_model->getAntecedentesComponente($poliza,2);
				$coberturas = $this->componente_model->getCoberturasPoliza($poliza);
				$data = array('conyuge' => $conyuge, 'coberturas' => $coberturas);
				$this->load->view('mantenedor/coberturas_view', $data);
			}
			
		} else {
			show_404();
		}
	}

	public function getterValidacion($poliza) {
		if ($this->input->is_ajax_request()) {
			$data = $this->componente_model->getValidaPoliza($poliza);
			//var_dump($data);
			echo $data;
		} else {
			show_404();
		}
	}

	public function getterPrimas($plan, $tipoComponente, $actuarial, $capital) {
		if ($this->input->is_ajax_request()) {
			$data = $this->componente_model->getPrimas($plan, $tipoComponente, $actuarial, $capital);
			//var_dump($data);
			echo $data;
		} else {
			show_404();
		}
	}

	public function getterRecargos($poliza, $componente, $cobertura, $recargo) {
		if ($this->input->is_ajax_request()) {
			$data = $this->componente_model->getRecargosComponente($poliza, $componente, $cobertura, $recargo);
			//var_dump($data);
			echo $data;
		} else {
			show_404();
		}
	}
	
	public function insertComponente() {
		if ($this->input->is_ajax_request()) {
			$poliza = $this->input->post('poliza');
			$tipoComponente = $this->input->post('tipoComponente');
			$rut = $this->input->post('rut');
			$nombre = utf8_decode($this->input->post('nombre'));
			$paterno = utf8_decode($this->input->post('paterno'));
			$materno = utf8_decode($this->input->post('materno'));
			$fcNacimiento = $this->input->post('fcNacimiento');
			$sexo = $this->input->post('sexo');
			$nacionalidad = utf8_decode($this->input->post('nacionalidad'));
			$capitalFallCAE = $this->input->post('capitalFallCAE');
			$capitalMaccRGF = $this->input->post('capitalMaccRGF');
			$primaFallCAE = $this->input->post('primaFallCAE');
			$primaMaccRGF = $this->input->post('primaMaccRGF');
			$recFallAct = $this->input->post('recFallAct');
			$recFallSal = $this->input->post('recFallSal');
			$recFallDep = $this->input->post('recFallDep');
			$recMaccAct = $this->input->post('recMaccAct');
			$recMaccSal = $this->input->post('recMaccSal');
			$recMaccDep = $this->input->post('recMaccDep');
			$actComision = $this->input->post('actComision');
			
			$data = $this->componente_model->insComponente($poliza, $tipoComponente, $rut, $nombre, $paterno, $materno, $fcNacimiento, $sexo, $nacionalidad, $capitalFallCAE, $capitalMaccRGF, $primaFallCAE, $primaMaccRGF, $recFallAct, $recFallSal, $recFallDep, $recMaccAct, $recMaccSal, $recMaccDep, $actComision, $this->usuario);
			//var_dump($data);
			echo $data;
		} else {
			show_404();
		}
	}

	public function updateComponente() {
		if ($this->input->is_ajax_request()) {
			$poliza = $this->input->post('poliza');
			$componente = $this->input->post('componente');
			$rut = $this->input->post('rut');
			$nombre = utf8_decode($this->input->post('nombre'));
			$paterno = utf8_decode($this->input->post('paterno'));
			$materno = utf8_decode($this->input->post('materno'));
			$fcNacimiento = $this->input->post('fcNacimiento');
			$sexo = $this->input->post('sexo');
			$nacionalidad = utf8_decode($this->input->post('nacionalidad'));
			$capitalFallCAE = $this->input->post('capitalFallCAE');
			$capitalMaccRGF = $this->input->post('capitalMaccRGF');
			$primaFallCAE = $this->input->post('primaFallCAE');
			$primaMaccRGF = $this->input->post('primaMaccRGF');
			$recFallAct = $this->input->post('recFallAct');
			$recFallSal = $this->input->post('recFallSal');
			$recFallDep = $this->input->post('recFallDep');
			$recMaccAct = $this->input->post('recMaccAct');
			$recMaccSal = $this->input->post('recMaccSal');
			$recMaccDep = $this->input->post('recMaccDep');
			$actComision = $this->input->post('actComision');

			$data = $this->componente_model->updComponente($poliza, $componente, $rut, $nombre, $paterno, $materno, $fcNacimiento, $sexo, $nacionalidad, $capitalFallCAE, $capitalMaccRGF, $primaFallCAE, $primaMaccRGF, $recFallAct, $recFallSal, $recFallDep, $recMaccAct, $recMaccSal, $recMaccDep, $actComision, $this->usuario);
			//var_dump($data);
			echo $data;
		} else {
			show_404();
		}
	}

	public function deleteComponente($poliza, $componente) {
		if ($this->input->is_ajax_request()) {
			$data = $this->componente_model->delComponente($poliza, $componente, $this->usuario);
			//var_dump($data);
			echo $data;
		} else {
			show_404();
		}
	}

	public function deleteCobertura($poliza, $componente, $cobertura) {
		if ($this->input->is_ajax_request()) {
			$data = $this->componente_model->delCobertura($poliza, $componente, $cobertura, $this->usuario);
			//var_dump($data);
			echo $data;
		} else {
			show_404();
		}
	}

}