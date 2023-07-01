<script type="text/javascript">

    var msg11Padrao     = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11); ?>';
    var msg52           = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_52); ?>';
    var msg53           = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_53); ?>';
    var msg54           = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_54); ?>';
    var vlFluxoFim      = '<?php echo MdUtlRevisaoRN::$FLUXO_FINALIZADO ?>';
    var vlFluxoNovaFila = '<?php echo MdUtlRevisaoRN::$NOVA_FILA ?>';
    var opcEncAvaliacao = "<?= MdUtlRevisaoINT::montarSelectEncaminhamentoString() ?>";
    var opCurrentEncAval = null;

    function verificarJustificativa(sel) {

        var val = sel.value;
        var nameSel = sel.name;
        var idSel = nameSel.split('_')[1];

        if(val.split('_')[1] == 'S'){
            infraGetElementById("selJust_"+idSel).style.display = "inherit";
            infraGetElementById("selJust_"+idSel).style.width = "100%";
            infraGetElementById("obs_"+idSel).style.display = "inherit";
            infraGetElementById("selJust_"+idSel).value = "";
            infraGetElementById("obs_"+idSel).value = "";
        }else{
            infraGetElementById("selJust_"+idSel).style.display = "none";
            infraGetElementById("obs_"+idSel).style.display = "none";
            infraGetElementById("selJust_"+idSel).value = "";
            infraGetElementById("obs_"+idSel).value = "";
        }
    }

    function salvar(){

        var isContestacao = '<?=$idContest ?>';
        var associarFila = '';
        var fila = '';

        if(isContestacao == 0) { // Quando é fluxo normal de Avaliação
            var selectEncaminhamento = document.querySelector('#selEncaminhamento');
            var option = selectEncaminhamento.children[selectEncaminhamento.selectedIndex];
            var encaminhamentoDetalhe = option.textContent;

            var selectFilaEscolhida = document.querySelector('#selFila');
            var optionFila = selectFilaEscolhida.children[selectFilaEscolhida.selectedIndex];
            fila = optionFila.textContent;

            var selectFila = infraGetElementById('selFila').value;
        }

        var valido = true;

        valido = validarSelects();

        if(valido) {
            valido = validarObservacao();
        }

        if( document.getElementById('selAvalQualitativa').value == '' ){
            alert( "<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11, 'Avaliação Qualitativa das Atividades Entregues')?>" );
            document.getElementById('selAvalQualitativa').focus();
            return false;
        }

        var txtInfoComplementar = document.getElementById('txaInformacaoComplementar');

        if( !validaQtdCaracteres(txtInfoComplementar,500) ){
            alert("<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Justificativa da Avaliao Qualitativa', '500'))?>");
            txtInfoComplementar.focus();
            return false;
        }

        var idEncaminhamento = isContestacao == 0 ? 'selEncaminhamento' : 'selEncaminhamentoContest';
        var encaminhamento = infraGetElementById(idEncaminhamento).value;        
        var associarFilaSelect = infraGetElementById('selAssociarProcFila').value;

        if(valido) {
            if (encaminhamento == '') {
                valido = false;
                var valor = isContestacao == 0 ? 'Encaminhamento da Avaliação' : 'Encaminhamento da Contestação';
                var msg = setMensagemPersonalizada(msg11Padrao, [valor]);
                alert(msg);
            }
        }

        if(valido){
            if(encaminhamento == vlFluxoFim){
                associarFila = 'Não';
                document.getElementById('selAssociarProcFila').value = 'N';
            }
        }

        if(valido){
            if(encaminhamento == vlFluxoNovaFila){
                encaminhamento == vlFluxoFim;
                associarFila = 'Sim';

                //verifica se selecionou uma fila
                if(selectFila == ''){
                    valido = false;
                    var msg = setMensagemPersonalizada(msg11Padrao, ['Fila']);
                    alert(msg);
                }
                // alterar valores para fluxo padrão
                if (valido){
                    document.getElementById('selEncaminhamento').value = vlFluxoFim;
                    document.getElementById('selAssociarProcFila').value = 'S';
                }
            }
        }

        if(valido){
            if( isContestacao == 0 ){ // se Fluxo Normal de Avaliação
                var isPossuiFila = document.getElementById('selFila').value != '';

                if(isPossuiFila) {
                    var nomeFila = document.getElementById('selFila').options[document.getElementById('selFila').selectedIndex].innerText;
                    document.getElementById('hdnSelFila').value = nomeFila.trim();
                }
            }

            bloquearBotaoSalvar();
            if(isContestacao == 0) {
                document.getElementById('hdnEncaminhamento').value = encaminhamentoDetalhe;
            }

            document.getElementById('hdnAssociarFila').value = associarFila;
            document.getElementById('hdnFila').value = fila;
            infraGetElementById('frmRevisaoCadastro').submit();
        }
    }

    function validarObservacao() {
        var inputs = document.getElementsByClassName('inputObservacao');

        for(var i = 0; i < inputs.length; i++ ){
            if( !validaQtdCaracteres(inputs[i],250)){
                alert("<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Observao', '250'))?>");
                inputs[i].focus();
                return false;
            }
        }

        return true;
    }

    function exibirCampoObservacao(){
        var inputs = document.getElementsByClassName('inputObservacao');
        for(var i = 0; i < inputs.length; i++ ){
            var input = inputs[i].id;

            if(infraGetElementById(input).value == ""){
                    inputs[i].style.display = 'none';

            }
        }
    }

    function inicializar(){
       exibirCampoObservacao();
       
       //se o checkbox da distribuicao automatica do processo ao final do fluxo estiver marcada, valida se o usuario ainda pertence
       //a fila vinculada ao processo
       <?php if ( $isEdicao ): ?>
            if( $('#validaDistAutoTriagem').val() == 'err' ){

                if( $('#chkDistAutoTriagem').val() == 'S' ) alert("<?= MdUtlMensagemINT::$MSG_UTL_124 ?>");

                $('#ckbDistAutoTriagAnalise')
                    .attr('disabled',true)
                    .prop('checked',false);
            }
        <?php endif; ?>

        const arrTpsCtrl = new Array();
	    <?php foreach($arrIdsTpCtrls as $tpCtrl): ?>
            arrTpsCtrl.push(<?= $tpCtrl?>);
	    <?php endforeach; ?>
        getCargaHrDistribuida(arrTpsCtrl, <?= $idUsuarioResp ?>);
    }

    function validarSelects() {

        var arrSel = document.getElementsByTagName('Select');

        for(var i = 0; i < arrSel.length; i++ ){
            var idSel = arrSel[i].id;

            if($('#'+idSel).is(':visible')){

                if(infraGetElementById(idSel).value == ""){
                    var campo = infraGetElementById(idSel).getAttribute('campo');

                    if(campo != undefined) {
                        if (campo == 'R') {
                            alert(msg53);
                        } else {
                            alert(msg54)
                        }

                        return false;
                    }
                }
            }
        }
        return true;
    }

    function encaminhamento(val){
        if( document.getElementById('selEncaminhamento') !== null ){
            if(val === 'N'){
                document.getElementById('divFila').style.display = 'inline-block';
                document.getElementById('selFila').innerHTML = '<?=$selFila?>';
                document.getElementById('divDistAutoTriagAnalise').style.display = '';
                if( document.querySelector('[name="hdnIdUsuarioDistrAuto"]').value == '' ){
                    document.querySelector('#ckbDistAutoTriagAnalise').setAttribute('disabled',true);
                }
            }else{
                document.getElementById('divFila').style.display='none';
                document.getElementById('selFila').value = '';
                document.getElementById('divDistAutoTriagAnalise').style.display = 'none';
                desmarcarCkbDistAutoTriagem();
            }

            if( val == 'F' || val == 'R'){
                document.getElementById('txtAlertEncAvaliacao').style.display = 'block';
            }else{
                document.getElementById('txtAlertEncAvaliacao').style.display = 'none';
            }
        }
    }

    function avaliacaoQualitativa( e ){
        if( e.value >= 0 && e.value <= 4 && e.value != '' ){
            document.getElementById('txtAlertAvalQualitativa').style.display = 'block';
            
            if( document.getElementById('divFila') !== null )
                document.getElementById('divFila').style.display = 'none';

            AddRemoveOptEncaminhamentoAnalise('rem');
        }else if( e.options.selectedIndex == 0 || e.value > 4 ){
            document.getElementById('txtAlertAvalQualitativa').style.display = 'none';
            AddRemoveOptEncaminhamentoAnalise('add');
        }
    }

    function AddRemoveOptEncaminhamentoAnalise( acao ){
        opCurrentEncAval = $('#selEncaminhamento').val();
        $('#selEncaminhamento').empty();
        $("#selEncaminhamento").append('<option value=""></option>');
        var arrOptions = opcEncAvaliacao.split('#');
        for(var i = 0 ; i < arrOptions.length ; i++){
            var arrItemOptions = arrOptions[i].split('_');
            let selectedItem = opCurrentEncAval == arrItemOptions[0] ? ' selected ' : '';
            if( acao == 'rem' ){
                if( arrItemOptions[0] == 'R' || arrItemOptions[0] == 'F' ){
                    $("#selEncaminhamento").append('<option value="'+arrItemOptions[0]+'" '+ selectedItem +'>'+arrItemOptions[1]+'</option>');
                }
            }else{
                $("#selEncaminhamento").append('<option value="'+arrItemOptions[0]+'" '+ selectedItem +'>'+arrItemOptions[1]+'</option>');
            }
        }
    }

    function realizarAvaliacaoProd( e ){
        <?php if($tpAcaoAval == MdUtlControleDsmpRN::$EM_ANALISE ) { ?>
            var arrIdxCol = new Array(4,5,6);
        <?php } else { ?>
            var arrIdxCol = new Array(2,3);
        <?php } ?>

        if ( e.checked ) {
            arrIdxCol.forEach( function( e , i) {
                mostraColumn( e );
            });
        }else{
            arrIdxCol.forEach( function( e , i) {
                ocultaColumn( e );
            });
        }
    }

    function ocultaColumn (colIndex) {
        configTable('hide',colIndex);
    }

    function mostraColumn (colIndex) {
        configTable('show',colIndex);
    }

    function configTable(acao,colIndex){

        var table = document.getElementById('tb_avaliacao');
        for (var r = 0; r < table.rows.length; r++){
            let linha = table.rows[r];
            if( linha.getAttribute('class') == 'infraTrClara' || linha.getAttribute('class') === null ){
                linha.cells[colIndex].style.display = acao == 'show' ? '' : 'none';
            }else{
                let vlr = acao == 'show' ? 5 : 2;
                linha.cells[0].setAttribute( 'colspan' , vlr );
            }
                
        }
        if( acao == 'show')
            $(".table-responsive .infraTable").css({"min-width": "1024px"});
        else
            $(".table-responsive .infraTable").css({"min-width": "0px"});
    }

    function fechar() {
        location.href = "<?= $strCancelar ?>";
    }

</script>
