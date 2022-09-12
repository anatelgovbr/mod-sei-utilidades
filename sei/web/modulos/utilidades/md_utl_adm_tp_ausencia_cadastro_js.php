<script type="text/javascript">

var msg11Padrao = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11) ?>';
  
function inicializar(){
  if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_ausencia_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_ausencia_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas(true);
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    var msg = setMensagemPersonalizada(msg11Padrao, ['Motivo de Ausência']);
    alert(msg);
    document.getElementById('txtNome').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txaDescricao').value)=='') {
    var msg = setMensagemPersonalizada(msg11Padrao, ['Descrição']);
    alert(msg);
    document.getElementById('txaDescricao').focus();
    return false;
  }
  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

</script>