function enviaFile(){
        myFrame = $('#zona_tabla');
        //alert(myFrame.src);   
        //banco = $('sl_bancos').options[$('sl_bancos').selectedIndex].value;
        if($('#up_file').val() == ''){
                alert('Debe seleccionar Archivo');
                return false;
        }
        $('#frm_file').attr('action', $('#site_url').val()+'/lista_correo/enviado');
        $('#frm_file').submit();
}

function cargaCliente(){
	
	$('#loader').css("display", "");
	$('#div_boton').css("display", "none");
	$.ajax({	    
        	url : $('#site_url').val()+'/carga_cliente',
        	success: function(datos){
			$('#loader').css("display", "none");
			$('#div_boton').css("display", "");
			dato = $.trim(datos);
			if(dato == 'ok')
				alert('Proceso terminado')
			else{	
				salida = dato.split('#');
				alert(salida[1]);
			}
		}
	});

}

function cargaSuscrito(){

        $('#loader').css("display", "");
        $('#div_boton').css("display", "none");
        $.ajax({
                url : $('#site_url').val()+'/carga_suscrito',
                success: function(datos){
                        $('#loader').css("display", "none");
                        $('#div_boton').css("display", "");
                        dato = $.trim(datos);
                        if(dato == 'ok')
                                alert('Proceso terminado')
                        else{
                                salida = dato.split('#');
                                alert(salida[1]);
                        }
                }
        });
	
}
