<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/07/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmFilaPrmGrUsuRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarControlado(MdUtlAdmFilaPrmGrUsuDTO $objMdUtlAdmFilaPrmGrUsuDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_fila_prm_gr_usu_cadastrar', __METHOD__, $objMdUtlAdmFilaPrmGrUsuDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      $objInfraException->lancarValidacoes();

      $objMdUtlAdmFilaPrmGrUsuBD = new MdUtlAdmFilaPrmGrUsuBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmFilaPrmGrUsuBD->cadastrar($objMdUtlAdmFilaPrmGrUsuDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Tipo de Ausência.',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmFilaPrmGrUsuDTO $objMdUtlAdmFilaPrmGrUsuDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_fila_prm_gr_usu_alterar', __METHOD__, $objMdUtlAdmFilaPrmGrUsuDTO);

      $objMdUtlAdmFilaPrmGrUsuBD = new MdUtlAdmFilaPrmGrUsuBD($this->getObjInfraIBanco());
      $objMdUtlAdmFilaPrmGrUsuBD->alterar($objMdUtlAdmFilaPrmGrUsuDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Tipo de Ausência.',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmFilaPrmGrUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_fila_prm_gr_usu_excluir', __METHOD__, $arrObjMdUtlAdmFilaPrmGrUsuDTO);

      $objMdUtlAdmFilaPrmGrUsuBD = new MdUtlAdmFilaPrmGrUsuBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmFilaPrmGrUsuDTO);$i++){
        $objMdUtlAdmFilaPrmGrUsuBD->excluir($arrObjMdUtlAdmFilaPrmGrUsuDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Tipo de Ausência.',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmFilaPrmGrUsuDTO $objMdUtlAdmFilaPrmGrUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_fila_prm_gr_usu_consultar');

      $objMdUtlAdmFilaPrmGrUsuBD = new MdUtlAdmFilaPrmGrUsuBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmFilaPrmGrUsuBD->consultar($objMdUtlAdmFilaPrmGrUsuDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Tipo de Ausência.',$e);
    }
  }

  protected function listarConectado(MdUtlAdmFilaPrmGrUsuDTO $objMdUtlAdmFilaPrmGrUsuDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_fila_prm_gr_usu_listar');

      $objMdUtlAdmFilaPrmGrUsuBD = new MdUtlAdmFilaPrmGrUsuBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmFilaPrmGrUsuBD->listar($objMdUtlAdmFilaPrmGrUsuDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Tipos de Ausência.',$e);
    }
  }

  protected function contarConectado(MdUtlAdmFilaPrmGrUsuDTO $objMdUtlAdmFilaPrmGrUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_fila_prm_gr_usu_listar');

      $objMdUtlAdmFilaPrmGrUsuBD = new MdUtlAdmFilaPrmGrUsuBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmFilaPrmGrUsuBD->contar($objMdUtlAdmFilaPrmGrUsuDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Tipos de Ausência.',$e);
    }
  }

  protected function desativarControlado($arrObjMdUtlAdmFilaPrmGrUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_fila_prm_gr_usu_desativar', __METHOD__, $arrObjMdUtlAdmFilaPrmGrUsuDTO);

      $objMdUtlAdmFilaPrmGrUsuBD = new MdUtlAdmFilaPrmGrUsuBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmFilaPrmGrUsuDTO);$i++){
        $objMdUtlAdmFilaPrmGrUsuBD->desativar($arrObjMdUtlAdmFilaPrmGrUsuDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Tipo de Ausência.',$e);
    }
  }

  protected function reativarControlado($arrObjMdUtlAdmFilaPrmGrUsuDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_fila_prm_gr_usu_reativar', __METHOD__, $arrObjMdUtlAdmFilaPrmGrUsuDTO);

      $objMdUtlAdmFilaPrmGrUsuBD = new MdUtlAdmFilaPrmGrUsuBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmFilaPrmGrUsuDTO);$i++){
        $objMdUtlAdmFilaPrmGrUsuBD->reativar($arrObjMdUtlAdmFilaPrmGrUsuDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Tipo de Ausência.',$e);
    }
  }
  
  protected function getPapeisDeUsuarioConectado($idsFilasPermitidas){

    $arrObjFilaDTO = null;

    if(count($idsFilasPermitidas) > 0) {
      $objDTO = new MdUtlAdmFilaPrmGrUsuDTO();
      $objDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objDTO->retTodos();
      $objDTO->setNumIdMdUtlAdmFila($idsFilasPermitidas, InfraDTO::$OPER_IN);
          
      $count = $this->contar($objDTO);

      if ($count > 0) {
        $arrObjFilaDTO = $this->listar($objDTO);
      }
    }

    return $arrObjFilaDTO;
  }

    protected function getUsuarioPorPapelConectado($valores)
    {
        $papelUsu = $valores[0];
        $arrFila = $valores[1];
        $idsUsuarios = array_key_exists(2, $valores) ? $valores[2] : null;

        $objUsuarioDTO = new MdUtlAdmFilaPrmGrUsuDTO();
        $objUsuarioDTO->retTodos();
        $objUsuarioDTO->setNumIdMdUtlAdmFila($arrFila);
        $objUsuarioDTO->retNumIdUsuario();

        if (!is_null($idsUsuarios) && is_array($idsUsuarios)) {
            $objUsuarioDTO->setNumIdUsuario($idsUsuarios, InfraDTO::$OPER_IN);
        }

        if ($papelUsu == MdUtlAdmFilaRN::$TRIADOR) {
            $objUsuarioDTO->setStrSinTriador('S');
        }

        if ($papelUsu == MdUtlAdmFilaRN::$ANALISTA) {
            $objUsuarioDTO->setStrSinAnalista('S');
        }

        if ($papelUsu == MdUtlAdmFilaRN::$REVISOR) {
            $objUsuarioDTO->setStrSinRevisor('S');
        }

        $count = $this->contar($objUsuarioDTO);

        if ($count > 0) {
            $arrObjFilaDTO = $this->listar($objUsuarioDTO);
        }

        return $arrObjFilaDTO;
    }

  protected function getResponsavelPorFilaConectado($idsFilasPermitidas){

      $objRegrasGeraisRN = new MdUtlRegrasGeraisRN();
      $idsUsuarioUnidade = $objRegrasGeraisRN->getIdsUsuariosUnidadeLogada();

      if(count($idsFilasPermitidas) > 0 && count($idsUsuarioUnidade) > 0) {
          $idsResponsaveis = '';

          $objFilaPrmUsuDTO = new MdUtlAdmFilaPrmGrUsuDTO();
          $objFilaPrmUsuDTO->setNumIdMdUtlAdmFila($idsFilasPermitidas, InfraDTO::$OPER_IN);
          $objFilaPrmUsuDTO->retNumIdUsuario();
          $objFilaPrmUsuDTO->setNumIdUsuario($idsUsuarioUnidade, InfraDTO::$OPER_IN);
          $objFilaPrmUsuDTO->retStrNomeUsuario();
          $objFilaPrmUsuDTO->setOrdStrNomeUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);

          $objFilaPrmUsuRN = new MdUtlAdmFilaPrmGrUsuRN();
          $objFilaPrmUsuDTO = $objFilaPrmUsuRN->listar($objFilaPrmUsuDTO);

          return $objFilaPrmUsuDTO;
      }

      return null;
  }

  protected function getPercentualTriagemAnalisePorFilaConectado($idFila){
      if(!is_null($idFila)) {
          $objMdUtlAdmFilaPrmGrUsuDTO = new MdUtlAdmFilaPrmGrUsuDTO();
          $objMdUtlAdmFilaPrmGrUsuDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
          $objMdUtlAdmFilaPrmGrUsuDTO->setNumIdMdUtlAdmFila($idFila);
          $objMdUtlAdmFilaPrmGrUsuDTO->retNumTipoRevisao();
          $objMdUtlAdmFilaPrmGrUsuDTO->setNumMaxRegistrosRetorno(1);

          $objMdUtlAdmFilaPrmGrUsuDTO = $this->consultar($objMdUtlAdmFilaPrmGrUsuDTO);


        if(!is_null($objMdUtlAdmFilaPrmGrUsuDTO)){
            $tipoRevisao = $objMdUtlAdmFilaPrmGrUsuDTO->getNumTipoRevisao();
            $tipoRevisao = is_null($tipoRevisao) ? 0 : $tipoRevisao;
              return $tipoRevisao;
          }
      }

      return null;
  }

  protected function alterarDadosTipoRevisaoControlado(){

    $objMdUtlAdmFilaPrmGrUsuDTO = new MdUtlAdmFilaPrmGrUsuDTO();
    $objMdUtlAdmFilaPrmGrUsuDTO->retTodos();
    $objMdUtlAdmFilaPrmGrUsuDTO = $this->listar($objMdUtlAdmFilaPrmGrUsuDTO);


    foreach ($objMdUtlAdmFilaPrmGrUsuDTO as $objDTO){
        $tipoRevisao = null;

        if ($objDTO->getNumTipoRevisao() > 0 && $objDTO->getNumTipoRevisao() < 100) {
            $tipoRevisao = MdUtlAdmFilaRN::$POR_ATIVIDADE;
        }

        if ($objDTO->getNumTipoRevisao() == 100) {
            $tipoRevisao = MdUtlAdmFilaRN::$TOTAL;
        }

        if ($objDTO->getNumTipoRevisao() == 0) {
            $tipoRevisao = MdUtlAdmFilaRN::$SEM_REVISAO;
        }

        $objDTO->setNumTipoRevisao($tipoRevisao);
     
        $this->alterar($objDTO);
       
      }
  }
}
