<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/09/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmGrpRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(MdUtlAdmGrpDTO $objMdUtlAdmGrpDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmGrpDTO->getStrNome())){
      $objInfraException->adicionarValidacao(' não informad.');
    }else{
      $objMdUtlAdmGrpDTO->setStrNome(trim($objMdUtlAdmGrpDTO->getStrNome()));

      if (strlen($objMdUtlAdmGrpDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrDescricao(MdUtlAdmGrpDTO $objMdUtlAdmGrpDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmGrpDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao(' não informad.');
    }else{
      $objMdUtlAdmGrpDTO->setStrDescricao(trim($objMdUtlAdmGrpDTO->getStrDescricao()));

      if (strlen($objMdUtlAdmGrpDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarNumIdMdUtlAdmTpCtrlDesemp(MdUtlAdmGrpDTO $objMdUtlAdmGrpDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmGrpDTO->getNumIdMdUtlAdmTpCtrlDesemp())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  protected function cadastrarControlado(MdUtlAdmGrpDTO $objMdUtlAdmGrpDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_grp_cadastrar', __METHOD__, $objMdUtlAdmGrpDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objMdUtlAdmGrpDTO, $objInfraException);
      $this->validarStrDescricao($objMdUtlAdmGrpDTO, $objInfraException);
      $this->validarNumIdMdUtlAdmTpCtrlDesemp($objMdUtlAdmGrpDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpBD = new MdUtlAdmGrpBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmGrpBD->cadastrar($objMdUtlAdmGrpDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando grupo atividades.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmGrpDTO $objMdUtlAdmGrpDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_grp_alterar', __METHOD__, $objMdUtlAdmGrpDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmGrpDTO->isSetStrNome()){
        $this->validarStrNome($objMdUtlAdmGrpDTO, $objInfraException);
      }
      if ($objMdUtlAdmGrpDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objMdUtlAdmGrpDTO, $objInfraException);
      }
      if ($objMdUtlAdmGrpDTO->isSetNumIdMdUtlAdmTpCtrlDesemp()){
        $this->validarNumIdMdUtlAdmTpCtrlDesemp($objMdUtlAdmGrpDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpBD = new MdUtlAdmGrpBD($this->getObjInfraIBanco());
      $objMdUtlAdmGrpBD->alterar($objMdUtlAdmGrpDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando grupo atividades.',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmGrpDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_grp_excluir', __METHOD__,$arrObjMdUtlAdmGrpDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpBD = new MdUtlAdmGrpBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmGrpDTO);$i++){
        $objMdUtlAdmGrpBD->excluir($arrObjMdUtlAdmGrpDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo grupo atividades.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmGrpDTO $objMdUtlAdmGrpDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_grp_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpBD = new MdUtlAdmGrpBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmGrpBD->consultar($objMdUtlAdmGrpDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando grupo atividades.',$e);
    }
  }

  protected function listarConectado(MdUtlAdmGrpDTO $objMdUtlAdmGrpDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_grp_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpBD = new MdUtlAdmGrpBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmGrpBD->listar($objMdUtlAdmGrpDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando grupo atividades.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmGrpDTO $objMdUtlAdmGrpDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_grp_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpBD = new MdUtlAdmGrpBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmGrpBD->contar($objMdUtlAdmGrpDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando grupo atividades.',$e);
    }
  }

  protected function cadastrarGrupoAtividadeFilaControlado($dados){

      try {

          $arrFila = PaginaSEI::getInstance()->getArrItensTabelaDinamica($dados['hdnFila']);
          $idMdUtlAdmGrp = isset($dados['hdnIdMdAdmGrp']) ? $dados['hdnIdMdAdmGrp'] : 0;
          for ($i = 0; $i < count($arrFila); $i++) {
              $arrIdFila[] = $arrFila[$i][0];
          }

          $mdUtlAdmGrpDTO = new MdUtlAdmGrpDTO();
          $mdUtlAdmGrpRN = new MdUtlAdmGrpRN();

          $mdUtlAdmGrpDTO->setStrNome($dados['txtNome']);

          $mdUtlAdmGrpDTO->setNumIdMdUtlAdmTpCtrlDesemp($dados['hdnIdTpCtrlUtl']);
          $mdUtlAdmGrpDTO->retNumIdMdUtlAdmGrp();

          if($this->validarDuplicidadeGrupoAtividade(array($mdUtlAdmGrpDTO,$idMdUtlAdmGrp))) {

              $mdUtlAdmGrpDTO->setStrDescricao($dados['txaDescricao']);

              if (isset($dados['sbmAlterarMdUtlAdmGrpFila'])) {

                  $mdUtlAdmGrpDTO->setNumIdMdUtlAdmGrp($idMdUtlAdmGrp);
                  $this->alterar($mdUtlAdmGrpDTO);

              } else {
                  $mdUtlAdmGrpDTO = $this->cadastrar($mdUtlAdmGrpDTO);
              }

          }

          $mdUtlAdmGrpFilaRN = new MdUtlAdmGrpFilaRN();

          for ($i = 0; $i < count($arrIdFila); $i++) {

              $mdUtlAdmGrpFilaDTO = new MdUtlAdmGrpFilaDTO();

              $mdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmGrp($mdUtlAdmGrpDTO->getNumIdMdUtlAdmGrp());
              $mdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmFila($arrIdFila[$i]);
              $mdUtlAdmGrpFilaDTO->retTodos() ;
              if(isset($dados['sbmAlterarMdUtlAdmGrpFila'])){
                  $mdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmGrpFila($dados['hdnIdMdAdmGrpFila'],InfraDTO::$OPER_DIFERENTE);
                  if($mdUtlAdmGrpFilaRN->validarDuplicidadeFila($mdUtlAdmGrpFilaDTO)) {
                      $mdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmGrpFila($dados['hdnIdMdAdmGrpFila']);
                      $mdUtlAdmGrpFilaRN->alterar($mdUtlAdmGrpFilaDTO);
                  }

              }else {
                  $mdUtlAdmGrpFilaDTO->setStrSinAtivo('S');
                  $mdUtlAdmGrpFilaRN->cadastrar($mdUtlAdmGrpFilaDTO);
              }
          }

          return $mdUtlAdmGrpFilaDTO;

      }catch (Exception $e){
          throw new InfraException('Erro Cadastrando grupo atividades.',$e);
      }

  }

  protected function validarDuplicidadeGrupoAtividadeControlado($params){


      $mdUtlAdmGrpDTO = $params[0];
      $idMdUtlAdmGrp  = $params[1];

      if($idMdUtlAdmGrp>0){
          $mdUtlAdmGrpDTO->setNumIdMdUtlAdmGrp($idMdUtlAdmGrp,InfraDTO::$OPER_DIFERENTE);
      }

      $objInfraException = new InfraException();
      $qtdRegistro = $this->contar($mdUtlAdmGrpDTO);

      if($qtdRegistro > 0){

          $objInfraException->lancarValidacao('Não é possível salvar este Grupo de Atividade , pois já existe um cadastrado com o mesmo nome para esse Tipo de Controle de Desempenho.');
          return false;

      }
      return true;
  }
    
}
