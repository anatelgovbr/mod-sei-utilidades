<?

try {
    require_once dirname(__FILE__) . '/../../SEI.php';
    session_start();

    SessaoSEI::getInstance()->validarLink();

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    PaginaSEI::getInstance()->salvarCamposPost(array('txtDescricao', 'txtTpJustificativa'));

    $idTpCtrl = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTpCtrlUtl'];
    $objTpControleUtlRN = new MdUtlAdmTpCtrlDesempRN();
    $objTipoControleUtlDTO = $objTpControleUtlRN->buscarObjTpControlePorId($idTpCtrl);
    $nomeTpCtrl = !is_null($objTipoControleUtlDTO) ? $objTipoControleUtlDTO->getStrNome() : '';

    switch ($_GET['acao']) {
        case 'md_utl_adm_just_contest_excluir':
            try{
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $objMdUtlAdmJustContestRN = new MdUtlAdmJustContestRN();
                $arrObjMdUtlAdmJustContestDTO = array();
                for ($i=0;$i<count($arrStrIds);$i++){
                    $objMdUtlAdmJustContestDTO = new MdUtlAdmJustContestDTO();
                    $objMdUtlAdmJustContestDTO->setNumIdMdUtlAdmJustContest($arrStrIds[$i]);
                    $objMdUtlAdmJustContestRN->validarExclusaoJustContest($arrStrIds[$i]);
                    $arrObjMdUtlAdmJustContestDTO[] = $objMdUtlAdmJustContestDTO;
                }
                $objMdUtlAdmJustContestRN = new MdUtlAdmJustContestRN();
                $objMdUtlAdmJustContestRN->excluir($arrObjMdUtlAdmJustContestDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl));
            die;


        case 'md_utl_adm_just_contest_desativar':
            try{
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjMdUtlAdmJustContestDTO = array();
                for ($i=0;$i<count($arrStrIds);$i++){
                    $objMdUtlAdmJustContestDTO = new MdUtlAdmJustContestDTO();
                    $objMdUtlAdmJustContestDTO->setNumIdMdUtlAdmJustContest($arrStrIds[$i]);
                    $arrObjMdUtlAdmJustContestDTO[] = $objMdUtlAdmJustContestDTO;
                }
                $objMdUtlAdmJustContestRN = new MdUtlAdmJustContestRN();
                $objMdUtlAdmJustContestRN->desativar($arrObjMdUtlAdmJustContestDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl));
            die;

        case 'md_utl_adm_just_contest_reativar':
            $strTitulo = 'Reativar Justificativa de Contestação';
            if ($_GET['acao_confirmada']=='sim'){
                try{
                    $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                    $arrObjMdUtlAdmJustContestDTO = array();
                    for ($i=0;$i<count($arrStrIds);$i++){
                        $objMdUtlAdmJustContestDTO = new MdUtlAdmJustContestDTO();
                        $idJustContest = $arrStrIds[$i];
                        $objMdUtlAdmJustContestDTO->setNumIdMdUtlAdmJustContest($arrStrIds[$i]);
                        $arrObjMdUtlAdmJustContestDTO[] = $objMdUtlAdmJustContestDTO;
                    }
                    $objMdUtlAdmJustContestRN = new MdUtlAdmJustContestRN();
                    $objMdUtlAdmJustContestRN->reativar($arrObjMdUtlAdmJustContestDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
                }catch(Exception $e){
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.PaginaSEI::getInstance()->montarAncora($idJustContest)));
                die;
            }
            break;

        case 'md_utl_adm_just_contest_listar':
            $strTitulo = 'Justificativa de Contestação';
            $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_contest_reativar');
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }
    $arrComandos = array();
    if ($_GET['acao'] === 'md_utl_adm_just_contest_listar') {
        $arrComandos[] = '<button type="submit" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
        $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_just_contest_cadastrar&id_tipo_controle_utl=' . $idTpCtrl . '&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
        $arrComandos[] = '<button type="button" accesskey="A" id="btnPrmContest" value="PrmContest" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_prm_contest_cadastrar&id_tipo_controle_utl=' . $idTpCtrl . '&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton">P<span class="infraTeclaAtalho">a</span>râmetro de Contestação</button>';
        $arrComandos[] = '<button type="button" accesskey="C" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($idTpCtrl)) . '\'" class="infraButton">Fe<span class="infraTeclaAtalho">c</span>har</button>';
    }

    $objMdUtlAdmJustContestDTO = new MdUtlAdmJustContestDTO();

    if ($_GET['acao'] == 'md_utl_adm_just_contest_listar') {
        $descricao = $_POST['txtDescricao'];
        $justificativa = $_POST['txtJustificativa'];

        $objMdUtlAdmJustContestDTO->setStrNome('%' . trim($justificativa . '%'), InfraDTO::$OPER_LIKE);
        $objMdUtlAdmJustContestDTO->setStrDescricao('%' . trim($descricao . '%'), InfraDTO::$OPER_LIKE);
    }

    $objMdUtlAdmJustContestDTO->retNumIdMdUtlAdmJustContest();
    $objMdUtlAdmJustContestDTO->retStrNome();
    $objMdUtlAdmJustContestDTO->retStrDescricao();
    $objMdUtlAdmJustContestDTO->retStrSinAtivo();
    $objMdUtlAdmJustContestDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
    if ($bolAcaoReativar) {
        $objMdUtlAdmJustContestDTO->setBolExclusaoLogica(false);
    }

    PaginaSEI::getInstance()->prepararOrdenacao($objMdUtlAdmJustContestDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
    PaginaSEI::getInstance()->prepararPaginacao($objMdUtlAdmJustContestDTO, 200);

    $objMdUtlAdmJustContestRN = new MdUtlAdmJustContestRN();

    $arrObjMdUtlAdmJustContestDTO = $objMdUtlAdmJustContestRN->listar($objMdUtlAdmJustContestDTO);

    PaginaSEI::getInstance()->processarPaginacao($objMdUtlAdmJustContestDTO);
    $numRegistros = count($arrObjMdUtlAdmJustContestDTO);

    if ($numRegistros > 0) {
        $bolCheck = false;

        if ($_GET['acao'] == 'md_utl_adm_just_contest_reativar') {
            $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_contest_reativar');
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_contest_consultar');
            $bolAcaoAlterar = false;
            $bolAcaoImprimir = true;
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_contest_excluir');
            $bolAcaoDesativar = false;
        } else {
            $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_contest_reativar');
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_contest_consultar');
            $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_contest_alterar');
            $bolAcaoImprimir = true;
            $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_contest_excluir');
            $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('md_utl_adm_just_contest_desativar');
        }

        if ($bolAcaoDesativar) {
            $bolCheck = true;
            $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_just_contest_desativar&id_tipo_controle_utl=' . $idTpCtrl . '&acao_origem=' . $_GET['acao']);
        }

        if ($bolAcaoReativar) {
            $bolCheck = true;
            $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_just_contest_reativar&id_tipo_controle_utl=' . $idTpCtrl . '&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
        }


        if ($bolAcaoExcluir) {
            $bolCheck = true;
            $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_just_contest_excluir&id_tipo_controle_utl=' . $idTpCtrl . '&acao_origem=' . $_GET['acao']);
        }

        $strResultado = '';
        $strSumarioTabela = 'Tabela de Justificativa de Contestação.';
        $strCaptionTabela = 'Justificativa de Contestação';

        $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
        $strResultado .= '<tr>';

        if ($bolCheck) {
            $strResultado .= '<th class="infraTh" width="1%" style="display: none">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
        }

        $strResultado .= '<th class="infraTh" width="35%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmJustContestDTO, 'Justificativa de Contestação', 'Nome', $arrObjMdUtlAdmJustContestDTO) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="50%">' . PaginaSEI::getInstance()->getThOrdenacao($objMdUtlAdmJustContestDTO, 'Descrição', 'Descricao', $arrObjMdUtlAdmJustContestDTO) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="15%">Ações</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        for ($i = 0; $i < $numRegistros; $i++) {

            $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
            if ($arrObjMdUtlAdmJustContestDTO[$i]->getStrSinAtivo() == 'N') {
                $strCssTr = '<tr class="trVermelha">';
            }
            $strResultado .= $strCssTr;

            if ($bolCheck) {
                $strResultado .= '<td valign="top" style="display: none">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjMdUtlAdmJustContestDTO[$i]->getNumIdMdUtlAdmJustContest(), $arrObjMdUtlAdmJustContestDTO[$i]->getNumIdMdUtlAdmJustContest()) . '</td>';
            }
            $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjMdUtlAdmJustContestDTO[$i]->getStrNome()) . '</td>';
            $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjMdUtlAdmJustContestDTO[$i]->getStrDescricao()) . '</td>';
            $strResultado .= '<td align="center">';
            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjMdUtlAdmJustContestDTO[$i]->getNumIdMdUtlAdmJustContest());

            if ($bolAcaoConsultar) {
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_just_contest_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTpCtrl . '&id_md_utl_adm_just_contest=' . $arrObjMdUtlAdmJustContestDTO[$i]->getNumIdMdUtlAdmJustContest()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/consultar.gif" title="Consultar Justificativa de Contestação" alt="Consultar Justificativa de Contestação" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoAlterar) {
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_utl_adm_just_contest_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_controle_utl=' . $idTpCtrl . '&id_md_utl_adm_just_contest=' . $arrObjMdUtlAdmJustContestDTO[$i]->getNumIdMdUtlAdmJustContest()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/alterar.gif" title="Alterar Justificativa de Contestação" alt="Alterar Justificativa de Contestação" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
                $strId = $arrObjMdUtlAdmJustContestDTO[$i]->getNumIdMdUtlAdmJustContest();
                $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMdUtlAdmJustContestDTO[$i]->getStrNome());
            }
            if ($bolAcaoDesativar && $arrObjMdUtlAdmJustContestDTO[$i]->getStrSinAtivo() == 'S') {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/desativar.gif" title="Desativar Justificativa de Contestação" alt="Desativar Justificativa de Contestação" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoReativar && $arrObjMdUtlAdmJustContestDTO[$i]->getStrSinAtivo() == 'N') {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/reativar.gif" title="Reativar Justificativa de Contestação" alt="Justificativa de Contestação" class="infraImg" /></a>&nbsp;';
            }


            if ($bolAcaoExcluir) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getDiretorioImagensGlobal() . '/excluir.gif" title="Excluir Justificativa de Contestação" alt="Excluir Justificativa de Contestação" class="infraImg" /></a>&nbsp;';
            }

            $strResultado .= '</td></tr>' . "\n";
        }
        $strResultado .= '</table>';
    }
} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}


PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>

<?if(0){?><style><?} ?>

.bloco {
    position: relative;
    float: left;
}

.clear {
    clear: both;
}
<?if(0){?></style><?}?>

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once 'md_utl_geral_js.php';
?>
<?if(0){?><script type="text/javascript"><?}?>
    var msg70 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_70); ?>';
    var msg72 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_72); ?>';
    var msg74 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_74); ?>';

    function inicializar(){
            document.getElementById('btnFechar').focus();
            infraEfeitoTabelas(true);
    }

    <? if ($bolAcaoDesativar){ ?>
    function acaoDesativar(id,desc){
        var msg = setMensagemPersonalizada(msg70, ['Justificativa de Contestação', desc]);
        if (confirm(msg)){
            document.getElementById('hdnInfraItemId').value=id;
            document.getElementById('frmMdUtlAdmJustContestLista').action='<?=$strLinkDesativar?>';
            document.getElementById('frmMdUtlAdmJustContestLista').submit();
        }
    }

    <? } ?>

    <? if ($bolAcaoReativar){ ?>
    function acaoReativar(id,desc){
        var msg = setMensagemPersonalizada(msg72, ['Justificativa de Contestação', desc]);
        if (confirm(msg)){
            document.getElementById('hdnInfraItemId').value=id;
            document.getElementById('frmMdUtlAdmJustContestLista').action='<?=$strLinkReativar?>';
            document.getElementById('frmMdUtlAdmJustContestLista').submit();
        }
    }
    <? } ?>

    <? if ($bolAcaoExcluir){ ?>
    function acaoExcluir(id,desc){
        var msg = setMensagemPersonalizada(msg74, ['Justificativa de Contestação', desc]);
        if (confirm(msg)){
            document.getElementById('hdnInfraItemId').value=id;
            document.getElementById('frmMdUtlAdmJustContestLista').action='<?=$strLinkExcluir?>';
            document.getElementById('frmMdUtlAdmJustContestLista').submit();
        }
    }

    <? } ?>

    <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmMdUtlAdmJustContestLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        ?>

        <div id="divInfraAreaDados" class="infraAreaDados">

            <div style="width: 27%;" class="bloco">
                <label id="lblJustificativa" for="txtJustificativa" accesskey="S" class="infraLabelOpcional">
                    Justificativa:
                </label>

                <div class="clear"></div>

                <input type="text" id="txtJustificativa" name="txtJustificativa" class="infraText" size="30"
                       value="<?=$justificativa?>" maxlength="100"
                       tabindex="502"/>
            </div>
            <div style="width: 68%;" class="bloco">
                <label id="lblDescricao" for="txtDescricao" accesskey="S"
                       class="infraLabelOpcional">
                    Descrição:
                </label>

                <div class="clear"></div>

                <input style="width: 45%" type="text" id="txtDescricao" name="txtDescricao" class="infraText"
                       size="30"
                       value="<?=$descricao?>" maxlength="100"
                       tabindex="502"/>
            </div>
        </div>

        <input type="hidden" name="hdnIdTpCtrlUtl" id="hdnIdTpCtrlUtl" value="<?php echo $idTpCtrl ?>"/>
        <?
        PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
PaginaSEI::getInstance()->fecharHtml();
PaginaSEI::getInstance()->fecharBody();
