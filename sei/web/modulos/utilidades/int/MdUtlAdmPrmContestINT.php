<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmPrmContestINT extends InfraINT
{
    public static function montarSelectSinReprovacao($valorSelecionado ='N'){
        $select = '';

        $arrFrequencia = array( MdUtlAdmPrmContestRN::$RETORNO_SIM => MdUtlAdmPrmContestRN::$STR_SIM,
            MdUtlAdmPrmContestRN::$RETORNO_NAO =>MdUtlAdmPrmContestRN::$STR_NAO);
        foreach ($arrFrequencia as $key=>$frequencia){

            $add= '';

            if($valorSelecionado == $key){
                $add= 'selected = selected';
            }

            $select.='<option '.$add.' value="'.$key.'">'.$frequencia.'</option>';
        }

        return $select;
    }
}