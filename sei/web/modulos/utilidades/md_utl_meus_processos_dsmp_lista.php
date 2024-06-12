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
$selPeriodoCampo = array_key_exists('selPeriodo', $_POST) ? $_POST['selPeriodo'] : PaginaSEI::getInstance()->recuperarCampo('selPeriodo');

$somaTmpExecucao = 0;
$idProcedimentoMeusProcessos = array_key_exists('id_procedimento', $_GET) ? $_GET['id_procedimento'] : $_POST['hdnIdProcedimento'];
$strSituacao = '';

$linkCtrlProcesso = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acaoorigem=' . $_GET['acao']);

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

/*
if ($isProcessoAutorizadoConcluir == 1) {
    $_POST['hdnIsConcluirProcesso'] = 0;
    $isProcessoAutorizadoConcluir = 0;

    $objEntradaConcluirProcessoAPI = new EntradaConcluirProcessoAPI();
    $objEntradaConcluirProcessoAPI->setIdProcedimento($idProcedimentoMeusProcessos);

    $objSEIRN = new SeiRN();
    $objSEIRN->concluirProcesso($objEntradaConcluirProcessoAPI);
}
*/

$arrPostDados = array('txtProcesso' => $txtProcessoCampo, 'selFila' => $selFilaCampo, 'selTipoProcesso' => $selTipoProcessoCampo, 'selStatus' => $selStatusCampo, 'selTpControle' => $selTpControleCampo, 'selPeriodo' => $selPeriodoCampo);
$strNmUnidade = SessaoSEI::getInstance()->getStrSiglaUnidadeAtual();

//Array que sera usado para montar os tipos de controles da unidade
try {
    $arrObjTpControleUnidadeLogada     = $objMdUtlAdmTpCtrlUndRN->getArrayTipoControleUnidadeLogada();
    $arrListaTpControle                = array();
    $arrListaIdsTpControle             = array();
    if (!is_null($arrObjTpControleUnidadeLogada) ){
        foreach ($arrObjTpControleUnidadeLogada as $k => $v) {
            $arrListaTpControle[$v->getNumIdMdUtlAdmTpCtrlDesemp()] = $v->getStrNomeTipoControle();
            array_push( $arrListaIdsTpControle , $v->getNumIdMdUtlAdmTpCtrlDesemp() );
        }
    } else {
        $msgErro = MdUtlMensagemINT::setMensagemPadraoPersonalizada( MdUtlMensagemINT::$MSG_UTL_138 , [$strNmUnidade] );
        throw new InfraException($msgErro);
    }
} catch( InfraException $e ){
    PaginaSEI::getInstance()->adicionarMensagem( $e->__toString() , InfraPagina::$TIPO_MSG_ERRO );
    header('Location: ' . $linkCtrlProcesso );
    die;
}


// retorna tipos de controles da unidade onde o usuario � membro participante
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
    $objTpCtrlDTO->retNumIdMdUtlAdmPrmGr();

    $arrObjTpControle = $objTpCtrlRN->listar( $objTpCtrlDTO );
}

$objChefiaImediata = null;
if( !empty( $arrObjTpControle ) )
    $objChefiaImediata = ( new MdUtlAdmPrmGrUsuRN() )->validaUsuarioIsChefiaImediata(
            [InfraArray::converterArrInfraDTO($arrObjTpControle,'IdMdUtlAdmPrmGr')]
    );

$selTpControle = is_null($arrObjTpControle) ? array() : MdUtlAdmFilaINT::montarSelectTpControle($arrObjTpControle,'NumIdMdUtlAdmTpCtrlDesemp', 'StrNome',$_POST['selTpControle']);

$idTipoControle = null;
if( isset($_POST['selTpControle']) && !empty($_POST['selTpControle']) ) {
    $idTipoControle = $_POST['selTpControle'];
} else if( !is_null( $arrObjTpControle ) && count( $arrObjTpControle ) == 1 ) {
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

    $idsFilasPermitidasUsBasico = $isGestorSipSei || is_null($arrObjsFilaUsuDTO) ? null : InfraArray::converterArrInfraDTO($arrObjsFilaUsuDTO, 'IdMdUtlAdmFila');

    if ($isGestorSipSei) {
        $selFila = MdUtlAdmFilaINT::montarSelectFilas($selFilaCampo, $arrObjsFilaDTO);
    } else {
        $selFila = !is_null($idsFilasPermitidasUsBasico) ? $selFila = MdUtlAdmFilaINT::montarSelectFilas($selFilaCampo, $arrObjsFilaDTO, $idsFilasPermitidasUsBasico) : null;
    }

    $selStatus = !is_null($idsStatusPermitido) || $isGestorSipSei ? MdUtlControleDsmpINT::montarSelectStatusMeusProcessos($selStatusCampo, false, $idsStatusPermitido) : null;
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
        throw new InfraException("A��o '" . $_GET['acao'] . "' n�o reconhecida.");
    //endregion
}


//Verifica se � a��o Selecionar
$bolSelecionar = $_GET['acao'] == 'md_utl_adm_fila_selecionar';

if( $bolHabDistProxProc ) {
    $arrComandos[] = '<button type="button" accesskey="m" id="btnAtribuirProximo" onclick="atribuirProximoModal()" class="infraButton">
                    Distribuir para <span class="infraTeclaAtalho">m</span>im o Pr�ximo Processo</button>';
}

$arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" onclick="pesquisar()" class="infraButton">
                                        <span class="infraTeclaAtalho">P</span>esquisar</button>';

$arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                        Fe<span class="infraTeclaAtalho">c</span>har</button>';
$numRegistros = 0;
if ($isParametrizado) {
    $paramTpCtrl = is_null($idTipoControle) ? $arrListaIdsTpControle : $idTipoControle;

	// habilita/configura combo Periodo
	$strSelPeriodo = '';
	if ( $idTipoControle ) {
		if( empty($selPeriodoCampo) ) {
			$bolMarcarSelected = true;
			$dtIniPer          = null;
			$dtFinPer          = null;
		} else {
			$bolMarcarSelected = false;
			$arrDtaPeriodos    = explode('|',$selPeriodoCampo);
			$dtIniPer          = $arrDtaPeriodos[0];
			$dtFinPer          = $arrDtaPeriodos[1];
		}

		$arrDadosPeriodo = MdUtlControleDsmpINT::montarSelectPeriodoAnalise( $idTipoControle, SessaoSEI::getInstance()->getNumIdUsuario(), $dtIniPer, $dtFinPer, null, $bolMarcarSelected );

		// caso nao tenha sido selecionado a combo Periodo mas foi informado o Tipo de Ctrl, o valor padrao sera o periodo atual
		if ( empty( $arrPostDados['selPeriodo'] ) ) $arrPostDados['selPeriodo'] = $arrDadosPeriodo[2]['inicial'].'|'.$arrDadosPeriodo[2]['final'];

		$dtPerIniEUA     = implode( '-' , array_reverse( explode( '/' , $arrDadosPeriodo[2]['inicial'] ) ) );
		$isPeriodoAtual  = $arrDadosPeriodo['periodoAtual'][0] == $dtPerIniEUA ? 'S' : 'N';
		$arrPostDados['dadosPeriodo']['periodoSelecionado'] = $arrDadosPeriodo[2];
		$arrPostDados['dadosPeriodo']['isPeriodoAtual']     = $isPeriodoAtual;
		$strSelPeriodo = $arrDadosPeriodo[1];
	}

    $objDTOCombo = $objMdUtlControleDsmpRN->getObjDTOParametrizadoMeusProcessos(array($arrObjsFilaUsuDTO, $isGestorSipSei, $arrObjsTpProcesso, $paramTpCtrl, array()));
    //Configura��o da Pagina��o
    /*
    if ((count($arrObjsFilaDTO) == 0 && !$isGestorSipSei) || !$isPermiteAssociacao) {
        $objDTO = null;
    } else {
        $objDTO = $objMdUtlControleDsmpRN->getObjDTOParametrizadoMeusProcessos(array($arrObjsFilaUsuDTO, $isGestorSipSei, $arrObjsTpProcesso, $paramTpCtrl, $arrPostDados));
    }
    */
    $objDTO = $objMdUtlControleDsmpRN->getObjDTOParametrizadoMeusProcessos(array($arrObjsFilaUsuDTO, $isGestorSipSei, $arrObjsTpProcesso, $paramTpCtrl, $arrPostDados));

	$paramTpCtrl = is_array($paramTpCtrl) ? $paramTpCtrl : [(int) $paramTpCtrl];

    $arrObjsProcessosExecutados = $objMdUtlControleDsmpRN->montaProcessosExecutados(array($arrObjsFilaUsuDTO, $isGestorSipSei, $arrObjsTpProcesso, $paramTpCtrl, $arrPostDados));

	$strProcessosExecutados = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrObjsProcessosExecutados[0]);

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
        $objDTO->retNumTempoExecucaoAtribuido();
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

            if (!is_null($idsTriagem) ) {
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

        /* 
            Habilita o icone de solicitacao de ajuste de prazo pela regra: 
            Todas as Atividade n�o possu�rem o Prazo em Dias para An�lise ou for igual a zero
        */
        $arrHabAjustePrazoAti = MdUtlControleDsmpINT::habAjustePrazoAtv( $idTriagemCombo );

        $count = !is_null($idTriagemCombo) ? count($idTriagemCombo) : $count;
    }

    $arrayObjs = [];

    if ($count > 0) {

        $arrObjsTriagemAtividade = $objMdUtlRelTriagemAtvRN->getObjsTriagemAtividade($idTriagemCombo);
        $selAtividade = MdUtlAdmAtividadeINT::montarSelectAtividadesTriagem($selAtividadeCampo, $arrObjsTriagemAtividade);

        foreach ($arrObjsTriagemAtividade as $obj) {
            if (array_key_exists($obj->getNumIdMdUtlTriagem(), $arrayObjs)) {
                $arrayObjs[$obj->getNumIdMdUtlTriagem()] = array();
            } else {
                $arrayObjs[$obj->getNumIdMdUtlTriagem()] = $obj->getStrNomeAtividade() . ' - ' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[ $obj->getNumComplexidadeAtividade() ];
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
        $numRegistros = !is_null($arrObjs) ? count($arrObjs) : 0;
        MdUtlControleDsmpINT::setNomeAtividade($arrObjs, $arrayObjs);
        PaginaSEI::getInstance()->processarPaginacao($objDTO);
        
        if ($numRegistros > 0) {
            //Tabela de resultado.
            $displayNoneCheck = 'style="display:none"';
            $strResultado .= '<table class="infraTable" style="width:100%;" summary="Processos" id="tbCtrlProcesso">';
            $strResultado .= '<caption class="infraCaption">';
            $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Meus Processos', $numRegistros);
            $strResultado .= '</caption>';


            //Cabe�alho da Tabela
            $strResultado .= '<thead><tr>';
            $strResultado .= '<th ' . $displayNoneCheck . ' class="infraTh" align="center" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>';
            $strResultado .= '<th class="txt-col-center infraTh" style="min-width: 175px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Processo', 'ProtocoloProcedimentoFormatado', $arrObjs) . '</th>';
            $strResultado .= '<th class="txt-col-center infraTh" style="min-width: 155px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Atividade', 'NomeAtividadeTriagem', $arrObjs) . '</th>';

            //ADICIONAR ORDENA��O PARA OS OUTROS CAMPOS
            $strResultado .= '<th class="txt-col-center infraTh" style="min-width: 125px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Tipo de Controle', 'NomeTpCtrlDsmp', $arrObjs) . '</th>';
            $strResultado .= '<th class="txt-col-center infraTh" style="min-width: 125px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Fila', 'NomeFila', $arrObjs) . '</th>';
            $strResultado .= '<th class="txt-col-center infraTh" style="min-width: 120px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Tempo de Execu��o', 'TempoExecucao', $arrObjs) . ' </th>';
            $strResultado .= '<th class="txt-col-center infraTh" style="min-width: 155px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Situa��o', 'StaAtendimentoDsmp', $arrObjs) . '</th>';
            $strResultado .= '<th class="txt-col-center infraTh" style="min-width: 112px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Data Situa��o', 'Atual', $arrObjs) . '</th>';
            $strResultado .= '<th class="txt-col-center infraTh" style="min-width: 110px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Prazo', 'PrazoTarefa', $arrObjs) . ' </th>';
            $strResultado .= '<th class="txt-col-center infraTh" style="min-width: 110px;">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Prazo Resposta', 'PrazoResposta', $arrObjs) . ' </th>';
            $strResultado .= '<th class="txt-col-center infraTh" style="min-width: 65px;">A��es</th>';
            $strResultado .= '<th class="infraTh" style="display: none">�ltima Fila</th>';
            $strResultado .= '</tr></thead>';

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
                $numTempoExecucao = $arrObjs[$i]->getNumTempoExecucaoAtribuido();
                $numIdTriagem = $arrObjs[$i]->getNumIdMdUtlTriagem();
                $numIdAjustePrazo = $arrObjs[$i]->getNumIdMdUtlAjustePrazo();
                $numIdContestRevisao = $arrObjs[$i]->getNumIdMdUtlContestRevisao();
                $strNmTpCtrl = $arrObjs[$i]->getStrNomeTpCtrlDsmp();

                $strNomeAtividade = array_key_exists($numIdTriagem, $arrayObjs) ? $arrayObjs[$numIdTriagem] : '';
                $linkAtvTriagem = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_atividade_triagem_listar&acao_origem=md_utl_distrib_usuario_listar&id_triagem=' . $numIdTriagem . '');

                if (is_array($strNomeAtividade)) {
                    $strNomeAtividade = '<a href="#" onclick="infraAbrirJanelaModal(\'' . $linkAtvTriagem . '\',650,500)" alt="M�ltiplas" title="M�ltiplas" class="ancoraPadraoAzul"> M�ltiplas </a>';
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
                $strResultado .= '<a href="javascript:void(0);" onclick="window.open(\'' . $linkProcedimento . '\')" alt="' . $nomeTpProcesso . '" title="' . $nomeTpProcesso . '" class="ancoraPadraoAzul" style="padding:0px !important;">' . $strProcesso . '</a>';
                $strResultado .= '</td>';

                //Linha Atividade
                $strResultado .= '<td class="tdNomeAtividade">';
                $strResultado .= $strNomeAtividade;
                $strResultado .= '</td>';

                 //Linha Tipo Controle
                 $strResultado .= '<td class="tdTpControle">';
                 $strResultado .= PaginaSEI::tratarHTML($strNmTpCtrl);
                 $strResultado .= '</td>';

                //Linha Fila Padr�o
                $strResultado .= '<td class="tdFilaProcesso">';
                $strResultado .= PaginaSEI::tratarHTML($strFila);
                $strResultado .= '</td>';

                $somaTmpExecucao += $numTempoExecucao;
                $valorCalculoTempoExecucao = MdUtlAdmPrmGrINT::convertToHoursMins( $numTempoExecucao );

                //Linha Unidade de Esfor�o
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
                $strResultado .= !$numIdContestRevisao ? MdUtlControleDsmpINT::getIconePadronizadoAjustePrazo($strStatus, $isDataPermitida, $arrObjs[$i]->getNumIdMdUtlAjustePrazo(), $arrObjs[$i]->getStrStaSolicitacaoAjustePrazo(), $numIdControleDsmp, $isDadosParametrizados[$arrObjs[$i]->getNumIdMdUtlAdmTpCtrlDesemp()], $strId, $statusAnterior, $prazoResposta, !is_null($numIdTriagem) ? $arrHabAjustePrazoAti[$numIdTriagem] : null, $dataPrazoFormatada ?? ''  ) : '';
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
PaginaSEI::getInstance()->fecharStyle();
require_once "md_utl_geral_css.php";

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();

PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');

// textos dos tooltips
$txtTooltipTotalTempoPendenteExecucao = 'O Total corresponde � soma do Tempo de Execu��o de cada processo sob responsabilidade do usu�rio logado, conforme constante na tabela de listagem abaixo, independentemente de quando tenha sido Distribu�do.\n \n O Tempo de Execu��o de Triagem � padr�o por Fila do Controle de Desempenho.\n \n O Tempo de Execu��o de An�lise depende das Atividades inclu�das na fase de Triagem. Contudo, ao final, o Membro Participante que realizar a An�lise somente ganhar� o Tempo de Execu��o das Atividades que tenha entregado pelo menos um Produto.\n \n O Tempo de Execu��o de Avalia��o depende de cada Produto entregue nas Atividades na fase de An�lise.';

$txtTooltipTotalTempoExecutadoPeriodo = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDinamicoMeusProcessos($idTipoControle);

$txtTooltipCargaHorariaDistribuidaPeriodo = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDinamicoCargaHorariaDistribuidaPeriodo($idTipoControle);
?>
    <form id="frmTpControleLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(
              SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
          ) ?>">

        <?php
            PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
            PaginaSEI::getInstance()->abrirAreaDados('');
            $col_default = "col-sm-6 col-md-6 col-lg-4 mb-2";
        ?>
        
        <div class="row mb-3">
            <div class="<?= $col_default ?>" id="divProcesso">
                <label id="lblProcesso" for="txtProcessoUtlMs" class="infraLabelOpcional"> Processo: </label>
                <input type="text" id="txtProcessoUtlMs" name="txtProcessoUtlMs" class="inputFila infraText padraoInput form-control"                   
                   value="<?= $txtProcessoCampo ?>"
                   maxlength="100" tabindex="502"/>
            </div>

            <div class="<?= $col_default ?>" id="divTpCtrl">
                <label id="lblTpControle" for="selTpControle" accesskey="" class="infraLabelOpcional">Tipo de Controle:</label>
                <select id="selTpControle" name="selTpControle" class="infraSelect padraoSelect form-control"
                        onchange="mudouElemento(this);"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $selTpControle ?>
                </select>
            </div>

            <div class="<?= $col_default ?>" id="divFila">
                <label id="lblFila" for="selFilaUtlMs" accesskey="" class="infraLabelOpcional">Fila:</label>
                <select id="selFilaUtlMs" name="selFilaUtlMs" class="infraSelect padraoSelect form-control"
                        onchange="pesquisar();"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $selFila ?>
                </select>
            </div>

            <div class="<?= $col_default ?>" id="divFila">
                <label id="lblTipoProcesso" for="selTipoProcessoUtlMs" accesskey="" class="infraLabelOpcional">
                    Tipo de Processo:
                </label>
                <select id="selTipoProcessoUtlMs" name="selTipoProcessoUtlMs" class="infraSelect padraoSelect form-control"
                        onchange="pesquisar();"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <option value=""></option>
                    <?= $selTipoProcesso ?>
                </select>
            </div>

            <div class="<?= $col_default ?>" id="divStatus">
                <label id="lblStatus" for="selStatusUtlMs" accesskey="" class="infraLabelOpcional">Situa��o:</label>
                <select id="selStatusUtlMs" name="selStatusUtlMs" class="infraSelect padraoSelect form-control"
                        onchange="pesquisar();"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <?= $selStatus ?>
                </select>
            </div>

            <div class="<?= $col_default ?>" id="divAtividade">
                <label id="lblAtividade" for="selAtividadeUtlMs" accesskey="" class="infraLabelOpcional">Atividade:</label>
                <select id="selAtividadeUtlMs" name="selAtividadeUtlMs" class="infraSelect padraoSelect form-control" 
                        onchange="pesquisar();"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                    <option value=""></option>
                    <?= $selAtividade ?>
                </select>
            </div>

            <div class="<?= $col_default ?> mb-2" id="divPeriodo">
                <label id="lblPeriodo" for="selPeriodo"  class="infraLabelOpcional">Per�odo:</label>
                <select id="selPeriodo" id="selPeriodo" name="selPeriodo" onchange="pesquisar()" class="infraSelect padraoSelect form-control"
                        tabindex="<?= PaginaSEI::getInstance()->getProxTabDados()?>">
			        <?= $strSelPeriodo ?>
                </select>
            </div>
        </div>

        <?php if ( !empty( $objChefiaImediata ) ): ?>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-info">
                        <label class="infraLabelOpcional">
                            O Tempo de Execu��o sobre as atividades realizadas n�o ser� contabilizado, pois o usu�rio logado est� parametrizado como Chefia Imediata.
                        </label>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <input type="hidden" id="hdnSubmit" name="hdnSubmit" value="<?php echo $vlControlePost; ?>"/>
        <input type="hidden" id="hdnValidaTipoControleUtl" name="hdnValidaTipoControleUtl"
               value="<?= is_null($arrListaTpControle) ? 0 : 1 ?>"/>
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

	    <?php $col_def_labels_lg6 = "col-12 col-sm-10 col-md-10 col-lg-6 col-xl-6"; ?>
	    <?php $col_def_labels = "col-12"; ?>

        <div class="row mt-3">
            <div class="<?= $col_def_labels_lg6 ?> mb-2 justify-content-center align-self-center" id="divSomaTmpExecucao">
                <label class="infraLabelOpcional">
                    Total de Tempo Pendente de Execu��o:
                    <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                        name="ajuda" <?= PaginaSEI::montarTitleTooltip($txtTooltipTotalTempoPendenteExecucao,'Ajuda') ?> />
                    <span class="badge badge-primary badge-pill ml-1 p-2" style="vertical-align: top;">
                      <?= MdUtlAdmPrmGrINT::convertToHoursMins($somaTmpExecucao) ?>
                    </span>
                </label>
            </div>

            <div class="<?= $col_def_labels_lg6 ?> mb-2" id="divCargaHrDistribExec">
                <label id="lblCargaHrDistribExec" class="infraLabelOpcional">
                    Total de Tempo Executado no Per�odo:
                    <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                        name="ajuda" <?= PaginaSEI::montarTitleTooltip($txtTooltipTotalTempoExecutadoPeriodo,'Ajuda') ?> />
                    <span id="spnCargaHrDistribExec" class="badge badge-primary badge-pill ml-1 p-2" style="vertical-align: top;">
                        <span class="spinner-border spinner-border-sm"></span>
                        0min
                    </span>
                </label>
            </div>

            <div class="<?= $col_def_labels_lg6 ?> mb-2" id="divCargaPadPeriodo">
                <label id="lblCargaHrPadrao" class="infraLabelOpcional">
                    Carga Exig�vel no Per�odo:
                    <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                            name="ajuda" <?= PaginaSEI::montarTitleTooltip($txtTooltipTotalTempoExecutadoPeriodo,'Ajuda') ?> />
                    <span id="spnCargaHrPadrao" class="badge badge-primary badge-pill ml-1 p-2" style="vertical-align: top;">
                        <span class="spinner-border spinner-border-sm"></span>
                        0min
                    </span>
                </label>
            </div>

            <div class="<?= $col_def_labels_lg6 ?> mb-2" id="divCargaHrDistrib">
                <label id="lblCargaHrDistrib" class="infraLabelOpcional">
                    Carga Hor�ria Distribu�da no Per�odo:
                    <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                        name="ajuda" <?= PaginaSEI::montarTitleTooltip($txtTooltipCargaHorariaDistribuidaPeriodo,'Ajuda') ?> />
                    <span id="spnCargaHrDistrib" class="badge badge-primary badge-pill ml-1 p-2" style="vertical-align: top;">
                        <span class="spinner-border spinner-border-sm"></span>
                        0min
                    </span>
                </label>
            </div>

            <!-- NOVA LABEL -->
            <div class="<?= $col_def_labels_lg6 ?> mb-2" id="divCargaPadraoMenosExecutado">
                <label id="lblCargaPadraoMenosExecutado" class="infraLabelOpcional">
                    <!--Diferen�a entre Carga Exig�vel e o Tempo Executado no Per�odo:-->
                    Carga Exig�vel Pendente de Execu��o no Per�odo
                    <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                         name="ajuda" <?= PaginaSEI::montarTitleTooltip('Diferen�a entre a Carga Exig�vel e o Tempo Executado no per�odo selecionado.','Ajuda') ?> />
                    <span id="spnCargaPadraoMenosExecutado" class="badge badge-primary badge-pill ml-1 p-2" style="vertical-align: top;">
                        <span class="spinner-border spinner-border-sm"></span>
                        <?= MdUtlAdmPrmGrINT::convertToHoursMins(0) ?>
                    </span>
                </label>
            </div>

            <div class="<?= $col_def_labels_lg6 ?> mb-2" id="divExecutadoExcedente">
                <label id="lblExecutadoExcedente" class="infraLabelOpcional">
                    Tempo Excedente Executado no Per�odo
                    <img align="top" src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg" class="infraImg"
                         name="ajuda" <?= PaginaSEI::montarTitleTooltip('Tempo Executado que excedeu ao previsto na Carga Exig�vel no Per�odo','Ajuda') ?> />
                    <span id="spnExecutadoExcedente" class="badge badge-primary badge-pill ml-1 p-2" style="vertical-align: top;">
                        <span class="spinner-border spinner-border-sm"></span>
                        <?= MdUtlAdmPrmGrINT::convertToHoursMins(0) ?>
                    </span>
                </label>

            </div>
            <!-- FIM NOVA LABEL -->
        </div>

        <div class="row">
            <div class="col-12">
                <div style="background-color: #999999; font-weight: 500; border-bottom: 1px #bfb7b7 solid; padding-top: 5px; padding-bottom:5px;">
                    <span class="spnExpandirTodos">
                        <img id="imgExpandir_01" class="imgExpandir" style="margin-bottom: -7px;"
                             onclick="expandirTodos('div01', this)"
                             src=" <?php echo PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/exibir.svg' ?>"/>
                        <!--  no Per�odo Selecionado -->
                        <label class="infraLabelObrigatorio">Lista de Processos Em Andamento</label>
                    </span>
                    <div id="div01" style="display: none;" class="table-responsive">
                        <?php PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <div style="background-color: #999999; font-weight: 500; border-bottom: 1px #bfb7b7 solid; padding-top: 5px; padding-bottom:5px;">
                    <span class="spnExpandirTodos">
                        <img id="imgExpandir_02" class="imgExpandir" style="margin-bottom: -7px;"
                             onclick="expandirTodos('div02', this)"
                             src=" <?php echo PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/exibir.svg' ?>"/>
                        <label class="infraLabelObrigatorio">Lista de Processos Executados no Per�odo Selecionado</label>
                    </span>
                    <div id="div02" style="display: none;" class="table-responsive">
	                    <?php if ( empty($strProcessosExecutados) ) :?>
                            <div class="mt-2">
                                <label class="infraLabelOpcional pl-2">Nenhum registro encontrado.</label>
                            </div>
	                    <?php else: ?>
                            <table class="infraTable table" summary="ProcessosExecutados" id="tbProcessosExecutados">
                                <caption class="infraCaption">
                                    <?= PaginaSEI::getInstance()->gerarCaptionTabela('Meus Processos Executados', count($arrObjsProcessosExecutados[0])) ?>
                                </caption>
                                <tr>
                                    <th class="infraTh">Origem Registro + N�mero incrementado</th>
                                    <th class="infraTh" align="left" style="width: 13%;">Processo</th>
                                    <th class="infraTh" align="left" style="width: 18%;">Atividade</th>
                                    <th class="infraTh" align="left" style="width: 18%;">Tipo de Controle</th>
                                    <th class="infraTh" align="left" style="width: 14%;">Fila</th>
                                    <th class="infraTh" align="left" style="width: 10%;">Tempo de Execu��o</th>
                                    <th class="infraTh" align="left" style="width: 12%;">Tipo de A��o</th>
                                    <th class="infraTh" align="left" style="width: 18%;">Per�odo de Execu��o</th>
                                </tr>
                            </table>
                        <?php endif; ?>
                    </div>
                    <!-- Paragrado adicionado somente para compara��o com o tempo informado na Label do Tempo Executado -->
                    <?php if ( !empty( $arrObjsProcessosExecutados[1] ) ) : ?>
                        <p class="ml-2 pt-2">
                            <label class="infraLabelOpcional font-weight-bold">Total Tempo de Execu��o: <?= MdUtlAdmPrmGrINT::convertToHoursMins( $arrObjsProcessosExecutados[1] ) ?></label>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php PaginaSEI::getInstance()->fecharAreaDados(); ?>

    </form>

    <input type="hidden" id="hdnTbProcessosExecutados" value="<?= $strProcessosExecutados ?>" />

    <?php
        require_once 'md_utl_geral_js.php';
        require_once 'md_utl_funcoes_js.php';
    ?>
  
<script type="text/javascript">

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

        /*
        if (isProcessoConcl == 1) {
            if (confirm(msgConclusao)) {
                document.getElementById('hdnIsConcluirProcesso').value = 1;
                document.getElementById("frmTpControleLista").submit();
            }
        }
        */

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

        let tmpPeriodo = $('#selPeriodo').val();

        getCargaHrDistribuida( todosValoresTpCtrl, null, '', tmpPeriodo );

        // carrega tabela com processos executados
        if ( document.querySelector('#hdnTbProcessosExecutados').value.length > 0 ) {
            objTabelaDinamicaProcExecutados = new infraTabelaDinamica('tbProcessosExecutados', 'hdnTbProcessosExecutados', false, false, false);
            objTabelaDinamicaProcExecutados.gerarEfeitoTabela = true;

            $('#tbProcessosExecutados > tbody > tr > td').css({
                'display': 'table-cell',
                'vertical-align': 'inherit'
            });

            let tbl = document.querySelector('#tbProcessosExecutados');
            [tbl.rows].map( row => $( row ).find('th:first , td:first').css('display','none') );
        }
        // Deixa visivel as duas Grids
        expandirTodos( 'div01' , document.querySelector('#imgExpandir_01') );
        expandirTodos( 'div02' , document.querySelector('#imgExpandir_02') );

        addEnter();
        
        // Adiciona a class "infraLabelOpcional" quando n�o retorna nenhum registro na grid
        // para ficar na mesma formata��o das labels que retornam dados referentes a tempo
        if( $('#divInfraAreaTabela').find('table').length == 0 ){
            $('#divInfraAreaPaginacaoSuperior').hide();
            $('#divInfraAreaPaginacaoInferior').hide();
            $('#divInfraAreaTabela').addClass('mt-2');
            $('#divInfraAreaTabela > label').addClass('infraLabelOpcional pl-2');
        }else{
            if( $('#divInfraAreaPaginacaoSuperior').find('select').length == 0 ){
                $('#divInfraAreaPaginacaoSuperior').hide();
                $('#divInfraAreaPaginacaoInferior').hide();
            }
        }

        // seta cor que contem a msg que quantidade de registros nas grids para ficar preto
        $('.infraCaption').css('color','black');
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
        
        if (key_code == 13) {
            pesquisar();
        }
    }

    function mudouElemento(elem){
        if ( $( elem ).attr('id') == 'selTpControle') {
            $('#selPeriodo').val('');
        }
        pesquisar();
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
                if( $(result).find('Erro').length > 0 ){
                    alert( $(result).find('Msg').text() );
                }else{
                    alert('O Processo sob n�mero: ' + $(result).find('ProtocoloFormatado').text() + ' foi distribu�do para voc�.');
                }                
            },
            error: function (e) {
                alert('Seguindo as parametriza��es de prioridade nenhum processo foi encontrado para Distribui��o!');
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

    function validarDataPrazo(data,link){
        if ( data == '' || data == null ) {
            alert("<?= MdUtlMensagemINT::$MSG_UTL_131 ?>");
            return false;
        }
        window.location.href = link;
    }
</script>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();