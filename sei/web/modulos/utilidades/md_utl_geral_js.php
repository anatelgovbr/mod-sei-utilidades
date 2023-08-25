<script type="text/javascript">

    var msgPadraoObrigatoriedade = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11); ?>';
    document.addEventListener("DOMContentLoaded", function(event) {
        inicializarGeral();
    });

    function inicializarGeral(){
        adicionarValidacaoPaste();
    }

    function adicionarValidacaoPaste(){
        var objs = getAllElementsWithAttribute('utlSomenteNumeroPaste');
        if(objs.length > 0){
            for(var i=0; i < objs.length; i++){
                if(objs[i] instanceof HTMLInputElement && objs[i].type == 'text') {
                    somenteNumeroPaste(objs[i]);
                }
            }
        }
    }

    function utlTrocarTooltip(obj, msg){
        obj.removeAttribute('onmouseover');
        obj.onmouseover = function(){
            return infraTooltipMostrar(msg);
        }
    }

    function retornaMsgObrigatoriedade(inputObj, isRadio){
        //  var pronome = inputObj.getAttribute('utlCampoObrigatorio');
        var idCampo = isRadio ? inputObj.name : inputObj.id;
        var label   = getLabelPorId(idCampo);
        var textoLabel = label.innerHTML;
        textoLabel =  textoLabel.replace(':','');
        var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, [textoLabel]);
        return msg;
    }

    function validarFormatoData(obj){
        var msg46 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_46); ?>';
        var validar = infraValidarData(obj, false);
        if(!validar){
            alert(msg46);
            obj.value = '';
            return false;
        }

        return  true;
    }

    function returnDateTime(valor, isDataHora) {

        if(isDataHora) {
            var valorArray = valor != '' ? valor.split(" ") : '';
        }

        if ((Array.isArray(valorArray) && isDataHora) || !isDataHora) {
            var data = isDataHora ? valorArray[0] : valor;
            data = data.split('/');
            var mes = parseInt(data[1]) - 1;
            var horas = isDataHora ? valorArray[1].split(':') : new Array;
            if(!isDataHora){
                horas.push("00", "00");
            }

            var segundos = typeof horas[2] != 'undefined' ? horas[2] : "00";
            var dataCompleta = new Date(data[2], mes, data[0], horas[0], horas[1], segundos);
            return dataCompleta;
        }

        return false;
    }

    function validarObrigatoriedadeCampos(inputObj){
        var selectVazio = inputObj.type == 'select-one' && inputObj.value == 0;

        if(($.trim(inputObj.value) == '' || selectVazio) && isVisible(inputObj)) {
            var msg = retornaMsgObrigatoriedade(inputObj, false);
            inputObj.focus();
            alert(msg);
            return false;
        }
        return true;
    }

    function isVisible(el) {
        var id = '#' + el.id;
        return $(id).is(":visible");
    }

    function utlValidarObrigatoriedade(){

        var objs = getAllElementsWithAttribute('utlCampoObrigatorio');

        if(objs.length > 0){
            for(var i=0; i < objs.length; i++){

                var isCampoPadrao = objs[i].type == 'text' || objs[i].type == 'textarea' || objs[i].type == 'select-one';
                var isHidden      = objs[i].type == 'hidden';
                var isRadio       = objs[i].type == 'radio';

                if(isCampoPadrao) {
                    if(!validarObrigatoriedadeCampos(objs[i])){
                        return false;
                    }
                }

                if(isRadio){
                    if(!validarObrigatoriedadeRadio(objs[i])){
                        return false;
                    }
                }

                if (isHidden) {
                    if (!validarObrigatoriedadeTableDinamica(objs[i])) {
                        return false;
                    }
                }

            }

            return true;

        }

    }

    function validarObrigatoriedadeTableDinamica(obj) {

        var isVazioTabela = obj.value == '';

        if (isVazioTabela) {
            var idHdn = obj.id;
            var idTabela = idHdn.replace('hdn', '');
            var primeiraLetra = idTabela.substring(0, 1);
            if (primeiraLetra != '') {
                var idTabela = primeiraLetra.toLowerCase() + idTabela.substring(1);
                var objTable = document.getElementById(idTabela);

                if (objTable != 'null') {
                    if (objTable.tagName == 'TABLE') {
                        var nomeTabela = objTable.summary;
                        var pronome = obj.getAttribute('utlCampoObrigatorio') == 'a' ? 'a' : '' ;
                        alert('Informe ao menos um'+pronome+' ' + nomeTabela + '!');
                        return false;
                    }
                }
            }
        }

        return true;

    }

    function validarObrigatoriedadeRadio(obj){
        var name = obj.name;
        var objs  = document.getElementsByName(name);
        var preenchido = false;

        if(objs.length > 0) {
            for (var i = 0; i < objs.length; i++) {

                if (objs[i].checked) {
                    return true;
                }
            }

            if (!preenchido && isVisible(obj)) {
                var msg = retornaMsgObrigatoriedade(obj, true);
                obj.focus();
                alert(msg);
                return false;
            }
        }

    }

    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    function getAllElementsWithAttribute(attribute)
    {
        var matchingElements = [];
        var allElements = document.getElementsByTagName('*');
        for (var i = 0, n = allElements.length; i < n; i++)
        {
            if (allElements[i].getAttribute(attribute) !== null)
            {
                // Element exists with attribute. Add to array.
                matchingElements.push(allElements[i]);
            }
        }
        return matchingElements;
    }

    function somenteNumeroPaste(obj){
        obj.onpaste = function(e){
            setTimeout(function(ev){
                var valor = obj.value;
                var isNumero = isNumber(valor);
                if(valor < 0 || !isNumero){
                    obj.value = '';
                }
            }, 0);
        }
    }

    function getLabelPorId(forLabelSelecao){
        var labels = document.getElementsByTagName('label');
        for (var i = 0; i < labels.length; i++) {
            var forLabel = labels[i].htmlFor;
            if (forLabel != '' && forLabel == forLabelSelecao) {
                var nomelabel = $.trim(labels[i]);
                return labels[i];
            }
        }
    }

    function setMensagemPersonalizada(msg, arrParams){
        var padraoSetMsg = '@VALOR$NUMERO$@';

        for(var i = 0; i < arrParams.length; i++){
            var numero = i + 1;
            var campoNome = padraoSetMsg.replace('$NUMERO$', numero);
            var campoSubst = $.trim(arrParams[i]);
            msg = msg.replace(campoNome, campoSubst);
        }

        return msg;
    }

    function bloquearBotaoSalvar(){
        var botoes = document.getElementsByClassName('botaoSalvar');
        if(botoes.length > 0){
            for(var i= 0; i< botoes.length; i++){
                botoes[i].setAttribute('disabled','disabled');
            }
        }
    }

    function removerTags( html ){
        html = infraRemoverFormatacaoXML(html);
        const data = new DOMParser().parseFromString(html, 'text/html');
        return data.body.textContent || "";
    }

    function scrollTela(idEle , top = 80){
        // scroll barra de rolagem automatica
        var nivel = document.getElementById( idEle ).offsetTop + top;
        divInfraMoverTopo = document.getElementById("divInfraAreaTelaD");
        $( divInfraMoverTopo ).animate( { scrollTop: nivel } , 600 );
    }

    function distribuicaoAutoParaMim( vlr , statusProc , idUsuario , tipoReq = null ){
        if( vlr.value == '' ) 
        {
            if( tipoReq == 'avaliacao' )
                desmarcarCkbDistAutoTriagem();
            else
                desmarcarCkbDistAutoParaMim();
        }
        else 
        {       
            if( idUsuario != 0 ){
                $.ajax({
                    url:"<?= $strLinkValidaUsuarioPertenceAFila ?>",
                    type: 'post',
                    dataType: 'xml',
                    data: { 
                        idFila: $('#selFila').val(), status: statusProc, id_usuario: idUsuario
                    }
                })
                .done( function( rs ){
                    let res = $( rs ).find('Resultado').text() == 'S';

                    if( tipoReq == 'avaliacao' ){
                        if( res )
                            $('#ckbDistAutoTriagAnalise').removeAttr('disabled');
                        else 
                            desmarcarCkbDistAutoTriagem();
                    }else{
                        if( res )
                            $('#divDistAutoParaMim').show(); 
                        else 
                            desmarcarCkbDistAutoParaMim();
                    }
                })
                .fail( function( xhr ){
                    console.error( 'Erro: ' + xhr.responseText );
                });
            }            
        }
    }

    function desmarcarCkbDistAutoParaMim(){
        $('#divDistAutoParaMim').hide();
        $('#ckbDistAutoParaMim').prop('checked',false);
    }

    function desmarcarCkbDistAutoTriagem(){
        $('#ckbDistAutoTriagAnalise')
            .prop('checked',false);
    }

    function ocultarColunaAcoes(idTable){
        let tbl = document.querySelector('#' + idTable);
        [tbl.rows].map( row => $( row ).find('th:last , td:last').css('display','none') )
    }

    function debugTableGeral(){
        let tb = document.querySelector('#hdnTbUsuario').value;
        let arrLinhas = tb.split('¥');
        console.log(arrLinhas);
    }
    
</script>