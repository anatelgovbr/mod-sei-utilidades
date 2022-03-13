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

$isProcessoConcluido = array_key_exists('is_processo_concluido', $_GET) ? $_GET['is_processo_concluido'] : 0;
$isProcessoAutorizadoConcluir = array_key_exists('hdnIsConcluirProcesso', $_POST) ? $_POST['hdnIsConcluirProcesso'] : 0;

PaginaSEI::getInstance()->salvarCamposPost(array('txtProcessoUtlMs', 'selFilaUtlMs', 'selTipoProcessoUtlMs', 'selStatusUtlMs', 'selAtividadeUtlMs', 'selTpControle'));

$txtProcessoCampo = array_key_exists('txtProcessoUtlMs', $_POST) ? $_POST['txtProcessoUtlMs'] : PaginaSEI::getInstance()->recuperarCampo('txtProcessoUtlMs');
$selFilaCampo = array_key_exists('selFilaUtlMs', $_POST) ? $_POST['selFilaUtlMs'] : PaginaSEI::getInstance()->recuperarCampo('selFilaUtlMs');
$selTipoProcessoCampo = array_key_exists('selTipoProcessoUtlMs', $_POST) ? $_POST['selTipoProcessoUtlMs'] : PaginaSEI::getInstance()->recuperarCampo('selTipoProcessoUtlMs');
$selStatusCampo = array_key_exists('selStatusUtlMs', $_POST) ? $_POST['selStatusUtlMs'] : PaginaSEI::getInstance()->recuperarCampo('selStatusUtlMs');
$selAtividadeCampo = array_key_exists('selAtividadeUtlMs', $_POST) ? $_POST['selAtividadeUtlMs'] : PaginaSEI::getInstance()->recuperarCampo('selAtividadeUtlMs');
$selTpControleCampo = array_key_exists('selTpControle', $_POST) ? $_POST['selTpControle'] : PaginaSEI::getInstance()->recuperarCampo('selTpControle');

$somaTmpExecucao = '';
$idProcedimentoMeusProcessos = array_key_exists('id_procedimento', $_GET) ? $_GET['id_procedimento'] : $_POST['hdnIdProcedimento'];
$strSituacao = '';

$objFilaRN = new MdUtlAdmFilaRN();
$objMdUtlAdmFilaPrmGrUsuRN = new MdUtlAdmFilaPrmGrUsuRN();
$objMdUtlAdmTpCtrlUsuRN = new MdUtlAdmRelTpCtrlDesempUsuRN();
$objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
$objMdUtlAdmTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
$objMdUtlRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
$objMdUtlHistControleRN = new MdUtlHistControleDsmpRN();
$objRegrasGerais = new MdUtlRegrasGeraisRN();
$objMdUtlAdmUtlTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();

if ($idProcedimentoMeusProcessos != null && $idProcedimentoMeusProcessos != '') {
    $objProcedimentoDTO = $objRegrasGerais->getObjProcedimentoPorId($idProcedimentoMeusProcessos);
    $strNumeroProcedimento = $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado();
    $msg107 = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_107, array($strNumeroProcedimento, SessaoSEI::getInstance()->getStrSiglaUnidadeAtual()));
} else {
    $msg107 = '';
}

if ($isProcessoAutorizadoConcluir == 1) {
    $_POST['hdnIsConcluirProcesso'] = 0;
    $isProcessoAutorizadoConcluir = 0;

    $objEntradaConcluirProcessoAPI = new EntradaConcluirProcessoAPI();
    $objEntradaConcluirProcessoAPI->setIdProcedimento($idProcedimentoMeusProcessos);

    $objSEIRN = new SeiRN();
    $objSEIRN->concluirProcesso($objEntradaConcluirProcessoAPI);
}


$arrPostDados = array('txtProcesso' => $txtProcessoCampo, 'selFila' => $selFilaCampo, 'selTipoProcesso' => $selTipoProcessoCampo, 'selStatus' => $selStatusCampo, 'selTpControle' => $selTpControleCampo);

//Array que sera usado para montar os tipos de controles da unidade
$arrObjTpControleUnidadeLogada     = $objMdUtlAdmTpCtrlUndRN->getArrayTipoControleUnidadeLogada();
$arrListaTpControle                = array();
$arrListaIdsTpControle             = array();
if (count($arrObjTpControleUnidadeLogada) > 0 ){
    foreach ($arrObjTpControleUnidadeLogada as $k => $v) {
        $arrListaTpControle[$v->getNumIdMdUtlAdmTpCtrlDesemp()] = $v->getStrNomeTipoControle();
        array_push( $arrListaIdsTpControle , $v->getNumIdMdUtlAdmTpCtrlDesemp() );
    }
}

// retorna tipos de controles da unidade onde o usuario é membro participante
$arrTpControleUsuMembroUnid = $objMdUtlAdmTpCtrlUndRN->getArrayTipoControleUnidadeUsuMembro($arrListaIdsTpControle);
$bolHabDistProxProc = !empty( $arrTpControleUsuMembroUnid );

$arrObjTpControle = null;
if ( !empty($arrTpControleUsuMembroUnid) ) {
    $objTpCtrlRN  = new MdUtlAdmTpCtrlDesempRN();
    $objTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
    $objTpCtrlDTO->setNumIdMdUtlAdmTpCtrlDesemp($arrTpControleUsuMembroUnid,InfraDTO::$OPER_IN);
    $objTpCtrlDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objTpCtrlDTO->retNumIdMdUtlAdmTpCtrlDesemp();
    $objTpCtrlDTO->retStrNome();

    $arrObjTpControle = $objTpCtrlRN->listar( $objTpCtrlDTO );
}

$selTpControle = is_null($arrObjTpControle) ? array() : MdUtlAdmFilaINT::montarSelectTpControle($arrObjTpControle,'NumIdMdUtlAdmTpCtrlDesemp', 'StrNome',$_POST['selTpControle']);

$idTipoControle = null;
if( isset($_POST['selTpControle']) && !empty($_POST['selTpControle']) ) {
    $idTipoControle = $_POST['selTpControle'];
} else if( count( $arrObjTpControle ) == 1 ) {
    $idTipoControle = $arrObjTpControle[0]->getNumIdMdUtlAdmTpCtrlDesemp();
}

$arrObjsFilaDTO = $objFilaRN->getFilasTipoControle($idTipoControle);
$idsFilasPermitidas = InfraArray::converterArrInfraDTO($arrObjsFilaDTO, 'IdMdUtlAdmFila');
$arrObjsFilaUsuDTO = $objMdUtlAdmFilaPrmGrUsuRN->getPapeisDeUsuario($idsFilasPermitidas);

$idParametro = null;

$isPermiteAssociacao = true;
//$isPermiteAssociacao = $objMdUtlControleDsmpRN->validaVisualizacaoUsuarioLogado($idTipoControle);
$isParametrizado     = true;
if (!is_null($idTipoControle)) {
    //$isParametrizado = $objMdUtlAdmUtlTpCtrlRN->verificaTipoControlePossuiParametrizacao($idTipoControle);
}

if ($isParametrizado) {
    $isGestorSipSei = $objMdUtlAdmTpCtrlUsuRN->usuarioLogadoIsGestorSipSei();
    $idsStatusPermitido = $objMdUtlControleDsmpRN->getStatusPermitido($arrObjsFilaUsuDTO, $isGestorSipSei, true);

//URL Base
    $strUrl = 'controlador.php?acao=md_utl_meus_processos_dsmp_';

//URL das Actions
    $strUrlPesquisar = SessaoSEI::getInstance()->assinarLink($strUrl . 'listar&acao_origem=' . $_GET['acao']);
    $strUrlFechar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);
    $strUrlAjaxAtribuirProximo = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_atribuir_proximo');
    $strUrlDistrMim = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_distribuir_para_mim&ids_tp_ctrl_dist='.implode(',',$arrTpControleUsuMembroUnid));
    $strUrlBuscarDadosCarga = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_buscar_dados_carga_usuario_todos_tpctrl');

    $idsFilasPermitidasUsBasico = $isGestorSipSei || count($arrObjsFilaUsuDTO) == 0 ? null : InfraArray::converterArrInfraDTO($arrObjsFilaUsuDTO, 'IdMdUtlAdmFila');

    if ($isGestorSipSei) {
        $selFila = MdUtlAdmFilaINT::montarSelectFilas($selFilaCampo, $arrObjsFilaDTO);
    } else {
        $selFila = count($idsFilasPermitidasUsBasico) > 0 ? $selFila = MdUtlAdmFilaINT::montarSelectFilas($selFilaCampo, $arrObjsFilaDTO, $idsFilasPermitidasUsBasico) : null;
    }

    $selStatus = count($idsStatusPermitido) > 0 || $isGestorSipSei ? MdUtlControleDsmpINT::montarSelectStatusMeusProcessos($selStatusCampo, false, $idsStatusPermitido) : null;
    $arrObjsTpProcesso = $objMdUtlControleDsmpRN->getTiposProcessoTipoControleAssociarFila(!is_null($idTipoControle) ? array($idTipoControle => $idTipoControle) : $arrListaTpControle);
    $selTipoProcesso = $isPermiteAssociacao ? InfraINT::montarSelectArrInfraDTO(null, null, $selTipoProcessoCampo, $arrObjsTpProcesso, 'IdTipoProcedimento', 'NomeProcedimento') : '';
}

$strTitulo = 'Meus Processos';

switch ($_GET['acao']) {

    //region Listar
    case 'md_utl_meus_processos_dsmp_listar':

        break;
    //endregion

    //region Retorno
    case 'md_utl_meus_processos_dsmp_retornar':
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

        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_meus_processos_dsmp_listar&acao_origem=' . $_GET['acao']));

        break;
    //endregion

    //region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    //endregion
}


//Verifica se é ação Selecionar
$bolSelecionar = $_GET['acao'] == 'md_utl_adm_fila_selecionar';

if( $bolHabDistProxProc ) {
    $arrComandos[] = '<button type="button" accesskey="m" id="btnAtribuirProximo" onclick="atribuirProximoModal()" class="infraButton">
                    Distribuir para <span class="infraTeclaAtalho">m</span>im o Próximo Processo</button>';
}

$arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" onclick="pesquisar()" class="infraButton">
                                        <span class="infraTeclaAtalho">P</span>esquisar</button>';

$arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                        Fe<span class="infraTeclaAtalho">c</span>har</button>';
$numRegistros = 0;
if ($isParametrizado) {
    $paramTpCtrl = is_null($idTipoControle) ? $arrListaIdsTpControle : $idTipoControle;
    $objDTOCombo = $objMdUtlControleDsmpRN->getObjDTOParametrizadoMeusProcessos(array($arrObjsFilaUsuDTO, $isGestorSipSei, $arrObjsTpProcesso, $paramTpCtrl, array()));
    //Configuração da Paginação
    /*
    if ((count($arrObjsFilaDTO) == 0 && !$isGestorSipSei) || !$isPermiteAssociacao) {
        $objDTO = null;
    } else {
        $objDTO = $objMdUtlControleDsmpRN->getObjDTOParametrizadoMeusProcessos(array($arrObjsFilaUsuDTO, $isGestorSipSei, $arrObjsTpProcesso, $paramTpCtrl, $arrPostDados));
    }
    */
    $objDTO = $objMdUtlControleDsmpRN->getObjDTOParametrizadoMeusProcessos(array($arrObjsFilaUsuDTO, $isGestorSipSei, $arrObjsTpProcesso, $paramTpCtrl, $arrPostDados));

    if (!is_null($objDTO)) {

        $objDTO->retNumIdMdUtlAjustePrazo();
        $objDTO->retNumIdMdUtlAdmRelControleDsmp();
        $objDTO->retNumIdMdUtlControleDsmp();
        $objDTO->retNumIdUnidade();
        $objDTO->retStrNomeTipoProcedimento();
        $objDTO->retStrStaAtendimentoDsmp();
        $objDTO->retStrSiglaUnidade();
        $objDTO->retStrProtocoloProcedimentoFormatado();
        $objDTO->retStrNomeFila();
        $objDTO->retNumIdFila();
        $objDTO->retNumTempoExecucao();
        $objDTO->retStrNomeUsuarioDistribuicao();
        $objDTO->retDthAtual();
        $objDTO->retDthPrazoTarefa();
        $objDTO->retStrSiglaUsuarioDistribuicao();
        $objDTO->retNumIdMdUtlAnalise();
        $objDTO->retNumIdMdUtlTriagem();
        $objDTO->retStrStaSolicitacaoAjustePrazo();
        $objDTO->retNumIdUsuarioDistribuicao();
        $objDTO->retNumIdMdUtlContestRevisao();
        $objDTO->retStrNomeTpCtrlDsmp();
        $objDTO->retNumIdMdUtlAdmTpCtrlDesemp();

        $objMdUtlTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();
        
        if( !empty($arrListaIdsTpControle)){
            $isDadosParametrizados = $objMdUtlTpCtrlRN->validaNovosDadosParametrizacao($arrListaIdsTpControle);
        }

        if ($selAtividadeCampo != '') {
            $objDTO->setStrValorAtividadeSelectUtl($selAtividadeCampo);
            $idsTriagem = $objMdUtlControleDsmpRN->pesquisarAtividade($objDTO);

            if (count($idsTriagem) > 0) {
                $objDTO->setNumIdMdUtlTriagem($idsTriagem, InfraDTO::$OPER_IN);
            } else {
                $objDTO = null;
            }
        }
    }
    $count = 0;
    //Combo de Atividade
    if (!is_null($objDTOCombo)) {
        $objDTOCombo->retNumIdMdUtlTriagem();

        $arrObjsCombo = $objMdUtlControleDsmpRN->listarProcessos($objDTOCombo);
        $idTriagemCombo = InfraArray::converterArrInfraDTO($arrObjsCombo, 'IdMdUtlTriagem');
        $idTriagemCombo = MdUtlControleDsmpINT::removeNullsTriagem($idTriagemCombo);
        $count = count($idTriagemCombo);
    }

    $arrayObjs = [];

    if ($count > 0) {

        $arrObjsTriagemAtividade = $objMdUtlRelTriagemAtvRN->getObjsTriagemAtividade($idTriagemCombo);
        $selAtividade = MdUtlAdmAtividadeINT::montarSelectAtividadesTriagem($selAtividadeCampo, $arrObjsTriagemAtividade);

        foreach ($arrObjsTriagemAtividade as $obj) {
            if (array_key_exists($obj->getNumIdMdUtlTriagem(), $arrayObjs)) {
                $arrayObjs[$obj->getNumIdMdUtlTriagem()] = array();
            } else {
                $arrayObjs[$obj->getNumIdMdUtlTriagem()] = $obj->getStrNomeAtividade() . ' - ' . MdUtlAdmPrmGrINT::convertToHoursMins($obj->getNumTempoExecucao());
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
        $numRegistros = count($arrObjs);
        MdUtlControleDsmpINT::setNomeAtividade($arrObjs, $arrayObjs);
        PaginaSEI::getInstance()->processarPaginacao($objDTO);

        if ($numRegistros > 0) {
            //Tabela de resultado.
            $displayNoneCheck = 'style="display:none"';
            $strResultado .= '<table width="99%" class="infraTable" summary="Processos" id="tbCtrlProcesso">';
            $strResultado .= '<caption class="infraCaption">';
            $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Meus Processos', $numRegistros);
            $strResultado .= '</caption>';


            //Cabeçalho da Tabela
            $strResultado .= '<tr>';
            $strResultado .= '<th ' . $displayNoneCheck . ' class="infraTh" align="center" width="1%" >' . PaginaSEI::getInstance()->getThCheck() . '</th>';
            $strResultado .= '<th class="infraTh" style="width: 160px">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Processo', 'ProtocoloProcedimentoFormatado', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Atividade', 'NomeAtividadeTriagem', $arrObjs) . '</th>';

            //ADICIONAR ORDENAÇÃO PARA OS OUTROS CAMPOS
            $strResultado .= '<th class="infraTh" style="width: 110px">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Tipo de Controle', 'NomeTpCtrlDsmp', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh"  >' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Fila', 'NomeFila', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" style="text-align: left;width: 90px">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Tempo de Execução', 'TempoExecucao', $arrObjs) . ' </th>';
            $strResultado .= '<th class="infraTh" style="width: 160px">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Situação', 'StaAtendimentoDsmp', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" style="width: 80px">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Data Situação', 'Atual', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" style="width: 80px">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Prazo', 'PrazoTarefa', $arrObjs) . ' </th>';
            $strResultado .= '<th class="infraTh" style="width: 80px">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Prazo Resposta', 'PrazoResposta', $arrObjs) . ' </th>';
            $strResultado .= '<th class="infraTh" style="width: 40px">Ações</th>';
            $strResultado .= '<th class="infraTh" style="display: none">Última Fila</th>';
            $strResultado .= '</tr>';

            //Linhas
            $strCssTr = '';
            for ($i = 0; $i < $numRegistros; $i++) {
                $strId = $arrObjs[$i]->getDblIdProcedimento();
                $strProcesso = $arrObjs[$i]->getStrProtocoloProcedimentoFormatado();
                $strFila = $arrObjs[$i]->getStrNomeFila();
                $idFila = $arrObjs[$i]->getNumIdFila();
                $strTpProcesso = $arrObjs[$i]->getNumIdTipoProcedimento();
                $nomeTpProcesso = $arrObjs[$i]->getStrNomeTipoProcedimento();
                $strStatus = trim($arrObjs[$i]->getStrStaAtendimentoDsmp());
                $numIdControleDsmp = $arrObjs[$i]->getNumIdMdUtlControleDsmp();
                $numTempoExecucao = $arrObjs[$i]->getNumTempoExecucao();
                $numIdTriagem = $arrObjs[$i]->getNumIdMdUtlTriagem();
                $numIdAjustePrazo = $arrObjs[$i]->getNumIdMdUtlAjustePrazo();
                $numIdContestRevisao = $arrObjs[$i]->getNumIdMdUtlContestRevisao();
                $strNmTpCtrl = $arrObjs[$i]->getStrNomeTpCtrlDsmp();
                $strNomeAtividade = array_key_exists($numIdTriagem, $arrayObjs) ? $arrayObjs[$numIdTriagem] : '';
                
                $linkAtvTriagem = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_atividade_triagem_listar&acao_origem=md_utl_distrib_usuario_listar&id_triagem=' . $numIdTriagem . '');

                if (is_array($strNomeAtividade)) {
                    $strNomeAtividade = '<a href="javascript:void(0);" onclick="infraAbrirJanela(\'' . $linkAtvTriagem . '\',\'urlAtividadeTriagemMult\',650,500,)" alt="Múltiplas" title="Múltiplas" class="ancoraPadraoAzul"> Múltiplas </a>';
                }

                $objStatusAnterior = $objMdUtlHistControleRN->getStatusAnterior($strId);
                $statusAnterior = !is_null($objStatusAnterior) ? $objStatusAnterior->getStrStaAtendimentoDsmp() : null;

                $arrSituacao = MdUtlControleDsmpINT::retornaArrSituacoesControleDsmpCompleto();
                $linkProcedimento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=md_utl_meus_processos_dsmp_listar&id_procedimento=' . $strId . '');
                $data = explode(' ', $arrObjs[$i]->getDthAtual());
                $dataFormatada = $data[0];
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

                $bolRegistroAtivo = true;

                $isPossuiAnalise = $objMdUtlControleDsmpRN->verificaTriagemPossuiAnalise($arrObjs[$i]);
                $arrCtrlUrls = MdUtlControleDsmpINT::retornaUrlsAcessoDsmp($strStatus, $isPossuiAnalise, $strId, $idFila, $arrObjs[$i]->getNumIdUsuarioDistribuicao(), true);
                $linkStatus = MdUtlControleDsmpINT::retornaLinkStatus($arrCtrlUrls, $strStatus);

                $status = !is_null($strStatus) ? PaginaSEI::tratarHTML($arrSituacao[$strStatus]) : PaginaSEI::tratarHTML($arrSituacao[0]);

                $idContestRevisao = $arrObjs[$i]->getNumIdMdUtlContestRevisao();
                $strSituacao = '';
                if ($idContestRevisao) {
                    $objContestRevisaoDTO = new MdUtlContestacaoDTO();
                    $objContestRevisaoRN = new MdUtlContestacaoRN();
                    $objContestRevisaoDTO->setNumIdMdUtlContestRevisao($idContestRevisao);
                    $objContestRevisaoDTO->retTodos();
                    $arrObjContestRevisao = $objContestRevisaoRN->consultar($objContestRevisaoDTO);
                    if ($arrObjContestRevisao->getStrStaSolicitacao()) {
                        $strSituacao = $arrObjContestRevisao->getStrStaSolicitacao() ? $arrObjContestRevisao->getStrStaSolicitacao() : '';
                    }
                }

                if ($strStatus == MdUtlControleDsmpRN::$SUSPENSO) {
                    $dataPrazoFormatada = 'Prazo Suspenso';
                } else if ($strStatus == MdUtlControleDsmpRN::$INTERROMPIDO) {
                    $dataPrazoFormatada = 'Prazo Interrompido';
                }

                $strIdContestRevisao = $_GET['id_contest_revisao'];

                //Linha Acessada
                $strCssTr = !$bolRegistroAtivo ? '<tr class="trVermelha ">' : ($strCssTr == '<tr class="infraTrClara ">' ? '<tr class="infraTrEscura ">' : '<tr class="infraTrClara ">');
                $strCssTr = in_array($strId, $arrIdProcedimentoDistrib) ? '<tr class="infraTrAcessada">' : $strCssTr;
                $strResultado .= $strCssTr;

                //Linha Checkbox
                $strResultado .= '<td ' . $displayNoneCheck . ' align="center" valign="top">';
                $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId, $strProcesso);
                $strResultado .= '</td>';

                //Linha Nome
                $strResultado .= '<td class="tdIdProcesso" style="display: none">';
                $strResultado .= $strId;
                $strResultado .= '</td>';

                //Linha Nome
                $strResultado .= '<td class="tdNomeProcesso">';
                $strResultado .= '<a href="javascript:void(0);" onclick="window.open(\'' . $linkProcedimento . '\')" alt="' . $nomeTpProcesso . '" title="' . $nomeTpProcesso . '" class="ancoraPadraoAzul">' . $strProcesso . '</a>';
                $strResultado .= '</td>';

                //Linha Atividade
                $strResultado .= '<td class="tdNomeAtividade">';
                $strResultado .= $strNomeAtividade;
                $strResultado .= '</td>';

                 //Linha Tipo Controle
                 $strResultado .= '<td class="tdTpControle">';
                 $strResultado .= PaginaSEI::tratarHTML($strNmTpCtrl);
                 $strResultado .= '</td>';

                //Linha Fila Padrão
                $strResultado .= '<td class="tdFilaProcesso">';
                $strResultado .= PaginaSEI::tratarHTML($strFila);
                $strResultado .= '</td>';

                if( $strStatus == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE && $numTempoExecucao == 0 ){
                    $vlrUndEsf = MdUtlAdmPrmGrUsuINT::_retornaUnidEsforcoAtividadesAnalisadas( $arrObjs[$i]->getNumIdMdUtlAnalise() );
                    $valorCalculoTempoExecucao = MdUtlAdmPrmGrINT::convertToHoursMins(MdUtlAdmPrmGrUsuINT::retornaCalculoPercentualDesempenho($vlrUndEsf, $arrObjs[$i]->getNumIdMdUtlAdmTpCtrlDesemp(), $arrObjs[$i]->getNumIdUsuarioDistribuicao()));
                    $somaTmpExecucao += MdUtlAdmPrmGrINT::convertToMins($valorCalculoTempoExecucao);
                }
                else if( $strStatus == MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM && $numTempoExecucao == 0 ){
                    $vlrUndEsf = MdUtlAdmPrmGrUsuINT::_retornaUnidEsforcoTriagem( $arrObjs[$i]->getNumIdMdUtlTriagem() );
                    $valorCalculoTempoExecucao = MdUtlAdmPrmGrINT::convertToHoursMins(MdUtlAdmPrmGrUsuINT::retornaCalculoPercentualDesempenho($vlrUndEsf, $arrObjs[$i]->getNumIdMdUtlAdmTpCtrlDesemp(), $arrObjs[$i]->getNumIdUsuarioDistribuicao()));
                    $somaTmpExecucao += MdUtlAdmPrmGrINT::convertToMins($valorCalculoTempoExecucao);
                }
                else{
                    $valorCalculoTempoExecucao = MdUtlAdmPrmGrINT::convertToHoursMins(MdUtlAdmPrmGrUsuINT::retornaCalculoPercentualDesempenho($numTempoExecucao, $arrObjs[$i]->getNumIdMdUtlAdmTpCtrlDesemp(), $arrObjs[$i]->getNumIdUsuarioDistribuicao()));
                    $somaTmpExecucao += MdUtlAdmPrmGrINT::convertToMins($valorCalculoTempoExecucao);
                }

                //Linha Unidade de Esforço
                $strResultado .= '<td class="tdUniEsforco">';
                $strResultado .= PaginaSEI::tratarHTML($valorCalculoTempoExecucao);
                $strResultado .= '</td>';

                //Linha Fila Status
                $strResultado .= '<td class="tdStatusProcesso">';
                $strResultado .= '<a href="' . $linkStatus . '" alt="' . $nomeTpProcesso . '" title="' . $nomeTpProcesso . '" class="ancoraPadraoAzul">' . $status . '</a>';
                $strResultado .= '</td>';

                //Linha Data Registro Status
                $strResultado .= '<td class="tdDtRegistroStatus">';
                $strResultado .= PaginaSEI::tratarHTML($dataFormatada);
                $strResultado .= '</td>';

                //Linha Prazo
                $strResultado .= '<td class="tdPrazo">';
                $strResultado .= PaginaSEI::tratarHTML($dataPrazoFormatada);
                $strResultado .= '</td>';

                //Linha Data Prazo Resposta
                $strResultado .= '<td class="tdDtRegistroStatus">';
                $strResultado .= PaginaSEI::tratarHTML($prazoResposta);
                $strResultado .= '</td>';

                //Linha Controle Dsmp
                $strResultado .= '<td class="tdIdControleDsmp" style="display: none">';
                $strResultado .= $numIdControleDsmp;
                $strResultado .= '</td>';

                $strResultado .= '<td style="text-align: center">';

                $strResultado .= !$numIdContestRevisao ? MdUtlControleDsmpINT::getIconePadronizadoAjustePrazo($strStatus, $isDataPermitida, $arrObjs[$i]->getNumIdMdUtlAjustePrazo(), $arrObjs[$i]->getStrStaSolicitacaoAjustePrazo(), $numIdControleDsmp, $isDadosParametrizados[$arrObjs[$i]->getNumIdMdUtlAdmTpCtrlDesemp()], $strId, $statusAnterior) : '';
                $strResultado .= !$numIdAjustePrazo ? MdUtlControleDsmpINT::getIconePadronizadoContestacao($strStatus, $numIdControleDsmp, $arrObjs[$i], $numIdTriagem, $isDadosParametrizados[$arrObjs[$i]->getNumIdMdUtlAdmTpCtrlDesemp()], $strSituacao) : '';
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
if (0) { ?>
    <style><? }
    ?>
        .bloco {
            position: relative;
            float: left;
            margin-top: 1%;
            width: 90%;
        }

        .clear {
            clear: both;
        }

        .padraoSelect {
            margin-top: 1px;
            height: 21px;
            width: 78%;
        }

        .padraoInput {
            width: 78%;
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

        .div_comun{
            position: relative;
            margin-top: 9px;
            width: 20%;
        }

        #divSomaTmpExecucao {
            margin-top: 105px;
            position: absolute;
        }

        #divCargaHrDistribExec {
            margin-top: 111px;
            position: absolute;
            margin-bottom: 10px;
        }

        #divCargaHrPadrao {
            margin-top: 123px;
            position: absolute;
            margin-bottom: 10px;
        }

        #divCargaHrDistrib {
            margin-top: 132px;
            position: absolute;
            margin-bottom: 10px;
        }
        <?php if( $numRegistros == 0 ){ ?>

            div.infraAreaTabela{
                margin-top: -17px;
            }

        <?php } ?>
        .tdNomeProcesso, .tdStatusProcesso {
            font-size: 0.92em;
        }

        a.ancoraPadraoAzul{
            padding: 0;
        }

        <?
        if (0) { ?></style><?
} ?>

<?php PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
require_once 'md_utl_funcoes_js.php';

if (0){ ?>
    <script type="text/javascript"><?}?>

        var msg24 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_24)?>';
        var msg25 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_25)?>';

        function inicializar() {
            infraEfeitoTabelas();
            var urlCtrlProcessos = document.getElementById('hdnUrlControleProcessos').value;
            var idParam = document.getElementById('hdnIdParametroCtrlUtl').value;
            var tpCtrl = document.getElementById('hdnValidaTipoControleUtl').value;
            var isProcessoConcl = '<?php echo $isProcessoConcluido ?>';
            var msgConclusao = '<?php echo $msg107 ?>';

            if (tpCtrl == 0) {
                alert(msg24);
                window.location.href = urlCtrlProcessos;
                return false;
            }

            if (idParam == 0) {
                alert(msg25);
                window.location.href = urlCtrlProcessos;
                return false;
            }

            if (isProcessoConcl == 1) {
                if (confirm(msgConclusao)) {
                    document.getElementById('hdnIsConcluirProcesso').value = 1;
                    document.getElementById("frmTpControleLista").submit();
                }
            }

            // traz a carga distribuida periodo
            let selTpCtrl = document.getElementById('selTpControle').value;
            let todosValoresTpCtrl = new Array();
            
            if( selTpCtrl != '' ){
                todosValoresTpCtrl.push( selTpCtrl );
            }else{
                $("#selTpControle option").each(function(){
                    if( $( this ).val() != '' ) todosValoresTpCtrl.push( $( this ).val() );
                });

                if( todosValoresTpCtrl.length == 0 ){
                    <?php foreach( $arrTpControleUsuMembroUnid as $idTpCtrl ) { ?>
                        todosValoresTpCtrl.push(<?= $idTpCtrl ?>);
                    <?php } ?>                    
                }
            }

            getCargaHrDistribuida( todosValoresTpCtrl );

            addEnter();
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

        function addEnter() {
            document.getElementById('txtProcessoUtlMs').addEventListener("keypress", function (evt) {
                addPesquisarEnter(evt);
            });

        }

        function addPesquisarEnter(evt) {
            var key_code = evt.keyCode ? evt.keyCode :
                evt.charCode ? evt.charCode :
                    evt.which ? evt.which : void 0;
            q
            if (key_code == 13) {
                pesquisar();
            }

        }

        function pesquisar() {
            document.getElementById('frmTpControleLista').action = "<?= $strUrlPesquisar ?>";
            document.getElementById('frmTpControleLista').submit();
        }

        function fechar() {
            location.href = "<?= $strUrlFechar ?>";
        }

        function atribuirProximoModal() {
            <?php if( !empty( $arrObjTpControle ) and count($arrObjTpControle) > 1 ) { ?>
                infraAbrirJanela("<?= $strUrlDistrMim ?>",'SelecionarTipoControle', 500, 250, 'location=0,status=1,resizable=1,scrollbars=1');                
            <?php } else { ?>
                atribuirProximo( <?= $idTipoControle ?> );
            <?php } ?>
        }

        window.atribuirProximo = function(idTpCtrl) {

            $.ajax({
                url: "<?= $strUrlAjaxAtribuirProximo ?>",
                type: 'post',
                dataType: 'xml',
                data: { idTpCtrl: idTpCtrl },
                beforeSend: function(){
                    infraExibirAviso(false);
                },
                success: function (result) {
                    alert('O Processo sob número: ' + $(result).find('ProtocoloFormatado').text() + ' foi distribuído para você.');
                    //window.location.reload();
                },
                error: function (e) {
                    alert('Seguindo as parametrizações de prioridade nenhum processo foi encontrado para Distribuição!');
                    console.error('Erro ao processar o XML do SEI: ' + e.responseText);
                },
                complete: function(xhr){
                    infraAvisoCancelar();
                    if(xhr.status == 200){
                       window.location.reload();
                    }
                }
            });
        }

        function getCargaHrDistribuida( idsTpCtrl ){
            var params = {
                idUsuarioParticipante: "<?= SessaoSEI::getInstance()->getNumIdUsuario() ?>",
                idTipoControle: idsTpCtrl
            };

            $.ajax({
                url: "<?= $strUrlBuscarDadosCarga ?>",
                type: 'POST',
                data: params,
                dataType: 'XML',
                success: function (r) {
                    var cargaDisti    = $(r).find('ValorUndEs').text();
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

        <?php if (0){ ?>
    </script><? } ?>

<?php PaginaSEI::getInstance()->fecharJavaScript(); ?>


<?php
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

// textos dos tooltips
$txtTooltipTotalTempoPendenteExecucao = 'O Total corresponde à soma do Tempo de Execução de cada processo sob responsabilidade do usuário logado, conforme constante na tabela de listagem abaixo, independentemente de quando tenha sido Distribuído.\n \n O Tempo de Execução de Triagem é padrão por Fila do Controle de Desempenho.\n \n O Tempo de Execução de Análise depende das Atividades incluídas na fase de Triagem. Contudo, ao final, o Membro Participante que realizar a Análise somente ganhará o Tempo de Execução das Atividades que tenha entregado pelo menos um Produto.\n \n O Tempo de Execução de Avaliação depende de cada Produto entregue nas Atividades na fase de Análise.';

$txtTooltipTotalTempoExecutadoPeriodo = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDinamicoMeusProcessos($idTipoControle);

$txtTooltipCargaHorariaDistribuidaPeriodo = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDinamicoCargaHorariaDistribuidaPeriodo($idTipoControle);

$txtTooltipCargaHorariaPadrao = 'A Carga Horária Padrão no Período corresponde ao tempo da jornada de trabalho esperada para o Período de distribuição e acompanhamento, conforme definido nos parâmetros gerais do Tipo de Controle de Desempenho, abatendo Feriados que ocorram no Período ou possível jornada reduzida do Participante neste Tipo de Controle de Desempenho.';
?>
    <form id="frmTpControleLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(
              SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
          ) ?>">

        <?php
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados();
        ?>
        <div class="bloco div_comun" id="divProcesso">
            <label id="lblProcesso" for="txtProcessoUtlMs" class="infraLabelOpcional">
                Processo:
            </label>

            <div class="clear"></div>

            <input type="text" id="txtProcessoUtlMs" name="txtProcessoUtlMs" class="inputFila infraText padraoInput"
                   size="30"
                   value="<?php echo $txtProcessoCampo ?>"
                   maxlength="100" tabindex="502"/>
        </div>

        <div id="divTpCtrl" class="bloco div_comun">
            <label id="lblTpControle" for="selTpControle" accesskey="" class="infraLabelOpcional">Tipo de Controle:</label>
            <select id="selTpControle" name="selTpControle" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selTpControle ?>
            </select>
        </div>

        <div id="divFila" class="bloco div_comun">
            <label id="lblFila" for="selFilaUtlMs" accesskey="" class="infraLabelOpcional">Fila:</label>
            <select id="selFilaUtlMs" name="selFilaUtlMs" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selFila ?>
            </select>
        </div>


        <div id="divTipoProcesso" class="bloco div_comun">
            <label id="lblTipoProcesso" for="selTipoProcessoUtlMs" accesskey="" class="infraLabelOpcional">Tipo de
                Processo:</label>
            <select id="selTipoProcessoUtlMs" name="selTipoProcessoUtlMs" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <option value=""></option>
                <?= $selTipoProcesso ?>
            </select>
        </div>


        <div id="divStatus" class="bloco div_comun">
            <label id="lblStatus" for="selStatusUtlMs" accesskey="" class="infraLabelOpcional">Situação:</label>
            <select id="selStatusUtlMs" name="selStatusUtlMs" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selStatus ?>
            </select>
        </div>

        <div id="divAtividade" style="" class="bloco div_comun">
            <label id="lblAtividade" for="selAtividadeUtlMs" accesskey="" class="infraLabelOpcional">Atividade:</label>
            <select id="selAtividadeUtlMs" name="selAtividadeUtlMs" class="infraSelect padraoSelect" onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <option value=""></option>
                <?= $selAtividade ?>
            </select>
        </div>


        <input type="hidden" id="hdnSubmit" name="hdnSubmit" value="<?php echo $vlControlePost; ?>"/>
        <input type="hidden" id="hdnValidaTipoControleUtl" name="hdnValidaTipoControleUtl"
               value="<?= count($arrListaTpControle) == 0 ? 0 : 1 ?>"/>
        <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl"
               value="<?php echo is_null($idTipoControle) ? '0' : $idTipoControle; ?>"/>
        <input type="hidden" id="hdnIdParametroCtrlUtl" name="hdnIdParametroCtrlUtl"
               value="<?php echo $isParametrizado ? '1' : '0'; ?>"/>
        <input type="hidden" id="hdnUrlControleProcessos" name="hdnUrlControleProcessos"
               value="<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']); ?>"/>

        <input type="hidden" id="hdnIsConcluirProcesso" name="hdnIsConcluirProcesso"
               value="<?php echo $isProcessoAutorizadoConcluir ?>"/>
        <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento"
               value="<?php echo $idProcedimentoMeusProcessos ?>"/>

        <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>

        <div id="divSomaTmpExecucao">
            <label>Total de Tempo Pendente de Execução:
                <a id="btnAtividade" <?= PaginaSEI::montarTitleTooltip($txtTooltipTotalTempoPendenteExecucao) ?>>
                    <img id="imgAjudaAtividade" border="0" style="width: 16px;height: 16px;margin-bottom: -3px;"
                            src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
                </a>
                <?= MdUtlAdmPrmGrINT::convertToHoursMins($somaTmpExecucao) ?>
            </label>
        </div>

        <br/>

        <div id="divCargaHrDistribExec" style='padding-top: 3px;'>
            <label id="lblCargaHrDistribExec" class="infraLabelOpcional">Total de Tempo Executado no Período:</label>
            <a id="btnAtividade" <?= PaginaSEI::montarTitleTooltip($txtTooltipTotalTempoExecutadoPeriodo) ?>>
                <img id="imgAjudaAtividade" border="0" style="width: 16px;height: 16px;margin-bottom: -3px;"
                     src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
            </a>
            <span id="spnCargaHrDistribExec" style='font-size:12px;'>0min</span>
        </div>

        <br/>

        <div id="divCargaHrPadrao">
            <label id="lblCargaHrPadrao" class="infraLabelOpcional">Carga Horária Padrão no Período:</label>
            <a id="btnAtividade" <?= PaginaSEI::montarTitleTooltip($txtTooltipCargaHorariaPadrao) ?>>
                <img id="imgAjudaAtividade" border="0" style="width: 16px;height: 16px;margin-bottom: -3px;"
                     src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
            </a>
            <span id="spnCargaHrPadrao" style='font-size:12px;'>0min</span>
        </div>

        <br/>

        <div id="divCargaHrDistrib">
            <label id="lblCargaHrDistrib" class="infraLabelOpcional">Carga Horária Distribuída no Período:</label>
            <a id="btnAtividade" <?= PaginaSEI::montarTitleTooltip($txtTooltipCargaHorariaDistribuidaPeriodo) ?>>
                <img id="imgAjudaAtividade" border="0" style="width: 16px;height: 16px;margin-bottom: -3px;"
                     src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
            </a>
            <span id="spnCargaHrDistrib" style='font-size:12px;'>0min</span>
        </div>

        <div style="margin-top: 130px;">
            <?php
            PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
            ?>
        </div>
        <?php
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
