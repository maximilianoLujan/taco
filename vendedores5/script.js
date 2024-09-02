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
function ordenAlfaNumerico(a,b) {
	var nulo = '';
	var A = a.split('-');
	var B = b.split('-');
	if( A[0] == '' ){
		nulo = A.shift();
		var Anumeric = parseFloat(A.shift()) * -1;
	}else{
		var Anumeric = parseFloat(A.shift());
	}
	if( B[0] == '' ){
		nulo = B.shift();
		var Bnumeric = parseFloat(B.shift()) * -1;
	}else{
		var Bnumeric = parseFloat(B.shift());
	}
	var Aalpha = A.join('-');
	var Balpha = B.join('-');
	if( Anumeric === Bnumeric ) {
		if( Aalpha < Balpha ){
			return 1;
		}else{
			return -1;
		}
	} else {
		if( Anumeric > Bnumeric ){
			return 1;
		}else{
	    	return -1;
		}
	}
}

function mostrarColores(){
	q('.tabla-coloreada').removeClass('tabla_unidades');
	q('.tabla-coloreada').removeClass('tabla_monto');
	q('.tabla-coloreada').removeClass('tabla_coloreada_unidades');
	q('.tabla-coloreada').removeClass('tabla_coloreada_monto');
	if( getCookie('coloreado')=='Coloreado' ){
		if(getCookie('ver')=='Ver $'){
			q('.tabla-coloreada').addClass('tabla_coloreada_monto');
		}else{
			q('.tabla-coloreada').addClass('tabla_coloreada_unidades');
		}
	}else{
		if(getCookie('ver')=='Ver $'){
			q('.tabla-coloreada').addClass('tabla_monto');
		}else{
			q('.tabla-coloreada').addClass('tabla_unidades');
		}
	}
}

function reOrdenar(){ // la primer columna debe ser de tipo texto y unica ya que es usada como identificador
	esperar();
	var disparador = q(this);
	var tabla = disparador.parent().parent();
	var columna = disparador.index();
	var arrayFilas = tabla.find('tr');
	var filasAsociadas = new Array();
	var filasOrdenadas = new Array();
	var tipoFilas = new Array();
	var tipoColumna = 'numerica';
	var cabecera = new Array();
	var claves = new Array();
	var nFilas = arrayFilas.length;
//	alert(nFilas);
	var j = 0;
	var k = '';
	var dinero = /^[ ]?\$[ ]?[-]?[ ]?[0-9]/;
	var porcentaje = /^[ ]?\%[ ]?[-]?[ ]?[0-9]/;
	var arrayIdentificadorFila = new Array();
	if( disparador.hasClass('descendente') ){
		var direccion = 'ascendente';
	}else{
		var direccion = 'descendente';
	}
	tabla.find('th').removeClass('descendente');
	tabla.find('th').removeClass('ascendente');
	disparador.addClass(direccion);

//	alert( dinero.test( "34" ) );

	for(i=0;i<nFilas;i++){ // detecto tipo de filas y columnas
		if( arrayFilas.eq(i).find('th').length > 0 ){
			tipoFilas[i] = 'cabecera';
		}else{
			tipoFilas[i] = 'cuerpo';
			var campo = arrayFilas.eq(i).find('td').eq(columna);
			if( campo.find('.monto').length == 1 && campo.find('.cantidad').length == 1 ){//ver si contiene divs de tipo monto/unidad
				campo = 0;
			}else{
				campo = campo.text();
				if( dinero.test( campo ) || porcentaje.test( campo ) ){
					campo = campo.trim();
					campo = campo.substr(1);
					campo = campo.replace(/\./g, '');
				}
				campo = parseFloat( campo );
			}
			if( !q.isNumeric( campo ) ){// si cualquier fila tiene un campo de texto, toda la columna es del tipo texto
				tipoColumna = 'texto';
			}
			//alert(campo + ' --> ' + tipoColumna );
		}
	}

//	alert('nFilas ' + nFilas);
	for(i=0;i<nFilas;i++){ // creo las claves para ordenarlo y remuevo las filas del cuerpo
		if( tipoFilas[i] == 'cuerpo' ){
			var td = arrayFilas.eq(i).find('td').eq(columna);
			if( td.find('.monto').length == 1 && td.find('.cantidad').length == 1 ){//ver si contiene divs de tipo monto/unidad
				if( getCookie('ver')=='Ver $' ){
					k = td.find('.monto').eq(0).text();
				}else{
					k = td.find('.cantidad').eq(0).text();
				}
				if( dinero.test( k ) || porcentaje.test( k ) ){
					k = k.trim();
					k = k.substr(1);
					k = k.replace(/\./g, '');
				}
				k = parseFloat( k );
			}else{
				k = td.text();
				if( dinero.test( k ) || porcentaje.test( k ) ){
					k = k.trim();
					k = k.substr(1);
					k = k.replace(/\./g, '');
				}
				k = parseFloat( k );
			}
//			alert('k ' + k);
			if( columna != 0 ){
				var textoA = arrayFilas.eq(i).find('td').eq(0).text();
				k = k + '-' + textoA;
			}else{
				var textoA = k;
			}
			claves.push(k);
			if( textoA != 'Total' && textoA != 'TOTAL' && textoA != 'total' ){ // parche para no mover la fila total
				filasAsociadas[k] = arrayFilas.eq(i).detach();
			}
		}
	}
	if( tipoColumna == 'texto' ){ // ordeno las claves
		clavesOrdenadas = claves.sort();
	}else{
		clavesOrdenadas = claves.sort( ordenAlfaNumerico );
	}
	if( direccion == 'descendente' ){
		clavesOrdenadas.reverse();
	}
	nFilasCuerpo = clavesOrdenadas.length;
	for(i=0;i<nFilasCuerpo;i++){ // agregar las filas del cuerpo ordenadas
		filaActual = filasAsociadas[ clavesOrdenadas[i] ];
		tabla.append( filaActual );
	}
	finEspera();
}

function esperar(){
	q("body").css("cursor", "progress");
	q('#indicador-de-carga').show();
}

function finEspera(){
	q("body").css("cursor", "auto");
	q('#indicador-de-carga').hide();
	q('#actualizar').removeClass('actualizar');
}

function ajustarCabecera(){
	q('#wrapper').css({'margin-top' : q('#barra-fija').height() + 'px' } );
}

function porQueNoCompra(){
	var trigger = q(this);
	var idCliente = trigger.attr('data-cliente');
	var idSuperRubro = trigger.attr('data-sr') || '';
	q('.dynamic-input').remove();
	var input = q('<input type="text" id="dynamic-input" class="dynamic-input" maxlength="200" />');
	input.val(trigger.text());
	input.keydown(function (e) {
		if (e.keyCode == 13 || e.keyCode == 27) {
			e.preventDefault();
			q(this).focusout();
			return false;
		}
	});
	var parent = trigger.parent();
	parent.append(input);
	input.focus();
	input.focusout(function(){
		q.ajax({
            url: 'por-que-no-compra.php',
            method: "post",
            data: {
				'id_cliente': idCliente,
                'id_super_rubro': idSuperRubro,
				'texto': input.val()
            }
        }).done( function(response){
            //console.log('-->', response);
			parent.text(input.val());
        }).fail(function( jqXHR, textStatus, errorThrown ){
            input.addClass('error');
            alert('No se pudo guardar');
        }).always(function(){
            //console.log(response);
        });
	});
}

q(document).ready(function(){
	ajustarCabecera();
	q('.boton-reordenar').click(reOrdenar);
	q('#vta').click(function(){location.href = '../vta';});
	q('#phpmyadmin').click(function(){location.href = '../phpmyadmin';});
	q('#boton-pass').click(function(){location.href = '/login';});
	q('#boton-scripts').click(function(){location.href = '../scripts/';});

//	q('#ordenar-color').on( "click", reOrdenar );

	mostrarColores();

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

	/*boton coloreado*/
	if( getCookie('coloreado') ){
		q('#coloreado').val( getCookie('coloreado') );
	}else{
		setCookie('coloreado',q('#coloreado').val(),diasCookie);
	}
	q('#coloreado').click(function(){
		if(getCookie('coloreado')=='Coloreado'){
			setCookie('coloreado','Sin Colorear',diasCookie);
		}else{
			setCookie('coloreado','Coloreado',diasCookie);
		}
		q('#coloreado').val(getCookie('coloreado'));
		mostrarColores();
	});

	q('.por-que-no-compra').click(porQueNoCompra);

	finEspera();
});


window.onresize = ajustarCabecera;



