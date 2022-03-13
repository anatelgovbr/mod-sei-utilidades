<?php

try {
    require_once dirname(__FILE__) . '/../../SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    InfraDebug::getInstance()->setBolLigado(false);
    InfraDebug::getInstance()->setBolDebugInfra(true);
    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
    
    $arrTpCtrlUsuMembro     = explode(',' , $_GET['ids_tp_ctrl_dist']);
    $objMdUtlAdmTpCtrlUndRN = new MdUtlAdmRelTpCtrlDesempUndRN();
    $arrObjTpControle       = $objMdUtlAdmTpCtrlUndRN->getArrayTipoControleUnidadeLogada();  
    $arrListaTpControle     = array();
    $arrListaIdsTpControle  = array();
    if (count($arrObjTpControle) > 0 ){
        foreach ($arrObjTpControle as $k => $v) {
            if( in_array( $v->getNumIdMdUtlAdmTpCtrlDesemp() , $arrTpCtrlUsuMembro ) ) {
                $arrListaTpControle[$v->getNumIdMdUtlAdmTpCtrlDesemp()] = $v->getStrNomeTipoControle();
                array_push( $arrListaIdsTpControle , $v->getNumIdMdUtlAdmTpCtrlDesemp() );
            }else{
                unset($arrObjTpControle[$k]);
            }
        }
    }
    
    $selTpControle = is_null($arrObjTpControle) ? array() : MdUtlAdmFilaINT::montarSelectTpControle($arrObjTpControle,'NumIdMdUtlAdmTpCtrlDesemp', 'StrNomeTipoControle', null);

    $strTitulo = 'Selecionar o Tipo de Controle';

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
    if (0){ ?>
        <script type="text/javascript"><?php } ?>

        function atribuirProcessoPai(idTpCtrl){
            window.opener.atribuirProximo(idTpCtrl);
            window.close();            
        }
    <?php if (0){ ?> </script> <?php } ?>
<?php
    PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>   
    <div id="divTpCtrl" style="margin-top:68px;">
        <label id="lblTpControleModal" for="selTpControleModal" accesskey="" class="infraLabelOpcional">Tipo de Controle:</label>
        <select id="selTpControleModal" name="selTpControleModal" class="infraSelect padraoSelect" style="width: 282px;"
                onchange="atribuirProcessoPai(this.value)" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
            <?= $selTpControle ?>
        </select>
    </div>
<?php
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>