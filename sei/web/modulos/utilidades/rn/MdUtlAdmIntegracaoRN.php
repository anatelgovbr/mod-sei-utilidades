<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2022 - criado por gustavos.colab
*
* Versão do Gerador de Código: 1.43.1
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmIntegracaoRN extends InfraRN {

	/*
  public static $LISTAR_CHEFIA          = '1';
  public static $STR_LISTAR_CHEFIA      = 'Recursos Humanos: Listar Chefia Imediata';
  public static $CONSULTAR_CHEFIA       = '2';
  public static $STR_CONSULTAR_CHEFIA   = 'Recursos Humanos: Consultar Chefia Imediata';
  public static $LISTAR_AUSENCIA        = '3';
	public static $STR_LISTAR_AUSENCIA    = 'Recursos Humanos: Listar Ausências de Servidores';
	public static $CONSULTAR_AUSENCIA     = '4';
	public static $STR_CONSULTAR_AUSENCIA = 'Recursos Humanos: Consultar Ausência de Servidor';
	*/
	public static $CHEFIA          = '1';
	public static $STR_CHEFIA      = 'Recursos Humanos: Chefia Imediata';
	public static $AUSENCIA        = '2';
	public static $STR_AUSENCIA    = 'Recursos Humanos: Ausências de Servidores';

	public static $TP_INTEGRACAO_SEM_AUTENTICACAO = 'SI';
	public static $TP_INTEGRACAO_REST             = 'RE';
	public static $TP_INTEGRACAO_SOAP             = 'SO';

  public static $AUT_VAZIA            = '1';
  public static $STR_AUT_VAZIA        = 'Sem Autenticação';
  public static $AUT_HEADER_TOKEN     = '2';
  public static $STR_AUT_HEADER_TOKEN = 'Header Authentication by Token';
  public static $AUT_BODY_TOKEN       = '3';
  public static $STR_AUT_BODY_TOKEN   = 'Body Authentication by Token';

  public static $FORMATO_JSON     = '1';
	public static $STR_FORMATO_JSON = 'JSON';
	public static $FORMATO_XML      = '2';
	public static $STR_FORMATO_XML  = 'XML';

	public static $REQUISICAO_POST     = '1';
	public static $STR_REQUISICAO_POST = 'POST';
	public static $REQUISICAO_GET      = '2';
	public static $STR_REQUISICAO_GET  = 'GET';

	public static $ARR_IDENTIFICADORES = [
			'dataInicial'    => 'dataInicial',
			'dataFinal'      => 'dataFinal',
			'meioExpediente' => 'meioExpediente',
			'loginUsuario'   => 'loginUsuario',
			'tipoEmpregado'  => 'tipoEmpregado',
			'token'          => 'token',
			'conteudoAutenticacao' => 'conteudoAutenticacao'
	];

	public static $INFO_RESTRITO = '*****';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegracaoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objMdUtlAdmIntegracaoDTO->setStrNome(trim($objMdUtlAdmIntegracaoDTO->getStrNome()));

      if (strlen($objMdUtlAdmIntegracaoDTO->getStrNome())>100){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarNumFuncionalidade(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegracaoDTO->getNumFuncionalidade())){
      $objInfraException->adicionarValidacao('Funcionalidade não informada.');
    }
  }

  private function validarStrTipoIntegracao(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegracaoDTO->getStrTipoIntegracao())){
      $objMdUtlAdmIntegracaoDTO->setStrTipoIntegracao(null);
    }else{
      $objMdUtlAdmIntegracaoDTO->setStrTipoIntegracao(trim($objMdUtlAdmIntegracaoDTO->getStrTipoIntegracao()));

      if (strlen($objMdUtlAdmIntegracaoDTO->getStrTipoIntegracao()) > 2){
        $objInfraException->adicionarValidacao('Possui tamanho superior a 2 caracteres.');
      }
    }
  }

  private function validarNumMetodoAutenticacao(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegracaoDTO->getNumMetodoAutenticacao())){
      $objMdUtlAdmIntegracaoDTO->setNumMetodoAutenticacao(null);
    }
  }

  private function validarNumMetodoRequisicao(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegracaoDTO->getNumMetodoRequisicao())){
      $objMdUtlAdmIntegracaoDTO->setNumMetodoRequisicao(null);
    }
  }

  private function validarNumFormatoResposta(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegracaoDTO->getNumFormatoResposta())){
      $objMdUtlAdmIntegracaoDTO->setNumFormatoResposta(null);
    }
  }

  private function validarStrVersaoSoap(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegracaoDTO->getStrVersaoSoap())){
      $objMdUtlAdmIntegracaoDTO->setStrVersaoSoap(null);
    }else{
      $objMdUtlAdmIntegracaoDTO->setStrVersaoSoap(trim($objMdUtlAdmIntegracaoDTO->getStrVersaoSoap()));

      if (strlen($objMdUtlAdmIntegracaoDTO->getStrVersaoSoap()) > 5){
        $objInfraException->adicionarValidacao('Versão SOAP possui tamanho superior a 5 caracteres.');
      }
    }
  }

  private function validarStrTokenAutenticacao(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegracaoDTO->getStrTokenAutenticacao())){
      $objMdUtlAdmIntegracaoDTO->setStrTokenAutenticacao(null);
    }else{
      $objMdUtlAdmIntegracaoDTO->setStrTokenAutenticacao(trim($objMdUtlAdmIntegracaoDTO->getStrTokenAutenticacao()));

      if (strlen($objMdUtlAdmIntegracaoDTO->getStrTokenAutenticacao()) > 76){
        $objInfraException->adicionarValidacao('Token possui tamanho superior a 76 caracteres.');
      }
    }
  }

  private function validarStrUrlWsdl(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegracaoDTO->getStrUrlWsdl())){
      $objInfraException->adicionarValidacao('Endereço da WebService não informada.');
    }else{
      $objMdUtlAdmIntegracaoDTO->setStrUrlWsdl(trim($objMdUtlAdmIntegracaoDTO->getStrUrlWsdl()));

      if (strlen($objMdUtlAdmIntegracaoDTO->getStrUrlWsdl()) > 250){
        $objInfraException->adicionarValidacao('Endereço da WebService possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrOperacaoWsdl(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegracaoDTO->getStrOperacaoWsdl())){
      $objInfraException->adicionarValidacao('Operação não informada.');
    }else{
      $objMdUtlAdmIntegracaoDTO->setStrOperacaoWsdl(trim($objMdUtlAdmIntegracaoDTO->getStrOperacaoWsdl()));

      if (strlen($objMdUtlAdmIntegracaoDTO->getStrOperacaoWsdl()) > 250){
        $objInfraException->adicionarValidacao('Operação possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmIntegracaoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdUtlAdmIntegracaoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO) {
    try{
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integracao_cadastrar', __METHOD__, $objMdUtlAdmIntegracaoDTO);
      return $this->processaCadastrarAlterar( $objMdUtlAdmIntegracaoDTO );

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Integração.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO){
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integracao_alterar', __METHOD__, $objMdUtlAdmIntegracaoDTO);
      return $this->processaCadastrarAlterar( $objMdUtlAdmIntegracaoDTO );

    }catch(Exception $e){
      throw new InfraException('Erro alterando Integração.',$e);
    }
  }

  private function processaCadastrarAlterar( $objMdUtlAdmIntegracaoDTO ){
    $isNovaIntegracao = isset( $_POST['sbmCadastrarMdUtlAdmIntegracao'] );

    $objMdUtlAdmIntegParamRN = new MdUtlAdmIntegParamRN();

    //Regras de Negocio
    $objInfraException = new InfraException();

	  $this->validarStrTipoIntegracao($objMdUtlAdmIntegracaoDTO, $objInfraException);
	  $objInfraException->lancarValidacoes();

	  if( $objMdUtlAdmIntegracaoDTO->getStrTipoIntegracao() != 'SI' ){
		  $this->validarStrNome($objMdUtlAdmIntegracaoDTO, $objInfraException);
		  $this->validarNumFuncionalidade($objMdUtlAdmIntegracaoDTO, $objInfraException);
		  $this->validarStrUrlWsdl($objMdUtlAdmIntegracaoDTO, $objInfraException);
		  $this->validarStrOperacaoWsdl($objMdUtlAdmIntegracaoDTO, $objInfraException);

		  $objInfraException->lancarValidacoes();
	  }

    $objMdUtlAdmIntegracaoBD = new MdUtlAdmIntegracaoBD( $this->getObjInfraIBanco() );

    if ( $isNovaIntegracao ) {
      $retIntegracao = $objMdUtlAdmIntegracaoBD->cadastrar( $objMdUtlAdmIntegracaoDTO );
    } else {
      $objMdUtlAdmIntegracaoBD->alterar( $objMdUtlAdmIntegracaoDTO );
      $retIntegracao = $objMdUtlAdmIntegracaoDTO;
    }

    $tpFuncionalidade = $objMdUtlAdmIntegracaoDTO->getNumFuncionalidade();

    if( $retIntegracao && $objMdUtlAdmIntegracaoDTO->getStrTipoIntegracao() != 'SI' ){
      $idMdUtlAdmIntegracao = $objMdUtlAdmIntegracaoDTO->getNumIdMdUtlAdmIntegracao();
      $arrTbHeaders = PaginaSEI::getInstance()->getArrItensTabelaDinamica( $_POST['hdnTbHeaders'] );

      //cadastro dos dados da GRID - HEADERS
      if ( !empty( $arrTbHeaders ) ) {
        $objMdUtlAdmIntegHeaderRN  = new MdUtlAdmIntegHeaderRN();

        if ( !$isNovaIntegracao && !empty($_POST['hdnIdsItensHeader']) ) {
          $arrIdsOrigemIntegHeader = explode( ',' , $_POST['hdnIdsItensHeader'] );
        }

        foreach ( $arrTbHeaders as $k => $v ) {
          $objMdUtlAdmIntegHeaderDTO = new MdUtlAdmIntegHeaderDTO();
          $objMdUtlAdmIntegHeaderDTO->setNumIdMdUtlAdmIntegracao( $idMdUtlAdmIntegracao );
          $objMdUtlAdmIntegHeaderDTO->setStrAtributo( $v[1] );
          $objMdUtlAdmIntegHeaderDTO->setStrConteudo( $v[3] == 'Sim' ? MdUtlAdmIntegracaoINT::gerenciaDadosRestritos( $v[4] ) : $v[4] );
          $objMdUtlAdmIntegHeaderDTO->setStrSinDadoConfidencial( $v[3] == 'Sim' ? 'S' : 'N' );

          $arrId = explode( '_' , $v[0] );

          if ( $arrId[0] == 'novo' ) {
            $objMdUtlAdmIntegHeaderRN->cadastrar( $objMdUtlAdmIntegHeaderDTO );
          } else {
            $objMdUtlAdmIntegHeaderDTO->setNumIdMdUtlAdmIntegHeader( $v[0] );
            $objMdUtlAdmIntegHeaderRN->alterar( $objMdUtlAdmIntegHeaderDTO );

            // remove do array o Id Integracao Header atualizado
            $chave = array_search( $v[0] , $arrIdsOrigemIntegHeader );
            if ( $chave !== false ) unset( $arrIdsOrigemIntegHeader[$chave] );
          }
        }

        // se conter ids no array, eh porque foi removido em memoria da tela, com isso, deve ser removido da tabela
        if ( !empty( $arrIdsOrigemIntegHeader ) ){
          foreach ( $arrIdsOrigemIntegHeader as $idIntegHeader ) {
            $objMdUtlAdmIntegHeaderDTO = new MdUtlAdmIntegHeaderDTO();
            $objMdUtlAdmIntegHeaderDTO->setNumIdMdUtlAdmIntegHeader( $idIntegHeader );
            $objMdUtlAdmIntegHeaderDTO->retTodos();

            $arrObjMdUtlAdmIntegHeader[] = $objMdUtlAdmIntegHeaderRN->consultar( $objMdUtlAdmIntegHeaderDTO );
          }
          $objMdUtlAdmIntegHeaderRN->excluir( $arrObjMdUtlAdmIntegHeader );
        }
      }

      //cadastro dos dados de Entrada e Saida
      if ( !empty( $_POST['dadosEntrada'.$tpFuncionalidade] ) ) {
      	// exclusao dos registros Parametros de Entrada/Saida para cadastrar registros novos ou editados
	      $objMdUtlAdmIntegParamExcDTO = new MdUtlAdmIntegParamDTO();
	      $objMdUtlAdmIntegParamExcDTO->setNumIdMdUtlAdmIntegracao( $idMdUtlAdmIntegracao );
	      $objMdUtlAdmIntegParamExcDTO->retNumIdMdUtlAdmIntegParam();
	      $arrObjsIntegParametrosExcluir = $objMdUtlAdmIntegParamRN->listar( $objMdUtlAdmIntegParamExcDTO );
	      $objMdUtlAdmIntegParamRN->excluir( $arrObjsIntegParametrosExcluir );

        foreach ( $_POST['dadosEntrada'.$tpFuncionalidade] as $k => $v ){
          $objMdUtlAdmIntegParamDTO = new MdUtlAdmIntegParamDTO();

          $objMdUtlAdmIntegParamDTO->setNumIdMdUtlAdmIntegracao( $idMdUtlAdmIntegracao );
          $objMdUtlAdmIntegParamDTO->setStrNome( $v );
          $objMdUtlAdmIntegParamDTO->setStrTpParametro( 'E' );
	        $objMdUtlAdmIntegParamDTO->setStrNomeCampo( $_POST['selDadosEntrada'.$tpFuncionalidade][$k] );
	        $objMdUtlAdmIntegParamDTO->setStrIdentificador( $_POST['identificadorEntrada'.$tpFuncionalidade][$k] );
	        $objMdUtlAdmIntegParamRN->cadastrar( $objMdUtlAdmIntegParamDTO );
        }

        foreach ( $_POST['dadosSaida'.$tpFuncionalidade] as $k => $v ){
          $objMdUtlAdmIntegParamDTO = new MdUtlAdmIntegParamDTO();
          $objMdUtlAdmIntegParamDTO->setNumIdMdUtlAdmIntegracao( $idMdUtlAdmIntegracao );
          $objMdUtlAdmIntegParamDTO->setStrNome( $v );
          $objMdUtlAdmIntegParamDTO->setStrTpParametro( 'S' );
	        $objMdUtlAdmIntegParamDTO->setStrNomeCampo( $_POST['selDadosSaida'.$tpFuncionalidade][$k] );
	        $objMdUtlAdmIntegParamDTO->setStrIdentificador( $_POST['identificadorSaida'.$tpFuncionalidade][$k] );

	        $objMdUtlAdmIntegParamRN->cadastrar( $objMdUtlAdmIntegParamDTO );
        }
      }
    }
    return $retIntegracao;
  }

  protected function excluirControlado($arrObjMdUtlAdmIntegracaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integracao_excluir', __METHOD__, $arrObjMdUtlAdmIntegracaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegracaoBD = new MdUtlAdmIntegracaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmIntegracaoDTO);$i++){
        $objMdUtlAdmIntegracaoBD->excluir($arrObjMdUtlAdmIntegracaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Integração.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integracao_consultar', __METHOD__, $objMdUtlAdmIntegracaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegracaoBD = new MdUtlAdmIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmIntegracaoBD->consultar($objMdUtlAdmIntegracaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Integração.',$e);
    }
  }

  protected function listarConectado(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO) {
    try {

      #SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integracao_listar', __METHOD__, $objMdUtlAdmIntegracaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegracaoBD = new MdUtlAdmIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmIntegracaoBD->listar($objMdUtlAdmIntegracaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Integrações.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integracao_listar', __METHOD__, $objMdUtlAdmIntegracaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegracaoBD = new MdUtlAdmIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmIntegracaoBD->contar($objMdUtlAdmIntegracaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Integrações.',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAdmIntegracaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integracao_desativar', __METHOD__, $arrObjMdUtlAdmIntegracaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegracaoBD = new MdUtlAdmIntegracaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmIntegracaoDTO);$i++){
        $objMdUtlAdmIntegracaoBD->desativar($arrObjMdUtlAdmIntegracaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Integração.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmIntegracaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integracao_reativar', __METHOD__, $arrObjMdUtlAdmIntegracaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegracaoBD = new MdUtlAdmIntegracaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmIntegracaoDTO);$i++){
        $objMdUtlAdmIntegracaoBD->reativar($arrObjMdUtlAdmIntegracaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Integração.',$e);
    }
  }

  protected function bloquearControlado(MdUtlAdmIntegracaoDTO $objMdUtlAdmIntegracaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_integracao_consultar', __METHOD__, $objMdUtlAdmIntegracaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmIntegracaoBD = new MdUtlAdmIntegracaoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmIntegracaoBD->bloquear($objMdUtlAdmIntegracaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Integração.',$e);
    }
  }

  /*
   * Consulta somente na Integracao
   * */
  protected function buscaIntegracaoPorFuncionalidadeConectado( $tpFuncionalidade ){

    $objMdUtlAdmIntegracaoDTO = new MdUtlAdmIntegracaoDTO();

    $objMdUtlAdmIntegracaoDTO->setNumFuncionalidade( $tpFuncionalidade );
    $objMdUtlAdmIntegracaoDTO->setStrSinAtivo( 'S' );

    $objMdUtlAdmIntegracaoDTO->retStrUrlWsdl();
    $objMdUtlAdmIntegracaoDTO->retStrOperacaoWsdl();
    $objMdUtlAdmIntegracaoDTO->retNumMetodoRequisicao();
    $objMdUtlAdmIntegracaoDTO->retStrTipoIntegracao();

    return $this->consultar( $objMdUtlAdmIntegracaoDTO );
  }

  protected function buscaFuncionalidadesCadastradasConectado(){
	  $objMdUtlAdmIntegracaoDTO = new MdUtlAdmIntegracaoDTO();

	  $objMdUtlAdmIntegracaoDTO->setStrSinAtivo('S');
	  $objMdUtlAdmIntegracaoDTO->retNumFuncionalidade();

	  return InfraArray::converterArrInfraDTO(
	  	$this->listar( $objMdUtlAdmIntegracaoDTO ),
		  'Funcionalidade'
	  );
  }

  /*
   * Retorna a cofiguracao da Integração + dados do header + dados de parametros de entrada/saida
   * por funcionalidade
   * */
  public function obterConfigIntegracaoPorFuncionalidadeControlado( $tpFuncionalidade = null ){
	  try {
		  if( is_null( $tpFuncionalidade ) )
			  throw new InfraException('O Tipo de Funcionalidade é parâmetro obrigatório para consulta da Integração.');

		  // obter dados da Integracao
		  $objMdUtlAdmIntegracaoDTO = new MdUtlAdmIntegracaoDTO();
		  $objMdUtlAdmIntegracaoDTO->setNumFuncionalidade( $tpFuncionalidade );
		  $objMdUtlAdmIntegracaoDTO->setStrSinAtivo('S');
		  $objMdUtlAdmIntegracaoDTO->retTodos();

		  $objMdUtlAdmIntegracaoDTO = $this->consultar( $objMdUtlAdmIntegracaoDTO );

			if ( empty($objMdUtlAdmIntegracaoDTO) ) return [];

		  // obter dados do Header da Integracao
		  $objMdUtlAdmIntegHeaderDTO = new MdUtlAdmIntegHeaderDTO();
		  $objMdUtlAdmIntegHeaderDTO->setNumIdMdUtlAdmIntegracao( $objMdUtlAdmIntegracaoDTO->getNumIdMdUtlAdmIntegracao() );
		  $objMdUtlAdmIntegHeaderDTO->retTodos();

		  $objMdUtlAdmIntegHeaderDTO = ( new MdUtlAdmIntegHeaderRN() )->listar( $objMdUtlAdmIntegHeaderDTO );

		  // obter dados de entrada/saida de parametros da Integracao
		  $objMdUtlAdmIntegParamDTO = new MdUtlAdmIntegParamDTO();
		  $objMdUtlAdmIntegParamDTO->setNumIdMdUtlAdmIntegracao( $objMdUtlAdmIntegracaoDTO->getNumIdMdUtlAdmIntegracao() );
		  $objMdUtlAdmIntegParamDTO->retTodos();

		  $objMdUtlAdmIntegParamDTO = ( new MdUtlAdmIntegParamRN() )->listar( $objMdUtlAdmIntegParamDTO );

		  return [
			  'integracao'            => $objMdUtlAdmIntegracaoDTO,
			  'headers-integracao'    => $objMdUtlAdmIntegHeaderDTO,
			  'parametros-integracao' => $objMdUtlAdmIntegParamDTO
		  ];

	  }catch(Exception $e){
	  	throw new InfraException('Erro na busca da Integração por Funcionalidade.',$e);
	  }
  }
}
