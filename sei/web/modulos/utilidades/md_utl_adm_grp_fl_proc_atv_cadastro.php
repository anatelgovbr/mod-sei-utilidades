<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4? REGI?O
*
* 13/09/2018 - criado por jhon.carvalho
*
* Vers?o do Gerador de C?digo: 1.41.0
*/

try {
  require_once dirname(__FILE__).'/../../SEI.php';

  session_start();
  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('md_utl_adm_grp_fl_proc_atv_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);


  $idTpCtrl    = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTpCtrlUtl'];
  $idGrpFila   = array_key_exists('id_md_utl_adm_grp_fila', $_GET) ? $_GET['id_md_utl_adm_grp_fila'] : $_POST['hdnIdMdAdmGrpFila'];

  $objTpControleUtlRN    = new MdUtlAdmTpCtrlDesempRN();
  $objTipoControleUtlDTO = $objTpControleUtlRN->buscarObjTpControlePorId($idTpCtrl);
  $idParametro           = $objTpControleUtlRN->verificaIdParametrizacao($idTpCtrl);

  $strLinkTpProcessoSelecaoUnica   = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_rel_prm_gr_proc_selecionar&tipo_selecao=1&id_object=objLupaTpProcesso&id_tipo_controle='.$idTpCtrl);
  $strLinkAtividadeSelecao         = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_atividade_selecionar&tipo_selecao=2&id_object=objLupaAtividade&id_tipo_controle_utl='.$idTpCtrl);
  $strLinkAjaxTpProcesso           = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_processo_parametrizado_auto_completar&id_parametro='.$idParametro);
  $strLinkAjaxAtividade            = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_atividade_auto_completar&id_tipo_controle_utl='.$idTpCtrl);

  $objMdUtlAdmGrpFilaRN   = new MdUtlAdmGrpFilaRN();
  $objMdUtlAdmGrpFilaDTO  = $objMdUtlAdmGrpFilaRN->buscarObjGrpFilaPorId($idGrpFila);
  $nomeGrupoAtividade     = $objMdUtlAdmGrpFilaDTO->getStrNomeGrupoAtividade();
  $nomeFila               = $objMdUtlAdmGrpFilaDTO->getStrNomeFila();

  $objMdUtlAdmGrpFlProcAtvDTO = new MdUtlAdmGrpFlProcAtvDTO();

  $strDesabilitar = '';
  $strTbGrpAtv = '';
  $isBolAlterar = false;

  $arrComandos = array();

  $objMdUtlAdmGrpFlProcAtvDTO = new MdUtlAdmGrpFilaProcDTO();
    $objMdUtlAdmGrpFlProcAtvRN = new MdUtlAdmGrpFilaProcRN();
    $objMdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmGrpFila($idGrpFila);
    $objMdUtlAdmGrpFlProcAtvDTO->retTodos(true);
    $objMdUtlAdmGrpFlProcAtvDTO->setOrdStrNomeProcedimento(InfraDTO::$TIPO_ORDENACAO_DESC);

    $objMdUtlAdmGrpFlProcAtv = $objMdUtlAdmGrpFlProcAtvRN->contar($objMdUtlAdmGrpFlProcAtvDTO);
    if($objMdUtlAdmGrpFlProcAtv > 0){

        $strTbGrpAtvOrigin = $objMdUtlAdmGrpFlProcAtvRN->listarParametros($objMdUtlAdmGrpFlProcAtvDTO);
        $strTbGrpAtv       = PaginaSEI::getInstance()->gerarItensTabelaDinamica($strTbGrpAtvOrigin);

        $isBolAlterar = true;

    }

  switch($_GET['acao']){
    case 'md_utl_adm_grp_fl_proc_atv_cadastrar':
      $strTitulo = 'Parametrizar '.$nomeGrupoAtividade.' na Fila  '.$nomeFila;
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdUtlAdmGrpFlProcAtv" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.PaginaSEI::getInstance()->montarAncora($idGrpFila)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmCadastrarMdUtlAdmGrpFlProcAtv'])) {
        try{
            if($isBolAlterar){
                $objMdUtlAdmGrpFlProcAtvRN = new MdUtlAdmGrpFilaProcRN();
                $objMdUtlAdmGrpFlProcAtvDTO = $objMdUtlAdmGrpFlProcAtvRN->alterarDadosProcAtv($strTbGrpAtvOrigin);
            }else if($_POST['hdnTbGrpAtv']!=''){
                $objMdUtlAdmGrpFlProcAtvRN = new MdUtlAdmGrpFilaProcRN();
                $objMdUtlAdmGrpFlProcAtvDTO = $objMdUtlAdmGrpFlProcAtvRN->cadastrarDadosProcAtv($_POST);
                //PaginaSEI::getInstance()->adicionarMensagem('Parametro "'.$objMdUtlAdmGrpFlProcAtvDTO->getNumIdMdUtlAdmGrpFlProcAtv().'" cadastrado com sucesso.');
            }
            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.PaginaSEI::getInstance()->montarAncora($idGrpFila)));
            die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_grp_fl_proc_atv_alterar':
      $strTitulo = 'Alterar Parametro';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMdUtlAdmGrpFlProcAtv" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_md_utl_adm_grp_fl_proc_atv'])){
        $objMdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmGrpFlProcAtv($_GET['id_md_utl_adm_grp_fl_proc_atv']);
        $objMdUtlAdmGrpFlProcAtvDTO->retTodos();
        $objMdUtlAdmGrpFlProcAtvRN = new MdUtlAdmGrpFlProcAtvRN();
        $objMdUtlAdmGrpFlProcAtvDTO = $objMdUtlAdmGrpFlProcAtvRN->consultar($objMdUtlAdmGrpFlProcAtvDTO);
        if ($objMdUtlAdmGrpFlProcAtvDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objMdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmGrpFlProcAtv($_POST['hdnIdMdUtlAdmGrpFlProcAtv']);
        $objMdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmAtividade($_POST['selMdUtlAdmAtividade']);
        $objMdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmGrpFilaProc($_POST['selMdUtlAdmGrpFilaProc']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdUtlAdmGrpFlProcAtvDTO->getNumIdMdUtlAdmGrpFlProcAtv())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarMdUtlAdmGrpFlProcAtv'])) {
        try{
          $objMdUtlAdmGrpFlProcAtvRN = new MdUtlAdmGrpFlProcAtvRN();
          $objMdUtlAdmGrpFlProcAtvRN->alterar($objMdUtlAdmGrpFlProcAtvDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Parametro "'.$objMdUtlAdmGrpFlProcAtvDTO->getNumIdMdUtlAdmGrpFlProcAtv().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMdUtlAdmGrpFlProcAtvDTO->getNumIdMdUtlAdmGrpFlProcAtv())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'md_utl_adm_grp_fl_proc_atv_consultar':
      $strTitulo = 'Consultar Parametro';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_md_utl_adm_grp_fl_proc_atv'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objMdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmGrpFlProcAtv($_GET['id_md_utl_adm_grp_fl_proc_atv']);
      $objMdUtlAdmGrpFlProcAtvDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmGrpFlProcAtvDTO->retTodos();
      $objMdUtlAdmGrpFlProcAtvRN = new MdUtlAdmGrpFlProcAtvRN();
      $objMdUtlAdmGrpFlProcAtvDTO = $objMdUtlAdmGrpFlProcAtvRN->consultar($objMdUtlAdmGrpFlProcAtvDTO);
      if ($objMdUtlAdmGrpFlProcAtvDTO===null){
        throw new InfraException("Registro n?o encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' n?o reconhecida.");
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
require_once "md_utl_geral_css.php";
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');

?>
<form id="frmMdUtlAdmGrpFlProcAtvCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">

<?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>

    <div id="divTpProcOrigem" style="display:none">
        <label id="lblTpProcOrigem" class="infraLabelObrigatorio" > </label>
        <br>
        <label class="infraLabelOpcional" id="lblOrigem" style="margin-bottom: 15px;"></label>
    </div>

<?php PaginaSEI::getInstance()->abrirAreaDados(''); ?>

    <div id="divTpProcesso" class="row mb-3">
      <div class="col-sm-8 col-md-8 col-lg-8">
        <label id="lblTpProcesso" for="selTpProcesso" accesskey="" class="infraLabelObrigatorio">Tipo Processo:</label>
        <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
            name="ajuda" <?= PaginaSEI::montarTitleTooltip('Selecione o Tipo de Processo que será cadastrado no Grupo de Atividades.','Ajuda') ?> />
        <div class="input-group">          
          <input type="text" id="txtTpProcesso" name="txtTpProcesso" class="infraText form-control" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
          <div id="divOpUnica" class="ml-1">
            <img id="imgLupaTpProcessoUnica" onclick="objLupaTpProcesso.selecionar(700,500);"  src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/pesquisar.svg'?>" alt="Selecionar Unidade" title="Selecionar Unidade" class="infraImg" />
            <img id="imgExcluirTpProcessoUnica" onclick="objLupaTpProcesso.remover();" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg'?>" alt="Remover Unidade Selecionada" title="Remover Unidade Selecionada" class="infraImg" />
          </div>
        </div>
      </div>
    </div>

    <div id="divAtividade" class="mb-3">
      <div class="row mb-1">
        <div class="col-xs-3 col-sm-7 col-md-7 col-lg-7">
          <label id="lblAtividade" for="selAtividade" accesskey="" class="infraLabelObrigatorio"> Atividades: </label>
          <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
              name="ajuda" <?= PaginaSEI::montarTitleTooltip('Selecione uma ou múltiplas Atividades que serão cadastradas no Grupo de Atividades.','Ajuda') ?> />
          <input type="text" id="txtAtividade" name="txtAtividade" class="infraText form-control" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        </div>
      </div>
      <div class="row">
        <div class="col-sm-10 col-md-10 col-lg-10">
          <div class="input-group">
            <select id="selAtividade" name="selAtividade" size="4" multiple="multiple" class="infraSelect form-control">
              <?=$strItensSelAtividade?>
            </select>
            <div id="divOpcoes" class="ml-1">
              <img id="imgLupaAtividade" onclick="objLupaAtividade.selecionar(700,500);" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/pesquisar.svg'?>" alt="Selecionar Unidade" title="Selecionar Unidade" class="infraImg" />
              <br>
              <img id="imgExcluirAtividade" onclick="objLupaAtividade.remover();" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg'?>" alt="Remover Unidade Selecionada" title="Remover Unidade Selecionada" class="infraImg" />
              <br>
              <span class="mr-3"></span>
              <button type="button" class="infraButton" id="btnAdicionar" accesskey="a" onclick="adicionarRegistro();"><span class="infraTeclaAtalho">A</span>dicionar</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <table class="infraTable" summary="GrupoAtividade" id="tbGrpAtv" style="<?= $strTbGrpAtv == '' ? 'display: none' : ''?>">
            <caption class="infraCaption">&nbsp;</caption>
            <tr>
                <th style="display: none">Id_tipo_processo</th>
                <th class="infraTh" align="center" width="50%">Tipo de Processo</th> <!--1-->
                <th class="infraTh" align="center" width="40%">Atividades</th> <!--2-->
                <th style="display: none">Id_atividade</th>
                <th style="display: none">Id_vinculo</th>
                <th class="infraTh" align="center" width="10%"  >Ações</th><!--3-->
            </tr>
        </table>
      </div>
    </div>

    <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>
  
    <input type="hidden" id="hdnAtividade"              name="hdnAtividade"                 value="<?=$strItensAtividade?>" />
    <input type="hidden" id="hdnIdAtividade"            name="hdnIdAtividade"               value="" />
    <input type="hidden" id="hdnTpProcesso"             name="hdnTpProcesso"                value="<?=$strItensAtividade?>" />
    <input type="hidden" id="hdnIdTpProcesso"           name="hdnIdTpProcesso"              value="" />
    <input type="hidden" id="hdnIdMdAdmGrpFila"         name="hdnIdMdAdmGrpFila"            value="<?=$idGrpFila?>" />
    <input type="hidden" id="hdnIdTpCtrlUtl"            name="hdnIdTpCtrlUtl"               value="<?php echo $idTpCtrl ?>" />
    <input type="hidden" id="hdnGrpAtv"                 name="hdnGrpAtv"                    value="<?=$_POST['hdnUsuario']?>" />
    <input type="hidden" id="hdnTbGrpAtv"               name="hdnTbGrpAtv"                  value="<?=$strTbGrpAtv?>" />
    <input type="hidden" id="hdnIdsRegistroRemovido"    name="hdnIdsRegistroRemovido"       value=""/>
    <input type="hidden" id="hdnIdsAtvAlterada"         name="hdnIdsAtvAlterada"            value=""/>
    <input type="hidden" id="hdnIdsAtvRemovida"         name="hdnIdsAtvRemovida"            value=""/>
</form>

<?php

require_once 'md_utl_adm_rel_prm_gr_proc_lista_js.php';
require_once ('md_utl_geral_js.php');
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
