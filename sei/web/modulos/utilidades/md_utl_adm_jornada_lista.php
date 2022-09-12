<?php

/**
 * @since  04/09/2018
 * @author jhon.carvalho
 */

require_once dirname(__FILE__) . '/../../SEI.php';
require_once('md_utl_adm_jornada_lista_acoes.php');

session_start();
SessaoSEI::getInstance()->validarLink();

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');

//Include de estilos CSS
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();

require_once 'md_utl_geral_css.php';

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

?>

    <div>
        <div class="row">
            <div class="col-md-12">
                <form id="frmTpControleLista" method="post" action="<?= PaginaSEI::getInstance()->formatarXHTML( SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ) ?>">

                    <?php 
                        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
                        PaginaSEI::getInstance()->abrirAreaDados('');
                    ?>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label id="lblNomeTpControle" for="txtNomeTpControle" accesskey="S" class="infraLabelOpcional">Nome:</label>
                                <input type="text" id="txtNomeTpControle" class="inputFila infraText form-control" name="txtNomeTpControle" value="<?= $strNome ?>" maxlength="100" tabindex="502">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label id="lblDescricaoTpControle" for="txtDescricaoTpControle" accesskey="S" class="infraLabelOpcional">
                                    Descrição:
                                </label>
                                <input type="text" id="txtDescricaoTpControle" name="txtDescricaoTpControle"
                                class="form-control inputFila infraText" size="30" value="<?= $strDescricao ?>" maxlength="100" tabindex="502"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-6">
                            <div class="form-group">
                                <label id="lblDtInicio" for="txtDtInicio" class="infraLabelOpcional">Início:</label>

                                <div class="input-group">

                                    <input type="text" name="txtDtInicio" id="txtDtInicio"
                                    onchange="validarDataJornada(this);"
                                    value="<?= $strDtInicio ?>"
                                    onkeypress="return infraMascara(this, event, '##/##/####');" 
                                    class="form-control rounded infraText"/>

                                    <div class="input-group-append">
                                        <span class="input-group-text bg-white border-0 px-0 pl-1" id="inputGroupPrepend">
                                            <img style="margin-bottom: -3px;"
                                            src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg" id="imgDtInicio"
                                            title="Selecionar Data/Hora Inicial"
                                            alt="Selecionar Data/Hora Inicial" class="infraImg"
                                            onclick="infraCalendario('txtDtInicio',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-6">
                            <div class="form-group">
                                <label id="lblDtFim" for="txtDtFim" class="infraLabelOpcional">Fim:</label>

                                <div class="input-group">

                                    <input type="text" name="txtDtFim" id="txtDtFim"
                                    value="<?= $strDtFim ?>"
                                    onchange="validarDataJornada(this);" onkeypress="return infraMascara(this, event, '##/##/####');"
                                    maxlength="16"  
                                    class="form-control rounded infraText"/>

                                    <div class="input-group-append">
                                        <span class="input-group-text bg-white border-0 px-0 pl-1" id="inputGroupPrepend">
                                        <img style="margin-bottom: -3px;"
                                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/calendario.svg" id="imgDtFim"
                                        title="Selecionar Data/Hora Final"
                                        alt="Selecionar Data/Hora Final"
                                        class="infraImg"
                                        onclick="infraCalendario('txtDtFim',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-6">
                            <div class="form-group">
                                <label id="lblTpAjuste" for="selTpAjuste" accesskey="" class="infraLabelOpcional">Ajuste:</label>
                                <select id="selTpAjuste" name="selTpAjuste" class="form-control infraSelect"
                                        onchange="pesquisar();"
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    <?= $strTpAjuste ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-3 col-md-3 col-sm-3 col-6">
                            <div class="form-group">
                                <label id="lblMembro" for="selMembro" accesskey="" class="infraLabelOpcional">Membro:</label>
                                <select id="selMembro" name="selMembro" class="form-control infraSelect" onchange="pesquisar();"
                                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    <?= $selMembros ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <?php 
                        PaginaSEI::getInstance()->fecharAreaDados();
                        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
                        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
                    ?>

                </form>
            </div>
        </div>
    </div>

<?php

require_once('md_utl_adm_jornada_lista_js.php');

PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
