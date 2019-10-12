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

PaginaSEI::getInstance()->salvarCamposPost(array('txtProcessoUtlMs', 'selFilaUtlMs', 'selTipoProcessoUtlMs', 'selStatusUtlMs', 'selAtividadeUtlMs'));

$txtProcessoCampo     = array_key_exists('txtProcessoUtlMs', $_POST) ? $_POST['txtProcessoUtlMs'] : PaginaSEI::getInstance()->recuperarCampo('txtProcessoUtlMs');
$selFilaCampo         = array_key_exists('selFilaUtlMs', $_POST) ? $_POST['selFilaUtlMs'] : PaginaSEI::getInstance()->recuperarCampo('selFilaUtlMs');
$selTipoProcessoCampo = array_key_exists('selTipoProcessoUtlMs', $_POST) ? $_POST['selTipoProcessoUtlMs'] : PaginaSEI::getInstance()->recuperarCampo('selTipoProcessoUtlMs');
$selStatusCampo       = array_key_exists('selStatusUtlMs', $_POST) ? $_POST['selStatusUtlMs'] : PaginaSEI::getInstance()->recuperarCampo('selStatusUtlMs');
$selAtividadeCampo    = array_key_exists('selAtividadeUtlMs', $_POST) ? $_POST['selAtividadeUtlMs'] : PaginaSEI::getInstance()->recuperarCampo('selAtividadeUtlMs');

$somaUndEsforco = '';
$idProcedimentoMeusProcessos = array_key_exists('id_procedimento', $_GET) ? $_GET['id_procedimento'] : $_POST['hdnIdProcedimento'];

$objFilaRN                 = new MdUtlAdmFilaRN();
$objMdUtlAdmFilaPrmGrUsuRN = new MdUtlAdmFilaPrmGrUsuRN();
$objMdUtlAdmTpCtrlUsuRN    = new MdUtlAdmRelTpCtrlDesempUsuRN();
$objMdUtlControleDsmpRN    = new MdUtlControleDsmpRN();
$objMdUtlAdmTpCtrlUndRN    = new MdUtlAdmRelTpCtrlDesempUndRN();
$objMdUtlRelTriagemAtvRN   = new MdUtlRelTriagemAtvRN();
$objMdUtlHistControleRN    = new MdUtlHistControleDsmpRN();
$objRegrasGerais           = new MdUtlRegrasGeraisRN();

if($idProcedimentoMeusProcessos != null && $idProcedimentoMeusProcessos != '') {
    $objProcedimentoDTO = $objRegrasGerais->getObjProcedimentoPorId($idProcedimentoMeusProcessos);
    $strNumeroProcedimento = $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado();
    $msg107 = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_107, array($strNumeroProcedimento, SessaoSEI::getInstance()->getStrSiglaUnidadeAtual()));
}else{
    $msg107 = '';
}

if($isProcessoAutorizadoConcluir == 1){
    $_POST['hdnIsConcluirProcesso'] = 0;
    $isProcessoAutorizadoConcluir = 0;

    $objEntradaConcluirProcessoAPI = new EntradaConcluirProcessoAPI();
    $objEntradaConcluirProcessoAPI->setIdProcedimento($idProcedimentoMeusProcessos);

    $objSEIRN = new SeiRN();
    $objSEIRN->concluirProcesso($objEntradaConcluirProcessoAPI);
}


$arrPostDados = array('txtProcesso' => $txtProcessoCampo, 'selFila' => $selFilaCampo, 'selTipoProcesso'=> $selTipoProcessoCampo, 'selStatus'=> $selStatusCampo);

$idTipoControle            = $objMdUtlAdmTpCtrlUndRN->getTipoControleUnidadeLogada();
$arrObjsFilaDTO            = $objFilaRN->getFilasTipoControle($idTipoControle);

$idsFilasPermitidas        = InfraArray::converterArrInfraDTO($arrObjsFilaDTO, 'IdMdUtlAdmFila');
$arrObjsFilaUsuDTO         = $objMdUtlAdmFilaPrmGrUsuRN->getPapeisDeUsuario($idsFilasPermitidas);

$idParametro = null;

$isPermiteAssociacao       = false;
$isPermiteAssociacao       = $objMdUtlControleDsmpRN->validaVisualizacaoUsuarioLogado($idTipoControle);

if (!is_null($idTipoControle)) {
    $objMdUtlAdmUtlTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();
    $isParametrizado = $objMdUtlAdmUtlTpCtrlRN->verificaTipoControlePossuiParametrizacao($idTipoControle);
}

if (!is_null($idTipoControle) && $isParametrizado) {

    $isGestorSipSei = $objMdUtlAdmTpCtrlUsuRN->usuarioLogadoIsGestorSipSei();
    $idsStatusPermitido = $objMdUtlControleDsmpRN->getStatusPermitido($arrObjsFilaUsuDTO, $isGestorSipSei, true);

//URL Base
    $strUrl = 'controlador.php?acao=md_utl_meus_processos_dsmp_';

//URL das Actions
    $strUrlPesquisar = SessaoSEI::getInstance()->assinarLink($strUrl . 'listar&acao_origem=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTipoControle);
    $strUrlFechar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);


    $idsFilasPermitidasUsBasico = $isGestorSipSei || count($arrObjsFilaUsuDTO) == 0 ? null : InfraArray::converterArrInfraDTO($arrObjsFilaUsuDTO, 'IdMdUtlAdmFila');

    if ($isGestorSipSei) {
        $selFila = MdUtlAdmFilaINT::montarSelectFilas($selFilaCampo, $arrObjsFilaDTO);
    } else {
        $selFila = count($idsFilasPermitidasUsBasico) > 0 ? $selFila = MdUtlAdmFilaINT::montarSelectFilas($selFilaCampo, $arrObjsFilaDTO, $idsFilasPermitidasUsBasico) : null;
    }

    $selStatus         =  count($idsStatusPermitido) > 0 || $isGestorSipSei ? MdUtlControleDsmpINT::montarSelectStatusMeusProcessos($selStatusCampo, false, $idsStatusPermitido): null;
    $arrObjsTpProcesso = $objMdUtlControleDsmpRN->getTiposProcessoTipoControle($idTipoControle);
    $selTipoProcesso   = $isPermiteAssociacao  ? InfraINT::montarSelectArrInfraDTO(null, null, $selTipoProcessoCampo, $arrObjsTpProcesso, 'IdTipoProcedimento', 'NomeProcedimento') : '';
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


$arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                        Fe<span class="infraTeclaAtalho">c</span>har</button>';
$numRegistros = 0;
if (!is_null($idTipoControle) && $isParametrizado) {

    $objDTOCombo = $objMdUtlControleDsmpRN->getObjDTOParametrizadoMeusProcessos(array($arrObjsFilaUsuDTO, $isGestorSipSei, $arrObjsTpProcesso, $idTipoControle, array()));

    //Configuração da Paginação
    if ((count($arrObjsFilaDTO) == 0 && !$isGestorSipSei) || !$isPermiteAssociacao) {
        $objDTO = null;
    } else {
        $objDTO = $objMdUtlControleDsmpRN->getObjDTOParametrizadoMeusProcessos(array($arrObjsFilaUsuDTO, $isGestorSipSei, $arrObjsTpProcesso, $idTipoControle, $arrPostDados));
    }

    if(!is_null($objDTO)) {

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
        $objDTO->retNumUnidadeEsforco();
        $objDTO->retStrNomeUsuarioDistribuicao();
        $objDTO->retDthAtual();
        $objDTO->retDthPrazoTarefa();
        $objDTO->retStrSiglaUsuarioDistribuicao();
        $objDTO->retNumIdMdUtlAnalise();
        $objDTO->retNumIdMdUtlTriagem();
        $objDTO->retStrStaSolicitacaoAjustePrazo();
        $objDTO->retNumIdUsuarioDistribuicao();

        $objMdUtlTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();
        $isDadosParametrizados = $objMdUtlTpCtrlRN->validaNovosDadosParametrizacao($idTipoControle);

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
    if(!is_null($objDTOCombo)) {
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
                $arrayObjs[$obj->getNumIdMdUtlTriagem()] = $obj->getStrNomeAtividade();
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
            $strResultado .= '<th class="infraTh" width="18%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Processo', 'ProtocoloProcedimentoFormatado', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" width="14%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Atividade', 'IdTipoProcedimento', $arrObjs) . '</th>';

            //ADICIONAR ORDENAÇÃO PARA OS OUTROS CAMPOS

            $strResultado .= '<th class="infraTh" width="15%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Fila', 'NomeFila', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" width="10%" style="text-align: left">'. PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Unidade de Esforço', 'UnidadeEsforco', $arrObjs) .' </th>';
            $strResultado .= '<th class="infraTh" width="14%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Status', 'StaAtendimentoDsmp', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" width="12%">' . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Data Registro Status', 'Atual', $arrObjs) . '</th>';
            $strResultado .= '<th class="infraTh" width="10%">'  . PaginaSEI::getInstance()->getThOrdenacao($objDTO, 'Prazo', 'PrazoTarefa',$arrObjs) . ' </th>';
            $strResultado .= '<th class="infraTh" style="11%">Ações</th>';
            $strResultado .= '<th class="infraTh" style="display: none">Última Fila</th>';
            $strResultado .= '</tr>';



            //Linhas
            $strCssTr = '<tr class="infraTrEscura">';

            for ($i = 0; $i < $numRegistros; $i++) {


                $strId              = $arrObjs[$i]->getDblIdProcedimento();
                $strProcesso        = $arrObjs[$i]->getStrProtocoloProcedimentoFormatado();
                $strFila            = $arrObjs[$i]->getStrNomeFila();
                $idFila             = $arrObjs[$i]->getNumIdFila();
                $strTpProcesso      = $arrObjs[$i]->getNumIdTipoProcedimento();
                $nomeTpProcesso     = $arrObjs[$i]->getStrNomeTipoProcedimento();
                $strStatus          = trim($arrObjs[$i]->getStrStaAtendimentoDsmp());
                $numIdControleDsmp  = $arrObjs[$i]->getNumIdMdUtlControleDsmp();
                $numUndEsforco      = $arrObjs[$i]->getNumUnidadeEsforco();
                $numIdTriagem       = $arrObjs[$i]->getNumIdMdUtlTriagem();
             
                $numUndEsforco      = $arrObjs[$i]->getNumUnidadeEsforco();
                $numIdTriagem       = $arrObjs[$i]->getNumIdMdUtlTriagem();
                $strNomeAtividade   = array_key_exists($numIdTriagem, $arrayObjs) ? $arrayObjs[$numIdTriagem] : '';
                $linkAtvTriagem     = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_atividade_triagem_listar&acao_origem=md_utl_distrib_usuario_listar&id_triagem=' . $numIdTriagem . '');

                if(is_array($strNomeAtividade)){
                    $strNomeAtividade = '<a href="javascript:void(0);" onclick="infraAbrirJanela(\'' . $linkAtvTriagem . '\',\'urlAtividadeTriagemMult\',650,500,)" alt="Múltiplas" title="Múltiplas" class="ancoraPadraoAzul"> Múltiplas </a>';;
                }

                $objStatusAnterior  = $objMdUtlHistControleRN->getStatusAnterior($strId);
                $statusAnterior = !is_null($objStatusAnterior) ? $objStatusAnterior->getStrStaAtendimentoDsmp() : null;



                $arrSituacao        = MdUtlControleDsmpINT::retornaArrSituacoesControleDsmpCompleto();
                $linkProcedimento   = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=md_utl_meus_processos_dsmp_listar&id_procedimento=' . $strId . '');
                $data               = explode(' ', $arrObjs[$i]->getDthAtual());
                $dataFormatada      = $data[0];
                $dataPrazo          = explode(' ', $arrObjs[$i]->getDthPrazoTarefa());
                $dataPrazoFormatada = $dataPrazo[0];

                $dataAtual = InfraData::getStrDataAtual();
                $isDataPermitida = InfraData::compararDatasSimples($dataAtual, $dataPrazoFormatada) >= 0;

                $bolRegistroAtivo   = true;

                $isPossuiAnalise = $objMdUtlControleDsmpRN->verificaTriagemPossuiAnalise($arrObjs[$i]);
                $arrCtrlUrls     = MdUtlControleDsmpINT::retornaUrlsAcessoDsmp($strStatus, $isPossuiAnalise, $strId, $idFila, $arrObjs[$i]->getNumIdUsuarioDistribuicao(), true);
                $linkStatus      = MdUtlControleDsmpINT::retornaLinkStatus($arrCtrlUrls, $strStatus);
                
                $status          = !is_null($strStatus) ? PaginaSEI::tratarHTML($arrSituacao[$strStatus]) : PaginaSEI::tratarHTML($arrSituacao[0]);

                    
                if($strStatus == MdUtlControleDsmpRN::$SUSPENSO){
                    $dataPrazoFormatada = 'Prazo Suspenso';
                } else if ($strStatus == MdUtlControleDsmpRN::$INTERROMPIDO) {
                    $dataPrazoFormatada = 'Prazo Interrompido';
                }

                $somaUndEsforco   += $numUndEsforco;

                $strCssTr = !$bolRegistroAtivo ? '<tr class="trVermelha">' : ($strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">');
                $strCssTr = in_array($strId, $arrIdProcedimentoDistrib) ? '<tr class="infraTrAcessada">' : $strCssTr;
                $strResultado .= $strCssTr;

                //Linha Checkbox
                $strResultado .= '<td ' . $displayNoneCheck . ' align="center" valign="top"  >';
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

                //Linha Fila Padrão
                $strResultado .= '<td class="tdFilaProcesso">';
                $strResultado .= PaginaSEI::tratarHTML($strFila);
                $strResultado .= '</td>';

                //Linha Unidade de Esforço
                $strResultado .= '<td class="tdUniEsforco">';
                $strResultado .=  PaginaSEI::tratarHTML($numUndEsforco);
                $strResultado .= '</td>';

                //Linha Fila Status
                $strResultado .= '<td class="tdStatusProcesso">';
                $strResultado .= '<a href="'.$linkStatus.'" alt="' . $nomeTpProcesso . '" title="' . $nomeTpProcesso . '" class="ancoraPadraoAzul">' . $status . '</a>';
                $strResultado .= '</td>';

                //Linha Data Registro Status
                $strResultado .= '<td class="tdDtRegistroStatus">';
                $strResultado .= PaginaSEI::tratarHTML($dataFormatada);
                $strResultado .= '</td>';

                //Linha Prazo
                $strResultado .= '<td class="tdPrazo">';
                $strResultado .= PaginaSEI::tratarHTML($dataPrazoFormatada);
                $strResultado .= '</td>';

                //Linha Controle Dsmp
                $strResultado .= '<td class="tdIdControleDsmp" style="display: none">';
                $strResultado .= $numIdControleDsmp;
                $strResultado .= '</td>';

                $strResultado .= '<td style="text-align: center">';
                $strResultado .= MdUtlControleDsmpINT::getIconePadronizadoAjustePrazo($strStatus, $isDataPermitida, $arrObjs[$i]->getNumIdMdUtlAjustePrazo(), $arrObjs[$i]->getStrStaSolicitacaoAjustePrazo(), $numIdControleDsmp, $isDadosParametrizados, $strId, $statusAnterior);
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

        #divProcesso {
            position: absolute;
            margin-top: 10px;
            width: 17.1%;
        }

        #divFila {
            position: absolute;
            margin-left: 14.8%;
            margin-top: 8px;
            width: 20.5%;
        }

        #divTipoProcesso {
            position: absolute;
            margin-left: 32%;
            margin-top: 8px;
            width: 24%;
        }

        #divStatus {
            position: absolute;
            margin-left: 52%;
            margin-top: 8px;
            width: 24%;
        }

        #divSomaUndEsforco {
            margin-top: 15px;
            position: absolute;
        }

        .tdNomeProcesso, .tdStatusProcesso{
            font-size: 0.92em;
        }

        <?
        if (0) { ?></style><?
} ?>

<?php PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';

if (0){ ?>
    <script type="text/javascript"><?}?>

        var msg24 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_24)?>';
        var msg25 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_25)?>';

        function inicializar() {

            var urlCtrlProcessos = document.getElementById('hdnUrlControleProcessos').value;
            var idParam = document.getElementById('hdnIdParametroCtrlUtl').value;
            var tpCtrl = document.getElementById('hdnIdTipoControleUtl').value;
            var isProcessoConcl  = '<?php echo $isProcessoConcluido ?>';
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


            if(isProcessoConcl == 1){
                if(confirm(msgConclusao)){
                    document.getElementById('hdnIsConcluirProcesso').value = 1;
                    document.getElementById("frmTpControleLista").submit();
                }
            }
        
            addEnter();
        }

        function confirmarRetorno(strStatus, $strUrlLink) {

        var msg105padrao     = '<?=MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_105)?>';

                if(strStatus == '<?=MdUtlControleDsmpRN::$SUSPENSO?>') {
                    var msg = setMensagemPersonalizada(msg105padrao, ['<?=MdUtlControleDsmpRN::$STR_SUSPENSO?>']);
                    var validar = confirm(msg);
                    if (validar == true) {
                        document.getElementById('frmTpControleLista').action = $strUrlLink;
                        document.getElementById('frmTpControleLista').submit();
                    }
                }
            
                if(strStatus == '<?=MdUtlControleDsmpRN::$INTERROMPIDO?>'){
                    var msg = setMensagemPersonalizada(msg105padrao, ['<?=MdUtlControleDsmpRN::$STR_INTERROMPIDO?>']);
                    var validar = confirm(msg);
                    if(validar == true){
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

        function pesquisar() {
            document.getElementById('frmTpControleLista').action = '<?= $strUrlPesquisar ?>';
            document.getElementById('frmTpControleLista').submit();
        }

        function fechar() {
            location.href = "<?= $strUrlFechar ?>";
        }


        <?php if (0){ ?>
    </script><? } ?>

<?php PaginaSEI::getInstance()->fecharJavaScript(); ?>


<?php
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmTpControleLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(
              SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
          ) ?>">

        <?php
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('7em');
        ?>
        <div class="bloco" id="divProcesso">
            <label id="lblProcesso" for="txtProcessoUtlMs" class="infraLabelOpcional">
                Processo:
            </label>

            <div class="clear"></div>

            <input type="text" id="txtProcessoUtlMs" name="txtProcessoUtlMs" class="inputFila infraText padraoInput"
                   size="30"
                   value="<?php echo $txtProcessoCampo ?>"
                   maxlength="100" tabindex="502"/>
        </div>


        <div id="divFila">
            <label id="lblFila" for="selFilaUtlMs" accesskey="" class="infraLabelOpcional">Fila:</label>
            <select id="selFilaUtlMs" name="selFilaUtlMs" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selFila ?>
            </select>
        </div>


        <div id="divTipoProcesso">
            <label id="lblTipoProcesso" for="selTipoProcessoUtlMs" accesskey="" class="infraLabelOpcional">Tipo de
                Processo:</label>
            <select id="selTipoProcessoUtlMs" name="selTipoProcessoUtlMs" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <option value=""></option>
                <?= $selTipoProcesso ?>
            </select>
        </div>


        <div id="divStatus">
            <label id="lblStatus" for="selStatusUtlMs" accesskey="" class="infraLabelOpcional">Status:</label>
            <select id="selStatusUtlMs" name="selStatusUtlMs" class="infraSelect padraoSelect"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $selStatus ?>
            </select>
        </div>

        <div id="divAtividade" style="margin-left: 72%; margin-top: 0.8%;">
            <label id="lblAtividade" for="selAtividadeUtlMs" accesskey="" class="infraLabelOpcional">Atividade:</label>
            <select id="selAtividadeUtlMs" name="selAtividadeUtlMs" class="infraSelect padraoSelect" style="width: 54%"
                    onchange="pesquisar();"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <option value=""></option>
                <?=$selAtividade?>
            </select>
        </div>


        <input type="hidden" id="hdnSubmit" name="hdnSubmit" value="<?php echo $vlControlePost; ?>"/>
        <input type="hidden" id="hdnIdTipoControleUtl" name="hdnIdTipoControleUtl"
               value="<?php echo is_null($idTipoControle) ? '0' : $idTipoControle; ?>"/>
        <input type="hidden" id="hdnIdParametroCtrlUtl" name="hdnIdParametroCtrlUtl"
               value="<?php echo $isParametrizado ? '1' : '0'; ?>"/>
        <input type="hidden" id="hdnUrlControleProcessos" name="hdnUrlControleProcessos"
               value="<?php echo SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']); ?>"/>

        <input type="hidden" id="hdnIsConcluirProcesso" name="hdnIsConcluirProcesso" value="<?php echo $isProcessoAutorizadoConcluir ?>"/>
        <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento" value="<?php echo $idProcedimentoMeusProcessos ?>"/>

        <?php
        PaginaSEI::getInstance()->fecharAreaDados();
        if($numRegistros > 0){
        ?>
        <div id="divSomaUndEsforco">
            <label><u>Total de Unidade de Esforço:<?=$somaUndEsforco?></u></label>
        </div>
        <?php } ?>
        <br/>
        <br/>
<div style="margin-top: -16px;">
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
