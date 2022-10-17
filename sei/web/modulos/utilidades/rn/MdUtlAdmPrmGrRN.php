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

    public static $FREQUENCIA_INICIO_DIARIO  = '1';
    public static $STR_FREQUENCIA_INICIO_DIARIO  = '00h00';
    public static $STR_FREQUENCIA_FIM_DIARIO  = '23h59';

    public static $FREQUENCIA_SEMANAL = 'S';
    public static $STR_FREQUENCIA_SEMANAL = 'Semanal';

    public static $FREQUENCIA_INICIO_SEMANAL_DOMINGO = '2';
    public static $STR_FREQUENCIA_INICIO_SEMANAL_DOMINGO = 'Domingo 00h00';
    public static $STR_FREQUENCIA_FIM_SEMANAL_DOMINGO = 'Sábado 23h59';

    public static $FREQUENCIA_INICIO_SEMANAL_SEGUNDA = '3';
    public static $STR_FREQUENCIA_INICIO_SEMANAL_SEGUNDA = 'Segunda 00h00';
    public static $STR_FREQUENCIA_FIM_SEMANAL_SEGUNDA = 'Domingo 23h59';

    public static $FREQUENCIA_MENSAL  = 'M';
    public static $STR_FREQUENCIA_MENSAL  = 'Mensal';

    public static $FREQUENCIA_MENSAL_PRIMEIRO_DIA_MES = '4';
    public static $STR_FREQUENCIA_MENSAL_PRIMEIRO_DIA_MES = 'Primeiro dia do mês 00h00';
    public static $STR_FREQUENCIA_MENSAL_ULTIMO_DIA_MES = 'Último dia do mês 23h59';

    public static $FREQUENCIA_MENSAL_PRIMEIRO_DIA_UTIL_MES = '5';
    public static $STR_FREQUENCIA_MENSAL_PRIMEIRO_DIA_UTIL_MES = 'Primeiro dia útil do mês 00h00';
    public static $STR_FREQUENCIA_MENSAL_ULTIMO_DIA_UTIL_MES = 'Dia anterior ao primeiro dia útil do mês seguinte 23h59';

    public static $FREQUENCIA_MENSAL_PRIMEIRA_SEGUNDA_MES = '6';
    public static $STR_FREQUENCIA_MENSAL_PRIMEIRA_SEGUNDA_MES = 'Primeira segunda feira do mês 00h00';
    public static $STR_FREQUENCIA_MENSAL_ULTIMA_SEGUNDA_MES = 'Dia anterior à primeira segunda feira do mês seguinte 23h59';

    //Retorno para última Fila
    public static $RETORNO_SIM = 'S';
    public static $RETORNO_NAO = 'N';

    //Resposta Tácita
    public static $APROVACAO_TACITA      = 'A';
    public static $STR_APROVACAO_TACITA  = 'Aprovação Tácita';
    public static $REPROVACAO_TACITA     = 'R';
    public static $STR_REPROVACAO_TACITA = 'Reprovação Tácita';


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
      /*
      if ($objMdUtlAdmPrmGrDTO->isSetNumIdMdUtlAdmFila()){
        $this->validarNumIdMdUtlAdmFila($objMdUtlAdmPrmGrDTO, $objInfraException);
      }
      */

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
          $objMdUtlAdmTpCtrlRN->incluiridMdUtlAdmPrmGrEmMdUtlAdmTpCtrlDesemp($objMdUtlAdmTpCtrlDTO);
      }

      return $idMdUtlAdmPrmGr;
  }

  private function _cadastrarRelParametrizacaoTpProcesso($bolAlterar, $idMdUtlAdmPrmGr, $arrTpProcesso){
      $mdUtlAdmRelPrmGrProcRN = new MdUtlAdmRelPrmGrProcRN();

      if ($bolAlterar) {

          $mdUtlAdmRelPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO();
          $mdUtlAdmRelPrmGrProcDTO->setNumIdMdUtlAdmParamGr($idMdUtlAdmPrmGr);
          $mdUtlAdmRelPrmGrProcDTO->retTodos();

          $arrObjs = $mdUtlAdmRelPrmGrProcRN->listar($mdUtlAdmRelPrmGrProcDTO);
          $newArr = array();
          $oldArr = array();

          foreach ($arrObjs as $objBdDTO) {
              $oldArr[] = $objBdDTO->getNumIdTipoProcedimento();
          }

          foreach ($arrTpProcesso as $objTela) {
              $newArr[] = $objTela[0];
          }

          for ($i = 0; $i < count($oldArr); $i++) {
              if (!in_array($oldArr[$i], $newArr)) {
                  $objDTO = new MdUtlAdmRelPrmGrProcDTO();
                  $objDTO->setNumIdMdUtlAdmParamGr($idMdUtlAdmPrmGr);
                  $objDTO->setNumIdTipoProcedimento($oldArr[$i]);
                  $objDTO->retTodos();

                  $objsAlteracaoDTO = $mdUtlAdmRelPrmGrProcRN->listar($objDTO);
                  $mdUtlAdmRelPrmGrProcRN->excluir($objsAlteracaoDTO);
              }
          }

          for ($i = 0; $i < count($arrTpProcesso); $i++) {
              if (count($oldArr) < 1) {
                  $mdUtlAdmRelPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO();
                  $mdUtlAdmRelPrmGrProcDTO->setNumIdMdUtlAdmParamGr($idMdUtlAdmPrmGr);
                  $mdUtlAdmRelPrmGrProcDTO->setNumIdTipoProcedimento($arrTpProcesso[$i][0]);
                  $mdUtlAdmRelPrmGrProcRN->cadastrar($mdUtlAdmRelPrmGrProcDTO);
              }elseif (!in_array($arrTpProcesso[$i][0], $oldArr)) {
                  $mdUtlAdmRelPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO();
                  $mdUtlAdmRelPrmGrProcDTO->setNumIdMdUtlAdmParamGr($idMdUtlAdmPrmGr);
                  $mdUtlAdmRelPrmGrProcDTO->setNumIdTipoProcedimento($arrTpProcesso[$i][0]);
                  $mdUtlAdmRelPrmGrProcRN->cadastrar($mdUtlAdmRelPrmGrProcDTO);
              }
          }
      }
  }

  private function _cadastrarAlterarUsuariosParticipantes($idsUsuarios, $arrDadosUsuario ,$idMdUtlAdmPrmGr, $isAlteracao = false){
      $objMdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();
      $arrObjs = array();
      if (count($idsUsuarios) > 0) {
          foreach($idsUsuarios as $idUsuario){
              $novosDados = $arrDadosUsuario[$idUsuario];

              $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
              $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($idMdUtlAdmPrmGr);
              $objMdUtlAdmPrmGrUsuDTO->setNumIdUsuario($idUsuario);
              $objMdUtlAdmPrmGrUsuDTO->setStrStaTipoPresenca($novosDados['TIPO_PRESENCA']);
              $objMdUtlAdmPrmGrUsuDTO->setNumFatorDesempDiferenciado($novosDados['FATOR_DESEMPENHO']);
              $objMdUtlAdmPrmGrUsuDTO->setStrStaTipoJornada($novosDados['TIPO_JORNADA']);
              $objMdUtlAdmPrmGrUsuDTO->setNumFatorReducaoJornada($novosDados['FATOR_REDUCAO']);
              $objMdUtlAdmPrmGrUsuDTO->setDblIdDocumento($novosDados['ID_DOCUMENTO']);

              if($isAlteracao && $novosDados['ID_VINCULADO'] != null){
                  $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGrUsu($novosDados['ID_VINCULADO']);
                  $objDTO = $objMdUtlAdmPrmGrUsuRN->alterar($objMdUtlAdmPrmGrUsuDTO);
                  $objDTO = $objMdUtlAdmPrmGrUsuDTO;
              }else{
                  $objDTO = $objMdUtlAdmPrmGrUsuRN->cadastrar($objMdUtlAdmPrmGrUsuDTO);
              }

              $arrObjs[]=$objDTO;
          }
      }


      return $arrObjs;
  }

    private function _verificarIdsAlterados($arrObjsUsuariosBd, $arrDadosUsuarioTela)
    {
        $idsUsuariosAlterados = array();
        if (count($arrObjsUsuariosBd) > 0) {
            foreach ($arrObjsUsuariosBd as $objBdDTO) {
                $idUsuarioBd = $objBdDTO->getNumIdUsuario();
                if (array_key_exists($idUsuarioBd, $arrDadosUsuarioTela)) {
                    $dadoUsuario     = $arrDadosUsuarioTela[$idUsuarioBd];
                    $isTipoPresnAlt  = $this->_isDadoAlterado($objBdDTO, 'StaTipoPresenca', $arrDadosUsuarioTela, 'TIPO_PRESENCA');
                    $isFatorDesemAlt = $this->_isDadoAlterado($objBdDTO, 'FatorDesempDiferenciado', $arrDadosUsuarioTela, 'FATOR_DESEMPENHO');
                    $isTipoJornadAlt = $this->_isDadoAlterado($objBdDTO, 'StaTipoJornada', $arrDadosUsuarioTela, 'TIPO_JORNADA');
                    $isFatorDsmAlt   = $this->_isDadoAlterado($objBdDTO, 'FatorReducaoJornada', $arrDadosUsuarioTela, 'FATOR_REDUCAO');
                    $isPlanoTrabAlt  = $this->_isDadoAlterado($objBdDTO, 'IdDocumento', $arrDadosUsuarioTela, 'ID_DOCUMENTO');

                    if($isTipoPresnAlt || $isFatorDesemAlt || $isTipoJornadAlt || $isFatorDsmAlt || $isPlanoTrabAlt){
                        $idsUsuariosAlterados[] = $idUsuarioBd;
                    }
                }
            }
        }

        return $idsUsuariosAlterados;
    }

    private function _isDadoAlterado($objDTO, $strAtributo, $arrDadosUsuarioTela, $strAtributoArr)
    {
        $dadoBanco = strtoupper($objDTO->get($strAtributo));
        $dadoBanco = ( $dadoBanco == '' || is_null($dadoBanco) ) ? null : $dadoBanco;
        $idUsuarioBd = $objDTO->getNumIdUsuario();
        $dadoTela = array_key_exists($idUsuarioBd, $arrDadosUsuarioTela) ? $arrDadosUsuarioTela[$idUsuarioBd][$strAtributoArr] : null;
        $dadoTela = ( $dadoTela == '' || is_null($dadoTela) ) ? null : $dadoTela;
        
        if ( $dadoTela !== $dadoBanco ) {
            return $dadoTela !== $dadoBanco;
        }

        return false;

    }

  private function _cadastrarRelParametrizacaoUsuario($isBolAlterarParametrizacao, $arrUsuarioPart, $idMdUtlAdmPrmGr, $idTipoControleUtl){
      $idsUsuariosExcl  = array();
      $idsUsuariosAlter = array();

      $objMdUtlAdmPrmGrUsuRN  = new MdUtlAdmPrmGrUsuRN();
      $funcFirstElement = function ($value) {
          reset($value);
          return current($value);
      };

      $arrObjsUsuariosBd = $isBolAlterarParametrizacao ? $this->_getAntigosUsuariosCadastrados($idMdUtlAdmPrmGr) : array();
      $idsVinculadosBd   = count($arrObjsUsuariosBd) > 0 ? InfraArray::converterArrInfraDTO($arrObjsUsuariosBd,  'IdMdUtlAdmPrmGrUsu', 'IdUsuario') : array();
      $arrDadosUsuario   = $this->_prepararArrDadosParametrizados($arrUsuarioPart, $arrObjsUsuariosBd, $idsVinculadosBd);

      $idsUsuariosTela   = array_map($funcFirstElement, $arrUsuarioPart);

      if($isBolAlterarParametrizacao) {
          $idsUsuariosBd = count($arrObjsUsuariosBd) > 0 ? InfraArray::converterArrInfraDTO($arrObjsUsuariosBd, 'IdUsuario') : array();
          $idsUsuariosNovos = array_diff($idsUsuariosTela, $idsUsuariosBd);
          $idsUsuariosExcl = array_diff($idsUsuariosBd, $idsUsuariosTela);
          $idsUsuariosAlter = $this->_verificarIdsAlterados($arrObjsUsuariosBd, $arrDadosUsuario);
      }else{
         $idsUsuariosNovos = $idsUsuariosTela;
      }

      if(count($idsUsuariosNovos) > 0){
          $arrObjsParametrizados = $this->_cadastrarAlterarUsuariosParticipantes($idsUsuariosNovos, $arrDadosUsuario, $idMdUtlAdmPrmGr);
          $this->_cadastrarNovoUsuarioHistorico($arrObjsParametrizados, $idTipoControleUtl);
      }


      if(count($idsUsuariosAlter) > 0){
          $arrObjsParametrizados = $this->_cadastrarAlterarUsuariosParticipantes($idsUsuariosAlter, $arrDadosUsuario, $idMdUtlAdmPrmGr, true);
          $this->_cadastrarDataFinalUsuarios($idMdUtlAdmPrmGr, $idsUsuariosAlter);
          $this->_cadastrarNovoUsuarioHistorico($arrObjsParametrizados, $idTipoControleUtl);
      }


      if(count($idsUsuariosExcl) > 0){
          $objMdUtlAdmPrmGrUsuRN->excluirUsuarioParticipante($idsUsuariosExcl, $idsVinculadosBd);
          $this->_cadastrarDataFinalUsuarios($idMdUtlAdmPrmGr, $idsUsuariosExcl);
      }

  }

  private function _cadastrarNovoUsuarioHistorico($arrObjsParametrizados, $idTipoControleUtl){
      if(count($arrObjsParametrizados) > 0) {
          $objHistoricoRN = new MdUtlAdmHistPrmGrUsuRN();
          foreach($arrObjsParametrizados as $objDTOParametrizado) {
              if(!is_null($objDTOParametrizado)) {
                  $objHistoricoDTO = $objHistoricoRN->clonarObjParametroParaHistorico($objDTOParametrizado);
                  $objHistoricoDTO->setDthInicial(InfraData::getStrDataHoraAtual());
                  $objHistoricoDTO->setNumIdUsuarioAtual(SessaoSEI::getInstance()->getNumIdUsuario());
                  $objHistoricoRN->cadastrar($objHistoricoDTO);

                  //atualizar distribuições no controle de desempenho para usuario alterado
                  MdUtlAdmPrmGrUsuINT::atualizarControleDesempenhoAoAlterarUsuario($objHistoricoDTO->getNumIdUsuario(), $idTipoControleUtl);
              }
          }
      }
  }

  private function _cadastrarDataFinalUsuarios($idMdUtlAdmPrmGr, $idsUsuariosAlter){
      $objMdUtlHistAdmPrmGrUsuRN  = new MdUtlAdmHistPrmGrUsuRN();
      $objMdUtlHistAdmPrmGrUsuDTO = new MdUtlAdmHistPrmGrUsuDTO();
      $objMdUtlHistAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($idMdUtlAdmPrmGr);
      $objMdUtlHistAdmPrmGrUsuDTO->setNumIdUsuario($idsUsuariosAlter, InfraDTO::$OPER_IN);
      $objMdUtlHistAdmPrmGrUsuDTO->retNumIdMdUtlAdmHistPrmGrUsu();
      $objMdUtlHistAdmPrmGrUsuDTO->setDthFinal(null);

      $count = $objMdUtlHistAdmPrmGrUsuRN->contar($objMdUtlHistAdmPrmGrUsuDTO);
      if($count > 0) {
          $arrObjs = $objMdUtlHistAdmPrmGrUsuRN->listar($objMdUtlHistAdmPrmGrUsuDTO);
          foreach($arrObjs as $objDTO){
              $objDTO->setDthFinal(InfraData::getStrDataHoraAtual());
              $objMdUtlHistAdmPrmGrUsuRN->alterar($objDTO);
          }
      }
  }

  private function _prepararArrDadosParametrizados($arrUsuarioPart, $arrObjsUsuariosBd, $idsVinculadosBd){
    $arrDadosUsuario = array();

    if(!is_null($arrUsuarioPart) && count($arrUsuarioPart) > 0){
        foreach($arrUsuarioPart as $arrUsuarioTela){
            $idUsuario = $arrUsuarioTela[0];
            $arrDadosUsuario[$idUsuario]['TIPO_PRESENCA']    = $arrUsuarioTela[3];
            $arrDadosUsuario[$idUsuario]['FATOR_DESEMPENHO'] = $arrUsuarioTela[3] == MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_DIFERENCIADO ? str_replace("%", "", $arrUsuarioTela[5]) : null;
            $arrDadosUsuario[$idUsuario]['TIPO_JORNADA']     = $arrUsuarioTela[7];
            $arrDadosUsuario[$idUsuario]['FATOR_REDUCAO']    = $arrUsuarioTela[7] == MdUtlAdmPrmGrUsuRN::$TIPOJORNADA_REDUZIDO ? str_replace("%", "", $arrUsuarioTela[8]) : null;
            $arrDadosUsuario[$idUsuario]['ID_VINCULADO']     = array_key_exists($idUsuario, $idsVinculadosBd)  ? $idsVinculadosBd[$idUsuario] : null;
            $arrDadosUsuario[$idUsuario]['ID_DOCUMENTO']     = $this->getObjDocumentoNumSei( strip_tags( $arrUsuarioTela[4] ) );
        }
    }
    return $arrDadosUsuario;
  }

  private function getObjDocumentoNumSei( $numSei ){
    $objDocumentoDTO = new DocumentoDTO();
    $objDocumentoRN = new DocumentoRN();

    $objDocumentoDTO->setStrProtocoloDocumentoFormatado( $numSei );
    $objDocumentoDTO->setNumMaxRegistrosRetorno(1);
    $objDocumentoDTO->retDblIdDocumento();

    $objDocumentoDTO = $objDocumentoRN->consultarRN0005( $objDocumentoDTO );
    
    return  !empty( $objDocumentoDTO ) ? $objDocumentoDTO->getDblIdDocumento() : null;
  }

  private function _getAntigosUsuariosCadastrados($idMdUtlAdmPrmGr){
      if(!is_null($idMdUtlAdmPrmGr)) {
          $objMdUtlPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();
          $objMdUtlPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
          $objMdUtlPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($idMdUtlAdmPrmGr);
          $objMdUtlPrmGrUsuDTO->retTodos();
          $arrObjs = $objMdUtlPrmGrUsuRN->listar($objMdUtlPrmGrUsuDTO);

          return $arrObjs;
      }

      return null;
  }

  public function cadastrarParametrizacao($idMdUtlAdmPrmGr, $idTipoControleUtl, $objMdUtlAdmPrmGrDTO, $objMdUtlAdmTpCtrlDTO){
      //Get Vars Iniciais
      $bolAlterar              = true; #$idMdUtlAdmPrmGr > 0;
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
      $this->_cadastrarRelParametrizacaoUsuario($bolAlterar, $arrUsuarioPart, $idMdUtlAdmPrmGr, $idTipoControleUtl);

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

    public function validaPrazoMaximoDiasJustificativaConectado($arrParams){
        $qtdDias     = array_key_exists(0, $arrParams) ? $arrParams[0] : null;
        $tipoSolic   = array_key_exists(1, $arrParams) ? $arrParams[1] : null;

        $objTpCtrlUtlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
        $objTpControle     = $objTpCtrlUtlUndRN->getObjTipoControleUnidadeLogada();

        $objMdUtlPrmGrDTO  = new MdUtlAdmPrmGrDTO();
        $objMdUtlPrmGrDTO->setNumIdMdUtlAdmPrmGr($objTpControle->getNumIdMdUtlAdmPrmGr());
        $objMdUtlPrmGrDTO->retNumPrazoMaxInterrupcao();
        $objMdUtlPrmGrDTO->retNumIdMdUtlAdmPrmGr();
        $objMdUtlPrmGrDTO->retNumPrazoMaxSuspensao();
        $objMdUtlPrmGrDTO->setNumMaxRegistrosRetorno(1);
        $objMdUtlPrmGrDTO = $this->consultar($objMdUtlPrmGrDTO);

        $prazoBd = $tipoSolic == MdUtlControleDsmpRN::$TP_SOLICITACAO_SUSPENSAO ? $objMdUtlPrmGrDTO->getNumPrazoMaxSuspensao(): $objMdUtlPrmGrDTO->getNumPrazoMaxInterrupcao();

        if($qtdDias > $prazoBd){
            return false;
        }

        return true;
    }

    protected function parametrizaInicioFimDoPeriodoControlado(){
        $objMdUtlPrmGrDTO = new MdUtlAdmPrmGrDTO();
        #$objMdUtlPrmGrDTO->retTodos();
        $objMdUtlPrmGrDTO->setNumInicioPeriodo(null);
        $objMdUtlPrmGrDTO->retNumInicioPeriodo();
        $objMdUtlPrmGrDTO->retStrStaFrequencia();
        $objMdUtlPrmGrDTO->retNumIdMdUtlAdmPrmGr();

        $arrObjDTO = $this->listar($objMdUtlPrmGrDTO);

        foreach ($arrObjDTO as $objDTO) {

            if(!is_null($objDTO->getStrStaFrequencia())) {

                if ($objDTO->getStrStaFrequencia() == self::$FREQUENCIA_DIARIO) {
                    $objDTO->setNumInicioPeriodo(self::$FREQUENCIA_INICIO_DIARIO);
                }

                if ($objDTO->getStrStaFrequencia() == self::$FREQUENCIA_SEMANAL) {
                    $objDTO->setNumInicioPeriodo(self::$FREQUENCIA_INICIO_SEMANAL_DOMINGO);
                }

                if ($objDTO->getStrStaFrequencia() == self::$FREQUENCIA_MENSAL) {
                    $objDTO->setNumInicioPeriodo(self::$FREQUENCIA_MENSAL_PRIMEIRO_DIA_MES);
                }

                $this->alterar($objDTO);
            }
        }
    }
}
