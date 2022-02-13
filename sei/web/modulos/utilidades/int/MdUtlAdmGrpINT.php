<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/09/2018 - criado por jhon.carvalho
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmGrpINT extends InfraINT {

    public static function montarSelectIdMdUtlAdmGrp($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdUtlAdmTpCtrlDesemp=''){
        $objMdUtlAdmGrpDTO = new MdUtlAdmGrpDTO();
        $objMdUtlAdmGrpDTO->retNumIdMdUtlAdmGrp();
        $objMdUtlAdmGrpDTO->retNumIdMdUtlAdmGrp();

        if ($numIdMdUtlAdmTpCtrlDesemp!==''){
          $objMdUtlAdmGrpDTO->setNumIdMdUtlAdmTpCtrlDesemp($numIdMdUtlAdmTpCtrlDesemp);
        }

        $objMdUtlAdmGrpDTO->setOrdNumIdMdUtlAdmGrp(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMdUtlAdmGrpRN = new MdUtlAdmGrpRN();
        $arrObjMdUtlAdmGrpDTO = $objMdUtlAdmGrpRN->listar($objMdUtlAdmGrpDTO);

        return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmGrpDTO, 'IdMdUtlAdmGrp', 'IdMdUtlAdmGrp');
    }

    public static function verificarExisteGruposParametrizado($idTipoControle, $idFila, $idTpProcedimento){

        $objMdUtlAdmGrpFilaProcRN = new MdUtlAdmGrpFilaProcRN();
        $idsGrupoFila = $objMdUtlAdmGrpFilaProcRN->getGruposFilaDesteProcesso($idTpProcedimento);

        if( is_null($idsGrupoFila)) return false;

        $objMdUtlAdmGrpFilaDTO = new MdUtlAdmGrpFilaDTO();
        $objMdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmFila($idFila);
        $objMdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
        $objMdUtlAdmGrpFilaDTO->setNumIdMdUtlAdmGrpFila($idsGrupoFila, InfraDTO::$OPER_IN);
        $objMdUtlAdmGrpFilaDTO->retNumIdMdUtlAdmGrpFila();
        $objMdUtlAdmGrpFilaDTO->retTodos();

        $objMdUtlAdmGrpFilaRN = new MdUtlAdmGrpFilaRN();
        $arrObjMdUtlAdmGrpFilaDTO = $objMdUtlAdmGrpFilaRN->listar($objMdUtlAdmGrpFilaDTO);

        $retorno = false;
        if (count($arrObjMdUtlAdmGrpFilaDTO) > 0){
            $retorno = true;
        }

        return $retorno;
    }
}
