<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/07/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmRelTpCtrlDesempUndRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  
  protected function cadastrarControlado(MdUtlAdmRelTpCtrlDesempUndDTO $objMdUtlAdmRelTpCtrlDesempUndDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_tp_ctrl_desemp_und_cadastrar', __METHOD__, $objMdUtlAdmRelTpCtrlDesempUndDTO);
      //Regras de Negocio
      $objInfraException = new InfraException();


      $objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUndBD = new MdUtlAdmRelTpCtrlDesempUndBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmRelTpCtrlDesempUndBD->cadastrar($objMdUtlAdmRelTpCtrlDesempUndDTO);
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmRelTpCtrlDesempUndDTO $objMdUtlAdmRelTpCtrlDesempUndDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_tp_ctrl_und_alterar', __METHOD__, $objMdUtlAdmRelTpCtrlDesempUndDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUndBD = new MdUtlAdmRelTpCtrlDesempUndBD($this->getObjInfraIBanco());
      $objMdUtlAdmRelTpCtrlDesempUndBD->alterar($objMdUtlAdmRelTpCtrlDesempUndDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmRelTpCtrlDesempUndDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_tp_ctrl_desemp_und_excluir', __METHOD__, $arrObjMdUtlAdmRelTpCtrlDesempUndDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUndBD = new MdUtlAdmRelTpCtrlDesempUndBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmRelTpCtrlDesempUndDTO);$i++){
        $objMdUtlAdmRelTpCtrlDesempUndBD->excluir($arrObjMdUtlAdmRelTpCtrlDesempUndDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmRelTpCtrlDesempUndDTO $objMdUtlAdmRelTpCtrlDesempUndDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_tp_ctrl_desemp_und_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUndBD = new MdUtlAdmRelTpCtrlDesempUndBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmRelTpCtrlDesempUndBD->consultar($objMdUtlAdmRelTpCtrlDesempUndDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdUtlAdmRelTpCtrlDesempUndDTO $objMdUtlAdmRelTpCtrlDesempUndDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_tp_ctrl_desemp_und_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUndBD = new MdUtlAdmRelTpCtrlDesempUndBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmRelTpCtrlDesempUndBD->listar($objMdUtlAdmRelTpCtrlDesempUndDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdUtlAdmRelTpCtrlDesempUndDTO $objMdUtlAdmRelTpCtrlDesempUndDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_rel_tp_ctrl_desemp_und_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUndBD = new MdUtlAdmRelTpCtrlDesempUndBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmRelTpCtrlDesempUndBD->contar($objMdUtlAdmRelTpCtrlDesempUndDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAdmRelTpCtrlDesempUndDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_tp_ctrl_desemp_und_desativar', __METHOD__, $arrObjMdUtlAdmRelTpCtrlDesempUndDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUndBD = new MdUtlAdmRelTpCtrlDesempUndBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmRelTpCtrlDesempUndDTO);$i++){
        $objMdUtlAdmRelTpCtrlDesempUndBD->desativar($arrObjMdUtlAdmRelTpCtrlDesempUndDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmRelTpCtrlDesempUndDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_rel_tp_ctrl_desemp_und_reativar', __METHOD__, $arrObjMdUtlAdmRelTpCtrlDesempUndDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmRelTpCtrlDesempUndBD = new MdUtlAdmRelTpCtrlDesempUndBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmRelTpCtrlDesempUndDTO);$i++){
        $objMdUtlAdmRelTpCtrlDesempUndBD->reativar($arrObjMdUtlAdmRelTpCtrlDesempUndDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

    protected function validarDuplicidadeUnidadeControlado($params){

        $arrUnidades = $params[0];
        $idTipoControleUtilidade = array_key_exists(1,$params)?$params[1]:0;
        $isAlterar = false;

        //Validar se já existe uma unidade cadastrada para outro tipo de controle.
        $objTipoControleUtilidadesUnidadeDTO = new MdUtlAdmRelTpCtrlDesempUndDTO();
        $objTipoControleUtilidadesUnidadeDTO->setNumIdUnidade($arrUnidades,InfraDTO::$OPER_IN);

        if($idTipoControleUtilidade>0) {
            $isAlterar = true;
            $objTipoControleUtilidadesUnidadeDTO->setNumIdMdUtlAdmTpCtrlDesemp($_POST['hdnIdTipoControleUtilidades'], InfraDTO::$OPER_DIFERENTE);
        }

        $objTipoControleUtilidadesUnidadeDTO->retTodos(true);
        $objTipoControleUtilidadesUnidadeRN = new MdUtlAdmRelTpCtrlDesempUndRN();
        $objTipoControleUtilidadesUnidade = $objTipoControleUtilidadesUnidadeRN->listar($objTipoControleUtilidadesUnidadeDTO);

        if(count($objTipoControleUtilidadesUnidade)>0){

            $msg  = '';
            $acao = $isAlterar ? 'alterar' : 'cadastrar';
            $msg  = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_01, $acao);
            $msg .= '\n';

            for($i = 0 ; $i< count($objTipoControleUtilidadesUnidade) ; $i++){
                $msg.=' - '.$objTipoControleUtilidadesUnidade[$i]->getStrSiglaUnidade().'\n';
            }

            $objInfraException = new InfraException();
            $objInfraException->lancarValidacao($msg);
        }

    }

    protected function getTipoControleUnidadeLogadaConectado()
    {
        $idTpCtrl = null;
        $objMdUtlAdmTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
        $objMdUtlAdmTpCtrlUndDTO = new MdUtlAdmRelTpCtrlDesempUndDTO();
        $objMdUtlAdmTpCtrlUndDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlAdmTpCtrlUndDTO->retNumIdMdUtlAdmTpCtrlDesemp();

        $count = $objMdUtlAdmTpCtrlUndRN->contar($objMdUtlAdmTpCtrlUndDTO);

        if ($count > 0) {
            $arrObjs = $objMdUtlAdmTpCtrlUndRN->listar($objMdUtlAdmTpCtrlUndDTO);
            $obj = current($arrObjs);
            $idTpCtrl = $obj->getNumIdMdUtlAdmTpCtrlDesemp();
        }

        return $idTpCtrl;
    }

    protected function getObjTipoControleUnidadeLogadaConectado()
    {
        $idTpCtrl = null;
        $objMdUtlAdmTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
        $objMdUtlAdmTpCtrlUndDTO = new MdUtlAdmRelTpCtrlDesempUndDTO();
        $objMdUtlAdmTpCtrlUndDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMdUtlAdmTpCtrlUndDTO->retNumIdMdUtlAdmTpCtrlDesemp();
        $objMdUtlAdmTpCtrlUndDTO->retStrNomeTipoControle();

        $count = $objMdUtlAdmTpCtrlUndRN->contar($objMdUtlAdmTpCtrlUndDTO);

        if ($count > 0) {
            $arrObjs = $objMdUtlAdmTpCtrlUndRN->listar($objMdUtlAdmTpCtrlUndDTO);
            $obj = current($arrObjs);
        }

        return $obj;
    }

}
