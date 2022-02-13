<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/07/2018 - criado por jhon.cast
*
*
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmFilaINT extends InfraINT {

  public static function consultarVinculoFilaUsuario($dados){

      $mdUtlAdmFilaPrmGrUsuDTO = new MdUtlAdmFilaPrmGrUsuDTO();
      $mdUtlAdmFilaPrmGrUsuRN = new MdUtlAdmFilaPrmGrUsuRN();

      $mdUtlAdmFilaPrmGrUsuDTO->setNumIdMdUtlAdmPrmGrUsu($dados['idVinculo']);
      $mdUtlAdmFilaPrmGrUsuDTO->retNumIdUsuario();
      $mdUtlAdmFilaPrmGrUsuDTO->retStrNomeUsuario();


      $numRegistro = $mdUtlAdmFilaPrmGrUsuRN->contar($mdUtlAdmFilaPrmGrUsuDTO);

      $xml = '<dados>';
      if($numRegistro >0) {
          $xml .= '<sucesso>1</sucesso>';
          $xml .= '<msg>Não é possível remover este usário , pois o mesmo possui vinculo com uma ou mais Filas.</msg>';
      }else{
          $xml .= '<sucesso>0</sucesso>';
         // $xml .= '<msg></msg>';
      }
      $xml .='</dados>';

      return $xml;

  }
    
    public static function montarSelectMembros(){
        
    }

    public static function autoCompletarFilas($strPalavrasPesquisa, $idTpCtrl)
    {
        if(!is_null($idTpCtrl) && $idTpCtrl != ''){

            $objMdUtlAdmFilaRN  = new MdUtlAdmFilaRN();
            $objMdUtlAdmFilaDTO = new MdUtlAdmFilaDTO();
            $objMdUtlAdmFilaDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
            $objMdUtlAdmFilaDTO->retTodos();
            $objMdUtlAdmFilaDTO->setNumMaxRegistrosRetorno(50);
            $objMdUtlAdmFilaDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

            if (!InfraString::isBolVazia($strPalavrasPesquisa)){

//                $strPalavrasPesquisa = InfraString::prepararIndexacao($strPalavrasPesquisa);
//
//                $arrPalavrasPesquisa = explode(' ',$strPalavrasPesquisa);
//                $numPalavras = count($arrPalavrasPesquisa);
//                for($i=0;$i<$numPalavras;$i++){
//                    $arrPalavrasPesquisa[$i] = '%'.$arrPalavrasPesquisa[$i].'%';
//                }
//
//                if ($numPalavras==1){
//                    $objMdUtlAdmFilaDTO->setStrNome($arrPalavrasPesquisa[0],InfraDTO::$OPER_LIKE);
//                }else{
//                    $a = array_fill(0,count($arrPalavrasPesquisa),'Nome');
//                    $c = array_fill(0,count($arrPalavrasPesquisa),InfraDTO::$OPER_LIKE);
//                    $d = array_fill(0,count($arrPalavrasPesquisa)-1,InfraDTO::$OPER_LOGICO_AND);
//                    $objMdUtlAdmFilaDTO->adicionarCriterio($a,$c,$arrPalavrasPesquisa,$d);
//                }
                $arrObjMdUtlAdmFilaDTO = $objMdUtlAdmFilaRN->listar($objMdUtlAdmFilaDTO);

                $results = array_filter($arrObjMdUtlAdmFilaDTO, function ($item) use ($strPalavrasPesquisa) {
                    if (stripos($item, $strPalavrasPesquisa) !== false) {
                        return true;
                    }
                    return false;
                });


                foreach ($results as $objFilaDTO) {
                    $objFilaDTO->setStrNome($objFilaDTO->getStrNome() . ' - ' . $objFilaDTO->getStrDescricao());
                }
            }

//            $arrObjs = $objMdUtlAdmFilaRN->listar($objMdUtlAdmFilaDTO);

             return $results;
        }

        return null;
    }

    public static function getPapeisDeUsuario($idStatus){
        $valor = null;
        switch($idStatus){
            //Triador
            case MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM:
            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM:
            case MdUtlControleDsmpRN::$EM_TRIAGEM:
            case MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM:
                $valor = MdUtlAdmFilaRN::$TRIADOR;        
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_ANALISE:
            case MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE:
            case MdUtlControleDsmpRN::$EM_ANALISE:
            case MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE:
                $valor = MdUtlAdmFilaRN::$ANALISTA;        
                break;

            case MdUtlControleDsmpRN::$AGUARDANDO_REVISAO:
            case MdUtlControleDsmpRN::$EM_REVISAO:
                $valor = MdUtlAdmFilaRN::$REVISOR;        
                break;

        }

        return $valor;
      }

    public static function montarSelectFilas($valorSelecionado ='' , $arrObjFilaDTO = null, $idsFilasPermitidasUsBasico = false, $nenhumaFila = false){

        $numRegistro = count($arrObjFilaDTO);
        $textSelectVazio = $nenhumaFila ? 'Nenhuma Fila' : '';
        $select='<option value="">'.$textSelectVazio.'</option>';
        $add='';

        for ($i = 0; $i < $numRegistro; $i++) {
            $isValido = ($idsFilasPermitidasUsBasico && in_array($arrObjFilaDTO[$i]->getNumIdMdUtlAdmFila(), $idsFilasPermitidasUsBasico)) || !$idsFilasPermitidasUsBasico;

            if ($isValido) {
                $add = "";

                if ($arrObjFilaDTO[$i]->getNumIdMdUtlAdmFila() == $valorSelecionado) {
                    $add = 'selected = selected';
                }

                $select .= '<option ' . $add . ' value="' . $arrObjFilaDTO[$i]->getNumIdMdUtlAdmFila() . '" >' . $arrObjFilaDTO[$i]->getStrNome() . '</option>';
            }
        }

        return $select;
    }

    public static function montarSelectTipoRevisao($valorSelecionado = ''){

        $select = '<option value=""></option>';

        $arrResposta = array( MdUtlAdmFilaRN::$TOTAL => MdUtlAdmFilaRN::$STR_TOTAL,
            MdUtlAdmFilaRN::$POR_ATIVIDADE => MdUtlAdmFilaRN::$STR_POR_ATIVIDADE,MdUtlAdmFilaRN::$SEM_REVISAO => MdUtlAdmFilaRN::$STR_SEM_REVISAO);
        foreach ($arrResposta as $key=>$resposta){

            $add= '';

            if($valorSelecionado == $key){
                $add= 'selected = selected';
            }

            $select.='<option '.$add.' value="'.$key.'">'.$resposta.'</option>';
        }

        return $select;
    }

    /*
    public static function montarListaTpControle( $idUnidade , $idTipoControle = null , $combo = false )
    {        
        $objListaTpCtrlUsu = self::retListaTpCtrlUsu();
        $objListaTpCtrlUnd = self::retListaTpCtrlUnd($idUnidade);
        $arrObjTpCtrl      = self::retListaTpCtrlUsu_Und($objListaTpCtrlUsu, $objListaTpCtrlUnd);

        // se null retorna o valor null
        if (empty($arrObjTpCtrl)) {
            return $arrObjTpCtrl; 
        }

        // monta a combo
        if( $combo ){
            $sel = '<option value=""></option>';
            foreach( $arrObjTpCtrl as $ctrl ){
                if( $idTipoControle == $ctrl->getNumIdMdUtlAdmTpCtrlDesemp() ){
                    $sel.= '<option value="'.$ctrl->getNumIdMdUtlAdmTpCtrlDesemp().'" selected>'. $ctrl->getStrNome() .'</option>';
                }else{
                    $sel.= '<option value="'.$ctrl->getNumIdMdUtlAdmTpCtrlDesemp().'">'. $ctrl->getStrNome() .'</option>';
                }            
            }
            return $sel;
        }        

        //montar as urls de acordo com os tipo de controle de desempenho        
        foreach ($arrObjTpCtrl as $ctrl) {
            $arrTpControle[$ctrl->getNumIdMdUtlAdmTpCtrlDesemp()] = $ctrl->getStrNome(); 
        }
        return $arrTpControle;        
    }
    */

    public static function montarSelectTpControle($arrObj , $val , $desc , $val_selected, $tela = null){
        $sel  = '<option value=""></option>';
        $tipo = null;
        if (count($arrObj) == 1 ) {
            if( $tela == 'associar') 
                $tipo = 1;
            else
                $tipo = 2;
        } else {
            $tipo = 1;
        }

        switch ($tipo) {
            case 1:
                foreach( $arrObj as $el ){
                    $p1 = "get".$val;
                    $p2 = "get".$desc;
                    if( $val_selected == $el->$p1() ){
                        $sel.= '<option value="'.$el->$p1().'" selected>'. $el->$p2() .'</option>';
                    }else{
                        $sel.= '<option value="'.$el->$p1().'">'. $el->$p2() .'</option>';
                    }            
                }
                break;
            case 2:
                foreach( $arrObj as $el ){
                    $p1 = "get".$val;
                    $p2 = "get".$desc;
                    $sel.= '<option value="'.$el->$p1().'" selected>'. $el->$p2() .'</option>';           
                }
                break;
            default:
                break;
        }
        return $sel;
    }

    private static function retListaTpCtrlUsu()
    {
        $objMdUtlAdmRelTpCtrlDesempUsuRN  = new MdUtlAdmRelTpCtrlDesempUsuRN();
        $objMdUtlAdmRelTpCtrlDesempUsuDTO = new MdUtlAdmRelTpCtrlDesempUsuDTO();
        $objMdUtlAdmRelTpCtrlDesempUsuDTO->setNumIdUsuario( SessaoSEI::getInstance()->getNumIdUsuario() );
        $objMdUtlAdmRelTpCtrlDesempUsuDTO->retNumIdMdUtlAdmTpCtrlDesemp();              
        $arrObjTpCtrlUsu = $objMdUtlAdmRelTpCtrlDesempUsuRN->listar( $objMdUtlAdmRelTpCtrlDesempUsuDTO );
        $arrLista = array();
        foreach ($arrObjTpCtrlUsu as $row) {
            $arrLista[$row->getNumIdMdUtlAdmTpCtrlDesemp()] = $row->getNumIdMdUtlAdmTpCtrlDesemp();
        }
        return $arrLista;
    }

    private static function retListaTpCtrlUnd($idUnidade)
    {
        $objMdUtlAdmRelTpCtrlDesempUndRN  = new MdUtlAdmRelTpCtrlDesempUndRN();
        $objMdUtlAdmRelTpCtrlDesempUndDTO = new MdUtlAdmRelTpCtrlDesempUndDTO();
        $objMdUtlAdmRelTpCtrlDesempUndDTO->setNumIdUnidade( $idUnidade );
        $objMdUtlAdmRelTpCtrlDesempUndDTO->retNumIdMdUtlAdmTpCtrlDesemp();
        $arrObjTpCtrlUnd = $objMdUtlAdmRelTpCtrlDesempUndRN->listar( $objMdUtlAdmRelTpCtrlDesempUndDTO );
        $arrLista = array();
        foreach ($arrObjTpCtrlUnd as $row) {
            $arrLista[$row->getNumIdMdUtlAdmTpCtrlDesemp()] = $row->getNumIdMdUtlAdmTpCtrlDesemp();
        }
        return $arrLista;
    }

    private static function retListaTpCtrlUsu_Und($arrUsu , $arrUnd)
    {        
        // Valida retorno dos arrays, continuando na function, somente, se ambos retornarem registros        
        if (empty($arrUsu) && $arrUnd ) {
            return $arrUnd;
        } elseif (empty($arrUsu) && empty($arrUnd)) {
            return null;
        } elseif ($arrUsu && empty($arrUnd)) {
            return null;
        }

        $arrListaRet = array();
        foreach ($arrUsu as $k => $v) {
            if (array_key_exists($k, $arrUnd)) {
                array_push($arrListaRet,$v);
            }
        }

        // Se não existir uma interseção de tipos de controle de usuario x unidade retorna null
        if( empty($arrListaRet)) {
            return null;
        }
       
        $objMdUtlAdmTpCtrlDesempRN  = new MdUtlAdmTpCtrlDesempRN();
        $objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();
        $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($arrListaRet,InfraDTO::$OPER_IN);
        $objMdUtlAdmTpCtrlDesempDTO->setStrSinAtivo('S');        
        $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmPrmGr(null,InfraDTO::$OPER_DIFERENTE);

        $objMdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmTpCtrlDesemp();
        $objMdUtlAdmTpCtrlDesempDTO->retStrNome();
        return $objMdUtlAdmTpCtrlDesempRN->listar($objMdUtlAdmTpCtrlDesempDTO);
    }
}
