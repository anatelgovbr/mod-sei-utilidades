<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/09/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmJustPrazoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(MdUtlAdmJustPrazoDTO $objMdUtlAdmJustPrazoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmJustPrazoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Justificativa não informada.');
    }else{
      $objMdUtlAdmJustPrazoDTO->setStrNome(trim($objMdUtlAdmJustPrazoDTO->getStrNome()));

      if (strlen($objMdUtlAdmJustPrazoDTO->getStrNome())>100){
        $objInfraException->adicionarValidacao('Justificativa possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrDescricao(MdUtlAdmJustPrazoDTO $objMdUtlAdmJustPrazoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmJustPrazoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descricao não informada.');
    }else{
      $objMdUtlAdmJustPrazoDTO->setStrDescricao(trim($objMdUtlAdmJustPrazoDTO->getStrDescricao()));

      if (strlen($objMdUtlAdmJustPrazoDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descricao possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(MdUtlAdmJustPrazoDTO $objMdUtlAdmJustPrazoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmJustPrazoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdUtlAdmJustPrazoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(MdUtlAdmJustPrazoDTO $objMdUtlAdmJustPrazoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_just_prazo_cadastrar', __METHOD__, $objMdUtlAdmJustPrazoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objMdUtlAdmJustPrazoDTO, $objInfraException);
      $this->validarStrDescricao($objMdUtlAdmJustPrazoDTO, $objInfraException);
      $this->validarStrSinAtivo($objMdUtlAdmJustPrazoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmJustPrazoBD->cadastrar($objMdUtlAdmJustPrazoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Justificativa de Dilação de Prazo.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmJustPrazoDTO $objMdUtlAdmJustPrazoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_just_prazo_alterar', __METHOD__. $objMdUtlAdmJustPrazoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmJustPrazoDTO->isSetStrNome()){
        $this->validarStrNome($objMdUtlAdmJustPrazoDTO, $objInfraException);
      }
      if ($objMdUtlAdmJustPrazoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objMdUtlAdmJustPrazoDTO, $objInfraException);
      }
      if ($objMdUtlAdmJustPrazoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objMdUtlAdmJustPrazoDTO, $objInfraException);
      }

     
      $objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      $objMdUtlAdmJustPrazoBD->alterar($objMdUtlAdmJustPrazoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Justificativa de Dilação de Prazo.',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmJustPrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_just_prazo_excluir', __METHOD__, $arrObjMdUtlAdmJustPrazoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmJustPrazoDTO);$i++){
        $objMdUtlAdmJustPrazoBD->excluir($arrObjMdUtlAdmJustPrazoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Justificativa de Dilação de Prazo.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmJustPrazoDTO $objMdUtlAdmJustPrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_just_prazo_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmJustPrazoBD->consultar($objMdUtlAdmJustPrazoDTO);
      
      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Justificativa de Dilação de Prazo.',$e);
    }
  }

  protected function listarConectado(MdUtlAdmJustPrazoDTO $objMdUtlAdmJustPrazoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_just_prazo_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmJustPrazoBD->listar($objMdUtlAdmJustPrazoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Justificativas de Dilação de Prazo.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmJustPrazoDTO $objMdUtlAdmJustPrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_just_prazo_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmJustPrazoBD->contar($objMdUtlAdmJustPrazoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Justificativas de Dilação de Prazo.',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAdmJustPrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_just_prazo_desativar',__METHOD__, $arrObjMdUtlAdmJustPrazoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmJustPrazoDTO);$i++){
        $objMdUtlAdmJustPrazoBD->desativar($arrObjMdUtlAdmJustPrazoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Justificativa de Dilação de Prazo.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmJustPrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_just_prazo_reativar',__METHOD__, $arrObjMdUtlAdmJustPrazoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmJustPrazoDTO);$i++){
        $objMdUtlAdmJustPrazoBD->reativar($arrObjMdUtlAdmJustPrazoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Justificativa de Dilação de Prazo.',$e);
    }
  }
  
  protected function validarDuplicidadeControlado($params){

      $mdUtlAdmJustPrazoDTO = new MdUtlAdmJustPrazoDTO();
      $mdUtlAdmJustPrazoDTO->setStrNome(trim($params[0]));
      $mdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmTpCtrlDesemp($params[1]);
      if(array_key_exists(2,$params)){
          $mdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmJustPrazo($params[2],InfraDTO::$OPER_DIFERENTE);
      }

      if($this->contar($mdUtlAdmJustPrazoDTO)>0){
          $objInfraException = new InfraException();
          $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_08, array('Justificativa'));
          $objInfraException->lancarValidacao($msg);
      }
      return true;


  }


}
