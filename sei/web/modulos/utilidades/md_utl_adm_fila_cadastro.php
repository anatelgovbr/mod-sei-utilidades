<?
/**
 * ANATEL
 *
 * criado por jaqueline.mendes - CAST
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
    require_once 'md_utl_adm_fila_cadastro_acoes.php';
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
require_once('md_utl_adm_fila_cadastro_css.php');
// ======================= FIM CSS

PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();

//variaveis para campos de selecao multipla
$strLinkUsuarioParticipante     = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_usuario_selecionar&tipo_selecao=2&id_object=objLupaUsuarioParticipante&id_tipo_controle_utl='.$idTipoControle);
$strLinkAjaxUsuarioParticipante = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_usuario_interno_auto_completar&id_tipo_controle_utl=' . $idTipoControle);

// ======================= INICIO JS
require_once('md_utl_adm_fila_cadastro_js.php');
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
        <div class="bloco">
            <label id="lblNome" for="txtNome" class="infraLabelObrigatorio">Nome:</label>
            <a style="" id="btAjudaNome" <?=PaginaSEI::montarTitleTooltip('Informar o Nome da Fila')?>
               tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                <img class="tamanhoBtnAjuda" id="imgAjudaNome" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/>
            </a>
            <div class="clear"></div>

            <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?= !is_null($objFilaDTO) ? PaginaSEI::tratarHTML($objFilaDTO->getStrNome()) : $_POST['txtNome'];?>" onkeypress="return infraMascaraTexto(this,event,50);"
                   maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        </div>


        <div class="bloco">
            <label id="lblDescricao" for="txtDescricao" accesskey="q" class="infraLabelObrigatorio">Descrição:</label>
            <a style="" id="btAjudaDescricao" <?=PaginaSEI::montarTitleTooltip('Informar uma descrição para a Fila')?>
               tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                <img id="imgAjudaDescricao" border="0"  class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/>
            </a>
            <div class="clear"></div>
            <textarea id="txaDescricao" name="txaDescricao" rows="4" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,250);" maxlength="250"
                      tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= !is_null($objFilaDTO) ? PaginaSEI::tratarHTML($objFilaDTO->getStrDescricao()) :  $_POST['txaDescricao'] ;?></textarea>
        </div>

        <!--  EsforcoTriagem -->
        <div id="divEsforcoTriagem" class="bloco">

            <label id="lblTmpExecucaoTriagem" for="txtTmpExecucaoTriagem" accesskey="" class="infraLabelObrigatorio">Tempo de execução de Triagem (em minutos):</label>

            <a style="" id="btAjudaTmpExecucaoTriagem" <?=PaginaSEI::montarTitleTooltip('Tempo de execução de Triagem (em minutos) que será considerado  para usuários que fizerem a triagem nessa fila')?>
               tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                <img id="imgAjudaTmpExecucaoTriagem" border="0" class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/>
            </a>

            <div class="clear"></div>

            <input utlSomenteNumeroPaste="true" maxlength="6" type="text" value="<?=!is_null($objFilaDTO) ? $objFilaDTO->getNumTmpExecucaoTriagem() : $_POST['txtTmpExecucaoTriagem'] ?>" onkeypress="return infraMascaraNumero(this,event,6);" id="txtTmpExecucaoTriagem" name="txtTmpExecucaoTriagem" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

        </div>

        <!-- Prazo da Tarefa -->
        <div id="divPrazoTarefa" class="bloco">
            <label id="lblPrazoTarefa" for="txtPrazoTarefa" class="infraLabelObrigatorio">Prazo para Execução da Triagem:</label>

            <a style="" id="btAjudaPrazoTarefa" <?=PaginaSEI::montarTitleTooltip('Indicar o prazo em dias úteis que a Triagem deve ser concluída.')?>
               tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                <img id="imgAjudaPrazoTarefa" border="0" class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/>
            </a>

            <div class="clear"></div>

            <input utlSomenteNumeroPaste="true" maxlength="3" type="text" value="<?=!is_null($objFilaDTO) ? $objFilaDTO->getNumPrazoTarefa() : $_POST['txtPrazoTarefa'] ?>" onkeypress="return infraMascaraNumero(this,event,3);" id="txtPrazoTarefa" name="txtPrazoTarefa" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        </div>

        <!-- Resposta tácita para dilação de prazo-->
        <div id="divRespostaTacita" class="bloco">
            <label id="lblDilacao" for="selDilacao" accesskey="" class="infraLabelOpcional">Resposta Tácita para Dilação de Prazo: </label>
            <a id="hintDilacao" <?= PaginaSEI::montarTitleTooltip('Informar o Tipo de Resposta Tácita para Solicitação de Dilação de Prazo.') ?>
               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <img id="imgDilacao" border="0" style="width: 16px;height: 16px;"
                     src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
            </a>
            <select id="selDilacao" name="selDilacao" class="infraSelect" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?=$strItensSelRespDilacao?>
            </select>
        </div>

        <!-- Distribuição Automática -->
        <div id="divDistribuicaoAutomatica" class="bloco">
            <div style="margin-top: 8px">
                <?php $checkedDstAutomatica = !is_null($objFilaDTO) && $objFilaDTO->getStrSinDistribuicaoAutomatica() == 'S' || $_POST['rdoDstAutomatica'] && $_POST['rdoDstAutomatica'] == 'S' ? 'checked="checked"' : '' ?>
                <input <?php echo $checkedDstAutomatica; ?> value="S" type="checkbox" name="rdoDstAutomatica" id="rdoDstAutomatica" class="infraCheckbox" onchange="habilitarUltimaFila(this);"/>
                <label style="position:absolute;margin-top: 1px;" class="infraLabelCheckbox infraLabelOpcional" for="rdoDstAutomatica" id="lblDstAuto">Distribuição Automática
                    <img align="top" style="height:16px; width:16px;" id="imgAjuda" src="/infra_css/imagens/ajuda.gif" name="ajuda" onmouseover="return infraTooltipMostrar('Indicar se a fila terá suas atividades distribuídas por meio de rotina ou não. Independente da distribuição automática, o Gestor do Controle pode distribuir diretamente ou o membro que participa do Controle pode assumir diretamente qualquer processo pendente de distribuição.');" onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImg">
                </label>

            </div>

            <div class="clear" style="margin-top: 14px;"></div>

            <div style="margin-bottom: 9px">
                <?php

                $checkedDstUltFila  = !is_null($objFilaDTO) && $objFilaDTO->getStrSinDistribuicaoUltUsuario() == 'S' || $_POST['rdoDstUltimaFila'] && $_POST['rdoDstUltimaFila'] == 'S' ? 'checked="checked"' : '' ;
                $disabledDstUltFila = $checkedDstUltFila!= '' ? '' : 'disabled="disabled"';
                ?>
                <input  <?php echo $checkedDstUltFila ?>  value="S"  <?php echo $disabledDstUltFila ?> type="checkbox" name="rdoDstUltimaFila" id="rdoDstUltimaFila" class="infraCheckbox"/>
                <label style="position:absolute;margin-top: 1px;" class="infraLabelCheckbox infraLabelOpcional" for="rdoDstUltimaFila" id="lblDstUltimaFila">Distribuição Último Servidor
                    <img align="top" style="height:16px; width:16px;" id="imgAjuda" src="/infra_css/imagens/ajuda.gif" name="ajuda" onmouseover="return infraTooltipMostrar('Indicar se os processos da fila serão atribuídos automaticamente para o usuário que fez a última análise do processo na área.');" onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImg">
                </label>
                <!-- <a style="margin-left: 13.9%" id="btAjudaUltimaFila" <?/*= PaginaSEI::montarTitleTooltip('Indica se os processos da fila serão atribuídos automaticamente para o usuário que fez a última análise do processo na área.') */?>
           tabindex="<?/*= PaginaSEI::getInstance()->getProxTabDados() */?>">
          <img id="imgAjudaUltimaFila" border="0"
               class="tamanhoBtnAjuda" src="<?/*= PaginaSEI::getInstance()->getDiretorioImagensGlobal() */?>/ajuda.gif" class="infraImg"/>
        </a>-->
            </div>

        </div>

        <!--  Usuários Participantes -->
        <div id="blocoMenbrosPart" class="bloco">
            <fieldset style="width: 86%;" class="infraFieldset">
                <legend class="infraLegend">Membros Participantes</legend>

                <!-- Componente de Usuário Participante -->
                <div id="divUsuarioParticipante">
                    <label id="lblUsuarioParticipante" for="selUsuarioParticipante" accesskey="" class="infraLabelObrigatorio">Usuários
                        Participantes:</label>
                    <a style="" id="btAjudaUsuarioParticipante" <?= PaginaSEI::montarTitleTooltip('Selecionar os participantes que atuarão na Fila cadastrada.') ?>
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <img id="imgAjudaUsuarioParticipante" border="0"
                             class="tamanhoBtnAjuda" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
                    </a>
                    <div class="clear"></div>
                    <input type="text" id="txtUsuarioParticipante" name="txtUsuarioParticipante" class="infraText"
                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

                    <select id="selUsuarioParticipante" name="selUsuarioParticipante" size="4" multiple="multiple"
                            class="infraSelect">
                        <?= $strItensSelUsuarioParticipante ?>
                    </select>
                    <div id="divOpcoesUsuarioParticipante">
                        <img id="imgLupaUsuarioParticipante" onclick="objLupaUsuarioParticipante.selecionar(700,500);"
                             src="/infra_css/imagens/lupa.gif" alt="Selecionar Usuário Selecionado" title="Selecionar Usuário" class="infraImg"/>
                        <br>
                        <img id="imgExcluirUsuarioParticipante" onclick="objLupaUsuarioParticipante.remover();"
                             src="/infra_css/imagens/remover.gif" alt="Remover Usuário Selecionado"
                             title="Remover Unidade Selecionada" class="infraImg"/>
                    </div>

                </div>
                <div class="divPapeis" id="divPapeis">

                    <div style="margin-bottom: 7px;">
                        <label id="lblPapel" name="lblPapel" class="infraLabelObrigatorio">Papel: </label>
                        <a style="" id="btAjudaPapel" <?= PaginaSEI::montarTitleTooltip('Informar o papel do Usuário Participante nesta Fila.') ?>
                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                            <img id="imgAjudaPapel" border="0"
                                 class="tamanhoBtnAjuda" src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
                        </a>

                    </div>

                    <!-- Triador -->
                    <div>
                        <input onchange="controlarVisualizacaoPercentual();" type="checkbox" name="rdoTriador" id="rdoTriador" class="infraCheckbox checkedPapel"/>
                        <label style="position:absolute;margin-top: 1px;" class="infraLabelCheckbox infraLabelOpcional" for="rdoTriador" id="lblTriador">Triador</label>
                    </div>

                    <!-- Analista -->
                    <div style="margin-top: 8px">
                        <input onchange="controlarVisualizacaoPercentual();" type="checkbox" name="rdoAnalista" id="rdoAnalista" class="infraCheckbox checkedPapel"/>
                        <label style="position:absolute;margin-top: 1px;" class="infraLabelCheckbox infraLabelOpcional" for="rdoAnalista" id="lblAnalista">Analista</label>
                    </div>

                    <!-- Revisor -->
                    <div style="margin-top: 8px">
                        <input type="checkbox" name="rdoRevisor" id="rdoRevisor" class="infraCheckbox checkedPapel"/>
                        <label style="position:absolute;margin-top: 1px;" class="infraLabelCheckbox infraLabelOpcional" for="rdoRevisor" id="lblRevisor">Avaliador</label>
                    </div>
                </div>


                <!-- Tipo de Avaliação do Analista -->
                <div id="divTipoRevisao">
                    <label id="lblTipoRevisao" name="lblTipoRevisao" for="selTipoRevisao" class="infraLabelOpcional">Tipo de Avaliação:</label>
                    <a style="" id="btAjudaPercRevisao" <?=PaginaSEI::montarTitleTooltip('Define se os processos do usuário irão ou não para Avaliação.')?>
                       tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                        <img class="tamanhoBtnAjuda" id="imgAjudaPercRevisao" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/>
                    </a>
                    <select  disabled="disabled" id="selTipoRevisao" name="selTipoRevisao" class="infraSelect" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <?=$strItensSelTipoRevisao?>
                    </select>
                </div>



                <div  style="width: 100%;clear: both"></div>
                <!-- Btn Adicionar -->
                <?php if ($_GET['acao'] != 'md_utl_adm_fila_consultar') { ?>
                    <div id="divBtnAdicionar">
                        <button type="button" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                                onclick="adicionarUsuarioParticipante();" accesskey="a" id="btnAdicionar" class="infraButton"><span
                                    class="infraTeclaAtalho">A</span>dicionar
                        </button>
                    </div>
                <?php } ?>

                <div style="width: 100%"></div>

                <div id="divTabelaUsuarioParticipante" style="<?php echo $strGridUsuariosParticipantes == '' ? 'display: none' : ''?>">

                    <table width="99%" class="infraTable" summary="Usuário Participante" id="tbUsuarioParticipante">
                        <caption class="infraCaption"> <?= PaginaSEI::getInstance()->gerarCaptionTabela('Membros Participantes', 0) ?> </caption>
                        <tr>
                            <th class="infraTh" width="0" style="display: none;">IDVINCULO</th>
                            <th class="infraTh" align="center" width="40%">Usuários</th>
                            <th class="infraTh" align="center" width="10%">Triador</th>
                            <th class="infraTh" width="0" style="display: none;">SinTriador</th>
                            <th class="infraTh" align="center" width="10%">Analista</th>
                            <th class="infraTh" width="0" style="display: none;">SinAnalista</th>
                            <th class="infraTh" align="center" width="15%">Tipo de Avaliação</th>
                            <th class="infraTh" align="center" width="10%"> Avaliador </th>
                            <th class="infraTh" width="0" style="display: none;">SinRevisor</th>
                            <th class="infraTh" width="0" style="display: none;">usuario sem html</th>
                            <th class="infraTh" width="0" style="display: none;">possui vinc distr</th>
                            <th class="infraTh" width="0" style="display: none;">id aparente</th>
                            <th class="infraTh" align="center" width="15%"> Ações </th>
                        </tr>

                    </table>

                </div>

            </fieldset>
        </div>

        <input type="hidden" id="hdnUsuarioParticipanteLupa" name="hdnUsuarioParticipanteLupa" value="<?=$_POST['hdnUsuarioParticipanteLupa']?>" />
        <input type="hidden" id="hdnUsuarioParticipante" name="hdnUsuarioParticipante" value="<?=$strGridUsuariosParticipantes?>" />
        <input type="hidden" id="hdnIdUsuarioParticipanteLupa" name="hdnIdUsuarioParticipanteLupa" value=""/>
        <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl" value="<?php echo $idTipoControle; ?>"/>
        <input type="hidden" id="hdnIdFila" name="hdnIdFila" value="<?php echo $idFila;?>"/>
        <input type="hidden" id="hdnIsAlterar" name="hdnIsAlterar" value="<?php echo $isAlterar;?>"/>


        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>