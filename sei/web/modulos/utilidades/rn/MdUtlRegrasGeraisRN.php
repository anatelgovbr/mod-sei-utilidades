<?php
/**
 * Created by PhpStorm.
 * User: jaqueline.mendes
 * Date: 09/08/2018
 * Time: 15:52
 */

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdUtlRegrasGeraisRN extends InfraRN
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function verificarExistenciaUnidadeConectado($params)
    {
        $arrObjUnidadeAPI = $params[0];
        $acao = $params[1];

        $arrIds = array();
        $msg = '';
        foreach ($arrObjUnidadeAPI as $objUnidade) {
            $arrIds[] = $objUnidade->getIdUnidade();
        }

        $objMdUtlAdmRelTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
        $objMdUtlAdmRelTpCtrlUndDTO = new MdUtlAdmRelTpCtrlDesempUndDTO();
        $objMdUtlAdmRelTpCtrlUndDTO->setNumIdUnidade($arrIds, InfraDTO::$OPER_IN);
        $objMdUtlAdmRelTpCtrlUndDTO->retTodos();

        $existeUnidade = $objMdUtlAdmRelTpCtrlUndRN->contar($objMdUtlAdmRelTpCtrlUndDTO) > 0;

        if ($existeUnidade) {
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_43, array($acao));
        }

        return $msg;
    }

    protected function verificarExistenciaTipoDocumentoConectado($params)
    {
        $arrObjSerieAPI = $params[0];
        $acao = $params[1];

        $arrIds = array();
        $msg = '';
        foreach ($arrObjSerieAPI as $objSerie) {
            $arrIds[] = $objSerie->getIdSerie();
        }

        $objMdUtlAdmAtvSerieProdRN = new MdUtlAdmAtvSerieProdRN();

        $objMdUtlAdmAtvSerieProdDTO = new MdUtlAdmAtvSerieProdDTO();
        $objMdUtlAdmAtvSerieProdDTO->setNumIdSerie($arrIds, InfraDTO::$OPER_IN);
        $objMdUtlAdmAtvSerieProdDTO->retTodos();

        $existeTpDocumento = $objMdUtlAdmAtvSerieProdRN->contar($objMdUtlAdmAtvSerieProdDTO) > 0;

        if ($existeTpDocumento) {
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_34, array($acao));
        }

        return $msg;
    }

    protected function verificarExistenciaTipoProcessoConectado($params)
    {
        $arrObjTpProcAPI = $params[0];
        $acao = $params[1];

        $arrIds = array();
        $msg = '';
        foreach ($arrObjTpProcAPI as $objSerie) {
            $arrIds[] = $objSerie->getIdTipoProcedimento();
        }

        $objMdUtlAdmRelPrmGrProcRN = new MdUtlAdmRelPrmGrProcRN();
        $objMdUtlAdmRelPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO();
        $objMdUtlAdmRelPrmGrProcDTO->setNumIdTipoProcedimento($arrIds, InfraDTO::$OPER_IN);
        $objMdUtlAdmRelPrmGrProcDTO->retTodos(true);

        $existeTpProcedimento = $objMdUtlAdmRelPrmGrProcRN->contar($objMdUtlAdmRelPrmGrProcDTO) > 0;

        if ($existeTpProcedimento) {
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_39, array($acao));

            if($acao=='desativar'){
                $msg = 'Não é possível desativar este Tipo de Processo, pois ele ainda está associado aos Tipos de Controle de Desempenho abaixo:\n\n';

                $listaTpProcedimento = $objMdUtlAdmRelPrmGrProcRN->listar($objMdUtlAdmRelPrmGrProcDTO);
                if($listaTpProcedimento) {
                    foreach ($listaTpProcedimento as $TpControleDesemp) {
                        $msg .= '- ' . $TpControleDesemp->getStrNomeTipoControle() . '\n';
                    }
                }

            }

        }

        return $msg;
    }

    private function _validarGestorTipoControle($arrIds, $acao)
    {
        $msg = '';

        $objMdUtlAdmRelTpCtrlUsuRN = new MdUtlAdmRelTpCtrlDesempUsuRN();
        $objMdUtlAdmRelTpCtrlUsuDTO = new MdUtlAdmRelTpCtrlDesempUsuDTO();
        $objMdUtlAdmRelTpCtrlUsuDTO->setNumIdUsuario($arrIds, InfraDTO::$OPER_IN);
        $objMdUtlAdmRelTpCtrlUsuDTO->retTodos();

        $existeGestor = $objMdUtlAdmRelTpCtrlUsuRN->contar($objMdUtlAdmRelTpCtrlUsuDTO) > 0;

        if ($existeGestor) {
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_40, array($acao));
        }

        return $msg;
    }

    private function _validarControleDsmp($arrIds, $acao)
    {
        $msg = '';

        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->retTodos();

        $objMdUtlControleDsmpDTO->adicionarCriterio(array('IdUsuarioAtual', 'IdUsuarioDistribuicao'), array(InfraDTO::$OPER_IN, InfraDTO::$OPER_IN), array($arrIds, $arrIds), array(InfraDTO::$OPER_LOGICO_OR));
        $existeControleDsmp = $objMdUtlControleDsmpRN->contar($objMdUtlControleDsmpDTO) > 0;

        if ($existeControleDsmp) {
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_41, array($acao));
        }

        return $msg;

    }

    private function _validarHsControleDsmp($arrIds, $acao)
    {

        $msg = '';

        $objMdUtlHistControleDsmpRN = new MdUtlHistControleDsmpRN();
        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->retTodos();

        $objMdUtlHistControleDsmpDTO->adicionarCriterio(array('IdUsuarioAtual', 'IdUsuarioDistribuicao'), array(InfraDTO::$OPER_IN, InfraDTO::$OPER_IN), array($arrIds, $arrIds), array(InfraDTO::$OPER_LOGICO_OR));
        $existeHsControleDsmp = $objMdUtlHistControleDsmpRN->contar($objMdUtlHistControleDsmpDTO) > 0;

        if ($existeHsControleDsmp) {
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_42, array($acao));
        }

        return $msg;

    }

    protected function verificarExistenciaUsuarioConectado($params)
    {

        $arrObjUsuarioAPI = $params[0];
        $acao = $params[1];
        $arrIds = array();
        $msg = '';


        foreach ($arrObjUsuarioAPI as $objUsuario) {
            $arrIds[] = $objUsuario->getIdUsuario();
        }


        //Validar Controle DSMP
        $msg = $this->_validarControleDsmp($arrIds, $acao);
        if ($msg != '') {
            return $msg;
        }

        //Validar Histórico Controle DSMP
        $msg = $this->_validarHsControleDsmp($arrIds, $acao);
        if ($msg != '') {
            return $msg;
        }


        //Validar Gestor no Tipo de Controle
        $msg = $this->_validarGestorTipoControle($arrIds, $acao);
        if ($msg != '') {
            return $msg;
        }

        //Validar Usuário Participante na Parametrização do Tipo de Controle
        $msg = $this->_validarUsuarioParticipanteParametrizacao($arrIds, $acao);
        if ($msg != '') {
            return $msg;
        }
    }

    private function _validarUsuarioParticipanteParametrizacao($arrIds, $acao)
    {
        $objMdUtlAdmRelPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();
        $objMdUtlAdmRelPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
        $objMdUtlAdmRelPrmGrUsuDTO->setNumIdUsuario($arrIds, InfraDTO::$OPER_IN);
        $objMdUtlAdmRelPrmGrUsuDTO->retTodos();

        $existeUsuarioParticipante = $objMdUtlAdmRelPrmGrUsuRN->contar($objMdUtlAdmRelPrmGrUsuDTO) > 0;

        if ($existeUsuarioParticipante) {
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_36, array($acao));
        }

        return $msg;
    }

    private function _validarUsuarioParticipanteJornada($arrIds, $acao)
    {
        $objMdUtlAdmRelJornadaUsuRN = new MdUtlAdmRelJornadaUsuRN();
        $objMdUtlAdmRelJornadaUsuDTO = new MdUtlAdmRelJornadaUsuDTO();
        $objMdUtlAdmRelJornadaUsuDTO->setNumIdUsuario($arrIds, InfraDTO::$OPER_IN);
        $objMdUtlAdmRelJornadaUsuDTO->retTodos();

        $existeUsuarioParticipante = $objMdUtlAdmRelJornadaUsuRN->contar($objMdUtlAdmRelJornadaUsuDTO) > 0;

        if ($existeUsuarioParticipante) {
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_37, array($acao));
        }

        return $msg;
    }

    protected function getObjProcedimentoPorIdConectado($idProcedimento = null)
    {
        $objRn = new ProcedimentoRN();
        if (!is_null($idProcedimento)) {
            $objProcedimentoDTO = new ProcedimentoDTO();
            $objProcedimentoDTO->setDblIdProcedimento($idProcedimento);
            $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
            $objProcedimentoDTO->retTodos();
            $count = $objRn->contarRN0279($objProcedimentoDTO);

            if ($count > 0) {
                $objProcedimentoDTO->setNumMaxRegistrosRetorno(1);
                $objProcedimentoDTO = $objRn->consultarRN0201($objProcedimentoDTO);
                return $objProcedimentoDTO;
            }
        }

        return null;
    }

    protected function retornaArrDadosDocumentoSEIConectado($arrParams)
    {
        $arrDados = array();
        $numeroSei = array_key_exists(0, $arrParams) && $arrParams[0] != '' ? $arrParams[0] : false;
        $numeroSei = trim($numeroSei);
        $idProced = array_key_exists(1, $arrParams) && $arrParams[1] != '' ? $arrParams[1] : false;
        $idSerieAtv = array_key_exists(2, $arrParams) && $arrParams[2] != '' ? $arrParams[2] : false;
        $isPossuiAssinatura = true;

        if ($numeroSei && $idProced && $idSerieAtv) {

            $objDocumentoDTO = new DocumentoDTO();

            if ($numeroSei) {
                $objDocumentoDTO->setStrProtocoloDocumentoFormatado($numeroSei);
            }

            $objDocumentoDTO->retDblIdDocumento();
            $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
            $objDocumentoDTO->retArrObjAssinaturaDTO();
            $objDocumentoDTO->retStrStaDocumento();
            $objDocumentoDTO->retDtaGeracaoProtocolo();
            $objDocumentoDTO->retStrNomeSerie();
            $objDocumentoDTO->retNumIdSerie();
            $objDocumentoDTO->retDblIdProcedimento();
            $objDocumentoDTO->retStrNumero();
            $objDocumentoDTO->setNumMaxRegistrosRetorno(1);

            $objDocumentoRN = new DocumentoRN();
            $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

            if (!is_null($objDocumentoDTO)) {
                if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO) {
                    $isPossuiAssinatura = false;
                    $arrAssinatura = ! $objDocumentoDTO->isSetArrObjAssinaturaDTO() ? null : $objDocumentoDTO->getArrObjAssinaturaDTO();
                    if (!is_null($arrAssinatura)) {
                        $objAssinaturaDTO = new AssinaturaDTO();
                        $objAssinaturaDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
                        $objAssinaturaDTO->retDthAberturaAtividade();
                        $objAssinaturaDTO->setOrdDthAberturaAtividade(InfraDTO::$TIPO_ORDENACAO_ASC);
                        $objAssinaturaRN = new AssinaturaRN();
                        $arrObjAssinaturaDTO = $objAssinaturaRN->listarRN1323($objAssinaturaDTO);
                        $countAss = $objAssinaturaRN->contarRN1324($objAssinaturaDTO);
                        $isPossuiAssinatura = $countAss > 0 ? true : false;
                    }
                }
            }
        } else {
            return array('msg' => 'Dados Incompletos!', 'erro' => true);
        }

        $arrDados = $this->_retornaArrFormatadoValidacoesDoc($objDocumentoDTO, $isPossuiAssinatura, $idProced, $idSerieAtv);

        return $arrDados;
    }

    private function _retornaArrFormatadoValidacoesDoc($objDocumentoDTO, $isPossuiAssinatura, $idProced, $idSerieAtv)
    {

        //$docsPermt = false;
        $arrDados['erro'] = false;
        $arrDados['msg'] = '';

        //Valida Existência do Número SEI
        if (is_null($objDocumentoDTO)) {
            $arrDados['msg'] = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_29);
            $arrDados['erro'] = true;
            return $arrDados;
        }


        //Verificando se Documento pertence a esse processo
        if ($idProced) {
            if ($idProced != $objDocumentoDTO->getDblIdProcedimento()) {
                $arrDados['msg'] = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_30);
                $arrDados['erro'] = true;
                return $arrDados;
            }
        }

        //Verificando se o tipo de documento é do tipo exigido pela Atividade
        if ($idSerieAtv) {
            if ($idSerieAtv != $objDocumentoDTO->getNumIdSerie()) {
                $arrDados['msg'] = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_31);
                $arrDados['erro'] = true;
                return $arrDados;
            }
        }

        //Verifica se os Tipos de Documento são permitidos
        // TODO: Desativada valiação desnecessária do tipo de documento permitido do protocolo na tela de Análise
        /*   $docsPermt = $objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO || $objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EXTERNO;
        if (!$docsPermt) {
            $arrDados['msg'] = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_32);
            $arrDados['erro'] = true;
            return $arrDados;
        }*/


        /*   if(!$isPossuiAssinatura){
               $arrDados['msg']  = 'Documentos Internos devem estar assinados para serem vinculados!';
               $arrDados['erro'] =  true;
               return $arrDados;
           }*/

        /*      if($staAplSolic != $objDocumentoDTO->getStrStaDocumento()){
          $arrDados['erro'] =  true;
          return true;
      }*/

        return $arrDados;

    }

    protected function getUltimoUsuarioAtribuidoUnidadeLogadaConectado($idProcedimento = null)
    {

        if (!is_null($idProcedimento)) {
            $objAtividadeRN = new AtividadeRN();
            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setDblIdProtocolo($idProcedimento);
            $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_ATRIBUIDO);
            $objAtividadeDTO->setOrdDthAbertura(InfraDTO::$TIPO_ORDENACAO_DESC);
            $objAtividadeDTO->setNumMaxRegistrosRetorno(1);
            $objAtividadeDTO->retTodos();
            $objRetorno = $objAtividadeRN->consultarRN0033($objAtividadeDTO);
            return !is_null($objRetorno) ? $objRetorno->getNumIdUsuarioAtribuicao() : null;
        }

        return null;
    }

    protected function validarSituacaoProcessoConectado($idProcedimento = null)
    {
        $isValido = false;
        if (!is_null($idProcedimento)) {
            $objProcedimentoDTO = new ProcedimentoDTO();
            $objProcedimentoDTO->setDblIdProcedimento($idProcedimento);
            $objProcedimentoDTO->setStrStaEstadoProtocolo(ProtocoloRN::$TE_NORMAL);
            $objProcedimentoDTO->setStrStaNivelAcessoLocalProtocolo(array(ProtocoloRN::$NA_PUBLICO, ProtocoloRN::$NA_RESTRITO, ProtocoloRN::$NA_SIGILOSO), InfraDTO::$OPER_IN);
            $objProcedimentoDTO->setNumMaxRegistrosRetorno(1);

            $objProcedimentoRN = new ProcedimentoRN();
            $isValido = $objProcedimentoRN->contarRN0279($objProcedimentoDTO) > 0;
        }

        return $isValido;
    }

    protected function verificaConclusaoProcessoConectado($arrDados)
    {
        $isConcluido = true;
        $idProcedimento = array_key_exists('0', $arrDados) ? $arrDados[0] : null;
        $idUnidade = array_key_exists('1', $arrDados) ? $arrDados[1] : SessaoSEI::getInstance()->getNumIdUnidadeAtual();

        if (!is_null($idProcedimento)) {
            $objEntradaProcedimentoAPI = new EntradaConsultarProcedimentoAPI();
            $objEntradaProcedimentoAPI->setSinRetornarUnidadesProcedimentoAberto('S');
            $objEntradaProcedimentoAPI->setIdProcedimento($idProcedimento);

            $objSEIRN = new SeiRN();
            $arrObjRetorno = $objSEIRN->consultarProcedimento($objEntradaProcedimentoAPI);

            if (!is_null($arrObjRetorno)) {
                $arrUnidades = $arrObjRetorno->getUnidadesProcedimentoAberto();

                if (count($arrUnidades) > 0) {
                    foreach ($arrUnidades as $objUnidadeGeralAPI) {

                        $idUnidadeObj = $objUnidadeGeralAPI->getUnidade()->getIdUnidade();

                        if ($idUnidade == $idUnidadeObj) {
                            $isConcluido = false;
                            break;
                        }
                    }
                }
            }
        }

        return $isConcluido;
    }

    public function controlarAtribuicaoGrupo($arrIdsProcedimento)
    {
        if (count($arrIdsProcedimento) > 0) {
            foreach ($arrIdsProcedimento as $idProcedimento) {
                $objAtribuirDTO = new AtribuirDTO();
                $objAtividadeRN = new AtividadeRN();
                $objProtocoloDTO = new ProtocoloDTO();
                $arrObjProtocoloDTO = array();

                $objProtocoloDTO->setDblIdProtocolo($idProcedimento);
                $arrObjProtocoloDTO[] = $objProtocoloDTO;
                $objAtribuirDTO->setNumIdUsuarioAtribuicao(null);
                $objAtribuirDTO->setArrObjProtocoloDTO($arrObjProtocoloDTO);
                $objAtividadeRN->atribuirRN0985($objAtribuirDTO);
            }
        }
    }

    public function controlarAtribuicao($idProcedimento, $idUsuarioAtb)
    {
        $idUsuarioCore = $this->getUltimoUsuarioAtribuidoUnidadeLogada($idProcedimento);

        $objAtribuirDTO = new AtribuirDTO();
        $objAtividadeRN = new AtividadeRN();
        $objProtocoloDTO = new ProtocoloDTO();
        $arrObjProtocoloDTO = array();

        if ($idUsuarioAtb == $idUsuarioCore) {
            $objProtocoloDTO->setDblIdProtocolo($idProcedimento);
            $arrObjProtocoloDTO[] = $objProtocoloDTO;
            $objAtribuirDTO->setNumIdUsuarioAtribuicao(null);
            $objAtribuirDTO->setArrObjProtocoloDTO($arrObjProtocoloDTO);
            $objAtividadeRN->atribuirRN0985($objAtribuirDTO);
        }
    }

    public function validarPrazoJustificativa($tipoSolicitacao, $prazoDias, $idControleDesemp)
    {
        $isValido = true;

        if ($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_DILACAO) {
            $objTriagemRN = new MdUtlTriagemRN();
            $isValido = $objTriagemRN->validaPrazoMaximoDiasJustificativa(array($prazoDias, $idControleDesemp));
        }

        if ($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_INTERRUPCAO || $tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_SUSPENSAO) {
            $objPrmGrRN = new MdUtlAdmPrmGrRN();
            $isValido = $objPrmGrRN->validaPrazoMaximoDiasJustificativa(array($prazoDias, $tipoSolicitacao));
        }

        return $isValido;
    }

    public function retornaDadosIconesProcesso($arrIdProcedimento, $nomeTpCtrl = null)
    {

        $objControleDsmpRN = new MdUtlControleDsmpRN();
        $arrRetorno = array();

        if (is_null($nomeTpCtrl)) {
            $objTpCtrlUtlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
            $objTpControleDTO = $objTpCtrlUtlUndRN->getObjTipoControleUnidadeLogada();
            if (!is_null($objTpControleDTO)) {
                $nomeTpCtrl = 'Controle de Desempenho - ' . $objTpControleDTO->getStrNomeTipoControle() . ': ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual();
            }
        }

        if (!is_null($nomeTpCtrl)) {
            $arrStatus = MdUtlControleDsmpINT::retornaArrSituacoesControleDsmpCompleto();

            if (is_array($arrIdProcedimento)) {
                $arrObjControleDsmpDTO = $objControleDsmpRN->getObjsControleDsmpAtivoAjustePrazo($arrIdProcedimento);

                if (count($arrObjControleDsmpDTO) > 0) {
                    foreach ($arrObjControleDsmpDTO as $objControleDsmpDTO) {
                        $arrDados = $this->_verificaStatusPreencheArr($objControleDsmpDTO, $arrStatus);

                        if (count($arrDados) > 0) {
                            $arr = array();
                            #$link = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_controle_dsmp_detalhar&acao_origem=procedimento_controlar&acao_retorno=procedimento_controlar&id_procedimento=' . $objControleDsmpDTO->getDblIdProcedimento());
                            $link = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=procedimento_controlar&acao_retorno=procedimento_controlar&id_procedimento='.$objControleDsmpDTO->getDblIdProcedimento());

                            $arr['img'] = '<a href="' . $link . '" ' . PaginaSEI::montarTitleTooltip($arrDados['TOOLTIP'], $nomeTpCtrl) . '><img src="' . $arrDados['IMG'] . '" class="imagemStatus" /></a>';

                            $arrRetorno[$objControleDsmpDTO->getDblIdProcedimento()] = $arr;
                        }
                    }
                }

            } else {
                $objControleDsmpDTO = $objControleDsmpRN->getObjsControleDsmpAtivoAjustePrazo($arrIdProcedimento);
                if (!is_null($objControleDsmpDTO)) {
                    $arrRetorno = $this->_verificaStatusPreencheArr($objControleDsmpDTO, $arrStatus);
                    $arrRetorno['TOOLTIP'] = $nomeTpCtrl . '\n' . $arrRetorno['TOOLTIP'];
                }
            }
        }

        return $arrRetorno;
    }

    private function _verificaStatusPreencheArr($objControleDsmpDTO, $arrStatus)
    {

        $strStatus = !is_null($objControleDsmpDTO) ? $objControleDsmpDTO->getStrStaAtendimentoDsmp() : null;
        $strNomeStatus = !is_null($objControleDsmpDTO) ? $arrStatus[$strStatus] : null;
        $strFila = !is_null($objControleDsmpDTO) ? $objControleDsmpDTO->getStrNomeFila() : null;
        $dthStatus = !is_null($objControleDsmpDTO) ? $objControleDsmpDTO->getDthAtual() : null;
        $dthStatus = !is_null($dthStatus) ? explode(" ", $dthStatus) : null;
        $dthDataPrazo = !is_null($objControleDsmpDTO) ? $objControleDsmpDTO->getDthPrazoTarefa() : null;
        $dthDataPrazo = !is_null($dthDataPrazo) ? explode(" ", $dthDataPrazo) : null;
        $dthSuspensoInterrompido = !is_null($objControleDsmpDTO) ? $objControleDsmpDTO->getDthPrazoSolicitacaoAjustePrazo() : null;
        $dthSuspensoInterrompido = !is_null($dthSuspensoInterrompido) ? explode(" ", $dthSuspensoInterrompido) : null;

        $arrRetorno = array();

        $imgAmarela = 'modulos/utilidades/imagens/svg/icone_controle_utl_amarelo.svg';
        $imgVermelha = 'modulos/utilidades/imagens/svg/icone_controle_utl_vermelho.svg';
        $imgAzul = 'modulos/utilidades/imagens/svg/icone_controle_utl_azul.svg';
        $imgRoxo = 'modulos/utilidades/imagens/svg/icone_controle_utl_roxo.svg';
        $imgVerde = 'modulos/utilidades/imagens/svg/icone_controle_utl_verde.svg';

        $strStatus = trim($strStatus);
        switch ($strStatus) {

            case MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM :
                $arrRetorno['IMG'] = $imgAmarela;
                $arrRetorno['TOOLTIP'] = 'Fila: ' . $strFila . '\nStatus: ' . $strNomeStatus . '\nData do Status: ' . $dthStatus[0];
                break;

            case MdUtlControleDsmpRN::$EM_TRIAGEM :
                $arrRetorno['IMG'] = $imgAmarela;
                $arrRetorno['TOOLTIP'] = 'Fila: ' . $strFila . '\nStatus: ' . $strNomeStatus . '\nData do Status: ' . $dthStatus[0];
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_ANALISE :
                $arrRetorno['IMG'] = $imgAzul;
                $arrRetorno['TOOLTIP'] = 'Fila: ' . $strFila . '\nStatus: ' . $strNomeStatus . '\nData do Status: ' . $dthStatus[0];
                break;

            case MdUtlControleDsmpRN::$EM_ANALISE:
            case MdUtlControleDsmpRN::$RASCUNHO_ANALISE:
                $arrRetorno['IMG'] = $imgAzul;
                $arrRetorno['TOOLTIP'] = 'Fila: ' . $strFila . '\nStatus: ' . $strNomeStatus . '\nData do Status: ' . $dthStatus[0];
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_REVISAO :
                $arrRetorno['IMG'] = $imgVerde;
                $arrRetorno['TOOLTIP'] = 'Fila: ' . $strFila . '\nStatus: ' . $strNomeStatus . '\nData do Status: ' . $dthStatus[0];
                break;

            case MdUtlControleDsmpRN::$EM_REVISAO:
                $arrRetorno['IMG'] = $imgVerde;
                $arrRetorno['TOOLTIP'] = 'Fila: ' . $strFila . '\nStatus: ' . $strNomeStatus . '\nData do Status: ' . $dthStatus[0];
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM :
            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE :
                $arrRetorno['IMG'] = $imgVermelha;
                $arrRetorno['TOOLTIP'] = 'Fila: ' . $strFila . '\nStatus: ' . $strNomeStatus . '\nData do Status: ' . $dthStatus[0];
                break;

            case MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM:
            case MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE:
            case MdUtlControleDsmpRN::$RASCUNHO_CORRECAO_ANALISE:
                $arrRetorno['IMG'] = $imgVermelha;
                $arrRetorno['TOOLTIP'] = 'Fila: ' . $strFila . '\nStatus: ' . $strNomeStatus . '\nData do Status: ' . $dthStatus[0];
                break;

            case MdUtlControleDsmpRN::$SUSPENSO:
            case MdUtlControleDsmpRN::$INTERROMPIDO:
                $arrRetorno['IMG'] = $imgRoxo;
                $arrRetorno['TOOLTIP'] = 'Fila: ' . $strFila . '\nStatus: ' . $strNomeStatus . '\nData do Status: ' . $dthStatus[0];
                break;

        }

        return $arrRetorno;
    }

    protected function getIdsUsuariosUnidadeLogadaConectado()
    {
        $idsUsuarioUnidade = array();
        $numIdUnidade = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setNumIdUnidade($numIdUnidade);
        $objUsuarioRN = new UsuarioRN();
        $arrObjUsuarioDTOUnd = $objUsuarioRN->listarPorUnidadeRN0812($objUnidadeDTO);

        if (count($arrObjUsuarioDTOUnd) > 0) {
            $idsUsuarioUnidade = InfraArray::converterArrInfraDTO($arrObjUsuarioDTOUnd, 'IdUsuario');
        }

        return $idsUsuarioUnidade;
    }

    protected function retornaArrAtendimentoMapeadoConectado($arrObjs)
    {
        $idsProcesso = InfraArray::converterArrInfraDTO($arrObjs, 'IdProtocolo');
        $idsUnidade = InfraArray::converterArrInfraDTO($arrObjs, 'IdUnidade');
        $arrMapeado = array();
        $contadorIds = 0;

        if (count($idsUnidade) && count($idsProcesso) > 0) {
            foreach ($arrObjs as $objDTO) {
                $idProtocolo = $objDTO->getDblIdProtocolo();
                $idUnidade = $objDTO->getNumIdUnidade();
                $contadorIds++;
                $arrMapeado[$idProtocolo][$idUnidade] = null;
            }

            $objMdUtlHistoricoRN = new MdUtlHistControleDsmpRN();
            $objMdUtlHistoricoDTO = new MdUtlHistControleDsmpDTO();
            $objMdUtlHistoricoDTO->setDblIdProcedimento($idsProcesso, InfraDTO::$OPER_IN);
            $objMdUtlHistoricoDTO->setNumIdUnidade($idsUnidade, InfraDTO::$OPER_IN);
            $objMdUtlHistoricoDTO->retNumIdAtendimento();
            $objMdUtlHistoricoDTO->retNumIdUnidade();
            $objMdUtlHistoricoDTO->retDblIdProcedimento();
            $objMdUtlHistoricoDTO->setOrdNumIdAtendimento(InfraDTO::$TIPO_ORDENACAO_DESC);

            $arrRetornoDTO = $objMdUtlHistoricoRN->listar($objMdUtlHistoricoDTO);


            foreach ($arrRetornoDTO as $objDTO) {
                $idUnidade = $objDTO->getNumIdUnidade();
                $idProcesso = $objDTO->getDblIdProcedimento();

                if (array_key_exists($idProcesso, $arrMapeado)) {
                    $arrProcesso = $arrMapeado[$idProcesso];

                    if (array_key_exists($idUnidade, $arrProcesso)) {
                        $idAtendimento = $arrMapeado[$idProcesso][$idUnidade];

                        if (is_null($idAtendimento)) {
                            $arrMapeado[$idProcesso][$idUnidade] = $objDTO->getNumIdAtendimento();
                        }
                    }
                }
            }

        }

        return $arrMapeado;
    }

    public function recuperarObjHistorico($idProcedimento, $order = null)
    {
        $objMdUtlHistControleDsmpRn = new MdUtlHistControleDsmpRN();
        $objMdUtlHistControleDsmpDto = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDto->retStrTipoAcao();
        $objMdUtlHistControleDsmpDto->retStrStaAtendimentoDsmp();
        $objMdUtlHistControleDsmpDto->retNumIdMdUtlHistControleDsmp();
        $objMdUtlHistControleDsmpDto->setDblIdProcedimento($idProcedimento);
        if (!is_null($order)) {
            $objMdUtlHistControleDsmpDto->setOrd('IdMdUtlHistControleDsmp', $order);
        } else {
            $objMdUtlHistControleDsmpDto->setOrd('IdMdUtlHistControleDsmp', InfraDTO::$TIPO_ORDENACAO_DESC);
        }

        $objMdUtlHistControleDsmpDto->retTodos();

        return $objMdUtlHistControleDsmpRn->listar($objMdUtlHistControleDsmpDto);
    }

    public function regraAcaoTriagem($objHistControleDesmp, $idProcedimento, $isChefiaImediata)
    {
        $params['dataInicio'] = '';
        $params['dataPrazo'] = '';
        $params['tempoExecucao'] = '';
        $params['tempoExecucaoAtribuido'] = '';

        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->retStrStaAtendimentoDsmp();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
        $objMdUtlControleDsmpDTO->retDthPrazoTarefa();
        $objMdUtlControleDsmpDTO->retStrTipoAcao();
        $objMdUtlControleDsmpDTO->retDblIdProcedimento();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlAdmFila();
        $objMdUtlControleDsmpDTO->retDthAtual();
        $objMdUtlControleDsmpDTO->setDblIdProcedimento($idProcedimento);
        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlControleDsmpDTO->setOrd('IdMdUtlControleDsmp', InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdUtlControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objControleDsmp = $objMdUtlControleDsmpRN->consultar($objMdUtlControleDsmpDTO);

        if (!$objControleDsmp) {
            $objControleDsmp = $objHistControleDesmp[0];
        }

        switch ($objControleDsmp->getStrTipoAcao()) {
            case MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM:
            case MdUtlControleDsmpRN::$STR_TIPO_ACAO_ASSOCIACAO:

                $this->extrairParamsHistTriagem($objHistControleDesmp, $params);
                if( $isChefiaImediata ) {
                    $params['tempoExecucaoAtribuido'] = null;
                    $params['tempoExecucao']          = 0;
                }
                break;

            case MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM:

                $this->extrairParamsHistRetriagem($params, $objHistControleDesmp);
                break;
        }

        return $params;
    }

    public function regraAcaoAnalise($objHistControleDesmp, $objMdUtlAnaliseDTO)
    {
        $params['dataInicio'] = '';
        $params['dataPrazo'] = '';
        $params['tempoExecucao'] = '';
        $params['tempoExecucaoAtribuido'] = '';

        $this->extrairParamsHistAnalise($objHistControleDesmp, $params, $objMdUtlAnaliseDTO);

        return $params;
    }

    public function regraAcaoRevisao($objHistControleDesmp)
    {
        $params['dataInicio'] = '';
        $params['dataPrazo'] = '';
        $params['tempoExecucao'] = '';
        $params['tempoExecucaoAtribuido'] = '';

        $this->extrairParamsHistRevisao($objHistControleDesmp, $params);

        return $params;
    }

    private function extrairParamsHistTriagem($objHistControleDesmp, &$params)
    {
        $dataDistribuicao = '';

        foreach ($objHistControleDesmp as $historico) {
            $acao = $historico->getStrTipoAcao();
            $status = $historico->getStrStaAtendimentoDsmp();

            if ($acao == MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO &&
                in_array($status, array(MdUtlControleDsmpRN::$EM_TRIAGEM, MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM))) {
                $novaData = DateTime::createFromFormat('d/m/Y H:i:s', $historico->getDthAtual());

                if ($novaData > $dataDistribuicao) {
                    $dataDistribuicao = $novaData;

                    $params['dataInicio'] = $historico->getDthAtual();
                    $params['dataPrazo'] = $historico->getDthPrazoTarefa();
                    $params['tempoExecucao'] = $historico->getNumTempoExecucao();
                    $params['tempoExecucaoAtribuido'] = $historico->getNumTempoExecucaoAtribuido();
                }

                break;
            }
        }
    }

    private function extrairParamsHistRetriagem( &$params, $arrHistControleDesmp)
    {
        // busca a ultima distribuição para preenchimento da retriagem
        $arrHistControleDesmpASC = array_reverse($arrHistControleDesmp);
        foreach ($arrHistControleDesmpASC as $objHistControleDesmpASC) {
            if ( $objHistControleDesmpASC->getStrTipoAcao() == MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO) {
                $ultimaDistribuicao = $objHistControleDesmpASC;
            }
        }

        $params['dataInicio'] = $ultimaDistribuicao->getDthAtual();
        $params['dataPrazo'] = current($arrHistControleDesmp)->getDthPrazoTarefa() ? current($arrHistControleDesmp)->getDthPrazoTarefa() : null ;
        $params['tempoExecucao'] = 0;
    }

    private function extrairParamsHistAnalise($objHistControleDesmp, &$params, $objMdUtlAnaliseDTO = null)
    {
        $dataDistribuicao = '';
        $dataRetriagem = '';
        $dataRetornoStatus = '';
        $dataInicio = '';
        $dataPrazo = '';
        $arrTempoExecucao = [];
        $temRetriagem = false;
        $temAprAjuste = false;
        $temInte = false;
        $temSusp = false;

        foreach ($objHistControleDesmp as $key => $historico) {
            $acao = $historico->getStrTipoAcao();
            $status = $historico->getStrStaAtendimentoDsmp();

            if ($acao == MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO &&
	              in_array($status, array(MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE, MdUtlControleDsmpRN::$AGUARDANDO_REVISAO))
            ) {
                if (empty($dataDistribuicao)) {
                    $dataDistribuicao = DateTime::createFromFormat('d/m/Y H:i:s', $historico->getDthAtual());
                    if ($dataDistribuicao > $dataRetornoStatus) {
                        $dataInicio = $historico->getDthAtual();
                        if (!$temRetriagem) {
                            $arrTempoExecucao = $this->buscarAtividadesEntregues($objMdUtlAnaliseDTO->getNumIdMdUtlAnalise());
                        }
                        if (!$temSusp && !$temAprAjuste) {
                            $dataInicio = $historico->getDthAtual();
                            $dataPrazo = $historico->getDthPrazoTarefa();
                        }
                    } else {
                        if ($temSusp) {
                            $dataInicio = $historico->getDthAtual();
                        }
                    }
                } else {
                    $novaData = DateTime::createFromFormat('d/m/Y H:i:s', $historico->getDthAtual());
                    if ($novaData > $dataDistribuicao) {
                        $dataInicio = $historico->getDthAtual();

                        if (!$temRetriagem) {
                            $arrTempoExecucao = $this->buscarAtividadesEntregues($objMdUtlAnaliseDTO->getNumIdMdUtlAnalise());
                        }
                        if (!$temSusp && !$temAprAjuste) {
                            $dataInicio = $historico->getDthAtual();
                            $dataPrazo = $historico->getDthPrazoTarefa();
                        }
                    }
                }
            }

            if ($acao == MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM &&
                in_array($status, array(MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE))) {
                $dataRetriagem = DateTime::createFromFormat('d/m/Y H:i:s', $historico->getDthAtual());

                if ($dataRetriagem > $dataDistribuicao) {
                    $temRetriagem = true;
                    $arrTempoExecucao = $this->buscarAtividadesEntregues($objMdUtlAnaliseDTO->getNumIdMdUtlAnalise());
                }
            }

            if ($acao == MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETORNO_STATUS &&
                in_array($status, array(MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE))) {
                $idProcedimento = $historico->getDblIdProcedimento();
                $idHistorico = $historico->getNumIdMdUtlHistControleDsmp();
                $isAjuste = $this->exiteSolicitacaoAjusteHistorico($idProcedimento, $idHistorico);

                if ($isAjuste['possui_ajuste'] == true) {
                    $dataRetornoStatus = DateTime::createFromFormat('d/m/Y H:i:s', $historico->getDthAtual());
                    if ($dataRetornoStatus > $dataDistribuicao) {
                        if (!$temRetriagem) {
                            $arrTempoExecucao = [
                                'tempoExecucao'          => $historico->getNumTempoExecucao() , 
                                'tempoExecucaoAtribuido' => $historico->getNumTempoExecucaoAtribuido()
                            ];
                        }
                    }

                    if ($isAjuste['possui_interrupcao'] == true) {
                        if (!$temInte) {
                            if ($dataRetornoStatus > $dataDistribuicao) {
                                $temInte = true;
                                $dataInicio = $historico->getDthAtual();
                                $dataPrazo = $historico->getDthPrazoTarefa();
                            }
                        }
                    } elseif ($isAjuste['possui_suspensao'] == true) {
                        if (!$temSusp) {
                            if ($dataRetornoStatus > $dataDistribuicao) {
                                $temSusp = true;
                                $dataPrazo = $historico->getDthPrazoTarefa();
                            }
                        }
                    }
                }
            }

            if ($acao == MdUtlControleDsmpRN::$STR_TIPO_ACAO_APV_AJUSTE_PRAZO) {
                $idProcedimento = $historico->getDblIdProcedimento();
                $idHistorico = $historico->getNumIdMdUtlHistControleDsmp();
                $isAjuste = $this->exiteSolicitacaoAjusteHistorico($idProcedimento, $idHistorico);

                if ($isAjuste['possui_ajuste'] == true) {
                    if ($isAjuste['possui_dilacao'] == true) {
                        $dataAprov = DateTime::createFromFormat('d/m/Y H:i:s', $historico->getDthAtual());
                        if (!$temAprAjuste) {
                            if ($dataAprov > $dataDistribuicao) {
                                if ($dataAprov > $dataRetornoStatus) {
                                    $temAprAjuste = true;
                                    $dataPrazo = $historico->getDthPrazoTarefa();
                                }
                            }
                        }
                    }
                }
            }
        }

        $params['dataInicio']             = $dataInicio;
        $params['dataPrazo']              = $dataPrazo;
        $params['tempoExecucao']          = $arrTempoExecucao['tempoExecucao'];
        $params['tempoExecucaoAtribuido'] = $arrTempoExecucao['tempoExecucaoAtribuido'];
    }

    private function buscarAtividadesEntregues($idAnalise)
    {
        $tempoExecucao          = 0;
        $tempoExecucaoAtribuido = 0;

        $objMdUtlRelAnaliseProdutoRN = new MdUtlRelAnaliseProdutoRN();
        $obMdUtlRelAnaliseProdutoDTO = new MdUtlRelAnaliseProdutoDTO();
        $obMdUtlRelAnaliseProdutoDTO->setNumIdMdUtlAnalise($idAnalise);
        $obMdUtlRelAnaliseProdutoDTO->setDistinct(true);
        $obMdUtlRelAnaliseProdutoDTO->retNumIdMdUtlAdmAtividade();
        $obMdUtlRelAnaliseProdutoDTO->retNumIdMdUtlRelTriagemAtv();
        $obMdUtlRelAnaliseProdutoDTO->retNumTempoExecucao();
        $obMdUtlRelAnaliseProdutoDTO->retNumTempoExecucaoAtribuido();
        $arrMdUtlRelAnaliseProduto = $objMdUtlRelAnaliseProdutoRN->listar($obMdUtlRelAnaliseProdutoDTO);

        foreach ($arrMdUtlRelAnaliseProduto as $MdUtlRelAnaliseProduto) {
            $tempoExecucao +=          $MdUtlRelAnaliseProduto->getNumTempoExecucao();
            $tempoExecucaoAtribuido += $MdUtlRelAnaliseProduto->getNumTempoExecucaoAtribuido();
        }

        return ['tempoExecucao' => $tempoExecucao , 'tempoExecucaoAtribuido' => $tempoExecucaoAtribuido];

    }

    private function extrairParamsHistRevisao($objHistControleDesmp, &$params)
    {
        $dataDistribuicao = '';
        $dataRetriagem = '';
        $dataRetornoStatus = '';
        $dataInicio = '';
        $dataPrazo = '';
        $tempoExecucao = '';
        $tempoExecucaoAtribuido = '';
        $temRetriagem = false;
        $temAprAjuste = false;
        $temInte = false;
        $temSusp = false;

        foreach ($objHistControleDesmp as $key => $historico) {
            $acao = $historico->getStrTipoAcao();
            $status = $historico->getStrStaAtendimentoDsmp();

            if ($acao == MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO &&
                in_array($status, array(MdUtlControleDsmpRN::$EM_REVISAO))) {

                if (empty($dataDistribuicao)) {
                    $dataDistribuicao = DateTime::createFromFormat('d/m/Y H:i:s', $historico->getDthAtual());
                    if ($dataDistribuicao > $dataRetornoStatus) {
                        $dataInicio = $historico->getDthAtual();
                        if (!$temRetriagem) {
                            $tempoExecucao = $historico->getNumTempoExecucao();
                            $tempoExecucaoAtribuido = $historico->getNumTempoExecucaoAtribuido();
                        }
                        if (!$temSusp && !$temAprAjuste) {
                            $dataInicio = $historico->getDthAtual();
                            $dataPrazo = $historico->getDthPrazoTarefa();
                        }
                    } else {
                        if ($temSusp) {
                            $dataInicio = $historico->getDthAtual();
                        }
                    }
                } else {
                    $novaData = DateTime::createFromFormat('d/m/Y H:i:s', $historico->getDthAtual());
                    if ($novaData > $dataDistribuicao) {
                        $dataInicio = $historico->getDthAtual();

                        if (!$temRetriagem) {
                            $tempoExecucao = $historico->getNumTempoExecucao();
                            $tempoExecucaoAtribuido = $historico->getNumTempoExecucaoAtribuido();
                        }
                        if (!$temSusp && !$temAprAjuste) {
                            $dataInicio = $historico->getDthAtual();
                            $dataPrazo = $historico->getDthPrazoTarefa();
                        }
                    }
                }
            }

            if ($acao == MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM &&
                in_array($status, array(MdUtlControleDsmpRN::$EM_ANALISE))) {
                $dataRetriagem = DateTime::createFromFormat('d/m/Y H:i:s', $historico->getDthAtual());

                if ($dataRetriagem > $dataDistribuicao) {
                    $temRetriagem = true;
                    $tempoExecucao = $historico->getNumTempoExecucao();
                    $tempoExecucaoAtribuido = $historico->getNumTempoExecucaoAtribuido();
                }
            }

            if ($acao == MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETORNO_STATUS &&
                in_array($status, array(MdUtlControleDsmpRN::$EM_REVISAO))) {
                $idProcedimento = $historico->getDblIdProcedimento();
                $idHistorico = $historico->getNumIdMdUtlHistControleDsmp();
                $isAjuste = $this->exiteSolicitacaoAjusteHistorico($idProcedimento, $idHistorico);

                if ($isAjuste['possui_ajuste'] == true) {
                    $dataRetornoStatus = DateTime::createFromFormat('d/m/Y H:i:s', $historico->getDthAtual());
                    if ($dataRetornoStatus > $dataDistribuicao) {
                        if (!$temRetriagem) {
                            $tempoExecucao = $historico->getNumTempoExecucao();
                            $tempoExecucaoAtribuido = $historico->getNumTempoExecucaoAtribuido();
                        }
                    }

                    if ($isAjuste['possui_interrupcao'] == true) {
                        if (!$temInte) {
                            if ($dataRetornoStatus > $dataDistribuicao) {
                                $temInte = true;
                                $dataInicio = $historico->getDthAtual();
                                $dataPrazo = $historico->getDthPrazoTarefa();
                            }
                        }
                    } elseif ($isAjuste['possui_suspensao'] == true) {
                        if (!$temSusp) {
                            if ($dataRetornoStatus > $dataDistribuicao) {
                                $temSusp = true;
                                $dataPrazo = $historico->getDthPrazoTarefa();
                            }
                        }
                    }
                }
            }

            if ($acao == MdUtlControleDsmpRN::$STR_TIPO_ACAO_APV_AJUSTE_PRAZO) {
                $idProcedimento = $historico->getDblIdProcedimento();
                $idHistorico = $historico->getNumIdMdUtlHistControleDsmp();
                $isAjuste = $this->exiteSolicitacaoAjusteHistorico($idProcedimento, $idHistorico);

                if ($isAjuste['possui_ajuste'] == true) {
                    if ($isAjuste['possui_dilacao'] == true) {
                        $dataAprov = DateTime::createFromFormat('d/m/Y H:i:s', $historico->getDthAtual());
                        if (!$temAprAjuste) {
                            if ($dataAprov > $dataDistribuicao) {
                                if ($dataAprov > $dataRetornoStatus) {
                                    $temAprAjuste = true;
                                    $dataPrazo = $historico->getDthPrazoTarefa();
                                }
                            }
                        }
                    }
                }
            }
        }

        $params['dataInicio'] = $dataInicio;
        $params['dataPrazo'] = $dataPrazo;
        $params['tempoExecucao'] = $tempoExecucao;
        $params['tempoExecucaoAtribuido'] = $tempoExecucaoAtribuido;

    }

    public function validarTriagem($controleDsmp, &$params, $idHistorico)
    {
        $idProcedimento = $controleDsmp->getDblIdProcedimento();
        $status = $controleDsmp->getStrStaAtendimentoDsmp();

        if ($status == MdUtlControleDsmpRN::$AGUARDANDO_ANALISE ||
            $status == MdUtlControleDsmpRN::$AGUARDANDO_REVISAO ||
            $status == MdUtlControleDsmpRN::$FLUXO_FINALIZADO) {
            $arrObjControleDsmp = $this->recuperarHistAnterior(
                $idProcedimento,
                $idHistorico,
                MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO,
                array(MdUtlControleDsmpRN::$EM_TRIAGEM, MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM)
            );

            if ($arrObjControleDsmp && count($arrObjControleDsmp) > 0) {
                $params['dataInicio'] = $arrObjControleDsmp[0]->getDthAtual();
                $params['dataPrazo'] = $arrObjControleDsmp[0]->getDthPrazoTarefa();
                $params['tempoExecucao'] = $arrObjControleDsmp[0]->getNumTempoExecucao();
            }
        }
    }

    public function validarAnalise($controleDsmp, &$params, $idHistorico)
    {
        $idProcedimento = $controleDsmp->getDblIdProcedimento();
        $status = $controleDsmp->getStrStaAtendimentoDsmp();

        if ($status == MdUtlControleDsmpRN::$AGUARDANDO_REVISAO ||
            $status == MdUtlControleDsmpRN::$FLUXO_FINALIZADO) {
            $ultimaDistribuicao = $this->recuperarHistAnterior(
                $idProcedimento,
                $idHistorico,
                MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO,
                array(MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE)
            );
            $dataUltDist = DateTime::createFromFormat('d/m/Y H:i:s', $ultimaDistribuicao[0]->getDthAtual());

            $ultimaRetriagem = $this->recuperarHistAnterior(
                $idProcedimento,
                $idHistorico,
                MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM,
                array(MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE)
            );

            $params['tempoExecucao'] = $ultimaDistribuicao[0]->getNumTempoExecucao();
            if ($ultimaRetriagem && count($ultimaRetriagem) > 0) {
                $dataRetiagem = DateTime::createFromFormat('d/m/Y H:i:s', $ultimaRetriagem[0]->getDthAtual());
                if (($dataRetiagem > $dataUltDist)) {
                    $params['tempoExecucao'] = $ultimaRetriagem[0]->getNumTempoExecucao();
                }
            }

            $isAjuste = $this->exiteSolicitacaoAjusteHistorico($idProcedimento, $idHistorico);

            if ($isAjuste['possui_ajuste'] == true) {
                $retornoStatus = $this->recuperarHistAnterior(
                    $idProcedimento,
                    $idHistorico,
                    MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETORNO_STATUS,
                    array(MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE)
                );

                $params['dataInicio'] = $ultimaDistribuicao[0]->getDthAtual();

                if ($isAjuste['possui_interrupcao'] == true) {
                    if ($retornoStatus && count($retornoStatus) > 0) {
                        $dataStatus = DateTime::createFromFormat('d/m/Y H:i:s', $retornoStatus[0]->getDthAtual());

                        if ($dataStatus > $dataUltDist) {
                            $params['dataInicio'] = $retornoStatus[0]->getDthAtual();
                            $params['dataPrazo'] = $retornoStatus[0]->getDthPrazoTarefa();
                        } else {
                            $params['dataInicio'] = $ultimaDistribuicao[0]->getDthAtual();
                            $params['dataPrazo'] = $ultimaDistribuicao[0]->getDthPrazoTarefa();
                        }
                    } else {
                        $params['dataPrazo'] = $ultimaDistribuicao[0]->getDthPrazoTarefa();
                    }
                } elseif ($isAjuste['possui_suspensao'] == true) {
                    if ($retornoStatus && count($retornoStatus) > 0) {
                        $dataStatus = DateTime::createFromFormat('d/m/Y H:i:s', $retornoStatus[0]->getDthAtual());

                        if ($dataStatus > $dataUltDist) {
                            $params['dataPrazo'] = $retornoStatus[0]->getDthPrazoTarefa();
                        } else {
                            $params['dataPrazo'] = $ultimaDistribuicao[0]->getDthPrazoTarefa();
                        }
                    } else {
                        $params['dataPrazo'] = $ultimaDistribuicao[0]->getDthPrazoTarefa();
                    }
                } elseif ($isAjuste['possui_dilacao'] == true) {
                    $params['dataInicio'] = $ultimaDistribuicao[0]->getDthAtual();

                    $aprovAjuste = $this->recuperarHistAnterior(
                        $idProcedimento,
                        $idHistorico,
                        MdUtlControleDsmpRN::$STR_TIPO_ACAO_APV_AJUSTE_PRAZO,
                        array(MdUtlControleDsmpRN::$EM_ANALISE)
                    );

                    if ($aprovAjuste && count($aprovAjuste) > 0) {
                        $dataAprov = DateTime::createFromFormat('d/m/Y H:i:s', $aprovAjuste[0]->getDthAtual());

                        if ($dataAprov > $dataUltDist) {
                            $params['dataPrazo'] = $aprovAjuste[0]->getDthPrazoTarefa();
                        } else {
                            $params['dataPrazo'] = $ultimaDistribuicao[0]->getDthPrazoTarefa();
                        }
                    } else {
                        $params['dataPrazo'] = $ultimaDistribuicao[0]->getDthPrazoTarefa();
                    }
                } else {
                    $params['dataInicio'] = $ultimaDistribuicao[0]->getDthAtual();
                    $params['dataPrazo'] = $ultimaDistribuicao[0]->getDthPrazoTarefa();
                }
            } else {
                $params['dataInicio'] = $ultimaDistribuicao[0]->getDthAtual();
                $params['dataPrazo'] = $ultimaDistribuicao[0]->getDthPrazoTarefa();
            }
        }

    }

    public function validarRevisao($controleDsmp, &$params, $idHistorico)
    {
        $idProcedimento = $controleDsmp->getDblIdProcedimento();
        $status = $controleDsmp->getStrStaAtendimentoDsmp();

        if ($status == MdUtlControleDsmpRN::$FLUXO_FINALIZADO ||
            $status == MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE ||
            $status == MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM ||
            $status == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE ||
            $status == MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM) {

            $ultimaDistribuicao = $this->recuperarHistAnterior(
                $idProcedimento,
                $idHistorico,
                MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO,
                array(MdUtlControleDsmpRN::$EM_REVISAO)
            );
            $dataUltDist = DateTime::createFromFormat('d/m/Y H:i:s', $ultimaDistribuicao[0]->getDthAtual());

            $ultimaRetriagem = $this->recuperarHistAnterior(
                $idProcedimento,
                $idHistorico,
                MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM,
                array(MdUtlControleDsmpRN::$EM_ANALISE)
            );

            $params['tempoExecucao'] = $ultimaDistribuicao[0]->getNumTempoExecucao();
            if ($ultimaRetriagem && count($ultimaRetriagem) > 0) {
                $dataRetiagem = DateTime::createFromFormat('d/m/Y H:i:s', $ultimaRetriagem[0]->getDthAtual());
                if (($dataRetiagem > $dataUltDist)) {
                    $params['tempoExecucao'] = $ultimaRetriagem[0]->getNumTempoExecucao();
                }
            }

            $isAjuste = $this->exiteSolicitacaoAjusteHistorico($idProcedimento, $idHistorico);

            if ($isAjuste['possui_ajuste'] == true) {
                $retornoStatus = $this->recuperarHistAnterior(
                    $idProcedimento,
                    $idHistorico,
                    MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETORNO_STATUS,
                    array(MdUtlControleDsmpRN::$EM_REVISAO)
                );

                $params['dataInicio'] = $ultimaDistribuicao[0]->getDthAtual();

                if ($isAjuste['possui_interrupcao'] == true) {
                    if ($retornoStatus && count($retornoStatus) > 0) {
                        $dataStatus = DateTime::createFromFormat('d/m/Y H:i:s', $retornoStatus[0]->getDthAtual());

                        if ($dataStatus > $dataUltDist) {
                            $params['dataInicio'] = $retornoStatus[0]->getDthAtual();
                            $params['dataPrazo'] = $retornoStatus[0]->getDthPrazoTarefa();
                        } else {
                            $params['dataInicio'] = $ultimaDistribuicao[0]->getDthAtual();
                            $params['dataPrazo'] = $ultimaDistribuicao[0]->getDthPrazoTarefa();
                        }
                    }
                } elseif ($isAjuste['possui_suspensao'] == true) {
                    if ($retornoStatus && count($retornoStatus) > 0) {
                        $dataStatus = DateTime::createFromFormat('d/m/Y H:i:s', $retornoStatus[0]->getDthAtual());

                        if ($dataStatus > $dataUltDist) {
                            $params['dataPrazo'] = $retornoStatus[0]->getDthPrazoTarefa();
                        } else {
                            $params['dataPrazo'] = $ultimaDistribuicao[0]->getDthPrazoTarefa();
                        }
                    } else {
                        $params['dataPrazo'] = $ultimaDistribuicao[0]->getDthPrazoTarefa();
                    }
                } elseif ($isAjuste['possui_dilacao'] == true) {
                    $params['dataInicio'] = $ultimaDistribuicao[0]->getDthAtual();

                    $aprovAjuste = $this->recuperarHistAnterior(
                        $idProcedimento,
                        $idHistorico,
                        MdUtlControleDsmpRN::$STR_TIPO_ACAO_APV_AJUSTE_PRAZO,
                        array(MdUtlControleDsmpRN::$EM_REVISAO)
                    );

                    if ($aprovAjuste && count($aprovAjuste) > 0) {
                        $dataAprov = DateTime::createFromFormat('d/m/Y H:i:s', $aprovAjuste[0]->getDthAtual());

                        if ($dataAprov > $dataUltDist) {
                            $params['dataPrazo'] = $aprovAjuste[0]->getDthPrazoTarefa();
                        } else {
                            $params['dataPrazo'] = $ultimaDistribuicao[0]->getDthPrazoTarefa();
                        }
                    } else {
                        $params['dataPrazo'] = $ultimaDistribuicao[0]->getDthPrazoTarefa();
                    }
                } else {
                    $params['dataInicio'] = $ultimaDistribuicao[0]->getDthAtual();
                    $params['dataPrazo'] = $ultimaDistribuicao[0]->getDthPrazoTarefa();
                }
            } else {
                $params['dataInicio'] = $ultimaDistribuicao[0]->getDthAtual();
                $params['dataPrazo'] = $ultimaDistribuicao[0]->getDthPrazoTarefa();
            }
        }
    }

    public function calcularDia($sinDiaUtil, $qtdeDia, $strDataInicio = null)
    {
        $dataCalculada = null;

        if (is_null($strDataInicio)) {
            $strDataInicio = InfraData::getStrDataAtual();
        }

        if ($sinDiaUtil == 'S') {
            //busca feriados ate 1 ano a frente do periodo corrido solicitado
            $strDataFinal = InfraData::calcularData(($qtdeDia + 365), InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $strDataInicio);

            $objFeriadoDTO = new FeriadoDTO();
            $objFeriadoDTO->setNumIdOrgao(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());
            $objFeriadoDTO->setDtaInicial($strDataInicio);
            $objFeriadoDTO->setDtaFinal($strDataFinal);

            $objPublicacaoRN = new PublicacaoRN();
            $arrFeriados     = InfraArray::simplificarArr($objPublicacaoRN->listarFeriados($objFeriadoDTO), 'Data');
            $count           = 0;
            $dataCalculada   = $strDataInicio;

            while ($count < $qtdeDia) {
                $dataCalculada = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $dataCalculada);

                if (InfraData::obterDescricaoDiaSemana($dataCalculada) != 'sábado' &&
                    InfraData::obterDescricaoDiaSemana($dataCalculada) != 'domingo' &&
                    !in_array($dataCalculada, $arrFeriados)) {
                    $count++;
                }
            }

        } else {
            $dataCalculada = InfraData::calcularData($qtdeDia, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $strDataInicio);

        }

        return $dataCalculada;

    }

    public static function atribuirProximoPrioridade()
    {
        $objFilaRN = new MdUtlAdmFilaRN();
        $mdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $mdUtlAdmFilaPrmGrUsuRN = new MdUtlAdmFilaPrmGrUsuRN();
        $mdUtlAdmTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
        $mdUtlAdmPrmDsRN = new MdUtlAdmPrmDsRN();
        $mdUtlRegrasGeraisRN = new MdUtlRegrasGeraisRN();
        $MdUtlAdmPrmGrUsuINT = new MdUtlAdmPrmGrUsuINT;

        try {
            $idTipoControle = $_POST['idTpCtrl'];
            
            //pega as filas que usuário logado é membro
            $arrObjsFilaDTO = $objFilaRN->getFilasTipoControle($idTipoControle);
            $idsFilasPermitidas = InfraArray::converterArrInfraDTO($arrObjsFilaDTO, 'IdMdUtlAdmFila');

            //pega os papeis do usuário pelas filas em que ele faz parte
            $arrObjsFilaUsuDTO = $mdUtlAdmFilaPrmGrUsuRN->getPapeisDeUsuario($idsFilasPermitidas);

            if (is_null( $arrObjsFilaUsuDTO )) {
                throw new Exception("Usuário não está cadastrado nas Filas.");
            }

            $objDTO = $mdUtlControleDsmpRN->getObjDTOParametrizadoDistrib(array($arrObjsFilaUsuDTO, false, $idTipoControle, array()));

            if (is_null( $objDTO )) {
                throw new Exception(MdUtlMensagemINT::$MSG_UTL_132);
            }

            $objDTO->setOrdDthAtual(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objDTO->setNumIdUsuarioDistribuicao( null , InfraDTO::$OPER_IGUAL );
            $objDTO->setNumIdUnidade( SessaoSEI::getInstance()->getNumIdUnidadeAtual() );
            $objDTO->retNumIdMdUtlControleDsmp();
            $objDTO->retNumIdUnidade();
            $objDTO->retNumIdTipoProcedimento();
            $objDTO->retStrStaAtendimentoDsmp();
            $objDTO->retStrSiglaUnidade();
            $objDTO->retStrProtocoloProcedimentoFormatado();
            $objDTO->retStrNomeFila();
            $objDTO->retNumIdFila();
            $objDTO->retDthAtual();
            $objDTO->retNumIdMdUtlTriagem();

            $objMdUtlAdmPrmDsDTO = $mdUtlAdmPrmDsRN->getPrioridades($idTipoControle);
            $idMdUtlAdmPrmDs = $objMdUtlAdmPrmDsDTO->getNumIdMdUtlAdmPrmDs();
            $diasUteis = $objMdUtlAdmPrmDsDTO->getNumQtdDiasUteis();

            $arrPrioridades = $mdUtlRegrasGeraisRN->montarArrPrioridadeGeral($objMdUtlAdmPrmDsDTO);
            $arrPrioridadesFinal = $mdUtlRegrasGeraisRN->montarArrPrioridadeFinal($arrPrioridades, $idMdUtlAdmPrmDs, $diasUteis);

            $objMdUtlControleDsmpDTO = $mdUtlControleDsmpRN->listarProcessos($objDTO);

            $arrFim = array();
            $arrSemPrioridade = array();
            $existePrazoIgual = false;
            $arrPriPrazo = array();

            foreach ($objMdUtlControleDsmpDTO as $idItem => $item) {
                $idProcedimento = $item->getDblIdProcedimento();
                $permiteDistribuirParaMim = $MdUtlAdmPrmGrUsuINT->verificaPermissaoDistribuirParaMim($idProcedimento);

                if (!empty($arrPrioridades) && $permiteDistribuirParaMim) {
                    foreach ($arrPrioridadesFinal as $keyPri => $prioridade) {
                        if ($keyPri == 'FilaPrioridade') {
                            foreach ($prioridade as $key1 => $prior) {
                                if ($item->getNumIdFila() == $prior['id']) {
                                    $arrFim['FilaPrioridade'][$prior['prioridade']][] = $idProcedimento;
                                }
                            }
                        }
                        if ($keyPri == 'StatusPrioridade') {
                            foreach ($prioridade as $key1 => $prior) {
                                if ($item->getStrStaAtendimentoDsmp() == $prior['id']) {
                                    $arrFim['StatusPrioridade'][$prior['prioridade']][] = $idProcedimento;
                                }
                            }
                        }
                        if ($keyPri == 'AtividadePrioridade') {
                            $mdUtlTriagemRN = new MdUtlTriagemRN();
                            foreach ($prioridade as $key1 => $prior) {
                                $idTriagem = $item->getNumIdMdUtlTriagem();

                                if (!is_null($idTriagem)) {
                                    $arrAtiv = $mdUtlTriagemRN->getIdsAtividadesTriagem($idTriagem);

                                    if (in_array($prior['id'], $arrAtiv)) {
                                        $arrFim['AtividadePrioridade'][$prior['prioridade']][] = $idProcedimento;
                                    }
                                }

                            }
                        }
                        if ($keyPri == 'TipoProcessoPrioridade') {
                            foreach ($prioridade as $key1 => $prior) {
                                if ($item->getNumIdTipoProcedimento() == $prior['id']) {
                                    $arrFim['TipoProcessoPrioridade'][$prior['prioridade']][] = $idProcedimento;
                                }
                            }
                        }
                        if ($keyPri == 'DiasUteisPrioridade') {
                            $dataItem = DateTime::createFromFormat('d/m/Y H:i:s', $item->getDthAtual());
                            $dataItemFmt = $dataItem->format('d/m/Y');
                            $dataCalculada = $mdUtlRegrasGeraisRN->calcularDia('S',$diasUteis,$dataItemFmt);
                            $dataItemAlt = DateTime::createFromFormat('d/m/Y', $dataCalculada);
                            $dataAtual = DateTime::createFromFormat('d/m/Y', InfraData::getStrDataAtual());

                            if ($dataItemAlt <= $dataAtual) {
                                $arrFim['DiasUteisPrioridade'][$prior['prioridade']][] = $idProcedimento;
                            }
                        }
                        if ($keyPri == 'DistribuicaoPrioridade') {
                            $mdUtlTriagemRN = new MdUtlTriagemRN();
                            $idTriagem = $item->getNumIdMdUtlTriagem();

                            if (!is_null($idTriagem)) {

                                $objTriagemDTO = $mdUtlTriagemRN->buscarObjTriagemPorId($idTriagem);
                                $dthPrazo = $objTriagemDTO->getDthPrazoResposta();

                                if (!is_null($dthPrazo)) {
                                    $dthPrazo = $dthPrazo;

                                    $dataFmt = DateTime::createFromFormat('d/m/Y H:i:s', $dthPrazo);

                                    if (key_exists($dataFmt->format('Y/m/d'), $arrPriPrazo)) {
                                        $existePrazoIgual = true;
                                    }
                                    $arrPriPrazo[$dataFmt->format('Y/m/d')][] = $idProcedimento;
                                    ksort($arrPriPrazo);

                                    $arrFim['DistribuicaoPrioridade'] = array_values($arrPriPrazo);
                                }
                            }

                        }

                    }
                    if (empty($arrFim)) {
                        $arrSemPrioridade[] = $idProcedimento;
                    }
                } else {
                    if($permiteDistribuirParaMim){
                        $arrSemPrioridade[] = $idProcedimento;
                    }
                }
            }

            $map = array_map("count", $arrFim);

            $agIntersect = array();

            if (!empty($arrFim)) {
                $mdUtlRegrasGeraisRN->analisaPilha($arrPrioridades, $arrFim, $agIntersect, $existePrazoIgual);
            } else {
                $atribuir = $arrSemPrioridade;
            }

            if (empty($atribuir)) {
                $atribuir = $agIntersect;
            }

            if (count($atribuir)) {
                // aguarda alguns segundos para tentar evitar distribuicao do mesmo processo para n usuarios
                sleep(rand(1,6));
                foreach ($atribuir as $k => $v) {
                    $rs = $mdUtlRegrasGeraisRN->montarParaAtribuir($v, $idTipoControle);
                    if( $rs ){
                        $priorizado = $rs;
                        break;
                    }
                }
            }

            if (isset($priorizado)) {
                $objProtocolo = $mdUtlControleDsmpRN->atribuirPrioridadeUsuarioLogado($priorizado);

                $objProtocoloRN = new ProtocoloRN();
                $objProtocoloDTO = new ProtocoloDTO();
                $objProtocoloDTO->setDblIdProtocolo($objProtocolo->getDblIdProtocolo());
                $objProtocoloDTO->retTodos();
                $arrObjProtocolo = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

                $xml = '<Documento>';
                $xml .= '<IdProtocolo>' . $arrObjProtocolo->getDblIdProtocolo() . '</IdProtocolo>';
                $xml .= '<ProtocoloFormatado>' . $arrObjProtocolo->getStrProtocoloFormatado() . '</ProtocoloFormatado>';
                $xml .= '</Documento>';

                return $xml;
            } else {
                return array('msg' => 'Nenhum processo encontrado para atribuição', 'erro' => true);
            }
        } catch (Exception $e) {
            return array('msg' => $e->getMessage(), 'erro' => true);
        }
    }

    public function analisaPilha($arrPrioridades, $arrFim, &$agIntersect, $existePrazoIgual)
    {
        $map = array_map("count", $arrFim);
        foreach ($arrPrioridades as $prioridade => $value) {
            if (!empty($arrFim[$prioridade])) {
                ksort($arrFim[$prioridade]);
                if (key_exists($prioridade, $map)) {
                    foreach ($arrFim[$prioridade] as $key => $item) {
                        if ($prioridade == 'DistribuicaoPrioridade' && !$existePrazoIgual) {
                            $novoArr[] = array_shift($item);
                            if (count($agIntersect) == 0) {
                                $agIntersect = $novoArr;
                            } else {
                                $intersection = array_intersect($agIntersect, $novoArr);
                                if (empty($agIntersection)) {
                                    continue;
                                } else {
                                    $agIntersect = $intersection;
                                }
                            }
                        } else {
                            if (!is_array($item)) {
                                $item = array($item);
                            }
                            $intersection = array_intersect($item, $agIntersect);
                            if (empty($intersection) && $value == 1) {
                                $agIntersect = $item;
                                break;
                            } else if (empty($intersection) && $value > 1) {
                                if (count($agIntersect) == 0) {
                                    $agIntersect = $item;
                                } else {
                                    continue;
                                }
                            } else if (count($intersection) > 1) {
                                $agIntersect = $intersection;
                            } else if (count($intersection) == 1) {
                                $agIntersect = $intersection;
                            }

                            if (count($agIntersect) == 0) {
                                continue;
                            } else if (count($agIntersect) > 1) {
                                break;
                            } else if (count($agIntersect) == 1) {
                                break;
                            }
                        }
                    }
                }

                if (count($agIntersect) == 1) {
                    break;
                }
            }
        }
    }

    public function montarParaAtribuir($idProcedimento, $idTipoControle)
    {
        $objProcedimentoRN = new ProcedimentoRN();
        $objProcedimentoDto = new MdUtlProcedimentoDTO();
        $objProcedimentoDto->setDblIdProcedimento($idProcedimento);
        $objProcedimentoDto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objProcedimentoDto->retNumIdMdUtlControleDsmp();
        $objProcedimentoDto->retStrStaAtendimentoDsmp();
        $objProcedimentoDto->retNumIdFila();
        $objProcedimentoDto->retNumIdUsuarioDistribuicao();

        $objDTO = $objProcedimentoRN->listarCompleto($objProcedimentoDto);
        
        if( !is_null( $objDTO[0]->getNumIdUsuarioDistribuicao() ) ){
            return false;
        }

        $atribuir['id_procedimento'] = $objDTO[0]->getDblIdProcedimento();
        $atribuir['id_controle_dsmp'] = $objDTO[0]->getNumIdMdUtlControleDsmp();
        $atribuir['status'] = $objDTO[0]->getStrStaAtendimentoDsmp();
        $atribuir['id_fila'] = $objDTO[0]->getNumIdFila();
        $atribuir['id_tp_ctrl'] = $idTipoControle;

        return $atribuir;
    }

    private function montarArrPrioridadeGeral($objMdUtlAdmPrmDsDTO)
    {
        $arrPrioridades = array();
        if ($objMdUtlAdmPrmDsDTO->getStrSinPriorizarDistribuicao() == 'S') {
            $arrPrioridades['DistribuicaoPrioridade'] = $objMdUtlAdmPrmDsDTO->getNumDistribuicaoPrioridade();
        }
        if ($objMdUtlAdmPrmDsDTO->getStrSinFila() == 'S') {
            $arrPrioridades['FilaPrioridade'] = $objMdUtlAdmPrmDsDTO->getNumFilaPrioridade();
        }
        if ($objMdUtlAdmPrmDsDTO->getStrSinStatusAtendimentoDsmp() == 'S') {
            $arrPrioridades['StatusPrioridade'] = $objMdUtlAdmPrmDsDTO->getNumStatusPrioridade();
        }
        if ($objMdUtlAdmPrmDsDTO->getStrSinAtividade() == 'S') {
            $arrPrioridades['AtividadePrioridade'] = $objMdUtlAdmPrmDsDTO->getNumAtividadePrioridade();
        }
        if ($objMdUtlAdmPrmDsDTO->getStrSinTipoProcesso() == 'S') {
            $arrPrioridades['TipoProcessoPrioridade'] = $objMdUtlAdmPrmDsDTO->getNumTipoProcessoPrioridade();
        }
        if ($objMdUtlAdmPrmDsDTO->getStrSinDiasUteis() == 'S') {
            $arrPrioridades['DiasUteisPrioridade'] = $objMdUtlAdmPrmDsDTO->getNumDiasUteisPrioridade();
        }

        asort($arrPrioridades);

        return $arrPrioridades;
    }

    private function montarArrPrioridadeFinal($arrPrioridades, $idMdUtlAdmPrmDs, $diasUteis)
    {
        $arrPrioridadeFinal = array();
        foreach ($arrPrioridades as $key => $prioridade) {
            if ($key == 'DistribuicaoPrioridade') {
                $arrPrioridadeFinal['DistribuicaoPrioridade'][] = true;
            }
            if ($key == 'DiasUteisPrioridade') {
                $arrPrioridadeFinal['DiasUteisPrioridade'][] = $diasUteis;
            }
            if ($key == 'FilaPrioridade') {
                $objMdUtlAdmRelPrmDsFilaRN = new MdUtlAdmRelPrmDsFilaRN();
                $arrFila = $objMdUtlAdmRelPrmDsFilaRN->montarArrFilaPrioridade($idMdUtlAdmPrmDs);
                $arrPrioridadeFinal['FilaPrioridade'] = $arrFila;
            }
            if ($key == 'StatusPrioridade') {
                $objMdUtlAdmRelPrmDsAtenRN = new MdUtlAdmRelPrmDsAtenRN();
                $arrStatus = $objMdUtlAdmRelPrmDsAtenRN->montarArrStatusPrioridade($idMdUtlAdmPrmDs);
                $arrPrioridadeFinal['StatusPrioridade'] = $arrStatus;
            }
            if ($key == 'AtividadePrioridade') {
                $objMdUtlAdmRelPrmDsAtivRN = new MdUtlAdmRelPrmDsAtivRN();
                $arrAtividade = $objMdUtlAdmRelPrmDsAtivRN->montarArrAtiviPrioridade($idMdUtlAdmPrmDs);
                $arrPrioridadeFinal['AtividadePrioridade'] = $arrAtividade;
            }
            if ($key == 'TipoProcessoPrioridade') {
                $objMdUtlAdmRelPrmDsAtivRN = new MdUtlAdmRelPrmDsProcRN();
                $arrTipoProcesso = $objMdUtlAdmRelPrmDsAtivRN->montarArrTipoProcessoPrioridade($idMdUtlAdmPrmDs);
                $arrPrioridadeFinal['TipoProcessoPrioridade'] = $arrTipoProcesso;
            }
        }

        return $arrPrioridadeFinal;
    }

    public function migracaoHistoricoDsmp($objHistControleDesmp)
    {
        foreach ($objHistControleDesmp as $historico) {
            $params['dataInicio'] = '';
            $params['dataPrazo'] = '';
            $params['tempoExecucao'] = '';
            $idHistorico = $historico->getNumIdMdUtlHistControleDsmp();

            switch ($historico->getStrTipoAcao()) {
                case MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM:

                    $this->validarTriagem($historico, $params, $idHistorico);

                    $mdUtlTriagemRN = new MdUtlTriagemRN();
                    $objMdUtlTriagemDTO = new MdUtlTriagemDTO();
                    $objMdUtlTriagemDTO->setBolExclusaoLogica(false);
                    $objMdUtlTriagemDTO->setNumIdMdUtlTriagem($historico->getNumIdMdUtlTriagem());

                    $objMdUtlTriagemDTO->retNumIdMdUtlTriagem();
	                  $objMdUtlTriagemDTO->retDthInicio();
	                  $objMdUtlTriagemDTO->retDthPrazo();
	                  $objMdUtlTriagemDTO->retNumTempoExecucao();
                    #$objMdUtlTriagemDTO->retTodos();

                    $objMdUtlTriagemDTO = $mdUtlTriagemRN->consultar($objMdUtlTriagemDTO);

                    $objMdUtlTriagemDTO->setDthInicio($params['dataInicio'] ? $params['dataInicio'] : '');
                    $objMdUtlTriagemDTO->setDthPrazo($params['dataPrazo'] ? $params['dataPrazo'] : '');
                    $objMdUtlTriagemDTO->setNumTempoExecucao(isset($params['tempoExecucao']) ? $params['tempoExecucao'] : '');

                    $retorno = $mdUtlTriagemRN->alterar($objMdUtlTriagemDTO);

                    break;

                case MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE:

                    $this->validarAnalise($historico, $params, $idHistorico);

                    $mdUtlAnaliseRN = new MdUtlAnaliseRN();
                    $objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();
                    $objMdUtlAnaliseDTO->setBolExclusaoLogica(false);
                    $objMdUtlAnaliseDTO->setNumIdMdUtlAnalise($historico->getNumIdMdUtlAnalise());

                    $objMdUtlAnaliseDTO->retNumIdMdUtlAnalise();
		                $objMdUtlAnaliseDTO->retDthInicio();
		                $objMdUtlAnaliseDTO->retDthPrazo();
		                $objMdUtlAnaliseDTO->retNumTempoExecucao();
	                  #$objMdUtlAnaliseDTO->retTodos();

                    $objMdUtlAnaliseDTO = $mdUtlAnaliseRN->consultar($objMdUtlAnaliseDTO);

                    $objMdUtlAnaliseDTO->setDthInicio($params['dataInicio'] ? $params['dataInicio'] : '');
                    $objMdUtlAnaliseDTO->setDthPrazo($params['dataPrazo'] ? $params['dataPrazo'] : '');
                    $objMdUtlAnaliseDTO->setNumTempoExecucao(isset($params['tempoExecucao']) ? $params['tempoExecucao'] : '');

                    $retorno = $mdUtlAnaliseRN->alterar($objMdUtlAnaliseDTO);

                    break;

                case MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO:

                    $this->validarRevisao($historico, $params, $idHistorico);

                    $mdUtlRevisaoRN = new MdUtlRevisaoRN();
                    $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
                    $objMdUtlRevisaoDTO->setBolExclusaoLogica(false);
                    $objMdUtlRevisaoDTO->setNumIdMdUtlRevisao($historico->getNumIdMdUtlRevisao());

	                  $objMdUtlRevisaoDTO->retNumIdMdUtlRevisao();
	                  $objMdUtlRevisaoDTO->retDthInicio();
	                  $objMdUtlRevisaoDTO->retDthPrazo();
		                $objMdUtlRevisaoDTO->retNumTempoExecucao();
                    #$objMdUtlRevisaoDTO->retTodos();

                    $objMdUtlRevisaoDTO = $mdUtlRevisaoRN->consultar($objMdUtlRevisaoDTO);

                    $objMdUtlRevisaoDTO->setDthInicio($params['dataInicio'] ? $params['dataInicio'] : '');
                    $objMdUtlRevisaoDTO->setDthPrazo($params['dataPrazo'] ? $params['dataPrazo'] : '');
                    $objMdUtlRevisaoDTO->setNumTempoExecucao(isset($params['tempoExecucao']) ? $params['tempoExecucao'] : '');

                    $retorno = $mdUtlRevisaoRN->alterar($objMdUtlRevisaoDTO);
                    break;
            }
        }
        return $retorno;
    }

    public function recuperarHistAnterior($idProcedimento, $idHist, $tipoAcao, array $status)
    {
        $objMdUtlHistControleDsmpRn = new MdUtlHistControleDsmpRN();
        $objMdUtlHistControleDsmpDto = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDto->retTodos();
        $objMdUtlHistControleDsmpDto->setDblIdProcedimento($idProcedimento);
        $objMdUtlHistControleDsmpDto->setStrTipoAcao($tipoAcao);
        $objMdUtlHistControleDsmpDto->setStrStaAtendimentoDsmp($status, InfraDTO::$OPER_IN);
        $objMdUtlHistControleDsmpDto->setNumIdMdUtlHistControleDsmp($idHist, InfraDTO::$OPER_MENOR);
        $objMdUtlHistControleDsmpDto->setOrd('IdMdUtlHistControleDsmp', InfraDTO::$TIPO_ORDENACAO_DESC);

        return $objMdUtlHistControleDsmpRn->listar($objMdUtlHistControleDsmpDto);
    }

    public function exiteSolicitacaoAjusteHistorico($idProcedimento, $idHist)
    {
        $retorno['possui_ajuste'] = false;

        $objMdUtlHistControleDsmpRn = new MdUtlHistControleDsmpRN();
        $objMdUtlHistControleDsmpDto = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDto->retTodos();
        $objMdUtlHistControleDsmpDto->setDblIdProcedimento($idProcedimento);
        $objMdUtlHistControleDsmpDto->setNumIdMdUtlHistControleDsmp($idHist, InfraDTO::$OPER_MENOR);
        $objMdUtlHistControleDsmpDto->setOrd('IdMdUtlHistControleDsmp', InfraDTO::$TIPO_ORDENACAO_ASC);

        $arrObjHistorico = $objMdUtlHistControleDsmpRn->listar($objMdUtlHistControleDsmpDto);
        foreach ($arrObjHistorico as $historico) {
            if ($historico->isSetNumIdMdUtlAjustePrazo() && !is_null($historico->getNumIdMdUtlAjustePrazo())) {
                $objMdUtlAjustePrazoRN = new MdUtlAjustePrazoRN();
                $objMdUtlAjustePrazoDTO = new MdUtlAjustePrazoDTO();

                $objMdUtlAjustePrazoDTO->setNumIdMdUtlAjustePrazo($historico->getNumIdMdUtlAjustePrazo());
                $objMdUtlAjustePrazoDTO->retTodos();

                $objAjustePrazo = $objMdUtlAjustePrazoRN->consultar($objMdUtlAjustePrazoDTO);

                if ($objAjustePrazo && $objAjustePrazo->getStrStaSolicitacao() == MdUtlAjustePrazoRN::$APROVADA) {
                    $retorno['possui_ajuste'] = true;
                    $detalhe = $historico->getStrDetalhe();

                    if ($detalhe == MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_INTERRUPCAO) {
                        $retorno['possui_interrupcao'] = true;
                        $retorno['possui_suspensao'] = false;
                        $retorno['possui_dilacao'] = true;
                    }
                    if ($detalhe == MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_SUSPENSAO) {
                        $retorno['possui_interrupcao'] = false;
                        $retorno['possui_suspensao'] = true;
                        $retorno['possui_dilacao'] = false;
                    }
                    if ($detalhe == MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_DILACAO) {
                        $retorno['possui_interrupcao'] = false;
                        $retorno['possui_suspensao'] = false;
                        $retorno['possui_dilacao'] = true;
                    }
                    $retorno['obj'] = $objAjustePrazo;
                }
            }
        }
        return $retorno;
    }

    protected function validaPlanoTrabalhoConectado( $arrPost )
    {
        if( empty( $arrPost ) ) return ['msg' => 'Dados Incompletos!', 'erro' => true];

        // valida se existe o documento informado e se o tipo de documento esta configurado no Tipo de Ctrl
        $objDocumentoDTO = new DocumentoDTO();
        
        $objDocumentoDTO->setStrProtocoloDocumentoFormatado( $arrPost['num_sei'] );
        $objDocumentoDTO->setNumMaxRegistrosRetorno(1);

        $objDocumentoDTO->retDblIdDocumento();
        $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
        $objDocumentoDTO->retNumIdSerie();
        $objDocumentoDTO->retDblIdProcedimento();

        $objDocumentoRN = new DocumentoRN();
        $objDocumentoDTO = $objDocumentoRN->consultarRN0005( $objDocumentoDTO );
        
        if( is_null( $objDocumentoDTO ) ) return ['msg' => MdUtlMensagemINT::$MSG_UTL_29 , 'erro' => true];
        
        if( $objDocumentoDTO->getNumIdSerie() != $arrPost['id_serie'] ) return ['msg' => MdUtlMensagemINT::$MSG_UTL_31 , 'erro' => true];

        // valida se está assinado
        $objAssinaturaDTO = new AssinaturaDTO();
        $objAssinaturaDTO->setDblIdDocumento( $objDocumentoDTO->getDblIdDocumento() );
        $objAssinaturaDTO->retDthAberturaAtividade();
        $objAssinaturaDTO->setOrdDthAberturaAtividade( InfraDTO::$TIPO_ORDENACAO_ASC );

        $objAssinaturaRN = new AssinaturaRN();
        
        $countAss = $objAssinaturaRN->contarRN1324($objAssinaturaDTO);

        $isPossuiAssinatura = $countAss > 0;

        if( !$isPossuiAssinatura ) return ['msg' => 'O Plano de Trabalho (Número SEI) informado não está assinado.' , 'erro' => true];

        // valida se o documento informado já foi utilizado em outra parametrizacao
        $objHistPrmGrUsuDTO = new MdUtlAdmHistPrmGrUsuDTO();
        $objHistPrmGrUsuRN  = new MdUtlAdmHistPrmGrUsuRN();

        $objHistPrmGrUsuDTO->setStrProtocoloFormatadoDocumento( $arrPost['num_sei'] );
        $objHistPrmGrUsuDTO->retDblIdDocumento();
        $objHistPrmGrUsuDTO->retNumIdMdUtlAdmPrmGr();

        $arrHistPrmGrUsu = $objHistPrmGrUsuRN->listar( $objHistPrmGrUsuDTO );

        if ( !empty( $arrHistPrmGrUsu ) ){
            $idsPrmGr     = InfraArray::converterArrInfraDTO( $arrHistPrmGrUsu ,'IdMdUtlAdmPrmGr' );
            $objTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
            $objTpCtrlRN  = new MdUtlAdmTpCtrlDesempRN();

            $objTpCtrlDTO->setNumIdMdUtlAdmPrmGr( $idsPrmGr , InfraDTO::$OPER_IN );
            $objTpCtrlDTO->retNumIdMdUtlAdmPrmGr();
            $objTpCtrlDTO->retNumIdMdUtlAdmTpCtrlDesemp();
            $objTpCtrlDTO->retStrNome();

            $arrTpCtrl = InfraArray::converterArrInfraDTO( $objTpCtrlRN->listar( $objTpCtrlDTO ) ,'Nome' , 'IdMdUtlAdmPrmGr' ) ;

            $msg_ret = "O Plano de Trabalho (Número SEI) já foi utilizado";

            if( ! array_key_exists( $arrPost['id_prm_gr'] , $arrTpCtrl ) ){
                $msg_ret .= " no seguinte Tipo de Controle:\n";
                foreach ( $arrTpCtrl as $nome ) $msg_ret .= "- " . $nome . "\n";
            }else{
                $msg_ret .= '.';
            }
            return ['msg' => $msg_ret , 'erro' => true];
        }

        $msg = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento=' . $objDocumentoDTO->getDblIdDocumento());

        return ['msg' => $msg , 'erro' => false];
    }    
}

?>