<?

require_once dirname(__FILE__) . '/../../../SEI.php';

class MdUtlGestaoAjustPrazoRN extends InfraRN
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    protected function buscarSolicitacoesAjustePrazoConectado($arrDados)
    {
        $idTipoControle = array_key_exists(0, $arrDados) ? $arrDados[0] : null;
        $arrPostDados = array_key_exists(1, $arrDados) ? $arrDados[1] : null;


        if (!is_null($arrPostDados)) {
            $isStrProcesso = array_key_exists('txtProcessoMdGestao', $arrPostDados) && $arrPostDados['txtProcessoMdGestao'] != '';
            $isStrStatusProcesso = array_key_exists('selStatusProcMdGestao', $arrPostDados) && $arrPostDados['selStatusProcMdGestao'] != '';
            $isIdUsuarioSolicitante = array_key_exists('selServidorMdGestao', $arrPostDados) && $arrPostDados['selServidorMdGestao'] != '';
        }


        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objRN = new MdUtlControleDsmpRN();


        if ($isStrProcesso) {
            $objMdUtlControleDsmpDTO->setStrProtocoloProcedimentoFormatado('%' . trim($arrPostDados['txtProcessoMdGestao'] . '%'), InfraDTO::$OPER_LIKE);
        }

        if ($isStrStatusProcesso) {


            $objMdUtlControleDsmpDTO->setStrStaAtendimentoDsmp($arrPostDados['selStatusProcMdGestao']);
        } else {
            $arrStatus = array(MdUtlControleDsmpRN::$INTERROMPIDO, MdUtlControleDsmpRN::$SUSPENSO);
            $objMdUtlControleDsmpDTO->setStrStaAtendimentoDsmp($arrStatus, InfraDTO::$OPER_NOT_IN);
        }

        if ($isIdUsuarioSolicitante) {
            $objMdUtlControleDsmpDTO->setNumIdUsuarioDistribuicao($arrPostDados['selServidorMdGestao']);
        }


        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        if( is_array($idTipoControle)) 
            $objMdUtlControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle,InfraDTO::$OPER_IN);
        else
            $objMdUtlControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);

        $objMdUtlControleDsmpDTO->setNumIdMdUtlAjustePrazo(null, InfraDTO::$OPER_DIFERENTE);
        $objMdUtlControleDsmpDTO->setStrStaSolicitacaoAjustePrazo(MdUtlAjustePrazoRN::$PENDENTE_RESPOSTA);
        $objMdUtlControleDsmpDTO->setOrdDthPrazoInicialAjustePrazo(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdUtlControleDsmpDTO->retTodos();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlAjustePrazo();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlAdmJustPrazo();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
        $objMdUtlControleDsmpDTO->retDblIdProcedimento();
        $objMdUtlControleDsmpDTO->retStrNomeTipoProcesso();
        $objMdUtlControleDsmpDTO->retStrSiglaUsuarioDistribuicao();
        $objMdUtlControleDsmpDTO->retStrNomeUsuarioDistribuicao();
        $objMdUtlControleDsmpDTO->retNumIdUsuarioDistribuicao();
        $objMdUtlControleDsmpDTO->retStrProtocoloProcedimentoFormatado();
        $objMdUtlControleDsmpDTO->retStrSinAtivoAjustePrazo();
        $objMdUtlControleDsmpDTO->retStrStaSolicitacaoAjustePrazo();
        $objMdUtlControleDsmpDTO->retStrStaTipoSolicitacaoAjustePrazo();
        $objMdUtlControleDsmpDTO->retDthPrazoInicialAjustePrazo();
        $objMdUtlControleDsmpDTO->retDthPrazoSolicitacaoAjustePrazo();
        $objMdUtlControleDsmpDTO->retStrNomeJustificativa();
        $objMdUtlControleDsmpDTO->retDthAtual();
        $objMdUtlControleDsmpDTO->retStrObservacao();
        $objMdUtlControleDsmpDTO->retNumIdContato();
        $objMdUtlControleDsmpDTO->retStrNomeTpControle();
        $count = $objRN->contar($objMdUtlControleDsmpDTO);
        if ($count > 0) {
            $objMdUtlControleDsmpDTO = $objRN->listar($objMdUtlControleDsmpDTO);
            return $objMdUtlControleDsmpDTO;
        }

        return null;
    }

    protected function aprovarSolicitacaoControlado($objControleDsmpDTO)
    {

        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $objHistoricoRN = new MdUtlHistControleDsmpRN();
        $objMdUtlAjustePrazoRN = new MdUtlAjustePrazoRN();

        if (!is_null($objControleDsmpDTO->getNumIdMdUtlAjustePrazo())) {
            $objAjustPrazoDTO = new MdUtlAjustePrazoDTO();
            $objAjustPrazoDTO->setNumIdMdUtlAjustePrazo($objControleDsmpDTO->getNumIdMdUtlAjustePrazo());
            $objAjustPrazoDTO->retTodos();
            $objAjustPrazoDTO->setNumMaxRegistrosRetorno(1);
            $objAjustPrazoDTO = $objMdUtlAjustePrazoRN->consultar($objAjustPrazoDTO);
        }

        $isAprovado = true;
        $strProcesso = $objControleDsmpDTO->getStrProtocoloProcedimentoFormatado();
        $idProcedimento = $objControleDsmpDTO->getDblIdProcedimento();
        $idFila = $objControleDsmpDTO->getNumIdMdUtlAdmFila();
        $idTpCtrl = $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp();
        $strNovoStatus = $objControleDsmpDTO->getStrStaAtendimentoDsmp();
        $strStatus = $objControleDsmpDTO->getStrStaAtendimentoDsmp();
        $idTriagem = $objControleDsmpDTO->getNumIdMdUtlTriagem();
        $idAnalise = $objControleDsmpDTO->getNumIdMdUtlAnalise();
        $idRevisao = $objControleDsmpDTO->getNumIdMdUtlRevisao();
        $tempoExecucao = $objControleDsmpDTO->getNumTempoExecucao();
        $idUsuarioDistr = $objControleDsmpDTO->getNumIdUsuarioDistribuicao();
        $idAjustePrazo = $objControleDsmpDTO->getNumIdMdUtlAjustePrazo(); /*Adicionar apenas em Suspensão ou Interrupção*/
        $idContato = $objControleDsmpDTO->getNumIdContato();
        $strTipoAcao = MdUtlControleDsmpRN::$STR_TIPO_ACAO_APV_AJUSTE_PRAZO;
        $tipoSolicitacao = $objAjustPrazoDTO->getStrStaTipoSolicitacao();
        $idUnidade = $objControleDsmpDTO->getNumIdUnidade();

        $objContatoRN = new ContatoRN();
        $objContatoDTO = new ContatoDTO();
        $objContatoDTO->setNumIdContato($idContato);
        $objContatoDTO->retTodos();
        $objContatoDTO = $objContatoRN->listarRN0325($objContatoDTO);

        $strNome = $objContatoDTO[0]->getStrNome();
        $strEmailSolicitante = $objContatoDTO[0]->getStrEmail();

        /*Alterar status para aprovada*/
        if ($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_DILACAO) {
            $strDetalheAjust = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_DILACAO;
            $dthPrazo = $objAjustPrazoDTO->getDthPrazoSolicitacao();
            $objAjustPrazoDTO->setStrStaSolicitacao(MdUtlAjustePrazoRN::$APROVADA);
            /*Se Dilação alterar o prazo da tarefa em analise ou revisao para o prazo informado*/
        }

        /*Se Suspensão alterar status para Suspenso*/
        if ($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_SUSPENSAO) {
            $strDetalheAjust = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_SUSPENSAO;
            $strNovoStatus = MdUtlControleDsmpRN::$SUSPENSO;
            $dthPrazo = $objAjustPrazoDTO->getDthPrazoSolicitacao();
            $objAjustPrazoDTO->setStrStaSolicitacao(MdUtlAjustePrazoRN::$APROVADA);
        }

        /*Se Interrupção alterar status para Interrompido*/
        if ($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_INTERRUPCAO) {
            $strDetalheAjust = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_INTERRUPCAO;
            $strNovoStatus = MdUtlControleDsmpRN::$INTERROMPIDO;
            $dthPrazo = $objAjustPrazoDTO->getDthPrazoSolicitacao();
            $objAjustPrazoDTO->setStrStaSolicitacao(MdUtlAjustePrazoRN::$APROVADA);
        }
        /*Enviar e-mail para solicitante*/
        if ($strEmailSolicitante != '') {
            $arrDados = array($strNome, $strEmailSolicitante, $strProcesso, $strNovoStatus, $isAprovado, null, null, $strStatus);
            $this->emailRespostaSolicitacao($arrDados);
        }

        $arrIds = array($idProcedimento);

        $arrObjsAtuais = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimentoPorUnidade(array($arrIds, $idUnidade));
        $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, $arrIds, 'N', 'N', 'N', $idUnidade));
        $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

        $objMdUtlAjustePrazoRN->alterar($objAjustPrazoDTO);
        $objMdUtlAjustePrazoRN->desativar(array($objAjustPrazoDTO));


        //Cadastrando para essa fila, e esse procedimento e unidade o novo status
        if ($objAjustPrazoDTO->getStrStaTipoSolicitacao() == MdUtlControleDsmpRN::$TP_SOLICITACAO_DILACAO) {
            $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso(array($idProcedimento, $idFila, $idTpCtrl, $strNovoStatus, $idUnidade, $tempoExecucao, $idUsuarioDistr, $idTriagem, $idAnalise, $idRevisao, $strDetalheAjust, $strTipoAcao, null, null, $dthPrazo));
        } else {
            $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso(array($idProcedimento, $idFila, $idTpCtrl, $strNovoStatus, $idUnidade, $tempoExecucao, $idUsuarioDistr, $idTriagem, $idAnalise, $idRevisao, $strDetalheAjust, $strTipoAcao, null, $idAjustePrazo, $dthPrazo));
        }
    }

    protected function reprovarSolicitacaoControlado($objControleDsmpDTO)
    {

        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $objHistoricoRN = new MdUtlHistControleDsmpRN();
        $objMdUtlAjustePrazoRN = new MdUtlAjustePrazoRN();
        $objMdUtlContestacaoRN = new MdUtlContestacaoRN();
        $isContestacao = false;
        $isAjustePrazo = false;

        if (!is_null($objControleDsmpDTO->getNumIdMdUtlAjustePrazo())) {
            $isAjustePrazo = true;
            $objAjustPrazoDTO = new MdUtlAjustePrazoDTO();
            $objAjustPrazoDTO->setNumIdMdUtlAjustePrazo($objControleDsmpDTO->getNumIdMdUtlAjustePrazo());
            $objAjustPrazoDTO->retTodos();
            $objAjustPrazoDTO->setNumMaxRegistrosRetorno(1);
            $objAjustPrazoDTO = $objMdUtlAjustePrazoRN->consultar($objAjustPrazoDTO);
        }

        if (!is_null($objControleDsmpDTO->getNumIdMdUtlContestRevisao())) {
            $isContestacao = true;
            $objContestacaoDTO = new MdUtlContestacaoDTO();
            $objContestacaoDTO->setNumIdMdUtlContestRevisao($objControleDsmpDTO->getNumIdMdUtlContestRevisao());
            $objContestacaoDTO->retTodos();
            $objContestacaoDTO->setNumMaxRegistrosRetorno(1);
            $objContestacaoDTO = $objMdUtlContestacaoRN->consultar($objContestacaoDTO);
        }

        $strProcesso = $objControleDsmpDTO->getStrProtocoloProcedimentoFormatado();
        $idProcedimento = $objControleDsmpDTO->getDblIdProcedimento();
        $idFila = $objControleDsmpDTO->getNumIdMdUtlAdmFila();
        $idTpCtrl = $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp();
        $strNovoStatus = $objControleDsmpDTO->getStrStaAtendimentoDsmp();
        $idTriagem = $objControleDsmpDTO->getNumIdMdUtlTriagem();
        $idAnalise = $objControleDsmpDTO->getNumIdMdUtlAnalise();
        $idRevisao = $objControleDsmpDTO->getNumIdMdUtlRevisao();
        $tempoExecucao = $objControleDsmpDTO->getNumTempoExecucao();
        $idUsuarioDistr = $objControleDsmpDTO->getNumIdUsuarioDistribuicao();
        $dthPrazo = $objControleDsmpDTO->getDthPrazoTarefa();
        $idContato = $objControleDsmpDTO->getNumIdContato();
        $idUnidade = $objControleDsmpDTO->getNumIdUnidade();

        if ($isAjustePrazo) {
            $strTipoAcao = MdUtlControleDsmpRN::$STR_TIPO_ACAO_RPV_AJUSTE_PRAZO;
            $idAjustePrazo = $objControleDsmpDTO->getNumIdMdUtlAjustePrazo(); /*Adicionar apenas em Suspensão ou Interrupção*/
            $tipoSolicitacao = $objAjustPrazoDTO->getStrStaTipoSolicitacao();
        }

        if ($isContestacao) {
            $strTipoAcao = MdUtlControleDsmpRN::$STR_TIPO_ACAO_RPV_CONTESTACAO;
            $idContestacao = $objControleDsmpDTO->getNumIdMdUtlContestRevisao();
            $objContestacaoDTO->setStrStaSolicitacao(MdUtlAjustePrazoRN::$REPROVADA);
            $strDetalheAjust = MdUtlAjustePrazoRN::$STR_REPROVADA;
        }


        $objContatoRN = new ContatoRN();
        $objContatoDTO = new ContatoDTO();
        $objContatoDTO->setNumIdContato($idContato);
        $objContatoDTO->retTodos();
        $objContatoDTO = $objContatoRN->listarRN0325($objContatoDTO);

        $strNome = $objContatoDTO[0]->getStrNome();
        $strEmailSolicitante = $objContatoDTO[0]->getStrEmail();

        if ($isAjustePrazo) {
            /*Alterar status para reprovada*/
            if ($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_DILACAO) {
                $strDetalheAjust = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_DILACAO;
                $objAjustPrazoDTO->setStrStaSolicitacao(MdUtlAjustePrazoRN::$REPROVADA);
            }

            /*Se Suspensão alterar status para Suspenso*/
            if ($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_SUSPENSAO) {
                $strDetalheAjust = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_SUSPENSAO;
                $objAjustPrazoDTO->setStrStaSolicitacao(MdUtlAjustePrazoRN::$REPROVADA);
            }

            /*Se Interrupção alterar status para Interrompido*/
            if ($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_INTERRUPCAO) {
                $strDetalheAjust = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_INTERRUPCAO;
                $objAjustPrazoDTO->setStrStaSolicitacao(MdUtlAjustePrazoRN::$REPROVADA);
            }
        }

        /*Enviar e-mail para solicitante*/
        if ($strEmailSolicitante != '') {
            $arrDados = array($strNome, $strEmailSolicitante, $strProcesso, $strNovoStatus, false);

            if ($isContestacao) {
                $strAssunto = 'Resultado da Solicitação de Contestação de Avaliação';
                $strConteudo = '@nome_usuario_solicitante@, a sua solicitação de Contestação de Avaliação referente à @status_solicitacao@ do Processo @numero_processo@ foi @acao_solicitacao@. Na dúvida converse com o Gestor do Tipo de Controle da sua área.';
                array_push($arrDados, $strAssunto);
                array_push($arrDados, $strConteudo);
            }

            $this->emailRespostaSolicitacao($arrDados);
        }

        $arrIds = array($idProcedimento);

        $arrObjsAtuais = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimentoPorUnidade(array($arrIds, $idUnidade));
        $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, $arrIds, 'N', 'N', 'N', $idUnidade));

        if ($isAjustePrazo) {
            $objMdUtlAjustePrazoRN->alterar($objAjustPrazoDTO);
            $objMdUtlAjustePrazoRN->desativar(array($objAjustPrazoDTO));
        }

        if ($isContestacao) {
            $objMdUtlContestacaoRN->alterar($objContestacaoDTO);
            $objMdUtlContestacaoRN->desativar(array($objContestacaoDTO));
        }

        $objMdUtlControleDsmpRN->excluir(array($objControleDsmpDTO));
        $arrSituacao = array($idProcedimento, $idFila, $idTpCtrl, $strNovoStatus, $idUnidade, $tempoExecucao, $idUsuarioDistr, $idTriagem, $idAnalise, $idRevisao, $strDetalheAjust, $strTipoAcao, null, null, $dthPrazo);
        $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso($arrSituacao);
    }


    public function emailRespostaSolicitacao($arrDadosEmail)
    {
        $strNome = array_key_exists(0, $arrDadosEmail) ? $arrDadosEmail[0] : null;
        $strEmailSolicitante = array_key_exists(1, $arrDadosEmail) ? $arrDadosEmail[1] : null;
        $strProcesso = array_key_exists(2, $arrDadosEmail) ? $arrDadosEmail[2] : null;
        $strNovoStatus = array_key_exists(3, $arrDadosEmail) ? $arrDadosEmail[3] : null;
        $isAprovado = array_key_exists(4, $arrDadosEmail) ? $arrDadosEmail[4] : false;
        $strAssunto = array_key_exists(5, $arrDadosEmail) ? $arrDadosEmail[5] : null;
        $strConteudo = array_key_exists(6, $arrDadosEmail) ? $arrDadosEmail[6] : null;
        $strStatus = array_key_exists(7, $arrDadosEmail) ? $arrDadosEmail[7] : null;
        //Enviar Email
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

        if (is_null($strStatus)) {
            $strStatus = $strNovoStatus;
        }


        if ($strStatus == MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM) {
            $strNovoStatus = MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM;
        }

        if ($strStatus == MdUtlControleDsmpRN::$EM_ANALISE || $strStatus == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE) {
            $strNovoStatus = MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE;
        }

        if ($strStatus == MdUtlControleDsmpRN::$EM_REVISAO) {
            $strNovoStatus = MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO;
        }

        $strAcao = $isAprovado ? MdUtlAjustePrazoRN::$STR_APROVADA : MdUtlAjustePrazoRN::$STR_REPROVADA;

        //Monta Email
        $strDe = SessaoSEI::getInstance()->getStrSiglaSistema();
        $strDe .= '<' . $objInfraParametro->getValor('SEI_EMAIL_SISTEMA') . '>';
        $strPara = $strEmailSolicitante;

        if (is_null($strAssunto)) {
            $strAssunto = 'Resultado da Solicitação do Ajuste de Prazo';
            $strConteudo = '@nome_usuario_solicitante@, a sua solicitação de ajuste de prazo referente à @status_solicitacao@ do Processo @numero_processo@ foi @acao_solicitacao@. Na dúvida converse com o Gestor do Tipo de Controle da sua área.';
        }

        $strConteudo = str_replace('@nome_usuario_solicitante@', $strNome, $strConteudo);
        $strConteudo = str_replace('@numero_processo@', $strProcesso, $strConteudo);
        $strConteudo = str_replace('@status_solicitacao@', $strNovoStatus, $strConteudo);
        $strConteudo = str_replace('@acao_solicitacao@', $strAcao, $strConteudo);

        $objEmailDTO = new EmailDTO();
        $objEmailDTO->setStrDe($strDe);
        $objEmailDTO->setStrPara($strPara);
        $objEmailDTO->setStrAssunto($strAssunto);
        $objEmailDTO->setStrMensagem($strConteudo);

        EmailRN::processar(array($objEmailDTO));
    }

    protected function buscarSolicitacoesContestacaoConectado($arrDados)
    {
        $idTipoControle = array_key_exists(0, $arrDados) ? $arrDados[0] : null;
        $arrPostDados = array_key_exists(1, $arrDados) ? $arrDados[1] : null;

        if (!is_null($arrPostDados)) {
            $isStrProcesso = array_key_exists('txtProcessoMdGestao', $arrPostDados) && $arrPostDados['txtProcessoMdGestao'] != '';
            $isStrStatusProcesso = array_key_exists('selStatusProcMdGestao', $arrPostDados) && $arrPostDados['selStatusProcMdGestao'] != '';
            $isIdUsuarioSolicitante = array_key_exists('selServidorMdGestao', $arrPostDados) && $arrPostDados['selServidorMdGestao'] != '';
        }

        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objRN = new MdUtlControleDsmpRN();

        if ($isStrProcesso) {
            $objMdUtlControleDsmpDTO->setStrProtocoloProcedimentoFormatado('%' . trim($arrPostDados['txtProcessoMdGestao'] . '%'), InfraDTO::$OPER_LIKE);
        }

        if ($isStrStatusProcesso) {
            $objMdUtlControleDsmpDTO->setStrStaAtendimentoDsmp($arrPostDados['selStatusProcMdGestao']);
        }

        if ($isIdUsuarioSolicitante) {
            $objMdUtlControleDsmpDTO->setNumIdUsuarioDistribuicao($arrPostDados['selServidorMdGestao']);
        }


        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        if( is_array($idTipoControle)) 
            $objMdUtlControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle,InfraDTO::$OPER_IN);
        else
            $objMdUtlControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);

        $objMdUtlControleDsmpDTO->setNumIdMdUtlContestRevisao(null, InfraDTO::$OPER_DIFERENTE);
        $objMdUtlControleDsmpDTO->setOrdDthAtual(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objMdUtlControleDsmpDTO->setStrStaSolicitacaoContestacao(MdUtlContestacaoRN::$PENDENTE_RESPOSTA);
        $objMdUtlControleDsmpDTO->retTodos();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlContestRevisao();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlAdmJustContest();
        $objMdUtlControleDsmpDTO->retNumIdMdUtlControleDsmp();
        $objMdUtlControleDsmpDTO->retDblIdProcedimento();
        $objMdUtlControleDsmpDTO->retStrNomeTipoProcesso();
        $objMdUtlControleDsmpDTO->retStrSiglaUsuarioDistribuicao();
        $objMdUtlControleDsmpDTO->retStrNomeUsuarioDistribuicao();
        $objMdUtlControleDsmpDTO->retNumIdUsuarioDistribuicao();
        $objMdUtlControleDsmpDTO->retNumIdUsuarioAtual();
        $objMdUtlControleDsmpDTO->retStrProtocoloProcedimentoFormatado();
        //$objMdUtlControleDsmpDTO->retStrSinAtivoAjustePrazo();sinAtivoContestação
        $objMdUtlControleDsmpDTO->retStrNomeJustContestacao();
        $objMdUtlControleDsmpDTO->retStrIdJustContestacao();
        $objMdUtlControleDsmpDTO->retStrInformacoesComplementares();
        $objMdUtlControleDsmpDTO->retDthAtual();
        $objMdUtlControleDsmpDTO->retStrNomeTpControle();
        $count = $objRN->contar($objMdUtlControleDsmpDTO);

        if ($count > 0) {
            $objMdUtlControleDsmpDTO = $objRN->listar($objMdUtlControleDsmpDTO);
            return $objMdUtlControleDsmpDTO;
        }

        return null;
    }

    public function recuperarServidoresSolicitacoes($idTipoControle)
    {
        $objRegrasGeraisRN = new MdUtlRegrasGeraisRN();
        $idsUsuarioUnidade = $objRegrasGeraisRN->getIdsUsuariosUnidadeLogada();

        if (count($idsUsuarioUnidade) > 0) {
            $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
            $objRN = new MdUtlControleDsmpRN();

            $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objMdUtlControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
            $objMdUtlControleDsmpDTO->retNumIdUsuarioDistribuicao();
            $objMdUtlControleDsmpDTO->setNumIdUsuarioDistribuicao($idsUsuarioUnidade, InfraDTO::$OPER_IN);
            $objMdUtlControleDsmpDTO->retStrNomeUsuarioDistribuicao();
            $objMdUtlControleDsmpDTO->setUsuarioDistribuicaoFK(InfraDTO::$TIPO_FK_OBRIGATORIA);

            $objMdUtlControleDsmpDTO->adicionarCriterio(array('IdMdUtlAjustePrazo', 'IdMdUtlContestRevisao'),
                array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_DIFERENTE),
                array(null, null),
                array(InfraDTO::$OPER_LOGICO_OR));

            $objMdUtlControleDsmpDTO->setOrdStrNomeUsuarioDistribuicao(InfraDTO::$TIPO_ORDENACAO_ASC);

            return $objRN->listar($objMdUtlControleDsmpDTO);
        }

        return null;

    }

    public function getGestoresTpControle($arrDadosEmail)
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
                    $this->emailSolicitacaoAjustePrazo($strTipoAcao, $strItemMantido, $strNomeControle, $strNomeUsuario, $strProtocolo, $strSiglaUnidade, $objContatoDTO->getStrEmail());
                }
            }
        }
    }

    public function verificaUnidadeGestor($usuario, $idUnidadeSolicitante)
    {
        $objInfraSip = new InfraSip(SessaoSEI::getInstance());
        $arrPerfisSip = $objInfraSip->carregarPerfis(SessaoSEI::getInstance()->getNumIdSistema(), $usuario->getNumIdUsuario(), $idUnidadeSolicitante);

        for ($i = 0; $i < count($arrPerfisSip); $i++) {
            if ($arrPerfisSip[$i][1] == 'Gestor de Controle de Desempenho') {
                return true;
            }
        }
    }

    /**
     * Dispara email ao solicitar ajuste de prazo
     * @param $strTipoAcao
     * @param $strItemMantido
     */
    public function emailSolicitacaoAjustePrazo($strTipoAcao, $strItemMantido, $strNomeControle, $strNomeUsuario, $strProtocolo, $strSiglaUnidade, $email)
    {
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
