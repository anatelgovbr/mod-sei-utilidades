<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 18/07/2018 - criado por jhon.cast
 *
 *
 */

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmFilaPrmGrUsuINT extends InfraINT {

    public static function consultarVinculoFilaUsuario($dados){
        $mdUtlAdmFilaPrmGrUsuDTO = new MdUtlAdmFilaPrmGrUsuDTO();
        $mdUtlAdmFilaPrmGrUsuRN = new MdUtlAdmFilaPrmGrUsuRN();

        $mdUtlAdmFilaPrmGrUsuDTO->setNumIdMdUtlAdmPrmGrUsu($dados);
        $mdUtlAdmFilaPrmGrUsuDTO->retNumIdUsuario();
        $mdUtlAdmFilaPrmGrUsuDTO->retStrNomeUsuario();


        $numRegistro = $mdUtlAdmFilaPrmGrUsuRN->contar($mdUtlAdmFilaPrmGrUsuDTO);

        $xml = '<dados>';
        if($numRegistro >0) {
            $xml .= '<sucesso>1</sucesso>';

            $xml .= '<msg>';
            $xml .= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_82);
            $xml .= '</msg>';
        }else{
            $xml .= '<sucesso>0</sucesso>';
            // $xml .= '<msg></msg>';
        }
        $xml .='</dados>';

        return $xml;
    }

    public static function autoCompletarUsuarioParticipante($strPalavrasPesquisa, $idFila, $idStatus){
        $pesquisa = null;
        $objUsuarioRN = new UsuarioRN();
        $strPapelUsuario = MdUtlAdmFilaINT::getPapeisDeUsuario($idStatus);
        if (!is_null($strPapelUsuario)) {
            $objMdUtlAdmFilaPrmUsuRN = new MdUtlAdmFilaPrmGrUsuRN();
            $arrDTO = $objMdUtlAdmFilaPrmUsuRN->getUsuarioPorPapel(array($strPapelUsuario, $idFila));
            $idsUsuario = InfraArray::converterArrInfraDTO($arrDTO, 'IdUsuario');

            $objUsuarioDTO = new UsuarioDTO();
            //$objUsuarioDTO->retTodos();
            $objUsuarioDTO->retStrNome();
            $objUsuarioDTO->retStrSigla();
            $objUsuarioDTO->retNumIdUsuario();
            $objUsuarioDTO->setNumIdUsuario($idsUsuario, InfraDTO::$OPER_IN);
            $objUsuarioDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);
            $objUsuarioDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
            $pesquisa = $objUsuarioRN->pesquisar($objUsuarioDTO);


            foreach ($pesquisa as $objUsuarioDTO) {
                $objUsuarioDTO->setStrSigla($objUsuarioDTO->getStrNome() . ' (' . $objUsuarioDTO->getStrSigla() . ')');
            }
        }

        return $pesquisa;
    }

    public static function montarSelectResponsavel($valorSelecionado = '', $arrObjsResponsavelDTO = null){
        $numRegistros = count($arrObjsResponsavelDTO);
        $select='<option value=""></option>';
        $add='';

        for ($i = 0; $i < $numRegistros; $i++) {
            $add = "";

            if ($arrObjsResponsavelDTO[$i]->getNumIdUsuario() == $valorSelecionado) {
                $add = 'selected = selected';
            }

            $select .= '<option ' . $add . ' value="' . $arrObjsResponsavelDTO[$i]->getNumIdUsuario() . '" >' . $arrObjsResponsavelDTO[$i]->getStrNomeUsuario() . '</option>';
        }

        return $select;
    }
}
