<?
/**
*
* 08/05/2019 - criado por jaqueline.mendes
*
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAjustePrazoRN extends InfraRN {

  public static $PENDENTE_RESPOSTA = 'P';
  public static $STR_PENDENTE_RESPOSTA = 'Pendente de Resposta do Gestor';

  public static $APROVADA = 'A';
  public static $STR_APROVADA = 'Aprovada';

  public static $REPROVADA = 'R';
  public static $STR_REPROVADA = 'Reprovada';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrSinAtivo(MdUtlAjustePrazoDTO $objMdUtlAjustePrazoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAjustePrazoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdUtlAjustePrazoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(MdUtlAjustePrazoDTO $objMdUtlAjustePrazoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_ajuste_prazo_cadastrar', __METHOD__, $objMdUtlAjustePrazoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrSinAtivo($objMdUtlAjustePrazoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmJustPrazoBD->cadastrar($objMdUtlAjustePrazoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Justificativa de Dilação de Prazo.',$e);
    }
  }

  protected function alterarControlado(MdUtlAjustePrazoDTO $objMdUtlAjustePrazoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_ajuste_prazo_alterar', __METHOD__. $objMdUtlAjustePrazoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAjustePrazoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objMdUtlAjustePrazoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      $objMdUtlAdmJustPrazoBD->alterar($objMdUtlAjustePrazoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Justificativa de Dilação de Prazo.',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAjustePrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_ajuste_prazo_excluir', __METHOD__, $arrObjMdUtlAjustePrazoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAjustePrazoDTO);$i++){
        $objMdUtlAdmJustPrazoBD->excluir($arrObjMdUtlAjustePrazoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Justificativa de Dilação de Prazo.',$e);
    }
  }

  protected function consultarConectado(MdUtlAjustePrazoDTO $objMdUtlAjustePrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_ajuste_prazo_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmJustPrazoBD->consultar($objMdUtlAjustePrazoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Justificativa de Dilação de Prazo.',$e);
    }
  }

  protected function listarConectado(MdUtlAjustePrazoDTO $objMdUtlAjustePrazoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_ajuste_prazo_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmJustPrazoBD->listar($objMdUtlAjustePrazoDTO);


      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Justificativas de Dilação de Prazo.',$e);
    }
  }

  protected function contarConectado(MdUtlAjustePrazoDTO $objMdUtlAjustePrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_ajuste_prazo_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmJustPrazoBD->contar($objMdUtlAjustePrazoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Justificativas de Dilação de Prazo.',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAjustePrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_ajuste_prazo_desativar',__METHOD__, $arrObjMdUtlAjustePrazoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAjustePrazoDTO);$i++){
        $objMdUtlAdmJustPrazoBD->desativar($arrObjMdUtlAjustePrazoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Justificativa de Dilação de Prazo.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAjustePrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_ajuste_prazo_reativar',__METHOD__, $arrObjMdUtlAjustePrazoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmJustPrazoBD = new MdUtlAdmJustPrazoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAjustePrazoDTO);$i++){
        $objMdUtlAdmJustPrazoBD->reativar($arrObjMdUtlAjustePrazoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Justificativa de Dilação de Prazo.',$e);
    }
  }

    protected function solicitarAjustePrazoControlado($arrParams)
    {

        //Cadastro da solicitação de ajuste;
        $objDTO = $arrParams[0];
        $objDTO = $this->cadastrar($objDTO);

        //Controle do Fluxo de Atendimento;
        $objControleDTO = $arrParams[1];
        $objMdUtlControleDsmpRN = new MdUtlControleDsmpRN();
        $objHistoricoRN = new MdUtlHistControleDsmpRN();

        $isAlterar = array_key_exists(2, $arrParams) ? $arrParams[2] : false;

        $idProcedimento = $objControleDTO->getDblIdProcedimento();
        $idFila = $objControleDTO->getNumIdFila();
        $idTpCtrl = $objControleDTO->getNumIdMdUtlAdmTpCtrlDesemp();
        $strNovoStatus = $objControleDTO->getStrStaAtendimentoDsmp();
        $idTriagem = $objControleDTO->getNumIdMdUtlTriagem();
        $idAnalise = $objControleDTO->getNumIdMdUtlAnalise();
        $idRevisao = $objControleDTO->getNumIdMdUtlRevisao();
        $tempoExecucao = $objControleDTO->getNumTempoExecucao();
        $idUsuarioDistr = $objControleDTO->getNumIdUsuarioDistribuicao();
        $strDetalheAjust = $_POST['hdnDetalheFluxoAtend'];
        $arrIds = array($idProcedimento);
        $arrObjsAtuais = $objMdUtlControleDsmpRN->getObjsAtivosPorProcedimento($arrIds);
        $arrRetorno = $objHistoricoRN->controlarHistoricoDesempenho(array($arrObjsAtuais, $arrIds, 'N', 'N'));
        $dthPrazo = $arrRetorno[$idProcedimento]['DTH_PRAZO_TAREFA'];

        if (!is_null($objControleDTO->getNumIdMdUtlAjustePrazo())) {
            $objAjustPrazoDTODes = new MdUtlAjustePrazoDTO();
            $objAjustPrazoDTODes->setNumIdMdUtlAjustePrazo($objControleDTO->getNumIdMdUtlAjustePrazo());
            $objAjustPrazoDTODes->retTodos();
            $objAjustPrazoDTODes->setNumMaxRegistrosRetorno(1);
            $objAjustPrazoDTODes = $this->consultar($objAjustPrazoDTODes);
            $this->desativar(array($objAjustPrazoDTODes));
        }

        $objMdUtlControleDsmpRN->excluir($arrObjsAtuais);

        $strTipoAcao = $isAlterar ? MdUtlControleDsmpRN::$STR_TIPO_ACAO_ALT_AJUSTE_PRAZO : MdUtlControleDsmpRN::$STR_TIPO_ACAO_CAD_AJUSTE_PRAZO;
        //Cadastrando para essa fila, e esse procedimento e unidade o novo status
        $objControleDesempenhoNovoDTO = $objMdUtlControleDsmpRN->cadastrarNovaSituacaoProcesso(array($idProcedimento, $idFila, $idTpCtrl, $strNovoStatus, null, $tempoExecucao, $idUsuarioDistr, $idTriagem, $idAnalise, $idRevisao, $strDetalheAjust, $strTipoAcao, null, $objDTO->getNumIdMdUtlAjustePrazo(), $dthPrazo));

        $tipoControleDsmpRN = new MdUtlAdmTpCtrlDesempRN();
        $tipoControleDsmpDTO = new MdUtlAdmTpCtrlDesempDTO();
        $tipoControleDsmpDTO->retStrNome();
        $tipoControleDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);

        $objTipoControleDsmpDTO = $tipoControleDsmpRN->listar($tipoControleDsmpDTO);

        $acaoEmail = $isAlterar ? 'alterada' : 'incluída';
        $arrDadosEmail = array(
            'acao_email' => $acaoEmail,
            'tipo' => 'Solicitação de Ajuste de Prazo',
            'id_tipo' => $idTpCtrl,
            'protocolo_formatado' => $objControleDTO->getStrProtocoloProcedimentoFormatado(),
            'nome_controle' => $objTipoControleDsmpDTO[0]->getStrNome(),
        );
        $objGestaoAjustePrazoRN = new MdUtlGestaoAjustPrazoRN();
        $objGestaoAjustePrazoRN->getGestoresTpControle($arrDadosEmail);

        return $objControleDesempenhoNovoDTO;
    }
  
}
