<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
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
      $isPossuiAnalise          = $dados['hdnIsPossuiAnalise'] == 'S';
      $objMdUtlControleDsmpRN    = new MdUtlControleDsmpRN();
      $objRelAtvTriagemRN       = new MdUtlRelTriagemAtvRN();
      $objMdUtlFilaPrmUsuRN     = new MdUtlAdmFilaPrmGrUsuRN;
      $objHistoricoRN           = new MdUtlHistControleDsmpRN();
      $objMdUtlAdmPrmGrRN       = new MdUtlAdmPrmGrRN();
      $idProcedimento           = $dados['hdnIdProcedimento'];
      $objControleDsmpDTO        = $objMdUtlControleDsmpRN->getObjControleDsmpAtivo($idProcedimento);
      $isTpProcParametrizado    = $objMdUtlAdmPrmGrRN->verificaTipoProcessoParametrizado(array($objControleDsmpDTO->getNumIdTpProcedimento(), $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp()));
      $objTriagem               = $this->_salvaObjTriagem($dados, $dados['hdnIsPossuiAnalise'], $isTpProcParametrizado);
      $arrObjs                  = $objRelAtvTriagemRN->cadastrarObjsTriagem(array($dados, $objTriagem));
      $idFila                   = $objControleDsmpDTO->getNumIdMdUtlAdmFila();
      $arrObjsAtuais            = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimento(array($idProcedimento));
      $numPercentualRevisao     = $objMdUtlFilaPrmUsuRN->getPercentualTriagemAnalisePorFila($idFila);
      $vlEncaminhamento         = $dados['selEncaminhamentoTriagem'];

        if (!is_null($arrObjsAtuais)) {
            $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, array($idProcedimento), 'N', 'S'));
            $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);
            $strDetalhe = $this->_retornaDetalheTriagem();

            if (($isPossuiAnalise) || (!$isPossuiAnalise && $numPercentualRevisao == 100)) {
                $this->_continuarFluxoTriagem($objTriagem, $dados, $isPossuiAnalise, $idFila, $idProcedimento, $arrRetorno, $strDetalhe);
            } else {
                $objHistoricoRN->concluirControleDsmp(array($idProcedimento, $arrRetorno, MdUtlControleDsmpRN::$CONCLUIR_TRIAGEM, $objTriagem->getNumIdMdUtlTriagem(), $strDetalhe));

                if ($vlEncaminhamento == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA) {
                    $idTipoControle = $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp();
                    $idNovaFila = $dados['selFila'];
                    $objMdUtlControleDsmpRN->associarFilaAnaliseTriagem(array($idProcedimento, $idNovaFila, $idTipoControle, MdUtlControleDsmpRN::$STR_TIPO_ACAO_ASSOCIACAO));
                }
            }

            $objRNGerais = new MdUtlRegrasGeraisRN();
            $idUsuarioAtb = $arrRetorno[$idProcedimento]['ID_USUARIO_ATRIBUICAO'];
            $objRNGerais->controlarAtribuicao($idProcedimento, $idUsuarioAtb);
        }

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando a Triagem .',$e);
    }

  }

  private function _continuarFluxoTriagem($objTriagem, $dados, $isPossuiAnalise, $idFila, $idProcedimento, $arrRetorno, $strDetalhe){
      $objMdUtlControleDsmpRN    = new MdUtlControleDsmpRN();
      $idTpCtrl                 = $dados['hdnIdTpCtrl'];
      $isCorrecaoTriagem        = array_key_exists('isCorrecaoTriagem', $dados) ? $dados['isCorrecaoTriagem'] : false;
      $strNovoStatus            = !$isPossuiAnalise ? MdUtlControleDsmpRN::$AGUARDANDO_REVISAO : MdUtlControleDsmpRN::$AGUARDANDO_ANALISE;

      if ($isCorrecaoTriagem) {
          $idTriagem = $arrRetorno[$idProcedimento]['ID_TRIAGEM'];
          if (!is_null($idTriagem)) {
              $this->desativarPorIds(array($idTriagem));
          }
      }

      $arrParams = array($idProcedimento, $idFila, $idTpCtrl, $strNovoStatus, null, $dados['hdnUndEsforco'], null, $objTriagem->getNumIdMdUtlTriagem(), null, null, $strDetalhe, MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM);
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


}
