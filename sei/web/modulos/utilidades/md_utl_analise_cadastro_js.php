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
            desmarcarCkbDistAutoParaMim();
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

        verificarItemChecado();
        addSelecionados();

        bloquearDragDrop();

        if ('<?=$_GET['acao']?>'=='md_utl_analise_cadastrar'){
            document.getElementById('btnSalvar').focus();
            $('#divCargaHrDistribExecRascunho').css("display", "block");
        } else if ('<?=$_GET['acao']?>'=='md_utl_analise_consultar'){
            isConsultar = true;
            infraDesabilitarCamposAreaDados();
            bloquearCheckbox();

            document.getElementById('btnFechar').focus();
        } else {
            $('#divCargaHrDistribExecRascunho').css("display", "block");
        }
        //  $('input[name^=chkItem]').trigger('click');
        carregarHiddenDominio();

        const arrTpsCtrl = new Array();
	    <?php foreach($arrIdsTpCtrls as $tpCtrl): ?>
            arrTpsCtrl.push(<?= $tpCtrl?>);
	    <?php endforeach; ?>

        getCargaHrDistribuida(arrTpsCtrl,<?= $idUsuarioResp ?>);
        preencherNomeHidden();
        $('input[type=checkbox]').on('change', function() {
            var idCheckbox = this.id;
            if(idCheckbox.indexOf("chkItem") != -1) {
                var contIdCheckbox = idCheckbox.split('chkItem');
                var idAtividade = $("[name=idRelTriagem_" + contIdCheckbox[1] + "]").val();
                var tempoAlocadoAtividade = $("#complexidadeTarefa" + idAtividade).val();
                var atividadesSelecionadas = $("#atividadesSelecionadas").val();
                var tempoDecorrido = $("#spnCargaHrDistribRascunho").html();
                tempoDecorrido = convertToMins(tempoDecorrido);
                if (this.checked) {
                    if (atividadesSelecionadas != "") {
                        var arrayAtividadesSelecionadas = atividadesSelecionadas.split(',');
                        $("#atividadesSelecionadas").val(atividadesSelecionadas + "," + idAtividade);
                        if ($.inArray(idAtividade, arrayAtividadesSelecionadas) == -1) {
                            var tempoSomado = parseInt(tempoDecorrido) + parseInt(tempoAlocadoAtividade);
                            $("#spnCargaHrDistribRascunho").html(convertToHoursMins(tempoSomado));
                        }
                    } else {
                        $("#atividadesSelecionadas").val(idAtividade);
                        var tempoSomado = parseInt(tempoDecorrido) + parseInt(tempoAlocadoAtividade);
                        $("#spnCargaHrDistribRascunho").html(convertToHoursMins(tempoSomado));
                    }
                } else {
                    var arrayAtividadesSelecionadas = atividadesSelecionadas.split(',');
                    arrayAtividadesSelecionadas.splice(arrayAtividadesSelecionadas.indexOf(idAtividade), 1);
                    $("#atividadesSelecionadas").val(arrayAtividadesSelecionadas);
                    if ($.inArray(idAtividade, arrayAtividadesSelecionadas) == -1) {
                        var tempoSomado = parseInt(tempoDecorrido) - parseInt(tempoAlocadoAtividade);
                        if(tempoSomado > 0) {
                            $("#spnCargaHrDistribRascunho").html(convertToHoursMins(tempoSomado));
                        } else {
                            $("#spnCargaHrDistribRascunho").html(convertToHoursMins(tempoSomado));
                        }
                    }
                }
            }
        });
        <?php
            if($_GET['acao'] == 'md_utl_analise_alterar') {
                ?>
            var tempoSomado = 0;
            const atividadesSelecionadas = [];
            $('input[type=checkbox]').each(function () {
                var idCheckbox = this.id;
                if(idCheckbox.indexOf("chkItem") != -1 && this.checked) {
                    var contIdCheckbox = idCheckbox.split('chkItem');
                    var idAtividade = $("[name=idRelTriagem_" + contIdCheckbox[1] + "]").val();
                    var tempoAlocadoAtividade = $("#complexidadeTarefa" + idAtividade).val();
                    if ($.inArray(idAtividade, atividadesSelecionadas) == -1) {
                        tempoSomado = parseInt(tempoSomado) + parseInt(tempoAlocadoAtividade);
                    }
                    atividadesSelecionadas.push(idAtividade);
                }
            });
            $("#atividadesSelecionadas").val(atividadesSelecionadas);
            $("#spnCargaHrDistribRascunho").html(convertToHoursMins(tempoSomado));
        <?php
            }
        ?>

        $('#divInfraAreaPaginacaoSuperior').remove();
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
                <?php if ( !is_null( $idUsuarioFezAnalise ) && $idUsuarioFezAnalise == $idUsuarioDistrAnalise && $rascunho != "1"): ?>
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
        location.href = "<?= $strCancelar ?>";
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
    function validarCamposObrigatoriosDataDiaDia(){
        var camposObrigatorios = document.getElementsByClassName('txtDtAnaliseAtividade');
        var todosPreenchidos = true;
        for(var i=0; i < camposObrigatorios.length; i++){
            if(todosPreenchidos){
                if( camposObrigatorios[i].value == ""){
                    var idCampo = camposObrigatorios[i].id;
                    var idAtividadeCampo = idCampo.split('txtDtAnaliseAtividade');
                    var atividadesSelecionadas = $("#atividadesSelecionadas").val();
                    if (atividadesSelecionadas != "") {
                        var arrayAtividadesSelecionadas = atividadesSelecionadas.split(',');
                        if ($.inArray(idAtividadeCampo[1], arrayAtividadesSelecionadas) != -1) {
                            todosPreenchidos = false;
                            alert("<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11, 'Data') ?>");
                            camposObrigatorios[i].focus();
                        }
                    }
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
        if($("#staFrequenciaAdmPrmGr").val() != "D") {
            if($("#selPeriodo").val() == "") {
                var msg = setMensagemPersonalizada(msg11Padrao, ['Período']);
                alert(msg);
                return false;
            }
        } else {
            if($("#txtDtAnalise").val() == "") {
                var msg = setMensagemPersonalizada(msg11Padrao, ['Data']);
                alert(msg);
                return false;
            }
        }
        if($("#ckbRelatarDiaDia").prop("checked")) {
            if(!validarCamposObrigatoriosDataDiaDia()) {
                return false;
            }
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
    function salvarRascunho () {
        if(!validarUmPreenchido()){
            alert(msg51);
            return false;
        }

        if(!validarCamposObrigatoriosNumeroSEI()){
            var msg = setMensagemPersonalizada(msg11Padrao, ['Número SEI dos Produtos Esperados']);
            alert(msg);
            return false;
        }
        document.getElementById('hdnSalvarRascunho').value = '1';
        var nomeFila   = isParametrizadoProcesso == 1 ? document.getElementById('selFila').options[document.getElementById('selFila').selectedIndex].innerText : '';
        document.getElementById('hdnSelFila').value = isParametrizadoProcesso == 1 ? nomeFila.trim() : '';
        bloquearBotaoSalvar();
        document.getElementById("frmUtlAnaliseCadastro").submit();
    }
    function validaPeriodoData(data) {

        let dataSelecionada = data.value;
        const dataSelecionadaSplit = dataSelecionada.split('/');

        const dia = dataSelecionadaSplit[0]; // 15
        const mes = dataSelecionadaSplit[1]; // 04
        const ano = dataSelecionadaSplit[2]; // 2019

        dataSelecionada = new Date(ano, mes - 1, dia);
        dataHoje = new Date();

        dataCorte = new Date('<?= $selPeriodo[1] ?>');
        if(dataSelecionada.getTime() > dataHoje.getTime()) {
            alert("A data informada deve ser anterior a data atual.");
            $("#txtDtCorte").val(dataAtualBr());
            return false;
        } else if(dataSelecionada.getTime() < dataCorte.getTime() && '<?= $selPeriodo[1] ?>' != "") {
            alert("A data informada deve ser posterior a data de inicio de sua participação ou, caso não definida, deve ser posterior a Data de Corte parametrizada na administração do Tipo de Controle.");
            $("#txtDtCorte").val(dataAtualBr());
            return false;
        }
        validarFormatoData(this);
    }
    function dataAtualBr() {
        const today = new Date();
        const yyyy = today.getFullYear();
        let mm = today.getMonth() + 1; // Months start at 0!
        let dd = today.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        const formattedToday = dd + '/' + mm + '/' + yyyy;

        return formattedToday;
    }
    function relatarDiaDia(checkbox) {
        periodo = $("#selPeriodo").val();
        if(periodo != "") {
            if(checkbox.checked) {
                $(".dataRelatarDiaDia").css("display", "block");
            } else {
                $(".dataRelatarDiaDia").css("display", "none");
            }
        } else {
            $("#ckbRelatarDiaDia").prop("checked", false);
            alert("Antes de relatar dia a dia do Período é necessário selecionar um período.");
            $("#selPeriodo").focus();
            return false;
        }
    }
    $(".infraImg").click(function() {
        $('.infraCalendario').css({
            left: '',
            right: 0
        });
    });
    function validaPeriodoDataDiaADia(data) {

        let dataSelecionada = data.value;
        const dataSelecionadaSplit = dataSelecionada.split('/');

        dia = dataSelecionadaSplit[0]; // 15
        mes = dataSelecionadaSplit[1]; // 04
        ano = dataSelecionadaSplit[2]; // 2019

        dataSelecionada = new Date(ano, mes - 1, dia);

        periodoSelecionado = $("#selPeriodo").val();
        periodoSelecionadoExplodido = periodoSelecionado.split('|');

        const dataInicialPeriodoSplit = periodoSelecionadoExplodido[0].split('/');

        dia = dataInicialPeriodoSplit[0]; // 15
        mes = dataInicialPeriodoSplit[1]; // 04
        ano = dataInicialPeriodoSplit[2]; // 2019

        dataPeriodoInicial = new Date(ano, mes - 1, dia);

        const dataFinalPeriodoSplit = periodoSelecionadoExplodido[1].split('/');

        dia = dataFinalPeriodoSplit[0]; // 15
        mes = dataFinalPeriodoSplit[1]; // 04
        ano = dataFinalPeriodoSplit[2]; // 2019

        dataPeriodoFinal = new Date(ano, mes - 1, dia);

        if(dataSelecionada.getTime() > dataPeriodoFinal.getTime() || dataSelecionada.getTime() < dataPeriodoInicial.getTime()) {
            alert("A data informada deve estar de acordo com o período selecionado.");
            $(data).val('<?= $dataExecucaoAtividade ?>');
            return false;
        }
        validarFormatoData(this);
    }
    function preencherNomeHidden() {
        $("#hdnNomeMembroResponsavelAvaliacao").val($("#selUsuarioResponsavelAvaliacao option:selected").html());
    }
    function limparCamposData() {
        var camposDataDiaDia = document.getElementsByClassName('txtDtAnaliseAtividade');
        for(var i=0; i < camposDataDiaDia.length; i++){
            camposDataDiaDia[i].value = "";
        }
    }
</script>