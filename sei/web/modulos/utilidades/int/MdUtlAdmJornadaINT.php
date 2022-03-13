<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/07/2018 - criado por jhon.cast
*
*
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmJornadaINT extends InfraINT {

    public static function montarSelectTipoAjusteJornada($optionSelected = null){
        $option      = '<option @valor_selected value="@valor_tipo_jornada">@tipo_jornada </option>';
        $htmlRetorno = '<option value=""></option>';
        $arrAttr     = MdUtlAdmJornadaINT::retornaArrEnumTipoAjusteJornada();

        foreach ($arrAttr as $key=> $add){
            $selected    = !is_null($optionSelected) && $optionSelected == $key ? 'selected="selected"' : '';

            $optionAdd    = str_replace('@valor_selected', $selected, $option);
            $optionAdd    = str_replace('@valor_tipo_jornada',$key, $optionAdd);
            $optionAdd    = str_replace('@tipo_jornada',$add, $optionAdd);
            $htmlRetorno .= $optionAdd;
        }

        return $htmlRetorno;
    }

    public static function retornaArrEnumTipoAjusteJornada(){
        $arr = array(MdUtlAdmJornadaRN::$TIPO_JORNADA_GERAL=>MdUtlAdmJornadaRN::$STR_TIPO_JORNADA_GERAL, MdUtlAdmJornadaRN::$TIPO_JORNADA_ESPECIFICO => MdUtlAdmJornadaRN::$STR_TIPO_JORNADA_ESPECIFICO);
        return $arr;
    }

    public static function buscarUrlsAssinadasPorTipoControle($idTipoControle, $validarParams){
        $objMdTpCtrlRN       = new MdUtlAdmTpCtrlDesempRN();

        $existeParametrização = $objMdTpCtrlRN->verificaTipoControlePossuiParametrizacao($idTipoControle);
        $xml = '<Documento>';

        if($existeParametrização) {
            $strLinkMembros = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_usuario_selecionar&tipo_selecao=2&id_object=objLupaMembros&id_tipo_controle_utl=' . $idTipoControle.'&is_bol_usuario=1');
            $strLinkAjaxMembros = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=md_utl_adm_usuario_interno_auto_completar&id_tipo_controle_utl=' . $idTipoControle.'&is_bol_usuario=1');

            $xml .= '<LinkLupa>' . htmlspecialchars($strLinkMembros) . '</LinkLupa>';
            $xml .= '<LinkAjax>' . htmlspecialchars($strLinkAjaxMembros) . '</LinkAjax>';
            $xml .= '<Sucesso>1</Sucesso>';

        }else{
            $xml .= '<Sucesso>0</Sucesso>';
            $xml .= '<Mensagem>O Tipo de Controle selecionado não está parametrizado. Realize a parametrização do mesmo para incluir uma Jornada.</Mensagem>';
        }

        $xml .= '</Documento>';

        return $xml;
    }
    
    public static function validarDuplicidadeJornada($idTpControle, $nome, $idJornada = null){
        $objMdUtlJornadaRN = new MdUtlAdmJornadaRN();
        $objMdUtlJornadaDTO2 = new MdUtlAdmJornadaDTO();
        $objMdUtlJornadaDTO2->setStrNome($nome);
        $objMdUtlJornadaDTO2->setNumIdMdUtlAdmTpCtrlDesemp($idTpControle);

        if(!is_null($idJornada) && $idJornada != 0){
            $objMdUtlJornadaDTO2->setNumIdMdUtlAdmJornada($idJornada, InfraDTO::$OPER_DIFERENTE);
        }

        $existeRegistroDupl = $objMdUtlJornadaRN->contar($objMdUtlJornadaDTO2) > 0;

        $vl = $existeRegistroDupl ? '0' : '1';
        $xml = '<Documento>';
        $xml.= '<Sucesso>'.$vl.'</Sucesso>';
        $xml .= '</Documento>';
        
        return $xml;
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
