<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/08/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmAtividadeRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdUtlAdmTpCtrlDesemp(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmAtividadeDTO->getNumIdMdUtlAdmTpCtrlDesemp())){
      $objInfraException->adicionarValidacao(' Tipo de Controle não Informado.');
    }
  }

  private function validarStrNome(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO, InfraException $objInfraException){
      $objMdUtlAdmAtividadeDTO->setStrNome(trim($objMdUtlAdmAtividadeDTO->getStrNome()));

      if (strlen($objMdUtlAdmAtividadeDTO->getStrNome())>50){
        $msg =  MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Nome', '50'));
        $objInfraException->adicionarValidacao($msg);
    }
  }

  private function validarStrDescricao(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmAtividadeDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao(' não informad.');
    }else{
      $objMdUtlAdmAtividadeDTO->setStrDescricao(trim($objMdUtlAdmAtividadeDTO->getStrDescricao()));

      if (strlen($objMdUtlAdmAtividadeDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinAnalise(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmAtividadeDTO->getStrSinAnalise())){
      $objInfraException->adicionarValidacao('Sinalizador de  não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdUtlAdmAtividadeDTO->getStrSinAnalise())){
        $objInfraException->adicionarValidacao('Sinalizador de  inválid.');
      }
    }
  }

  private function validarNumUndEsforcoAtv(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmAtividadeDTO->getNumUndEsforcoAtv())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumPrzExecucaoAtv(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmAtividadeDTO->getNumPrzExecucaoAtv())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumUndEsforcoRev(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmAtividadeDTO->getNumUndEsforcoRev())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumPrzRevisaoAtv(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmAtividadeDTO->getNumPrzRevisaoAtv())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarStrSinAtvRevAmostragem(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmAtividadeDTO->getStrSinAtvRevAmostragem())){
      $objInfraException->adicionarValidacao('Sinalizador de  não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdUtlAdmAtividadeDTO->getStrSinAtvRevAmostragem())){
        $objInfraException->adicionarValidacao('Sinalizador de  inválid.');
      }
    }
  }

  private function validarStrSinAtivo(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmAtividadeDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMdUtlAdmAtividadeDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_atividade_cadastrar', __METHOD__, $objMdUtlAdmAtividadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdUtlAdmTpCtrlDesemp($objMdUtlAdmAtividadeDTO, $objInfraException);
      $this->validarStrNome($objMdUtlAdmAtividadeDTO, $objInfraException);
      $this->validarStrDescricao($objMdUtlAdmAtividadeDTO, $objInfraException);
      $this->validarStrSinAnalise($objMdUtlAdmAtividadeDTO, $objInfraException);
      $this->validarNumPrzRevisaoAtv($objMdUtlAdmAtividadeDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmAtividadeBD = new MdUtlAdmAtividadeBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmAtividadeBD->cadastrar($objMdUtlAdmAtividadeDTO);


      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando atividade.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_atividade_alterar', __METHOD__, $objMdUtlAdmAtividadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmAtividadeBD = new MdUtlAdmAtividadeBD($this->getObjInfraIBanco());
      $objMdUtlAdmAtividadeBD->alterar($objMdUtlAdmAtividadeDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando atividade.',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmAtividadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_atividade_excluir', __METHOD__, $arrObjMdUtlAdmAtividadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmAtividadeBD = new MdUtlAdmAtividadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmAtividadeDTO);$i++){
        $objMdUtlAdmAtividadeBD->excluir($arrObjMdUtlAdmAtividadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo atividade.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_atividade_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmAtividadeBD = new MdUtlAdmAtividadeBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmAtividadeBD->consultar($objMdUtlAdmAtividadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando atividade.',$e);
    }
  }

  protected function listarConectado(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_atividade_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmAtividadeBD = new MdUtlAdmAtividadeBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmAtividadeBD->listar($objMdUtlAdmAtividadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando atividades.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_atividade_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmAtividadeBD = new MdUtlAdmAtividadeBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmAtividadeBD->contar($objMdUtlAdmAtividadeDTO);


      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando atividades.',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAdmAtividadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_atividade_desativar', __METHOD__. $arrObjMdUtlAdmAtividadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmAtividadeBD = new MdUtlAdmAtividadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmAtividadeDTO);$i++){
        $objMdUtlAdmAtividadeBD->desativar($arrObjMdUtlAdmAtividadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando atividade.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmAtividadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_atividade_reativar', __METHOD__, $arrObjMdUtlAdmAtividadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmAtividadeBD = new MdUtlAdmAtividadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmAtividadeDTO);$i++){
        $objMdUtlAdmAtividadeBD->reativar($arrObjMdUtlAdmAtividadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando atividade.',$e);
    }
  }
    
  protected function excluirTodosRelacionamentoControlado($params){

      $tpAtividade = $params[0];
      $idAtividade = $params[1];


      $objMdUtlAdmAtividadeDTO = new MdUtlAdmAtividadeDTO();
      $objMdUtlAdmAtividadeDTO->setNumIdMdUtlAdmAtividade($idAtividade);

      if($tpAtividade == 'S'){

          $objMdUtlAdmAtividadeDTO->setNumUndEsforcoRev(null);

          $this->alterar($objMdUtlAdmAtividadeDTO);

      }else{

          $objMdUtlAdmAtividadeDTO->setNumUndEsforcoAtv(null);
          $objMdUtlAdmAtividadeDTO->setNumPrzExecucaoAtv(null);

          $objMdUtlAdmAtividadeDTO->setStrSinAtvRevAmostragem(null);

          $this->alterar($objMdUtlAdmAtividadeDTO);


          $mdUtlAdmAtvSerieProdRN = new MdUtlAdmAtvSerieProdRN();

          $mdUtlAdmAtvSerieProdRN->consultarExcluirVinculos($idAtividade);

      }
  }

  protected function verificarNomeDuplicidadeControlado($params){

      $objInfraException  = new InfraException();

      $nomeAtividade = $params[0];
      $idTpCtrl      = $params[1];
      $idAtividade   = array_key_exists(2,$params)? $params[2] :0;

      $mdUtlAdmAtividadeDTO = new MdUtlAdmAtividadeDTO();
      $mdUtlAdmAtividadeDTO->retTodos();
      $mdUtlAdmAtividadeDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
      $mdUtlAdmAtividadeDTO->setStrNome(trim($nomeAtividade),InfraDTO::$OPER_IGUAL);
      $mdUtlAdmAtividadeDTO->setNumIdMdUtlAdmAtividade($idAtividade,InfraDTO::$OPER_DIFERENTE);
      $mdUtlAdmAtividadeDTO->setBolExclusaoLogica(false);

      if($this->contar($mdUtlAdmAtividadeDTO) > 0) {
         $msg =  MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_08, 'Atividade');
         $objInfraException->lancarValidacao($msg);
         return false;
      }

      return true;
  }

  protected function cadastrarAtividadeControlado($idTipoControle){

      $mdUtlAdmAtividadeDTO   = new MdUtlAdmAtividadeDTO();
      $mdUtlAdmAtvSerieProdRN = new MdUtlAdmAtvSerieProdRN();

      $mdUtlAdmAtividadeDTO->retNumIdMdUtlAdmAtividade();
      $mdUtlAdmAtividadeDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
      $mdUtlAdmAtividadeDTO->setNumIdMdUtlAdmAtividade(null);
      $mdUtlAdmAtividadeDTO->setStrNome($_POST['txtAtividade']);
      $mdUtlAdmAtividadeDTO->setStrDescricao($_POST['txaDescricao']);
      $mdUtlAdmAtividadeDTO->setStrSinAnalise($_POST['rdnTpAtivdade']);

      if($_POST['rdnTpAtivdade']=='S'){

          $mdUtlAdmAtividadeDTO->setNumUndEsforcoAtv($_POST['txtUndEsforco']);
          $mdUtlAdmAtividadeDTO->setNumPrzExecucaoAtv($_POST['txtExecucaoAtividade']);

          if(isset($_POST['chkAtvRevAmost'])) {
              $mdUtlAdmAtividadeDTO->setStrSinAtvRevAmostragem('S');
          }

      }else{
          $mdUtlAdmAtividadeDTO->setNumUndEsforcoRev($_POST['txtRevUnidEsf']);
      }

      $mdUtlAdmAtividadeDTO->setNumPrzRevisaoAtv($_POST['txtRevAtividade']);
      $mdUtlAdmAtividadeDTO->setStrSinAtivo('S');

      $mdUtlAdmAtividade = $this->cadastrar($mdUtlAdmAtividadeDTO);
      if($_POST['rdnTpAtivdade']=='S') {
          $mdUtlAdmAtvSerieProdRN->cadastrarListaProdutosEsperados(array($mdUtlAdmAtividade->getNumIdMdUtlAdmAtividade(), $_POST['hdnTbProdutoEsperado']));
      }
      return $mdUtlAdmAtividade ;

  }

  private function _verificaContinuaAlteracaoAtv($idAtividade){
        $tipoAtvTela = $_POST['rdnTpAtivdade'];

        $objMdUtlAdmAtividadeDTO = new MdUtlAdmAtividadeDTO();
        $objMdUtlAdmAtividadeDTO->setNumIdMdUtlAdmAtividade($idAtividade);
        $objMdUtlAdmAtividadeDTO->retStrSinAnalise();
        $objMdUtlAdmAtividadeDTO->setNumMaxRegistrosRetorno(1);

        $objMdUtlAdmAtividadeDTO = $this->consultar($objMdUtlAdmAtividadeDTO);

        if($objMdUtlAdmAtividadeDTO && $tipoAtvTela != $objMdUtlAdmAtividadeDTO->getStrSinAnalise()){
            $objInfraException = new InfraException();
            $msg =  MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_09);
            $objInfraException->lancarValidacao($msg);
            return false;
        }

        return true;
  }

  protected function alterarAtividadeControlado($params){

      $idAtividade    = $params[0];
      $idTipoControle = $params[1];
      $rdnTpAtividade = $params[2];

      $isContinuaAlteracao = true;
      $objMdUtlCtrlDsmpRN = new MdUtlControleDsmpRN();
      $existeVincAnalise = $objMdUtlCtrlDsmpRN->verificaExisteRelacionamentoAtivoAtividade($idAtividade);

      if($existeVincAnalise){
          $isContinuaAlteracao = $this->_verificaContinuaAlteracaoAtv($idAtividade);
      }

      if($isContinuaAlteracao) {

          $mdUtlAdmAtividadeDTO = new MdUtlAdmAtividadeDTO();

          $mdUtlAdmAtvSerieProdRN = new MdUtlAdmAtvSerieProdRN();
          $mdUtlAdmAtividadeDTO->retNumIdMdUtlAdmAtividade();
          $mdUtlAdmAtividadeDTO->setNumIdMdUtlAdmAtividade($idAtividade);
          $mdUtlAdmAtividadeDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
          $mdUtlAdmAtividadeDTO->setStrNome($_POST['txtAtividade']);
          $mdUtlAdmAtividadeDTO->setStrDescricao($_POST['txaDescricao']);
          $mdUtlAdmAtividadeDTO->setStrSinAnalise($_POST['rdnTpAtivdade']);

          //Remove todos os vinculos anteriores caso haja uma troca no tipo de analises
          if ($rdnTpAtividade != $_POST['rdnTpAtivdade']) {
              $this->excluirTodosRelacionamento(array($_POST['rdnTpAtivdade'], $idAtividade));
          }

          if ($_POST['rdnTpAtivdade'] == 'S') {

              $mdUtlAdmAtividadeDTO->setNumUndEsforcoAtv($_POST['txtUndEsforco']);
              $mdUtlAdmAtividadeDTO->setNumPrzExecucaoAtv($_POST['txtExecucaoAtividade']);

              if (isset($_POST['chkAtvRevAmost'])) {
                  $mdUtlAdmAtividadeDTO->setStrSinAtvRevAmostragem('S');
              }

          } else {
              $mdUtlAdmAtividadeDTO->setNumUndEsforcoRev($_POST['txtRevUnidEsf']);
          }

          $mdUtlAdmAtividadeDTO->setNumPrzRevisaoAtv($_POST['txtRevAtividade']);

          $mdUtlAdmAtividade = $this->alterar($mdUtlAdmAtividadeDTO);

          if ($_POST['rdnTpAtivdade'] == 'S') {
              $mdUtlAdmAtvSerieProdRN->cadastrarListaProdutosEsperados(array($idAtividade, $_POST['hdnTbProdutoEsperado']));
          }

          return $mdUtlAdmAtividadeDTO;
      }

      return null;
  }
    
    protected function verificarUtilizacaoTriagemConectado($idAtividade){
        $isTriagemAtiva = 0;

        $objRelTriagemRN  = new MdUtlRelTriagemAtvRN();
        $objRelTriagemDTO = new MdUtlRelTriagemAtvDTO();
        $objRelTriagemDTO->setNumIdMdUtlAdmAtividade($idAtividade);
        $objRelTriagemDTO->retTodos();
        $isTriagemAtiva =$objRelTriagemRN->contar($objRelTriagemDTO) > 0 ? 1 : 0;

        return $isTriagemAtiva;
    }
    

}
