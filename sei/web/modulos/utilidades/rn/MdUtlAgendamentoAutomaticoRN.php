<?

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdUtlAgendamentoAutomaticoRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    public static $SIM = 'S';
    public static $NAO = 'N';

    /* Método responsável por atualizar o andamento dos objs no correios */
    protected function aprovarReprovarAjustesPrazoControlado()
{

    try {
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '1024M');

        InfraDebug::getInstance()->setBolLigado(true);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        InfraDebug::getInstance()->setBolEcho(false);
        InfraDebug::getInstance()->limpar();

        $numSeg = InfraUtil::verificarTempoProcessamento();
        InfraDebug::getInstance()->gravar('ATUALIZANDO STATUS DO OBJETO DE UTILIDADES');

        $objCtrlDsmpRN = new MdUtlControleDsmpRN();
        $objGestaoAjustPrazoRN = new MdUtlGestaoAjustPrazoRN();

        /*Busca id do usuário de utilidades para agendamento automático do sistema*/
        $objUsuarioRN = new MdUtlUsuarioRN();
        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioDTO = $objUsuarioRN->getObjUsuarioUtilidades();

        /*Verificar todas as solicitações de de ajuste de prazo com a situação igual a pendente de resposta do gestor e que o prazo atual para a execução da tarefa vence em D-1*/
        $arrObjCtrlDsmpDTO = $this->_verificarAjustePendente();

        /*Busca na parametrização a resposta tácita*/
        $arrRetorno = $this->_buscarRespostaTacitaParametrizacao($arrObjCtrlDsmpDTO);

        /*Percorre os objetos e aprova ou reprova conforme sua resposta tácita*/
        foreach ($arrObjCtrlDsmpDTO as $objDTO){
            $idCtrlDsmp = $objDTO->getNumIdMdUtlControleDsmp();
            $idTpCtrlDsmp = $objDTO->getNumIdMdUtlAdmTpCtrlDesemp();
            $strTipoDeSolicitacao = $objDTO->getStrStaTipoSolicitacaoAjustePrazo();
            $idUnidade = $objDTO->getNumIdUnidade();
            $respTacitaFila      = $objDTO->getStrRespTacitaDilacao();
            $respostaTacitaMain  = $arrRetorno[$idTpCtrlDsmp][$strTipoDeSolicitacao];

            if(!is_null($respTacitaFila)&& $strTipoDeSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_DILACAO){
                $respostaTacitaMain = $respTacitaFila;
            }

            SessaoSEI::getInstance()->simularLogin(null, null, $objUsuarioDTO->getNumIdUsuario(), $idUnidade );

            $objControleDsmpDTO = new MdUtlControleDsmpDTO();

            $objControleDsmpDTO->setNumIdMdUtlControleDsmp($idCtrlDsmp);
            $objControleDsmpDTO->retTodos();
            $objControleDsmpDTO->retStrProtocoloProcedimentoFormatado();
            $objControleDsmpDTO->retNumIdContato();
            $objControleDsmpDTO->retStrEmail();
            $objControleDsmpDTO->setNumMaxRegistrosRetorno(1);
            $objControleDsmpDTO = $objCtrlDsmpRN->consultar($objControleDsmpDTO);

            if($respostaTacitaMain == MdUtlAdmPrmGrRN::$APROVACAO_TACITA){
                $objGestaoAjustPrazoRN->aprovarSolicitacao($objControleDsmpDTO);
            } else {
                $objGestaoAjustPrazoRN->reprovarSolicitacao($objControleDsmpDTO);
            }

        }


        $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
        InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: ' . $numSeg . ' s');
        InfraDebug::getInstance()->gravar('FIM');

        LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(), InfraLog::$INFORMACAO);

        InfraDebug::getInstance()->setBolLigado(false);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        InfraDebug::getInstance()->setBolEcho(false);

    } catch (Exception $e) {

        InfraDebug::getInstance()->setBolLigado(false);
        InfraDebug::getInstance()->setBolDebugInfra(false);
        InfraDebug::getInstance()->setBolEcho(false);
        throw new InfraException('Erro atualizando os objetos em andamento de utilidades.', $e);
    }

}

    protected function retornarStatusFinalControlado()
    {

        try {
            ini_set('max_execution_time', '0');
            ini_set('memory_limit', '1024M');

            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            InfraDebug::getInstance()->limpar();

            $numSeg = InfraUtil::verificarTempoProcessamento();
            InfraDebug::getInstance()->gravar('ATUALIZANDO STATUS DO OBJETO DE UTILIDADES');

            $objMdUtlControleRN = new MdUtlControleDsmpRN();
            $objMdUtlControleRN->retornaStatusImpedido();

            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: ' . $numSeg . ' s');
            InfraDebug::getInstance()->gravar('FIM');

            LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(), InfraLog::$INFORMACAO);

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);

        } catch (Exception $e) {

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            throw new InfraException('Erro atualizando os objetos em andamento de utilidades.', $e);
        }

    }

    protected function associarProcessoFilaControlado()
    {

        try {
            ini_set('max_execution_time', '0');
            ini_set('memory_limit', '1024M');

            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            InfraDebug::getInstance()->limpar();

            $numSeg = InfraUtil::verificarTempoProcessamento();
            InfraDebug::getInstance()->gravar('ATUALIZANDO STATUS DO OBJETO DE UTILIDADES');

            $this->_associarFilaAutomaticamente();

            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: ' . $numSeg . ' s');
            InfraDebug::getInstance()->gravar('FIM');

            LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(), InfraLog::$INFORMACAO);

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);

        } catch (Exception $e) {

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            throw new InfraException('Erro atualizando os objetos em andamento de utilidades.', $e);
        }

    }

    protected function reprovarContestacaoControlado()
    {

        try {
            ini_set('max_execution_time', '0');
            ini_set('memory_limit', '1024M');

            InfraDebug::getInstance()->setBolLigado(true);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            InfraDebug::getInstance()->limpar();

            $numSeg = InfraUtil::verificarTempoProcessamento();
            InfraDebug::getInstance()->gravar('REPROVANDO CONTESTAÇÕES PENDENTES POR PRAZO TÁCITO');

            $objContestacaoRN = new MdUtlContestacaoRN();
            $objContestacaoRN->getContestacaoPendente(true);

            $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
            InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: ' . $numSeg . ' s');
            InfraDebug::getInstance()->gravar('FIM');

            LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(), InfraLog::$INFORMACAO);

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);

        } catch (Exception $e) {

            InfraDebug::getInstance()->setBolLigado(false);
            InfraDebug::getInstance()->setBolDebugInfra(false);
            InfraDebug::getInstance()->setBolEcho(false);
            throw new InfraException('Erro atualizando os objetos em andamento de utilidades.', $e);
        }

    }

    private function _associarFilaAutomaticamente()
    {
        $objMdUtlTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
        $objMdUtlTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();

        $objMdUtlTpCtrlDTO->adicionarCriterio(array('SinUltimaFila'), array(InfraDTO::$OPER_IGUAL), array('S'));

        $objMdUtlTpCtrlDTO->retNumIdMdUtlAdmTpCtrlDesemp();
        $objMdUtlTpCtrlDTO->setParametroFiltroFk(InfraDTO::$FILTRO_FK_WHERE);
        $objMdUtlTpCtrlDTO->setParametroFk(InfraDTO::$TIPO_FK_OBRIGATORIA);
        $arrObjsTpCtrlDTO = $objMdUtlTpCtrlRN->listar($objMdUtlTpCtrlDTO);
        
        $arrTiposControleCondicional = array();
        $arrTiposControleUtilizados = array();

        foreach ($arrObjsTpCtrlDTO as $objDTO) {
            $arrTiposControleCondicional[] = $objDTO->getNumIdMdUtlAdmTpCtrlDesemp();
        }
        
        if (count($arrTiposControleUtilizados) > 0 || count($arrTiposControleCondicional) > 0) {
            $idsTiposProcessoParametrizados = $objMdUtlTpCtrlRN->getTiposProcessoTodosTipoControle();

            if (count($idsTiposProcessoParametrizados) > 0) {
                $idsTiposProcessoParametrizados = array_unique($idsTiposProcessoParametrizados);
                $objMdUtlAtvPrincipalDTO = new MdUtlAtividadePrincipalDTO();

                $objMdUtlAtvPrincipalDTO->retNumIdUnidade();
                $objMdUtlAtvPrincipalDTO->retDblIdProtocolo();
                $objMdUtlAtvPrincipalDTO->retNumIdMdUtlAdmTpCtrlDesemp();
                $objMdUtlAtvPrincipalDTO->retDthAbertura();
                $objMdUtlAtvPrincipalDTO->retNumIdUtlTipoProcedimentoProcedimento();
                $objMdUtlAtvPrincipalDTO->setOrd('Abertura', InfraDTO::$TIPO_ORDENACAO_ASC);
                $objMdUtlAtvPrincipalDTO->setNumMaxRegistrosRetorno(10000);
                $objMdUtlAtvPrincipalDTO->setNumIdUtlTipoProcedimentoProcedimento($idsTiposProcessoParametrizados, InfraDTO::$OPER_IN);
                $objMdUtlAtvPrincipalDTO->setDthConclusao(null);
                $objMdUtlAtvPrincipalDTO->setNumIdMdUtlControleDsmp(null);
                $objMdUtlAtvPrincipalDTO->setStrStaUtlNivelAcessoLocalProtocolo(array(ProtocoloRN::$NA_PUBLICO, ProtocoloRN::$NA_RESTRITO), InfraDTO::$OPER_IN);
                $objMdUtlAtvPrincipalDTO->setStrStaEstadoProtocolo(ProtocoloRN::$TE_NORMAL);

                if (count($arrTiposControleCondicional) > 0) {
                    $objMdUtlAtvPrincipalDTO->adicionarCriterio(array('IdMdUtlAdmTpCtrlDesemp', 'IdMdUtlHistControleDsmp'),
                        array(InfraDTO::$OPER_IN, InfraDTO::$OPER_DIFERENTE),
                        array($arrTiposControleCondicional, null),
                        array(InfraDTO::$OPER_LOGICO_AND),
                        'CriterioUltimasFilas');
                    $arrGrupos[] = 'CriterioUltimasFilas';
                }

                $objMdUtlAtvPrincipalDTO->setDistinct(true);

                $objAtividadeRN = new AtividadeRN();
                $objControleDsmpRN = new MdUtlControleDsmpRN();
                $objHistoricoRN = new MdUtlHistControleDsmpRN();

                $arrObjs = $objAtividadeRN->listarRN0036(($objMdUtlAtvPrincipalDTO));

                /*Busca id do usuário de utilidades para agendamento automático do sistema*/
                $objUsuarioRN = new MdUtlUsuarioRN();
                $objUsuarioDTO = new UsuarioDTO();
                $objUsuarioDTO = $objUsuarioRN->getObjUsuarioUtilidades();

                if (count($arrObjs) > 0) {
                    $objRnGerais = new MdUtlRegrasGeraisRN();
                    $arrAtendimentosMapeado = $objRnGerais->retornaArrAtendimentoMapeado($arrObjs);
                    $arrTipoProcedimentoCompleto = $objMdUtlTpCtrlRN->getObjTipoControlePorPrm();

                    foreach ($arrObjs as $obj) {
                        $idFilaHistorico = '';
                        $idFilaPadrao = '';

                        $idAtendimentoAntigo = null;
                        $idProcedimento = $obj->getDblIdProtocolo();
                        $idTipoControle = $obj->getNumIdMdUtlAdmTpCtrlDesemp();
                        $idUnidade = $obj->getNumIdUnidade();
                        $idTipoProcedimento = $obj->getNumIdUtlTipoProcedimentoProcedimento();

                        $arrTiposProcedimento = array_key_exists($idTipoControle, $arrTipoProcedimentoCompleto) ? $arrTipoProcedimentoCompleto[$idTipoControle] : null;

                        if (!is_null($arrTiposProcedimento)) {

                            if (array_key_exists($idTipoProcedimento, $arrTiposProcedimento)) {

                                SessaoSEI::getInstance()->simularLogin(null, null, $objUsuarioDTO->getNumIdUsuario(), $idUnidade);

                                $objMdUtlTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
                                $objMdUtlTpCtrlDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
                                $objMdUtlTpCtrlDTO->retStrSinUltimaFila();
                                #$objMdUtlTpCtrlDTO->retNumIdMdUtlAdmFila();
                                $objMdUtlTpCtrlDTO->setNumMaxRegistrosRetorno(1);

                                $numRegistrosTpCtrl = $objMdUtlTpCtrlRN->contar($objMdUtlTpCtrlDTO);
                                $objMdUtlTpCtrlDTO = $objMdUtlTpCtrlRN->consultar($objMdUtlTpCtrlDTO);

                                if ($numRegistrosTpCtrl > 0) {
                                    $nomeFila = '';
                                    $sinUltimaFila = $objMdUtlTpCtrlDTO->getStrSinUltimaFila();
                                    $TmpExecucao = 0;                                 

                                    /*Se possuir ultima fila - buscar em historico e atribuir*/
                                    if ($sinUltimaFila == 'S') {
                                        $objHistoricoDTO = new MdUtlHistControleDsmpDTO();
                                        $objHistoricoDTO->setDblIdProcedimento($idProcedimento);
                                        $objHistoricoDTO->setNumIdUnidade($idUnidade);
                                        $objHistoricoDTO->setOrdDthAtual(InfraDTO::$TIPO_ORDENACAO_DESC);
                                        $objHistoricoDTO->setNumMaxRegistrosRetorno(1);
                                        $objHistoricoDTO->retNumIdMdUtlAdmFila();
                                        $objHistoricoDTO->retNumIdAtendimento();
                                        $objHistoricoDTO->retStrNomeFila();

                                        $numRegistrosHist = $objHistoricoRN->contar($objHistoricoDTO);
                                        $objHistoricoDTO = $objHistoricoRN->consultar($objHistoricoDTO);

                                        if ($numRegistrosHist > 0) {
                                            $idFilaHistorico = $objHistoricoDTO->getNumIdMdUtlAdmFila();
                                            $nomeUltimaFila = $objHistoricoDTO->getStrNomeFila();
                                            $idAtendimentoAntigo = $objHistoricoDTO->getNumIdAtendimento() != '' ? $objHistoricoDTO->getNumIdAtendimento() : $idAtendimentoAntigo;
                                        }
                                    }

                                    $arrDadosFilaAssociar = MdUtlControleDsmpINT::verificaFilaAssociacaoAutomatica($sinUltimaFila, $idFilaHistorico, $nomeUltimaFila, $idFilaPadrao, $nomeFila);
                                    $idFilaCompleto   = $arrDadosFilaAssociar['idFilaCompleto'];
                                    $nomeFilaCompleto = $arrDadosFilaAssociar['nomeFilaCompleto'];
                                    $isPreenchido     = $arrDadosFilaAssociar['isPreenchido'];

                                    if($isPreenchido) {
                                        $arrProcessosAtend   = array_key_exists($idProcedimento, $arrAtendimentosMapeado) ? $arrAtendimentosMapeado[$idProcedimento] : null;

                                        $idAtendimentoAntigo = null;
                                        $idAtendimentoAntigo = !is_null($arrProcessosAtend) && array_key_exists($idUnidade, $arrProcessosAtend) ? $arrProcessosAtend[$idUnidade] : null;
                                        $idAtendimentoNovo   =  (is_null($idAtendimentoAntigo)) ? 1 : $idAtendimentoAntigo + 1;

                                        if ($idProcedimento != '' && $idUnidade != '') {
                                            $isExiste = $objControleDsmpRN->validaExistenciaProcessoAtivo(array($idProcedimento, $idUnidade));

                                            $isFilasPreenchidas = !is_null($idFilaCompleto);

                                            if (!$isExiste && $isFilasPreenchidas) {

                                                //busca o tempo de execucao da triagem
                                                $objFilaRN = new MdUtlAdmFilaRN();
                                                $objFilaDTO = new MdUtlAdmFilaDTO();
                                                $objFilaDTO->setNumIdMdUtlAdmFila($objHistoricoDTO->getNumIdMdUtlAdmFila());
                                                $objFilaDTO->retTodos();
                                                $numRegistrosFila = $objFilaRN->contar( $objFilaDTO );
                                                if ( $numRegistrosFila > 0 ) {
                                                    $objFilaDTO  = $objFilaRN->consultar( $objFilaDTO );                                                    
                                                    $TmpExecucao = $objFilaDTO->getNumTmpExecucaoTriagem();
                                                }

                                                //monta o parametro para associar o processo a fila configurada
                                                $arrParams = array($idProcedimento, $idFilaCompleto, $idTipoControle, MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM, $idUnidade, $TmpExecucao, null, null, null, null, $nomeFilaCompleto, MdUtlControleDsmpRN::$STR_TIPO_ACAO_ASSOCIACAO, $idAtendimentoNovo, null, null, date('d/m/Y H:i:s', strtotime('+3 second')));
                                                $objControleDsmpRN->cadastrarNovaSituacaoProcesso($arrParams);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /*Método ára consultar os ajustes pendentes e com data igual a D-1*/
    private function _verificarAjustePendente(){
        $data          = InfraData::getStrDataAtual();
        $data          .= ' 00:00:00';

        $objCtrlDsmpDTO = new MdUtlControleDsmpDTO();
        $objCtrlDsmpDTO->setStrStaSolicitacaoAjustePrazo(MdUtlAjustePrazoRN::$PENDENTE_RESPOSTA);
        $objCtrlDsmpDTO->setDthPrazoTarefa($data, InfraDTO::$OPER_MENOR);
        $objCtrlDsmpDTO->setAjustePrazoFK(InfraDTO::$TIPO_FK_OBRIGATORIA);
        $objCtrlDsmpDTO->retTodos();
        $objCtrlDsmpDTO->retDthPrazoTarefa();
        $objCtrlDsmpDTO->retStrStaAtendimentoDsmp();
        $objCtrlDsmpDTO->retStrStaTipoSolicitacaoAjustePrazo();
        $objCtrlDsmpDTO->retStrRespTacitaDilacao();

        $objCtrlDsmpRN  = new MdUtlControleDsmpRN();
        $arrObjCtrlDsmpDTO = $objCtrlDsmpRN->listar($objCtrlDsmpDTO);

        return $arrObjCtrlDsmpDTO;
    }

    /*Método que busca a resposta tácita da parametrização*/
    private function _buscarRespostaTacitaParametrizacao($arrObjCtrlDesempDTO){
        $arrIdTpCtrlDsmp = InfraArray::converterArrInfraDTO($arrObjCtrlDesempDTO, 'IdMdUtlAdmTpCtrlDesemp');
        $arrIdTpCtrlDsmp = ($arrIdTpCtrlDsmp) ? array_unique($arrIdTpCtrlDsmp) : null;

        if(!is_null($arrIdTpCtrlDsmp)){
            $objTpCtrlDsmpRN  = new MdUtlAdmTpCtrlDesempRN();
            $objTpCtrlDsmpDTO = new MdUtlAdmTpCtrlDesempDTO();
            $objTpCtrlDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($arrIdTpCtrlDsmp, InfraDTO::$OPER_IN);
            $objTpCtrlDsmpDTO->retTodos();
            $objTpCtrlDsmpDTO->retStrRespTacitaDilacao();
            $objTpCtrlDsmpDTO->retStrRespTacitaInterrupcao();
            $objTpCtrlDsmpDTO->retStrRespTacitaSuspensao();
            $arrObjs = $objTpCtrlDsmpRN->listar($objTpCtrlDsmpDTO);

            $arrRetorno = [];

            foreach ($arrObjs as $obj){
                $arrRetorno[$obj->getNumIdMdUtlAdmTpCtrlDesemp()] = array(
                    MdUtlControleDsmpRN::$TP_SOLICITACAO_DILACAO => $obj->getStrRespTacitaDilacao(),
                    MdUtlControleDsmpRN::$TP_SOLICITACAO_SUSPENSAO => $obj->getStrRespTacitaSuspensao(),
                    MdUtlControleDsmpRN::$TP_SOLICITACAO_INTERRUPCAO => $obj->getStrRespTacitaInterrupcao(),
                );

            }
        }

        return $arrRetorno;
    }

}
