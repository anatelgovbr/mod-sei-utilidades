<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlContestacaoRN extends InfraRN
{

    public static $PENDENTE_RESPOSTA = 'P';
    public static $STR_PENDENTE_RESPOSTA = 'Pendente de Resposta do Gestor';

    public static $APROVADA = 'A';
    public static $STR_APROVADA = 'Aprovada';

    public static $REPROVADA = 'R';
    public static $STR_REPROVADA = 'Reprovada';

    public static $CANCELADA = 'C';
    public static $STR_CANCELADA = 'Cancelada';

    public static $SOLICITACAO = 1;
    public static $STR_SOLICITACAO = 'Solicitação';

    public static $ALTERACAO = 2;
    public static $STR_ALTERACAO = 'Alteração';

    public static $CANCELAMENTO = 3;
    public static $STR_CANCELAMENTO = 'Cancelamento';

    public static $ATIVO = 'S';
    public static $INATIVO = 'N';

    /*Variáveis para o agendamento de constestação*/
    public static $SIN_REPROVACAO = 'SinReprovacao';
    public static $STR_SIM_REPROVACAO_TACITA = 'S';
    public static $STR_NAO_REPROVACAO_TACITA = 'N';
    public static $DIAS_UTEIS = 'DiasUteis';
    /*--------------------------------------------*/

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    private function validarStrStaSolicitacao(MdUtlContestacaoDTO $objMdUtlContestacaoDTO, InfraException $objInfraException){
        if (InfraString::isBolVazia($objMdUtlContestacaoDTO->getNumIdMdUtlAdmJustContest())){
            $objInfraException->adicionarValidacao('Informe a Justificativa de Contestação.');
        }
    }

    private function validarStrInformacoesComplementares(MdUtlContestacaoDTO $objMdUtlContestacaoDTO, InfraException $objInfraException){
        if (InfraString::isBolVazia($objMdUtlContestacaoDTO->getStrInformacoesComplementares())){
            $objInfraException->adicionarValidacao('Preencha a Informação Complementar da Contestação.');
        }
    }

    public function cadastrarControlado(MdUtlContestacaoDTO $objMdUtlContestacaoDTO) {
        try {

            //Valida Permissão
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_contest_revisao_cadastrar', __METHOD__, $objMdUtlContestacaoDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarStrStaSolicitacao($objMdUtlContestacaoDTO, $objInfraException);
            $objInfraException->lancarValidacoes();

            $this->validarStrInformacoesComplementares($objMdUtlContestacaoDTO, $objInfraException);
            $objInfraException->lancarValidacoes();

            $objMdUtlContestacaoBD = new MdUtlContestacaoBD($this->getObjInfraIBanco());
            $ret = $objMdUtlContestacaoBD->cadastrar($objMdUtlContestacaoDTO);

            return $ret;

        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando Justificativa de Contestação', $e);
        }
    }

    public function alterarControlado(MdUtlContestacaoDTO $objMdUtlContestacaoDTO) {
        try {

            //Valida Permissão
            SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_contest_revisao_cadastrar', __METHOD__, $objMdUtlContestacaoDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarStrStaSolicitacao($objMdUtlContestacaoDTO, $objInfraException);
            $this->validarStrInformacoesComplementares($objMdUtlContestacaoDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objMdUtlContestacaoBD = new MdUtlContestacaoBD($this->getObjInfraIBanco());
            $ret = $objMdUtlContestacaoBD->alterar($objMdUtlContestacaoDTO);

        } catch (Exception $e) {
            throw new InfraException('Erro alterando Justificativa de Contestação', $e);
        }
    }

    public function consultarConectado(MdUtlContestacaoDTO $objMdUtlContestacaoDTO) {
        try {

            //Valida Permissao
            SessaoSEI::getInstance()->validarPermissao('md_utl_contest_revisao_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdUtlContestacaoBD = new MdUtlContestacaoBD($this->getObjInfraIBanco());
            $ret = $objMdUtlContestacaoBD->consultar($objMdUtlContestacaoDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando Justificativa de Contestação.', $e);
        }
    }

    protected function desativarControlado($arrObjMdUtlContestacaoDTO){
        try {

            //Valida Permissao

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdUtlContestacaoBD = new MdUtlContestacaoBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdUtlContestacaoDTO);$i++){
                $objMdUtlContestacaoBD->desativar($arrObjMdUtlContestacaoDTO[$i]);
            }

            //Auditoria

        }catch(Exception $e){
            throw new InfraException('Erro desativando Justificativa de Dilação de Contestação.',$e);
        }
    }

    protected function reativarControlado($arrObjMdUtlContestacaoDTO){
        try {

            //Valida Permissao

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objMdUtlContestacaoBD = new MdUtlContestacaoBD($this->getObjInfraIBanco());
            for($i=0;$i<count($arrObjMdUtlContestacaoDTO);$i++){
                $objMdUtlContestacaoBD->reativar($arrObjMdUtlContestacaoDTO[$i]);
            }

            //Auditoria

        }catch(Exception $e){
            throw new InfraException('Erro reativando Justificativa de Dilação de Contestação.',$e);
        }
    }

    public function solicitarContestacaoControlado($arrParams) {

        //Cadastro da solicitação de Conntestação;
        $objDTO  = $arrParams[0];
        $objDTO  = $this->cadastrar($objDTO);

        //Controle do Fluxo de Atendimento;
        $objControleDTO         = $arrParams[1];
        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $objHistoricoRN         = new MdUtlHistControleDsmpRN();

//        $isAlterar = array_key_exists(2, $arrParams) ? $arrParams[2] : false;

        //Detalhe Fluxo Atendimento; //fazer a funcionalidade
        $strDetalheFluxoAtend   = $arrParams[2];

        if($strDetalheFluxoAtend == MdUtlContestacaoRN::$SOLICITACAO) {
            $detalheContestacao = MdUtlContestacaoRN::$STR_SOLICITACAO;
            $acaoEmail = 'incluída';
            $idContestacao = $objDTO->getNumIdMdUtlContestRevisao();
        } elseif ($strDetalheFluxoAtend == MdUtlContestacaoRN::$ALTERACAO) {
            $detalheContestacao = MdUtlContestacaoRN::$STR_ALTERACAO;
            $acaoEmail = 'alterada';
            $idContestacao = $objDTO->getNumIdMdUtlContestRevisao();
        } else {
            $detalheContestacao = MdUtlContestacaoRN::$STR_CANCELAMENTO;
            $acaoEmail = 'cancelada';
            $idContestacao = null;
        }

        $idProcedimento  = $objControleDTO->getDblIdProcedimento();
        $idFila          = $objControleDTO->getNumIdFila();
        $idTpCtrl        = $objControleDTO->getNumIdMdUtlAdmTpCtrlDesemp();
        $strNovoStatus   = $objControleDTO->getStrStaAtendimentoDsmp();
        $idTriagem       = $objControleDTO->getNumIdMdUtlTriagem();
        $idAnalise       = $objControleDTO->getNumIdMdUtlAnalise();
        $idRevisao       = $objControleDTO->getNumIdMdUtlRevisao();
        $tempoExecucao      = $objControleDTO->getNumTempoExecucao();
        $idUsuarioDistr  = $objControleDTO->getNumIdUsuarioDistribuicao();
        $strDetalheContest = $detalheContestacao;
        $arrIds          = array($idProcedimento);
        $arrObjsAtuais   = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimento($arrIds);
        $arrRetorno      = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais ,$arrIds, 'N','N'));
        $dthPrazo        = $arrRetorno[$idProcedimento]['DTH_PRAZO_TAREFA'];

        $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

        $strTipoAcao = MdUtlControleDsmpRN::$STR_TIPO_CONTESTACAO_REVISAO;

        //Cadastrando para essa fila, e esse procedimento e unidade o novo status
        $arrParams = array();
        $arrParams['dblIdProcedimento'] = $idProcedimento;
        $arrParams['intIdFila'] = $idFila;
        $arrParams['intIdTpCtrl'] = $idTpCtrl;
        $arrParams['strStatus'] = $strNovoStatus;
        $arrParams['intTempoExecucao'] = $tempoExecucao;
        $arrParams['idUsuarioDistrib'] = $idUsuarioDistr;
        $arrParams['idTriagem'] = $idTriagem;
        $arrParams['idAnalise'] = $idAnalise;
        $arrParams['idRevisao'] = $idRevisao;
        $arrParams['strDetalhe'] = $strDetalheContest;
        $arrParams['tipoAcao'] = $strTipoAcao;
        $arrParams['dtPrazo'] = $dthPrazo;
        $arrParams['idContestacao'] = $idContestacao;
        $objControleDesempenhoNovoDTO = $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso($arrParams);

        $tipoControleDsmpRN = new MdUtlAdmTpCtrlDesempRN();
        $tipoControleDsmpDTO = new MdUtlAdmTpCtrlDesempDTO();
        $tipoControleDsmpDTO->retStrNome();
        $tipoControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);

        $objTipoControleDsmpDTO = $tipoControleDsmpRN->listar($tipoControleDsmpDTO);

        $arrDadosEmail = array(
            'acao_email' => $acaoEmail,
            'tipo' => 'Contestação de Avaliação',
            'id_tipo' => $idTpCtrl,
            'protocolo_formatado' => $objControleDTO->getStrProtocoloProcedimentoFormatado(),
            'nome_controle' => $objTipoControleDsmpDTO[0]->getStrNome(),
        );
        $this->getArrGestoresTpControle($arrDadosEmail);
        return $objControleDesempenhoNovoDTO;
    }

    public function getJustificativasContestacaoConectado($idTipoControle) {
        $objMdUtlAdmJustContestDTO = new MdUtlAdmJustContestDTO();
        $objMdUtlAdmJustContesRN = new MdUtlAdmJustContestRN();

        if($_GET['acao'] == 'md_utl_contest_revisao_consultar') {
            $objMdUtlAdmJustContestDTO->setBolExclusaoLogica(false);
        }

        $objMdUtlAdmJustContestDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
        $objMdUtlAdmJustContestDTO->retStrSinAtivo();
        $objMdUtlAdmJustContestDTO->retNumIdMdUtlAdmTpCtrlDesemp();
        $objMdUtlAdmJustContestDTO->retStrNome();
        $objMdUtlAdmJustContestDTO->retStrDescricao();
        $objMdUtlAdmJustContestDTO->retNumIdMdUtlAdmJustContest();
        $objMdUtlAdmJustContestDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
        $arrObjMdUtlAdmJustContest = $objMdUtlAdmJustContesRN->listar($objMdUtlAdmJustContestDTO);

        return $arrObjMdUtlAdmJustContest;
    }

    protected function listarConectado(MdUtlContestacaoDTO $objMdUtlContestacaoDTO) {
        try {

            $objMdUtlContestacaoBD = new MdUtlContestacaoBD($this->getObjInfraIBanco());
            $ret = $objMdUtlContestacaoBD->listar($objMdUtlContestacaoDTO);

            //Auditoria

            return $ret;

        }catch(Exception $e){
            throw new InfraException('Erro listando Contestacao de Avaliação.',$e);
        }
    }
    
    protected function contarConectado(MdUtlContestacaoDTO $objMdUtlContestacaoDTO){
        try {

            SessaoSEI::getInstance()->validarPermissao('md_utl_adm_just_contest_listar');

            $objMdUtlContestacaoBD = new MdUtlContestacaoBD($this->getObjInfraIBanco());
            $ret = $objMdUtlContestacaoBD->contar($objMdUtlContestacaoDTO);

            return $ret;
        }catch(Exception $e){
            throw new InfraException('Erro contando .',$e);
        }
    }

    public function getContestacaoPendente($isAgendamento = false)
    {
        $arrPrmContest = [];
        $idsTpControle = array();
        $data = InfraData::getStrDataAtual();
        $data .= ' 00:00:00';

        $objPrmContestDTO = new MdUtlAdmPrmContestDTO();
        $objPrmContestDTO->retTodos();
        $objPrmContestDTO->setStrSinReprovacaoAutomatica(self::$STR_SIM_REPROVACAO_TACITA);
        $objPrmContestRN = new MdUtlAdmPrmContestRN();

        $count = $objPrmContestRN->contar($objPrmContestDTO);
        if($count > 0) {
            $arrObjPrmContestDTO = $objPrmContestRN->listar($objPrmContestDTO);

            foreach ($arrObjPrmContestDTO as $obj) {
                if (!array_key_exists($obj->getNumIdMdUtlAdmTpCtrlDesemp(), $idsTpControle)) {
                    $idsTpControle[] = $obj->getNumIdMdUtlAdmTpCtrlDesemp();
                }

                $arrPrmContest[$obj->getNumIdMdUtlAdmTpCtrlDesemp()] = array(
                    self::$SIN_REPROVACAO => $obj->getStrSinReprovacaoAutomatica(),
                    self::$DIAS_UTEIS => $obj->getNumQtdDiasUteisReprovacao(),
                );
            }

            if (count($idsTpControle) > 0) {
                $objCtrlDsmpDTO = new MdUtlControleDsmpDTO();

                $objCtrlDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idsTpControle, InfraDTO::$OPER_IN);
                $objCtrlDsmpDTO->setContestacaoFK(InfraDTO::$TIPO_FK_OBRIGATORIA);
                $objCtrlDsmpDTO->setStrStaSolicitacaoContestacao(self::$PENDENTE_RESPOSTA);
                $objCtrlDsmpDTO->retTodos();
                $objCtrlDsmpDTO->retStrProtocoloProcedimentoFormatado();
                $objCtrlDsmpDTO->retNumIdContato();
                $objCtrlDsmpDTO->retStrEmail();

                $objCtrlDsmpRN = new MdUtlControleDsmpRN();
                $numRegistrosCtrlDsmp = $objCtrlDsmpRN->contar($objCtrlDsmpDTO);

                $objMdUtlGestaoAjustPrazoRN = new MdUtlGestaoAjustPrazoRN();
                $objPrazoRN = new MdUtlPrazoRN();

                if ($numRegistrosCtrlDsmp > 0) {

                    $objUsuarioRN = new MdUtlUsuarioRN();
                    $objUsuarioDTO = new UsuarioDTO();
                    $objUsuarioDTO = $objUsuarioRN->getObjUsuarioUtilidades();

                    $arrObjCtrlDsmpDTO = $objCtrlDsmpRN->listar($objCtrlDsmpDTO);

                    foreach ($arrObjCtrlDsmpDTO as $objCtrlDsmpDTO) {
                        $idUnidade    = $objCtrlDsmpDTO->getNumIdUnidade();
                        $idTpCtrlDsmp = $objCtrlDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp();

                        if(is_null(SessaoSEI::getInstance()->getNumIdUsuario() )){
                            SessaoSEI::getInstance(false)->simularLogin(null, null, $objUsuarioDTO->getNumIdUsuario(), $idUnidade);
                        }

                        if ($arrPrmContest[$idTpCtrlDsmp][self::$SIN_REPROVACAO] == self::$STR_SIM_REPROVACAO_TACITA) {
                            $dthAtual  = InfraData::getStrDataAtual();
                            $diaPrmCont = $arrPrmContest[$idTpCtrlDsmp][self::$DIAS_UTEIS];
                            $dthSolicitacao = $objCtrlDsmpDTO->getDthAtual();
                            $dthPrazoReprovacao = $objPrazoRN->somarDiaUtil($diaPrmCont, $dthSolicitacao);
                            $checkData = $dthAtual > $dthPrazoReprovacao;

                           if ($checkData) {
                                $objMdUtlGestaoAjustPrazoRN->reprovarSolicitacao($objCtrlDsmpDTO);
                            }
                        }
                    }


                }
            }
        }
    }

    protected function aprovarContestacaoConectado($arrParams){
            $idContestacao = array_key_exists(0, $arrParams) ? $arrParams[0] : null;
            $idRevisao     = array_key_exists(1, $arrParams) ? $arrParams[1] : null;
            $objMdUtlContestacaoDTO = new MdUtlContestacaoDTO();
            $objMdUtlContestacaoDTO->setNumIdMdUtlContestRevisao($idContestacao);
            $objMdUtlContestacaoDTO->setNumMaxRegistrosRetorno(1);
            $objMdUtlContestacaoDTO->retTodos();
            $objMdUtlContestacaoDTO = $this->consultar($objMdUtlContestacaoDTO);

            $objMdUtlContestacaoDTO->setNumIdMdUtlRevisao($idRevisao);
            $objMdUtlContestacaoDTO->setStrStaSolicitacao(self::$APROVADA);
            $this->alterar($objMdUtlContestacaoDTO);
    }

    /**
     * Recupera os gestores do tipo de controle e envia e-mail
     * @param $idTpCtrl
     * @param $acao
     */
    public function getArrGestoresTpControle($arrDadosEmail)
    {
        $idUnidadeLogado = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
        $strTipoAcao = $arrDadosEmail['acao_email'];
        $strItemMantido = $arrDadosEmail['tipo'];
        $idTpCtrl = $arrDadosEmail['id_tipo'];
        $strNomeControle = $arrDadosEmail['nome_controle'];
        $strProtocolo = $arrDadosEmail['protocolo_formatado'];
        $strNomeUsuario = SessaoSEI::getInstance()->getStrNomeUsuario();
        $strSiglaUnidade = SessaoSEI::getInstance()->getStrSiglaUnidadeAtual();

        $objTipoControleUtilidadesUsuarioDTO = new MdUtlAdmRelTpCtrlDesempUsuDTO();
        $objTipoControleUtilidadesUsuarioDTO->retTodos();
        $objTipoControleUtilidadesUsuarioDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
        $objTipoControleUtilidadesUsuarioDTO->setOrdStrNomeUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objTipoControleUtilidadesUsuarioDTO->retStrNomeUsuario();
        $objTipoControleUtilidadesUsuarioDTO->retStrSiglaUsuario();

        $objRelTipoControleUtilidadesUsuarioRN = new MdUtlAdmRelTpCtrlDesempUsuRN();
        $arrGestoresDTO = $objRelTipoControleUtilidadesUsuarioRN->listar($objTipoControleUtilidadesUsuarioDTO);

        foreach ($arrGestoresDTO as $usuario) {

            $isGestor = $this->verificaUnidadeGestor($usuario, $idUnidadeLogado);

            if ($isGestor) {
                $objUsuarioDTO = new UsuarioDTO();
                $objUsuarioRN = new UsuarioRN();
                $objUsuarioDTO->setNumIdUsuario($usuario->getNumIdUsuario());
                $objUsuarioDTO->retNumIdContato();
                $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

                $objContatoDTO = new ContatoDTO();
                $objContatoRN = new ContatoRN();
                $objContatoDTO->retStrEmail();
                $objContatoDTO->setNumIdContato($objUsuarioDTO->getNumIdContato());
                $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);

                if ($objContatoDTO->getStrEmail()) {
                    $this->emailContestacao($strTipoAcao, $strItemMantido, $strNomeControle, $strNomeUsuario, $strProtocolo, $strSiglaUnidade, $objContatoDTO->getStrEmail());
                }
            }
        }
    }

    public function verificaUnidadeGestor($usuario, $idUnidadeSolicitante) {
        $objInfraSip = new InfraSip(SessaoSEI::getInstance());
        $arrPerfisSip = $objInfraSip->carregarPerfis(SessaoSEI::getInstance()->getNumIdSistema(), $usuario->getNumIdUsuario(), $idUnidadeSolicitante);

        for ($i = 0; $i < count($arrPerfisSip); $i++) {
            if ($arrPerfisSip[$i][1] == 'Gestor de Controle de Desempenho') {
                return true;
            }
        }
    }

    public function emailContestacao($strTipoAcao, $strItemMantido, $strNomeControle, $strNomeUsuario, $strProtocolo, $strSiglaUnidade, $email) {
        $strAssunto = '[Controle de Desempenho] @acao@ a @item mantido@';
        $strAssunto = str_replace('@acao@', ucfirst($strTipoAcao), $strAssunto);
        $strAssunto = str_replace('@item mantido@', $strItemMantido, $strAssunto);

        $strConteudo = '
            :: Este é um e-mail automático ::
            
            Uma ' . $strItemMantido . ' foi ' . $strTipoAcao . ' no Controle de Desempenho '.$strNomeControle.' do SEI por '.$strNomeUsuario.' sobre o Processo nº '.$strProtocolo.' e no momento está pendente de resposta.
            
            Assim, o Gestor do citado Controle de Desempenho na Unidade '.$strSiglaUnidade.' deve acessar o menu Constrole de Desempenho > Gestão de Solicitações para mais informações.';

        //Enviar Email
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

        //Monta Email
        $strDe = SessaoSEI::getInstance()->getStrSiglaSistema();
        $strDe .= '<' . $objInfraParametro->getValor('SEI_EMAIL_SISTEMA') . '>';
        $strPara = $email;

        $objEmailDTO = new EmailDTO();
        $objEmailDTO->setStrDe($strDe);
        $objEmailDTO->setStrPara($strPara);
        $objEmailDTO->setStrAssunto($strAssunto);
        $objEmailDTO->setStrMensagem($strConteudo);

        EmailRN::processar(array($objEmailDTO));
    }
}
