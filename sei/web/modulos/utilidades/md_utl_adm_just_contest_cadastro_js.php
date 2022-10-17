<script type="text/javascript">

    function inicializar(){
        if ('<?=$_GET['acao']?>'=='md_utl_adm_just_contest_cadastrar'){
            document.getElementById('txtNome').focus();
        } else if ('<?=$_GET['acao']?>'=='md_utl_adm_just_contest_consultar'){
            infraDesabilitarCamposAreaDados();
        }else{
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas(true);
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtNome').value)=='') {
            alert(' Informe o Nome da Justificativa.');
            document.getElementById('txtNome').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txaDescricao').value)=='') {
            alert('Informe a Descrição da Justificativa.');
            document.getElementById('txaDescricao').focus();
            return false;
        }

        return true;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }

</script>