<?

require_once dirname(__FILE__).'/../../../SEI.php';
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

    protected function buscarSolicitacoesAjustePrazoConectado($arrDados){
        $idTipoControle = array_key_exists(0, $arrDados) ? $arrDados[0] : null;
        $arrPostDados   = array_key_exists(1, $arrDados) ? $arrDados[1] : null;

        if(!is_null($arrPostDados)){
            $isStrProcesso          = array_key_exists('txtProcessoMdGestao', $arrPostDados) && $arrPostDados['txtProcessoMdGestao'] != '';
            $isStrStatusProcesso    = array_key_exists('selStatusProcMdGestao', $arrPostDados) && $arrPostDados['selStatusProcMdGestao'] != '';
            $isIdUsuarioSolicitante = array_key_exists('selServidorMdGestao', $arrPostDados) && $arrPostDados['selServidorMdGestao'] != '';
        }

        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objRN = new MdUtlControleDsmpRN();


        if($isStrProcesso){
            $objMdUtlControleDsmpDTO->setStrProtocoloProcedimentoFormatado('%' . trim($arrPostDados['txtProcessoMdGestao'] . '%'), InfraDTO::$OPER_LIKE);
        }

        if($isStrStatusProcesso){


            $objMdUtlControleDsmpDTO->setStrStaAtendimentoDsmp($arrPostDados['selStatusProcMdGestao']);
        }else{
            $arrStatus = array(MdUtlControleDsmpRN::$INTERROMPIDO, MdUtlControleDsmpRN::$SUSPENSO);
            $objMdUtlControleDsmpDTO->setStrStaAtendimentoDsmp($arrStatus, InfraDTO::$OPER_NOT_IN);
        }

        if($isIdUsuarioSolicitante){
            $objMdUtlControleDsmpDTO->setNumIdUsuarioDistribuicao($arrPostDados['selServidorMdGestao']);
        }


        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
        $objMdUtlControleDsmpDTO->setNumIdMdUtlAjustePrazo(null, InfraDTO::$OPER_DIFERENTE);
        $objMdUtlControleDsmpDTO->setStrStaSolicitacaoAjustePrazo(MdUtlAjustePrazoRN::$PENDENTE_RESPOSTA);
        $objMdUtlControleDsmpDTO->setOrdStrDthPrazoInicialAjustePrazo(InfraDTO::$TIPO_ORDENACAO_ASC);
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
        $objMdUtlControleDsmpDTO->retStrDthPrazoInicialAjustePrazo();
        $objMdUtlControleDsmpDTO->retStrDthPrazoSolicitacaoAjustePrazo();
        $objMdUtlControleDsmpDTO->retStrNomeJustificativa();
        $objMdUtlControleDsmpDTO->retDthAtual();
        $objMdUtlControleDsmpDTO->retStrObservacao();
        $objMdUtlControleDsmpDTO->retNumIdContato();
        $count = $objRN->contar($objMdUtlControleDsmpDTO);
        if($count > 0){
            $objMdUtlControleDsmpDTO = $objRN->listar($objMdUtlControleDsmpDTO);
            return $objMdUtlControleDsmpDTO;
        }

        return null;
    }

    protected function aprovarSolicitacaoControlado($objControleDsmpDTO){

        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $objHistoricoRN         = new MdUtlHistControleDsmpRN();
        $objMdUtlAjustePrazoRN  = new MdUtlAjustePrazoRN();

        if(!is_null($objControleDsmpDTO->getNumIdMdUtlAjustePrazo())){
            $objAjustPrazoDTO = new MdUtlAjustePrazoDTO();
            $objAjustPrazoDTO->setNumIdMdUtlAjustePrazo($objControleDsmpDTO->getNumIdMdUtlAjustePrazo());
            $objAjustPrazoDTO->retTodos();
            $objAjustPrazoDTO->setNumMaxRegistrosRetorno(1);
            $objAjustPrazoDTO = $objMdUtlAjustePrazoRN->consultar($objAjustPrazoDTO);
        }

        $isAprovado      = true;
        $strProcesso     = $objControleDsmpDTO->getStrProtocoloProcedimentoFormatado();
        $idProcedimento  = $objControleDsmpDTO->getDblIdProcedimento();
        $idFila          = $objControleDsmpDTO->getNumIdMdUtlAdmFila();
        $idTpCtrl        = $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp();
        $strNovoStatus   = $objControleDsmpDTO->getStrStaAtendimentoDsmp();
        $idTriagem       = $objControleDsmpDTO->getNumIdMdUtlTriagem();
        $idAnalise       = $objControleDsmpDTO->getNumIdMdUtlAnalise();
        $idRevisao       = $objControleDsmpDTO->getNumIdMdUtlRevisao();
        $undEsforco      = $objControleDsmpDTO->getNumUnidadeEsforco();
        $idUsuarioDistr  = $objControleDsmpDTO->getNumIdUsuarioDistribuicao();
        $idAjustePrazo   = $objControleDsmpDTO->getNumIdMdUtlAjustePrazo(); /*Adicionar apenas em Suspensão ou Interrupção*/
        $idContato       = $objControleDsmpDTO->getNumIdContato();
        $strTipoAcao     = MdUtlControleDsmpRN::$STR_TIPO_ACAO_APV_AJUSTE_PRAZO;
        $tipoSolicitacao = $objAjustPrazoDTO->getStrStaTipoSolicitacao();
        $idUnidade       =  $objControleDsmpDTO->getNumIdUnidade();

        $objContatoRN = new ContatoRN();
        $objContatoDTO = new ContatoDTO();
        $objContatoDTO->setNumIdContato($idContato);
        $objContatoDTO->retTodos();
        $objContatoDTO = $objContatoRN->listarRN0325($objContatoDTO);

        $strNome             = $objContatoDTO[0]->getStrNome();
        $strEmailSolicitante = $objContatoDTO[0]->getStrEmail();

        /*Alterar status para aprovada*/
        if($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_DILACAO){
            $strDetalheAjust = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_DILACAO;
            $dthPrazo        = $objAjustPrazoDTO->getDthPrazoSolicitacao();
            $objAjustPrazoDTO->setStrStaSolicitacao(MdUtlAjustePrazoRN::$APROVADA);
            /*Se Dilação alterar o prazo da tarefa em analise ou revisao para o prazo informado*/
        }

        /*Se Suspensão alterar status para Suspenso*/
        if($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_SUSPENSAO){
            $strDetalheAjust = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_SUSPENSAO;
            $strNovoStatus   = MdUtlControleDsmpRN::$SUSPENSO;
            $dthPrazo        = $objAjustPrazoDTO->getDthPrazoSolicitacao();
            $objAjustPrazoDTO->setStrStaSolicitacao(MdUtlAjustePrazoRN::$APROVADA);
        }

        /*Se Interrupção alterar status para Interrompido*/
        if($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_INTERRUPCAO){
            $strDetalheAjust = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_INTERRUPCAO;
            $strNovoStatus   = MdUtlControleDsmpRN::$INTERROMPIDO;
            $dthPrazo        = $objAjustPrazoDTO->getDthPrazoSolicitacao();
            $objAjustPrazoDTO->setStrStaSolicitacao(MdUtlAjustePrazoRN::$APROVADA);
        }
        /*Enviar e-mail para solicitante*/
        if($strEmailSolicitante != ''){
            $this->emailRespostaSolicitacao(array($strNome, $strEmailSolicitante, $strProcesso, $strNovoStatus, $isAprovado));
        }
        $arrIds = array($idProcedimento);

        $arrObjsAtuais = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimentoPorUnidade(array($arrIds, $idUnidade));
        $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais,$arrIds, 'N','N','N', $idUnidade));
        $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

        $objMdUtlAjustePrazoRN->alterar($objAjustPrazoDTO);
        $objMdUtlAjustePrazoRN->desativar(array($objAjustPrazoDTO));


        //Cadastrando para essa fila, e esse procedimento e unidade o novo status
        if($objAjustPrazoDTO->getStrStaTipoSolicitacao() == MdUtlControleDsmpRN::$TP_SOLICITACAO_DILACAO){
            $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso(array($idProcedimento, $idFila, $idTpCtrl, $strNovoStatus, $idUnidade , $undEsforco, $idUsuarioDistr, $idTriagem, $idAnalise, $idRevisao, $strDetalheAjust, $strTipoAcao, null, null, $dthPrazo));
        } else {
            $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso(array($idProcedimento, $idFila, $idTpCtrl, $strNovoStatus, $idUnidade , $undEsforco, $idUsuarioDistr, $idTriagem, $idAnalise, $idRevisao, $strDetalheAjust, $strTipoAcao, null, $idAjustePrazo, $dthPrazo));
        }
    }

    protected function reprovarSolicitacaoControlado($objControleDsmpDTO){

        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $objHistoricoRN         = new MdUtlHistControleDsmpRN();
        $objMdUtlAjustePrazoRN  = new MdUtlAjustePrazoRN();

        if(!is_null($objControleDsmpDTO->getNumIdMdUtlAjustePrazo())){
            $objAjustPrazoDTO = new MdUtlAjustePrazoDTO();
            $objAjustPrazoDTO->setNumIdMdUtlAjustePrazo($objControleDsmpDTO->getNumIdMdUtlAjustePrazo());
            $objAjustPrazoDTO->retTodos();
            $objAjustPrazoDTO->setNumMaxRegistrosRetorno(1);
            $objAjustPrazoDTO = $objMdUtlAjustePrazoRN->consultar($objAjustPrazoDTO);
        }

        $strProcesso     = $objControleDsmpDTO->getStrProtocoloProcedimentoFormatado();
        $idProcedimento  = $objControleDsmpDTO->getDblIdProcedimento();
        $idFila          = $objControleDsmpDTO->getNumIdMdUtlAdmFila();
        $idTpCtrl        = $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp();
        $strNovoStatus   = $objControleDsmpDTO->getStrStaAtendimentoDsmp();
        $idTriagem       = $objControleDsmpDTO->getNumIdMdUtlTriagem();
        $idAnalise       = $objControleDsmpDTO->getNumIdMdUtlAnalise();
        $idRevisao       = $objControleDsmpDTO->getNumIdMdUtlRevisao();
        $undEsforco      = $objControleDsmpDTO->getNumUnidadeEsforco();
        $idUsuarioDistr  = $objControleDsmpDTO->getNumIdUsuarioDistribuicao();
        $idAjustePrazo   = $objControleDsmpDTO->getNumIdMdUtlAjustePrazo(); /*Adicionar apenas em Suspensão ou Interrupção*/
        $dthPrazo        = $objControleDsmpDTO->getDthPrazoTarefa();
        $idContato       = $objControleDsmpDTO->getNumIdContato();
        $strTipoAcao     = MdUtlControleDsmpRN::$STR_TIPO_ACAO_RPV_AJUSTE_PRAZO;
        $tipoSolicitacao = $objAjustPrazoDTO->getStrStaTipoSolicitacao();
        $idUnidade       =  $objControleDsmpDTO->getNumIdUnidade();

        $objContatoRN = new ContatoRN();
        $objContatoDTO = new ContatoDTO();
        $objContatoDTO->setNumIdContato($idContato);
        $objContatoDTO->retTodos();
        $objContatoDTO = $objContatoRN->listarRN0325($objContatoDTO);

        $strNome             = $objContatoDTO[0]->getStrNome();
        $strEmailSolicitante = $objContatoDTO[0]->getStrEmail();

        /*Alterar status para reprovada*/
        if($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_DILACAO){
            $strDetalheAjust = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_DILACAO;
            $objAjustPrazoDTO->setStrStaSolicitacao(MdUtlAjustePrazoRN::$REPROVADA);
        }

        /*Se Suspensão alterar status para Suspenso*/
        if($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_SUSPENSAO){
            $strDetalheAjust = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_SUSPENSAO;
            $objAjustPrazoDTO->setStrStaSolicitacao(MdUtlAjustePrazoRN::$REPROVADA);
        }

        /*Se Interrupção alterar status para Interrompido*/
        if($tipoSolicitacao == MdUtlControleDsmpRN::$TP_SOLICITACAO_INTERRUPCAO){
            $strDetalheAjust = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_INTERRUPCAO;
            $objAjustPrazoDTO->setStrStaSolicitacao(MdUtlAjustePrazoRN::$REPROVADA);
        }

        /*Enviar e-mail para solicitante*/
        if($strEmailSolicitante != ''){
            $this->emailRespostaSolicitacao(array($strNome, $strEmailSolicitante, $strProcesso, $strNovoStatus));
        }

        $arrIds = array($idProcedimento);

        $arrObjsAtuais = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimentoPorUnidade(array($arrIds, $idUnidade));
        $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais,$arrIds, 'N','N','N', $idUnidade));

        $objMdUtlAjustePrazoRN->alterar($objAjustPrazoDTO);
        $objMdUtlAjustePrazoRN->desativar(array($objAjustPrazoDTO));
        $objMdUtlControleDsmpRN->excluir(array($objControleDsmpDTO));

        //Cadastrando para essa fila, e esse procedimento e unidade o novo status
         $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso(array($idProcedimento, $idFila, $idTpCtrl, $strNovoStatus, $idUnidade , $undEsforco, $idUsuarioDistr, $idTriagem, $idAnalise, $idRevisao, $strDetalheAjust, $strTipoAcao, null, null, $dthPrazo));
    }


    public function emailRespostaSolicitacao($arrDadosEmail){
        $strNome             = array_key_exists(0, $arrDadosEmail) ? $arrDadosEmail[0] : null;
        $strEmailSolicitante = array_key_exists(1, $arrDadosEmail) ? $arrDadosEmail[1] : null;
        $strProcesso         = array_key_exists(2, $arrDadosEmail) ? $arrDadosEmail[2] : null;
        $strNovoStatus       = array_key_exists(3, $arrDadosEmail) ? $arrDadosEmail[3] : null;
        $isAprovado          = array_key_exists(4, $arrDadosEmail) ? $arrDadosEmail[4] : false;

        //Enviar Email
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

        if($strNovoStatus == MdUtlControleDsmpRN::$EM_ANALISE || $strNovoStatus == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE){
            $strNovoStatus = MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE;
        }

        if($strNovoStatus == MdUtlControleDsmpRN::$EM_REVISAO){
            $strNovoStatus = MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO;
        }

        if($isAprovado){
            $strAcao = MdUtlAjustePrazoRN::$STR_APROVADA;
        } else {
            $strAcao = MdUtlAjustePrazoRN::$STR_REPROVADA;
        }


        //Monta Email
        $strDe = SessaoSEI::getInstance()->getStrSiglaSistema();
        $strDe .= '<' . $objInfraParametro->getValor('SEI_EMAIL_SISTEMA') . '>';

        $strPara = $strEmailSolicitante;

        $strAssunto = 'Resultado da solicitação do Ajuste de Prazo';

        $strConteudo = '@nome_usuario_solicitante@, a sua solicitação de ajuste de prazo referente à @status_solicitacao@ do Processo @numero_processo@ foi @acao_solicitacao@. Na dúvida converse com o Gestor do Tipo de Controle da sua área.';

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
}
