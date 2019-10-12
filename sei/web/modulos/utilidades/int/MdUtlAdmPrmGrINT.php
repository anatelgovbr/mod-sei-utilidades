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

    $arrFrequencia = self::retornaArrPadraoFrequenciaDiaria();

    foreach ($arrFrequencia as $key=>$frequencia){

        $add= '';

        if($valorSelecionado == $key){
            $add= 'selected = selected';
        }

        $select.='<option '.$add.' value="'.$key.'">'.$frequencia.'</option>';
    }


      
    return $select;
  }

    public static function retornaArrPadraoFrequenciaDiaria(){
        $arrFrequencia = array( MdUtlAdmPrmGrRN::$FREQUENCIA_DIARIO => MdUtlAdmPrmGrRN::$STR_FREQUENCIA_DIARIO,
            MdUtlAdmPrmGrRN::$FREQUENCIA_SEMANAL => MdUtlAdmPrmGrRN::$STR_FREQUENCIA_SEMANAL,
            MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL => MdUtlAdmPrmGrRN::$STR_FREQUENCIA_MENSAL);

        return $arrFrequencia;
    }

    public static function montarSelectInicioPeriodo($valorSelecionado = '', $valueCombo = ''){
        $arrFrequencia = self::retornaArrInicioPeriodoParametros();

        if($valorSelecionado != '') {
            $select = '<option value="0"></option>';
            $arrFrequenciaSelecionado = $arrFrequencia[$valorSelecionado];

            if (count($arrFrequenciaSelecionado) > 0) {
                foreach ($arrFrequenciaSelecionado as $key => $frequencia) {
                    $add = '';
                    if ($valueCombo == $key) {
                        $add = 'selected = selected';
                    }
                    $select .= '<option ' . $add . ' value="' . $key . '">' . $frequencia . '</option>';
                }
                return $select;
            }
        }
        return '';
    }

    public static function montarSelectFimPeriodo($valorSelecionado = ''){
        $arrFrequencia = self::retornaArrFimPeriodoParametros();
        $arrFrequenciaSelecionado = $arrFrequencia[$valorSelecionado];

        if(count($arrFrequenciaSelecionado)> 0) {
            foreach ($arrFrequenciaSelecionado as $key => $frequencia) {
                $add = '';
                if ($valorSelecionado == $key) {
                    $add = 'selected = selected';
                }
                $select = '<option ' . $add . ' value="' . $key . '">' . $frequencia . '</option>';
            }
            return $select;
        }

        return '';
    }


    public static function retornaArrInicioPeriodoParametros(){
      $arrRetorno = array();
      $arrRetorno[MdUtlAdmPrmGrRN::$FREQUENCIA_DIARIO]  = array(MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_DIARIO => MdUtlAdmPrmGrRN::$STR_FREQUENCIA_INICIO_DIARIO);
      $arrRetorno[MdUtlAdmPrmGrRN::$FREQUENCIA_SEMANAL] = array(MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_SEMANAL_DOMINGO => MdUtlAdmPrmGrRN::$STR_FREQUENCIA_INICIO_SEMANAL_DOMINGO,
                                                                MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_SEMANAL_SEGUNDA => MdUtlAdmPrmGrRN::$STR_FREQUENCIA_INICIO_SEMANAL_SEGUNDA);
      $arrRetorno[MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL]  = array(MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRO_DIA_MES => MdUtlAdmPrmGrRN::$STR_FREQUENCIA_MENSAL_PRIMEIRO_DIA_MES,
                                                                MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRO_DIA_UTIL_MES => MdUtlAdmPrmGrRN::$STR_FREQUENCIA_MENSAL_PRIMEIRO_DIA_UTIL_MES,
                                                                MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRA_SEGUNDA_MES => MdUtlAdmPrmGrRN::$STR_FREQUENCIA_MENSAL_PRIMEIRA_SEGUNDA_MES);
      return $arrRetorno;
    }

    public static function retornaArrFimPeriodoParametros(){
        $arrRetorno = array();
        $arrRetorno[MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_DIARIO] = array(MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_DIARIO => MdUtlAdmPrmGrRN::$STR_FREQUENCIA_FIM_DIARIO);
        $arrRetorno[MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_SEMANAL_DOMINGO] = array(MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_SEMANAL_DOMINGO => MdUtlAdmPrmGrRN::$STR_FREQUENCIA_FIM_SEMANAL_DOMINGO);
        $arrRetorno[MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_SEMANAL_SEGUNDA] =array(MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_SEMANAL_SEGUNDA => MdUtlAdmPrmGrRN::$STR_FREQUENCIA_FIM_SEMANAL_SEGUNDA);
        $arrRetorno[MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRO_DIA_MES] = array(MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRO_DIA_MES => MdUtlAdmPrmGrRN::$STR_FREQUENCIA_MENSAL_ULTIMO_DIA_MES);
        $arrRetorno[MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRO_DIA_UTIL_MES] = array(MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRO_DIA_UTIL_MES => MdUtlAdmPrmGrRN::$STR_FREQUENCIA_MENSAL_ULTIMO_DIA_UTIL_MES);
        $arrRetorno[MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRA_SEGUNDA_MES] = array(MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRA_SEGUNDA_MES => MdUtlAdmPrmGrRN::$STR_FREQUENCIA_MENSAL_ULTIMA_SEGUNDA_MES);
        return $arrRetorno;
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

    public static function montarSelectSinRetornoUltimaFila($valorSelecionado =''){


        $select = '<option value=""></option>';

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

    public static function montarSelectRespostaTacita($valorSelecionado = ''){

        $select = '<option value=""></option>';

        $arrResposta = array( MdUtlAdmPrmGrRN::$APROVACAO_TACITA => MdUtlAdmPrmGrRN::$STR_APROVACAO_TACITA,
            MdUtlAdmPrmGrRN::$REPROVACAO_TACITA => MdUtlAdmPrmGrRN::$STR_REPROVACAO_TACITA);
        foreach ($arrResposta as $key=>$resposta){

            $add= '';

            if($valorSelecionado == $key){
                $add= 'selected = selected';
            }

            $select.='<option '.$add.' value="'.$key.'">'.$resposta.'</option>';
        }

        return $select;
    }

    public static function autoCompletarTipoProcedimento($strPalavrasPesquisa){

        $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
        $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
        $objTipoProcedimentoDTO->retStrNome();
        $objTipoProcedimentoDTO->setNumMaxRegistrosRetorno(50);
        $objTipoProcedimentoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objTipoProcedimentoRN = new TipoProcedimentoRN();

        $arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO);

        $strPalavrasPesquisa = trim($strPalavrasPesquisa);
        if ($strPalavrasPesquisa != ''){
            $ret = array();
            $strPalavrasPesquisa = strtolower($strPalavrasPesquisa);
            foreach($arrObjTipoProcedimentoDTO as $objTipoProcedimentoDTO){
                if (strpos(strtolower($objTipoProcedimentoDTO->getStrNome()),$strPalavrasPesquisa)!==false){
                    $ret[] = $objTipoProcedimentoDTO;
                }
            }
        }else{
            $ret = $arrObjTipoProcedimentoDTO;
        }
        return $ret;
    }

    public static function autoCompletarUsuarios($numIdOrgao, $strPalavrasPesquisa, $bolOutros, $bolExternos, $bolSiglaNome, $bolInativos){

        $objUsuarioDTO = new UsuarioDTO();

        if ($bolInativos){
            $objUsuarioDTO->setBolExclusaoLogica(false);
        }

        $objUsuarioDTO->retNumIdContato();
        $objUsuarioDTO->retNumIdUsuario();
        $objUsuarioDTO->retStrSigla();
        $objUsuarioDTO->retStrNome();
        $objUsuarioDTO->setNumMaxRegistrosRetorno(50);
        $objUsuarioDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);

        if (!InfraString::isBolVazia($numIdOrgao)){
            $objUsuarioDTO->setNumIdOrgao($numIdOrgao);
        }

        if ($bolOutros){
            $objUsuarioDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario(),InfraDTO::$OPER_DIFERENTE);
        }

        if (!$bolExternos){
            $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_SIP);
        }else{
            $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_EXTERNO);
        }

        $objUsuarioDTO->setNumMaxRegistrosRetorno(50);

        $objUsuarioDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objUsuarioRN = new UsuarioRN();
        $arrObjUsuarioDTO = $objUsuarioRN->pesquisar($objUsuarioDTO);

        if ($bolSiglaNome) {
            foreach ($arrObjUsuarioDTO as $objUsuarioDTO) {
                $objUsuarioDTO->setStrSigla($objUsuarioDTO->getStrNome() . ' (' . $objUsuarioDTO->getStrSigla() . ')');
            }
        }

        return $arrObjUsuarioDTO;
    }
    

}
