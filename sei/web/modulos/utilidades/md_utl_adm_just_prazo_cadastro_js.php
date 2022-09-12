<script type="text/javascript">

    function inicializar() {
        if ('<?=$_GET['acao']?>' == 'md_utl_adm_just_prazo_cadastrar') {
            document.getElementById('txtNome').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_utl_adm_just_prazo_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas(true);
    }

    function validarCadastro() {
        $rdoDilacao = document.getElementById('rdoDilacao').checked == false;
        $rdoSuspensao = document.getElementById('rdoSuspensao').checked == false;
        $rdoInterrupcao = document.getElementById('rdoInterrupcao').checked == false;

        if (infraTrim(document.getElementById('txtNome').value) == '') {
            var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Justificativa']);
            alert(msg);
            document.getElementById('txtNome').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txaDescricao').value) == '') {
            var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Descrição']);
            alert(msg);
            document.getElementById('txaDescricao').focus();
            return false;
        }

        if ($rdoDilacao && $rdoSuspensao && $rdoInterrupcao) {
            var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Tipo de Solicitação']);
            alert(msg);
            return false;
        }

        return true;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }

</script>