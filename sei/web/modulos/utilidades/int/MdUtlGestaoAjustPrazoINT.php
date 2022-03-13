<?

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlGestaoAjustPrazoINT extends InfraINT {

    public static function retornaArrStatusProcesso(){
        $arrRetorno = array();
        $arrRetorno[MdUtlControleDsmpRN::$EM_ANALISE] =  MdUtlControleDsmpRN::$STR_EM_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$EM_REVISAO] = MdUtlControleDsmpRN::$STR_EM_REVISAO;
        $arrRetorno[MdUtlControleDsmpRN::$EM_CORRECAO_ANALISE] = MdUtlControleDsmpRN::$STR_EM_CORRECAO_ANALISE;
        $arrRetorno[MdUtlControleDsmpRN::$EM_CORRECAO_TRIAGEM] = MdUtlControleDsmpRN::$STR_EM_CORRECAO_TRIAGEM;

        return $arrRetorno;
    }

    public static function montarSelectStatusProcesso($valorSelecionado = ''){
        $arrDados = self::retornaArrStatusProcesso();
        $html = '<option value=""></option>';

        foreach ($arrDados as $key => $status) {
            $selected = '';
            if ($valorSelecionado != '' && $valorSelecionado != null && $valorSelecionado == $key) {
                $selected = 'selected=selected';
            }
            $html .= '<option ' . $selected . ' value="' . $key . '">' . $status . '</option>';
        }
        return $html;
    }

    public static function montarSelectServidor($valorSelecionado = '', $arrObjsResponsavelDTO = null){

        $numRegistros = count($arrObjsResponsavelDTO);
        $select='<option value=""></option>';
        $add='';

        for ($i = 0; $i < $numRegistros; $i++) {
            $add = "";
            $idUsuarioDist   = $arrObjsResponsavelDTO[$i]->getNumIdUsuarioDistribuicao();
            $nomeUsuarioDist = $arrObjsResponsavelDTO[$i]->getStrNomeUsuarioDistribuicao();

            if ($idUsuarioDist == $valorSelecionado) {
                $add = 'selected = selected';
            }

            $select .= '<option ' . $add . ' value="' . $idUsuarioDist . '" >' . $nomeUsuarioDist. '</option>';
        }


        return $select;
    }

    public static function montarTipoSolicitacao($valor){

        $retorno = '';

        switch ($valor){
            case MdUtlControleDsmpRN::$TP_SOLICITACAO_DILACAO:
                $retorno = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_DILACAO;
                break;
            case MdUtlControleDsmpRN::$TP_SOLICITACAO_SUSPENSAO:
                $retorno = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_SUSPENSAO;
                break;
            case MdUtlControleDsmpRN::$TP_SOLICITACAO_INTERRUPCAO:
                $retorno = MdUtlControleDsmpRN::$STR_TP_SOLICITACAO_INTERRUPCAO;
                break;
        }
        return $retorno;
    }

    public static function formatarData($dataHoraCompleta, $separacaoData = '-'){
        if(!is_null($dataHoraCompleta)) {
            $arrDataHoraCompleta = explode(' ', $dataHoraCompleta);
            $dataCompleta = $arrDataHoraCompleta[0];
            $arrDataFormatada = explode($separacaoData, $dataCompleta);

            return $arrDataFormatada[0] . '/' . $arrDataFormatada[1] . '/' . $arrDataFormatada[2];
        }
    }

}
