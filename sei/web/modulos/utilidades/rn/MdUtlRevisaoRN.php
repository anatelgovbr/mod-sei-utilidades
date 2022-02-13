<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/12/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlRevisaoRN extends InfraRN {

    public static $FLUXO_FINALIZADO = 'X';
    public static $STR_FLUXO_FINALIZADO = 'Finalizar Fluxo';

    public static $VOLTAR_PARA_FILA = 'F';
    public static $STR_VOLTAR_PARA_FILA = 'Voltar para a Fila';    
    public static $STR_VOLTAR_OUTRO_PARTICIPANTE = 'Retornar para Correção por outro Participante na mesma Fila';
    /* Deixar este valor abaixo por enquanto, para fim de testes no calculo */
    public static $STR_VOLTAR_OUTRO_PARTICIPANTE_OLD = 'Retornar para Correção por outro Participante';

    public static $VOLTAR_PARA_RESPONSAVEL = 'R';
    public static $STR_VOLTAR_PARA_RESPONSAVEL = 'Voltar para o Responsável';
    public static $STR_VOLTAR_PARA_O_MESMO_PARTICIPANTE = 'Retornar para Correção pelo mesmo Participante';

    public static $MANTER_O_RESPONSAVEL = 'M';
    public static $STR_MANTER_O_RESPONSAVEL = 'Manter com o Responsável';

    public static $NOVA_FILA = 'N';
    public static $STR_NOVA_FILA = 'Associar em Fila após Finalizar Fluxo';

    //Associar processo em fila após a Avaliação
    public static $ASSOCIAR_SIM = 'S';
    public static $ASSOCIAR_NAO = 'N';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarControlado(MdUtlRevisaoDTO $objMdUtlRevisaoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_revisao_cadastrar', __METHOD__, $objMdUtlRevisaoDTO);


      $objMdUtlRevisaoBD = new MdUtlRevisaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlRevisaoBD->cadastrar($objMdUtlRevisaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdUtlRevisaoDTO $objMdUtlRevisaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_revisao_alterar', __METHOD__, $objMdUtlRevisaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objInfraException->lancarValidacoes();

      $objMdUtlRevisaoBD = new MdUtlRevisaoBD($this->getObjInfraIBanco());
      $objMdUtlRevisaoBD->alterar($objMdUtlRevisaoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlRevisaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_revisao_excluir', __METHOD__, $arrObjMdUtlRevisaoDTO);

      $objMdUtlRevisaoBD = new MdUtlRevisaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlRevisaoDTO);$i++){
        $objMdUtlRevisaoBD->excluir($arrObjMdUtlRevisaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdUtlRevisaoDTO $objMdUtlRevisaoDTO){
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_revisao_consultar');

      $objMdUtlRevisaoBD = new MdUtlRevisaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlRevisaoBD->consultar($objMdUtlRevisaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdUtlRevisaoDTO $objMdUtlRevisaoDTO) {
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_revisao_listar');

      $objMdUtlRevisaoBD = new MdUtlRevisaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlRevisaoBD->listar($objMdUtlRevisaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdUtlRevisaoDTO $objMdUtlRevisaoDTO){
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_revisao_listar');

      $objMdUtlRevisaoBD = new MdUtlRevisaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlRevisaoBD->contar($objMdUtlRevisaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlRevisaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_revisao_desativar', __METHOD__, $arrObjMdUtlRevisaoDTO);

      $objMdUtlRevisaoBD = new MdUtlRevisaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlRevisaoDTO);$i++){
        $objMdUtlRevisaoBD->desativar($arrObjMdUtlRevisaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }
  
  protected function salvarObjRevisaoConectado($isContestacao = false){
      $objRevisaoDTO    = new MdUtlRevisaoDTO();
      $idEncaminhamento = $_POST['selEncaminhamento'];
      $isAnalise        = $_GET['acao'] == 'md_utl_revisao_analise_cadastrar'? true : false;

      if($isAnalise) {
          $objRevisaoDTO->setStrSinAnalise('S');
      }else{
          $objRevisaoDTO->setStrSinAnalise('N');
      }

      $sinAtivo = $idEncaminhamento  && $idEncaminhamento == MdUtlRevisaoRN::$VOLTAR_PARA_FILA ? 'S' : 'N';
      $objRevisaoDTO->setStrInformacoesComplementares($_POST['txaInformacaoComplementar']);
      $objRevisaoDTO->setStrSinAtivo($sinAtivo);

      if($isContestacao){
          $objRevisaoDTO->setStrStaEncaminhamentoContestacao($_POST['selEncaminhamentoContest']);
      }else {
          $objRevisaoDTO->setStrStaEncaminhamentoRevisao($_POST['selEncaminhamento']);
      }

      $objRevisaoDTO->setDthAtual(InfraData::getStrDataHoraAtual());
      $objRevisaoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objRevisaoDTO->setStrSinAssociarFila($_POST['selAssociarProcFila']);
      $objRevisaoDTO->setNumIdMdUtlAdmFila($_POST['selFila']);

      /* Novos campos */
      if(!isset($_POST['cbkRealizarAvalProdAProd'])){
        $objRevisaoDTO->setStrSinRealizarAvalProdProd('N');
      }else{
        $objRevisaoDTO->setStrSinRealizarAvalProdProd('S');
      }

      $objRevisaoDTO->setNumAvaliacaoQualitativa($_POST['selAvalQualitativa']);      
      /* Novos campos Fim */

      $objRevisaoDTO->retTodos();

      return $this->cadastrar($objRevisaoDTO);
  }

  protected function buscarObjRevisaoPorIdConectado($idRevisao){
      $objRevisaoDTO = new MdUtlRevisaoDTO();
      $objRevisaoDTO->setNumIdMdUtlRevisao($idRevisao);
      $objRevisaoDTO->setNumMaxRegistrosRetorno(1);
      $objRevisaoDTO->retTodos();
      return $this->consultar($objRevisaoDTO);
  }

  protected function desativarPorIdsConectado(Array $idsRevisao){
        if(count($idsRevisao) > 0){
            $objRevisaoDTO = new MdUtlRevisaoDTO();
            $objRevisaoDTO->setNumIdMdUtlRevisao($idsRevisao, InfraDTO::$OPER_IN);
            $objRevisaoDTO->retNumIdMdUtlRevisao();
            $objRevisaoDTO->setStrSinAtivo('S');
            $count = $this->contar($objRevisaoDTO);
            if($count > 0){
                $this->desativar($this->listar($objRevisaoDTO));
            }
        }
    }

    protected function checarDadosRevisaoControlado($idUsuario){
        /*Busca id do usuário de utilidades para agendamento automático do sistema*/

        $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
        $objMdUtlRevisaoDTO->adicionarCriterio(array('Atual','IdUsuario'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array(null, null),InfraDTO::$OPER_LOGICO_OR);
        $objMdUtlRevisaoDTO->retNumIdMdUtlRevisao();
        $objRN = new MdUtlRevisaoRN();
        $numRegistros = $objRN->contar($objMdUtlRevisaoDTO);

        if ($numRegistros > 0) {
            $arrDadosRevisao = $objRN->listar($objMdUtlRevisaoDTO);
            foreach ($arrDadosRevisao as $dadoRevisao) {
                $objMdUtlRevisaoDTO = new MdUtlRevisaoDTO();
                $objMdUtlRevisaoDTO->setNumIdMdUtlRevisao($dadoRevisao->getNumIdMdUtlRevisao());
                $objMdUtlRevisaoDTO->setDthAtual(InfraData::getStrDataHoraAtual());
                $objMdUtlRevisaoDTO->setNumIdUsuario($idUsuario);
                $objRN->alterar($objMdUtlRevisaoDTO);
            }
        }
    }

}
