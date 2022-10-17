<?php

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    SessaoSEI::getInstance()->validarLink();
    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

    $objMdUtlRelTriagemAtvRN = new MdUtlRelTriagemAtvRN();

    $idTriagem = array_key_exists('id_triagem', $_GET) ?  $_GET['id_triagem'] : null;
    $arrObjsTriagemAtividade = $objMdUtlRelTriagemAtvRN->getObjsTriagemAtividade(array($idTriagem));
    $numRegistro = count($arrObjsTriagemAtividade);
    $strCssTr = '<tr class="infraTrEscura">';


    if ($numRegistro > 0) {
        $arrayObjs = [];
        foreach ($arrObjsTriagemAtividade as $obj) {
            $strNomeAtividade = $obj->getStrNomeAtividade().' (' . MdUtlAdmAtividadeRN::$ARR_COMPLEXIDADE[$obj->getNumComplexidadeAtividade()] . ') - '. MdUtlAdmPrmGrINT::convertToHoursMins($obj->getNumTempoExecucaoAtribuido());
            array_push($arrayObjs, $strNomeAtividade);

        }

    }

    $arrComandos[] = '';
    //'<button type="button" accesskey="c" id="btnFechar" onclick="window.close()" class="infraButton">
    //                                  Fe<span class="infraTeclaAtalho">c</span>har</button>';

    switch($_GET['acao']){

        case 'md_utl_atividade_triagem_listar':
            $strTitulo = 'Lista de Atividades';
            break;

        default:
            throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
    }

}catch(Exception $e){
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
PaginaSEI::getInstance()->fecharJavaScript();


PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo);
?>
<?php
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
PaginaSEI::getInstance()->abrirAreaDados('15em');
?>
<div style="margin-top: 5%;">
    <table width="99%" class="infraTable" summary="AtividadesTri" id="tbAtividadesTri">
        <tr>
            <th class="infraTh">Atividades</th>
        </tr>
        <?php for ($i = 0; $numRegistro > $i; $i++){
            $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
            echo $strCssTr;?>
                <td class="tdAtividade"><?php echo $arrayObjs[$i]?></td>
            </tr>
        <?php }?>

    </table>
</div>

<?php
PaginaSEI::getInstance()->fecharAreaDados();
?>

<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();