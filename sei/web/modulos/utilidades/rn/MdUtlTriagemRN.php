<?
/**
*
* 06/11/2018 - criado por jaqueline.cast
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlTriagemRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarControlado(MdUtlTriagemDTO $objMdUtlTriagemDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_triagem_cadastrar', __METHOD__, $objMdUtlTriagemDTO);

      $objInfraException = new InfraException();
      $objInfraException->lancarValidacoes();

      $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
      $ret = $objMdUtlTriagemBD->cadastrar($objMdUtlTriagemDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdUtlTriagemDTO $objMdUtlTriagemDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_triagem_alterar', __METHOD__, $objMdUtlTriagemDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      $objInfraException->lancarValidacoes();

      $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
      $objMdUtlTriagemBD->alterar($objMdUtlTriagemDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlTriagemDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_triagem_excluir', __METHOD__, $arrObjMdUtlTriagemDTO);

      $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlTriagemDTO);$i++){
        $objMdUtlTriagemBD->excluir($arrObjMdUtlTriagemDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdUtlTriagemDTO $objMdUtlTriagemDTO){
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_triagem_consultar');

      $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
      $ret = $objMdUtlTriagemBD->consultar($objMdUtlTriagemDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdUtlTriagemDTO $objMdUtlTriagemDTO) {
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_triagem_listar');

      $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
      $ret = $objMdUtlTriagemBD->listar($objMdUtlTriagemDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdUtlTriagemDTO $objMdUtlTriagemDTO){
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_triagem_listar');

      $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
      $ret = $objMdUtlTriagemBD->contar($objMdUtlTriagemDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlTriagemDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_triagem_desativar', __METHOD__, $arrObjMdUtlTriagemDTO);

      $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlTriagemDTO);$i++){
        $objMdUtlTriagemBD->desativar($arrObjMdUtlTriagemDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlTriagemDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_triagem_reativar', __METHOD__, $arrObjMdUtlTriagemDTO);

      $objMdUtlTriagemBD = new MdUtlTriagemBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlTriagemDTO);$i++){
        $objMdUtlTriagemBD->reativar($arrObjMdUtlTriagemDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }
    
  private function _retornaDetalheTriagem(){
        $arrAtividades = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTbAtividade']);
        $strDetalheAtividade = '';

        foreach($arrAtividades as $key => $dadoAtv){
            if($key != 0) {
                $strDetalheAtividade .= ', ';
            }

            $strDetalheAtividade .= array_key_exists(2, $dadoAtv) ? $dadoAtv[2]: '';
        }

        return $strDetalheAtividade;
    }

  protected function cadastrarDadosTriagemControlado($dados){

    try {
 
      $objMdUtlControleDsmpRN   = new MdUtlControleDsmpRN();
      $objMdUtlControleDsmpDTO  = new MdUtlControleDsmpDTO();
      $objRelAtvTriagemRN       = new MdUtlRelTriagemAtvRN();
      $objMdUtlFilaPrmUsuRN     = new MdUtlAdmFilaPrmGrUsuRN;
      $objHistoricoRN           = new MdUtlHistControleDsmpRN();
      $objMdUtlAdmPrmGrRN       = new MdUtlAdmPrmGrRN();
      $isPossuiAnalise          = $dados['hdnIsPossuiAnalise'] == 'S';
      $idProcedimento           = $dados['hdnIdProcedimento'];
      $isRetriagem              = $dados['hdnIdRetriagem'];
      $isRtgAnlCorrecao         = $dados['hdnIdRtgAnlCorrecao'];
      $isAlterar                = $_GET['acao'] == 'md_utl_triagem_alterar';
      $isHabilitar              = false;

      $objControleDsmpDTO       = $objMdUtlControleDsmpRN->getObjControleDsmpAtivo($idProcedimento);
      $isTpProcParametrizado    = $objMdUtlAdmPrmGrRN->verificaTipoProcessoParametrizado(array($objControleDsmpDTO->getNumIdTpProcedimento(), $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp()));

      $objTriagem               = $this->_salvaObjTriagem($dados, $dados['hdnIsPossuiAnalise'], $isTpProcParametrizado);
      $idTriagem                = $objTriagem->getNumIdMdUtlTriagem();
      $arrObjs                  = $objRelAtvTriagemRN->cadastrarObjsTriagem(array($dados, $objTriagem));

      $idFila                   = $objControleDsmpDTO->getNumIdMdUtlAdmFila();
      $arrObjsAtuais            = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimento(array($idProcedimento));
      $tipoRevisao              = $objMdUtlFilaPrmUsuRN->getPercentualTriagemAnalisePorFila($idFila);
      $vlEncaminhamento         = $dados['selEncaminhamentoTriagem'];
      $isHabilitar              = $tipoRevisao == MdUtlAdmFilaRN::$POR_ATIVIDADE  ? $this->verificaHabilitarAtvParaRevisao($idTriagem) : false;
      $novoStatus               = $this->_retornaProximoStatus($isPossuiAnalise, $tipoRevisao, $isHabilitar);
      $arrIdsProcedimentos      = array($idProcedimento);
      $isProcessoConcluido      = 0;

        if (!is_null($arrObjsAtuais)) {
            $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, array($idProcedimento), 'N', 'S', 'S'));

            if ($isRetriagem == 1 && !$isPossuiAnalise) {

                if($novoStatus == MdUtlControleDsmpRN::$AGUARDANDO_REVISAO) {
                    if ($isRtgAnlCorrecao == 1) {
                        $arrDados = array($arrIdsProcedimentos, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE);
                    } else {
                        $arrDados = array($arrIdsProcedimentos, MdUtlControleDsmpRN::$EM_ANALISE);
                    }
                }else{
                    $arrDados = array($arrIdsProcedimentos, MdUtlControleDsmpRN::$FLUXO_FINALIZADO);
                }

                $objMdUtlControleDsmpRN->controlarAjustePrazo($arrDados);
            }


            $isStatusAlteracao = !$isRetriagem && $isAlterar;
            $isStatusRegMudanca = $isRetriagem == 1 && !$isPossuiAnalise;


            if ($isStatusAlteracao || $isStatusRegMudanca) {
                $arrDados = array($arrIdsProcedimentos, $novoStatus);
                $objMdUtlControleDsmpRN->controlarContestacao($arrDados);
                $arrRetorno[$idProcedimento]['ID_CONTESTACAO'] = null;
            }

            $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

            $strDetalhe = $this->_retornaDetalheTriagem();


            if($novoStatus == MdUtlControleDsmpRN::$AGUARDANDO_REVISAO) {
                $this->_continuarFluxoAtendimento($objTriagem, $dados, $isPossuiAnalise, $idFila, $idProcedimento, $arrRetorno, $strDetalhe);
            }else {
                $isProcessoConcluido = $this->_concluirFluxoTriagem($idProcedimento, $arrRetorno, $objTriagem, $strDetalhe, $vlEncaminhamento, $objControleDsmpDTO, $dados);
            }

            $objRNGerais = new MdUtlRegrasGeraisRN();
            $idUsuarioAtb = $arrRetorno[$idProcedimento]['ID_USUARIO_ATRIBUICAO'];
            $objRNGerais->controlarAtribuicao($idProcedimento, $idUsuarioAtb);
        }

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando a Triagem .',$e);
    }

    return $isProcessoConcluido;

  }

    private function _retornaProximoStatus($isPossuiAnalise, $tipoRevisao, $isHabilitar)
    {
        $novoStatus = MdUtlControleDsmpRN::$AGUARDANDO_REVISAO;

        if(!$isPossuiAnalise) {
            if (($tipoRevisao == MdUtlAdmFilaRN::$POR_ATIVIDADE && !$isHabilitar) || $tipoRevisao == MdUtlAdmFilaRN::$SEM_REVISAO) {
                $novoStatus = MdUtlControleDsmpRN::$FLUXO_FINALIZADO;
            }
        }

        return $novoStatus;
    }

    protected function verificaHabilitarAtvParaRevisaoConectado($idTriagem){


        $objMdUtlRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
        $objMdUtlRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
        $objMdUtlRelTriagemAtvDTO->setNumIdMdUtlTriagem($idTriagem);
        $objMdUtlRelTriagemAtvDTO->setStrSinAtvRevAmostragem('S');
        $objMdUtlRelTriagemAtvDTO->retNumIdMdUtlAdmAtividade();

        $count = $objMdUtlRelTriagemAtvRN->contar($objMdUtlRelTriagemAtvDTO);

        return $count > 0;
    }

    private function _concluirFluxoTriagem($idProcedimento, $arrRetorno, $objTriagem, $strDetalhe, $vlEncaminhamento, $objControleDsmpDTO, $dados){
        $objHistoricoRN = new MdUtlHistControleDsmpRN();
        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();

        $objHistoricoRN->concluirControleDsmp(array($idProcedimento, $arrRetorno, MdUtlControleDsmpRN::$CONCLUIR_TRIAGEM, $objTriagem->getNumIdMdUtlTriagem(), $strDetalhe));

        if ($vlEncaminhamento == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA) {
            $idTipoControle = $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp();
            $idNovaFila = $dados['selFila'];
            $objMdUtlControleDsmpRN->associarFilaAnaliseTriagem(array($idProcedimento, $idNovaFila, $idTipoControle, MdUtlControleDsmpRN::$STR_TIPO_ACAO_ASSOCIACAO));
        }else{
            return 1;
        }

        return 0;
    }

  private function _continuarFluxoAtendimento($objTriagem, $dados, $isPossuiAnalise, $idFila, $idProcedimento, $arrRetorno, $strDetalhe){
      $objMdUtlControleDsmpRN   = new MdUtlControleDsmpRN();
      $idTpCtrl                 = $dados['hdnIdTpCtrl'];
      $isCorrecaoTriagem        = array_key_exists('isCorrecaoTriagem', $dados) ? $dados['isCorrecaoTriagem'] : false;
      $strNovoStatus            = !$isPossuiAnalise ? MdUtlControleDsmpRN::$AGUARDANDO_REVISAO : MdUtlControleDsmpRN::$AGUARDANDO_ANALISE;
      $isRetriagem              = array_key_exists('hdnIdRetriagem', $dados) && $dados['hdnIdRetriagem'] == 1 ? $dados['hdnIdRetriagem'] : false;
      $isRtgAnlCorrecao         = array_key_exists('hdnIdRtgAnlCorrecao', $dados) && $dados['hdnIdRtgAnlCorrecao'] == 1 ? $dados['hdnIdRtgAnlCorrecao'] : false;
      $idRevisao                = $arrRetorno[$idProcedimento]['ID_REVISAO'];
      $idAjusTarefa             = $arrRetorno[$idProcedimento]['ID_AJUST_PRAZO'];

      if ($isCorrecaoTriagem || $isRetriagem) {
          $idTriagem = $arrRetorno[$idProcedimento]['ID_TRIAGEM'];
          if (!is_null($idTriagem)) {
              $this->desativarPorIds(array($idTriagem));
          }
      }

      if($isRetriagem){
          $idUsuarioDistrib  = $isPossuiAnalise ?  SessaoSEI::getInstance()->getNumIdUsuario() : null;
          $strNovoStatus     = $isPossuiAnalise ?  MdUtlControleDsmpRN::$EM_ANALISE : MdUtlControleDsmpRN::$AGUARDANDO_REVISAO;
          $dthPrazoTarefa    = null;

          if($isPossuiAnalise){
              $dthPrazoTarefa = $arrRetorno[$idProcedimento]['DTH_PRAZO_TAREFA'];
          }


          if($isRtgAnlCorrecao) {
              $strNovoStatus = $isPossuiAnalise ? MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE : MdUtlControleDsmpRN::$AGUARDANDO_REVISAO;
          }


          $idAnalise      = $strNovoStatus == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE ?  $arrRetorno[$idProcedimento]['ID_ANALISE'] : null;

          $arrParams = array($idProcedimento, $idFila, $idTpCtrl, $strNovoStatus, null, $dados['hdnUndEsforco'], $idUsuarioDistrib, $objTriagem->getNumIdMdUtlTriagem(),  $idAnalise, $idRevisao, $strDetalhe, MdUtlControleDsmpRN::$STR_TIPO_ACAO_RETRIAGEM, null, $idAjusTarefa, $dthPrazoTarefa, null, $arrRetorno[$idProcedimento]['ID_CONTESTACAO']);

      }else{

          $arrParams = array($idProcedimento, $idFila, $idTpCtrl, $strNovoStatus, null, $dados['hdnUndEsforco'], null, $objTriagem->getNumIdMdUtlTriagem(), null, $idRevisao, $strDetalhe, MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM, null, null, null, null, $arrRetorno[$idProcedimento]['ID_CONTESTACAO']);
      }

      $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso($arrParams);

      return true;
  }

  private function _salvaObjTriagem($dados, $strSinAnalise, $isTpProcParametrizado){
      $isSemAnalise = $strSinAnalise == 'N';
      
      $objMdUtlTriagemDTO = new MdUtlTriagemDTO();
      $objMdUtlTriagemDTO->setNumIdMdUtlTriagem(null);
      $objMdUtlTriagemDTO->setDthPrazoResposta($dados['txtPrazoResposta']);
      $objMdUtlTriagemDTO->setStrInformacaoComplementar($dados['txaInformacaoComplementar']);
      $objMdUtlTriagemDTO->setStrSinAtivo('S');
      $objMdUtlTriagemDTO->setStrSinPossuiAnalise($strSinAnalise);
      $objMdUtlTriagemDTO->setDthAtual(InfraData::getStrDataHoraAtual());
      $objMdUtlTriagemDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

      if($isSemAnalise && $isTpProcParametrizado){
          $objMdUtlTriagemDTO->setStrStaEncaminhamentoTriagem($dados['selEncaminhamentoTriagem']);

          if($dados['selEncaminhamentoTriagem'] == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA) {
              $objMdUtlTriagemDTO->setNumIdMdUtlAdmFila($dados['selFila']);
          }
      }

      return $this->cadastrar($objMdUtlTriagemDTO);
  }

  protected function getIdsAtividadesTriagemConectado($idTriagem){
      $idsAtividade = array();
      $objMdUtlRelTriagemAtvRN  = new MdUtlRelTriagemAtvRN();
      $objMdUtlRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
      $objMdUtlRelTriagemAtvDTO->setNumIdMdUtlTriagem($idTriagem);
      $objMdUtlRelTriagemAtvDTO->retNumIdMdUtlAdmAtividade();

      $countIds = $objMdUtlRelTriagemAtvRN->contar($objMdUtlRelTriagemAtvDTO);

      if($countIds > 0) {
        $idsAtividade = InfraArray::converterArrInfraDTO($objMdUtlRelTriagemAtvRN->listar($objMdUtlRelTriagemAtvDTO), 'IdMdUtlAdmAtividade');
      }

    return $idsAtividade;
  }

  protected function retornaArrVinculosAtividadeTriagemConectado($idsAtividade)
  {
    $arrRetorno = array();
    $objRelTriagemAtvRN  = new MdUtlRelTriagemAtvRN();
    $objRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
    $objRelTriagemAtvDTO->setNumIdMdUtlAdmAtividade($idsAtividade, InfraDTO::$OPER_IN);
    $objRelTriagemAtvDTO->retTodos();

    $count = $objRelTriagemAtvRN->contar($objRelTriagemAtvDTO);
    $idsAtividadeVinculado = array();

    if($count > 0){
      $idsAtividadeVinculado = InfraArray::converterArrInfraDTO($objRelTriagemAtvRN->listar($objRelTriagemAtvDTO), 'IdMdUtlAdmAtividade');
    }

    foreach($idsAtividade as $idAtv){
      $arrRetorno[$idAtv] = count($idsAtividadeVinculado) > 0 && in_array($idAtv, $idsAtividadeVinculado) ? true : false;
    }

    return $arrRetorno;

  }

  protected function verificaTiposAnaliseValidoConectado($objTriagem)
  {
      $strAnaliseInicial   = '';
      $objRelTriagemRN     = new MdUtlRelTriagemAtvRN();
      $objRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
      $objRelTriagemAtvDTO->setNumIdMdUtlTriagem($objTriagem->getNumIdMdUtlTriagem());
      $objRelTriagemAtvDTO->retStrSinAnalise();

      $count = $objRelTriagemRN->contar($objRelTriagemAtvDTO);

      if($count > 0) {
          $arrObjs = $objRelTriagemRN->listar($objRelTriagemAtvDTO);

          foreach ($arrObjs as $obj) {
              if ($strAnaliseInicial != '' && $strAnaliseInicial != $obj->getStrSinAnalise()) {
                  return false;
              }

              $strAnaliseInicial = $obj->getStrSinAnalise();
          }

          return $strAnaliseInicial;
      }

      return false;
  }

  protected function buscarObjTriagemPorIdConectado($idTriagem)
  {
       $objTriagemDTO = new MdUtlTriagemDTO();
       $objTriagemDTO->setNumIdMdUtlTriagem($idTriagem);
       $objTriagemDTO->setNumMaxRegistrosRetorno(1);
       $objTriagemDTO->retTodos();
       return $this->consultar($objTriagemDTO);
  }

  protected function desativarPorIdsConectado(Array $idsTriagem){
        if(count($idsTriagem) > 0){
            $objTriagemDTO = new MdUtlTriagemDTO();
            $objTriagemDTO->setNumIdMdUtlTriagem($idsTriagem, InfraDTO::$OPER_IN);
            $objTriagemDTO->retNumIdMdUtlTriagem();
            $objTriagemDTO->setStrSinAtivo('S');
            $count = $this->contar($objTriagemDTO);
            if($count > 0){
                $this->desativar($this->listar($objTriagemDTO));
            }
        }
    }

  protected function getObjDTOAnaliseConectado($idTriagem){
        $objRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();

        $objRelTriagemAtvDTO->setNumIdMdUtlTriagem($idTriagem);
        $objRelTriagemAtvDTO->retTodos();
        $objRelTriagemAtvDTO->retNumIdMdUtlAdmAtvSerieProd();
        $objRelTriagemAtvDTO->retNumUnidadeEsforcoProduto();
        $objRelTriagemAtvDTO->retStrSinObrigatorio();
        $objRelTriagemAtvDTO->retStrNomeSerie();
        $objRelTriagemAtvDTO->retStrNomeAtividade();
        $objRelTriagemAtvDTO->retStrStaAplicabilidadeSerie();
        $objRelTriagemAtvDTO->retStrNomeProduto();
        $objRelTriagemAtvDTO->retNumIdMdUtlAdmTpProduto();
        $objRelTriagemAtvDTO->retNumIdSerieRel();

        return $objRelTriagemAtvDTO;
}

  protected function getNumPrazoAtividadePorTriagemConectado($idTriagem){
      $objRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
      $objRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
      $objRelTriagemAtvDTO->setNumIdMdUtlTriagem($idTriagem);
      $objRelTriagemAtvDTO->retNumPrazoExecucaoAtividade();

      $arrObjRelTriagemAtvDTO = $objRelTriagemAtvRN->listar($objRelTriagemAtvDTO);

      $isMaior = 0;
      foreach($arrObjRelTriagemAtvDTO as $objDTO){
          if($objDTO->getNumPrazoExecucaoAtividade() > $isMaior){
              $isMaior = $objDTO->getNumPrazoExecucaoAtividade();
          }
      }

      return $isMaior;
  }

  protected function getNumPrazoAtividadePorTriagemParaRevConectado($idTriagem){
        $objRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();
        $objRelTriagemAtvDTO = new MdUtlRelTriagemAtvDTO();
        $objRelTriagemAtvDTO->setNumIdMdUtlTriagem($idTriagem);
        $objRelTriagemAtvDTO->retNumPrazoRevisaoAtividade();

        $arrObjRelTriagemAtvDTO = $objRelTriagemAtvRN->listar($objRelTriagemAtvDTO);

        $isMaior = 0;

        foreach($arrObjRelTriagemAtvDTO as $objDTO){
            if($objDTO->getNumPrazoRevisaoAtividade() > $isMaior){
                $isMaior = $objDTO->getNumPrazoRevisaoAtividade();
            }
        }

        return $isMaior;
    }

    protected function validaPrazoMaximoDiasJustificativaConectado($arrParams){
        $qtdDias       = array_key_exists(0, $arrParams) ? $arrParams[0] : null;
        $idControleDsp = array_key_exists(1, $arrParams) ? $arrParams[1] : null;
        $prazo         = 0;

        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();

        $objControleDsmpDTO = $objMdUtlControleDsmpRN->getObjControleDsmpPorId($idControleDsp);

        $idTriagem  = $objControleDsmpDTO->getNumIdMdUtlTriagem();

        $arrStatusAnalise = array(MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE);

        if(in_array($objControleDsmpDTO->getStrStaAtendimentoDsmp(), $arrStatusAnalise)){
            $prazo = $this->getNumPrazoAtividadePorTriagem($idTriagem);
        }else{
            $prazo = $this->getNumPrazoAtividadePorTriagemParaRev($idTriagem);
        }

        $qtdDias = intval($qtdDias);
        $prazo = intval($prazo);

        if ($qtdDias > $prazo) {
            return false;
        }

        return true;

    }


    protected function checarDadosTriagemControlado($idUsuario){
     
        $objMdUtlTriagemDTO = new MdUtlTriagemDTO();
        $objMdUtlTriagemDTO->adicionarCriterio(array('Atual','IdUsuario'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array(null, null),InfraDTO::$OPER_LOGICO_OR);
        $objMdUtlTriagemDTO->retNumIdMdUtlTriagem();
        $objRN = new MdUtlTriagemRN();
        $numRegistros = $objRN->contar($objMdUtlTriagemDTO);

        if ($numRegistros > 0) {
            $arrDadosTriagem = $objRN->listar($objMdUtlTriagemDTO);
            foreach ($arrDadosTriagem as $dadoTriagem) {
                $objMdUtlTriagemDTO = new MdUtlTriagemDTO();
                $objMdUtlTriagemDTO->setNumIdMdUtlTriagem($dadoTriagem->getNumIdMdUtlTriagem());
                $objMdUtlTriagemDTO->setDthAtual(InfraData::getStrDataHoraAtual());
                $objMdUtlTriagemDTO->setNumIdUsuario($idUsuario);
                $objRN->alterar($objMdUtlTriagemDTO);
            }
        }
    }



}
