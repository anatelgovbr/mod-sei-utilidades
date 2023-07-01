<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/12/2022 - criado por gustavos.colab
*
* Versão do Gerador de Código: 1.43.2
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmIntegHeaderRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdUtlAdmIntegracao(MdUtlAdmIntegHeaderDTO $objMdUtlAdmIntegHeaderDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegHeaderDTO->getNumIdMdUtlAdmIntegracao())){
      $objInfraException->adicionarValidacao('ID da Integração não informado.');
    }
  }

  private function validarStrAtributo(MdUtlAdmIntegHeaderDTO $objMdUtlAdmIntegHeaderDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegHeaderDTO->getStrAtributo())){
      $objInfraException->adicionarValidacao('Campo Atributo não informado.');
    }else{
      $objMdUtlAdmIntegHeaderDTO->setStrAtributo(trim($objMdUtlAdmIntegHeaderDTO->getStrAtributo()));

      if (strlen($objMdUtlAdmIntegHeaderDTO->getStrAtributo())>100){
        $objInfraException->adicionarValidacao('Campo Atributo possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrConteudo(MdUtlAdmIntegHeaderDTO $objMdUtlAdmIntegHeaderDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegHeaderDTO->getStrConteudo())){
      $objInfraException->adicionarValidacao('Campo Conteúdo não informado.');
    }else{
      $objMdUtlAdmIntegHeaderDTO->setStrConteudo(trim($objMdUtlAdmIntegHeaderDTO->getStrConteudo()));

      if (strlen($objMdUtlAdmIntegHeaderDTO->getStrConteudo())>200){
        $objInfraException->adicionarValidacao('Campo Conteúdo possui tamanho superior a 200 caracteres.');
      }
    }
  }

  private function validarStrSinDadoConfidencial(MdUtlAdmIntegHeaderDTO $objMdUtlAdmIntegHeaderDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegHeaderDTO->getStrSinDadoConfidencial())){
      $objInfraException->adicionarValidacao('Sinalizador de Dado Restrito não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdUtlAdmIntegHeaderDTO->getStrSinDadoConfidencial())){
        $objInfraException->adicionarValidacao('Sinalizador de Dado Restrito inválido.');
      }
    }
  }

  protected function cadastrarControlado(MdUtlAdmIntegHeaderDTO $objMdUtlAdmIntegHeaderDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_header_cadastrar', __METHOD__, $objMdUtlAdmIntegHeaderDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdUtlAdmIntegracao($objMdUtlAdmIntegHeaderDTO, $objInfraException);
      $this->validarStrAtributo($objMdUtlAdmIntegHeaderDTO, $objInfraException);
      $this->validarStrConteudo($objMdUtlAdmIntegHeaderDTO, $objInfraException);
      $this->validarStrSinDadoConfidencial($objMdUtlAdmIntegHeaderDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegHeaderBD = new MdUtlAdmIntegHeaderBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmIntegHeaderBD->cadastrar($objMdUtlAdmIntegHeaderDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Header.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmIntegHeaderDTO $objMdUtlAdmIntegHeaderDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_header_alterar', __METHOD__, $objMdUtlAdmIntegHeaderDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmIntegHeaderDTO->isSetNumIdMdUtlAdmIntegracao()){
        $this->validarNumIdMdUtlAdmIntegracao($objMdUtlAdmIntegHeaderDTO, $objInfraException);
      }
      if ($objMdUtlAdmIntegHeaderDTO->isSetStrAtributo()){
        $this->validarStrAtributo($objMdUtlAdmIntegHeaderDTO, $objInfraException);
      }
      if ($objMdUtlAdmIntegHeaderDTO->isSetStrConteudo()){
        $this->validarStrConteudo($objMdUtlAdmIntegHeaderDTO, $objInfraException);
      }
      if ($objMdUtlAdmIntegHeaderDTO->isSetStrSinDadoConfidencial()){
        $this->validarStrSinDadoConfidencial($objMdUtlAdmIntegHeaderDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegHeaderBD = new MdUtlAdmIntegHeaderBD($this->getObjInfraIBanco());
      $objMdUtlAdmIntegHeaderBD->alterar($objMdUtlAdmIntegHeaderDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Header.',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmIntegHeaderDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_header_excluir', __METHOD__, $arrObjMdUtlAdmIntegHeaderDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegHeaderBD = new MdUtlAdmIntegHeaderBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmIntegHeaderDTO);$i++){
        $objMdUtlAdmIntegHeaderBD->excluir($arrObjMdUtlAdmIntegHeaderDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Header.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmIntegHeaderDTO $objMdUtlAdmIntegHeaderDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_header_consultar', __METHOD__, $objMdUtlAdmIntegHeaderDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegHeaderBD = new MdUtlAdmIntegHeaderBD($this->getObjInfraIBanco());

      /** @var MdUtlAdmIntegHeaderDTO $ret */
      $ret = $objMdUtlAdmIntegHeaderBD->consultar($objMdUtlAdmIntegHeaderDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Header.',$e);
    }
  }

  protected function listarConectado(MdUtlAdmIntegHeaderDTO $objMdUtlAdmIntegHeaderDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_header_listar', __METHOD__, $objMdUtlAdmIntegHeaderDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegHeaderBD = new MdUtlAdmIntegHeaderBD($this->getObjInfraIBanco());

      /** @var MdUtlAdmIntegHeaderDTO[] $ret */
      $ret = $objMdUtlAdmIntegHeaderBD->listar($objMdUtlAdmIntegHeaderDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Headers.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmIntegHeaderDTO $objMdUtlAdmIntegHeaderDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_header_listar', __METHOD__, $objMdUtlAdmIntegHeaderDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegHeaderBD = new MdUtlAdmIntegHeaderBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmIntegHeaderBD->contar($objMdUtlAdmIntegHeaderDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Headers.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdUtlAdmIntegHeaderDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_header_desativar', __METHOD__, $arrObjMdUtlAdmIntegHeaderDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegHeaderBD = new MdUtlAdmIntegHeaderBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmIntegHeaderDTO);$i++){
        $objMdUtlAdmIntegHeaderBD->desativar($arrObjMdUtlAdmIntegHeaderDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Header.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmIntegHeaderDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_header_reativar', __METHOD__, $arrObjMdUtlAdmIntegHeaderDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegHeaderBD = new MdUtlAdmIntegHeaderBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmIntegHeaderDTO);$i++){
        $objMdUtlAdmIntegHeaderBD->reativar($arrObjMdUtlAdmIntegHeaderDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Header.',$e);
    }
  }

  protected function bloquearControlado(MdUtlAdmIntegHeaderDTO $objMdUtlAdmIntegHeaderDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integ_header_consultar', __METHOD__, $objMdUtlAdmIntegHeaderDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegHeaderBD = new MdUtlAdmIntegHeaderBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmIntegHeaderBD->bloquear($objMdUtlAdmIntegHeaderDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Header.',$e);
    }
  }

 */

  protected function montarArrHeadersConectado( $idMdUtlAdmIntegracao ){

    $objMdUtlAdmIntegHeaderDTO = new MdUtlAdmIntegHeaderDTO();
    $objMdUtlAdmIntegHeaderDTO->setNumIdMdUtlAdmIntegracao( $idMdUtlAdmIntegracao );
    $objMdUtlAdmIntegHeaderDTO->setOrdNumIdMdUtlAdmIntegHeader( InfraDTO::$TIPO_ORDENACAO_DESC );
    $objMdUtlAdmIntegHeaderDTO->retTodos();
    $arrListaHeader    = $this->listar( $objMdUtlAdmIntegHeaderDTO );
    $arrItensHeader    = [];
    $arrIdsItensHeader = [];

    if( !empty( $arrListaHeader ) ){
      foreach ( $arrListaHeader as $k => $v ) {
        $arrIdsItensHeader[] = $v->getNumIdMdUtlAdmIntegHeader();

        $itemHeader = [
          $v->getNumIdMdUtlAdmIntegHeader(),
          $v->getStrAtributo(),
	        $v->getStrSinDadoConfidencial() == 'S' ? MdUtlAdmIntegracaoRN::$INFO_RESTRITO : $v->getStrConteudo(),
          $v->getStrSinDadoConfidencial() == 'S' ? 'Sim' : 'Não',
	        $v->getStrSinDadoConfidencial() == 'S'
		        ? MdUtlAdmIntegracaoINT::gerenciaDadosRestritos( $v->getStrConteudo() , 'D' )
		        : $v->getStrConteudo()
        ];

        $arrItensHeader[] = $itemHeader;
      }
    }

    return [
      'itensTabela'       => $arrItensHeader,
      'qtdHeader'         => $arrItensHeader ? count( $arrItensHeader ) : 0,
      'strIdsItensHeader' => implode( ',' , $arrIdsItensHeader )
    ];
  }
}
