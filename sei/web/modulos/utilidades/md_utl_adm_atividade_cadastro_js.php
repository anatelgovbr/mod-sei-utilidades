<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 06/08/2018
 * Time: 11:29
 */

if(0){ ?>
    <script>
<?php } ?>

var objTabelaDinamicaListaProduto= null;
var contadorTabelaDinamica       = 0;
var msg009 = '<?php echo MdUtlMensagemINT::$MSG_UTL_09 ?>';
var msg15Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_15)?>';
var msg16Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_16)?>';


function inicializar() {
    iniciarTabelaDinamicaListaProduto();
    iniciarRadioECheckbox();

    $('input').on('drop', function() {
        return false;
    });

}

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function iniciarRadioECheckbox(){


    var divTpAtividade = infraGetElementById('divAnalise');
    var inputs = divTpAtividade.getElementsByTagName('input');
    var radio  = null;

    for(var i=0 ; i < inputs.length ; i++){
        if(inputs[i].type == 'radio'){
            var r = inputs[i];
            if(r.checked){
                radio = r.value;
            }

        }
    }

    var comAnalise          = infraGetElementById('divComAnalise');
    var semAnalise          = infraGetElementById('divSemAnalise');
    var blocoListaProduto   = infraGetElementById('blocoListaProduto');
    var divAtvRevAmost      = infraGetElementById('divAtvRevAmost');

    if(radio =='S'){
        comAnalise.classList.toggle('blocoExibir');
        infraGetElementById('divRevATividade').style.display= 'inherit';
        blocoListaProduto.classList.toggle('blocoExibir');
        divAtvRevAmost.classList.toggle('blocoExibir');
    }
    if(radio =='N'){
        semAnalise.classList.toggle('blocoExibir');
        divAtvRevAmost.classList.toggle('blocoExibir');
        infraGetElementById('divRevATividade').style.display= 'inherit';

    }

}

function realizarValidacaoVinculoAnalise(item) {
       removerItemCadastrado(item);
       verificaTabela(2);
       return true;
}

function iniciarTabelaDinamicaListaProduto(){

    var bolConsultar = "<?php echo $bolConsultar?>";
    var acaoRemover  = bolConsultar  ? false : true;

    objTabelaDinamicaListaProduto = new infraTabelaDinamica('tbProdutoEsperado','hdnTbProdutoEsperado',false,acaoRemover);
    objTabelaDinamicaListaProduto.gerarEfeitoTabela = true;

    objTabelaDinamicaListaProduto.remover = function(item){
        return realizarValidacaoVinculoAnalise(item);
    };

    objTabelaDinamicaListaProduto.procuraLinha = function (id,vlTipo) {

        var qtd;
        var linha;
        qtd = document.getElementById('tbProdutoEsperado').rows.length;

        for (i = 1; i < qtd; i++) {
            linha = document.getElementById('tbProdutoEsperado').rows[i];
            var valorLinha = $.trim(linha.cells[1].innerText);
            var valor       = id+vlTipo;

            if (valorLinha == valor ) {
                return i;
            }

        }
        return 0;
    };

}

function removerItemCadastrado(item){

    var idVinculo      = parseInt(item[7]);
    if(idVinculo>0) {

        var hdnIdsRemovido = infraGetElementById('hdnIdsRemovido').value;
        if(hdnIdsRemovido == ''){
            infraGetElementById('hdnIdsRemovido').value = hdnIdsRemovido + idVinculo;
        }else {
            infraGetElementById('hdnIdsRemovido').value = hdnIdsRemovido +'-'+ idVinculo;
        }

    }

}

function manterTipoAtividade(obj){

    if (obj.value == 'S') {
        document.getElementById('rdnTpAtivdadeSemAnalise').checked = true;
    }else{
        document.getElementById('rdnTpAtivdadeComAnalise').checked = true;
    }
}

function trocarTipoAtividade(obj){
    var isAlterar = document.getElementById('hdnIsAlterar').value == 1;
    if(isAlterar){
        validarMudancaTipoAtividade(obj);
    }else{
        tipoAtividade(obj);
    }
}

function validarMudancaTipoAtividade(obj){
    $.ajax({
        type: "POST",
        url: "<?= $strLinkAjaxVinTpAtiv ?>",
        dataType: "XML",
        data: {
            id_atividade: document.getElementById('hdnIdAtividade').value,
        },
        success: function (r) {
            if ($(r).find('IsValido').text() == '0') {
                alert(msg009);
                manterTipoAtividade(obj);
                return false;
            }else {
                tipoAtividade(obj);
                return true;
            }
        }
    });
}

function tipoAtividade(obj){

    var comAnalise          = infraGetElementById('divComAnalise');
    var blocoListaProduto   = infraGetElementById('blocoListaProduto');
    var divAtvRevAmost      = infraGetElementById('divAtvRevAmost');
    var divRevATividade     = infraGetElementById('divRevATividade');
    var semAnalise          = infraGetElementById('divSemAnalise');


        if (obj.value == 'S') {

            comAnalise.classList.toggle("blocoExibir");
            blocoListaProduto.classList.toggle("blocoExibir");
            divAtvRevAmost.classList.toggle("blocoExibir");

            if (semAnalise.className.split(" ").length == 1) {
                //Adiciona Campo de SEM ANALISE
                semAnalise.classList.toggle('blocoExibir');
                divAtvRevAmost.classList.toggle("blocoExibir");

            }

            document.getElementById('hdnTbProdutoEsperado').setAttribute('utlCampoObrigatorio', 'a');


        } else {

            if (comAnalise.className.split(" ").length == 1) {
                //Remove campos de COM ANALISE
                comAnalise.classList.toggle('blocoExibir');
                blocoListaProduto.classList.toggle('blocoExibir');
                divRevATividade.classList.toggle('blocoExibir');
                divAtvRevAmost.classList.toggle("blocoExibir");
            }

            //Adiciona Campo de SEM ANALISE
            semAnalise.classList.toggle('blocoExibir');
            divAtvRevAmost.classList.toggle("blocoExibir");

            document.getElementById('hdnTbProdutoEsperado').removeAttribute('utlCampoObrigatorio');
        }
        divRevATividade.style.display = 'inherit';
}

function exibirTipo(obj){

    var divTpProduto    = infraGetElementById('divTpProduto');
    var divTpDocumento  = infraGetElementById('divTpDocumento');
    var divFinal        = infraGetElementById('divFinal');
    var toltipTipo      = infraGetElementById('btAjudaTipo');

    if(obj.value =='P'){

        if(divTpDocumento.className.split(" ").length == 1) {
            divTpDocumento.classList.toggle('blocoExibir');
        }
        divTpProduto.classList.toggle('blocoExibir');

        utlTrocarTooltip(toltipTipo, 'Selecionar o Produto na lista abaixo e após o preenchimento de todos os campos clicar no botão Adicionar.');

    }else{

        if(divTpProduto.className.split(" ").length == 1) {
            divTpProduto.classList.toggle('blocoExibir');
        }
        divTpDocumento.classList.toggle('blocoExibir');
        utlTrocarTooltip(toltipTipo, 'Selecionar o Documento na lista abaixo e após o preenchimento de todos os campos clicar no botão Adicionar.')
    }

    divFinal.style.display='inherit';

}

function exibirTipoDocumento(val){

    var selTpDocumentoExt    = infraGetElementById('selTpDocumentoExt');
    var selTpDocumentoInt    = infraGetElementById('selTpDocumentoInt');

    if(val == 'I'){
        selTpDocumentoExt.style.display = 'none';
        selTpDocumentoInt.style.display = 'inherit';

    }else{

        selTpDocumentoExt.style.display = 'inherit';
        selTpDocumentoInt.style.display = 'none';
    }

}


function adicionarRegistroTabelaProduto() {

    if(validarFieldsetListaProduto()) {

        var tpRadioTip = null;
        var tpRadioApl = null;
        var indexTp    = null;
        var valTp      = null;
        var txtTp      = null;
        var idVinculo  = null;
        var valido     = null;
        var blocoLista = infraGetElementById('blocoListaProduto');
        var inputs     = blocoLista.getElementsByTagName('input');
        var radio      = [];

        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].type == 'radio') {
                var r = inputs[i];
                if (r.checked) {
                    radio.push(r.value);
                }
            }
        }

        if (radio[0] == 'P') {

            tpRadioTip = radio[0];
            indexTp = document.getElementById('selTpProduto').selectedIndex;
            valTp = document.getElementById('selTpProduto').value;
            txtTp = document.getElementById('selTpProduto').options[indexTp].text;

        } else {
            tpRadioTip = radio[0];

            if (radio[1] == 'I') {

                tpRadioApl = radio[1];
                indexTp = document.getElementById('selTpDocumentoInt').selectedIndex;
                valTp = document.getElementById('selTpDocumentoInt').value;
                txtTp = document.getElementById('selTpDocumentoInt').options[indexTp].text;

            } else {

                tpRadioApl = radio[1];
                indexTp = document.getElementById('selTpDocumentoExt').selectedIndex;
                valTp = document.getElementById('selTpDocumentoExt').value;
                txtTp = document.getElementById('selTpDocumentoExt').options[indexTp].text;

            }
        }


        var valorRevisao = document.getElementById('txtRevUnidade').value;
        var obrigatorio = null;
        var chkobrigatorio = infraGetElementById('chkObrigatorio').checked;

        if (chkobrigatorio) {
            obrigatorio = 'Sim';
        } else {
            obrigatorio = 'Não';
        }

        valido = objTabelaDinamicaListaProduto.procuraLinha(valTp,tpRadioTip);
        var pkTabela = 'NOVO_REGISTRO_' +contadorTabelaDinamica;
        if(valido == 0) {
            var arrLinha = [
                pkTabela,
                valTp+tpRadioTip,
                tpRadioTip,
                tpRadioApl,
                txtTp,
                valorRevisao,
                obrigatorio,
                chkobrigatorio,
                idVinculo,
                valTp,
                'N'
            ];
            contadorTabelaDinamica++;
            objTabelaDinamicaListaProduto.adicionar(arrLinha);
            infraGetElementById('tbProdutoEsperado').style.display = 'inherit';

            limparCamposListaProdutos();

            var toltipTipo      = infraGetElementById('btAjudaTipo');
            utlTrocarTooltip(toltipTipo, 'Selecionar o Tipo de Produto, posteriormente escolher o documento/produto na lista abaixo e após o preenchimento de todos os campos clicar no botão Adicionar.')

        }else{
            var msg = '';

            if(tpRadioTip == 'P') {
                var tipo = 'Produto "' + txtTp + '"';
                msg = setMensagemPersonalizada(msg16Padrao, [tipo]);
            }else{
                var tipo = 'Documento "' + txtTp + '"';
                msg = setMensagemPersonalizada(msg16Padrao, [tipo]);
            }

            alert(msg);
            return false;
        }
    }

}

function limparCamposListaProdutos(){

    var blocoLista = infraGetElementById('blocoListaProduto');
    var inputs     = blocoLista.getElementsByTagName('input');
    var divTpDocumento = blocoLista.getElementsByTagName('div').divTpDocumento;
    var divTpProduto   = blocoLista.getElementsByTagName('div').divTpProduto;
    var radio  = [];

    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].type == 'checkbox' || inputs[i].type == 'radio') {
            inputs[i].checked = false;
        }else{
            inputs[i].value = '';
        }

    }

    var selects = blocoLista.getElementsByTagName('select');
    for (i = 0; i < selects.length; i++) {
        var options = selects[i].querySelectorAll('option');
        if (options.length > 0) {
            selects[i].value = options[0].value;
        }
    }

    //Remove os campos da tela de acordo com o tipo de atividade selecionado
    if(divTpDocumento.classList.length == 1){

        divTpDocumento.classList.toggle('blocoExibir');
    }else if(divTpProduto.classList.length == 1){

        divTpProduto.classList.toggle('blocoExibir');
    }

    var selTpDocumentoExt    = infraGetElementById('selTpDocumentoExt');
    var selTpDocumentoInt    = infraGetElementById('selTpDocumentoInt');

     selTpDocumentoExt.style.display = 'none';
     selTpDocumentoInt.style.display = 'none';

}

function verificaTabela(qtdLinha) {

    var tbProdutoEsperado = document.getElementById('tbProdutoEsperado');
    var ultimoRegistro = tbProdutoEsperado.rows.length == qtdLinha;

    if (ultimoRegistro) {
        document.getElementById('tbProdutoEsperado').style.display = 'none';
    }

    return true;
}

function onSubmitForm(){

  if(!validarAtividadeComAnalise()){
      return false;
  }

    return utlValidarObrigatoriedade();
}

function validarAtividadeComAnalise() {
    var chkAtvComAnalise = infraGetElementById('rdnTpAtivdadeComAnalise');
    if(chkAtvComAnalise.checked){
        var txtExecucaoAtividade = infraGetElementById('txtExecucaoAtividade');
        var txtRevAtividade = infraGetElementById('txtRevAtividade');
        if(parseInt(txtExecucaoAtividade.value) == 0) {
            var msg = setMensagemPersonalizada(msg15Padrao, ['Prazo para Execução da Atividade']);
            alert(msg);
            return false;
        } else if(parseInt(txtRevAtividade.value) == 0) {
            var msg = setMensagemPersonalizada(msg15Padrao, ['Prazo para Revisão da Atividade']);
            alert(msg);
            return false;
        }
    }
    
    return true;
}

function validarFieldsetListaProduto() {

    // Validar Field lista de Produtos
    var divTpAtividade = infraGetElementById('divTpAtividade');
    var inputs = divTpAtividade.getElementsByTagName('input');
    var radio  = null;

    for(var i=0 ; i < inputs.length ; i++){
        if(inputs[i].type == 'radio'){
            var r = inputs[i];
            if(r.checked){
                radio = r.value;
            }

        }
    }

    // validar radio Tipo
    if(radio == null){
        var msg = setMensagemPersonalizada(msg11Padrao, ['Tipo']);
        alert(msg);
        return false;
    }

    if(radio == 'P'){
        var selTpProduto =  infraGetElementById('selTpProduto').value;

        if(selTpProduto == 0){
            infraGetElementById('selTpProduto').focus();
            var msg = setMensagemPersonalizada(msg11Padrao, ['Tipo de Produto']);
            alert(msg);
            return false;
        }
    }

    if(radio == 'D'){

        var divTpDocumento = infraGetElementById('divTpDocumento');
        var inputs = divTpDocumento.getElementsByTagName('input');
        var radioDoc  = null;

        for(var i=0 ; i < inputs.length ; i++){
            if(inputs[i].type == 'radio'){
                var r = inputs[i];
                if(r.checked){
                    radioDoc = r.value;
                }

            }
        }

        //validar aplicabilidade do documento Interno ou Externo
        if(radioDoc == null){
            var msg = setMensagemPersonalizada(msg11Padrao, ['Aplicabilidade']);
            alert(msg);
            return false;
        }

        if(radioDoc == 'I') {
            if(infraGetElementById('selTpDocumentoInt').value == 0){
                infraGetElementById('selTpDocumentoInt').focus();
                var msg = setMensagemPersonalizada(msg11Padrao, ['Tipo de Documento SEI']);
                alert(msg);
                return false;
            }
        }

        if(radioDoc == 'E'){
            if(infraGetElementById('selTpDocumentoExt').value == 0){
                infraGetElementById('selTpDocumentoExt').focus();
                var msg = setMensagemPersonalizada(msg11Padrao, ['Tipo de Documento SEI']);
                alert(msg);
                return false;
            }
        }

    }

    // validar Valor da revisão do produto em Unidades de Esforço
    if(infraGetElementById('txtRevUnidade').value == ''){
        infraGetElementById('txtRevUnidade').focus();
        var msg = setMensagemPersonalizada(msg11Padrao, ['Valor da revisão do produto em Unidades de Esforço (EU)']);
        alert(msg);
        return false;
    }

    return true;
}
<?php if(0){ ?>
</script>
<?php } ?>
