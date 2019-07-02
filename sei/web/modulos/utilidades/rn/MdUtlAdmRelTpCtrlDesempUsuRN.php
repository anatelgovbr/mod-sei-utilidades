<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/07/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelTpCtrlDesempUsuRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  
  protected function cadastrarControlado(MdUtlAdmRelTpCtrlDesempUsuDTO $objMdUtlAdmRelTpCtrlDesempUsuDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_tp_ctrl_desemp_usu_cadastrar', __METHOD__, $objMdUtlAdmRelTpCtrlDesempUsuDTO);
      
      //Regras de Negocio
      $objInfraException = new InfraException();


      $objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUsuBD = new MdUtlAdmRelTpCtrlDesempUsuBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmRelTpCtrlDesempUsuBD->cadastrar($objMdUtlAdmRelTpCtrlDesempUsuDTO);
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmRelTpCtrlDesempUsuDTO $objMdUtlAdmRelTpCtrlDesempUsuDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_tp_ctrl_desemp_usu_alterar', __METHOD__, $objMdUtlAdmRelTpCtrlDesempUsuDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUsuBD = new MdUtlAdmRelTpCtrlDesempUsuBD($this->getObjInfraIBanco());
      $objMdUtlAdmRelTpCtrlDesempUsuBD->alterar($objMdUtlAdmRelTpCtrlDesempUsuDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmRelTpCtrlDesempUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_tp_ctrl_desemp_usu_excluir', __METHOD__, $arrObjMdUtlAdmRelTpCtrlDesempUsuDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUsuBD = new MdUtlAdmRelTpCtrlDesempUsuBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmRelTpCtrlDesempUsuDTO);$i++){
        $objMdUtlAdmRelTpCtrlDesempUsuBD->excluir($arrObjMdUtlAdmRelTpCtrlDesempUsuDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmRelTpCtrlDesempUsuDTO $objMdUtlAdmRelTpCtrlDesempUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_tp_ctrl_desemp_usu_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUsuBD = new MdUtlAdmRelTpCtrlDesempUsuBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmRelTpCtrlDesempUsuBD->consultar($objMdUtlAdmRelTpCtrlDesempUsuDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado($objMdUtlAdmRelTpCtrlDesempUsuDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_tp_ctrl_desemp_usu_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUsuBD = new MdUtlAdmRelTpCtrlDesempUsuBD($this->getObjInfraIBanco());


  /*    if(is_array($objMdUtlAdmRelTpCtrlDesempUsuDTO)){
        $ret = $objMdUtlAdmRelTpCtrlDesempUsuBD->listar($objMdUtlAdmRelTpCtrlDesempUsuDTO[0], true);

      }else{*/
        $ret = $objMdUtlAdmRelTpCtrlDesempUsuBD->listar($objMdUtlAdmRelTpCtrlDesempUsuDTO);
    //  }
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdUtlAdmRelTpCtrlDesempUsuDTO $objMdUtlAdmRelTpCtrlDesempUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_tp_ctrl_desemp_usu_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUsuBD = new MdUtlAdmRelTpCtrlDesempUsuBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmRelTpCtrlDesempUsuBD->contar($objMdUtlAdmRelTpCtrlDesempUsuDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAdmRelTpCtrlDesempUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_tp_ctrl_desemp_usu_desativar',__METHOD__, $arrObjMdUtlAdmRelTpCtrlDesempUsuDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUsuBD = new MdUtlAdmRelTpCtrlDesempUsuBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmRelTpCtrlDesempUsuDTO);$i++){
        $objMdUtlAdmRelTpCtrlDesempUsuBD->desativar($arrObjMdUtlAdmRelTpCtrlDesempUsuDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmRelTpCtrlDesempUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_tp_ctrl_desemp_usu_reativar', __METHOD__, $arrObjMdUtlAdmRelTpCtrlDesempUsuDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUsuBD = new MdUtlAdmRelTpCtrlDesempUsuBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmRelTpCtrlDesempUsuDTO);$i++){
        $objMdUtlAdmRelTpCtrlDesempUsuBD->reativar($arrObjMdUtlAdmRelTpCtrlDesempUsuDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function usuarioLogadoIsGestorTpControleConectado(){
    $objMdUtlAdmTpCtrlDesempRN = new MdUtlAdmRelTpCtrlDesempUsuRN();
    $idUsInterno = SessaoSEI::getInstance()->getNumIdUsuario();

    if($idUsInterno) {
        $objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmRelTpCtrlDesempUsuDTO();
        $objMdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmTpCtrlDesemp();
        $objMdUtlAdmTpCtrlDesempDTO->setNumIdUsuario($idUsInterno);

        $isGestorAlgumaUnidade = $objMdUtlAdmTpCtrlDesempRN->contar($objMdUtlAdmTpCtrlDesempDTO) > 0;

        if ($isGestorAlgumaUnidade) {
            $arrIdsTpCtrl = InfraArray::converterArrInfraDTO($objMdUtlAdmTpCtrlDesempRN->listar($objMdUtlAdmTpCtrlDesempDTO), 'IdMdUtlAdmTpCtrlDesemp');

            $objMdUtlAdmUndDTO = new MdUtlAdmRelTpCtrlDesempUndDTO();
            $objMdUtlAdmUndRN  = new MdUtlAdmRelTpCtrlDesempUndRN();
            $objMdUtlAdmUndDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objMdUtlAdmUndDTO->setNumIdMdUtlAdmTpCtrlDesemp($arrIdsTpCtrl, InfraDTO::$OPER_IN);
            $objMdUtlAdmUndDTO->retNumIdMdUtlAdmTpCtrlDesemp();

            $isGestor = $objMdUtlAdmUndRN->contar($objMdUtlAdmUndDTO) > 0;

            if ($isGestor) {
                $arrObjs = $objMdUtlAdmTpCtrlDesempRN->listar($objMdUtlAdmTpCtrlDesempDTO);
                $idsTpControle = InfraArray::converterArrInfraDTO($arrObjs, 'IdMdUtlAdmTpCtrlDesemp');
                return $idsTpControle;
            } else {
                return false;
            }
        }
    }

    return false;
  }

  protected function usuarioLogadoIsGestorSipSeiConectado(){
      $isGestorTpCtrl = count($this->usuarioLogadoIsGestorTpControle()) > 0;
      $objPermissaoRN  = new MdUtlAdmPermissaoRN();
      $isGestorSip     = $objPermissaoRN->isGestor();

      return $isGestorSip && $isGestorTpCtrl;
  }

  protected function getUnidadesTipoControleConectado($idTpControle = false){

        if($idTpControle) {
            $objMdUtlAdmTpControleUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
            $objMdUtlAdmTpControleUndDTO = new MdUtlAdmRelTpCtrlDesempUndDTO();
            $objMdUtlAdmTpControleUndDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpControle);
            $objMdUtlAdmTpControleUndDTO->retNumIdUnidade();

            $count = $objMdUtlAdmTpControleUndRN->contar($objMdUtlAdmTpControleUndDTO);

            if ($count > 0) {
                $arrObjs = $objMdUtlAdmTpControleUndRN->listar($objMdUtlAdmTpControleUndDTO);
                $idsUnidades = InfraArray::converterArrInfraDTO($arrObjs, 'IdUnidade');
                return $idsUnidades;
            }
        }

        return null;
    }


}
