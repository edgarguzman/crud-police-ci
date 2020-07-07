<?php if ($coberturas != null) { ?>
	<div id="coberturasMayores">
		<div class="col">
			<div class="cell panel">
				<div class="header">
					Coberturas Contratadas
				</div>
				<div class="body">
					<div class="col">
						<div class="cell">
							<table class="header-border">
								<thead>
									<tr>
										<th class="aling-bot width-2of8" nowrap>Componente</th>
										<th class="aling-bot width-3of8" nowrap>Cobertura</th>
										<th class="aling-bot width-2of8" nowrap>Prima Mensual UF</th>
										<th class="aling-bot width-1of8" nowrap></th>
									</tr>
								</thead>
								<tbody>
								<?php
								$componente = '';
								foreach ($coberturas as $row) { 
									$plan = $row['NR_PLAN'];
									$prima = $row['MN_PRIMA']+$row['MN_RECARGO']; 

									if ($row['TP_COMPONENTE'] == 'T' || $row['TP_COMPONENTE'] == 'C') { ?>
										<tr id="<?= $row['NR_POLIZA'].$row['NR_COMPONENTE'].$row['NR_COBERTURA'] ?>">
											<th>
												<?php 
												if ($row['TP_COMPONENTE'] != $componente) {
													$componente = $row['TP_COMPONENTE'];
													if ($componente == 'T')
														echo 'TITULAR';
													else
														echo 'C&Oacute;NYUGE O SUSTITUTO';
												} ?>
												<input type="hidden" value="<?= ($row['TP_COMPONENTE']=='T') ? 'TITULAR' : 'C&Oacute;NYUGE O SUSTITUTO' ?>" />
											</th>
											<td><?= strtoupper($row['NM_COBERTURA']) ?></td>
											<td class="aling-center"><?= number_format($prima,4,',','') ?></td>
											<td>
												<div class="pull-right">
												<?php if ($row['TP_COBERTURA'] != 'M' && $row['TP_COBERTURA'] != 'H') { ?>
													<a href="<?= base_url('index.php/home/deleteCobertura').'/'.$row['NR_POLIZA'].'/'.$row['NR_COMPONENTE'].'/'.$row['NR_COBERTURA'] ?>" title="Eliminar"><span class="icon icon-remove"></span></a>
												<?php } ?>
												</div>
											</td>
										</tr>
								<?php }
								} ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<?php if ($plan >= 11017 && $plan <= 11021 && $conyuge == null) { ?>
					<div class="footer collapse-section">
						<div class="pull-right">
							<label class="pull-left">&iquest;Desea incorporar al C&oacute;nyuge o Sustituto?</label>
							<label class="pull-left"><input type="radio" name="incConyuge" value="S" class="aling-mid"/>S&iacute;</label>
							<label class="pull-left"><input type="radio" name="incConyuge" value="N" class="aling-mid" checked/>No</label>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

	<?php
	if ($conyuge != null) {
		foreach ($conyuge as $row) { 
			$rutConyuge = number_format($row['NR_RUT'],0,',','.');
			$dvConyuge = $row['DV_RUT'];
			$nmConyuge = $row['NM_NOMBRES'];
			$apPatConyuge = $row['NM_AP_PATERNO'];
			$apMatConyuge = $row['NM_AP_MATERNO'];
			$fcNacConyuge = date_format2(date_format2($row['FC_NACIMIENTO'],'DEC'),'EN','-');
			$actuarialConyuge = $row['NR_EDAD_ACTUARIAL'];
			$sexoConyuge = $row['TP_SEXO'];
			$nacionConyuge = $row['DS_NACIONALIDAD'];
			$capitalConyuge = number_format($row['MN_CAPITAL'],0,',','.');
			$primaConyuge = number_format($row['MN_PRIMA']+$row['MN_RECARGO'],4,',','.');
		}
	}?>

	<div id="antecedentesConyuge">
		<div class="col">
			<div class="cell panel">
				<div class="header">
					Antecedentes del C&oacute;nyuge o Sustituto
				</div>
				<div class="body">
					<div class="cell">
						<form id="formConyuge" action="#">
							<div class="col">
								<div class="col width-2of12">
									<div class="cell">
										<label for="rutConyuge">Rut</label>
									</div>
								</div>
								<div class="col width-2of12">
									<div class="col width-4of8">
										<div class="cell">
											<input type="text" id="rutConyuge" name="rutConyuge" value="<?= (isset($rutConyuge)) ? $rutConyuge : '' ?>" class="number" <?= ($conyuge!=null) ? 'readonly' : '' ?> />
										</div>
									</div>
									<div class="col width-1of8">
										<div class="cell">
											<input type="text" id="dvConyuge" name="dvConyuge" value="<?= (isset($dvConyuge)) ? $dvConyuge : '' ?>" class="dv" title="D&iacute;gito Verificador" <?= ($conyuge!=null) ? 'readonly' : '' ?> />
										</div>
									</div>
									<div class="col width-fill"></div>
								</div>

								<div class="col width-4of12">
									<div class="cell">
										<label for="nombreConyuge">Nombres</label>
									</div>
								</div>
								<div class="col width-4of12">
									<div class="cell">
										<input type="text" id="nombreConyuge" name="nombreConyuge" value="<?= (isset($nmConyuge)) ? $nmConyuge : '' ?>" class="name-full" <?= ($conyuge!=null) ? 'readonly' : '' ?> />
									</div>
								</div>
							</div>

							<div class="col">
								<div class="col width-2of12">
									<div class="cell">
										<label for="paternoConyuge">Apellido Paterno</label>
									</div>
								</div>
								<div class="col width-4of12">
									<div class="cell">
										<input type="text" id="paternoConyuge" name="paternoConyuge" value="<?= (isset($apPatConyuge)) ? $apPatConyuge : '' ?>" class="name" <?= ($conyuge!=null) ? 'readonly' : '' ?> />
									</div>
								</div>

								<div class="col width-2of12">
									<div class="cell">
										<label for="maternoConyuge">Apellido Materno</label>
									</div>
								</div>
								<div class="col width-4of12">
									<div class="cell">
										<input type="text" id="maternoConyuge" name="maternoConyuge" value="<?= (isset($apMatConyuge)) ? $apMatConyuge : '' ?>" class="name" <?= ($conyuge!=null) ? 'readonly' : '' ?> />
									</div>
								</div>
							</div>

							<div class="col">
								<div class="col width-2of12">
									<div class="cell">
										<label for="fcNacConyuge">Fecha de Nacimiento</label>
									</div>
								</div>
								<div class="col width-1of12">
									<div class="cell">
										<input type="text" id="fcNacConyuge" name="fcNacConyuge" value="<?= (isset($fcNacConyuge)) ? $fcNacConyuge : '' ?>" class="date-es" <?= ($conyuge!=null) ? 'readonly' : '' ?> />
									</div>
								</div>

								<div class="col width-5of12">
									<div class="cell">
										<label for="actuarialConyuge">Edad Actuarial</label>
									</div>
								</div>
								<div class="col width-1of12">
									<div class="cell">
										<input type="text" id="actuarialConyuge" name="actuarialConyuge" value="<?= (isset($actuarialConyuge)) ? $actuarialConyuge : '' ?>" readonly/>
									</div>
								</div>

								<div class="col width-2of12">
									<div class="cell">
										<label for="sexoConyuge">Sexo</label>
									</div>
								</div>
								<div class="col width-1of12">
									<div class="cell">
										<label for="sexoConyugeF" class="pull-left" title="Femenino"><input type="radio" id="sexoConyugeF" name="sexoConyuge" value="F" <?= (isset($sexoConyuge) && $sexoConyuge=='F') ? 'checked' : '' ?> class="aling-mid" <?= ($conyuge!=null) ? 'disabled' : '' ?> />F</label>
										<label for="sexoConyugeM" class="pull-left" title="Masculino"><input type="radio" id="sexoConyugeM" name="sexoConyuge" value="M" <?= (isset($sexoConyuge) && $sexoConyuge=='M') ? 'checked' : '' ?> class="aling-mid" <?= ($conyuge!=null) ? 'disabled' : '' ?> />M</label>
									</div>
								</div>
							</div>

							<div class="col">
								<div class="col width-2of12">
									<div class="cell">
										<label for="nacionalidadConyuge">Nacionalidad</label>
									</div>
								</div>
								<div class="col width-2of12">
									<div class="cell">
										<input type="text" id="nacionalidadConyuge" name="nacionalidadConyuge" value="<?= (isset($nacionConyuge)) ? $nacionConyuge : '' ?>" class="nacion" <?= ($conyuge!=null) ? 'readonly' : '' ?> />
									</div>
								</div>
								
								<div class="col width-4of12">
									<div class="cell">
										<label for="capitalConyuge">Capital Asegurado UF</label>
									</div>
								</div>
								<div class="col width-1of12">
									<div class="cell">
										<input type="text" id="capitalConyuge" name="capitalConyuge" value="<?= (isset($capitalConyuge)) ? $capitalConyuge : '' ?>" class="uf-dec-0" readonly/>
									</div>
								</div>

								<div class="col width-2of12">
									<div class="cell">
										<label for="primaConyuge">Prima Mensual UF</label>
									</div>
								</div>
								<div class="col width-1of12">
									<div class="cell">
										<input type="hidden" id="primaFall" name="primaFall" readonly/>
										<input type="hidden" id="primaMacc" name="primaMacc" readonly/>
										<input type="text" id="primaConyuge" name="primaConyuge" value="<?= (isset($primaConyuge)) ? $primaConyuge : '' ?>" class="uf-dec-4" readonly/>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<?php if ($conyuge == null) { ?>
					<div class="footer collapse-section">
						<div class="pull-right">
							<button type="button" id="guardarConyuge" class="button icon-button"><b>Guardar</b><span class="icon icon-save"></span></button>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	
	<div id="coberturasMenores">
		<div class="col">
			<div class="cell panel datasheet">
				<div class="header">
					Cl&aacute;usula de Ayuda Educacional y Reembolso de Gastos Funerarios para Hijo
				</div>
				<table class="body">
					<thead>
						<tr>
							<th class="aling-bot aling-center width-1of27" nowrap>#</th>
							<th class="aling-bot width-5of27" nowrap>Nombres&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class="aling-bot width-4of27" nowrap>Apellido Paterno&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class="aling-bot width-4of27" nowrap>Apellido Materno&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th class="aling-bot aling-center width-2of27" nowrap>Fecha de<br>Nacimiento</th>
							<th class="aling-bot aling-center width-1of27" nowrap>Edad<br>Actuarial</th>
							<th class="aling-bot aling-center width-1of27" nowrap>Inc.<br>CAE</th>
							<th class="aling-bot aling-center width-2of27" title="Cl&aacute;usula de Ayuda Educacional" nowrap>Renta<br>Anual UF</th>
							<th class="aling-bot aling-center width-1of27" nowrap>Inc.<br>RGF</th>
							<th class="aling-bot aling-center width-2of27" title="Reembolso de Gastos Funerarios" nowrap>Monto UF</th>
							<th class="aling-bot aling-center width-2of27" nowrap>Prima<br>Mensual UF</th>
							<th class="aling-bot aling-center width-1of27" nowrap><span class="icon icon-angle-down"></span></th>
							<th class="aling-bot aling-center width-1of27" nowrap><span class="icon icon-angle-down"></span></th>
						</tr>
					</thead>
					<tbody id="detCoberturasMenores">
					<?php
					$hijo = 0; 
					$componente = '';
					foreach ($coberturas as $row) { 
						$fcVigCAE = 0;
						$fcVigRGF = 0;
						$capitalCAE = 0;
						$capitalRGF = 0;
						$primaCAE = 0;
						$recargoCAE = 0;
						$primaRGF = 0;
						$primaHijo = 0;
						$fcVigHijo = 0;
						if ($row['TP_COMPONENTE'] == 'H' && $row['NR_COMPONENTE'] != $componente) { 
							$componente = $row['NR_COMPONENTE'];
							$hijo++; 
							foreach ($coberturas as $row2) { 
								if ($row2['NR_COMPONENTE'] == $componente) { 
									if ($row2['TP_COBERTURA'] == 'E') { 
										$fcVigCAE = $row2['FC_VIGENCIA'];
										$capitalCAE = $row2['MN_CAPITAL'] ; 
										$primaCAE = $row2['MN_PRIMA'] ; 
										$recargoCAE = $row2['MN_RECARGO'] ; 
										$primaHijo += $row2['MN_PRIMA'] + $row2['MN_RECARGO'] ; 
									} elseif ($row2['TP_COBERTURA'] == 'F') { 
										$fcVigRGF = $row2['FC_VIGENCIA'];
										$capitalRGF = $row2['MN_CAPITAL'] ; 
										$primaRGF = $row2['MN_PRIMA'] ; 
										$primaHijo += $row2['MN_PRIMA'] ; 
									}
								}
							}

							if ($fcVigCAE > 0 && $fcVigRGF > 0) {
								if ($fcVigCAE < $fcVigRGF)
									$fcVigHijo = $fcVigCAE;
								else
									$fcVigHijo = $fcVigRGF;
							} else {
								if ($fcVigCAE > 0)
									$fcVigHijo = $fcVigCAE;
								if ($fcVigRGF > 0)
									$fcVigHijo = $fcVigRGF;
							}
							?>
							<tr id="<?= $row['NR_POLIZA'].$row['NR_COMPONENTE'] ?>">
								<th class="aling-mid aling-center"><?= $hijo ?></th>
								<td class="aling-mid"><?= $row['NM_NOMBRES'] ?></td>
								<td class="aling-mid"><?= $row['NM_AP_PATERNO'] ?></td>
								<td class="aling-mid"><?= $row['NM_AP_MATERNO'] ?></td>
								<td class="aling-mid aling-center">
									<input type="text" id="fcNacHijo" name="fcNacHijo" value="<?= date_format2(date_format2($row['FC_NACIMIENTO'],'DEC'),'EN','-') ?>" class="date-es" style="cursor:default;background-color:transparent" readonly/>
									<input type="hidden" id="fcVigHijo" name="fcVigHijo" value="<?= date_format2(date_format2($fcVigHijo,'DEC'),'EN','-') ?>" />
								</td>
								<td class="aling-mid aling-center"><?= getEdadActuarial(date_format2($row['FC_NACIMIENTO'],'DEC'),date_format2($fcVigHijo,'DEC')) ?></td>
								<td class="aling-mid aling-center">
									<input type="hidden" id="actuarialCAE" name="actuarialCAE" value="<?= ($fcVigCAE!=0) ? getEdadActuarial(date_format2($row['FC_NAC_ASEGURADO'],'DEC'),date_format2($fcVigCAE,'DEC')) : '' ?>" />
									<input type="hidden" id="fcVigCAE" name="fcVigCAE" value="<?= date_format2(date_format2($fcVigCAE,'DEC'),'EN','-') ?>" />
									<input type="hidden" id="primaCAE" name="primaCAE" value="<?= number_format($primaCAE+$recargoCAE,4,',','') ?>" />
									<input type="hidden" id="recargoCAE" name="recargoCAE" value="<?= number_format($recargoCAE,4,',','') ?>" />
									<input type="checkbox" id="incCAE" name="incCAE" <?php if ($capitalCAE > 0) { echo 'checked'; } ?> class="aling-mid" disabled/>
								</td>
								<td class="aling-mid aling-center"><?php if ($capitalCAE > 0) { echo number_format($capitalCAE,'0',',','.'); } ?></td>
								<td class="aling-mid aling-center">
									<input type="hidden" id="fcVigRGF" name="fcVigRGF" value="<?= date_format2(date_format2($fcVigRGF,'DEC'),'EN','-') ?>" />
									<input type="hidden" id="primaRGF" name="primaRGF" value="<?= number_format($primaRGF,4,',','') ?>"/>
									<input type="checkbox" id="incRGF" name="incRGF" <?php if ($capitalRGF > 0) { echo 'checked'; } ?> class="aling-mid" disabled/>
								</td>
								<td class="aling-mid aling-center"><?php if ($capitalRGF > 0) { echo number_format($capitalRGF,'0',',','.'); } ?></td>
								<td class="aling-mid aling-center"><?= number_format($primaHijo,4,',','') ?></td>
								<td class="aling-mid aling-center" nowrap>
									<a href="#" title="Editar"><span class="icon icon-edit"></span></a>
								</td>
								<td class="aling-mid aling-center" nowrap>
									<a href="<?= base_url('index.php/home/deleteComponente').'/'.$row['NR_POLIZA'].'/'.$row['NR_COMPONENTE'] ?>" title="Eliminar"><span class="icon icon-remove"></span></a>
								</td>
							</tr>
					<?php }
					} ?>
					</tbody>
				</table>
				<div class="footer collapse-section">
					<div class="pull-right">
						<button type="button" id="nuevoHijo" class="button icon-button"><b>Nuevo</b><span class="icon icon-user"></span></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
	<div id="modalRecargo">
		<div class="col">
			<div class="cell panel">
				<div class="header">
					Recargos UF
					<div class="pull-right">
						<a id="cancelarComponente" title="Cancelar"><span class="icon icon-remove"></span></a>
					</div>
				</div>
				<div class="body">
					<div class="cell">
						<form id="formRecargo" action="#">
							<div class="col">
								<div class="col width-1of4">
									<div class="cell"></div>
								</div>
								<div class="col width-1of4">
									<div class="cell aling-center">Actividad</div>
								</div>
								<div class="col width-1of4">
									<div class="cell aling-center">Salud</div>
								</div>
								<div class="col width-1of4">
									<div class="cell aling-center">Deporte</div>
								</div>
							</div>

							<div class="col">
								<div class="col width-1of4">
									<div class="cell">
										<label style="cursor:default"><b>Vida</b></label>
									</div>
								</div>
								<div class="col width-1of4">
									<div class="cell">
										<input type="hidden" id="tipoComponente" name="tipoComponente"/>
										<input type="text" id="recFallAct" name="recFallAct" class="uf-dec-4"/>
									</div>
								</div>
								<div class="col width-1of4">
									<div class="cell">
										<input type="text" id="recFallSal" name="recFallSal" class="uf-dec-4"/>
									</div>
								</div>
								<div class="col width-1of4">
									<div class="cell">
										<input type="text" id="recFallDep" name="recFallDep" class="uf-dec-4"/>
									</div>
								</div>
							</div>

							<div class="col">
								<div class="col width-1of4">
									<div class="cell">
										<label style="cursor:default"><b>Accidente</b></label>
									</div>
								</div>
								<div class="col width-1of4">
									<div class="cell">
										<input type="text" id="recMaccAct" name="recMaccAct" class="uf-dec-4"/>
									</div>
								</div>
								<div class="col width-1of4">
									<div class="cell">
										<input type="text" id="recMaccSal" name="recMaccSal" class="uf-dec-4"/>
									</div>
								</div>
								<div class="col width-1of4">
									<div class="cell">
										<input type="text" id="recMaccDep" name="recMaccDep" class="uf-dec-4"/>
									</div>
								</div>
							</div>
							<div class="col">
								<div class="cell">
									<br>
									<div class="info text-blue"><span class="icon icon-info-sign"></span>Si no existen recargos, dejar los campos vac&iacute;os y <b>Confirmar</b></div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="footer collapse-section">
					<div class="pull-right">
                                            <button type="button" id="guardarComponente" class="button icon-button"><b>Confirmar</b><span class="icon icon-ok"></span></button>
						<button type="button" id="guardarComponente" class="button icon-button"><b>Confirmar</b><span class="icon icon-ok"></span></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	


<?php } ?>