<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ï¿½ REGIï¿½O
*
* 06/11/2018 - criado por jaqueline.cast
*
* Versï¿½o do Gerador de Cï¿½digo: 1.42.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAnaliseRN extends InfraRN {

  public function __construct(){
        parent::__construct();
    }

  protected function inicializarObjInfraIBanco(){
        return BancoSEI::getInstance();
    }

  protected function cadastrarControlado(MdUtlAnaliseDTO $objMdUtlAnaliseDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_analise_cadastrar', __METHOD__, $objMdUtlAnaliseDTO);

      $objMdUtlAnaliseBD = new MdUtlAnaliseBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAnaliseBD->cadastrar($objMdUtlAnaliseDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdUtlAnaliseDTO $objMdUtlAnaliseDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_analise_alterar', __METHOD__, $objMdUtlAnaliseDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();


      $objInfraException->lancarValidacoes();

      $objMdUtlAnaliseBD = new MdUtlAnaliseBD($this->getObjInfraIBanco());
      $objMdUtlAnaliseBD->alterar($objMdUtlAnaliseDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAnaliseDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_analise_excluir', __METHOD__, $arrObjMdUtlAnaliseDTO);

      $objMdUtlAnaliseBD = new MdUtlAnaliseBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAnaliseDTO);$i++){
        $objMdUtlAnaliseBD->excluir($arrObjMdUtlAnaliseDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdUtlAnaliseDTO $objMdUtlAnaliseDTO){
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_analise_consultar');

      $objMdUtlAnaliseBD = new MdUtlAnaliseBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAnaliseBD->consultar($objMdUtlAnaliseDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdUtlAnaliseDTO $objMdUtlAnaliseDTO) {
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_analise_listar');

      $objMdUtlAnaliseBD = new MdUtlAnaliseBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAnaliseBD->listar($objMdUtlAnaliseDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdUtlAnaliseDTO $objMdUtlAnaliseDTO){
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_analise_listar');

      $objMdUtlAnaliseBD = new MdUtlAnaliseBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAnaliseBD->contar($objMdUtlAnaliseDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

  protected function cadastrarDadosAnaliseControlado($arrParams){
      try {
          $isProcessoConcluido       = 0;
          $dados                     = $arrParams[0];
          $isTpProcParametrizado     = $arrParams[1];
          $isAlterar                 = $arrParams[2];
          $idFila                    = $dados['hdnIdFilaAtiva'];
          $objMdUtlFilaPrmUsuRN      = new MdUtlAdmFilaPrmGrUsuRN();
          $objMdUtlControleDsmpRN    = new MdUtlControleDsmpRN();
          $objHistoricoRN            = new MdUtlHistControleDsmpRN();
          $objMdUtlTriagemRN         = new MdUtlTriagemRN();

          $idProcedimento            = $dados['hdnIdProcedimento'];
          $vlEncaminhamento          = $dados['selEncaminhamentoAnl'];
          $idTpCtrl                  = $dados['hdnIdTpCtrl'];

          // Sanitizando Itens Selecionados:
          $dados['hdnItensSelecionados'] = implode(',', array_unique(explode(',',$dados['hdnItensSelecionados'])));

          $arrStrIdsSel              = explode(',',$dados['hdnItensSelecionados']);

          //Cadastrando o obj de analise com base no relacionamento do novo status
          $strDetalhe = '';
          $id = null;

          $formatarDetalhe = function ($value, $key) use (&$strDetalhe, $arrStrIdsSel, &$id, $dados){
              $arrStr = explode('_', $key);
              //$id     = count($arrStr) > 0 && $arrStr[0] == 'idSerieProd' ? $value : $id;
              if(count($arrStr) > 0 && $arrStr[0] == 'nomeProduto') {
                  $idSelecionado = array_key_exists('1', $arrStr) ? $arrStr[1] : null;
                  if(!is_null($idSelecionado)) {
                      if (in_array($idSelecionado, $arrStrIdsSel)) {
                          $temChave = 'numeroSEI_'.$arrStr[1];
                          $numSei = array_key_exists( $temChave , $dados ) ? ' ' . $dados[$temChave] : '';
                          $strDetalhe .= $strDetalhe != '' ? ', ' : '';
                          $strDetalhe .= $value . $numSei;
                      }
                  }
              }
          };

          array_walk( $dados, $formatarDetalhe);

          $objAnalise    = $this->_salvaObjAnalise($dados, $isTpProcParametrizado);
          $tipoRevisao   = $objMdUtlFilaPrmUsuRN->getPercentualTriagemAnalisePorFila($idFila);
          $arrObjsAtuais = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimento(array($idProcedimento));

          if (!is_null($arrObjsAtuais)) {

              $arrIdsProcedimentos = array($idProcedimento);

              $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, $arrIdsProcedimentos, 'N', 'S', 'S'));

              switch ($tipoRevisao) {
                  case MdUtlAdmFilaRN::$TOTAL:

                      $arrDados = array($arrIdsProcedimentos, MdUtlControleDsmpRN::$AGUARDANDO_REVISAO);
                      if($isAlterar) {
                          $objMdUtlControleDsmpRN->controlarContestacao($arrDados);
                      }

                      $objMdUtlControleDsmpRN->controlarAjustePrazo($arrDados);
                      $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

                      $this->_continuarFluxoAnalise($dados, $objAnalise, $strDetalhe, $arrRetorno);
                      break;
                  case MdUtlAdmFilaRN::$POR_ATIVIDADE:
                      $idTriagem =  $arrRetorno[$idProcedimento]['ID_TRIAGEM'];
                      $isHabilitar = $objMdUtlTriagemRN->verificaHabilitarAtvParaRevisao($idTriagem);

                      $nextStatus = $isHabilitar ? MdUtlControleDsmpRN::$AGUARDANDO_REVISAO : MdUtlControleDsmpRN::$FLUXO_FINALIZADO;
                      $arrDados = array($arrIdsProcedimentos, $nextStatus);
                      $objMdUtlControleDsmpRN->controlarAjustePrazo($arrDados);
                      if($isAlterar) {
                          $objMdUtlControleDsmpRN->controlarContestacao($arrDados);
                      }

                      $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

                      if ($isHabilitar) {
                          $this->_continuarFluxoAnalise($dados, $objAnalise, $strDetalhe, $arrRetorno);
                      } else {
                          $objHistoricoRN->concluirControleDsmp(array($idProcedimento, $arrRetorno, MdUtlControleDsmpRN::$CONCLUIR_ANALISE, $objAnalise->getNumIdMdUtlAnalise(), $strDetalhe));

                          if ($vlEncaminhamento == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA) {
                              $idNovaFila = $dados['selFila'];
                              $objMdUtlControleDsmpRN->associarFilaAnaliseTriagem(array($idProcedimento, $idNovaFila, $idTpCtrl, MdUtlControleDsmpRN::$STR_TIPO_ACAO_ASSOCIACAO));
                              
                              //distribui, após finalizar/associar a Fila, para o ultimo analista
                              if ( isset( $_POST['ckbDistAutoParaMim'] ) ) {
                                $objMdUtlControleDsmpRN->distrAutoAposFinalizar();
                              }
                          }else{
                              $isProcessoConcluido = 1;
                          }
                      }

                      break;
                  case MdUtlAdmFilaRN::$SEM_REVISAO:
                      $arrDados = array($arrIdsProcedimentos, MdUtlControleDsmpRN::$FLUXO_FINALIZADO);
                      $objMdUtlControleDsmpRN->controlarAjustePrazo($arrDados);

                      if ($isAlterar) {
                           $objMdUtlControleDsmpRN->controlarContestacao($arrDados);
                       }

                      $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

                      $objHistoricoRN->concluirControleDsmp(array($idProcedimento, $arrRetorno, MdUtlControleDsmpRN::$CONCLUIR_ANALISE, $objAnalise->getNumIdMdUtlAnalise(), $strDetalhe));

                      if ($vlEncaminhamento == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA) {
                          $idNovaFila = $dados['selFila'];
                          $objMdUtlControleDsmpRN->associarFilaAnaliseTriagem(array($idProcedimento, $idNovaFila, $idTpCtrl, MdUtlControleDsmpRN::$STR_TIPO_ACAO_ASSOCIACAO));

                          //distribui, após finalizar/associar a Fila, para o ultimo analista
                          if ( isset( $_POST['ckbDistAutoParaMim'] ) ) {
                            $objMdUtlControleDsmpRN->distrAutoAposFinalizar();
                          }
                      }else{
                          $isProcessoConcluido = 1;
                      }
                      break;

              }

              $objRNGerais = new MdUtlRegrasGeraisRN();
              $idUsuarioAtb = $arrRetorno[$idProcedimento]['ID_USUARIO_ATRIBUICAO'];
              $objRNGerais->controlarAtribuicao($idProcedimento, $idUsuarioAtb);
              $this->_atualizaObjAnalise($idProcedimento, $objAnalise, $idTpCtrl, $idUsuarioAtb);
          }

          return $isProcessoConcluido;
      }catch(Exception $e){
          throw new InfraException('Erro cadastrando a Triagem .',$e);
      }
  }

    private function _atualizaObjAnalise($idProcedimento, $objMdUtlAnaliseDTO, $idTpCtrl, $idUsuarioAtb)
    {
        $regrasGerais = new MdUtlRegrasGeraisRN();
        $objTodosHistDesmp = $regrasGerais->recuperarObjHistorico($idProcedimento);
        $arrParams = $regrasGerais->regraAcaoAnalise($objTodosHistDesmp, $objMdUtlAnaliseDTO);
        $arrDadosPercentualDesempenho = MdUtlAdmPrmGrUsuINT::retornaDadosPercentualDesempenho($arrParams['tempoExecucao'] ? $arrParams['tempoExecucao'] : 0, $idTpCtrl, $idUsuarioAtb);

        $objMdUtlAnaliseDTO->setDthInicio($arrParams['dataInicio'] ? $arrParams['dataInicio'] : '');
        $objMdUtlAnaliseDTO->setDthPrazo($arrParams['dataPrazo'] ? $arrParams['dataPrazo'] : '');
        $objMdUtlAnaliseDTO->setNumTempoExecucao(isset($arrParams['tempoExecucao']) ? $arrParams['tempoExecucao'] : '');

        $objMdUtlAnaliseDTO->setStrStaTipoPresenca($arrDadosPercentualDesempenho['strStaTipoPresenca']);
        $objMdUtlAnaliseDTO->setNumTempoExecucaoAtribuido($arrParams['tempoExecucaoAtribuido'] ?: '');
        $objMdUtlAnaliseDTO->setNumPercentualDesempenho($arrDadosPercentualDesempenho['numPercentualDesempenho']);

        return $this->alterar($objMdUtlAnaliseDTO);
    }

  private function _continuarFluxoAnalise($dados, $objAnalise, $strDetalhe, $arrRetorno)
    {
        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $idProcedimento         = $dados['hdnIdProcedimento'];
        $idFila                 = $dados['hdnIdFilaAtiva'];
        $tempoExecucao             = $this->_getTempoExecucaoAnalise($dados);
        $idTpCtrl               = $dados['hdnIdTpCtrl'];
        $idRevisao              = $arrRetorno[$idProcedimento]['ID_REVISAO'];

        $idTriagem = $arrRetorno[$idProcedimento]['ID_TRIAGEM'];
        $idAntigaAnalise = $arrRetorno[$idProcedimento]['ID_ANALISE'];

        if (!is_null($idAntigaAnalise)) {
            $this->desativarPorIds(array($idAntigaAnalise));
        }

        $arrParams = array($idProcedimento, $idFila, $idTpCtrl, MdUtlControleDsmpRN::$AGUARDANDO_REVISAO, null, $tempoExecucao, null, $idTriagem, $objAnalise->getNumIdMdUtlAnalise(), $idRevisao, $strDetalhe, MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE);
        $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso($arrParams);
    }

  private function _getTempoExecucaoAnalise($dados){
    
        // Sanitizando Itens Selecionados:
        $dados['hdnItensSelecionados'] = implode(',', array_unique(explode(',',$dados['hdnItensSelecionados'])));

        $numItensSelecionados = explode(',',$dados['hdnItensSelecionados']);
        $numRegistros = count($numItensSelecionados);

        $numTmpExecucao = [];
        $somaTmpExecucao = 0;

        if($numRegistros > 0){
            for($i = 0; $i < $numRegistros; $i++){
                $id = $numItensSelecionados[$i];
                array_push($numTmpExecucao, $dados['TmpExecucao_'.$id]);
                $somaTmpExecucao += $numTmpExecucao[$i];
            }
        }

        return $somaTmpExecucao;
    }

  private function _salvaObjAnalise($dados, $isTpProcParametrizado){

    // Sanitizando Itens Selecionados:
    $dados['hdnItensSelecionados'] = implode(',', array_unique(explode(',',$dados['hdnItensSelecionados'])));

    $arrStrIdsSel           = explode(',',$dados['hdnItensSelecionados']);
    $idFilaEncaminhamento   = $dados['selFila'];
    $idEncaminhamentoAnl    = $dados['selEncaminhamentoAnl'];
    $objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();
    $objMdUtlAnaliseDTO->setStrInformacoesComplementares(trim($dados['txaInformacaoComplementar']));
    $objMdUtlAnaliseDTO->setStrSinAtivo('S');
    $objMdUtlAnaliseDTO->setDthAtual(InfraData::getStrDataHoraAtual());
    $objMdUtlAnaliseDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

    if (isset($dados['ckbDistAutoParaMim'])) $objMdUtlAnaliseDTO->setStrDistAutoParaMim( $dados['ckbDistAutoParaMim'] );
    else $objMdUtlAnaliseDTO->setStrDistAutoParaMim( null );

    if($isTpProcParametrizado) {
        $objMdUtlAnaliseDTO->setStrStaEncaminhamentoAnalise($idEncaminhamentoAnl);
        $objMdUtlAnaliseDTO->setNumIdMdUtlAdmFila($idFilaEncaminhamento);
    }

    $objMdUtlAnaliseDTO->retTodos();

    $objMdUtlAnaliseDTO   = $this->cadastrar($objMdUtlAnaliseDTO);
    if(!is_null($objMdUtlAnaliseDTO)) {
        $this->_cadastrarRelacionamentosAnaliseProduto($arrStrIdsSel, $dados, $objMdUtlAnaliseDTO->getNumIdMdUtlAnalise());

        return $objMdUtlAnaliseDTO;
    }

    return false;
  }

  private function _cadastrarRelacionamentosAnaliseProduto($arrStrIdsSel, $dados, $idMdUtlAnalise){
      $objDocumentoRN       = new DocumentoRN();
      $objRelSerieAnaliseRN = new MdUtlRelAnaliseProdutoRN();
      $idProcedimento       = $dados['hdnIdProcedimento'];

      for ($i = 0; $i < count($arrStrIdsSel); $i++) {

          $objRelAnaliseProduto = new MdUtlRelAnaliseProdutoDTO();
          $inputDoc = 'numeroSEI_'  . $arrStrIdsSel[$i];

          if(array_key_exists($inputDoc, $dados)) {
              $objDocumentoDTO = new DocumentoDTO();
              $objDocumentoDTO->setStrProtocoloDocumentoFormatado($dados['numeroSEI_' . $arrStrIdsSel[$i]]);
              $objDocumentoDTO->setDblIdProcedimento($idProcedimento);
              $objDocumentoDTO->retTodos();
              $objDocumentoDTO->retNumIdSerie();
              $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
              $objDocumentoDTO->setNumMaxRegistrosRetorno(1);

              if ($objDocumentoRN->contarRN0007($objDocumentoDTO) > 0) {
                  $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);
                  $objRelAnaliseProduto->setNumIdSerie($objDocumentoDTO->getNumIdSerie());
                  $objRelAnaliseProduto->setStrProtocoloFormatado($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
              }
          }else{
              $objRelAnaliseProduto->setStrProtocoloFormatado(null);
              $objRelAnaliseProduto->setNumIdSerie(null);
          }

          $objRelAnaliseProduto->setNumIdMdUtlAdmAtividade($dados['idAtividade_'. $arrStrIdsSel[$i]]);
          $objRelAnaliseProduto->setNumIdMdUtlAnalise($idMdUtlAnalise);
          $objRelAnaliseProduto->setNumIdMdUtlRelTriagemAtv($dados['idRelTriagem_'. $arrStrIdsSel[$i]]);
          $objRelAnaliseProduto->setNumIdMdUtlAdmTpProduto($dados['idProduto_'. $arrStrIdsSel[$i]]);
          $objRelAnaliseProduto->setStrObservacaoAnalise(trim($dados['observacao_' . $arrStrIdsSel[$i]]));

          $objRelSerieAnaliseRN->cadastrar($objRelAnaliseProduto);
      }
  }

  protected function desativarControlado($arrObjMdUtlAnaliseDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_analise_desativar', __METHOD__, $arrObjMdUtlAnaliseDTO);

      //Regras de Negocio

      $objMdUtlAnaliseBD = new MdUtlAnaliseBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAnaliseDTO);$i++){
        $objMdUtlAnaliseBD->desativar($arrObjMdUtlAnaliseDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function verificarExistenciaAnaliseConectado($idProcedimento){

      $MdUtlTriagemDTO = new MdUtlTriagemDTO();
      $MdUtlTriagemRN = new MdUtlTriagemRN();

      $MdUtlTriagemDTO->setDblIdProcedimento($idProcedimento);
      $MdUtlTriagemDTO->retTodos();
      $MdUtlTriagemDTO->setStrSinAtivo('S');
      $MdUtlTriagem = $MdUtlTriagemRN->consultar($MdUtlTriagemDTO);

      if(count($MdUtlTriagem)>0) {
          $possuiAnalise = $MdUtlTriagem->getStrSinPossuiAnalise() == 'S' ? true : false;
      }else{
          $possuiAnalise =false;
      }
      return $possuiAnalise;

  }

  protected function desativarPorIdsConectado(Array $idsAnalise){
        if(count($idsAnalise) > 0){
            $objAnaliseDTO = new MdUtlAnaliseDTO();
            $objAnaliseDTO->setNumIdMdUtlAnalise($idsAnalise, InfraDTO::$OPER_IN);
            $objAnaliseDTO->setStrSinAtivo('S');
            $objAnaliseDTO->retNumIdMdUtlAnalise();
            $objAnaliseDTO->retStrSinAtivo();
            $count = $this->contar($objAnaliseDTO);
            if($count > 0){
                $this->desativar($this->listar($objAnaliseDTO));
            }
        }
    }

  protected function getNumPrazoAtividadePorAnaliseConectado($idAnalise){
      $objRelAnaliseProdutoRN  = new MdUtlRelAnaliseProdutoRN();
      $objRelAnaliseProdutoDTO = new MdUtlRelAnaliseProdutoDTO();
      $objRelAnaliseProdutoDTO->setNumIdMdUtlAnalise($idAnalise);
      $objRelAnaliseProdutoDTO->retNumPrazoRevisaoAtividade();

      $arrObjRelAnaliseProdutoDTO = $objRelAnaliseProdutoRN->listar($objRelAnaliseProdutoDTO);

      $isMaior = 0;

      foreach($arrObjRelAnaliseProdutoDTO as $objDTO){
          if($objDTO->getNumPrazoRevisaoAtividade() > $isMaior){
              $isMaior = $objDTO->getNumPrazoRevisaoAtividade();
          }
      }

      return $isMaior;
  }

    protected function checarDadosAnaliseControlado($idUsuario){

        $objMdUtlAnaliseDTO = new MdUtlAnaliseDTO($idUsuario);
        $objMdUtlAnaliseDTO->adicionarCriterio(array('Atual','IdUsuario'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array(null, null),InfraDTO::$OPER_LOGICO_OR);
        $objMdUtlAnaliseDTO->retNumIdMdUtlAnalise();
        $objRN = new MdUtlAnaliseRN();
        $numRegistros = $objRN->contar($objMdUtlAnaliseDTO);

        if ($numRegistros > 0) {
            $arrDadosAnalise = $objRN->listar($objMdUtlAnaliseDTO);
            foreach ($arrDadosAnalise as $dadoAnalise) {
                $objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();
                $objMdUtlAnaliseDTO->setNumIdMdUtlAnalise($dadoAnalise->getNumIdMdUtlAnalise());
                $objMdUtlAnaliseDTO->setDthAtual(InfraData::getStrDataHoraAtual());
                $objMdUtlAnaliseDTO->setNumIdUsuario($idUsuario);
                $objRN->alterar($objMdUtlAnaliseDTO);
            }
        }
    }

    public function getAnalisePorId( $idAnalise ){
    
      if( is_null( $idAnalise ) ) return null;
  
      $objAnaliseDTO = new MdUtlAnaliseDTO();
      $objAnaliseDTO->setNumIdMdUtlAnalise( $idAnalise );
      $objAnaliseDTO->retTodos();
  
      return $this->consultar( $objAnaliseDTO );
    }

}
