<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/07/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelJornadaUsuRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }


  protected function cadastrarControlado(MdUtlAdmRelJornadaUsuDTO $objMdUtlAdmRelJornadaUsuDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_jornada_usu_cadastrar', __METHOD__, $objMdUtlAdmRelJornadaUsuDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmRelJornadaUsuBD = new MdUtlAdmRelJornadaUsuBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmRelJornadaUsuBD->cadastrar($objMdUtlAdmRelJornadaUsuDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Jornada.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmRelJornadaUsuDTO $objMdUtlAdmRelJornadaUsuDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_jornada_usu_alterar', __METHOD__, $objMdUtlAdmRelJornadaUsuDTO);

      //Regras de Negocio
  /*    $objInfraException = new InfraException();

      $objInfraException->lancarValidacoes();*/

      $objMdUtlAdmRelJornadaUsuBD = new MdUtlAdmRelJornadaUsuBD($this->getObjInfraIBanco());
      $objMdUtlAdmRelJornadaUsuBD->alterar($objMdUtlAdmRelJornadaUsuDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Jornada.',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmRelJornadaUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_jornada_usu_excluir', __METHOD__, $arrObjMdUtlAdmRelJornadaUsuDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelJornadaUsuBD = new MdUtlAdmRelJornadaUsuBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmRelJornadaUsuDTO);$i++){
        $objMdUtlAdmRelJornadaUsuBD->excluir($arrObjMdUtlAdmRelJornadaUsuDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Jornada.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmRelJornadaUsuDTO $objMdUtlAdmRelJornadaUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_jornada_usu_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelJornadaUsuBD = new MdUtlAdmRelJornadaUsuBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmRelJornadaUsuBD->consultar($objMdUtlAdmRelJornadaUsuDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Jornada.',$e);
    }
  }

  protected function listarConectado(MdUtlAdmRelJornadaUsuDTO $objMdUtlAdmRelJornadaUsuDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_jornada_usu_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelJornadaUsuBD = new MdUtlAdmRelJornadaUsuBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmRelJornadaUsuBD->listar($objMdUtlAdmRelJornadaUsuDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Jornada.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmRelJornadaUsuDTO $objMdUtlAdmRelJornadaUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_jornada_usu_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelJornadaUsuBD = new MdUtlAdmRelJornadaUsuBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmRelJornadaUsuBD->contar($objMdUtlAdmRelJornadaUsuDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Jornada.',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAdmRelJornadaUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_jornada_usu_desativar', __METHOD__, $arrObjMdUtlAdmRelJornadaUsuDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelJornadaUsuBD = new MdUtlAdmRelJornadaUsuBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmRelJornadaUsuDTO);$i++){
        $objMdUtlAdmRelJornadaUsuBD->desativar($arrObjMdUtlAdmRelJornadaUsuDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Jornada.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmRelJornadaUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_jornada_usu_reativar', __METHOD__, $arrObjMdUtlAdmRelJornadaUsuDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelJornadaUsuBD = new MdUtlAdmRelJornadaUsuBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmRelJornadaUsuDTO);$i++){
        $objMdUtlAdmRelJornadaUsuBD->reativar($arrObjMdUtlAdmRelJornadaUsuDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Jornada.',$e);
    }
  }

}
