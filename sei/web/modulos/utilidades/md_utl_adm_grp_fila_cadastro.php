<?php
/**
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
PaginaSEI::getInstance()->fecharStyle();
require_once "md_utl_geral_css.php";
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');

$strLinkAction = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao']);
?>
    <form id="frmMdUtlAdmTpJustRevisaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?php echo $strLinkAction; ?>">
        
        <?php
            PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
            //PaginaSEI::getInstance()->montarAreaValidacao();
            PaginaSEI::getInstance()->abrirAreaDados('');
        ?>           

        <div class="row mb-3">
            <div class="col-sm-10 col-md-10 col-lg-10">
                <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Grupo de Atividade:</label>
                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                    name="ajuda" <?= PaginaSEI::montarTitleTooltip('Nome do Grupo de Atividade para todas as filas cadastradas.','Ajuda') ?> />
                
                <input type="text" <?=$strDesabilitar?> id="txtNome" name="txtNome" maxlength="50" class="infraText form-control"  
                    value="<?= PaginaSEI::tratarHTML($grupoAtividade);?>" 
                    onkeypress="return infraMascaraTexto(this,event,50);" 
                    tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-10 col-md-10 col-lg-10">
                <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                    name="ajuda" <?= PaginaSEI::montarTitleTooltip('Descrição do Grupo de Atividade para todas as filas cadastradas.','Ajuda') ?> />

                <textarea <?=$strDesabilitar?> id="txaDescricao" rows="3" maxlength="250" name="txaDescricao" class="infraTextArea form-control" 
                        onkeypress="return infraMascaraTexto(this,event,250);" 
                        tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= PaginaSEI::tratarHTML($descricaoGrupoAtividade);?></textarea>
            </div>
        </div>

        <div id="divTpProcesso">                
            <?php if ( $selectMultiplo ){ ?>

                <div class="row mb-1">
                    <div class="col-xs-2 col-sm-7 col-md-7 col-lg-7">                    
                        <label id="lblFila" for="selFila" accesskey="" class="infraLabelObrigatorio">Filas:</label>
                        <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                            name="ajuda" <?= PaginaSEI::montarTitleTooltip('Selecione as Filas que serão cadastradas no Grupo de Atividades.','Ajuda') ?> />
                        
                        <input type="text" id="txtFila" name="txtFila" class="infraText form-control" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
                    </div>
                </div>    
                
                <div class="row">
                    <div class="col-sm-10 col-md-10 col-lg-10">
                        <div class="input-group">
                            <select id="selFila" name="selFila" size="4" multiple="multiple" class="infraSelect form-control">
                                <?=$strItensSelFila?>
                            </select>
                            <div id="divOpcoes" class="ml-1">
                                <img id="imgLupaFila" onclick="objLupaFila.selecionar(700,500);" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/pesquisar.svg'?>" alt="Selecionar Unidade" title="Selecionar Unidade" class="infraImg" />
                                <br>
                                <img id="imgExcluirFila" onclick="objLupaFila.remover();" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg'?>" alt="Remover Unidade Selecionada" title="Remover Unidade Selecionada" class="infraImg" />
                            </div>
                        </div>
                    </div>
                </div>

            <?php }else{ ?>

                <div class="row mb-1">
                    <div class="col-xs-2 col-sm-7 col-md-7 col-lg-7">
                        <label id="lblFila" for="selFila" accesskey="" class="infraLabelObrigatorio">Filas:</label>
                        <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                            name="ajuda" <?= PaginaSEI::montarTitleTooltip('Selecionar um ou múltiplos tipos de processos que serão tratados no tipo de controle. Se o tipo de processo estiver desabilitado, significa que ele esta em uso em outro tipo de controle com mesmo conjunto de unidades.','Ajuda') ?> />

                        <input type="hidden" id="txtFila" name="txtFila" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-10 col-md-10 col-lg-10">
                        <div class="input-group">
                            <input id="selFila" name="selFila" class="infraText form-control">
                                <?=$strItensSelFila?>
                            </input>
                            <?php if ( ! $isConsultar ) { ?>
                                <div id="divOpcoesUnica" class="ml-1">
                                    <img id="imgLupaFilaUnica" onclick="objLupaFilaUnica.selecionar(700,500);" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/pesquisar.svg'?>" alt="Selecionar Unidade" title="Selecionar Unidade" class="infraImg" />
                                    <img id="imgExcluirFilaUnica" onclick="objLupaFilaUnica.remover();" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/remover.svg'?>" alt="Remover Unidade Selecionada" title="Remover Unidade Selecionada" class="infraImg" />
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>

        <input type="hidden" id="hdnFila" name="hdnFila" value="<?=$strItensFila?>" />
        <input type="hidden" id="hdnIdFila" name="hdnIdFila" value="" />
        <input type="hidden" id="hdnIdMdAdmGrpFila" name="hdnIdMdAdmGrpFila" value="<?=$idMdAdmGrpFila?>" />
        <input type="hidden" id="hdnIdMdAdmGrp" name="hdnIdMdAdmGrp" value="<?=$idMdAdmGrp?>" />
        <input type="hidden" name="hdnIdTpCtrlUtl" id="hdnIdTpCtrlUtl" value="<?php echo $idTpCtrl ?>" />
        
    </form>

<?php
require_once 'md_utl_geral_js.php';
require_once 'md_utl_adm_grp_fila_cadastro_js.php';
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();