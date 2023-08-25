<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/01/2023 - criado por gustavos.colab
*
* Versão do Gerador de Código: 1.43.2
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmPrmGrUsuCargaRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdUtlAdmPrmGrUsu(MdUtlAdmPrmGrUsuCargaDTO $objMdUtlAdmPrmGrUsuCargaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmPrmGrUsuCargaDTO->getNumIdMdUtlAdmPrmGrUsu())){
      $objInfraException->adicionarValidacao('ID do Usuário não informado.');
    }
  }

  private function validarNumCargaHoraria(MdUtlAdmPrmGrUsuCargaDTO $objMdUtlAdmPrmGrUsuCargaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmPrmGrUsuCargaDTO->getNumCargaHoraria())){
      $objInfraException->adicionarValidacao(' Carga Horária não informada.');
    }
  }

  private function validarDtaPeriodoInicial(MdUtlAdmPrmGrUsuCargaDTO $objMdUtlAdmPrmGrUsuCargaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmPrmGrUsuCargaDTO->getDtaPeriodoInicial())){
      $objInfraException->adicionarValidacao('Período Inicial não informado.');
    }
  }

  private function validarDtaPeriodoFinal(MdUtlAdmPrmGrUsuCargaDTO $objMdUtlAdmPrmGrUsuCargaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmPrmGrUsuCargaDTO->getDtaPeriodoFinal())){
      $objInfraException->adicionarValidacao('Período Final não informado.');
    }
  }

  protected function cadastrarControlado(MdUtlAdmPrmGrUsuCargaDTO $objMdUtlAdmPrmGrUsuCargaDTO) {
    try{

      #SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_gr_usu_carga_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdUtlAdmPrmGrUsu($objMdUtlAdmPrmGrUsuCargaDTO, $objInfraException);
      $this->validarNumCargaHoraria($objMdUtlAdmPrmGrUsuCargaDTO, $objInfraException);
      $this->validarDtaPeriodoInicial($objMdUtlAdmPrmGrUsuCargaDTO, $objInfraException);
      $this->validarDtaPeriodoFinal($objMdUtlAdmPrmGrUsuCargaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

	    $objMdUtlAdmPrmGrUsuCargaDTO->setDthInclusao(InfraData::getStrDataHoraAtual());

      $objMdUtlAdmPrmGrUsuCargaBD = new MdUtlAdmPrmGrUsuCargaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmPrmGrUsuCargaBD->cadastrar($objMdUtlAdmPrmGrUsuCargaDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando carga.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmPrmGrUsuCargaDTO $objMdUtlAdmPrmGrUsuCargaDTO){
    try {

      #SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_gr_usu_carga_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmPrmGrUsuCargaDTO->isSetNumIdMdUtlAdmPrmGrUsu()){
        $this->validarNumIdMdUtlAdmPrmGrUsu($objMdUtlAdmPrmGrUsuCargaDTO, $objInfraException);
      }
      if ($objMdUtlAdmPrmGrUsuCargaDTO->isSetNumCargaHoraria()){
        $this->validarNumCargaHoraria($objMdUtlAdmPrmGrUsuCargaDTO, $objInfraException);
      }
      if ($objMdUtlAdmPrmGrUsuCargaDTO->isSetDtaPeriodoInicial()){
        $this->validarDtaPeriodoInicial($objMdUtlAdmPrmGrUsuCargaDTO, $objInfraException);
      }
      if ($objMdUtlAdmPrmGrUsuCargaDTO->isSetDtaPeriodoFinal()){
        $this->validarDtaPeriodoFinal($objMdUtlAdmPrmGrUsuCargaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrUsuCargaBD = new MdUtlAdmPrmGrUsuCargaBD($this->getObjInfraIBanco());
      $objMdUtlAdmPrmGrUsuCargaBD->alterar($objMdUtlAdmPrmGrUsuCargaDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando carga.',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmPrmGrUsuCargaDTO){
    try {

      #SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_gr_usu_carga_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrUsuCargaBD = new MdUtlAdmPrmGrUsuCargaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmPrmGrUsuCargaDTO);$i++){
        $objMdUtlAdmPrmGrUsuCargaBD->excluir($arrObjMdUtlAdmPrmGrUsuCargaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo carga.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmPrmGrUsuCargaDTO $objMdUtlAdmPrmGrUsuCargaDTO){
    try {

      #SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_gr_usu_carga_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrUsuCargaBD = new MdUtlAdmPrmGrUsuCargaBD($this->getObjInfraIBanco());

      /** @var MdUtlAdmPrmGrUsuCargaDTO $ret */
      $ret = $objMdUtlAdmPrmGrUsuCargaBD->consultar($objMdUtlAdmPrmGrUsuCargaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando carga.',$e);
    }
  }

  protected function listarConectado(MdUtlAdmPrmGrUsuCargaDTO $objMdUtlAdmPrmGrUsuCargaDTO) {
    try {

      #SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_gr_usu_carga_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrUsuCargaBD = new MdUtlAdmPrmGrUsuCargaBD($this->getObjInfraIBanco());

      /** @var MdUtlAdmPrmGrUsuCargaDTO[] $ret */
      $ret = $objMdUtlAdmPrmGrUsuCargaBD->listar($objMdUtlAdmPrmGrUsuCargaDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando cargas.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmPrmGrUsuCargaDTO $objMdUtlAdmPrmGrUsuCargaDTO){
    try {

      #SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_gr_usu_carga_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrUsuCargaBD = new MdUtlAdmPrmGrUsuCargaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmPrmGrUsuCargaBD->contar($objMdUtlAdmPrmGrUsuCargaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando cargas.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMdUtlAdmPrmGrUsuCargaDTO){
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_gr_usu_carga_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrUsuCargaBD = new MdUtlAdmPrmGrUsuCargaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmPrmGrUsuCargaDTO);$i++){
        $objMdUtlAdmPrmGrUsuCargaBD->desativar($arrObjMdUtlAdmPrmGrUsuCargaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando carga.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmPrmGrUsuCargaDTO){
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_gr_usu_carga_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrUsuCargaBD = new MdUtlAdmPrmGrUsuCargaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmPrmGrUsuCargaDTO);$i++){
        $objMdUtlAdmPrmGrUsuCargaBD->reativar($arrObjMdUtlAdmPrmGrUsuCargaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando carga.',$e);
    }
  }

  protected function bloquearControlado(MdUtlAdmPrmGrUsuCargaDTO $objMdUtlAdmPrmGrUsuCargaDTO){
    try {

      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_gr_usu_carga_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrUsuCargaBD = new MdUtlAdmPrmGrUsuCargaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmPrmGrUsuCargaBD->bloquear($objMdUtlAdmPrmGrUsuCargaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando carga.',$e);
    }
  }
 */

	public function addCargaHorariaMembro( MdUtlAdmPrmGrUsuDTO $objMdUtlAdmPrmGrUsuDTO , $strFrequencia = null , $isChefeAlterado = false ){
		if( empty( $strFrequencia) ) throw new InfraException('O Tipo de Frequência é informação obrigatória.');

		$objMdUtlAdmPrmGrUsuCargaDTO = new MdUtlAdmPrmGrUsuCargaDTO();

		$fatorPres = $objMdUtlAdmPrmGrUsuDTO->getStrStaTipoJornada() == 'R'
			? $objMdUtlAdmPrmGrUsuDTO->getNumFatorReducaoJornada()
			: null;

		$arrPeriodo     = ( new MdUtlAdmPrmGrUsuRN() )->getDiasUteisNoPeriodo( [$strFrequencia,false] );
		$dtIniPadraoEUA = implode('-',array_reverse(explode('/',$arrPeriodo['dtInicial'])));
		$dtFimPadraoEUA = implode('-',array_reverse(explode('/',$arrPeriodo['dtFinal'])));

		$dtaIniPart = explode(' ' , $objMdUtlAdmPrmGrUsuDTO->getDthInicioParticipacao())[0];
		$dtaFimPart = empty($objMdUtlAdmPrmGrUsuDTO->getDthFimParticipacao())
									? $arrPeriodo['dtFinal']
									: explode(' ' , $objMdUtlAdmPrmGrUsuDTO->getDthFimParticipacao())[0];

		$dtIniPartPadraoEUA = implode('-',array_reverse(explode('/',$dtaIniPart)));
		$dtFimPartPadraoEUA = implode('-',array_reverse(explode('/',$dtaFimPart)));

		$cargaHoraria      = 0; //$this->geraTempoCargaHoraria( $fatorPres , $arrPeriodo['numFrequencia'] , $_POST['txtCargaPadrao'] );
		$arrDatasAusencias = [];
		$diaAtual          = date('Y-m-d');
		$isPodeCadastrar   = false;

    // virou chefe
    if ( $objMdUtlAdmPrmGrUsuDTO->getStrSinChefiaImediata() == 'S' && $isChefeAlterado ) {
	    $arrRangeDiasChefia = MdUtlAdmPrmGrUsuCargaINT::geraRangeDias($dtIniPadraoEUA , $dtFimPadraoEUA);
	    foreach ( $arrRangeDiasChefia as $diaChefia ) {
		    if ( strtotime($diaChefia) < strtotime($diaAtual) &&
			     ( strtotime($diaChefia) >= strtotime($dtIniPartPadraoEUA) && strtotime($diaChefia) <= strtotime($dtFimPartPadraoEUA) )
		    ) {
			    $cargaHoraria += (new MdUtlAdmPrmGrUsuCargaRN())->geraTempoCargaHoraria($fatorPres, 1, $_POST['txtCargaPadrao']);
		    }
	    }
    }
    // deixou de ser chefe
    else if ( $objMdUtlAdmPrmGrUsuDTO->getStrSinChefiaImediata() == 'N' && $isChefeAlterado ) {
	    $arrRangeDiasChefia = MdUtlAdmPrmGrUsuCargaINT::geraRangeDias($dtIniPadraoEUA , $dtFimPadraoEUA);
	    foreach ( $arrRangeDiasChefia as $diaChefia ) {
		    if ( strtotime($diaChefia) >= strtotime($diaAtual) &&
			     ( strtotime($diaChefia) >= strtotime($dtIniPartPadraoEUA) && strtotime($diaChefia) <= strtotime($dtFimPartPadraoEUA) )
		    ) {
			    $cargaHoraria += (new MdUtlAdmPrmGrUsuCargaRN())->geraTempoCargaHoraria($fatorPres, 1, $_POST['txtCargaPadrao']);
		    }
	    }
    }
    // Ou eh registro novo ou nao teve alteracao na informacao de chefia
    // e o valor do campo eh: nao chefe imediato
		else if ( $objMdUtlAdmPrmGrUsuDTO->getStrSinChefiaImediata() == 'N' && $isChefeAlterado == false ) {
			$arrRangeDias = MdUtlAdmPrmGrUsuCargaINT::geraRangeDias($dtIniPadraoEUA , $dtFimPadraoEUA);
			foreach ( $arrRangeDias as $dia ) {
				if ( strtotime($dia) >= strtotime($dtIniPartPadraoEUA) && strtotime($dia) <= strtotime($dtFimPartPadraoEUA)
				) {
					$cargaHoraria += (new MdUtlAdmPrmGrUsuCargaRN())->geraTempoCargaHoraria($fatorPres, 1, $_POST['txtCargaPadrao']);
				}
			}
		}
		// caso seja novo usuario e chefe, já foi inicializado a variavel $cargaHoraria com Zero, ou seja, não entra nas
		// condicoes acima

	  if ( $cargaHoraria > 0 ) {
      // verifica se a integracao esta ativa, com REST e o membro tem ausencias no periodo
      $objMdUtlAdmIntegDTO = ( new MdUtlAdmIntegracaoRN() )->obterConfigIntegracaoPorFuncionalidade(MdUtlAdmIntegracaoRN::$AUSENCIA);

      if ( $objMdUtlAdmIntegDTO && $objMdUtlAdmIntegDTO['integracao']->getStrTipoIntegracao() == 'RE' ) {
	      // retorna dados do usuario atual
	      $objUserDTO = new UsuarioDTO();
	      $objUserDTO->setNumIdUsuario($objMdUtlAdmPrmGrUsuDTO->getNumIdUsuario());
	      $objUserDTO->retStrSigla();
	      $objUserDTO = ( new UsuarioRN() )->consultarRN0489($objUserDTO);

	      if( !is_null($objUserDTO) ) {
		      $isPodeCadastrar = true;

		      $arrParams = ['dataInicial' => $dtIniPadraoEUA, 'dataFinal' => $dtFinPadraoEUA, 'loginUsuario' => $objUserDTO->getStrSigla()];
		      $arrParams = ['parametros' => MdUtlAdmIntegracaoINT::montaParametrosEntrada($objMdUtlAdmIntegDTO, $arrParams)];
		      $arrObjAusencia = MdUtlAdmIntegracaoINT::executarConsultaREST($objMdUtlAdmIntegDTO, $arrParams['parametros']);

		      if (!empty($arrObjAusencia)) {
			      $arrIdentificador = MdUtlAdmIntegracaoINT::montaParametrosSaida($objMdUtlAdmIntegDTO['parametros-integracao']);
			      foreach ($arrObjAusencia as $objAusencia) {
				      $arrRangeDiasAus = MdUtlAdmPrmGrUsuCargaINT::geraRangeDias($objAusencia->{$arrIdentificador['dataInicial']}, $objAusencia->{$arrIdentificador['dataFinal']});
				      foreach ($arrRangeDiasAus as $diaAusencia) {
					      if (strtotime($diaAusencia) >= strtotime($dtIniPadraoEUA) && strtotime($diaAusencia) <= strtotime($dtFinPadraoEUA)) {
						      array_push($arrDatasAusencias, $diaAusencia);
						      $tmpParcial = (new MdUtlAdmPrmGrUsuCargaRN())->geraTempoCargaHoraria($fatorPres, 1, $_POST['txtCargaPadrao']);
						      $cargaHoraria -= $tmpParcial;
					      }
				      }
			      }
		      }
	      }
      }
	  }

	  if ( $isPodeCadastrar ) {
		  $strDatasAusencias = empty($arrDatasAusencias) ? null : MdUtlAdmPrmGrUsuCargaINT::montaDatasAusenciasBanco($arrDatasAusencias);

		  $objMdUtlAdmPrmGrUsuCargaDTO->setNumCargaHoraria($cargaHoraria);
		  $objMdUtlAdmPrmGrUsuCargaDTO->setDtaPeriodoInicial($arrPeriodo['dtInicial']);
		  $objMdUtlAdmPrmGrUsuCargaDTO->setDtaPeriodoFinal($arrPeriodo['dtFinal']);
		  $objMdUtlAdmPrmGrUsuCargaDTO->setNumIdMdUtlAdmPrmGrUsu($objMdUtlAdmPrmGrUsuDTO->getNumIdMdUtlAdmPrmGrUsu());
		  $objMdUtlAdmPrmGrUsuCargaDTO->setStrDatasAusencias($strDatasAusencias);
		  $objMdUtlAdmPrmGrUsuCargaDTO->setStrSinAtivo('S');
		  $objMdUtlAdmPrmGrUsuCargaDTO->setNumIdUsuario($objMdUtlAdmPrmGrUsuDTO->getNumIdUsuario());
		  $objMdUtlAdmPrmGrUsuCargaDTO->setNumIdMdUtlAdmPrmGr($objMdUtlAdmPrmGrUsuDTO->getNumIdMdUtlAdmPrmGr());

		  $this->cadastrar($objMdUtlAdmPrmGrUsuCargaDTO);
	  }
	}

  public function atualizarCargaHorariaAntiga( MdUtlAdmPrmGrUsuDTO $objMdUtlAdmPrmGrUsuDTO, $objMdUtlAdmPrmGrUsuCargaDTO,  $strFrequencia = null){

    if( empty( $strFrequencia) ) throw new InfraException('O Tipo de Frequência é informação obrigatória.');

    $fatorPres = $objMdUtlAdmPrmGrUsuDTO->getStrStaTipoJornada() == 'R'
      ? $objMdUtlAdmPrmGrUsuDTO->getNumFatorReducaoJornada()
      : null;

    $dataInicial = $objMdUtlAdmPrmGrUsuCargaDTO->getDtaPeriodoInicial();
    $dataFinal   = date("d/m/Y", strtotime('-1 day'));

    $MdUtlPrazoRN = new MdUtlPrazoRN();
    $arrPeriodo = $MdUtlPrazoRN->retornaQtdDiaUtil($dataInicial, $dataFinal, false, false);

    $dtIniPadraoEUA    = implode('-',array_reverse(explode('/',$dataInicial)));
    $dtFinPadraoEUA    = implode('-',array_reverse(explode('/',$dataFinal)));
    $cargaHoraria      = $this->geraTempoCargaHoraria( $fatorPres , $arrPeriodo , $_POST['txtCargaPadrao'] );
    $arrDatasAusencias = [];

    // retorna dados do usuario atual
    $objUserDTO = new UsuarioDTO();
    $objUserDTO->setNumIdUsuario($objMdUtlAdmPrmGrUsuDTO->getNumIdUsuario());
    $objUserDTO->retStrSigla();
    $objUserDTO = ( new UsuarioRN() )->consultarRN0489($objUserDTO);

    // monta o primeiro parametro de consulta ao web service
    $arrParams = ['loginUsuario' => $objUserDTO->getStrSigla()];

    // verifica se o membro eh chefe imediato
    $objMdUtlAdmIntegDTO = ( new MdUtlAdmIntegracaoRN() )->obterConfigIntegracaoPorFuncionalidade(MdUtlAdmIntegracaoRN::$CHEFIA);

    if ( !empty($objMdUtlAdmIntegDTO) && $objMdUtlAdmIntegDTO['integracao']->getStrTipoIntegracao() == 'RE') {
      $arrParams = ['parametros' => MdUtlAdmIntegracaoINT::montaParametrosEntrada( $objMdUtlAdmIntegDTO, $arrParams )];
      $objChefia = MdUtlAdmIntegracaoINT::executarConsultaREST( $objMdUtlAdmIntegDTO , $arrParams['parametros'] );

      if ( !empty($objChefia) ) {
	      $arrIdentificador = MdUtlAdmIntegracaoINT::montaParametrosSaida($objMdUtlAdmIntegDTO['parametros-integracao']);
	      // Chefe Titular
	      if ( intval($objChefia[0]->{$arrIdentificador['tipoEmpregado']}) == 1 ) {
              $cargaHoraria = 0;
        } else { // Chefe Substituto
          $arrRangeDiasChefia = MdUtlAdmPrmGrUsuCargaINT::geraRangeDias( $objChefia[0]->{$arrIdentificador['dataInicial']} , $objChefia[0]->{$arrIdentificador['dataFinal']} );
          foreach ( $arrRangeDiasChefia as $diaChefia ) {
            if ( strtotime($diaChefia) >= strtotime($dtIniPadraoEUA ) && strtotime($diaChefia) <= strtotime( $dtFinPadraoEUA ) ) {
                $tmpParcial = (new MdUtlAdmPrmGrUsuCargaRN())->geraTempoCargaHoraria( $fatorPres, 1, $_POST['txtCargaPadrao'] );
                $cargaHoraria -= $tmpParcial;
            }
          }
        }
      }
    }

    $objMdUtlAdmIntegDTO = null;

    if ( $cargaHoraria > 0 ) {
      // verifica se a integracao esta ativa, com REST e o membro tem ausencias no periodo
      $objMdUtlAdmIntegDTO = ( new MdUtlAdmIntegracaoRN() )->obterConfigIntegracaoPorFuncionalidade(MdUtlAdmIntegracaoRN::$AUSENCIA);

      if ( $objMdUtlAdmIntegDTO && $objMdUtlAdmIntegDTO['integracao']->getStrTipoIntegracao() == 'RE' ) {
        $arrParams      = ['dataInicial' => $dtIniPadraoEUA, 'dataFinal' => $dtFinPadraoEUA, 'loginUsuario' => $objUserDTO->getStrSigla()];
        $arrParams      = ['parametros' => MdUtlAdmIntegracaoINT::montaParametrosEntrada( $objMdUtlAdmIntegDTO, $arrParams )];
        $arrObjAusencia = MdUtlAdmIntegracaoINT::executarConsultaREST( $objMdUtlAdmIntegDTO , $arrParams['parametros'] );

        if ( !empty( $arrObjAusencia ) ) {
	        $arrIdentificador = MdUtlAdmIntegracaoINT::montaParametrosSaida($objMdUtlAdmIntegDTO['parametros-integracao']);
          foreach ( $arrObjAusencia as $objAusencia ) {
            $arrRangeDiasAus = MdUtlAdmPrmGrUsuCargaINT::geraRangeDias( $objAusencia->{$arrIdentificador['dataInicial']} , $objAusencia->{$arrIdentificador['dataFinal']} );
            foreach ( $arrRangeDiasAus as $diaAusencia ) {
              if ( strtotime($diaAusencia) >= strtotime($dtIniPadraoEUA ) && strtotime($diaAusencia) <= strtotime( $dtFinPadraoEUA )	) {
                array_push($arrDatasAusencias,$diaAusencia);
                $tmpParcial = (new MdUtlAdmPrmGrUsuCargaRN())->geraTempoCargaHoraria( $fatorPres, 1, $_POST['txtCargaPadrao'] );
                $cargaHoraria -= $tmpParcial;
              }
            }
          }
        }
      }
    }

    $strDatasAusencias = empty($arrDatasAusencias) ? null : MdUtlAdmPrmGrUsuCargaINT::montaDatasAusenciasBanco($arrDatasAusencias);

    $objMdUtlAdmPrmGrUsuCargaDTO->setNumCargaHoraria( $cargaHoraria );
    $objMdUtlAdmPrmGrUsuCargaDTO->setDtaPeriodoInicial( $dataInicial );
    $objMdUtlAdmPrmGrUsuCargaDTO->setDtaPeriodoFinal( $dataFinal );
    $objMdUtlAdmPrmGrUsuCargaDTO->setNumIdMdUtlAdmPrmGrUsu( $objMdUtlAdmPrmGrUsuDTO->getNumIdMdUtlAdmPrmGrUsu() );
    $objMdUtlAdmPrmGrUsuCargaDTO->setStrDatasAusencias( $strDatasAusencias );
    $objMdUtlAdmPrmGrUsuCargaDTO->setStrSinAtivo('S');
    $objMdUtlAdmPrmGrUsuCargaDTO->setNumIdUsuario($objMdUtlAdmPrmGrUsuDTO->getNumIdUsuario());
    $objMdUtlAdmPrmGrUsuCargaDTO->setNumIdMdUtlAdmPrmGr($objMdUtlAdmPrmGrUsuDTO->getNumIdMdUtlAdmPrmGr());

    $this->alterar( $objMdUtlAdmPrmGrUsuCargaDTO );
  }

	public function geraTempoCargaHoraria( $fatorPresenca , $qtdDias , $cargaPadrao ){
		$fator        = !is_null($fatorPresenca) ? $fatorPresenca / 100 : null;
		$tempo_padrao = $cargaPadrao;
		return is_null( $fator ) ? $tempo_padrao * $qtdDias : intval( ( $tempo_padrao * $qtdDias ) * $fator );
	}

	public function buscaPeriodoParaAvaliacao( $arrParams ){
		$objMdUtlAdmPrmGrUsuCargaDTO = new MdUtlAdmPrmGrUsuCargaDTO();
		$objMdUtlAdmPrmGrUsuCargaDTO->setNumIdMdUtlAdmPrmGrUsu($arrParams['idPrmGrUsu']);
		$objMdUtlAdmPrmGrUsuCargaDTO->setDtaPeriodoInicial($arrParams['periodoIni']);
		$objMdUtlAdmPrmGrUsuCargaDTO->setDtaPeriodoFinal($arrParams['periodoFin']);
		$objMdUtlAdmPrmGrUsuCargaDTO->setNumMaxRegistrosRetorno(1);
		$objMdUtlAdmPrmGrUsuCargaDTO->setOrd('IdMdUtlAdmPrmGrUsuCarga',InfraDTO::$TIPO_ORDENACAO_DESC);

		$objMdUtlAdmPrmGrUsuCargaDTO->retTodos();

		return $this->consultar( $objMdUtlAdmPrmGrUsuCargaDTO );
	}

	public function buscaPeriodoPorParametrizacaoTpCtrl( $arrParams ) {
		$objMdUtlAdmPrmGrUsuCargaDTO = new MdUtlAdmPrmGrUsuCargaDTO();

		$objMdUtlAdmPrmGrUsuCargaDTO->setNumIdMdUtlAdmPrmGr($arrParams['idPrmGr']);
		$objMdUtlAdmPrmGrUsuCargaDTO->setDtaPeriodoInicial($arrParams['periodoIni']);
		$objMdUtlAdmPrmGrUsuCargaDTO->setDtaPeriodoFinal($arrParams['periodoFin']);
		$objMdUtlAdmPrmGrUsuCargaDTO->setStrSinAtivo('S');

		$objMdUtlAdmPrmGrUsuCargaDTO->retTodos();

		return $this->listar( $objMdUtlAdmPrmGrUsuCargaDTO );
	}

	public function desativarCargaHorariaAtual( $idPrmGrUsu ){
		$arrPeriodo = ( new MdUtlAdmPrmGrUsuRN() )->getDiasUteisNoPeriodo( [$_POST['selStaFrequencia'],false] );

		$arrParams = [
			'idPrmGrUsu' => $idPrmGrUsu,
			'periodoIni' => $arrPeriodo['dtInicial'] ,
			'periodoFin' => $arrPeriodo['dtFinal']
		];

		$objMdUtlCargaDTO = $this->buscaPeriodoParaAvaliacao( $arrParams );

		if( !is_null( $objMdUtlCargaDTO )) {
			$objMdUtlCargaDTO->setStrSinAtivo('N');
			$this->alterar($objMdUtlCargaDTO);
		}
	}

	public function getInfoCargaPeriodoAtivo( $arrParams , $isRetUnicoRegistro = true ){

		$objMdUtlAdmPrmGrUsuCargaDTO = new MdUtlAdmPrmGrUsuCargaDTO();

		$objMdUtlAdmPrmGrUsuCargaDTO->setStrSinAtivo('S');

		if( isset($arrParams['idPrmGr']) && !is_null($arrParams['idPrmGr']) )
			$objMdUtlAdmPrmGrUsuCargaDTO->setNumIdMdUtlAdmPrmGr([$arrParams['idPrmGr']] , InfraDTO::$OPER_IN);

		if( isset($arrParams['idPrmGrUsu']) && !is_null($arrParams['idPrmGrUsu']) )
			$objMdUtlAdmPrmGrUsuCargaDTO->setNumIdMdUtlAdmPrmGrUsu($arrParams['idPrmGrUsu']);

		if( isset($arrParams['idUsuario']) && !is_null($arrParams['idUsuario']) )
			$objMdUtlAdmPrmGrUsuCargaDTO->setNumIdUsuario([$arrParams['idUsuario']] , InfraDTO::$OPER_IN);

		if( isset($arrParams['periodoIni']) && !is_null($arrParams['periodoIni']) )
			$objMdUtlAdmPrmGrUsuCargaDTO->setDtaPeriodoInicial($arrParams['periodoIni']);

		if( isset($arrParams['periodoFin']) && !is_null($arrParams['periodoFin']) )
			$objMdUtlAdmPrmGrUsuCargaDTO->setDtaPeriodoFinal($arrParams['periodoFin']);

		if( $isRetUnicoRegistro )
			$objMdUtlAdmPrmGrUsuCargaDTO->setNumMaxRegistrosRetorno(1);

		//config ordenacao
		//$objMdUtlAdmPrmGrUsuCargaDTO->setOrd('IdMdUtlAdmPrmGrUsuCarga',InfraDTO::$TIPO_ORDENACAO_DESC);

		//config coluna(s) para retorno
		$objMdUtlAdmPrmGrUsuCargaDTO->retNumCargaHoraria();

		if ( $isRetUnicoRegistro ) {
			$ret = $this->consultar($objMdUtlAdmPrmGrUsuCargaDTO);
			return is_null($ret) ? '0' : $ret->getNumCargaHoraria();
		} else {
			return $this->listar($objMdUtlAdmPrmGrUsuCargaDTO);
		}
	}
}
