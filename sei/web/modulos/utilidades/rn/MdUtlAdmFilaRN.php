<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/07/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmFilaRN extends InfraRN {

      public static $TRIADOR = 'T';
      public static $ANALISTA = 'A';
      public static $REVISOR = 'R';

      public static $TOTAL = '1';
      public static $STR_TOTAL = 'Total';
      public static $POR_ATIVIDADE = '2';
      public static $STR_POR_ATIVIDADE = 'Por Atividade';
      public static $SEM_REVISAO = '3';
      public static $STR_SEM_REVISAO = 'Sem Avaliação';
    

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(MdUtlAdmFilaDTO $objMdUtlAdmFilaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmFilaDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objMdUtlAdmFilaDTO->setStrNome(trim($objMdUtlAdmFilaDTO->getStrNome()));

      if (strlen($objMdUtlAdmFilaDTO->getStrNome())>100){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrDescricao(MdUtlAdmFilaDTO $objMdUtlAdmFilaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmFilaDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descricao não informada.');
    }else{
      $objMdUtlAdmFilaDTO->setStrDescricao(trim($objMdUtlAdmFilaDTO->getStrDescricao()));

      if (strlen($objMdUtlAdmFilaDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descricao possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(MdUtlAdmFilaDTO $objMdUtlAdmFilaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmFilaDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdUtlAdmFilaDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  private function validarDuplicacao($objMdUtlAdmFilaDTO, $objInfraException){
    $idFila = $objMdUtlAdmFilaDTO->getNumIdMdUtlAdmFila();
    $objMdUtlAdmFilaDTO2 = new MdUtlAdmFilaDTO();
    $objMdUtlAdmFilaDTO2->setStrNome($objMdUtlAdmFilaDTO->getStrNome());
    $objMdUtlAdmFilaDTO2->setNumIdMdUtlAdmTpCtrlDesemp($_POST['hdnIdTipoControleUtl']);

    if(!is_null($idFila)){
      $objMdUtlAdmFilaDTO2->setNumIdMdUtlAdmFila($idFila, InfraDTO::$OPER_DIFERENTE);
    }

    $existeRegistroDupl = $this->contar($objMdUtlAdmFilaDTO2) > 0;

    if($existeRegistroDupl){
      $objInfraException->lancarValidacao('Já existe uma Fila cadastrada com este nome para o presente Tipo de Controle.');
    }
  }

  protected function cadastrarControlado(MdUtlAdmFilaDTO $objMdUtlAdmFilaDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_fila_cadastrar', __METHOD__, $objMdUtlAdmFilaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objMdUtlAdmFilaDTO, $objInfraException);
      $this->validarStrDescricao($objMdUtlAdmFilaDTO, $objInfraException);
      $this->validarStrSinAtivo($objMdUtlAdmFilaDTO, $objInfraException);
      $this->validarDuplicacao($objMdUtlAdmFilaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmFilaBD = new MdUtlAdmFilaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmFilaBD->cadastrar($objMdUtlAdmFilaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Fila.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmFilaDTO $objMdUtlAdmFilaDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_fila_alterar',__METHOD__, $objMdUtlAdmFilaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmFilaDTO->isSetStrNome()){
        $this->validarStrNome($objMdUtlAdmFilaDTO, $objInfraException);
      }
      if ($objMdUtlAdmFilaDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objMdUtlAdmFilaDTO, $objInfraException);
      }
      if ($objMdUtlAdmFilaDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objMdUtlAdmFilaDTO, $objInfraException);
      }

      $this->validarDuplicacao($objMdUtlAdmFilaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

       $arrTbUsuarioParticipante = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnUsuarioParticipante']);
      $objMdUtlAdmFilaBD = new MdUtlAdmFilaBD($this->getObjInfraIBanco());
      $objMdUtlAdmFilaBD->alterar($objMdUtlAdmFilaDTO);


      $this->excluirRelacionamentos($objMdUtlAdmFilaDTO->getNumIdMdUtlAdmFila());
      $this->_cadastrarRelacionamentos($arrTbUsuarioParticipante, $objMdUtlAdmFilaDTO);
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Fila.',$e);
    }
  }

  protected function excluirRelacionamentosControlado($idMdFila){
    //apagando Gestores
    $objMdUtlFilaDTO = new MdUtlAdmFilaPrmGrUsuDTO();
    $objMdUtlFilaDTO->retTodos();
    $objMdUtlFilaDTO->setNumIdMdUtlAdmFila($idMdFila);

    $objMdUtlRelFilaUsuarioRN = new MdUtlAdmFilaPrmGrUsuRN();
    $arrObjMdUtlRelFilaUsuarioDTO = $objMdUtlRelFilaUsuarioRN->listar($objMdUtlFilaDTO);
    $objMdUtlRelFilaUsuarioRN->excluir($arrObjMdUtlRelFilaUsuarioDTO);
  }

  protected function excluirControlado($arrObjMdUtlAdmFilaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_fila_excluir', __METHOD__,$arrObjMdUtlAdmFilaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmFilaBD = new MdUtlAdmFilaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmFilaDTO);$i++){
        $objMdUtlAdmFilaBD->excluir($arrObjMdUtlAdmFilaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Fila.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmFilaDTO $objMdUtlAdmFilaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_fila_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmFilaBD = new MdUtlAdmFilaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmFilaBD->consultar($objMdUtlAdmFilaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Fila.',$e);
    }
  }

  protected function listarConectado(MdUtlAdmFilaDTO $objMdUtlAdmFilaDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_fila_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmFilaBD = new MdUtlAdmFilaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmFilaBD->listar($objMdUtlAdmFilaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Fila.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmFilaDTO $objMdUtlAdmFilaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_fila_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmFilaBD = new MdUtlAdmFilaBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmFilaBD->contar($objMdUtlAdmFilaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Fila.',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAdmFilaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_fila_desativar',__METHOD__,$arrObjMdUtlAdmFilaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmFilaBD = new MdUtlAdmFilaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmFilaDTO);$i++){
        $objMdUtlAdmFilaBD->desativar($arrObjMdUtlAdmFilaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Fila.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmFilaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_fila_reativar', __METHOD__, $arrObjMdUtlAdmFilaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmFilaBD = new MdUtlAdmFilaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmFilaDTO);$i++){
        $objMdUtlAdmFilaBD->reativar($arrObjMdUtlAdmFilaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Fila.',$e);
    }
  }
  

  private function _cadastrarTabelaPrincipalFila(){
    $sinDstAutomatica = array_key_exists('rdoDstAutomatica', $_POST) && $_POST['rdoDstAutomatica']== 'S' ? 'S': 'N';
    $sinDstUltFila    = array_key_exists('rdoDstUltimaFila', $_POST) && $_POST['rdoDstUltimaFila']== 'S' ? 'S': 'N';

    $objMdUtlFilaDTO = new MdUtlAdmFilaDTO();
    $objMdUtlFilaDTO->setNumIdMdUtlAdmFila(null);
    $objMdUtlFilaDTO->setNumIdMdUtlAdmTpCtrlDesemp($_POST['hdnIdTipoControleUtl']);
    $objMdUtlFilaDTO->setStrNome($_POST['txtNome']);
    $objMdUtlFilaDTO->setStrDescricao($_POST['txaDescricao']);
    $objMdUtlFilaDTO->setNumTmpExecucaoTriagem($_POST['txtTmpExecucaoTriagem']);
    $objMdUtlFilaDTO->setStrSinDistribuicaoAutomatica($sinDstAutomatica);
    $objMdUtlFilaDTO->setStrSinDistribuicaoUltUsuario($sinDstUltFila);
    $objMdUtlFilaDTO->setNumPrazoTarefa($_POST['txtPrazoTarefa']);
    $objMdUtlFilaDTO->setStrSinAtivo('S');
    $objMdUtlFilaDTO->setStrRespTacitaDilacao($_POST['selDilacao']);
    
    return $this->cadastrar($objMdUtlFilaDTO);
  }

  protected function cadastrarFilaControlado(){
    $arrUsuarioParticipante = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnUsuarioParticipante']);
    $objMdUtlFilaDTO        = $this->_cadastrarTabelaPrincipalFila();

    //Cadastrar Tabela Fila
    $this->_cadastrarRelacionamentos($arrUsuarioParticipante, $objMdUtlFilaDTO);

     return $objMdUtlFilaDTO;
  }

  private function _cadastrarRelacionamentos($arrUsuarioParticipante, $objMdUtlFilaDTO){
    $objMdUtlFilaUsuRN      = new MdUtlAdmFilaPrmGrUsuRN();

    foreach($arrUsuarioParticipante as $usuParticipante){
      $idVinculo    = array_key_exists(0, $usuParticipante) ? $usuParticipante[0] : null;
      $sinTriador   = array_key_exists(3, $usuParticipante) ? $usuParticipante[3] : null;
      $sinAnalista  = array_key_exists(5, $usuParticipante) ? $usuParticipante[5] : null;
      $vlTipoRevisao = array_key_exists(6, $usuParticipante) ? $usuParticipante[6] : null;
      $sinRevisor   = array_key_exists(8, $usuParticipante) ? $usuParticipante[8] : null;
       if($vlTipoRevisao == MdUtlAdmFilaRN::$STR_TOTAL){
            $vlTipoRevisao = MdUtlAdmFilaRN::$TOTAL;
        }
        if($vlTipoRevisao == MdUtlAdmFilaRN::$STR_POR_ATIVIDADE){
            $vlTipoRevisao = MdUtlAdmFilaRN::$POR_ATIVIDADE;
        }
        if($vlTipoRevisao == MdUtlAdmFilaRN::$STR_SEM_REVISAO){
            $vlTipoRevisao = MdUtlAdmFilaRN::$SEM_REVISAO;
        }

      if(!is_null($idVinculo))
      {
        $objMdUtlFilaUsuDTO = new MdUtlAdmFilaPrmGrUsuDTO();
        $objMdUtlFilaUsuDTO->setNumIdMdUtlAdmFilaPrmGrUsu(null);
        $objMdUtlFilaUsuDTO->setNumIdMdUtlAdmPrmGrUsu($idVinculo);
        $objMdUtlFilaUsuDTO->setNumIdMdUtlAdmFila($objMdUtlFilaDTO->getNumIdMdUtlAdmFila());
        $objMdUtlFilaUsuDTO->setStrSinTriador($sinTriador);
        $objMdUtlFilaUsuDTO->setStrSinAnalista($sinAnalista);
        $objMdUtlFilaUsuDTO->setStrSinRevisor($sinRevisor);
        $objMdUtlFilaUsuDTO->setNumTipoRevisao($vlTipoRevisao);
          
        $objMdUtlFilaUsuRN->cadastrar($objMdUtlFilaUsuDTO);
      }
    }
  }

  protected function getIdFilaPadraoPorTipoControleConectado($idTipoControle){
        $objMdUtlTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();

        $objMdUtlTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
        $objMdUtlTpCtrlDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
        $objMdUtlTpCtrlDTO->retNumIdMdUtlAdmPrmGr();
        $objMdUtlTpCtrlDTO->retNumIdMdUtlAdmFila();
        $objMdUtlTpCtrlDTO->setNumMaxRegistrosRetorno(1);
        $objMdUtlTpCtrlDTO = $objMdUtlTpCtrlRN->consultar($objMdUtlTpCtrlDTO);

        $idFila = $objMdUtlTpCtrlDTO->getNumIdMdUtlAdmFila();
    
        return $idFila;
  }

  protected function setFilaPadraoConectado($arrDados){
    $count = count($arrDados);

    if ($count > 0) {
      $idTipoControle =  $arrDados[0]->getNumIdMdUtlAdmTpCtrlDesemp();
      #$idFila         = $this->getIdFilaPadraoPorTipoControle($idTipoControle);

       foreach ($arrDados as $key=> $objDTO) {
          #$objDTO->getNumIdMdUtlAdmFila() == $idFila ? 'Sim' : 'Não';
          $vlFila = 'Não';
          $arrDados[$key]->setStrFilaPadrao($vlFila);
       }
    }

    return $arrDados;

  }

  private function _filaIsFilaPadraoTipoControle($idFilaExcluir, $idTipoControle, $isExcluir){
    $acao    = $isExcluir ? 'excluir' : 'desativar';
    $idFila = $this->getIdFilaPadraoPorTipoControle($idTipoControle);

    if($idFila == $idFilaExcluir){
      $objInfraException = new InfraException();
      $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_23, $acao);
      $objInfraException->lancarValidacao($msg);
      return true;
    }

    return false;
  }

  private function _filaIsGrupoAtividade($idFila, $isExcluir){
    $acao = $isExcluir ? 'excluir' : 'desativar';
    $mdUtlAdmGrpFilaDTO = new  MdUtlAdmGrpFilaDTO();
    $mdUtlAdmGrpFilaRN = new  MdUtlAdmGrpFilaRN();
    $mdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmFila($idFila);
    $mdUtlAdmGrpFilaDTO->retTodos();

    if($mdUtlAdmGrpFilaRN->contar($mdUtlAdmGrpFilaDTO)> 0){
      $objInfraException= new InfraException();
      $msg =  MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_38, array($acao));
      $objInfraException->lancarValidacao($msg);
      return true;
    }

    return false;
  }

  private function _filaIsControleProcesso($idFila, $isExcluir){
      $acao = '';
    $possuiVinculoHistorico    = false;
    $objMdUtlControleDsmpRN  = new MdUtlControleDsmpRN();
    $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
    $objMdUtlControleDsmpDTO->setNumIdMdUtlAdmFila($idFila);
    $possuiVinculoControleDsmp = $objMdUtlControleDsmpRN->contar($objMdUtlControleDsmpDTO) > 0;
    $acao = $isExcluir ? 'excluir' : 'desativar';

    if($isExcluir) {
        $objMdUtlHistControleDsmpRN  = new MdUtlHistControleDsmpRN();
        $objMdUtlHistControleDsmpDTO  = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->setNumIdMdUtlAdmFila($idFila);
        $possuiVinculoHistorico = $objMdUtlHistControleDsmpRN->contar($objMdUtlHistControleDsmpDTO) > 0;
    }

    if($possuiVinculoControleDsmp || $possuiVinculoHistorico){
      $objInfraException= new InfraException();
      $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_21, array($acao));
      $objInfraException->lancarValidacao($msg);
      return true;
    }

    return false;
    
  }

  protected  function validarExclusaoFilaConectado($arrPrm){

    $idFila         = array_key_exists(0, $arrPrm) ? $arrPrm[0] : null;
    $idTipoControle = array_key_exists(1, $arrPrm) ? $arrPrm[1] : null;
    $isExcluir      = array_key_exists(2, $arrPrm) ? $arrPrm[2] : null;

    $isFilaPadrao         = $this->_filaIsFilaPadraoTipoControle($idFila, $idTipoControle, $isExcluir);

      if (!$isFilaPadrao) {
          $isFilaGrupoAtividade = $this->_filaIsGrupoAtividade($idFila, $isExcluir);

          if (!$isFilaGrupoAtividade) {
              $isFilaDistribuicao = $this->_filaIsDistribuicao($idFila);

              if (!$isFilaDistribuicao) {
                  $isFilaCtrlProcessos = $this->_filaIsControleProcesso($idFila, $isExcluir);

                  if (!$isFilaCtrlProcessos) {
                      $isFilaAssociadoAnalise = $this->_filaIsAnalise($idFila);

                      if (!$isFilaAssociadoAnalise) {
                          $isFilaAssociadoTriagem = $this->_filaIsTriagem($idFila);

                          if (!$isFilaAssociadoTriagem) {
                              return true;
                          }
                      }

                  }
              }

          }
    }else{
      return false;
    }
  }

  private function _filaIsTriagem($idFila){
      $objTriagemDTO = new MdUtlTriagemDTO();
      $objTriagemDTO->setNumIdMdUtlAdmFila($idFila);
      $objTriagemDTO->retTodos();

       $objTriagemRN = new MdUtlTriagemRN();
       $isTriagem = $objTriagemRN->contar($objTriagemDTO) > 0;

      if($isTriagem){
          $objInfraException= new InfraException();
          $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_81, array('excluir'));
          $objInfraException->lancarValidacao($msg);
          return true;
      }

      return $isTriagem;
  }

  private function _filaIsAnalise($idFila){
      $objAnaliseDTO = new MdUtlAnaliseDTO();
      $objAnaliseDTO->setNumIdMdUtlAdmFila($idFila);
      $objAnaliseDTO->retTodos();

      $objAnaliseRN = new MdUtlAnaliseRN();
      $isAnalise = $objAnaliseRN->contar($objAnaliseDTO) > 0;

      if($isAnalise){
          $objInfraException= new InfraException();
          $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_80, array('excluir'));
          $objInfraException->lancarValidacao($msg);
          return true;
      }

      return $objAnaliseRN->contar($objAnaliseDTO) > 0;
  }


  protected function getFilasTipoControleConectado($idTipoControleUtl){

      $mdUtlAdmFilaDTO = new MdUtlAdmFilaDTO();
      $mdUtlAdmFilaRN = new MdUtlAdmFilaRN();
      if(is_array($idTipoControleUtl)){
        $mdUtlAdmFilaDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControleUtl,InfraDTO::$OPER_IN);
      }else{
        $mdUtlAdmFilaDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControleUtl);
      }      
      $mdUtlAdmFilaDTO->setStrSinAtivo('S');
      $mdUtlAdmFilaDTO->retStrNome();
      $mdUtlAdmFilaDTO->retNumIdMdUtlAdmFila();
      $mdUtlAdmFilaDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
      $mdUtlAdmFila = $mdUtlAdmFilaRN->listar($mdUtlAdmFilaDTO);

      return $mdUtlAdmFila;
  }

  protected function getFilasVinculadosUsuarioConectado($idTipoControle){

    //Filas do Tipo de Controle
    $objMdUtlAdmFilaPrmGrUsuRN = new MdUtlAdmFilaPrmGrUsuRN();
    $arrObjsFilaDTO = InfraArray::converterArrInfraDTO( $this->getFilasTipoControle($idTipoControle) , 'IdMdUtlAdmFila' );

    // Filas que o usuario eh membro - 1 regra do retorno das filas
    $arrObjsFilas = $objMdUtlAdmFilaPrmGrUsuRN->getPapeisDeUsuario($arrObjsFilaDTO);

    $arrObjsFilaUsuDTO = array();

    if( !is_null( $arrObjsFilas ) ){
      $arrObjsFilaUsuDTO = InfraArray::converterArrInfraDTO( $arrObjsFilas , 'IdMdUtlAdmFila');
    }
    
    $arrIdsFila = $arrObjsFilaUsuDTO;

    if( count($arrIdsFila) > 0 ){
      $objFilaDTO = new MdUtlAdmFilaDTO();
      $objFilaDTO->setNumIdMdUtlAdmFila( $arrIdsFila , InfraDTO::$OPER_IN );
      $objFilaDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objFilaDTO->retNumIdMdUtlAdmFila();
      $objFilaDTO->retStrNome();

      return $this->listar($objFilaDTO);
    }else{
      return $arrIdsFila; // retorna array vazio
    }
  }

    protected function getTempoExecucaoFilaConectado($idFila){
        $objMdUtlAdmFilaDTO = new MdUtlAdmFilaDTO();
        $objMdUtlAdmFilaDTO->setNumIdMdUtlAdmFila($idFila);
        $objMdUtlAdmFilaDTO->retTodos();

        $objFilaDTO = $this->listar($objMdUtlAdmFilaDTO);
        $numRegistros = count($objFilaDTO);
        if($numRegistros > 0){
            for($i = 0; $i < $numRegistros; $i++){
                $TmpExecucao = $objFilaDTO[$i]->getNumTmpExecucaoTriagem();
            }
        }
        return $TmpExecucao;
    }

    protected function getNumPrazoTarefaPorIdFilaConectado($idFila)
    {
        $objMdUtlAdmFilaDTO = new MdUtlAdmFilaDTO();
        $objMdUtlAdmFilaDTO->retNumPrazoTarefa();
        $objMdUtlAdmFilaDTO->setNumIdMdUtlAdmFila($idFila);
        $objMdUtlAdmFilaDTO->setNumMaxRegistrosRetorno(1);

        $objDTO = $this->consultar($objMdUtlAdmFilaDTO);

        return !is_null($objDTO) ? $objDTO->getNumPrazoTarefa() : 0;
    }

    protected function verificaUsuarioLogadoPertenceFilaConectado($arrParams){
        $idFila   = $arrParams[0];
        $idStatus = $arrParams[1];
        $bolFiltraPorPapel = array_key_exists(2,$arrParams) ? true : false;
        $idUsuario         = array_key_exists(3,$arrParams) ? $arrParams[3] : SessaoSEI::getInstance()->getNumIdUsuario();

        $idsStatusTriador = array(MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM, MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM, MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM);
        $idsStatusAnalise = array(MdUtlControleDsmpRN::$AGUARDANDO_ANALISE, MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE, MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE);
        $idsStatusRevisao = array(MdUtlControleDsmpRN::$AGUARDANDO_REVISAO, MdUtlControleDsmpRN::$EM_REVISAO);
        $idsStatusSemBotao =  array(MdUtlControleDsmpRN::$INTERROMPIDO, MdUtlControleDsmpRN::$SUSPENSO);

        if(is_null($idStatus) || in_array($idStatus, $idsStatusSemBotao)){
            return false;
        }
        $objMdRelFilaUsuarioRN  = new MdUtlAdmFilaPrmGrUsuRN();
        $objMdRelFilaUsuarioDTO = new MdUtlAdmFilaPrmGrUsuDTO();
        $objMdRelFilaUsuarioDTO->setNumIdMdUtlAdmFila($idFila);
        $objMdRelFilaUsuarioDTO->setNumIdUsuario( $idUsuario );

        if( $bolFiltraPorPapel ) {
            if (in_array($idStatus, $idsStatusTriador)) {
                $objMdRelFilaUsuarioDTO->setStrSinTriador('S');
            }

            if (in_array($idStatus, $idsStatusAnalise)) {
                $objMdRelFilaUsuarioDTO->setStrSinAnalista('S');
            }

            if (in_array($idStatus, $idsStatusRevisao)) {
                $objMdRelFilaUsuarioDTO->setStrSinRevisor('S');
            }
        }

        return ($objMdRelFilaUsuarioRN->contar($objMdRelFilaUsuarioDTO) > 0);
    }

    public function verificaUsuarioLogadoAvaliador( $idsTpCtrl ){
      // busca as filas relacionadas aos tipos de controles enviados no parametro
      $objFilaDTO = new MdUtlAdmFilaDTO();
      $objFilaDTO->setDistinct(true);
      $objFilaDTO->setNumIdMdUtlAdmTpCtrlDesemp( $idsTpCtrl , InfraDTO::$OPER_IN );
      $objFilaDTO->retNumIdMdUtlAdmFila();

      if( $this->contar( $objFilaDTO ) === 0 ) return [ 'qtdUserAvaliador' => 0 , 'idsTpCtrlUsuarioAvaliador' => [] ];

      $arrFilas = InfraArray::converterArrInfraDTO( $this->listar( $objFilaDTO ) , 'IdMdUtlAdmFila' );

      $objFilaDTO = null;

      // busca as filas onde o usuario eh avaliador
      $objMdRelFilaUsuarioRN  = new MdUtlAdmFilaPrmGrUsuRN();
      $objMdRelFilaUsuarioDTO = new MdUtlAdmFilaPrmGrUsuDTO();
      $objMdRelFilaUsuarioDTO->setDistinct(true);
      $objMdRelFilaUsuarioDTO->setNumIdMdUtlAdmFila( $arrFilas , InfraDTO::$OPER_IN );
      $objMdRelFilaUsuarioDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objMdRelFilaUsuarioDTO->setStrSinRevisor('S');
      $objMdRelFilaUsuarioDTO->retNumIdMdUtlAdmFila();      
      
      // qtd de filas onde o usuario eh avaliador
      $qtdUserAvaliador = $objMdRelFilaUsuarioRN->contar( $objMdRelFilaUsuarioDTO );

      if( $qtdUserAvaliador === 0 ) return [ 'qtdUserAvaliador' => 0 , 'idsTpCtrlUsuarioAvaliador' => [] ];

      // retorna as filas filtradas, de acordo com a consulta anterior, para recuperar os tipos de controles 
      $arrIdsFila = InfraArray::converterArrInfraDTO( $objMdRelFilaUsuarioRN->listar( $objMdRelFilaUsuarioDTO ) , 'IdMdUtlAdmFila' );

      $objFilaDTO = new MdUtlAdmFilaDTO();
      $objFilaDTO->setNumIdMdUtlAdmFila( $arrIdsFila , InfraDTO::$OPER_IN );
      $objFilaDTO->setDistinct( true );
      $objFilaDTO->retNumIdMdUtlAdmTpCtrlDesemp();

      return [
        'qtdUserAvaliador'          => $qtdUserAvaliador , 
        'idsTpCtrlUsuarioAvaliador' => InfraArray::converterArrInfraDTO( $this->listar( $objFilaDTO ) , 'IdMdUtlAdmTpCtrlDesemp' )
      ];
    }

    public function buscaFilasUsuarioAvaliador( $arrIdsPrmGr ){
        $objMdRelFilaUsuarioRN  = new MdUtlAdmFilaPrmGrUsuRN();
        $objMdRelFilaUsuarioDTO = new MdUtlAdmFilaPrmGrUsuDTO();

        #$objMdRelFilaUsuarioDTO->setNumIdMdUtlAdmFila($idFila);
        $objMdRelFilaUsuarioDTO->setNumIdUsuario( SessaoSEI::getInstance()->getNumIdUsuario() );
        $objMdRelFilaUsuarioDTO->setStrSinRevisor('S');
        $objMdRelFilaUsuarioDTO->adicionarCriterio(['IdMdUtlPrmGr'],[InfraDTO::$OPER_IN],[$arrIdsPrmGr]);

        $objMdRelFilaUsuarioDTO->retNumIdMdUtlAdmFila();
        $objMdRelFilaUsuarioDTO->retStrSinRevisor();
        $objMdRelFilaUsuarioDTO->retNumIdMdUtlPrmGr();

        return $objMdRelFilaUsuarioRN->listar( $objMdRelFilaUsuarioDTO );
    }

    protected function pesquisarConectado(MdUtlAdmFilaDTO $objMdUtlAdmFilaDTO) {
        try {

            if ($objMdUtlAdmFilaDTO->isSetStrNome()){
                if (trim($objMdUtlAdmFilaDTO->getStrNome())!=''){
                    $strPalavrasPesquisa = InfraString::transformarCaixaAlta($objMdUtlAdmFilaDTO->getStrNome());
                    $arrPalavrasPesquisa = explode(' ',$strPalavrasPesquisa);

                    for($i=0;$i<count($arrPalavrasPesquisa);$i++){
                        $arrPalavrasPesquisa[$i] = '%'.$arrPalavrasPesquisa[$i].'%';
                    }

                  if (count($arrPalavrasPesquisa)==1){
                        $objMdUtlAdmFilaDTO->setStrNome($arrPalavrasPesquisa[0],InfraDTO::$OPER_LIKE);
                    }else{
                        $objMdUtlAdmFilaDTO->unSetStrNome();
                        $a = array_fill(0,count($arrPalavrasPesquisa),'Nome');
                        $b = array_fill(0,count($arrPalavrasPesquisa),InfraDTO::$OPER_LIKE);
                        $d = array_fill(0,count($arrPalavrasPesquisa)-1,InfraDTO::$OPER_LOGICO_AND);
                        $objMdUtlAdmFilaDTO->adicionarCriterio($a,$b,$arrPalavrasPesquisa,$d);
                    }

                }
            }else {
                $objMdUtlAdmFilaDTO->unSetStrNome();
            }

            if ($objMdUtlAdmFilaDTO->isSetStrDescricao()){
                if (trim($objMdUtlAdmFilaDTO->getStrDescricao())!=''){
                    $strPalavrasPesquisa = InfraString::transformarCaixaAlta($objMdUtlAdmFilaDTO->getStrDescricao());
                    $arrPalavrasPesquisa = explode(' ',$strPalavrasPesquisa);

                    for($i=0;$i<count($arrPalavrasPesquisa);$i++){
                        $arrPalavrasPesquisa[$i] = '%'.$arrPalavrasPesquisa[$i].'%';
                    }

                  if (count($arrPalavrasPesquisa)==1){
                        $objMdUtlAdmFilaDTO->setStrdescricao($arrPalavrasPesquisa[0],InfraDTO::$OPER_LIKE);
                    }else{
                        $objMdUtlAdmFilaDTO->unSetStrDescricao();
                        $a = array_fill(0,count($arrPalavrasPesquisa),'Descricao');
                        $b = array_fill(0,count($arrPalavrasPesquisa),InfraDTO::$OPER_LIKE);
                        $d = array_fill(0,count($arrPalavrasPesquisa)-1,InfraDTO::$OPER_LOGICO_AND);
                        $objMdUtlAdmFilaDTO->adicionarCriterio($a,$b,$arrPalavrasPesquisa,$d);
                    }

                }
            }else {
                $objMdUtlAdmFilaDTO->unSetStrDescricao();
            }


            return $this->listar($objMdUtlAdmFilaDTO);

        }catch(Exception $e){
            throw new InfraException('Erro pesquisando Filas.',$e);
        }
    }
    private function _filaIsDistribuicao($idFila){
        $objMdUtlAdmRelPrmDsFilaDTO = new  MdUtlAdmRelPrmDsFilaDTO();
        $objMdUtlAdmRelPrmDsFilaRN = new  MdUtlAdmRelPrmDsFilaRN();

        $objMdUtlAdmRelPrmDsFilaDTO->setNumIdMdUtlAdmFila($idFila);
        $objMdUtlAdmRelPrmDsFilaDTO->retTodos();

       $isDistribuicao = $objMdUtlAdmRelPrmDsFilaRN->contar($objMdUtlAdmRelPrmDsFilaDTO) > 0;

        if($isDistribuicao){
            $objInfraException= new InfraException();
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_109, array('excluir'));
            $objInfraException->lancarValidacao($msg);
            return true;
        }

        return $objMdUtlAdmRelPrmDsFilaRN->contar($objMdUtlAdmRelPrmDsFilaDTO) > 0;
    }
}
