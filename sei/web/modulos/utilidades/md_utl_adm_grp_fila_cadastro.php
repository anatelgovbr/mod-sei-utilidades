<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 05/09/2018
 * Time: 11:43
 */
try {
    require_once dirname(__FILE__).'/../../SEI.php';
    session_start();

    // ======================= INICIO ACOES PHP DA PAGINA
    require_once 'md_utl_adm_grp_fila_cadastro_acoes.php';
    // ======================= FIM ACOES PHP DA PAGINA

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

// ======================= INICIO CSS PHP DA PAGINA
require_once 'md_utl_adm_grp_fila_cadastro_css.php';
// ======================= FIM CSS PHP DA PAGINA

PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();

require_once 'md_utl_geral_js.php';
// ======================= INICIO JS PHP DA PAGINA
require_once 'md_utl_adm_grp_fila_cadastro_js.php';
// ======================= FIM JS PHP DA PAGINA

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
        <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Grupo de Atividade:</label>
        <a href="javascript:void(0);" id="ancAjudaNome" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Nome do Grupo de Atividade para todas as filas cadastradas.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>

        <input type="text" <?=$strDesabilitar?> id="txtNome" name="txtNome" maxlength="50" class="infraText"  value="<?= PaginaSEI::tratarHTML($grupoAtividade);?>" onkeypress="return infraMascaraTexto(this,event,50);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->abrirAreaDados('20em');
        ?>
        <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
        <a href="javascript:void(0);" id="ancAjudaDesc" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Descrição do Grupo de Atividade para todas as filas cadastradas.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>
        <textarea type="text" <?=$strDesabilitar?> id="txaDescricao" rows="3" maxlength="250" name="txaDescricao" class="infraTextArea" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= PaginaSEI::tratarHTML($descricaoGrupoAtividade);?></textarea>

        <?php if($selectMultiplo){?>

        <div id="divTpProcesso">
            <label id="lblFila" for="selFila" accesskey="" class="infraLabelObrigatorio">Filas:</label>
            <a  id="btnFila" <?= PaginaSEI::montarTitleTooltip('Selecionar um ou múltiplos tipos de processos que serão tratados no tipo de controle. Se o tipo de processo estiver desabilitado, significa que ele esta em uso em outro tipo de controle com mesmo conjunto de unidades.') ?>
                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <img id="imgAjudaFila" border="0" style="width: 16px;height: 16px;margin-bottom: -3px;"
                     src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
            </a>
            <div class="clear"></div>
            <input type="text" id="txtFila" name="txtFila" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <select id="selFila" name="selFila" size="4" multiple="multiple" class="infraSelect">
                <?=$strItensSelFila?>
            </select>
            <div id="divOpcoes">
                <img id="imgLupaFila" onclick="objLupaFila.selecionar(700,500);" src="/infra_css/imagens/lupa.gif" alt="Selecionar Unidade" title="Selecionar Unidade" class="infraImg" />
                <br>
                <img id="imgExcluirFila" onclick="objLupaFila.remover();" src="/infra_css/imagens/remover.gif" alt="Remover Unidade Selecionada" title="Remover Unidade Selecionada" class="infraImg" />
            </div>
        </div>

        <? }else {?>

        <div id="divTpProcesso">
            <label id="lblFila" for="selFila" accesskey="" class="infraLabelObrigatorio">Filas:</label>
            <a  id="btnFila" <?= PaginaSEI::montarTitleTooltip('Selecionar um ou múltiplos tipos de processos que serão tratados no tipo de controle. Se o tipo de processo estiver desabilitado, significa que ele esta em uso em outro tipo de controle com mesmo conjunto de unidades.') ?>
                tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <img id="imgAjudaFila" border="0" style="width: 16px;height: 16px;margin-bottom: -3px;"
                     src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
            </a>
            <div class="clear"></div>
            <input type="hidden" id="txtFila" name="txtFila" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
            <select id="selFila" name="selFila" size="1" multiple="true" style="width: 40%;margin-top: 0%;" class="infraSelect">
                <?=$strItensSelFila?>
            </select>
            <div id="divOpcoesUnica">
                <img id="imgLupaFilaUnica" onclick="objLupaFilaUnica.selecionar(700,500);" src="/infra_css/imagens/lupa.gif" alt="Selecionar Unidade" title="Selecionar Unidade" class="infraImg" />
                <img id="imgExcluirFilaUnica" onclick="objLupaFilaUnica.remover();" src="/infra_css/imagens/remover.gif" alt="Remover Unidade Selecionada" title="Remover Unidade Selecionada" class="infraImg" />
            </div>
        </div>

        <?
        }
        PaginaSEI::getInstance()->fecharAreaDados();
        ?>
        <input type="hidden" id="hdnFila" name="hdnFila" value="<?=$strItensFila?>" />
        <input type="hidden" id="hdnIdFila" name="hdnIdFila" value="" />
        <input type="hidden" id="hdnIdMdAdmGrpFila" name="hdnIdMdAdmGrpFila" value="<?=$idMdAdmGrpFila?>" />
        <input type="hidden" id="hdnIdMdAdmGrp" name="hdnIdMdAdmGrp" value="<?=$idMdAdmGrp?>" />
        <input type="hidden" name="hdnIdTpCtrlUtl" id="hdnIdTpCtrlUtl" value="<?php echo $idTpCtrl ?>" />
        <?
        //PaginaSEI::getInstance()->montarAreaDebug();
        //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();

