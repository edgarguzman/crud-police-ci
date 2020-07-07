$.fn.reset = function () {
	$(this).each (function() { this.reset(); });
}

$.fn.valNum = function (options) {
	(options==undefined) ? n=$.trim($(this).val()) : n=$.trim($(this).html());
	n=n.split('.').join('');
	n=n.split(',').join('.');
  	//return (n.replace('.','').replace(',','.')) || 0 ;
  	return parseFloat(n) || 0 ;
}

function capitalize(text) {
    return text.toLowerCase().replace(/(^|\s)([a-zñáàéèíìóòúùü])/g, function(m, p1, p2) { return p1 + p2.toUpperCase() });
}

function number_format(number, decimals, dec_point, thousands_sep) {
    var n = !isFinite(+number) ? 0 : +number, 
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function date_format(date, separator) {
	var arrDate = date.split(separator);
	var date = arrDate[2]+'-'+arrDate[1]+'-'+arrDate[0];
	return date || null;
}

function getEdad(dateNac, dateNow) {
	dateNac = new Date(dateNac.split('-').join('/'));
	if (dateNow == undefined)
		dateNow = new Date();
	else 
		dateNow = new Date(dateNow.split('-').join('/'));
	var edad = parseInt((dateNow-dateNac)/365/24/60/60/1000,10);
	return edad || 0;
}

function getEdadActuarial(dateNac, dateVig) {
   	arrDateNac = dateNac.split('-');
	d = parseInt(arrDateNac[2],10);
	m = parseInt(arrDateNac[1],10);
	Y = parseInt(arrDateNac[0],10);
   	if (dateVig == undefined ) {
		dateNow = new Date();
		dd = dateNow.getDate();
		mm = dateNow.getMonth()+1;
		YY = dateNow.getFullYear();
	} else {
		arrDateVig = dateVig.split('-');
		dd = parseInt(arrDateVig[2],10);
		mm = parseInt(arrDateVig[1],10);
		YY = parseInt(arrDateVig[0],10);
	}

	dateNac = new Date(Y,m-1,d);
	dateVig = new Date(YY,mm-1,dd);
	
	if (dateNac <= dateVig) {
	   	if (arrDateNac.length == 3 && d > 0 && m > 0 && Y > 0) {
		   	if (dd < d) {
		   		dd += 30;
		   		mm -= 1;
		   	}
		   	if (mm < m) {
		   		mm += 12;
		   		YY -= 1;
		   	}
		   	edad = YY - Y;
		   	mes = mm - m;
		   	if (mes >= 6) 
		   		edad += 1;
		}
		return edad;
	}
	return null;
}

function valDate (date, format, separator) {
	format = format || 'EN';
	separator = separator || '-';
	arrDate = date.split(separator);
	if (format == 'ES') {
	   	d = parseInt(arrDate[0],10);
	   	m = parseInt(arrDate[1],10);
	   	Y = parseInt(arrDate[2],10);
	} else if (format == 'EN') {
		d = parseInt(arrDate[2],10);
	   	m = parseInt(arrDate[1],10);
	   	Y = parseInt(arrDate[0],10);
	}
	return m > 0 && m < 13 && Y > 0 && Y < 32768 && d > 0 && d <= (new Date(Y, m, 0)).getDate();
}

function valRangeDate(dateNac, dateVig, YYmin, YYmax) {	
	var dateMin = new Date(dateVig.split('-').join('/'));
	var dateMax = new Date(dateVig.split('-').join('/'));
	var dateNac = new Date(dateNac.split('-').join('/'));
	var dateVig = new Date(dateVig.split('-').join('/'));

	var month = dateVig.getMonth()+1;
	var year = dateVig.getFullYear();
	var daysAyear = ( ( (year%400)==0 ) || ( (year%4)==0 && (year%100)!=0 ) ) ? 366 : 365;
	var daysAyearAux = ( ( ((year-1)%400)==0 ) || ( ((year-1)%4)==0 && ((year-1)%100)!=0 ) ) ? 366 : 365;

	dateMin.setMonth(dateMin.getMonth() + 12 * YYmin);
	
	if (daysAyear == 365) {
		
		if (month == 1) {

			if (daysAyearAux == 365) {
				dateMax.setTime(dateMax.getTime() + parseInt(YYmax*365) * (1000*60*60*24) );
			} else {
				dateMax.setTime(dateMax.getTime() + (parseInt(YYmax*365)+1) * (1000*60*60*24) );
			}

		} else {
			dateMax.setTime(dateMax.getTime() + parseInt(YYmax*365) * (1000*60*60*24) );
		}

	} else {
		
		if (month == 1) {

			if (daysAyearAux == 365) {
				dateMax.setTime(dateMax.getTime() + parseInt(YYmax*365) * (1000*60*60*24) );
			} else {
				dateMax.setTime(dateMax.getTime() + (parseInt(YYmax*365)+1) * (1000*60*60*24) );
			}

		} else {
			dateMax.setTime(dateMax.getTime() + parseInt(YYmax*365) * (1000*60*60*24) );
		}

	}

	if (dateNac >= dateMin && dateNac <= dateMax)
		return true;
	else
		return false;
}


function valRutDv(rut, dv) {
	if (rut != undefined || dv != undefined) {
		if (dv == getDv(rut))
			return true;
	}
	return false;
}

function getDv(rut) {
	var M = 0, S = 1;
	for (; rut; rut = Math.floor(rut/10))
		S = (S + rut % 10 * (9 - M++ %6)) % 11;
	return S ? S-1 : 'K';
}

function valStrLen(str, min, max) {
	if (str.length >= min && str.length <= max)
		return true;
	else
		return false;
}

function valStrNaN(str) {
	var num='0123456789';
	for(i=0; i<str.length; i++) {
		if (num.indexOf(str.charAt(i),0) != -1)
			return false;
	}
	return true;
}

function monthDiff(d1, d2) {
	var months;
	months = (d2.getFullYear() - d1.getFullYear()) * 12;
	months += d2.getMonth() - d1.getMonth();
	return months <= 0 ? 0 : months;
}
