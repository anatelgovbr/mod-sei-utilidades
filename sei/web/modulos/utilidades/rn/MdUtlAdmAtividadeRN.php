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

  public static $ARR_COMPLEXIDADE = array (
      '0'=>'Baixa',
      '1'=>'Média',
      '2'=>'Alta',
      '3'=>'Muito Baixa', 
      '4'=>'Muito Alta', 
      '5'=>'Especial'
  ) ;

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

      if (strlen($objMdUtlAdmAtividadeDTO->getStrNome())>100){
        $msg =  MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Nome', '100'));
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

  private function validarNumTmpExecucaoAtv(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmAtividadeDTO->getNumTmpExecucaoAtv())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumPrzExecucaoAtv(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmAtividadeDTO->getNumPrzExecucaoAtv())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumTmpExecucaoRev(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmAtividadeDTO->getNumTmpExecucaoRev())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumPrzRevisaoAtv(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmAtividadeDTO->getNumPrzRevisaoAtv())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumComplexidade(MdUtlAdmAtividadeDTO $objMdUtlAdmAtividadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmAtividadeDTO->getNumComplexidade())){
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
      $this->validarNumComplexidade($objMdUtlAdmAtividadeDTO, $objInfraException);
      $this->validarStrSinAnalise($objMdUtlAdmAtividadeDTO, $objInfraException);
      //$this->validarNumPrzRevisaoAtv($objMdUtlAdmAtividadeDTO, $objInfraException); #retirado a obrigatoriedade deste campo

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

          $objMdUtlAdmAtividadeDTO->setNumTmpExecucaoRev(null);

          $this->alterar($objMdUtlAdmAtividadeDTO);

      }else{

          $objMdUtlAdmAtividadeDTO->setNumTmpExecucaoAtv(null);
          $objMdUtlAdmAtividadeDTO->setNumPrzExecucaoAtv(null);

          #$objMdUtlAdmAtividadeDTO->setStrSinAtvRevAmostragem(null);

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
      $complexidade     = $params[2];

      $mdUtlAdmAtividadeDTO = new MdUtlAdmAtividadeDTO();
      $mdUtlAdmAtividadeDTO->retTodos();
      $mdUtlAdmAtividadeDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
      $mdUtlAdmAtividadeDTO->setStrNome(trim($nomeAtividade),InfraDTO::$OPER_IGUAL);
      $mdUtlAdmAtividadeDTO->setNumIdMdUtlAdmAtividade($idAtividade,InfraDTO::$OPER_DIFERENTE);
      $mdUtlAdmAtividadeDTO->setNumComplexidade($complexidade,InfraDTO::$OPER_IGUAL);
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
      $mdUtlAdmAtividadeDTO->setNumComplexidade($_POST['selComplexidade']);
      $mdUtlAdmAtividadeDTO->setStrSinAnalise($_POST['rdnTpAtivdade']);
      $mdUtlAdmAtividadeDTO->setStrSinAtvRevAmostragem('N');
      $mdUtlAdmAtividadeDTO->setStrSinNaoAplicarPercDsmp(isset($_POST['chkNaoAplicarPercDsmp']) ? 'S' : 'N');

      if($_POST['rdnTpAtivdade']=='S'){

          $mdUtlAdmAtividadeDTO->setNumTmpExecucaoAtv($_POST['txtTmpExecucao']);
          $mdUtlAdmAtividadeDTO->setNumPrzExecucaoAtv($_POST['txtExecucaoAtividade']);

          if(isset($_POST['chkAtvRevAmost'])) {
              $mdUtlAdmAtividadeDTO->setStrSinAtvRevAmostragem('S');
          }

      }else{
          $mdUtlAdmAtividadeDTO->setNumTmpExecucaoRev($_POST['txtRevUnidEsf']);

          if(isset($_POST['chkAtvRevAmost'])) {
              $mdUtlAdmAtividadeDTO->setStrSinAtvRevAmostragem('S');
          }
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
          $mdUtlAdmAtividadeDTO->setNumComplexidade($_POST['selComplexidade']);
          $mdUtlAdmAtividadeDTO->setStrSinAnalise($_POST['rdnTpAtivdade']);
          $mdUtlAdmAtividadeDTO->setStrSinNaoAplicarPercDsmp(isset($_POST['chkNaoAplicarPercDsmp']) ? 'S' : 'N');

          //Remove todos os vinculos anteriores caso haja uma troca no tipo de analises
          if ($rdnTpAtividade != $_POST['rdnTpAtivdade']) {
              $this->excluirTodosRelacionamento(array($_POST['rdnTpAtivdade'], $idAtividade));
          }

          if ($_POST['rdnTpAtivdade'] == 'S') {

              $mdUtlAdmAtividadeDTO->setNumTmpExecucaoAtv($_POST['txtTmpExecucao']);
              $mdUtlAdmAtividadeDTO->setNumPrzExecucaoAtv($_POST['txtExecucaoAtividade']);

              if (isset($_POST['chkAtvRevAmost'])) {
                  $mdUtlAdmAtividadeDTO->setStrSinAtvRevAmostragem('S');
              }else{
                  $mdUtlAdmAtividadeDTO->setStrSinAtvRevAmostragem('N');
              }

          } else {
              $mdUtlAdmAtividadeDTO->setNumTmpExecucaoRev($_POST['txtRevUnidEsf']);

              if (isset($_POST['chkAtvRevAmost'])) {
                  $mdUtlAdmAtividadeDTO->setStrSinAtvRevAmostragem('S');
              }else{
                  $mdUtlAdmAtividadeDTO->setStrSinAtvRevAmostragem('N');
              }
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


    protected function preencherCorretamenteHabilitarRevisaoControlado(){
        $objMdUtlAtividadeDTO = new MdUtlAdmAtividadeDTO();
        $objMdUtlAtividadeDTO->setStrSinAtvRevAmostragem(null);
        $objMdUtlAtividadeDTO->retTodos();
        $objMdUtlAtividadeDTO->setBolExclusaoLogica(false);
        $count  = $this->contar($objMdUtlAtividadeDTO);

        if($count > 0) {
            $arrObjs = $this->listar($objMdUtlAtividadeDTO);

            foreach ($arrObjs as $objDTO) {
                $objDTO->setStrSinAtvRevAmostragem('N');
                $this->alterar($objDTO);
            }
        }
    }

    protected function verificaAtividadeDistribuicaoConectado($idAtividade){
        $objMdUtlAdmRelPrmDsAtivDTO = new  MdUtlAdmRelPrmDsAtivDTO();
        $objMdUtlAdmRelPrmDsAtivRN = new  MdUtlAdmRelPrmDsAtivRN();

        $objMdUtlAdmRelPrmDsAtivDTO->setNumIdMdUtlAdmAtividade($idAtividade);
        $objMdUtlAdmRelPrmDsAtivDTO->retTodos();

        $isAtividade = $objMdUtlAdmRelPrmDsAtivRN->contar($objMdUtlAdmRelPrmDsAtivDTO) > 0;

        if($isAtividade){
            $objInfraException= new InfraException();
            $msg = MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_110, array('excluir'));
            $objInfraException->lancarValidacao($msg);
        }
    }
    
    public function getAtividadesParaRetriagem($idsAtividades){
      $objMdUtlAtividadeDTO = new MdUtlAdmAtividadeDTO();
      $arrIds = strpos($idsAtividades,',') > 0 ? explode(',',$idsAtividades) : array($idsAtividades);

      $objMdUtlAtividadeDTO->setNumIdMdUtlAdmAtividade( $arrIds , InfraDTO::$OPER_IN );

      $objMdUtlAtividadeDTO->retNumIdMdUtlAdmAtividade();
      $objMdUtlAtividadeDTO->retStrNome();
      $objMdUtlAtividadeDTO->retStrSinAnalise();
      $objMdUtlAtividadeDTO->retNumTmpExecucaoAtv();
      $objMdUtlAtividadeDTO->retNumComplexidade();
      
      $arrMdUtlAtividadeDTO = $this->listar( $objMdUtlAtividadeDTO );
      $contador = 0;
      $arrGrid  = array();
      $tmpExecucao = 0;

      foreach ( $arrMdUtlAtividadeDTO as $objDTO ) {
        $idMain = $contador . '_' . $objDTO->getNumIdMdUtlAdmAtividade();
        $idPk = $objDTO->getNumIdMdUtlAdmAtividade();
        $vlUe = $objDTO->getStrSinAnalise() == 'S' ? MdUtlAdmPrmGrINT::convertToHoursMins($objDTO->getNumTmpExecucaoAtv()) : '0min';
        $strVlAnalise = $objDTO->getStrSinAnalise() == 'S' ? 'Sim' : 'Não';
        $tmpExecucao += $objDTO->getNumTmpExecucaoAtv();
        $contador++;
        $arrGrid[] = array($idMain, $idPk, $objDTO->getStrNome() . ' (' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$objDTO->getNumComplexidade()] . ')', $vlUe, $objDTO->getStrSinAnalise(), $strVlAnalise, $objDTO->getNumTmpExecucaoAtv());
      }
      return array( 'itensTable' => PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrGrid) , 'tmpExecucao' => $tmpExecucao );
    }

}
