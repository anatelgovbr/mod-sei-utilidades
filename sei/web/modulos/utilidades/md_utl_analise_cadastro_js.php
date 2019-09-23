<?php if(0){ ?>
<script type="javascript">
    <?php } ?>
    var isConsultar = '<?=$_GET['acao']?>'=='md_utl_analise_consultar' ? true : false;
    var encAssociarFila            = '';
    var isParametrizadoProcesso    = '<?=$isJsTpProcParametrizado?>';
    var msg11Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11); ?>';
    var msg50Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_50); ?>';
    var msg51 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_51); ?>';
    var isChecked = false;

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
            }else{

                if(campoNumeroSei) {
                    campoNumeroSei.setAttribute('disabled', 'disabled');
                    campoNumeroSei.classList.remove("campoNumeroSeiObrigatorio");
                    campoNumeroSei.value = '';
                }

                campoObservacao.setAttribute('disabled','disabled');
                campoObservacao.classList.remove("campoObservacaoObrigatorio");
                campoObservacao.value = '';
            }
        }
    }

    function validarDocumentoSEI(idSerie, idCampo){
        var id = 'numeroSEI_' + idCampo;

        var params = {
            idProcedimento       : document.getElementById('hdnIdProcedimento').value,
            numeroSEI            : document.getElementById(id).value,
            idSerieSolicitado    : idSerie
        };

        var valorNumeroSei =  document.getElementById(id).value;
        $('#'+id).val('');

        $.ajax({
            url : '<?= $strUrlValidarDocumentoSEI ?>',
            type: 'POST',
            data: params,
            dataType: 'XML',
            success: function (r) {
                var erro = $(r).find('Erro').text();
                var msg = $(r).find('Msg').text();
                if(erro == '1'){
                    $('#'+id).val('');
                    $('#'+id).focus();
                    alert(msg);
                }else{
                    $('#'+id).val(valorNumeroSei);
                }
            },
            error: function (e) {
                console.error('Erro ao validar o documento SEI: ' + e.responseText);
            }
        });
    }



    function fechar() {
        window.history.back();
    }

    function abrirModalRevisao() {
        infraAbrirJanela('<?=$strLinkIniciarRevisao?>','janelaAjudaVariaveisModelo',800,600,'location=0,status=1,resizable=1,scrollbars=1',false);
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
        var camposObrigatorios = document.getElementsByClassName('campoNumeroSeiObrigatorio');
        var todosPreenchidos = true;

        for(var i=0; i < camposObrigatorios.length; i++){
            if(todosPreenchidos){
                var valor    = camposObrigatorios[i].value;
                if($.trim(valor)== ''){
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
                var valor    = camposObrigatorios[i].value;
                if($.trim(valor)== ''){
                    todosPreenchidos = false;
                }
            }
        }

        return todosPreenchidos;
    }

    function validarUmPreenchido() {
        var checks       = document.getElementsByClassName('infraCheckbox');
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
        var existeEncaminhamento = document.getElementById('selEncaminhamentoAnl').value;
        if(existeEncaminhamento != ''){
            return true;
        }
        document.getElementById('selEncaminhamentoAnl').focus();
        return false;
    }


    function validarFila(){
        var existeEncaminhamento = document.getElementById('selEncaminhamentoAnl').value;
        var existeFila = document.getElementById('selFila').value;
        if(existeFila == '' && existeEncaminhamento == <?=MdUtlControleDsmpRN::$ENC_ASSOCIAR_EM_FILA?>){
            return false;
        }
        return true;
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

        if(!validarCamposObrigatoriosNumeroSEI()){
            var msg = setMensagemPersonalizada(msg11Padrao, ['Número SEI dos Produtos Esperados']);
            alert(msg);
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


        var nomeFila   = isParametrizadoProcesso == 1 ? document.getElementById('selFila').options[document.getElementById('selFila').selectedIndex].innerText : '';
        document.getElementById('hdnSelFila').value = isParametrizadoProcesso == 1 ? nomeFila.trim() : '';

        bloquearBotaoSalvar();
        return true;
    }

    <?php if(0){ ?>
</script>
<?php } ?>
