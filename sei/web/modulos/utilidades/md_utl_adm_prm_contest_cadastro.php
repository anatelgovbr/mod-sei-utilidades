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
require_once 'md_utl_adm_prm_contest_cadastro_css.php';
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');

?>
    <form id="frmMdUtlAdmPrmContest" method="post" onsubmit="return OnSubmitForm();" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaSEI::getInstance()->abrirAreaDados();
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <fieldset class="infraFieldset mb-4 p-4">
                    <legend class="infraLegend">Resultado Tácito de Contestação de Avaliação</legend>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label id="lblResultado" for="selResultado" accesskey="" class="infraLabelObrigatorio"> Deseja ter Reprovação Tácita:
                                    <img align="top" class="infraImg" name="ajuda"
                                        src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                        <?= PaginaSEI::montarTitleTooltip('Informe se deseja que as Solicitações de Contestações de Avaliação sejam reprovadas automaticamente.','Ajuda') ?>/>
                                </label>
                                <select id="selResultado" name="selResultado" class="infraSelect form-control" onchange=hiddenblocoQtdDiasReprovacao(this.value) tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                                    <?=$strItensSelSinReprovacao?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <div id=blocoQtdDiasReprovacao style="display:none;">
                                    <label id="lblQtdDiasReprovacao" for="txtQtdDiasReprovacao" accesskey="" class="infraLabelObrigatorio">
                                    Quantidade de dias úteis para Reprovação:
                                        <img align="top" class="infraImg" name="ajuda"
                                                src="<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() ?>/ajuda.svg"
                                                <?= PaginaSEI::montarTitleTooltip('Informe a quantidade de dias úteis para reprovação automática das Contestações.','Ajuda') ?>/>
                                    </label>
                                    <input type="text" id="txtQtdDiasReprovacao" name="txtQtdDiasReprovacao" onkeypress="return infraMascaraNumero(this, event,6)" ondrop="return infraMascaraNumero(this,event, 3);"
                                            class="infraText form-control" value="<?=PaginaSEI::tratarHTML($qtdDiasUteisReprovacao) ?>"
                                            tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        ?>
        <input type="hidden" id="hdnIdMdUtlAdmPrmContest" name="hdnIdMdUtlAdmPrmContest" value="<?php echo $idObjMdUtlAdmPrmContestDTO;?>" />
        <input type="hidden" name="hdnIdTpCtrlUtl" id="hdnIdTpCtrlUtl" value="<?php echo $idTpCtrl ?>"/>
    </form>


<?php
require_once('md_utl_geral_js.php');
require_once('md_utl_adm_prm_contest_cadastro_js.php');
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
