<?php

/**
 * @author Jaqueline Mendes
 * @since  06/11/2018
 */

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();
$isPgPadrao      = array_key_exists('pg_padrao', $_GET) ? $_GET['pg_padrao'] : (array_key_exists('hdnIsPgPadrao', $_POST) ? $_POST['hdnIsPgPadrao'] : 0);

$isMeusProcessos = true;

if(is_null($isPgPadrao) || $isPgPadrao == 0) {
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
    $isMeusProcessos = false;
}

//Acao única
$acaoPrincipal = 'md_utl_triagem_cadastrar';

//URL Base
$strUrlPadrao = 'controlador.php?acao=' . $acaoPrincipal;

// Vars
$idProcedimento  = array_key_exists('id_procedimento', $_GET) ? $_GET['id_procedimento'] : $_POST['hdnIdProcedimento'];
$strTitulo       = 'Triagem ';

//Tipo de Controle e Procedimento
$objMdUtlAdmTpCtrlUndRN    = new MdUtlAdmRelTpCtrlDesempUndRN();
$objTriagemRN              = new MdUtlTriagemRN();
$objRegrasGerais           = new MdUtlRegrasGeraisRN();
$objMdUtlControleDsmpRN     = new MdUtlControleDsmpRN();
$objRelTriagemAtvRN        = new MdUtlRelTriagemAtvRN();
$objFilaRN                 = new MdUtlAdmFilaRN();
$objMdUtlAdmPrmGrRN        = new MdUtlAdmPrmGrRN();

$idTipoControle            =  $objMdUtlAdmTpCtrlUndRN->getTipoControleUnidadeLogada();
$objProcedimentoDTO        = $objRegrasGerais->getObjProcedimentoPorId($idProcedimento);
$idFilaAtiva               = $_GET['id_fila'];
$idTipoProcedimento        = $objProcedimentoDTO->getNumIdTipoProcedimento();
$isBuscaDados              = $_GET['acao'] == 'md_utl_triagem_alterar' || $_GET['acao'] == 'md_utl_triagem_consultar';
$idTriagem                 = null;
$objTriagemDTO             = null;

if($isBuscaDados) {
    $objControleDsmpDTO = $objMdUtlControleDsmpRN->getObjControleDsmpAtivo($idProcedimento);
    $idTriagem = $objControleDsmpDTO->getNumIdMdUtlTriagem();
    if (!is_null($idTriagem))
    {
        $objTriagemDTO = $objTriagemRN->buscarObjTriagemPorId($idTriagem);
    }
}

//Urls
$strLinkAtividadeSelecao   = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_atividade_selecionar&tipo_selecao=2&id_object=objLupaAtividade&id_tipo_controle_utl='.$idTipoControle.'&id_tipo_procedimento='.$idTipoProcedimento.'&acao_origem='.$acaoPrincipal);
$strLinkAjaxAtividade      = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_atividade_filtro_auto_completar&id_tipo_controle_utl='.$idTipoControle.'&id_tipo_procedimento='.$idTipoProcedimento);

$strLinkGrupoAtividadeSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_grp_fila_selecionar&tipo_selecao=2&id_object=objLupaGrupoAtividade&id_fila_ativa='.$idFilaAtiva.'&id_tipo_controle_utl='.$idTipoControle.'&id_tipo_procedimento='.$idTipoProcedimento);
$strLinkAjaxGrupoAtividade    = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_grp_fila_auto_completar&id_fila_ativa='.$idFilaAtiva.'&id_tipo_controle_utl='.$idTipoControle.'&id_tipo_procedimento='.$idTipoProcedimento);
$strUrlAjaxValidarGrupoAtvAtividade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_validar_grupo_atividade&id_fila_ativa='.$idFilaAtiva.'&id_tipo_controle_utl='.$idTipoControle.'&id_tipo_procedimento='.$idTipoProcedimento);


$isConsultar                  = false;
$strGridTriagem               = '';
$valorTotalUE                 = 0;
$dtaPrazoResp                 = '';
$strInformComp                = '';
$valorTotalHdn                = 0;
$hdnContadorPagina            = 0;
$selEncaminhamentoTriagem     = '';
$vlEncaminhamentoTriagem      = !is_null($objTriagemDTO) ? $objTriagemDTO->getStrStaEncaminhamentoTriagem() : null;
$vlFila                       = !is_null($objTriagemDTO) ? $objTriagemDTO->getNumIdMdUtlAdmFila() : null;

$selEncaminhamentoTriagem     = MdUtlControleDsmpINT::montarSelectEncaminhamentoAnaliseTriagem($vlEncaminhamentoTriagem);
$arrObjFilaDTO                = $objFilaRN->getFilasTipoControle($idTipoControle);
$selFila                      = MdUtlAdmFilaINT::montarSelectFilas($vlFila, $arrObjFilaDTO);
$displayEncaminhamento        = "display:none";
$displayFila                  = "display:none";

$isTpProcessoParametrizado   = $objMdUtlAdmPrmGrRN->verificaTipoProcessoParametrizado(array($idTipoProcedimento, $idTipoControle));
$isJsTpProcParametrizado     = $isTpProcessoParametrizado ? '1' : '0';

switch ($_GET['acao']) {

    //region Listar
    case $acaoPrincipal:

        $arrComandos[] = '<button type="submit" accesskey="s" id="btnSalvar" value="salvar" class="infraButton botaoSalvar">
                                    <span class="infraTeclaAtalho">S</span>alvar</button>';


        $arrComandos[] = '<button type="button" accesskey="c" id="btnFecharSelecao" value="Cancelar" onclick="fechar();" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar
                            </button>';

        if(!empty($_POST)){
            $idProcedimento = array_key_exists('hdnIdProcedimento', $_POST) ? $_POST['hdnIdProcedimento'] : null;
            if(!is_null($idProcedimento)){
                try{
                    $objTriagemRN->cadastrarDadosTriagem($_POST);

                    if($isPgPadrao == 0) {
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $idProcedimento));
                    }else{
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_meus_processos_dsmp_listar&id_procedimento=' . $idProcedimento));
                    }

                    die;

                } catch(Exception $e){
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
        }
        break;

    case 'md_utl_triagem_consultar':
        $isConsultar = true;
        $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" value="Fechar" onclick="fechar();" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                            </button>';

        if(!is_null($idTriagem)) {
            $arrObjsRel      = $objRelTriagemAtvRN->getObjsPorIdTriagem($idTriagem);
            $isSemAnalise = false;

            if (!is_null($objTriagemDTO) && (is_array($arrObjsRel) && !is_null($arrObjsRel))) {
                $arrGrid = array();
                $contador = 0;

                foreach ($arrObjsRel as $objDTO) {
                    $idMain = $contador . '_' . $objDTO->getNumIdMdUtlAdmAtividade();
                    $idPk = $objDTO->getNumIdMdUtlAdmAtividade();
                    $vlUe = $objDTO->getStrSinAnalise() == 'S' ? $objDTO->getNumUnidadeEsforco() : '';
                    $strVlAnalise = $objDTO->getStrSinAnalise() == 'S' ? 'Sim' : 'Não';
                    $isSemAnalise = $objDTO->getStrSinAnalise() == 'N';
                    $contador++;
                    $arrGrid[] = array($idMain, $idPk, $objDTO->getStrNomeAtividade(), $vlUe, $objDTO->getStrSinAnalise(), $strVlAnalise);
                    $valorTotalUE += $vlUe;
                }

                $strGridTriagem = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrGrid);

                //Prazo Resposta
                $dtaPrazoResp = $objTriagemDTO->getDthPrazoResposta();
                if (!is_null($dtaPrazoResp)) {
                    $dtaPrazoResp = explode(' ', $dtaPrazoResp);
                    $dtaPrazoResp = array_key_exists('0', $dtaPrazoResp) ? $dtaPrazoResp[0] : '';
                }

                $strInformComp = $objTriagemDTO->getStrInformacaoComplementar();

                if($isSemAnalise && $isTpProcessoParametrizado){
                    $displayEncaminhamento = '';

                    if($objTriagemDTO->getStrStaEncaminhamentoTriagem() == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA) {
                        $displayFila = '';
                    }
                }

            }
        }
        break;

    case 'md_utl_triagem_alterar':
        $strLinkIniciarRevisao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_revisao_triagem_consultar&acao_origem=' . $_GET['acao'] . '&id_procedimento=' . $idProcedimento.'&id_revisao='.$objControleDsmpDTO->getNumIdMdUtlRevisao());
        $arrObjsRel            = $objRelTriagemAtvRN->getObjsPorIdTriagem($idTriagem);

        $arrComandos[] = '<button type="button" accesskey="c" id="btnFecharSelecao" value="Cancelar" onclick="abrirModalRevisao();" class="infraButton">
                                    <span class="infraTeclaAtalho">R</span>evisão
                            </button>';

        $arrComandos[] = '<button type="submit" accesskey="s" id="btnSalvar" value="salvar" class="infraButton botaoSalvar">
                                    <span class="infraTeclaAtalho">S</span>alvar</button>';


        $arrComandos[] = '<button type="button" accesskey="c" id="btnFecharSelecao" value="Cancelar" onclick="fechar();" class="infraButton">
                                    <span class="infraTeclaAtalho">C</span>ancelar
                            </button>';


        if(!is_null($objTriagemDTO) && (is_array($arrObjsRel) && !is_null($arrObjsRel))) {
            $arrGrid = array();
            $contador = 0;
            $isSemAnalise = false;

            foreach ($arrObjsRel as $objDTO) {
                $idMain = $contador . '_' . $objDTO->getNumIdMdUtlAdmAtividade();
                $idPk = $objDTO->getNumIdMdUtlAdmAtividade();
                $vlUe = $objDTO->getStrSinAnalise() == 'S' ? $objDTO->getNumVlUndEsforcoAtv() : '';
                $vlUeHdn = $objDTO->getStrSinAnalise() == 'S' ? $objDTO->getNumVlUndEsforcoAtv() : $objDTO->getNumVlUndEsforcoRev();
                $strVlAnalise = $objDTO->getStrSinAnalise() == 'S' ? 'Sim' : 'Não';
                $contador++;
                $valorTotalUE += $vlUe;
                $valorTotalHdn += $vlUeHdn;
                $arrGrid[] = array($idMain, $idPk, $objDTO->getStrNomeAtividade(), $vlUe, $objDTO->getStrSinAnalise(), $strVlAnalise, $vlUeHdn);
                $isSemAnalise = $objDTO->getStrSinAnalise() == 'N';
            }

            $hdnContadorPagina = $contador;
            $strGridTriagem = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrGrid);

            //Prazo Resposta
            $dtaPrazoResp   = $objTriagemDTO->getDthPrazoResposta();
            if(!is_null($dtaPrazoResp)){
                $dtaPrazoResp = explode(' ', $dtaPrazoResp);
                $dtaPrazoResp = array_key_exists('0', $dtaPrazoResp) ? $dtaPrazoResp[0] : '';
            }

            $strInformComp  = $objTriagemDTO->getStrInformacaoComplementar();

            if($isSemAnalise && $isTpProcessoParametrizado){
                $displayEncaminhamento = '';

                if($objTriagemDTO->getStrStaEncaminhamentoTriagem() == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA) {
                    $displayFila = '';
                }
            }
        }

        if(!empty($_POST)){
            $idProcedimento = array_key_exists('hdnIdProcedimento', $_POST) ? $_POST['hdnIdProcedimento'] : null;
            if(!is_null($idProcedimento)){
                try{

                    //Desativar Relacionamentos
                    $objTriagemRN->desativar(array($objTriagemDTO));

                    $objRevisaoRN = new MdUtlRevisaoRN();
                    $idRevisao     = $objControleDsmpDTO->getNumIdMdUtlRevisao();
                    $objRevisaoDTO = $objRevisaoRN->buscarObjRevisaoPorId($idRevisao);
                    $objRevisaoRN->desativar(array($objRevisaoDTO));

                    $dados = $_POST;
                    $dados['isCorrecaoTriagem'] = true;
                    $objTriagemRN->cadastrarDadosTriagem($dados);

                    if($isPgPadrao == 0) {
                        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_processo_listar&id_procedimento=' . $idProcedimento));
                    }else{
                        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_meus_processos_dsmp_listar&id_procedimento=' . $idProcedimento));
                    }

                    die;

                } catch(Exception $e){
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
        }
        break;
    //region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    //endregion
}


//Botões de ação do topo



PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle(); ?>
    .clear {
    clear: both;
    }

    select[multiple] {
    width: 79%;
    margin-top: 0.5%;
    }

    div[id="divOpcoesAtividade"] {
        position: absolute;
        width: 1%;
        margin-left: 61%;
        margin-top: -5%;
    }

    div[id="divOpcoesGrupoAtividade"] {
        position: absolute;
        width: 1%;
        margin-left: 61%;
        margin-top: -5%;
    }

    #divAtividade {
    margin-top: 4%;
    }

    #divTbAtividade{
    margin-bottom: 4%;
    }

    #btnAdicionar{
    margin-left: 55.9%;
    margin-top: 2.5%;
    }

    #divEncaminhamentoTriagem{
     margin-top: -1.8%;
     margin-bottom: 2%;
     display: inline-block;
    }

    #divFila{
      display: inline-block;
    margin-left: 45px;
    }

    #divPrincipalEncaminhamento{
       width: 100%;
    }

    #selEncaminhamentoTriagem{
       width: 260px;
    }

    #selFila{
    width: 201px;
    }

    .tamanhoBtnAjuda{
    width: 16px;
    height: 16px;
    }

    #imgAjudaEncTriagem{
    position: absolute;
    margin-top: -0.4px;
    }


<?php if($isConsultar){ ?>
    #divPrazoResposta{
    width: 20%;
    margin-left: 0%;
    }
<?php }else{ ?>
    #divPrazoResposta{
    width: 20%;
    margin-left: 0%;
    margin-top: 3px;
    margin-bottom: 13px;
    }

<?php } ?>
    #imgPrazoResposta{
    width: 16px;
    height: 16px;
    margin-bottom: -2%;
    }

<?php PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once ('md_utl_triagem_cadastro_js.php');
require_once('md_utl_geral_js.php');
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form onsubmit="return onSubmitForm();" id="frmUtlTriagemCadastro" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(
              SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
          ) ?>">

        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos); ?>
        <?php PaginaSEI::getInstance()->abrirAreaDados('auto');
        ?>

        <div class="clear"></div>


        <div id="divGrupoAtividade" <?php echo $isConsultar ? 'style="display:none"' : '' ?>>
            <label id="lblGrupoAtividade" for="selGrupoAtividade" accesskey="" class="infraLabelOpcional">Grupo de Atividade:</label>
            <a id="btnGrupoAtividade" <?= PaginaSEI::montarTitleTooltip('Selecionar um ou múltiplos tipos de processos que serão tratados no tipo de controle. Se o tipo de processo estiver desabilitado, significa que ele esta em uso em outro tipo de controle com mesmo conjunto de unidades.') ?>
               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <img id="imgAjudaGrupoAtividade" border="0" style="width: 16px;height: 16px;margin-bottom: -3px;"
                     src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
            </a>
            <div class="clear"></div>
            <input type="text" style="width:60.3%" id="txtGrupoAtividade" name="txtGrupoAtividade" class="infraText"
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

            <select id="selGrupoAtividade" style="width:60.5%" name="selGrupoAtividade" size="4" multiple="multiple" class="infraSelect">
                <?= $strItensSelGrupoAtividade ?>
            </select>
            <div id="divOpcoesGrupoAtividade">
                <img id="imgLupaGrupoAtividade" onclick="abrirGrupoAtividade();" src="/infra_css/imagens/lupa.gif"
                     alt="Selecionar Unidade" title="Selecionar Unidade" class="infraImg"/>
                <br>
                <img id="imgExcluirGrupoAtividade" onclick="removerGrupoAtividade()" src="/infra_css/imagens/remover.gif"
                     alt="Remover Unidade Selecionada" title="Remover Unidade Selecionada" class="infraImg"/>
            </div>


            <input type="hidden" id="hdnGrupoAtividade" name="hdnGrupoAtividade" value=""/>
            <input type="hidden" id="hdnIdGrupoAtividade" name="hdnIdGrupoAtividade" value=""/>
        </div>

        <div id="divAtividade" <?php echo $isConsultar ? 'style="display:none"' : '' ?>>
            <label id="lblAtividade" for="selAtividade" accesskey="" class="infraLabelObrigatorio">Atividades:</label>
            <a id="btnAtividade" <?= PaginaSEI::montarTitleTooltip('Selecionar um ou múltiplos tipos de processos que serão tratados no tipo de controle. Se o tipo de processo estiver desabilitado, significa que ele esta em uso em outro tipo de controle com mesmo conjunto de unidades.') ?>
               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <img id="imgAjudaAtividade" border="0" style="width: 16px;height: 16px;margin-bottom: -3px;"
                     src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
            </a>
            <div class="clear"></div>
            <input type="text" style="width:60.3%" id="txtAtividade" name="txtAtividade" class="infraText"
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

            <select id="selAtividade" style="width:60.5%" name="selAtividade" size="4" multiple="multiple" class="infraSelect">
                <?= $strItensSelAtividade ?>
            </select>
            <div id="divOpcoesAtividade">
                <img id="imgLupaAtividade" onclick="selecionarAtividade();" src="/infra_css/imagens/lupa.gif"
                     alt="Selecionar Unidade" title="Selecionar Unidade" class="infraImg"/>
                <br>
                <img id="imgExcluirAtividade" onclick="objLupaAtividade.remover();" src="/infra_css/imagens/remover.gif"
                     alt="Remover Unidade Selecionada" title="Remover Unidade Selecionada" class="infraImg"/>
            </div>

            <input type="hidden" id="hdnAtividade" name="hdnAtividade" value=""/>
            <input type="hidden" id="hdnIdAtividade" name="hdnIdAtividade" value=""/>
            <input type="hidden" id="hdnContadorTableAtv" name="hdnContadorTableAtv" value="<?php echo $hdnContadorPagina ?>"/>

        </div>


        <div class="clear">
            <?php $disabled= $isConsultar ? 'style="display:none"' : ''; ?>
            <button <?php echo $disabled; ?> type="button" class="infraButton" id="btnAdicionar" accesskey="a" onclick="adicionarRegistroTabelaAtividade();">
                <span class="infraTeclaAtalho">A</span>dicionar
            </button>
        </div>

        <div id="divTbAtividade" style="<?php echo $strGridTriagem != '' ? '' : 'display:none' ?>">
            <table width="60.6%" class="infraTable" summary="Atividade" id="tbAtividade">
                <caption class="infraCaption">&nbsp;</caption>
                <tr>
                    <th style="display: none">id_atividade_contador</th><!--0-->
                    <th style="display: none">id_atividade</th><!--1-->
                    <th class="infraTh" align="center" width="50%">Atividade</th> <!--2-->
                    <th class="infraTh" align="center" width="35%">Valor da Atividade em U.E.</th> <!--3ComAnalise-->
                    <th style="display: none"></th><!--4-->
                    <th class="infraTh" align="center" width="20%">Com Análise? </th> <!--5-->
                    <th style="display: none">Valor em UE </th><!--4-->
                    <?php if(!$isConsultar) { ?>
                        <th class="infraTh" align="center" width="15%"  >Ações</th><!--6-->
                    <?php } ?>
                </tr>
            </table>
            <div id="divContadorTabela" style="margin-top: 12px">
                <label id="lblTltAtividade">Total de Atividade em U.E. :</label> <label id="lblVlTltAtividade"><?php echo $valorTotalUE; ?></label>
            </div>
        </div>

        <input type="hidden" name="hdnTbAtividade" id="hdnTbAtividade" utlCampoObrigatorio="a" value="<?php echo $strGridTriagem; ?>"/>

        <div id="divPrincipalEncaminhamento">
            <div id="divEncaminhamentoTriagem" style="<?php echo $displayEncaminhamento ?>">
                <label for="selEncaminhamentoTriagem" id="lblEncaminhamentoTriagem" class="infraLabelObrigatorio">Encaminhamento da Triagem:</label>
                <a style="" id="btAjudaEncTriagem" <?=PaginaSEI::montarTitleTooltip('Informe o Tipo de Encaminhamento da Triagem.')?>
                   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
                    <img class="tamanhoBtnAjuda" id="imgAjudaEncTriagem" border="0" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/>
                </a>

                <select onchange="controlarExibicaoFila(this);" id="selEncaminhamentoTriagem" class="infraSelect" name="selEncaminhamentoTriagem">
                    <?= $selEncaminhamentoTriagem; ?>
                </select>
            </div>

            <div id="divFila" style="<?php echo $displayFila ?>">
                <label for="selFila" id="lblFila" class="infraLabelObrigatorio">Fila:</label>

                <select id="selFila" name="selFila" class="infraSelect">
                    <?= $selFila; ?>
                </select>
            </div>
        </div>

        <div  id="divPrazoResposta">

            <label id="lblPrazoResposta" accesskey="" for="txtPrazoResposta" class="infraLabelOpcional"
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                Prazo para Resposta:
            </label>

            <input onchange="return validarFormatoDataTriagem(this);" type="text" id="txtPrazoResposta" name="txtPrazoResposta" onkeypress="return infraMascaraData(this, event)"
                   class="infraText" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"
                   style="width: 100px;margin-top: 1%;"
                   value="<?php echo $dtaPrazoResp ?>"/>

            <img src="/infra_css/imagens/calendario.gif" id="imgPrazoResposta" title="Selecionar Prazo para Resposta"
                 alt="Selecionar Prazo para Resposta"
                 size="10"
                 class="infraImg" onclick="infraCalendario('txtPrazoResposta',this);"
                 tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

        </div>

        <div id="divInformacaoComplementar" style="margin-top: 1.8%">
            <label id="lblInformacaoComplementar" style="display: block" for="txaInformacaoComplementar" class="infraLabelOpcional"
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                Informação Complementar:
            </label>

            <textarea style="width: 60.3%;resize: none" id="txaInformacaoComplementar" name="txaInformacaoComplementar" rows="4" class="infraTextArea" onkeypress="return infraMascaraTexto(this,event, 500);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?php echo $strInformComp ?></textarea>
        </div>

        <input type="hidden" name="hdnIdProcedimento"   id="hdnIdProcedimento" value="<?php echo $idProcedimento ?>"/>
        <input type="hidden" name="hdnIdFilaAtiva"      id="hdnIdFilaAtiva"    value="<?php echo $idFilaAtiva ?>"/>
        <input type="hidden" name="hdnIdTpCtrl"         id="hdnIdTpCtrl"       value="<?php echo $idTipoControle ?>"/>
        <input type="hidden" name="hdnUndEsforco" id="hdnUndEsforco" value="<?php echo $valorTotalHdn; ?>"/>
        <input type="hidden" name="hdnIsPossuiAnalise" id="hdnIsPossuiAnalise" value=""/>
        <input type="hidden" name="hdnStaPermiteAssociarFila" id="hdnStaPermiteAssociarFila" value="<?php echo MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA ?>"/>
        <input type="hidden" name="hdnSelFila" id="hdnSelFila" value=""/>
        <input type="hidden" name="hdnIsPgPadrao" id="hdnIsPgPadrao" value="<?php echo $isPgPadrao; ?>"/>

        <?php

        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>


    </form>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();

