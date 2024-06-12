<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/07/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmPrmGrUsuRN extends InfraRN {

    //Tipo de Presença
    public static $TP_PRESENCA_PRESENCIAL   = 'P';
    public static $TP_PRESENCA_TELETRABALHO = 'T';
    public static $TP_PRESENCA_DIFERENCIADO = 'D';

    //Tipo de Jornada
    public static $TIPOJORNADA_INTEGRAL = 'I';
    public static $TIPOJORNADA_REDUZIDO = 'R';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }


  private function validarNumIdMdUtlAdmPrmGr(MdUtlAdmPrmGrUsuDTO $objMdUtlAdmPrmGrUsuDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmPrmGrUsuDTO->getNumIdMdUtlAdmPrmGr())){
      $objInfraException->adicionarValidacao('IdMdUtlAdmPrmGr não informad.');
    }
  }

  private function validarNumIdUsuario(MdUtlAdmPrmGrUsuDTO $objMdUtlAdmPrmGrUsuDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmPrmGrUsuDTO->getNumIdUsuario())){
      $objMdUtlAdmPrmGrUsuDTO->setNumIdUsuario(null);
    }
  }


  protected function cadastrarControlado(MdUtlAdmPrmGrUsuDTO $objMdUtlAdmPrmGrUsuDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_prm_gr_usu_cadastrar', __METHOD__, $objMdUtlAdmPrmGrUsuDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdUtlAdmPrmGr($objMdUtlAdmPrmGrUsuDTO, $objInfraException);
      $this->validarNumIdUsuario($objMdUtlAdmPrmGrUsuDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrUsuBD = new MdUtlAdmPrmGrUsuBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmPrmGrUsuBD->cadastrar($objMdUtlAdmPrmGrUsuDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmPrmGrUsuDTO $objMdUtlAdmPrmGrUsuDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_prm_gr_usu_alterar', __METHOD__, $objMdUtlAdmPrmGrUsuDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmPrmGrUsuDTO->isSetNumIdMdUtlAdmPrmGr()){
        $this->validarNumIdMdUtlAdmPrmGr($objMdUtlAdmPrmGrUsuDTO, $objInfraException);
      }
      if ($objMdUtlAdmPrmGrUsuDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objMdUtlAdmPrmGrUsuDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrUsuBD = new MdUtlAdmPrmGrUsuBD($this->getObjInfraIBanco());
      $objMdUtlAdmPrmGrUsuBD->alterar($objMdUtlAdmPrmGrUsuDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmPrmGrUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_prm_gr_usu_excluir', __METHOD__, $arrObjMdUtlAdmPrmGrUsuDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrUsuBD = new MdUtlAdmPrmGrUsuBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmPrmGrUsuDTO);$i++){
        $ret = $objMdUtlAdmPrmGrUsuBD->excluir($arrObjMdUtlAdmPrmGrUsuDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmPrmGrUsuDTO $objMdUtlAdmPrmGrUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_gr_usu_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrUsuBD = new MdUtlAdmPrmGrUsuBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmPrmGrUsuBD->consultar($objMdUtlAdmPrmGrUsuDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado($objMdUtlAdmPrmGrUsuDTO) {
    try {

        $objMdUtlAdmPrmGrUsuBD = new MdUtlAdmPrmGrUsuBD($this->getObjInfraIBanco());

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_gr_usu_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();


      $ret = $objMdUtlAdmPrmGrUsuBD->listar($objMdUtlAdmPrmGrUsuDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdUtlAdmPrmGrUsuDTO $objMdUtlAdmPrmGrUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_gr_usu_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrUsuBD = new MdUtlAdmPrmGrUsuBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmPrmGrUsuBD->contar($objMdUtlAdmPrmGrUsuDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

  protected function pesquisarUsuarioParametrosConectado(MdUtlAdmPrmGrUsuDTO $objUsuarioDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('usuario_listar',__METHOD__,$objUsuarioDTO);

      if ($objUsuarioDTO->isSetStrSigla()){
        if (!InfraString::isBolVazia($objUsuarioDTO->getStrSigla())) {
          $objUsuarioDTO->setStrSigla('%' . $objUsuarioDTO->getStrSigla() . '%', InfraDTO::$OPER_LIKE);
        }else{
          $objUsuarioDTO->unSetStrSigla();
        }
      }

      if ($objUsuarioDTO->isSetStrNome()){
        if (!InfraString::isBolVazia($objUsuarioDTO->getStrNome())) {
          $strPalavrasPesquisa = InfraString::prepararIndexacao($objUsuarioDTO->getStrNome());
          $arrPalavrasPesquisa = explode(' ', $strPalavrasPesquisa);

          for ($i = 0; $i < count($arrPalavrasPesquisa); $i++) {
            $arrPalavrasPesquisa[$i] = '%' . $arrPalavrasPesquisa[$i] . '%';
          }

          if (count($arrPalavrasPesquisa) == 1) {
            $objUsuarioDTO->setStrNome($arrPalavrasPesquisa[0], InfraDTO::$OPER_LIKE);
          } else {
            $objUsuarioDTO->unSetStrNome();
            $a = array_fill(0, count($arrPalavrasPesquisa), 'Nome');
            $b = array_fill(0, count($arrPalavrasPesquisa), InfraDTO::$OPER_LIKE);
            $d = array_fill(0, count($arrPalavrasPesquisa) - 1, InfraDTO::$OPER_LOGICO_AND);
            $objUsuarioDTO->adicionarCriterio($a, $b, $arrPalavrasPesquisa, $d);
          }
        }else{
          $objUsuarioDTO->unSetStrNome();
        }
      }

      if ($objUsuarioDTO->isSetStrPalavrasPesquisa()) {
        if (!InfraString::isBolVazia($objUsuarioDTO->getStrPalavrasPesquisa())) {
          $strPalavrasPesquisa = InfraString::prepararIndexacao($objUsuarioDTO->getStrPalavrasPesquisa());

          $arrPalavrasPesquisa = explode(' ', $strPalavrasPesquisa);

          $numPalavrasPesquisa = count($arrPalavrasPesquisa);

          if ($numPalavrasPesquisa) {
            for ($i = 0; $i < $numPalavrasPesquisa; $i++) {
              $arrPalavrasPesquisa[$i] = '%' . $arrPalavrasPesquisa[$i] . '%';
            }

            if ($numPalavrasPesquisa == 1) {
              $objUsuarioDTO->setStrIdxUsuario($arrPalavrasPesquisa[0], InfraDTO::$OPER_LIKE);
            } else {
              $a = array_fill(0, $numPalavrasPesquisa, 'IdxUsuario');
              $b = array_fill(0, $numPalavrasPesquisa, InfraDTO::$OPER_LIKE);
              $d = array_fill(0, $numPalavrasPesquisa - 1, InfraDTO::$OPER_LOGICO_AND);
              $objUsuarioDTO->adicionarCriterio($a, $b, $arrPalavrasPesquisa, $d);
            }
          }
        } else {
          $objUsuarioDTO->unSetStrPalavrasPesquisa();
        }
      }

      return $this->listar($objUsuarioDTO);

    }catch(Exception $e){
      throw new InfraException('Erro pesquisando Usuários.',$e);
    }
  }

  protected function montarArrUsuarioParticipanteControlado($idMdUtlAdmPrmGr){
			$objMdUtlAdmPrmGrUsuCargaRN = new MdUtlAdmPrmGrUsuCargaRN();

	    $arrDatasFiltro = ( new MdUtlPrazoRN() )->getDatasPeriodoAtual($idMdUtlAdmPrmGr);

      $mdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
      $mdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($idMdUtlAdmPrmGr);
      $mdUtlAdmPrmGrUsuDTO->retTodos(true);
      $mdUtlAdmPrmGrUsuDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_DESC);
      $mdUtlAdmPrmGrUsuDTO->setOrdNumIdMdUtlAdmPrmGrUsu(InfraDTO::$TIPO_ORDENACAO_DESC);
      $mdUtlAdmPrmGrUsu = $this->listar($mdUtlAdmPrmGrUsuDTO);



      $arrPresenca = array(
          MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_PRESENCIAL => 'Presencial',
          MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_TELETRABALHO => 'Teletrabalho'
      );

      $arrJornada = array( MdUtlAdmPrmGrUsuRN::$TIPOJORNADA_INTEGRAL =>'Integral',
          MdUtlAdmPrmGrUsuRN::$TIPOJORNADA_REDUZIDO => 'Reduzido');

      foreach ($mdUtlAdmPrmGrUsu as $dadosUsuParticipante){
          $UsuarioParticipante = array();
          $htmlDadosUsuario  = '<a alt="'.$dadosUsuParticipante->getStrNome().'" title="'.$dadosUsuParticipante->getStrNome().'" class="ancoraSigla"> '.$dadosUsuParticipante->getStrSigla().' </a>';

          //$UsuarioParticipante[]= $dadosUsuParticipante->getNumIdMdUtlAdmPrmGrUsu();
          $UsuarioParticipante[]= $dadosUsuParticipante->getNumIdUsuario();
          $UsuarioParticipante[]= $htmlDadosUsuario;

          //Tipo presenca
          $UsuarioParticipante[]= $arrPresenca[$dadosUsuParticipante->getStrStaTipoPresenca()];
          $UsuarioParticipante[]= $dadosUsuParticipante->getStrStaTipoPresenca();
          
          // Id Dcomento
          $linkAux   = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_documento='. $dadosUsuParticipante->getDblIdDocumento());
          $numSei    = $this->getNumeroSeiPlanoTrabalho( $dadosUsuParticipante->getDblIdDocumento() );
          $montaLink = !empty( $numSei )
                          ? '<a alt="'.$numSei.'" href="'.$linkAux.'" target="_blank" style="text-decoration: underline;">'.$numSei.'</a>'
                          : '';
                          
          $UsuarioParticipante[]= $montaLink;

          // adicionado com null para não alterar o índice dos dados na grid de membros participantes
	        $UsuarioParticipante[] = null;

          //Tipo Jornada
          $UsuarioParticipante[]= $arrJornada[$dadosUsuParticipante->getStrStaTipoJornada()];
          $UsuarioParticipante[]= $dadosUsuParticipante->getStrStaTipoJornada();

          if($dadosUsuParticipante->getStrStaTipoJornada() == self::$TIPOJORNADA_REDUZIDO) {
              $UsuarioParticipante[] = $dadosUsuParticipante->getNumFatorReducaoJornada() . '%';
          }else{
              $UsuarioParticipante[] = $dadosUsuParticipante->getNumFatorReducaoJornada();
          }

          $UsuarioParticipante[]= $dadosUsuParticipante->getNumIdMdUtlAdmPrmGrUsu();
          $UsuarioParticipante[]= $dadosUsuParticipante->getStrNome().'('.$dadosUsuParticipante->getStrSigla().')';

          //Novo campo - chefia imediata
          $UsuarioParticipante[]= $dadosUsuParticipante->getStrSinChefiaImediata() == 'S' ? 'Sim' : 'Não';

          //Data Inicio e Fim de Participacao
          $UsuarioParticipante[]= empty($dadosUsuParticipante->getDthInicioParticipacao()) ? '' : explode(' ', $dadosUsuParticipante->getDthInicioParticipacao())[0];
          $UsuarioParticipante[]= empty($dadosUsuParticipante->getDthFimParticipacao()) ? '' : explode(' ', $dadosUsuParticipante->getDthFimParticipacao())[0];

          //Carga Horaria
	        $arrParams = [
	        	'periodoIni' => $arrDatasFiltro['DT_INICIAL'],
		        'periodoFin' => $arrDatasFiltro['DT_FINAL'],
		        'idUsuario'  => $dadosUsuParticipante->getNumIdUsuario(),
		        'idPrmGr'    => $idMdUtlAdmPrmGr,
	        ];

	        $cargaHorariaMembro = $objMdUtlAdmPrmGrUsuCargaRN->getInfoCargaPeriodoAtivo($arrParams);
	        $cargaHorariaMembro = empty($cargaHorariaMembro) ? '0' : $cargaHorariaMembro;

	        $UsuarioParticipante[] = MdUtlAdmPrmGrINT::convertToHoursMins($cargaHorariaMembro);

          $arrUsuarioParticipante[]= $UsuarioParticipante;
      }
      $arrTbUsuarioParticipante = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrUsuarioParticipante);

      return array('itensTabela'=>$arrUsuarioParticipante,'qtdUsuario'=> $arrUsuarioParticipante ? count($arrUsuarioParticipante) : 0);
  }

  protected function getDadosUsuarioMembroConectado( $idMdUtlAdmPrmGr ){
    $objUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
    $objUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($idMdUtlAdmPrmGr);
    $objUtlAdmPrmGrUsuDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_DESC);
    $objUtlAdmPrmGrUsuDTO->setOrdNumIdMdUtlAdmPrmGrUsu(InfraDTO::$TIPO_ORDENACAO_DESC);

	  $objUtlAdmPrmGrUsuDTO->retNumIdMdUtlAdmPrmGrUsu();
	  $objUtlAdmPrmGrUsuDTO->retNumIdUsuario();
	  $objUtlAdmPrmGrUsuDTO->retStrSigla();
		$objUtlAdmPrmGrUsuDTO->retStrSinChefiaImediata();
	  $objUtlAdmPrmGrUsuDTO->retStrStaFrequenciaParametrizacao();
	  $objUtlAdmPrmGrUsuDTO->retNumCargaPadraoParametrizacao();
	  $objUtlAdmPrmGrUsuDTO->retStrStaTipoPresenca();
	  $objUtlAdmPrmGrUsuDTO->retStrStaTipoJornada();
	  $objUtlAdmPrmGrUsuDTO->retNumFatorReducaoJornada();
	  $objUtlAdmPrmGrUsuDTO->retNumIdMdUtlAdmPrmGr();

    $arrObjUtlAdmPrmGrUsuDTO = $this->listar($objUtlAdmPrmGrUsuDTO);

    $arrUsuarios = [];

    foreach ( $arrObjUtlAdmPrmGrUsuDTO as $usuario ) {
      $arrUsuarios[] = [
        'idPrmGrUsu'     => $usuario->getNumIdMdUtlAdmPrmGrUsu(),
        'idUsuario'      => $usuario->getNumIdUsuario(),
        'siglaUsuario'   => $usuario->getStrSigla(),
        'chefiaImediata' => $usuario->getStrSinChefiaImediata(),
	      'frequencia'     => $usuario->getStrStaFrequenciaParametrizacao(),
	      'tipoJornada'    => $usuario->getStrStaTipoJornada(),
	      'tipoPresenca'   => $usuario->getStrStaTipoPresenca(),
	      'fatorJornada'   => $usuario->getNumFatorReducaoJornada(),
	      'cargaPadrao'    => $usuario->getNumCargaPadraoParametrizacao(),
	      'idPrmGr'        => $usuario->getNumIdMdUtlAdmPrmGr(),
      ];
    }
    return $arrUsuarios;
  }

  public function getNumeroSeiPlanoTrabalho( $idDoc ){
    $objDocumentoDTO = new DocumentoDTO();
    $objDocumentoRN = new DocumentoRN();

    $objDocumentoDTO->setDblIdDocumento( $idDoc );    
    $objDocumentoDTO->setNumMaxRegistrosRetorno(1);
    $objDocumentoDTO->retStrProtocoloDocumentoFormatado();    

    $objDocumentoDTO = $objDocumentoRN->consultarRN0005( $objDocumentoDTO );
    
    return $objDocumentoDTO ? $objDocumentoDTO->getStrProtocoloDocumentoFormatado() : null;
  }

  public function excluirUsuarioParticipante($idsUsuariosExcl, $idsVinculadosBd){
      $arrGrUsuDTO = array();
	    $arrDadosExtraMembro = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTbUsuarioRemove']);
      foreach($idsUsuariosExcl as $idUsuario){
          $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
          $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGrUsu($idsVinculadosBd[$idUsuario]);
          $objMdUtlAdmPrmGrUsuDTO->retTodos();

          $objMdUtlAdmPrmGrUsuDTO = $this->consultar($objMdUtlAdmPrmGrUsuDTO);

          // loop para buscar o usuario em questao para pegar a data Fim Participacao que constava na grid
		      foreach ( $arrDadosExtraMembro as $updFimPart ) {
			      if ( (int) $updFimPart[1] == (int) $idUsuario ) {
				      $objMdUtlAdmPrmGrUsuDTO->setDthFimParticipacao( $updFimPart[2] );
				      break;
			      }
		      }

          $arrGrUsuDTO[] = $objMdUtlAdmPrmGrUsuDTO;
          $this->atualizarCargaAposExclusao( $objMdUtlAdmPrmGrUsuDTO );
      }
      $this->excluir($arrGrUsuDTO);
  }

  protected function atualizarCargaAposExclusao( $objMdUtlAdmPrmGrUsuDTO ){
	  $objMdUtlAdmPrmGrUsuCargaRN = new MdUtlAdmPrmGrUsuCargaRN();
  	$objMdUtlCargaDTO           = new MdUtlAdmPrmGrUsuCargaDTO();
  	$dtFimParticipacao = explode(' ' , $objMdUtlAdmPrmGrUsuDTO->getDthFimParticipacao() )[0];
	  $objMdUtlCargaDTO->setDtaPeriodoInicial( $dtFimParticipacao , InfraDTO::$OPER_MAIOR_IGUAL );
	  $objMdUtlCargaDTO->setNumIdUsuario( $objMdUtlAdmPrmGrUsuDTO->getNumIdUsuario() );
	  $objMdUtlCargaDTO->setNumIdMdUtlAdmPrmGr( $objMdUtlAdmPrmGrUsuDTO->getNumIdMdUtlAdmPrmGr() );
	  $objMdUtlCargaDTO->setStrSinAtivo('S');
	  $objMdUtlCargaDTO->retTodos();

	  $arrObjs = $objMdUtlAdmPrmGrUsuCargaRN->listar( $objMdUtlCargaDTO );

	  $fatorPres = $objMdUtlAdmPrmGrUsuDTO->getStrStaTipoJornada() == 'R'
		  ? $objMdUtlAdmPrmGrUsuDTO->getNumFatorReducaoJornada()
		  : null;

	  if ( !empty($arrObjs)) {
	  	foreach ( $arrObjs as $carga ) { // loop em cada periodo da carga
				if ( InfraData::compararDatasSimples($dtFimParticipacao , $carga->getDtaPeriodoInicial()) >= 0 ) {
					$carga->setNumCargaHoraria(0);
				} else {
					$_carga         = $carga->getNumCargaHoraria();
					$dtIniEUA      = implode('-',array_reverse(explode('/',$carga->getDtaPeriodoInicial())));
					$dtFinEUA      = implode('-',array_reverse(explode('/',$carga->getDtaPeriodoFinal())));
					$dtFinPartEUA  = implode('-',array_reverse(explode('/',$dtFimParticipacao)));
					$arrRangeDatas = MdUtlAdmPrmGrUsuCargaINT::geraRangeDias($dtIniEUA , $dtFinEUA);
					$cargaDiaria   = $objMdUtlAdmPrmGrUsuCargaRN->geraTempoCargaHoraria( $fatorPres, 1, $_POST['selStaFrequencia'] );
					foreach ( $arrRangeDatas as $dia ) {
						if ( strtotime($dia) >= strtotime($dtFinPartEUA) ) $_carga -= $cargaDiaria;
					}
					$carga->setNumCargaHoraria($_carga);
				}
			  $objMdUtlAdmPrmGrUsuCargaRN->alterar($carga);
		  }
	  }
  }

 protected  function usuarioLogadoIsUsuarioParticipanteConectado($idPrmTpCtrl){

     $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
     $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($idPrmTpCtrl);
     $objMdUtlAdmPrmGrUsuDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
     
     return $this->contar($objMdUtlAdmPrmGrUsuDTO) > 0;
 }

    protected function verificaCargaPadraoConectado($arrObj){

        $idUsuarioParticipante = array_key_exists(0, $arrObj) ? $arrObj[0] : null;
        $idParam               = array_key_exists(1, $arrObj) ? $arrObj[1] : null;
	      $arrDatasFiltro        = ( new MdUtlPrazoRN() )->getDatasPeriodoAtual($idParam);
        $numCargaPadrao        = array_key_exists(2, $arrObj) ? $arrObj[2] : null;
        $numPercentualTele     = array_key_exists(3, $arrObj) ? $arrObj[3] : null;
	      $diasUteis             = array_key_exists(4, $arrObj) ? $arrObj[4]['numFrequencia'] : null;
	      $periodoInicial        = ( array_key_exists(4, $arrObj) && !is_null($arrObj[4]['dtInicial']) ) ? $arrObj[4]['dtInicial'] : $arrDatasFiltro['DT_INICIAL'];
	      $periodoFinal          = ( array_key_exists(4, $arrObj) && !is_null($arrObj[4]['dtFinal']) ) ? $arrObj[4]['dtFinal'] : $arrDatasFiltro['DT_FINAL'];
		    $arrParams             = [ 'idPrmGr' => $idParam , 'idUsuario' => $idUsuarioParticipante , 'periodoIni' =>  $periodoInicial , 'periodoFin' => $periodoFinal];

		    $cargaPeriodoAtual     = ( new MdUtlAdmPrmGrUsuCargaRN() )->getInfoCargaPeriodoAtivo($arrParams);

		    if ( !is_null($cargaPeriodoAtual) ) return $cargaPeriodoAtual;

		    // se o resultado acima retornar null ou array vazio, busca pelo carga padrão parametrizada no Tipo de Ctrl
        $fatorReducaoFornada = 0;
        $fatorDesempUsu      = 0;
        
        $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();

        $objMdUtlAdmPrmGrUsuDTO->setNumIdUsuario($idUsuarioParticipante);
        $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($idParam);
        $objMdUtlAdmPrmGrUsuDTO->retStrStaTipoPresenca();
        $objMdUtlAdmPrmGrUsuDTO->retStrStaTipoJornada();
        $objMdUtlAdmPrmGrUsuDTO->retNumFatorReducaoJornada();
        $objMdUtlAdmPrmGrUsuDTO->setOrd('IdMdUtlAdmPrmGrUsu', 'desc');
        $objMdUtlAdmPrmGrUsuDTO->setNumMaxRegistrosRetorno(1);

        $objPrmGrUsuDTO = $this->consultar($objMdUtlAdmPrmGrUsuDTO);

        if( is_null($objPrmGrUsuDTO)) return 0;
        
        $staTipoJornada  = $objPrmGrUsuDTO->getStrStaTipoJornada();
        $numFatorReducaoJor = $objPrmGrUsuDTO->getNumFatorReducaoJornada();

        switch ($staTipoJornada){
            case MdUtlAdmPrmGrUsuRN::$TIPOJORNADA_INTEGRAL:
                $fatorReducaoFornada = 1;
                break;

            case MdUtlAdmPrmGrUsuRN::$TIPOJORNADA_REDUZIDO:
                $fatorReducaoFornada = $numFatorReducaoJor / 100;
                break;
        }

        return intval(($numCargaPadrao * $diasUteis)   * $fatorReducaoFornada);
    }

    protected function getDiasUteisNoPeriodoConectado($arrParams){
  	    $staFrequencia    = $arrParams[0];
	    $isUsarFeriadoSEI = array_key_exists(1,$arrParams) ? $arrParams[1] : true;
	    $dataAtual        = array_key_exists(2,$arrParams) ? $arrParams[2] : InfraData::getStrDataAtual();
        $MdUtlPrazoRN     = new MdUtlPrazoRN();


        $dataAtualFormatada = explode('/', $dataAtual);
        $diaAtual      = $dataAtualFormatada[0];
        $mesAtual      = $dataAtualFormatada[1];
        $anoAtual      = $dataAtualFormatada[2];

        switch ($staFrequencia){
            case MdUtlAdmPrmGrRN::$FREQUENCIA_DIARIO:
                $dtInicial = $diaAtual . '/' . $mesAtual . '/' . $anoAtual;
                $dtFinal   = $diaAtual . '/' . $mesAtual . '/' . $anoAtual;
                $diaUtil = $MdUtlPrazoRN->verificaDiaUtil($dtInicial, $dtFinal, $isUsarFeriadoSEI);

                $numFrequencia = $diaUtil ? 1 : 0;
                break;

            case MdUtlAdmPrmGrRN::$FREQUENCIA_SEMANAL:

                $dataAtualFormatada = implode('-',$dataAtualFormatada);
                $dataPrimeiroDiaSemana = $dataAtualFormatada;

                $dataPrimeiroDiaSemana = $MdUtlPrazoRN->retornaPrimeiroDiaSemana($dataPrimeiroDiaSemana);
                $dtFinal   = date('d/m/Y', strtotime('+6 days', strtotime($dataPrimeiroDiaSemana)));

                $arrDataPrimeiroDiaSemana = explode('-',$dataPrimeiroDiaSemana);
	            $dtInicial = implode('/', $arrDataPrimeiroDiaSemana);
                $diasUteis = $MdUtlPrazoRN->retornaQtdDiaUtil($dtInicial, $dtFinal, false, $isUsarFeriadoSEI);
                $numFrequencia = $diasUteis;
                break;

            case MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL:
                $numDias = InfraData::obterUltimoDiaMes($mesAtual, $anoAtual);
                $dtInicial = '01/' . $mesAtual . '/' . $anoAtual;
                $dtFinal   = $numDias . '/' . $mesAtual . '/' . $anoAtual;

                $diasUteis = $MdUtlPrazoRN->retornaQtdDiaUtil($dtInicial, $dtFinal, false, $isUsarFeriadoSEI);
                $numFrequencia = $diasUteis;
                break;
        }

        return ['numFrequencia' => $numFrequencia , 'dtInicial' => $dtInicial , 'dtFinal' => $dtFinal];
    }

    public function validaUsuarioIsChefiaImediataConectado( $arrParams ){
        $arrIdsPrmGr = array_key_exists( 0 , $arrParams ) ? $arrParams[0] : null;
        $IdUsuario   = array_key_exists( 1 , $arrParams ) ? $arrParams[1] : SessaoSEI::getInstance()->getNumIdUsuario();

        $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
        $objMdUtlAdmPrmGrUsuRN  = new MdUtlAdmPrmGrUsuRN();

	      $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr( $arrIdsPrmGr , InfraDTO::$OPER_IN );
	      $objMdUtlAdmPrmGrUsuDTO->setNumIdUsuario( $IdUsuario );
	      $objMdUtlAdmPrmGrUsuDTO->setStrSinChefiaImediata( 'S' );
        #$objMdUtlAdmHistPrmGrUsuDTO->setDthFinal( null );
	      $objMdUtlAdmPrmGrUsuDTO->retStrSinChefiaImediata();
        #$objMdUtlAdmHistPrmGrUsuDTO->retDthInicial();
	      $objMdUtlAdmPrmGrUsuDTO->retDthInicioParticipacao();

        if ( $objMdUtlAdmPrmGrUsuRN->contar( $objMdUtlAdmPrmGrUsuDTO ) > 0 ){
            $arrLista = $objMdUtlAdmPrmGrUsuRN->listar( $objMdUtlAdmPrmGrUsuDTO );
            foreach ( $arrLista as $item ){
                if( $item->getStrSinChefiaImediata() == 'S' ){
                    $item->setDthInicioParticipacao( explode( ' ' , $item->getDthInicioParticipacao() )[0] );
                    return $item;
                }
            }
        }
        return null;
    }

    /*
     * Chefia Titular = 1 ; Substituto = 2
     * */
    protected function trataUsuariosChefiaImediataControlado( $arrUsuarios ){
    	try{
		    $bolTemIntegracao = false;
		    $dadosChefia      = null;
		    $arrObjIntegracao = (new MdUtlAdmIntegracaoRN())->obterConfigIntegracaoPorFuncionalidade(MdUtlAdmIntegracaoRN::$CHEFIA);

		    // verifica se o serviço esta cadastrado e ativo
		    if (!empty($arrObjIntegracao) && $arrObjIntegracao['integracao']->getStrTipoIntegracao() == 'RE') {
			    $arrParams = ['loginUsuario' => ''];
			    $arrParams = ['parametros' => MdUtlAdmIntegracaoINT::montaParametrosEntrada($arrObjIntegracao,$arrParams)];
			    $dadosChefia      = MdUtlAdmIntegracaoINT::executarConsultaREST( $arrObjIntegracao , $arrParams );
			    $bolTemIntegracao = true;
			    $arrIdentificador = MdUtlAdmIntegracaoINT::montaParametrosSaida($arrObjIntegracao['parametros-integracao']);

			    foreach ( $arrUsuarios as $usuario ) {
				    $arrDadosChefia = null;
				    foreach ( $dadosChefia as $chefia ) {
					    if ( $usuario['siglaUsuario'] == $chefia->{$arrIdentificador['loginUsuario']} ) {
						    $arrDadosChefia = [$chefia];  break;
					    }
				    }
				    $this->atualizarInfoChefiaImediata( $arrDadosChefia , $usuario );
			    }
		    }
		    return $bolTemIntegracao;
	    } catch ( Exception $e ) {
    		$func = MdUtlAdmIntegracaoRN::$STR_CHEFIA;
    		$msg  = "Não foi possível estabelecer a integração com o Sistema de Recursos Humanos para atualizar a indicação de 
    		Chefia Imediata dos Membros Participantes deste Controle de Desempenho.";
    		$msg .= "\n\n" . $func;
		    PaginaSEI::getInstance()->adicionarMensagem( $msg , InfraPagina::$TIPO_MSG_AVISO );
	    }
    }

    /*
     * Salva se o usuario eh chefe imediato ou deixou de ser
     */
    public function atualizarInfoChefiaImediata( $dadosREST , $usuario , &$isAtualizado = false){
    	try {
		    $isChefeImediato        = false;
		    $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();

		    if (!empty($dadosREST)) {
			    if ($usuario['chefiaImediata'] == 'N') {
			    	//caso tenha alguma atulizacao no registro, consulta a parametrizacao atual do usuario
				    $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGrUsu($usuario['idPrmGrUsu']);
				    $objMdUtlAdmPrmGrUsuDTO->retTodos();
				    $objMdUtlAdmPrmGrUsuDTO = $this->consultar( $objMdUtlAdmPrmGrUsuDTO );

				    $objMdUtlAdmPrmGrUsuDTO->setStrSinChefiaImediata('S');
			    }
		    } else {
			    // nao eh chefe imediato, mas esta salvo na parametrizacao como chefe imediato, entao atualiza usuario
			    if (!$isChefeImediato && $usuario['chefiaImediata'] == 'S') {
				    //caso tenha alguma atulizacao no registro, consulta a parametrizacao atual do usuario
				    $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGrUsu($usuario['idPrmGrUsu']);
				    $objMdUtlAdmPrmGrUsuDTO->retTodos();
				    $objMdUtlAdmPrmGrUsuDTO = $this->consultar( $objMdUtlAdmPrmGrUsuDTO );

				    $objMdUtlAdmPrmGrUsuDTO->setStrSinChefiaImediata('N');
				    $isAtualizado = true;
			    }
		    }

		    // executa update para marcar ou desmarcar chefia imediata
		    if ( $objMdUtlAdmPrmGrUsuDTO->isSetAtributo('IdMdUtlAdmPrmGrUsu') ) {
			    $objMdUtlAdmPrmGrRN = new MdUtlAdmPrmGrRN();
			    //registra a data final antes da atualizacao
			    $objMdUtlAdmPrmGrRN->_cadastrarDataFinalUsuarios($usuario['idPrmGr'], [$usuario['idUsuario']]);

			    //altera dados da parametrizacao do usuario
			    $this->alterar($objMdUtlAdmPrmGrUsuDTO);

			    //replica os dados da parametrizacao do usuario no historico
			    $objTpCtrDTO = new MdUtlAdmTpCtrlDesempDTO();
			    $objTpCtrDTO->setNumIdMdUtlAdmPrmGr($usuario['idPrmGr']);
			    $objTpCtrDTO->retNumIdMdUtlAdmTpCtrlDesemp();
			    $objTpCtrDTO = ( new MdUtlAdmTpCtrlDesempRN() )->consultar($objTpCtrDTO);

			    $objMdUtlAdmPrmGrRN->_cadastrarNovoUsuarioHistorico([$objMdUtlAdmPrmGrUsuDTO],$objTpCtrDTO->getNumIdMdUtlAdmTpCtrlDesemp());
		    }

	    }catch(Exception $e){
		    throw new InfraException('Erro na execução do Update da Chefia Imediata.',$e);
	    }
    }

	/**
	 * busca pelo id do usuario se é chefia imediata ou não
	 */
    public function buscaUsuarioChefiaImediataControlado( $loginUsuario = null ){

    	if( is_null( $loginUsuario ) ) throw new InfraException('O Login do Usuário é obrigatório para realizar a consulta no WebService.');

    	// retorna os dados da integracao + header + parametros de entrada/saida
    	$arrObjMdUtlAdmIntegracao = ( new MdUtlAdmIntegracaoRN() )
		    ->obterConfigIntegracaoPorFuncionalidade( MdUtlAdmIntegracaoRN::$CHEFIA );

    	if( empty( $arrObjMdUtlAdmIntegracao ) )
    		return [
    			'comIntegracao' => false,
			    'retorno'       => 'Não obteve dados de retorno do mapeamento da Integração.'
		    ];

	    $arrParams = ['loginUsuario' => $loginUsuario];
	    $arrParams = ['parametros'   =>
		                  MdUtlAdmIntegracaoINT::montaParametrosEntrada(
		                  	$arrObjMdUtlAdmIntegracao,
			                  $arrParams
		                  )];

	    return [
	    	'comIntegracao' => true,
	    	'retorno'       => MdUtlAdmIntegracaoINT::executarConsultaREST( $arrObjMdUtlAdmIntegracao , $arrParams['parametros'] )
	    ];
    }

    public function validaRegraParticipacaoEmOutroTpCtrlControlado( $post ){
			if( empty( $post ) ) throw new InfraException("Não foi passado nenhum parâmetro para executar a validação.");

	    if( empty( $post['id_usuario'] ) ) throw new InfraException('O Id do Usuário é obrigatório para realizar a validação em outro Tipo de Controle.');

	    // retorna o id das parametrizacoes que estejam ativas
	    $objMdUtlAdmTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
	    $objMdUtlAdmTpCtrlDTO->setStrSinAtivo('S');
	    $objMdUtlAdmTpCtrlDTO->retNumIdMdUtlAdmPrmGr();
	    $objMdUtlAdmTpCtrlDTO->retStrNome();

	    $arrObjsTpCtrlDTO      = ( new MdUtlAdmTpCtrlDesempRN() )->listar( $objMdUtlAdmTpCtrlDTO );
	    $arrIdsPrmGr           = InfraArray::converterArrInfraDTO( $arrObjsTpCtrlDTO , 'IdMdUtlAdmPrmGr' );
	    $arrIdsNomeTpCtrlPrmGr = InfraArray::converterArrInfraDTO( $arrObjsTpCtrlDTO , 'Nome' , 'IdMdUtlAdmPrmGr' );

	    $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
	    $objMdUtlAdmPrmGrUsuDTO->setNumIdUsuario( $post['id_usuario'] );
	    $objMdUtlAdmPrmGrUsuDTO->adicionarCriterio(
	    	['IdMdUtlAdmPrmGr' , 'IdMdUtlAdmPrmGr'],
		    [InfraDTO::$OPER_IN , InfraDTO::$OPER_DIFERENTE],
		    [$arrIdsPrmGr , $post['id_prm_gr']],
		    [InfraDTO::$OPER_LOGICO_AND]
	    );

	    $objMdUtlAdmPrmGrUsuDTO->retTodos();

	    if ( $this->contar( $objMdUtlAdmPrmGrUsuDTO ) > 0 ) {
		    $arrObjsMdUtlAdmPrmGrUsuDTO = $this->listar( $objMdUtlAdmPrmGrUsuDTO );

		    // valida se tem algum registro do usuario a ser cadastrado com tempo integral
		    $sumTotalFatorReducao = 0;
		    $strRegraFator        = '';

		    foreach ( $arrObjsMdUtlAdmPrmGrUsuDTO as $item ) {
		    	$nmTpCtrl = $arrIdsNomeTpCtrlPrmGr[$item->getNumIdMdUtlAdmPrmGr()];

		      if ( $item->getStrStaTipoJornada() == self::$TIPOJORNADA_INTEGRAL ) {
		      	  // retorna o nome do tipo de controle que o usuario já é cadastrado
							return [ 'msg' => $this->gerenciar_msg( 'integral' , $nmTpCtrl ) ];
		      } else {
			      $strRegraFator .= "$nmTpCtrl - Fator de Presença da Jornada: {$item->getNumFatorReducaoJornada()}\n";
			      $sumTotalFatorReducao += $item->getNumFatorReducaoJornada();
		      }
		    }

		    if ( $sumTotalFatorReducao + (int) $post['fator_jornada_red'] > 100 )
		    	return [ 'msg' => $this->gerenciar_msg('fator' , $strRegraFator) ];
	    }
	    return true;
    }

    private function gerenciar_msg( $qual_msg , $strNomeTpCtrl = null ){
    	switch ( $qual_msg ){
		    case 'integral':
		    	return "O Membro Participante selecionado está vinculado ao Controle de Desempenho: $strNomeTpCtrl, com o Tipo de Jornada Integral.\nAntes de vincular este Membro Participante a outro Tipo de Controle, altere o Tipo de Jornada para Reduzido e defina o Fator de Presença da Jornada.\nA soma do Fator de Presença da Jornada do mesmo Membro Participante não pode ser superior a 100.";
		    	break;

		    case 'fator':
		    	return "A soma do Fator de Presença da Jornada do mesmo Membro Participante em todos os Controles de Desempenho que esteja vinculado não pode ser superior a 100%.\nAntes de vincular este Membro Participante, revise a parametrização dele nos Controles de Desempenho abaixo:\n\n$strNomeTpCtrl";
		    	break;

		    default:
		    	break;
	    }
    }
/*
    public function trataFeriadosMembrosControlado( $arrUsuarios , $idPrmGr ){
	    $objMdUtlAdmIntegracaoRN = new MdUtlAdmIntegracaoRN();

	    // retorna os dados da integracao + header + parametros de entrada/saida
	    $arrObjMdUtlAdmIntegracao = $objMdUtlAdmIntegracaoRN->obterConfigIntegracaoPorFuncionalidade( MdUtlAdmIntegracaoRN::$LISTAR_AUSENCIA );

	    // atualiza, se necessario, dados da carga do membro somente das ausencias relacionadas a Feriados cadastrados no SEI
	    if ( empty( $arrObjMdUtlAdmIntegracao ) ) {
	    	$dti = date('d/m/Y' , strtotime('-6 months'));
	    	$dtf = date('d/m/Y');
		    $arrFeriados = ( new MdUtlPrazoRN() )->recuperarFeriados( $dti , $dtf );
		    $rs = empty($arrFeriados) ? 0 : count($arrFeriados);
	    } else {
	    	$objMdUtlAdmPrmGrUsuCargaRN = new MdUtlAdmPrmGrUsuCargaRN();
		    $objMdUtlAdmPrmGrUsuCargaRN->gerenciarCargaHorariaMembroFeriado( , )
	    }
    }
*/
}
