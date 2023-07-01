<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/09/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmGrpFlProcAtvRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdUtlAdmAtividade(MdUtlAdmGrpFlProcAtvDTO $objMdUtlAdmGrpFlProcAtvDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmGrpFlProcAtvDTO->getNumIdMdUtlAdmAtividade())){
      $objInfraException->adicionarValidacao('Id de Atividade não informado');
    }
  }

  private function validarNumIdMdUtlAdmGrpFilaProc(MdUtlAdmGrpFlProcAtvDTO $objMdUtlAdmGrpFlProcAtvDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmGrpFlProcAtvDTO->getNumIdMdUtlAdmGrpFilaProc())){
      $objInfraException->adicionarValidacao('Grupo de Atividade não informado.');
    }
  }

  protected function cadastrarControlado($objMdUtlAdmGrpFlProcAtvDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_grp_fl_proc_atv_cadastrar', __METHOD__, $objMdUtlAdmGrpFlProcAtvDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFlProcAtvBD = new MdUtlAdmGrpFlProcAtvBD($this->getObjInfraIBanco());
      if(is_array($objMdUtlAdmGrpFlProcAtvDTO)) {
          for($i = 0; $i <count($objMdUtlAdmGrpFlProcAtvDTO) ; $i++) {
              $ret = $objMdUtlAdmGrpFlProcAtvBD->cadastrar($objMdUtlAdmGrpFlProcAtvDTO[$i]);
          }
      }else{
          $ret = $objMdUtlAdmGrpFlProcAtvBD->cadastrar($objMdUtlAdmGrpFlProcAtvDTO);
      }
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Parametro.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmGrpFlProcAtvDTO $objMdUtlAdmGrpFlProcAtvDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_grp_fl_proc_atv_alterar', __METHOD__, $objMdUtlAdmGrpFlProcAtvDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmGrpFlProcAtvDTO->isSetNumIdMdUtlAdmAtividade()){
        $this->validarNumIdMdUtlAdmAtividade($objMdUtlAdmGrpFlProcAtvDTO, $objInfraException);
      }
      if ($objMdUtlAdmGrpFlProcAtvDTO->isSetNumIdMdUtlAdmGrpFilaProc()){
        $this->validarNumIdMdUtlAdmGrpFilaProc($objMdUtlAdmGrpFlProcAtvDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFlProcAtvBD = new MdUtlAdmGrpFlProcAtvBD($this->getObjInfraIBanco());
      $objMdUtlAdmGrpFlProcAtvBD->alterar($objMdUtlAdmGrpFlProcAtvDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Parametro.',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmGrpFlProcAtvDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_grp_fl_proc_atv_excluir', __METHOD__,$arrObjMdUtlAdmGrpFlProcAtvDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFlProcAtvBD = new MdUtlAdmGrpFlProcAtvBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmGrpFlProcAtvDTO);$i++){
          $ret = $objMdUtlAdmGrpFlProcAtvBD->excluir($arrObjMdUtlAdmGrpFlProcAtvDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Parametro.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmGrpFlProcAtvDTO $objMdUtlAdmGrpFlProcAtvDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_grp_fl_proc_atv_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFlProcAtvBD = new MdUtlAdmGrpFlProcAtvBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmGrpFlProcAtvBD->consultar($objMdUtlAdmGrpFlProcAtvDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Parametro.',$e);
    }
  }

  protected function listarConectado(MdUtlAdmGrpFlProcAtvDTO $objMdUtlAdmGrpFlProcAtvDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_grp_fl_proc_atv_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFlProcAtvBD = new MdUtlAdmGrpFlProcAtvBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmGrpFlProcAtvBD->listar($objMdUtlAdmGrpFlProcAtvDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Parametros.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmGrpFlProcAtvDTO $objMdUtlAdmGrpFlProcAtvDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_grp_fl_proc_atv_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFlProcAtvBD = new MdUtlAdmGrpFlProcAtvBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmGrpFlProcAtvBD->contar($objMdUtlAdmGrpFlProcAtvDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Parametros.',$e);
    }
  }

  protected function cadastrarAtvVinculoControlado($arrProcAtv){
      $arrDTO = array();

          $arrIdMdUtlAdmGrpFilaProc = array_keys($arrProcAtv);
        for($j = 0 ; $j<count($arrIdMdUtlAdmGrpFilaProc);$j++) {

            $idMdUtlAdmGrpFilaProc = $arrIdMdUtlAdmGrpFilaProc[$j];

            foreach ($arrProcAtv[$idMdUtlAdmGrpFilaProc] as $atividade){

                $mdUtlAdmGrpFlProcAtvDTO = new MdUtlAdmGrpFlProcAtvDTO();
                $mdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmAtividade($atividade);
                $mdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmGrpFilaProc($idMdUtlAdmGrpFilaProc);
                $arrDTO[] = $mdUtlAdmGrpFlProcAtvDTO;

            }
        }

        $this->cadastrar($arrDTO);

  }

  protected function consultarExcluirVinculoControlado($idRel){
      $mdUtlAdmGrpFlProcAtvDTO = new MdUtlAdmGrpFlProcAtvDTO();
      $mdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmAtividade($idRel);
      $mdUtlAdmGrpFlProcAtvDTO->retTodos();

      if($this->contar($mdUtlAdmGrpFlProcAtvDTO)>0){
          $objInfraException = new InfraException();
          $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_44, array('excluir'));
          $objInfraException->lancarValidacao($msg);
      }

  }
}
