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

  PaginaSEI::getInstance()->verificarSelecao('md_utl_adm_tp_just_revisao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objMdUtlAdmTpJustRevisaoDTO = new MdUtlAdmTpJustRevisaoDTO();

  $strDesabilitar = '';

  $idTpCtrl              = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTpCtrlUtl'];
  $objTpControleUtlRN    = new MdUtlAdmTpCtrlDesempRN();
  $objTipoControleUtlDTO = $objTpControleUtlRN->buscarObjTpControlePorId($idTpCtrl);
  $arrComandos           = array();

  switch($_GET['acao']){
    case 'md_utl_adm_tp_just_revisao_cadastrar':
      $nomeTpCtrl = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';
      $strTitulo = 'Nova Justificativa de Avaliação - '.$nomeTpCtrl;
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdUtlAdmTpJustRevisao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objMdUtlAdmTpJustRevisaoDTO->setNumIdMdUtlAdmTpJustRevisao(null);
      $objMdUtlAdmTpJustRevisaoDTO->setStrNome($_POST['txtNome']);
      $objMdUtlAdmTpJustRevisaoDTO->setStrDescricao($_POST['txaDescricao']);
      $objMdUtlAdmTpJustRevisaoDTO->setStrSinAtivo('S');
      $objMdUtlAdmTpJustRevisaoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);

      if (isset($_POST['sbmCadastrarMdUtlAdmTpJustRevisao'])) {
        try{
          $objMdUtlAdmTpJustRevisaoRN = new MdUtlAdmTpJustRevisaoRN();
          $objMdUtlAdmTpJustRevisaoDTO = $objMdUtlAdmTpJustRevisaoRN->cadastrar($objMdUtlAdmTpJustRevisaoDTO);
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.'&id_md_utl_adm_tp_just_revisao='.$objMdUtlAdmTpJustRevisaoDTO->getNumIdMdUtlAdmTpJustRevisao().PaginaSEI::getInstance()->montarAncora($objMdUtlAdmTpJustRevisaoDTO->getNumIdMdUtlAdmTpJustRevisao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_tp_just_revisao_alterar':
      $nomeTpCtrl = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';
      $strTitulo = 'Alterar Justificativa de Avaliação - '.$nomeTpCtrl;
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdUtlAdmTpJustRevisao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_md_utl_adm_tp_justificativa'])){
        $objMdUtlAdmTpJustRevisaoDTO->setNumIdMdUtlAdmTpJustRevisao($_GET['id_md_utl_adm_tp_justificativa']);
        $objMdUtlAdmTpJustRevisaoDTO->retTodos();
        $objMdUtlAdmTpJustRevisaoRN = new MdUtlAdmTpJustRevisaoRN();
        $objMdUtlAdmTpJustRevisaoDTO->setBolExclusaoLogica(false);
        $objMdUtlAdmTpJustRevisaoDTO = $objMdUtlAdmTpJustRevisaoRN->consultar($objMdUtlAdmTpJustRevisaoDTO);
        if ($objMdUtlAdmTpJustRevisaoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objMdUtlAdmTpJustRevisaoDTO->setNumIdMdUtlAdmTpJustRevisao($_POST['hdnIdMdUtlAdmTpJustRevisao']);
        $objMdUtlAdmTpJustRevisaoDTO->setStrNome($_POST['txtNome']);
        $objMdUtlAdmTpJustRevisaoDTO->setStrDescricao($_POST['txaDescricao']);
        $objMdUtlAdmTpJustRevisaoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdUtlAdmTpJustRevisaoDTO->getNumIdMdUtlAdmTpJustRevisao())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarMdUtlAdmTpJustRevisao'])) {
        try{
          $objMdUtlAdmTpJustRevisaoRN = new MdUtlAdmTpJustRevisaoRN();
          $objMdUtlAdmTpJustRevisaoRN->alterar($objMdUtlAdmTpJustRevisaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Justificativa de Avaliação "'.$objMdUtlAdmTpJustRevisaoDTO->getNumIdMdUtlAdmTpJustRevisao().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.PaginaSEI::getInstance()->montarAncora($objMdUtlAdmTpJustRevisaoDTO->getNumIdMdUtlAdmTpJustRevisao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_tp_just_revisao_consultar':
      $nomeTpCtrl = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';
      $strTitulo = 'Consultar Justificativa de Avaliação - '.$nomeTpCtrl;
      $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_md_utl_adm_tp_justificativa'])).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
      $objMdUtlAdmTpJustRevisaoDTO->setNumIdMdUtlAdmTpJustRevisao($_GET['id_md_utl_adm_tp_justificativa']);
      $objMdUtlAdmTpJustRevisaoDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmTpJustRevisaoDTO->retTodos();
      $objMdUtlAdmTpJustRevisaoRN = new MdUtlAdmTpJustRevisaoRN();
      $objMdUtlAdmTpJustRevisaoDTO = $objMdUtlAdmTpJustRevisaoRN->consultar($objMdUtlAdmTpJustRevisaoDTO);
      if ($objMdUtlAdmTpJustRevisaoDTO===null){
        throw new InfraException("Registro não encontrado.");
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
<?if(0){?><style><?}?>
  #lblNome {position:absolute;left:0%;top:6%;width:40%;}
  #ancAjudaNome{position: absolute;
    left: 121px;
    top: 5%;}
  #txtNome {position:absolute;left:0%;top:45%;width:40%;}

  #lblDescricao {position:absolute;left:0%;top:10%;width:60%;}
  #ancAjudaDesc {position:absolute;left:63px;top:10%;}
  #txaDescricao {position:absolute;left:0%;top:25%;width:60%;resize: none}

  .tamanhoBtnAjuda{
    width: 16px;
    height: 16px;
  }

  <?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
?>

<?


if(0){?><script type="text/javascript"><?}?>

  function inicializar(){
    if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_just_revisao_cadastrar'){
      document.getElementById('txtNome').focus();
    } else if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_just_revisao_consultar'){
      infraDesabilitarCamposAreaDados();
    }else{
      document.getElementById('btnCancelar').focus();
    }
    infraEfeitoTabelas(true);
  }

  function validarCadastro() {
    if (infraTrim(document.getElementById('txtNome').value)=='') {
      var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Tipo de Justificativa']);
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
  <form id="frmMdUtlAdmTpJustRevisaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    PaginaSEI::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Justificativa de Avaliação:</label>
    <a href="javascript:void(0);" id="ancAjudaNome" style="margin-left: 21px;" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Nome da Justificativa de Avaliação que irá aparecer ao se cadastrar um Tipo de Avaliação.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>

    <input type="text" id="txtNome" name="txtNome" maxlength="50" class="infraText"  value="<?=PaginaSEI::tratarHTML($objMdUtlAdmTpJustRevisaoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('12em');
    ?>
    <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
    <a href="javascript:void(0);" id="ancAjudaDesc" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Texto que define a Justificativa de Avaliação.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>
    <textarea type="text" id="txaDescricao" rows="3" maxlength="250" name="txaDescricao" class="infraTextArea" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objMdUtlAdmTpJustRevisaoDTO->getStrDescricao());?></textarea>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    ?>
    <input type="hidden" id="hdnIdMdUtlAdmTpJustRevisao" name="hdnIdMdUtlAdmTpJustRevisao" value="<?=$objMdUtlAdmTpJustRevisaoDTO->getNumIdMdUtlAdmTpJustRevisao();?>" />
    <input type="hidden" name="hdnIdTpCtrlUtl" id="hdnIdTpCtrlUtl" value="<?php echo $idTpCtrl ?>"/>
    <?
    //PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
