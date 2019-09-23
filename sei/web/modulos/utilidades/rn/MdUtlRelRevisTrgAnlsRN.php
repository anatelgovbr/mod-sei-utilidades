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

  protected function listarConectado(MdUtlRelRevisTrgAnlsDTO $objMdUtlRelRevisTrgAnlsDTO) {
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_rel_revis_trg_anls_listar');

      $objMdUtlRelRevisTrgAnlsBD = new MdUtlRelRevisTrgAnlsBD($this->getObjInfraIBanco());
      $ret = $objMdUtlRelRevisTrgAnlsBD->listar($objMdUtlRelRevisTrgAnlsDTO);

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

      $objHistoricoRN           = new MdUtlHistControleDsmpRN();
      $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();

      $idProcedimento = $_POST['hdnIdProcedimento'];
      $idFila         = $_POST['hdnIdFilaAtiva'];
      $idTpCtrl       = $_POST['hdnIdTpCtrl'];
      $undEsforco     = $_POST['hdnUndEsforco'];
      $strDetalheRev  = $_POST['hdnEncaminhamento'];
      $idUnidade      = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
      $idEncaminhamento = $_POST['selEncaminhamento'];
      $isAnalise     = $_GET['acao'] == 'md_utl_revisao_analise_cadastrar'? true : false;
      $objMdUtlRevisaoRN  = new MdUtlRevisaoRN();
      $idRevisao = $objMdUtlRevisaoRN->salvarObjRevisao();
      $objRNGerais        = new MdUtlRegrasGeraisRN();

      $this->_salvarObjsRelacionadosRevisao($idRevisao, $isAnalise);

      switch ($idEncaminhamento){

          case MdUtlRevisaoRN::$VOLTAR_PARA_FILA:
              $strNovoStatus = $_GET['acao'] == 'md_utl_revisao_analise_cadastrar' ? MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE : MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM;
              $arrObjsAtuais   = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimento(array($idProcedimento));

              if(!is_null($arrObjsAtuais)) {
                  $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, array($idProcedimento), 'N','S', 'S'));
                  $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

                  $idTriagem = $arrRetorno[$idProcedimento]['ID_TRIAGEM'];
                  $idAnalise = $arrRetorno[$idProcedimento]['ID_ANALISE'];

                  //Cadastrando para essa fila, e esse procedimento e unidade o novo status
                  $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso(array($idProcedimento, $idFila, $idTpCtrl, $strNovoStatus, null , $undEsforco, null, $idTriagem, $idAnalise, $idRevisao, $strDetalheRev, MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO));

                  $idUsuarioAtb = $arrRetorno[$idProcedimento]['ID_USUARIO_ATRIBUICAO'];
                  $objRNGerais->controlarAtribuicao($idProcedimento, $idUsuarioAtb);
              }


              break;

          case MdUtlRevisaoRN::$FLUXO_FINALIZADO:
              //Atribuição no Core
              $arrObjsAtuais = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimento(array($idProcedimento));

              if (!is_null($arrObjsAtuais)) {
                  $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, array(), 'N', 'N', 'S'));
                  $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

                  $objMdUtlControleDsmpRN->desativarIdsAtivosControleDsmp($arrRetorno);
                  $objHistoricoRN->concluirControleDsmp(array($idProcedimento, $arrRetorno, MdUtlControleDsmpRN::$CONCLUIR_REVISAO, $idRevisao));

                  $idUsuarioAtb = $arrRetorno[$idProcedimento]['ID_USUARIO_ATRIBUICAO'];
                  $objRNGerais->controlarAtribuicao($idProcedimento, $idUsuarioAtb);

                  $this->_verificaProcessoAssociaAutomaticamente($objControleDsmpDTO, $isAnalise);
              }

              break;

          case MdUtlRevisaoRN::$VOLTAR_PARA_RESPONSAVEL:
              $strNovoStatus = $_GET['acao'] == 'md_utl_revisao_analise_cadastrar' ? MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE : MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM;

              $arrRetorno = $objHistoricoRN->getUltimosResponsaveisPorProcesso(array(array($idProcedimento), true));

              $idUsuarioDistr  = $arrRetorno[$idProcedimento]['ID_USUARIO'];
              $strDetalheDistr = $arrRetorno[$idProcedimento]['NOME']. ' ('.$arrRetorno[$idProcedimento]['SIGLA'].')';

              $objAtribuirDTO  = new AtribuirDTO();
              $objAtividadeRN  = new AtividadeRN();
              $objProtocoloDTO = new ProtocoloDTO();
              $arrObjProtocoloDTO = array();

              //Atribuição no Core
              $objProtocoloDTO->setDblIdProtocolo($idProcedimento);
              $arrObjProtocoloDTO[] = $objProtocoloDTO;
              $objAtribuirDTO->setNumIdUsuarioAtribuicao($idUsuarioDistr);
              $objAtribuirDTO->setArrObjProtocoloDTO($arrObjProtocoloDTO);
              $objAtividadeRN->atribuirRN0985($objAtribuirDTO);

              $arrObjsAtuais   = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimento(array($idProcedimento));

              $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, array($idProcedimento), 'N','S','S'));
              $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

              $idTriagem = $arrRetorno[$idProcedimento]['ID_TRIAGEM'];
              $idAnalise = $arrRetorno[$idProcedimento]['ID_ANALISE'];

              //Cadastrar Histórico - Solicitação Negocial
              $objHistoricoRN->salvarObjHistoricoRevisao(array($idProcedimento, $arrRetorno, $idRevisao, $strDetalheRev, $strNovoStatus, $idUsuarioDistr));

              //Cadastrando para essa fila, e esse procedimento e unidade o novo status
              $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso(array($idProcedimento, $idFila, $idTpCtrl, $strNovoStatus, null , 0, $idUsuarioDistr, $idTriagem, $idAnalise, $idRevisao, $strDetalheDistr, MdUtlControleDsmpRN::$STR_TIPO_ACAO_DISTRIBUICAO));
              break;
      }

  }

  private function _verificaProcessoAssociaAutomaticamente($objControleDsmpDTO, $isAnalise){
       $staEncaminhamento = $isAnalise ? $objControleDsmpDTO->getStrStaEncaminhamentoAnalise() : $objControleDsmpDTO->getStrStaEncaminhamentoTriagem();
        if(!is_null($staEncaminhamento) && $staEncaminhamento == MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA){
            $idMdUtlAdmFila = $isAnalise ? $objControleDsmpDTO->getNumIdMdUtlAdmFilaEncAnalise() : $objControleDsmpDTO->getNumIdMdUtlAdmFilaEncTriagem();
            if(!is_null($idMdUtlAdmFila)){
                $nomeFila = $isAnalise ? $objControleDsmpDTO->getStrNomeFilaEncAnalise() : $objControleDsmpDTO->getStrNomeFilaEncTriagem();
                $objControleDsmpRN = new MdUtlControleDsmpRN();
                $objControleDsmpRN->associarFilaAnaliseTriagem(array($objControleDsmpDTO->getDblIdProcedimento(), $idMdUtlAdmFila, $objControleDsmpDTO->getNumIdMdUtlAdmTpCtrlDesemp(), MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO, $nomeFila));
            }
        }
  }

}
