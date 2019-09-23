<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/07/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmPrmGrRN extends InfraRN {

    //Frenquencia de distribuição
  public static $FREQUENCIA_DIARIO  = 'D';
  public static $STR_FREQUENCIA_DIARIO  = 'Diário';
    
  public static $FREQUENCIA_SEMANAL = 'S';
  public static $STR_FREQUENCIA_SEMANAL = 'Semanal';
    
  public static $FREQUENCIA_MENSAL  = 'M';
  public static $STR_FREQUENCIA_MENSAL  = 'Mensal';

    //Retorno para última Fila
  public static $RETORNO_SIM = 'S';
  public static $RETORNO_NAO = 'N';


  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  
  private function validarNumCargaPadrao(MdUtlAdmPrmGrDTO $objMdUtlAdmPrmGrDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmPrmGrDTO->getNumCargaPadrao())){
      $objMdUtlAdmPrmGrDTO->setNumCargaPadrao(null);
    }
  }

  private function validarStrStaFrequencia(MdUtlAdmPrmGrDTO $objMdUtlAdmPrmGrDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmPrmGrDTO->getStrStaFrequencia())){
      $objMdUtlAdmPrmGrDTO->setStrStaFrequencia(null);
    }
  }

  private function validarDblPercentualTeletrabalho(MdUtlAdmPrmGrDTO $objMdUtlAdmPrmGrDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmPrmGrDTO->getDblPercentualTeletrabalho())){
      $objMdUtlAdmPrmGrDTO->setDblPercentualTeletrabalho(null);
    }
  }

  private function validarNumIdMdUtlAdmFila(MdUtlAdmPrmGrDTO $objMdUtlAdmPrmGrDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmPrmGrDTO->getNumIdMdUtlAdmFila())){
      $objMdUtlAdmPrmGrDTO->setNumIdMdUtlAdmFila(null);
    }
  }

  private function validarStrSinRetornoUltFila(MdUtlAdmPrmGrDTO $objMdUtlAdmPrmGrDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmPrmGrDTO->getStrSinRetornoUltFila())){
      $objInfraException->adicionarValidacao('Sinalizador de SinRetornoUltFila não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdUtlAdmPrmGrDTO->getStrSinRetornoUltFila())){
        $objInfraException->adicionarValidacao('Sinalizador de SinRetornoUltFila inválid.');
      }
    }
  }

  protected function cadastrarControlado(MdUtlAdmPrmGrDTO $objMdUtlAdmPrmGrDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_prm_gr_cadastrar',__METHOD__,$objMdUtlAdmPrmGrDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumCargaPadrao($objMdUtlAdmPrmGrDTO, $objInfraException);
      $this->validarStrStaFrequencia($objMdUtlAdmPrmGrDTO, $objInfraException);
      $this->validarDblPercentualTeletrabalho($objMdUtlAdmPrmGrDTO, $objInfraException);
      //$this->validarNumIdMdUtlAdmFila($objMdUtlAdmPrmGrDTO, $objInfraException);
      //$this->validarStrSinRetornoUltFila($objMdUtlAdmPrmGrDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrBD = new MdUtlAdmPrmGrBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmPrmGrBD->cadastrar($objMdUtlAdmPrmGrDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Parâmetro Geral.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmPrmGrDTO $objMdUtlAdmPrmGrDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_prm_gr_alterar',__METHOD__, $objMdUtlAdmPrmGrDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmPrmGrDTO->isSetNumCargaPadrao()){
        $this->validarNumCargaPadrao($objMdUtlAdmPrmGrDTO, $objInfraException);
      }
      if ($objMdUtlAdmPrmGrDTO->isSetStrStaFrequencia()){
        $this->validarStrStaFrequencia($objMdUtlAdmPrmGrDTO, $objInfraException);
      }
      if ($objMdUtlAdmPrmGrDTO->isSetDblPercentualTeletrabalho()){
        $this->validarDblPercentualTeletrabalho($objMdUtlAdmPrmGrDTO, $objInfraException);
      }
      if ($objMdUtlAdmPrmGrDTO->isSetNumIdMdUtlAdmFila()){
        $this->validarNumIdMdUtlAdmFila($objMdUtlAdmPrmGrDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrBD = new MdUtlAdmPrmGrBD($this->getObjInfraIBanco());
      $objMdUtlAdmPrmGrBD->alterar($objMdUtlAdmPrmGrDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Parâmetro Geral.',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmPrmGrDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_prm_gr_excluir', __METHOD__, $arrObjMdUtlAdmPrmGrDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrBD = new MdUtlAdmPrmGrBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmPrmGrDTO);$i++){
        $objMdUtlAdmPrmGrBD->excluir($arrObjMdUtlAdmPrmGrDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Parâmetro Geral.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmPrmGrDTO $objMdUtlAdmPrmGrDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_gr_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrBD = new MdUtlAdmPrmGrBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmPrmGrBD->consultar($objMdUtlAdmPrmGrDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Parâmetro Geral.',$e);
    }
  }

  protected function listarConectado(MdUtlAdmPrmGrDTO $objMdUtlAdmPrmGrDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_gr_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmPrmGrBD = new MdUtlAdmPrmGrBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmPrmGrBD->listar($objMdUtlAdmPrmGrDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Parâmetros Gerais.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmPrmGrDTO $objMdUtlAdmPrmGrDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_prm_gr_listar');

      $objMdUtlAdmPrmGrBD = new MdUtlAdmPrmGrBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmPrmGrBD->contar($objMdUtlAdmPrmGrDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Parâmetros Gerais.',$e);
    }
  }

  private function _retornaFormatadoArrLupa($arr){
      foreach($arr as $objArr){
          $arrRetorno[] = current($objArr);
      }
      return $arrRetorno;
  }

    private function _controlarStatusUtilidadesProcessos($arrIdsTpProcessoOrigin, $arrIdsTpProcesso, $idTipoControleUtl)
    {
        $objMdUtlAdmTpCtrlUsuRN = new MdUtlAdmRelTpCtrlDesempUsuRN();

        $arrIdsUnidades = $objMdUtlAdmTpCtrlUsuRN->getUnidadesTipoControle($idTipoControleUtl);

        if (!is_null($arrIdsTpProcessoOrigin)) {
            $arrIdsTpProcessoRemovido = array_diff($arrIdsTpProcessoOrigin, $arrIdsTpProcesso);

            //Desativar o antigo relacionamento de Utilidades.
            if (count($arrIdsTpProcessoRemovido) > 0) {
                $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
                if(!is_null($arrIdsUnidades)) {
                    $objMdUtlControleDsmpRN->desativarControleDsmpObjs(array($arrIdsTpProcessoRemovido, $arrIdsUnidades));
                }
            }
        }
    }

  private function _cadastrarAlterarParametrizacao($idMdUtlAdmPrmGr, $objMdUtlAdmPrmGrDTO, $objMdUtlAdmTpCtrlDTO){
      $objMdUtlAdmTpCtrlRN     = new MdUtlAdmTpCtrlDesempRN();

      if ($idMdUtlAdmPrmGr > 0) {
          $objMdUtlAdmPrmGrDTO = $this->alterar($objMdUtlAdmPrmGrDTO);
      } else {
          $objMdUtlAdmPrmGrDTO = $this->cadastrar($objMdUtlAdmPrmGrDTO);
          $idMdUtlAdmPrmGr = $objMdUtlAdmPrmGrDTO->getNumIdMdUtlAdmPrmGr();
          $objMdUtlAdmTpCtrlDTO->setNumIdMdUtlAdmPrmGr($idMdUtlAdmPrmGr);
          $objMdUtlAdmTpCtrlRN->alterar($objMdUtlAdmTpCtrlDTO);
      }

      return $idMdUtlAdmPrmGr;
  }

  private function _cadastrarRelParametrizacaoTpProcesso($bolAlterar, $idMdUtlAdmPrmGr, $arrTpProcesso){
      $mdUtlAdmRelPrmGrProcRN = new MdUtlAdmRelPrmGrProcRN();

      if ($bolAlterar) {

          $mdUtlAdmRelPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO();
          $mdUtlAdmRelPrmGrProcDTO->setNumIdMdUtlAdmParamGr($idMdUtlAdmPrmGr);
          $mdUtlAdmRelPrmGrProcDTO->retTodos();

          $count = $mdUtlAdmRelPrmGrProcRN->contar($mdUtlAdmRelPrmGrProcDTO);
          if($count > 0) {
              $objsAlteracaoDTO = $mdUtlAdmRelPrmGrProcRN->listar($mdUtlAdmRelPrmGrProcDTO);
              $mdUtlAdmRelPrmGrProcRN->excluir($objsAlteracaoDTO);
          }

      }


      // Cadastros dos tipos de Processos
      for ($i = 0; $i < count($arrTpProcesso); $i++) {

          $mdUtlAdmRelPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO();
          $mdUtlAdmRelPrmGrProcDTO->setNumIdMdUtlAdmParamGr($idMdUtlAdmPrmGr);
          $mdUtlAdmRelPrmGrProcDTO->setNumIdTipoProcedimento($arrTpProcesso[$i][0]);
          $mdUtlAdmRelPrmGrProcRN->cadastrar($mdUtlAdmRelPrmGrProcDTO);

      }
  }

  private function _cadastrarRelParametrizacaoUsuario($bolAlterar, $arrUsuarioPart, $idMdUtlAdmPrmGr){
      $arrUsuarioPartRemovido  = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTbUsuarioRemove']);

      $mdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();

      if (!$bolAlterar) {
          // Cadastros dos Usuarios participantes
          for ($i = 0; $i < count($arrUsuarioPart); $i++) {
              $mdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
              $mdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($idMdUtlAdmPrmGr);
              $mdUtlAdmPrmGrUsuDTO->setNumIdUsuario($arrUsuarioPart[$i][0]);
              $mdUtlAdmPrmGrUsuDTO->setStrStaTipoPresenca($arrUsuarioPart[$i][3]);

              if ($arrUsuarioPart[$i][3] != '') {
                  $fatorDesempDif = str_replace("%", "", $arrUsuarioPart[$i][4]);
                  $mdUtlAdmPrmGrUsuDTO->setNumFatorDesempDiferenciado($fatorDesempDif);
              }

              $mdUtlAdmPrmGrUsuDTO->setStrStaTipoJornada($arrUsuarioPart[$i][6]);

              if ($arrUsuarioPart[$i][5] != '') {
                  $fatorReducaoJornada = str_replace("%", "", $arrUsuarioPart[$i][7]);
                  $mdUtlAdmPrmGrUsuDTO->setNumFatorReducaoJornada($fatorReducaoJornada);
              }

              $mdUtlAdmPrmGrUsuRN->cadastrar($mdUtlAdmPrmGrUsuDTO);
          }
      }else{

          $arrUsuarioNovo = array();
          $arrUsuarioAlterar = array();

          for ($i = 0; $i < count($arrUsuarioPart); $i++) {
              if ($arrUsuarioPart[$i][8] == 'undefined' || $arrUsuarioPart[$i][8] == 0) {
                  $arrUsuarioNovo[] = $arrUsuarioPart[$i];
              } else {
                  $arrUsuarioAlterar[] = $arrUsuarioPart[$i];
              }
          }

          //remove os usuarios participantes que não tem nenhum vinculo com a fila
          if (count($arrUsuarioPartRemovido) > 0) {
              $mdUtlAdmPrmGrUsuRN->excluirUsuarioParticipante($arrUsuarioPartRemovido);
          }

          if (count($arrUsuarioNovo) > 0) {
              // Cadastros dos Usuarios participantes
              for ($i = 0; $i < count($arrUsuarioNovo); $i++) {
                  $mdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
                  $mdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($idMdUtlAdmPrmGr);
                  $mdUtlAdmPrmGrUsuDTO->setNumIdUsuario($arrUsuarioNovo[$i][0]);
                  $mdUtlAdmPrmGrUsuDTO->setStrStaTipoPresenca($arrUsuarioNovo[$i][3]);

                  if ($arrUsuarioPart[$i][4] != '') {
                      $fatorDesempDif = str_replace("%", "", $arrUsuarioNovo[$i][4]);
                      $mdUtlAdmPrmGrUsuDTO->setNumFatorDesempDiferenciado($fatorDesempDif);
                  }

                  $mdUtlAdmPrmGrUsuDTO->setStrStaTipoJornada($arrUsuarioNovo[$i][6]);

                  if ($arrUsuarioPart[$i][5] != '') {
                      $fatorReducaoJornada = str_replace("%", "", $arrUsuarioNovo[$i][7]);
                      $mdUtlAdmPrmGrUsuDTO->setNumFatorReducaoJornada($fatorReducaoJornada);
                  }

                  $mdUtlAdmPrmGrUsuRN->cadastrar($mdUtlAdmPrmGrUsuDTO);
              }
          }

          if (count($arrUsuarioAlterar) > 0) {
              // Alterar os Usuarios participantes
              for ($i = 0; $i < count($arrUsuarioAlterar); $i++) {
                  $mdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
                  $mdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($idMdUtlAdmPrmGr);
                  $mdUtlAdmPrmGrUsuDTO->setNumIdUsuario($arrUsuarioAlterar[$i][0]);
                  $mdUtlAdmPrmGrUsuDTO->setStrStaTipoPresenca($arrUsuarioAlterar[$i][3]);

                  if ($arrUsuarioPart[$i][4] != '') {
                      $fatorDesempDif = str_replace("%", "", $arrUsuarioAlterar[$i][4]);
                      $mdUtlAdmPrmGrUsuDTO->setNumFatorDesempDiferenciado($fatorDesempDif);
                  }

                  $mdUtlAdmPrmGrUsuDTO->setStrStaTipoJornada($arrUsuarioAlterar[$i][6]);

                  if ($arrUsuarioPart[$i][7] != '') {
                      $fatorReducaoJornada = str_replace("%", "", $arrUsuarioAlterar[$i][7]);
                      $mdUtlAdmPrmGrUsuDTO->setNumFatorReducaoJornada($fatorReducaoJornada);
                  }
                  $mdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGrUsu($arrUsuarioAlterar[$i][8]);

                  $mdUtlAdmPrmGrUsuRN->alterar($mdUtlAdmPrmGrUsuDTO);
              }
          }

      }


  }

  public function cadastrarParametrizacao($idMdUtlAdmPrmGr, $idTipoControleUtl, $objMdUtlAdmPrmGrDTO, $objMdUtlAdmTpCtrlDTO){
      //Get Vars Iniciais
      $bolAlterar              = $idMdUtlAdmPrmGr > 0;
      $isValido                = true;
      $arrUsuarioPart          = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTbUsuario']);
      $arrTpProcesso           = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTpProcesso']);
      $mdUtlAdmRelPrmGrProcRN  = new MdUtlAdmRelPrmGrProcRN();
      $strLupaTpProcesso       = $mdUtlAdmRelPrmGrProcRN->montarArrTpProcesso($idMdUtlAdmPrmGr);
      $arrLupaTpProcessoOrigin = PaginaSEI::getInstance()->getArrItensTabelaDinamica($strLupaTpProcesso);

      $arrIdsTpProcesso       = array();
      $arrIdsTpProcessoOrigin = array();

      $arrIdsTpProcesso       = $this->_retornaFormatadoArrLupa($arrTpProcesso);
      $arrIdsTpProcessoOrigin = $this->_retornaFormatadoArrLupa($arrLupaTpProcessoOrigin);

      $idMdUtlAdmPrmGr = $this->_cadastrarAlterarParametrizacao($idMdUtlAdmPrmGr, $objMdUtlAdmPrmGrDTO, $objMdUtlAdmTpCtrlDTO);
      $this->_cadastrarRelParametrizacaoTpProcesso($bolAlterar, $idMdUtlAdmPrmGr, $arrTpProcesso);
      $this->_cadastrarRelParametrizacaoUsuario($bolAlterar, $arrUsuarioPart, $idMdUtlAdmPrmGr);


    if($bolAlterar) {
        //$this->_controlarStatusUtilidadesProcessos($arrIdsTpProcessoOrigin, $arrIdsTpProcesso, $idTipoControleUtl);
    }

  }

  protected function verificaParametrizacaoTpControleConectado($arrDados){
        $isValido = false;

        $mdUtlAdmTpCtrlDesempRN = new MdUtlAdmTpCtrlDesempRN();

        $idTipoControle = array_key_exists(0, $arrDados) ? $arrDados[0] : null;
        $idTpProcesso   = array_key_exists(1, $arrDados) ? $arrDados[1] : null;

        $idParametro = $mdUtlAdmTpCtrlDesempRN->verificaIdParametrizacao($idTipoControle);

        if(!is_null($idParametro)){
            $mdUtlAdmRelPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO();
            $mdUtlAdmRelPrmGrProcDTO->setNumIdMdUtlAdmParamGr($idParametro);
            $mdUtlAdmRelPrmGrProcDTO->setNumIdTipoProcedimento($idTpProcesso);

            $mdUtlAdmRelPrmGrProcRN = new MdUtlAdmRelPrmGrProcRN();
            $count = $mdUtlAdmRelPrmGrProcRN->contar($mdUtlAdmRelPrmGrProcDTO);
            $isValido = $count > 0;

        }

        return $isValido;

    }

  protected function verificaTipoProcessoParametrizadoConectado($arrParams){
      $idTipoProcedimento = $arrParams[0];
      $idTipoControle     = $arrParams[1];

      $objMdUtlAdmTpCtrlDesempRN = new MdUtlAdmTpCtrlDesempRN();
      $idParam = $objMdUtlAdmTpCtrlDesempRN->verificaIdParametrizacao($idTipoControle);

      if(!is_null($idParam)) {
          $objMdUtlPrmGrTpProcRN  = new MdUtlAdmRelPrmGrProcRN();
          $objMdUtlPrmGrTpProcDTO = new MdUtlAdmRelPrmGrProcDTO();
          $objMdUtlPrmGrTpProcDTO->setNumIdTipoProcedimento($idTipoProcedimento);
          $objMdUtlPrmGrTpProcDTO->setNumIdMdUtlAdmParamGr($idParam);

          return ($objMdUtlPrmGrTpProcRN->contar($objMdUtlPrmGrTpProcDTO) > 0);
      }

      return false;
  }
}
