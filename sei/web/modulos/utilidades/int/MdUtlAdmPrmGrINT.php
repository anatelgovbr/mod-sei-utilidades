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

        if(!is_null($arrFrequenciaSelecionado)) {
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
        $objTipoProcedimentoDTO->setStrNome('%'.trim($strPalavrasPesquisa).'%',InfraDTO::$OPER_LIKE);
        $objTipoProcedimentoDTO->setNumMaxRegistrosRetorno(50);
        $objTipoProcedimentoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
        $objTipoProcedimentoDTO->retStrNome();        

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

    public static function convertToHoursMins($time)
    {
    	if ( is_string($time) ) return $time;

        $hours = intVal($time / 60);
        $minutes = ($time % 60);
        if ($time == 0) {
            $format = '0min';
        } else {
            if ($time < 60) {
                $format = sprintf('%1dmin', $minutes);
            } else {
                if($minutes > 0) {
                    $format = sprintf("%1dh %1dmin", $hours, $minutes);
                } else {
                    $format = sprintf('%1dh', $hours);
                }
            }
        }

        return trim($format);
    }

    public static function convertToMins($valor)
    {
        $arrValor = explode(" ", trim($valor));
        $minutos = 0;

        if(count($arrValor) == 1){
            if(strripos($arrValor[0], 'min') !== false){
                $minutos = (int) str_replace("min", "", $arrValor[0]) + $minutos;
            }
            if(strripos($arrValor[0], 'h') !== false) {
                $minutos = (int) str_replace("h", "", $arrValor[0]) * 60;
            }
        } else {
            $minutos = (int)str_replace("h", "", $arrValor[0]) * 60;
            $minutos = (int)str_replace("h", "", $arrValor[1]) + $minutos;
        }
        return $minutos;
    }


    public static function recuperarTextoFrequenciaTooltipDinamico($idControleDesempenho = null)
    {
        $objTpCtrlDsmpRN = new MdUtlAdmTpCtrlDesempRN();
        $objTpCtrlDsmpDTO = new MdUtlAdmTpCtrlDesempDTO();

        $objTpCtrlDsmpDTO->setNumIdMdUtlAdmTpCtrlDesemp( $idControleDesempenho );
        $objTpCtrlDsmpDTO->retNumIdMdUtlAdmPrmGr();

        $rs = $objTpCtrlDsmpRN->consultar( $objTpCtrlDsmpDTO );

        $objMdUtlAdmPrmGrRN = new MdUtlAdmPrmGrRN();
        $objMdUtlAdmPrmGrDTO = new MdUtlAdmPrmGrDTO();
        $objMdUtlAdmPrmGrDTO->setNumIdMdUtlAdmPrmGr( $rs->getNumIdMdUtlAdmPrmGr() );
        $objMdUtlAdmPrmGrDTO->retStrStaFrequencia();
        $objMdUtlAdmPrmGrDTO->retNumInicioPeriodo();
        $objMdUtlAdmPrmGr = $objMdUtlAdmPrmGrRN->consultar($objMdUtlAdmPrmGrDTO);

        $frequencia = '';

        switch ($objMdUtlAdmPrmGr->getStrStaFrequencia()){
             case 'D':
                 $frequencia = MdUtlAdmPrmGrRN::$STR_FREQUENCIA_DIARIO;
                 $textoFrequencia = 'Para o Tipo de Controle selecionado, o Período de distribuição e acompanhamento é "Diário", tendo sempre início "todo dia às 0h", o que marca o fim do Período anterior.';
                 break;
             case 'S':
                 $frequencia = MdUtlAdmPrmGrRN::$STR_FREQUENCIA_SEMANAL;
                 switch ($objMdUtlAdmPrmGr->getNumInicioPeriodo()){
                     case MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_SEMANAL_DOMINGO:
                         $textoFrequencia = 'Para o Tipo de Controle selecionado, o Período de distribuição e acompanhamento é "Semanal", tendo sempre início "todo domingo às 0h", o que marca o fim do Período anterior.';
                         break;
                     case MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_SEMANAL_SEGUNDA:
                         $textoFrequencia = 'Para o Tipo de Controle selecionado, o Período de distribuição e acompanhamento é "Semanal", tendo sempre início "toda segunda-feira às 0h", o que marca o fim do Período anterior.';
                         break;
                 }
                 break;
             case 'M':
                 $frequencia = MdUtlAdmPrmGrRN::$STR_FREQUENCIA_MENSAL;
                 switch ($objMdUtlAdmPrmGr->getNumInicioPeriodo()){
                     case MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRO_DIA_MES:
                         $textoFrequencia = 'Para o Tipo de Controle selecionado, o Período de distribuição e acompanhamento é "Mensal", tendo sempre início "todo primeiro dia do mês às 0h", o que marca o fim do Período anterior.';
                         break;
                     case MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRO_DIA_UTIL_MES:
                         $textoFrequencia = 'Para o Tipo de Controle selecionado, o Período de distribuição e acompanhamento é "Mensal", tendo sempre início "todo primeiro dia útil do mês às 0h", o que marca o fim do Período anterior.';
                         break;
                     case MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRA_SEGUNDA_MES:
                         $textoFrequencia = 'Para o Tipo de Controle selecionado, o Período de distribuição e acompanhamento é "Mensal", tendo sempre início "toda primeira segunda-feira do mês às 0h", o que marca o fim do Período anterior.';
                         break;
                 }
                 break;
        }

        $retorno['frequencia'] = $frequencia;
        $retorno['textoFrequencia'] = $textoFrequencia;

        return $retorno;
    }

    public static function recuperarTextoFrequenciaTooltipDinamicoMeusProcessos($idControleDesempenho = null, $strTela = null)
    {
    	$msgPadrao = $strTela ? MdUtlMensagemINT::setMensagemPadraoPersonalizada(MdUtlMensagemINT::$MSG_UTL_137,[$strTela]) : MdUtlMensagemINT::$MSG_UTL_136;
        if (!$idControleDesempenho){
	        return $msgPadrao;
        }
        $dados = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDinamico($idControleDesempenho);
        return $msgPadrao ."\n \n". $dados['textoFrequencia'];
    }

    public static function recuperarTextoFrequenciaTooltipDinamicoDistribuirProcessos($idControleDesempenho)
    {
	      $msgPadrao = MdUtlMensagemINT::$MSG_UTL_136;
	      $msgDist   = 'A Carga Exigível no Período Selecionado somente será exibida depois que for aplicado o filtro "Membro Participante". \n \n '. $msgPadrao;
        if (!$idControleDesempenho) {
	        return $msgDist;
        }
        $dados = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDinamico($idControleDesempenho);
        return $msgDist ."\n \n". $dados['textoFrequencia'];
    }

    public static function recuperarTextoFrequenciaTooltipDinamicoDistribuirPessoaCargaHoraria($idControleDesempenho)
    {
        if (!$idControleDesempenho) {
            return 'A Carga Horária Distribuída no Período somente será exibida depois que for selecionado o Membro Participante.\n \n A Carga total abrange todo e qualquer tempo que foi distribuído para o Membro Participante no Tipo de Controle indicado, dentro do Período em andamento, conforme definido nos parâmetros gerais do Tipo de Controle de Desempenho.\n \n Para o Tipo de Controle selecionado, o Período de distribuição e acompanhamento é "Semanal", tendo sempre início toda segunda-feira às 0h, o que marca o fim do Período anterior;';
        }

        $dados = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDinamico($idControleDesempenho);
        return 'A Carga Horária Distribuída no Período somente será exibida depois que for selecionado o Membro Participante.\n \n A Carga total abrange todo e qualquer tempo que foi distribuído para o Membro Participante no Tipo de Controle indicado, dentro do Período em andamento, conforme definido nos parâmetros gerais do Tipo de Controle de Desempenho.\n \n '. $dados['textoFrequencia'];
    }

    public static function recuperarTextoFrequenciaTooltipDinamicoDistribuirPessoaTempoExecutadoPeriodo($idControleDesempenho)
    {
        if (!$idControleDesempenho) {
            return 'O Total de Tempo Executado no Período somente será exibido depois que for selecionado o Membro Participante.\n \n O Total abrange toda e qualquer execução realizada pelo Membro Participante no Tipo de Controle indicado, dentro do Período em andamento, conforme definido nos parâmetros gerais do Tipo de Controle de Desempenho.\n \n Para o Tipo de Controle selecionado, o Período de distribuição e acompanhamento é "Semanal", tendo sempre início toda segunda-feira às 0h, o que marca o fim do Período anterior;';
        }

        $dados = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDinamico($idControleDesempenho);
        return 'O Total de Tempo Executado no Período somente será exibido depois que for selecionado o Membro Participante.\n \n O Total abrange toda e qualquer execução realizada pelo Membro Participante no Tipo de Controle indicado, dentro do Período em andamento, conforme definido nos parâmetros gerais do Tipo de Controle de Desempenho.\n \n '. $dados['textoFrequencia'];
    }

    public static function recuperarTextoFrequenciaTooltipDinamicoCargaHorariaDistribuidaPeriodo($idControleDesempenho)
    {
        if (!$idControleDesempenho) {
            return 'A Carga total abrange todo e qualquer tempo que foi distribuído para o usuário logado no Tipo de Controle indicado, dentro do Período selecionado, conforme definido nos parâmetros gerais do Tipo de Controle de Desempenho.\n \n Para o Tipo de Controle selecionado, o Período de distribuição e acompanhamento  "Semanal", tendo sempre início toda segunda-feira às 0h, o que marca o fim do Período anterior;';
        }

        $dados = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDinamico($idControleDesempenho);
        return 'A Carga total abrange todo e qualquer tempo que foi distribuído para o usuário logado no Tipo de Controle indicado, dentro do Período selecionado, conforme definido nos parâmetros gerais do Tipo de Controle de Desempenho.\n \n '. $dados['textoFrequencia'];
    }

    public static function recuperarTextoFrequenciaTooltipDistribuicaoDinamicoCargaHorariaDistribuidaPeriodo($idControleDesempenho)
    {
        if (!$idControleDesempenho) {
            return 'A Carga Horária Distribuída no Período somente será exibida depois que for aplicado o filtro "Responsável".\n \n A Carga total abrange todo e qualquer tempo que foi distribuído para o responsável no Tipo de Controle indicado, dentro do Período selecionado, conforme definido nos parâmetros gerais do Tipo de Controle de Desempenho.\n \n Para o Tipo de Controle selecionado, o Período de distribuido e acompanhamento  "Semanal", tendo sempre início toda segunda-feira às 0h, o que marca o fim do Período anterior;';
        }

        $dados = MdUtlAdmPrmGrINT::recuperarTextoFrequenciaTooltipDinamico($idControleDesempenho);
        return 'A Carga Horária Distribuída no Período somente será exibida depois que for aplicado o filtro "Responsável".\n \n A Carga total abrange todo e qualquer tempo que foi distribuído para o responsável no Tipo de Controle indicado, dentro do Período selecionado, conforme definido nos parâmetros gerais do Tipo de Controle de Desempenho.\n \n'. $dados['textoFrequencia'];
    }

    public static function retornaTipoPeriodo($idTipoControle)
    {
	    $objMdUtlAdmTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
	    $objMdUtlAdmTpCtrlDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
	    $objMdUtlAdmTpCtrlDTO->retStrStaFrequencia();
	    $objMdUtlAdmTpCtrlDTO = ( new MdUtlAdmTpCtrlDesempRN() )->consultar($objMdUtlAdmTpCtrlDTO);

	    $retorno = '';
	    switch($objMdUtlAdmTpCtrlDTO->getStrStaFrequencia()){
		    case MdUtlAdmPrmGrRN::$FREQUENCIA_DIARIO:
			    $retorno = MdUtlAdmPrmGrRN::$STR_FREQUENCIA_DIARIO;
			    break;

		    case MdUtlAdmPrmGrRN::$FREQUENCIA_SEMANAL:
			    $retorno = MdUtlAdmPrmGrRN::$STR_FREQUENCIA_SEMANAL;
			    break;

		    case MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL:
			    $retorno = MdUtlAdmPrmGrRN::$STR_FREQUENCIA_MENSAL;
			    break;
	    }
      return $retorno;
    }

}
