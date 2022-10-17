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
    require_once 'md_utl_adm_tp_ctrl_desemp_cadastro_acoes.php';
    // ======================= FIM ACOES PHP DA PAGINA

    //variaveis para campos de selecao multipla
    $strLinkUnidadesSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_selecionar_todas&tipo_selecao=2&id_object=objLupaUnidades');
    $strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_todas');

    $strLinkTipoProcessosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_selecionar&tipo_selecao=2&id_object=objLupaTipoProcessos');
    $strLinkAjaxTipoProcesso = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=tipo_procedimento_auto_completar');

    $strLinkGestoresSelecao     = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_selecionar&tipo_selecao=2&id_object=objLupaGestores');
    //$strLinkGestoresSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_selecionar&tipo_selecao=2&id_object=objLupaGestores');
    $strLinkAjaxGestor = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_usuario_auto_completar');

    $strItensSelTpDocumentoExtAndInt = MdUtlAdmAtividadeINT::montarSelectTipoDocumentoIntAndExt( $objTipoControleUtilidadesDTO->getNumIdSerie() );

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
require_once('md_utl_adm_tp_ctrl_desemp_cadastro_css.php');

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();

PaginaSEI::getInstance()->fecharHead();

PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
    <form id="frmTipoControleUtilidadesCadastro" method="post" onsubmit="return onSubmitForm();" action="<?=PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao']))?>">
        
        <?php 
            PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); 
            PaginaSEI::getInstance()->abrirAreaDados();
        ?>
        
        <div class="row mb-3">
            <div class="col-sm-10 col-md-10 col-lg-9">
                <label id="lblNome" for="txtNome" class="infraLabelObrigatorio">Nome:
                    <img align="top"
                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                             name="ajuda" <?= PaginaSEI::montarTitleTooltip('Nome indicativo do Tipo de Controle de Desempenho.', 'Ajuda') ?>
                             class="infraImg"/>
                </label>
                <input type="text" id="txtNome" name="txtNome" class="infraText form-control" value="<?= PaginaSEI::tratarHTML( $objTipoControleUtilidadesDTO->getStrNome() );?>" onkeypress="return infraMascaraTexto(this,event,50);"
                       maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-10 col-md-10 col-lg-9">
                <label id="lblDescricao" for="txtDescricao" accesskey="q" class="infraLabelObrigatorio">Descrição:
                    <img align="top"
                             src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                             name="ajuda" <?= PaginaSEI::montarTitleTooltip('Breve descrição do Tipo de Controle de Desempenho.', 'Ajuda') ?>
                             class="infraImg"/>
                </label>                
                <textarea maxlength="250" id="txaDescricao" name="txtDescricao" rows="4" class="infraTextarea form-control" onkeypress="return infraMascaraTexto(this,event,250);"
                          tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= PaginaSEI::tratarHTML( $objTipoControleUtilidadesDTO->getStrDescricao() )?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-10 col-md-10 col-lg-9">
                <label id="lblDescricao" for="txtDescricao" class="infraLabelObrigatorio">
                    Tipo de Documento do Plano de Trabalho:
                    <img align="top"
                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                        name="ajuda" <?= PaginaSEI::montarTitleTooltip('Tipo de Documento com Aplicabilidade Interna e Externa', 'Ajuda') ?>
                        class="infraImg"/>
                </label>
                <select name="selTpDocumento" id="selTpDocumento" class="infraSelect form-control" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                    <?= $strItensSelTpDocumentoExtAndInt ?>
                </select>
            </div>
        </div>

        <!--  GESTORES -->
        <div class="row">
            <div id="divGestores" class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
                <label id="lblGestores" for="selGestores" accesskey="" class="infraLabelObrigatorio">Gestores:
                    <img align="top" class="infraImg" name="ajuda"
                         src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" 
                          <?= PaginaSEI::montarTitleTooltip('Nome dos Usuários que serão Gestores do Controle de Desempenho.\n\n\nPara o funcionamento correto deste parâmetro, no SIP deve ser concedido o Perfil “Gestor de Controle de Desempenho” aos Usuários selecionados.','Ajuda') ?>/>
                </label>                               
                <input type="text" id="txtGestor" name="txtGestor" class="infraText form-control" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-10 col-md-10 col-lg-9">
                <div class="input-group">
                    <select id="selGestores" name="selGestores" size="8" multiple="multiple" class="infraSelect form-control <?= $strDesabilitar != '' ? '' : 'mr-1'?>">
                        <?=$strItensSelGestores?>
                    </select>

                    <div id="_divOpcoesGestores" style="<?= $strDesabilitar ?>" class="ml-1">
                        <img id="imgLupaGestores" onclick="objLupaGestores.selecionar(700,500);" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/pesquisar.svg'?>" alt="Selecionar Gestor" title="Selecionar Gestor" class="infraImg" />
                        <br>
                        <img id="imgExcluirGestores" onclick="objLupaGestores.remover();" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg'?>" alt="Remover Gestor Selecionado" title="Remover Gestor Selecionado" class="infraImg" />
                    </div>
                    <input type="hidden" id="hdnIdGestor" name="hdnIdGestor" value="" />
                </div>
            </div>
        </div>

        <!--  UNIDADES ASSOCIADAS -->
        <div class="row">
            <div id="divUnidades" class="col-xs-5 col-sm-8 col-md-8 col-lg-6">
                <label id="lblUnidades" for="selUnidades" accesskey="" class="infraLabelObrigatorio">Unidades:
                    <img align="top"
                         src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                         name="ajuda" <?= PaginaSEI::montarTitleTooltip('Unidades que farão parte do Controle de Desempenho.','Ajuda') ?>
                         class="infraImg"/>
                </label>                               
                <input type="text" id="txtUnidade" name="txtUnidade" class="infraText form-control" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
            </div>
        </div>

        <div class="row">
            <div class="col-sm-10 col-md-10 col-lg-9">
                <div class="input-group">
                    <select id="selUnidades" name="selUnidades" size="8" multiple="multiple" class="infraSelect form-control <?= $strDesabilitar != '' ? '' : 'mr-1'?>">
                        <?=$strItensSelUnidades?>
                    </select>

                    <div id="_divOpcoesUnidades" style="<?= $strDesabilitar ?>" class="ml-1">
                        <img id="imgLupaUnidades" onclick="objLupaUnidades.selecionar(700,500);" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/pesquisar.svg'?>" alt="Selecionar Unidade" title="Selecionar Unidade" class="infraImg" />
                        <br>
                        <img id="imgExcluirUnidades" onclick="objLupaUnidades.remover();" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg'?>" alt="Remover Unidade Selecionada" title="Remover Unidade Selecionada" class="infraImg" />
                    </div>
                    <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" value="" />
                </div>
            </div>
        </div>

        <input type="hidden" id="hdnIdTipoControleUtilidades" name="hdnIdTipoControleUtilidades" value="<?=$objTipoControleUtilidadesDTO->getNumIdMdUtlAdmTpCtrlDesemp();?>" />
        <input type="hidden" id="hdnUnidades" name="hdnUnidades" value="<?= $_POST['hdnUnidades'] ?>" />
        <input type="hidden" id="hdnGestores" name="hdnGestores" value="<?= $_POST['hdnGestores'] ?>" />
        <input type="hidden" id="hdnMotivos" name="hdnMotivos" value="<?= $_POST['hdnMotivos'] ?>" />

        <?php
            PaginaSEI::getInstance()->fecharAreaDados();
            //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>

<?php
require_once('md_utl_adm_tp_ctrl_desemp_cadastro_js.php');
require_once('md_utl_geral_js.php');
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>