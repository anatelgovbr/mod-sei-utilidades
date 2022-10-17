<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/07/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmTpCtrlDesempRN extends InfraRN
{

  public function __construct()
  {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco()
  {
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdUtlAdmTpCtrlDesemp(MdUtlAdmTpCtrlDesempDTO $objMdUtlAdmTpCtrlDesempDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objMdUtlAdmTpCtrlDesempDTO->getNumIdMdUtlAdmTpCtrlDesemp())) {
      $objInfraException->adicionarValidacao('Id do Tipo de Controle não Informado.');
    }
  }


  private function validarNumIdMdUtlAdmPrmGr(MdUtlAdmTpCtrlDesempDTO $objMdUtlAdmTpCtrlDesempDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objMdUtlAdmTpCtrlDesempDTO->getNumIdMdUtlAdmPrmGr())) {
      $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmPrmGr(null);
    }
  }

  private function _existeRegistroDuplicado($objMdUtlAdmTpCtrlDesempDTO)
  {
    $nome = $objMdUtlAdmTpCtrlDesempDTO->getStrNome();

    $objMdUtlAdmTpCtrlDesempDTO2 = new MdUtlAdmTpCtrlDesempDTO();
    $objMdUtlAdmTpCtrlDesempDTO2->setStrNome($nome);

    if (!is_null($objMdUtlAdmTpCtrlDesempDTO->getNumIdMdUtlAdmTpCtrlDesemp())) {
      $objMdUtlAdmTpCtrlDesempDTO2->setNumIdMdUtlAdmTpCtrlDesemp($objMdUtlAdmTpCtrlDesempDTO->getNumIdMdUtlAdmTpCtrlDesemp(), InfraDTO::$OPER_DIFERENTE);
    }

    return $this->contar($objMdUtlAdmTpCtrlDesempDTO2) > 0;
  }

  private function validarStrNome(MdUtlAdmTpCtrlDesempDTO $objMdUtlAdmTpCtrlDesempDTO, InfraException $objInfraException)
  {
      if (strlen($objMdUtlAdmTpCtrlDesempDTO->getStrNome()) > 50) {
        $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Nome', '50'));
        $objInfraException->adicionarValidacao($msg);
      }

    if ($this->_existeRegistroDuplicado($objMdUtlAdmTpCtrlDesempDTO)) {
        $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_07,'Tipo de Controle');
        $objInfraException->adicionarValidacao($msg);
    }
  }

  private function validarStrDescricao(MdUtlAdmTpCtrlDesempDTO $objMdUtlAdmTpCtrlDesempDTO, InfraException $objInfraException)
  {
    $objMdUtlAdmTpCtrlDesempDTO->setStrDescricao(trim($objMdUtlAdmTpCtrlDesempDTO->getStrDescricao()));

    if (strlen($objMdUtlAdmTpCtrlDesempDTO->getStrDescricao()) > 250) {
        $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Descrição', '250'));
        $objInfraException->adicionarValidacao($msg);
     }
  }


  protected function excluirRelacionamentosControlado($idTipoDeControle)
  {
    $objMdUtlRelTipoControleUtilidadesDTO = new MdUtlAdmRelTpCtrlDesempUndDTO();
    $objMdUtlRelTipoControleUtilidadesDTO->retTodos();
    $objMdUtlRelTipoControleUtilidadesDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoDeControle);

    $objMdUtlRelTipoControleUnidadeRN = new MdUtlAdmRelTpCtrlDesempUndRN();
    $arrObjMdUtlRelTipoControleUnidadeDTO = $objMdUtlRelTipoControleUnidadeRN->listar($objMdUtlRelTipoControleUtilidadesDTO);
    $objMdUtlRelTipoControleUnidadeRN->excluir($arrObjMdUtlRelTipoControleUnidadeDTO);

    //apagando Gestores
    $objMdUtlRelTipoControleUsuarioDTO = new MdUtlAdmRelTpCtrlDesempUsuDTO();
    $objMdUtlRelTipoControleUsuarioDTO->retTodos();
    $objMdUtlRelTipoControleUsuarioDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoDeControle);

    $objMdUtlRelTipoControleUsuarioRN = new MdUtlAdmRelTpCtrlDesempUsuRN();
    $arrObjMdUtlRelTipoControleUsuarioDTO = $objMdUtlRelTipoControleUsuarioRN->listar($objMdUtlRelTipoControleUsuarioDTO);
    $objMdUtlRelTipoControleUsuarioRN->excluir($arrObjMdUtlRelTipoControleUsuarioDTO);
  }


  protected function cadastrarRelacionamentosControlado($obj)
  {
    $id = $obj->getNumIdMdUtlAdmTpCtrlDesemp();

    //criando os relacionamentos
    $arrGestores = $obj->getArrObjRelTipoControleUtilidadesUsuarioDTO();
    $arrUnidades = $obj->getArrObjRelTipoControleUtilidadesUnidadeDTO();

    $objRelTipoControleUtilidadesUsuarioRN = new MdUtlAdmRelTpCtrlDesempUsuRN();
    $objRelTipoControleUtilidadesUnidadeRN = new MdUtlAdmRelTpCtrlDesempUndRN();

    //salvar os gestores
    foreach ($arrGestores as $objRelTipoControleUtilidadesUsuarioDTO) {
      $objRelTipoControleUtilidadesUsuarioDTO->setNumIdMdUtlAdmTpCtrlDesemp($id);
      $objRelTipoControleUtilidadesUsuarioRN->cadastrar($objRelTipoControleUtilidadesUsuarioDTO);
    }

    //salvar as unidades associadas
    foreach ($arrUnidades as $objRelTipoControleUtilidadeUnidadeDTO) {
      $objRelTipoControleUtilidadeUnidadeDTO->setNumIdMdUtlAdmTpCtrlDesemp($id);
      $objRelTipoControleUtilidadesUnidadeRN->cadastrar($objRelTipoControleUtilidadeUnidadeDTO);
    }

    return $obj;
  }

  protected function cadastrarControlado(MdUtlAdmTpCtrlDesempDTO $objMdUtlAdmTpCtrlDesempDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_ctrl_desemp_cadastrar', __METHOD__, $objMdUtlAdmTpCtrlDesempDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objMdUtlAdmTpCtrlDesempDTO, $objInfraException);
      $this->validarStrDescricao($objMdUtlAdmTpCtrlDesempDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmTpCtrlDesempBD = new MdUtlAdmTpCtrlDesempBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpCtrlDesempBD->cadastrar($objMdUtlAdmTpCtrlDesempDTO);

      $this->cadastrarRelacionamentos($ret);

      //Auditoria
      return $ret;

    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando .', $e);
    }
  }

  protected function alterarControlado(MdUtlAdmTpCtrlDesempDTO $objMdUtlAdmTpCtrlDesempDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_ctrl_desemp_alterar', __METHOD__, $objMdUtlAdmTpCtrlDesempDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdUtlAdmTpCtrlDesemp($objMdUtlAdmTpCtrlDesempDTO, $objInfraException);

      if ($objMdUtlAdmTpCtrlDesempDTO->isSetStrNome()) {
        $this->validarStrNome($objMdUtlAdmTpCtrlDesempDTO, $objInfraException);
      }
      if ($objMdUtlAdmTpCtrlDesempDTO->isSetStrDescricao()) {
        $this->validarStrDescricao($objMdUtlAdmTpCtrlDesempDTO, $objInfraException);
      }


      $objInfraException->lancarValidacoes();

      $objMdUtlAdmTpCtrlDesempBD = new MdUtlAdmTpCtrlDesempBD($this->getObjInfraIBanco());
      $objMdUtlAdmTpCtrlDesempBD->alterar($objMdUtlAdmTpCtrlDesempDTO);

      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro alterando .', $e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmTpCtrlDesempDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_ctrl_desemp_excluir', __METHOD__, $arrObjMdUtlAdmTpCtrlDesempDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpCtrlDesempBD = new MdUtlAdmTpCtrlDesempBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjMdUtlAdmTpCtrlDesempDTO); $i++) {
        $this->excluirRelacionamentos($arrObjMdUtlAdmTpCtrlDesempDTO[$i]->getNumIdMdUtlAdmTpCtrlDesemp());
        $objMdUtlAdmTpCtrlDesempBD->excluir($arrObjMdUtlAdmTpCtrlDesempDTO[$i]);
      }

      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro excluindo .', $e);
    }
  }

  protected function consultarConectado(MdUtlAdmTpCtrlDesempDTO $objMdUtlAdmTpCtrlDesempDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_ctrl_desemp_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpCtrlDesempBD = new MdUtlAdmTpCtrlDesempBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpCtrlDesempBD->consultar($objMdUtlAdmTpCtrlDesempDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando .', $e);
    }
  }

  protected function listarConectado(MdUtlAdmTpCtrlDesempDTO $objMdUtlAdmTpCtrlDesempDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_ctrl_desemp_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpCtrlDesempBD = new MdUtlAdmTpCtrlDesempBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpCtrlDesempBD->listar($objMdUtlAdmTpCtrlDesempDTO);
      //Auditoria
      return $ret;

    } catch (Exception $e) {
      throw new InfraException('Erro listando .', $e);
    }
  }

  protected function contarConectado(MdUtlAdmTpCtrlDesempDTO $objMdUtlAdmTpCtrlDesempDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_tp_ctrl_desemp_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpCtrlDesempBD = new MdUtlAdmTpCtrlDesempBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmTpCtrlDesempBD->contar($objMdUtlAdmTpCtrlDesempDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando .', $e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAdmTpCtrlDesempDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_ctrl_desemp_desativar', __METHOD__, $arrObjMdUtlAdmTpCtrlDesempDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpCtrlDesempBD = new MdUtlAdmTpCtrlDesempBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjMdUtlAdmTpCtrlDesempDTO); $i++) {
        $objMdUtlAdmTpCtrlDesempBD->desativar($arrObjMdUtlAdmTpCtrlDesempDTO[$i]);
      }

      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro desativando .', $e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmTpCtrlDesempDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_tp_ctrl_desemp_reativar', __METHOD__, $arrObjMdUtlAdmTpCtrlDesempDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmTpCtrlDesempBD = new MdUtlAdmTpCtrlDesempBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjMdUtlAdmTpCtrlDesempDTO); $i++) {
        $objMdUtlAdmTpCtrlDesempBD->reativar($arrObjMdUtlAdmTpCtrlDesempDTO[$i]);
      }

      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro reativando .', $e);
    }
  }

  protected function buscarObjTpControlePorIdConectado($id)
  {
    try {

      if (is_null($id) || $id == '') {
        $objInfraException = new InfraException();
        $objInfraException->lancarValidacao('O Id do Tipo de Controle não pode ser nulo!');
      }

      $objMdUtlTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
      $objMdUtlTpCtrlDTO->setNumIdMdUtlAdmTpCtrlDesemp($id);
      $objMdUtlTpCtrlDTO->setNumMaxRegistrosRetorno(1);
      $objMdUtlTpCtrlDTO->retTodos();
      
      return $this->consultar($objMdUtlTpCtrlDTO);

    } catch (Exception $e) {
      throw new InfraException('Erro bloqueando .', $e);
    }
  }

  protected function verificaTipoControlePossuiParametrizacaoConectado($idTipoControle)
  {

    if (!is_null($idTipoControle) && $idTipoControle != '') {
      $objMdUtlAdmTpControleDTO = new MdUtlAdmTpCtrlDesempDTO();
      $objMdUtlAdmTpControleRN = new MdUtlAdmTpCtrlDesempRN();
      $objMdUtlAdmTpControleDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
      $objMdUtlAdmTpControleDTO->retNumIdMdUtlAdmPrmGr();
      $objMdUtlAdmTpControleDTO->setNumMaxRegistrosRetorno(1);
      $objMdUtlAdmTpControleDTO = $objMdUtlAdmTpControleRN->consultar($objMdUtlAdmTpControleDTO);
    }

    return !is_null($objMdUtlAdmTpControleDTO->getNumIdMdUtlAdmPrmGr());
  }

  private function _validarFila($idTpCtrl, $acao)
  {
    $objMdUtlAdmFilaDTO = new MdUtlAdmFilaDTO();
    $objMdUtlAdmFilaRN = new MdUtlAdmFilaRN();

    $objMdUtlAdmFilaDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
    $objMdUtlAdmFilaDTO->retNumIdMdUtlAdmFila();
    $objMdUtlAdmFilaDTO->retStrNome();
    $objMdUtlAdmFilaDTO->setBolExclusaoLogica(false);

    $count = $objMdUtlAdmFilaRN->contar($objMdUtlAdmFilaDTO);

    if ($count > 0) {
      $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_04, array($acao, 'Fila'));
      $objInfraException = new InfraException();
      $objInfraException->lancarValidacao($msg);
      return false;
    }

    return true;
  }

  private function _validarTpProduto($idTpCtrl, $acao)
  {
    $objMdUtlAdmTpProdutoRN = new MdUtlAdmTpProdutoRN();
    $objMdUtlAdmTpProdutoDTO = new MdUtlAdmTpProdutoDTO();

    $objMdUtlAdmTpProdutoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
    $objMdUtlAdmTpProdutoDTO->retTodos();
    $objMdUtlAdmTpProdutoDTO->setBolExclusaoLogica(false);

    $contar = $objMdUtlAdmTpProdutoRN->contar($objMdUtlAdmTpProdutoDTO);

    if ($contar > 0) {
      $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_05, array($acao, 'Tipo de Produto'));
      $objInfraException = new InfraException();
      $objInfraException->lancarValidacao($msg);
      return false;
    }

    return true;
  }

  private function _validarAtividade($idTpCtrl, $acao)
  {
    $objMdUtlAdmAtividadeRN = new MdUtlAdmAtividadeRN();
    $objMdUtlAdmAtividadeDTO = new MdUtlAdmAtividadeDTO();

    $objMdUtlAdmAtividadeDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
    $objMdUtlAdmAtividadeDTO->setBolExclusaoLogica(false);
    $objMdUtlAdmAtividadeDTO->retTodos();

    $count = $objMdUtlAdmAtividadeRN->contar($objMdUtlAdmAtividadeDTO);

    if ($count > 0) {
      $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_04, array($acao, 'Atividade'));
      $objInfraException = new InfraException();
      $objInfraException->lancarValidacao($msg);
      return false;
    }

    return true;
  }

  protected function verificarVinculosConectado($params)
  {

    $idTpCtrl = $params[0];
    $acao = $params[1];

    $jornadaValido = $this->_validarJornada($idTpCtrl, $acao);
    if (!$jornadaValido) {
      return $jornadaValido;
    }

    $filaValido = $this->_validarFila($idTpCtrl, $acao);
    if (!$filaValido) {
      return $filaValido;
    }

    $atividadeValido = $this->_validarAtividade($idTpCtrl, $acao);
    if (!$atividadeValido) {
      return $atividadeValido;
    }

    $tpProdutoValido = $this->_validarTpProduto($idTpCtrl, $acao);
    if (!$tpProdutoValido) {
      return $tpProdutoValido;
    }

    $tpRevisaoValido = $this->_validarTpRevisao($idTpCtrl, $acao);
    if (!$tpRevisaoValido) {
      return $tpRevisaoValido;
    }

    $justificativaDilacao = $this->_validarJustificativaDilacao($idTpCtrl,$acao);
    if (!$justificativaDilacao) {
      return $justificativaDilacao;
    }
    $tpJustificativaValido = $this->_validarTpJustificativa($idTpCtrl, $acao);
    if (!$tpJustificativaValido) {
      return $tpJustificativaValido;
    }
    $tpJustContestacao = $this->_validarTpJustContestacao($idTpCtrl,$acao);
     if (!$tpJustContestacao) {
         return $tpJustContestacao;
     }

    return true;
  }

  private function _validarTpJustificativa($idTpCtrl, $acao)
  {
    $objMdUtlAdmJustRevisaoRN = new MdUtlAdmTpJustRevisaoRN();
    $objMdUtlAdmJustRevisaoDTO = new MdUtlAdmTpJustRevisaoDTO();

    $objMdUtlAdmJustRevisaoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
    $objMdUtlAdmJustRevisaoDTO->retTodos();
    $objMdUtlAdmJustRevisaoDTO->setBolExclusaoLogica(false);

    $count = $objMdUtlAdmJustRevisaoRN->contar($objMdUtlAdmJustRevisaoDTO);

    if ($count > 0) {
      $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_04, array($acao, 'Justificativa de Avaliação'));
      $objInfraException = new InfraException();
      $objInfraException->lancarValidacao($msg);
      return false;
    }

    return true;
  }

  private function _validarTpJustContestacao($idTpCtrl, $acao){
      $mdUtlAdmJustContestDTO = new MdUtlAdmJustContestDTO();
      $mdUtlAdmJustContestRN  = new MdUtlAdmJustContestRN();

      $mdUtlAdmJustContestDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
      $mdUtlAdmJustContestDTO->retTodos();

      $count = $mdUtlAdmJustContestRN->contar($mdUtlAdmJustContestDTO);

      if($count > 0){
          $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_04, array($acao, 'Justificativa de Contestação'));
          $objInfraException = new InfraException();
          $objInfraException->lancarValidacao($msg);
          return false;
      }
      return true;
  }

  private function _validarJustificativaDilacao($idTpCtrl, $acao)
  {
      $mdUtlAdmJustPrazoDTO = new MdUtlAdmJustPrazoDTO();
      $mdUtlAdmJustPrazoRN  = new MdUtlAdmJustPrazoRN();

      $mdUtlAdmJustPrazoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
      $mdUtlAdmJustPrazoDTO->retTodos();

      $count = $mdUtlAdmJustPrazoRN->contar($mdUtlAdmJustPrazoDTO);

      if ($count > 0){
          $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_04, array($acao, 'Justificativa de Dilação de Prazo'));
          $objInfraException = new InfraException();
          $objInfraException->lancarValidacao($msg);
          return false;
      }
      return true;
  }

  private function _validarTpRevisao($idTpCtrl, $acao)
  {
    $objMdUtlRevisaoRN = new MdUtlAdmTpRevisaoRN();
    $objMdUtlRevisaoDTO = new MdUtlAdmTpRevisaoDTO();

    $objMdUtlRevisaoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
    $objMdUtlRevisaoDTO->retNumIdMdUtlAdmTpRevisao();
    $objMdUtlRevisaoDTO->retStrNome();
    $objMdUtlRevisaoDTO->setBolExclusaoLogica(false);

    $count = $objMdUtlRevisaoRN->contar($objMdUtlRevisaoDTO);

    if ($count > 0) {
      $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_05, array($acao, 'Resultado de Avaliação'));
      $objInfraException = new InfraException();
      $objInfraException->lancarValidacao($msg);
      return false;
    }

    return true;
  }

  private function _validarJornada($idTpCtrl, $acao)
  {
    // Verifica se Existe algum tipo de jornada vinculada ao tipo de controle
    $objMdUtlAdmJornadaRN = new MdUtlAdmJornadaRN();
    $objMdUtlAdmJornadaDTO = new MdUtlAdmJornadaDTO();

    $objMdUtlAdmJornadaDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
    $objMdUtlAdmJornadaDTO->retNumIdMdUtlAdmJornada();
    $objMdUtlAdmJornadaDTO->retStrNome();
    $objMdUtlAdmJornadaDTO->setBolExclusaoLogica(false);

    $count = $objMdUtlAdmJornadaRN->contar($objMdUtlAdmJornadaDTO);

    if ($count > 0) {
      $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_04, array($acao, 'Jornada'));
      $objInfraException = new InfraException();
      $objInfraException->lancarValidacao($msg);
      return false;
    }

    return true;
  }

  protected function buscarTpCtrlTpProdutoCadastradoConectado($arrObj)
  {
    $arrRetorno = array();
    $idsTpCtrl = InfraArray::converterArrInfraDTO($arrObj, 'IdMdUtlAdmTpCtrlDesemp');
    $objMdUtlAdmTpProdutoRN = new MdUtlAdmTpProdutoRN();
    $objMdUtlAdmTpProdutoDTO = new MdUtlAdmTpProdutoDTO();
    $objMdUtlAdmTpProdutoDTO->setNumIdMdUtlAdmTpCtrlDesemp($idsTpCtrl, InfraDTO::$OPER_IN);
    $objMdUtlAdmTpProdutoDTO->retNumIdMdUtlAdmTpCtrlDesemp();
    $arrObjsProdutoDTO = $objMdUtlAdmTpProdutoRN->listar($objMdUtlAdmTpProdutoDTO);

    $idsTpCtrlProduto = InfraArray::converterArrInfraDTO($arrObjsProdutoDTO, 'IdMdUtlAdmTpCtrlDesemp');
    $idsTpCtrlProduto = count($idsTpCtrlProduto) > 0 ? array_unique($idsTpCtrlProduto) : array();

    foreach ($idsTpCtrl as $idTipoControle) {
      $arrRetorno[$idTipoControle] = in_array($idTipoControle, $idsTpCtrlProduto);
    }

    return $arrRetorno;
  }

  public function _getIdsParamsTpControle($idsTpControle)
  {
    $objMdUtlAdmTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
    $objMdUtlAdmTpCtrlDTO->setNumIdMdUtlAdmTpCtrlDesemp($idsTpControle, InfraDTO::$OPER_IN);
    $objMdUtlAdmTpCtrlDTO->retNumIdMdUtlAdmPrmGr();
    $objMdUtlAdmTpCtrlDTO->setNumIdMdUtlAdmPrmGr(null, InfraDTO::$OPER_DIFERENTE);
    $count = $this->contar($objMdUtlAdmTpCtrlDTO);
    if ($count > 0) {
      $arrObjs = $this->listar($objMdUtlAdmTpCtrlDTO);
      $idsParams = InfraArray::converterArrInfraDTO($arrObjs, 'IdMdUtlAdmPrmGr');

      return $idsParams;
    }

    return false;
  }

  private function _excluirUsuariosPrmGr($idsParams)
  {
    $objMdUtlAdmPrmUsuRN = new MdUtlAdmPrmGrUsuRN();
    $objMdUtlAdmPrmUsuDTO = new MdUtlAdmPrmGrUsuDTO();
    $objMdUtlAdmPrmUsuDTO->setNumIdMdUtlAdmPrmGr($idsParams, InfraDTO::$OPER_IN);
    $objMdUtlAdmPrmUsuDTO->retTodos();

    $count = $objMdUtlAdmPrmUsuRN->contar($objMdUtlAdmPrmUsuDTO);

    if ($count > 0) {
      $arrObjs = $objMdUtlAdmPrmUsuRN->listar($objMdUtlAdmPrmUsuDTO);
      $objMdUtlAdmPrmUsuRN->excluir($arrObjs);
    }
  }

  private function _excluirRelPrmDsProc($idsParams)
  {
      $objMdUtlAdmRelPrmDsProcRN = new MdUtlAdmRelPrmDsProcRN();
      $objMdUtlAdmRelPrmDsProcDTO = new MdUtlAdmRelPrmDsProcDTO();
      $objMdUtlAdmRelPrmDsProcDTO->setNumIdMdUtlAdmParamDs($idsParams, InfraDTO::$OPER_IN);
      $objMdUtlAdmRelPrmDsProcDTO->retTodos();

      $count = $objMdUtlAdmRelPrmDsProcRN->contar($objMdUtlAdmRelPrmDsProcDTO);

      if ($count > 0) {
          $arrObjs = $objMdUtlAdmRelPrmDsProcRN->listar($objMdUtlAdmRelPrmDsProcDTO);
          $objMdUtlAdmRelPrmDsProcRN->excluir($arrObjs);
      }
  }

  private function _excluirTpProcPrmGr($idsParams){
    $objMdUtlAdmPrmProcRN = new MdUtlAdmRelPrmGrProcRN();
    $objMdUtlAdmPrmProcDTO = new MdUtlAdmRelPrmGrProcDTO();
    $objMdUtlAdmPrmProcDTO->setNumIdMdUtlAdmParamGr($idsParams, InfraDTO::$OPER_IN);
    $objMdUtlAdmPrmProcDTO->retTodos();

    $count = $objMdUtlAdmPrmProcRN->contar($objMdUtlAdmPrmProcDTO);

    if ($count > 0) {
      $arrObjs = $objMdUtlAdmPrmProcRN->listar($objMdUtlAdmPrmProcDTO);
      $objMdUtlAdmPrmProcRN->excluir($arrObjs);
    }
  }


  private function _excluirPrm($idsParams){
    $objMdUtlAdmPrmRN = new MdUtlAdmPrmGrRN();
    $objMdUtlAdmPrmDTO = new MdUtlAdmPrmGrDTO();
    $objMdUtlAdmPrmDTO->setNumIdMdUtlAdmPrmGr($idsParams, InfraDTO::$OPER_IN);
    $objMdUtlAdmPrmDTO->retTodos();

    $count = $objMdUtlAdmPrmRN->contar($objMdUtlAdmPrmDTO);

    if ($count > 0) {
      $arrObjs = $objMdUtlAdmPrmRN->listar($objMdUtlAdmPrmDTO);
      $objMdUtlAdmPrmRN->excluir($arrObjs);
    }
  }

  protected function excluirTipoControleControlado($arrObjsTpControle)
  {
    try {

      $idsTpControle = InfraArray::converterArrInfraDTO($arrObjsTpControle, 'IdMdUtlAdmTpCtrlDesemp');
      $idsParams = $this->_getIdsParamsTpControle($idsTpControle);
      $isParametrizacaoTpCtrl = false;

      //Parametrizacao Tipo de Controle
      if (is_array($idsParams) && !is_null($idsParams))
      {
        $isParametrizacaoTpCtrl = true;
          $this->_excluirRelPrmDsProc($idsParams);
          $this->_excluirUsuariosPrmGr($idsParams);
          $this->_excluirTpProcPrmGr($idsParams);
          $this->_excluirHistoricoUsuarios($idsParams);
      }
      //Parametrização da Contestação
       $this->_excluirPrmContestacao($idsTpControle);
     //Parametrização da Distribuição
      $this->_excluirPrmDistribuicao($idsTpControle);

      $this->excluir($arrObjsTpControle);

      if($isParametrizacaoTpCtrl){
          $this->_excluirPrm($idsParams);
      }


    } catch (Exception $e) {
      PaginaSEI::getInstance()->processarExcecao($e);
    }
  }

  public function verificarVinculosUnidade($idsRemovidos, $idTipoControleDsmp){

      if(count($idsRemovidos) > 0) {
          $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
          $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
          $objMdUtlControleDsmpDTO->setNumIdUnidade($idsRemovidos, InfraDTO::$OPER_IN);
          $objMdUtlControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControleDsmp);
          $objMdUtlControleDsmpDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);
          $objMdUtlControleDsmpDTO->retStrSiglaUnidade();
          $objMdUtlControleDsmpDTO->retNumIdUnidade();
          $objMdUtlControleDsmpDTO->retStrProtocoloProcedimentoFormatado();
          $objMdUtlControleDsmpDTO->retStrSiglaUnidade();

          // verifica se tem mais de 15 registros para sinalisar a existencia de mais registros além dos exibidos na tela
          $qtdMdUtlControleDsmp = $objMdUtlControleDsmpRN->contar($objMdUtlControleDsmpDTO);
          $msgFim = '';
          if ($qtdMdUtlControleDsmp > 15) {
              $msgFim = '...';
          }

          // limita a busca para exibir somente 10, que eh o padrao SEI
          $objMdUtlControleDsmpDTO->setNumMaxRegistrosRetorno(15);
          $arrMdUtlControleDsmp = $objMdUtlControleDsmpRN->listar($objMdUtlControleDsmpDTO);

          if (!empty($arrMdUtlControleDsmp)) {

              $idUnidade = current($arrMdUtlControleDsmp)->getNumIdUnidade();
              $maisDeUmaUnidade = false;

              foreach ($arrMdUtlControleDsmp as $objMdUtlControleDsmp) {
                  $msgCorpo .= $objMdUtlControleDsmp->getStrSiglaUnidade() . ": ". $objMdUtlControleDsmp->getStrProtocoloProcedimentoFormatado() . "\n";
                  if ($idUnidade != $objMdUtlControleDsmp->getNumIdUnidade()) {
                      $maisDeUmaUnidade = true;
                  }
              }

              // adequação de texto para plural ou singular
              $msgInicio = "A Unidade não pode ser removida, pois está vinculada com processos em fluxo de atendimento em andamento.\n \n";
              if ($maisDeUmaUnidade) {
                  $msgInicio = "As Unidades não podem ser removidas, pois está vinculada com processos em fluxo de atendimento em andamento.\n \n";
              }

              $msg = $msgInicio . $msgCorpo . $msgFim;

              $objInfraException = new InfraException();
              $objInfraException->lancarValidacao($msg);
              return false;
          }
      }
      return  true;
  }

  public function alterarTipoControle($arrUnidadesOrigin, $objTipoControleUtilidadesDTO){
      $arrUnidades       = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']);
      $arrUnidadesOrigin = InfraArray::converterArrInfraDTO($arrUnidadesOrigin,'IdUnidade');
      $idsRemovidos      = array_diff($arrUnidadesOrigin,$arrUnidades);
      $idsNovos          = array_diff($arrUnidades, $arrUnidadesOrigin);
      $isParametrizado   = false;
      $idTipoControle    = $objTipoControleUtilidadesDTO->getNumIdMdUtlAdmTpCtrlDesemp();
      $idParametro       = null;

      //Validar se já existe uma unidade cadastrada para outro tipo de controle.
      #$objTipoControleUtilidadesUnidadeRN = new MdUtlAdmRelTpCtrlDesempUndRN();
      #$objTipoControleUtilidadesUnidadeRN->validarDuplicidadeUnidade(array($arrUnidades,$_POST['hdnIdTipoControleUtilidades']));

      if ($this->verificarVinculosUnidade($idsRemovidos, $objTipoControleUtilidadesDTO->getNumIdMdUtlAdmTpCtrlDesemp())) {
          $this->excluirRelacionamentos($objTipoControleUtilidadesDTO->getNumIdMdUtlAdmTpCtrlDesemp());
          $this->cadastrarRelacionamentos($objTipoControleUtilidadesDTO);
          $this->alterar($objTipoControleUtilidadesDTO);


          if (!is_null($idTipoControle)) {
              $objMdUtlAdmUtlTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();
              $isParametrizado = $objMdUtlAdmUtlTpCtrlRN->verificaTipoControlePossuiParametrizacao($idTipoControle);


              if ($isParametrizado) {
                  if (count($idsRemovidos) > 0) {
                      $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
                      if(!is_null($idsRemovidos)) {
                          $objMdUtlControleDsmpRN->desativarControleDsmpObjs(array(null, $idsRemovidos));
                      }
                  }
              }
          }
      }
  }

  protected function verificaIdParametrizacaoConectado($idTipoControle){

    if (!is_null($idTipoControle) && $idTipoControle != '') {
      $objMdUtlAdmTpControleDTO = new MdUtlAdmTpCtrlDesempDTO();
      $objMdUtlAdmTpControleRN = new MdUtlAdmTpCtrlDesempRN();
      $objMdUtlAdmTpControleDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
      $objMdUtlAdmTpControleDTO->retNumIdMdUtlAdmPrmGr();
      $objMdUtlAdmTpControleDTO->setNumMaxRegistrosRetorno(1);
      $objMdUtlAdmTpControleDTO = $objMdUtlAdmTpControleRN->consultar($objMdUtlAdmTpControleDTO);
      $idParametro = $objMdUtlAdmTpControleDTO->getNumIdMdUtlAdmPrmGr();
    }

    return $idParametro;
  }

  protected function validaNovosDadosParametrizacaoConectado($idTipoControle){

    $objMdUtlAdmTpControleDTO = new MdUtlAdmTpCtrlDesempDTO();
    $objMdUtlAdmTpControleRN = new MdUtlAdmTpCtrlDesempRN();
    $objMdUtlAdmTpControleDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle , InfraDTO::$OPER_IN);
    $objMdUtlAdmTpControleDTO->retNumIdMdUtlAdmPrmGr();
    $objMdUtlAdmTpControleDTO->retNumIdMdUtlAdmTpCtrlDesemp();
    //$objMdUtlAdmTpControleDTO->setNumMaxRegistrosRetorno(1);

    $objMdUtlAdmTpControleDTO->retStrRespTacitaDilacao();
    $objMdUtlAdmTpControleDTO->retStrRespTacitaInterrupcao();
    $objMdUtlAdmTpControleDTO->retStrRespTacitaSuspensao();

    $objMdUtlAdmTpControleDTO->retStrPrazoMaxSuspensao();
    $objMdUtlAdmTpControleDTO->retStrPrazoMaxInterrupcao();
    $objMdUtlAdmTpControleDTO->setParametroFk(InfraDTO::$TIPO_FK_OBRIGATORIA);

    $objMdUtlAdmTpControleDTO = $objMdUtlAdmTpControleRN->listar($objMdUtlAdmTpControleDTO);
    
    $arrRetorno = array();

    foreach ($objMdUtlAdmTpControleDTO as $k => $v) {
      $isPrazoSusNull =   $v->getStrPrazoMaxSuspensao() == 0 || is_null($v->getStrPrazoMaxSuspensao());
      $isPrazoIntNull =   $v->getStrPrazoMaxInterrupcao() == 0 || is_null($v->getStrPrazoMaxInterrupcao());

      if($isPrazoIntNull || $isPrazoSusNull || is_null($v->getStrRespTacitaSuspensao()) ||  is_null($v->getStrRespTacitaInterrupcao()) || is_null($v->getStrRespTacitaDilacao())) {
        $arrRetorno[$v->getNumIdMdUtlAdmTpCtrlDesemp()] = false;
      }else{
        $arrRetorno[$v->getNumIdMdUtlAdmTpCtrlDesemp()] = true;
      }
    }
    
    return $arrRetorno;
  }

  protected function getTiposProcessoTodosTipoControleConectado()
  {
      $objMdUtlAdmPrmGrProcRN = new MdUtlAdmRelPrmGrProcRN();
      $objMdUtlAdmPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO();
      $objMdUtlAdmPrmGrProcDTO->retNumIdTipoProcedimento();
      $count = $objMdUtlAdmPrmGrProcRN->contar($objMdUtlAdmPrmGrProcDTO);

      if ($count > 0) {
              $idsArr = InfraArray::converterArrInfraDTO($objMdUtlAdmPrmGrProcRN->listar($objMdUtlAdmPrmGrProcDTO), 'IdTipoProcedimento');
              return $idsArr;
      }

      return null;
  }

  private function _retornaTiposProcessoParametrizados(){
      $objMdUtlAdmPrmGrProcRN = new MdUtlAdmRelPrmGrProcRN();
      $objMdUtlAdmPrmGrProcDTO = new MdUtlAdmRelPrmGrProcDTO();
      $objMdUtlAdmPrmGrProcDTO->retTodos();

      $count = $objMdUtlAdmPrmGrProcRN->contar($objMdUtlAdmPrmGrProcDTO);

      if ($count > 0) {
          return $objMdUtlAdmPrmGrProcRN->listar($objMdUtlAdmPrmGrProcDTO);
      }

      return null;
  }

  protected function getObjTipoControlePorPrmConectado(){
      $arrRetorno = array();
      $objMdUtlTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
      $objMdUtlTpCtrlDTO->setNumIdMdUtlAdmPrmGr(null, InfraDTO::$OPER_DIFERENTE);
      $objMdUtlTpCtrlDTO->retNumIdMdUtlAdmTpCtrlDesemp();
      $objMdUtlTpCtrlDTO->retNumIdMdUtlAdmPrmGr();

      $count = $this->contar($objMdUtlTpCtrlDTO);

      $arrObjsPrmDTO = $this->_retornaTiposProcessoParametrizados();

      if($count > 0) {
          $arrObjs = $this->listar($objMdUtlTpCtrlDTO);
          $arrTipoControle = array();
            foreach($arrObjs as $objDTO){
                $arrTipoControle[$objDTO->getNumIdMdUtlAdmPrmGr()] =    $objDTO->getNumIdMdUtlAdmTpCtrlDesemp();
            }

           foreach($arrObjsPrmDTO as $objDTO2){
               $idTipoControle = $arrTipoControle[$objDTO2->getNumIdMdUtlAdmParamGr()];
               $arrRetorno[$idTipoControle][$objDTO2->getNumIdTipoProcedimento()] = $objDTO2->getNumIdMdUtlAdmParamGr();
           }

      }

      return $arrRetorno;
  }

  private function _excluirPrmContestacao($idsTpControle){
      $objMdUtlAdmPrmContestDTO = new MdUtlAdmPrmContestDTO();
      $objMdUtlAdmPrmContestRN = new MdUtlAdmPrmContestRN();

      $objMdUtlAdmPrmContestDTO->setNumIdMdUtlAdmTpCtrlDesemp($idsTpControle, InfraDTO::$OPER_IN);
      $objMdUtlAdmPrmContestDTO->retTodos();

      $count = $objMdUtlAdmPrmContestRN->contar($objMdUtlAdmPrmContestDTO);

      if($count > 0){
          $arrObjExcluir = $objMdUtlAdmPrmContestRN->listar($objMdUtlAdmPrmContestDTO);
          $objMdUtlAdmPrmContestRN->excluir($arrObjExcluir);
      }


  }

  private function _excluirPrmDistribuicao($idsTpControle){
      $objMdUtlAdmPrmDsDTO = new MdUtlAdmPrmDsDTO();
      $objMdUtlAdmPrmDsRN= new MdUtlAdmPrmDsRN();

      $objMdUtlAdmPrmDsDTO->setNumIdMdUtlAdmTpCtrlDesemp($idsTpControle, InfraDTO::$OPER_IN);
      $objMdUtlAdmPrmDsDTO->retTodos();

      $count = $objMdUtlAdmPrmDsRN->contar($objMdUtlAdmPrmDsDTO);

      if($count > 0){
          $arrObjPrmDsDTO = $objMdUtlAdmPrmDsRN->listar($objMdUtlAdmPrmDsDTO);

          $idsPrmDs = InfraArray::converterArrInfraDTO($arrObjPrmDsDTO, 'IdMdUtlAdmPrmDs');

          $objMdUtlAdmRelPrmDsAtenDTO = new MdUtlAdmRelPrmDsAtenDTO();
          $objMdUtlAdmRelPrmDsAtenRN = new MdUtlAdmRelPrmDsAtenRN();

          $objMdUtlAdmRelPrmDsAtenDTO->setNumIdMdUtlAdmParamDs($idsPrmDs,InfraDTO::$OPER_IN);
          $objMdUtlAdmRelPrmDsAtenDTO->retTodos();

          $count = $objMdUtlAdmRelPrmDsAtenRN->contar($objMdUtlAdmRelPrmDsAtenDTO);
          if($count > 0){
              $arrPrmDsAtenDTO = $objMdUtlAdmRelPrmDsAtenRN->listar($objMdUtlAdmRelPrmDsAtenDTO);
              $objMdUtlAdmRelPrmDsAtenRN->excluir($arrPrmDsAtenDTO);
          }
          $objMdUtlAdmPrmDsRN->excluir($arrObjPrmDsDTO);
      }

  }
  private function _excluirHistoricoUsuarios($idsParams){

        $objMdUtlAdmHistPrmGrUsuDTO = new MdUtlAdmHistPrmGrUsuDTO();
        $objMdUtlAdmHistPrmGrUsuRN = new MdUtlAdmHistPrmGrUsuRN();

        $objMdUtlAdmHistPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($idsParams, InfraDTO::$OPER_IN);
        $objMdUtlAdmHistPrmGrUsuDTO->retTodos();

        $count = $objMdUtlAdmHistPrmGrUsuRN->contar($objMdUtlAdmHistPrmGrUsuDTO);

        if($count > 0){
            $arrObjExcluir = $objMdUtlAdmHistPrmGrUsuRN->listar($objMdUtlAdmHistPrmGrUsuDTO);
            $objMdUtlAdmHistPrmGrUsuRN->excluir($arrObjExcluir);
        }
  }

    /**
     * @param MdUtlAdmTpCtrlDesempDTO $objMdUtlAdmTpCtrlDesempDTO
     * @throws InfraException
     */
    protected function incluiridMdUtlAdmPrmGrEmMdUtlAdmTpCtrlDesempControlado(MdUtlAdmTpCtrlDesempDTO $objMdUtlAdmTpCtrlDesempDTO)
    {
        try {

            //TODO
            //fazer refectory para remover o idMdUtlAdmPrmGr da tabela MdUtlAdmTpCtrl e colocar o idMdUtlAdmTpCtrl na
            //tabela MdUtlAdmPrmGr e alterar toda aplicação
            //Apos fazer o refectory remover essa function

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarNumIdMdUtlAdmTpCtrlDesemp($objMdUtlAdmTpCtrlDesempDTO, $objInfraException);

            if ($objMdUtlAdmTpCtrlDesempDTO->isSetStrNome()) {
                $this->validarStrNome($objMdUtlAdmTpCtrlDesempDTO, $objInfraException);
            }
            if ($objMdUtlAdmTpCtrlDesempDTO->isSetStrDescricao()) {
                $this->validarStrDescricao($objMdUtlAdmTpCtrlDesempDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objMdUtlAdmTpCtrlDesempBD = new MdUtlAdmTpCtrlDesempBD($this->getObjInfraIBanco());
            $objMdUtlAdmTpCtrlDesempBD->alterar($objMdUtlAdmTpCtrlDesempDTO);

            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro alterando .', $e);
        }
    }

    public function buscarUnidadesComProcessosEmAndamentoTpControle($idTipoControleDsmp, $arrObjUnidades)
    {
        $arrMdUtlControleDsmpVinculadosProcessos = array();

        foreach ($arrObjUnidades as $objUnidades) {
            $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
            $objMdUtlControleDsmpDTO = new MdUtlControleDsmpDTO();
            $objMdUtlControleDsmpDTO->setNumIdUnidade($objUnidades->getNumIdUnidade());
            $objMdUtlControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControleDsmp);
            $objMdUtlControleDsmpDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);
            $objMdUtlControleDsmpDTO->retStrSiglaUnidade();
            $objMdUtlControleDsmpDTO->retNumIdUnidade();
            $objMdUtlControleDsmpDTO->retStrProtocoloProcedimentoFormatado();
            $objMdUtlControleDsmpDTO->retStrSiglaUnidade();

            $arrMdUtlControleDsmp = $objMdUtlControleDsmpRN->listar($objMdUtlControleDsmpDTO);
            foreach ($arrMdUtlControleDsmp as $objMdUtlControleDsmp) {
                array_push($arrMdUtlControleDsmpVinculadosProcessos, $objMdUtlControleDsmp);
            }
        }

        return $arrMdUtlControleDsmpVinculadosProcessos;
    }

}
