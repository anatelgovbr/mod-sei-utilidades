<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/08/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmTpJustRevisaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdUtlAdmTpCtrlDesemp(MdUtlAdmTpJustRevisaoDTO $objMdUtlAdmTpJustRevisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmTpJustRevisaoDTO->getNumIdMdUtlAdmTpCtrlDesemp())){
      $objInfraException->adicionarValidacao('Id de Controle de Desempenho.');
    }
  }

  private function validarStrNome(MdUtlAdmTpJustRevisaoDTO $objMdUtlAdmTpJustRevisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmTpJustRevisaoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nom não informado');
    }else{
      $objMdUtlAdmTpJustRevisaoDTO->setStrNome(trim($objMdUtlAdmTpJustRevisaoDTO->getStrNome()));

      if (strlen($objMdUtlAdmTpJustRevisaoDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrDescricao(MdUtlAdmTpJustRevisaoDTO $objMdUtlAdmTpJustRevisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmTpJustRevisaoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objMdUtlAdmTpJustRevisaoDTO->setStrDescricao(trim($objMdUtlAdmTpJustRevisaoDTO->getStrDescricao()));

      if (strlen($objMdUtlAdmTpJustRevisaoDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(MdUtlAdmTpJustRevisaoDTO $objMdUtlAdmTpJustRevisaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmTpJustRevisaoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdUtlAdmTpJustRevisaoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }


  private function validarDuplicacao($objMdUtlAdmDTO, $objInfraException){
    $id       = $objMdUtlAdmDTO->getNumIdMdUtlAdmTpJustRevisao();
    
    $objMdUtlAdmDTO2 = new MdUtlAdmTpJustRevisaoDTO();
    $objMdUtlAdmDTO2->setStrNome($objMdUtlAdmDTO->getStrNome());

    $idTpCtrl = $_POST['hdnIdTpCtrlUtl'];
    $objMdUtlAdmDTO2->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
    $objMdUtlAdmDTO2->setBolExclusaoLogica(false);
    
    if(!is_null($id)){
      $objMdUtlAdmDTO2->setNumIdMdUtlAdmTpJustRevisao($id, InfraDTO::$OPER_DIFERENTE);
    }

    $existeRegistroDupl = $this->contar($objMdUtlAdmDTO2) > 0;

    if($existeRegistroDupl){
      $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_19, array('Tipo de Justificativa'));
      $objInfraException->lancarValidacao($msg);
    }
  }

  protected function cadastrarControlado(MdUtlAdmTpJustRevisaoDTO $objMdUtlAdmTpJustRevisaoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_just_revisao_cadastrar', __METHOD__, $objMdUtlAdmTpJustRevisaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdUtlAdmTpCtrlDesemp($objMdUtlAdmTpJustRevisaoDTO, $objInfraException);
      $this->validarStrNome($objMdUtlAdmTpJustRevisaoDTO, $objInfraException);
      $this->validarStrDescricao($objMdUtlAdmTpJustRevisaoDTO, $objInfraException);
      $this->validarStrSinAtivo($objMdUtlAdmTpJustRevisaoDTO, $objInfraException);
      $this->validarDuplicacao($objMdUtlAdmTpJustRevisaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmTpJustRevisaoBD = new MdUtlAdmTpJustRevisaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpJustRevisaoBD->cadastrar($objMdUtlAdmTpJustRevisaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando as.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmTpJustRevisaoDTO $objMdUtlAdmTpJustRevisaoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_just_revisao_alterar', __METHOD__, $objMdUtlAdmTpJustRevisaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmTpJustRevisaoDTO->isSetNumIdMdUtlAdmTpCtrlDesemp()){
        $this->validarNumIdMdUtlAdmTpCtrlDesemp($objMdUtlAdmTpJustRevisaoDTO, $objInfraException);
      }
      if ($objMdUtlAdmTpJustRevisaoDTO->isSetStrNome()){
        $this->validarStrNome($objMdUtlAdmTpJustRevisaoDTO, $objInfraException);
      }
      if ($objMdUtlAdmTpJustRevisaoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objMdUtlAdmTpJustRevisaoDTO, $objInfraException);
      }
      if ($objMdUtlAdmTpJustRevisaoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objMdUtlAdmTpJustRevisaoDTO, $objInfraException);
      }

      $this->validarDuplicacao($objMdUtlAdmTpJustRevisaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmTpJustRevisaoBD = new MdUtlAdmTpJustRevisaoBD($this->getObjInfraIBanco());
      $objMdUtlAdmTpJustRevisaoBD->alterar($objMdUtlAdmTpJustRevisaoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando as.',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmTpJustRevisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_just_revisao_excluir', __METHOD__,$arrObjMdUtlAdmTpJustRevisaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpJustRevisaoBD = new MdUtlAdmTpJustRevisaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmTpJustRevisaoDTO);$i++){
        $objMdUtlAdmTpJustRevisaoBD->excluir($arrObjMdUtlAdmTpJustRevisaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo as.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmTpJustRevisaoDTO $objMdUtlAdmTpJustRevisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_just_revisao_consultar');

      $objMdUtlAdmTpJustRevisaoBD = new MdUtlAdmTpJustRevisaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpJustRevisaoBD->consultar($objMdUtlAdmTpJustRevisaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando as.',$e);
    }
  }

  protected function listarConectado(MdUtlAdmTpJustRevisaoDTO $objMdUtlAdmTpJustRevisaoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_just_revisao_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpJustRevisaoBD = new MdUtlAdmTpJustRevisaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpJustRevisaoBD->listar($objMdUtlAdmTpJustRevisaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando a.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmTpJustRevisaoDTO $objMdUtlAdmTpJustRevisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_just_revisao_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpJustRevisaoBD = new MdUtlAdmTpJustRevisaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpJustRevisaoBD->contar($objMdUtlAdmTpJustRevisaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando a.',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAdmTpJustRevisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_just_revisao_desativar', __METHOD__, $arrObjMdUtlAdmTpJustRevisaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpJustRevisaoBD = new MdUtlAdmTpJustRevisaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmTpJustRevisaoDTO);$i++){
        $objMdUtlAdmTpJustRevisaoBD->desativar($arrObjMdUtlAdmTpJustRevisaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando as.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmTpJustRevisaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_just_revisao_reativar', __METHOD__, $arrObjMdUtlAdmTpJustRevisaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpJustRevisaoBD = new MdUtlAdmTpJustRevisaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmTpJustRevisaoDTO);$i++){
        $objMdUtlAdmTpJustRevisaoBD->reativar($arrObjMdUtlAdmTpJustRevisaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando as.',$e);
    }
  }
  
}
