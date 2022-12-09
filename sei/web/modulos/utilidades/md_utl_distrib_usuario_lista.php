<?php
/**
 * Created by PhpStorm.
 * User: thamires.zamai
 * Date: 11/01/2019
 * Time: 10:57
 */

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();
$arrIdProcedimentoDistrib = array();
if (isset($_SESSION['IDS_PROCEDIMENTOS_DISTRIBUICAO'])) {
    $arrIdProcedimentoDistrib = $_SESSION['IDS_PROCEDIMENTOS_DISTRIBUICAO'];
    unset($_SESSION['IDS_PROCEDIMENTOS_DISTRIBUICAO']);
}


/**
 * @param $time
 * @return string
 */
function convertToHoursMins($time) {

    $hours = floor($time / 60);
    $minutes = ($time % 60);
    if($time == 0){
        $format = '0min';
    }else{
        if($time <  60){
            $format = sprintf('%2dmin', $minutes);
        }else{
            $format = sprintf('%2dh %2dmin', $hours, $minutes);
        }
    }

    return $format;
}

PaginaSEI::getInstance()->salvarCamposPost(array('txtProcessoUtlDist', 'txtDocumento', 'selFilaUtlDist', 'selTipoProcessoUtlDist', 'selResponsavelUtlDist', 'selStatusUtlDist', 'selAtividadeUtlDist'));

//Id tipo de controle
$objFilaRN = new MdUtlAdmFilaRN();
$objMdUtlAdmFilaPrmGrUsuRN = new MdUtlAdmFilaPrmGrUsuRN();
$objMdUtlAdmTpCtrlUsuRN = new MdUtlAdmRelTpCtrlDesempUsuRN();
$objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
$objMdUtlAdmTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
$objMdUtlRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
$objMdUtlAdmUtlTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();
$objMdUtlAdmTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
$objMdUtlHistControleRN = new MdUtlHistControleDsmpRN();

//Array que sera usado para montar os tipos de controles da unidade
$arrObjTpControle = $objMdUtlAdmTpCtrlUndRN->getArrayTipoControleUnidadeLogada();
$arrListaTpCtrl = array();
if (!is_null( $arrObjTpControle ) ){
    foreach ( $arrObjTpControle as $k => $v ) {
        $arrListaTpCtrl[$v->getNumIdMdUtlAdmTpCtrlDesemp()] = $v->getNumIdMdUtlAdmTpCtrlDesemp();
    }
}

//recupera o id tipo controle
$idTipoControle = null;

if( isset($_POST['hdnIdTipoControleUtl']) && !empty($_POST['hdnIdTipoControleUtl']) ) {
    $idTipoControle = $_POST['hdnIdTipoControleUtl'];
} else if( count( $arrListaTpCtrl ) == 1 ) {
    $idTipoControle = array_keys($arrListaTpCtrl)[0];
}

/* Configuracao para retorno da combo Responsaveis */
$idsTpCtrlBuscaResp = !is_null($idTipoControle) ? array($idTipoControle) : $arrListaTpCtrl;
$arrObjsResponsavelDTO = null;

if( !empty( $idsTpCtrlBuscaResp ) ) {

    # Retorna os ids parametrizacao geral
    $objRespMdUtlAdmTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
    $objRespMdUtlAdmTpCtrlDTO->setNumIdMdUtlAdmTpCtrlDesemp($idsTpCtrlBuscaResp, InfraDTO::$OPER_IN);
    $objRespMdUtlAdmTpCtrlDTO->retNumIdMdUtlAdmPrmGr();

    $idsPrmGrResp = InfraArray::converterArrInfraDTO($objMdUtlAdmUtlTpCtrlRN->listar($objRespMdUtlAdmTpCtrlDTO), 'IdMdUtlAdmPrmGr');

    $objRespAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
    $objRespAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();

    $objRegrasGeraisRN     = new MdUtlRegrasGeraisRN();
    $idsUsuarioUnidadeResp = $objRegrasGeraisRN->getIdsUsuariosUnidadeLogada();

    if ( !empty($idsUsuarioUnidadeResp) && !empty($idsPrmGrResp) ) {
        $objRespAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($idsPrmGrResp, InfraDTO::$OPER_IN);
        $objRespAdmPrmGrUsuDTO->setNumIdUsuario($idsUsuarioUnidadeResp,InfraDTO::$OPER_IN);
        $objRespAdmPrmGrUsuDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objRespAdmPrmGrUsuDTO->setDistinct(true);

        $objRespAdmPrmGrUsuDTO->retNumIdUsuario();
        $objRespAdmPrmGrUsuDTO->retStrNome();

        $arrObjsResponsavelDTO = $objRespAdmPrmGrUsuRN->listar($objRespAdmPrmGrUsuDTO);
    }
}

/* FIM - Configuracao para retorno da combo Responsaveis */

$txtProcessoCampo = array_key_exists('txtProcessoUtlDist', $_POST) ? $_POST['txtProcessoUtlDist'] : PaginaSEI::getInstance()->recuperarCampo('txtProcessoUtlDist');
$txtDocumentoCampo = array_key_exists('txtDocumentoUtlDist', $_POST) ? $_POST['txtDocumentoUtlDist'] : PaginaSEI::getInstance()->recuperarCampo('txtDocumentoUtlDist');
$selFilaCampo = array_key_exists('selFilaUtlDist', $_POST) ? $_POST['selFilaUtlDist'] : PaginaSEI::getInstance()->recuperarCampo('selFilaUtlDist');
$selTipoProcessoCampo = array_key_exists('selTipoProcessoUtlDist', $_POST) ? $_POST['selTipoProcessoUtlDist'] : PaginaSEI::getInstance()->recuperarCampo('selTipoProcessoUtlDist');
$selResponsavelCampo = array_key_exists('selResponsavelUtlDist', $_POST) ? $_POST['selResponsavelUtlDist'] : PaginaSEI::getInstance()->recuperarCampo('selResponsavelUtlDist');
$selStatusCampo = array_key_exists('selStatusUtlDist', $_POST) ? $_POST['selStatusUtlDist'] : PaginaSEI::getInstance()->recuperarCampo('selStatusUtlDist');
$selAtividadeCampo = array_key_exists('selAtividadeUtlDist', $_POST) ? $_POST['selAtividadeUtlDist'] : PaginaSEI::getInstance()->recuperarCampo('selAtividadeUtlDist');
// responsavel pelos dados da combo Tipo de Controle
$selTpControle = is_null($arrObjTpControle) ? array() : MdUtlAdmFilaINT::montarSelectTpControle($arrObjTpControle,'NumIdMdUtlAdmTpCtrlDesemp', 'StrNomeTipoControle',$_POST['selTpControle']);

$isGestorTpControle = false;
if( !is_null($idTipoControle)){
    if ($tpsCtrlUsuario) {
        $isGestorTpControle = in_array($idTipoControle, $tpsCtrlUsuario);
    }
}

$isPermiteAssociacao = true;
$isAvaliadorEmAlgumCtrl = false;
if(!empty($arrListaTpCtrl)){
    $arrObjsFilaDTO = $objFilaRN->getFilasTipoControle(!is_null($idTipoControle) ? $idTipoControle : $arrListaTpCtrl);
    $tpsCtrlUsuario = $objMdUtlAdmTpCtrlUsuRN->usuarioLogadoIsGestorTpControle();

    $idsFilasPermitidas = InfraArray::converterArrInfraDTO($arrObjsFilaDTO, 'IdMdUtlAdmFila');
    $arrObjsFilaUsuDTO = $objMdUtlAdmFilaPrmGrUsuRN->getPapeisDeUsuario($idsFilasPermitidas);
    $isAvaliadorEmAlgumCtrl = !is_null($arrObjsFilaUsuDTO) ? in_array( 'S' , InfraArray::converterArrInfraDTO($arrObjsFilaUsuDTO,'SinRevisor') ) : false;
    $idParametro = null;
    $idsFilasResponsavel = $selFilaCampo != '' ? array($selFilaCampo) : $idsFilasPermitidas;
    $arrObjsResponsavelDTO = !is_null($arrObjsResponsavelDTO) ? InfraArray::distinctArrInfraDTO($arrObjsResponsavelDTO, 'IdUsuario') : null;
}

$arrPostDados = array(
    'txtProcesso'     => $txtProcessoCampo,
    'txtDocumento'    => $txtDocumentoCampo,
    'selFila'         => $selFilaCampo,
    'selTipoProcesso' => $selTipoProcessoCampo,
    'selResponsavel'  => $selResponsavelCampo,
    'selStatus'       => $selStatusCampo,
    'selTpControle'   => $selTpControle,
    'telaDistrib'     => true,
    'isAvalAlgumCtrl' => $isAvaliadorEmAlgumCtrl
);

$isParametrizado = !empty($arrListaTpCtrl);
$staFrequencia   = '0';

if (!empty($idTipoControle)) {
    $objMdUtlAdmTpCtrlDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
    $objMdUtlAdmTpCtrlDTO->retStrStaFrequencia();
    $objMdUtlAdmTpCtrlDTO->setNumMaxRegistrosRetorno(1);
    $objDTOTipoControle = $objMdUtlAdmUtlTpCtrlRN->consultar($objMdUtlAdmTpCtrlDTO);

    $staFrequencia = !is_null($objDTOTipoControle) ? $objDTOTipoControle->getStrStaFrequencia() : '0';
}

if ($isParametrizado) {
    $isGestorSipSei = $objMdUtlAdmTpCtrlUsuRN->usuarioLogadoIsGestorSipSei();
    $idsStatusPermitido = $objMdUtlControleDsmpRN->getStatusPermitido($arrObjsFilaUsuDTO, ($isGestorSipSei || $isAvaliadorEmAlgumCtrl));
}

//URL Base
$strUrl = 'controlador.php?acao=md_utl_distrib_usuario_';

//URL das Actions
$strUrlPesquisar = SessaoSEI::getInstance()->assinarLink($strUrl . 'listar&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTipoControle);
$strUrlFechar    = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);
$strUrlValDistr  = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_val_distrib_multiplo');
$strUrlBuscarDadosCarga = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_buscar_dados_carga_usuario_todos_tpctrl');

$idsFilasPermitidasUsBasico = $isGestorSipSei || empty($arrObjsFilaUsuDTO) ? null : InfraArray::converterArrInfraDTO($arrObjsFilaUsuDTO, 'IdMdUtlAdmFila');

if( !is_null($idTipoControle)){
    if ($isGestorSipSei) {
        $selFila = MdUtlAdmFilaINT::montarSelectFilas($selFilaCampo, $arrObjsFilaDTO);
    } else {
        $selFila = !empty($idsFilasPermitidasUsBasico) ? $selFila = MdUtlAdmFilaINT::montarSelectFilas($selFilaCampo, $arrObjsFilaDTO, $idsFilasPermitidasUsBasico) : null;
    }
}

if ( !empty($arrObjsResponsavelDTO) ) {
    $selResponsavel = MdUtlAdmFilaPrmGrUsuINT::montarSelectResponsavel($selResponsavelCampo, $arrObjsResponsavelDTO, true);
} else {
    $selResponsavel = '';
}

$selStatus = !is_null($idsStatusPermitido) || $isGestorSipSei ? MdUtlControleDsmpINT::montarSelectStatus($selStatusCampo, false, $idsStatusPermitido) : null;
$arrObjsTpProcesso = $objMdUtlControleDsmpRN->getTiposProcessoTipoControleAssociarFila( !is_null($idTipoControle) ? array($idTipoControle => $idTipoControle) : $arrListaTpCtrl );
$selTipoProcesso = $isPermiteAssociacao ? InfraINT::montarSelectArrInfraDTO(null, null, $selTipoProcessoCampo, $arrObjsTpProcesso, 'IdTipoProcedimento', 'NomeProcedimento') : '';


$strTitulo = 'Distribuição';

switch ($_GET['acao']) {

    //region Listar
    case 'md_utl_distrib_usuario_listar':

        break;
    //region Retorno
    case 'md_utl_distrib_usuario_retornar':
        $idProcedimento = array_key_exists('id_procedimento', $_GET) ? $_GET['id_procedimento'] : null;

        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setDblIdProcedimento($idProcedimento);
        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlControleDsmpDTO->retTodos();
        $objMdUtlControleDsmpDTO->retNumDiasUteisExcedentes();
        $objMdUtlControleDsmpDTO = $objMdUtlControleDsmpRN->consultar($objMdUtlControleDsmpDTO);

        $idStatus = $objMdUtlControleDsmpDTO->getStrStaAtendimentoDsmp();

        if ($idStatus == MdUtlControleDsmpRN::$INTERROMPIDO || $idStatus == MdUtlControleDsmpRN::$SUSPENSO) {
            $objMdUtlControleRN = new MdUtlControleDsmpRN();
            $objMdUtlControleRN->retornaStatusImpedido(array($objMdUtlControleDsmpDTO));
        }

        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_distrib_usuario_listar&acao_origem=' . $_GET['acao']));

        break;
    //endregion

    //region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    //endregion
}

//Verifica se é ação Selecionar
$bolSelecionar = $_GET['acao'] == 'md_utl_adm_fila_selecionar';

$arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" onclick="pesquisar()" class="infraButton">
                                        <span class="infraTeclaAtalho">P</span>esquisar</button>';

if ( $isPermiteAssociacao ) {
    $strLinkDistribuirGeral = '';
    if ( !empty($idTipoControle) ) {
        $strLinkDistribuirGeral = SessaoSEI::getInstance()->assinarLink($strUrl . 'cadastrar&acao_origem=' . $_GET['acao'] . '&id_tp_controle_desmp=' . $idTipoControle . '&acao_retorno=' . $_GET['acao']);
    }
    $arrComandos[] = '<button type="button" accesskey="i" id="btnAssoFila" onclick="distribuir(true, false, false, false, \''.$staFrequencia .'\', \''.$strLinkDistribuirGeral.'\' )" class="infraButton">
                                        D<span class="infraTeclaAtalho">i</span>stribuir</button>';

    $staFrequencia = 0;
}

$arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                        Fe<span class="infraTeclaAtalho">c</span>har</button>';
$numRegistros = 0;
if ($isParametrizado) {
    $objDTOCombo = $objMdUtlControleDsmpRN->getObjDTOParametrizadoDistrib(array($arrObjsFilaUsuDTO, $isGestorSipSei, !empty($idTipoControle) ? array($idTipoControle) : $arrListaTpCtrl , $arrPostDados));

    //Configuração da Paginação
    if ((empty($arrObjsFilaDTO) && !$isGestorSipSei) || !$isPermiteAssociacao) {
        $objDTO = null;
    } else {
        $objDTO = $objMdUtlControleDsmpRN->getObjDTOParametrizadoDistrib(array($arrObjsFilaUsuDTO, $isGestorSipSei, !empty($idTipoControle) ? array($idTipoControle) : $arrListaTpCtrl, $arrPostDados));
    }

    if (!is_null($objDTO)) {
        $objDTO->retNumIdMdUtlAjustePrazo();
        $objDTO->retNumIdMdUtlContestRevisao();
        $objDTO->retNumIdMdUtlAdmRelControleDsmp();
        $objDTO->retNumIdMdUtlControleDsmp();
        $objDTO->retNumIdUnidade();
        $objDTO->retStrNomeTipoProcedimento();
        $objDTO->retStrStaAtendimentoDsmp();
        $objDTO->retStrSiglaUnidade();
        $objDTO->retStrProtocoloProcedimentoFormatado();
        $objDTO->retStrNomeFila();
        $objDTO->retNumIdFila();
        $objDTO->retNumTempoExecucaoAtribuido();
        $objDTO->retNumTempoExecucao();
        $objDTO->retStrNomeUsuarioDistribuicao();
        $objDTO->retNumIdUsuarioDistribuicao();
        $objDTO->retDthAtual();
        $objDTO->retStrSiglaUsuarioDistribuicao();
        $objDTO->retNumIdMdUtlAnalise();
        $objDTO->retNumIdMdUtlTriagem();
        $objDTO->retDthPrazoTarefa();
        $objDTO->retStrStaSolicitacaoAjustePrazo();
        $objDTO->retStrNomeTpCtrlDsmp();
        $objDTO->retNumIdMdUtlAdmTpCtrlDesemp();

        if ($selAtividadeCampo != '') {
            $objDTO->setStrValorAtividadeSelectUtl($selAtividadeCampo);
            $idsTriagem = $objMdUtlControleDsmpRN->pesquisarAtividade($objDTO);

            if (!empty($idsTriagem)) {
                $objDTO->setNumIdMdUtlTriagem($idsTriagem, InfraDTO::$OPER_IN);
            } else {
                $objDTO = null;
            }
        }
        $objMdUtlTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();
        //$isDadosParametrizados = $objMdUtlTpCtrlRN->validaNovosDadosParametrizacao($idTipoControle);
    }
    $count = 0;
    //Combo de Atividade
    if (!is_null($objDTOCombo)) {
        $objDTOCombo->retNumIdMdUtlTriagem();
        $arrObjsCombo = $objMdUtlControleDsmpRN->listarProcessos($objDTOCombo);

        $idTriagemCombo = InfraArray::converterArrInfraDTO($arrObjsCombo, 'IdMdUtlTriagem');
        $idTriagemCombo = MdUtlControleDsmpINT::removeNullsTriagem($idTriagemCombo);

        $arrayObjs = [];
        $count = !is_null($idTriagemCombo) ? count($idTriagemCombo) : 0;
    }

    if ($count > 0) {

        $arrObjsTriagemAtividade = $objMdUtlRelTriagemAtvRN->getObjsTriagemAtividade($idTriagemCombo);
        $selAtividade = MdUtlAdmAtividadeINT::montarSelectAtividadesTriagem($selAtividadeCampo, $arrObjsTriagemAtividade);

        foreach ($arrObjsTriagemAtividade as $obj) {
            if (array_key_exists($obj->getNumIdMdUtlTriagem(), $arrayObjs)) {
                $arrayObjs[$obj->getNumIdMdUtlTriagem()] = array();
            } else {
                $arrayObjs[$obj->getNumIdMdUtlTriagem()] = $obj->getStrNomeAtividade(); //. ' (' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$obj->getNumComplexidadeAtividade()] . ')';
            }
        }

    } else {
        $selAtividade = '';
    }

    //Fim da Combo de Atividade

    if (!is_null($objDTO)) {

        PaginaSEI::getInstance()->prepararOrdenacao($objDTO, 'ProtocoloProcedimentoFormatado', InfraDTO::$TIPO_ORDENACAO_ASC);
        PaginaSEI::getInstance()->prepararPaginacao($objDTO, 200);

        $arrObjs = $objMdUtlControleDsmpRN->listarProcessos($objDTO);
        MdUtlControleDsmpINT::setNomeAtividade($arrObjs, $arrayObjs);
        $numRegistros = !is_null($arrObjs) ? count($arrObjs) : 0;

        PaginaSEI::getInstance()->processarPaginacao($objDTO);

        if ($numRegistros > 0) {

            //Tabela de resultado.
            $displayNoneCheck = $isPermiteAssociacao ? '' : 'style="display:none"';
            $strResultado .= '<table class="infraTable" style="width: 100%;" summary="Processos" id="tbCtrlProcesso">';
            $strResultado .= '<caption class="infraCaption">';
            $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Distribuição', $numRegistros);
            $strResultado .= '</caption>';


            //Cabeçalho da Tabela
            $strResultado .= '<thead><tr>';
            $strResultado .= '<th ' . $displayNoneCheck . ' class="infraTh utlSelecionarTodos" align="center" style="width:1%;">' . PaginaSEI::getInstance()->getThCheck() . '</th>';
            $strResultado .= '<th class="txt-col-left infraTh" style="min-width: 175px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Processo', 'ProtocoloProcedimentoFormatado', $arrObjs) . '</th>';
            $strResultado .= '<th class="txt-col-left infraTh" style=" min-width: 155px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Atividade', 'NomeAtividadeTriagem', $arrObjs) . '</th>';

            //ADICIONAR ORDENAÇÃO PARA OS OUTROS CAMPOS
            $strResultado .= '<th class="txt-col-left infraTh" style="min-width: 155px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Tipo Controle', 'NomeTpCtrlDsmp', $arrObjs) . '</th>';
            $strResultado .= '<th class="txt-col-left infraTh" style="min-width: 135px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Fila', 'NomeFila', $arrObjs) . '</th>';
            $strResultado .= '<th class="txt-col-left infraTh" style="min-width: 120px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Tempo de Execução', 'TempoExecucao', $arrObjs) . ' </th>';
            $strResultado .= '<th class="txt-col-left infraTh" style="min-width: 155px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Responsável', 'NomeUsuarioDistribuicao', $arrObjs) . '</th>';
            $strResultado .= '<th class="txt-col-left infraTh" style="min-width: 155px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Situação', 'StaAtendimentoDsmp', $arrObjs) . '</th>';
            $strResultado .= '<th class="txt-col-left infraTh" style="min-width: 112px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Data Situação', 'Atual', $arrObjs) . '</th>';
            $strResultado .= '<th class="txt-col-left infraTh" style="min-width: 110px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Prazo Resposta', 'PrazoResposta', $arrObjs) . ' </th>';

            if ($isPermiteAssociacao) {
                $strResultado .= '<th class="infraTh" style="text-align:center; min-width: 70px;"> Ações </th>';
            }

            $strResultado .= '<th class="infraTh" style="display: none">Última Fila</th>';
            $strResultado .= '</tr></thead>';


            //Linhas
            $strCssTr = '<tr class="infraTrEscura">';
            $numIdTpCtrlLink   = 0;
            $strLinkDistribuir = '';
            for ($i = 0; $i < $numRegistros; $i++) {

                //controle do link de distribuir e a busca do campo staFrequencia de acordo com o tipo de controle do loop
                if( $numIdTpCtrlLink != $arrObjs[$i]->getNumIdMdUtlAdmTpCtrlDesemp() ){
                    // monta o link para o tipo de controle corrente
                    $strLinkDistribuir = SessaoSEI::getInstance()->assinarLink($strUrl . 'cadastrar&acao_origem=' . $_GET['acao'] . '&id_tp_controle_desmp=' . $arrObjs[$i]->getNumIdMdUtlAdmTpCtrlDesemp() . '&acao_retorno=' . $_GET['acao'] . "&btn_acao=0");

                    //captura o staFrequencia da parametrizacao/tipo de controle corrente
                    $objMdUtlAdmTpCtrlDTO->setNumIdMdUtlAdmTpCtrlDesemp($arrObjs[$i]->getNumIdMdUtlAdmTpCtrlDesemp());
                    $objMdUtlAdmTpCtrlDTO->retStrStaFrequencia();
                    $objMdUtlAdmTpCtrlDTO->setNumMaxRegistrosRetorno(1);
                    $objDTOTipoControle = $objMdUtlAdmUtlTpCtrlRN->consultar($objMdUtlAdmTpCtrlDTO);
                    $staFrequencia = !is_null($objDTOTipoControle) ? $objDTOTipoControle->getStrStaFrequencia() : '';

                    //guarda o id tipo de controle para controle no loop
                    $numIdTpCtrlLink = $arrObjs[$i]->getNumIdMdUtlAdmTpCtrlDesemp();
                }

                $strId = $arrObjs[$i]->getDblIdProcedimento();
                $strProcesso = $arrObjs[$i]->getStrProtocoloProcedimentoFormatado();
                $strTpCtrlDsmp = $arrObjs[$i]->getStrNomeTpCtrlDsmp();
                $strFila = $arrObjs[$i]->getStrNomeFila();
                $strTpProcesso = $arrObjs[$i]->getNumIdTipoProcedimento();
                $nomeTpProcesso = $arrObjs[$i]->getStrNomeTipoProcedimento();
                $tempoExecucao = $arrObjs[$i]->getNumTempoExecucao();
                $tempoExecucaoAtrib = $arrObjs[$i]->getNumTempoExecucaoAtribuido();
                $strStatus = trim($arrObjs[$i]->getStrStaAtendimentoDsmp());
                $numIdControleDsmp = $arrObjs[$i]->getNumIdMdUtlControleDsmp();
                $numIdTriagem = $arrObjs[$i]->getNumIdMdUtlTriagem();
                $numIdAjustePrazo = $arrObjs[$i]->getNumIdMdUtlAjustePrazo();
                $numIdContestRevisao = $arrObjs[$i]->getNumIdMdUtlContestRevisao();
                $strNomeAtividade = array_key_exists($numIdTriagem, $arrayObjs) ? $arrayObjs[$numIdTriagem] : '';
                $linkAtvTriagem = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_atividade_triagem_listar&acao_origem=md_utl_distrib_usuario_listar&id_triagem=' . $numIdTriagem . '');

                $objStatusAnterior = $objMdUtlHistControleRN->getStatusAnterior($strId);
                $statusAnterior = !is_null($objStatusAnterior) ? $objStatusAnterior->getStrStaAtendimentoDsmp() : null;

                if (is_array($strNomeAtividade)) {
                    $strNomeAtividade = '<a href="#" onclick="infraAbrirJanelaModal(\'' . $linkAtvTriagem . '\',650,500,)" alt="Múltiplas" title="Múltiplas" class="ancoraPadraoAzul"> Múltiplas </a>';
                }

                $dataPrazo = explode(' ', $arrObjs[$i]->getDthPrazoTarefa());
                $dataPrazoFormatada = $dataPrazo[0];

                $prazoResposta = '';
                if ($numIdTriagem) {
                    $objTriagemRN = new MdUtlTriagemRN();
                    $objTriagem = $objTriagemRN->buscarObjTriagemPorId($numIdTriagem);
                    if ($objTriagem) {
                        if ($objTriagem->isSetDthPrazoResposta()) {
                            $objData = explode(' ', $objTriagem->getDthPrazoResposta());
                            $prazoResposta = $objData[0];
                        }
                    }
                }

                $dataAtual = InfraData::getStrDataAtual();
                $isDataPermitida = InfraData::compararDatasSimples($dataAtual, $dataPrazoFormatada) >= 0;

                $arrSituacao = MdUtlControleDsmpINT::retornaArrSituacoesControleDsmp();
                $linkProcedimento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=md_utl_distrib_usuario_listar&id_procedimento=' . $strId . '');
                $data = explode(' ', $arrObjs[$i]->getDthAtual());
                $dataFormatada = $data[0];
                $bolRegistroAtivo = true;


                $strCssTr = !$bolRegistroAtivo ? '<tr class="trVermelha ">' : ($strCssTr == '<tr class="infraTrClara ">' ? '<tr class="infraTrEscura ">' : '<tr class="infraTrClara ">');
                $strCssTr = in_array($strId, $arrIdProcedimentoDistrib) ? '<tr class="infraTrAcessada">' : $strCssTr;
                $strResultado .= $strCssTr;

                //Linha Checkbox
                $strResultado .= '<td ' . $displayNoneCheck . ' align="center" valign="middle">';
                $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId, $strProcesso);
                $strResultado .= '</td>';

                //Linha Nome
                $strResultado .= '<td class="tdIdProcesso" style="display: none">';
                $strResultado .= $strId;
                $strResultado .= '</td>';

                //Linha Nome
                $strResultado .= '<td class="tdNomeProcesso">';
                $strResultado .= '<a href="#" onclick="window.open(\'' . $linkProcedimento . '\')" alt="' . $nomeTpProcesso . '" title="' . $nomeTpProcesso . '" class="ancoraPadraoAzul" style="padding:0px !important;">' . $strProcesso . '</a>';
                $strResultado .= '</td>';

                //Linha Atividade
                $strResultado .= '<td class="tdNomeAtividade">';
                $strResultado .= $strNomeAtividade;
                $strResultado .= '</td>';

                //Linha Nome Tipo de Controle Dsmp
                $strResultado .= '<td class="tdNomeTpCtrl">';
                $strResultado .= PaginaSEI::tratarHTML($strTpCtrlDsmp);
                $strResultado .= '</td>';

                //Linha Fila Padrão
                $strResultado .= '<td class="tdFilaProcesso">';
                $strResultado .= PaginaSEI::tratarHTML($strFila);
                $strResultado .= '</td>';

                $tmpExecucaoExibir = $tempoExecucaoAtrib ?: $tempoExecucao;
                $tmpExecucaoExibir = MdUtlAdmPrmGrINT::convertToHoursMins( $tmpExecucaoExibir );

                //Linha Tempo de Execução
                $strResultado .= '<td class="tdUniEsforco">';
                $strResultado .= PaginaSEI::tratarHTML( $tmpExecucaoExibir );
                $strResultado .= '</td>';

                //Linha Responsável
                $strResultado .= '<td class="tdResponsavel">';
                $strResultado .= '<a class="ancoraSigla" href="#" alt="' . PaginaSEI::tratarHTML($arrObjs[$i]->getStrNomeUsuarioDistribuicao()) . '" title="' . PaginaSEI::tratarHTML($arrObjs[$i]->getStrNomeUsuarioDistribuicao()) . '">' . PaginaSEI::tratarHTML($arrObjs[$i]->getStrSiglaUsuarioDistribuicao()) . '</a>';
                $strResultado .= '</td>';

                //Linha Fila Status
                $strResultado .= '<td class="tdStatusProcesso">';
                $strResultado .= !is_null($strStatus) ? PaginaSEI::tratarHTML($arrSituacao[$strStatus]) : PaginaSEI::tratarHTML($arrSituacao[0]);
                $strResultado .= '</td>';

                //Linha Data Registro Status
                $strResultado .= '<td class="tdDtRegistroStatus">';
                $strResultado .= PaginaSEI::tratarHTML($dataFormatada);
                $strResultado .= '</td>';

//                //Linha Data Prazo Resposta
                $strResultado .= '<td class="tdDtRegistroStatus">';
                $strResultado .= PaginaSEI::tratarHTML($prazoResposta);
                $strResultado .= '</td>';

                //Linha Açôes
                if ($isPermiteAssociacao) {
                    $strResultado .= '<td class="tdAcoes">';
                    $btnDistribuir = '<img src="modulos/utilidades/imagens/svg/distribuir.svg?11" width="24" height="24" id="btnDistribuicao" style="margin-left: 30%" onclick="distribuir(false ,\'' . $numIdControleDsmp . '\' ,\'' . $strStatus . '\' ,\'' . $arrObjs[$i]->getNumIdFila() . '\' , \''.$staFrequencia.'\' , \''. $strLinkDistribuir . '\');" title="Distribuir" alt="Distribuir" class="infraImg" />';
                    $strResultado .= $btnDistribuir;

                    if ($isGestorTpControle) {
                        $strUrl = PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_distrib_usuario_retornar&acao_origem=md_utl_distrib_usuario_listar&acao_retorno=md_utl_distrib_usuario_listar&id_procedimento=' . $strId));

                        if ($strStatus == MdUtlControleDsmpRN::$SUSPENSO || $strStatus == MdUtlControleDsmpRN::$INTERROMPIDO && !is_null($statusAnterior)) {
                            if ($statusAnterior == MdUtlControleDsmpRN::$EM_REVISAO) {
                                $strResultado .= '<a id="retornarRevisao" onclick="confirmarRetorno(\'' . $strStatus . '\',\'' . $strUrl . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/svg/retornar_processo.svg?11" width="24" height="24" title="Retornar para Avaliação" alt="Retornar para Avaliação" class="infraImg" /></a>&nbsp;';
                            } else if ($statusAnterior == MdUtlControleDsmpRN::$EM_ANALISE || MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE) {
                                $strResultado .= '<a id="retornarAnalise" onclick="confirmarRetorno(\'' . $strStatus . '\',\'' . $strUrl . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/svg/retornar_processo.svg?11" width="24" height="24" title="Retornar para Análise" alt="Retornar para Análise" class="infraImg" /></a>&nbsp;';
                            }
                        }
                    }

                    $strResultado .= '</td>';
                }

                //Linha Controle Dsmp
                $strResultado .= '<td class="tdIdControleDsmp" style="display: none">';
                $strResultado .= $numIdControleDsmp;
                $strResultado .= '</td>';

                $strResultado .= '</tr>';

            }
            $strResultado .= '</table>';
        }
    }
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');

//Include de estilos CSS
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
require_once "md_utl_geral_css.php";

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

// textos dos tooltips
$txtTooltipTotalTempoExecucaoProcessosSelecionados = 'O Total de Tempo de Execução dos Processos Selecionados corresponde à soma do Tempo de Execução de cada processo selecionado, conforme constante na tabela de listagem abaixo.\n \n O Tempo de Execução de Triagem é padrão por Fila do Controle de Desempenho.\n \n O Tempo de Execução de Análise depende das Atividades incluídas na fase de Triagem. Contudo, ao final, o Membro Participante que realizar a Análise somente ganhará o Tempo de Execução das Atividades que tenha entregado pelo menos um Produto.\n \n O Tempo de Execução de Avaliação depende de cada Produto entregue nas Atividades na fase de Análise.';

$txtTooltipTotalTempoExecutadoPeriodo = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDinamicoDistribuirProcessos($idTipoControle);

$txtTooltipCargaHorariaDistribuidaPeriodo = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDistribuicaoDinamicoCargaHorariaDistribuidaPeriodo($idTipoControle);

?>
    <form id="frmTpControleLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(
              SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
          ) ?>">

        <?php
            PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
            PaginaSEI::getInstance()->abrirAreaDados('auto');
            $col_default = "col-sm-6 col-md-6 col-lg-4 mb-2";
        ?>

        <div class="row mb-3">
            <div class="<?= $col_default ?> mb-2" id="divProcesso">
                <label id="lblProcesso" for="txtProcessoUtlDist" class="infraLabelOpcional">Processo:</label>
                <input type="text" id="txtProcessoUtlDist" name="txtProcessoUtlDist" class="inputFila infraText padraoInput form-control"
                        value="<?= $txtProcessoCampo ?>"
                        maxlength="100" tabindex="502"/>
            </div>

            <div class="<?= $col_default ?> mb-2" id="divDocumento">
                <label id="lblDocumento" for="txtDocumentoUtlDist" accesskey="S" class="infraLabelOpcional">Documento SEI:</label>
                <input type="text" id="txtDocumentoUtlDist" name="txtDocumentoUtlDist"
                    class="inputFila infraText padraoInput form-control"
                    value="<?= $txtDocumentoCampo ?>"
                    maxlength="100" tabindex="502"/>
            </div>

            <div class="<?= $col_default ?> mb-2" id="divTpControle">
                <label id="lblTpControle" for="selTpControle" accesskey="" class="infraLabelOpcional">Tipo de Controle:</label>
                <select id="selTpControle" name="selTpControle" class="infraSelect padraoSelect form-control"
                        onchange="pesquisar()"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $selTpControle ?>
                </select>
            </div>

            <div class="<?= $col_default ?> mb-2" id="divFila">
                <label id="lblFila" for="selFilaUtlDist" accesskey="" class="infraLabelOpcional">Fila:</label>
                <select id="selFilaUtlDist" name="selFilaUtlDist" class="infraSelect padraoSelect form-control"
                        onchange="pesquisar();"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $selFila ?>
                </select>
            </div>

            <div class="<?= $col_default ?> mb-2" id="divStatus">
                <label id="lblStatus" for="selStatusUtlDist" accesskey="" class="infraLabelOpcional">Situação:</label>
                <select id="selStatusUtlDist" name="selStatusUtlDist" class="infraSelect padraoSelect form-control"
                        onchange="pesquisar();"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $selStatus ?>
                </select>
            </div>

            <div class="<?= $col_default ?> mb-2" id="divResponsavel">
                <label id="lblResponsavel" for="selResponsavelUtlDist" accesskey="" class="infraLabelOpcional">Membro Participante:</label>
                <select id="selResponsavelUtlDist" name="selResponsavelUtlDist" class="infraSelect padraoSelect form-control"
                        onchange="pesquisar();" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $selResponsavel ?>
                </select>
            </div>

            <div class="<?= $col_default ?> mb-2" id="divTipoProcesso">
                <label id="lblTipoProcesso" for="selTipoProcessoUtlDist" accesskey="" class="infraLabelOpcional">
                    Tipo de Processo:
                </label>
                <select id="selTipoProcessoUtlDist" name="selTipoProcessoUtlDist" class="infraSelect padraoSelect form-control"
                        onchange="pesquisar();" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <option value=""></option>
                    <?= $selTipoProcesso ?>
                </select>
            </div>

            <div class="<?= $col_default ?> mb-2" id="divAtividade">
                <label id="lblAtividade" for="selAtividadeUtlDist" accesskey="" class="infraLabelOpcional">Atividade:</label>
                <select id="selAtividadeUtlDist"
                        name="selAtividadeUtlDist"
                        onchange="pesquisar();"
                        class="infraSelect padraoSelect form-control"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <option value=""></option>
                    <?= $selAtividade ?>
                </select>
            </div>
        </div>

        <?php $col_def_labels = "col-12 col-sm-10 col-md-10 col-lg-8 col-xl-6"; ?>

        <div class="row">
            <div class="<?= $col_def_labels ?> mb-2 justify-content-center align-self-center" id="divTotalUnidade">
                <label id="lblTotalUnidade" class="infraLabelOpcional">Total de Tempo de Execução dos Processos Selecionados:</label>
                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                    name="ajuda" <?= PaginaSEI::montarTitleTooltip($txtTooltipTotalTempoExecucaoProcessosSelecionados,'Ajuda') ?> />
                <span id="spnTotalUnidade" class="badge badge-primary badge-pill ml-1 p-2" style="vertical-align: top;" valor="0">0min</span>
            </div>

            <div class="<?= $col_def_labels ?> mb-2 justify-content-center align-self-center" id="divCargaHrDistribExec">
                <label id="lblCargaHrDistribExec" class="infraLabelOpcional">Total de Tempo Executado no Período:</label>
                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                    name="ajuda" <?= PaginaSEI::montarTitleTooltip($txtTooltipTotalTempoExecutadoPeriodo,'Ajuda') ?> />
                <span id="spnCargaHrDistribExec" class="badge badge-primary badge-pill ml-1 p-2" style='vertical-align: top;'>0min</span>
            </div>

            <div class="<?= $col_def_labels ?> mb-2 justify-content-center align-self-center" id="divCargaHrPadrao">
                <label id="lblCargaHrPadrao" class="infraLabelOpcional">Carga Horária Padrão no Período:</label>
                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                    name="ajuda" <?= PaginaSEI::montarTitleTooltip($txtTooltipTotalTempoExecutadoPeriodo,'Ajuda') ?> />

                <span id="spnCargaHrPadrao" class="badge badge-primary badge-pill ml-1 p-2" style="vertical-align: top;">0min</span>
            </div>

            <div class="<?= $col_def_labels ?> mb-2 justify-content-center align-self-center" id="divCargaHrDistrib">
                <label id="lblCargaHrDistrib" class="infraLabelOpcional">Carga Horária Distribuída no Período:</label>
                <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                    name="ajuda" <?= PaginaSEI::montarTitleTooltip($txtTooltipCargaHorariaDistribuidaPeriodo,'Ajuda') ?> />
                <span id="spnCargaHrDistrib" class="badge badge-primary badge-pill ml-1 p-2" style="vertical-align: top;">0min</span>
            </div>
        </div>

        <input type="hidden" id="hdnSelStatus" name="hdnSelStatus" value=""/>
        <input type="hidden" id="hdnSubmit" name="hdnSubmit" value="<?= $vlControlePost ?>"/>
        <input type="hidden" id="hdnSelFila" name="hdnSelFila" value=""/>
        <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl"
               value="<?= is_null($idTipoControle) ? '0' : $idTipoControle ?>"/>
        <input type="hidden" id="hdnIdParametroCtrlUtl" name="hdnIdParametroCtrlUtl"
               value="<?= empty($arrListaTpCtrl) ? 0 : 1 ?>"/>
        <input type="hidden" id="hdnDadosAssociarFila" name="hdnDadosAssociarFila"/>
        <input type="hidden" id="hdnDistribuicao" name="hdnDistribuicao"/>
        <input type="hidden" id="hdnUrlControleProcessos" name="hdnUrlControleProcessos"
               value="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']) ?>"/>
        <input type="hidden" id="hdnTpCtrlValida" name="hdnTpCtrlValida" value="<?= empty($arrListaTpCtrl) ? '0' : '1' ?>">

        <?php
            PaginaSEI::getInstance()->fecharAreaDados();
            PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
            PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>

    <?php
        require_once 'md_utl_geral_js.php';
        require_once 'md_utl_funcoes_js.php';
    ?>

<script type="text/javascript">
    var msg57 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_57); ?>';
    var msg58 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_58); ?>';
    var msg59 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_59); ?>';
    var msg24 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_24); ?>';
    var msg25 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_25); ?>';
    var msg96 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_96); ?>';

    var totalUnidade = 0;
    var count = 0;
    var strUrlMultiplo = '';

    function inicializar() {

        <?php if($numRegistros == 0 ){ ?>
            $('#divInfraAreaPaginacaoSuperior').remove();
        <?php } ?>

        var urlCtrlProcessos = document.getElementById('hdnUrlControleProcessos').value;
        var idParam = document.getElementById('hdnIdParametroCtrlUtl').value;
        var tpCtrl = document.getElementById('hdnTpCtrlValida').value;


        if (tpCtrl == 0) {
            alert(msg24);
            window.location.href = urlCtrlProcessos;
            return false;
        }

        /*
        if (idParam == 0) {
            alert(msg25);
            window.location.href = urlCtrlProcessos;
            return false;
        }
        */
        configuraCkeckbox();

        if ('<?= $_GET['acao'] ?>' == 'md_utl_distrib_usuario_selecionar') {
            infraReceberSelecao();
            document.getElementById('btnFecharSelecao').focus();
        } else {
            infraEfeitoTabelas();
        }

        document.getElementById('spnTotalUnidade').innerHTML = convertToHoursMins(totalUnidade);

            // traz a carga distribuida periodo
            let selTpCtrl = document.getElementById('selTpControle').value;
            let selResp   = document.getElementById('selResponsavelUtlDist').value;
            
            if( selResp != '' || selTpCtrl != '' ){
                let todosValoresTpCtrl = new Array();

            if( selTpCtrl != '' ){
                todosValoresTpCtrl.push( selTpCtrl );
            }else{
                $("#selTpControle option").each(function(){
                    if( $( this ).val() != '' ) todosValoresTpCtrl.push( $( this ).val() );
                });
            }

            getCargaHrDistribuida( todosValoresTpCtrl );
        }

        addEnter();

        // Adiciona a class "infraLabelOpcional" quando não retorna nenhum registro na grid
        // para ficar na mesma formatação das labels que retornam dados referentes a tempo
        if( $('#divInfraAreaTabela').find('table').length == 0 ){
            $('#divInfraAreaPaginacaoSuperior').hide();
            $('#divInfraAreaTabela').addClass('mt-2');
            $('#divInfraAreaTabela > label').addClass('infraLabelOpcional'); 
        }else{
            if( $('#divInfraAreaPaginacaoSuperior').find('select').length == 0 ){
                $('#divInfraAreaPaginacaoSuperior').hide();
            }
        }
    }

    function addEnter() {
        document.getElementById('txtProcessoUtlDist').addEventListener("keypress", function (evt) {
            addPesquisarEnter(evt);
        });

        document.getElementById('txtDocumentoUtlDist').addEventListener("keypress", function (evt) {
            addPesquisarEnter(evt);
        });
    }

    function addPesquisarEnter(evt) {
        var key_code = evt.keyCode ? evt.keyCode :
            evt.charCode ? evt.charCode :
                evt.which ? evt.which : void 0;

        if (key_code == 13) {
            pesquisar();
        }

    }

    function distribuir(multiplo, idSelecionado, idStatus, idFila, staFreq, strLink) {
        var numeroRegistroTela = '<?= $numRegistros ?>';
        var isValido = true;
        var staFrequencia = staFreq;

        if (numeroRegistroTela == 0) {
            alert(msg59);
            return false;
        }
        /*
        if (staFrequencia == 0) {
            alert('A Frequência de Distribuição não está parametrizada no Tipo de Controle desta Unidade. Converse com o Gestor da sua área!');
            return false;
        }
        */

        if (multiplo) {
            isValido = realizarValidacoesFiltro();
            strLink = strUrlMultiplo;
        }

        if (isValido) {
            preencherHiddenDistribuicao(multiplo, idSelecionado);
            enviarStatusFila(multiplo, idStatus, idFila);
            document.getElementById('frmTpControleLista').action = strLink;
            document.getElementById('frmTpControleLista').submit();
        }
    }

    function enviarStatusFila(multiplo, idStatus, idFila) {
        /*
        var idStatusEnviar = multiplo ? document.getElementById('selStatusUtlDist').value : idStatus;
        var idFilaEnviar = multiplo ? document.getElementById('selFilaUtlDist').value : idFila;
        */
        if(!multiplo){
            document.getElementById('hdnSelStatus').value = idStatus; //idStatusEnviar;
            document.getElementById('hdnSelFila').value = idFila; //idFilaEnviar ;
        }
    }

    function realizarValidacoesFiltro() {

        var numSelecionados = infraNroItensSelecionados();

        if (numSelecionados == 0) {
            alert(msg59);
            return false;
        }

        var valid = regrasDistribuicaoMultiplo();

        if( ! (valid) ){
            return false;
        }

        return true;
    }

    function regrasDistribuicaoMultiplo(){
        var elems          = $('.infraTrMarcada');
        var valid          = true;
        var arrObjProcesso = new Array();

        $( elems ).each(function( i , obj ){
            arrObjProcesso.push( parseInt( $(obj).closest('tr').find('.tdIdProcesso').text() ) );
        });

        var params = { listProcessos: arrObjProcesso , acao: "<?= $_GET['acao'] ?>" };

        $.ajax({
            url: "<?= $strUrlValDistr ?>",
            type: 'post',
            dataType: 'xml',
            async: false,
            data: params
        })
        .done( function( rs ){
            var msg = '';

            if( $( rs ).find('TipoControle').length > 0 ) {
                msg += "Os processos selecionados precisam ter o mesmo Tipo de Controle.\n";
            }

            if( $( rs ).find('Fila').length > 0 && $( rs ).find('Fila').text() == '' ) {
                msg += "Os processos selecionados precisam ter a mesma Fila.\n";
            }

            if( $( rs ).find('Situacao').length > 0 && $( rs ).find('Situacao').text() == '' ) {
                msg += "Os processos selecionados precisam ter o mesmo Status.\n";
            }

            if( msg != '' ){
                valid = false;
                alert( msg );
            }else{
                strUrlMultiplo = $( rs ).find('Url').text();
                $('#hdnSelStatus').val( $( rs ).find('Situacao').text() );
                $('#hdnSelFila').val( $( rs ).find('Fila').text() );
            }
        })
        .fail( function( xhr ){
            alert( xhr.responseText );
        });

        return valid;
    }

    function validoDistribuicao() {
        var linhas = document.getElementsByClassName('infraTrMarcada');
        var msgInicio = msg96;
        var valido = true;

        for (var i = 0; i < linhas.length; i++) {
            var idStatus = $(linhas[i]).find('.tdIdStatus').text();

            if (idStatus == idStatusSuspensao || idStatus == idStatusInterrupcao) {
                if (valido) {
                    msgInicio += "\n";
                }

                valido = false;
                msgInicio += "\n";
                msgInicio += " - " + $(linhas[i]).find('.tdNomeProcessoFormatado').text();
            }
        }

        if (!valido) {
            alert(msgInicio);
        }

        return valido;
    }

    function pesquisar() {
        var selTpCtrl = document.getElementById('selTpControle').value;
        document.getElementById('hdnIdTipoControleUtl').value = selTpCtrl;

        document.getElementById('frmTpControleLista').action = '<?= $strUrlPesquisar ?>';
        document.getElementById('frmTpControleLista').submit();
    }

    function fechar() {
        location.href = "<?= $strUrlFechar ?>";
    }

    function preencherHiddenDistribuicao(multiplo, idSelecionado) {
        var json = '';
        var linhas = new Array();

        if (multiplo) {
            var objs = document.getElementsByClassName('infraTrMarcada');

            for (var i = 0; i < objs.length; i++) {
                var idControleDsmp = $(objs[i]).find('.tdIdControleDsmp').text();
                linhas.push(idControleDsmp);
            }

        } else {
            linhas.push(idSelecionado);
        }

        if (linhas.length > 0) {
            json = JSON.stringify(linhas);
            document.getElementById('hdnDistribuicao').value = json;
        }
    }

    function configuraCkeckbox() {
        //seleção unica
        var atributos = document.getElementsByClassName('infraCheckbox');

        for (i = 0; i < atributos.length; i++) {
            atributos[i].removeAttribute('onclick');
        }

        for (i = 0; i < atributos.length; i++) {
            atributos[i].addEventListener('click', function (e) {
                infraSelecionarItens(e.target, 'Infra');
                getTempoExecucao(e.target);
            });
        }

        //seleção multipla
        var ultSelecionarTodos = document.getElementsByClassName('utlSelecionarTodos');

        if(ultSelecionarTodos.length > 0 ){
            var atributoMult = document.getElementsByClassName('utlSelecionarTodos')[0].children[1];

            atributoMult.removeAttribute('onclick');
            atributoMult.addEventListener('click', function (e) {
                infraSelecaoMultipla('Infra');
                getTempoExecucaoMultiplo(e);
            });
        }

        setTimeout(controlaChecksInicializacao, '10')
    }


    function controlaChecksInicializacao() {

        var objsMarcados = document.getElementsByClassName('infraTrMarcada');
        var total = 0;

        if (objsMarcados.length > 0) {

            for (var i = 0; i < objsMarcados.length; i++) {
                var undEsfor = convertToMins(objsMarcados[i].children[6].innerText);
                total += undEsfor;
            }
            document.getElementById('spnTotalUnidade').setAttribute('valor', total);
            document.getElementById('spnTotalUnidade').innerHTML = convertToHoursMins(total);
        }

    }


    function getTempoExecucao(obj) {
        var trPrincipal = obj.parentElement.parentElement.parentElement;
        var valorUniEsforco = convertToMins(trPrincipal.children[6].innerText);console.log(valorUniEsforco);
        var totalUnidade = parseInt(document.getElementById('spnTotalUnidade').getAttribute('valor'));


        if (obj.checked == true) {
            totalUnidade += valorUniEsforco;
        } else {
            totalUnidade -= valorUniEsforco;
        }

        document.getElementById('spnTotalUnidade').setAttribute('valor', totalUnidade);
        document.getElementById('spnTotalUnidade').innerHTML = convertToHoursMins(totalUnidade);

    }

    function getTempoExecucaoMultiplo(ev) {

        var objs = document.getElementsByClassName('infraTrMarcada');


        if ($.trim(ev.target.title) != 'Selecionar Tudo') {
            var somaUniEsforco = 0;
            for (var i = 0; i < objs.length; i++) {
                somaUniEsforco += convertToMins(objs[i].children[6].innerText);
            }

        } else {
            somaUniEsforco = 0;
        }

        document.getElementById('spnTotalUnidade').setAttribute('valor', somaUniEsforco);
        document.getElementById('spnTotalUnidade').innerHTML = convertToHoursMins(somaUniEsforco);
    }

    function confirmarRetorno(strStatus, $strUrlLink) {
        var msg105padrao = '<?=MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_105)?>';

        if (strStatus == '<?=MdUtlControleDsmpRN::$SUSPENSO?>') {
            var msg = setMensagemPersonalizada(msg105padrao, ['<?=MdUtlControleDsmpRN::$STR_SUSPENSO?>']);
            var validar = confirm(msg);
            if (validar == true) {
                document.getElementById('frmTpControleLista').action = $strUrlLink;
                document.getElementById('frmTpControleLista').submit();
            }
        }

        if (strStatus == '<?=MdUtlControleDsmpRN::$INTERROMPIDO?>') {
            var msg = setMensagemPersonalizada(msg105padrao, ['<?=MdUtlControleDsmpRN::$STR_INTERROMPIDO?>']);
            var validar = confirm(msg);
            if (validar == true) {
                document.getElementById('frmTpControleLista').action = $strUrlLink;
                document.getElementById('frmTpControleLista').submit();
            }
        }

    }

    function getCargaHrDistribuida( idsTpCtrl ){
        var params = {
            idUsuarioParticipante: document.getElementById('selResponsavelUtlDist').value,
            idTipoControle: idsTpCtrl
        };

        $.ajax({
            url: "<?= $strUrlBuscarDadosCarga ?>",
            type: 'POST',
            data: params,
            dataType: 'XML',
            success: function (r) {
                var cargaDisti = $(r).find('ValorUndEs').text();
                var cargaDistiExe = $(r).find('ValorUndEsExecutado').text();
                var cargaPadrao = $(r).find('ValorCarga').text();
                document.getElementById('divCargaHrDistrib').style.display = 'block';
                document.getElementById('divCargaHrDistribExec').style.display = 'block';
                document.getElementById('spnCargaHrDistrib').innerHTML = String(convertToHoursMins(cargaDisti));
                document.getElementById('spnCargaHrDistribExec').innerHTML = String(convertToHoursMins(cargaDistiExe));
                document.getElementById('spnCargaHrPadrao').innerHTML = String(convertToHoursMins(cargaPadrao));
            },
            error: function (e) {
                console.error('Erro ao buscar URL de Tipo de Controle: ' + e.responseText);
            }
        });
    }

</script>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();