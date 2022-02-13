<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
 *
 * 06/07/2018 - criado por jaqueline.cast
 *
 * Vers�o do Gerador de C�digo: 1.41.0
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

  $objMdUtlAdmTpProdutoDTO = new MdUtlAdmTpProdutoDTO();

  $strDesabilitar = '';

  $idTpCtrl = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTpCtrlUtl'];
  $objTpControleUtlRN    = new MdUtlAdmTpCtrlDesempRN();
  $objTipoControleUtlDTO = $objTpControleUtlRN->buscarObjTpControlePorId($idTpCtrl);
  $nomeTpCtrl = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'md_utl_adm_tp_produto_cadastrar':
      $strTitulo = 'Novo Tipo de Produto  - '.$nomeTpCtrl;
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdUtlAdmTpProduto" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objMdUtlAdmTpProdutoDTO->setNumIdMdUtlAdmTpProduto(null);
      $objMdUtlAdmTpProdutoDTO->setStrNome($_POST['txtNome']);
      $objMdUtlAdmTpProdutoDTO->setStrDescricao($_POST['txaDescricao']);
      $objMdUtlAdmTpProdutoDTO->setStrSinAtivo('S');
      $objMdUtlAdmTpProdutoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);

      if (isset($_POST['sbmCadastrarMdUtlAdmTpProduto'])) {
        try{
          $objMdUtlAdmTpProdutoRN = new MdUtlAdmTpProdutoRN();
          $objMdUtlAdmTpProdutoDTO = $objMdUtlAdmTpProdutoRN->cadastrar($objMdUtlAdmTpProdutoDTO);
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.'&id_md_utl_adm_tp_produto='.$objMdUtlAdmTpProdutoDTO->getNumIdMdUtlAdmTpProduto().PaginaSEI::getInstance()->montarAncora($objMdUtlAdmTpProdutoDTO->getNumIdMdUtlAdmTpProduto())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_tp_produto_alterar':
      $strTitulo = 'Alterar Tipo de Produto - '.$nomeTpCtrl;
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdUtlAdmTpProduto" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_md_utl_adm_tp_produto'])) {
        $objMdUtlAdmTpProdutoDTO->setNumIdMdUtlAdmTpProduto($_GET['id_md_utl_adm_tp_produto']);
        $objMdUtlAdmTpProdutoDTO->retTodos();
        $objMdUtlAdmTpProdutoDTO->setBolExclusaoLogica(false);
        $objMdUtlAdmTpProdutoRN = new MdUtlAdmTpProdutoRN();
        $objMdUtlAdmTpProdutoDTO = $objMdUtlAdmTpProdutoRN->consultar($objMdUtlAdmTpProdutoDTO);
        if ($objMdUtlAdmTpProdutoDTO == null) {
          throw new InfraException("Registro n�o encontrado.");
        }
      } else {
        $objMdUtlAdmTpProdutoDTO->setNumIdMdUtlAdmTpProduto($_POST['hdnIdMdUtlAdmTpProduto']);
        $objMdUtlAdmTpProdutoDTO->setStrNome($_POST['txtNome']);
        $objMdUtlAdmTpProdutoDTO->setStrDescricao($_POST['txaDescricao']);
        $objMdUtlAdmTpProdutoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdUtlAdmTpProdutoDTO->getNumIdMdUtlAdmTpProduto())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarMdUtlAdmTpProduto'])) {
        try{
          $objMdUtlAdmTpProdutoRN = new MdUtlAdmTpProdutoRN();
          $objMdUtlAdmTpProdutoRN->alterar($objMdUtlAdmTpProdutoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Tipo de Produto "'.$objMdUtlAdmTpProdutoDTO->getNumIdMdUtlAdmTpProduto().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.PaginaSEI::getInstance()->montarAncora($objMdUtlAdmTpProdutoDTO->getNumIdMdUtlAdmTpProduto())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_tp_produto_consultar':
      $strTitulo = 'Consultar Tipo de Produto - '.$nomeTpCtrl;
      $arrComandos[] = '<button type="button" accesskey="C" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_tipo_controle_utl='.$idTpCtrl.'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_md_utl_adm_tp_produto'])).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
      $objMdUtlAdmTpProdutoDTO->setNumIdMdUtlAdmTpProduto($_GET['id_md_utl_adm_tp_produto']);
      $objMdUtlAdmTpProdutoDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmTpProdutoDTO->retTodos();
      $objMdUtlAdmTpProdutoRN = new MdUtlAdmTpProdutoRN();
      $objMdUtlAdmTpProdutoDTO = $objMdUtlAdmTpProdutoRN->consultar($objMdUtlAdmTpProdutoDTO);
      if ($objMdUtlAdmTpProdutoDTO===null){
        throw new InfraException("Registro n�o encontrado.");
      }
      break;

    default:
      throw new InfraException("A��o '".$_GET['acao']."' n�o reconhecida.");
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
    left: 98px;
    top: 5%;}
  #txtNome {position:absolute;left:0%;top:45%;width:40%;}

  #lblDescricao {position:absolute;left:0%;top:10%;width:60%;}
  #ancAjudaDesc {position:absolute;left:63px;top:10%;}
  #txaDescricao {position:absolute;left:0%;top:29%;width:60%;resize: none}

  .tamanhoBtnAjuda{
    width: 16px;
    height: 16px;
  }

  <?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once ('md_utl_geral_js.php');
?>
<?if(0){?><script type="text/javascript"><?}?>

    var msg11Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11); ?>'

  function inicializar(){
    if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_produto_cadastrar'){
      document.getElementById('txtNome').focus();
    } else if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_produto_consultar'){
      infraDesabilitarCamposAreaDados();
    }else{
      document.getElementById('btnCancelar').focus();
    }
    infraEfeitoTabelas(true);
  }

  function validarCadastro() {
    if (infraTrim(document.getElementById('txtNome').value)=='') {
     var msg = setMensagemPersonalizada(msg11Padrao, ['Tipo de Produto']);
     alert(msg);
      document.getElementById('txtNome').focus();
      return false;
    }

    if (infraTrim(document.getElementById('txaDescricao').value)=='') {
        var msg = setMensagemPersonalizada(msg11Padrao, ['Descri��o']);
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
  <form id="frmMdUtlAdmTpProdutoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    PaginaSEI::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Tipo de Produto:</label>
    <a href="javascript:void(0);" id="ancAjudaNome" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Nome do Tipo de Produto que ir� aparecer ao se cadastrar uma atividade.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>

    <input type="text" id="txtNome" name="txtNome" maxlength="50" class="infraText"  value="<?=PaginaSEI::tratarHTML($objMdUtlAdmTpProdutoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('9em');
    ?>
    <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelObrigatorio">Descri��o:</label>
    <a href="javascript:void(0);" id="ancAjudaDesc" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Texto que define o Tipo de Produto.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>
    <textarea type="text" id="txaDescricao" rows="3" maxlength="250" name="txaDescricao" class="infraTextArea" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objMdUtlAdmTpProdutoDTO->getStrDescricao());?></textarea>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    ?>

    <input type="hidden" id="hdnIdMdUtlAdmTpProduto" name="hdnIdMdUtlAdmTpProduto" value="<?=$objMdUtlAdmTpProdutoDTO->getNumIdMdUtlAdmTpProduto();?>" />
    <input type="hidden" name="hdnIdTpCtrlUtl" id="hdnIdTpCtrlUtl" value="<?php echo $idTpCtrl ?>"/>
    <?
    //PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
