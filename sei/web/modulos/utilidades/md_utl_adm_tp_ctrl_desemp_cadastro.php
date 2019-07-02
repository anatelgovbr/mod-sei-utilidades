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
require_once('md_utl_adm_tp_ctrl_desemp_cadastro_css.php');
// ======================= FIM CSS

PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();

//variaveis para campos de selecao multipla
$strLinkUnidadesSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_selecionar_todas&tipo_selecao=2&id_object=objLupaUnidades');
$strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_todas');

$strLinkTipoProcessosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_selecionar&tipo_selecao=2&id_object=objLupaTipoProcessos');
$strLinkAjaxTipoProcesso = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=tipo_procedimento_auto_completar');

$strLinkGestoresSelecao     = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_selecionar&tipo_selecao=2&id_object=objLupaGestores');
//$strLinkGestoresSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_selecionar&tipo_selecao=2&id_object=objLupaGestores');
$strLinkAjaxGestor = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar');

// ======================= INICIO JS
require_once('md_utl_adm_tp_ctrl_desemp_cadastro_js.php');
require_once('md_utl_geral_js.php');
// ======================= FIM JS

PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
    <form id="frmTipoControleUtilidadesCadastro" method="post" onsubmit="return onSubmitForm();" action="<?=PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao']))?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('50em');
        ?>
        <div class="bloco">
            <label id="lblNome" for="txtNome" class="infraLabelObrigatorio">Nome:</label>
            <a style="" id="btAjudaNome" <?=PaginaSEI::montarTitleTooltip('Nome indicativo do Tipo de Controle de Desempenho.')?>
               tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                <img id="imgAjudaNome" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamanhoBtnAjuda"/>
            </a>
            <div class="clear"></div>
            <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?= PaginaSEI::tratarHTML( $objTipoControleUtilidadesDTO->getStrNome() );?>" onkeypress="return infraMascaraTexto(this,event,50);"
                   maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        </div>


        <div class="bloco">
            <label id="lblDescricao" for="txtDescricao" accesskey="q" class="infraLabelObrigatorio">Descrição:</label>
            <a style="" id="btAjudaDescricao" <?=PaginaSEI::montarTitleTooltip('Breve descrição do Tipo de Controle de Desempenho.')?>
               tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                <img id="imgAjudaDescricao" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamanhoBtnAjuda"/>
            </a>
            <div class="clear"></div>
    <textarea maxlength="250" id="txaDescricao" name="txtDescricao" rows="4" class="infraTextarea" onkeypress="return infraMascaraTexto(this,event,250);"
              tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?= PaginaSEI::tratarHTML( $objTipoControleUtilidadesDTO->getStrDescricao() ) ;?></textarea>
        </div>

        <!--  GESTORES -->
        <div id="divGestores" class="bloco">

            <label id="lblGestores" for="selGestores" accesskey="" class="infraLabelObrigatorio">Gestores:</label>
            <a style="" id="btAjudaGestor" <?=PaginaSEI::montarTitleTooltip('Nome dos Usuários que serão Gestores do Controle de Desempenho.\n\n\nPara o funcionamento correto deste parâmetro, no SIP deve ser concedido o Perfil “Gestor de Controle de Desempenho” aos Usuários selecionados.')?>
               tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                <img id="imgAjudaGestor" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamanhoBtnAjuda"/>
            </a>
            <div class="clear"></div>
            <input type="text" id="txtGestor" name="txtGestor" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <select id="selGestores" name="selGestores" size="4" multiple="multiple" class="infraSelect">
                <?=$strItensSelGestores?>
            </select>

            <div id="divOpcoesGestores">
                <img id="imgLupaGestores" onclick="objLupaGestores.selecionar(700,500);" src="/infra_css/imagens/lupa.gif" alt="Selecionar Gestor" title="Selecionar Gestor" class="infraImg" />
                <img id="imgExcluirGestores" onclick="objLupaGestores.remover();" src="/infra_css/imagens/remover.gif" alt="Remover Gestor Selecionado" title="Remover Gestor Selecionado" class="infraImg" />
            </div>

            <input type="hidden" id="hdnIdGestor" name="hdnIdGestor" value="" />

        </div>


        <!--  UNIDADES ASSOCIADAS -->
        <div id="divUnidades" class="bloco">

            <label id="lblUnidades" for="selUnidades" accesskey="" class="infraLabelObrigatorio">Unidades:</label>
            <a style="" id="btAjudaGestor" <?=PaginaSEI::montarTitleTooltip('Unidades que farão parte do Controle de Desempenho.')?>
               tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                <img id="imgAjudaGestor" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="tamanhoBtnAjuda"/>
            </a>
            <div class="clear"></div>
            <input type="text" id="txtUnidade" name="txtUnidade" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <select id="selUnidades" name="selUnidades" size="4" multiple="multiple" class="infraSelect">
                <?=$strItensSelUnidades?>
            </select>
            <div id="divOpcoesUnidades">
                <img id="imgLupaUnidades" onclick="objLupaUnidades.selecionar(700,500);" src="/infra_css/imagens/lupa.gif" alt="Selecionar Unidade" title="Selecionar Unidade" class="infraImg" />
                <br>
                <img id="imgExcluirUnidades" onclick="objLupaUnidades.remover();" src="/infra_css/imagens/remover.gif" alt="Remover Unidade Selecionada" title="Remover Unidade Selecionada" class="infraImg" />
            </div>
            <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" value="" />

        </div>


            <input type="hidden" id="hdnIdTipoControleUtilidades" name="hdnIdTipoControleUtilidades" value="<?=$objTipoControleUtilidadesDTO->getNumIdMdUtlAdmTpCtrlDesemp();?>" />
            <input type="hidden" id="hdnUnidades" name="hdnUnidades" value="<?=$_POST['hdnUnidades']?>" />
            <input type="hidden" id="hdnGestores" name="hdnGestores" value="<?=$_POST['hdnGestores']?>" />
            <input type="hidden" id="hdnMotivos" name="hdnMotivos" value="<?=$_POST['hdnMotivos']?>" />

            <?
            PaginaSEI::getInstance()->fecharAreaDados();
            //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
            ?>
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>