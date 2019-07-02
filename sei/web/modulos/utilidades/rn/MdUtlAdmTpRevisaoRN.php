<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/08/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmTpRevisaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdUtlAdmTpCtrlDesemp(MdUtlAdmTpRevisaoDTO $objMdUtlAdmTpRevisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmTpRevisaoDTO->getNumIdMdUtlAdmTpCtrlDesemp())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarDuplicacao($objMdUtlAdmDTO, $objInfraException){
    $id = $objMdUtlAdmDTO->getNumIdMdUtlAdmTpRevisao();
    $objMdUtlAdmDTO2 = new MdUtlAdmTpRevisaoDTO();
    $objMdUtlAdmDTO2->setStrNome($objMdUtlAdmDTO->getStrNome());
    $objMdUtlAdmDTO2->setBolExclusaoLogica(false);

    if(!is_null($id)){
      $objMdUtlAdmDTO2->setNumIdMdUtlAdmTpRevisao($id, InfraDTO::$OPER_DIFERENTE);
    }

    $idTpCtrl = $_POST['hdnIdTpCtrlUtl'];
    $objMdUtlAdmDTO2->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);

    $existeRegistroDupl = $this->contar($objMdUtlAdmDTO2) > 0;

    if($existeRegistroDupl){
      $objInfraException->lancarValidacao('Já existe um Tipo de Revisão cadastrado com este nome.');
    }
  }

  private function validarStrNome(MdUtlAdmTpRevisaoDTO $objMdUtlAdmTpRevisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmTpRevisaoDTO->getStrNome())){
      $objInfraException->adicionarValidacao(' não informad.');
    }else{
      $objMdUtlAdmTpRevisaoDTO->setStrNome(trim($objMdUtlAdmTpRevisaoDTO->getStrNome()));

      if (strlen($objMdUtlAdmTpRevisaoDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrDescricao(MdUtlAdmTpRevisaoDTO $objMdUtlAdmTpRevisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmTpRevisaoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao(' não informad.');
    }else{
      $objMdUtlAdmTpRevisaoDTO->setStrDescricao(trim($objMdUtlAdmTpRevisaoDTO->getStrDescricao()));

      if (strlen($objMdUtlAdmTpRevisaoDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinJustificativa(MdUtlAdmTpRevisaoDTO $objMdUtlAdmTpRevisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmTpRevisaoDTO->getStrSinJustificativa())){
      $objInfraException->adicionarValidacao('Sinalizador de  não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdUtlAdmTpRevisaoDTO->getStrSinJustificativa())){
        $objInfraException->adicionarValidacao('Sinalizador de  inválid.');
      }
    }
  }

  private function validarStrSinAtivo(MdUtlAdmTpRevisaoDTO $objMdUtlAdmTpRevisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmTpRevisaoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdUtlAdmTpRevisaoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(MdUtlAdmTpRevisaoDTO $objMdUtlAdmTpRevisaoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_revisao_cadastrar', __METHOD__, $objMdUtlAdmTpRevisaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdUtlAdmTpCtrlDesemp($objMdUtlAdmTpRevisaoDTO, $objInfraException);
      $this->validarStrNome($objMdUtlAdmTpRevisaoDTO, $objInfraException);
      $this->validarStrDescricao($objMdUtlAdmTpRevisaoDTO, $objInfraException);
      $this->validarStrSinJustificativa($objMdUtlAdmTpRevisaoDTO, $objInfraException);
      $this->validarStrSinAtivo($objMdUtlAdmTpRevisaoDTO, $objInfraException);
      $this->validarDuplicacao($objMdUtlAdmTpRevisaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmTpRevisaoBD = new MdUtlAdmTpRevisaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpRevisaoBD->cadastrar($objMdUtlAdmTpRevisaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Revisão.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmTpRevisaoDTO $objMdUtlAdmTpRevisaoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_revisao_alterar', __METHOD__, $objMdUtlAdmTpRevisaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmTpRevisaoDTO->isSetNumIdMdUtlAdmTpCtrlDesemp()){
        $this->validarNumIdMdUtlAdmTpCtrlDesemp($objMdUtlAdmTpRevisaoDTO, $objInfraException);
      }
      if ($objMdUtlAdmTpRevisaoDTO->isSetStrNome()){
        $this->validarStrNome($objMdUtlAdmTpRevisaoDTO, $objInfraException);
      }
      if ($objMdUtlAdmTpRevisaoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objMdUtlAdmTpRevisaoDTO, $objInfraException);
      }
      if ($objMdUtlAdmTpRevisaoDTO->isSetStrSinJustificativa()){
        $this->validarStrSinJustificativa($objMdUtlAdmTpRevisaoDTO, $objInfraException);
      }
      if ($objMdUtlAdmTpRevisaoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objMdUtlAdmTpRevisaoDTO, $objInfraException);
      }

      $this->validarDuplicacao($objMdUtlAdmTpRevisaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmTpRevisaoBD = new MdUtlAdmTpRevisaoBD($this->getObjInfraIBanco());
      $objMdUtlAdmTpRevisaoBD->alterar($objMdUtlAdmTpRevisaoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Revisão.',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmTpRevisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_revisao_excluir', __METHOD__, $arrObjMdUtlAdmTpRevisaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpRevisaoBD = new MdUtlAdmTpRevisaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmTpRevisaoDTO);$i++){
        $objMdUtlAdmTpRevisaoBD->excluir($arrObjMdUtlAdmTpRevisaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Revisão.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmTpRevisaoDTO $objMdUtlAdmTpRevisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_revisao_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpRevisaoBD = new MdUtlAdmTpRevisaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpRevisaoBD->consultar($objMdUtlAdmTpRevisaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Revisão.',$e);
    }
  }

  protected function listarConectado(MdUtlAdmTpRevisaoDTO $objMdUtlAdmTpRevisaoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_revisao_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpRevisaoBD = new MdUtlAdmTpRevisaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpRevisaoBD->listar($objMdUtlAdmTpRevisaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Revisões.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmTpRevisaoDTO $objMdUtlAdmTpRevisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_revisao_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpRevisaoBD = new MdUtlAdmTpRevisaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpRevisaoBD->contar($objMdUtlAdmTpRevisaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Revisões.',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAdmTpRevisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_revisao_desativar', __METHOD__, $arrObjMdUtlAdmTpRevisaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpRevisaoBD = new MdUtlAdmTpRevisaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmTpRevisaoDTO);$i++){
        $objMdUtlAdmTpRevisaoBD->desativar($arrObjMdUtlAdmTpRevisaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Revisão.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmTpRevisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_revisao_reativar', __METHOD__, $arrObjMdUtlAdmTpRevisaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpRevisaoBD = new MdUtlAdmTpRevisaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmTpRevisaoDTO);$i++){
        $objMdUtlAdmTpRevisaoBD->reativar($arrObjMdUtlAdmTpRevisaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Revisão.',$e);
    }
  }
  
}
