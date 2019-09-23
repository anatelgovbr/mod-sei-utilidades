<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/07/2018 - criado por jhon.cast
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../../../SEI.php';

class MdUtlAdmPrmGrUsuINT extends InfraINT {

  public static function montarSelectIdMdUtlAdmPrmGrUsu($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMdUtlAdmPrmGr='', $numIdUsuario=''){
    $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
    $objMdUtlAdmPrmGrUsuDTO->retNumIdMdUtlAdmPrmGrUsu();
    $objMdUtlAdmPrmGrUsuDTO->retNumIdMdUtlAdmPrmGrUsu();

    if ($numIdMdUtlAdmPrmGr!==''){
      $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGr($numIdMdUtlAdmPrmGr);
    }

    if ($numIdUsuario!==''){
      $objMdUtlAdmPrmGrUsuDTO->setNumIdUsuario($numIdUsuario);
    }

    $objMdUtlAdmPrmGrUsuDTO->setOrdNumIdMdUtlAdmPrmGrUsu(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objMdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();
    $arrObjMdUtlAdmPrmGrUsuDTO = $objMdUtlAdmPrmGrUsuRN->listar($objMdUtlAdmPrmGrUsuDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMdUtlAdmPrmGrUsuDTO, 'IdMdUtlAdmPrmGrUsu', 'IdMdUtlAdmPrmGrUsu');
  }

  public static function montarSelectStaTipoPresenca($strValorItemSelecionado =''){
      $select = '<option value="0"></option>';

      $arrFrequencia = array( MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_DIFERENCIADO =>'Diferenciado',
          MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_PRESENCIAL => 'Presencial',
          MdUtlAdmPrmGrUsuRN::$TP_PRESENCA_TELETRABALHO => 'Teletrabalho' );
      foreach ($arrFrequencia as $key=>$frequencia){

          $add= '';

          if($strValorItemSelecionado == $key){
              $add= 'selected = selected';
          }

          $select.='<option '.$add.' value="'.$key.'">'.$frequencia.'</option>';
      }

      return $select;
  }

  public static function montarSelectStaTipoJornada($strValorItemSelecionado = ''){

      $select = '<option value="0"></option>';

      $arrFrequencia = array( MdUtlAdmPrmGrUsuRN::$TIPOJORNADA_INTEGRAL =>'Integral',
          MdUtlAdmPrmGrUsuRN::$TIPOJORNADA_REDUZIDO => 'Reduzido');
      foreach ($arrFrequencia as $key=>$frequencia){
          $add= '';

          if($strValorItemSelecionado == $key){
              $add= 'selected = selected';
          }

          $select.='<option '.$add.' value="'.$key.'">'.$frequencia.'</option>';
      }

      return $select;
  }
    public static function autoCompletarUsuariosInternos($numIdOrgao, $strPalavrasPesquisa, $idTpControle){

        $arrObjUsuarioDTO = null;

       if(!is_null($idTpControle) && $idTpControle !='') {
           $objMdUtlAdmTpCtrlRN = new MdUtlAdmTpCtrlDesempRN();

           $objMdUtlAdmTpCtrlDTO = new MdUtlAdmTpCtrlDesempDTO();
           $objMdUtlAdmTpCtrlDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpControle);
           $objMdUtlAdmTpCtrlDTO->retNumIdMdUtlAdmPrmGr();
           $objMdUtlAdmTpCtrlDTO->setNumTotalRegistros(1);
           $objMdUtlAdmTpCtrlDTO = $objMdUtlAdmTpCtrlRN->consultar($objMdUtlAdmTpCtrlDTO);

           if (!is_null($objMdUtlAdmTpCtrlDTO)) {

               $idParams = $objMdUtlAdmTpCtrlDTO->getNumIdMdUtlAdmPrmGr();

               $objUsuarioDTO = new MdUtlAdmPrmGrUsuDTO();
               $objUsuarioDTO->retNumIdMdUtlAdmPrmGrUsu();
               $objUsuarioDTO->retNumIdUsuario();
               $objUsuarioDTO->retStrSigla();
               $objUsuarioDTO->retStrNome();
               $objUsuarioDTO->setNumIdMdUtlAdmPrmGr($idParams);

               $objUsuarioDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);

               if (!InfraString::isBolVazia($numIdOrgao)) {
                   $objUsuarioDTO->setNumIdOrgao($numIdOrgao);
               }

               $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_SIP);
               $objUsuarioDTO->setNumMaxRegistrosRetorno(50);
               $objUsuarioDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

               $objUsuarioRN = new MdUtlAdmPrmGrUsuRN();
               $arrObjUsuarioDTO = $objUsuarioRN->pesquisarUsuarioParametros($objUsuarioDTO);

               foreach ($arrObjUsuarioDTO as $objUsuarioDTO) {
                   $objUsuarioDTO->setStrSigla($objUsuarioDTO->getStrNome() . ' (' . $objUsuarioDTO->getStrSigla() . ')');
               }
           }
       }

        return $arrObjUsuarioDTO;
    }

    public static function buscarNomeDescricaoUsuario($ids){
        $xml            = '';
        $objMdUsuPrmRN  = new MdUtlAdmPrmGrUsuRN();
        $objMdUsuPrmDTO = new MdUtlAdmPrmGrUsuDTO();
        $objMdUsuPrmDTO->setNumIdMdUtlAdmPrmGrUsu($ids, InfraDTO::$OPER_IN);
        $objMdUsuPrmDTO->retNumIdUsuario();
        $objMdUsuPrmDTO->retStrNome();
        $objMdUsuPrmDTO->retStrSigla();
        $objMdUsuPrmDTO->retNumIdMdUtlAdmPrmGrUsu();

        $count = $objMdUsuPrmRN->contar($objMdUsuPrmDTO);
        if($count > 0) {
            $arrRetLista = $objMdUsuPrmRN->listar($objMdUsuPrmDTO);

            $xml = '<Documento>';
            foreach($arrRetLista as $objDTO){
                //$arrRetorno[$objDTO->getNumIdMdUtlAdmPrmGrUsu()] = htmlentities()

                $id = $objDTO->getNumIdMdUtlAdmPrmGrUsu();
                $xml .= '<IdUsuario'.$id.'>';
                $xml .= htmlspecialchars('<a alt="'.$objDTO->getStrNome().'" title="'.$objDTO->getStrNome().'" class="ancoraSigla"> '.$objDTO->getStrSigla().' </a>');
                $xml .= '</IdUsuario'.$id.'>';
            }
            $xml .= '</Documento>';
        }



        return $xml;
    }
    public static function buscarNomeDescricaoUsuarioSelecionado($ids){
        $xml            = '';
        $objUsuarioRN  = new UsuarioRN();
        $objUsuarioDTO = new UsuarioDTO();

        $objUsuarioDTO->setNumIdUsuario($ids, InfraDTO::$OPER_IN);
        $objUsuarioDTO->retStrNome();
        $objUsuarioDTO->retStrSigla();
        $objUsuarioDTO->retNumIdUsuario();

        $count = $objUsuarioRN->contarRN0492($objUsuarioDTO);

        if($count > 0) {
            $arrRetLista = $objUsuarioRN->listarRN0490($objUsuarioDTO);

            $xml = '<Documento>';
            foreach($arrRetLista as $objDTO){
                //$arrRetorno[$objDTO->getNumIdMdUtlAdmPrmGrUsu()] = htmlentities()

                $id = $objDTO->getNumIdUsuario();
                $xml .= '<IdUsuario'.$id.'>';
                $xml .= htmlspecialchars('<a alt="'.$objDTO->getStrNome().'" title="'.$objDTO->getStrNome().'" class="ancoraSigla"> '.$objDTO->getStrSigla().' </a>');
                $xml .= '</IdUsuario'.$id.'>';
            }
            $xml .= '</Documento>';
        }

        return $xml;
    }

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
        }

        $xml .='</dados>';

        return $xml;
    }

    public static function autoCompletarUsuarioParticipante($strPalavrasPesquisa, $idFila, $idStatus){
   
        $objUsuarioRN = new UsuarioRN();
        $idStatus = trim($idStatus);
        $idStatus = trim($idFila);
        
        $strPapelUsuario = MdUtlAdmFilaINT::getPapeisDeUsuario($idStatus);
    
        if (!is_null($strPapelUsuario)) {
    
            $objMdUtlAdmFilaPrmUsuRN = new MdUtlAdmFilaPrmGrUsuRN();
            $arrDTO = $objMdUtlAdmFilaPrmUsuRN->getUsuarioPorPapel(array($strPapelUsuario, $idFila));
            $idsUsuario = InfraArray::converterArrInfraDTO($arrDTO, 'IdUsuario');
            
            $objUsuarioDTO = new UsuarioDTO();
            $objUsuarioDTO->retTodos();
            $objUsuarioDTO->setNumIdUsuario($idsUsuario, InfraDTO::$OPER_IN);
            $objUsuarioDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);

            $arrObjUsuarioDTO = $objUsuarioRN->pesquisar($objUsuarioDTO);
     

        foreach ($arrObjUsuarioDTO as $objUsuarioDTO) {
            $objUsuarioDTO->setStrSigla($objUsuarioDTO->getStrNome() . ' (' . $objUsuarioDTO->getStrSigla() . ')');
        }
        return $arrObjUsuarioDTO;
    }

        return null;
    }

    public static function consultarVinculoParametrizacaoUsuario($idVinculo, $idFila){


        $objMdUtlAdmPrmGrUsuDTO = new MdUtlAdmPrmGrUsuDTO();
        $objMdUtlAdmPrmGrUsuRN = new MdUtlAdmPrmGrUsuRN();

        $objMdUtlAdmPrmGrUsuDTO->setNumIdMdUtlAdmPrmGrUsu($idVinculo);
        $objMdUtlAdmPrmGrUsuDTO->retNumIdUsuario();
        $objMdUtlAdmPrmGrUsuDTO->setNumMaxRegistrosRetorno(1);
        $objMdUtlAdmPrmGrUsuDTO = $objMdUtlAdmPrmGrUsuRN->consultar($objMdUtlAdmPrmGrUsuDTO);
        $idUsuario = $objMdUtlAdmPrmGrUsuDTO->getNumIdUsuario();
  
        $mdUtlAdmDsmpDTO = new MdUtlControleDsmpDTO();
        $mdUtlAdmDsmpRN = new MdUtlControleDsmpRN();
        $mdUtlAdmDsmpDTO->setNumIdUsuarioDistribuicao($idUsuario);
        $mdUtlAdmDsmpDTO->setNumIdMdUtlAdmFila($idFila);
        $numRegistro = $mdUtlAdmDsmpRN->contar($mdUtlAdmDsmpDTO);

        $xml = '<dados>';
        if($numRegistro > 0 && !is_null($idUsuario)) {
            $xml .= '<sucesso>0</sucesso>';
            $xml .= '<msg>';
            $xml .= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_83);
            $xml .= '</msg>';
        }else{
            $xml .= '<sucesso>1</sucesso>';
            $xml .= '<msg>Nenhum registro encontrado.</msg>';
        }

        $xml .='</dados>';

        return $xml;
    }

    public static function buscarDadosCargaUsuario($idUsuarioParticipante, $idParam, $numCargaPadrao, $numPercentualTele, $staFrequencia, $idTipoControle){

        $objMdUtlAdmPrmGrUsuRN      = new MdUtlAdmPrmGrUsuRN();
        $objMdUtlControleDsmpRN     = new MdUtlControleDsmpRN();
        $objMdUtlHistControleDsmpRN = new MdUtlHistControleDsmpRN();
        $objMdUtlPrazoRN            = new MdUtlPrazoRN();

        $arrDatasFiltro = $objMdUtlPrazoRN->getDatasPorFrequencia($staFrequencia);

        $unidEsforco      = $objMdUtlControleDsmpRN->buscarUnidadeEsforco(array($idUsuarioParticipante, $idTipoControle, $arrDatasFiltro));
        $unidEsforcoHist  = $objMdUtlHistControleDsmpRN->buscarUnidadeEsforcoHist(array($idUsuarioParticipante, $idTipoControle, $arrDatasFiltro));
        $totalUnidEsforco = $unidEsforco + $unidEsforcoHist;

        $diasUteis  = $objMdUtlAdmPrmGrUsuRN->getDiasUteisNoPeriodo($staFrequencia);
        $totalCarga = $objMdUtlAdmPrmGrUsuRN->verificaCargaPadrao(array($idUsuarioParticipante, $idParam, $numCargaPadrao, $numPercentualTele, $diasUteis));

        $xml = '<Documento>';
        $xml .= '<ValorCarga>' . $totalCarga . '</ValorCarga>';
        $xml .= '<ValorUndEs>' . $totalUnidEsforco . '</ValorUndEs>';
        $xml .= '</Documento>';

        return $xml;
    }

}
