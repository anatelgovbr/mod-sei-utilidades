<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 06/11/2018 - criado por jaqueline.cast
*
* Vers�o do Gerador de C�digo: 1.42.0
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
          $dados                     = $arrParams[0];
          $isTpProcParametrizado     = $arrParams[1];
          $idFila                    = $dados['hdnIdFilaAtiva'];
          $objMdUtlFilaPrmUsuRN      = new MdUtlAdmFilaPrmGrUsuRN();
          $objMdUtlControleDsmpRN     = new MdUtlControleDsmpRN();
          $objHistoricoRN            = new MdUtlHistControleDsmpRN();
          $idProcedimento            = $dados['hdnIdProcedimento'];
          $vlEncaminhamento          = $dados['selEncaminhamentoAnl'];
          $idTpCtrl                  = $dados['hdnIdTpCtrl'];
          $arrStrIdsSel              = explode(',',$dados['hdnItensSelecionados']);

          //Cadastrando o obj de analise com base no relacionamento do novo status
          $strDetalhe = '';
          $id = null;

          $formatarDetalhe = function ($value, $key) use (&$strDetalhe, $arrStrIdsSel, &$id){
              $arrStr = explode('_', $key);
              //$id     = count($arrStr) > 0 && $arrStr[0] == 'idSerieProd' ? $value : $id;
              if(count($arrStr) > 0 && $arrStr[0] == 'nomeProduto') {
                  $idSelecionado = array_key_exists('1', $arrStr) ? $arrStr[1] : null;
                  if(!is_null($idSelecionado)) {
                      if (in_array($idSelecionado, $arrStrIdsSel)) {
                          $strDetalhe .= $strDetalhe != '' ? ', ' : '';
                          $strDetalhe .= $value;
                      }
                  }
              }
          };

          array_walk( $dados, $formatarDetalhe);

          $objAnalise = $this->_salvaObjAnalise($dados, $isTpProcParametrizado);
          $numPercentualRevisao     = $objMdUtlFilaPrmUsuRN->getPercentualTriagemAnalisePorFila($idFila);
          $arrObjsAtuais = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimento(array($idProcedimento));

          if (!is_null($arrObjsAtuais)) {
              $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, array($idProcedimento), 'N', 'S'));
              $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

              if ($numPercentualRevisao == 100) {
                  $this->_continuarFluxoAnalise($dados, $objAnalise, $strDetalhe, $arrRetorno);
              } else {
                  $objHistoricoRN->concluirControleDsmp(array($idProcedimento, $arrRetorno, MdUtlControleDsmpRN::$CONCLUIR_ANALISE, $objAnalise->getNumIdMdUtlAnalise(), $strDetalhe));

                  if ($vlEncaminhamento == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA) {
                      $idNovaFila = $dados['selFila'];
                      $objMdUtlControleDsmpRN->associarFilaAnaliseTriagem(array($idProcedimento, $idNovaFila, $idTpCtrl, MdUtlControleDsmpRN::$STR_TIPO_ACAO_ASSOCIACAO));
                  }
              }

              $objRNGerais = new MdUtlRegrasGeraisRN();
              $idUsuarioAtb = $arrRetorno[$idProcedimento]['ID_USUARIO_ATRIBUICAO'];
              $objRNGerais->controlarAtribuicao($idProcedimento, $idUsuarioAtb);
          }

          return true;
      }catch(Exception $e){
          throw new InfraException('Erro cadastrando a Triagem .',$e);
      }
  }

  private function _continuarFluxoAnalise($dados, $objAnalise, $strDetalhe, $arrRetorno)
    {
        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $idProcedimento        = $dados['hdnIdProcedimento'];
        $idFila                = $dados['hdnIdFilaAtiva'];
        $undEsforco            = $this->_getUnidadeEsforcoAnalise($dados);
        $idTpCtrl              = $dados['hdnIdTpCtrl'];

        $idTriagem = $arrRetorno[$idProcedimento]['ID_TRIAGEM'];
        $arrParams = array($idProcedimento, $idFila, $idTpCtrl, MdUtlControleDsmpRN::$AGUARDANDO_REVISAO, null, $undEsforco, null, $idTriagem, $objAnalise->getNumIdMdUtlAnalise(), null, $strDetalhe, MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE);

        $idAntigaAnalise = $arrRetorno[$idProcedimento]['ID_ANALISE'];
        if (!is_null($idAntigaAnalise)) {
            $this->desativarPorIds(array($idAntigaAnalise));
        }

        $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso($arrParams);
    }

  private function _getUnidadeEsforcoAnalise($dados){
        $numItensSelecionados = split(',', $dados['hdnItensSelecionados']);
        $numRegistros = count($numItensSelecionados);

        $numUndEsforco = [];
        $somaUndEsforco = 0;

        if($numRegistros > 0){
            for($i = 0; $i < $numRegistros; $i++){
                $id = $numItensSelecionados[$i];
                array_push($numUndEsforco, $dados['undEsforco_'.$id]);
                $somaUndEsforco += $numUndEsforco[$i];
            }
        }

        return $somaUndEsforco;
    }

  private function _salvaObjAnalise($dados, $isTpProcParametrizado){

        $arrStrIdsSel           = explode(',',$dados['hdnItensSelecionados']);
        $idFilaEncaminhamento   = $dados['selFila'];
        $idEncaminhamentoAnl    = $dados['selEncaminhamentoAnl'];

        $objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();

        $objMdUtlAnaliseDTO->setStrInformacoesComplementares($dados['txaInformacaoComplementar']);
        $objMdUtlAnaliseDTO->setStrSinAtivo('S');

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

              if ($objDocumentoRN->contarRN0007($objDocumentoDTO) > 0) {
                  $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);
                  $objRelAnaliseProduto->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
                  $objRelAnaliseProduto->setNumIdSerie($objDocumentoDTO->getNumIdSerie());
              }
          }else{
              $objRelAnaliseProduto->setDblIdDocumento(null);
              $objRelAnaliseProduto->setNumIdSerie(null);
          }

          $objRelAnaliseProduto->setNumIdMdUtlAdmAtividade($dados['idAtividade_'. $arrStrIdsSel[$i]]);
          $objRelAnaliseProduto->setNumIdMdUtlAnalise($idMdUtlAnalise);
          $objRelAnaliseProduto->setNumIdMdUtlRelTriagemAtv($dados['idRelTriagem_'. $arrStrIdsSel[$i]]);
          $objRelAnaliseProduto->setNumIdMdUtlAdmTpProduto($dados['idProduto_'. $arrStrIdsSel[$i]]);
          $objRelAnaliseProduto->setStrObservacaoAnalise($dados['observacao_' . $arrStrIdsSel[$i]]);

          $objRelSerieAnaliseRN->cadastrar($objRelAnaliseProduto);
      }
  }

  protected function desativarControlado($arrObjMdUtlAnaliseDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_analise_desativar', __METHOD__, $arrObjMdUtlAnaliseDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

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
            $objAnaliseDTO->retNumIdMdUtlAnalise();
            $objAnaliseDTO->setStrSinAtivo('S');
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

}
