<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4º REGIÃO
 *
 *  09/05/2019 - criado por jaqueline.cast
 *
 */

require_once dirname(__FILE__).'/../../../SEI.php';
class MdUtlControleDsmpRN extends InfraRN
{

    public static $AGUARDANDO_FILA = '0';
    public static $STR_AGUARDANDO_FILA = 'Aguardando Fila';

    public static $AGUARDANDO_TRIAGEM = '1';
    public static $STR_AGUARDANDO_TRIAGEM = 'Aguardando Triagem';

    public static $EM_TRIAGEM = '2';
    public static $STR_EM_TRIAGEM = 'Em Triagem';

    public static $AGUARDANDO_ANALISE = '3';
    public static $STR_AGUARDANDO_ANALISE = 'Aguardando Análise';

    public static $EM_ANALISE = '4';
    public static $STR_EM_ANALISE = 'Em Análise';

    public static $AGUARDANDO_REVISAO = '5';
    public static $STR_AGUARDANDO_REVISAO = 'Aguardando Revisão';

    public static $EM_REVISAO = '6';
    public static $STR_EM_REVISAO = 'Em Revisão';

    public static $AGUARDANDO_CORRECAO_TRIAGEM = '7';
    public static $STR_AGUARDANDO_CORRECAO_TRIAGEM = 'Aguardando Correção Triagem';

    public static $EM_CORRECAO_TRIAGEM = '8';
    public static $STR_EM_CORRECAO_TRIAGEM = 'Em Correção de Triagem';

    public static $AGUARDANDO_CORRECAO_ANALISE = '9';
    public static $STR_AGUARDANDO_CORRECAO_ANALISE = 'Aguardando Correção Análise';

    public static $EM_CORRECAO_ANALISE = '10';
    public static $STR_EM_CORRECAO_ANALISE = 'Em Correção de Análise';

    public static $FLUXO_FINALIZADO = '11';
    public static $STR_FLUXO_FINALIZADO = 'Fluxo Finalizado';

    public static $REMOCAO_FILA = '12';
    public static $STR_REMOCAO_FILA = 'Fila Removida';

    public static $INTERROMPIDO = '13';
    public static $STR_INTERROMPIDO = 'Interrompido';

    public static $SUSPENSO = '14';
    public static $STR_SUSPENSO = 'Suspenso';

    public static $ENC_ASSOCIAR_EM_FILA = '1';
    public static $STR_ENC_ASSOCIAR_EM_FILA = 'Associar em Fila após a finalização do Fluxo';

    public static $ENC_FINALIZAR_TAREFA = '2';
    public static $STR_ENC_FINALIZAR_TAREFA = 'Finalizar Tarefa';

    /*  Status da Revisão */
    public static $CONCLUIR_ASSOCIACAO = 0;
    public static $CONCLUIR_TRIAGEM = 1;
    public static $CONCLUIR_ANALISE = 2;
    public static $CONCLUIR_REVISAO = 3;
    public static $VOLTAR_RESP_REVISAO = 4;
    public static $CONCLUIR_CONTESTACAO = 5;

    /* Ações Gerais */
    public static $STR_TIPO_ACAO_ASSOCIACAO       = 'Associação em Fila';
    public static $STR_TIPO_ACAO_TRIAGEM          = 'Triagem';
    public static $STR_TIPO_ACAO_ANALISE          = 'Análise';
    public static $STR_TIPO_ACAO_REVISAO          = 'Revisão';
    public static $STR_TIPO_ACAO_DISTRIBUICAO     = 'Distribuição';
    public static $STR_TIPO_ACAO_CAD_AJUSTE_PRAZO = 'Solicitação de Ajuste de Prazo';
    public static $STR_TIPO_ACAO_ALT_AJUSTE_PRAZO = 'Alteração de Solicitação de Ajuste de Prazo';
    public static $STR_TIPO_ACAO_APV_AJUSTE_PRAZO = 'Aprovação de Ajuste de Prazo';
    public static $STR_TIPO_ACAO_RPV_AJUSTE_PRAZO = 'Reprovação de Ajuste de Prazo';
	public static $STR_TIPO_ACAO_RETORNO_STATUS   = 'Retorno de Status';
    public static $STR_FIM_SUSPENSAO              = 'Fim da Suspensão';
    public static $STR_FIM_INTERRUPCAO            = 'Fim da Interrupção';
    public static $STR_TIPO_ACAO_RETRIAGEM        = 'Retriagem';

    /* Tipo de Solicitação */
    public static $TP_SOLICITACAO_DILACAO         = 'D';
    public static $STR_TP_SOLICITACAO_DILACAO     = 'Dilação';
    public static $TP_SOLICITACAO_SUSPENSAO       = 'S';
    public static $STR_TP_SOLICITACAO_SUSPENSAO   = 'Suspensão';
    public static $TP_SOLICITACAO_INTERRUPCAO     = 'I';
    public static $STR_TP_SOLICITACAO_INTERRUPCAO = 'Interrupção';

    public static $STR_TIPO_ACAO_CAD_CONTESTACAO = 'Solicitação de Contestatação';
    public static $STR_TIPO_ACAO_ALT_CONTESTACAO = 'Alteração de Solicitação de Contestatação';
    public static $STR_TIPO_ACAO_APV_CONTESTACAO = 'Aprovação de Contestatação';
    public static $STR_TIPO_ACAO_RPV_CONTESTACAO = 'Reprovação de Contestatação';
    public static $STR_TIPO_CONTESTACAO_REVISAO  = 'Contestação de Revisão';





    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function cadastrarControlado(MdUtlControleDsmpDTO $objMdUtlControleDsmpDTO)
    {
        try {
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_controle_dsmp_cadastrar', __METHOD__, $objMdUtlControleDsmpDTO);
            $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD($this->getObjInfraIBanco());

            $ret = $objMdUtlControleDsmpBD->cadastrar($objMdUtlControleDsmpDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando Controle do Dsmp.', $e);
        }
    }

    protected function alterarControlado(MdUtlControleDsmpDTO $objMdUtlControleDsmpDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_controle_dsmp_alterar', __METHOD__, $objMdUtlControleDsmpDTO);

            $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD($this->getObjInfraIBanco());
            $objMdUtlControleDsmpBD->alterar($objMdUtlControleDsmpDTO);

        } catch (Exception $e) {
            throw new InfraException('Erro alterando Controle do Dsmp.', $e);
        }
    }

    protected function excluirControlado($arrObjMdUtlControleDsmpDTO)
    {
        try {
            //Valida Permissao
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_controle_dsmp_excluir', __METHOD__, $arrObjMdUtlControleDsmpDTO);

            $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdUtlControleDsmpDTO); $i++) {
                $objMdUtlControleDsmpBD->excluir($arrObjMdUtlControleDsmpDTO[$i]);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo Controle do Dsmp.', $e);
        }
    }

    protected function consultarConectado(MdUtlControleDsmpDTO $objMdUtlControleDsmpDTO)
    {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_utl_controle_dsmp_consultar');

            $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD($this->getObjInfraIBanco());
            $ret = $objMdUtlControleDsmpBD->consultar($objMdUtlControleDsmpDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando Controle do Dsmp.', $e);
        }
    }

    protected function listarConectado($objMdUtlControleDsmpDTO)
    {
        try {
            $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD($this->getObjInfraIBanco());

            if(is_array($objMdUtlControleDsmpDTO)){
                $ret = $objMdUtlControleDsmpBD->listar($objMdUtlControleDsmpDTO[0], true);
                print_r($ret);exit;
            }

            if(!$objMdUtlControleDsmpDTO->isSetAtributo('SinVerificarPermissao')){
                $objMdUtlControleDsmpDTO->setStrSinVerificarPermissao('S');
            }

            if($objMdUtlControleDsmpDTO->getStrSinVerificarPermissao() == 'S'){
                SessaoSEI::getInstance()->validarPermissao('md_utl_controle_dsmp_listar');
            }
            return $objMdUtlControleDsmpBD->listar($objMdUtlControleDsmpDTO);

        } catch (Exception $e) {
            throw new InfraException('Erro listando Controle do Dsmp.', $e);
        }
    }

    protected function contarConectado(MdUtlControleDsmpDTO $objMdUtlControleDsmpDTO)
    {
        try {

            if(!$objMdUtlControleDsmpDTO->isSetAtributo('SinVerificarPermissao')){
                $objMdUtlControleDsmpDTO->setStrSinVerificarPermissao('S');
            }

            if($objMdUtlControleDsmpDTO->getStrSinVerificarPermissao() == 'S') {
                SessaoSEI::getInstance()->validarPermissao('md_utl_controle_dsmp_listar');
            }

            $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD($this->getObjInfraIBanco());
            $ret = $objMdUtlControleDsmpBD->contar($objMdUtlControleDsmpDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando o Controle do Dsmp.', $e);
        }
    }

    protected function desativarControlado($arrObjMdUtlControleDsmpDTO)
    {
        try {

            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_controle_dsmp_desativar', __METHOD__, $arrObjMdUtlControleDsmpDTO);

            $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdUtlControleDsmpDTO); $i++) {
                $objMdUtlControleDsmpBD->desativar($arrObjMdUtlControleDsmpDTO[$i]);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro desativando Controle do Dsmp.', $e);
        }
    }

    protected function reativarControlado($arrObjMdUtlControleDsmpDTO)
    {
        try {

            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_controle_dsmp_reativar', __METHOD__, $arrObjMdUtlControleDsmpDTO);
            $objMdUtlControleDsmpBD = new MdUtlControleDsmpBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjMdUtlControleDsmpDTO); $i++) {
                $objMdUtlControleDsmpBD->reativar($arrObjMdUtlControleDsmpDTO[$i]);
            }

        } catch (Exception $e) {
            throw new InfraException('Erro reativando Controle do Dsmp.', $e);
        }
    }

    public function getTiposProcessoTipoControle($idTpCtrl = null, $returnIds = false)
    {
        $objMdUtlAdmTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();
        $objMdUtlAdmTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
        $idTpCtrl = is_null($idTpCtrl) ? $objMdUtlAdmTpCtrlUndRN->getTipoControleUnidadeLogada() : $idTpCtrl;

        $objMdUtlAdmTpCtrlDTO = !is_null($idTpCtrl) ? $objMdUtlAdmTpCtrlRN->buscarObjTpControlePorId($idTpCtrl) : null;
        $idPrmTpCtrl = !is_null($objMdUtlAdmTpCtrlDTO) ? $objMdUtlAdmTpCtrlDTO->getNumIdMdUtlAdmPrmGr() : null;

        if (!is_null($idPrmTpCtrl)) {
            $objMdUtlAdmPrmGrProcRN = new MdUtlAdmRelPrmGrProcRN();
            $objMdUtlAdmPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO();
            $objMdUtlAdmPrmGrProcDTO->setNumIdMdUtlAdmParamGr($idPrmTpCtrl);
            $objMdUtlAdmPrmGrProcDTO->retNumIdTipoProcedimento();
            $objMdUtlAdmPrmGrProcDTO->retStrNomeProcedimento();
            $objMdUtlAdmPrmGrProcDTO->setOrdStrNomeProcedimento(InfraDTO::$TIPO_ORDENACAO_ASC);
            $count = $objMdUtlAdmPrmGrProcRN->contar($objMdUtlAdmPrmGrProcDTO);

            if ($count > 0) {
                if ($returnIds) {
                    $idsArr = InfraArray::converterArrInfraDTO($objMdUtlAdmPrmGrProcRN->listar($objMdUtlAdmPrmGrProcDTO), 'IdTipoProcedimento');
                    return $idsArr;
                } else {
                    return $objMdUtlAdmPrmGrProcRN->listar($objMdUtlAdmPrmGrProcDTO);
                }
            }
        }

        return null;
    }

    protected function validaVisualizacaoUsuarioLogadoConectado($idTpCtrl = null, $idPrmTpCtrl = null)
    {
        $objMdUtlAdmTpCtrlUsuRN = new MdUtlAdmRelTpCtrlDesempUsuRN();
        $objMdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();
        $objMdUtlAdmTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
        $objMdUtlAdmTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();

        if (is_null($idTpCtrl)) {
            $idTpCtrl = $objMdUtlAdmTpCtrlUndRN->getTipoControleUnidadeLogada();
        }

        if (is_null($idPrmTpCtrl) && !is_null($idTpCtrl)) {
            $objMdUtlAdmTpCtrlDTO = !is_null($idTpCtrl) ? $objMdUtlAdmTpCtrlRN->buscarObjTpControlePorId($idTpCtrl) : null;
            $idPrmTpCtrl = !is_null($objMdUtlAdmTpCtrlDTO) ? $objMdUtlAdmTpCtrlDTO->getNumIdMdUtlAdmPrmGr() : null;
        }

        //Se a Unidade possuir um Tipo de Controle
        if (!is_null($idTpCtrl) && !is_null($idPrmTpCtrl)) {

            //Se o Tipo de Controle da Unidade estiver parametrizado
            $idsTpCtrlUsuarioGestor = $objMdUtlAdmTpCtrlUsuRN->usuarioLogadoIsGestorTpControle();
            $objPermissaoRN         = new MdUtlAdmPermissaoRN();
            $isGestorSip            = $objPermissaoRN->isGestor();

            if(!$isGestorSip){

            }

            $idsTpCtrlUsuarioGestor = count($idsTpCtrlUsuarioGestor) > 0 && !$isGestorSip ? array() : $idsTpCtrlUsuarioGestor;
            $isUsuarioParticipante = $objMdUtlAdmPrmGrUsuRN->usuarioLogadoIsUsuarioParticipante($idPrmTpCtrl);

            $isUsuarioGestor = count($idsTpCtrlUsuarioGestor) > 0 && (is_array($idsTpCtrlUsuarioGestor) && in_array($idTpCtrl, $idsTpCtrlUsuarioGestor));

            if ($isUsuarioGestor || $isUsuarioParticipante) {
                return true;
            }
        }

        return false;
    }

    protected function getIdsProcessoAbertoUnidadeConectado($objDTO)
    {
        $arrDadosDTO = array();
        $objProcedimentoRN = new ProcedimentoRN();
        $objDTO2 = clone($objDTO);
        $objDTO2->retTodos();
        $objDTO2->setDthConclusaoAtvUtilidades(null);
        $objDTO2->setNumIdUnidadeAtvUtilidades(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        $count = $objProcedimentoRN->contarRN0279($objDTO2);

        if ($count > 0) {
            $arrObjs = $objProcedimentoRN->listarRN0278($objDTO2);
            return array_unique(InfraArray::converterArrInfraDTO($arrObjs, 'IdProcedimento'));
        }

        return $arrDadosDTO;
    }

    protected function listarProcessosConectado(MdUtlProcedimentoDTO $objProcedimentoDTO)
    {
        $arrDados = array();
        $objProcedimentoDTO->retTodos();
        $objProcedimentoRN = new ProcedimentoRN();

        $count = $objProcedimentoRN->contarRN0279($objProcedimentoDTO);

        if ($count > 0) {
            $arrDados = $objProcedimentoRN->listarRN0278($objProcedimentoDTO);
        }

        return $arrDados;

    }

    protected function associarFilaControlado()
    {

        $arrDados = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTbAssociarFila']);
        $objHistoricoRN = new MdUtlHistControleDsmpRN();

        if (count($arrDados) > 0) {
            //Set Vars
            $idFila   = $_POST['selFila'];
            $idTpCtrl = $_POST['hdnIdTipoControleUtl'];
            $nomeSelFilaDetalhe = $_POST['hdnSelFila'];

            $arrDados = $this->controlarHistorico($arrDados, 'S', 'N', true, false);

            $arrIdProcedimento = $arrDados[0];;
            $objMdUtlAdmFilaRN = new MdUtlAdmFilaRN();
            $undEsforco = $objMdUtlAdmFilaRN->getUnidadeEsforcoFila($idFila);

            for ($i = 0; $i < count($arrIdProcedimento); $i++) {
                $idAtendimento = $objHistoricoRN->controlarIdAtendimento($arrIdProcedimento[$i]);
                $arrParams = array($arrIdProcedimento[$i], $idFila, $idTpCtrl, self::$AGUARDANDO_TRIAGEM, null, $undEsforco, null, null, null, null, $nomeSelFilaDetalhe, MdUtlControleDsmpRN::$STR_TIPO_ACAO_ASSOCIACAO,$idAtendimento);
                $this->cadastrarNovaSituacaoProcesso($arrParams);
            }

            $_SESSION['ID_PROCEDIMENTO_FILA_ASSOCIAR'] = $arrIdProcedimento;
        }
    }

    protected function associarFilaAnaliseTriagemConectado($arrDados){
        $idProcedimento = $arrDados[0];
        $idFila         = $arrDados[1];
        $idTpCtrl       = $arrDados[2];
        $strTipoAcao    = $arrDados[3];
        $strDetalhe     = array_key_exists(4, $arrDados) ? $arrDados[4] : (array_key_exists('hdnSelFila', $_POST) ? $_POST['hdnSelFila'] : '');

        $objMdUtlAdmFilaRN = new MdUtlAdmFilaRN();
        $objHistoricoRN = new MdUtlHistControleDsmpRN();

        $undEsforco     = $objMdUtlAdmFilaRN->getUnidadeEsforcoFila($idFila);
        $idAtendimento  = $objHistoricoRN->controlarIdAtendimento($idProcedimento);
        $arrParams = array($idProcedimento, $idFila, $idTpCtrl, self::$AGUARDANDO_TRIAGEM, null, $undEsforco, null, null, null, null, $strDetalhe, MdUtlControleDsmpRN::$STR_TIPO_ACAO_ASSOCIACAO, $idAtendimento, null, null, date('d/m/Y H:i:s', strtotime('+3 second')));
        $this->cadastrarNovaSituacaoProcesso($arrParams);
    }

    protected function getObjsAtivosPorProcedimentoConectado($arrIdsProcedimento)
    {
        $arrObjs = null;
        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setDblIdProcedimento($arrIdsProcedimento, InfraDTO::$OPER_IN);
        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlControleDsmpDTO->retTodos();

        $count = $this->contar($objMdUtlControleDsmpDTO);
        if ($count > 0) {
            $arrObjs = $this->listar($objMdUtlControleDsmpDTO);
        }

        return $arrObjs;
    }

    protected function getObjsAtivosPorProcedimentoPorUnidadeConectado($arrParams)
    {
        $arrIdsProcedimento = array_key_exists(0, $arrParams) ? $arrParams[0] : array();
        $idUnidade          = array_key_exists(1, $arrParams) ? $arrParams[1] :null;
        $arrObjs = null;

        if(count($arrIdsProcedimento) > 0 && !is_null($idUnidade)) {
            $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
            $objMdUtlControleDsmpDTO->setDblIdProcedimento($arrIdsProcedimento, InfraDTO::$OPER_IN);
            $objMdUtlControleDsmpDTO->setNumIdUnidade($idUnidade);
            $objMdUtlControleDsmpDTO->retTodos();

            $count = $this->contar($objMdUtlControleDsmpDTO);
            if ($count > 0) {
                $arrObjs = $this->listar($objMdUtlControleDsmpDTO);
            }
        }

        return $arrObjs;
    }

    protected function cadastrarNovaSituacaoProcessoControlado($arrParams)
    {

        $objMdUtlAdmFilaRN = new MdUtlAdmFilaRN();
        $objHistoricoRN    = new MdUtlHistControleDsmpRN();

        $dblIdProcedimento = array_key_exists(0, $arrParams) ? $arrParams[0] : null;
        $intIdFila         = array_key_exists(1, $arrParams) ? $arrParams[1] : null;
        $intIdTpCtrl       = array_key_exists(2, $arrParams) ? $arrParams[2] : null;
        $strStatus         = array_key_exists(3, $arrParams) && !is_null($arrParams[3]) ? trim($arrParams[3]) : null;
        $intIdUnidade      = array_key_exists(4, $arrParams) && !is_null($arrParams[4]) ? $arrParams[4] : SessaoSEI::getInstance()->getNumIdUnidadeAtual();
        $intUndEsforco     = array_key_exists(5, $arrParams) && !is_null($arrParams[5]) ? $arrParams[5] : null;
        $idUsuarioDistrib  = array_key_exists(6, $arrParams) && !is_null($arrParams[6]) ? $arrParams[6] : null;
        $idTriagem         = array_key_exists(7, $arrParams) && !is_null($arrParams[7]) ? $arrParams[7] : null;
        $idAnalise         = array_key_exists(8, $arrParams) && !is_null($arrParams[8]) ? $arrParams[8] : null;
        $idRevisao         = array_key_exists(9, $arrParams) && !is_null($arrParams[9]) ? $arrParams[9] : null;
        $strDetalhe        = array_key_exists(10, $arrParams) && !is_null($arrParams[10]) ? $arrParams[10] : null;
        $tipoAcao          = array_key_exists(11, $arrParams) && !is_null($arrParams[11]) ? $arrParams[11] : null;
        $idAtendimento     = array_key_exists(12, $arrParams) && !is_null($arrParams[12]) ? $arrParams[12] : null;
        $idAjustePrazo     = array_key_exists(13, $arrParams) && !is_null($arrParams[13]) ? $arrParams[13] : null;
        $dtPrazo           = array_key_exists(14, $arrParams) && !is_null($arrParams[14]) ? $arrParams[14] : null;
        $dtHora            = array_key_exists(15, $arrParams) && !is_null($arrParams[15]) ? $arrParams[15] : date('d/m/Y H:i:s', strtotime('+2 second'));;
        $idContestacao     = array_key_exists(16, $arrParams) && !is_null($arrParams[16]) ? $arrParams[16] : null;

        if(is_null($idAtendimento)){
            $idAtendimento = $objHistoricoRN->getIdAtendimentoAtual($dblIdProcedimento);
        }


        if(is_null($dtPrazo)) {
            $dtPrazo = $this->_getPrazoTarefa($strStatus, $dtHora, $intIdFila, $idTriagem, $idAnalise);
        }

        $idUsuario = SessaoSEI::getInstance()->getNumIdUsuario();

        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setDblIdProcedimento($dblIdProcedimento);
        $objMdUtlControleDsmpDTO->setNumIdUnidade($intIdUnidade);
        $objMdUtlControleDsmpDTO->setNumIdUsuarioAtual($idUsuario);
        $objMdUtlControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($intIdTpCtrl);
        $objMdUtlControleDsmpDTO->setDthAtual($dtHora);
        $objMdUtlControleDsmpDTO->setStrStaAtendimentoDsmp($strStatus);
        $objMdUtlControleDsmpDTO->setNumUnidadeEsforco($intUndEsforco);
        $objMdUtlControleDsmpDTO->setNumIdUsuarioDistribuicao($idUsuarioDistrib);
        $objMdUtlControleDsmpDTO->setStrDetalhe($strDetalhe);
        $objMdUtlControleDsmpDTO->setNumIdAtendimento($idAtendimento);
        $objMdUtlControleDsmpDTO->setStrTipoAcao($tipoAcao);
        $objMdUtlControleDsmpDTO->setDthPrazoTarefa($dtPrazo);

        if(!is_null($idAjustePrazo)){
            $objMdUtlControleDsmpDTO->setNumIdMdUtlAjustePrazo($idAjustePrazo);
        }

        if (!is_null($intIdFila)) {
            $objMdUtlControleDsmpDTO->setNumIdMdUtlAdmFila($intIdFila);
        }

        if (!is_null($idTriagem)) {
            $objMdUtlControleDsmpDTO->setNumIdMdUtlTriagem($idTriagem);
        }

        if (!is_null($idAnalise)) {
            $objMdUtlControleDsmpDTO->setNumIdMdUtlAnalise($idAnalise);
        }

        if (!is_null($idRevisao)) {
            $objMdUtlControleDsmpDTO->setNumIdMdUtlRevisao($idRevisao);
        }

        if(!is_null($idContestacao)){
            $objMdUtlControleDsmpDTO->setNumIdMdUtlContestRevisao($idContestacao);
        }


        return $this->cadastrar($objMdUtlControleDsmpDTO);
    }

    protected function _getPrazoTarefa($staStatus, $dtHora, $intIdFila, $idTriagem, $idAnalise)
    {
        $objMdUtlPrazoRN   = new MdUtlPrazoRN();
        $objMdUtlTriagemRN = new MdUtlTriagemRN();
        $objMdUtlFilaRN    = new MdUtlAdmFilaRN();
        $objMdUtlAnaliseRN = new MdUtlAnaliseRN();
        $dtPrazoTarefa     = null;
        $qtdDias           = null;

        switch ($staStatus)
        {
            case MdUtlControleDsmpRN::$EM_TRIAGEM:
            case MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM:
                $qtdDias = $objMdUtlFilaRN->getNumPrazoTarefaPorIdFila($intIdFila);
                break;

            case MdUtlControleDsmpRN::$EM_ANALISE:
            case MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE:
                $qtdDias = $objMdUtlTriagemRN->getNumPrazoAtividadePorTriagem($idTriagem);
                break;

            case MdUtlControleDsmpRN::$EM_REVISAO:
                if(is_null($idAnalise)) {
                    $qtdDias = $objMdUtlTriagemRN->getNumPrazoAtividadePorTriagemParaRev($idTriagem);
                }else{
                    $qtdDias = $objMdUtlAnaliseRN->getNumPrazoAtividadePorAnalise($idAnalise);
                }
                break;
        }

        if (!is_null($qtdDias) && $qtdDias > 0) {
            $dtPrazoSoma      = $objMdUtlPrazoRN->somarDiaUtil($qtdDias, $dtHora);
            $arrHoraFim       = explode(' ', $dtHora);
            $arrDtPrazoTarefa = explode(' ',$dtPrazoSoma);
            $dtPrazoTarefa    = $arrDtPrazoTarefa[0].' '.$arrHoraFim[1];
        }

        return $dtPrazoTarefa;
    }

    protected function getObjDTOParametrizadoDistribConectado($arrParams)
    {
        //Set Params Recebidos
        $arrObjsFilaUsuDTO = array_key_exists(0, $arrParams) ? $arrParams[0] : null;
        $isGestorSipSei    = array_key_exists(1, $arrParams) ? $arrParams[1] : null;
        $idTipoControle    = array_key_exists(2, $arrParams) ? $arrParams[2] : null;
        $arrPost           = array_key_exists(3, $arrParams) ? $arrParams[3] : null;

        //Sett Posts
        $txtProcesso      = array_key_exists('txtProcesso', $arrPost) && $arrPost['txtProcesso'] != '';
        $isTipoProcesso   = array_key_exists('selTipoProcesso', $arrPost) && $arrPost['selTipoProcesso'] != '';
        $isIdFila         = array_key_exists('selFila', $arrPost) && $arrPost['selFila'] != '';
        $isStrStatus      = array_key_exists('selStatus', $arrPost) && $arrPost['selStatus'] != '';
        $isStrDocumento   = array_key_exists('txtDocumento', $arrPost) && trim($arrPost['txtDocumento']) != '';
        $isStrResponsavel = array_key_exists('selResponsavel', $arrPost) && trim($arrPost['selResponsavel']) != '';

        //Inicializa Vars
        $objDTO = new MdUtlProcedimentoDTO();

        //Set Campos definidos por Regras
        $objDTO->setStrStaEstadoProtocolo(ProtocoloRN::$TE_NORMAL);
        $objDTO->setStrStaNivelAcessoLocalProtocolo(array(ProtocoloRN::$NA_PUBLICO, ProtocoloRN::$NA_RESTRITO), InfraDTO::$OPER_IN);
        $objDTO->setControleDsmpTIPOFK(InfraDTO::$TIPO_FK_OBRIGATORIA);

        if(!$isGestorSipSei && is_null($arrObjsFilaUsuDTO)){
            return null;
        }

        // Refina a pesquisa para Usuários que não são gestores.
        if (!$isGestorSipSei) {
            $this->setCriteriosDistribuicao($arrObjsFilaUsuDTO, $objDTO);
        }

        if ($isTipoProcesso) {
            $objDTO->setNumIdTipoProcedimento($arrPost['selTipoProcesso']);
        }

        if ($isIdFila) {
            $objDTO->setNumIdFila($arrPost['selFila']);
        }

        if($isStrResponsavel){
            $objDTO->setNumIdUsuarioDistribuicao($arrPost['selResponsavel']);
        }

        if ($isStrStatus) {

            $objDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objDTO->setControleDsmpTIPOFK(InfraDTO::$TIPO_FK_OBRIGATORIA);
            $objDTO->setStrStaAtendimentoDsmp(trim($arrPost['selStatus']));
            $objDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);

        } else {
            $objDTO->setStrStaAtendimentoDsmp(array(MdUtlControleDsmpRN::$INTERROMPIDO, MdUtlControleDsmpRN::$SUSPENSO), InfraDTO::$OPER_NOT_IN);
            $objDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        }

        if ($txtProcesso) {
            $objDTO->setStrProtocoloProcedimentoFormatado('%' . trim($arrPost['txtProcesso'] . '%'), InfraDTO::$OPER_LIKE);
        }

        $idsProcessoAberto = $this->getIdsProcessoAbertoUnidade($objDTO);

        if (count($idsProcessoAberto) == 0) {
            return null;
        }

        if ($isStrDocumento) {
            //Realiza o filtro de Documento
            $idsProcessoDocumento = $this->getIdsProcessoDocumentosFiltrados(array($objDTO, $idsProcessoAberto, $arrPost));

            //Se existir Processos válidos ele realiza a pesquisa sem Joins em decorrência de duplicação no caso do Distinct
            if (count($idsProcessoDocumento) > 0) {
                $objDTO->setDblIdProcedimento($idsProcessoDocumento, InfraDTO::$OPER_IN);
            } else {
                //Se não existir dados validos ele faz a pesquisa de forma normal, para retornar a paginação.
                $objDTO->setStrProtocoloFormatadoDocumento('%' . trim($arrPost['txtDocumento']) . '%', InfraDTO::$OPER_LIKE);
            }

        }

        return $objDTO;
    }

    private function setCriteriosDistribuicao($arrObjsFilaUsuDTO, $objDTO)
    {
        //Set Regras Tela de Distribuição
        $arrColunas = array();
        $arrComparacao = array();
        $arrValores = array();
        $arrLogico = array();

        if (count($arrObjsFilaUsuDTO) > 0) {
            foreach ($arrObjsFilaUsuDTO as $objFilaPapelDTO) {

                if ($objFilaPapelDTO->getStrSinTriador() == 'S') {
                    $this->setArrCriteriosDistribuicao($arrColunas, $arrComparacao, $arrValores, $arrLogico, $objFilaPapelDTO->getNumIdMdUtlAdmFila(), MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM);
                    $this->setArrCriteriosDistribuicao($arrColunas, $arrComparacao, $arrValores, $arrLogico, $objFilaPapelDTO->getNumIdMdUtlAdmFila(), MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM);
                }

                if ($objFilaPapelDTO->getStrSinAnalista() == 'S') {
                    $this->setArrCriteriosDistribuicao($arrColunas, $arrComparacao, $arrValores, $arrLogico, $objFilaPapelDTO->getNumIdMdUtlAdmFila(), MdUtlControleDsmpRN::$AGUARDANDO_ANALISE);
                    $this->setArrCriteriosDistribuicao($arrColunas, $arrComparacao, $arrValores, $arrLogico, $objFilaPapelDTO->getNumIdMdUtlAdmFila(), MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE);
                }

                if ($objFilaPapelDTO->getStrSinRevisor() == 'S') {
                    $this->setArrCriteriosDistribuicao($arrColunas, $arrComparacao, $arrValores, $arrLogico, $objFilaPapelDTO->getNumIdMdUtlAdmFila(), MdUtlControleDsmpRN::$AGUARDANDO_REVISAO);
                }
            }
            $objDTO->adicionarCriterio($arrColunas, $arrComparacao, $arrValores, $arrLogico);
        }


    }

    private function setArrCriteriosDistribuicao(&$arrColunas, &$arrComparacao, &$arrValores, &$arrLogico, $idFila, $status)
    {
        if (count($arrColunas) > 0) {
            array_push($arrLogico, InfraDTO::$OPER_LOGICO_OR);
        }

        array_push($arrColunas, 'IdFila');
        array_push($arrColunas, 'StaAtendimentoDsmp');
        array_push($arrComparacao, InfraDTO::$OPER_IGUAL);
        array_push($arrComparacao, InfraDTO::$OPER_IGUAL);
        array_push($arrValores, $idFila);
        array_push($arrValores, $status);
        array_push($arrLogico, InfraDTO::$OPER_LOGICO_AND);
    }

    public function getStatusPermitido($arrObjFilaUsuDTO, $isGestor, $isMeusProcessos = false)
    {
        $arrRetorno = array();
        $isDistrib  = !$isGestor && !$isMeusProcessos;

        if ($isDistrib || $isMeusProcessos) {
            if (count($arrObjFilaUsuDTO) > 0) {
                foreach ($arrObjFilaUsuDTO as $objDTO)
                {
                    if ($objDTO->getStrSinTriador() == 'S') {
                        if($isMeusProcessos){
                            $arrRetorno[] = self::$EM_TRIAGEM;
                            $arrRetorno[] = self::$EM_CORRECAO_TRIAGEM;
                        }else {
                            $arrRetorno[] = self::$AGUARDANDO_TRIAGEM;
                            $arrRetorno[] = self::$AGUARDANDO_CORRECAO_TRIAGEM;
                        }

                    }

                    if ($objDTO->getStrSinAnalista() == 'S') {
                        if($isMeusProcessos){
                            $arrRetorno[] = self::$EM_ANALISE;
                            $arrRetorno[] = self::$EM_CORRECAO_ANALISE;
                        }else {
                            $arrRetorno[] = self::$AGUARDANDO_ANALISE;
                            $arrRetorno[] = self::$AGUARDANDO_CORRECAO_ANALISE;
                        }
                    }

                    if ($objDTO->getStrSinRevisor() == 'S') {
                        if($isMeusProcessos){
                            $arrRetorno[] = self::$EM_REVISAO;
                        }else {
                            $arrRetorno[] = self::$AGUARDANDO_REVISAO;
                        }
                    }

            
                    if($objDTO->getStrSinRevisor() == 'S' || $objDTO->getStrSinAnalista() == 'S'){
                        $arrRetorno[] = self::$SUSPENSO;
                        $arrRetorno[] = self::$INTERROMPIDO;
                    }
                    
                    
                    
                    if (count($arrRetorno) == 7) {
                        return $arrRetorno;
                    }
                }
            }

            return $arrRetorno;
        }

        return false;
    }

    protected function incluirNovaDistribuicaoControlado($idStatus)
    {
        try {
            $isProcessos = $_POST['hdnTbProcesso'] != '';
            $nomeUsuPart = $_POST['hdnSelParticipante'];

            if ($isProcessos) {
                $itensGridDinamica = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTbProcesso']);

                $funcFirstElement = function ($value) {
                    reset($value);
                    return current($value);
                };

                $arrIdProcedimento = array_map($funcFirstElement, $itensGridDinamica);

                $objInfraException = new InfraException();
                $this->verificarDuplicidadeUsuario($arrIdProcedimento, $objInfraException, true);
                $objInfraException->lancarValidacoes();

                $idNovoStatus = MdUtlControleDsmpINT::getProximoStatusDistribuicao($idStatus);
                $this->controlarContestacao(array($arrIdProcedimento, $idNovoStatus));

                $arrAjustePrazo = array($arrIdProcedimento, $idStatus);
                $this->controlarAjustePrazo($arrAjustePrazo);

                $arrDados = $this->controlarHistorico($itensGridDinamica, 'N', 'N', false);
                $arrIdProcedimento = $arrDados[0];
                $arrDadosRetornoHs = $arrDados[1];

                $objAtribuirDTO     = new AtribuirDTO();
                $arrObjProtocoloDTO = array();


                for ($i = 0; $i < count($arrIdProcedimento); $i++) {
                    $idUsuarioDistribuicao = $_POST['hdnIdUsuarioParticipanteLupa'];
                    $dadosHistorico        = array_key_exists($arrIdProcedimento[$i], $arrDadosRetornoHs) ? $arrDadosRetornoHs[$arrIdProcedimento[$i]] : '0';
                    $undEsforco            = $dadosHistorico['UNIDADE_ESFORCO'];
                    $idTriagem             = $dadosHistorico['ID_TRIAGEM'];
                    $idAnalise             = $dadosHistorico['ID_ANALISE'];
                    $idRevisao             = $dadosHistorico['ID_REVISAO'];
                    $idAtendimento         = $dadosHistorico['ID_ATENDIMENTO'];

                    $arrParams = array($arrIdProcedimento[$i], $_POST['hdnIdFila'], $_POST['hdnIdTipoControleUtl'], $idNovoStatus, null, $undEsforco, $idUsuarioDistribuicao, $idTriagem, $idAnalise, $idRevisao, $nomeUsuPart, MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO);
                    $this->cadastrarNovaSituacaoProcesso($arrParams);

                    //Atribuição no Core
                    $objAtribuirDTO->setNumIdUsuarioAtribuicao($idUsuarioDistribuicao);
                    $objProtocoloDTO = new ProtocoloDTO();
                    $objProtocoloDTO->setDblIdProtocolo($arrIdProcedimento[$i]);
                    $arrObjProtocoloDTO[] = $objProtocoloDTO;
                }

                $objAtividadeRN  = new AtividadeRN();
                $objAtribuirDTO->setArrObjProtocoloDTO($arrObjProtocoloDTO);
                $objAtividadeRN->atribuirRN0985($objAtribuirDTO);

                $isTelaProcesso = $_POST['hdnIsTelaProcesso'] == 1;

                if(!$isTelaProcesso) {
                    $_SESSION['IDS_PROCEDIMENTOS_DISTRIBUICAO'] = $arrIdProcedimento;
                }
            }
        } catch (Exception $e) {
            throw new InfraException('Erro realizando a Distribuição - Controle do Dsmp.', $e);
        }
    }


    public function controlarHistorico($arrDados, $sinFila = 'S', $sinResponsavel = 'S', $isAssociarFila, $isRemoverFila = false)
    {

        $objHistoricoRN = new MdUtlHistControleDsmpRN();

        $funcFirstElement = function ($value) {
            reset($value);
            return current($value);
        };

        $arrIdProcedimento = array_map($funcFirstElement, $arrDados);
        $arrObjsAtuais = $this->getObjsAtivosPorProcedimento($arrIdProcedimento);

        if (!is_null($arrObjsAtuais)) {
            $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, $arrIdProcedimento, $sinFila, $sinResponsavel));

           if($isAssociarFila){
               $strStatus = $isRemoverFila ? MdUtlControleDsmpRN::$REMOCAO_FILA : MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM;
               $this->controlarAjustePrazo(array($arrIdProcedimento, $strStatus));
            }


            $this->excluir($arrObjsAtuais);
        }

        if($isAssociarFila){
            $this->desativarIdsAtivosControleDsmp($arrRetorno);
        }

        return array($arrIdProcedimento, $arrRetorno);
    }


    public function controlaAjustePrazoAssociacaoFila($arrDados){
        $arrStatusProc = array();

        $addArrStatusProcedimento = function ($arrLinha) use (&$arrStatusProc) {
            $idProcedimento = $arrLinha[0];
            $idStatus       = $arrLinha[4];
            $arrStatusProc[$idProcedimento] = $idStatus;
        };

        array_walk($arrDados, $addArrStatusProcedimento);

        if(count($arrStatusProc) > 0){
            foreach($arrStatusProc as $idProcedimento => $idStatus){
                $arrDados = array(array($idProcedimento), $idStatus);
                $this->controlarAjustePrazo($arrDados);
            }
        }
    }

    protected function getObjControleDsmpAtivoConectado($idProcedimento)
    {
        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setDblIdProcedimento($idProcedimento);
        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlControleDsmpDTO->retTodos();
        $objMdUtlControleDsmpDTO->retStrNomeUsuarioAtual();
        $objMdUtlControleDsmpDTO->retStrSiglaUsuarioAtual();
        $objMdUtlControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objMdUtlControleDsmpDTO->retStrNomeFila();
        $objMdUtlControleDsmpDTO->retNumIdTpProcedimento();

        return $this->consultar($objMdUtlControleDsmpDTO);
    }

    protected function getObjsControleDsmpAtivoAjustePrazoConectado($idProcedimento)
    {

        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlControleDsmpDTO->retTodos();
        $objMdUtlControleDsmpDTO->retStrNomeUsuarioAtual();
        $objMdUtlControleDsmpDTO->retStrSiglaUsuarioAtual();
        $objMdUtlControleDsmpDTO->retStrNomeFila();
        $objMdUtlControleDsmpDTO->retNumIdTpProcedimento();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlAjustePrazo();
        $objMdUtlControleDsmpDTO->retDthPrazoSolicitacaoAjustePrazo();

        if(is_array($idProcedimento)){
            $objMdUtlControleDsmpDTO->setDblIdProcedimento($idProcedimento, InfraDTO::$OPER_IN);
            return $this->listar($objMdUtlControleDsmpDTO);
        }else {
            $objMdUtlControleDsmpDTO->setDblIdProcedimento($idProcedimento);
            $objMdUtlControleDsmpDTO->setNumMaxRegistrosRetorno(1);
            return $this->consultar($objMdUtlControleDsmpDTO);
        }
    }



    protected function verificaTriagemPossuiAnaliseConectado($objControleDsmpDTO)
    {

        if(!is_null($objControleDsmpDTO)) {
            $idStatus = trim($objControleDsmpDTO->getStrStaAtendimentoDsmp());
            $statusAntesAnalise = array(self::$AGUARDANDO_FILA, self::$AGUARDANDO_TRIAGEM, self::$EM_TRIAGEM);
            $statusEmAnalise = array(self::$AGUARDANDO_ANALISE, self::$EM_ANALISE, self::$EM_CORRECAO_ANALISE, self::$AGUARDANDO_CORRECAO_ANALISE);
            $statusIndefinido = array(self::$AGUARDANDO_REVISAO, self::$EM_REVISAO);

            if (in_array($idStatus, $statusAntesAnalise) || $idStatus == self::$AGUARDANDO_CORRECAO_TRIAGEM) {
                return false;
            } else if (in_array($idStatus, $statusEmAnalise)) {
                return true;
            } else if (in_array($idStatus, $statusIndefinido)) {
                return !is_null($objControleDsmpDTO->getNumIdMdUtlAnalise());

            }
        }

    }

    protected function desativarIdsAtivosControleDsmpConectado($arrRetorno){
        $idsTriagem   = array();
        $idsAnalise   = array();
        $idsRevisao   = array();

        $objTriagemRN = new MdUtlTriagemRN();
        $objAnaliseRN = new MdUtlAnaliseRN();
        $objRevisaoRN = new MdUtlRevisaoRN();

        if(count($arrRetorno) > 0) {
            foreach ($arrRetorno as $idProcedimento => $arrDados) {
                if (!is_null($arrDados['ID_TRIAGEM'])) {
                    array_push($idsTriagem, $arrDados['ID_TRIAGEM']);
                }

                if (!is_null($arrDados['ID_ANALISE'])) {
                    array_push($idsAnalise, $arrDados['ID_ANALISE']);
                }

                if (!is_null($arrDados['ID_REVISAO'])) {
                    array_push($idsRevisao, $arrDados['ID_REVISAO']);
                }
            }
        }

        $objTriagemRN->desativarPorIds($idsTriagem);
        $objAnaliseRN->desativarPorIds($idsAnalise);
        $objRevisaoRN->desativarPorIds($idsRevisao);
    }

    protected function listarProcessosAtivosPorIdTipoProcConectado($arrParams){
        $arrIdsRemovidos = $arrParams[0];
        $arrIdsUnidades  = $arrParams[1];
        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        if(!is_null($arrIdsRemovidos)) {
            $objMdUtlControleDsmpDTO->setNumIdTpProcedimento($arrIdsRemovidos, InfraDTO::$OPER_IN);
        }
        $objMdUtlControleDsmpDTO->setNumIdUnidade($arrIdsUnidades, InfraDTO::$OPER_IN);
        $objMdUtlControleDsmpDTO->retTodos();

        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $arrObjs = $objMdUtlControleDsmpRN->listar($objMdUtlControleDsmpDTO);

        return $arrObjs;
    }

    protected function desativarControleDsmpObjsConectado($arrParams){
        $objHistoricoRN = new MdUtlHistControleDsmpRN();
        $objHistoricoRN->desativarTodasFlagsHistorico($arrParams);

        $arrObjs = $this->listarProcessosAtivosPorIdTipoProc($arrParams);
        if (!is_null($arrObjs)) {
            $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenhoParametrizacao($arrObjs);
            $this->excluir($arrObjs);
        }

        $this->desativarIdsAtivosControleDsmp($arrRetorno);
    }

    protected function desativarControleDsmpPorProcedimentoConectado($idProcedimento)
    {
        $objHistoricoRN = new MdUtlHistControleDsmpRN();
        $objRNGerais = new MdUtlRegrasGeraisRN();
        $objHistoricoRN->desativarTodasFlagsHistoricoPorIdProcedimento($idProcedimento);

        $arrObjsAtuais = $this->getObjsAtivosPorProcedimento(array($idProcedimento));

        if (!is_null($arrObjsAtuais)) {

            $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenhoParametrizacao($arrObjsAtuais);
            $this->excluir($arrObjsAtuais);

            foreach ($arrObjsAtuais as $objAtual) {
                $idUsuarioCore     = $objRNGerais->getUltimoUsuarioAtribuidoUnidadeLogada($objAtual->getDblIdProcedimento());
                $idUsuarioAtb       = $arrRetorno[$objAtual->getDblIdProcedimento()]['ID_USUARIO_ATRIBUICAO'];
                $objAtribuirDTO     = new AtribuirDTO();
                $objAtividadeRN     = new AtividadeRN();
                $objProtocoloDTO    = new ProtocoloDTO();
                $arrObjProtocoloDTO = array();

                if($idUsuarioCore == $idUsuarioAtb){
                    $objProtocoloDTO->setDblIdProtocolo($idProcedimento);
                    $arrObjProtocoloDTO[] = $objProtocoloDTO;
                    $objAtribuirDTO->setNumIdUsuarioAtribuicao(null);
                    $objAtribuirDTO->setArrObjProtocoloDTO($arrObjProtocoloDTO);
                    $objAtividadeRN->atribuirRN0985($objAtribuirDTO);
                }
            }
        }

    }

    protected function getIdsAtivosControleDesempenhoConectado($idsProcedimento){
        if (count($idsProcedimento) > 0) {
            $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
            $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objMdUtlControleDsmpDTO->setDblIdProcedimento($idsProcedimento, InfraDTO::$OPER_IN);
            $objMdUtlControleDsmpDTO->retDblIdProcedimento();

            $count = $this->contar($objMdUtlControleDsmpDTO);

            if ($count > 0) {
                $arrObjs = $this->listar($objMdUtlControleDsmpDTO);
                return InfraArray::converterArrInfraDTO($arrObjs, 'IdProcedimento');
            }
        }

        return array();
    }

    protected function getIdsProcessoDocumentosFiltradosConectado($arr)
    {
        $objDTO = $arr[0];
        $idsProtocolo = $arr[1];
        $arrPost = array_key_exists('2', $arr) ? $arr[2] : $_POST;

        $objDTO2 = clone($objDTO);
        $objDTO2->retDblIdProcedimento();
        $objDTO2->retDblIdDocumento();
        $objDTO2->retDblIdProtocoloDoc();
        $objDTO2->setDblIdProcedimento($idsProtocolo, InfraDTO::$OPER_IN);
        $objDTO2->setStrProtocoloFormatadoDocumento('%' . trim($arrPost['txtDocumento']) . '%', InfraDTO::$OPER_LIKE);

        $objProcedimentoRN = new ProcedimentoRN();

        $count = $objProcedimentoRN->contarRN0279($objDTO2);

        if ($count > 0) {
            $arrDados = $objProcedimentoRN->listarRN0278($objDTO2);
            $arrIds = InfraArray::converterArrInfraDTO($arrDados, 'IdProcedimento');

            if (!is_null($arrIds)) {
                $arrIds = array_unique($arrIds);
            }

            return $arrIds;
        }

        return array();
    }

    protected function verificaUnidadeControleDsmpConectado($idUnidade){
        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setNumIdUnidade($idUnidade);
        $objMdUtlControleDsmpDTO->retTodos();
        $countDsmp = $this->contar($objMdUtlControleDsmpDTO);

        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpRN = new MdUtlHistControleDsmpRN();
        $objMdUtlHistControleDsmpDTO->setNumIdUnidade($idUnidade);
        $objMdUtlHistControleDsmpDTO->retTodos();
        $countHsDsmp = $objMdUtlHistControleDsmpRN->contar($objMdUtlHistControleDsmpDTO);

        if($countHsDsmp > 0 || $countDsmp > 0 ){
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_45, array('excluir'));
            return $msg;
        }
    }

    public function verificaProcessoAtivoDsmp($arrParams)
    {
        $idsProcedimento = array_key_exists(0, $arrParams) ? $arrParams[0] : null;
        $idUnidade       = array_key_exists(1, $arrParams) ? $arrParams[1] : false;
        $msg             = '';
        $countProcessos  = 0;
        $objMdUtlControleDsmpRN  = new MdUtlControleDsmpRN();

        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setStrSinVerificarPermissao('N');

        if(is_array($idsProcedimento)){
            $objMdUtlControleDsmpDTO->setDblIdProcedimento($idsProcedimento, InfraDTO::$OPER_IN);
        } else {
            $objMdUtlControleDsmpDTO->setDblIdProcedimento($idsProcedimento);
        }

        if($idUnidade){
            $objMdUtlControleDsmpDTO->setNumIdUnidade($idUnidade);
        }

        $objMdUtlControleDsmpDTO->retStrProtocoloProcedimentoFormatado();
        $countProcessos = $objMdUtlControleDsmpRN->contar($objMdUtlControleDsmpDTO);
        if($countProcessos > 0)
        {
            $arrObjControleDsmpDTO = $objMdUtlControleDsmpRN->listar($objMdUtlControleDsmpDTO);

            foreach ($arrObjControleDsmpDTO as $obj) {
                $msg .= ' - '.$obj->getStrProtocoloProcedimentoFormatado().'\n';
            }
        }

        $dados          = array();
        $dados['MSG']   = $msg;
        $dados['COUNT'] = $countProcessos;
        return $dados;
    }

    protected function getObjControleDsmpAtivoRevisaoConectado($arrParams)
    {
        $idProcedimento = $arrParams[0];
        $isAnalise      = $arrParams[1];

        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setDblIdProcedimento($idProcedimento);
        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlControleDsmpDTO->retTodos();
        $objMdUtlControleDsmpDTO->retStrNomeUsuarioAtual();
        $objMdUtlControleDsmpDTO->retStrSiglaUsuarioAtual();
        $objMdUtlControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objMdUtlControleDsmpDTO->retStrNomeFila();
        $objMdUtlControleDsmpDTO->retNumIdContato();
        $objMdUtlControleDsmpDTO->retStrProtocoloProcedimentoFormatado();


        if($isAnalise){
            $objMdUtlControleDsmpDTO->retStrStaEncaminhamentoAnalise();
            $objMdUtlControleDsmpDTO->retNumIdMdUtlAdmFilaEncAnalise();
            $objMdUtlControleDsmpDTO->retStrNomeFilaEncAnalise();
        }else{
            $objMdUtlControleDsmpDTO->retStrStaEncaminhamentoTriagem();
            $objMdUtlControleDsmpDTO->retNumIdMdUtlAdmFilaEncTriagem();
            $objMdUtlControleDsmpDTO->retStrNomeFilaEncTriagem();
        }

        return $this->consultar($objMdUtlControleDsmpDTO);
    }

    protected function getObjDTOParametrizadoMeusProcessosConectado($arrParams)
    {

        //Set Params Recebidos
        $arrObjsFilaUsuDTO = array_key_exists(0, $arrParams) ? $arrParams[0] : null;
        $isGestorSipSei    = array_key_exists(1, $arrParams) ? $arrParams[1] : null;
        $idsTpProcesso     = array_key_exists(2, $arrParams) ? InfraArray::converterArrInfraDTO($arrParams[2], 'IdTipoProcedimento') : null;
        $idTipoControle    = array_key_exists(3, $arrParams) ? $arrParams[3] : null;
        $arrPost           = array_key_exists(4, $arrParams) ? $arrParams[4] : null;

        //Inicializa Vars
        $objDTO = new MdUtlProcedimentoDTO();

        //Set Campos definidos por Regras
        $objDTO->setStrStaEstadoProtocolo(ProtocoloRN::$TE_NORMAL);
        $objDTO->setStrStaNivelAcessoLocalProtocolo(array(ProtocoloRN::$NA_PUBLICO, ProtocoloRN::$NA_RESTRITO), InfraDTO::$OPER_IN);
        $objDTO->setControleDsmpTIPOFK(InfraDTO::$TIPO_FK_OBRIGATORIA);
        $objDTO->setNumIdUsuarioDistribuicao(SessaoSEI::getInstance()->getNumIdUsuario());
        $objDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objDTO->retDthPrazoTarefa();
        $objDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);

        $txtProcesso    = null;
        $isTipoProcesso = null;
        $isIdFila       = null;
        $isStrStatus    = null;

        if(!is_null($arrPost)){
            $txtProcesso    = array_key_exists('txtProcesso', $arrPost) && $arrPost['txtProcesso'] != '';
            $isTipoProcesso = array_key_exists('selTipoProcesso', $arrPost) && $arrPost['selTipoProcesso'] != '';
            $isIdFila       = array_key_exists('selFila', $arrPost) && $arrPost['selFila'] != '';
            $isStrStatus   = array_key_exists('selStatus', $arrPost) && $arrPost['selStatus'] != '';
        }

        if ($isTipoProcesso) {
            $objDTO->setNumIdTipoProcedimento($arrPost['selTipoProcesso']);
        }

        if ($isIdFila) {
            $objDTO->setNumIdFila($arrPost['selFila']);
        }

        if ($isStrStatus) {
            $objDTO->setStrStaAtendimentoDsmp(trim($arrPost['selStatus']));
        }

        if ($txtProcesso) {
            $objDTO->setStrProtocoloProcedimentoFormatado('%' . trim($arrPost['txtProcesso'] . '%'), InfraDTO::$OPER_LIKE);
        }

        return $objDTO;
    }

    protected function verificaExisteRelacionamentoAtivoAtividadeConectado($idAtividade)
    {
        $objMdRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
        $objMdRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
        $objMdRelTriagemAtvDTO->setNumIdMdUtlAdmAtividade($idAtividade);
        $objMdRelTriagemAtvDTO->retNumIdMdUtlTriagem();
        $objMdRelTriagemAtvDTO->setDistinct(true);

        if ($objMdRelTriagemAtvRN->contar($objMdRelTriagemAtvDTO) > 0) {
            $arrObjs = $objMdRelTriagemAtvRN->listar($objMdRelTriagemAtvDTO);
            $idsTriagem = InfraArray::converterArrInfraDTO($arrObjs, 'IdMdUtlTriagem');

            $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
            $objMdUtlControleDsmpDTO->setNumIdMdUtlTriagem($idsTriagem, InfraDTO::$OPER_IN);
            $objMdUtlControleDsmpDTO->retTodos();

            if ($this->contar($objMdUtlControleDsmpDTO) > 0) {
                return true;
            }
        }

        return false;
    }

    protected function validaExclusaoDocumentoConectado($objDocumentoAPI){
        $isValidoEx = true;
        $idDocumento = $objDocumentoAPI->getIdDocumento();

        $objMdUtlRelAnlProdutoDTO = new MdUtlRelAnaliseProdutoDTO();
        $objMdUtlRelAnlProdutoRN = new MdUtlRelAnaliseProdutoRN();
        $objMdUtlRelAnlProdutoDTO->setDblIdDocumento($idDocumento);
        $objMdUtlRelAnlProdutoDTO->retNumIdMdUtlAnalise();

        $count = $objMdUtlRelAnlProdutoRN->contar($objMdUtlRelAnlProdutoDTO);

        if($count > 0) {
            $arrAnalises = $objMdUtlRelAnlProdutoRN->listar($objMdUtlRelAnlProdutoDTO);
            $idsAnalises = InfraArray::converterArrInfraDTO($arrAnalises, 'IdMdUtlAnalise');

            $objMdUtlControleDTO = new MdUtlControleDsmpDTO();
            $objMdUtlControleDTO->setNumIdMdUtlAnalise($idsAnalises, InfraDTO::$OPER_IN);
            $objMdUtlControleDTO->retNumIdMdUtlControleDsmp();

            $isValidoEx = $this->contar($objMdUtlControleDTO) <= 0;
        }

        return $isValidoEx;
    }

    protected function pesquisarAtividadeConectado($objDTO){

        $idAtividade = $objDTO->getStrValorAtividadeSelectUtl();
        $arrObjs      = $this->listarProcessos($objDTO);

        $idsTriagem = InfraArray::converterArrInfraDTO($arrObjs, 'IdMdUtlTriagem');
        $idsTriagem = MdUtlControleDsmpINT::removeNullsTriagem($idsTriagem);
        $idsTriagemRetorno = null;
        if(count($idsTriagem) > 0) {
            $objMdUtlRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
            $objMdUtlRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
            $objMdUtlRelTriagemAtvDTO->setNumIdMdUtlTriagem($idsTriagem, InfraDTO::$OPER_IN);
            $objMdUtlRelTriagemAtvDTO->setNumIdMdUtlAdmAtividade($idAtividade);
            $objMdUtlRelTriagemAtvDTO->retTodos();

            $arrObjs = $objMdUtlRelTriagemAtvRN->listar($objMdUtlRelTriagemAtvDTO);

            $idsTriagemRetorno = InfraArray::converterArrInfraDTO($arrObjs, 'IdMdUtlTriagem');
        }

        return $idsTriagemRetorno;
    }


    protected function buscarUnidadeEsforcoConectado($arrParams){
        $idUsuarioParticipante = array_key_exists(0, $arrParams) ? $arrParams[0] : null;
        $idTipoControle        = array_key_exists(1, $arrParams) ? $arrParams[1] : null;
        $arrDatas              = array_key_exists(2, $arrParams) ? $arrParams[2] : null;
        $dtInicio              = $arrDatas['DT_INICIAL'];
        $dtFim                 = $arrDatas['DT_FINAL'];
        $numUnidEsforco        = 0;

        $dtInicio = MdUtlControleDsmpINT::formatarDatasComDoisDigitos($dtInicio);
        $dtFim = MdUtlControleDsmpINT::formatarDatasComDoisDigitos($dtFim);

        if(!is_null($idUsuarioParticipante) && !is_null($idTipoControle)) {
            $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
            $objMdUtlControleDsmpDTO->setNumIdUsuarioDistribuicao($idUsuarioParticipante);
            $objMdUtlControleDsmpDTO->setStrStaAtendimentoDsmp(array(MdUtlControleDsmpRN::$EM_REVISAO, MdUtlControleDsmpRN::$EM_TRIAGEM, MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE), InfraDTO::$OPER_IN);
            $objMdUtlControleDsmpDTO->retNumUnidadeEsforco();
            $objMdUtlControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
            $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objMdUtlControleDsmpDTO->adicionarCriterio(array('Atual', 'Atual', 'Atual'),
                array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
                array(null, $dtInicio, $dtFim),
                array(InfraDTO::$OPER_LOGICO_AND, InfraDTO::$OPER_LOGICO_AND));


            $count  = $this->contar($objMdUtlControleDsmpDTO);

            if($count > 0) {
                $arrUnidEsforco = $this->listar($objMdUtlControleDsmpDTO);

                foreach ($arrUnidEsforco as $objs) {
                    $numUnidEsforco += $objs->getNumUnidadeEsforco();
                }
            }

            return $numUnidEsforco;
        }
    }


    protected function controlarAjustePrazoControlado($arrDados){
        $arrIdProcedimento = array_key_exists(0, $arrDados) ? $arrDados[0] : null;
        $strStatus         = array_key_exists(1, $arrDados) ? $arrDados[1] : null;

      //verifica se os ids de Procedimento possuem
        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setDblIdProcedimento($arrIdProcedimento, InfraDTO::$OPER_IN);
        $objMdUtlControleDsmpDTO->setAjustePrazoFK(InfraDTO::$TIPO_FK_OBRIGATORIA);
        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlControleDsmpDTO->retTodos();
        $objMdUtlControleDsmpDTO->retStrStaTipoSolicitacaoAjustePrazo();
        $objMdUtlControleDsmpDTO->retStrStaSolicitacaoAjustePrazo();

        if($this->contar($objMdUtlControleDsmpDTO) > 0){
            $arrObjs = $this->listar($objMdUtlControleDsmpDTO);
            $dtNova = date('d/m/Y H:i:s', strtotime('+1 second'));
            $arrTipoSolicitacao = MdUtlControleDsmpINT::retornaSelectTipoSolicitacao();
            $objMdUtlHsControleRN = new MdUtlHistControleDsmpRN();

            foreach ($arrObjs as $objConsulta){
                if($objConsulta->getStrStaSolicitacaoAjustePrazo() == MdUtlAjustePrazoRN::$PENDENTE_RESPOSTA) {
                  $tpSolicitacao = $arrTipoSolicitacao[$objConsulta->getStrStaTipoSolicitacaoAjustePrazo()];
                    $objMdUtlHsControleDTO = new MdUtlHistControleDsmpDTO();
                    $objMdUtlHsControleDTO->setDblIdProcedimento($objConsulta->getDblIdProcedimento());
                    $objMdUtlHsControleDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                    $objMdUtlHsControleDTO->setNumIdMdUtlAjustePrazo($objConsulta->getNumIdMdUtlAjustePrazo());
                    $objMdUtlHsControleDTO->setNumIdMdUtlAdmFila($objConsulta->getNumIdMdUtlAdmFila());
                    $objMdUtlHsControleDTO->setNumIdUsuarioAtual(SessaoSEI::getInstance()->getNumIdUsuario());
                    $objMdUtlHsControleDTO->setNumIdUsuarioDistribuicao($objConsulta->getNumIdUsuarioDistribuicao());
                    $objMdUtlHsControleDTO->setNumIdMdUtlAdmTpCtrlDesemp($objConsulta->getNumIdMdUtlAdmTpCtrlDesemp());
                    $objMdUtlHsControleDTO->setNumIdMdUtlTriagem($objConsulta->getNumIdMdUtlTriagem());
                    $objMdUtlHsControleDTO->setNumIdMdUtlAnalise($objConsulta->getNumIdMdUtlAnalise());
                    $objMdUtlHsControleDTO->setNumIdMdUtlRevisao($objConsulta->getNumIdMdUtlRevisao());
                    $objMdUtlHsControleDTO->setNumUnidadeEsforco($objConsulta->getNumUnidadeEsforco());
                    $objMdUtlHsControleDTO->setDthAtual($dtNova);
                    $objMdUtlHsControleDTO->setDthFinal($dtNova);
                    $objMdUtlHsControleDTO->setStrStaAtendimentoDsmp($strStatus);
                    $objMdUtlHsControleDTO->setStrSinUltimaFila('N');
                    $objMdUtlHsControleDTO->setStrSinUltimoResponsavel('N');
                    $objMdUtlHsControleDTO->setStrDetalhe($tpSolicitacao);
                    $objMdUtlHsControleDTO->setNumIdAtendimento($objConsulta->getNumIdAtendimento());
                    $objMdUtlHsControleDTO->setStrTipoAcao('Cancelamento da Solicitação de Ajuste de Prazo');
                    $objMdUtlHsControleDTO->setStrSinAcaoConcluida('N');
                    $objMdUtlHsControleRN->cadastrar($objMdUtlHsControleDTO);
                }
            }
        }

        $this->verificarAjusteDePrazo($arrIdProcedimento);
    }

    protected function controlarContestacaoControlado($arrDados){
        $arrIdProcedimento = array_key_exists(0, $arrDados) ? $arrDados[0] : null;
        $strStatus         = array_key_exists(1, $arrDados) ? $arrDados[1] : null;

        //verifica se os ids de Procedimento possuem
        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setDblIdProcedimento($arrIdProcedimento, InfraDTO::$OPER_IN);
        $objMdUtlControleDsmpDTO->setContestacaoFK(InfraDTO::$TIPO_FK_OBRIGATORIA);
        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlControleDsmpDTO->retTodos();
        $objMdUtlControleDsmpDTO->retStrStaSolicitacaoContestacao();
        $objMdUtlControleDsmpDTO->retStrStaSinAtivoContestacao();
        $objMdUtlControleDsmpDTO->setStrStaSolicitacaoContestacao(MdUtlContestacaoRN::$PENDENTE_RESPOSTA);

        if($this->contar($objMdUtlControleDsmpDTO) > 0){

            $arrObjs = $this->listar($objMdUtlControleDsmpDTO);

            $dtNova = date('d/m/Y H:i:s', strtotime('+1 second'));
            $objMdUtlHsControleRN = new MdUtlHistControleDsmpRN();

            foreach ($arrObjs as $objConsulta){
                    $objMdUtlHsControleDTO = new MdUtlHistControleDsmpDTO();
                    $objMdUtlHsControleDTO->setDblIdProcedimento($objConsulta->getDblIdProcedimento());
                    $objMdUtlHsControleDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                    $objMdUtlHsControleDTO->setNumIdMdUtlContestRevisao(null);
                    $objMdUtlHsControleDTO->setNumIdMdUtlAdmFila($objConsulta->getNumIdMdUtlAdmFila());
                    $objMdUtlHsControleDTO->setNumIdUsuarioAtual(SessaoSEI::getInstance()->getNumIdUsuario());
                    $objMdUtlHsControleDTO->setNumIdUsuarioDistribuicao($objConsulta->getNumIdUsuarioDistribuicao());
                    $objMdUtlHsControleDTO->setNumIdMdUtlAdmTpCtrlDesemp($objConsulta->getNumIdMdUtlAdmTpCtrlDesemp());
                    $objMdUtlHsControleDTO->setNumIdMdUtlTriagem($objConsulta->getNumIdMdUtlTriagem());
                    $objMdUtlHsControleDTO->setNumIdMdUtlAnalise($objConsulta->getNumIdMdUtlAnalise());
                    $objMdUtlHsControleDTO->setNumIdMdUtlRevisao($objConsulta->getNumIdMdUtlRevisao());
                    $objMdUtlHsControleDTO->setNumUnidadeEsforco($objConsulta->getNumUnidadeEsforco());
                    $objMdUtlHsControleDTO->setDthAtual($dtNova);
                    $objMdUtlHsControleDTO->setDthFinal($dtNova);
                    $objMdUtlHsControleDTO->setStrStaAtendimentoDsmp($strStatus);
                    $objMdUtlHsControleDTO->setStrSinUltimaFila('N');
                    $objMdUtlHsControleDTO->setStrSinUltimoResponsavel('N');
                    $objMdUtlHsControleDTO->setStrDetalhe(MdUtlContestacaoRN::$STR_CANCELAMENTO);
                    $objMdUtlHsControleDTO->setNumIdAtendimento($objConsulta->getNumIdAtendimento());
                    $objMdUtlHsControleDTO->setStrTipoAcao(MdUtlControleDsmpRN::$STR_TIPO_CONTESTACAO_REVISAO);
                    $objMdUtlHsControleDTO->setStrSinAcaoConcluida('N');
                    $objMdUtlHsControleRN->cadastrar($objMdUtlHsControleDTO);
            }

            $this->verificarContestacao($arrObjs);
        }

    }

    protected function getObjControleDsmpPorIdConectado($idControleDesempenho)
    {
        $objProcedimentoDTO = new MdUtlProcedimentoDTO();
        $objProcedimentoDTO->setNumIdMdUtlControleDsmp($idControleDesempenho);
        $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
        $objProcedimentoDTO->retDblIdProcedimento();
        $objProcedimentoDTO->retStrStaAtendimentoDsmp();
        $objProcedimentoDTO->retDthPrazoTarefa();
        $objProcedimentoDTO->retNumIdMdUtlRevisao();
        $objProcedimentoDTO->retNumUnidadeEsforco();
        $objProcedimentoDTO->retNumIdMdUtlTriagem();
        $objProcedimentoDTO->retNumIdMdUtlAnalise();
        $objProcedimentoDTO->retNumIdMdUtlRevisao();
        $objProcedimentoDTO->retNumIdFila();
        $objProcedimentoDTO->retNumIdMdUtlAdmTpCtrlDesemp();
        $objProcedimentoDTO->retNumUnidadeEsforco();
        $objProcedimentoDTO->retNumIdUsuarioDistribuicao();
        $objProcedimentoDTO->retNumIdMdUtlAjustePrazo();
        $objProcedimentoDTO->retDthAtual();
        $objProcedimentoDTO->setControleDsmpTIPOFK(InfraDTO::$TIPO_FK_OBRIGATORIA);
        $objProcedimentoDTO->setNumMaxRegistrosRetorno(1);

        $objProcedimentoRN = new ProcedimentoRN();

        $objDTO =  $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);

        return $objDTO;
    }


    public function verificarDuplicidadeUsuario($arrIdProcedimento, $objInfraException, $isDistribuicao = true)
    {
        try {
            $idUsuPart = $isDistribuicao ? $_POST['hdnIdUsuarioParticipanteLupa'] : SessaoSEI::getInstance()->getNumIdUsuario();

            $protProcedimentoFormatado = array();

            $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
            $objMdUtlControleDsmpDTO->setDblIdProcedimento($arrIdProcedimento, InfraDTO::$OPER_IN);
            $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objMdUtlControleDsmpDTO->setNumIdUsuarioDistribuicao($idUsuPart);
            $objMdUtlControleDsmpDTO->retTodos();
            $objMdUtlControleDsmpDTO->retStrProtocoloProcedimentoFormatado();

            $count = $this->contar($objMdUtlControleDsmpDTO);

            if ($count > 0) {

                $arrObjs = $this->listar($objMdUtlControleDsmpDTO);

                foreach ($arrObjs as $obj) {
                    array_push($protProcedimentoFormatado, $obj->getStrProtocoloProcedimentoFormatado());
                }


                if (count($protProcedimentoFormatado) == 1) {
                    $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_92, $protProcedimentoFormatado);
                } else {
                    $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_93);
                    $msg = $msg . '\n';
                    for ($i = 0; $i < sizeof($protProcedimentoFormatado); $i++) {
                        $msg .= ' \n - ' . $protProcedimentoFormatado[$i];
                    }

                }

                $_POST['txtUsuarioParticipante'] = '';

                $objInfraException->adicionarValidacao($msg);
            }
        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando Item do Conjunto de Estilos.', $e);
        }
    }

    protected function verificarAjusteDePrazoControlado($arrIdProcedimento){

        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlAjustePrazoRN   = new MdUtlAjustePrazoRN();

        $objMdUtlControleDsmpDTO->setDblIdProcedimento($arrIdProcedimento, InfraDTO::$OPER_IN);
        $objMdUtlControleDsmpDTO->setAjustePrazoFK(InfraDTO::$TIPO_FK_OBRIGATORIA);
        $objMdUtlControleDsmpDTO->setStrSinAtivoAjustePrazo('S');
        $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlAjustePrazo();
        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        $count = $this->contar($objMdUtlControleDsmpDTO);

        if ($count > 0) {
            $arrIdAjustePrazo = $this->listar($objMdUtlControleDsmpDTO);
            $arrObjs = array();

            foreach ($arrIdAjustePrazo as $objAjustPrazoDTO) {
                $objMdUtlAjustePrazoDTO = new MdUtlAjustePrazoDTO();
                $objMdUtlAjustePrazoDTO->setNumIdMdUtlAjustePrazo($objAjustPrazoDTO->getNumIdMdUtlAjustePrazo());
                $arrObjs[] = $objMdUtlAjustePrazoDTO;
            }

            $objMdUtlAjustePrazoRN->desativar($arrObjs);
        }
    }

    private function verificarContestacao($arrObjs){
        $objContestacaoRN = new MdUtlContestacaoRN();
        $arrIdContestacao = InfraArray::converterArrInfraDTO($arrObjs, 'IdMdUtlContestRevisao');

        if (count($arrIdContestacao) > 0) {

            foreach ($arrIdContestacao as $idContestacao) {
                $objMdUtlContestacaoDTO = new MdUtlContestacaoDTO();
                $objMdUtlContestacaoDTO->setNumIdMdUtlContestRevisao($idContestacao);
                $arrObjsContest[] = $objMdUtlContestacaoDTO;
            }

            $objContestacaoRN->desativar($arrObjsContest);
        }
    }

    protected function retornaStatusImpedidoControlado($arrObjCtrlDsmpDTO = null)
    {
        /*Recuperar os processos com os status igual a Suspenso e Interrompido com a data fim de suspensão/Interrupção igual D-1.*/
        if (is_null($arrObjCtrlDsmpDTO)) {
            $arrObjCtrlDsmpDTO = $this->_getAjusteSuspensaoInterrupcao();
        }

        /*buscar em historico a ultima alteração do processo*/
        if(!is_null($arrObjCtrlDsmpDTO) && $arrObjCtrlDsmpDTO[0] != null) {
            $this->_getHistoricoProcedimento($arrObjCtrlDsmpDTO);
        }
    }

    /*Método para retornar o ultimo status do historio dos ajustes suspensos e interrompidos com D-1*/
    private function _getAjusteSuspensaoInterrupcao(){
        $data          = InfraData::getStrDataAtual();
        $data          .= ' 00:00:00';

        $objCtrlDsmpDTO = new MdUtlControleDsmpDTO();
        $objCtrlDsmpDTO->setDthPrazoTarefa($data, InfraDTO::$OPER_MENOR);
        $objCtrlDsmpDTO->setStrStaAtendimentoDsmp(array(MdUtlControleDsmpRN::$SUSPENSO, MdUtlControleDsmpRN::$INTERROMPIDO), InfraDTO::$OPER_IN);
        $objCtrlDsmpDTO->setStrStaTipoSolicitacaoAjustePrazo(array(MdUtlControleDsmpRN::$TP_SOLICITACAO_INTERRUPCAO, MdUtlControleDsmpRN::$TP_SOLICITACAO_SUSPENSAO), InfraDTO::$OPER_IN);
        $objCtrlDsmpDTO->setOrdDthAtual(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objCtrlDsmpDTO->setAjustePrazoFK(InfraDTO::$TIPO_FK_OBRIGATORIA);
        $objCtrlDsmpDTO->retTodos();
        $objCtrlDsmpDTO->retNumDiasUteisExcedentes();

        $objCtrlDsmpRN  = new MdUtlControleDsmpRN();

        $count =  $objCtrlDsmpRN->contar($objCtrlDsmpDTO);

        if($count > 0){
            return  $objCtrlDsmpRN->listar($objCtrlDsmpDTO);
        }

        return null;
    }

    /*Método para retornar o último status do processo em histórico*/
    private function _getHistoricoProcedimento($arrObjCtrlDsmpDTO){

        /*Busca id do usuário de utilidades para agendamento automático do sistema*/
        $objUsuarioRN = new MdUtlUsuarioRN();
        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioDTO = $objUsuarioRN->getObjUsuarioUtilidades();

        foreach ($arrObjCtrlDsmpDTO as $objControleDsmpDTO){
            $idProcedimento  = $objControleDsmpDTO->getDblIdProcedimento();
            $idUnidade       = $objControleDsmpDTO->getNumIdUnidade();
            $idFila          = $objControleDsmpDTO->getNumIdMdUtlAdmFila();
            $idTpCtrl        = $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp();
            $strStatusAtual  = $objControleDsmpDTO->getStrStaAtendimentoDsmp();
            $idTriagem       = $objControleDsmpDTO->getNumIdMdUtlTriagem();
            $idAnalise       = $objControleDsmpDTO->getNumIdMdUtlAnalise();
            $idRevisao       = $objControleDsmpDTO->getNumIdMdUtlRevisao();
            $undEsforco      = $objControleDsmpDTO->getNumUnidadeEsforco();
            $idUsuarioDistr  = $objControleDsmpDTO->getNumIdUsuarioDistribuicao();
            $dthPrazo        = $objControleDsmpDTO->getDthPrazoTarefa();
            $diasExcedentes  = $objControleDsmpDTO->getNumDiasUteisExcedentes();
            $strTipoAcao     = MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETORNO_STATUS;
            $objHistoricoDTO = new MdUtlHistControleDsmpDTO();
            $objHistoricoDTO->setDblIdProcedimento($idProcedimento);
            $objHistoricoDTO->setNumIdUnidade($idUnidade);
            $objHistoricoDTO->setStrStaAtendimentoDsmp(array(MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE, MdUtlControleDsmpRN::$EM_REVISAO), InfraDTO::$OPER_IN);
            $objHistoricoDTO->setOrdDthAtual(InfraDTO::$TIPO_ORDENACAO_DESC);
            $objHistoricoDTO->setNumMaxRegistrosRetorno(1);
            $objHistoricoDTO->retTodos();

            $objHistoricoRN = new MdUtlHistControleDsmpRN();
            $arrObjHistoricoDTO = $objHistoricoRN->consultar($objHistoricoDTO);

            if(!is_null($arrObjHistoricoDTO)) {

                $strStatusHist = $arrObjHistoricoDTO->getStrStaAtendimentoDsmp();

                SessaoSEI::getInstance()->simularLogin(null, null, $objUsuarioDTO->getNumIdUsuario(), $idUnidade);


                $triagemRN = new MdUtlTriagemRN();
                $prazoExecucaoAtividade = $triagemRN->getNumPrazoAtividadePorTriagem($idTriagem);
                $prazoRevisaoAtividade = $triagemRN->getNumPrazoAtividadePorTriagemParaRev($idTriagem);

                $prazoRN = new MdUtlPrazoRN();
                /*Se suspenso data_atual + (dias uteis entre data_prazo e data_solicitacao)*/
                if ($strStatusAtual == MdUtlControleDsmpRN::$SUSPENSO) {
                    $dthPrazo = $prazoRN->somarDiaUtil($diasExcedentes, InfraData::getStrDataHoraAtual());
                    $strDetalheAjust = MdUtlControleDsmpRN::$STR_FIM_SUSPENSAO;
                }

                /*Se interrompido e analise ou correção_analise data_atual + prazo_execucao_atividade (considerar a atividade com maior prazo)
                  Se interrompido e revisao data_atual + prazo_revisao_atividade (considerar a atividade com maior prazo)*/
                if ($strStatusAtual == MdUtlControleDsmpRN::$INTERROMPIDO) {
                    $strDetalheAjust = MdUtlControleDsmpRN::$STR_FIM_INTERRUPCAO;
                    if ($strStatusHist == MdUtlControleDsmpRN::$EM_ANALISE || $strStatusHist == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE) {
                        $dthPrazo = $prazoRN->somarDiaUtil($prazoExecucaoAtividade, InfraData::getStrDataHoraAtual());
                    } else {
                        $dthPrazo = $prazoRN->somarDiaUtil($prazoRevisaoAtividade, InfraData::getStrDataHoraAtual());
                    }
                }

                $strStatusAtual = $strStatusHist;

                $arrIds = array($idProcedimento);
                $arrObjsAtuais = $this->getObjsAtivosPorProcedimentoPorUnidade(array($arrIds, $idUnidade));
                $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, $arrIds, 'N', 'N', 'N', $idUnidade));

                $this->excluir($arrObjsAtuais);
                $this->cadastrarNovaSituacaoProcesso(array($idProcedimento, $idFila, $idTpCtrl, $strStatusAtual, $idUnidade, $undEsforco, $idUsuarioDistr, $idTriagem, $idAnalise, $idRevisao, $strDetalheAjust, $strTipoAcao, null, null, $dthPrazo));
            }

        }
    }

    protected function getArrVinculosExistentesConectado($arrParams){
        $idsUsuarios = array_key_exists(0, $arrParams) ? $arrParams[0] : null;
        $idFila      = array_key_exists(1, $arrParams) ? $arrParams[1] : null;
        $arrRetorno = array();

        if(!is_null($idsUsuarios) && !is_null($idFila)) {
            $objMdUtlAdmDsmpDTO = new MdUtlControleDsmpDTO();
            $objMdUtlAdmDsmpDTO->setNumIdUsuarioDistribuicao($idsUsuarios, InfraDTO::$OPER_IN);
            $objMdUtlAdmDsmpDTO->setNumIdMdUtlAdmFila($idFila);
            $objMdUtlAdmDsmpDTO->retNumIdUsuarioDistribuicao();

            $count   = $this->contar($objMdUtlAdmDsmpDTO);
            
            if($count > 0) {
                $arrObjs = $this->listar($objMdUtlAdmDsmpDTO);

                foreach ($arrObjs as $objDTO) {
                    array_push($arrRetorno, $objDTO->getNumIdUsuarioDistribuicao());
                }
            }
        }
        
        return $arrRetorno;
    }
    
    protected function validaExistenciaProcessoAtivoConectado($arrParams){
        $idProcesso = array_key_exists(0, $arrParams) ? $arrParams[0] : null;
        $idUnidade  = array_key_exists(1, $arrParams) ? $arrParams[1] : null;

        $objControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objControleDsmpDTO->setDblIdProcedimento($idProcesso);
        $objControleDsmpDTO->setNumIdUnidade($idUnidade);
        $objControleDsmpDTO->retNumIdMdUtlControleDsmp();
        return $this->contar($objControleDsmpDTO) > 0;
    }

    protected function corrigirCampoUltimaFilaConectado(){
        $objMdUtlPrmGrDTO = new MdUtlAdmPrmGrDTO();
        $objMdUtlPrmGrDTO->setStrSinRetornoUltFila('0');
        $objMdUtlPrmGrDTO->retNumIdMdUtlAdmPrmGr();
        $objMdUtlPrmGrRN = new MdUtlAdmPrmGrRN();
        $count = $objMdUtlPrmGrRN->contar($objMdUtlPrmGrDTO);

        if($count > 0){
            $arrObjsPrmGrDTO = $objMdUtlPrmGrRN->listar($objMdUtlPrmGrDTO);

            foreach($arrObjsPrmGrDTO as $objDTO){
                $objDTO->setStrSinRetornoUltFila(null);
                $objMdUtlPrmGrRN->alterar($objDTO);
            }
        }
    }

    protected function atribuirDistribuicaoUsuarioLogadoControlado(){
        try{

           $objHistoricoRN = new MdUtlHistControleDsmpRN();

            $idCtrlDsmp = $_GET['id_controle_dsmp'];
            $idStatus   = $_GET['status'];
            $idFila     = $_GET['id_fila'];
            $idTpCtrl   = $_GET['id_tp_ctrl'];
            $idProcedimento = $_GET['id_procedimento'];
            $idUsuarioLgd =  SessaoSEI::getInstance()->getNumIdUsuario();
            $nomeUsuarioLgd =SessaoSEI::getInstance()->getStrNomeUsuario(). ' ('.SessaoSEI::getInstance()->getStrSiglaUsuario().')';
            $sinFila ='N';
            $sinResponsavel ='N';

            $arrIdProcedimento = array($idProcedimento);
            $idNovoStatus = MdUtlControleDsmpINT::getProximoStatusDistribuicao($idStatus);

            $arrObjsAtuais = $this->getObjsAtivosPorProcedimento($arrIdProcedimento);

            if (!is_null($arrObjsAtuais)) {
                $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, $arrIdProcedimento, $sinFila, $sinResponsavel));
            }

            $this->excluir($arrObjsAtuais);

            $objAtribuirDTO     = new AtribuirDTO();
            $arrObjProtocoloDTO = array();

            $undEsforco            = $arrRetorno[$idProcedimento]['UNIDADE_ESFORCO'];
            $idTriagem             = $arrRetorno[$idProcedimento]['ID_TRIAGEM'];
            $idAnalise             = $arrRetorno[$idProcedimento]['ID_ANALISE'];
            $idRevisao             = $arrRetorno[$idProcedimento]['ID_REVISAO'];

            $arrParams = array($idProcedimento, $idFila, $idTpCtrl, $idNovoStatus, null, $undEsforco, $idUsuarioLgd, $idTriagem, $idAnalise, $idRevisao, $nomeUsuarioLgd, MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO);
            $this->cadastrarNovaSituacaoProcesso($arrParams);

            $objAtribuirDTO->setNumIdUsuarioAtribuicao($idUsuarioLgd);
            $objProtocoloDTO = new ProtocoloDTO();
            $objProtocoloDTO->setDblIdProtocolo($idProcedimento);
            $arrObjProtocoloDTO[] = $objProtocoloDTO;

            $objAtividadeRN  = new AtividadeRN();
            $objAtribuirDTO->setArrObjProtocoloDTO($arrObjProtocoloDTO);
            $objAtividadeRN->atribuirRN0985($objAtribuirDTO);

        }catch (Exception $e){
            throw new InfraException('Erro realizando a Atribuição.', $e);
        }
    }

}
