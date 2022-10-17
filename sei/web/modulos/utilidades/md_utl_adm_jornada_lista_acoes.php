<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 04/09/2018
 * Time: 14:57
 */

//Id tipo de controle
//$idTipoControle = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTipoControleUtl'];

//URL Base
$strUrl               = 'controlador.php?acao=md_utl_adm_jornada_';

//URL das Actions
$objTpCtrlUtlUndRN    = new MdUtlAdmRelTpCtrlDesempUndRN();
$objTpCtrlUtlRN       = new MdUtlAdmRelTpCtrlDesempUsuRN();
$objPermissaoRN       = new MdUtlAdmPermissaoRN();
$isGestor             = $objTpCtrlUtlRN->usuarioLogadoIsGestorTpControle();
$isAdmUsuario         = $objPermissaoRN->isAdm();
$isGestorSipUsuario   = $objPermissaoRN->isGestor();
$idTipoControle       = $objTpCtrlUtlUndRN->getTipoControleUnidadeLogada();
$objMdUtlAdmJornadaRN = new MdUtlAdmJornadaRN();
$strUrlDesativar      = SessaoSEI::getInstance()->assinarLink($strUrl . 'desativar&acao_origem=' . $_GET['acao']);
$strUrlReativar       = SessaoSEI::getInstance()->assinarLink($strUrl . 'reativar&acao_origem=' . $_GET['acao']);
$strUrlExcluir        = SessaoSEI::getInstance()->assinarLink($strUrl . 'excluir&acao_origem=' . $_GET['acao']);
$strUrlPesquisar      = SessaoSEI::getInstance()->assinarLink($strUrl . 'listar&acao_origem=' . $_GET['acao']);
$strUrlFechar         = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);

$strUrlNovo           = !is_array($isGestor) ? '' : SessaoSEI::getInstance()->assinarLink($strUrl . 'cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']);

$strTitulo            = 'Ajuste de Jornada';

switch ($_GET['acao']) {

    //region Desativar
    case 'md_utl_adm_jornada_desativar':
        try {

            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            for ($i = 0; $i < count($arrStrIds); $i++) {

                $objMdUtlAdmJornadaDTO = new MdUtlAdmJornadaDTO();
                $objMdUtlAdmJornadaDTO->setNumIdMdUtlAdmJornada($arrStrIds[$i]);
                $objMdUtlAdmJornadaDTO->setStrSinAtivo('N');
                $arrObjMdUtlAdmFila[] = $objMdUtlAdmJornadaDTO;

            }
            $objMdUtlAdmJornadaRN = new MdUtlAdmJornadaRN();
            $objMdUtlAdmJornadaRN->desativar($arrObjMdUtlAdmFila);

        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
        die;
        break;
    //endregion

    //region Reativar
    case 'md_utl_adm_jornada_reativar':

        try {
            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $id        = reset($arrStrIds);

            for ($i = 0; $i < count($arrStrIds); $i++) {
                $objMdUtlAdmJornadaDTO = new MdUtlAdmJornadaDTO();
                $objMdUtlAdmJornadaDTO->setNumIdMdUtlAdmJornada($arrStrIds[$i]);
                $objMdUtlAdmJornadaDTO->setStrSinAtivo('S');
                $arrObjMdUtlAdmJornada[] = $objMdUtlAdmJornadaDTO;
            }

            $objMdUtlAdmJornadaRN = new MdUtlAdmJornadaRN();
            $objMdUtlAdmJornadaRN->reativar($arrObjMdUtlAdmJornada);

        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'].'&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($id)));
        die;

        break;

    //endregion

    //region Excluir
    case 'md_utl_adm_jornada_excluir':
        try {

            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $objMdUtlAdmJornadaRN = new MdUtlAdmJornadaRN();

            for ($i = 0; $i < count($arrStrIds); $i++) {
                $objMdUtlAdmJornadaDTO = new MdUtlAdmJornadaDTO();
                $objMdUtlAdmJornadaDTO->setNumIdMdUtlAdmJornada($arrStrIds[$i]);
                $arrObjMdUtlAdmJornada[] = $objMdUtlAdmJornadaDTO;
                $objMdUtlAdmJornadaRN->excluirRelacionamentos($arrStrIds[$i]);
            }

            $objMdUtlAdmJornadaRN->excluir($arrObjMdUtlAdmJornada);

        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
        die;
        break;
    //endregion

    //region Selecionar
    case 'md_utl_adm_jornada_selecionar':
        $strTitulo       = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Tipo de Controle de Desempenho', 'Selecionar Tipo de Controle de Desempenho');
        $strUrlPesquisar = SessaoSEI::getInstance()->assinarLink($strUrl . 'selecionar&acao_origem=' . $_GET['acao']);

        break;
    //endregion

    //region Listar
    case 'md_utl_adm_jornada_listar':


        break;
    //endregion

    //region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    //endregion
}

//Verifica se é ação Selecionar
$bolSelecionar = $_GET['acao'] == 'md_utl_adm_jornada_selecionar';

//Botões de ação do topo
$arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" onclick="pesquisar()" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

if (!$bolSelecionar) {
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" onclick="novo()" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    // $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" onclick="imprimir()" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
} else {
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
    $arrComandos[] = '<button type="button" accesskey="c" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
}

// Consulta
$objMdUtlAdmJornadaDTO = new MdUtlAdmJornadaDTO();
$objMdUtlAdmJornadaDTO->retTodos();

if (isset($_POST ['txtNomeTpControle']) && trim($_POST ['txtNomeTpControle']) != '') {
    $strNome = $_POST ['txtNomeTpControle'];
    $objMdUtlAdmJornadaDTO->setStrNome('%' . $_POST ['txtNomeTpControle'] . '%', InfraDTO::$OPER_LIKE);
}

if (isset($_POST ['txtDescricaoTpControle']) && trim($_POST ['txtDescricaoTpControle']) != '') {
    $strDescricao = $_POST ['txtDescricaoTpControle'];
    $objMdUtlAdmJornadaDTO->setStrDescricao('%' . $_POST ['txtDescricaoTpControle'] . '%', InfraDTO::$OPER_LIKE);
}

if (isset($_POST ['txtDtInicio']) && trim($_POST ['txtDtInicio']) != '') {
    $strDtInicio = $_POST ['txtDtInicio'];
}

if (isset($_POST ['txtDtFim']) && trim($_POST ['txtDtFim']) != '') {
    $strDtFim = $_POST['txtDtFim'];
}

if (isset($_POST ['selTpAjuste']) && trim($_POST ['selTpAjuste']) != '') {
    $selTpAjuste = $_POST ['selTpAjuste'];
    $objMdUtlAdmJornadaDTO->setStrStaTipoAjuste('%' . $_POST ['selTpAjuste'] . '%', InfraDTO::$OPER_LIKE);
}

$selMembro            = $_POST ['selMembro'];
$isTpAjusteGeral      = array_key_exists('selTpAjuste', $_POST) && trim($_POST ['selTpAjuste']) != '' && $_POST ['selTpAjuste'] == MdUtlAdmJornadaRN::$TIPO_JORNADA_GERAL;
$isTpAjusteEspecifico = array_key_exists('selTpAjuste', $_POST) && trim($_POST ['selTpAjuste']) != '' && $_POST ['selTpAjuste'] == MdUtlAdmJornadaRN::$TIPO_JORNADA_ESPECIFICO;
$isTpAjusteNulo       = !array_key_exists('selTpAjuste', $_POST) || $_POST['selTpAjuste'] == '';
$isMembroPreenchido   = isset ($_POST ['selMembro']) && trim($_POST ['selMembro']) != '';
$isNull               = false;

if($isMembroPreenchido) {

    $idsJornadaUsuEspecifico = $objMdUtlAdmJornadaRN->getAjusteJornadaUsuario($_POST['selMembro']);

    //Se o Tipo de Ajuste for Selecionado e for Geral
    if ($isTpAjusteGeral) {
        $tpsCtrlUsuario = $objMdUtlAdmJornadaRN->getTiposControleParametrizadoUsuario($_POST['selMembro']);
        $objMdUtlAdmJornadaDTO->retNumIdMdUtlAdmJornada();
        $objMdUtlAdmJornadaDTO->setJornadaTIPOFK(InfraDTO::$TIPO_FK_OPCIONAL);
        $objMdUtlAdmJornadaDTO->adicionarCriterio(array('StaTipoAjuste', 'IdMdUtlAdmTpCtrlDesemp'),
            array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IN),
            array(MdUtlAdmJornadaRN::$TIPO_JORNADA_GERAL, $tpsCtrlUsuario),
            array(InfraDTO::$OPER_LOGICO_AND));
    }

    //Se o Tipo de Ajuste for Selecionado e for Especifico
    if ($isTpAjusteEspecifico) {
        if(!is_null($idsJornadaUsuEspecifico)) {
            $objMdUtlAdmJornadaDTO->setNumIdMdUtlAdmJornada($idsJornadaUsuEspecifico, InfraDTO::$OPER_IN);
        }else{
            $isNull = true;
        }
    }

    //Se o tipo ajuste NÃO for selecionado
    if ($isTpAjusteNulo) {
        $tpsCtrlUsuario = $objMdUtlAdmJornadaRN->getTiposControleParametrizadoUsuario($_POST['selMembro']);

        //Se o usuário possuir ajuste de Jornada Especificos
        if (!is_null($idsJornadaUsuEspecifico)) {

            $objMdUtlAdmJornadaDTO->retNumIdMdUtlAdmJornada();
            $objMdUtlAdmJornadaDTO->setJornadaTIPOFK(InfraDTO::$TIPO_FK_OPCIONAL);
            $objMdUtlAdmJornadaDTO->adicionarCriterio(array('StaTipoAjuste', 'IdMdUtlAdmTpCtrlDesemp', 'StaTipoAjuste', 'IdMdUtlAdmJornada'),
                array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IN, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IN),
                array(MdUtlAdmJornadaRN::$TIPO_JORNADA_GERAL, $tpsCtrlUsuario, MdUtlAdmJornadaRN::$TIPO_JORNADA_ESPECIFICO, $idsJornadaUsuEspecifico),
                array(InfraDTO::$OPER_LOGICO_AND, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_AND));

        } else {

            // Se o usuário possuir apenas Ajustes Gerais
            // $objMdUtlAdmJornadaDTO->setNumIdMdUtlAdmTpCtrlDesemp($tpsCtrlUsuario, InfraDTO::$OPER_IN);

            $objMdUtlAdmJornadaDTO->retNumIdMdUtlAdmJornada();
            $objMdUtlAdmJornadaDTO->setJornadaTIPOFK(InfraDTO::$TIPO_FK_OPCIONAL);
            $objMdUtlAdmJornadaDTO->adicionarCriterio(array('StaTipoAjuste', 'IdMdUtlAdmTpCtrlDesemp'),
                array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IN),
                array(MdUtlAdmJornadaRN::$TIPO_JORNADA_GERAL, $tpsCtrlUsuario),
                array(InfraDTO::$OPER_LOGICO_AND));

        }
    }

}

$objMdUtlAdmJornadaRN = new MdUtlAdmJornadaRN();
if($strDtFim != '' && $strDtInicio != ''){
    // $objMdUtlAdmJornadaRN->verificarPeriodo(array($strDtInicio,$strDtFim));

    $objMdUtlAdmJornadaDTO->adicionarCriterio(array('Fim', 'Inicio'),
        array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
        array($strDtInicio, $strDtFim),
        array(InfraDTO::$OPER_LOGICO_AND));
}


if($strDtFim != '' && $strDtInicio == ''){
    // $objMdUtlAdmJornadaDTO->setDthFim($strDtFim, InfraDTO::$OPER_MENOR_IGUAL);

    $objMdUtlAdmJornadaDTO->adicionarCriterio(array('Inicio'),
        array(InfraDTO::$OPER_MENOR_IGUAL),
        array( $strDtFim));
}

if($strDtFim == '' && $strDtInicio != ''){

    // $objMdUtlAdmJornadaDTO->setDthInicio($strDtInicio, InfraDTO::$OPER_MAIOR_IGUAL);

    $objMdUtlAdmJornadaDTO->adicionarCriterio(array('Fim'),
        array(InfraDTO::$OPER_MAIOR_IGUAL),
        array($strDtInicio));
}

$objMdUtlAdmJornadaRN = new MdUtlAdmJornadaRN();

// if( is_array($isGestor)) {
//     $objMdUtlAdmJornadaDTO->setNumIdMdUtlAdmTpCtrlDesemp($isGestor, InfraDTO::$OPER_IN);
// }

if( is_array($isGestor)) {
    $objMdUtlAdmJornadaDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTipoControle);
}

//Configuração da Paginação
PaginaSEI::getInstance()->prepararOrdenacao($objMdUtlAdmJornadaDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC, true);
PaginaSEI::getInstance()->prepararPaginacao($objMdUtlAdmJornadaDTO, 200);

$arrObjMdUtlAdmJornada = !is_null($idTipoControle) ? $objMdUtlAdmJornadaRN->listar(($objMdUtlAdmJornadaDTO)) : null;

// if(!is_array($isGestor)){
//   $arrObjMdUtlAdmJornada = null;
// }

PaginaSEI::getInstance()->processarPaginacao($objMdUtlAdmJornadaDTO);
$numRegistros = count($arrObjMdUtlAdmJornada);

//Tabela de resultado.
if (!is_null($arrObjMdUtlAdmJornada) && $numRegistros > 0) {
    
    $strResultado .= '<table width="99%" class="infraTable" summary="Ajuste de Jornada">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Ajuste de Jornada', $numRegistros);
    $strResultado .= '</caption>';
    
    // Cabeçalho da Tabela
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" align="center" style="display: none" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>';
    $strResultado .= '<th class="infraTh" width="20%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmJornadaDTO, 'Nome', 'Nome', $arrObjMdUtlAdmJornada) . '</th>';
    $strResultado .= '<th class="infraTh" width="30%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmJornadaDTO, 'Descrição', 'Descricao', $arrObjMdUtlAdmJornada) . '</th>';
    $strResultado .= '<th class="infraTh" align="center" width="10%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmJornadaDTO, 'Tipo', 'StaTipoAjuste', $arrObjMdUtlAdmJornada) . '</th>';
    $strResultado .= '<th class="infraTh" width="20%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmJornadaDTO, 'Período', 'Inicio', $arrObjMdUtlAdmJornada) . '</th>';
    $strResultado .= '<th class="infraTh" align="center" width="15%">Ações</th>';
    $strResultado .= '</tr>';

    // Linhas
    $strCssTr   = '<tr class="infraTrEscura">';
    $arrTipo    = [
        MdUtlAdmJornadaRN::$TIPO_JORNADA_GERAL => MdUtlAdmJornadaRN::$STR_TIPO_JORNADA_GERAL, 
        MdUtlAdmJornadaRN::$TIPO_JORNADA_ESPECIFICO => MdUtlAdmJornadaRN::$STR_TIPO_JORNADA_ESPECIFICO
    ];

    $arrIds     = [];

    for ($i = 0; $i < $numRegistros; $i++) {

        // vars
        $strId                      = $arrObjMdUtlAdmJornada[$i]->getNumIdMdUtlAdmJornada();
        $arrIds[]                   = $strId;
        $strNomeTpControle          = $arrObjMdUtlAdmJornada[$i]->getStrNome();
        $strDescricaoTpControle     = $arrObjMdUtlAdmJornada[$i]->getStrDescricao();
        $strNomeTpControleParametro = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdUtlAdmJornada[$i]->getStrNome());
        $strDtaInicio               = explode(" ",$arrObjMdUtlAdmJornada[$i]->getDthInicio())[0];
        $strDtaFim                  = explode(" ",$arrObjMdUtlAdmJornada[$i]->getDthFim())[0];

        $strPeriodo                 = $strDtaInicio.' a '.$strDtaFim;
        $bolRegistroAtivo           = $arrObjMdUtlAdmJornada[$i]->getStrSinAtivo() == 'S';

        $strCssTr = !$bolRegistroAtivo ? '<tr class="trVermelha">' : ($strCssTr == '<tr class="infraTrClara">' ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">');
        $strResultado .= $strCssTr;

        //Linha Checkbox
        $strResultado .= '<td align="center" valign="top" style="display: none">';
        $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $strId, $strNomeTpControle);
        $strResultado .= '</td>';

        //Linha Nome
        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($strNomeTpControle);
        $strResultado .= '</td>';

        //Linha Descrição
        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($strDescricaoTpControle);
        $strResultado .= '</td>';

        //Linha Tipo
        $strResultado .= '<td>';
        $strResultado .= PaginaSEI::tratarHTML($arrTipo[$arrObjMdUtlAdmJornada[$i]->getStrStaTipoAjuste()]);
        $strResultado .='</td>';

        //Linha Tipo
        $strResultado .= '<td align="center">';
        $strResultado .= PaginaSEI::tratarHTML($strPeriodo);
        $strResultado .='</td>';

        $strResultado .= '<td align="center">';

        //Ação Consulta
        if (!$bolSelecionar) {

            //Ação Consultar
            $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink($strUrl . 'consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_jornada='.$strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/consultar.svg" title="Consultar Jornada" alt="Consultar Jornada" class="infraImg" /></a>&nbsp;';

            //Ação Alterar
            $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink($strUrl . 'alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_jornada='.$strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg" title="Alterar Jornada" alt="Alterar Jornada" class="infraImg" /></a>&nbsp;';

            //Ação Desativar
            if ($bolRegistroAtivo) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="desativar(\'' . $strId . '\',\'' . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeTpControle) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/desativar.svg" title="Desativar Jornada" alt="Desativar Jornada" class="infraImg" /></a>&nbsp;';
            }

            //Ação Reativar
            if (!$bolRegistroAtivo) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="reativar(\'' . $strId . '\',\'' . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeTpControle) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/reativar.svg" title="Reativar Jornada" alt="Reativar Jornada" class="infraImg" /></a>&nbsp;';
            }

            //Ação Excluir
            $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="excluir(\'' . $strId . '\',\'' . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeTpControle) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg" title="Excluir Jornada" alt="Excluir Jornada" class="infraImg" /></a>&nbsp;';

        } else {
            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $strId);
        }
        $strResultado .= '</td>';
        $strResultado .= '</tr>';

    }
    $strResultado .= '</table>';
}

$selMembros     = MdUtlAdmTpCtrlDesempINT::montarSelectMembros($isGestor,$selMembro);
$strTpAjuste    = MdUtlAdmJornadaINT::montarSelectTipoAjusteJornada($selTpAjuste);
