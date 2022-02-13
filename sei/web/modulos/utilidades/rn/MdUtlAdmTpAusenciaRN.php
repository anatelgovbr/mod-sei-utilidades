<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/07/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';
class MdUtlAdmTpAusenciaRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }



  private function validarStrNome(MdUtlAdmTpAusenciaDTO $objMdUtlAdmTpAusenciaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmTpAusenciaDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objMdUtlAdmTpAusenciaDTO->setStrNome(trim($objMdUtlAdmTpAusenciaDTO->getStrNome()));

      if (strlen($objMdUtlAdmTpAusenciaDTO->getStrNome())>100){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrDescricao(MdUtlAdmTpAusenciaDTO $objMdUtlAdmTpAusenciaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmTpAusenciaDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descricao não informada.');
    }else{
      $objMdUtlAdmTpAusenciaDTO->setStrDescricao(trim($objMdUtlAdmTpAusenciaDTO->getStrDescricao()));

      if (strlen($objMdUtlAdmTpAusenciaDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descricao possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(MdUtlAdmTpAusenciaDTO $objMdUtlAdmTpAusenciaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmTpAusenciaDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdUtlAdmTpAusenciaDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  private function validarDuplicacao($objMdUtlAdmTpAusenciaDTO, $objInfraException){
      $idTpAusencia = $objMdUtlAdmTpAusenciaDTO->getNumIdMdUtlAdmTpAusencia();
      $objMdUtlAdmTpAusenciaDTO2 = new MdUtlAdmTpAusenciaDTO();
      $objMdUtlAdmTpAusenciaDTO2->setBolExclusaoLogica(false);
      $objMdUtlAdmTpAusenciaDTO2->setStrNome($objMdUtlAdmTpAusenciaDTO->getStrNome());

      if(!is_null($idTpAusencia)){
        $objMdUtlAdmTpAusenciaDTO2->setNumIdMdUtlAdmTpAusencia($idTpAusencia, InfraDTO::$OPER_DIFERENTE);
      }

      $existeRegistroDupl = $this->contar($objMdUtlAdmTpAusenciaDTO2) > 0;

      if($existeRegistroDupl){
        $objInfraException->lancarValidacao('Já existe um Motivo de Ausência cadastrado com este nome.');
      }
  }

  protected function cadastrarControlado(MdUtlAdmTpAusenciaDTO $objMdUtlAdmTpAusenciaDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_ausencia_cadastrar', __METHOD__, $objMdUtlAdmTpAusenciaDTO);
      $objMdUtlAdmTpAusenciaBD = new MdUtlAdmTpAusenciaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpAusenciaBD->cadastrar($objMdUtlAdmTpAusenciaDTO);
      
        //Regras de Negocio
        $objInfraException = new InfraException();
        $this->validarDuplicacao($objMdUtlAdmTpAusenciaDTO, $objInfraException);
        $objInfraException->lancarValidacoes();

        return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Motivo de Ausência.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmTpAusenciaDTO $objMdUtlAdmTpAusenciaDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_ausencia_alterar', __METHOD__, $objMdUtlAdmTpAusenciaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmTpAusenciaDTO->isSetStrNome()){
        $this->validarStrNome($objMdUtlAdmTpAusenciaDTO, $objInfraException);
      }
      if ($objMdUtlAdmTpAusenciaDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objMdUtlAdmTpAusenciaDTO, $objInfraException);
      }
      if ($objMdUtlAdmTpAusenciaDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objMdUtlAdmTpAusenciaDTO, $objInfraException);
      }

      $this->validarDuplicacao($objMdUtlAdmTpAusenciaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmTpAusenciaBD = new MdUtlAdmTpAusenciaBD($this->getObjInfraIBanco());
      $objMdUtlAdmTpAusenciaBD->alterar($objMdUtlAdmTpAusenciaDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Motivo de Ausência.',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmTpAusenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_ausencia_excluir', __METHOD__, $arrObjMdUtlAdmTpAusenciaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpAusenciaBD = new MdUtlAdmTpAusenciaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmTpAusenciaDTO);$i++){
        $objMdUtlAdmTpAusenciaBD->excluir($arrObjMdUtlAdmTpAusenciaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Motivo de Ausência.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmTpAusenciaDTO $objMdUtlAdmTpAusenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_ausencia_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpAusenciaBD = new MdUtlAdmTpAusenciaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpAusenciaBD->consultar($objMdUtlAdmTpAusenciaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Motivo de Ausência.',$e);
    }
  }

  protected function listarConectado(MdUtlAdmTpAusenciaDTO $objMdUtlAdmTpAusenciaDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_ausencia_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpAusenciaBD = new MdUtlAdmTpAusenciaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpAusenciaBD->listar($objMdUtlAdmTpAusenciaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Motivos de Ausência.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmTpAusenciaDTO $objMdUtlAdmTpAusenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_ausencia_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpAusenciaBD = new MdUtlAdmTpAusenciaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpAusenciaBD->contar($objMdUtlAdmTpAusenciaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Motivos de Ausência.',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAdmTpAusenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_ausencia_desativar', __METHOD__, $arrObjMdUtlAdmTpAusenciaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpAusenciaBD = new MdUtlAdmTpAusenciaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmTpAusenciaDTO);$i++){
        $objMdUtlAdmTpAusenciaBD->desativar($arrObjMdUtlAdmTpAusenciaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Motivo de Ausência.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmTpAusenciaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_ausencia_reativar', __METHOD__, $arrObjMdUtlAdmTpAusenciaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpAusenciaBD = new MdUtlAdmTpAusenciaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmTpAusenciaDTO);$i++){
        $objMdUtlAdmTpAusenciaBD->reativar($arrObjMdUtlAdmTpAusenciaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Motivo de Ausência.',$e);
    }
  }

}
