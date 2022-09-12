<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/07/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelPrmGrProcRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdUtlAdmParamGr(MdUtlAdmRelPrmGrProcDTO $objMdUtlAdmRelPrmGrProcDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmRelPrmGrProcDTO->getNumIdMdUtlAdmParamGr())){
      $objMdUtlAdmRelPrmGrProcDTO->setNumIdMdUtlAdmParamGr(null);
    }
  }

  private function validarNumIdTipoProcedimento(MdUtlAdmRelPrmGrProcDTO $objMdUtlAdmRelPrmGrProcDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmRelPrmGrProcDTO->getNumIdTipoProcedimento())){
      $objMdUtlAdmRelPrmGrProcDTO->setNumIdTipoProcedimento(null);
    }
  }

  protected function cadastrarControlado(MdUtlAdmRelPrmGrProcDTO $objMdUtlAdmRelPrmGrProcDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_prm_gr_proc_cadastrar', __METHOD__, $objMdUtlAdmRelPrmGrProcDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdUtlAdmParamGr($objMdUtlAdmRelPrmGrProcDTO, $objInfraException);
      $this->validarNumIdTipoProcedimento($objMdUtlAdmRelPrmGrProcDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmRelPrmGrProcBD = new MdUtlAdmRelPrmGrProcBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmRelPrmGrProcBD->cadastrar($objMdUtlAdmRelPrmGrProcDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmRelPrmGrProcDTO $objMdUtlAdmRelPrmGrProcDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_prm_gr_proc_alterar', __METHOD__, $objMdUtlAdmRelPrmGrProcDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmRelPrmGrProcDTO->isSetNumIdMdUtlAdmParamGr()){
        $this->validarNumIdMdUtlAdmParamGr($objMdUtlAdmRelPrmGrProcDTO, $objInfraException);
      }
      if ($objMdUtlAdmRelPrmGrProcDTO->isSetNumIdTipoProcedimento()){
        $this->validarNumIdTipoProcedimento($objMdUtlAdmRelPrmGrProcDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmRelPrmGrProcBD = new MdUtlAdmRelPrmGrProcBD($this->getObjInfraIBanco());
      $objMdUtlAdmRelPrmGrProcBD->alterar($objMdUtlAdmRelPrmGrProcDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmRelPrmGrProcDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_prm_gr_proc_excluir', __METHOD__, $arrObjMdUtlAdmRelPrmGrProcDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelPrmGrProcBD = new MdUtlAdmRelPrmGrProcBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmRelPrmGrProcDTO);$i++){
          $obj = $objMdUtlAdmRelPrmGrProcBD->excluir($arrObjMdUtlAdmRelPrmGrProcDTO[$i]);

      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmRelPrmGrProcDTO $objMdUtlAdmRelPrmGrProcDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_prm_gr_proc_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelPrmGrProcBD = new MdUtlAdmRelPrmGrProcBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmRelPrmGrProcBD->consultar($objMdUtlAdmRelPrmGrProcDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdUtlAdmRelPrmGrProcDTO $objMdUtlAdmRelPrmGrProcDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_prm_gr_proc_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelPrmGrProcBD = new MdUtlAdmRelPrmGrProcBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmRelPrmGrProcBD->listar($objMdUtlAdmRelPrmGrProcDTO);


      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdUtlAdmRelPrmGrProcDTO $objMdUtlAdmRelPrmGrProcDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_prm_gr_proc_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelPrmGrProcBD = new MdUtlAdmRelPrmGrProcBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmRelPrmGrProcBD->contar($objMdUtlAdmRelPrmGrProcDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

  protected function montarArrTpProcessoControlado($idMdUtlAdmPrmGr){

      $mdUtlAdmRelPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO();
      $mdUtlAdmRelPrmGrProcDTO->setNumIdMdUtlAdmParamGr($idMdUtlAdmPrmGr);
      $mdUtlAdmRelPrmGrProcDTO->retTodos();
      $mdUtlAdmRelPrmGrProcDTO->retStrNomeProcedimento();
      $mdUtlAdmRelPrmGrProcDTO->setOrdStrNomeProcedimento(InfraDTO::$TIPO_ORDENACAO_ASC);

      $mdUtlAdmRelPrmGrProc = $this->listar($mdUtlAdmRelPrmGrProcDTO);
      $arrTpProcedimento = array();
      for($i =0 ; $i< count($mdUtlAdmRelPrmGrProc); $i++){

          $tpProc = array();
          $tpProc[] =$mdUtlAdmRelPrmGrProc[$i]->getNumIdTipoProcedimento();
          $tpProc[] =$mdUtlAdmRelPrmGrProc[$i]->getStrNomeProcedimento();

          $arrTpProcedimento[]=$tpProc;
      }

      $arrItenLupaTpProcesso = PaginaSEI::getInstance()->gerarItensLupa($arrTpProcedimento);
     return $arrItenLupaTpProcesso;
  }

}
