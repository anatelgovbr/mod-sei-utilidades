<?php

/**
 * @since  04/09/2018
 * @author jhon.carvalho
 */

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();
// ======================= INICIO JS
require_once('md_utl_adm_jornada_lista_acoes.php');
// ======================= FIM JS
PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');

//Include de estilos CSS
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
if (0) { ?>
    <style><?php } ?>

.bloco {
    position: relative;
    float: left;
    margin-top: 1%;
    width: 90%;
}

.clear {
    clear: both;
}

inputFila {
    width: 45% !important;
}

textarea {
    resize: none;
    width: 60%;
}

select[multiple] {
    width: 61%;
    margin-top: 0.5%;
}

img[id^="imgExcluir"] {
    margin-left: -2px;
}

div[id^="divOpcoes"] {
    position: absolute;
    width: 1%;
    left: 62%;
    top: 44%;
}

img[id^="imgAjuda"] {
    margin-bottom: -4px;
}

#divInicioFim {
    position: absolute;
    margin-top: 60px;
}

#lblDtFim {
    margin-left: 37.5%;
}

#txtDtFim {
    margin-left: 10%;
    width: 30%;
}

#divTpAjuste {
    position: absolute;
    margin-left: 34%;
    margin-top: 60px;

}

#divMembro {
    width: 50%;
    position: absolute;
    margin-left: 58%;
    margin-top: 60px;
}


<?
if (0) { ?></style><?
} ?>
<?php

PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();

// ======================= INICIO JS
require_once('md_utl_adm_jornada_lista_js.php');
// ======================= FIM JS

PaginaSEI::getInstance()->fecharJavaScript(); ?>


<?php
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmTpControleLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(
              SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
          ) ?>">

        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('13em; overflow: hidden');
        ?>
        <div id="divNome" style="width: 49%;position: absolute" class="bloco">
            <label id="lblNomeTpControle" for="txtNomeTpControle" accesskey="S" class="infraLabelOpcional">
                Nome:
            </label>

            <div class="clear"></div>

            <input type="text" id="txtNomeTpControle" style="width:60%" name="txtNomeTpControle"
                   class="inputFila infraText" size="30"
                   value="<?= $strNome ?>" maxlength="100"
                   tabindex="502"/>
        </div>

        <div id="divInicioFim" style="width:36%">

            <!--  Data Inicio  -->
            <label id="lblDtInicio" for="txtDtInicio" class="infraLabelOpcional">Início:</label>

            <!--  Data Fim  -->
            <label id="lblDtFim" for="txtDtFim" class="infraLabelOpcional">Fim:</label>

            <div class="clear"></div>

            <input style="width: 30%;" type="text" name="txtDtInicio" id="txtDtInicio"
                   onchange="validarDataJornada(this);"
                   value="<?= $strDtInicio ?>"
                   onkeypress="return infraMascara(this, event, '##/##/####');" class="infraText"/>
            <img style="margin-bottom: -3px;"
                 src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif" id="imgDtInicio"
                 title="Selecionar Data/Hora Inicial"
                 alt="Selecionar Data/Hora Inicial" class="infraImg"
                 onclick="infraCalendario('txtDtInicio',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>


            <input type="text" name="txtDtFim" id="txtDtFim"
                   value="<?= $strDtFim ?>"
                   onchange="validarDataJornada(this);" onkeypress="return infraMascara(this, event, '##/##/####');"
                   maxlength="16" class="infraText"/>
            <img style="margin-bottom: -3px;"
                 src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/calendario.gif" id="imgDtFim"
                 title="Selecionar Data/Hora Final"
                 alt="Selecionar Data/Hora Final"
                 class="infraImg"
                 onclick="infraCalendario('txtDtFim',this,false,'<?= InfraData::getStrDataAtual() ?>');"/>

        </div>

        <div id="divDescricao" style="width: 50%;position: absolute; margin-left: 34%" class="bloco">
            <label id="lblDescricaoTpControle" for="txtDescricaoTpControle" accesskey="S"
                   class="infraLabelOpcional">
                Descrição:
            </label>

            <div class="clear"></div>

            <input style="width: 87%" type="text" id="txtDescricaoTpControle" name="txtDescricaoTpControle"
                   class="inputFila infraText"
                   size="30"
                   value="<?= $strDescricao ?>" maxlength="100"
                   tabindex="502"/>
        </div>

        <div id="divTpAjuste" style="width:50%">
            <label id="lblTpAjuste" for="selTpAjuste" accesskey="" class="infraLabelOpcional">Tipo de
                Ajuste:</label>
            <select style="width:39%" id="selTpAjuste" name="selTpAjuste" class="infraSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $strTpAjuste ?>
            </select>
        </div>

        <div id="divMembro">
            <label id="lblMembro" for="selMembro" accesskey="" class="infraLabelOpcional">Membro:</label>
            <select style="width:39%" id="selMembro" name="selMembro" class="infraSelect" onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selMembros ?>
            </select>
        </div>


        <?php
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();

