<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 25/09/2018 - criado por jhon.carvalho
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
      $strTitulo = 'Nova Justificativa de Ajuste de Prazo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdUtlAdmJustPrazo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo(null);
      $objMdUtlAdmJustPrazoDTO->setStrNome($_POST['txtNome']);
      $objMdUtlAdmJustPrazoDTO->setStrDescricao($_POST['txaDescricao']);
      $objMdUtlAdmJustPrazoDTO->setStrSinAtivo('S');
      $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);

      $strSinDilacao     = array_key_exists('rdoDilacao', $_POST) && $_POST['rdoDilacao']== 'on'? 'S' : 'N';
      $strSinSuspensao   = array_key_exists('rdoSuspensao', $_POST) && $_POST['rdoSuspensao']== 'on'? 'S' : 'N';
      $strSinInterrupcao = array_key_exists('rdoInterrupcao', $_POST) && $_POST['rdoInterrupcao']== 'on'? 'S' : 'N';

      $objMdUtlAdmJustPrazoDTO->setStrSinDilacao($strSinDilacao);
      $objMdUtlAdmJustPrazoDTO->setStrSinSuspensao($strSinSuspensao);
      $objMdUtlAdmJustPrazoDTO->setStrSinInterrupcao($strSinInterrupcao);


      if (isset($_POST['sbmCadastrarMdUtlAdmJustPrazo'])) {
        try{
          $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
          $objMdUtlAdmJustPrazoRN->validarDuplicidade(array($_POST['txtNome'],$idTipoControle));

          $objMdUtlAdmJustPrazoDTO = $objMdUtlAdmJustPrazoRN->cadastrar($objMdUtlAdmJustPrazoDTO);


          PaginaSEI::getInstance()->adicionarMensagem('Justificativa de Ajuste de Prazo "'.$objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo().'" cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle.'&id_md_utl_adm_just_prazo='.$objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo().PaginaSEI::getInstance()->montarAncora($objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_just_prazo_alterar':
      $isAlterar = true;
      $strTitulo = 'Alterar Justificativa de Ajuste de Prazo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdUtlAdmJustPrazo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_md_utl_adm_just_prazo'])){
        $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo($_GET['id_md_utl_adm_just_prazo']);
        $objMdUtlAdmJustPrazoDTO->retTodos();
        $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
        $objMdUtlAdmJustPrazoDTO = $objMdUtlAdmJustPrazoRN->consultar($objMdUtlAdmJustPrazoDTO);
        if ($objMdUtlAdmJustPrazoDTO==null){
          throw new InfraException("Registro n�o encontrado.");
        }
      } else {
        $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo($_POST['hdnIdMdUtlAdmJustPrazo']);
        $objMdUtlAdmJustPrazoDTO->setStrNome($_POST['txtNome']);
        $objMdUtlAdmJustPrazoDTO->setStrDescricao($_POST['txaDescricao']);
        $objMdUtlAdmJustPrazoDTO->setStrSinAtivo('S');
        $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);

        $checkedDilacao = array_key_exists('rdoDilacao', $_POST) && $_POST['rdoDilacao'] == 'on' ? 'S' : 'N';
        $objMdUtlAdmJustPrazoDTO->setStrSinDilacao($checkedDilacao);

        $checkedDilacao = array_key_exists('rdoSuspensao', $_POST) && $_POST['rdoSuspensao'] == 'on' ? 'S' : 'N';
        $objMdUtlAdmJustPrazoDTO->setStrSinSuspensao($checkedDilacao);

        $checkedDilacao = array_key_exists('rdoInterrupcao', $_POST) && $_POST['rdoInterrupcao'] == 'on' ? 'S' : 'N';
        $objMdUtlAdmJustPrazoDTO->setStrSinInterrupcao($checkedDilacao);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle.PaginaSEI::getInstance()->montarAncora($objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarMdUtlAdmJustPrazo'])) {
        try{
          $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
          $objMdUtlAdmJustPrazoRN->validarDuplicidade(array($_POST['txtNome'],$idTipoControle,$_POST['hdnIdMdUtlAdmJustPrazo']));

          $strSinDilacao     = array_key_exists('rdoDilacao', $_POST) && $_POST['rdoDilacao']== 'on'? 'S' : 'N';
          $strSinSuspensao   = array_key_exists('rdoSuspensao', $_POST) && $_POST['rdoSuspensao']== 'on'? 'S' : 'N';
          $strSinInterrupcao = array_key_exists('rdoInterrupcao', $_POST) && $_POST['rdoInterrupcao']== 'on'? 'S' : 'N';

          $objMdUtlAdmJustPrazoDTO->setStrSinDilacao($strSinDilacao);
          $objMdUtlAdmJustPrazoDTO->setStrSinSuspensao($strSinSuspensao);
          $objMdUtlAdmJustPrazoDTO->setStrSinInterrupcao($strSinInterrupcao);

          $objMdUtlAdmJustPrazoRN->alterar($objMdUtlAdmJustPrazoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Justificativa de Ajuste de Prazo "'.$objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo().'" alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle.PaginaSEI::getInstance()->montarAncora($objMdUtlAdmJustPrazoDTO->getNumIdMdUtlAdmJustPrazo())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_just_prazo_consultar':
      $strTitulo = 'Consultar Justificativa de Ajuste de Prazo';
      $arrComandos[] = '<button type="button" accesskey="c" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTipoControle.PaginaSEI::getInstance()->montarAncora($_GET['id_md_utl_adm_just_prazo'])).'\';" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
      $objMdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo($_GET['id_md_utl_adm_just_prazo']);
      $objMdUtlAdmJustPrazoDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmJustPrazoDTO->retTodos();
      $objMdUtlAdmJustPrazoRN = new MdUtlAdmJustPrazoRN();
      $objMdUtlAdmJustPrazoDTO = $objMdUtlAdmJustPrazoRN->consultar($objMdUtlAdmJustPrazoDTO);

      if ($objMdUtlAdmJustPrazoDTO===null){
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
  #lblNome {position:absolute;left:0%;top:0%;width:40%;}
  #txtNome {position:absolute;left:0%;top:40%;width:40%;}

  #lblDescricao {position:absolute;left:0%;top:0%;width:50%;}
  #txaDescricao {position:absolute;left:0%;top:20%;width:50%;}

  #lblTipoSolicitacao {position:absolute;left:0%;top:0%;width:50%;}

  #ancAjudaDesc {position:absolute;left:65px;top:0%;}
  #ancAjudaNome {position: absolute;left: 77px;top: 0%;}
  #ancAjudaTipoSolicitacao {position: absolute;left: 117px;top: 0%;}

  #divDilacao{margin-top: 24px}
  #divSuspensao{margin-top: -18px}
  .campSuspensao{float: right; width: 90%;}
  #divInterrupcao{margin-top: -18px}
  .campInterrupcao{float: right; width: 78%;}
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
  $rdoDilacao     = document.getElementById('rdoDilacao').checked == false;
  $rdoSuspensao   = document.getElementById('rdoSuspensao').checked == false;
  $rdoInterrupcao = document.getElementById('rdoInterrupcao').checked == false;

  if (infraTrim(document.getElementById('txtNome').value)=='') {
     var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Justificativa']);
     alert(msg);
    document.getElementById('txtNome').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txaDescricao').value)=='') {
      var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Descri��o']);
      alert(msg);
    document.getElementById('txaDescricao').focus();
    return false;
  }

  if($rdoDilacao && $rdoSuspensao && $rdoInterrupcao){
    var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Tipo de Solicita��o']);
    alert(msg);
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
    <a href="javascript:void(0);" id="ancAjudaNome" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Texto que define a Justificativa de Ajuste de Prazo.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>

    <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objMdUtlAdmJustPrazoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('9em');
?>
  <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelObrigatorio">Descri��o:</label>
    <a href="javascript:void(0);" id="ancAjudaDesc" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Texto que define a Justificativa de Ajuste de Prazo.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>

    <textarea type="text" id="txaDescricao" rows="3" maxlength="250" name="txaDescricao" class="infraTextArea" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objMdUtlAdmJustPrazoDTO->getStrDescricao());?></textarea>

<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('9em');
?>
  <label id="lblTipoSolicitacao" for="txtTipoSolicitacao" accesskey="" class="infraLabelObrigatorio">Tipo de Solicita��o:</label>
  <a href="javascript:void(0);" id="ancAjudaTipoSolicitacao" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Op��o que define o Tipo de Solicita��o de Ajuste de Prazo.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>

  <div id="divTipoSolicitacao" class="bloco">
    <div id="divDilacao">

      <?php $checkedDilacao = $objMdUtlAdmJustPrazoDTO->getStrSinDilacao() != null && $objMdUtlAdmJustPrazoDTO->getStrSinDilacao() == 'S' ? 'checked="checked"' : ''; ?>
      <input <?php echo $checkedDilacao; ?>  type="checkbox" name="rdoDilacao" id="rdoDilacao" class="infraCheckbox"/>
      <label style="position:absolute;margin-top: 1px;" class="infraLabelChec infraLabelOpcional" for="rdoDilacao" id="lblDilacao">Dila��o</label>
    </div>
    <div id="divSuspensao" class="campSuspensao">
      <?php $checkeSuspensao = $objMdUtlAdmJustPrazoDTO->getStrSinSuspensao() != null && $objMdUtlAdmJustPrazoDTO->getStrSinSuspensao() == 'S' ? 'checked="checked"' : ''; ?>
      <input <?php echo $checkeSuspensao; ?>  type="checkbox" name="rdoSuspensao" id="rdoSuspensao" class="infraCheckbox"/>
      <label style="position:absolute;margin-top: 1px;" class="infraLabelCheckbox infraLabelOpcional" for="rdoSuspensao" id="lblSuspensao">Suspens�o</label>
    </div>
    <div id="divInterrupcao" class="campInterrupcao">
      <?php $checkeInterrupcao = $objMdUtlAdmJustPrazoDTO->getStrSinInterrupcao() != null && $objMdUtlAdmJustPrazoDTO->getStrSinInterrupcao() == 'S' ? 'checked="checked"' : ''; ?>
      <input <?php echo $checkeInterrupcao; ?>  type="checkbox" name="rdoInterrupcao" id="rdoInterrupcao" class="infraCheckbox"/>
      <label style="position:absolute;margin-top: 1px;" class="infraLabelCheckbox infraLabelOpcional" for="rdoInterrupcao" id="lblInterrupcao">Interrup��o</label>
    </div>
  </div>

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
