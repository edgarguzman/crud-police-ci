<?php

class Componente_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		require('./application/libraries/conexion_db2.php');
		$this->db = new ConexionDB2();
		$this->conexion = $this->db->conectarDB();
	}

	public function getValidaPoliza($poliza) {
		$result = null;

		$query = "SELECT CUWNOBJ . F_VALIDA_POLIZA_MANTENEDOR (".$poliza.") FROM SYSIBM.SYSDUMMY1" ;
		$stmt = db2_prepare($this->conexion, $query);
		if ($stmt) {
			if (db2_execute($stmt)) {
				while ($row = db2_fetch_array($stmt)) {
					$result = $row[0];
				}
			}
		}
		return ($result);
	}

	public function getAntecedentesPoliza($poliza) {
		$result = null;

		if ($poliza != null) {

			$query = "CALL CUWNOBJ.SERV_OBT_ANTECEDENTES_POLIZA(?)" ;
			$stmt = db2_prepare($this->conexion, $query);
			db2_bind_param($stmt, 1, 'poliza', DB2_PARAM_IN);
			if (db2_execute($stmt)) {
				$i = 0;
				while ($row = db2_fetch_assoc($stmt)) {
					$result[$i] = $row;
					$i++;
				}
			}
		}
		return $result;
	}

	public function getAntecedentesComponente($poliza, $componente) {
		$result = null;

		if ($poliza != null) {

			$query = "CALL CUWNOBJ.SERV_OBT_ANTECEDENTES_COMPONENTE(?,?)" ;
			$stmt = db2_prepare($this->conexion, $query);
			db2_bind_param($stmt, 1, 'poliza', DB2_PARAM_IN);
			db2_bind_param($stmt, 2, 'componente', DB2_PARAM_IN);
			if (db2_execute($stmt)) {
				$i = 0;
				while ($row = db2_fetch_assoc($stmt)) {
					$result[$i] = $row;
					$i++;
				}
			}
		}
		return $result;
	}

	public function getCoberturasPoliza($poliza) {
		$result = null;

		if ($poliza != null) {

			$query = "CALL CUWNOBJ.SERV_OBT_COBERTURAS_POLIZA(?)" ;
			$stmt = db2_prepare($this->conexion, $query);
			db2_bind_param($stmt, 1, 'poliza', DB2_PARAM_IN);
			if (db2_execute($stmt)) {
				$i = 0;
				while ($row = db2_fetch_assoc($stmt)) {
					$result[$i] = $row;
					$i++;
				}
			}
		}
		return $result;
	}

	public function getPrimas($plan, $tipoComponente, $actuarial, $capital) {
		$result = null;

		if ($plan != null && $tipoComponente != null && $actuarial != null && $capital != null) {
			$fecha = date('Ymd');

			if ($tipoComponente == 'H') {
				$query = "CALL PRWNOBJ.SERV_OBT_CALCULA_PRIMA_CAE_PF(?,?,?)" ;
				$stmt = db2_prepare($this->conexion, $query);
				db2_bind_param($stmt, 1, 'actuarial', DB2_PARAM_IN);
				db2_bind_param($stmt, 2, 'capital', DB2_PARAM_IN);
				db2_bind_param($stmt, 3, 'fecha', DB2_PARAM_IN);
			} else {
				if ($plan >= 11017 && $plan <= 11021)
					$query = "CALL PRWNOBJ.SERV_OBT_CALCULA_PRIMA_PF(?,?,?,?,?)" ;
				if ($plan >= 11024 && $plan <= 11028)
					$query = "CALL PRWNOBJ.SERV_OBT_CALCULA_PRIMA_MMP(?,?,?,?,?)" ;
				$stmt = db2_prepare($this->conexion, $query);
				db2_bind_param($stmt, 1, 'plan', DB2_PARAM_IN);
				db2_bind_param($stmt, 2, 'tipoComponente', DB2_PARAM_IN);
				db2_bind_param($stmt, 3, 'actuarial', DB2_PARAM_IN);
				db2_bind_param($stmt, 4, 'capital', DB2_PARAM_IN);
				db2_bind_param($stmt, 5, 'fecha', DB2_PARAM_IN);
			}
			if (db2_execute($stmt)) {
				$i = 0;
				while ($row = db2_fetch_assoc($stmt)) {
					$result[$i] = $row;
					$i++;
				}
			}
		}
		echo json_encode($result);
	}

	public function getRecargosComponente($poliza, $componente, $cobertura, $recargo) {
		$result = null;

		if ($poliza != null && $componente != null && $cobertura != null && $recargo != null) {
			
			$query = "CALL CUWNOBJ.SERV_OBT_RECARGOS_COMPONENTE(?,?,?,?)" ;
			$stmt = db2_prepare($this->conexion, $query);
			db2_bind_param($stmt, 1, 'poliza', DB2_PARAM_IN);
			db2_bind_param($stmt, 2, 'componente', DB2_PARAM_IN);
			db2_bind_param($stmt, 3, 'cobertura', DB2_PARAM_IN);
			db2_bind_param($stmt, 4, 'recargo', DB2_PARAM_IN);
			
			if (db2_execute($stmt)) {
				$i = 0;
				while ($row = db2_fetch_assoc($stmt)) {
					$result[$i] = $row;
					$i++;
				}
			}
		}
		echo json_encode($result);
	}

	public function insComponente($poliza, $tipoComponente, $rut, $nombre, $paterno, $materno, $fcNacimiento, $sexo, $nacionalidad, $capitalFallCAE, $capitalMaccRGF, $primaFallCAE, $primaMaccRGF, $recFallAct, $recFallSal, $recFallDep, $recMaccAct, $recMaccSal, $recMaccDep, $actComision, $usuario) {
		$result = null;
		$sqlcode = -1;

		if ($poliza != null && $tipoComponente != null && $nombre != null && $paterno != null && $materno != null && $fcNacimiento != null && $capitalFallCAE != null && $capitalMaccRGF != null && $primaFallCAE != null && $primaMaccRGF != null && $recFallAct != null && $recFallSal != null && $recFallDep != null && $recMaccAct != null && $recMaccSal != null && $recMaccDep != null && $actComision != null) {

			$query = "CALL CUWNOBJ.SERV_GUARDA_COMPONENTE(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)" ;
			$stmt = db2_prepare($this->conexion, $query);
			db2_bind_param($stmt, 1, 'poliza', DB2_PARAM_IN);
			db2_bind_param($stmt, 2, 'tipoComponente', DB2_PARAM_IN);
			db2_bind_param($stmt, 3, 'rut', DB2_PARAM_IN);
			db2_bind_param($stmt, 4, 'nombre', DB2_PARAM_IN);
			db2_bind_param($stmt, 5, 'paterno', DB2_PARAM_IN);
			db2_bind_param($stmt, 6, 'materno', DB2_PARAM_IN);
			db2_bind_param($stmt, 7, 'fcNacimiento', DB2_PARAM_IN);
			db2_bind_param($stmt, 8, 'sexo', DB2_PARAM_IN);
			db2_bind_param($stmt, 9, 'nacionalidad', DB2_PARAM_IN);
			db2_bind_param($stmt, 10, 'capitalFallCAE', DB2_PARAM_IN);
			db2_bind_param($stmt, 11, 'capitalMaccRGF', DB2_PARAM_IN);
			db2_bind_param($stmt, 12, 'primaFallCAE', DB2_PARAM_IN);
			db2_bind_param($stmt, 13, 'primaMaccRGF', DB2_PARAM_IN);
			db2_bind_param($stmt, 14, 'recFallAct', DB2_PARAM_IN);
			db2_bind_param($stmt, 15, 'recFallSal', DB2_PARAM_IN);
			db2_bind_param($stmt, 16, 'recFallDep', DB2_PARAM_IN);
			db2_bind_param($stmt, 17, 'recMaccAct', DB2_PARAM_IN);
			db2_bind_param($stmt, 18, 'recMaccSal', DB2_PARAM_IN);
			db2_bind_param($stmt, 19, 'recMaccDep', DB2_PARAM_IN);
			db2_bind_param($stmt, 20, 'actComision', DB2_PARAM_IN);
			db2_bind_param($stmt, 21, 'usuario', DB2_PARAM_IN);
			db2_bind_param($stmt, 22, 'sqlcode', DB2_PARAM_OUT);
			db2_execute($stmt);
			$result = $sqlcode;
		}
		return $result;
	}

	public function updComponente($poliza, $componente, $rut, $nombre, $paterno, $materno, $fcNacimiento, $sexo, $nacionalidad, $capitalFallCAE, $capitalMaccRGF, $primaFallCAE, $primaMaccRGF, $recFallAct, $recFallSal, $recFallDep, $recMaccAct, $recMaccSal, $recMaccDep, $actComision, $usuario) {
		$result = null;
		$sqlcode = -1;

		if ($poliza != null && $componente != null && $nombre != null && $paterno != null && $materno != null && $fcNacimiento != null && $capitalFallCAE != null && $capitalMaccRGF != null && $primaFallCAE != null && $primaMaccRGF != null && $recFallAct != null && $recFallSal != null && $recFallDep != null && $recMaccAct != null && $recMaccSal != null && $recMaccDep != null && $actComision != null) {

			$query = "CALL CUWNOBJ.SERV_ACTUALIZA_COMPONENTE(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)" ;
			$stmt = db2_prepare($this->conexion, $query);
			db2_bind_param($stmt, 1, 'poliza', DB2_PARAM_IN);
			db2_bind_param($stmt, 2, 'componente', DB2_PARAM_IN);
			db2_bind_param($stmt, 3, 'rut', DB2_PARAM_IN);
			db2_bind_param($stmt, 4, 'nombre', DB2_PARAM_IN);
			db2_bind_param($stmt, 5, 'paterno', DB2_PARAM_IN);
			db2_bind_param($stmt, 6, 'materno', DB2_PARAM_IN);
			db2_bind_param($stmt, 7, 'fcNacimiento', DB2_PARAM_IN);
			db2_bind_param($stmt, 8, 'sexo', DB2_PARAM_IN);
			db2_bind_param($stmt, 9, 'nacionalidad', DB2_PARAM_IN);
			db2_bind_param($stmt, 10, 'capitalFallCAE', DB2_PARAM_IN);
			db2_bind_param($stmt, 11, 'capitalMaccRGF', DB2_PARAM_IN);
			db2_bind_param($stmt, 12, 'primaFallCAE', DB2_PARAM_IN);
			db2_bind_param($stmt, 13, 'primaMaccRGF', DB2_PARAM_IN);
			db2_bind_param($stmt, 14, 'recFallAct', DB2_PARAM_IN);
			db2_bind_param($stmt, 15, 'recFallSal', DB2_PARAM_IN);
			db2_bind_param($stmt, 16, 'recFallDep', DB2_PARAM_IN);
			db2_bind_param($stmt, 17, 'recMaccAct', DB2_PARAM_IN);
			db2_bind_param($stmt, 18, 'recMaccSal', DB2_PARAM_IN);
			db2_bind_param($stmt, 19, 'recMaccDep', DB2_PARAM_IN);
			db2_bind_param($stmt, 20, 'actComision', DB2_PARAM_IN);
			db2_bind_param($stmt, 21, 'usuario', DB2_PARAM_IN);
			db2_bind_param($stmt, 22, 'sqlcode', DB2_PARAM_OUT);
			db2_execute($stmt);
			$result = $sqlcode;
		}
		return $result;
	}

	public function delComponente($poliza, $componente, $usuario) {
		$result = null;
		$sqlcode = -1;

		if ($poliza != null && $componente != null) {

			$query = "CALL CUWNOBJ.SERV_ELIMINA_COMPONENTE(?,?,?,?)" ;
			$stmt = db2_prepare($this->conexion, $query);
			db2_bind_param($stmt, 1, 'poliza', DB2_PARAM_IN);
			db2_bind_param($stmt, 2, 'componente', DB2_PARAM_IN);
			db2_bind_param($stmt, 3, 'usuario', DB2_PARAM_IN);
			db2_bind_param($stmt, 4, 'sqlcode', DB2_PARAM_OUT);
			db2_execute($stmt);
			$result = $sqlcode;
		}
		return $result;
	}

	public function delCobertura($poliza, $componente, $cobertura, $usuario) {
		$result = null;
		$sqlcode = -1;

		if ($poliza != null && $componente != null && $cobertura != null) {

			$query = "CALL CUWNOBJ.SERV_ELIMINA_COBERTURA_COMPONENTE(?,?,?,?,?)" ;
			$stmt = db2_prepare($this->conexion, $query);
			db2_bind_param($stmt, 1, 'poliza', DB2_PARAM_IN);
			db2_bind_param($stmt, 2, 'componente', DB2_PARAM_IN);
			db2_bind_param($stmt, 3, 'cobertura', DB2_PARAM_IN);
			db2_bind_param($stmt, 4, 'usuario', DB2_PARAM_IN);
			db2_bind_param($stmt, 5, 'sqlcode', DB2_PARAM_OUT);
			db2_execute($stmt);
			$result = $sqlcode;
		}
		return $result;
	}

}
