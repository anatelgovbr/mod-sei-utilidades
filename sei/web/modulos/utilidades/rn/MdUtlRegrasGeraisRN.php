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

    protected function verificarExistenciaUnidadeConectado($params){
        $arrObjUnidadeAPI = $params[0];
        $acao             = $params[1];

        $arrIds = array();
        $msg = '';
        foreach ($arrObjUnidadeAPI as $objUnidade) {
            $arrIds[] = $objUnidade->getIdUnidade();
        }

        $objMdUtlAdmRelTpCtrlUndRN  = new MdUtlAdmRelTpCtrlDesempUndRN();
        $objMdUtlAdmRelTpCtrlUndDTO = new MdUtlAdmRelTpCtrlDesempUndDTO();
        $objMdUtlAdmRelTpCtrlUndDTO->setNumIdUnidade($arrIds, InfraDTO::$OPER_IN);
        $objMdUtlAdmRelTpCtrlUndDTO->retTodos();

        $existeUnidade = $objMdUtlAdmRelTpCtrlUndRN->contar($objMdUtlAdmRelTpCtrlUndDTO) > 0;

        if($existeUnidade){
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_43, array($acao));
        }

        return $msg;
    }

    protected function verificarExistenciaTipoDocumentoConectado($params){
        $arrObjSerieAPI = $params[0];
        $acao             = $params[1];

        $arrIds = array();
        $msg = '';
        foreach ($arrObjSerieAPI as $objSerie) {
            $arrIds[] = $objSerie->getIdSerie();
        }

        $objMdUtlAdmAtvSerieProdRN  = new MdUtlAdmAtvSerieProdRN();

        $objMdUtlAdmAtvSerieProdDTO = new MdUtlAdmAtvSerieProdDTO();
        $objMdUtlAdmAtvSerieProdDTO->setNumIdSerie($arrIds, InfraDTO::$OPER_IN);
        $objMdUtlAdmAtvSerieProdDTO->retTodos();

        $existeTpDocumento = $objMdUtlAdmAtvSerieProdRN->contar($objMdUtlAdmAtvSerieProdDTO) > 0;

        if($existeTpDocumento){
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_34, array($acao));
        }

        return $msg;
    }

    protected function verificarExistenciaTipoProcessoConectado($params){
        $arrObjTpProcAPI  =  $params[0];
        $acao             = $params[1];

        $arrIds = array();
        $msg = '';
        foreach ($arrObjTpProcAPI as $objSerie) {
            $arrIds[] = $objSerie->getIdTipoProcedimento();
        }

        $objMdUtlAdmRelPrmGrProcRN   = new MdUtlAdmRelPrmGrProcRN();
        $objMdUtlAdmRelPrmGrProcDTO  = new MdUtlAdmRelPrmGrProcDTO();
        $objMdUtlAdmRelPrmGrProcDTO->setNumIdTipoProcedimento($arrIds, InfraDTO::$OPER_IN);
        $objMdUtlAdmRelPrmGrProcDTO->retTodos();

        $existeTpProcedimento = $objMdUtlAdmRelPrmGrProcRN->contar($objMdUtlAdmRelPrmGrProcDTO) > 0;

        if($existeTpProcedimento){
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_39, array($acao));
        }

        return $msg;
    }

    private function _validarGestorTipoControle($arrIds, $acao){
        $msg = '';

        $objMdUtlAdmRelTpCtrlUsuRN   = new MdUtlAdmRelTpCtrlDesempUsuRN();
        $objMdUtlAdmRelTpCtrlUsuDTO = new MdUtlAdmRelTpCtrlDesempUsuDTO();
        $objMdUtlAdmRelTpCtrlUsuDTO->setNumIdUsuario($arrIds, InfraDTO::$OPER_IN);
        $objMdUtlAdmRelTpCtrlUsuDTO->retTodos();

        $existeGestor = $objMdUtlAdmRelTpCtrlUsuRN->contar($objMdUtlAdmRelTpCtrlUsuDTO) > 0;

        if($existeGestor){
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_40, array($acao));
        }

        return $msg;
    }

    private function _validarControleDsmp($arrIds, $acao){
        $msg = '';

        $objMdUtlControleDsmpRN  = new MdUtlControleDsmpRN();
        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->retTodos();

        $objMdUtlControleDsmpDTO->adicionarCriterio(array('IdUsuarioAtual', 'IdUsuarioDistribuicao'), array(InfraDTO::$OPER_IN, InfraDTO::$OPER_IN), array($arrIds, $arrIds), array(InfraDTO::$OPER_LOGICO_OR));
        $existeControleDsmp = $objMdUtlControleDsmpRN->contar($objMdUtlControleDsmpDTO) > 0;

        if($existeControleDsmp){
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_41, array($acao));
        }

        return $msg;

    }

    private function _validarHsControleDsmp($arrIds, $acao){

        $msg = '';

        $objMdUtlHistControleDsmpRN  = new MdUtlHistControleDsmpRN();
        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->retTodos();

        $objMdUtlHistControleDsmpDTO->adicionarCriterio(array('IdUsuarioAtual', 'IdUsuarioDistribuicao'), array(InfraDTO::$OPER_IN, InfraDTO::$OPER_IN), array($arrIds, $arrIds), array(InfraDTO::$OPER_LOGICO_OR));
        $existeHsControleDsmp = $objMdUtlHistControleDsmpRN->contar($objMdUtlHistControleDsmpDTO) > 0;

        if($existeHsControleDsmp){
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_42, array($acao));
        }

        return $msg;

    }

    protected function verificarExistenciaUsuarioConectado($params){

        $arrObjUsuarioAPI = $params[0];
        $acao             = $params[1];
        $arrIds           = array();
        $msg              = '';


        foreach ($arrObjUsuarioAPI as $objUsuario) {
            $arrIds[] = $objUsuario->getIdUsuario();
        }


        //Validar Controle DSMP
        $msg  = $this->_validarControleDsmp($arrIds, $acao);
        if($msg != ''){
            return $msg;
        }

        //Validar Histуrico Controle DSMP
        $msg  = $this->_validarHsControleDsmp($arrIds, $acao);
        if($msg != ''){
            return $msg;
        }


        //Validar Gestor no Tipo de Controle
        $msg  = $this->_validarGestorTipoControle($arrIds, $acao);
        if($msg != ''){
            return $msg;
        }

        //Validar Usuбrio Participante na Parametrizaзгo do Tipo de Controle
        $msg  = $this->_validarUsuarioParticipanteParametrizacao($arrIds, $acao);
        if($msg != ''){
            return $msg;
        }

        //Validar Usuario Participante na Jornada
        $msg = $this->_validarUsuarioParticipanteJornada($arrIds, $acao);
        return $msg;
    }

    private function _validarUsuarioParticipanteParametrizacao($arrIds, $acao){
        $objMdUtlAdmRelPrmGrUsuRN  = new MdUtlAdmPrmGrUsuRN();
        $objMdUtlAdmRelPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
        $objMdUtlAdmRelPrmGrUsuDTO->setNumIdUsuario($arrIds, InfraDTO::$OPER_IN);
        $objMdUtlAdmRelPrmGrUsuDTO->retTodos();

        $existeUsuarioParticipante = $objMdUtlAdmRelPrmGrUsuRN->contar($objMdUtlAdmRelPrmGrUsuDTO) > 0;

        if($existeUsuarioParticipante){
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_36, array($acao));
        }

        return $msg;
    }

    private function _validarUsuarioParticipanteJornada($arrIds, $acao){
        $objMdUtlAdmRelJornadaUsuRN  = new MdUtlAdmRelJornadaUsuRN();
        $objMdUtlAdmRelJornadaUsuDTO = new MdUtlAdmRelJornadaUsuDTO();
        $objMdUtlAdmRelJornadaUsuDTO->setNumIdUsuario($arrIds, InfraDTO::$OPER_IN);
        $objMdUtlAdmRelJornadaUsuDTO->retTodos();

        $existeUsuarioParticipante = $objMdUtlAdmRelJornadaUsuRN->contar($objMdUtlAdmRelJornadaUsuDTO) > 0;

        if($existeUsuarioParticipante){
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_37, array($acao));
        }

        return $msg;
    }

    protected function getObjProcedimentoPorIdConectado($idProcedimento = null){
        $objRn = new ProcedimentoRN();
        if(!is_null($idProcedimento)){
            $objProcedimentoDTO = new ProcedimentoDTO();
            $objProcedimentoDTO->setDblIdProcedimento($idProcedimento);
            $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
            $objProcedimentoDTO->retTodos();
            $count = $objRn->contarRN0279($objProcedimentoDTO);

            if($count > 0){
                $objProcedimentoDTO->setNumMaxRegistrosRetorno(1);
                $objProcedimentoDTO = $objRn->consultarRN0201($objProcedimentoDTO);
                return $objProcedimentoDTO;
            }
        }

        return null;
    }

    protected function retornaArrDadosDocumentoSEIConectado($arrParams){
        $arrDados    = array();
        $numeroSei   = array_key_exists(0, $arrParams) && $arrParams[0] != '' ? $arrParams[0] : false;
        $numeroSei   = trim($numeroSei);
        $idProced    = array_key_exists(1, $arrParams) && $arrParams[1] != '' ? $arrParams[1] : false;
        $idSerieAtv  = array_key_exists(2, $arrParams) && $arrParams[2] != '' ? $arrParams[2] : false;
        $isPossuiAssinatura = true;

        if($numeroSei && $idProced && $idSerieAtv){

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
                    $arrAssinatura = $objDocumentoDTO->getArrObjAssinaturaDTO();
                    if (count($arrAssinatura) > 0) {
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
        }else{
            return array('msg'=> 'Dados Incompletos!','erro'=>true);
        }

        $arrDados = $this->_retornaArrFormatadoValidacoesDoc($objDocumentoDTO, $isPossuiAssinatura, $idProced, $idSerieAtv);

        return $arrDados;
    }

    private function _retornaArrFormatadoValidacoesDoc($objDocumentoDTO, $isPossuiAssinatura, $idProced, $idSerieAtv){

        $docsPermt        = false;
        $arrDados['erro'] = false;
        $arrDados['msg']  = '';

        //Valida Existкncia do Nъmero SEI
        if (is_null($objDocumentoDTO)) {
            $arrDados['msg']  = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_29);
            $arrDados['erro'] = true;
            return $arrDados;
        }


        //Verificando se Documento pertence a esse processo
        if ($idProced) {
            if ($idProced != $objDocumentoDTO->getDblIdProcedimento()) {
                $arrDados['msg']  =  MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_30);
                $arrDados['erro'] = true;
                return $arrDados;
            }
        }

        //Verificando se o tipo de documento й do tipo exigido pela Atividade
        if($idSerieAtv){
            if ($idSerieAtv != $objDocumentoDTO->getNumIdSerie()) {
                $arrDados['msg']  =  MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_31);
                $arrDados['erro'] = true;
                return $arrDados;
            }
        }

        //Verifica se os Tipos de Documento sгo permitidos
        $docsPermt = $objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EDITOR_INTERNO || $objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_EXTERNO;
        if (!$docsPermt) {
            $arrDados['msg']  =  MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_32);
            $arrDados['erro'] =  true;
            return $arrDados;
        }


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
    
    protected function getUltimoUsuarioAtribuidoUnidadeLogadaConectado($idProcedimento = null){

        if(!is_null($idProcedimento)) {
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

    protected function validarSituacaoProcessoConectado($idProcedimento = null){
        $isValido = false;
        if(!is_null($idProcedimento)) {
            $objProcedimentoDTO = new ProcedimentoDTO();
            $objProcedimentoDTO->setDblIdProcedimento($idProcedimento);
            $objProcedimentoDTO->setStrStaEstadoProtocolo(ProtocoloRN::$TE_NORMAL);
            $objProcedimentoDTO->setStrStaNivelAcessoLocalProtocolo(array(ProtocoloRN::$NA_PUBLICO, ProtocoloRN::$NA_RESTRITO), InfraDTO::$OPER_IN);
            $objProcedimentoDTO->setNumMaxRegistrosRetorno(1);

            $objProcedimentoRN = new ProcedimentoRN();
            $isValido = $objProcedimentoRN->contarRN0279($objProcedimentoDTO) > 0;
        }

        return $isValido;
    }

    protected function verificaConclusaoProcessoConectado($arrDados){
        $isConcluido    = true;
        $idProcedimento = array_key_exists('0', $arrDados) ? $arrDados[0] : null;
        $idUnidade      = array_key_exists('1', $arrDados) ? $arrDados[1] : SessaoSEI::getInstance()->getNumIdUnidadeAtual();

        if(!is_null($idProcedimento)) {
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

    public function controlarAtribuicaoGrupo($arrIdsProcedimento){
        if(count($arrIdsProcedimento) > 0){
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
}

?>