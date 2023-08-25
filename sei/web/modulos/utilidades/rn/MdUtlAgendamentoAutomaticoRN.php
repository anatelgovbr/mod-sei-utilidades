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
                                                $arrParams = array();
                                                $arrParams['dblIdProcedimento'] = $idProcedimento;
                                                $arrParams['intIdFila'] = $idFilaCompleto;
                                                $arrParams['intIdTpCtrl'] = $idTipoControle;
                                                $arrParams['strStatus'] = MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM;
                                                $arrParams['intIdUnidade'] = $idUnidade;
                                                $arrParams['intTempoExecucao'] = $TmpExecucao;
                                                $arrParams['strDetalhe'] = $nomeFilaCompleto;
                                                $arrParams['tipoAcao'] = MdUtlControleDsmpRN::$STR_TIPO_ACAO_ASSOCIACAO;
                                                $arrParams['idAtendimentoNovo'] = $idAtendimentoNovo;
                                                $arrParams['dtHora'] =  date('d/m/Y H:i:s', strtotime('+3 second'));
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

	/**
	 * Funcao acionada pelo Agendamento
	 */
		protected function incluirPeriodoControlado(){
			try {
				$this->initDadosDebug();

				$numSeg = InfraUtil::verificarTempoProcessamento();
				InfraDebug::getInstance()->gravar('INCLUINDO A CARGA DOS USUÁRIOS COM TRATAMENTO RELACIONADO À CHEFIA IMEDIATA E AUSÊNCIAS');

				$dadosChefia = null;
				$arrObjIntegracao = (new MdUtlAdmIntegracaoRN())->obterConfigIntegracaoPorFuncionalidade(MdUtlAdmIntegracaoRN::$CHEFIA);

				// verifica se o serviço esta cadastrado e ativo
				if (!empty($arrObjIntegracao) && $arrObjIntegracao['integracao']->getStrTipoIntegracao() == 'RE') {
					$arrParams = ['loginUsuario' => ''];
					$arrParams = ['parametros' => MdUtlAdmIntegracaoINT::montaParametrosEntrada($arrObjIntegracao, $arrParams)];
					$dadosChefia = MdUtlAdmIntegracaoINT::executarConsultaREST($arrObjIntegracao, $arrParams['parametros']);
				}

				// instancia objetos da classe RN
				$objUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();
				$objMdUtlAdmPrmGrUsuCargaRN = new MdUtlAdmPrmGrUsuCargaRN();
				$objUtlAdmTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();

				// retorna os tipos de controles com o alguns dados da parametrizacao
				$objUtlAdmTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();

				$objUtlAdmTpCtrlDTO->setStrSinAtivo('S');
				$objUtlAdmTpCtrlDTO->setNumIdMdUtlAdmPrmGr(0, InfraDTO::$OPER_MAIOR);
				#$objUtlAdmTpCtrlDTO->setNumIdMdUtlAdmPrmGr([43],InfraDTO::$OPER_IN); //teste

				$objUtlAdmTpCtrlDTO->retNumIdMdUtlAdmPrmGr();
				$objUtlAdmTpCtrlDTO->retNumCargaPadrao();
				$objUtlAdmTpCtrlDTO->retStrStaFrequencia();

				$arrObjs = $objUtlAdmTpCtrlRN->listar($objUtlAdmTpCtrlDTO);

				// efetua loop em cada Tipo de Controle
				foreach ($arrObjs as $objPrmGr) {

					// retorna dados da parametrizacao do usuario + alguns dados da parametrizacao do Tipo de Controle
					$arrUsuarios = $objUtlAdmPrmGrUsuRN->getDadosUsuarioMembro($objPrmGr->getNumIdMdUtlAdmPrmGr());

					$continua = $this->validarStaFrequencia($arrUsuarios[0]['frequencia']);

					if ($continua) {
						foreach ($arrUsuarios as $usuario) {
							// variavel a ser usado no final para qualquer insert ou update
							$cargaHoraria = 0;
							$strDatasAusenciasUtilizadas = null;

							$arrPeriodos = $this->trataTempoMembroComAusenciasEChefia($usuario, $cargaHoraria, $strDatasAusenciasUtilizadas, true, $dadosChefia);

							$objMdUtlAdmPrmGrUsuCargaDTO = new MdUtlAdmPrmGrUsuCargaDTO();

							$cargaHoraria = $cargaHoraria < 0 ? 0 : $cargaHoraria;

							$objMdUtlAdmPrmGrUsuCargaDTO->setNumCargaHoraria($cargaHoraria);
							$objMdUtlAdmPrmGrUsuCargaDTO->setDtaPeriodoInicial($arrPeriodos['dtInicial']);
							$objMdUtlAdmPrmGrUsuCargaDTO->setDtaPeriodoFinal($arrPeriodos['dtFinal']);
							$objMdUtlAdmPrmGrUsuCargaDTO->setNumIdMdUtlAdmPrmGrUsu($usuario['idPrmGrUsu']);
							$objMdUtlAdmPrmGrUsuCargaDTO->setStrDatasAusencias($strDatasAusenciasUtilizadas);
							$objMdUtlAdmPrmGrUsuCargaDTO->setStrSinAtivo('S');
							$objMdUtlAdmPrmGrUsuCargaDTO->setNumIdMdUtlAdmPrmGr($usuario['idPrmGr']);
							$objMdUtlAdmPrmGrUsuCargaDTO->setNumIdUsuario($usuario['idUsuario']);

							$objMdUtlAdmPrmGrUsuCargaRN->cadastrar($objMdUtlAdmPrmGrUsuCargaDTO);
						}
					}
				}

				$numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
				InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: ' . $numSeg . ' s');
				InfraDebug::getInstance()->gravar('FIM');

				LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(), InfraLog::$INFORMACAO);
				$this->limpaDadosDebug();

			} catch ( Exception $e ) {
				$exception = $this->trataException($e);
				LogSEI::getInstance()->gravar( $exception , InfraLog::$INFORMACAO );
				$this->limpaDadosDebug();
				throw new InfraException('Falha no agendamento incluirPeriodo',$e);
			}
		}

		private function trataTempoMembroComAusenciasEChefia($usuario, &$cargaHoraria, &$strDatasAusenciasUtilizadas, $retornaPeriodo = false , $dadosChefia){

			$fatorPres    = $usuario['tipoJornada'] == 'R' ? $usuario['fatorJornada'] : null;
			$arrPeriodo   = (new MdUtlAdmPrmGrUsuRN())->getDiasUteisNoPeriodo([$usuario['frequencia'],false]);
			$cargaHoraria = (new MdUtlAdmPrmGrUsuCargaRN())->geraTempoCargaHoraria($fatorPres, $arrPeriodo['numFrequencia'], $usuario['cargaPadrao']);
			$arrDiasAusencias = [];

			$arrParams = [
				'idUsuario'     => $usuario['idUsuario'],
				'siglaUsuario'  => $usuario['siglaUsuario'],
				'cargaPadrao'   => $usuario['cargaPadrao'],
				'fatorPresenca' => $fatorPres,
				'dtInicialPer'  => implode('-',array_reverse(explode('/',$arrPeriodo['dtInicial']))),
				'dtFinalPer'    => implode('-',array_reverse(explode('/',$arrPeriodo['dtFinal']))),
			];

			// tratar o tempo descontando os dias de chefia
			$this->tratarInclusaoPeriodoChefia($cargaHoraria,$arrParams,$dadosChefia,$arrDiasAusencias);

			if ( $cargaHoraria > 0 ) {
				// tratar o tempo descontando as ausencias
				$this->tratarInclusaoPeriodoAusencias($cargaHoraria,$arrDiasAusencias,$arrParams);
				$strDatasAusenciasUtilizadas = !empty( $arrDiasAusencias )
					? MdUtlAdmPrmGrUsuCargaINT::montaDatasAusenciasBanco($arrDiasAusencias)
					: null;
			}

			if( $retornaPeriodo ) return $arrPeriodo;
		}

		private function validarStaFrequencia($staFrequencia){
			$diaSemana = InfraData::obterDescricaoDiaSemana(date("d/m/Y"));
			switch ($staFrequencia) {
				case 'D':
					$arrSemanaNaoPermitida = ['sábado','domingo'];
					return in_array($diaSemana,$arrSemanaNaoPermitida) ? false : true;
					break;

				case 'S':
					return $diaSemana == 'segunda-feira';
					break;

				case 'M':
					return date('d') == '01';
					break;

				default:
					return false;
					break;
			}
		}

		/*
		 * $arrParams = [idUsuario, siglaUsuario, cargaPadrao, fatorPresenca,  dtInicialPer, dtFinalPer]
		 */
		private function tratarInclusaoPeriodoAusencias(&$cargaHoraria,&$arrDiasAusencias,$arrParams){
			$arrObjIntegracao = ( new MdUtlAdmIntegracaoRN() )->obterConfigIntegracaoPorFuncionalidade(MdUtlAdmIntegracaoRN::$AUSENCIA);

			// verifica se o serviço esta cadastrado, ativo e Tipo de Integracao igual a REST
			if( !empty( $arrObjIntegracao ) && $arrObjIntegracao['integracao']->getStrTipoIntegracao() == 'RE'){

				$arrParamsAus = [
					'loginUsuario' => $arrParams['siglaUsuario'],
					'dataInicial'  => $arrParams['dtInicialPer'],
					'dataFinal'    => $arrParams['dtFinalPer'],
				];
				$arrParamsAus = ['parametros' => MdUtlAdmIntegracaoINT::montaParametrosEntrada( $arrObjIntegracao, $arrParamsAus )];
				$dadosAusencia = MdUtlAdmIntegracaoINT::executarConsultaREST( $arrObjIntegracao , $arrParamsAus['parametros'] );

				if ( !empty( $dadosAusencia ) ) {
					$arrIdentificador = MdUtlAdmIntegracaoINT::montaParametrosSaida($arrObjIntegracao['parametros-integracao']);
					foreach ( $dadosAusencia as $ausencia ) {
						$arrRangeDiasAusencia = MdUtlAdmPrmGrUsuCargaINT::geraRangeDias($ausencia->{$arrIdentificador['dataInicial']},$ausencia->{$arrIdentificador['dataFinal']});
						foreach ( $arrRangeDiasAusencia as $dtAusencia ) {
							if(
								strtotime($dtAusencia) >= strtotime($arrParams['dtInicialPer']) &&
								strtotime($dtAusencia) <= strtotime($arrParams['dtFinalPer']) &&
								!in_array($dtAusencia,$arrDiasAusencias)
							){
								$tmpParcial = (new MdUtlAdmPrmGrUsuCargaRN())->geraTempoCargaHoraria( $arrParams['fatorPresenca'], 1, $arrParams['cargaPadrao'] );
								$tmpParcial = $ausencia->{$arrIdentificador['meioExpediente']} == 'N' ? $tmpParcial : intval($tmpParcial / 2);
								$cargaHoraria -= $tmpParcial;
								array_push($arrDiasAusencias,$dtAusencia);
							}
						}
					}
				}
			} else { // usa a tabela de Feriados do SEI
				$arrRangeDiasPeriodo = MdUtlAdmPrmGrUsuCargaINT::geraRangeDias($arrParams['dtInicialPer'] , $arrParams['dtFinalPer']);

				foreach ( $arrRangeDiasPeriodo as $dia ) {
					$diaPT_BR = ( new DateTime($dia) )->format('d/m/Y');
					if( ! ( new MdUtlPrazoRN() )->verificaDiaUtil($diaPT_BR, $diaPT_BR, true ) ) {
						$tmpParcial = (new MdUtlAdmPrmGrUsuCargaRN())->geraTempoCargaHoraria( $arrParams['fatorPresenca'], 1, $arrParams['cargaPadrao'] );
						$cargaHoraria -= $tmpParcial;
						array_push($arrDiasAusencias,$dia);
					}
				}
			}
		}

		private function tratarInclusaoPeriodoChefia(&$cargaHoraria, $arrParams, $dadosChefia, &$arrDiasAusencias){
			if ( $dadosChefia ) {
				$objMdUtlAdmIntegDTO = ( new MdUtlAdmIntegracaoRN() )->obterConfigIntegracaoPorFuncionalidade(MdUtlAdmIntegracaoRN::$CHEFIA);
				$arrIdentificador    = MdUtlAdmIntegracaoINT::montaParametrosSaida($objMdUtlAdmIntegDTO['parametros-integracao']);
				foreach ($dadosChefia as $chefia) {
					if ($arrParams['siglaUsuario'] == $chefia->{$arrIdentificador['loginUsuario']}) {
						// Chefe Titular
						if (intval($chefia->{$arrIdentificador['tipoEmpregado']}) == 1) {
							$cargaHoraria = 0;
							break;
						} else { // Chefe Substituto
							$arrRangeDiasChefia = MdUtlAdmPrmGrUsuCargaINT::geraRangeDias($chefia->{$arrIdentificador['dataInicial']},$chefia->{$arrIdentificador['dataFinal']});
							foreach ($arrRangeDiasChefia as $diaChefia) {
								if (
									strtotime($diaChefia) >= strtotime($arrParams['dtInicialPer']) &&
									strtotime($diaChefia) <= strtotime($arrParams['dtFinalPer'])
								) {
									array_push($arrDiasAusencias, $diaChefia);
									$tmpParcial = (new MdUtlAdmPrmGrUsuCargaRN())->geraTempoCargaHoraria($arrParams['fatorPresenca'], 1, $arrParams['cargaPadrao']);
									$cargaHoraria -= $tmpParcial;
								}
							}
						}
					}
				}
			}
		}

		/**
		 * Funcao acionada pelo Agendamento
		 */
    protected function listarChefiaImediataControlado(){
	    try {
		    $this->initDadosDebug();

		    $numSeg = InfraUtil::verificarTempoProcessamento();
		    InfraDebug::getInstance()->gravar('ATUALIZANDO REGISTROS DE USUÁRIOS - CHEFIA IMEDIATA');

		    // EXECUTA A FUNCAO DE ATUALIZACAO DOS USUARIOS - CHEFIA IMEDIATA
		    $this->executaAtualizacaoChefiaImediata();

		    $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
		    InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: ' . $numSeg . ' s');
		    InfraDebug::getInstance()->gravar('FIM');

		    LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(), InfraLog::$INFORMACAO);
		    $this->limpaDadosDebug();

	    } catch ( Exception $e ){
		    $exception = $this->trataException($e);
		    LogSEI::getInstance()->gravar( $exception , InfraLog::$INFORMACAO );
		    $this->limpaDadosDebug();
		    throw new InfraException('Falha no agendamento listarChefiaImediata',$e);
	    }
    }

    private function executaAtualizacaoChefiaImediata(){
	    $strDefMap = 'MdUtlAgendamentoAutomaticoRN::listarChefiaImediata => Mapeamento de Integração \'Chefia Imediata\'';

	    // busca a integracao da Funcionalidade: listar chefia imediata
	    $arrObjIntegracao = (new MdUtlAdmIntegracaoRN())->obterConfigIntegracaoPorFuncionalidade(MdUtlAdmIntegracaoRN::$CHEFIA);

	    if ( empty($arrObjIntegracao) ) throw new Exception("$strDefMap não existe ou está inativa");

	    if( $arrObjIntegracao['integracao']->getStrTipoIntegracao() != 'RE' ) throw new Exception("$strDefMap aceita somente o Tipo de Integração REST");

	    $arrParams = ['loginUsuario' => ''];
	    $arrParams = ['parametros' => MdUtlAdmIntegracaoINT::montaParametrosEntrada( $arrObjIntegracao, $arrParams )];

	    $dadosChefia = MdUtlAdmIntegracaoINT::executarConsultaREST( $arrObjIntegracao , $arrParams['parametros'] );

	    if ( empty($dadosChefia) ) throw new Exception("Não foram encontrados registros relacionados a Chefia Imediata");

	    $arrIdentificador = MdUtlAdmIntegracaoINT::montaParametrosSaida($arrObjIntegracao['parametros-integracao']);

	    // retorna os tipos de controles com o ID da parametrizacao
	    $objUtlAdmTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
	    $objUtlAdmTpCtrlRN  = new MdUtlAdmTpCtrlDesempRN();

	    $objUtlAdmTpCtrlDTO->setStrSinAtivo('S');
	    $objUtlAdmTpCtrlDTO->setNumIdMdUtlAdmPrmGr(0,InfraDTO::$OPER_MAIOR);
	    #$objUtlAdmTpCtrlDTO->setNumIdMdUtlAdmPrmGr([43],InfraDTO::$OPER_IN); //teste
	    $objUtlAdmTpCtrlDTO->retNumIdMdUtlAdmPrmGr();

	    $arrObjsTpCtrlDTO = $objUtlAdmTpCtrlRN->listar( $objUtlAdmTpCtrlDTO );

	    if( empty( $arrObjsTpCtrlDTO ) ) throw new InfraException('Não foi encontrado nenhum Tipo de Controle de Desempenho Ativo.');

	    $objUtlAdmPrmGrUsuRN      = new MdUtlAdmPrmGrUsuRN();
	    $objUtlAdmPrmGrUsuCargaRN = new MdUtlAdmPrmGrUsuCargaRN();

	    foreach ( $arrObjsTpCtrlDTO as $objPrmGr ) {
		    // busca usuarios membros da parametrizacao atual
		    $arrUsuarios = $objUtlAdmPrmGrUsuRN->getDadosUsuarioMembro($objPrmGr->getNumIdMdUtlAdmPrmGr());

		    foreach ($arrUsuarios as $usuario) {
			    // retorna dados da carga horaria e ausencias por default
			    $cargaHoraria                = 0;
			    $strDatasAusenciasUtilizadas = null;
			    $arrPeriodos = $this->trataTempoMembroComAusenciasEChefia($usuario, $cargaHoraria, $strDatasAusenciasUtilizadas, true, $dadosChefia);

			    $params = [
				    'idPrmGrUsu' => $usuario['idPrmGrUsu'],
				    'periodoIni' => $arrPeriodos['dtInicial'],
				    'periodoFin' => $arrPeriodos['dtFinal']
			    ];

			    // retorna o ultimo registro ativo da carga horaria do membro atual
			    $objMdUtlPrmGrUsuCarga = $objUtlAdmPrmGrUsuCargaRN->buscaPeriodoParaAvaliacao($params);

			    if ( !is_null($objMdUtlPrmGrUsuCarga)) {
				    $bolEncontrou      = false;
				    $objDadosUsuChefia = null;
				    foreach ($dadosChefia as $usuarioChefiaImediata) {
					    if ( $usuario['siglaUsuario'] == $usuarioChefiaImediata->{$arrIdentificador['loginUsuario']} ) {
						    $objDadosUsuChefia = $usuarioChefiaImediata;
						    $bolEncontrou = true;
						    break;
					    }
				    }

				    if ($bolEncontrou) {
					    //atualiza registro da parametrizacao do usuario
					    $objUtlAdmPrmGrUsuRN->atualizarInfoChefiaImediata($objDadosUsuChefia, $usuario);

					    // se teve mudança para menos no tempo da carga cadastrada no periodo, desativa o atual e insere um novo
					    if ($cargaHoraria < $objMdUtlPrmGrUsuCarga->getNumCargaHoraria()) {
						    //desativa o registro atual
						    $objMdUtlPrmGrUsuCarga->setStrSinAtivo('N');
						    $objUtlAdmPrmGrUsuCargaRN->alterar($objMdUtlPrmGrUsuCarga);

						    //cadastra um novo registro
						    $this->insertSimplesCargaMembro($objMdUtlPrmGrUsuCarga, $cargaHoraria, $strDatasAusenciasUtilizadas);
					    }
				    } else {
					    $isAtualizado = false;
					    $objUtlAdmPrmGrUsuRN->atualizarInfoChefiaImediata(null, $usuario, $isAtualizado);
					    if ( $isAtualizado ) {

						    //desativa o registro atual
						    $objMdUtlPrmGrUsuCarga->setStrSinAtivo('N');
						    $objUtlAdmPrmGrUsuCargaRN->alterar($objMdUtlPrmGrUsuCarga);

						    //cadastra um novo registro
					    	$this->insertSimplesCargaMembro($objMdUtlPrmGrUsuCarga, $cargaHoraria, $strDatasAusenciasUtilizadas);
					    }
				    }
			    }
		    }
	    }
    }

    private function insertSimplesCargaMembro($objMdUtlPrmGrUsuCarga,$carga,$ausencias){
	    $objUtlAdmPrmGrUsuCargaRN = new MdUtlAdmPrmGrUsuCargaRN();

	    $objMdUtlPrmGrUsuCarga->setNumIdMdUtlAdmPrmGrUsuCarga(null);
	    $objMdUtlPrmGrUsuCarga->setNumCargaHoraria($carga);
	    $objMdUtlPrmGrUsuCarga->setStrDatasAusencias($ausencias);
	    $objMdUtlPrmGrUsuCarga->setStrSinAtivo('S');

	    $objUtlAdmPrmGrUsuCargaRN->cadastrar($objMdUtlPrmGrUsuCarga);
    }

		/**
		 * Funcao acionada pelo Agendamento
		 */
    protected function listarAusenciasRhControlado(){
	    try {
		    $this->initDadosDebug();

		    $numSeg = InfraUtil::verificarTempoProcessamento();
		    InfraDebug::getInstance()->gravar('ATUALIZANDO REGISTROS DE USUÁRIOS - AUSÊNCIAS');

		    $REF_PARAMETRO = 'mesesPassado';

		    $objAgendamentoDTO = new InfraAgendamentoTarefaDTO();
		    $objAgendamentoDTO->setStrComando('MdUtlAgendamentoAutomaticoRN::listarAusenciasRh');
		    $objAgendamentoDTO->retTodos();
		    $objAgendamentoDTO = ( new InfraAgendamentoTarefaRN() )->consultar( $objAgendamentoDTO );

		    if( empty( $objAgendamentoDTO->getStrParametro() ) ) throw new Exception('Não foi cadastrado dados sobre o campo Parâmetros.');

		    $arrStrParametros = explode(',' , $objAgendamentoDTO->getStrParametro() );

		    if( strpos( $arrStrParametros[0] , $REF_PARAMETRO.'=' ) === false ) throw new Exception('Não foi encontrado o Parâmetro:'. $REF_PARAMETRO .'=');

				$arrParam = explode( '=' , $arrStrParametros[0] );

				if( !array_key_exists( 1 , $arrParam ) ) throw new Exception('Não foi informado o valor do parâmetro: ' . $REF_PARAMETRO .'.' );

				if( empty( $arrParam[1] ) ) throw new Exception('O valor do parâmetro: "' . $REF_PARAMETRO .'" está vazio ou igual a Zero.');

		    if( !is_numeric( $arrParam[1] ) ) throw new Exception('O valor do parâmetro: ' . $REF_PARAMETRO .' deve ser um valor numérico.');

		    if( $arrParam[1] < 0 ) throw new Exception('O valor do parâmetro: ' . $REF_PARAMETRO .' deve ser um valor maior que Zero.');

				// apos validacoes anteriores, aciona o metodo que executara as atualizacoes das ausencias dos membros nos tipos de controle
		    $this->executaAtualizacaoAusencias( $arrParam );

		    $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
		    InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: ' . $numSeg . ' s');
		    InfraDebug::getInstance()->gravar('FIM');

		    LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(), InfraLog::$INFORMACAO);
		    $this->limpaDadosDebug();

	    } catch ( Exception $e ){
	    	$exception = $this->trataException($e);
		    LogSEI::getInstance()->gravar( $exception , InfraLog::$INFORMACAO );
		    $this->limpaDadosDebug();
		    throw new InfraException('Falha no agendamento listarAusenciasRh',$e);
	    }
    }

    private function executaAtualizacaoAusencias( $arrParam ){
	    $dti = date('Y-m-d' , strtotime( "- {$arrParam[1]} months") );
	    $dtf = date('Y-m-d');
	    $strDefMap = 'MdUtlAgendamentoAutomaticoRN::listarAusenciasRh => Mapeamento de Integração \'Ausências Servidores\'';

	    $arrObjIntegracao = ( new MdUtlAdmIntegracaoRN() )->obterConfigIntegracaoPorFuncionalidade(MdUtlAdmIntegracaoRN::$AUSENCIA);

	    if( empty( $arrObjIntegracao) ) throw new Exception("$strDefMap não existe ou está inativa");

	    if( $arrObjIntegracao['integracao']->getStrTipoIntegracao() != 'RE' ) throw new Exception("$strDefMap aceita somente o Tipo de Integração REST");

	    $arrParamsAus  = ['dataInicial' => $dti , 'dataFinal' => $dtf];
	    $arrParamsAus  = ['parametros' => MdUtlAdmIntegracaoINT::montaParametrosEntrada( $arrObjIntegracao, $arrParamsAus )];
	    $dadosAusencia = MdUtlAdmIntegracaoINT::executarConsultaREST( $arrObjIntegracao , $arrParamsAus['parametros'] );

	    if ( empty( $dadosAusencia ) ) throw new Exception("Não foram encontrados registros relacionados à Ausência de Servidores");

			$arrIdentificador = MdUtlAdmIntegracaoINT::montaParametrosSaida($arrObjIntegracao['parametros-integracao']);

	    $objMdUtlAdmPrmGrUsuCargaRN = new MdUtlAdmPrmGrUsuCargaRN();
	    $objMdUtlAdmPrmGrUsuRN      = new MdUtlAdmPrmGrUsuRN();

	    // loop em cada ausencia retornada pelo SARH, podendo ter o mesmo usuario mais de uma vez
	    foreach( $dadosAusencia as $k => $ausencia ) {
	    	// busca os tipos de ctrl/parametrizacao que o usuario faz parte
		    $objUsuarioDTO = new MdUtlAdmPrmGrUsuDTO();
		    $objUsuarioDTO->setStrSigla( $ausencia->{$arrIdentificador['loginUsuario']} );
		    #$objUsuarioDTO->setNumIdMdUtlAdmPrmGr([43,36],InfraDTO::$OPER_IN); //teste
		    $objUsuarioDTO->retStrSigla();
		    $objUsuarioDTO->retNumIdMdUtlAdmPrmGrUsu();
		    $objUsuarioDTO->retNumIdMdUtlAdmPrmGr();
		    $objUsuarioDTO->retNumIdUsuario();
		    $objUsuarioDTO->retStrStaTipoJornada();
		    $objUsuarioDTO->retNumFatorReducaoJornada();
		    $objUsuarioDTO->retNumCargaPadraoParametrizacao();
		    $objUsuarioDTO->retStrStaFrequenciaParametrizacao();

		    $arrObjUsuarioDTO = ( new MdUtlAdmPrmGrUsuRN() )->listar( $objUsuarioDTO );

		    $objUsuarioDTO = null;

		    if (!empty($arrObjUsuarioDTO)) {
		    	foreach( $arrObjUsuarioDTO as $objUsuarioDTO ) {

				    // retorna registros relacionados a parametrizacao do usuario x carga horaria
				    $objMdUtlAdmPrmGrUsuCargaDTO = new MdUtlAdmPrmGrUsuCargaDTO();
				    $objMdUtlAdmPrmGrUsuCargaDTO->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());
				    $objMdUtlAdmPrmGrUsuCargaDTO->setNumIdMdUtlAdmPrmGrUsu($objUsuarioDTO->getNumIdMdUtlAdmPrmGrUsu());
				    $objMdUtlAdmPrmGrUsuCargaDTO->setStrSinAtivo('S');
				    $objMdUtlAdmPrmGrUsuCargaDTO->setDtaPeriodoFinal(date('d/m/Y',strtotime($dti)),InfraDTO::$OPER_MAIOR_IGUAL);

				    $objMdUtlAdmPrmGrUsuCargaDTO->retNumIdMdUtlAdmPrmGrUsu();
				    $objMdUtlAdmPrmGrUsuCargaDTO->retTodos();

				    $arrUsuCargaDTO = $objMdUtlAdmPrmGrUsuCargaRN->listar($objMdUtlAdmPrmGrUsuCargaDTO);

				    if ( !empty($arrUsuCargaDTO) ) {

					    foreach ($arrUsuCargaDTO as $item) { // loop em cada periodo cadastrado do usuario

						    // calculo de qtos dias uteis o usuario terá no intervalo de seu periodo inicial/final
						    $qtdDiasUteis = ( new MdUtlPrazoRN() )->retornaQtdDiaUtil($item->getDtaPeriodoInicial(),$item->getDtaPeriodoFinal(),false,false);

						    $fatorPres = $objUsuarioDTO->getStrStaTipoJornada() == 'R'
							    ? $objUsuarioDTO->getNumFatorReducaoJornada()
							    : null;

						    $cargaTotal = $objMdUtlAdmPrmGrUsuCargaRN->geraTempoCargaHoraria( $fatorPres, $qtdDiasUteis, $objUsuarioDTO->getNumCargaPadraoParametrizacao() );

						    // gera os dias ja utilizados/salvos no banco
						    $arrDiasAusenciasUtilizados = MdUtlAdmPrmGrUsuCargaINT::criaDiasAusenciasUtilizados($item->getStrDatasAusencias());
						    $dtPerInicial               = implode('-',array_reverse(explode('/',$item->getDtaPeriodoInicial())));
						    $dtPerFinal                 = implode('-',array_reverse(explode('/',$item->getDtaPeriodoFinal())));
						    $isAlterarPeriodo           = false;
						    $arrDatasDeAusenciaLoop     = MdUtlAdmPrmGrUsuCargaINT::geraRangeDias(
						    	                              $ausencia->{$arrIdentificador['dataInicial']},
							                                  $ausencia->{$arrIdentificador['dataFinal']}
							                                );

								// para cada dia de ausencia, faz o controle se esta dentro do periodo
						    foreach($arrDatasDeAusenciaLoop as $dtRefAusencia){
							    if (strtotime($dtRefAusencia) >= strtotime($dtPerInicial) && strtotime($dtRefAusencia) <= strtotime($dtPerFinal) && !in_array($dtRefAusencia, $arrDiasAusenciasUtilizados) ) {
								    array_push($arrDiasAusenciasUtilizados, $dtRefAusencia);
								    $tmpParcial = $objMdUtlAdmPrmGrUsuCargaRN->geraTempoCargaHoraria( $fatorPres, 1, $objUsuarioDTO->getNumCargaPadraoParametrizacao() );
								    $tmpParcial = $ausencia->{$arrIdentificador['meioExpediente']} == 'N' ? $tmpParcial : intval($tmpParcial / 2);
								    $cargaTotal -= $tmpParcial;
								    $isAlterarPeriodo = true;
							    }
						    }

						    if(
						    	$isAlterarPeriodo &&
							    $item->getNumCargaHoraria() != 0 &&
							    $cargaTotal < $item->getNumCargaHoraria()
						    )
						    {
							    $cargaTotal                  = $cargaTotal < 0 ? 0 : $cargaTotal;
							    $strDatasAusenciasUtilizadas = MdUtlAdmPrmGrUsuCargaINT::montaDatasAusenciasBanco($arrDiasAusenciasUtilizados);

							    // altera a flag ativo como 'N'
							    $item->setStrSinAtivo('N');
							    $objMdUtlAdmPrmGrUsuCargaRN->alterar($item);

							    // cadastra um novo periodo com os novos dados
							    $item->setNumIdMdUtlAdmPrmGrUsuCarga(null);
							    $item->setStrSinAtivo('S');
							    $item->setNumCargaHoraria($cargaTotal);
							    $item->setStrDatasAusencias($strDatasAusenciasUtilizadas);
							    $objMdUtlAdmPrmGrUsuCargaRN->cadastrar($item);
						    }
					    }
				    }
			    }
		    }
	    }
    }

    private function trataException( $e ){
	    $strErro = $e->getMessage() . "\n";
	    $strErro .= "====================================================== \n\n";
	    return $strErro;
		}

		private function initDadosDebug(){
      ini_set('max_execution_time', '0');
	    ini_set('memory_limit', '1024M');

	    InfraDebug::getInstance()->setBolLigado(true);
	    InfraDebug::getInstance()->setBolDebugInfra(false);
	    InfraDebug::getInstance()->setBolEcho(false);
	    InfraDebug::getInstance()->limpar();
    }

		private function limpaDadosDebug(){
			InfraDebug::getInstance()->setBolLigado(false);
			InfraDebug::getInstance()->setBolDebugInfra(false);
			InfraDebug::getInstance()->setBolEcho(false);
			InfraDebug::getInstance()->limpar();
		}
}
