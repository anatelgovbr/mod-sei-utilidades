<?php

/**
 * @since  11/07/2018
 * @author Jaqueline Mendes <jaqueline.mendes@castgroup.com.br>
 */

require_once dirname(__FILE__) . '/../../SEI.php';

session_start();
SessaoSEI::getInstance()->validarLink();

$objPermissaoRN = new MdUtlAdmPermissaoRN();
$objMdUtlAdmTpCtrlDesempUsuRN = new MdUtlAdmRelTpCtrlDesempUsuRN();
//URL Base
$strUrl = 'controlador.php?acao=md_utl_adm_tp_ctrl_desemp_';


//URL das Actions
$isAdmUsuario       = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_ctrl_desemp_cadastrar');
$isGestorSipGeral   = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_tp_ctrl_desemp_listar');
$isGestorUnidadeAt  = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_atividade_cadastrar');
$isGestorSipUsuario = $isGestorSipGeral && $isGestorUnidadeAt;

$strUrlDesativar    = SessaoSEI::getInstance()->assinarLink($strUrl . 'desativar&acao_origem=' . $_GET['acao']);
$strUrlReativar     = SessaoSEI::getInstance()->assinarLink($strUrl . 'reativar&acao_origem=' . $_GET['acao']);
$strUrlExcluir      = SessaoSEI::getInstance()->assinarLink($strUrl . 'excluir&acao_origem=' . $_GET['acao']);
$strUrlPesquisar    = SessaoSEI::getInstance()->assinarLink($strUrl . 'listar&acao_origem=' . $_GET['acao']);
$strUrlNovo         = $isAdmUsuario ? SessaoSEI::getInstance()->assinarLink($strUrl . 'cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) : '';
$strUrlFechar       = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao']);
$tpsCtrlUsuario     = $objMdUtlAdmTpCtrlDesempUsuRN->usuarioLogadoIsGestorTpControle();

$strTitulo = 'Tipos de Controle de Desempenho';

switch ($_GET['acao']) {

    //region Desativar
    case 'md_utl_adm_tp_ctrl_desemp_desativar':
        try {

            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            for ($i = 0; $i < count($arrStrIds); $i++) {
                $objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();
                $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($arrStrIds[$i]);
                $arrObjMdUtlAdmTpCtrlDesemp[] = $objMdUtlAdmTpCtrlDesempDTO;
            }

            $objMdUtlAdmTpCtrlDesempRN = new MdUtlAdmTpCtrlDesempRN();
            $objMdUtlAdmTpCtrlDesempRN->desativar($arrObjMdUtlAdmTpCtrlDesemp);

        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
        die;
        break;
    //endregion

    //region Reativar
    case 'md_utl_adm_tp_ctrl_desemp_reativar':

        try {
            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
            $id        = reset($arrStrIds);
            for ($i = 0; $i < count($arrStrIds); $i++) {
                $objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();
                $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($arrStrIds[$i]);
                $arrObjMdUtlAdmTpCtrlDesemp[] = $objMdUtlAdmTpCtrlDesempDTO;
            }
            $objMdUtlAdmTpCtrlDesempRN = new MdUtlAdmTpCtrlDesempRN();
            $objMdUtlAdmTpCtrlDesempRN->reativar($arrObjMdUtlAdmTpCtrlDesemp);

            PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($id)));
        die;

        break;

    //endregion

    //region Excluir
    case 'md_utl_adm_tp_ctrl_desemp_excluir':
        try {

            $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();

            for ($i = 0; $i < count($arrStrIds); $i++) {
                $objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();
                $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($arrStrIds[$i]);
                $arrObjMdUtlAdmTpCtrlDesemp[] = $objMdUtlAdmTpCtrlDesempDTO;
            }
            $objMdUtlAdmTpCtrlDesempRN = new MdUtlAdmTpCtrlDesempRN();

            $isValid = $objMdUtlAdmTpCtrlDesempRN->verificarVinculos(array($arrStrIds[0],'excluir'));
            if($isValid) {
                $objMdUtlAdmTpCtrlDesempRN->excluirTipoControle($arrObjMdUtlAdmTpCtrlDesemp);
            }

        } catch (Exception $e) {
            PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
        die;
        break;
    //endregion

    //region Selecionar
    case 'md_utl_adm_tp_ctrl_desemp_selecionar':
        $strTitulo       = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Tipo de Controle de Desempenho', 'Selecionar Tipo de Controle de Desempenho');
        $strUrlPesquisar = SessaoSEI::getInstance()->assinarLink($strUrl . 'selecionar&acao_origem=' . $_GET['acao']);

        break;
    //endregion

    //region Listar
    case 'md_utl_adm_tp_ctrl_desemp_listar':


        break;
    //endregion

    //region Erro
    default:
        throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.A");
    //endregion
}

//Verifica se é ação Selecionar
$bolSelecionar = $_GET['acao'] == 'md_utl_adm_tp_ctrl_desemp_selecionar';


//Botões de ação do topo
$arrComandos[] = '<button type="button" accesskey="P" id="btnPesquisar" onclick="pesquisar()" class="infraButton">
                                    <span class="infraTeclaAtalho">P</span>esquisar
                              </button>';
if (!$bolSelecionar) {

    if($isAdmUsuario) {
        $arrComandos[] = '<button  type="button" accesskey="N" id="btnNovo" onclick="novo()" class="infraButton">
                                    <span class="infraTeclaAtalho">N</span>ovo
                              </button>';
    }
  //  $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" onclick="imprimir()" class="infraButton">
    //                                <span class="infraTeclaAtalho">I</span>mprimir
      //                        </button>';
    $arrComandos[] = '<button type="button" accesskey="c" id="btnFechar" onclick="fechar()" class="infraButton">
                                    Fe<span class="infraTeclaAtalho">c</span>har
                              </button>';
} else {
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton">
                                    <span class="infraTeclaAtalho">T</span>ransportar
                            </button>';

    $arrComandos[] = '<button type="button" accesskey="c" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton">
                                    <span class="infraTeclaAtalho">F</span>echar
                            </button>';
}


//Consulta
$objMdUtlAdmTpCtrlDesempDTO = new MdUtlAdmTpCtrlDesempDTO();
$objMdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmTpCtrlDesemp();
$objMdUtlAdmTpCtrlDesempDTO->retStrNome();
$objMdUtlAdmTpCtrlDesempDTO->retStrDescricao();
$objMdUtlAdmTpCtrlDesempDTO->retStrSinAtivo();
$objMdUtlAdmTpCtrlDesempDTO->retNumIdMdUtlAdmPrmGr();

if (isset ($_POST ['txtNomeTpControle']) && trim($_POST ['txtNomeTpControle']) != '') {
    $txtNome = $_POST ['txtNomeTpControle'];
    $objMdUtlAdmTpCtrlDesempDTO->setStrNome('%' . $_POST ['txtNomeTpControle'] . '%', InfraDTO::$OPER_LIKE);
}

if (isset ($_POST ['txtDescricaoTpControle']) && trim($_POST ['txtDescricaoTpControle']) != '') {
    $txtDescricao = $_POST ['txtDescricaoTpControle'];
    $objMdUtlAdmTpCtrlDesempDTO->setStrDescricao('%' . $_POST ['txtDescricaoTpControle'] . '%', InfraDTO::$OPER_LIKE);
}


if(!$isAdmUsuario && $tpsCtrlUsuario && count($tpsCtrlUsuario) > 0) {
    $objMdUtlAdmTpCtrlDesempDTO->setNumIdMdUtlAdmTpCtrlDesemp($tpsCtrlUsuario, InfraDTO::$OPER_IN);
}

$objMdUtlAdmTpCtrlDesempRN = new MdUtlAdmTpCtrlDesempRN();

//Configuração da Paginação
PaginaSEI::getInstance()->prepararOrdenacao($objMdUtlAdmTpCtrlDesempDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
PaginaSEI::getInstance()->prepararPaginacao($objMdUtlAdmTpCtrlDesempDTO, 200);

if($isAdmUsuario || ($tpsCtrlUsuario && count($tpsCtrlUsuario) > 0)) {
    $arrObjMdUtlAdmTpCtrlDesemp = $objMdUtlAdmTpCtrlDesempRN->listar($objMdUtlAdmTpCtrlDesempDTO);
}else{
    $arrObjMdUtlAdmTpCtrlDesemp = null;
}

PaginaSEI::getInstance()->processarPaginacao($objMdUtlAdmTpCtrlDesempDTO);
$numRegistros = count($arrObjMdUtlAdmTpCtrlDesemp);


//Tabela de resultado.
if ($numRegistros > 0) {
    
    $arrObjsTpProduto = $objMdUtlAdmTpCtrlDesempRN->buscarTpCtrlTpProdutoCadastrado($arrObjMdUtlAdmTpCtrlDesemp);

    $strResultado .= '<table width="99%" class="infraTable" summary="Tipo de Controle de Desempenho">';
    $strResultado .= '<caption class="infraCaption">';
    $strResultado .= PaginaSEI::getInstance()->gerarCaptionTabela('Tipos de Controle de Desempenho', $numRegistros);
    $strResultado .= '</caption>';
    //Cabeçalho da Tabela
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" align="center" width="1%" style="display: none">' . PaginaSEI::getInstance()->getThCheck() . '</th>';
    $strResultado .= '<th class="infraTh" width=140px">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmTpCtrlDesempDTO, 'Nome', 'Nome', $arrObjMdUtlAdmTpCtrlDesemp) . '</th>';
    $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmTpCtrlDesempDTO, 'Descrição', 'Descricao', $arrObjMdUtlAdmTpCtrlDesemp) . '</th>';
    $strResultado .= '<th class="infraTh" width="30%">Ações</th>';
    $strResultado .= '</tr>';

    //Linhas

    $strCssTr = '<tr class="infraTrEscura">';

    for ($i = 0; $i < $numRegistros; $i++) {
        //vars
        $strId                      = $arrObjMdUtlAdmTpCtrlDesemp[$i]->getNumIdMdUtlAdmTpCtrlDesemp();
        $strNomeTpControle          = $arrObjMdUtlAdmTpCtrlDesemp[$i]->getStrNome();
        $strDescricaoTpControle     = $arrObjMdUtlAdmTpCtrlDesemp[$i]->getStrDescricao();
        $strNomeTpControleParametro = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdUtlAdmTpCtrlDesemp[$i]->getStrNome());
        $bolRegistroAtivo           = $arrObjMdUtlAdmTpCtrlDesemp[$i]->getStrSinAtivo() == 'S';
        $exibirIconeAtividade       = array_key_exists($strId, $arrObjsTpProduto) ? $arrObjsTpProduto[$strId] : false;
        $exibirIconesGestor         = $isGestorSipUsuario && $tpsCtrlUsuario && count($tpsCtrlUsuario) > 0 && in_array($strId, $tpsCtrlUsuario);
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

        $strResultado .= '<td align="center">';


        //Ação Consulta
        if (!$bolSelecionar) {

                //link para tela de Parametrização de Distribuição nos tipos de controle
//                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_prm_ds_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/distribuir1.png" title="Distribuição" alt="Distribuição" class="infraImg" /></a>&nbsp;';

            //link para a tela de justificativa de contestacão
            $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_just_contest_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/justificativa.png" title="Justificativa de Contestação" alt="Justificativa de Contestação" class="infraImg" /></a>&nbsp;';

                //link para tela de Tipo de Justificativa prazo nos tipos de controle
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_just_prazo_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/motivo_dilatacao2.png" title="Justificativa de Ajuste de Prazo" alt="Justificativa de Ajuste de Prazo" class="infraImg" /></a>&nbsp;';

                //link para tela de Justificativa de Revisão nos tipos de controle
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_just_revisao_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="imagens/sei_valores.gif" title="Justificativa de Revisão" alt="Justificativa de Revisão" class="infraImg" /></a>&nbsp;';

                //link para tela de Tipo de Revisão nos tipos de controle
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_revisao_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/livro.png" title="Resultado da Revisão" alt="Resultado da Revisão" class="infraImg" /></a>&nbsp;';

                //link para a tela de Tipo de Produto nos tipos de controle
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_tp_produto_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/tipoprodutoatividade.png" title="Tipo de Produto" alt="Tipo de Produto" class="infraImg" /></a>&nbsp;';

                //link para a tela de Grupo de atividades tipos de controle
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_grp_fila_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/grupoatividades.png" title="Grupo de Atividades" alt="Grupo de Atividades" class="infraImg" /></a>&nbsp;';

                //link para a tela de atividades tipos de controle
                 $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_atividade_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/atividade.png" title="Atividades" alt="Atividades" class="infraImg" /></a>&nbsp;';

                //link para tela de filas
                if(!is_null($arrObjMdUtlAdmTpCtrlDesemp[$i]->getNumIdMdUtlAdmPrmGr())){
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_fila_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="modulos/utilidades/imagens/fila.png" title="Filas" alt="Filas" class="infraImg" /></a>&nbsp;';
                }


            //link para a tela de parametrizar tipos de controle
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_prm_gr_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensLocal() . '/sei_servicos.gif" title="Parametrizar Tipo de Controle" alt="Parametrizar Tipo de Controle" class="infraImg" /></a>&nbsp;';

                //Consultar Tipo de Controle
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink($strUrl . 'consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utilidades=' . $strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/consultar.gif" title="Consultar Tipo de Controle de Desempenho" alt="Consultar Tipo de Controle de Desempenho" class="infraImg" /></a>&nbsp;';

                //Ação Alterar
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink($strUrl . 'alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utilidades=' . $strId)) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/alterar.gif" title="Alterar Tipo de Controle de Desempenho" alt="Alterar Tipo de Controle de Desempenho" class="infraImg" /></a>&nbsp;';

                //Ícones são exibidos somente para o Administrador.
                if ($isAdmUsuario) {

                    //Ação Desativar
                    if ($bolRegistroAtivo) {
                        $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="desativar(\'' . $strId . '\',\'' . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeTpControle) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/desativar.gif" title="Desativar Tipo de Controle de Desempenho" alt="Desativar Tipo de Controle de Desempenho" class="infraImg" /></a>&nbsp;';
                    }

                    //Ação Reativar
                    if (!$bolRegistroAtivo) {
                        $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="reativar(\'' . $strId . '\',\'' . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeTpControle) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/reativar.gif" title="Reativar Tipo de Controle de Desempenho" alt="Reativar Tipo de Controle de Desempenho" class="infraImg" /></a>&nbsp;';
                    }

                    //Ação Excluir
                    $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="excluir(\'' . $strId . '\',\'' . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeTpControle) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/excluir.gif" title="Excluir Tipo de Controle de Desempenho" alt="Excluir Tipo de Controle de Desempenho" class="infraImg" /></a>&nbsp;';
            }

        } else {
            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $strId);
        }
        $strResultado .= '</td>';
        $strResultado .= '</tr>';

    }
    $strResultado .= '</table>';
}


PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(':: ' . PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo . ' ::');

//Include de estilos CSS
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
require_once 'md_utl_adm_tp_ctrl_desemp_lista_css.php';
PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
?>
    var msg70 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_70); ?>';
    var msg72 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_72); ?>';
    var msg74 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_74); ?>';

    function inicializar() {
    if ('<?= $_GET['acao'] ?>' == 'md_utl_adm_tp_ctrl_desemp_selecionar') {
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
    } else {
    infraEfeitoTabelas();
    }

    addEventoEnter();
    }

    function addEventoEnter() {
        var obj1 = document.getElementById('txtNomeTpControle');
        var obj2 = document.getElementById('txtDescricaoTpControle');

        obj1.addEventListener("keypress", function (evt) {
            addPesquisarEnter(evt);
        });

        obj2.addEventListener("keypress", function (evt) {
        addPesquisarEnter(evt);
        });
    }


    function addPesquisarEnter(evt) {
        var key_code = evt.keyCode ? evt.keyCode :
            evt.charCode ? evt.charCode :
                evt.which ? evt.which : void 0;

        if (key_code == 13) {
            pesquisar();
        }

    }

    function pesquisar(){
    document.getElementById('frmTpControleLista').action='<?= $strUrlPesquisar ?>';
    document.getElementById('frmTpControleLista').submit();
    }

    function desativar(id, desc) {
    var msg = setMensagemPersonalizada(msg70, ['Tipo de Controle de Desempenho', desc]);
    if (confirm(msg)) {
    document.getElementById('hdnInfraItemId').value = id;
    document.getElementById('frmTpControleLista').action = '<?= $strUrlDesativar ?>';
    document.getElementById('frmTpControleLista').submit();
    }
    }

    function reativar(id, desc){
    var msg = setMensagemPersonalizada(msg72, ['Tipo de Controle de Desempenho', desc]);
    if (confirm(msg)){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTpControleLista').action='<?= $strUrlReativar ?>';
    document.getElementById('frmTpControleLista').submit();
    }
    }

    function excluir(id, desc){
    var msg = setMensagemPersonalizada(msg74, ['Tipo de Controle de Desempenho', desc]);
    if (confirm(msg)){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTpControleLista').action='<?= $strUrlExcluir ?>';
    document.getElementById('frmTpControleLista').submit();
    }
    }

    function novo(){
    location.href="<?= $strUrlNovo ?>";
    }

    function imprimir(){
    infraImprimirTabela();
    }

    function fechar(){
    location.href="<?= $strUrlFechar ?>";
    }
<?php PaginaSEI::getInstance()->fecharJavaScript(); ?>


<?php
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmTpControleLista" method="post"
          action="<?= PaginaSEI::getInstance()->formatarXHTML(
              SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])
          ) ?>">

        <?php PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('4em');?>

            <div style="width: 45%;" id="divNome">
                    <label id="lblNomeTpControle" for="txtNomeTpControle" accesskey="S" class="infraLabelOpcional">
                        Nome:
                    </label>

                <div class="clear"></div>

                    <input type="text" id="txtNomeTpControle" name="txtNomeTpControle" style="width: 64%;" class="infraText" size="30"
                           value="<?=$txtNome?>" maxlength="100"
                           tabindex="502"/>
            </div>
            <div style="width: 45%;" id="divDescricao">
                    <label id="lblDescricaoTpControle" for="txtDescricaoTpControle" accesskey="S"
                           class="infraLabelOpcional">
                        Descrição:
                    </label>

                <div class="clear"></div>

                    <input style="width: 68%" type="text" id="txtDescricaoTpControle" name="txtDescricaoTpControle" class="infraText"
                           size="30"
                           value="<?=$txtDescricao?>" maxlength="100"
                           tabindex="502"/>
            </div>

        <?php
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>

    </form>

<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();

