<?php

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlContestacaoINT extends InfraINT {

    public static function montarSelectJustificativa($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $idTipoControle) {

        $objMdUtlAdmJustContestDTO = new MdUtlAdmJustContestDTO();
        $objMdUtlAdmJustContesRN   = new MdUtlAdmJustContestRN();

        if($_GET['acao'] == 'md_utl_contest_revisao_consultar') {
            $objMdUtlAdmJustContestDTO->setBolExclusaoLogica(false);

        }

        $objMdUtlAdmJustContestDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
        $objMdUtlAdmJustContestDTO->retStrSinAtivo();
        $objMdUtlAdmJustContestDTO->retNumIdMdUtlAdmTpCtrlDesemp();
        $objMdUtlAdmJustContestDTO->retStrNome();
        $objMdUtlAdmJustContestDTO->retStrDescricao();
        $objMdUtlAdmJustContestDTO->retNumIdMdUtlAdmJustContest();
        $objMdUtlAdmJustContestDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
        $arrObjMdUtlAdmJustContest = $objMdUtlAdmJustContesRN->listar($objMdUtlAdmJustContestDTO);


       return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmJustContest, 'IdMdUtlAdmJustContest', 'Nome');
    }


}
