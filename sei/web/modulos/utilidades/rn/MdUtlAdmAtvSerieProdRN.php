<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/08/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmAtvSerieProdRN extends InfraRN {

  public static $TIPO_PRODUTO = 'P';
  public static $TIPO_DOCUMENTO = 'D';

  public static $S_OBRIGATORIO = array('S','Sim');
  public static $N_OBRIGATORIO = array('N','Não');

  public static $APLICABILIDADESERIE_INTERNO = 'I';
  public static $APLICABILIDADESERIE_EXTERNO = 'E';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarControlado(MdUtlAdmAtvSerieProdDTO $objMdUtlAdmAtvSerieProdDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_atv_serie_prod_cadastrar', __METHOD__, $objMdUtlAdmAtvSerieProdDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      $objInfraException->lancarValidacoes();

      $objMdUtlAdmAtvSerieProdBD = new MdUtlAdmAtvSerieProdBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmAtvSerieProdBD->cadastrar($objMdUtlAdmAtvSerieProdDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmAtvSerieProdDTO $objMdUtlAdmAtvSerieProdDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_atv_serie_prod_alterar', __METHOD__, $objMdUtlAdmAtvSerieProdDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      $objInfraException->lancarValidacoes();

      $objMdUtlAdmAtvSerieProdBD = new MdUtlAdmAtvSerieProdBD($this->getObjInfraIBanco());
      $objMdUtlAdmAtvSerieProdBD->alterar($objMdUtlAdmAtvSerieProdDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }


  protected function excluirControlado($arrObjMdUtlAdmAtvSerieProdDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_atv_serie_prod_excluir', __METHOD__, $arrObjMdUtlAdmAtvSerieProdDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmAtvSerieProdBD = new MdUtlAdmAtvSerieProdBD($this->getObjInfraIBanco());

      for($i=0;$i<count($arrObjMdUtlAdmAtvSerieProdDTO);$i++){
        $objMdUtlAdmAtvSerieProdBD->excluir($arrObjMdUtlAdmAtvSerieProdDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmAtvSerieProdDTO $objMdUtlAdmAtvSerieProdDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_atv_serie_prod_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmAtvSerieProdBD = new MdUtlAdmAtvSerieProdBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmAtvSerieProdBD->consultar($objMdUtlAdmAtvSerieProdDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado($objMdUtlAdmAtvSerieProdDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_atv_serie_prod_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmAtvSerieProdBD = new MdUtlAdmAtvSerieProdBD($this->getObjInfraIBanco());

        $ret = $objMdUtlAdmAtvSerieProdBD->listar($objMdUtlAdmAtvSerieProdDTO);
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdUtlAdmAtvSerieProdDTO $objMdUtlAdmAtvSerieProdDTO){

      try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_atv_serie_prod_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmAtvSerieProdBD = new MdUtlAdmAtvSerieProdBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmAtvSerieProdBD->contar($objMdUtlAdmAtvSerieProdDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }

  }

    private function _getIdsRemovidos($idAtividade, $itemTbProdutosEsperados){
        $idsRemovidos      = array();

        //Verifica quais ids se manteram em tela
        $idsAtuaisMantidos = $this->_getIdsAtuaisRelacionadosMantidos($itemTbProdutosEsperados);

        //Verifica quais ids estão cadastrados no banco
        $idsAntigos        = $this->_getIdsAntigosRelacionamentos($idAtividade);

        if(count($idsAntigos) > 0) {
            //Se todos os antigos foram removidos, o sistema deve remover todos os ids antigos
            if (count($idsAtuaisMantidos) == 0) {
                return $idsAntigos;
            } else {
                $idsRemovidos = array_diff($idsAntigos, $idsAtuaisMantidos);
            }
        }

        return $idsRemovidos;

    }

    private function _getIdsAtuaisRelacionadosMantidos($itemTbProdutosEsperados){
        $idsAtuaisMantidos = array();
        foreach($itemTbProdutosEsperados as $produtosEsperado){
            $isInclusao = strpos($produtosEsperado[0], '_');
            if(!$isInclusao){
                array_push($idsAtuaisMantidos, $produtosEsperado[0]);
            }
        }

        return $idsAtuaisMantidos;
    }

    private function _getIdsAntigosRelacionamentos($idAtividade){
        $idsRelAtv = array();
        $objMdUtlAdmRelAtvSerieProdDTO = new MdUtlAdmAtvSerieProdDTO();
        $objMdUtlAdmRelAtvSerieProdDTO->setNumIdMdUtlAdmAtividade($idAtividade);
        $objMdUtlAdmRelAtvSerieProdDTO->retNumIdMdUtlAdmAtvSerieProd();

        $count   = $this->contar($objMdUtlAdmRelAtvSerieProdDTO);
        if($count > 0) {
            $arrObjs = $this->listar($objMdUtlAdmRelAtvSerieProdDTO);
            $idsRelAtv = InfraArray::converterArrInfraDTO($arrObjs, 'IdMdUtlAdmAtvSerieProd');
        }
        return $idsRelAtv;
    }

  protected function cadastrarListaProdutosEsperadosControlado($params){

      $idAtividade             = $params[0];
      $itemTbProdutosEsperados = PaginaSEI::getInstance()->getArrItensTabelaDinamica($params[1]);
      $this->_verificaRemoveRelacionamentosAtv($itemTbProdutosEsperados, $idAtividade);
      $this->_cadastrarNovosProdutosRelacionados($itemTbProdutosEsperados, $idAtividade);
      $this->_alterarProdutosRelacionados($itemTbProdutosEsperados);
      return true;
  }

  private function _alterarProdutosRelacionados($itemTbProdutosEsperados){
      foreach ($itemTbProdutosEsperados as $produtoAlterado ){
            if($produtoAlterado[11] == 'S'){
                $mdUtlAdmAtvSerieProdDTO = new MdUtlAdmAtvSerieProdDTO();
                $mdUtlAdmAtvSerieProdDTO->setNumIdMdUtlAdmAtvSerieProd(intval($produtoAlterado[0]));
                $mdUtlAdmAtvSerieProdDTO->retTodos();

                $objAlterarDTO = $this->consultar($mdUtlAdmAtvSerieProdDTO);
                $sinObrigatorio = $produtoAlterado[6] == 'Sim' ? 'S' : 'N';

                $objAlterarDTO->setStrSinObrigatorio($sinObrigatorio);
                $objAlterarDTO->setNumTempoExecucaoProduto(intval($produtoAlterado[5]));

                $this->alterar($objAlterarDTO);
            }
      }

  }

    private function _verificaRemoveRelacionamentosAtv($itemTbProdutosEsperados, $idAtividade){
        $idsRemovidos            = $this->_getIdsRemovidos($idAtividade, $itemTbProdutosEsperados);

        if(count($idsRemovidos) > 0){

            $mdUtlAdmAtvSerieProdDTO = new MdUtlAdmAtvSerieProdDTO();
            $mdUtlAdmAtvSerieProdDTO->setNumIdMdUtlAdmAtvSerieProd($idsRemovidos, InfraDTO::$OPER_IN);
            $mdUtlAdmAtvSerieProdDTO->retTodos();
            $arrObjs = $this->listar($mdUtlAdmAtvSerieProdDTO);

            $this->excluir($arrObjs);
        }
    }

    private function _cadastrarNovosProdutosRelacionados($itemTbProdutosEsperados, $idAtividade){

        foreach ($itemTbProdutosEsperados as $registro) {
            $idPk      = null;
            $isInclusao = strpos($registro[0], 'NOVO_REGISTRO') !== false;

            if ($isInclusao) {

                $mdUtlAdmAtvSerieProdDTO = new MdUtlAdmAtvSerieProdDTO();
                
                $mdUtlAdmAtvSerieProdDTO->setStrStaTipo($registro[2]);

                if ($registro[2] == self::$TIPO_PRODUTO) {
                    $mdUtlAdmAtvSerieProdDTO->setNumIdMdUtlAdmTpProduto($registro[9]);
                } else {

                    $mdUtlAdmAtvSerieProdDTO->setNumIdSerie($registro[9]);
                    /*
                    if ($registro[3] == 'I') {
                        $mdUtlAdmAtvSerieProdDTO->setStrStaAplicabilidadeSerie(self::$APLICABILIDADESERIE_INTERNO);
                    } else {
                        $mdUtlAdmAtvSerieProdDTO->setStrStaAplicabilidadeSerie(self::$APLICABILIDADESERIE_EXTERNO);
                    }
                    */
                }
                $mdUtlAdmAtvSerieProdDTO->setNumIdMdUtlAdmAtividade($idAtividade);
                $mdUtlAdmAtvSerieProdDTO->setNumTempoExecucaoProduto($registro[5]);

                $obrigatorio = $registro[7] == 'true' ? 'S' : 'N';
                $mdUtlAdmAtvSerieProdDTO->setStrSinObrigatorio($obrigatorio);

                $this->cadastrar($mdUtlAdmAtvSerieProdDTO);
            }
        }
    }

  protected function retornarItensTabelasDinamicaControlado($arrParams){
      $idAtividade = array_key_exists(0,$arrParams) ? $arrParams[0] : null;
      $isClonar    = array_key_exists(1,$arrParams) ? $arrParams[1] : null;

      $mdUtlAdmAtvSerieProdDTO = new MdUtlAdmAtvSerieProdDTO();
      $mdUtlAdmAtvSerieProdDTO->setNumIdMdUtlAdmAtividade($idAtividade);
      $mdUtlAdmAtvSerieProdDTO->retTodos(true);

      $mdUtlAdmAtvSerieProd = $this->listar($mdUtlAdmAtvSerieProdDTO);

      $arrIten = array();
      $idx = 0;
      foreach ($mdUtlAdmAtvSerieProd as $item){
        $linha= array();
        if($item->getStrStaTipo() == self::$TIPO_PRODUTO) {

            $linha[]  = $isClonar ? 'NOVO_REGISTRO_'.$idx : $item->getNumIdMdUtlAdmAtvSerieProd();
            $linha[]  = $item->getNumIdMdUtlAdmTpProduto().'P';
            $linha[]  = $item->getStrStaTipo();
            $linha[]  = null;
            $linha[]  = $item->getStrNomeProduto();

        }elseif ($item->getStrStaTipo() == self::$TIPO_DOCUMENTO){

            $linha[] = $isClonar ? 'NOVO_REGISTRO_'.$idx : $item->getNumIdMdUtlAdmAtvSerieProd();
            $linha[] = $item->getNumIdSerie().'D';
            $linha[] = $item->getStrStaTipo();
            $linha[] = null;
            $linha[] = $item->getStrNomeSerie();
        }

          $linha[] = $item->getNumTempoExecucaoProduto();

          if($item->getStrSinObrigatorio() == self::$S_OBRIGATORIO[0]){

              $linha[] = self::$S_OBRIGATORIO[1];
              $linha[] = 'true';

          }else{
              $linha[] = self::$N_OBRIGATORIO[1];
              $linha[] = 'false';
          }

          $linha[]   = $item->getNumIdMdUtlAdmAtvSerieProd();
          $linha[]   = $item->getStrStaTipo() == self::$TIPO_PRODUTO ? $item->getNumIdMdUtlAdmTpProduto() : $item->getNumIdSerie() ;
          $linha[]   = 'N';
          $linha[]   = 'N';

          $arrIten[] = $linha;
          $idx++;
      }
      return $arrIten;
  }

  protected function consultarExcluirVinculosControlado($idAtividade){

      $mdUtlAdmAtvSerieProdDTO = new MdUtlAdmAtvSerieProdDTO();
      $mdUtlAdmAtvSerieProdDTO->setNumIdMdUtlAdmAtividade($idAtividade);
      $mdUtlAdmAtvSerieProdDTO->retNumIdMdUtlAdmAtvSerieProd();
      $mdUtlAdmAtvSerieProdDTO->retNumIdMdUtlAdmAtividade();

      $mdUtlAdmAtvSerieProd =$this->contar($mdUtlAdmAtvSerieProdDTO);

      if($mdUtlAdmAtvSerieProd>0){
          $mdUtlAdmAtvSerieProd =$this->listar($mdUtlAdmAtvSerieProdDTO);
          $this->excluir($mdUtlAdmAtvSerieProd);
      }
  }
}
