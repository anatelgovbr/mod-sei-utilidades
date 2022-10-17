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
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmMdUtlAdmTpJustRevisaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?php
      PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
      //PaginaSEI::getInstance()->montarAreaValidacao();
      PaginaSEI::getInstance()->abrirAreaDados('');
    ?>

    <div class="row mb-3">
      <div class="col-sm-8 col-md-8 col-lg-8">
        <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Justificativa de Avaliação:</label>
        <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
          name="ajuda" <?= PaginaSEI::montarTitleTooltip('Nome da Justificativa de Avaliação que irá aparecer ao se cadastrar um Tipo de Avaliação.','Ajuda') ?>/>
        
        <input type="text" id="txtNome" name="txtNome" maxlength="50" class="infraText form-control" value="<?=PaginaSEI::tratarHTML($objMdUtlAdmTpJustRevisaoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    
      </div>
    </div>

    <div class="row">
      <div class="col-sm-8 col-md-8 col-lg-8">
        <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
        <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
          name="ajuda" <?= PaginaSEI::montarTitleTooltip('Texto que define a Justificativa de Avaliação.','Ajuda') ?>/>

        <textarea id="txaDescricao" rows="3" maxlength="250" name="txaDescricao" class="infraTextArea form-control" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objMdUtlAdmTpJustRevisaoDTO->getStrDescricao());?></textarea>
      </div>
    </div>
    
    <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>

    <input type="hidden" id="hdnIdMdUtlAdmTpJustRevisao" name="hdnIdMdUtlAdmTpJustRevisao" value="<?=$objMdUtlAdmTpJustRevisaoDTO->getNumIdMdUtlAdmTpJustRevisao();?>" />
    <input type="hidden" name="hdnIdTpCtrlUtl" id="hdnIdTpCtrlUtl" value="<?php echo $idTpCtrl ?>"/>
    
  </form>

<?php require_once 'md_utl_geral_js.php'; ?>

<script type="text/javascript">

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

  </script>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
