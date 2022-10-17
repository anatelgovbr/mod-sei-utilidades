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

    //variaveis para campos de selecao multipla
    $strLinkUsuarioParticipante     = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_usuario_selecionar&tipo_selecao=2&id_object=objLupaUsuarioParticipante&id_tipo_controle_utl='.$idTipoControle);
    $strLinkAjaxUsuarioParticipante = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_usuario_interno_auto_completar&id_tipo_controle_utl=' . $idTipoControle);

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
PaginaSEI::getInstance()->fecharStyle();

// ======================= INICIO CSS
#require_once('md_utl_adm_fila_cadastro_css.php');
require_once('md_utl_geral_css.php');
// ======================= FIM CSS

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
    <form id="frmTipoControleUtilidadesCadastro" method="post" onsubmit="return onSubmitForm();" action="<?=PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao']))?>">
        <?php
            PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
            PaginaSEI::getInstance()->abrirAreaDados('auto');
        ?>
        
        <div class="row mb-3">
            <div class="col-sm-10 col-md-8 col-lg-8">
                <label id="lblNome" for="txtNome" class="infraLabelObrigatorio">Nome:
                    <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" name="ajuda" class="infraImg"
                        <?= PaginaSEI::montarTitleTooltip('Informar o Nome da Fila.','Ajuda') ?> />
                </label>
                <input type="text" id="txtNome" name="txtNome" class="infraText form-control" value="<?= !is_null($objFilaDTO) ? PaginaSEI::tratarHTML($objFilaDTO->getStrNome()) : $_POST['txtNome'];?>" onkeypress="return infraMascaraTexto(this,event,50);"
                    maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />    
            </div>            
        </div>

        <div class="row mb-3">
            <div class="col-sm-10 col-md-8 col-lg-8">
                <label id="lblDescricao" for="txtDescricao" accesskey="q" class="infraLabelObrigatorio">Descrição:
                    <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
                        <?= PaginaSEI::montarTitleTooltip('Informar uma descrição para a Fila.','Ajuda') ?>/>                
                </label>                
                <textarea id="txaDescricao" name="txaDescricao" rows="4" class="infraTextarea form-control" onkeypress="return infraLimitarTexto(this,event,250);" maxlength="250"
                        tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= !is_null($objFilaDTO) ? PaginaSEI::tratarHTML($objFilaDTO->getStrDescricao()) :  $_POST['txaDescricao'] ?></textarea>
            </div>
        </div>

        <div class="row mb-3" id="divEsforcoTriagem">
            <div class="col-sm-10 col-md-8 col-lg-8">
                <label id="lblTmpExecucaoTriagem" for="txtTmpExecucaoTriagem" accesskey="" class="infraLabelObrigatorio">Tempo de execução de Triagem (em minutos):
                    <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                        name="ajuda" <?= PaginaSEI::montarTitleTooltip('Tempo de execução de Triagem (em minutos) que será considerado  para usuários que fizerem a triagem nessa fila.','Ajuda') ?>/>
                </label>
                <input utlSomenteNumeroPaste="true" maxlength="6" type="text" value="<?=!is_null($objFilaDTO) ? $objFilaDTO->getNumTmpExecucaoTriagem() : $_POST['txtTmpExecucaoTriagem'] ?>" onkeypress="return infraMascaraNumero(this,event,6);" id="txtTmpExecucaoTriagem" name="txtTmpExecucaoTriagem" 
                       class="infraText form-control" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
            </div>
        </div>

        <div class="row mb-3" id="divPrazoTarefa">
            <div class="col-sm-10 col-md-8 col-lg-8">
                <label id="lblPrazoTarefa" for="txtPrazoTarefa" class="infraLabelObrigatorio">Prazo para Execução da Triagem:</label>                
                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                    name="ajuda" <?= PaginaSEI::montarTitleTooltip('Indicar o prazo em dias úteis que a Triagem deve ser concluída.','Ajuda') ?>/>
                
                <input utlSomenteNumeroPaste="true" maxlength="3" type="text" value="<?=!is_null($objFilaDTO) ? $objFilaDTO->getNumPrazoTarefa() : $_POST['txtPrazoTarefa'] ?>" onkeypress="return infraMascaraNumero(this,event,3);" id="txtPrazoTarefa" name="txtPrazoTarefa" class="infraText form-control" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
            </div>
        </div>

        <div id="divRespostaTacita" class="row mb-3">
            <div class="col-sm-10 col-md-8 col-lg-8">                                
                <label id="lblDilacao" for="selDilacao" accesskey="" class="infraLabelOpcional">Resposta Tácita para Dilação de Prazo: </label>
                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda" 
                    <?= PaginaSEI::montarTitleTooltip('Informar o Tipo de Resposta Tácita para Solicitação de Dilação de Prazo.','Ajuda') ?>/>   

                <select id="selDilacao" name="selDilacao" class="infraSelect form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?=$strItensSelRespDilacao?>
                </select>
            </div>
        </div>

        <div id="divDistribuicaoAutomatica" class="row mb-3">
            <div class="col-12 form-check">
                <?php $checkedDstAutomatica = !is_null($objFilaDTO) && $objFilaDTO->getStrSinDistribuicaoAutomatica() == 'S' || $_POST['rdoDstAutomatica'] && $_POST['rdoDstAutomatica'] == 'S' ? 'checked="checked"' : '' ?>
                <input <?php echo $checkedDstAutomatica; ?> value="S" type="checkbox" name="rdoDstAutomatica" id="rdoDstAutomatica" class="infraCheckbox form-check-input" onchange="habilitarUltimaFila(this);"/>
                <label class="infraLabelCheckbox infraLabelOpcional" for="rdoDstAutomatica" id="lblDstAuto">Distribuição Automática</label>
                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
                    <?= PaginaSEI::montarTitleTooltip('Indicar se a fila terá suas atividades distribuídas por meio de rotina ou não. Independente da distribuição automática, o Gestor do Controle pode distribuir diretamente ou o membro que participa do Controle pode assumir diretamente qualquer processo pendente de distribuição.','Ajuda') ?>/>
            </div>
            <div class="col-12 form-check">
                <?php
                    $checkedDstUltFila  = !is_null($objFilaDTO) && $objFilaDTO->getStrSinDistribuicaoUltUsuario() == 'S' || $_POST['rdoDstUltimaFila'] && $_POST['rdoDstUltimaFila'] == 'S' ? 'checked="checked"' : '' ;
                    $disabledDstUltFila = $checkedDstUltFila!= '' ? '' : 'disabled="disabled"';
                ?>
                <input <?= $checkedDstUltFila ?> value="S" <?= $disabledDstUltFila ?> type="checkbox" name="rdoDstUltimaFila" id="rdoDstUltimaFila" class="infraCheckbox form-check-input"/>
                <label class="infraLabelCheckbox infraLabelOpcional" for="rdoDstUltimaFila" id="lblDstUltimaFila">Distribuição Último Servidor</label>
                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
                    <?= PaginaSEI::montarTitleTooltip('Indicar se os processos da fila serão atribuídos automaticamente para o usuário que fez a última análise do processo na área.','Ajuda') ?>/>
            </div>
        </div>

        <div class="row rowFieldSet" id="blocoMembrosPart">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <fieldset class="infraFieldset fieldset-comum form-control">
                    <legend class="infraLegend">Membros Participantes</legend>
                    <?php if( ! $isConsultar ){ ?>
                        <div id="divUsuarioParticipante" class="row mb-3">
                            <div class="col-xs col-sm-8 col-md-8 col-lg-8 mt-2">
                                <label id="lblUsuarioParticipante" for="selUsuarioParticipante" accesskey="" class="infraLabelObrigatorio">Usuários Participantes:</label>                        
                                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
                                    <?= PaginaSEI::montarTitleTooltip('Selecionar os participantes que atuarão na Fila cadastrada.','Ajuda') ?>/>

                                <input type="text" id="txtUsuarioParticipante" name="txtUsuarioParticipante" class="infraText form-control"
                                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                            </div>
                            <div class="col-xs col-sm-10 col-md-10 col-lg-10">
                                <select id="selUsuarioParticipante" name="selUsuarioParticipante" size="7" multiple="multiple" class="infraSelect form-control">
                                    <?= $strItensSelUsuarioParticipante ?>
                                </select>
                            </div>
                            <div id="divOpcoesUsuarioParticipante" class="col-sm-1 col-md-1 col-lg-1 position-lupa-remover">
                                <img id="imgLupaUsuarioParticipante" onclick="objLupaUsuarioParticipante.selecionar(700,500);"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/pesquisar.svg'?>" alt="Selecionar Usuário Selecionado" title="Selecionar Usuário" class="infraImg"/>
                                <br>
                                <img id="imgExcluirUsuarioParticipante" onclick="objLupaUsuarioParticipante.remover();"
                                    src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg'?>" alt="Remover Usuário Selecionado"
                                    title="Remover Unidade Selecionada" class="infraImg"/>
                            </div>                    
                        </div>

                        <div id="divPapeis" class="row">
                            <div class="col-12">
                                <label id="lblPapel" name="lblPapel" class="infraLabelObrigatorio">Papel: </label>
                                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
                                    <?= PaginaSEI::montarTitleTooltip('Informar o papel do Usuário Participante nesta Fila.','Ajuda') ?>/>
                            </div>

                            <div class="col-sm-5 col-md-5 col-lg-5 mb-2">
                                <div class="form-check">
                                    <input onchange="controlarVisualizacaoPercentual();" type="checkbox" name="rdoTriador" id="rdoTriador" class="infraCheckbox checkedPapel form-check-input"/>
                                    <label style="position:absolute;margin-top: 1px;" class="infraLabelCheckbox infraLabelOpcional" for="rdoTriador" id="lblTriador">Triador</label>
                                </div>
                                
                                <div class="form-check">
                                    <input onchange="controlarVisualizacaoPercentual();" type="checkbox" name="rdoAnalista" id="rdoAnalista" class="infraCheckbox checkedPapel form-check-input"/>
                                    <label style="position:absolute;margin-top: 1px;" class="infraLabelCheckbox infraLabelOpcional" for="rdoAnalista" id="lblAnalista">Analista</label>
                                </div>
                                
                                <div class="form-check">
                                    <input type="checkbox" name="rdoRevisor" id="rdoRevisor" class="infraCheckbox checkedPapel form-check-input"/>
                                    <label style="position:absolute;margin-top: 1px;" class="infraLabelCheckbox infraLabelOpcional" for="rdoRevisor" id="lblRevisor">Avaliador</label>
                                </div>
                            </div>
                            <div id="divTipoRevisao" class="col-xs  col-sm-5 col-md-5 col-lg-5">
                                <label id="lblTipoRevisao" name="lblTipoRevisao" for="selTipoRevisao" class="infraLabelObrigatorio">Tipo de Avaliação:</label>                                
                                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg" name="ajuda"
                                    <?= PaginaSEI::montarTitleTooltip('Define se os processos do usuário irão ou não para Avaliação.','Ajuda') ?>/>

                                <select  disabled="disabled" id="selTipoRevisao" name="selTipoRevisao" class="infraSelect form-control" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    <?=$strItensSelTipoRevisao?>
                                </select>
                            </div> 
                            <!-- Btn Adicionar -->
                            <?php if ($_GET['acao'] != 'md_utl_adm_fila_consultar') { ?>
                                <div id="divBtnAdicionar" class="mt-2 col-xs col-sm-3 offset-sm-7 col-md-3 offset-md-7 col-lg-2 offset-lg-8">
                                    <button type="button" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"  accesskey="a" id="btnAdicionar" class="infraButton float-right"
                                            onclick="adicionarUsuarioParticipante();">
                                        <span class="infraTeclaAtalho">A</span>dicionar
                                    </button>
                                </div>
                            <?php } ?>          
                        </div>
                    <?php } ?>
                    <div id="divTabelaUsuarioParticipante" class="row" style="<?= $strGridUsuariosParticipantes == '' ? 'display: none' : ''?>">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="infraTable table" summary="Usuário Participante" id="tbUsuarioParticipante">
                                    <caption class="infraCaption"> <?= PaginaSEI::getInstance()->gerarCaptionTabela('Membros Participantes', 0) ?> </caption>
                                    <tr>
                                        <th class="infraTh" width="0" style="display: none;">IDVINCULO</th>
                                        <th class="infraTh" align="center" width="30%">Usuários</th>
                                        <th class="infraTh" align="center" width="13%">Triador</th>
                                        <th class="infraTh" width="0" style="display: none;">SinTriador</th>
                                        <th class="infraTh" align="center" width="13%">Analista</th>
                                        <th class="infraTh" width="0" style="display: none;">SinAnalista</th>
                                        <th class="infraTh" align="center" width="18%">Tipo de Avaliação</th>
                                        <th class="infraTh" align="center" width="13%"> Avaliador </th>
                                        <th class="infraTh" width="0" style="display: none;">SinRevisor</th>
                                        <th class="infraTh" width="0" style="display: none;">usuario sem html</th>
                                        <th class="infraTh" width="0" style="display: none;">possui vinc distr</th>
                                        <th class="infraTh" width="0" style="display: none;">id aparente</th>
                                        <?php if ( ! $isConsultar ){ ?>
                                            <th class="infraTh" align="center" width="19%"> Ações </th>
                                        <?php } ?>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <input type="hidden" id="hdnUsuarioParticipanteLupa" name="hdnUsuarioParticipanteLupa" value="<?=$_POST['hdnUsuarioParticipanteLupa']?>" />
        <input type="hidden" id="hdnUsuarioParticipante" name="hdnUsuarioParticipante" value="<?=$strGridUsuariosParticipantes?>" />
        <input type="hidden" id="hdnIdUsuarioParticipanteLupa" name="hdnIdUsuarioParticipanteLupa" value=""/>
        <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl" value="<?php echo $idTipoControle; ?>"/>
        <input type="hidden" id="hdnIdFila" name="hdnIdFila" value="<?php echo $idFila;?>"/>
        <input type="hidden" id="hdnIsAlterar" name="hdnIsAlterar" value="<?php echo $isAlterar;?>"/>

        <?php
            PaginaSEI::getInstance()->fecharAreaDados();
            PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?php

// ======================= INICIO JS
require_once('md_utl_adm_fila_cadastro_js.php');
require_once('md_utl_geral_js.php');
// ======================= FIM JS

PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>