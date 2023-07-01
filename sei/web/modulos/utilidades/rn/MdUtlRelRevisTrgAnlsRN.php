<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/12/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlRelRevisTrgAnlsRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }
 
  protected function cadastrarControlado(MdUtlRelRevisTrgAnlsDTO $objMdUtlRelRevisTrgAnlsDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_rel_revis_trg_anls_cadastrar', __METHOD__, $objMdUtlRelRevisTrgAnlsDTO);

      $objMdUtlRelRevisTrgAnlsBD = new MdUtlRelRevisTrgAnlsBD($this->getObjInfraIBanco());
      $ret = $objMdUtlRelRevisTrgAnlsBD->cadastrar($objMdUtlRelRevisTrgAnlsDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdUtlRelRevisTrgAnlsDTO $objMdUtlRelRevisTrgAnlsDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_rel_revis_trg_anls_alterar', __METHOD__, $objMdUtlRelRevisTrgAnlsDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      $objInfraException->lancarValidacoes();

      $objMdUtlRelRevisTrgAnlsBD = new MdUtlRelRevisTrgAnlsBD($this->getObjInfraIBanco());
      $objMdUtlRelRevisTrgAnlsBD->alterar($objMdUtlRelRevisTrgAnlsDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlRelRevisTrgAnlsDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_rel_revis_trg_anls_excluir', __METHOD__, $arrObjMdUtlRelRevisTrgAnlsDTO);

      $objMdUtlRelRevisTrgAnlsBD = new MdUtlRelRevisTrgAnlsBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlRelRevisTrgAnlsDTO);$i++){
        $objMdUtlRelRevisTrgAnlsBD->excluir($arrObjMdUtlRelRevisTrgAnlsDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdUtlRelRevisTrgAnlsDTO $objMdUtlRelRevisTrgAnlsDTO){
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_rel_revis_trg_anls_consultar');

      $objMdUtlRelRevisTrgAnlsBD = new MdUtlRelRevisTrgAnlsBD($this->getObjInfraIBanco());
      $ret = $objMdUtlRelRevisTrgAnlsBD->consultar($objMdUtlRelRevisTrgAnlsDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado($objMdUtlRelRevisTrgAnlsDTO) {
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_rel_revis_trg_anls_listar');

      $objMdUtlRelRevisTrgAnlsBD = new MdUtlRelRevisTrgAnlsBD($this->getObjInfraIBanco());

      if(is_array($objMdUtlRelRevisTrgAnlsDTO)) {
          $ret = $objMdUtlRelRevisTrgAnlsBD->listar($objMdUtlRelRevisTrgAnlsDTO[0], true);
          print_r($ret);exit;
      }else{
          $ret = $objMdUtlRelRevisTrgAnlsBD->listar($objMdUtlRelRevisTrgAnlsDTO);
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdUtlRelRevisTrgAnlsDTO $objMdUtlRelRevisTrgAnlsDTO){
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_rel_revis_trg_anls_listar');

      $objMdUtlRelRevisTrgAnlsBD = new MdUtlRelRevisTrgAnlsBD($this->getObjInfraIBanco());
      $ret = $objMdUtlRelRevisTrgAnlsBD->contar($objMdUtlRelRevisTrgAnlsDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

  private function _salvarObjsRelacionadosRevisao($idRevisao, $isAnalise){
      $hdnTbRevisaoAnalise = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTbRevisaoAnalise']);
      foreach ($hdnTbRevisaoAnalise as $item){

          $objMdUtlRelRevisTrgAnlsDTO = new MdUtlRelRevisTrgAnlsDTO();

          $idRelacionamento       = $item[0];
          $idResultadoRevisao     = explode('_',$_POST[$item[1]])[0];
          $idJustificativaRevisao = $_POST[$item[2]];
          $observacao             = $_POST[$item[3]];

          if($isAnalise) {
              $objMdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlRelAnaliseProduto($idRelacionamento);
          }else{
              $objMdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlRelTriagemAtv($idRelacionamento);
          }

          $objMdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlAdmTpRevisao($idResultadoRevisao);

          if($idJustificativaRevisao >0) {
              $objMdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlAdmTpJustRevisao($idJustificativaRevisao);
          }

          $objMdUtlRelRevisTrgAnlsDTO->setStrObservacao($observacao);
          $objMdUtlRelRevisTrgAnlsDTO->setNumIdMdUtlRevisao($idRevisao);

          $this->cadastrar($objMdUtlRelRevisTrgAnlsDTO);
      }
  }

  protected function cadastrarRevisaoTriagemAnaliseControlado($objControleDsmpDTO){
      try {
      $objHistoricoRN           = new MdUtlHistControleDsmpRN();
      $objMdUtlControleDsmpRN   = new MdUtlControleDsmpRN();
      $objMdUtlAdmPrmGrUsuRN    = new MdUtlAdmPrmGrUsuRN();

      $isProcessoConcluido  = 0;
      $idProcedimento       = $_POST['hdnIdProcedimento'];
      $idFila               = $_POST['hdnIdFilaAtiva'];
      $idTpCtrl             = $_POST['hdnIdTpCtrl'];
      $TmpExecucao          = $_POST['hdnTmpExecucao'];
      $strDetalheRev        = $_POST['hdnEncaminhamento'];
      $idUnidade            = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
      $idEncaminhamento     = $_POST['selEncaminhamento'];
      $isAnalise            = $_GET['acao'] == 'md_utl_revisao_analise_cadastrar'? true : false;
      $objMdUtlRevisaoRN    = new MdUtlRevisaoRN();
      //$idRevisao 			= $objMdUtlRevisaoRN->salvarObjRevisao();
      $objRevisao         	= $objMdUtlRevisaoRN->salvarObjRevisao();
      $idRevisao      		= $objRevisao->getNumIdMdUtlRevisao();
      $ckbDistAutoParaMim = isset( $_POST['ckbDistAutoParaMim'] ) ? $_POST['ckbDistAutoParaMim'] : null;
      $isChefiaImediata     = false;
      $objRNGerais        	= new MdUtlRegrasGeraisRN();
      
      if( isset($_POST['cbkRealizarAvalProdAProd']) || isset($_POST['chkItemcbkRealizarAvalProdAProd']) ){
        $this->_salvarObjsRelacionadosRevisao($idRevisao, $isAnalise);
      }

      $idPrmGr = ( new MdUtlAdmTpCtrlDesempRN() )->_getIdsParamsTpControle( [$idTpCtrl] )[0];
      $arrParams = [ [ $idPrmGr ] , SessaoSEI::getInstance()->getNumIdUsuario() ];
      if ( !empty($objMdUtlAdmPrmGrUsuRN->validaUsuarioIsChefiaImediata( $arrParams ) ) ) $isChefiaImediata = true;

      switch ($idEncaminhamento){

          case MdUtlRevisaoRN::$VOLTAR_PARA_FILA:
              $strNovoStatus = $_GET['acao'] == 'md_utl_revisao_analise_cadastrar' ? MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE : MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM;
              $arrObjsAtuais = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimento(array($idProcedimento));
              
              if($strNovoStatus == MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE){ // unidade de esforco sera o tempo das atividades analisadas
                $TmpExecucao = MdUtlAdmPrmGrUsuINT::_retornaUnidEsforcoAtividadesAnalisadas( $arrObjsAtuais[0]->getNumIdMdUtlAnalise() );
              }else{ // unidade de esforco sera o tempo configurado na Fila
                $objMdUtlAdmFilaRN = new MdUtlAdmFilaRN();
                $TmpExecucao = $objMdUtlAdmFilaRN->getTempoExecucaoFila($idFila);
              }

              if(!is_null($arrObjsAtuais)) {
                  $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, array($idProcedimento), 'N','S', 'S'));

                  $arrIdsProcedimentos = array($idProcedimento);
                  $arrDados = array($arrIdsProcedimentos, $strNovoStatus);
                  $objMdUtlControleDsmpRN->controlarAjustePrazo($arrDados);

                  $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

                  $idTriagem = $arrRetorno[$idProcedimento]['ID_TRIAGEM'];
                  $idAnalise = $arrRetorno[$idProcedimento]['ID_ANALISE'];

                  $arrParams = array();
                  $arrParams['dblIdProcedimento'] = $idProcedimento;
                  $arrParams['intIdFila'] = $idFila;
                  $arrParams['intIdTpCtrl'] = $idTpCtrl;
                  $arrParams['strStatus'] = $strNovoStatus;
                  $arrParams['intTempoExecucao'] = $TmpExecucao;
                  $arrParams['idTriagem'] = $idTriagem;
                  $arrParams['idAnalise'] = $idAnalise;
                  $arrParams['idRevisao'] = $idRevisao;
                  $arrParams['strDetalhe'] = $strDetalheRev;
                  $arrParams['tipoAcao'] = MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO;

                  //Cadastrando para essa fila, e esse procedimento e unidade o novo status
                  $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso($arrParams);

                  $idUsuarioAtb = $arrRetorno[$idProcedimento]['ID_USUARIO_ATRIBUICAO'];
                  $objRNGerais->controlarAtribuicao($idProcedimento, $idUsuarioAtb);
              }

              $this->_atualizaObjRevisao($idProcedimento, $objRevisao, $idTpCtrl, $idUsuarioAtb, $isChefiaImediata);

              break;

          case MdUtlRevisaoRN::$FLUXO_FINALIZADO:
              //Atribuição no Core
              $arrObjsAtuais = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimento(array($idProcedimento));

              if (!is_null($arrObjsAtuais)) {
                  $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, array(), 'N', 'N', 'S'));

                  $arrIdsProcedimentos = array($idProcedimento);
                  $arrDados = array($arrIdsProcedimentos, MdUtlControleDsmpRN::$FLUXO_FINALIZADO);
                  $objMdUtlControleDsmpRN->controlarAjustePrazo($arrDados);

                  $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

                  $objMdUtlControleDsmpRN->desativarIdsAtivosControleDsmp($arrRetorno);
                  $objHistoricoRN->concluirControleDsmp(array($idProcedimento, $arrRetorno, MdUtlControleDsmpRN::$CONCLUIR_REVISAO, $idRevisao));

                  $idUsuarioAtb = $arrRetorno[$idProcedimento]['ID_USUARIO_ATRIBUICAO'];
                  $objRNGerais->controlarAtribuicao($idProcedimento, $idUsuarioAtb);

                  $isAssociado = $this->_verificaProcessoAssociaAutomaticamente($objControleDsmpDTO->getDblIdProcedimento(), $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp(), $isAnalise);
                  if(!$isAssociado){
                      $isProcessoConcluido = 1;
                  }
              }
              $this->_atualizaObjRevisao($idProcedimento, $objRevisao, $idTpCtrl, $idUsuarioAtb, $isChefiaImediata);

              break;

          case MdUtlRevisaoRN::$VOLTAR_PARA_RESPONSAVEL:
              $strNovoStatus = $_GET['acao'] == 'md_utl_revisao_analise_cadastrar' ? MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE : MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM;

              $arrRetorno = $objHistoricoRN->getUltimosResponsaveisPorProcesso(array(array($idProcedimento), true));

              $idUsuarioDistr  = $arrRetorno[$idProcedimento]['ID_USUARIO'];
              $strDetalheDistr = $arrRetorno[$idProcedimento]['NOME']. ' ('.$arrRetorno[$idProcedimento]['SIGLA'].')';

              $objInfraException = new InfraException();

              if( empty( $strDetalheDistr ) ) $objInfraException->lancarValidacao(MdUtlMensagemINT::$MSG_UTL_122);

              $objAtribuirDTO  = new AtribuirDTO();
              $objAtividadeRN  = new AtividadeRN();
              $objProtocoloDTO = new ProtocoloDTO();
              $objProtocoloRN = new ProtocoloRN();
              $arrObjProtocoloDTO = array();

              //Atribuição no Core
              $objProtocoloDTO->setDblIdProtocolo($idProcedimento);
              $objProtocoloDTO->retStrStaNivelAcessoGlobal();
              $arrObjValidaProtocoloDTO = $objProtocoloRN->listarRN0668($objProtocoloDTO);

              if( $arrObjValidaProtocoloDTO[0]->getStrStaNivelAcessoGlobal() != ProtocoloRN::$NA_SIGILOSO ){
                $arrObjProtocoloDTO[] = $objProtocoloDTO;
                $objAtribuirDTO->setNumIdUsuarioAtribuicao($idUsuarioDistr);
                $objAtribuirDTO->setArrObjProtocoloDTO($arrObjProtocoloDTO);
                $objAtividadeRN->atribuirRN0985($objAtribuirDTO);
              }

              $arrIdsProcedimentos = array($idProcedimento);
              $arrObjsAtuais   = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimento($arrIdsProcedimentos);

              $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, $arrIdsProcedimentos, 'N','S','S'));

              $arrDados = array($arrIdsProcedimentos, $strNovoStatus);
              $objMdUtlControleDsmpRN->controlarAjustePrazo($arrDados);

              $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

              $idTriagem = $arrRetorno[$idProcedimento]['ID_TRIAGEM'];
              $idAnalise = $arrRetorno[$idProcedimento]['ID_ANALISE'];

              //Cadastrar Histórico - Solicitação Negocial
              $objHistoricoRN->salvarObjHistoricoRevisao(array($idProcedimento, $arrRetorno, $idRevisao, $strDetalheRev, $strNovoStatus, $idUsuarioDistr, $idFila));

              $arrParams = array();
              $arrParams['dblIdProcedimento'] = $idProcedimento;
              $arrParams['intIdFila'] = $idFila;
              $arrParams['intIdTpCtrl'] = $idTpCtrl;
              $arrParams['strStatus'] = $strNovoStatus;
              $arrParams['intTempoExecucao'] = 0;
              $arrParams['idUsuarioDistrib'] = $idUsuarioDistr;
              $arrParams['idTriagem'] = $idTriagem;
              $arrParams['idAnalise'] = $idAnalise;
              $arrParams['idRevisao'] = $idRevisao;
              $arrParams['strDetalhe'] = $strDetalheDistr;
              $arrParams['tipoAcao'] = MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO;

              //Cadastrando para essa fila, e esse procedimento e unidade o novo status
              $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso($arrParams);
              $this->_atualizaObjRevisao($idProcedimento, $objRevisao, $idTpCtrl, $idUsuarioDistr, $isChefiaImediata);
              break;
      }

      }catch(Exception $e){
          throw new InfraException('Erro cadastrando Avaliação .',$e);
      }

      return $isProcessoConcluido;

  }

    private function _atualizaObjRevisao($idProcedimento, $objMdUtlRevisaoDTO, $idTpCtrl, $idUsuarioAtb, $isChefiaImediata = false) {

        $objMdUtlRevisaoRN = new MdUtlRevisaoRN();
        $objMdUtlRevisao = new MdUtlRevisaoDTO();
        $objMdUtlRevisao->retNumIdMdUtlRevisao();
        $objMdUtlRevisao->retTodos();
        $objMdUtlRevisao->setOrd('IdMdUtlRevisao', InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdUtlRevisao->setNumMaxRegistrosRetorno(1);

        $objMdUtlRevisao = $objMdUtlRevisaoRN->consultar($objMdUtlRevisao);

        if ($objMdUtlRevisao->getDthInicio() || $objMdUtlRevisao->getDthPrazo() || $objMdUtlRevisao->getNumTempoExecucao()) {

            $arrDadosPercentualDesempenho = MdUtlAdmPrmGrUsuINT::retornaDadosPercentualDesempenho($objMdUtlRevisao->getNumTempoExecucao(), $idTpCtrl, $idUsuarioAtb);

            $objMdUtlRevisao->setStrStaTipoPresenca($arrDadosPercentualDesempenho['strStaTipoPresenca']);
            $objMdUtlRevisao->setNumTempoExecucaoAtribuido($arrDadosPercentualDesempenho['numTempoExecucao']);
            $objMdUtlRevisao->setNumPercentualDesempenho($arrDadosPercentualDesempenho['numPercentualDesempenho']);

            if( $isChefiaImediata ){
                $objMdUtlRevisao->setNumTempoExecucao( 0 );
                $objMdUtlRevisao->setNumTempoExecucaoAtribuido( null );
            }

            $objMdUrltRevisaoRN = new MdUtlRevisaoRN();
            $objMdUrltRevisaoRN->alterar($objMdUtlRevisao);

            return $objMdUtlRevisao;
        } else {
            $regrasGerais = new MdUtlRegrasGeraisRN();
            $objTodosHistDesmp = $regrasGerais->recuperarObjHistorico($idProcedimento);
            $arrParams = $regrasGerais->regraAcaoRevisao($objTodosHistDesmp);
            $arrDadosPercentualDesempenho = MdUtlAdmPrmGrUsuINT::retornaDadosPercentualDesempenho($arrParams['tempoExecucao'] ? $arrParams['tempoExecucao'] : 0, $idTpCtrl, $idUsuarioAtb);

            $objMdUtlRevisaoDTO->setDthInicio($arrParams['dataInicio'] ? $arrParams['dataInicio'] : '');
            $objMdUtlRevisaoDTO->setDthPrazo($arrParams['dataPrazo'] ? $arrParams['dataPrazo'] : '');
            $objMdUtlRevisaoDTO->setNumTempoExecucao(isset($arrParams['tempoExecucao']) ? $arrParams['tempoExecucao'] : '');

            $objMdUtlRevisaoDTO->setStrStaTipoPresenca($arrDadosPercentualDesempenho['strStaTipoPresenca']);
            $objMdUtlRevisaoDTO->setNumTempoExecucaoAtribuido($arrParams['tempoExecucaoAtribuido']);
            $objMdUtlRevisaoDTO->setNumPercentualDesempenho($arrDadosPercentualDesempenho['numPercentualDesempenho']);

            if( $isChefiaImediata ){
                $objMdUtlRevisaoDTO->setNumTempoExecucao( 0 );
                $objMdUtlRevisaoDTO->setNumTempoExecucaoAtribuido( null );
            }

            $objMdUrltRevisaoRN = new MdUtlRevisaoRN();
            return $objMdUrltRevisaoRN->alterar($objMdUtlRevisaoDTO);
        }
    }

    protected function cadastrarRevisaoTriagemAnaliseContestControlado($arrParams){

        try {
            $idProcedimento         = $arrParams[0];
            $idContato              = $arrParams[1];
            $strNumeroProcesso      = $arrParams[2];
            $objMdUtlContestacaoRN  = new MdUtlContestacaoRN();
            $objHistoricoRN         = new MdUtlHistControleDsmpRN();
            $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
            $objMdUtlRevisaoRN      = new MdUtlRevisaoRN();
            $objRNGerais            = new MdUtlRegrasGeraisRN();
            $arrIdsProcedimentos    = array($idProcedimento);
            $arrObjsAtuais          = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimento($arrIdsProcedimentos);
//            $idNovaRevisao          = $objMdUtlRevisaoRN->salvarObjRevisao(true);
            $objRevisao          = $objMdUtlRevisaoRN->salvarObjRevisao(true);
            $idNovaRevisao          = $objRevisao->getNumIdMdUtlRevisao();
            $isAnalise              = $_GET['acao'] == 'md_utl_revisao_analise_cadastrar';

            if( isset($_POST['cbkRealizarAvalProdAProd']) || isset($_POST['chkItemcbkRealizarAvalProdAProd']) ){
              $this->_salvarObjsRelacionadosRevisao($idNovaRevisao, $isAnalise);
            }
            $strStatus              = null;

            $strEncaminhamentoConts = $_POST['selEncaminhamentoContest'];
            switch($strEncaminhamentoConts){
                case MdUtlRevisaoRN::$MANTER_O_RESPONSAVEL:

                    $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, $arrIdsProcedimentos, 'N','N','N'));
                    $idContestacao = $arrRetorno[$idProcedimento]['ID_CONTESTACAO'];
                    $objMdUtlContestacaoRN->aprovarContestacao(array($idContestacao, $idNovaRevisao));
                    $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

                    $idTriagem = $arrRetorno[$idProcedimento]['ID_TRIAGEM'];
                    $idAnalise = $arrRetorno[$idProcedimento]['ID_ANALISE'];
                    $strStatus = $arrRetorno[$idProcedimento]['STATUS'];
                    $idFila    = $arrRetorno[$idProcedimento]['ID_FILA'];
                    $idTpCtrl  = $arrRetorno[$idProcedimento]['ID_TIPO_CONTROLE'];
                    $idUsuarioDistr = $arrRetorno[$idProcedimento]['ID_USUARIO_ATRIBUICAO'];

                    $arrParams = array();
                    $arrParams['dblIdProcedimento'] = $idProcedimento;
                    $arrParams['intIdFila'] = $idFila;
                    $arrParams['intIdTpCtrl'] = $idTpCtrl;
                    $arrParams['strStatus'] = $strStatus;
                    $arrParams['intTempoExecucao'] = 0;
                    $arrParams['idUsuarioDistrib'] = $idUsuarioDistr;
                    $arrParams['idTriagem'] = $idTriagem;
                    $arrParams['idAnalise'] = $idAnalise;
                    $arrParams['idRevisao'] = $idNovaRevisao;
                    $arrParams['strDetalhe'] = MdUtlContestacaoRN::$STR_APROVADA;
                    $arrParams['tipoAcao'] = MdUtlControleDsmpRN::$STR_TIPO_CONTESTACAO_REVISAO;

                    //Cadastrando para essa fila, e esse procedimento e unidade o novo status
                   $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso($arrParams);
                break;
                case MdUtlRevisaoRN::$FLUXO_FINALIZADO:

                    $arrObjsAtuais = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimento(array($idProcedimento));

                    if (!is_null($arrObjsAtuais)) {
                        $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, array(), 'N', 'N', 'N'));
                        $idTpCtrl  = $arrRetorno[$idProcedimento]['ID_TIPO_CONTROLE'];
                        $strStatus = $arrRetorno[$idProcedimento]['STATUS'];

                        $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);
                        $objMdUtlControleDsmpRN->desativarIdsAtivosControleDsmp($arrRetorno);
                        $objHistoricoRN->concluirControleDsmp(array($idProcedimento, $arrRetorno, MdUtlControleDsmpRN::$CONCLUIR_CONTESTACAO, $idNovaRevisao));

                        $idUsuarioAtb = $arrRetorno[$idProcedimento]['ID_USUARIO_ATRIBUICAO'];

                        // Ajuste quando o processo igual a Sigiloso, não realizar atribuição pelo CORE do SEI
                        $objProtocoloRN = new ProtocoloRN();
                        $objProtocoloDTO = new ProtocoloDTO();
                        $objProtocoloDTO->setDblIdProtocolo($idProcedimento);
                        $objProtocoloDTO->retStrStaNivelAcessoGlobal();
                        $arrObjValidaProtocoloDTO = $objProtocoloRN->listarRN0668($objProtocoloDTO);
                        if( $arrObjValidaProtocoloDTO[0]->getStrStaNivelAcessoGlobal() != ProtocoloRN::$NA_SIGILOSO )
                          $objRNGerais->controlarAtribuicao($idProcedimento, $idUsuarioAtb);

                        $isAssociado = $this->_verificaProcessoAssociaAutomaticamente($idProcedimento, $idTpCtrl, $isAnalise);
                        if(!$isAssociado){
                            $isProcessoConcluido = 1;
                        }
                    }
                    break;

            }

            $objContatoRN  = new ContatoRN();
            $objContatoDTO = new ContatoDTO();
            $objContatoDTO->setNumIdContato($idContato);
            $objContatoDTO->retTodos();
            $objContatoDTO = $objContatoRN->listarRN0325($objContatoDTO);
            $strNome = $objContatoDTO[0]->getStrNome();
            $strEmailSolicitante = $objContatoDTO[0]->getStrEmail();
            $isContatoVazio = 1;

            $objMdUtlGestaoAjusteRN = new MdUtlGestaoAjustPrazoRN();
            if ($strEmailSolicitante != '') {
                $strAssunto = 'Resultado da Solicitação de Contestação de Avaliação';
                $strConteudo = '@nome_usuario_solicitante@, a sua solicitação de Contestação de Avaliação referente à @status_solicitacao@ do Processo @numero_processo@ foi @acao_solicitacao@. Na dúvida converse com o Gestor do Tipo de Controle da sua área.';

                $arrDados = array($strNome, $strEmailSolicitante, $strNumeroProcesso, $strStatus, true, $strAssunto, $strConteudo);
                $isContatoVazio = 0;
                $objMdUtlGestaoAjusteRN->emailRespostaSolicitacao($arrDados);
            }

            return array($isProcessoConcluido, $isContatoVazio);

        } catch(Exception $e){
            throw new InfraException('Erro cadastrando Avaliação .',$e);
        }

    }

  private function _verificaProcessoAssociaAutomaticamente($idProcedimento, $idTipoControle, $isAnalise){
       $staEncaminhamento = $_POST['selAssociarProcFila'] == 'S';

        if($staEncaminhamento){
            $idMdUtlAdmFila = $_POST['selFila'];
            if(!is_null($idMdUtlAdmFila)){
                $nomeFila = $_POST['hdnSelFila'];
                $objControleDsmpRN = new MdUtlControleDsmpRN();
                $objControleDsmpRN->associarFilaAnaliseTriagem(array($idProcedimento, $idMdUtlAdmFila, $idTipoControle, MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO, $nomeFila));

                if ( isset( $_POST['ckbDistAutoTriagAnalise'] ) ) {
                  $objControleDsmpRN->distrAutoAposFinalizar();
                }
            }

            return true;
        }

        return false;
  }

  public function validaDistAutoTriagAnalise( $objTriagAnalise, &$objMdUtlFilaRN, &$validaDistAutoTriagem, &$strNomeUsuarioDistrAuto, &$idUsuarioDistrAuto ){
    $dd = $objMdUtlFilaRN->verificaUsuarioLogadoPertenceFila( 
      [ $objTriagAnalise->getNumIdMdUtlAdmFila() , 1 , true , $objTriagAnalise->getNumIdUsuario() ]
    );

    if ( $dd ) $validaDistAutoTriagem = true;

    $objUsuarioDTO = new UsuarioDTO();
    $objUsuarioDTO->setNumIdUsuario( $objTriagAnalise->getNumIdUsuario() );
    $objUsuarioDTO->retStrNome();
    $objUsuarioDTO->retStrSigla();

    //insere os valores nome e sigla do Usuario no campo hidden para usar na atribuicao automatica
    $objUsuarioDTO = ( new UsuarioRN() )->consultarRN0489( $objUsuarioDTO );

    if( $objUsuarioDTO ){
        $strNomeUsuarioDistrAuto = $objUsuarioDTO->getStrNome() . " ({$objUsuarioDTO->getStrSigla()})";
        $idUsuarioDistrAuto      = $objTriagAnalise->getNumIdUsuario();
    }else{
        $validaDistAutoTriagem = false;
    }
  }

}
