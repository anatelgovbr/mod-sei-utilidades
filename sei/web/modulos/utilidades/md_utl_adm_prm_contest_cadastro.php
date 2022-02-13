<?php
try{
    require_once dirname(__FILE__).'/../../SEI.php';
    session_start();

    SessaoSEI::getInstance()->validarLink();
    sessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objMdUtlAdmPrmContestDTO = new MdUtlAdmPrmContestDTO();

    $idTpCtrl = array_key_exists('id_tipo_controle_utl', $_GET) ? $_GET['id_tipo_controle_utl'] : $_POST['hdnIdTpCtrlUtl'];
    $idObjMdUtlAdmPrmContestDTO = '';
    $arrComandos = array();

    switch ($_GET['acao']){

        case 'md_utl_adm_prm_contest_cadastrar':
            $strTitulo =  'Parâmetro de Contestação';

            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMdUtlAdmPrmContest" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objMdUtlAdmPrmContestDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
            $objMdUtlAdmPrmContestDTO->retTodos();
            $objMdUtlAdmPrmContestDTO->setBolExclusaoLogica(false);

            $objMdUtlAdmPrmContestRN = new MdUtlAdmPrmContestRN();
            $objMdUtlAdmPrmContestDTO = $objMdUtlAdmPrmContestRN->consultar($objMdUtlAdmPrmContestDTO);

            $objMdUtlAdmPrmContestDTO != null ?
                $strItensSelSinReprovacao = MdUtlAdmPrmContestINT::montarSelectSinReprovacao($objMdUtlAdmPrmContestDTO->getStrSinReprovacaoAutomatica()) :
                $strItensSelSinReprovacao = MdUtlAdmPrmContestINT::montarSelectSinReprovacao();

            $objMdUtlAdmPrmContestDTO != null ?
                $qtdDiasUteisReprovacao = $objMdUtlAdmPrmContestDTO->getNumQtdDiasUteisReprovacao() :
                $qtdDiasUteisReprovacao = '';

            $idObjMdUtlAdmPrmContestDTO = $objMdUtlAdmPrmContestDTO != null ? $objMdUtlAdmPrmContestDTO->getNumIdMdUtlAdmPrmContest() : '';

            if (isset($_POST['sbmCadastrarMdUtlAdmPrmContest'])) {
                try{
                    $objMdUtlAdmPrmContestRN = new MdUtlAdmPrmContestRN();
                    $objMdUtlAdmPrmContestDTO = new MdUtlAdmPrmContestDTO();

                    $idObjMdUtlAdmPrmContestDTO != null ?  $objMdUtlAdmPrmContestDTO->setNumIdMdUtlAdmPrmContest($idObjMdUtlAdmPrmContestDTO) : null;
                    $objMdUtlAdmPrmContestDTO->setNumQtdDiasUteisReprovacao($_POST['txtQtdDiasReprovacao']);
                    $objMdUtlAdmPrmContestDTO->setNumIdMdUtlAdmTpCtrlDesemp($idTpCtrl);
                    $objMdUtlAdmPrmContestDTO->setStrSinReprovacaoAutomatica($_POST['selResultado']);

                    $idObjMdUtlAdmPrmContestDTO != null ?
                            $objMdUtlAdmPrmContestDTO = $objMdUtlAdmPrmContestRN->alterar($objMdUtlAdmPrmContestDTO) :
                            $objMdUtlAdmPrmContestDTO = $objMdUtlAdmPrmContestRN->cadastrar($objMdUtlAdmPrmContestDTO) ;

                    header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_controle_utl='.$idTpCtrl.'&id_md_utl_adm_prm_contest='.$idObjMdUtlAdmPrmContestDTO));
                    die;
                }catch(Exception $e){
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }

            break;

        default:
            throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
    }

}catch (Exception $e){
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
<?if(0){?><style><?}?>

    #lblQtdDiasReprovacao {position:absolute;left:1%;top:22%;width:40%;}
    #ancAjudaQtdDiasReprovacao{position: absolute;
        left: 260px;
        top: 21%;}
    #txtQtdDiasReprovacao {position:absolute;left:1%;top:27%;width:4.3%;}

    <?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
require_once('md_utl_geral_js.php');
?>
<?if(0){?><script type="text/javascript"><?}?>

    function inicializar(){
        document.getElementById('btnCancelar').focus();
        infraEfeitoTabelas(true);
        hiddenblocoQtdDiasReprovacao(document.getElementById('selResultado').value);
    }

    function hiddenblocoQtdDiasReprovacao(resp) {
       if(resp === 'S'){
            let div = document.getElementById('blocoQtdDiasReprovacao');
            div.style.display = 'block';
       }else{
           let div = document.getElementById('blocoQtdDiasReprovacao');
           let input = document.getElementById('txtQtdDiasReprovacao');

           input.value = '';
           div.style.display = 'none';
       }
    }

    function validarCadastro() {
        if(document.getElementById('selResultado').value == 0){
            alert('Informe a se haverá Reprovação Tática.');
            document.getElementById('selResultado').focus();
            return false;
        }
        if(document.getElementById('selResultado').value === 'S'){
            if (infraTrim(document.getElementById('txtQtdDiasReprovacao').value)=='' || document.getElementById('txtQtdDiasReprovacao').value == 0) {
                alert('Informe a quantidade de dias para Reprovação.');
                document.getElementById('txtQtdDiasReprovacao').focus();
                return false;
            }
        }

        return true;
    }

    function OnSubmitForm() {
        return validarCadastro();
    }
    <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');

?>
    <form id="frmMdUtlAdmPrmContest" method="post" onsubmit="return OnSubmitForm();" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados('36em; overflow:unset;');
        ?>
        <div id="blocoRsultTatico">
            <fieldset class="infraFieldset" style="padding-bottom: 6%; margin-top: 15px;width: 52%" >
                </br>
                <legend class="infraLegend" >Resultado Tácito de Contestação de Avaliação</legend>
                <div>
                    <label id="lblResultado" for="selResultado" accesskey="" class="infraLabelObrigatorio">Deseja ter Reprovação Tácita na Contestação de Avaliação:</label>
                    <a style="" id="btnResultado" <?= PaginaSEI::montarTitleTooltip('Informe se deseja que as Solicitações de Contestações de Avaliação sejam reprovadas automaticamente.') ?>
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <img id="imgAjudaResultado" border="0" style="width: 16px;height: 16px;"
                             src="<?= PaginaSEI::getInstance()->getDiretorioImagensGlobal() ?>/ajuda.gif" class="infraImg"/>
                    </a>
                    <select id="selResultado" name="selResultado" class="infraSelect" onchange=hiddenblocoQtdDiasReprovacao(this.value)
                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                        <?= $strItensSelSinReprovacao ?>
                    </select>
                </div>
                <div id=blocoQtdDiasReprovacao style="display:none;">
                    <label id="lblQtdDiasReprovacao" for="txtQtdDiasReprovacao" accesskey="" class="infraLabelObrigatorio">Quantidade de dias úteis para Reprovação:</label>
                    <a href="javascript:void(0);" id="ancAjudaQtdDiasReprovacao" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=PaginaSEI::montarTitleTooltip('Informe a quantidade de dias úteis para reprovação automática das Contestações.')?>><img class="tamanhoBtnAjuda" src="<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/ajuda.gif" class="infraImg"/></a>

                    <input type="text" id="txtQtdDiasReprovacao" name="txtQtdDiasReprovacao" maxlength="50" class="infraText" utlSomenteNumeroPaste="true" ondrop="return infraMascaraNumero(this,event, 3);" onkeypress="return infraMascaraNumero(this, event,3)"  value="<?=PaginaSEI::tratarHTML($qtdDiasUteisReprovacao) ?>" onkeypress="return infraMascaraTexto(this,event,3);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
                </div>

            </fieldset>
        </div>
        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        ?>
        <input type="hidden" id="hdnIdMdUtlAdmPrmContest" name="hdnIdMdUtlAdmPrmContest" value="<?php echo $idObjMdUtlAdmPrmContestDTO;?>" />
        <input type="hidden" name="hdnIdTpCtrlUtl" id="hdnIdTpCtrlUtl" value="<?php echo $idTpCtrl ?>"/>
    </form>


<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();