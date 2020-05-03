<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/09/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmGrpFilaRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdUtlAdmGrp(MdUtlAdmGrpFilaDTO $objMdUtlAdmGrpFilaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmGrpFilaDTO->getNumIdMdUtlAdmGrp())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumIdMdUtlAdmFila(MdUtlAdmGrpFilaDTO $objMdUtlAdmGrpFilaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmGrpFilaDTO->getNumIdMdUtlAdmFila())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarStrSinAtivo(MdUtlAdmGrpFilaDTO $objMdUtlAdmGrpFilaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmGrpFilaDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdUtlAdmGrpFilaDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(MdUtlAdmGrpFilaDTO $objMdUtlAdmGrpFilaDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_grp_fila_cadastrar', __METHOD__, $objMdUtlAdmGrpFilaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdUtlAdmGrp($objMdUtlAdmGrpFilaDTO, $objInfraException);
      $this->validarNumIdMdUtlAdmFila($objMdUtlAdmGrpFilaDTO, $objInfraException);
      $this->validarStrSinAtivo($objMdUtlAdmGrpFilaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFilaBD = new MdUtlAdmGrpFilaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmGrpFilaBD->cadastrar($objMdUtlAdmGrpFilaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando grupo atividades.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmGrpFilaDTO $objMdUtlAdmGrpFilaDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_grp_fila_alterar', -__METHOD__, $objMdUtlAdmGrpFilaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmGrpFilaDTO->isSetNumIdMdUtlAdmGrp()){
        $this->validarNumIdMdUtlAdmGrp($objMdUtlAdmGrpFilaDTO, $objInfraException);
      }
      if ($objMdUtlAdmGrpFilaDTO->isSetNumIdMdUtlAdmFila()){
        $this->validarNumIdMdUtlAdmFila($objMdUtlAdmGrpFilaDTO, $objInfraException);
      }
      if ($objMdUtlAdmGrpFilaDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objMdUtlAdmGrpFilaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFilaBD = new MdUtlAdmGrpFilaBD($this->getObjInfraIBanco());
      $objMdUtlAdmGrpFilaBD->alterar($objMdUtlAdmGrpFilaDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando grupo atividades.',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmGrpFilaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_grp_fila_excluir', __METHOD__, $arrObjMdUtlAdmGrpFilaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFilaBD = new MdUtlAdmGrpFilaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmGrpFilaDTO);$i++){
        $objMdUtlAdmGrpFilaBD->excluir($arrObjMdUtlAdmGrpFilaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo grupo atividades.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmGrpFilaDTO $objMdUtlAdmGrpFilaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_grp_fila_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFilaBD = new MdUtlAdmGrpFilaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmGrpFilaBD->consultar($objMdUtlAdmGrpFilaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando grupo atividades.',$e);
    }
  }

  protected function listarConectado($objMdUtlAdmGrpFilaDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_grp_fila_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFilaBD = new MdUtlAdmGrpFilaBD($this->getObjInfraIBanco());
      if(is_array($objMdUtlAdmGrpFilaDTO)){
        $ret = $objMdUtlAdmGrpFilaBD->listar($objMdUtlAdmGrpFilaDTO[0], true);
        echo '<pre>';
        print_r($ret);
        exit;
      }
      $ret = $objMdUtlAdmGrpFilaBD->listar($objMdUtlAdmGrpFilaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando grupo atividades.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmGrpFilaDTO $objMdUtlAdmGrpFilaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_grp_fila_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFilaBD = new MdUtlAdmGrpFilaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmGrpFilaBD->contar($objMdUtlAdmGrpFilaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando grupo atividades.',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAdmGrpFilaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_grp_fila_desativar', __METHOD__, $arrObjMdUtlAdmGrpFilaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFilaBD = new MdUtlAdmGrpFilaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmGrpFilaDTO);$i++){
        $objMdUtlAdmGrpFilaBD->desativar($arrObjMdUtlAdmGrpFilaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando grupo atividades.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmGrpFilaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_grp_fila_reativar', __METHOD__, $arrObjMdUtlAdmGrpFilaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFilaBD = new MdUtlAdmGrpFilaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmGrpFilaDTO);$i++){
        $objMdUtlAdmGrpFilaBD->reativar($arrObjMdUtlAdmGrpFilaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando grupo atividades.',$e);
    }
  }
  

  protected function verificaQtdRegistrosRelacionadosConectado($idRel){
    if($idRel && $idRel != '') {
      $objMdUtlAdmGrpDTO = new  MdUtlAdmGrpFilaDTO();
      $objMdUtlAdmGrpDTO->setNumIdMdUtlAdmGrpFila($idRel);
      $objMdUtlAdmGrpDTO->retTodos();
      $objMdUtlAdmGrpDTO->setBolExclusaoLogica(false);
      $objMdUtlAdmGrpDTO->setStrSinAtivo(array('S','N'), InfraDTO::$OPER_IN);
      $objDTO = $this->consultar($objMdUtlAdmGrpDTO);

      $idMain = $objDTO->getNumIdMdUtlAdmGrp();

      if($idMain){
        $objMdUtlAdmGrpDTO = new  MdUtlAdmGrpFilaDTO();
        $objMdUtlAdmGrpDTO->setNumIdMdUtlAdmGrp($idMain);
        $objMdUtlAdmGrpDTO->setBolExclusaoLogica(false);
        $objMdUtlAdmGrpDTO->setStrSinAtivo(array('S','N'), InfraDTO::$OPER_IN);
        $objMdUtlAdmGrpDTO->retTodos();
        $count = $this->contar($objMdUtlAdmGrpDTO);

        return array($count, $idMain);
      }

    }

    return null;
  }

  protected function validarDuplicidadeFilaControlado($mdUtlAdmGrpFilaDTO){

      $objInfraException = new InfraException();
      $mdUtlAdmGrpFilaDTO2 = clone($mdUtlAdmGrpFilaDTO);
      $mdUtlAdmGrpFilaDTO2->setBolExclusaoLogica(false);
      $mdUtlAdmGrpFilaDTO2->setStrSinAtivo(array('S','N'), InfraDTO::$OPER_IN);

      $qtdRegistro = $this->contar($mdUtlAdmGrpFilaDTO2);

      if($qtdRegistro > 0){
          $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_22);
          $objInfraException->lancarValidacao($msg);
           return false;
      }

      return true;
  }

  protected function retornarIdGrpFilaControlado($mdUtlAdmGrpFilaDTO){

      $arrMdUtlAdmGrpFilaDTO = $this->listar($mdUtlAdmGrpFilaDTO);
      $arrIdMdUtlAdmGrpFila = array();

      for($i=0 ; $i < count($arrMdUtlAdmGrpFilaDTO) ; $i++){
          $arrIdMdUtlAdmGrpFila[] = $arrMdUtlAdmGrpFilaDTO[$i]->getNumIdMdUtlAdmGrpFila();
      }
      return $arrIdMdUtlAdmGrpFila;
  }

  protected function buscarObjGrpFilaPorIdControlado($idGrpFila){

      $mdUtlAdmGrpFilaDTO = new MdUtlAdmGrpFilaDTO();
      $mdUtlAdmGrpFilaDTO->retStrNomeGrupoAtividade();
      $mdUtlAdmGrpFilaDTO->retStrNomeFila();
      $mdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmGrpFila($idGrpFila);
      $mdUtlAdmGrpFilaDTO->setBolExclusaoLogica(false);

      return $this->consultar($mdUtlAdmGrpFilaDTO);
  }
  
  protected function buscarGruposFilaVinculadosConectado($arrDados){
    $post      = $arrDados[0];
    $arrParams = $arrDados[1];
   
    $objRnProcesso        = new MdUtlAdmGrpFilaProcRN();
    $idTipoProcedimento   = array_key_exists('id_tipo_procedimento', $arrParams) ? $arrParams['id_tipo_procedimento'] : null;
    $idFilaAtiva          = array_key_exists('id_fila_ativa', $arrParams) ? $arrParams['id_fila_ativa'] : null;
    $idTpCtrlUtl          = array_key_exists('id_tipo_controle_utl', $arrParams) ? $arrParams['id_tipo_controle_utl'] : null;
    $strPalavras          = array_key_exists('palavras_pesquisa', $post) ? $post['palavras_pesquisa'] : null;

    if(!is_null($idTpCtrlUtl) && !is_null($idTipoProcedimento) && !is_null($idFilaAtiva) && !is_null($strPalavras)){
      $objMdUtlAdmGrpFilaDTO = new MdUtlAdmGrpFilaDTO();
      $objMdUtlAdmGrpFilaDTO->setStrNomeGrupoAtividade('%'.trim($strPalavras.'%'),InfraDTO::$OPER_LIKE);
      $objMdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmFila($idFilaAtiva);
      $objMdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrlUtl);
      $objMdUtlAdmGrpFilaDTO->retTodos();

      $idsGrupoFila = $objRnProcesso->getGruposFilaDesteProcesso($idTipoProcedimento);

      $addGruposFila = !is_null($idsGrupoFila) && is_array($idsGrupoFila) && count($idsGrupoFila) > 0;
      if($addGruposFila){
        $objMdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmGrpFila($idsGrupoFila, InfraDTO::$OPER_IN);
      }else{
        return null;
      }

      return $this->listar($objMdUtlAdmGrpFilaDTO);

    }

    return null;
  }
  




}
