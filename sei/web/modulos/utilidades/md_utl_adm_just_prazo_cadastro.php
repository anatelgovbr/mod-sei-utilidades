<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/09/2018 - criado por jhon.carvalho
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

    //Id tipo de controle
    $idTipoControle = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTipoControleUtl'];

    SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('md_utl_adm_just_prazo_selecionar');

  //SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objMdUtlAdmJustPrazoDTO = new MdUtlAdmJustPrazoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'md_utl_adm_just_prazo_cadastrar':
      $strTitulo = 'Nova Justificativa de Dilação de Prazo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdUtlAdmJustPrazo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo(null);
      $objMdUtlAdmJustPrazoDTO->setStrNome($_POST['txtNome']);
      $objMdUtlAdmJustPrazoDTO->setStrDescricao($_POST['txaDescricao']);
      $objMdUtlAdmJustPrazoDTO->setStrSinAtivo('S');
      $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);


      if (isset($_POST['sbmCadastrarMdUtlAdmJustPrazo'])) {
        try{
          $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
          $objMdUtlAdmJustPrazoRN->validarDuplicidade(array($_POST['txtNome'],$idTipoControle));

          $objMdUtlAdmJustPrazoDTO = $objMdUtlAdmJustPrazoRN->cadastrar($objMdUtlAdmJustPrazoDTO);


          PaginaSEI::getInstance()->adicionarMensagem('Justificativa de Dilação de Prazo "'.$objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo().'" cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle.'&id_md_utl_adm_just_prazo='.$objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo().PaginaSEI::getInstance()->montarAncora($objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_just_prazo_alterar':
      $strTitulo = 'Alterar Justificativa de Dilação de Prazo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdUtlAdmJustPrazo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_md_utl_adm_just_prazo'])){
        $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo($_GET['id_md_utl_adm_just_prazo']);
        $objMdUtlAdmJustPrazoDTO->retTodos();
        $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
        $objMdUtlAdmJustPrazoDTO = $objMdUtlAdmJustPrazoRN->consultar($objMdUtlAdmJustPrazoDTO);
        if ($objMdUtlAdmJustPrazoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo($_POST['hdnIdMdUtlAdmJustPrazo']);
        $objMdUtlAdmJustPrazoDTO->setStrNome($_POST['txtNome']);
        $objMdUtlAdmJustPrazoDTO->setStrDescricao($_POST['txaDescricao']);
        $objMdUtlAdmJustPrazoDTO->setStrSinAtivo('S');
        $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle.PaginaSEI::getInstance()->montarAncora($objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarMdUtlAdmJustPrazo'])) {
        try{
          $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
          $objMdUtlAdmJustPrazoRN->validarDuplicidade(array($_POST['txtNome'],$idTipoControle,$_POST['hdnIdMdUtlAdmJustPrazo']));
          $objMdUtlAdmJustPrazoRN->alterar($objMdUtlAdmJustPrazoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Justificativa de Dilação de Prazo "'.$objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo().'" alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle.PaginaSEI::getInstance()->montarAncora($objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_just_prazo_consultar':
      $strTitulo = 'Consultar Justificativa de Dilação de Prazo';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle.PaginaSEI::getInstance()->montarAncora($_GET['id_md_utl_adm_just_prazo'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo($_GET['id_md_utl_adm_just_prazo']);
      $objMdUtlAdmJustPrazoDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmJustPrazoDTO->retTodos();
      $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
      $objMdUtlAdmJustPrazoDTO = $objMdUtlAdmJustPrazoRN->consultar($objMdUtlAdmJustPrazoDTO);
      if ($objMdUtlAdmJustPrazoDTO===null){
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
#lblNome {position:absolute;left:0%;top:0%;width:40%;}
#txtNome {position:absolute;left:0%;top:40%;width:40%;}

#lblDescricao {position:absolute;left:0%;top:0%;width:50%;}
#txaDescricao {position:absolute;left:0%;top:20%;width:50%;}

#ancAjudaDesc {position:absolute;left:65px;top:0%;}
    #ancAjudaNome{position: absolute;
        left: 77px;
        top: 0%;}
<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='md_utl_adm_just_prazo_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='md_utl_adm_just_prazo_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas(true);
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtNome').value)=='') {
     var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Justificativa']);
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
<form id="frmMdUtlAdmJustPrazoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('5em');
?>
  <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Justificativa:</label>
    <a href="javascript:void(0);" id="ancAjudaNome" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Texto que define a Justificativa da Dilação de Prazo.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>

    <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objMdUtlAdmJustPrazoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('9em');
?>
  <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
    <a href="javascript:void(0);" id="ancAjudaDesc" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Texto que define a Justificativa da Dilação de Prazo.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>

    <textarea type="text" id="txaDescricao" rows="3" maxlength="250" name="txaDescricao" class="infraTextArea" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objMdUtlAdmJustPrazoDTO->getStrDescricao());?></textarea>

<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="hdnIdMdUtlAdmJustPrazo" name="hdnIdMdUtlAdmJustPrazo" value="<?=$objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo();?>" />
  <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl" value="<?=$idTipoControle?>" />
    <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
