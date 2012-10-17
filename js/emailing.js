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
