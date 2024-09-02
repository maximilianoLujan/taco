var q = jQuery.noConflict();
var diasCookie = 7;
function setCookie(name,value,days) {
    if (days) {
	   var date = new Date();
	   date.setTime(date.getTime()+(days*24*60*60*1000));
	   var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
	   var c = ca[i];
	   while (c.charAt(0)==' ') c = c.substring(1,c.length);
	   if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function unserialize(data){
	var that = this,
	utf8Overhead = function(chr){
		var code = chr.charCodeAt(0);
		if(code<0x0080){
			return 0;
		}
		if(code<0x0800){
			return 1;
		}
		return 2;
	},
	error = function(type, msg, filename, line){
		throw new that.window[type](msg, filename, line);
	},
	read_until = function(data, offset, stopchr){
		var i=2, buf=[], chr=data.slice(offset, offset+1);
		while(chr!=stopchr){
			if((i+offset)>data.length){
				error('Error', 'Invalid');
			}
			buf.push(chr);
			chr=data.slice(offset+(i-1), offset+i);
			i+=1;
		}
		return [buf.length, buf.join('')];
	},
	read_chrs = function(data, offset, length){
		var i, chr, buf;
		buf = [];
		for(i=0;i<length;i++){
			chr = data.slice(offset+(i-1), offset+i);
			buf.push(chr);
			length -= utf8Overhead(chr);
		}
		return [buf.length, buf.join('')];
	},
	_unserialize = function(data, offset){
		var dtype, dataoffset, keyandchrs, keys, contig,
		length, array, readdata, readData, ccount,
		stringlength, i, key, kprops, kchrs, vprops,
		vchrs, value, chrs = 0,
		typeconvert = function(x){
			return x;
		};
		if(!offset){
			offset = 0;
		}
		dtype = (data.slice(offset,offset+1)).toLowerCase();
		dataoffset = offset+2;
		switch(dtype){
		case 'i':
			typeconvert = function(x){
				return parseInt(x,10);
			};
			readData = read_until(data, dataoffset, ';');
			chrs = readData[0];
			readdata = readData[1];
			dataoffset += chrs + 1;
			break;
		case 'b':
			typeconvert = function(x){
				return parseInt(x,10)!==0;
			};
			readData = read_until(data, dataoffset, ';');
			chrs = readData[0];
			readdata = readData[1];
			dataoffset += chrs+1;
			break;
		case 'd':
			typeconvert = function(x){
				return parseFloat(x);
			};
			readData = read_until(data, dataoffset, ';');
			chrs = readData[0];
			readdata = readData[1];
			dataoffset += chrs+1;
			break;
		case 'n':
			readdata = null;
			break;
		case 's':
			ccount = read_until(data, dataoffset, ':');
			chrs = ccount[0];
			stringlength = ccount[1];
			dataoffset += chrs + 2;
			readData = read_chrs(data, dataoffset+1, parseInt(stringlength,10));
			chrs = readData[0];
			readdata = readData[1];
			dataoffset += chrs+2;
			if(chrs != parseInt(stringlength,10) && chrs != readdata.length){
				error('SyntaxError', 'String length mismatch');
			}
			break;
		case 'a':
			readdata = {};
			keyandchrs = read_until(data, dataoffset, ':');
			chrs = keyandchrs[0];
			keys = keyandchrs[1];
			dataoffset += chrs + 2;
			length = parseInt(keys, 10);
			contig = true;
			for(i=0;i<length;i++){
				kprops = _unserialize(data, dataoffset);
				kchrs = kprops[1];
				key = kprops[2];
				dataoffset += kchrs;
				vprops = _unserialize(data, dataoffset);
				vchrs = vprops[1];
				value = vprops[2];
				dataoffset += vchrs;
				if(key!==i) contig = false;
					readdata[key] = value;
				}
				if(contig){
					array = new Array(length);
					for(i=0;i<length;i++) array[i] = readdata[i];
					readdata = array;
				}
			dataoffset += 1;
			break;
		default:
			error('SyntaxError', 'Unknown / Unhandled data type(s): '+dtype);
			break;
		}
		return [dtype, dataoffset - offset, typeconvert(readdata)];
	};
	return _unserialize((data + ''), 0)[2];
}

function esperar(){
	q("body").css("cursor", "progress");
	q('#indicador-de-carga').show();
}

function finEspera(){
	q("body").css("cursor", "auto");
	q('#indicador-de-carga').hide();
}

function ajustarCabecera(){
	q('#wrapper').css({'margin-top' : q('#barra-fija').height() + 'px' } );
}
function isHovered(selector){
    return q(selector + ":hover").length > 0;
}

function panelLateral(){
	event.preventDefault();
	var tipo = getCookie('boton-unidad-tiempo-inicio');
//	alert(tipo);
	var ajusteInflacion = getCookie('ajuste-inflacion');
	var request = q.ajax({
		url: "panel-lateral.php",
		type: "GET",
		data: {'tipo' : tipo, 'ajuste-inflacion' : ajusteInflacion },
		dataType: "html",
		cache: true
	});
	request.done(function(response){
		q('#datos-principal').html( response );
	});
	request.fail(function(response){
		q('#datos-principal').html( 'Error' );
	});
	return false;
}

q(document).ready(function(){
	ajustarCabecera();
	if(location.search == '?mostrar=1'){
		panelLateral();
	}
	q('#vta').click(function(){location.href = './vta';});
	q('#actualizar').click(function(){location.href = './?mostrar=1';});
	q('#phpmyadmin').click(function(){location.href = '/phpmyadmin';});
	q('#boton-pass').click(function(){location.href = './login';});
	q('#boton-scripts').click(function(){location.href = './scripts/';});

	/*boton ver*/
	if( getCookie('ver') ){
		q('#ver').val( getCookie('ver') );
	}else{
		setCookie('ver',q('#ver').val(),diasCookie);
	}
	q('#ver').click(function(){
		if(getCookie('ver')=='Ver $'){
			setCookie('ver','Ver U',diasCookie);
		}else if(getCookie('ver')=='Ver U'){
			setCookie('ver','Ver $/U',diasCookie);
		}else{
			setCookie('ver','Ver $',diasCookie);
		}
		q('#ver').val(getCookie('ver'));
	});

	/*boton sucursal*/
	if( getCookie('sucursal') ){
		q('#sucursal').val( getCookie('sucursal') );
	}else{
		setCookie('sucursal',q('#sucursal').val(),diasCookie);
	}
	q('#sucursal').click(function(){
		if(getCookie('sucursal')=='Total'){
			setCookie('sucursal','Pico',diasCookie);
		}else if(getCookie('sucursal')=='Pico'){
			setCookie('sucursal','MDP',diasCookie);
		}else{
			setCookie('sucursal','Total',diasCookie);
		}
		q('#sucursal').val(getCookie('sucursal'));
	});

	/*boton ajuste-inflacion*/
	if( getCookie('ajuste-inflacion') ){
		q('#ajuste-inflacion').val( getCookie('ajuste-inflacion') );
	}else{
		setCookie('ajuste-inflacion',q('#ajuste-inflacion').val(),diasCookie);
	}
	if( getCookie('ajuste-inflacion')=='A.I Si' ){
		q('#ajuste-inflacion').addClass('boton-alerta');
	}
	q('#ajuste-inflacion').click(function(){
		if(getCookie('ajuste-inflacion')=='A.I No'){
			setCookie('ajuste-inflacion','A.I Si',diasCookie);
			q('#ajuste-inflacion').addClass('boton-alerta');
		}else{
			setCookie('ajuste-inflacion','A.I No',diasCookie);
			q('#ajuste-inflacion').removeClass('boton-alerta');
		}
		q('#ajuste-inflacion').val(getCookie('ajuste-inflacion'));
		q('#actualizar').addClass('actualizar');
	});

	/*boton-unidad-tiempo*/
	if( getCookie('boton-unidad-tiempo-inicio') ){
		q('#boton-unidad-tiempo-inicio').val( getCookie('boton-unidad-tiempo-inicio') );
	}else{
		setCookie('boton-unidad-tiempo-inicio',q('#boton-unidad-tiempo-inicio').val(),diasCookie);
	}
	q('#boton-unidad-tiempo-inicio').click(function(){
		if(getCookie('boton-unidad-tiempo-inicio')=='Años'){
			setCookie('boton-unidad-tiempo-inicio','Meses',diasCookie);
		}else if(getCookie('boton-unidad-tiempo-inicio')=='Meses'){
			setCookie('boton-unidad-tiempo-inicio','Trimestre',diasCookie);
		}else{
			setCookie('boton-unidad-tiempo-inicio','Años',diasCookie);
		}
		q('#boton-unidad-tiempo-inicio').val(getCookie('boton-unidad-tiempo-inicio'));
	});

	q('.grafico-dinamico').each(function(){
		var src = q(this).attr('src');
		var ver = 'ver=' + encodeURIComponent(getCookie('ver'));
		var sucursal = 'sucursal=' + encodeURIComponent(getCookie('sucursal'));
		var ai = 'ai=' + encodeURIComponent(getCookie('ajuste-inflacion'));
		var periodo = 'periodo=' + encodeURIComponent(getCookie('boton-unidad-tiempo-inicio'));
		q(this).attr('src', src + '?' + ver + '&' + sucursal + '&' + ai + '&' + periodo );
	});

});

window.onresize = ajustarCabecera;
window.addEventListener("orientationchange",function(){
	//alert('ajustarCabecera()');
},false);

