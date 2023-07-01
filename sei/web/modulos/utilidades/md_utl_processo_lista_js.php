<script type="text/javascript">

var msg25 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_25)?>';

function inicializar() {

    var idParam = document.getElementById('hdnIdParametroCtrlUtl').value;
    var isProcessoConcl  = '<?php echo $isProcessoConcluido ?>';
    var msgConclusao = '<?php echo $msg107 ?>';

    if (idParam == 0) {
        alert(msg25);
    }

    /*
    if(isProcessoConcl == 1){
       if(confirm(msgConclusao)) {
           document.getElementById('hdnIsConcluirProcesso').value = 1;
           document.getElementById("frmUtlProcessoLista").submit();
       }else{
          window.location.href = '<?=$urlInicial?>';
       }
    }
    */

    if ('<?= $_GET['acao'] ?>' == 'md_utl_processo_listar') {
        infraReceberSelecao();
    } else {
        infraEfeitoTabelas();
    }

    <?php if (
                isset( $_GET['is_processo_concluido'] ) ||
                (
                  isset($_GET['acao_origem']) && in_array( $_GET['acao_origem'],['md_utl_distrib_usuario_cadastrar','md_utl_atribuicao_automatica'] ) &&
                  !isset($_GET['isFechar'])
                )
        ):
    ?>
        atualizaUtilidadesArvore();
    <?php endif; ?>
}

function atualizaUtilidadesArvore(){
    let link = "<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].'&montar_visualizacao=0&id_procedimento='.$idProcedimento) ?>";
    parent.document.querySelector('#ifrArvore').src = link;
}

function associarFila(){
      infraAbrirJanela('<?=$strLinkAssociarFila?>', 'janelaAssinatura', 1000, 450, 'location=0,status=1,resizable=1,scrollbars=1');
}

function iniciarTriagem(){
    window.location.href = '<?= $strLinkIniciarTriagem ?>';
}

function iniciarAnalise(){
    window.location.href = '<?= $strLinkIniciarAnalise ?>';
}

function iniciarRevisao(){
    window.location.href = '<?= $strLinkIniciarRevisao ?>';
}

function iniciarDistribuicao(){

    var staFrequencia = '<?=$staFrequencia?>';
    if(staFrequencia == 0){
        alert('A Frequência de Distribuição não está parametrizada no Tipo de Controle desta Unidade. Converse com o Gestor da sua área!');
        return false;
    }else{
        window.location.href = '<?= $strLinkIniciarDistrb ?>';
    }
}

function atribuicaoAutomatica() {
    if( $('#hdnIdStatusAtual').val() == <?= MdUtlControleDsmpRN::$AGUARDANDO_REVISAO ?> ||
        $('#hdnIdStatusAtual').val() == <?= MdUtlControleDsmpRN::$EM_REVISAO ?>){
        distribuirParaMimProcesso();
    } else {
        execAtribuicaoAutomatica();
    }
}

function execAtribuicaoAutomatica(){
    if( confirm("Confirma a Distribuição do Processo em sua carga?") ){
        document.getElementById('frmUtlProcessoLista').action = '<?= $strLinkAtribuir ?>';
        document.getElementById('frmUtlProcessoLista').submit();
    }
}

function exibirExcessaoDuplicidade(){
    var msg92 = '<?php echo $msg92 ?>'
    alert(msg92);
}

function fechar() {
    window.location.href = '<?= $strLinkFechar ?>';
}

function atualizarHistorico(){

    var paramsAjax = {
        idTipoControleSelecionado : document.getElementById('filtrarTipoControle').value,
        idProcedimento : '<?= $idProcedimento?>',
        strStatusAtual : '<?= $strStatusAtual?>',
        strTitulo : '<?= $strTitulo?>',
    };

    $.ajax({
        url: '<?=$strLinkAjaxListarHistoricoTipoControle?>',
        type: 'POST',
        dataType: 'XML',
        data: paramsAjax,
        success: function (response) {
            $('#tbHistDetalhe').replaceWith($(response).find("NovaTabela").html());
        },
        error: function (e) {
            console.error('Erro ao processar o XML do SEI: ' + e.responseText);
        }
    });
}

function distribuirParaMimProcesso(){

    var paramsAjax = {
        idProcedimento : '<?= $idProcedimento?>',
    };

    $.ajax({
        url: '<?=$strLinkAjaxVerificarSePodeDistribuirParaMim?>',
        type: 'POST',
        dataType: 'XML',
        data: paramsAjax,
        async: false,
        success: function (response) {
            if ( Number( $(response).find("PermiteDistribuirParaMim").html() ) == 1 ) {
                execAtribuicaoAutomatica();
            }else{
                alert('Não é permitido Distribuir a Avaliação cuja tarefa no fluxo a ser avaliada tenha sido realizada pelo mesmo Membro Participante.');
            }
        },
        error: function (e) {
            console.error('Erro ao processar o XML do SEI: ' + e.responseText);
        }
    });
}
</script>