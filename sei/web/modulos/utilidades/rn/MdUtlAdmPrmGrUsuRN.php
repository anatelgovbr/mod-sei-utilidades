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

        if(is_array($objMdUtlAdmPrmGrUsuDTO)){
            $sql = $objMdUtlAdmPrmGrUsuBD->listar($objMdUtlAdmPrmGrUsuDTO[0], true);
                print_r($sql);exit;
        }
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


      $mdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
      $mdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($idMdUtlAdmPrmGr);
      $mdUtlAdmPrmGrUsuDTO->retTodos(true);
      $mdUtlAdmPrmGrUsuDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_DESC);
      $mdUtlAdmPrmGrUsuDTO->setOrdNumIdMdUtlAdmPrmGrUsu(InfraDTO::$TIPO_ORDENACAO_DESC);
      $mdUtlAdmPrmGrUsu = $this->listar($mdUtlAdmPrmGrUsuDTO);

      $arrPresenca = array( MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_DIFERENCIADO =>'Diferenciado',
          MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_PRESENCIAL => 'Presencial',
          MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_TELETRABALHO => 'Teletrabalho' );

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

          if($dadosUsuParticipante->getStrStaTipoPresenca() == self::$TP_PRESENCA_DIFERENCIADO) {
              $UsuarioParticipante[] = $dadosUsuParticipante->getNumFatorDesempDiferenciado().'%';
          }else{
              $UsuarioParticipante[] = $dadosUsuParticipante->getNumFatorDesempDiferenciado();
          }

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

          $arrUsuarioParticipante[]= $UsuarioParticipante;

      }

      $arrTbUsuarioParticipante = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrUsuarioParticipante);

      return array('itensTabela'=>$arrUsuarioParticipante,'qtdUsuario'=>count($arrUsuarioParticipante));
  }

  public function excluirUsuarioParticipante($idsUsuariosExcl, $idsVinculadosBd){

      $arrGrUsuDTO = array();
      foreach($idsUsuariosExcl as $idUsuario){
              $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
              $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGrUsu($idsVinculadosBd[$idUsuario]);
              $arrGrUsuDTO [] = $objMdUtlAdmPrmGrUsuDTO;
      }

      $this->excluir($arrGrUsuDTO);
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
        $numCargaPadrao        = array_key_exists(2, $arrObj) ? $arrObj[2] : null;
        $numPercentualTele     = array_key_exists(3, $arrObj) ? $arrObj[3] : null;
        $diasUteis             = array_key_exists(4, $arrObj) ? $arrObj[4] : null;

        $fatorReducaoFornada = 0;
        $fatorDesempUsu      = 0;
        
        $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();

        $objMdUtlAdmPrmGrUsuDTO->setNumIdUsuario($idUsuarioParticipante);
        $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($idParam);
        $objMdUtlAdmPrmGrUsuDTO->retStrStaTipoPresenca();
        $objMdUtlAdmPrmGrUsuDTO->retNumFatorDesempDiferenciado();
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

    protected function getDiasUteisNoPeriodoConectado($staFrequencia){

        $MdUtlPrazoRN = new MdUtlPrazoRN();

        $dataAtual = InfraData::getStrDataAtual();
        $dataAtualFormatada = explode('/', $dataAtual);
        $diaAtual      = $dataAtualFormatada[0];
        $mesAtual      = $dataAtualFormatada[1];
        $anoAtual      = $dataAtualFormatada[2];

        switch ($staFrequencia){
            case MdUtlAdmPrmGrRN::$FREQUENCIA_DIARIO:
                $dtInicial = $diaAtual . '/' . $mesAtual . '/' . $anoAtual;
                $dtFinal   = $diaAtual . '/' . $mesAtual . '/' . $anoAtual;
                $diaUtil = $MdUtlPrazoRN->verificaDiaUtil($dtInicial, $dtFinal, false);

                $numFrequencia = $diaUtil ? 1 : 0;
                break;

            case MdUtlAdmPrmGrRN::$FREQUENCIA_SEMANAL:

                $dataAtualFormatada = implode('-',$dataAtualFormatada);
                $dataPrimeiroDiaSemana = $dataAtualFormatada;

                $dataPrimeiroDiaSemana = $MdUtlPrazoRN->retornaPrimeiroDiaSemana($dataPrimeiroDiaSemana);
                $dtFinalSemana   = date('d/m/Y', strtotime('+6 days', strtotime($dataPrimeiroDiaSemana)));

                $arrDataPrimeiroDiaSemana = explode('-',$dataPrimeiroDiaSemana);
                $dtInicialSemana = implode('/', $arrDataPrimeiroDiaSemana);
                $diasUteis = $MdUtlPrazoRN->retornaQtdDiaUtil($dtInicialSemana, $dtFinalSemana, false);

                $numFrequencia = $diasUteis;
                break;

            case MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL:
                $numDias = InfraData::obterUltimoDiaMes($mesAtual, $anoAtual);
                $dtInicial = '01' . '/' . $mesAtual . '/' . $anoAtual;
                $dtFinal   = $numDias . '/' . $mesAtual . '/' . $anoAtual;

                $diasUteis = $MdUtlPrazoRN->retornaQtdDiaUtil($dtInicial, $dtFinal, false);
                $numFrequencia = $diasUteis;
                break;
        }

        return $numFrequencia;
    }
}
