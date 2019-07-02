<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/07/2018 - criado por jaqueline.mendes
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmTpCtrlDesempINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmTpCtrlDesemp($strPrimeiroItemValor, $strValorItemSelecionado){

    $objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmRelTpCtrlDesempUndDTO();
    if($strPrimeiroItemValor){
        $objMdUtlAdmTpCtrlDesempDTO->retNumIdUnidade();
        $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($strValorItemSelecionado);
    } else {
        $objMdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmTpCtrlDesemp();
        $objMdUtlAdmTpCtrlDesempDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    }
    $objMdUtlAdmTpCtrlDesempDTO->retTodos();
    $objMdUtlAdmTpCtrlDesempDTO->retStrNomeTipoControle();
    $objMdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmPrmGr();
    
    $objMdUtlAdmTpCtrlDesempDTO->setOrdNumIdMdUtlAdmTpCtrlDesemp(InfraDTO::$TIPO_ORDENACAO_ASC);
    
    $objMdUtlAdmTpCtrlDesempRN = new MdUtlAdmRelTpCtrlDesempUndRN();
    $arrObjMdUtlAdmTpCtrlDesempDTO = $objMdUtlAdmTpCtrlDesempRN->listar($objMdUtlAdmTpCtrlDesempDTO);
    
    $htmlRetorno = '';
    foreach ($arrObjMdUtlAdmTpCtrlDesempDTO as $objDTO){
      $selected       = !is_null($strValorItemSelecionado) && $objDTO->getNumIdMdUtlAdmTpCtrlDesemp() == $strValorItemSelecionado ? 'selected = selected' : '';
      $parametrizacao = is_null($objDTO->getNumIdMdUtlAdmPrmGr()) ? 'N' : 'S';
      $htmlRetorno .= '<option parametros="'.$parametrizacao.'" '.$selected.' value="'.$objDTO->getNumIdMdUtlAdmTpCtrlDesemp().'">'.$objDTO->getStrNomeTipoControle().'</option>';
    }

    return $htmlRetorno;
  }

    public static function montarSelectMembros($arrId,$valorSelecionado){


        $select = '<option value="" ></option>';

        if(is_array($arrId) && count($arrId)>0) {

            $mdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();
            $mdUtlAdmTpCtrlDesempRN = new MdUtlAdmTpCtrlDesempRN();

            $mdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($arrId, InfraDTO::$OPER_IN);
            $mdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmPrmGr();
            
            $mdUtlAdmTpCtrlDesemp = $mdUtlAdmTpCtrlDesempRN->listar($mdUtlAdmTpCtrlDesempDTO);
            $arrIdMdUtlAdmPrmGr = array();
            
            for ($i = 0; $i < count($mdUtlAdmTpCtrlDesemp); $i++) {
                $arrIdMdUtlAdmPrmGr[] = $mdUtlAdmTpCtrlDesemp[$i]->getNumIdMdUtlAdmPrmGr();
            }
            
            if(count($arrIdMdUtlAdmPrmGr)>0) {
                $mdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
                $mdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();
                
                $mdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($arrIdMdUtlAdmPrmGr, InfraDTO::$OPER_IN);
                $mdUtlAdmPrmGrUsuDTO->retNumIdUsuario();
                $mdUtlAdmPrmGrUsuDTO->retStrNome();
                $mdUtlAdmPrmGrUsuDTO->setDistinct(true);
                $mdUtlAdmPrmGrUsuDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

                $mdUtlAdmPrmGrUsu = $mdUtlAdmPrmGrUsuRN->listar($mdUtlAdmPrmGrUsuDTO);


                if (count($mdUtlAdmPrmGrUsu) > 0) {
                    $arrUsuarios = array();
                    if (count($mdUtlAdmPrmGrUsu) > 0) {
                        //verifica se existe mais de um mesmo usuário para varios tipos de jornadas

                        for ($i = 0; $i < count($mdUtlAdmPrmGrUsu); $i++) {
                            $add = "";
                            if ($mdUtlAdmPrmGrUsu[$i]->getNumIdUsuario() == $valorSelecionado) {
                                $add = 'selected="selected"';
                            }
                            $select .= '<option ' . $add . ' value="' . $mdUtlAdmPrmGrUsu[$i]->getNumIdUsuario() . '">' . $mdUtlAdmPrmGrUsu[$i]->getStrNome() . '</option>';
                        }

                    }
                }
            }
        }

        return $select;

    }
  
  
}
