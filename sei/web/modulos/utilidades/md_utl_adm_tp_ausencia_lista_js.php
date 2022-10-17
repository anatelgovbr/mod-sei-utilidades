<script type="text/javascript">

  var msg100Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_100)?>';

  function inicializar(){
  if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_ausencia_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas(true);
}

function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Motivo de Ausência \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdUtlAdmTpAusenciaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmMdUtlAdmTpAusenciaLista').submit();
  }
}



function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Motivo de Ausência \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdUtlAdmTpAusenciaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmMdUtlAdmTpAusenciaLista').submit();
  }
}

function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Motivo de Ausência \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMdUtlAdmTpAusenciaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMdUtlAdmTpAusenciaLista').submit();
  }
}


function fechar(){
  location.href="<?= $strUrlFechar ?>";
}

</script>