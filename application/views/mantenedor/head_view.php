<!DOCTYPE html>
<html lang="es">
<head>
	<title><?= $titulo ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="UTF-8">
	<meta name="author" content="AP-EGM-15">

	<link rel="stylesheet" type="text/css" media="all" href="<?= base_url() ?>template/css/cascade/production/build-full.min.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?= base_url() ?>template/css/cascade/production/icons-ie7.min.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?= base_url() ?>template/css/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" media="all" href="<?= base_url() ?>template/css/style.css">

	<script type="text/javascript" src="<?= base_url() ?>template/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>template/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>template/js/jquery.mask.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>template/js/scripts.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>template/js/json2.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {

			var base_url = '<?= base_url() ?>';
			var capitalCAEmin = 10;
			var capitalCAEmax = 200;
			var capitalRGF = 40;
			var primaRGF = 0.021;

			setInterval(function() {
				$.ajax({
					type: 'POST',
					cache: false,
					async: true,
					url: base_url+'index.php/error/getterSesion/',
					success: function (data) {
						if ($.trim(data) != 0)
							location.href = base_url;
					}
				});
			}, 10000);

			if (navigator.userAgent.indexOf("MSIE")>0) {
				 $('#footer').hide();
			}

			activarMascara();
			$('#comision').hide();


			$('#formBuscar').on('submit', function(e) {
				e.preventDefault();
				$('#formBuscar #actComision').attr('checked',false);  // resetea checkbox x cada accion realizada
				var capitalCAEvig = 0; // resetea capital cae vigente

				if ($.trim($('#polizaAux').val()) == '' ){
					$('#comision').hide();
					$('#antecedentes').html('');
					$('#coberturas').html('');
					alert('Ingrese la p\u00F3liza que desea actualizar.');
					$('#polizaAux').focus();
					return false;
				}

				var poliza = $('#polizaAux').valNum();
				var url = base_url+'index.php/home/getterValidacion/'+poliza;
				$.ajax({
					type: 'POST',
					url: url,
					beforeSend: function() {
						$('body').css('cursor', 'wait');
					},
					success: function(data) {
						//console.log(data);
						if ($.trim(data) != 0) {
							$('#comision').hide();
							$('#antecedentes').html('');
							$('#coberturas').html('');
							switch($.trim(data)) {
								case '1':
									alert('La p\u00F3liza no existe.');
									break;
								case '2':
									alert('La p\u00F3liza no esta vigente.');
									break;
								case '3':
									alert('S\u00F3lo se pueden modificar p\u00F3lizas de los planes:\n  - Mutual Protecci\u00F3n Flexible\n  - Mutual Mujer Protegida\n  - Seguro Pap\u00E1');
									break;
								case '4':
									alert('La p\u00F3liza tiene una condici\u00F3n que a\u00FAn no entra en vigencia.');
									break;
								default:
									alert('Error: Ha ocurrido un problema al cargar la p\u00F3liza. Cont\u00E1ctese con el \u00C1rea de Sistemas de Informaci\u00F3n.');
							}
							$('#polizaAux').focus();
						} else {
							var tipo = 1;
							var url = base_url+'index.php/home/loadAntecedentes/'+tipo+'/'+poliza;
							$.ajax({
								type: 'POST',
								url: url,
								beforeSend: function() {
									$('body').css('cursor', 'wait');
								},
								success: function(data) {
									$('#antecedentes').html(data);
									$('html, body').animate({ scrollTop: 0 }, 0);
								},
								complete: function() {
									tipo = 2;
									url = base_url+'index.php/home/loadAntecedentes/'+tipo+'/'+poliza;
									$.ajax({
										type: 'POST',
										url: url,
										success: function(data) {
											//console.log(data);
											$('#comision').show('slow');
											$('#coberturas').html(data);
										},
										complete: function() {
											if ($('#formConyuge #primaConyuge').valNum() == 0)
												$('#antecedentesConyuge').css('display','none');

											$('input[name=incConyuge]').on('click', function(e) {
												var incConyuge = $('input[name=incConyuge]:checked').val();
												if (incConyuge == 'S') {
													$('#antecedentesConyuge').css('display','block');
													$('#formConyuge').reset();
													$('#formConyuge #rutConyuge').focus();
												} else {
													$('#antecedentesConyuge').css('display','none');
												}
											});

											$('#coberturasMayores table tr td a').on('click', function(e) {
												e.preventDefault();

												var url = $(this).attr('href');
												var tr = $(this).parent().parent().parent();
												var tdComponente = tr.find('th:eq(0)');
												var tdCobertura = tr.find('td:eq(0)');
												var componente = tdComponente.find('input').val();
												var cobertura = tdCobertura.html();

												if (cobertura.match(/^.*ONCO.*$/))
													var msg = capitalize(cobertura)+' del '+capitalize(componente);
												else
													var msg = 'de '+capitalize(cobertura)+' del '+capitalize(componente);

												if (confirm('\u00BFEst\u00E1s seguro de eliminarS la cobertura '+msg+'?')) {
													$.ajax({
														type: 'POST',
														url: url,
														beforeSend: function() {
															$('body').css('cursor', 'wait');
														},
														success: function(data) {
															//console.log(data);
															if ($.trim(data) != 0)
																alert('Error: Ha ocurrido un problema al eliminar la cobertura '+msg+'. Cont\u00E1ctese con el \u00C1rea de Sistemas de Informaci\u00F3n.');
														},
														complete: function() {
															$('body').css('cursor', 'default');
															$('#formBuscar #polizaAux').val($('#formPoliza #poliza').valNum());
															$('#formBuscar').submit();
														}
													});
												}
											});

											$('#coberturasMenores table tr td a').on('click', function(e) {
												e.preventDefault();

												var url = $(this).attr('href');
												var opt = $(this).attr('title');
												var tr = $(this).parent().parent();
												var id = tr.attr('id');
												var tdNombres = tr.find('td:eq(0)');
												var tdApPaterno = tr.find('td:eq(1)');
												var tdApMaterno = tr.find('td:eq(2)');
												var tdFcNacVig = tr.find('td:eq(3)');
												var tdEdadActuarial = tr.find('td:eq(4)');
												var tdIncCAE = tr.find('td:eq(5)');
												var tdCapitalCAE = tr.find('td:eq(6)');
												var tdIncRGF = tr.find('td:eq(7)');
												var tdCapitalRGF = tr.find('td:eq(8)');
												var tdPrima = tr.find('td:eq(9)');
												var tdOpt1 = tr.find('td:eq(10)');
												var tdOpt2 = tr.find('td:eq(11)');

												switch(opt) {
													case 'Editar':
														tdNombres.html(
															'<input type="text" id="nombreHijo" name="nombreHijo" value="'+tdNombres.html()+'" class="name-full"/>');

														tdApPaterno.html(
															'<input type="text" id="paternoHijo" name="paternoHijo" value="'+tdApPaterno.html()+'" class="name"/>');

														tdApMaterno.html(
															'<input type="text" id="maternoHijo" name="maternoHijo" value="'+tdApMaterno.html()+'" class="name"/>');

														tdFcNacVig.html(
															'<input type="text" id="fcNacHijo" name="fcNacHijo" value="'+tdFcNacVig.find('#fcNacHijo').val()+'" class="date-es"/>' +
															'<input type="hidden" id="fcVigHijo" name="fcVigHijo" value="'+tdFcNacVig.find('#fcVigHijo').val()+'" />');

														tdEdadActuarial.html(
															'<input type="text" id="actuarialHijo" name="actuarialHijo" value="'+tdEdadActuarial.html()+'" class="text-center" readonly/>');

														if (parseFloat(tdCapitalCAE.html()) > 0)
															checked = 'checked';
														else
															checked = '';

														tdIncCAE.html(
															'<input type="hidden" id="actuarialCAE" name="actuarialCAE" value="'+tdIncCAE.find('#actuarialCAE').val()+'" />'+
															'<input type="hidden" id="fcVigCAE" name="fcVigCAE" value="'+tdIncCAE.find('#fcVigCAE').val()+'" />'+
															'<input type="hidden" id="primaCAE" name="primaCAE" value="'+tdIncCAE.find('#primaCAE').val()+'" /> '+
															'<input type="hidden" id="recargoCAE" name="recargoCAE" value="'+tdIncCAE.find('#recargoCAE').val()+'" /> '+
															'<input type="checkbox" id="incCAE" name="incCAE" '+checked+' class="aling-mid"/>');

														 capitalCAEvig = parseFloat(tdCapitalCAE.html());

														if (parseFloat(tdCapitalCAE.html()) > 0)
															tdCapitalCAE.html(
																'<input type="text" id="capitalCAE" name="capitalCAE" value="'+tdCapitalCAE.html()+'" class="uf-dec-0 text-center"/>');
														else
															tdCapitalCAE.html(
																'<input type="text" id="capitalCAE" name="capitalCAE" value="" class="uf-dec-0 text-center" readonly/>');

														if (parseFloat(tdCapitalRGF.html()) > 0)
															checked = 'checked';
														else
															checked = '';

														tdIncRGF.html(
															'<input type="hidden" id="fcVigRGF" name="fcVigRGF" value="'+tdIncRGF.find('#fcVigRGF').val()+'" />'+
															'<input type="hidden" id="primaRGF" name="primaRGF" value="'+tdIncRGF.find('#primaRGF').val()+'" /> '+
															'<input type="checkbox" id="incRGF" name="incRGF" '+checked+' class="aling-mid"/>');

														tdCapitalRGF.html(
															'<input type="text" id="capitalRGF" name="capitalRGF" value="'+tdCapitalRGF.html()+'" class="uf-dec-0 text-center" readonly/>');

														tdPrima.html(
															'<input type="text" id="primaHijo" name="primaHijo" value="'+tdPrima.html()+'" class="uf-dec-5 text-center" readonly/> '+
															'<input type="hidden" id="primaHijoAnterior" name="primaHijoAnterior" value="'+tdPrima.html()+'" />');

														tdOpt1.find('a').html('<span class="icon icon-save"></span>');
														tdOpt2.find('a').html('<span class="icon icon-reply"></span>');
														tdOpt1.find('a').attr('title', 'Guardar Cambios');
														tdOpt2.find('a').attr('title', 'Cancelar Cambios');

														$('#nuevoHijo').parent().parent().hide();
														$('a[title="Editar"]').hide();
														$('a[title="Eliminar"]').hide();
														$('html, body').animate({ scrollTop: $(document).height() }, 0);

														tdFcNacVig.find('#fcNacHijo').on('change', function(e) {
															var fcNacimiento = date_format($(this).val(),'-');  // formato = ENG
															var fcVigencia = date_format(tdFcNacVig.find('#fcVigHijo').val(),'-');  // formato = ENG
															if (valDate(fcNacimiento)) {
																var actuarial = getEdadActuarial(fcNacimiento, fcVigencia);
																if (actuarial >= 0)
																	tdEdadActuarial.find('#actuarialHijo').val(actuarial);
																else
																	tdEdadActuarial.find('#actuarialHijo').val('');
															} else {
																tdEdadActuarial.find('#actuarialHijo').val('');
															}
														});

														tdIncCAE.find('#incCAE').on('click', function(e) {
													    	var primaHijo = 0;
													    	if ($(this).is(':checked')) {
														        tdCapitalCAE.find('#capitalCAE').attr('readonly', false);
														    } else {
														    	tdIncCAE.find('#primaCAE').val('');
														    	tdCapitalCAE.find('#capitalCAE').val('');
														    	tdCapitalCAE.find('#capitalCAE').attr('readonly', true);
														    	if (tdIncRGF.find('#incRGF').is(':checked')) {
																	primaHijo += primaRGF;
																}
																if (primaHijo > 0) {
																	tdPrima.find('#primaHijo').val(number_format(primaHijo,4,',',''));
																} else {
																	tdPrima.find('#primaHijo').val('');
																}
														    }
														});

														tdIncRGF.find('#incRGF').on('click', function(e) {
													    	var primaHijo = 0;
													    	var primaCAE = 0;
													        if ($(this).is(':checked')) {
													        	tdIncRGF.find('#primaRGF').val(number_format(primaRGF,4,',',''));
													        	tdCapitalRGF.find('#capitalRGF').val(capitalRGF);
													        	primaHijo += primaRGF;
														        if (tdIncCAE.find('#incCAE').is(':checked')) {
														        	primaCAE = tdIncCAE.find('#primaCAE').valNum();
																	primaHijo += primaCAE;
														        }
																tdPrima.find('#primaHijo').val(number_format(primaHijo,4,',',''));
														    } else {
														    	tdIncRGF.find('#incRGF').val('');
														    	tdIncRGF.find('#primaRGF').val('');
														    	tdCapitalRGF.find('#capitalRGF').val('');
														        if (tdIncCAE.find('#incCAE').is(':checked')) {
														        	primaCAE = tdIncCAE.find('#primaCAE').valNum();
																	primaHijo += primaCAE;
																}
																if (primaHijo > 0)
																	tdPrima.find('#primaHijo').val(number_format(primaHijo,4,',',''));
																else
																	tdPrima.find('#primaHijo').val('');
														    }
													    });

														tdCapitalCAE.find('#capitalCAE').on('change', function(e) {
															var primaHijo = 0;
															var primaCAE = 0;
															var recargoCAE = 0;
															var capitalCAE = ($.trim($(this).val())!='') ? $(this).valNum() : 0;
															var plan = ($.trim($('#formPoliza #plan').val())!='') ? $('#formPoliza #plan').valNum() : 0;
															var tipoComponente = 'H';
															var actuarialAsegurado = ($.trim(tdIncCAE.find('#actuarialCAE').val())!='') ? tdIncCAE.find('#actuarialCAE').valNum() : $('#formPoliza #actuarialAsegurado').valNum();
															//console.log(actuarialAsegurado);
															if (capitalCAE > 0 && actuarialAsegurado >= 0) {
																primaCAE = getPrima(plan, tipoComponente, 'E', actuarialAsegurado, capitalCAE);
																recargoCAE = tdIncCAE.find('#recargoCAE').valNum();
																primaHijo += primaCAE + recargoCAE;
																if (tdIncRGF.find('#incRGF').is(':checked')) {
																	primaHijo += primaRGF;
																}
																tdIncCAE.find('#primaCAE').val(number_format(primaCAE+recargoCAE,4,',',''));
																tdPrima.find('#primaHijo').val(number_format(primaHijo,4,',',''));
															}
														});

														break;

													case 'Guardar Cambios':
														var nombre = $.trim(tdNombres.find('#nombreHijo').val());
														var paterno = $.trim(tdApPaterno.find('#paternoHijo').val());
														var materno = $.trim(tdApMaterno.find('#maternoHijo').val());
														var fcNacimiento = date_format(tdFcNacVig.find('#fcNacHijo').val(),'-');  // formato = ENG
														var fcVigencia = date_format(tdFcNacVig.find('#fcVigHijo').val(),'-');  // formato = ENG
														var fcVigNueva = date_format($('#formPoliza #fcVigComponente').val(),'-');  // formato = ENG
														var fcVigCAE = date_format(tdIncCAE.find('#fcVigCAE').val(),'-');  // formato = ENG
														var fcVigRGF = date_format(tdIncRGF.find('#fcVigRGF').val(),'-');  // formato = ENG
														var capitalCAE = ($.trim(tdCapitalCAE.find('#capitalCAE').val())!='') ? tdCapitalCAE.find('#capitalCAE').valNum() : 0;

														if (nombre != '') {
															if (valStrLen(nombre, 3, 30)) {
																if (!valStrNaN(nombre))  {
																	alert('El nombre no debe contener d\u00EDgitos.');
																	tdNombres.find('#nombreHijo').focus();
																	return false;
																}
															} else {
																alert('El nombre debe contener entre 3 y 30 caracteres.');
																tdNombres.find('#nombreHijo').focus();
																return false;
															}
														} else {
															alert('Ingrese nombres.');
															tdNombres.find('#nombreHijo').focus();
															return false;
														}

														if (paterno != '') {
															if (valStrLen(paterno, 3, 15)) {
																if (!valStrNaN(paterno))  {
																	alert('El apellido paterno no debe contener d\u00EDgitos.');
																	tdApPaterno.find('#paternoHijo').focus();
																	return false;
																}
															} else {
																alert('El apellido paterno debe contener entre 3 y 15 caracteres.');
																tdApPaterno.find('#paternoHijo').focus();
																return false;
															}
														} else {
															alert('Ingrese apellido paterno.');
															tdApPaterno.find('#paternoHijo').focus();
															return false;
														}

														if (materno != '') {
															if (valStrLen(materno, 3, 15)) {
																if (!valStrNaN(materno))  {
																	alert('El apellido materno no debe contener d\u00EDgitos.');
																	tdApMaterno.find('#maternoHijo').focus();
																	return false;
																}
															} else {
																alert('El apellido materno debe contener entre 3 y 15 caracteres.');
																tdApMaterno.find('#maternoHijo').focus();
																return false;
															}
														} else {
															alert('Ingrese apellido materno.');
															tdApMaterno.find('#maternoHijo').focus();
															return false;
														}

														if (fcNacimiento != '') {
															if (!valDate(fcNacimiento)) {
																alert('La fecha de nacimiento no es v\u00E1lida.');
																tdFcNacVig.find('#fcNacHijo').focus();
																return false;
															}
														} else {
															alert('Ingrese fecha de nacimiento.');
															tdFcNacVig.find('#fcNacHijo').focus();
															return false;
														}

														if (tdIncCAE.find('#incCAE').is(':checked') || tdIncRGF.find('#incRGF').is(':checked')) {
															if (tdIncCAE.find('#incCAE').is(':checked')) {

																if (capitalCAE > 0) {

																	if (fcVigCAE == 0) {

																		var dateVig = new Date(fcVigNueva.split('-').join('/'));
																		var dateNac = new Date(fcNacimiento.split('-').join('/'));
																		var date24a = new Date(dateNac.getFullYear()+24,dateNac.getMonth(),dateNac.getDate());

																		if (date24a.getFullYear() == dateVig.getFullYear()) {
																			if (date24a > dateVig) {
																				alert('No se puede incorporar la cobertura CAE del componente, ya que tendr\u00E1 24 a\u00F1os cronol\u00F3gicos el mismo a\u00F1o en que entra en vigencia la cobertura ('+dateVig.getFullYear()+').');
																			} else {
																				alert('No se puede incorporar la cobertura CAE del componente, ya que al '+date_format(fcVigNueva,'-')+' tendr\u00E1 24 a\u00F1os cronol\u00F3gicos.');
																			}
																			tdFcNacVig.find('#fcNacHijo').focus();
																			return false;
																		}

																		if (!valRangeDate(fcNacimiento, fcVigNueva, -23.9167, -0.0833)) {
																			alert('El componente debe tener entre 30 d\u00EDas de nacido y 23 a\u00F1os con 11 meses cronol\u00F3gicos al '+date_format(fcVigNueva,'-')+'.');  // formato = ESP
																			tdFcNacVig.find('#fcNacHijo').focus();
																			return false;
																		}

																		if (capitalCAE < capitalCAEmin || capitalCAE > capitalCAEmax) {
																			alert('La renta anual para la CAE debe ser entre '+capitalCAEmin+' UF y '+capitalCAEmax+' UF.');
																			tdFcNacVig.find('#fcNacHijo').focus();
																			return false;
																		}

																	} else {

																		if (capitalCAE != capitalCAEvig && capitalCAEvig == capitalCAEmin) {
																			alert('La renta anual para la CAE no puede ser menor ni mayor a '+capitalCAEmin+' UF.');
																			tdCapitalCAE.find('#capitalCAE').focus();
																			return false;
																		}

																		if (capitalCAE < capitalCAEmin || capitalCAE > capitalCAEvig) {
																			alert('La renta anual para la CAE debe ser entre '+capitalCAEmin+' UF y '+capitalCAEvig+' UF.');
																			tdCapitalCAE.find('#capitalCAE').focus();
																			return false;
																		}
																	}

																} else {
																	alert('Ingrese renta anual para la CAE.');
																	tdCapitalCAE.find('#capitalCAE').focus();
																	return false;
																}

															}

															if (tdIncRGF.find('#incRGF').is(':checked')) {
																if (fcVigRGF == 0) {
																	if (!valRangeDate(fcNacimiento, fcVigNueva, -23.9167, -0.0833)) {
																		alert('El componente debe tener entre 30 d\u00EDas de nacido y 23 a\u00F1os con 11 meses cronol\u00F3gicos al '+date_format(fcVigNueva,'-')+'.');  // formato = ESP
																		tdFcNacVig.find('#fcNacHijo').focus();
																		return false;
																	}
																}
															}

														} else {
															alert('Debe incorporar al menos una cobertura.');
															return false;
														}


														$('#formRecargo #tipoComponente').val(id);

														if (tdIncCAE.find('#incCAE').is(':checked')) {
															var poliza = $('#formPoliza #poliza').valNum();
															var componente = id.slice(8);
															var recFallAct = getRecargo(poliza, componente, 'M', 2);
															var recFallSal = getRecargo(poliza, componente, 'M', 3);
															var recFallDep = getRecargo(poliza, componente, 'M', 1);
															var recMaccAct = getRecargo(poliza, componente, 'A', 2);
															var recMaccSal = getRecargo(poliza, componente, 'A', 3);
															var recMaccDep = getRecargo(poliza, componente, 'A', 1);
															$('#formRecargo #recFallAct').val((recFallAct>0) ? number_format(recFallAct,4,',','') : '');
															$('#formRecargo #recFallSal').val((recFallSal>0) ? number_format(recFallSal,4,',','') : '');
															$('#formRecargo #recFallDep').val((recFallDep>0) ? number_format(recFallDep,4,',','') : '');
															$('#formRecargo #recMaccAct').val((recMaccAct>0) ? number_format(recMaccAct,4,',','') : '');
															$('#formRecargo #recMaccSal').val((recMaccSal>0) ? number_format(recMaccSal,4,',','') : '');
															$('#formRecargo #recMaccDep').val((recMaccDep>0) ? number_format(recMaccDep,4,',','') : '');

															$('#modalRecargo').dialog('open');
														} else {
															$('#guardarComponente').click();
														}

														break;

													case 'Cancelar Cambios':
														$('#formBuscar #polizaAux').val($('#formPoliza #poliza').valNum());
														$('#formBuscar').submit();
														break;

													case 'Eliminar':
														var nombre = tdNombres.html();
														var paterno = tdApPaterno.html();
														var materno = tdApMaterno.html();

														if (confirm('\u00BFEst\u00E1s seguro de eliminar las coberturas de '+capitalize(nombre)+' '+capitalize(paterno)+' '+capitalize(materno)+'?')) {
															$.ajax({
																type: 'POST',
																url: url,
																async: false,
																beforeSend: function() {
																	$('body').css('cursor', 'wait');
																},
																success: function(data) {
																	//console.log(data);
																	if ($.trim(data) != 0)
																		alert('Error: Ha ocurrido un problema al eliminar las coberturas de '+capitalize(nombre)+' '+capitalize(paterno)+' '+capitalize(materno)+'. Cont\u00E1ctese con el \u00C1rea de Sistemas de Informaci\u00F3n.');
																},
																complete: function() {
																	$('body').css('cursor', 'default');
																}
															});
															$('#formBuscar #polizaAux').val($('#formPoliza #poliza').valNum());
															$('#formBuscar').submit();
														}
														break;

													default:
														alert('Acci\u00F3n no v\u00E1lida');
														break;
												}

												activarMascara();
											});

											$('#nuevoHijo').on('click', function(e) {
												tr  = '<tr id="formHijo">';
												tr += '<th class="aling-mid aling-center"> </th>';
												tr += '<td class="aling-mid"> <input type="text" id="nombreHijo" name="nombreHijo" class="name-full"/> </td>';
												tr += '<td class="aling-mid"> <input type="text" id="paternoHijo" name="paternoHijo" class="name"/> </td>';
												tr += '<td class="aling-mid"> <input type="text" id="maternoHijo" name="maternoHijo" class="name"/> </td>';
												tr += '<td class="aling-mid"> <input type="text" id="fcNacHijo" name="fcNacHijo" class="date-es"/> </td>';
												tr += '<td class="aling-mid"> <input type="text" id="actuarialHijo" name="actuarialHijo" class="text-center" readonly/> </td>';
												tr += '<td class="aling-mid aling-center"><input type="checkbox" id="incCAE" name="incCAE" class="aling-mid"/> </td>';
												tr += '<td class="aling-mid"> <input type="text" id="capitalCAE" name="capitalCAE" class="uf-dec-0 text-center" readonly/> <input type="hidden" id="primaCAE" name="primaCAE" readonly/> </td>';
												tr += '<td class="aling-mid aling-center"> <input type="checkbox" id="incRGF" name="incRGF" class="aling-mid"/> </td>';
												tr += '<td class="aling-mid"> <input type="text" id="capitalRGF" name="capitalRGF" class="uf-dec-0 text-center" readonly/> <input type="hidden" id="primaRGF" name="primaRGF" readonly/> </td>';
												tr += '<td class="aling-mid"> <input type="text" id="primaHijo" name="primaHijo" class="uf-dec-5 text-center" readonly/> </td>';
												tr += '<td class="aling-mid aling-center" nowrap> <a id="guardarHijo" title="Guardar"><span class="icon icon-save"></span></a> </td>';
												tr += '<td class="aling-mid aling-center" nowrap> <a id="cancelarHijo" title="Cancelar"><span class="icon icon-reply"></span></a> </td>';
												tr += '<tr>';
												$('#detCoberturasMenores').append(tr);
												$('#formHijo #nombreHijo').focus();

												$('#nuevoHijo').parent().parent().hide();
												$('a[title="Editar"]').hide();
												$('a[title="Eliminar"]').hide();
												$('html, body').animate({ scrollTop: $(document).height() }, 0);

												$('#formHijo #fcNacHijo').on('change', function(e) {
													var fcNacimiento = date_format($(this).val(),'-');  // formato = ENG
													var fcVigencia = date_format($('#formPoliza #fcVigComponente').val(),'-');  // formato = ENG
													if (valDate(fcNacimiento)) {
														var actuarial = getEdadActuarial(fcNacimiento, fcVigencia);
														if (actuarial >= 0)
															$('#formHijo #actuarialHijo').val(actuarial);
														else
															$('#formHijo #actuarialHijo').val('');
													} else {
														$('#formHijo #actuarialHijo').val('');
													}
												});

												$('#formHijo #incCAE').on('click', function(e) {
													var primaHijo = 0;
											        if ($(this).is(':checked')) {
												        $('#formHijo #capitalCAE').attr('readonly', false);
												    } else {
														$('#formHijo #capitalCAE').attr('readonly', true);
														$('#formHijo #capitalCAE').val('');
														$('#formHijo #primaCAE').val('');
														if ($('#formHijo #incRGF').is(':checked')) {
															primaHijo += primaRGF;
														}
														if (primaHijo > 0) {
															$('#formHijo #primaHijo').val(number_format(primaHijo,4,',',''));
														} else {
															$('#formHijo #primaHijo').val('');
														}
												    }
											    });

											    $('#formHijo #incRGF').on('click', function(e) {
											    	var primaHijo = 0;
											    	var primaCAE = 0;
											        if ($(this).is(':checked')) {
											        	$('#formHijo #primaRGF').val(number_format(primaRGF,4,',',''));
											        	$('#formHijo #capitalRGF').val(capitalRGF);
											        	primaHijo += primaRGF;
												        if ($('#formHijo #incCAE').is(':checked')) {
												        	primaCAE = $('#formHijo #primaCAE').valNum();
															primaHijo += primaCAE;
												        }
														$('#formHijo #primaHijo').val(number_format(primaHijo,4,',',''));
												    } else {
												    	$('#formHijo #primaRGF').val('');
												    	$('#formHijo #capitalRGF').val('');
												        if ($('#formHijo #incCAE').is(':checked')) {
												        	primaCAE = $('#formHijo #primaCAE').valNum();
															primaHijo += primaCAE;
														}
														if (primaHijo > 0)
															$('#formHijo #primaHijo').val(number_format(primaHijo,4,',',''));
														else
															$('#formHijo #primaHijo').val('');
												    }
											    });

												$('#formHijo #capitalCAE').on('change', function(e) {
													var primaHijo = 0;
													var primaCAE = 0;
													var capital = ($.trim($(this).val())!='') ? $(this).valNum() : 0;
													var plan = ($.trim($('#formPoliza #plan').val())!='') ? $('#formPoliza #plan').valNum() : 0;
													var tipoComponente = 'H';
													var actuarial = ($.trim($('#formPoliza #actuarialAsegurado').val())!='') ? $('#formPoliza #actuarialAsegurado').valNum() : 0;
													if (capital > 0 && actuarial >= 0) {
														primaCAE = getPrima(plan, tipoComponente, 'E', actuarial, capital);
														primaHijo += primaCAE;
														if ($('#formHijo #incRGF').is(':checked')) {
															primaHijo += primaRGF;
														}
														$('#formHijo #primaCAE').val(number_format(primaCAE,4,',',''));
														$('#formHijo #primaHijo').val(number_format(primaHijo,4,',',''));
													}
												});

											    $('#guardarHijo').on('click', function(e) {
													var nombre = $.trim($('#formHijo #nombreHijo').val());
													var paterno = $.trim($('#formHijo #paternoHijo').val());
													var materno = $.trim($('#formHijo #maternoHijo').val());
													var fcNacimiento = date_format($('#formHijo #fcNacHijo').val(),'-');  // formato = ENG
													var fcVigencia = date_format($('#formPoliza #fcVigComponente').val(),'-');  // formato = ENG
													var capitalCAE = ($.trim($('#formHijo #capitalCAE').val())!='') ? $('#formHijo #capitalCAE').valNum() : 0;

													if (nombre != '') {
														if (valStrLen(nombre, 3, 30)) {
															if (!valStrNaN(nombre))  {
																alert('El nombre no debe contener d\u00EDgitos.');
																$('#formHijo #nombreHijo').focus();
																return false;
															}
														} else {
															alert('El nombre debe contener entre 3 y 30 caracteres.');
															$('#formHijo #nombreHijo').focus();
															return false;
														}
													} else {
														alert('Ingrese nombres.');
														$('#formHijo #nombreHijo').focus();
														return false;
													}

													if (paterno != '') {
														if (valStrLen(paterno, 3, 15)) {
															if (!valStrNaN(paterno))  {
																alert('El apellido paterno no debe contener d\u00EDgitos.');
																$('#formHijo #paternoHijo').focus();
																return false;
															}
														} else {
															alert('El apellido paterno debe contener entre 3 y 15 caracteres.');
															$('#formHijo #paternoHijo').focus();
															return false;
														}
													} else {
														alert('Ingrese apellido paterno.');
														$('#formHijo #paternoHijo').focus();
														return false;
													}

													if (materno != '') {
														if (valStrLen(materno, 3, 15)) {
															if (!valStrNaN(materno))  {
																alert('El apellido materno no debe contener d\u00EDgitos.');
																$('#formHijo #maternoHijo').focus();
																return false;
															}
														} else {
															alert('El apellido materno debe contener entre 3 y 15 caracteres.');
															$('#formHijo #maternoHijo').focus();
															return false;
														}
													} else {
														alert('Ingrese apellido materno.');
														$('#formHijo #maternoHijo').focus();
														return false;
													}

													if (fcNacimiento != '') {
														if (!valDate(fcNacimiento)) {
															alert('La fecha de nacimiento no es v\u00E1lida.');
															$('#formHijo #fcNacHijo').focus();
															return false;
														}
													} else {
														alert('Ingrese fecha de nacimiento.');
														$('#formHijo #fcNacHijo').focus();
														return false;
													}

													if ($('#formHijo #incCAE').is(':checked') || $('#formHijo #incRGF').is(':checked')) {

														if ($('#formHijo #incCAE').is(':checked')) {

															var dateVig = new Date(fcVigencia.split('-').join('/'));
															var dateNac = new Date(fcNacimiento.split('-').join('/'));
															var date24a = new Date(dateNac.getFullYear()+24,dateNac.getMonth(),dateNac.getDate());

															if (date24a.getFullYear() == dateVig.getFullYear()) {
																if (date24a > dateVig) {
																	alert('No se puede incorporar la cobertura CAE del componente, ya que tendr\u00E1 24 a\u00F1os cronol\u00F3gicos el mismo a\u00F1o en que entra en vigencia la cobertura ('+dateVig.getFullYear()+').');
																} else {
																	alert('No se puede incorporar la cobertura CAE del componente, ya que al '+date_format(fcVigencia,'-')+' tendr\u00E1 24 a\u00F1os cronol\u00F3gicos.');
																}
																$('#formHijo #fcNacHijo').focus();
																return false;
															}

															if (!valRangeDate(fcNacimiento, fcVigencia, -23.9167, -0.0833)) {
																alert('El componente debe tener entre 30 d\u00EDas de nacido y 23 a\u00F1os con 11 meses cronol\u00F3gicos al '+date_format(fcVigencia,'-')+'.');  // formato = ESP
																$('#formHijo #fcNacHijo').focus();
																return false;
															}

															if (capitalCAE > 0) {
																if (capitalCAE < capitalCAEmin || capitalCAE > capitalCAEmax) {
																	alert('La renta anual para la CAE debe ser entre '+capitalCAEmin+' UF y '+capitalCAEmax+' UF.');
																	$('#formHijo #capitalCAE').focus();
																	return false;
																}
															} else {
																alert('Ingrese renta anual para la CAE.');
																$('#formHijo #capitalCAE').focus();
																return false;
															}

														}

														if ($('#formHijo #incRGF').is(':checked')) {
															if (!valRangeDate(fcNacimiento, fcVigencia, -23.9167, -0.0833)) {
																alert('El componente debe tener entre 30 d\u00EDas de nacido y 23 a\u00F1os con 11 meses cronol\u00F3gicos al '+date_format(fcVigencia,'-')+'.');  // formato = ESP
																$('#formHijo #fcNacHijo').focus();
																return false;
															}
														}

													} else {
														alert('Debe incorporar al menos una cobertura.');
														return false;
													}

													$('#formRecargo #tipoComponente').val('H');

													if ($('#formHijo #incCAE').is(':checked')) {
														$('#modalRecargo').dialog('open');
													} else {
														$('#guardarComponente').click();
													}

												});

												$('#cancelarHijo').on('click', function(e) {
													var tr = $(this).parent().parent();
													tr.remove();
													$('#nuevoHijo').parent().parent().show();
													$('a[title="Editar"]').show();
													$('a[title="Eliminar"]').show();
												});

											    activarMascara();
											});

											$('#formConyuge #fcNacConyuge').on('change', function(e) {
												var fcNacimiento = date_format($(this).val(),'-');  // formato = ENG
												var fcVigencia = date_format($('#formPoliza #fcVigComponente').val(),'-');  // formato = ENG
												if (valDate(fcNacimiento)) {
													var actuarial = getEdadActuarial(fcNacimiento, fcVigencia);
													if (actuarial >= 0) {
														$('#formConyuge #actuarialConyuge').val(actuarial);
														if (valRangeDate(fcNacimiento, fcVigencia, -64.9973, -18)) {  // 1 dia = 1/365 = 0.0027  1 mes = 1/12 = 0.0833  1 año = 1
															$('#formConyuge #capitalConyuge').attr('readonly', false);
															var capital = ($.trim($('#formConyuge #capitalConyuge').val())!='') ? $('#formConyuge #capitalConyuge').valNum() : 0;
															if (capital > 0) {
																var plan = ($.trim($('#formPoliza #plan').val())!='') ? $('#formPoliza #plan').valNum() : 0;
																var tipoComponente = 'C';
																var primaFall = getPrima(plan, tipoComponente, 'M', actuarial, capital);
																var primaMacc = getPrima(plan, tipoComponente, 'A', actuarial, capital);
																var primaTotal = primaFall + primaMacc;
																$('#formConyuge #primaFall').val(number_format(primaFall,4,',',''));
																$('#formConyuge #primaMacc').val(number_format(primaMacc,4,',',''));
																$('#formConyuge #primaConyuge').val(number_format(primaTotal,4,',',''));
															}
														} else {
															$('#formConyuge #capitalConyuge').val('');
															$('#formConyuge #primaConyuge').val('');
															$('#formConyuge #capitalConyuge').attr('readonly', true);
														}
													} else {
														$('#formConyuge #actuarialConyuge').val('');
														$('#formConyuge #capitalConyuge').val('');
														$('#formConyuge #primaConyuge').val('');
														$('#formConyuge #capitalConyuge').attr('readonly', true);
													}
												} else {
													$('#formConyuge #actuarialConyuge').val('');
													$('#formConyuge #capitalConyuge').val('');
													$('#formConyuge #primaConyuge').val('');
													$('#formConyuge #capitalConyuge').attr('readonly', true);
												}
											});

											$('#formConyuge #capitalConyuge').on('change', function(e) {
												var capital = ($.trim($(this).val())!='') ? $(this).valNum() : 0;
												var actuarial = ($.trim($('#formConyuge #actuarialConyuge').val())!='') ? $('#formConyuge #actuarialConyuge').valNum() : 0;
												if (capital > 0 && actuarial >= 18) {
													var plan = ($.trim($('#formPoliza #plan').val())!='') ? $('#formPoliza #plan').valNum() : 0;
													var tipoComponente = 'C';
													var primaFall = getPrima(plan, tipoComponente, 'M', actuarial, capital);
													var primaMacc = getPrima(plan, tipoComponente, 'A', actuarial, capital);
													var primaTotal = primaFall + primaMacc;
													$('#formConyuge #primaFall').val(number_format(primaFall,4,',',''));
													$('#formConyuge #primaMacc').val(number_format(primaMacc,4,',',''));
													$('#formConyuge #primaConyuge').val(number_format(primaTotal,4,',',''));
												}
											});

											$('#guardarConyuge').on('click', function(e) {
												var rut = $('#formConyuge #rutConyuge').valNum();
												var dv = $.trim($('#formConyuge #dvConyuge').val());
												var nombre = $.trim($('#formConyuge #nombreConyuge').val());
												var paterno = $.trim($('#formConyuge #paternoConyuge').val());
												var materno = $.trim($('#formConyuge #maternoConyuge').val());
												var fcNacimiento = ($.trim($('#formConyuge #fcNacConyuge').val())!='') ? date_format($('#formConyuge #fcNacConyuge').val(),'-') : '';  // formato = ENG
												var fcVigencia = date_format($('#formPoliza #fcVigComponente').val(),'-');  // formato = ENG
												var actuarial = ($.trim($('#formConyuge #actuarialConyuge').val())!='') ? $('#formConyuge #actuarialConyuge').valNum() : 0;
												var sexo = ($('input:radio[name=sexoConyuge]:checked').val()!=undefined) ? $('input:radio[name=sexoConyuge]:checked').val() : '';
												var nacionalidad = $.trim($('#formConyuge #nacionalidadConyuge').val());
												var capital = ($.trim($('#formConyuge #capitalConyuge').val())!='') ? $('#formConyuge #capitalConyuge').valNum() : 0;
												var prima = ($.trim($('#formConyuge #primaConyuge').val())!='') ? $('#formConyuge #primaConyuge').valNum() : 0;
												var capitalPoliza = ($.trim($('#formPoliza #capitalPoliza').val())!='') ? $('#formPoliza #capitalPoliza').valNum() : 0;

												if (rut != '') {
													if (dv != '') {
														if (!valRutDv(rut,dv)) {
															alert('El rut no es v\u00E1lido.');
															$('#formConyuge #rutConyuge').focus();
															return false;
														}
													} else {
														alert('Ingrese d\u00EDgito verificador.');
														$('#formConyuge #dvConyuge').focus();
														return false;
													}
												} else {
													alert('Ingrese rut.');
													$('#formConyuge #rutConyuge').focus();
													return false;
												}

												if (nombre != '') {
													if (valStrLen(nombre, 3, 30)) {
														if (!valStrNaN(nombre))  {
															alert('El nombre no debe contener d\u00EDgitos.');
															$('#formConyuge #nombreConyuge').focus();
															return false;
														}
													} else {
														alert('El nombre debe contener entre 3 y 30 caracteres.');
														$('#formConyuge #nombreConyuge').focus();
														return false;
													}
												} else {
													alert('Ingrese nombres.');
													$('#formConyuge #nombreConyuge').focus();
													return false;
												}

												if (paterno != '') {
													if (valStrLen(paterno, 3, 15)) {
														if (!valStrNaN(paterno))  {
															alert('El apellido paterno no debe contener d\u00EDgitos.');
															$('#formConyuge #paternoConyuge').focus();
															return false;
														}
													} else {
														alert('El apellido paterno debe contener entre 3 y 15 caracteres.');
														$('#formConyuge #paternoConyuge').focus();
														return false;
													}
												} else {
													alert('Ingrese apellido paterno.');
													$('#formConyuge #paternoConyuge').focus();
													return false;
												}

												if (materno != '') {
													if (valStrLen(materno, 3, 15)) {
														if (!valStrNaN(materno))  {
															alert('El apellido materno no debe contener d\u00EDgitos.');
															$('#formConyuge #maternoConyuge').focus();
															return false;
														}
													} else {
														alert('El apellido materno debe contener entre 3 y 15 caracteres.');
														$('#formConyuge #maternoConyuge').focus();
														return false;
													}
												} else {
													alert('Ingrese apellido materno.');
													$('#formConyuge #maternoConyuge').focus();
													return false;
												}

												if (fcNacimiento != '') {
													if (valDate(fcNacimiento)) {
														if (!valRangeDate(fcNacimiento, fcVigencia, -64.9973, -18)) {  // 1 dia = 1/365 = 0.0027  1 mes = 1/12 = 0.0833  1 año = 1
															alert('El componente debe tener entre 18 a\u00F1os y 64 a\u00F1os con 365 d\u00CDas cronol\u00F3gicos al '+date_format(fcVigencia,'-')+'.');  // formato = ESP
															$('#formConyuge #fcNacConyuge').focus();
															return false;
														}
													} else {
														alert('La fecha de nacimiento no es v\u00E1lida.');
														$('#formConyuge #fcNacConyuge').focus();
														return false;
													}
												} else {
													alert('Ingrese fecha de nacimiento.');
													$('#formConyuge #fcNacConyuge').focus();
													return false;
												}

												if (sexo == '') {
													alert('Seleccione sexo.');
													$('#formConyuge #sexoConyugeF').focus();
													return false;
												}

												if (nacionalidad != '') {
													if (valStrLen(nacionalidad, 3, 20)) {
														if (!valStrNaN(nacionalidad))  {
															alert('La nacionalidad no debe contener d\u00EDgitos.');
															$('#formConyuge #nacionalidadConyuge').focus();
															return false;
														}
													} else {
														alert('La nacionalidad debe contener entre 3 y 20 caracteres.');
														$('#formConyuge #nacionalidadConyuge').focus();
														return false;
													}
												} else {
													alert('Ingrese nacionalidad.');
													$('#formConyuge #nacionalidadConyuge').focus();
													return false;
												}

												if (capital > 0) {
													if (capital < capitalPoliza/2 || capital > capitalPoliza) {
														alert('El capital asegurado debe ser entre '+capitalPoliza/2+' UF y '+capitalPoliza+' UF.');
														$('#formConyuge #capitalConyuge').focus();
														return false;
													}
												} else {
													alert('Ingrese capital asegurado.');
													$('#formConyuge #capitalConyuge').focus();
													return false;
												}

												$('#formRecargo #tipoComponente').val('C');
												$('#modalRecargo').dialog('open');
											});

											$('#guardarComponente').on('click', function(e) {
												var recFallAct = ($.trim($('#formRecargo #recFallAct').val())!='') ? $('#formRecargo #recFallAct').valNum() : 0;
												var recFallSal = ($.trim($('#formRecargo #recFallSal').val())!='') ? $('#formRecargo #recFallSal').valNum() : 0;
												var recFallDep = ($.trim($('#formRecargo #recFallDep').val())!='') ? $('#formRecargo #recFallDep').valNum() : 0;
												var recMaccAct = ($.trim($('#formRecargo #recMaccAct').val())!='') ? $('#formRecargo #recMaccAct').valNum() : 0;
												var recMaccSal = ($.trim($('#formRecargo #recMaccSal').val())!='') ? $('#formRecargo #recMaccSal').valNum() : 0;
												var recMaccDep = ($.trim($('#formRecargo #recMaccDep').val())!='') ? $('#formRecargo #recMaccDep').valNum() : 0;
												var totalRecargos = recFallAct+recFallSal+recFallDep+recMaccAct+recMaccSal+recMaccDep;

												var tipoComponente = $.trim($('#formRecargo #tipoComponente').val());
												var poliza = $('#formPoliza #poliza').valNum();
												var primaPoliza = $('#formPoliza #primaPoliza').valNum();
												var accion = 'incorporar';

												if (tipoComponente == 'C') {
													var primaConyuge = ($.trim($('#formConyuge #primaConyuge').val())!='') ? $('#formConyuge #primaConyuge').valNum() : 0;
													if (totalRecargos >= primaPoliza+primaConyuge) {
														alert('El total de recargos debe ser inferior a la nueva prima mensual informada de '+number_format(primaPoliza+primaConyuge,4,',','')+' UF.');
														return false;
													}
													var rut = $('#formConyuge #rutConyuge').valNum();
													var nombre = $.trim($('#formConyuge #nombreConyuge').val());
													var paterno = $.trim($('#formConyuge #paternoConyuge').val());
													var materno = $.trim($('#formConyuge #maternoConyuge').val());
													var fcNacimiento = date_format($('#formConyuge #fcNacConyuge').val(),'-');  // formato = ENG
													var sexo = $('input:radio[name=sexoConyuge]:checked').val();
													var nacionalidad = $.trim($('#formConyuge #nacionalidadConyuge').val());
													var capitalFallCAE = $('#formConyuge #capitalConyuge').valNum();
													var capitalMaccRGF = capitalFallCAE;
													var primaFallCAE = $('#formConyuge #primaFall').valNum();
													var primaMaccRGF = $('#formConyuge #primaMacc').valNum();

													var url = base_url+'index.php/home/insertComponente';
													var data = 'poliza='+poliza+'&tipoComponente='+tipoComponente+'&rut='+rut+'&nombre='+nombre+'&paterno='+paterno+'&materno='+materno+'&fcNacimiento='+fcNacimiento+'&sexo='+sexo+'&nacionalidad='+nacionalidad+'&capitalFallCAE='+capitalFallCAE+'&capitalMaccRGF='+capitalMaccRGF+'&primaFallCAE='+primaFallCAE+'&primaMaccRGF='+primaMaccRGF+'&recFallAct='+recFallAct+'&recFallSal='+recFallSal+'&recFallDep='+recFallDep+'&recMaccAct='+recMaccAct+'&recMaccSal='+recMaccSal+'&recMaccDep='+recMaccDep;
													var msg = '\u00BFEst\u00E1s seguro de incorporar las coberturas de Fallecimiento y Muerte Accidental de '+capitalize(nombre)+' '+capitalize(paterno)+' '+capitalize(materno)+'?';

												} else if (tipoComponente == 'H') {
													var primaHijo = ($.trim($('#formHijo #primaHijo').val())!='') ? $('#formHijo #primaHijo').valNum() : 0;
													if (totalRecargos >= primaPoliza+primaHijo) {
														alert('El total de recargos debe ser inferior a la nueva prima mensual informada de '+number_format(primaPoliza+primaHijo,4,',','')+' UF.');
														return false;
													}
													var rut = 0;
													var nombre = $.trim($('#formHijo #nombreHijo').val());
													var paterno = $.trim($('#formHijo #paternoHijo').val());
													var materno = $.trim($('#formHijo #maternoHijo').val());
													var fcNacimiento = date_format($('#formHijo #fcNacHijo').val(),'-');  // formato = ENG
													var sexo = '';
													var nacionalidad = '';
													var capitalFallCAE = ($.trim($('#formHijo #capitalCAE').val())!='') ? $('#formHijo #capitalCAE').valNum() : 0;
													var capitalMaccRGF = ($.trim($('#formHijo #capitalRGF').val())!='') ? $('#formHijo #capitalRGF').valNum() : 0;
													var primaFallCAE = ($.trim($('#formHijo #primaCAE').val())!='') ? $('#formHijo #primaCAE').valNum() : 0;
													var primaMaccRGF = ($.trim($('#formHijo #primaRGF').val())!='') ? $('#formHijo #primaRGF').valNum() : 0;

													var url = base_url+'index.php/home/insertComponente';
													var data = 'poliza='+poliza+'&tipoComponente='+tipoComponente+'&rut='+rut+'&nombre='+nombre+'&paterno='+paterno+'&materno='+materno+'&fcNacimiento='+fcNacimiento+'&sexo='+sexo+'&nacionalidad='+nacionalidad+'&capitalFallCAE='+capitalFallCAE+'&capitalMaccRGF='+capitalMaccRGF+'&primaFallCAE='+primaFallCAE+'&primaMaccRGF='+primaMaccRGF+'&recFallAct='+recFallAct+'&recFallSal='+recFallSal+'&recFallDep='+recFallDep+'&recMaccAct='+recMaccAct+'&recMaccSal='+recMaccSal+'&recMaccDep='+recMaccDep;
													if (capitalFallCAE > 0 && capitalMaccRGF > 0)
														var msg = '\u00BFEst\u00E1s seguro de incorporar la Cl\u00E1usula de Ayuda Educacional y el Reembolso de Gastos Funerarios de '+capitalize(nombre)+' '+capitalize(paterno)+' '+capitalize(materno)+'?';
													else if (capitalFallCAE > 0)
														var msg = '\u00BFEst\u00E1s seguro de incorporar la Cl\u00E1usula de Ayuda Educacional de '+capitalize(nombre)+' '+capitalize(paterno)+' '+capitalize(materno)+'?';
													else
														var msg = '\u00BFEst\u00E1s seguro de incorporar el Reembolso de Gastos Funerarios de '+capitalize(nombre)+' '+capitalize(paterno)+' '+capitalize(materno)+'?';

												} else {
													var tr = $('#'+tipoComponente);
													var tdNombres = tr.find('td:eq(0)');
													var tdApPaterno = tr.find('td:eq(1)');
													var tdApMaterno = tr.find('td:eq(2)');
													var tdFcNacVig = tr.find('td:eq(3)');
													var tdEdadActuarial = tr.find('td:eq(4)');
													var tdIncCAE = tr.find('td:eq(5)');
													var tdCapitalCAE = tr.find('td:eq(6)');
													var tdIncRGF = tr.find('td:eq(7)');
													var tdCapitalRGF = tr.find('td:eq(8)');
													var tdPrima = tr.find('td:eq(9)');

													var primaHijoNueva = ($.trim(tdPrima.find('#primaHijo').val())!='') ? tdPrima.find('#primaHijo').valNum() : 0;
													var primaHijoAnterior = ($.trim(tdPrima.find('#primaHijoAnterior').val())!='') ? tdPrima.find('#primaHijoAnterior').valNum() : 0;
													var primaHijoAux = primaHijoNueva-primaHijoAnterior;
													if (totalRecargos >= primaPoliza+primaHijoAux) {
														alert('El total de recargos debe ser inferior a la nueva prima mensual informada de '+number_format(primaPoliza+primaHijoAux,4,',','')+' UF.');
														return false;
													}

													var rut = 0;
													var nombre = $.trim(tdNombres.find('#nombreHijo').val());
													var paterno = $.trim(tdApPaterno.find('#paternoHijo').val());
													var materno = $.trim(tdApMaterno.find('#maternoHijo').val());
													var fcNacimiento = date_format(tdFcNacVig.find('#fcNacHijo').val(),'-');  // formato = ENG
													var sexo = '';
													var nacionalidad = '';
													var capitalFallCAE = ($.trim(tdCapitalCAE.find('#capitalCAE').val())!='') ? tdCapitalCAE.find('#capitalCAE').valNum() : 0;
													var capitalMaccRGF = ($.trim(tdCapitalRGF.find('#capitalRGF').val())!='') ? tdCapitalRGF.find('#capitalRGF').valNum() : 0;
													var primaFallCAE = ($.trim(tdIncCAE.find('#primaCAE').val())!='') ? tdIncCAE.find('#primaCAE').valNum() - tdIncCAE.find('#recargoCAE').valNum() : 0;
													var primaMaccRGF = ($.trim(tdIncRGF.find('#primaRGF').val())!='') ? tdIncRGF.find('#primaRGF').valNum() : 0;

													var componente = tipoComponente.slice(8);

													var url = base_url+'index.php/home/updateComponente';
													var data = 'poliza='+poliza+'&componente='+componente+'&rut='+rut+'&nombre='+nombre+'&paterno='+paterno+'&materno='+materno+'&fcNacimiento='+fcNacimiento+'&sexo='+sexo+'&nacionalidad='+nacionalidad+'&capitalFallCAE='+capitalFallCAE+'&capitalMaccRGF='+capitalMaccRGF+'&primaFallCAE='+primaFallCAE+'&primaMaccRGF='+primaMaccRGF+'&recFallAct='+recFallAct+'&recFallSal='+recFallSal+'&recFallDep='+recFallDep+'&recMaccAct='+recMaccAct+'&recMaccSal='+recMaccSal+'&recMaccDep='+recMaccDep;
													var msg = '\u00BFEst\u00E1s seguro de modificar las coberturas de '+capitalize(nombre)+' '+capitalize(paterno)+' '+capitalize(materno)+'?';
													var accion = 'modificar';
												}

												var actComision = ($('#formBuscar #actComision').is(':checked')) ? 'S' : 'N';
												data += '&actComision='+actComision;
												//console.log(url+'?'+data);
												if (confirm(msg)) {
													$.ajax({
														type: 'POST',
														url: url,
														data: data,
														beforeSend: function() {
															$('body').css('cursor', 'wait');
														},
														success: function(data) {
															//console.log(data);
															if ($.trim(data) != 0)
																alert('Error: Ha ocurrido un problema al '+accion+' las coberturas de '+capitalize(nombre)+' '+capitalize(paterno)+' '+capitalize(materno)+'. Cont\u00E1ctese con el \u00C1rea de Sistemas de Informaci\u00F3n.');
														},
														complete: function() {
															$('body').css('cursor', 'default');
															$('#modalRecargo').dialog('close');
															$('#formBuscar #polizaAux').val(poliza);
															$('#formBuscar').submit();
														}
													});
												}
											});

											$('.uf-dec-4').keydown(function(e) {
												//Permite números y una sola coma
												var bYaHayComa = $(this).val().indexOf(',') < 0 ? false : true;
												// Permitir: backspace, delete, tab, escape, y enter
												if ( e.keyCode == 46 || e.keyCode == 8 || e.keyCode == 9 ||
													e.keyCode == 27 || e.keyCode == 13 || (e.keyCode == 188 && !bYaHayComa) ||//coma
													 // Permitir: Ctrl+A, Ctrl+C, Ctrl+V y Ctrl+X
													(e.keyCode == 65 && e.ctrlKey === true) ||
													(e.keyCode == 67 && e.ctrlKey === true) ||
													(e.keyCode == 86 && e.ctrlKey === true) ||
													(e.keyCode == 88 && e.ctrlKey === true) ||
													 // Permitir: inicio, fin, left, right
													(e.keyCode >= 35 && e.keyCode <= 39) ) {
														 // no hacer nada
														 return;
												}
												else {
													// Asegura que es un numero
													if (e.shiftKey ||
														(e.keyCode < 48 || e.keyCode > 57) &&
														(e.keyCode < 96 || e.keyCode > 105 ) ) {
														e.preventDefault();
													}
												}
											});


											activarMascara();

											$('#modalRecargo').dialog({
												autoOpen: false,
												draggable: false,
												resizable: false,
												height: 350,
												width: 400,
												modal: true,
												open: function(event, ui) {
                									$('#formBuscar').find('input, checkbox, button').attr('disabled',true);
                								},
												close: function(event, ui) {
                									$('#formBuscar').find('input, checkbox, button').attr('disabled',false);
                									$('#formRecargo').find('input').val('');
                								}
											});

											$('#cancelarComponente').on('click', function(e) {
												$('#modalRecargo').dialog('close');
											});

										}
									});

									$('body').css('cursor', 'default');
								}
							});
						}

					},
					complete: function() {
						$('body').css('cursor', 'default');
					}
				});
			});


			function getPrima(plan, tipoComponente, cobertura, actuarial, capital) {
				monto = 0;
				if (!isNaN(plan) && !isNaN(capital) && tipoComponente!='' && !isNaN(actuarial)) {
					url = base_url+'index.php/home/getterPrimas/'+plan+'/'+tipoComponente+'/'+actuarial+'/'+capital;
					//console.log(url);
					$.ajax({
						type: 'POST',
						url: url,
						async: false,
						beforeSend: function() {
							$('body').css('cursor', 'wait');
						},
						success: function(data) {
							//console.log(data);
							row = JSON.parse(data);
							i = 0;
							for(i in row) {
								if (tipoComponente == 'C') {
									if (cobertura == 'T') {  //total
										if (row[i].TP_TASA.match(/^.*TOTAL.*$/)) {
											monto += parseFloat(row[i].PRIMA_UF);
										}
									} else if (cobertura == 'M') {  //fallecimiento
										if (row[i].TP_TASA.match(/^.*FALL.*$/) || row[i].TP_TASA.match(/^.*FIJO.*$/)) {
											monto += parseFloat(row[i].PRIMA_UF);
										}
									} else if (cobertura == 'A') {  //muerte accidental
										if (row[i].TP_TASA.match(/^.*ACCI.*$/)) {
											monto += parseFloat(row[i].PRIMA_UF);
										}
									}
								} else if (tipoComponente == 'H') {
									monto += parseFloat(row[i].PRIMA_UF);
								}
							}
						},
						complete: function() {
							$('body').css('cursor', 'default');
						}
					});
				}
				return parseFloat(monto.toFixed(4));
			}

			function getRecargo(poliza, componente, cobertura, recargo) {
				monto = 0;
				if (!isNaN(poliza) && !isNaN(componente) && !isNaN(recargo)) {
					if (cobertura != 'M' && cobertura != 'A')
						cobertura = 'X';
					url = base_url+'index.php/home/getterRecargos/'+poliza+'/'+componente+'/'+cobertura+'/'+recargo;
					$.ajax({
						type: 'POST',
						url: url,
						async: false,
						beforeSend: function() {
							$('body').css('cursor', 'wait');
						},
						success: function(data) {
							//console.log(data);
							row = JSON.parse(data);
							i = 0;
							for(i in row) {
								monto += parseFloat(row[i].MN_RECARGO);
							}
						},
						complete: function() {
							$('body').css('cursor', 'default');
						}
					});
				}
				return parseFloat(monto.toFixed(4));
			}


			function activarMascara() {
				$('.rut-dv').mask('00.000.000-X', {translation: {'X': {pattern: /[0-9Kk]/, optional: true}}, reverse: true});
				$('.dv').mask('X', {translation: {'X': {pattern: /[0-9Kk]/}}});
				$('.date-es').mask('00-00-0000', {placeholder: '__-__-____'});
				$('.number').mask('00.000.000', {reverse: true});
				$('.uf-dec-0').mask('0.000', {reverse: true});
				$('.uf-dec-4').mask('0XX9999', {translation: {'X': {pattern: /[0-9,]/, optional: false}}});
				$('.name-full').mask('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', {translation: {'X': {pattern: /[A-Za-z \u00C1\u00E1\u00C9\u00E9\u00CD\u00ED\u00D3\u00F3\u00DA\u00FA\u00DC\u00FC\u00D1\u00F1.\-/]/, optional: true}}});
				$('.name').mask('XXXXXXXXXXXXXXX', {translation: {'X': {pattern: /[A-Za-z \u00C1\u00E1\u00C9\u00E9\u00CD\u00ED\u00D3\u00F3\u00DA\u00FA\u00DC\u00FC\u00D1\u00F1.\-/]/, optional: true}}});
				$('.nacion').mask('XXXXXXXXXXXXXXXXXXXX', {translation: {'X': {pattern: /[A-Za-z \u00C1\u00E1\u00C9\u00E9\u00CD\u00ED\u00D3\u00F3\u00DA\u00FA\u00DC\u00FC\u00D1\u00F1.\-/]/, optional: true}}});
			}

		});
</script>
</head>
