<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/12/2022 - criado por gustavos.colab
*
* Versão do Gerador de Código: 1.43.2
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmIntegParamRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdUtlAdmIntegracao(MdUtlAdmIntegParamDTO $objMdUtlAdmIntegParamDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegParamDTO->getNumIdMdUtlAdmIntegracao())){
      $objInfraException->adicionarValidacao('Integração não informada.');
    }
  }

  private function validarStrNome(MdUtlAdmIntegParamDTO $objMdUtlAdmIntegParamDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegParamDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome do Parâmetro não informado.');
    }else{
      $objMdUtlAdmIntegParamDTO->setStrNome(trim($objMdUtlAdmIntegParamDTO->getStrNome()));

      if (strlen($objMdUtlAdmIntegParamDTO->getStrNome()) > 100){
        $objInfraException->adicionarValidacao('Nome do Parâmetro possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrTpParametro(MdUtlAdmIntegParamDTO $objMdUtlAdmIntegParamDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegParamDTO->getStrTpParametro())){
      $objInfraException->adicionarValidacao('Tipo Parâmetro não informado.');
    }else{
      $objMdUtlAdmIntegParamDTO->setStrTpParametro(trim($objMdUtlAdmIntegParamDTO->getStrTpParametro()));

      if (strlen($objMdUtlAdmIntegParamDTO->getStrTpParametro()) > 1){
        $objInfraException->adicionarValidacao('Tipo Parâmetro possui tamanho superior a 1 caracteres.');
      }
    }
  }

  private function validarStrNomeCampo(MdUtlAdmIntegParamDTO $objMdUtlAdmIntegParamDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegParamDTO->getStrNomeCampo())){
      $objMdUtlAdmIntegParamDTO->setStrNomeCampo(null);
    }else{
      $objMdUtlAdmIntegParamDTO->setStrNomeCampo(trim($objMdUtlAdmIntegParamDTO->getStrNomeCampo()));

      if (strlen($objMdUtlAdmIntegParamDTO->getStrNomeCampo()) > 500){
        $objInfraException->adicionarValidacao('Nome do Campo possui tamanho superior a 500 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdUtlAdmIntegParamDTO $objMdUtlAdmIntegParamDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_param_cadastrar', __METHOD__, $objMdUtlAdmIntegParamDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdUtlAdmIntegracao($objMdUtlAdmIntegParamDTO, $objInfraException);
      $this->validarStrNome($objMdUtlAdmIntegParamDTO, $objInfraException);
      $this->validarStrTpParametro($objMdUtlAdmIntegParamDTO, $objInfraException);
      $this->validarStrNomeCampo($objMdUtlAdmIntegParamDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegParamBD = new MdUtlAdmIntegParamBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmIntegParamBD->cadastrar($objMdUtlAdmIntegParamDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmIntegParamDTO $objMdUtlAdmIntegParamDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_param_alterar', __METHOD__, $objMdUtlAdmIntegParamDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmIntegParamDTO->isSetNumIdMdUtlAdmIntegracao()){
        $this->validarNumIdMdUtlAdmIntegracao($objMdUtlAdmIntegParamDTO, $objInfraException);
      }
      if ($objMdUtlAdmIntegParamDTO->isSetStrNome()){
        $this->validarStrNome($objMdUtlAdmIntegParamDTO, $objInfraException);
      }
      if ($objMdUtlAdmIntegParamDTO->isSetStrTpParametro()){
        $this->validarStrTpParametro($objMdUtlAdmIntegParamDTO, $objInfraException);
      }
      if ($objMdUtlAdmIntegParamDTO->isSetStrNomeCampo()){
        $this->validarStrNomeCampo($objMdUtlAdmIntegParamDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegParamBD = new MdUtlAdmIntegParamBD($this->getObjInfraIBanco());
      $objMdUtlAdmIntegParamBD->alterar($objMdUtlAdmIntegParamDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmIntegParamDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_param_excluir', __METHOD__, $arrObjMdUtlAdmIntegParamDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegParamBD = new MdUtlAdmIntegParamBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmIntegParamDTO);$i++){
        $objMdUtlAdmIntegParamBD->excluir($arrObjMdUtlAdmIntegParamDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmIntegParamDTO $objMdUtlAdmIntegParamDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_param_consultar', __METHOD__, $objMdUtlAdmIntegParamDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegParamBD = new MdUtlAdmIntegParamBD($this->getObjInfraIBanco());

      /** @var MdUtlAdmIntegParamDTO $ret */
      $ret = $objMdUtlAdmIntegParamBD->consultar($objMdUtlAdmIntegParamDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdUtlAdmIntegParamDTO $objMdUtlAdmIntegParamDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_param_listar', __METHOD__, $objMdUtlAdmIntegParamDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegParamBD = new MdUtlAdmIntegParamBD($this->getObjInfraIBanco());

      /** @var MdUtlAdmIntegParamDTO[] $ret */
      $ret = $objMdUtlAdmIntegParamBD->listar($objMdUtlAdmIntegParamDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdUtlAdmIntegParamDTO $objMdUtlAdmIntegParamDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_param_listar', __METHOD__, $objMdUtlAdmIntegParamDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegParamBD = new MdUtlAdmIntegParamBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmIntegParamBD->contar($objMdUtlAdmIntegParamDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdUtlAdmIntegParamDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_param_desativar', __METHOD__, $arrObjMdUtlAdmIntegParamDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegParamBD = new MdUtlAdmIntegParamBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmIntegParamDTO);$i++){
        $objMdUtlAdmIntegParamBD->desativar($arrObjMdUtlAdmIntegParamDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmIntegParamDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_param_reativar', __METHOD__, $arrObjMdUtlAdmIntegParamDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegParamBD = new MdUtlAdmIntegParamBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmIntegParamDTO);$i++){
        $objMdUtlAdmIntegParamBD->reativar($arrObjMdUtlAdmIntegParamDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function bloquearControlado(MdUtlAdmIntegParamDTO $objMdUtlAdmIntegParamDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_param_consultar', __METHOD__, $objMdUtlAdmIntegParamDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegParamBD = new MdUtlAdmIntegParamBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmIntegParamBD->bloquear($objMdUtlAdmIntegParamDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }

 */

  public function buscaDadosEntradaSaida( $arrParams ){
    $idMdUtlAdmIntegracao = array_key_exists(0,$arrParams) ? $arrParams[0] : null;
    $isJson               = array_key_exists(1,$arrParams) ? $arrParams[1] : false;

    try {
      if( is_null( $idMdUtlAdmIntegracao ) ) throw new Exception("Parâmetro IdMdUtlAdmIntegração vazia.");

      $objMdUtlAdmIntegParamDTO = new MdUtlAdmIntegParamDTO();

      $objMdUtlAdmIntegParamDTO->setNumIdMdUtlAdmIntegracao( $idMdUtlAdmIntegracao );
      $objMdUtlAdmIntegParamDTO->retTodos();

      if ( $isJson ) {
        $strJson = '[';
        $arrDados = $this->listar( $objMdUtlAdmIntegParamDTO );
        $numCtrlLoop = 1;
        foreach ( $arrDados as $k => $v ) {
          $nome      = $v->getStrNome() ?: '';
          $tpParam   = $v->getStrTpParametro() ?: '';
          $nomeCampo = $v->getStrNomeCampo() ?: '';

          $strJson .= '{"nome": "'.$nome.'","TpParametro": "'.$tpParam.'","nomeCampo": "'.$nomeCampo.'"}';
          if( $numCtrlLoop < count( $arrDados ) ) $strJson .= ',';
          $numCtrlLoop++;
        }

        $strJson .= ']';
        return $strJson;
      } else {
        return $this->listar( $objMdUtlAdmIntegParamDTO );
      }
    }catch ( Exception $e ){
      throw new InfraException( $e->getMessage() , $e );
    }
  }
}
