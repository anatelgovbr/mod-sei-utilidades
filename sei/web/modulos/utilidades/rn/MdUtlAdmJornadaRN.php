<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/07/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmJornadaRN extends InfraRN {

  public static $TIPO_JORNADA_GERAL     = 'G';
  public static $STR_TIPO_JORNADA_GERAL = 'Geral';

  public static $TIPO_JORNADA_ESPECIFICO     = 'E';
  public static $STR_TIPO_JORNADA_ESPECIFICO = 'Específico';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(MdUtlAdmJornadaDTO $objMdUtlAdmJornadaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmJornadaDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objMdUtlAdmJornadaDTO->setStrNome(trim($objMdUtlAdmJornadaDTO->getStrNome()));

      if (strlen($objMdUtlAdmJornadaDTO->getStrNome())>100){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 100 caracteres.');
      }
    }
  }


  private function validarStrDescricao(MdUtlAdmJornadaDTO $objMdUtlAdmJornadaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmJornadaDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objMdUtlAdmJornadaDTO->setStrDescricao(trim($objMdUtlAdmJornadaDTO->getStrDescricao()));

      if (strlen($objMdUtlAdmJornadaDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descricao possui tamanho superior a 250 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(MdUtlAdmJornadaDTO $objMdUtlAdmJornadaDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_jornada_cadastrar', __METHOD__, $objMdUtlAdmJornadaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objMdUtlAdmJornadaDTO, $objInfraException);
      $this->validarStrDescricao($objMdUtlAdmJornadaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmJornadaBD = new MdUtlAdmJornadaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmJornadaBD->cadastrar($objMdUtlAdmJornadaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Jornada.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmJornadaDTO $objMdUtlAdmJornadaDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_jornada_alterar', __METHOD__, $objMdUtlAdmJornadaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objInfraException->lancarValidacoes();



      $objMdUtlAdmJornadaBD = new MdUtlAdmJornadaBD($this->getObjInfraIBanco());
      $objMdUtlAdmJornadaBD->alterar($objMdUtlAdmJornadaDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Jornada.',$e);
    }
  }

  protected function excluirRelacionamentosControlado($idMdJornada){
    //apagando Gestores
    $objMdUtlJornadaDTO = new MdUtlAdmRelJornadaUsuDTO();
    $objMdUtlJornadaDTO->retTodos();
    $objMdUtlJornadaDTO->setNumIdMdUtlAdmJornada($idMdJornada);

    $objMdUtlRelJornadaUsuarioRN = new MdUtlAdmRelJornadaUsuRN();
    $arrObjMdUtlRelJornadaUsuarioDTO = $objMdUtlRelJornadaUsuarioRN->listar($objMdUtlJornadaDTO);
    $objMdUtlRelJornadaUsuarioRN->excluir($arrObjMdUtlRelJornadaUsuarioDTO);
  }

  protected function excluirControlado($arrObjMdUtlAdmJornadaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_jornada_excluir', __METHOD__, $arrObjMdUtlAdmJornadaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJornadaBD = new MdUtlAdmJornadaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmJornadaDTO);$i++){
        $objMdUtlAdmJornadaBD->excluir($arrObjMdUtlAdmJornadaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Jornada.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmJornadaDTO $objMdUtlAdmJornadaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_jornada_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJornadaBD = new MdUtlAdmJornadaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmJornadaBD->consultar($objMdUtlAdmJornadaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Jornada.',$e);
    }
  }

  protected function listarConectado($objMdUtlAdmJornadaDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_jornada_listar');

      $objMdUtlAdmJornadaBD = new MdUtlAdmJornadaBD($this->getObjInfraIBanco());

      $ret = $objMdUtlAdmJornadaBD->listar($objMdUtlAdmJornadaDTO);
      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Jornada.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmJornadaDTO $objMdUtlAdmJornadaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_jornada_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJornadaBD = new MdUtlAdmJornadaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmJornadaBD->contar($objMdUtlAdmJornadaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Jornada.',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAdmJornadaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_jornada_desativar', __METHOD__, $arrObjMdUtlAdmJornadaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJornadaBD = new MdUtlAdmJornadaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmJornadaDTO);$i++){
        $objMdUtlAdmJornadaBD->desativar($arrObjMdUtlAdmJornadaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Jornada.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmJornadaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_jornada_reativar', __METHOD__, $arrObjMdUtlAdmJornadaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJornadaBD = new MdUtlAdmJornadaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmJornadaDTO);$i++){
        $objMdUtlAdmJornadaBD->reativar($arrObjMdUtlAdmJornadaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Jornada.',$e);
    }
  }

 
  private function _cadastrarTabelaPrincipalJornada(){
    $objMdUtlJornadaDTO      = new MdUtlAdmJornadaDTO();
    $objMdUtlAdmTpCtrlUndRN  = new MdUtlAdmRelTpCtrlDesempUndRN();
    $idTipoControle          = $objMdUtlAdmTpCtrlUndRN->getTipoControleUnidadeLogada();
    $objMdUtlJornadaDTO->setNumIdMdUtlAdmJornada(null);
    $objMdUtlJornadaDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
    $objMdUtlJornadaDTO->setStrNome($_POST['txtNome']);
    $objMdUtlJornadaDTO->setStrDescricao($_POST['txaDescricao']);
    $objMdUtlJornadaDTO->setNumPercentualAjuste($_POST['txtPercentualAjuste']);
    $objMdUtlJornadaDTO->setDthInicio($_POST['txtDtInicio']);
    $objMdUtlJornadaDTO->setDthFim($_POST['txtDtFim']);
    $objMdUtlJornadaDTO->setStrStaTipoAjuste($_POST['hdnTpAjuste']);
    $objMdUtlJornadaDTO->setStrSinAtivo('S');

    $this->validarDuplicacao($objMdUtlJornadaDTO);

    return $this->cadastrar($objMdUtlJornadaDTO);
  }

  protected function alterarJornadaControlado($idJornada){

    if(!is_null($idJornada)) {

      $this->_aplicarValidacoes();

      $objMdUtlJornadaDTO     = new MdUtlAdmJornadaDTO();
      $objMdUtlAdmTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
      $idTipoControle         = $objMdUtlAdmTpCtrlUndRN->getTipoControleUnidadeLogada();
      $objMdUtlJornadaDTO->setNumIdMdUtlAdmJornada($idJornada);
      $objMdUtlJornadaDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
      $objMdUtlJornadaDTO->setStrNome($_POST['txtNome']);
      $objMdUtlJornadaDTO->setStrDescricao($_POST['txaDescricao']);
      $objMdUtlJornadaDTO->setNumPercentualAjuste($_POST['txtPercentualAjuste']);
      $objMdUtlJornadaDTO->setDthInicio($_POST['txtDtInicio']);
      $objMdUtlJornadaDTO->setDthFim($_POST['txtDtFim']);
      $objMdUtlJornadaDTO->setStrStaTipoAjuste($_POST['hdnTpAjuste']);
      $objMdUtlJornadaDTO->setStrSinAtivo('S');

      $this->validarDuplicacao($objMdUtlJornadaDTO);

      $this->alterar($objMdUtlJornadaDTO);

      $this->excluirRelacionamentos($idJornada);
      $this->_cadastrarRelacionamentos($idJornada);
    }
  }


  protected function cadastrarJornadaControlado(){
    $objMdUtlJornadaDTO  = $this->_cadastrarTabelaPrincipalJornada();
    $tpAjuste            = array_key_exists('hdnTpAjuste', $_POST) ? $_POST['hdnTpAjuste'] : null;

    $this->_aplicarValidacoes();
    //Cadastrar Tabela Jornada
    if(!is_null($tpAjuste) && $tpAjuste == MdUtlAdmJornadaRN::$TIPO_JORNADA_ESPECIFICO)
    {
      $this->_cadastrarRelacionamentos($objMdUtlJornadaDTO->getNumIdMdUtlAdmJornada());
    }

    return $objMdUtlJornadaDTO;
  }

  private function _cadastrarRelacionamentos($idJornada){
    $arrMembros = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnMembrosLupa']);

    $objMdUtlJornadaUsuRN      = new MdUtlAdmRelJornadaUsuRN();

    foreach($arrMembros as $membros){
    
        $objMdUtlJornadaUsuDTO = new MdUtlAdmRelJornadaUsuDTO();
        $objMdUtlJornadaUsuDTO->setNumIdMdUtlAdmJornada($idJornada);
        $objMdUtlJornadaUsuDTO->setNumIdUsuario($membros);

        $objMdUtlJornadaUsuRN->cadastrar($objMdUtlJornadaUsuDTO);
    }
  }

  private function _aplicarValidacoes(){
    $idTpControle         = array_key_exists('hdnIdTipoControleUtl', $_POST) ? $_POST['hdnIdTipoControleUtl'] : null;
    $objMdTpCtrlRN        = new MdUtlAdmTpCtrlDesempRN();
    $existeParametrização = $objMdTpCtrlRN->verificaTipoControlePossuiParametrizacao($idTpControle);

    if(is_null($idTpControle)){
      $objInfraException = new InfraException();
      $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11, 'Tipo de Controle de Desempenho');
      $objInfraException->lancarValidacao($msg);
    }

    if(!$existeParametrização)
    {
      $objInfraException = new InfraException();
      $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_64);
      $objInfraException->lancarValidacao($msg);
    }

    return true;
  }

  private function _getObjJornada($objMdUtlJornadaDTO, $inicio = true){
    $idJornada = $objMdUtlJornadaDTO->getNumIdMdUtlAdmJornada();
    $idTpCtrl  = $objMdUtlJornadaDTO->getNumIdMdUtlAdmTpCtrlDesemp();
    $tpAjuste  = $objMdUtlJornadaDTO->getStrStaTipoAjuste();
    $dtInicio  = $objMdUtlJornadaDTO->getDthInicio();
    $dtFim     = $objMdUtlJornadaDTO->getDthFim();

    $objMdUtlAdmJornadaDTO2 = new MdUtlAdmJornadaDTO();
    $objMdUtlAdmJornadaDTO2->retTodos();

    $dtPadrao = $inicio ? $dtInicio : $dtFim;

    $objMdUtlAdmJornadaDTO2->adicionarCriterio(array('Inicio', 'Fim'),
        array(InfraDTO::$OPER_MENOR_IGUAL, InfraDTO::$OPER_MAIOR_IGUAL),
        array($dtPadrao, $dtPadrao),
        array(InfraDTO::$OPER_LOGICO_AND));

    if(!is_null($idJornada)){
      $objMdUtlAdmJornadaDTO2->setNumIdMdUtlAdmJornada($idJornada, InfraDTO::$OPER_DIFERENTE);
    }

    $objMdUtlAdmJornadaDTO2->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
    $objMdUtlAdmJornadaDTO2->setStrStaTipoAjuste($tpAjuste);

    return $objMdUtlAdmJornadaDTO2;
  }

  private function validarDuplicacao($objMdUtlJornadaDTO){
    $objInfraException  = new InfraException();
    $tpAjuste  = $objMdUtlJornadaDTO->getStrStaTipoAjuste();
    $objMdUtlAdmJornadaDTOInicio = $this->_getObjJornada($objMdUtlJornadaDTO);
    $objMdUtlAdmJornadaDTOFim    = $this->_getObjJornada($objMdUtlJornadaDTO, false);
    $count1 = $this->contar($objMdUtlAdmJornadaDTOInicio);
    $count2  = $this->contar($objMdUtlAdmJornadaDTOFim);


    if($tpAjuste == static::$TIPO_JORNADA_GERAL && ($count1 > 0 || $count2 > 0)){
      $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_63);
      $objInfraException->lancarValidacao($msg);
    }


    if($tpAjuste == static::$TIPO_JORNADA_ESPECIFICO && ($count1 > 0 || $count2 > 0)){
        $ids1 = array();
        $ids2 = array();
        $idsJornadas    = array();
        $objJornadaDTO1 = $this->listar($objMdUtlAdmJornadaDTOInicio);
        $objJornadaDTO2 = $this->listar($objMdUtlAdmJornadaDTOFim);

      if($count1 > 0){
        $ids1 = InfraArray::converterArrInfraDTO($objJornadaDTO1, 'IdMdUtlAdmJornada');
      }

      if($count2 > 0){
        $ids2 = InfraArray::converterArrInfraDTO($objJornadaDTO2, 'IdMdUtlAdmJornada');
      }

      $idsMembros = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnMembrosLupa']);
  
      $idsJornadas = array_merge($ids1, $ids2);

      $objMdUtlAdmRelJornadaRN    = new MdUtlAdmRelJornadaUsuRN();
      $objMdUtlAdmRelJornadaUsDTO = new MdUtlAdmRelJornadaUsuDTO();
      $objMdUtlAdmRelJornadaUsDTO->setNumIdUsuario($idsMembros, InfraDTO::$OPER_IN);
      $objMdUtlAdmRelJornadaUsDTO->setNumIdMdUtlAdmJornada($idsJornadas, InfraDTO::$OPER_IN);
      $objMdUtlAdmRelJornadaUsDTO->retTodos();
      $objMdUtlAdmRelJornadaUsDTO->retStrNomeUsuario();
      $countUsers = $objMdUtlAdmRelJornadaRN->contar($objMdUtlAdmRelJornadaUsDTO);

      if($countUsers > 0)
      {
        $arrObjrelJornadaUsuDTO = $objMdUtlAdmRelJornadaRN->listar($objMdUtlAdmRelJornadaUsDTO);
        $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_62);

        foreach($arrObjrelJornadaUsuDTO as $objDTO){
          $msg .= '\n* '.$objDTO->getStrNomeUsuario();
        }

        $objInfraException->lancarValidacao($msg);
      }

    }
  }

  protected function getTiposControleParametrizadoUsuarioConectado($idUsuario){
      $idsTpCtrl = null;

      $objMdUtlAdmPrGrUsuRN  = new MdUtlAdmPrmGrUsuRN();
      $objMdUtlAdmPrGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
      $objMdUtlAdmPrGrUsuDTO->setNumIdUsuario($idUsuario);
      $objMdUtlAdmPrGrUsuDTO->retNumIdMdUtlAdmPrmGr();

      $countPr  = $objMdUtlAdmPrGrUsuRN->contar($objMdUtlAdmPrGrUsuDTO);

    if($countPr > 0) {
      $idsPrm = InfraArray::converterArrInfraDTO($objMdUtlAdmPrGrUsuRN->listar($objMdUtlAdmPrGrUsuDTO), 'IdMdUtlAdmPrmGr');

      $objMdUtlAdmTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();
      $objMdUtlAdmTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
      $objMdUtlAdmTpCtrlDTO->setNumIdMdUtlAdmPrmGr($idsPrm, InfraDTO::$OPER_IN);
      $objMdUtlAdmTpCtrlDTO->retNumIdMdUtlAdmTpCtrlDesemp();

      $countTp = $objMdUtlAdmTpCtrlRN->contar($objMdUtlAdmTpCtrlDTO);
      if ($countTp > 0) {
        $idsTpCtrl = InfraArray::converterArrInfraDTO($objMdUtlAdmTpCtrlRN->listar($objMdUtlAdmTpCtrlDTO), 'IdMdUtlAdmTpCtrlDesemp');
      }
      
      
    }

    return $idsTpCtrl;
  }

  protected function getAjusteJornadaUsuarioConectado($idUsuario){
    $ids  = null;
    $objRN = new MdUtlAdmRelJornadaUsuRN();
    $objMdUtlAdmJornadaUsuDTO = new MdUtlAdmRelJornadaUsuDTO();
    $objMdUtlAdmJornadaUsuDTO->setNumIdUsuario($idUsuario);
    $objMdUtlAdmJornadaUsuDTO->retNumIdMdUtlAdmJornada();
    
    if($objRN->contar($objMdUtlAdmJornadaUsuDTO) > 0){
      $ids = InfraArray::converterArrInfraDTO($objRN->listar($objMdUtlAdmJornadaUsuDTO), 'IdMdUtlAdmJornada');
    }

    return $ids;
  }


}
