<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/07/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';
class MdUtlHistControleDsmpRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarControlado(MdUtlHistControleDsmpDTO $objMdUtlHistControleDsmpDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_hist_controle_dsmp_cadastrar', __METHOD__,$objMdUtlHistControleDsmpDTO);
      $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD($this->getObjInfraIBanco());
      $ret = $objMdUtlHistControleDsmpBD->cadastrar($objMdUtlHistControleDsmpDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Controle do Dsmp.',$e);
    }
  }

  protected function alterarControlado(MdUtlHistControleDsmpDTO $objMdUtlHistControleDsmpDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_hist_controle_dsmp_alterar', __METHOD__, $objMdUtlHistControleDsmpDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      $objInfraException->lancarValidacoes();

      $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD($this->getObjInfraIBanco());
      $objMdUtlHistControleDsmpBD->alterar($objMdUtlHistControleDsmpDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Controle do Dsmp.',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlHistControleDsmpDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_hist_controle_dsmp_excluir', __METHOD__, $arrObjMdUtlHistControleDsmpDTO);

      $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlHistControleDsmpDTO);$i++){
        $objMdUtlHistControleDsmpBD->excluir($arrObjMdUtlHistControleDsmpDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Controle do Dsmp.',$e);
    }
  }

  protected function consultarConectado(MdUtlHistControleDsmpDTO $objMdUtlHistControleDsmpDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_hist_controle_dsmp_consultar');

      $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD($this->getObjInfraIBanco());
      $ret = $objMdUtlHistControleDsmpBD->consultar($objMdUtlHistControleDsmpDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Controle do Dsmp.',$e);
    }
  }

  protected function listarConectado(MdUtlHistControleDsmpDTO $objMdUtlHistControleDsmpDTO) {
    try {

        if(!$objMdUtlHistControleDsmpDTO->isSetAtributo('SinVerificarPermissao')){
            $objMdUtlHistControleDsmpDTO->setStrSinVerificarPermissao('S');
        }

        if($objMdUtlHistControleDsmpDTO->getStrSinVerificarPermissao() == 'S'){
            SessaoSEI::getInstance()->validarPermissao('md_utl_hist_controle_dsmp_listar');
        }
      //Valida Permissao

      $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD($this->getObjInfraIBanco());
      $ret = $objMdUtlHistControleDsmpBD->listar($objMdUtlHistControleDsmpDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Controle do Dsmp.',$e);
    }
  }

  protected function contarConectado(MdUtlHistControleDsmpDTO $objMdUtlHistControleDsmpDTO){
    try {

      //Valida Permissao
        if(!$objMdUtlHistControleDsmpDTO->isSetAtributo('SinVerificarPermissao')){
            $objMdUtlHistControleDsmpDTO->setStrSinVerificarPermissao('S');
        }

        if($objMdUtlHistControleDsmpDTO->getStrSinVerificarPermissao() == 'S'){
            SessaoSEI::getInstance()->validarPermissao('md_utl_hist_controle_dsmp_listar');
        }

      $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD($this->getObjInfraIBanco());
      $ret = $objMdUtlHistControleDsmpBD->contar($objMdUtlHistControleDsmpDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando o Controle do Dsmp.',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlHistControleDsmpDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_hist_controle_dsmp_desativar', __METHOD__, $arrObjMdUtlHistControleDsmpDTO);

      $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlHistControleDsmpDTO);$i++){
        $objMdUtlHistControleDsmpBD->desativar($arrObjMdUtlHistControleDsmpDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Controle do Dsmp.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlHistControleDsmpDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_hist_controle_dsmp_reativar', __METHOD__, $arrObjMdUtlHistControleDsmpDTO);

      $objMdUtlHistControleDsmpBD = new MdUtlHistControleDsmpBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlHistControleDsmpDTO);$i++){
        $objMdUtlHistControleDsmpBD->reativar($arrObjMdUtlHistControleDsmpDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Controle do Dsmp.',$e);
    }
  }

  protected function controlarHistoricoDesempenhoControlado(Array $arrParams){

     $arrObjs            = array_key_exists(0, $arrParams) ? $arrParams[0] : null;
     $arrIdsProcedimento = array_key_exists(1, $arrParams) ? $arrParams[1] : null;
     $sinFila            = array_key_exists(2, $arrParams) ? $arrParams[2] : 'N';
     $sinResponsavel     = array_key_exists(3, $arrParams) ? $arrParams[3] : 'N';
     $sinAcaoConcluida   = array_key_exists(4, $arrParams) ? $arrParams[4] : 'N';

     $arrRetorno         = array();

      //Busca os objetos cadastrados para esses ids Procedimento
      if(count($arrObjs) > 0){
          $isAtualizarUltsFilas    = $sinFila == 'S';
          $isAtualizarUltsResponsv = $sinResponsavel == 'S';
          $isAtualizacaoValida = $isAtualizarUltsResponsv || $isAtualizarUltsFilas;

          if($isAtualizacaoValida && count($arrIdsProcedimento) > 0) {
              $this->controlarFlagsHistorico(array($arrIdsProcedimento, $isAtualizarUltsFilas, $isAtualizarUltsResponsv));
          }

            foreach($arrObjs as $objDTO){
                $objHistoricoDTO = $this->_clonarObjControleDsmp($objDTO);
                $objHistoricoDTO->setStrSinUltimaFila($sinFila);
                $objHistoricoDTO->setStrSinUltimoResponsavel($sinResponsavel);
                $objHistoricoDTO->setStrSinAcaoConcluida($sinAcaoConcluida);
                $objHistoricoDTO->setDthFinal(InfraData::getStrDataHoraAtual());
                $idProc = $objHistoricoDTO->getDblIdProcedimento();
                $arrRetorno[$idProc]['UNIDADE_ESFORCO'] = $objHistoricoDTO->getNumUnidadeEsforco();
                $arrRetorno[$idProc]['ID_TRIAGEM'] = $objHistoricoDTO->getNumIdMdUtlTriagem();
                $arrRetorno[$idProc]['ID_ANALISE'] = $objHistoricoDTO->getNumIdMdUtlAnalise();
                $arrRetorno[$idProc]['ID_REVISAO'] = $objHistoricoDTO->getNumIdMdUtlRevisao();
                $arrRetorno[$idProc]['ID_USUARIO_ATRIBUICAO'] = $objHistoricoDTO->getNumIdUsuarioDistribuicao();
                $arrRetorno[$idProc]['ID_ATENDIMENTO'] = $objHistoricoDTO->getNumIdAtendimento();
                $this->cadastrar($objHistoricoDTO);
            }
      }

      return $arrRetorno;
  }

  protected function controlarHistoricoDesempenhoParametrizacaoConectado($arrObjs){
      foreach($arrObjs as $objDTO){
          $objHistoricoDTO = $this->_clonarObjControleDsmp($objDTO);
          $objHistoricoDTO->setStrSinUltimaFila('S');
          $objHistoricoDTO->setStrSinUltimoResponsavel('N');
          $idProc = $objHistoricoDTO->getDblIdProcedimento();
          $arrRetorno[$idProc]['UNIDADE_ESFORCO'] = $objHistoricoDTO->getNumUnidadeEsforco();
          $arrRetorno[$idProc]['ID_TRIAGEM'] = $objHistoricoDTO->getNumIdMdUtlTriagem();
          $arrRetorno[$idProc]['ID_ANALISE'] = $objHistoricoDTO->getNumIdMdUtlAnalise();
          $arrRetorno[$idProc]['ID_REVISAO'] = $objHistoricoDTO->getNumIdMdUtlRevisao();
          $arrRetorno[$idProc]['ID_USUARIO_ATRIBUICAO'] = $objHistoricoDTO->getNumIdUsuarioDistribuicao();
          $arrRetorno[$idProc]['ID_ATENDIMENTO'] = $objHistoricoDTO->getNumIdAtendimento();
          $this->cadastrar($objHistoricoDTO);
      }

      return $arrRetorno;
  }

  private function _clonarObjControleDsmp($objDTOAtual){
      $objHistoricoDTO = new MdUtlHistControleDsmpDTO();

      foreach($objDTOAtual->getArrAtributos() as $attr){
          $strValor    = $attr[InfraDTO::$POS_ATRIBUTO_VALOR];
          $strAtributo = $attr[InfraDTO::$FLAG_SET];
          if($strAtributo == 'IdMdUtlControleDsmp'){
              $objHistoricoDTO->set('IdMdUtlHistControleDsmp', null);
          }else {
              $objHistoricoDTO->set($strAtributo, $strValor);
          }
      }

      return $objHistoricoDTO;
  }

  protected function controlarFlagsHistoricoConectado($arrDados){
        $arrIdsProcedimento   = array_key_exists(0, $arrDados) ? $arrDados[0] : null;
        $atualizarFila        = array_key_exists(1, $arrDados) ? $arrDados[1] : null;
        $atualizarResponsavel = array_key_exists(2, $arrDados) ? $arrDados[2] : null;

        $arrObjs                  = null;
        $objMdUtlHsControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHsControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlHsControleDsmpDTO->setDblIdProcedimento($arrIdsProcedimento, InfraDTO::$OPER_IN);
        $objMdUtlHsControleDsmpDTO->retTodos();

        if($atualizarResponsavel && $atualizarResponsavel) {
            $objMdUtlHsControleDsmpDTO->adicionarCriterio(array('SinUltimoResponsavel', 'SinUltimaFila'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array('S', 'S'), array(InfraDTO::$OPER_LOGICO_OR));
        }

        if($atualizarFila && !$atualizarResponsavel){
            $objMdUtlHsControleDsmpDTO->setStrSinUltimaFila('S');
        }

        if($atualizarResponsavel && !$atualizarFila){
            $objMdUtlHsControleDsmpDTO->setStrSinUltimoResponsavel('S');
        }

        $count  = $this->contar($objMdUtlHsControleDsmpDTO);

        if($count > 0) {
            $arrObjs = $this->listar($objMdUtlHsControleDsmpDTO);
            //Save Antigos Objs

            foreach($arrObjs as $objDTO){
                if($atualizarFila) {
                    $objDTO->setStrSinUltimaFila('N');
                }

                if($atualizarResponsavel) {
                    $objDTO->setStrSinUltimoResponsavel('N');
                }

                $this->alterar($objDTO);
            }
        }

        return $arrObjs;
  }

  protected function getUltimasFilasPorProcedimentoConectado($idsProcedimento){
      $arrObjs = null;
      $objMdUtlHsControleDsmpDTO = new MdUtlHistControleDsmpDTO();
      $objMdUtlHsControleDsmpDTO->setDblIdProcedimento($idsProcedimento, InfraDTO::$OPER_IN);
      $objMdUtlHsControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objMdUtlHsControleDsmpDTO->retTodos();
      $objMdUtlHsControleDsmpDTO->setStrSinUltimaFila('S');
      $objMdUtlHsControleDsmpDTO->retStrNomeFila();

      $count = $this->contar($objMdUtlHsControleDsmpDTO);

      if($count > 0) {
          $arrObjs = $this->listar($objMdUtlHsControleDsmpDTO);
      }

      return $arrObjs;
  }

  protected function getUltimosResponsaveisPorProcessoConectado($arrParams)
    {
        $idsProcedimento = $arrParams[0];
        $isRetornoNome   = array_key_exists(1, $arrParams) ? $arrParams[1] : true;

        $arrRetorno = array();

        $objMdUtlHsControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHsControleDsmpDTO->setDblIdProcedimento($idsProcedimento, InfraDTO::$OPER_IN);
        $objMdUtlHsControleDsmpDTO->setStrSinUltimoResponsavel('S');
        $objMdUtlHsControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlHsControleDsmpDTO->retTodos();
        $objMdUtlHsControleDsmpDTO->retStrSiglaUsuarioDist();
        $objMdUtlHsControleDsmpDTO->retStrNomeUsuarioDist();

        $count = $this->contar($objMdUtlHsControleDsmpDTO);

        if ($count > 0) {
            $arrObjsUltimosResponsaveis = $this->listar(  $objMdUtlHsControleDsmpDTO);

            if (count($arrObjsUltimosResponsaveis) > 0) {
               
                foreach ($arrObjsUltimosResponsaveis as $objDTO) {
                    if($isRetornoNome){
                        $arrRetorno[$objDTO->getDblIdProcedimento()]['SIGLA'] = $objDTO->getStrSiglaUsuarioDist();
                        $arrRetorno[$objDTO->getDblIdProcedimento()]['NOME'] = $objDTO->getStrNomeUsuarioDist();
                        $arrRetorno[$objDTO->getDblIdProcedimento()]['ID_USUARIO'] = $objDTO->getNumIdUsuarioDistribuicao();
                    }else{
                        $arrRetorno[$objDTO->getDblIdProcedimento()] = $objDTO->getNumIdUsuarioDistribuicao();
                    }

                }

            }

        }
        return $arrRetorno;
    }

  protected function removerFilaControleDsmpConectado($arrDados){
        $arrIdsProcedimento = $arrDados[0];
        $arrRetorno     = $arrDados[1];

        $arrIdsAtendimento = $this->getIdsAtendimentosAtuais($arrIdsProcedimento);

        foreach($arrIdsProcedimento as $idProcedimento){

            if(is_null($arrRetorno) || (is_array($arrRetorno) && !array_key_exists($idProcedimento, $arrRetorno))){
                $arrRetorno = array();
                $arrRetorno[$idProcedimento]['ID_TRIAGEM'] =null;
                $arrRetorno[$idProcedimento]['ID_ANALISE'] =null;
                $arrRetorno[$idProcedimento]['ID_REVISAO'] =null;
                $arrRetorno[$idProcedimento]['UNIDADE_ESFORCO'] =null;
                $arrRetorno[$idProcedimento]['ID_ATENDIMENTO'] = $arrIdsAtendimento[$idProcedimento];
            }
            $this->_salvarObjHistorico($idProcedimento, $arrRetorno, null, MdUtlControleDsmpRN::$CONCLUIR_ASSOCIACAO ,'Nenhuma Fila', MdUtlControleDsmpRN::$CONCLUIR_ASSOCIACAO);
        }
    }

  protected function getIdsAtendimentosAtuaisConectado($arrIdsProcedimento){

        $setIdInicial = function ($value) use (&$arrRetorno){
            $arrRetorno[$value] = 1;
        };

        array_map($setIdInicial, $arrIdsProcedimento);

        $arrRetorno = array();

        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($arrIdsProcedimento, InfraDTO::$OPER_IN);
        $objMdUtlHistControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlHistControleDsmpDTO->retNumIdAtendimento();
        $objMdUtlHistControleDsmpDTO->retDblIdProcedimento();
        $objMdUtlHistControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objMdUtlHistControleDsmpDTO->setOrdDthAtual(InfraDTO::$TIPO_ORDENACAO_DESC);
        $arrObjs = $this->listar($objMdUtlHistControleDsmpDTO);

        foreach($arrObjs as $objDTO){
            $arrRetorno[$objDTO->getDblIdProcedimento()] = $objDTO->getNumIdAtendimento();
        }

        return $arrRetorno;
    }

  protected function concluirControleDsmpConectado($arrDados){
      $idProcedimento  = $arrDados[0];
      $arrRetorno      = $arrDados[1];
      $idEuFinalizacao = $arrDados[2];
      $novoId          = $arrDados[3];
      $strDetalhe      = array_key_exists(4, $arrDados) ? $arrDados[4] :  MdUtlControleDsmpRN::$STR_FLUXO_FINALIZADO;

      $arrControleFlags = array(array($idProcedimento), true, true);
      $this->controlarFlagsHistorico($arrControleFlags);
      $this->_salvarObjHistorico($idProcedimento, $arrRetorno, $novoId, $idEuFinalizacao, $strDetalhe);
  }

  private function _salvarObjHistorico($idProcedimento, $arrRetorno, $novoId = null, $idEuFinalizacao = null, $strDetalhe = '',  $status = null, $idUsuarioDistrib = null, $idFilaParam = null)
    {
        $objHistoricoRN = new MdUtlHistControleDsmpRN();

        if(is_null($status)){
            $status = MdUtlControleDsmpRN::$FLUXO_FINALIZADO;
        }

        $idAtendimento    = $arrRetorno[$idProcedimento]['ID_ATENDIMENTO'];
        $idRevisao        = $arrRetorno[$idProcedimento]['ID_REVISAO'];
        $idAnalise        = $arrRetorno[$idProcedimento]['ID_ANALISE'];
        $idTriagem        = $arrRetorno[$idProcedimento]['ID_TRIAGEM'];
        $strTipoAcao      = '';
        $strUltimaFila    = 'S';
        $idFila           = array_key_exists('hdnIdFilaAtiva', $_POST) ? $_POST['hdnIdFilaAtiva'] : null;
        $undEsforco       = $arrRetorno[$idProcedimento]['UNIDADE_ESFORCO'];

        switch ($idEuFinalizacao){
            case  MdUtlControleDsmpRN::$CONCLUIR_ASSOCIACAO:
                $strTipoAcao = MdUtlControleDsmpRN::$STR_TIPO_ACAO_ASSOCIACAO;
                if(is_null($idAtendimento)){
                    $idAtendimento = $objHistoricoRN->controlarIdAtendimento($idProcedimento);
                }
                $status = MdUtlControleDsmpRN::$REMOCAO_FILA;
                break;
            case MdUtlControleDsmpRN::$CONCLUIR_REVISAO:
                $strTipoAcao   = MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO;
                $idRevisao     = $novoId;
                break;
            case MdUtlControleDsmpRN::$CONCLUIR_ANALISE:
                $strTipoAcao   = MdUtlControleDsmpRN::$STR_TIPO_ACAO_ANALISE;
                $idAnalise     = $novoId;
                break;
            case MdUtlControleDsmpRN::$CONCLUIR_TRIAGEM:
                $strTipoAcao   = MdUtlControleDsmpRN::$STR_TIPO_ACAO_TRIAGEM;
                $idTriagem     = $novoId;
                break;
            case MdUtlControleDsmpRN::$VOLTAR_RESP_REVISAO:
                $strTipoAcao   = MdUtlControleDsmpRN::$STR_TIPO_ACAO_REVISAO;
                $idRevisao     = $novoId;
                $strUltimaFila = 'N';
                $idFila        = $idFilaParam;
                $undEsforco    = 0;
                break;
        }

        $status = trim($status);
        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($idProcedimento);
        $objMdUtlHistControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlHistControleDsmpDTO->setNumIdMdUtlAdmFila($idFila);
        $objMdUtlHistControleDsmpDTO->setNumIdUsuarioAtual(SessaoSEI::getInstance()->getNumIdUsuario());
        $objMdUtlHistControleDsmpDTO->setNumIdUsuarioDistribuicao($idUsuarioDistrib);
        $objMdUtlHistControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($_POST['hdnIdTpCtrl']);
        $objMdUtlHistControleDsmpDTO->setNumIdMdUtlTriagem($idTriagem);
        $objMdUtlHistControleDsmpDTO->setNumIdMdUtlAnalise($idAnalise);
        $objMdUtlHistControleDsmpDTO->setNumIdMdUtlRevisao($idRevisao);
        $objMdUtlHistControleDsmpDTO->setNumUnidadeEsforco($undEsforco);
        $objMdUtlHistControleDsmpDTO->setDthAtual(InfraData::getStrDataHoraAtual());
        $objMdUtlHistControleDsmpDTO->setDthFinal(InfraData::getStrDataHoraAtual());
        $objMdUtlHistControleDsmpDTO->setStrStaAtendimentoDsmp($status);
        $objMdUtlHistControleDsmpDTO->setStrSinUltimaFila($strUltimaFila);
        $objMdUtlHistControleDsmpDTO->setStrSinUltimoResponsavel('N');
        $objMdUtlHistControleDsmpDTO->setStrDetalhe($strDetalhe);
        $objMdUtlHistControleDsmpDTO->setNumIdAtendimento($idAtendimento);
        $objMdUtlHistControleDsmpDTO->setStrTipoAcao($strTipoAcao);
        $objMdUtlHistControleDsmpDTO->setStrSinAcaoConcluida('N');


        $this->cadastrar($objMdUtlHistControleDsmpDTO);
    }

  protected function desativarTodasFlagsHistoricoConectado($arrParams){
      $arrIdsRemovidos = $arrParams[0];
      $arrIdsUnidades  = $arrParams[1];

      $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
      if(!is_null($arrIdsRemovidos)) {
          $objMdUtlHistControleDsmpDTO->setNumIdTpProcedimento($arrIdsRemovidos, InfraDTO::$OPER_IN);
      }

      $objMdUtlHistControleDsmpDTO->setNumIdUnidade($arrIdsUnidades, InfraDTO::$OPER_IN);
      $objMdUtlHistControleDsmpDTO->adicionarCriterio(array('SinUltimoResponsavel', 'SinUltimaFila'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array('S', 'S'), array(InfraDTO::$OPER_LOGICO_OR));
      $objMdUtlHistControleDsmpDTO->retTodos();

      $arrObjs = $this->listar($objMdUtlHistControleDsmpDTO);

      foreach($arrObjs as $obj){
          $obj->setStrSinUltimaFila('N');
          $obj->setStrSinUltimoResponsavel('N');
          $this->alterar($obj);
      }

  }

  protected function desativarTodasFlagsHistoricoPorIdProcedimentoConectado($idProcedimento){


        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($idProcedimento);
        $objMdUtlHistControleDsmpDTO->adicionarCriterio(array('SinUltimoResponsavel', 'SinUltimaFila'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array('S', 'S'), array(InfraDTO::$OPER_LOGICO_OR));
        $objMdUtlHistControleDsmpDTO->retTodos();

        $count  =  $this->contar($objMdUtlHistControleDsmpDTO);
        if($count > 0) {
            $arrObjs = $this->listar($objMdUtlHistControleDsmpDTO);

            foreach ($arrObjs as $obj) {
                $obj->setStrSinUltimaFila('N');
                $obj->setStrSinUltimoResponsavel('N');
                $this->alterar($obj);
            }
        }
    }

  protected function controlarIdAtendimentoConectado($idProcedimento){
        $idAtendimento = 1;

        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($idProcedimento);
        $objMdUtlHistControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlHistControleDsmpDTO->retNumIdAtendimento();
        $objMdUtlHistControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objMdUtlHistControleDsmpDTO->setOrdDthAtual(InfraDTO::$TIPO_ORDENACAO_DESC);

        if($this->contar($objMdUtlHistControleDsmpDTO) > 0){
            $objDTO = $this->consultar($objMdUtlHistControleDsmpDTO);
            $idAtendimentoAntigo = $objDTO->getNumIdAtendimento();
            $idAtendimento = $idAtendimentoAntigo + 1;
        }

        return $idAtendimento;
    }

  protected function getIdAtendimentoAtualConectado($idProcedimento){
        $idAtendimentoAntigo = 1;

        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($idProcedimento);
        $objMdUtlHistControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlHistControleDsmpDTO->retNumIdAtendimento();
        $objMdUtlHistControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objMdUtlHistControleDsmpDTO->setOrdDthAtual(InfraDTO::$TIPO_ORDENACAO_DESC);

        if($this->contar($objMdUtlHistControleDsmpDTO) > 0){
            $objDTO = $this->consultar($objMdUtlHistControleDsmpDTO);
            $idAtendimentoAntigo = $objDTO->getNumIdAtendimento();
        }

        return $idAtendimentoAntigo;
    }

  protected function salvarObjHistoricoRevisaoConectado($arrParams){
        $idProcedimento = $arrParams[0];
        $arrRetorno     = $arrParams[1];
        $idRevisao      = $arrParams[2];
        $strDetalhe     = $arrParams[3];
        $strStatus      = $arrParams[4];
        $idUsuarioDs    = $arrParams[5];

        $this->_salvarObjHistorico($idProcedimento, $arrRetorno, $idRevisao, MdUtlControleDsmpRN::$VOLTAR_RESP_REVISAO, $strDetalhe, $strStatus, $idUsuarioDs);
    }

  protected function verificaProcessoPossuiHistoricoDsmpConectado($idProcedimento){
        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();

        $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
        $objMdUtlControleDsmpDTO->setDblIdProcedimento($idProcedimento);
        $objMdUtlControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlControleDsmpDTO->retDblIdProcedimento();
        $countAtivos = $objMdUtlControleDsmpRN->contar($objMdUtlControleDsmpDTO) > 0;

        $objMdUtlHsControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHsControleDsmpDTO->setDblIdProcedimento($idProcedimento);
        $objMdUtlHsControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlHsControleDsmpDTO->retDblIdProcedimento();
        $countHistorico = $this->contar($objMdUtlHsControleDsmpDTO) > 0;

        return ($countAtivos || $countHistorico);
    }

    protected function preencherCamposGeraisControleDesempenhoConectado()
    {
        $arrDadosTriagem     = array();
        $arrDadosAnalise     = array();
        $arrDadosRevisao     = array();

        $objMdUtlHsControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHsControleDsmpDTO->retNumIdUnidade();
        $objMdUtlHsControleDsmpDTO->retDblIdProcedimento();

        $countHistorico = $this->contar($objMdUtlHsControleDsmpDTO);

        $arrRetorno = array();

        if ($countHistorico > 0) {
            $arrObjs    = $this->listar($objMdUtlHsControleDsmpDTO);
            foreach($arrObjs as $objDTO){
                $arrRetorno[$objDTO->getDblIdProcedimento()][$objDTO->getNumIdUnidade()]= true;
            }
        }

        if (count($arrRetorno) > 0) {
            foreach ($arrRetorno as $idProcedimento => $arrProcedimento) {
                foreach ($arrProcedimento as $idUnidade => $arrUnidade) {
                    if (!is_null($idUnidade) && !is_null($idProcedimento)) {
                        $objMdUtlHsControleDsmpDTO = new MdUtlHistControleDsmpDTO();
                        $objMdUtlHsControleDsmpDTO->setDblIdProcedimento($idProcedimento);
                        $objMdUtlHsControleDsmpDTO->setNumIdUnidade($idUnidade);
                        $objMdUtlHsControleDsmpDTO->retTodos();
                        $objMdUtlHsControleDsmpDTO->setOrdDthAtual(InfraDTO::$TIPO_ORDENACAO_DESC);

                        if ($this->contar($objMdUtlHsControleDsmpDTO) > 0) {
                            $arrObjsHistoricoDTO = $this->listar($objMdUtlHsControleDsmpDTO);
                            $objControleDsmpDTO  = $this->_getUltimoObjAtivo($idProcedimento, $idUnidade);
                            $this->_preencherDadosConjuntoObjHistorico($arrObjsHistoricoDTO, $objControleDsmpDTO);
                            $this->_preencherArrGeraisTriagAnaliseRev($arrObjsHistoricoDTO, $objControleDsmpDTO, $arrDadosTriagem, $arrDadosAnalise, $arrDadosRevisao);
                        }
                    }
                }
            }

            $this->_salvarDadosTriagem($arrDadosTriagem);
            $this->_salvarDadosAnalise($arrDadosAnalise);
            $this->_salvarDadosRevisao($arrDadosRevisao);
        }
    }

    private function _salvarDadosTriagem($arrDadosTriagem)
    {
        if (count($arrDadosTriagem) > 0) {
            $objRN = new MdUtlTriagemRN();
            foreach ($arrDadosTriagem as $key => $dadoTriagem) {
                if (!is_null($key) && $key != '') {
                    $objMdUtlTriagemDTO = new MdUtlTriagemDTO();
                    $objMdUtlTriagemDTO->setNumIdMdUtlTriagem($key);
                    $objMdUtlTriagemDTO->setDthAtual($dadoTriagem['DTH_ATUAL']);
                    $objMdUtlTriagemDTO->setNumIdUsuario($dadoTriagem['ID_USUARIO']);
                    $objRN->alterar($objMdUtlTriagemDTO);
                }
            }
        }
    }

    private function _salvarDadosAnalise($arrDadosAnalise)
    {
        if (count($arrDadosAnalise) > 0) {
            $objRN = new MdUtlAnaliseRN();
            foreach ($arrDadosAnalise as $key => $dado) {
                if (!is_null($key) && $key != '') {
                    $objMdUtlAnaliseDTO = new MdUtlAnaliseDTO();
                    $objMdUtlAnaliseDTO->setNumIdMdUtlAnalise($key);
                    $objMdUtlAnaliseDTO->setDthAtual($dado['DTH_ATUAL']);
                    $objMdUtlAnaliseDTO->setNumIdUsuario($dado['ID_USUARIO']);
                    $objRN->alterar($objMdUtlAnaliseDTO);
                }
            }
        }
    }

    private function _salvarDadosRevisao($arrDadosRevisao){
      if(count($arrDadosRevisao) > 0) {
          $objRN = new MdUtlRevisaoRN();
          foreach ($arrDadosRevisao as $key => $dado) {
              if (!is_null($key) && $key != '') {
                  $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
                  $objMdUtlRevisaoDTO->setNumIdMdUtlRevisao($key);
                  $objMdUtlRevisaoDTO->setDthAtual($dado['DTH_ATUAL']);
                  $objMdUtlRevisaoDTO->setNumIdUsuario($dado['ID_USUARIO']);
                  $objRN->alterar($objMdUtlRevisaoDTO);
              }
          }
      }
    }


    private function _preencherArrGeraisTriagAnaliseRev($arrObjsHistoricoDTO, $objControleDsmpDTO, &$arrDadosTriagem, &$arrDadosAnalise, &$arrDadosRevisao){

        $arrObjsReverseHsDTO = array_reverse($arrObjsHistoricoDTO);

        foreach($arrObjsReverseHsDTO as $objDTO){
            $this->_preencherArrPrincipal($objDTO, 'IdMdUtlTriagem', $objDTO->getNumIdMdUtlTriagem(), $arrDadosTriagem);
            $this->_preencherArrPrincipal($objDTO, 'IdMdUtlAnalise', $objDTO->getNumIdMdUtlAnalise(), $arrDadosAnalise);
            $this->_preencherArrPrincipal($objDTO, 'IdMdUtlRevisao', $objDTO->getNumIdMdUtlRevisao(), $arrDadosRevisao);
        }

        if(!is_null($objControleDsmpDTO)) {
            $this->_preencherArrPrincipal($objControleDsmpDTO, 'IdMdUtlTriagem', $objControleDsmpDTO->getNumIdMdUtlTriagem(), $arrDadosTriagem);
            $this->_preencherArrPrincipal($objControleDsmpDTO, 'IdMdUtlAnalise', $objControleDsmpDTO->getNumIdMdUtlAnalise(), $arrDadosAnalise);
            $this->_preencherArrPrincipal($objControleDsmpDTO, 'IdMdUtlRevisao', $objControleDsmpDTO->getNumIdMdUtlRevisao(), $arrDadosRevisao);
        }

    }

    private function _preencherArrPrincipal($objDTO, $atributo, $idSearch, &$arrDados){
        if((!is_null($objDTO->get($atributo))) && (!array_key_exists($idSearch, $arrDados))){
            $arrDados[$idSearch]['DTH_ATUAL'] = $objDTO->getDthAtual();
            $arrDados[$idSearch]['ID_USUARIO'] = $objDTO->getNumIdUsuarioAtual();
        }
    }


    private function _preencherDadosConjuntoObjHistorico($arrObjs, $objControleDsmpDTO){
      $dthFinalAtual       = null;
      $ultimaDthFinal      = !is_null($objControleDsmpDTO) ?  $objControleDsmpDTO->getDthAtual() : null;
      $isStatusIndicaConcl = array(MdUtlControleDsmpRN::$AGUARDANDO_ANALISE, MdUtlControleDsmpRN::$AGUARDANDO_REVISAO, MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE, MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM, MdUtlControleDsmpRN::$FLUXO_FINALIZADO);
      $statusAtual         = !is_null($objControleDsmpDTO) ? $objControleDsmpDTO->getStrStaAtendimentoDsmp() : null;
      $acaoConcluida       =  !is_null($statusAtual) && in_array($statusAtual, $isStatusIndicaConcl) ? true : false;
      $isStatusConclusao   = array(MdUtlControleDsmpRN::$EM_TRIAGEM, MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_REVISAO, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM);


        foreach ($arrObjs as $objDTO){
            //Get Valores
            $statusHsAtual   = $objDTO->getStrStaAtendimentoDsmp();

            //Preenche Data Final
            $dthFinalAtual = $statusHsAtual == MdUtlControleDsmpRN::$FLUXO_FINALIZADO ? $objDTO->getDthAtual() : $ultimaDthFinal;

            if(is_null($dthFinalAtual)){
                $dthFinalAtual = $objDTO->getDthAtual();
            }

            $objDTO->setDthFinal($dthFinalAtual);
            $ultimaDthFinal = $objDTO->getDthAtual();

            //Preenche e Controla ações Concluídas
            $isAcaoConcluidaPadrao       = in_array($statusHsAtual, $isStatusConclusao) && $acaoConcluida;
            $isAcaoVoltarParaResponsavel = $objDTO->getStrDetalhe() == MdUtlRevisaoRN::$STR_VOLTAR_PARA_RESPONSAVEL && ($statusHsAtual == MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM || $statusHsAtual == MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE);

            if($isAcaoConcluidaPadrao){
                $objDTO->setStrSinAcaoConcluida('S');
                $acaoConcluida = false;
            }else{
                $objDTO->setStrSinAcaoConcluida('N');
            }

            $acaoConcluida = in_array($statusHsAtual, $isStatusIndicaConcl) ? true : $acaoConcluida;

            if($isAcaoVoltarParaResponsavel){
                $acaoConcluida = true;
            }

            //Altera o Objeto Final
            $this->alterar($objDTO);
        }
    }

    private function _getUltimoObjAtivo($idProcedimento, $idUnidade){
        $objDTORetorno = null;
        $objDTO = new MdUtlControleDsmpDTO();
        $objDTO->setNumIdUnidade($idUnidade);
        $objDTO->setDblIdProcedimento($idProcedimento);
        $objDTO->setNumMaxRegistrosRetorno(1);
        $objDTO->retTodos();
        $objRN = new MdUtlControleDsmpRN();

        if($objRN->contar($objDTO) > 0){
            $objDTORetorno = $objRN->consultar($objDTO);
        }

        return $objDTORetorno;
    }

    protected function buscarUnidadeEsforcoHistConectado($arrParams){
        $idUsuarioParticipante = array_key_exists(0, $arrParams) ? $arrParams[0] : null;
        $idTipoControle        = array_key_exists(1, $arrParams) ? $arrParams[1] : null;
        $arrDatas              = array_key_exists(2, $arrParams) ? $arrParams[2] : null;
        $dtInicio              = $arrDatas['DT_INICIAL'];
        $dtFim                 = $arrDatas['DT_FINAL'];

        $numUnidEsforcoHist    = 0;
        $dtInicio = MdUtlControleDsmpINT::formatarDatasComDoisDigitos($dtInicio);
        $dtFim = MdUtlControleDsmpINT::formatarDatasComDoisDigitos($dtFim);

        if(!is_null($idUsuarioParticipante) && !is_null($idTipoControle)) {

            $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
            $objMdUtlHistControleDsmpDTO->setNumIdUsuarioDistribuicao($idUsuarioParticipante);
            $objMdUtlHistControleDsmpDTO->setStrSinAcaoConcluida('S');
            $objMdUtlHistControleDsmpDTO->setStrStaAtendimentoDsmp(array(MdUtlControleDsmpRN::$EM_REVISAO, MdUtlControleDsmpRN::$EM_TRIAGEM, MdUtlControleDsmpRN::$EM_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE), InfraDTO::$OPER_IN);
            $objMdUtlHistControleDsmpDTO->retNumUnidadeEsforco();
            $objMdUtlHistControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
            $objMdUtlHistControleDsmpDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

            $objMdUtlHistControleDsmpDTO->adicionarCriterio(array('Atual', 'Atual', 'Atual'),
                array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
                array(null, $dtInicio, $dtFim),
                array(InfraDTO::$OPER_LOGICO_AND, InfraDTO::$OPER_LOGICO_AND));

            $countHs = $this->contar($objMdUtlHistControleDsmpDTO);

            if ($countHs > 0) {
                $arrUnidEsforcoHist = $this->listar($objMdUtlHistControleDsmpDTO);

                foreach ($arrUnidEsforcoHist as $objs) {
                    $numUnidEsforcoHist += $objs->getNumUnidadeEsforco();
                }
            }
        }


        return $numUnidEsforcoHist;
    }
}