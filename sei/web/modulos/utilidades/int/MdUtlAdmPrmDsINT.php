<?php

/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 30/09/2019 - criado por rafael.cast
 *
 * Versão do Gerador de Código: 1.3.0
 */

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmPrmDsINT extends InfraINT {

    public static function montarSelectSinRetorno($valorSelecionado =''){


        $select = '<option value="0"></option>';

        $arrFrequencia = array( MdUtlAdmPrmDistribRN::$RETORNO_SIM => 'Sim',
            MdUtlAdmPrmDistribRN::$RETORNO_NAO =>'Não');
        foreach ($arrFrequencia as $key=>$frequencia){

            $add= '';

            if($valorSelecionado == $key){
                $add= 'selected = selected';
            }

            $select.='<option '.$add.' value="'.$key.'">'.$frequencia.'</option>';
        }

        return $select;
    }

    public static function montarSelectPrioridade($valorSelecionado) {

        $select = '<option value="1"></option>';
        $valor = count($valorSelecionado);

        for ($i = 1; $i < $valor+1; $i++) {
            $arrFrequencia = array($i);
            $select .= '<option value="'.$i.'">'.$i.'</option>';
        }
        foreach ($arrFrequencia as $key => $frequencia) {

            $add = '';

            if ($valorSelecionado == $key) {
                $add = 'selected = selected';
            }

            $select .= '<option ' . $add . ' value="' . $key . '">' . $frequencia . '</option>';
        }

        return $select;
    }

    public static function autoCompletarStatus($strPalavrasPesquisa, $idTpCtrl, $isPrmDistrib = false){
        $xml = '';
        $arrObjStatus = array(
            MdUtlControleDsmpRN::$AGUARDANDO_TRIAGEM => MdUtlControleDsmpRN::$STR_AGUARDANDO_TRIAGEM,
            MdUtlControleDsmpRN::$AGUARDANDO_ANALISE => MdUtlControleDsmpRN::$STR_AGUARDANDO_ANALISE,
            MdUtlControleDsmpRN::$AGUARDANDO_REVISAO => MdUtlControleDsmpRN::$STR_AGUARDANDO_REVISAO,
            MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_TRIAGEM => MdUtlControleDsmpRN::$STR_AGUARDANDO_CORRECAO_TRIAGEM,
            MdUtlControleDsmpRN::$AGUARDANDO_CORRECAO_ANALISE => MdUtlControleDsmpRN::$STR_AGUARDANDO_CORRECAO_ANALISE,
        );

        if (!InfraString::isBolVazia($strPalavrasPesquisa) || empty($strPalavrasPesquisa)){

            $results = array_filter($arrObjStatus, function ($item) use ($strPalavrasPesquisa) {
                if (stripos($item, $strPalavrasPesquisa) !== false) {
                    return true;
                }
                return false;
            });

            $xml = '<itens>';
            foreach ($results as $key => $result) {
                $xml .= '<item id="'.$key.'" descricao="'.$result.'"></item>';
            }
            $xml .= '</itens>';

        }

        return $xml;
    }

}
