<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/07/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmPrmGrINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmPrmGr($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdUtlAdmFila=''){
    $objMdUtlAdmPrmGrDTO = new MdUtlAdmPrmGrDTO();
    $objMdUtlAdmPrmGrDTO->retNumIdMdUtlAdmPrmGr();
    $objMdUtlAdmPrmGrDTO->retNumIdMdUtlAdmPrmGr();

    if ($numIdMdUtlAdmFila!==''){
      $objMdUtlAdmPrmGrDTO->setNumIdMdUtlAdmFila($numIdMdUtlAdmFila);
    }

    $objMdUtlAdmPrmGrDTO->setOrdNumIdMdUtlAdmPrmGr(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAdmPrmGrRN = new MdUtlAdmPrmGrRN();
    $arrObjMdUtlAdmPrmGrDTO = $objMdUtlAdmPrmGrRN->listar($objMdUtlAdmPrmGrDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmPrmGrDTO, 'IdMdUtlAdmPrmGr', 'IdMdUtlAdmPrmGr');
  }

  public static function montarSelectStaFrequencia($valorSelecionado =''){


    $select = '<option value="0"></option>';

    $arrFrequencia = array( MdUtlAdmPrmGrRN::$FREQUENCIA_DIARIO =>'Diário',
                            MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL => 'Mensal',
                            MdUtlAdmPrmGrRN::$FREQUENCIA_SEMANAL => 'Semanal' );
    foreach ($arrFrequencia as $key=>$frequencia){

        $add= '';

        if($valorSelecionado == $key){
            $add= 'selected = selected';
        }

        $select.='<option '.$add.' value="'.$key.'">'.$frequencia.'</option>';
    }

    return $select;
  }

    public static function montarSelectSinRetorno($valorSelecionado =''){


        $select = '<option value="0"></option>';

        $arrFrequencia = array( MdUtlAdmPrmGrRN::$RETORNO_SIM => 'Sim',
                                MdUtlAdmPrmGrRN::$RETORNO_NAO =>'Não');
        foreach ($arrFrequencia as $key=>$frequencia){

            $add= '';

            if($valorSelecionado == $key){
                $add= 'selected = selected';
            }

            $select.='<option '.$add.' value="'.$key.'">'.$frequencia.'</option>';
        }

        return $select;
    }

    public static function montarSelectFilaPadrao($valorSelecionado ='' , $idTipoControleUtl, $nenhumaFila = true, $null = true, $arrObjFilaDTO = null, $idsFilasPermitidasUsBasico = null){


        $select='';
        $add='';

        $objFilaDTO = new MdUtlAdmFilaDTO();
        $objFilaDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControleUtl);
        $objFilaDTO->retTodos();
        $objFilaDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objFilaDTO->setStrSinAtivo('S');
        $objFilaRN = new MdUtlAdmFilaRN();
        $arrObjFilaDTO = $objFilaRN->listar($objFilaDTO);

        $numRegistro = count($arrObjFilaDTO);
        $texto = $nenhumaFila ? 'Nenhuma Fila' : '';

        if($numRegistro > 0 ) {
            if($null) {
                $select .= '<option selected = selected value="null" >' . $texto . '</option>';
            }else{
                $select .= '<option selected = "selected" value="" >' . $texto . '</option>';
            }
            
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
        }else{
            if($null) {
                $select .= '<option selected = selected value="null" >' . $texto . '</option>';
            }else{
                $select .= '<option selected = "selected" value="" >' . $texto . '</option>';
            }
        }

        return $select;
    }
}
