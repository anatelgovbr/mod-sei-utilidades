<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/09/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmGrpFilaProcRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMdUtlAdmGrpFila(MdUtlAdmGrpFilaProcDTO $objMdUtlAdmGrpFilaProcDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmGrpFilaProcDTO->getNumIdMdUtlAdmGrpFila())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumIdTipoProcedimento(MdUtlAdmGrpFilaProcDTO $objMdUtlAdmGrpFilaProcDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMdUtlAdmGrpFilaProcDTO->getNumIdTipoProcedimento())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  protected function cadastrarControlado(MdUtlAdmGrpFilaProcDTO $objMdUtlAdmGrpFilaProcDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_grp_fila_proc_cadastrar', __METHOD__, $objMdUtlAdmGrpFilaProcDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMdUtlAdmGrpFila($objMdUtlAdmGrpFilaProcDTO, $objInfraException);
      $this->validarNumIdTipoProcedimento($objMdUtlAdmGrpFilaProcDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFilaProcBD = new MdUtlAdmGrpFilaProcBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmGrpFilaProcBD->cadastrar($objMdUtlAdmGrpFilaProcDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(MdUtlAdmGrpFilaProcDTO $objMdUtlAdmGrpFilaProcDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_grp_fila_proc_alterar', __METHOD__, $objMdUtlAdmGrpFilaProcDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMdUtlAdmGrpFilaProcDTO->isSetNumIdMdUtlAdmGrpFila()){
        $this->validarNumIdMdUtlAdmGrpFila($objMdUtlAdmGrpFilaProcDTO, $objInfraException);
      }
      if ($objMdUtlAdmGrpFilaProcDTO->isSetNumIdTipoProcedimento()){
        $this->validarNumIdTipoProcedimento($objMdUtlAdmGrpFilaProcDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFilaProcBD = new MdUtlAdmGrpFilaProcBD($this->getObjInfraIBanco());
      $objMdUtlAdmGrpFilaProcBD->alterar($objMdUtlAdmGrpFilaProcDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjMdUtlAdmGrpFilaProcDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('md_utl_adm_grp_fila_proc_excluir', __METHOD__, $arrObjMdUtlAdmGrpFilaProcDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFilaProcBD = new MdUtlAdmGrpFilaProcBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMdUtlAdmGrpFilaProcDTO);$i++){
        $objMdUtlAdmGrpFilaProcBD->excluir($arrObjMdUtlAdmGrpFilaProcDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(MdUtlAdmGrpFilaProcDTO $objMdUtlAdmGrpFilaProcDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_grp_fila_proc_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFilaProcBD = new MdUtlAdmGrpFilaProcBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmGrpFilaProcBD->consultar($objMdUtlAdmGrpFilaProcDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(MdUtlAdmGrpFilaProcDTO $objMdUtlAdmGrpFilaProcDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_grp_fila_proc_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFilaProcBD = new MdUtlAdmGrpFilaProcBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmGrpFilaProcBD->listar($objMdUtlAdmGrpFilaProcDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(MdUtlAdmGrpFilaProcDTO $objMdUtlAdmGrpFilaProcDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarPermissao('md_utl_adm_grp_fila_proc_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMdUtlAdmGrpFilaProcBD = new MdUtlAdmGrpFilaProcBD($this->getObjInfraIBanco());
      $ret = $objMdUtlAdmGrpFilaProcBD->contar($objMdUtlAdmGrpFilaProcDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

    protected function cadastrarDadosProcAtvControlado($dados){


      if(array_key_exists(1,$dados)){
          $arrTbGrpAtv = $dados[1];
          $dados = $dados[0];
      }else{
          $arrTbGrpAtv = PaginaSEI::getInstance()->getArrItensTabelaDinamica($dados['hdnTbGrpAtv']);
      }

        $arrIdTpProcesso = array();
        $arrIdAtividade  = array();

        for($i = 0; $i<count($arrTbGrpAtv); $i++){

            $IdsAtividade = array();
            $idTpProcesso  = $arrTbGrpAtv[$i][0];

            $arrIdTpProcesso[] = $idTpProcesso;
            $arrAtivdade = explode("§",$arrTbGrpAtv[$i][3]);

            for($j = 0 ;$j < count($arrAtivdade);$j++){

                $idAtividade = explode("#",$arrAtivdade[$j])[0];
                $arrIdAtividade[$idTpProcesso][]=$idAtividade;

            }

        }


        $idMdAdmGrpFila = $dados['hdnIdMdAdmGrpFila'];
        $arrProcAtv = array();
        for($i = 0 ; $i < count($arrIdTpProcesso) ; $i++){

            $mdUtlAdmGrpFilaProcDTO = new MdUtlAdmGrpFilaProcDTO();
            $mdUtlAdmGrpFilaProcDTO->setNumIdTipoProcedimento($arrIdTpProcesso[$i]);
            $mdUtlAdmGrpFilaProcDTO->setNumIdMdUtlAdmGrpFila($idMdAdmGrpFila);
            $mdUtlAdmGrpFilaProcDTO->retTodos();

            $mdUtlAdmGrpFilaProcDTO = $this->cadastrar($mdUtlAdmGrpFilaProcDTO);

            $arrProcAtv[$mdUtlAdmGrpFilaProcDTO->getNumIdMdUtlAdmGrpFilaProc()] = $arrIdAtividade[$arrIdTpProcesso[$i]];
        }
        $mdUtlAdmGrpFlProcAtvRN = new MdUtlAdmGrpFlProcAtvRN();
        $mdUtlAdmGrpFlProcAtvRN->cadastrarAtvVinculo($arrProcAtv);
    }

    protected function listarParametrosControlado($objMdUtlAdmGrpFlProcAtvDTO){

        $objMdUtlAdmGrpFlProcAtv = $this->listar($objMdUtlAdmGrpFlProcAtvDTO);
        $arrIdMdUtlAdmGrpFilaProc = array();

        $arrIdMdUtlAdmGrpFilaProc =explode(',',InfraArray::implodeArrInfraDTO($objMdUtlAdmGrpFlProcAtv,'IdMdUtlAdmGrpFilaProc'));

        $mdUtlAdmGrpFlProcAtvDTO = new MdUtlAdmGrpFlProcAtvDTO();
        $mdUtlAdmGrpFlProcAtvRN  = new MdUtlAdmGrpFlProcAtvRN();

        $mdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmGrpFilaProc($arrIdMdUtlAdmGrpFilaProc,InfraDTO::$OPER_IN);
        $mdUtlAdmGrpFlProcAtvDTO->retTodos(true);
        $mdUtlAdmGrpFlProcAtv = $mdUtlAdmGrpFlProcAtvRN->listar($mdUtlAdmGrpFlProcAtvDTO);

        $arrGrpAtv = array();

        for($i = 0 ; $i <count($arrIdMdUtlAdmGrpFilaProc);$i++) {
            $registroTabela = array();
            $arrAtv = InfraArray::filtrarArrInfraDTO($mdUtlAdmGrpFlProcAtv, 'IdMdUtlAdmGrpFilaProc', $arrIdMdUtlAdmGrpFilaProc[$i]);
            $registroTabela[] =$objMdUtlAdmGrpFlProcAtv[$i]->getNumIdTipoProcedimento();
            $registroTabela[] =$objMdUtlAdmGrpFlProcAtv[$i]->getStrNomeProcedimento();

            if(count($arrAtv)>1){
                $registroTabela[] = 'Múltiplas';
            }else{
                $registroTabela[] = $arrAtv[0]->getStrNomeAtividade();
            }
            $arrIdNomeAtv ="";

            for($j = 0; $j< count($arrAtv); $j++){
                if($arrIdNomeAtv!=""){
                    $arrIdNomeAtv=$arrIdNomeAtv.'§';
                }
                $arrIdNomeAtv =$arrIdNomeAtv. ($arrAtv[$j]->getNumIdMdUtlAdmAtividade() ."#". $arrAtv[$j]->getStrNomeAtividade());
            }

            $registroTabela[] = $arrIdNomeAtv;
            $registroTabela[] = $arrIdMdUtlAdmGrpFilaProc[$i];
            $arrGrpAtv[]=$registroTabela;
        }

      return $arrGrpAtv;

    }

    protected function alterarDadosProcAtvControlado($strTbGrpAtvOrigin){


        $arrTbGrpAtv = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnTbGrpAtv']);
        $arrRegistroNovo = array();
        foreach ($arrTbGrpAtv as $linha){

           if($linha[4] == 'null'){
               $arrRegistroNovo[] = $linha;
           }

        }

        //Obtendo array de Ids para remover processo e atividades
        if(isset($_POST['hdnIdsRegistroRemovido']) && $_POST['hdnIdsRegistroRemovido']!= ''){

            $arrIdRegistroRemovido = explode('-',$_POST['hdnIdsRegistroRemovido']);
        }

        //Obtendo array de Ids de vinculos que foram alterados
        if($_POST['hdnIdsAtvAlterada']!= ''){
            $arrIdAlterado = explode('#',$_POST['hdnIdsAtvAlterada']);
            $arrIdAlterado = array_unique($arrIdAlterado);
        }

        //Obtendo array de ids de processos que tiveram suas atividades removidas
        if($_POST['hdnIdsAtvRemovida']!= ''){

            $arrIdAtvRemovida = explode('#',$_POST['hdnIdsAtvRemovida']);
            $arrIdAtvRemovidaFormtado = array();
            foreach($arrIdAtvRemovida as $item){
                $idGrProcFl = explode(',',$item)[0];
                $atividades = explode(',',$item)[1];
                $idsAtividades[$idGrProcFl] = explode('-',$atividades);
                $arrIdAtvRemovidaFormtado[]=$idsAtividades;
            }
        }


        if(count($arrIdRegistroRemovido)>0) {
            $arrMdUtlAdmGrpFilaProcDTO = InfraArray::gerarArrInfraDTO('MdUtlAdmGrpFilaProcDTO', 'IdMdUtlAdmGrpFilaProc', $arrIdRegistroRemovido);
           // $arrMdUtlAdmGrpFlProcAtvDTO= InfraArray::gerarArrInfraDTO('MdUtlAdmGrpFlProcAtvDTO','IdMdUtlAdmGrpFilaProc',$arrIdRegistroRemovido);

            $arrMdUtlAdmGrpFlProcAtvDTO = new MdUtlAdmGrpFlProcAtvDTO();
            $arrMdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmGrpFilaProc($arrIdRegistroRemovido,InfraDTO::$OPER_IN);
            $arrMdUtlAdmGrpFlProcAtvDTO->retNumIdMdUtlAdmGrpFlProcAtv();

            $mdUtlAdmGrpFlProcAtvRN = new MdUtlAdmGrpFlProcAtvRN();
            $arrMdUtlAdmGrpFlProcAtvDTO = $mdUtlAdmGrpFlProcAtvRN->listar($arrMdUtlAdmGrpFlProcAtvDTO);


            $mdUtlAdmGrpFlProcAtvRN->excluir($arrMdUtlAdmGrpFlProcAtvDTO);
            $this->excluirControlado($arrMdUtlAdmGrpFilaProcDTO);
        }

        if(count($arrRegistroNovo)>0){
            $this->cadastrarDadosProcAtv(array($_POST,$arrRegistroNovo));
        }

        if(count($arrIdAlterado)>0) {
            foreach ($arrIdAlterado as $idAlterado) {
                $arrAtividades = $this->_retornaAtividadeAlterada($strTbGrpAtvOrigin, $arrTbGrpAtv, $idAlterado);
            }
        }

    }

    private function _retornaAtividadeAlterada($arrOrigin,$arrNovo ,$idVincAlterado){
      $arrRegistroEncontrado = array();

      foreach ($arrOrigin as $resgistro){
        if($resgistro[4]==$idVincAlterado){
            $arrRegistroEncontrado[] = explode('§',$resgistro[3]);
            break;
        }
      }

      foreach ($arrNovo as $resgistro){
            if($resgistro[4]==$idVincAlterado){
                $arrRegistroEncontrado[] = explode('§',$resgistro[3]);
                break;
            }
      }

      if(count($arrRegistroEncontrado)==2){

          $atividadeRemover[$idVincAlterado]   = array_diff($arrRegistroEncontrado[0],$arrRegistroEncontrado[1]);
          $atividadeAdicionar[$idVincAlterado] = array_diff($arrRegistroEncontrado[1],$arrRegistroEncontrado[0]);

          if(count($atividadeRemover[$idVincAlterado])>0){
              $mdUtlAdmGrpFlProcAtv = new MdUtlAdmGrpFlProcAtvRN();
              $arrMdUtlAdmGrpFlProcAtvDTO = array();
              foreach ($atividadeRemover[$idVincAlterado] as $atv){

                  $idAtvRmv = explode('#',$atv)[0];

                  $mdUtlAdmGrpFlProcAtvDTO = new MdUtlAdmGrpFlProcAtvDTO();
                  $mdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmGrpFilaProc($idVincAlterado);
                  $mdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmAtividade($idAtvRmv);
                  $mdUtlAdmGrpFlProcAtvDTO->retTodos();

                  $arrMdUtlAdmGrpFlProcAtvDTO[] = $mdUtlAdmGrpFlProcAtv->consultar($mdUtlAdmGrpFlProcAtvDTO);

              }

              $mdUtlAdmGrpFlProcAtv->excluir($arrMdUtlAdmGrpFlProcAtvDTO);

          }

          if(count($atividadeAdicionar[$idVincAlterado])>0){
              $arrMdUtlAdmGrpFlProcAtvDTO = array();

              foreach ($atividadeAdicionar[$idVincAlterado] as $atv){
                  $idAtvAdd = explode('#',$atv)[0];
                  $arrMdUtlAdmGrpFlProcAtvDTO[]= array('IdMdUtlAdmGrpFilaProc'=>$idVincAlterado,'IdMdUtlAdmAtividade'=>$idAtvAdd);

              }
              $arrMdUtlAdmGrpFlProcAtvDTO = InfraArray::gerarArrInfraDTOMultiAtributos('MdUtlAdmGrpFlProcAtvDTO',$arrMdUtlAdmGrpFlProcAtvDTO);

              $mdUtlAdmGrpFlProcAtv = new MdUtlAdmGrpFlProcAtvRN();
              $mdUtlAdmGrpFlProcAtv->cadastrar($arrMdUtlAdmGrpFlProcAtvDTO);
          }

      }


    }

    protected function verificarQtdRegistroRelacionadosControlado($idRel){
      $mdUtlAdmGrpFilaProcDTO = new MdUtlAdmGrpFilaProcDTO();
      $mdUtlAdmGrpFilaProcDTO->setNumIdMdUtlAdmGrpFila($idRel);
      $mdUtlAdmGrpFilaProcDTO->retNumIdMdUtlAdmGrpFilaProc();

        $qtFilaProc = $this->contar($mdUtlAdmGrpFilaProcDTO);

      if($qtFilaProc>0){
        $obgMdUtlAdmGrpFilaProcDTO = $this->listar($mdUtlAdmGrpFilaProcDTO);


        $arrIdGrpFilaProc = array();
         foreach ($obgMdUtlAdmGrpFilaProcDTO as $grpFila){
             $arrIdGrpFilaProc[]=$grpFila->getNumIdMdUtlAdmGrpFilaProc();
         }

         $mdUtlAdmGrpFlProcAtvDTO = new MdUtlAdmGrpFlProcAtvDTO();
         $mdUtlAdmGrpFlProcAtvRN  = new MdUtlAdmGrpFlProcAtvRN();

         $mdUtlAdmGrpFlProcAtvDTO->retNumIdMdUtlAdmGrpFlProcAtv();
         $mdUtlAdmGrpFlProcAtvDTO->setNumIdMdUtlAdmGrpFilaProc($arrIdGrpFilaProc,InfraDTO::$OPER_IN);
         $mdUtlAdmGrpFlProcAtv = $mdUtlAdmGrpFlProcAtvRN->listar($mdUtlAdmGrpFlProcAtvDTO);

         //Excluir Vinculos Atividade com Tp de Processo
         $mdUtlAdmGrpFlProcAtvRN->excluir($mdUtlAdmGrpFlProcAtv);

         //Excluir Vinculos Fila com Tp Processo
         $this->excluir($obgMdUtlAdmGrpFilaProcDTO);
      }
      return true;
    }

    protected function getGruposFilaDesteProcessoConectado($idTipoProcedimento){
        $objMdUtlAdmGrpFilaProcDTO = new MdUtlAdmGrpFilaProcDTO();
        $objMdUtlAdmGrpFilaProcDTO->setNumIdTipoProcedimento($idTipoProcedimento);
        $objMdUtlAdmGrpFilaProcDTO->retNumIdMdUtlAdmGrpFila();

        $count   = $this->contar($objMdUtlAdmGrpFilaProcDTO);

        if($count > 0) {
            $arrObjs = $this->listar($objMdUtlAdmGrpFilaProcDTO);
            $idsRetorno = InfraArray::converterArrInfraDTO($arrObjs, 'IdMdUtlAdmGrpFila');    
            return $idsRetorno;
        }

        return null;
    }

    protected function getAtividadePorIdGrupoFilaConectado($arrParams){
        $idsGrupoAtvFila    = array_key_exists(0, $arrParams) ? $arrParams[0] : null;
        $idTipoProcedimento = array_key_exists(1, $arrParams) ? $arrParams[1] : null;

        if(!is_null($idTipoProcedimento) && !is_null($idsGrupoAtvFila)) {
            $objMdUtlAdmGrpFilaProcDTO = new MdUtlAdmGrpFilaProcDTO();
            $objMdUtlAdmGrpFilaProcDTO->setNumIdMdUtlAdmGrpFila($idsGrupoAtvFila, InfraDTO::$OPER_IN);
            $objMdUtlAdmGrpFilaProcDTO->setNumIdTipoProcedimento($idTipoProcedimento);
            $objMdUtlAdmGrpFilaProcDTO->retNumIdMdUtlAdmGrpFilaProc();

            $count = $this->contar($objMdUtlAdmGrpFilaProcDTO);

            if ($count > 0) {
                $objRN = new MdUtlAdmGrpFlProcAtvRN();
                $idsGrupoProcFila = InfraArray::converterArrInfraDTO($this->listar($objMdUtlAdmGrpFilaProcDTO), 'IdMdUtlAdmGrpFilaProc');
                $objMdUtlAdmGrpProcFilaAtvDTO = new MdUtlAdmGrpFlProcAtvDTO();
                $objMdUtlAdmGrpProcFilaAtvDTO->setNumIdMdUtlAdmGrpFilaProc($idsGrupoProcFila, InfraDTO::$OPER_IN);
                $objMdUtlAdmGrpProcFilaAtvDTO->retNumIdMdUtlAdmAtividade();
                $count2 = $objRN->contar($objMdUtlAdmGrpProcFilaAtvDTO);
                if ($count2 > 0) {
                    $idsAtividade = InfraArray::converterArrInfraDTO($objRN->listar($objMdUtlAdmGrpProcFilaAtvDTO), 'IdMdUtlAdmAtividade');
                    return $idsAtividade;
                }
            }
        }

        return null;
    }

}
