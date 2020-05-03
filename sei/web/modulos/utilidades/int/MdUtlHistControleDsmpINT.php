<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 09/10/2018 - criado por jhon.carvalho
 *
 * Versão do Gerador de Código: 1.41.0
 */

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlHistControleDsmpINT extends InfraINT {


    public static function formatarDataHora($dataHoraCompleta){
        $arrDataHoraCompleta = explode(' ', $dataHoraCompleta);
        $dataCompleta = $arrDataHoraCompleta[0];
        $horaCompleta = $arrDataHoraCompleta[1];
        $arrHoraCompleta =  explode(':', $horaCompleta);

        $hora = $arrHoraCompleta[0];
        $minuto = $arrHoraCompleta[1];

        return $dataCompleta.' '.$hora.':'.$minuto;
    }

    public static function getusuarioRevisor($dblIdProcedimento){

        $objMdUtlHistControleDsmpDTO = new MdUtlHistControleDsmpDTO();
        $objMdUtlHistControleDsmpDTO->setDblIdProcedimento($dblIdProcedimento);
        $objMdUtlHistControleDsmpDTO->setNumMaxRegistrosRetorno(1);
        $objMdUtlHistControleDsmpDTO->setOrdDthAtual(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objMdUtlHistControleDsmpDTO->retStrSiglaUsuarioDist();
        $objMdUtlHistControleDsmpDTO->retStrNomeUsuarioDist();

        $objMdUtlHistControleDsmpRN = new MdUtlHistControleDsmpRN();
        $usuDistRevisor = $objMdUtlHistControleDsmpRN->listar($objMdUtlHistControleDsmpDTO);

        foreach ($usuDistRevisor as $obj){
            $objUsuRevisor = $obj;
        }

       return $objUsuRevisor;
    }

}
