<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/08/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmTpProdutoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(MdUtlAdmTpProdutoDTO $objMdUtlAdmTpProdutoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmTpProdutoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objMdUtlAdmTpProdutoDTO->setStrNome(trim($objMdUtlAdmTpProdutoDTO->getStrNome()));

      if (strlen($objMdUtlAdmTpProdutoDTO->getStrNome())>50){
        $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Nome', '50'));
        $objInfraException->adicionarValidacao($msg);
      }
    }
  }

  private function validarDuplicacao($objMdUtlAdmDTO, $objInfraException){
    $id = $objMdUtlAdmDTO->getNumIdMdUtlAdmTpProduto();
    $objMdUtlAdmDTO2 = new MdUtlAdmTpProdutoDTO();
    $objMdUtlAdmDTO2->setStrNome($objMdUtlAdmDTO->getStrNome());
    $objMdUtlAdmDTO2->setBolExclusaoLogica(false);
    
    if(!is_null($id)){
      $objMdUtlAdmDTO2->setNumIdMdUtlAdmTpProduto($id, InfraDTO::$OPER_DIFERENTE);
    }
    
    $idTpCtrl = $_POST['hdnIdTpCtrlUtl'];
    $objMdUtlAdmDTO2->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
    
    $existeRegistroDupl = $this->contar($objMdUtlAdmDTO2) > 0;

    if($existeRegistroDupl){
       $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_19, array('Tipo de Produto'));
      $objInfraException->lancarValidacao($msg);
    }
  }

  private function validarStrDescricao(MdUtlAdmTpProdutoDTO $objMdUtlAdmTpProdutoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmTpProdutoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objMdUtlAdmTpProdutoDTO->setStrDescricao(trim($objMdUtlAdmTpProdutoDTO->getStrDescricao()));

      if (strlen($objMdUtlAdmTpProdutoDTO->getStrDescricao())>250){
        $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Descrição', '250'));
        $objInfraException->adicionarValidacao($msg);
      }
    }
  }


  protected function cadastrarControlado(MdUtlAdmTpProdutoDTO $objMdUtlAdmTpProdutoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_produto_cadastrar', __METHOD__, $objMdUtlAdmTpProdutoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objMdUtlAdmTpProdutoDTO, $objInfraException);
      $this->validarStrDescricao($objMdUtlAdmTpProdutoDTO, $objInfraException);
      $this->validarDuplicacao($objMdUtlAdmTpProdutoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmTpProdutoBD = new MdUtlAdmTpProdutoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpProdutoBD->cadastrar($objMdUtlAdmTpProdutoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Tipo de Produto.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmTpProdutoDTO $objMdUtlAdmTpProdutoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_produto_alterar', __METHOD__, $objMdUtlAdmTpProdutoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmTpProdutoDTO->isSetStrNome()){
        $this->validarStrNome($objMdUtlAdmTpProdutoDTO, $objInfraException);
      }
      if ($objMdUtlAdmTpProdutoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objMdUtlAdmTpProdutoDTO, $objInfraException);
      }

      $this->validarDuplicacao($objMdUtlAdmTpProdutoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmTpProdutoBD = new MdUtlAdmTpProdutoBD($this->getObjInfraIBanco());
      $objMdUtlAdmTpProdutoBD->alterar($objMdUtlAdmTpProdutoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Tipo de Produto.',$e);
    }
  }

  private function _validarExclusaoDesativacaoTpProduto($params){
    $objDTO = $params[0];
    $acao   = $params[1];

    $objInfraException       = new InfraException();
    $objMdUtlAdmSerieProdRN  = new MdUtlAdmAtvSerieProdRN();
    $idProduto               = $objDTO->getNumIdMdUtlAdmTpProduto();
    $objMdUtlAdmSerieProdDTO = new MdUtlAdmAtvSerieProdDTO();
    $objMdUtlAdmSerieProdDTO->setNumIdMdUtlAdmTpProduto($idProduto);
    $objMdUtlAdmSerieProdDTO->retTodos();

    $isProduto = $objMdUtlAdmSerieProdRN->contar($objMdUtlAdmSerieProdDTO);
    if($isProduto == 0){
      return true;
    }else{
        $objInfraException = new InfraException();
        $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_35, array($acao));
        $objInfraException->lancarValidacao($msg);
    }
  }

  private function _validarRelAnaliseProduto($objDTO){
    $objInfraException     = new InfraException();
    $objMdUtlRelAnlProdRN  = new MdUtlRelAnaliseProdutoRN();
    $idProduto             = $objDTO->getNumIdMdUtlAdmTpProduto();
    $objMdUtlRelAnlProdDTO = new MdUtlRelAnaliseProdutoDTO();
    $objMdUtlRelAnlProdDTO->setNumIdMdUtlAdmTpProduto($idProduto);
    $objMdUtlRelAnlProdDTO->retTodos();

    $isProduto = $objMdUtlRelAnlProdRN->contar($objMdUtlRelAnlProdDTO);

    if($isProduto == 0){
      return true;
    }else{
      $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_20, array('excluir'));
      return $objInfraException->lancarValidacao($msg);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmTpProdutoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_produto_excluir', __METHOD__, $arrObjMdUtlAdmTpProdutoDTO);

      //Regras de Negocio

      $objMdUtlAdmTpProdutoBD = new MdUtlAdmTpProdutoBD($this->getObjInfraIBanco());

      for($i=0;$i<count($arrObjMdUtlAdmTpProdutoDTO);$i++){

        if($this->_validarExclusaoDesativacaoTpProduto(array($arrObjMdUtlAdmTpProdutoDTO[$i], 'excluir')) && $this->_validarRelAnaliseProduto($arrObjMdUtlAdmTpProdutoDTO[$i])) {
          $objMdUtlAdmTpProdutoBD->excluir($arrObjMdUtlAdmTpProdutoDTO[$i]);
        }
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Tipo de Produto.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmTpProdutoDTO $objMdUtlAdmTpProdutoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_produto_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpProdutoBD = new MdUtlAdmTpProdutoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpProdutoBD->consultar($objMdUtlAdmTpProdutoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Tipo de Produto.',$e);
    }
  }

  protected function listarConectado(MdUtlAdmTpProdutoDTO $objMdUtlAdmTpProdutoDTO) {
    try {

      //Valida Permissao
   //
       SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_produto_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpProdutoBD = new MdUtlAdmTpProdutoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpProdutoBD->listar($objMdUtlAdmTpProdutoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Tipos de Produto.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmTpProdutoDTO $objMdUtlAdmTpProdutoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_produto_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpProdutoBD = new MdUtlAdmTpProdutoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpProdutoBD->contar($objMdUtlAdmTpProdutoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Tipos de Produto.',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAdmTpProdutoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_produto_desativar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpProdutoBD = new MdUtlAdmTpProdutoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmTpProdutoDTO);$i++){
        if($this->_validarExclusaoDesativacaoTpProduto(array($arrObjMdUtlAdmTpProdutoDTO[$i], 'desativar'))) {
          $objMdUtlAdmTpProdutoBD->desativar($arrObjMdUtlAdmTpProdutoDTO[$i]);
        }else{
          $objInfraException->lancarValidacao('Não é possível desativar este Tipo de Produto pois o mesmo está vinculado a uma Atividade.');
        }
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Tipo de Produto.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmTpProdutoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_produto_reativar', __METHOD__, $arrObjMdUtlAdmTpProdutoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpProdutoBD = new MdUtlAdmTpProdutoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmTpProdutoDTO);$i++){
        $objMdUtlAdmTpProdutoBD->reativar($arrObjMdUtlAdmTpProdutoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Tipo de Produto.',$e);
    }
  }
  
}
