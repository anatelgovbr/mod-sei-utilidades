<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 06/07/2018 - criado por jaqueline.cast
 *
 * Versão do Gerador de Código: 1.41.0
 */

try {
  require_once dirname(__FILE__).'/../../SEI.php';
  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objMdUtlAdmTpRevisaoDTO = new MdUtlAdmTpRevisaoDTO();

  $strDesabilitar = '';

  $idTpCtrl = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTpCtrlUtl'];
  $objTpControleUtlRN    = new MdUtlAdmTpCtrlDesempRN();
  $objTipoControleUtlDTO = $objTpControleUtlRN->buscarObjTpControlePorId($idTpCtrl);

  $arrComandos = array();

  switch($_GET['acao']){
    case 'md_utl_adm_tp_revisao_cadastrar':
      $nomeTpCtrl = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';
      $strTitulo = 'Novo Resultado da Revisão - '.$nomeTpCtrl;
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdUtlAdmTpRevisao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objMdUtlAdmTpRevisaoDTO->setNumIdMdUtlAdmTpRevisao(null);
      $objMdUtlAdmTpRevisaoDTO->setStrNome($_POST['txtNome']);
      $objMdUtlAdmTpRevisaoDTO->setStrDescricao($_POST['txaDescricao']);
      $objMdUtlAdmTpRevisaoDTO->setStrSinAtivo('S');
      $objMdUtlAdmTpRevisaoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);

      $vlJustificativa = array_key_exists('rdoJustificativa', $_POST) ? $_POST['rdoJustificativa'] : 'N';
      $objMdUtlAdmTpRevisaoDTO->setStrSinJustificativa($vlJustificativa);

      if (isset($_POST['sbmCadastrarMdUtlAdmTpRevisao'])) {
        try{
          $objMdUtlAdmTpRevisaoRN = new MdUtlAdmTpRevisaoRN();
          $objMdUtlAdmTpRevisaoDTO = $objMdUtlAdmTpRevisaoRN->cadastrar($objMdUtlAdmTpRevisaoDTO);
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.'&id_md_utl_adm_tp_revisao='.$objMdUtlAdmTpRevisaoDTO->getNumIdMdUtlAdmTpRevisao().PaginaSEI::getInstance()->montarAncora($objMdUtlAdmTpRevisaoDTO->getNumIdMdUtlAdmTpRevisao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_tp_revisao_alterar':
      $nomeTpCtrl = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';
      $strTitulo = 'Alterar Resultado da Revisão - '.$nomeTpCtrl;
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdUtlAdmTpRevisao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_md_utl_adm_tp_revisao'])) {
        $objMdUtlAdmTpRevisaoDTO->setNumIdMdUtlAdmTpRevisao($_GET['id_md_utl_adm_tp_revisao']);
        $objMdUtlAdmTpRevisaoDTO->retTodos();
        $objMdUtlAdmTpRevisaoRN = new MdUtlAdmTpRevisaoRN();
        $objMdUtlAdmTpRevisaoDTO->setBolExclusaoLogica(false);
        $objMdUtlAdmTpRevisaoDTO = $objMdUtlAdmTpRevisaoRN->consultar($objMdUtlAdmTpRevisaoDTO);
        if ($objMdUtlAdmTpRevisaoDTO == null) {
          throw new InfraException("Registro não encontrado.");
        } else {
          $vlCheckJustificativa = $objMdUtlAdmTpRevisaoDTO->getStrSinJustificativa() == 'S' ? 'checked=checked' : '';
        }
      } else {
        $vlJustificativa = array_key_exists('rdoJustificativa', $_POST) ? $_POST['rdoJustificativa'] : 'N';
        $objMdUtlAdmTpRevisaoDTO->setStrSinJustificativa($vlJustificativa);
        $objMdUtlAdmTpRevisaoDTO->setNumIdMdUtlAdmTpRevisao($_POST['hdnIdMdUtlAdmTpRevisao']);
        $objMdUtlAdmTpRevisaoDTO->setStrNome($_POST['txtNome']);
        $objMdUtlAdmTpRevisaoDTO->setStrDescricao($_POST['txaDescricao']);
        $objMdUtlAdmTpRevisaoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdUtlAdmTpRevisaoDTO->getNumIdMdUtlAdmTpRevisao())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarMdUtlAdmTpRevisao'])) {
        try{
          $objMdUtlAdmTpRevisaoRN = new MdUtlAdmTpRevisaoRN();
          $objMdUtlAdmTpRevisaoRN->alterar($objMdUtlAdmTpRevisaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Tipo de Revisão "'.$objMdUtlAdmTpRevisaoDTO->getNumIdMdUtlAdmTpRevisao().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.PaginaSEI::getInstance()->montarAncora($objMdUtlAdmTpRevisaoDTO->getNumIdMdUtlAdmTpRevisao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_tp_revisao_consultar':
      $nomeTpCtrl = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';
      $strTitulo = 'Consultar Resultado da Revisão - '.$nomeTpCtrl;
      $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_md_utl_adm_tp_revisao'])).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
      $objMdUtlAdmTpRevisaoDTO->setNumIdMdUtlAdmTpRevisao($_GET['id_md_utl_adm_tp_revisao']);
      $objMdUtlAdmTpRevisaoDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmTpRevisaoDTO->retTodos();
      $objMdUtlAdmTpRevisaoRN = new MdUtlAdmTpRevisaoRN();
      $objMdUtlAdmTpRevisaoDTO = $objMdUtlAdmTpRevisaoRN->consultar($objMdUtlAdmTpRevisaoDTO);
      if ($objMdUtlAdmTpRevisaoDTO===null){
        throw new InfraException("Registro não encontrado.");
      } else {
        $vlCheckJustificativa = $objMdUtlAdmTpRevisaoDTO->getStrSinJustificativa() == 'S' ? 'checked=checked' : '';
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }


}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
<?
if(0){?><style><?}?>
  #lblNome {position:absolute;left:0%;top:6%;width:40%;}
  #ancAjudaNome{position: absolute;
    left: 131px;
    top: 5%;}
  #txtNome {position:absolute;left:0%;top:45%;width:40%;}

  #lblDescricao {position:absolute;left:0%;top:10%;width:60%;}
  #ancAjudaDesc {position:absolute;left:63px;top:10%;}
  #txaDescricao {position:absolute;left:0%;top:29%;width:60%;resize: none}

  .tamanhoBtnAjuda{
    width: 16px;
    height: 16px;
  }

  #lblJustificativa{
    margin-top: 0.3%;
    position: absolute;
  }

  <?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';

?>
<?if(0){?><script type="text/javascript"><?}?>

  function inicializar(){
    if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_revisao_cadastrar'){
      document.getElementById('txtNome').focus();
    } else if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_revisao_consultar'){
      infraDesabilitarCamposAreaDados();
    }else{
      document.getElementById('btnCancelar').focus();
    }
    infraEfeitoTabelas(true);
  }

  function validarCadastro() {
    if (infraTrim(document.getElementById('txtNome').value)=='') {
      var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Resultado da Revisão']);
      alert(msg);
      document.getElementById('txtNome').focus();
      return false;
    }

    if (infraTrim(document.getElementById('txaDescricao').value)=='') {
        var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Descrição']);
        alert(msg);
        document.getElementById('txaDescricao').focus();
       return false;
    }

    return true;
  }

  function OnSubmitForm() {
    return validarCadastro();
  }

  <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmMdUtlAdmTpRevisaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    PaginaSEI::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Resultado da Revisão:</label>
    <a href="javascript:void(0);" id="ancAjudaNome" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Nome do Resultado da Revisão.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>

    <input type="text" id="txtNome" name="txtNome" maxlength="50" class="infraText"  value="<?=PaginaSEI::tratarHTML($objMdUtlAdmTpRevisaoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('9em');
    ?>
    <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
    <a href="javascript:void(0);" id="ancAjudaDesc" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Texto que define o Tipo de Revisão.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>
    <textarea type="text" id="txaDescricao" rows="3" maxlength="250" name="txaDescricao" class="infraTextArea" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objMdUtlAdmTpRevisaoDTO->getStrDescricao());?></textarea>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('4em');
    ?>
    <input <?php echo $vlCheckJustificativa; ?> type="checkbox" name="rdoJustificativa" id="rdoJustificativa" value="S"/>
    <label id="lblJustificativa" name="lblJustificativa" for="rdoJustificativa" class="infraLabelCheckbox">Precisa de Justificativa?</label>
    <?php PaginaSEI::getInstance()->fecharAreaDados();  ?>
    <input type="hidden" id="hdnIdMdUtlAdmTpRevisao" name="hdnIdMdUtlAdmTpRevisao" value="<?=$objMdUtlAdmTpRevisaoDTO->getNumIdMdUtlAdmTpRevisao();?>" />
    <input type="hidden" name="hdnIdTpCtrlUtl" id="hdnIdTpCtrlUtl" value="<?php echo $idTpCtrl ?>"/>
    <?
    //PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
