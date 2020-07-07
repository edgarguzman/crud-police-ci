<?php if ($antecedentes != null) { 
	foreach ($antecedentes as $row) { 
		$nrPoliza = number_format($row['NR_POLIZA'],0,',','.');
		$nrPlan = $row['NR_PLAN'];
		$dsPlanPoliza = $nrPlan.' - '.$row['NM_PLAN'];
		$actuarialAsegurado = getEdadActuarial(date_format2($row['FC_NAC_ASEGURADO'],'DEC'),date_format2($row['FC_VIGENCIA_COMPONENTE'],'DEC'));
		$fcVigPoliza = date_format2(date_format2($row['FC_VIGENCIA'],'DEC'),'EN','-');
		$fcVigComponente = date_format2(date_format2($row['FC_VIGENCIA_COMPONENTE'],'DEC'),'EN','-');
		$capitalPoliza = number_format($row['MN_CAPITAL'],0,',','.');
		$primaPoliza = number_format($row['MN_PRIMA_POLIZA']+$row['MN_PRIMA_CLAUSULA'],4,',','.');
		if ($row['MN_PRIMA_CLAUSULA'] != 0)
			$primaClausula = number_format($row['MN_PRIMA_CLAUSULA'],4,',','.');
		$rutContratante = number_format($row['NR_RUT_CONTRATANTE'],0,',','.');
		$dvContratante = $row['DV_RUT_CONTRATANTE'];
		$nmContratante = $row['NM_NOMBRES'] . ' ' . $row['NM_AP_PATERNO'] . ' ' . $row['NM_AP_MATERNO'];
	?>
		<div class="col">
			<div class="cell panel">
				<div class="header">
					Antecedentes Generales
				</div>
				<div class="body">
					<div class="cell">
						<form id="formPoliza" action="#">
							<div class="col">
								<div class="col width-2of12">
									<div class="cell">
										<label for="fcVigPoliza">Fecha de Vigencia</label>
									</div>
								</div>
								<div class="col width-1of12">
									<div class="cell">
										<input type="hidden" id="poliza" name="poliza" value="<?= (isset($nrPoliza)) ? $nrPoliza : '' ?>" readonly/>
										<input type="hidden" id="plan" name="plan" value="<?= (isset($nrPlan)) ? $nrPlan : '' ?>" readonly/>
										<input type="hidden" id="actuarialAsegurado" name="actuarialAsegurado" value="<?= (isset($actuarialAsegurado)) ? $actuarialAsegurado : '' ?>" readonly/>
										<input type="text" id="fcVigPoliza" name="fcVigPoliza" value="<?= (isset($fcVigPoliza)) ? $fcVigPoliza : '' ?>" class="date-es" readonly/>
									</div>
								</div>

								<div class="col width-5of12">
									<div class="cell">
										<label for="fcVigComponente">Fecha de Vigencia Nuevo Componente</label>
									</div>
								</div>
								<div class="col width-1of12">
									<div class="cell">
										<input type="text" id="fcVigComponente" name="fcVigComponente" value="<?= (isset($fcVigComponente)) ? $fcVigComponente : '' ?>" class="date-es" readonly/>
										<!--<input type="text" id="fcVigComponente" name="fcVigComponente" value="01-11-2015" class="date-es" readonly/>-->
									</div>
								</div>

								<div class="col width-fill">
									<div class="cell">
										<span class="icon icon-info-sign text-blue" title="Fecha de vigencia que ser&aacute; considerada para la incorporaci&oacute;n de un componente o cobertura, a partir de la fecha de pr&oacute;xima cobranza de la p&oacute;liza."></span>
									</div>
								</div>
							</div>
								
							<div class="col">
								<div class="col width-2of12">
									<div class="cell">
										<label for="dsPlanPoliza">Plan</label>
									</div>
								</div>
								<div class="col width-4of12">
									<div class="cell">
										<input type="text" id="dsPlanPoliza" name="dsPlanPoliza" value="<?= (isset($dsPlanPoliza)) ? $dsPlanPoliza : '' ?>" readonly/>
									</div>
								</div>
								
								<div class="col width-2of12">
									<div class="cell">
										<label for="capitalPoliza">Capital UF</label>
									</div>
								</div>
								<div class="col width-1of12">
									<div class="cell">
										<input type="text" id="capitalPoliza" name="capitalPoliza" value="<?= (isset($capitalPoliza)) ? $capitalPoliza : '' ?>" class="uf-dec-0" readonly/>
									</div>
								</div>

								<div class="col width-2of12">
									<div class="cell">
										<label for="primaPoliza">Prima Mensual UF</label>
									</div>
								</div>
								<div class="col width-1of12">
									<div class="cell">
										<input type="text" id="primaPoliza" name="primaPoliza" value="<?= (isset($primaPoliza)) ? $primaPoliza : '' ?>" class="uf-dec-4" title="<?= (isset($primaClausula)) ? $primaClausula.' (Prima Ahorro UF)' : '' ?>" readonly/>
									</div>
								</div>
							</div>

							<div class="col">
								<div class="col width-2of12">
									<div class="cell">
										<label for="rutContratante">Rut Contratante</label>
									</div>
								</div>
								<div class="col width-2of12">
									<div class="col width-4of8">
										<div class="cell">
											<input type="text" id="rutContratante" name="rutContratante" value="<?= (isset($rutContratante)) ? $rutContratante : '' ?>" class="number" readonly/>
										</div>
									</div>
									<div class="col width-1of8">
										<div class="cell">
											<input type="text" id="dvContratante" name="dvContratante" value="<?= (isset($dvContratante)) ? $dvContratante : '' ?>" class="dv" title="D&iacute;gito Verificador" readonly/>
										</div>
									</div>
									<div class="col width-fill"></div>
								</div>
								
								<div class="col width-4of12">
									<div class="cell">
										<label for="nombreContratante">Nombre Contratante</label>
									</div>
								</div>
								<div class="col width-4of12">
									<div class="cell">
										<input type="text" id="nombreContratante" name="nombreContratante" value="<?= (isset($nmContratante)) ? $nmContratante : '' ?>" readonly/>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
<?php }
} ?>
