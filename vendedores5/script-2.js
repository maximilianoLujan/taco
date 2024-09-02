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
	var parentesis = /\(.*\)/;
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
				}
				k = k.replace(/\./g, '');
				k = parseFloat( k );
			}else{
				k = td.text();
				if( dinero.test( k ) || porcentaje.test( k ) ){
					k = k.trim();
					k = k.substr(1);
					k = k.replace(/\./g, '');
				}else if( q('#orden-por').val() == 'Orden por $' && parentesis.test(k) ){
                    var aperturaParentesis = 1 + k.indexOf('(');
                    var cierreParentesis = k.indexOf(')');
                    k = k.slice(aperturaParentesis,cierreParentesis);
                    //console.log(k);
					k = k.replace(/\./g, '');
                    k = parseFloat( k );
                }else{
					k = k.replace(/\./g, '');
                    k = parseFloat( k );
                }
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

function guardarPorQueNoCompra(idCliente, idSuperRubro, texto){
	q.ajax({
		url: 'por-que-no-compra.php',
		method: "post",
		data: {
			'id_cliente': idCliente,
			'id_super_rubro': idSuperRubro,
			'texto': texto
		}
	}).done( function(response){
		//console.log('-->', response);
		var json = JSON.parse(response);
		if(json.status == 'error'){
			alert(json.message);
		}
	}).fail(function( jqXHR, textStatus, errorThrown ){
		alert('Error de conexión. No se pudo guardar');
	}).always(function(){
		//console.log(response);
	});
}

function porQueNoCompra(){
	//console.log('focus in');
	var trigger = q(this);
	var idCliente = trigger.attr('data-cliente');
	var idSuperRubro = trigger.attr('data-sr') || '';
	var texto = trigger.text() || '';
	trigger.keydown(function (e) {
		if (e.keyCode == 27) {

			e.preventDefault();
			//console.log('focus out K 13/27');
			trigger.focusout();
			return false;

		}else if (e.keyCode == 13) {

			e.preventDefault();
			// console.log(e.keyCode);
			trigger.parent().next().find('.por-que-no-compra').focus();
			//console.log('focus out K 13/27');
			// trigger.focusout();
			return false;

		}
	});
	trigger.focusout(function(){
		//console.log('focus out');
		texto = trigger.text() || '';
		guardarPorQueNoCompra(idCliente, idSuperRubro, texto);
	});
}

function filtrarProximos(){
	// console.log(q(this).is(':checked'));
	esperar();
	if( q(this).is(':checked') ){
		q('.no-es-proximo').css("display", "none");
	}else{
		q('.no-es-proximo').css("display", "table-row");
	}
	finEspera();
}

function detallesMarcas(){
	esperar();
	var trigger = q(this);
	var cliente = trigger.attr('data-cliente');
	var sr = trigger.attr('data-sr');
	var top = trigger.offset().top;
	var left = trigger.offset().left + trigger.width();
	var request = q.ajax({
		url: 'detalles-marcas.php',
		data: {'id-cliente' : cliente, 'id-sr' : sr},
		type: "GET"
	});
	request.done(function(response){
		var contenedor = q('<div style="width:90%;text-align:left;margin: 0 auto; padding:20px;overflow: auto;"></div>');
		contenedor.addClass( 'fancybox-contenedor' );
		top = Math.round(top - 15);
		//console.log(top);
		left = q(window).width() * 0.05;
		contenedor.offset({ "top": top, "left": left });
		contenedor.html( response );
		q('body').append(contenedor);
		finEspera();
	});
	request.fail(function(response){
		alert('Error: No se pudo conectar con el servidor');
		finEspera();
	});
}

q(document).ready(function(){
	ajustarCabecera();
	q('.boton-reordenar').click(reOrdenar);
	q('#vta').click(function(){location.href = '../vta';});
	q('#phpmyadmin').click(function(){location.href = '../phpmyadmin';});
	q('#boton-pass').click(function(){location.href = '/login';});
	q('#boton-scripts').click(function(){location.href = '../scripts/';});

	q('#ver-proximos').click(filtrarProximos);

	q('.detalle-marcas').click(detallesMarcas);

	q('.mostrar-quince-menos-importantes').on('click', function() {
		var trigger = q(this);
		var cliente = trigger.attr('data-cliente');
        var rubros = q(`.quince-menos-importantes-${cliente}`);
		var siblingTable = trigger.prev('table');

		rubros.toggleClass('no-mostrar');
		
		if (rubros.hasClass('no-mostrar')) {
			trigger.text('Ver rubros restantes');
			siblingTable[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
		} else {
			trigger.text('Ocultar rubros restantes');
		}
    });

	q(window).click( function(){
		q('.fancybox-contenedor').remove();
	});
	
//	q('#ordenar-color').on( "click", reOrdenar );

	mostrarColores();

	/*boton orden-por*/
	if( getCookie('orden-por') ){
		q('#orden-por').val( getCookie('orden-por') );
	}else{
		setCookie('orden-por',q('#orden-por').val(),diasCookie);
	}
	q('#orden-por').click(function(){
		if(getCookie('orden-por')=='Orden por $'){
			setCookie('orden-por','Orden por U',diasCookie);
		}else{
			setCookie('orden-por','Orden por $',diasCookie);
		}
		q('#orden-por').val(getCookie('orden-por'));
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

	q('.por-que-no-compra').focusin(porQueNoCompra);

	q('#vendedor').change( function(){
		// console.log( q(this).val() );
		// console.log( vendedoresZonas );

		var vendedor = q(this).val();
		if( vendedor == 'Selecciona el Vendedor' ){
			var zonas = vendedoresZonas['TODAS'];
		}else{
			var zonas = vendedoresZonas[vendedor];
		}
		// console.log( zonas );

		var newHtml = `<option>Selecciona la zona</option>\n`;
		for (const zona in zonas) {
			// console.log(`${zona}: ${zonas[zona]}`);
			newHtml += `<option value="${zona}">${zonas[zona]}</option>\n`;
		}
		// console.log(newHtml);
		q('#zona').html(newHtml);
	});

	q('#formulario-opciones').submit(function(event) {
		if( q('#vendedor').val() == 'TODOS' && q('#codigo-plan').val() != 1 && q('#codigo-plan').val() != 2 ){
			event.preventDefault();
			alert('No se pueden ver todos los vendedores si no se selecciona un plan');
			return false
		}
	});

	finEspera();
});


window.onresize = ajustarCabecera;



