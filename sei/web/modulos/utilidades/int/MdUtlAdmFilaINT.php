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

}
