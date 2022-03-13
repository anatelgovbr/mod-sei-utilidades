<?
/**
 * ANATEL
 *
 * 19/01/2016 - criado por marcelo.bezerra - CAST
 *
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

  // ======================= INICIO ACOES PHP DA PAGINA
  require_once 'md_utl_adm_jornada_cadastro_acoes.php';
  // ======================= FIM ACOES PHP DA PAGINA

} catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: '.PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo.' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();

// ======================= INICIO CSS
require_once('md_utl_adm_jornada_cadastro_css.php');
// ======================= FIM CSS

PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();



// ======================= INICIO JS
require_once('md_utl_adm_jornada_cadastro_js.php');
require_once('md_utl_geral_js.php');
// ======================= FIM JS

PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmTipoControleUtilidadesCadastro" method="post" onsubmit="return onSubmitForm();" action="<?=PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao']))?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->abrirAreaDados('auto');
    ?>
    
    <!--  Tipo de Controle de Desempenho -->
    <div id="divTpControleDesempenho" class="bloco">

      <label id="lblTpControleDesempenho" accesskey="" class="infraLabelObrigatorio">Tipo de Controle de Desempenho:</label>

      <a style="" id="btAjudaTpControleDesempenho" <?=PaginaSEI::montarTitleTooltip('Tipo de Controle de Desempenho da Unidade Logada.')?>
         tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <img id="imgAjudaTpControleDesempenho" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamanhoBtnAjuda"/>
      </a>

      <div class="clear"></div>
      <label id="lblTpControleLogado" class="infraLabelOpcional"><?php echo $nomeTpControle; ?></label>

    </div>

    <div class="bloco">
      <label id="lblNome" for="txtNome" class="infraLabelObrigatorio">Nome:</label>
      <a style="" id="btAjudaNome" <?=PaginaSEI::montarTitleTooltip('Indicar o Nome do Ajuste.')?>
         tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <img id="imgAjudaNome" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamanhoBtnAjuda"/>
      </a>
      <div class="clear"></div>
      <?php
      $txtNome = $isAlterar ? (array_key_exists('txtNome', $_POST) ? $_POST['txtNome'] :  $objJornadaDTO->getStrNome()) : $_POST['txtNome'];
      ?>
      <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?= PaginaSEI::tratarHTML($txtNome); ?>" onkeypress="return infraMascaraTexto(this,event,100);"
             maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    </div>


    <div class="bloco">
      <label id="lblDescricao" for="txaDescricao" accesskey="q" class="infraLabelObrigatorio">Descrição:</label>
      <a style="" id="btAjudaDescricao" <?=PaginaSEI::montarTitleTooltip('Indicar a Descrição do Ajuste.')?>
         tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <img id="imgAjudaDescricao" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamanhoBtnAjuda"/>
      </a>
      <?php
      $txtDescricao = $isAlterar ? (array_key_exists('txaDescricao', $_POST) ? $_POST['txaDescricao'] :  $objJornadaDTO->getStrDescricao()) : $_POST['txaDescricao'];
      ?>
      <div class="clear"></div>
    <textarea id="txaDescricao" name="txaDescricao" rows="4" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,250);" maxlength="250"
              tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= $txtDescricao;?></textarea>
    </div>


    <div id="divPercentualAjuste" class="bloco">
      <label class="infraLabelCheckbox infraLabelObrigatorio" for="txtPercentualAjuste" id="lblPercentualAjuste">Percentual de Ajuste:
        <img align="top" style="height:16px; width:16px;" id="imgAjuda" src="/infra_css/imagens/ajuda.gif" name="ajuda"
             onmouseover="return infraTooltipMostrar('Indique o Percentual de Ajuste de Jornada.');"
             onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImg">
      </label>



      <?php
      $txtPercentualAjuste = $isAlterar ? (array_key_exists('txtPercentualAjuste', $_POST) ? $_POST['txtPercentualAjuste'] :  $objJornadaDTO->getNumPercentualAjuste()) : $_POST['txtPercentualAjuste'];
      ?>

      <div class="clear"></div>
      <input onchange="validarValorPercentual(this)" style="margin-top: 3px;" type="text" maxlength="3" utlSomenteNumeroPaste="true" onkeypress="return infraMascaraNumero(this,event, 3);" name="txtPercentualAjuste" id="txtPercentualAjuste" value="<?= $txtPercentualAjuste;?>">

      <div class="clear"></div>

    </div>

    <div class="clear" style="height: 11px"></div>

    <div id="divInicioFim">

      <!--  Data Inicio  -->
      <label id="lblDtInicio" for="txtDtInicio" class="infraLabelObrigatorio">Início:</label>
      <!--  Data Fim  -->
      <label id="lblDtFim" for="txtDtFim" class="infraLabelObrigatorio">Fim:</label>

      <div class="clear"></div>
      <?php
      $dtInicio = $isAlterar ? (array_key_exists('txtDtInicio', $_POST) ? $_POST['txtDtInicio'] :  $objJornadaDTO->getDthInicio()) : $_POST['txtDtInicio'];
      $dtFim    = $isAlterar ? (array_key_exists('txtDtFim', $_POST) ? $_POST['txtDtFim'] :  $objJornadaDTO->getDthFim()) : $_POST['txtDtFim'];
      ?>
      <input style="width: 7%;" type="text" name="txtDtInicio" id="txtDtInicio" onchange="validarDataJornada(this);"
             value="<?= $dtInicio ?>"
             onkeypress="return infraMascara(this, event, '##/##/####');" class="infraText" />
      <img style="margin-bottom: -3px;" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/calendario.gif" id="imgDtInicio"
           title="Selecionar Data/Hora Inicial"
           alt="Selecionar Data/Hora Inicial" class="infraImg"
           onclick="infraCalendario('txtDtInicio',this,false,'<?=InfraData::getStrDataAtual()?>');" />

      <input type="text" name="txtDtFim" id="txtDtFim"
             value="<?= $dtFim  ?>"
             onchange="validarDataJornada(this);" onkeypress="return infraMascara(this, event, '##/##/####');" maxlength="16" class="infraText"/>
      <img style="margin-bottom: -3px;" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/calendario.gif" id="imgDtFim"
           title="Selecionar Data/Hora Final"
           alt="Selecionar Data/Hora Final"
           class="infraImg" onclick="infraCalendario('txtDtFim',this,false,'<?=InfraData::getStrDataAtual()?>');" />
        <a style="" id="btAjudaPeriodo" <?= PaginaSEI::montarTitleTooltip('Data de Início/Fim do Ajuste') ?>
           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
            <img id="imgAjudaPeriodo" border="0"
                 src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="tamanhoBtnAjuda"/>
        </a>
    </div>

    <!-- Tipo de Ajuste -->
    <div id="divTpAjuste">

      <label name="lblTpAjuste" id="lblTpAjuste" for="selTpAjuste" class="infraLabelObrigatorio">Tipo de Ajuste:</label>
        <a style="" id="btAjudaTpAjuste" <?= PaginaSEI::montarTitleTooltip('Indicar se o ajuste é para todos os servidores ou se é para algum servidor específico.') ?>
           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
            <img id="imgAjudaTpAjuste" border="0"
                 src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="tamanhoBtnAjuda"/>
        </a>
      <div class="clear"></div>

      <div id="divRadiosTpAjuste">
      <div id="divOptGeral" class="infraDivRadio">
        <input <?php echo $idTpAjuste == MdUtlAdmJornadaRN::$TIPO_JORNADA_GERAL ? 'checked=checked' : '' ?> type="radio" name="rdoTipoAjuste" onchange="controlarHdnTipoAjuste();" id="rdoGeral"	value="<?php echo MdUtlAdmJornadaRN::$TIPO_JORNADA_GERAL ?>" class="infraRadio" />
        <label id="lblGeral"  for="rdoGeral"  class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Geral</label>
      </div>


      <div id="divOptEspecifico" class="infraDivRadio">
        <input <?php echo $idTpAjuste == MdUtlAdmJornadaRN::$TIPO_JORNADA_ESPECIFICO ? 'checked=checked' : '' ?> type="radio" name="rdoTipoAjuste" onchange="controlarHdnTipoAjuste();" id="rdoEspecifico"	value="<?php echo MdUtlAdmJornadaRN::$TIPO_JORNADA_ESPECIFICO ?>" class="infraRadio" />
        <label id="lblEspecifico"  for="rdoEspecifico"	class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Específico</label>
      </div>
      </div>

    </div>

    <!--  Usuários Participantes -->
    <div class="bloco" style="margin-top: 0%">
<?php $esconderComponente =  is_null($objJornadaDTO) || !is_null($objJornadaDTO) && $objJornadaDTO->getStrStaTipoAjuste() == MdUtlAdmJornadaRN::$TIPO_JORNADA_GERAL;

?>

      <!-- Componente de Membros -->
      <div id="divMembros"  <?php echo $esconderComponente ? 'style="display:none"' : '' ?>>
        <label id="lblMembros" for="selMembros" accesskey="" class="infraLabelObrigatorio">Membros:</label>
        <a style="" id="btAjudaMembros" <?= PaginaSEI::montarTitleTooltip('Indicar os membros relacionados ao Ajuste de Jornada.') ?>
           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
          <img id="imgAjudaMembros" border="0"
               src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="tamanhoBtnAjuda"/>
        </a>
        <div class="clear"></div>
        <input <?php echo !$isAlterar ? 'readonly="readonly' : '' ?> type="text" onfocus="controlarTxtMembros();" onclick="controlarTxtMembros();" id="txtMembros" name="txtMembros" class="infraText attrDivMembros"
               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

        <select id="selMembros" name="selMembros" size="4" multiple="multiple"
                class="infraSelect attrDivMembros">
          <?= $strItensSelUsuarios ?>
        </select>
        <div id="divOpcoesMembros">
          <img id="imgLupaMembros" onclick="selecionarMembro();"
               src="/infra_css/imagens/lupa.gif" alt="Selecionar Membro" title="Selecionar Membro" class="infraImg attrDivMembros"/>
          <br>
          <img id="imgExcluirMembros" onclick="removerMembros();"
               src="/infra_css/imagens/remover.gif" alt="Remover Membro Selecionado"
               title="Remover Unidade Selecionada" class="infraImg attrDivMembros"/>
        </div>

      </div>
    </div>

    <?php $hdnMembrosLupa = array_key_exists('hdnMembrosLupa', $_POST) ? $_POST['hdnMembrosLupa']  : $strGridUsuariosParticipantes ?>
    <input type="hidden" id="hdnMembrosLupa" name="hdnMembrosLupa" value="<?=$_POST['hdnMembrosLupa'] ?>" />
    <input type="hidden" id="hdnMembros" name="hdnMembros" value="<?=$strGridUsuariosParticipantes?>" />
    <input type="hidden" id="hdnIdMembrosLupa" name="hdnIdMembrosLupa" value=""/>
    <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl" value="<?php echo $idTipoControle; ?>"/>
    <input type="hidden" id="hdnIdJornada" name="hdnIdJornada" value="<?php echo $idJornada; ?>"/>
    <input type="hidden" id="hdnTpAjusteEspecifico" name="hdnTpAjusteEspecifico" value="<?php echo MdUtlAdmJornadaRN::$TIPO_JORNADA_ESPECIFICO ?>"/>
    <input type="hidden" id="hdnIdTpCtrlInicialAlteracao" name="hdnIdTpCtrlInicialAlteracao" value="<?php echo !is_null($objJornadaDTO) ? $objJornadaDTO->getNumIdMdUtlAdmTpCtrlDesemp() : '' ?>"/>
    <input type="hidden" id="hdnTpAjuste" name="hdnTpAjuste" value="<?php echo $idTpAjuste ?>"/>
  
    <?php
    PaginaSEI::getInstance()->fecharAreaDados();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>