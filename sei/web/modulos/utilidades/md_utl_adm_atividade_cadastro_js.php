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
var msg11Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11)?>';
var msg15Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_15)?>';
var msg16Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_16)?>';
var isAlterarGrid = false;
var tpRadioApl= '';
var tpRadioTip= '';
var indexTp = '';
var valTp   = '';
var txtTp   = '';

function inicializar() {
    iniciarTabelaDinamicaListaProduto();
    iniciarRadioECheckbox();

    $('input').on('drop', function() {
        return false;
    });

    // TET = Tempo de execu��o (em minutos) no Regime de Trabalho Teletrabalho
    // TE= Tempo de execu��o (em minutos)
    // PDMT = Percentual de Desempenho a Maior para Teletrabalho
    // TET = TE / (1 + (PDMT/100))
    $('#txtTmpExecucao').on('keypress keyup keydown',function(e) {
        var pdmt = <?=$percentualTeletrabalho?>;
        var te = $('#txtTmpExecucao').val();
        tet = te / (1 + (pdmt/100));
        var re = new RegExp('^-?\\d+(?:\.\\d{0,' + (2 || -1) + '})?');
        total = tet.toString().match(re)[0];

        $('#txtTempoExecucaoAnaliseAtividade').val(total);
    });

    <?php if ( $_GET['acao'] == 'md_utl_adm_atividade_alterar' || $_GET['acao'] == 'md_utl_adm_atividade_consultar' ) { ?>
        var pdmt = <?= $percentualTeletrabalho?>;
        var te = $('#txtTmpExecucao').val();
        tet = te / (1 + (pdmt/100));
        var re = new RegExp('^-?\\d+(?:\.\\d{0,' + (2 || -1) + '})?');
        total = tet.toString().match(re)[0];

        $('#txtTempoExecucaoAnaliseAtividade').val(total);    
    <?php } ?>
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
       limparCamposListaProdutos();
       return true;
}

function iniciarTabelaDinamicaListaProduto(){

    objTabelaDinamicaListaProduto = new infraTabelaDinamica('tbProdutoEsperado','hdnTbProdutoEsperado',true,true);
    objTabelaDinamicaListaProduto.gerarEfeitoTabela = true;

    objTabelaDinamicaListaProduto.remover = function(item){
        return realizarValidacaoVinculoAnalise(item);
    };

    objTabelaDinamicaListaProduto.alterar = function(item){

        isAlterarGrid = true;

        if(item[2] == 'P'){
            desabilitarCamposProdutoAlteracao(item);
        }else{
           desabilitarCamposDocumentoAlteracao(item);
        }

        infraGetElementById('rdnProduto').disabled = 'disabled';
        infraGetElementById('rdnDocumento').disabled = 'disabled';
        infraGetElementById('hdnIdAlteracao').value = item[0];
        infraGetElementById('chkObrigatorio').checked = (item[7] === 'true');
        infraGetElementById('txtRevUnidade').value = item[5];

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
 function desabilitarCamposProdutoAlteracao(item) {
    var obj = infraGetElementById('rdnProduto');
    infraGetElementById('rdnProduto').checked = 'checked';

    if(infraGetElementById('divTpProduto').className != 'bloco'){
        exibirTipo(obj);
    }
    infraGetElementById('selTpProduto').value = item[9];
    infraGetElementById('selTpProduto').disabled = 'disabled';
}

 function desabilitarCamposDocumentoAlteracao(item) {
     var obj = infraGetElementById('rdnDocumento');
     infraGetElementById('rdnDocumento').checked = 'checked';

     if(infraGetElementById('divTpDocumento').className != 'bloco'){
         exibirTipo(obj);
     }
     item[3] == 'I' ?  infraGetElementById('rdnAplicSerieInterno').checked = 'checked' : infraGetElementById('rdnAplicSerieExterno').checked = 'checked';
     infraGetElementById('rdnAplicSerieInterno').disabled = 'disabled';
     infraGetElementById('rdnAplicSerieExterno').disabled = 'disabled';

     exibirTipoDocumento(item[3]);

     item[3] == 'I' ? infraGetElementById('selTpDocumentoInt').value = item[9] : infraGetElementById('selTpDocumentoExt').value = item[9];
     item[3] == 'I' ? infraGetElementById('selTpDocumentoInt').disabled = 'disabled' : infraGetElementById('selTpDocumentoExt').disabled = 'disabled';
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

        utlTrocarTooltip(toltipTipo, 'Selecionar o Produto na lista abaixo e ap�s o preenchimento de todos os campos clicar no bot�o Adicionar.');
    }else{

        if(divTpProduto.className.split(" ").length == 1) {
            divTpProduto.classList.toggle('blocoExibir');
        }
        divTpDocumento.classList.toggle('blocoExibir');
        utlTrocarTooltip(toltipTipo, 'Selecionar o Documento na lista abaixo e ap�s o preenchimento de todos os campos clicar no bot�o Adicionar.')
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

function retornaRadiosTelaPreenchidos(){
    var blocoLista = infraGetElementById('blocoListaProduto');
    var inputs = blocoLista.getElementsByTagName('input');
    var radio = [];

    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].type === 'radio') {
            var r = inputs[i];
            if (r.checked) {
                radio.push(r.value);
            }
        }
    }

    return radio;
}
function definirTiposProdutosSelecionados(radio){
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
}

function validarDuplicidade(){
    var isPosicaoLinha = objTabelaDinamicaListaProduto.procuraLinha(valTp, tpRadioTip);
    var msg = '';
    var tipo ='';
    var valido = isPosicaoLinha == 0;

    if(!valido) {
        if (tpRadioTip === 'P') {
            tipo = 'Produto "' + txtTp + '"';
            msg = setMensagemPersonalizada(msg16Padrao, [tipo]);
        } else {
            tipo = 'Documento "' + txtTp + '"';
            msg = setMensagemPersonalizada(msg16Padrao, [tipo]);
        }

        alert(msg);
    }

    return valido;
}

function adicionarRegistroTabelaProduto() {

    if (validarFieldsetListaProduto()) {
        var radio = retornaRadiosTelaPreenchidos();
        definirTiposProdutosSelecionados(radio);

        if (!isAlterarGrid && !validarDuplicidade()) {
            return false;
        }

        var idVinculo = null;
        var valorRevisao = document.getElementById('txtRevUnidade').value;
        var chkobrigatorio = infraGetElementById('chkObrigatorio').checked;
        var valorPkAlteracao = infraGetElementById('hdnIdAlteracao').value;
        var obrigatorio = chkobrigatorio ? 'Sim' : 'N�o';
        var pkTabela = isAlterarGrid ? valorPkAlteracao : 'NOVO_REGISTRO_' + contadorTabelaDinamica;
        var isRegistroNovo =(pkTabela.indexOf('NOVO_REGISTRO_') -1);
        var isAlteracao = isAlterarGrid ? 'S' : 'N';

        var arrLinha = [
            pkTabela,
            valTp + tpRadioTip,
            tpRadioTip,
            tpRadioApl,
            txtTp,
            valorRevisao,
            obrigatorio,
            chkobrigatorio,
            idVinculo,
            valTp,
            'N',
            isAlteracao
        ];

        contadorTabelaDinamica++;
        objTabelaDinamicaListaProduto.adicionar(arrLinha);
        infraGetElementById('tbProdutoEsperado').style.display = 'inherit';

        limparCamposListaProdutos();

        var toltipTipo = infraGetElementById('btAjudaTipo');
        utlTrocarTooltip(toltipTipo, 'Selecionar o Tipo de Produto, posteriormente escolher o documento/produto na lista abaixo e ap�s o preenchimento de todos os campos clicar no bot�o Adicionar.')
        isAlterarGrid = false;

    }

    return false;

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
    infraGetElementById('rdnProduto').disabled = false;
    infraGetElementById('rdnDocumento').disabled = false;
    infraGetElementById('rdnAplicSerieInterno').disabled = false;
    infraGetElementById('rdnAplicSerieExterno').disabled = false;
    infraGetElementById('selTpDocumentoExt').disabled = false;
    infraGetElementById('selTpDocumentoInt').disabled = false;
    infraGetElementById('selTpProduto').disabled = false;

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
    //Retirado a obrigatoriedade destes campos
    /*
    if(!validarAtividadeComAnalise()){
        return false;
    }
    */
    return utlValidarObrigatoriedade();
}

function validarAtividadeComAnalise() {
    var chkAtvComAnalise = infraGetElementById('rdnTpAtivdadeComAnalise');
    if(chkAtvComAnalise.checked){
        var txtExecucaoAtividade = infraGetElementById('txtExecucaoAtividade');
        var txtRevAtividade = infraGetElementById('txtRevAtividade');
        if(parseInt(txtExecucaoAtividade.value) == 0) {
            var msg = setMensagemPersonalizada(msg15Padrao, ['Prazo para Execu��o da Atividade']);
            alert(msg);
            return false;
        } else if(parseInt(txtRevAtividade.value) == 0) {
            var msg = setMensagemPersonalizada(msg15Padrao, ['Prazo para Avalia��o da Atividade']);
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

    // validar Tempo de Execu��o da Avalia��o do Produto (em minutos)
    if(infraGetElementById('txtRevUnidade').value == ''){
        infraGetElementById('txtRevUnidade').focus();
        var msg = setMensagemPersonalizada(msg11Padrao, ['Tempo de Execu��o da Avalia��o do Produto (em minutos)']);
        alert(msg);
        return false;
    }

    return true;
}
<?php if(0){ ?>
</script>
<?php } ?>
