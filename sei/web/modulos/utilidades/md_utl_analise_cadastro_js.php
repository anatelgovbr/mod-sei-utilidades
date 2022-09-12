<script type="text/javascript">
    
    var isConsultar = '<?=$_GET['acao']?>'=='md_utl_analise_consultar' ? true : false;
    var encAssociarFila            = '';
    var isParametrizadoProcesso    = '<?=$isJsTpProcParametrizado?>';
    var msg11Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11); ?>';
    var msg50Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_50); ?>';
    var msg51 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_51); ?>';
    var isChecked = false;
    var isValidadoNumSei = true;
    var qtdAtividadesTriag = <?= count($idsAtividades) ?>;
    var isRetriagem = false;

    $( document ).ready(function() {
        verificarItemChecado();
        addSelecionados();
    });

    function addSelecionados(){

        var objs = $('input[name^=chkItem]');

        for(var i =0; i < objs.length; i++  ){
            var obj = objs[i];
            var check = objs[i].getAttribute('checkado') == 'S';
            var id = objs[i].getAttribute('id');
            if(check){
                $('#'+id).click();
            }
        }
    }

    function controlarExibicaoAnalise(obj){
        var isAssociacao = obj.value == encAssociarFila;
        var valorDisplay = isAssociacao ? '' : 'none';
        document.getElementById('divFila').style.display = valorDisplay;
        document.getElementById('hdnEncaminhamentoAnl').value = obj.value;
        if(!isAssociacao){
            document.getElementById('divFila').style.display = valorDisplay;
            document.getElementById('hdnFila').value = '';
            document.getElementById('selFila').value = '';
        }
    }

    function carregarHiddenDominio(){
        encAssociarFila = document.getElementById('hdnStaPermiteAssociarAnalise').value;
    }

    function carregarHiddenFila(obj){
        document.getElementById('hdnFila').value = obj.value;
        
        // md_utl_geral_js.php
        distribuicaoAutoParaMim(this , 1 , <?= SessaoSEI::getInstance()->getNumIdUsuario() ?>);

    }

    function inicializar(){

        bloquearDragDrop();

        if ('<?=$_GET['acao']?>'=='md_utl_analise_cadastrar'){
            document.getElementById('btnSalvar').focus();
        } else if ('<?=$_GET['acao']?>'=='md_utl_analise_consultar'){
            isConsultar = true;
            infraDesabilitarCamposAreaDados();
            bloquearCheckbox();

            document.getElementById('btnFechar').focus();
        }
        //  $('input[name^=chkItem]').trigger('click');
        carregarHiddenDominio();
    }

    function bloquearCheckbox(){
        var objInput = document.getElementsByTagName('input');
        for (var i = 0; i < objInput.length; i++) {
            if (objInput[i].type == 'checkbox'){
                infraGetElementById(objInput[i].id).disabled = true;
            }
        }
    }

    function selecionarTodosAnalise() {

        var linhasAnalise = $('input[name^=chkItem]');

        if (isChecked) {
            isChecked = false;
            linhasAnalise.prop('checked', false);
        } else {
            isChecked = true;
            linhasAnalise.prop('checked', true);
        }

        for (var i = 0; i < linhasAnalise.length; i++) {
            infraSelecionarItens(linhasAnalise[i], '');
            addChangeAnalise(linhasAnalise[i]);
        }
    }

    function bloquearDragDrop(){
        $('input').on('drop', function() {
            return false;
        });
    }

    function verificarItemChecado(){
        $('input[name^=chkItem]').click(function() {
            addChangeAnalise(this);
        });
    }

    function addChangeAnalise(obj){
        var idCampo =  $(obj).attr('id');

        var checked = $(obj).is(':checked');

        var idReferencia = idCampo.replace('chkItem', '');
        var idAlterar = 'numeroSEI' + '_' + idReferencia;
        var idObsAlterar = 'observacao' + '_' + idReferencia;

        var campoNumeroSei  = document.getElementById(idAlterar);
        var campoObservacao = document.getElementById(idObsAlterar);

        var campoRelTriagem  = document.getElementsByName( 'idRelTriagem_' + idReferencia );
        var campoSerieProd   = document.getElementsByName( 'idSerieProd_' + idReferencia );
        var campoTmpExecucao = document.getElementsByName( 'TmpExecucao_' + idReferencia );
        var campoProduto     = document.getElementsByName( 'idProduto_' + idReferencia );
        var campoAtividade   = document.getElementsByName( 'idAtividade_' + idReferencia );
        var campoNomeProduto = document.getElementsByName( 'nomeProduto_' + idReferencia );

        //Controle de Check para o Campo Numero SEI
        if(campoObservacao && !isConsultar){
            if(checked) {
                if(campoNumeroSei) {
                    campoNumeroSei.removeAttribute('disabled');
                    campoNumeroSei.classList.add("campoNumeroSeiObrigatorio");
                }else {
                    campoObservacao.classList.add("campoObservacaoObrigatorio");
                }

                campoObservacao.removeAttribute('disabled');

                //ajustado para os campos que nao estejam selecionados, não sejam enviados pelo post
                campoRelTriagem[0].removeAttribute('disabled');
                campoSerieProd[0].removeAttribute('disabled');
                campoTmpExecucao[0].removeAttribute('disabled');
                campoProduto[0].removeAttribute('disabled');
                campoAtividade[0].removeAttribute('disabled');
                campoNomeProduto[0].removeAttribute('disabled');
            }else{
                <?php if ( !is_null( $idUsuarioFezAnalise ) && $idUsuarioFezAnalise == $idUsuarioDistrAnalise ): ?>
                    var it_checado = $( obj ).attr('checkado');
                    if( it_checado !== undefined && it_checado == 'S' ){
                        alert('Não é possível remover esta Atividade analisada que está em Correção de Análise pelo mesmo Membro Responsável pela Análise');
                        //Como o checked e a class "infraTrMarcada" são desfeitas, após a msg, essas configuracoes voltam
                        $( obj ).prop('checked',true);
                        $( obj ).closest('tr').addClass('infraTrMarcada');
                        return false;
                    }
                <?php endif; ?>

                if(campoNumeroSei) {
                    campoNumeroSei.setAttribute('disabled', 'disabled');
                    campoNumeroSei.classList.remove("campoNumeroSeiObrigatorio");
                    // campoNumeroSei.value = '';
                }

                campoObservacao.setAttribute('disabled','disabled');
                campoObservacao.classList.remove("campoObservacaoObrigatorio");
                // campoObservacao.value = '';

                //ajustado para os campos que nao estejam selecionados, não sejam enviados pelo post
                campoRelTriagem[0].setAttribute('disabled','disabled');
                campoSerieProd[0].setAttribute('disabled','disabled');
                campoTmpExecucao[0].setAttribute('disabled','disabled');
                campoProduto[0].setAttribute('disabled','disabled');
                campoAtividade[0].setAttribute('disabled','disabled');
                campoNomeProduto[0].setAttribute('disabled','disabled');
            }
        }
    }

    function validarDocumentoSEI(idSerie, idCampo){
        var id = 'numeroSEI_' + idCampo;

        var valorNumeroSei =  document.getElementById(id).value;

        var params = {
            idProcedimento       : document.getElementById('hdnIdProcedimento').value,
            numeroSEI            : valorNumeroSei,
            idSerieSolicitado    : idSerie
        };

        $.ajax({
            url : '<?= $strUrlValidarDocumentoSEI ?>',
            type: 'POST',
            data: params,
            dataType: 'XML',
            async: false,
            success: function (r) {
                var erro = $(r).find('Erro').text();
                var msg = $(r).find('Msg').text();
                if(erro == '1'){                    
                    $('#'+id).val('').focus();
                    alert(msg);
                    isValidadoNumSei = false;
                }else{
                    $('#'+id).val(valorNumeroSei);
                    isValidadoNumSei = true;
                }
            },
            error: function (e) {
                console.error('Erro ao validar o documento SEI: ' + e.responseText);
            }
        });
    }



    function fechar() {
        
        if("<?= $isRetriagem ?>" == 1){
           location.href = "<?=$strDetalhamento?>";
        }else{
            window.history.back();
        }

    }
    
    function Retriagem() {
        location.href = "<?= $strUrlRetriagem ?>";
    }

    function RetriagemAnlCorrecao() {
        location.href = "<?= $strUrlRtgAnlCorrecao ?>";
    }

    function abrirModalRevisao() {
        infraAbrirJanela('<?=$strLinkIniciarRevisao?>','janelaAjudaVariaveisModelo',1200,600,'location=0,status=1,resizable=1,scrollbars=1',false);
    }

    function validarSeriesObrigatorias(){
        var todosPreenchidos = true;
        var tdsObrigatorios = document.getElementsByClassName('classTdObrigatorio');


        for(var i=0; i < tdsObrigatorios.length; i++){
            if(todosPreenchidos){
                var input    = tdsObrigatorios[i].getElementsByTagName("input");
                var elemento = input[0];
                var isCheckBox = elemento.type == 'checkbox';
                if(isCheckBox && elemento.checked == false){
                    todosPreenchidos = false;
                }
            }
        }

        return todosPreenchidos;
    }

    function validarCamposObrigatoriosNumeroSEI(){
        var camposNumSeiObrigatorios = document.getElementsByClassName('campoNumeroSeiObrigatorio');
        
        // caso não tenha nenhuma atividade selecionada, volta o valor inicial da variavel
        if( camposNumSeiObrigatorios.length == 0 ) { isValidadoNumSei = true; }
        
        var todosPreenchidos = true;
        
        for(var i=0; i < camposNumSeiObrigatorios.length; i++){
            if(todosPreenchidos){
                var valor = camposNumSeiObrigatorios[i].value;
                if( $.trim(valor) == '' ){
                    todosPreenchidos = false;
                }
            }
        }
        return todosPreenchidos;
    }

    function validarCamposObrigatoriosObservacao(){
        var camposObrigatorios = document.getElementsByClassName('campoObservacaoObrigatorio');
        var todosPreenchidos = true;

        for(var i=0; i < camposObrigatorios.length; i++){
            if(todosPreenchidos){
                // var valor = camposObrigatorios[i].value;
                // if( $.trim(valor) == '' ){
                //     todosPreenchidos = false;
                //     alert(setMensagemPersonalizada(msg11Padrao, ['Observações']));
                //     camposObrigatorios[i].focus();
                // }
                               
                if( !validaQtdCaracteres(camposObrigatorios[i],500) ){
                    todosPreenchidos = false;
                    alert("<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Observaes', '500'))?>");
                    camposObrigatorios[i].focus();
                }
            }
        }

        return todosPreenchidos;
    }

    function validarUmPreenchido() {
        var checks       = document.getElementsByClassName('infraCheckboxInput');
        var contCheckBox = checks.length;

        if(contCheckBox > 0){
            for(var i = 0; i < contCheckBox; i++){
                var obj = checks[i];
                if(obj.checked){
                    return true;
                }
            }
        }

        return false;
    }

    function validarEncaminhamento(){
        var existeEncaminhamento = document.getElementById('selEncaminhamentoAnl');
        if( typeof(existeEncaminhamento) != 'undefined' && existeEncaminhamento != null ){
            if(existeEncaminhamento.value != ''){
                return true;
            }
            document.getElementById('selEncaminhamentoAnl').focus();
            return false;
        }
        return true;
    }


    function validarFila(){
        var existeEncaminhamento = document.getElementById('selEncaminhamentoAnl');
        if( typeof(existeEncaminhamento) != 'undefined' && existeEncaminhamento != null ){
            var existeFila = document.getElementById('selFila').value;
            if(existeFila == '' && existeEncaminhamento.value == <?=MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA?>){
                return false;
            }
            return true;
        }
        return true;
    }


    function verificaSeRetriagem(){
        var qtdAtividadesSelecionadas = new Array();
        var list = document.getElementsByClassName('infraTrMarcada');
        var bloco = null;
        $( list ).each( (i , v) => {
            var row = v.closest("tr");
            var col = $( row ).find('td');
            var vlr = $( col[9] ).find('input').val();
            
            if( ! qtdAtividadesSelecionadas.includes(vlr) ){
                qtdAtividadesSelecionadas.push( vlr );
            }else if( qtdAtividadesSelecionadas.includes(vlr) && bloco != $( col[9] ).find('span').text() ){
                qtdAtividadesSelecionadas.push( vlr );
            }
            bloco = $( col[9] ).find('span').text();
        });

        if( qtdAtividadesSelecionadas.length < qtdAtividadesTriag ) {
            document.getElementById('hdnIdRetriagem').value = 1;
            <?php if( $situacaoAtual == 10 ){ ?>
                document.getElementById('hdnIdRtgAnlCorrecao').value = 1;
            <? } ?>
        }

        document.getElementById('idsAtividades').value = qtdAtividadesSelecionadas.join(',');
    }

    function onSubmitForm(){

        if(!validarUmPreenchido()){
            alert(msg51);
            return false;
        }

        if(!validarSeriesObrigatorias()){
            alert(msg50Padrao);
            return false;
        }

        if( !validarCamposObrigatoriosObservacao() ){
            return false;
        }

        if(!validarCamposObrigatoriosNumeroSEI()){
            var msg = setMensagemPersonalizada(msg11Padrao, ['Número SEI dos Produtos Esperados']);
            alert(msg);
            return false;
        }

        //variavel global para validar se algum numero SEI foi informado errado
        if( !isValidadoNumSei ){
            alert('Número SEI Inválido!');
            return false;
        }

        if(isParametrizadoProcesso == 1) {
            if (!validarEncaminhamento()) {
                var msg = setMensagemPersonalizada(msg11Padrao, ['Encaminhamento da Análise']);
                alert(msg);
                return false;
            }
        }

        if(!validarFila()){
            var msg = setMensagemPersonalizada(msg11Padrao, ['Fila']);
            alert(msg);
            return false;
        }
        
        var txtInfoComplementar = document.getElementById('txaInformacaoComplementar');

        if( !validaQtdCaracteres(txtInfoComplementar,500) ){
            alert("<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Informações Complementares', '500'))?>");
            txtInfoComplementar.focus();
            return false;
        }        

        verificaSeRetriagem();
        
        var nomeFila   = isParametrizadoProcesso == 1 ? document.getElementById('selFila').options[document.getElementById('selFila').selectedIndex].innerText : '';
        document.getElementById('hdnSelFila').value = isParametrizadoProcesso == 1 ? nomeFila.trim() : '';
        bloquearBotaoSalvar();
        return true;
    }
    
</script>