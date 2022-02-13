<?php if(0){ ?>
    <script>
<?php } ?>

//variaveis globais - declarar fora do escopo das fun�oes da pagina
var objLupaMembros          = null;
var objAutoCompletarMembros = null;
var objTabelaDinamicaUsuParticipante    = null;
var viewConsulta                        = false;
var idTpCtrl    = <?=$idTipoControle?>;
var msg10Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_10)?>';
var msg11Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11)?>';
var msg12Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_12)?>';
var msg98Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_98)?>';
var msg99Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_99)?>';
var msg46Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_46)?>';
var msg55Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_55)?>';

function inicializar() {
    if ('<?=$_GET['acao']?>'=='md_utl_adm_jornada_cadastrar'){

        document.getElementById('txtNome').focus();
    } else if ('<?=$_GET['acao']?>'=='md_utl_adm_jornada_consultar'){
        viewConsulta = true;
        infraDesabilitarCamposAreaDados();
    }else{
        document.getElementById('btnCancelar').focus();
    }

    var vlEspecifico   = document.getElementById('hdnTpAjusteEspecifico').value;
    var isEspecifico   = document.getElementById('hdnTpAjuste').value == vlEspecifico;

   if (('<?=$_GET['acao']?>'=='md_utl_adm_jornada_cadastrar' || '<?=$_GET['acao']?>'=='md_utl_adm_jornada_alterar') && isEspecifico){
       document.getElementById("divMembros").style.display = '';
       realizarAjaxBuscarLinks(false, false, false);
    }

    $('input').on('drop', function() {
        return false;
    });
}

function controlarHdnTipoAjuste(){
    var objGeral      = document.getElementById('rdoGeral');
    var objEspecifico = document.getElementById('rdoEspecifico');
    var objHdn        = document.getElementById('hdnTpAjuste');
    var sinGeral      = objGeral.checked ? objGeral.value : null;
    var isValor       = sinGeral == null && objEspecifico.checked ? objEspecifico.value : sinGeral;
    objHdn.value      = isValor != null ?  isValor : '';

    // var selTpControle = document.getElementById("selTpCtrlDesempenho");
    // var isParametrizado = selTpControle.options[selTpControle.selectedIndex].getAttribute('parametros') == 'S';

    // if(selTpControle.value == '' || !isParametrizado){
    if(idTpCtrl == ''){
        objLupaMembros = null;
        objAutoCompletarMembros = null;
        document.getElementById('txtMembros').setAttribute('readonly','readonly');
    }else{
        document.getElementById('txtMembros').removeAttribute('readonly');
    }

    alteracaoTipoAjuste(objHdn);

}


function onSubmitForm(){

    var txtNome = document.getElementById('txtNome');
    if($.trim(txtNome.value) == '')
    {
        var msg = setMensagemPersonalizada(msg11Padrao, ['Nome']);
        alert(msg);
        txtNome.focus();
        return false;
    }


    var txtDesc = document.getElementById('txaDescricao');
    if($.trim(txtDesc.value) == '')
    {
        var msg = setMensagemPersonalizada(msg11Padrao, ['Descri��o']);
        alert(msg);
        txtDesc.focus();
        return false;
    }

    var percAjuste = document.getElementById('txtPercentualAjuste');
    if(percAjuste.value == '')
    {
        var msg = setMensagemPersonalizada(msg11Padrao, ['Percentual de Ajuste']);
        alert(msg);
        percAjuste.focus();
        return false;
    }

    var dtInicio = document.getElementById('txtDtInicio');
    if(dtInicio.value == '')
    {
        var msg = setMensagemPersonalizada(msg11Padrao, ['In�cio']);
        alert(msg);
        dtInicio.focus();
        return false;
    }


    var dtFim = document.getElementById('txtDtFim');
    if(dtFim.value == '')
    {
        var msg = setMensagemPersonalizada(msg11Padrao, ['Fim']);
        alert(msg);
        dtFim.focus();
        return false;
    }

    var tpAjuste = document.getElementById('hdnTpAjuste');
    if(tpAjuste.value == '')
    {
        var msg = setMensagemPersonalizada(msg11Padrao, ['Tipo de Ajuste']);
        alert(msg);
        tpAjuste.focus();
        return false;
    }

    var optionsMembros = document.getElementById('selMembros').options;
    var vlEspecifico   = document.getElementById('hdnTpAjusteEspecifico').value;
    if( optionsMembros.length == 0 && tpAjuste.value == vlEspecifico){
        var msg = setMensagemPersonalizada(msg12Padrao, ['Membro']);
        alert(msg);
        document.getElementById('selMembros').focus();
        return false;
    }

}

function controlarTxtMembros(){
    // var selTpControle = document.getElementById("selTpCtrlDesempenho");
    // var isParametrizado = selTpControle.options[selTpControle.selectedIndex].getAttribute('parametros') == 'S';

    // if(selTpControle.value != '' && isParametrizado) {
    if(idTpCtrl != '') {
        realizarAjaxBuscarLinks(false, false, false);
    }
}

function controlarTxtMembrosteste(obj){
    // var selTpControle = document.getElementById("selTpCtrlDesempenho");

    // if(selTpControle.value == ''){
    //     alert('Selecione o Tipo de Controle de Desempenho!');
    //     obj.value = '';
    //     selTpControle.focus();
    //     return false;
    // }

/*    var isParametrizado = selTpControle.options[selTpControle.selectedIndex].getAttribute('parametros') == 'S';

    if(!isParametrizado){
        alert('O Tipo de Controle selecionado n�o est� parametrizado. Realize a parametriza��o do mesmo para incluir uma Jornada.');
        obj.value = '';
        selTpControle.focus();
        return false;
    }*/

    realizarAjaxBuscarLinks(false, false, true);

}

function carregarComponenteMembros(linkAjax, linkLupa){
    // ================= INICIO - JS para selecao de gestores =============================

    objAutoCompletarMembros = new infraAjaxAutoCompletar('hdnIdMembrosLupa','txtMembros', linkAjax);
    objAutoCompletarMembros.limparCampo = true;
    objAutoCompletarMembros.tamanhoMinimo = 3;
    objAutoCompletarMembros.prepararExecucao = function(){
        return 'palavras_pesquisa='+document.getElementById('txtMembros').value;
    };

    objAutoCompletarMembros.processarResultado = function(id,descricao,complemento){

        if (id!=''){
            var options = document.getElementById('selMembros').options;

            for(var i=0;i < options.length;i++){
                if (options[i].value == id){
                    var msg = setMensagemPersonalizada(msg10Padrao, ['Usu�rio Participante']);
                    alert(msg);
                    break;
                }
            }

            if (i==options.length){

                for(i=0;i < options.length;i++){
                    options[i].selected = false;
                }

                opt = infraSelectAdicionarOption(document.getElementById('selMembros'), descricao ,id);
                objLupaMembros.atualizar();
                opt.selected = true;
            }

            document.getElementById('txtMembros').value = '';
            document.getElementById('txtMembros').focus();

        }
    };

    objLupaMembros = new infraLupaSelect('selMembros','hdnMembrosLupa',linkLupa);
}

function validarValorPercentual(obj){
    var valorPercentual = obj.value;

    if(valorPercentual > 100){
        var msg = setMensagemPersonalizada(msg98Padrao, ['de Ajuste']);
        alert(msg);
        obj.value = '';
        obj.focus();
        return false;
    }
}

function selecionarMembro() {
    // var selectTpControle = document.getElementById('selTpCtrlDesempenho').value != '';
    var validarTpControle = idTpCtrl != '';

    if(validarTpControle) {
        realizarAjaxBuscarLinks(true, false, true);
    }/*else{
        alert('Selecione o Tipo de Controle de Desempenho!');
        document.getElementById('selTpCtrlDesempenho').focus();
    }*/
}

function realizarAjaxBuscarLinks(selecionar, remover, isValidarParams){

    var strValidarParams = isValidarParams ? '1' : '0';
    var params = {
        // idTipoControle: document.getElementById('selTpCtrlDesempenho').value,
        idTipoControle: idTpCtrl,
        validarParams : strValidarParams
    };

    $.ajax({
        url: '<?=$strUrlBuscarLinksAssinados?>',
        type: 'POST',
        data: params,
        dataType: 'XML',
        success: function (r) {
                var sucesso =  $(r).find('Sucesso').text();
            
            if(sucesso == '1') {
                var linkLupa = $(r).find('LinkLupa').text();
                var linkAjax = $(r).find('LinkAjax').text();
                carregarComponenteMembros(linkAjax, linkLupa);

                if(selecionar) {
                    objLupaMembros.selecionar(700, 500);
                }

                if(remover){
                    objLupaMembros.remover();
                }

            }else{
                var msg = $(r).find('Mensagem').text();
                alert(msg);
                // document.getElementById('selTpCtrlDesempenho').focus();
            }

        },
        error: function (e) {
            console.error('Erro ao buscar URL de Tipo de Controle: ' + e.responseText);
        }
    });

}

function alteracaoTipoAjuste(obj) {
    var tpAjusteSelec = obj.value;
    var vlEspecifico  = document.getElementById('hdnTpAjusteEspecifico').value;


    if(tpAjusteSelec == vlEspecifico){
        document.getElementById('divMembros').style.display = '';
    }else{
        document.getElementById('divMembros').style.display = 'none';
    }

    zerarValoresSelectedMembros(true);
}

// function onChangeTipoControle(){
//     var selTpCtrl  = infraGetElementById('selTpCtrlDesempenho');

//     if(objLupaMembros == null){
//         document.getElementById('selMembros').innerHTML = '';

//     }else{
//         zerarValoresSelectedMembros(true);
//     }

//     var selTpControle = document.getElementById("selTpCtrlDesempenho");
//     var isParametrizado = selTpControle.options[selTpControle.selectedIndex].getAttribute('parametros') == 'S';

//     if(selTpCtrl.value == '' || !isParametrizado){
//         objLupaMembros = null;
//         objAutoCompletarMembros = null;
//         document.getElementById('txtMembros').setAttribute('readonly','readonly');
//     }else{
//         document.getElementById('txtMembros').removeAttribute('readonly');
//     }
// }




function zerarValoresSelectedMembros(remover){
    var sel          = document.getElementById('selMembros');
    var valorRemover = false;
    for (var i=0; i<sel.options.length; i++) {
        valorRemover = true;
        sel.options[i].selected = true;
    }

    if(valorRemover && remover) {
        objLupaMembros.remover();
    }
}


function removerMembros(){
// var tpCtrl = document.getElementById('selTpCtrlDesempenho').value;
    var tpCtrl = idTpCtrl;
    if(objLupaMembros == null){
        if(tpCtrl != '') {
            realizarAjaxBuscarLinks(false, true, true);
        }else {
            var msg = setMensagemPersonalizada(msg99Padrao);
            alert(msg);
        }
    }else{
        objLupaMembros.remover();
    }
}

function validarDataJornada(obj){
    var validar = infraValidarData(obj, false);
    if(!validar){
        var msg = setMensagemPersonalizada(msg46Padrao);
        alert(msg);
        obj.value = '';
        obj.focus();
    }

    var dtInicio = infraGetElementById('txtDtInicio');
    var dtFim    = infraGetElementById('txtDtFim');

    if(dtInicio.value != '' && dtFim.value != ''){

        var dtTimeInicio = returnDateTime(dtInicio.value);
        var dtTimeFim    = returnDateTime(dtFim.value);

        var valido = (dtTimeInicio.getTime() <= dtTimeFim.getTime());

        if(!valido)
        {
            dtInicio.value = '';
            dtFim.value = '';
            var msg = setMensagemPersonalizada(msg55Padrao);
            alert(msg);
            dtInicio.focus();
            return false;
        }
    }
}

function returnDateTime(valor){

    var valorArray = valor != '' ? valor.split(" ") : '';

    if(Array.isArray(valorArray)){
        var data = valorArray[0]
        data = data.split('/');
        var mes = parseInt(data[1]) - 1;

        var dataCompleta = new Date(data[2], mes  ,data[0], '00', '00', '00');
        return dataCompleta;
    }

    return false;
}

function controlarVisualizacaoPercentual(obj){
    var txtTipoRevisao = document.getElementById('txtTipoRevisao');
    if(obj.checked){
        txtTipoRevisao.removeAttribute('disabled');
    }else{
        txtTipoRevisao.setAttribute('disabled', 'disabled');
        txtTipoRevisao.value = '0';
    }
}

function validarDuplicidade(){
    var arrUsuarios = document.getElementById('selMembros').options;
    var msg         = '';

    for (var i = 0; i < arrUsuarios.length; i++) {
        if(objTabelaDinamicaUsuParticipante.existeIdUsuario(arrUsuarios[i].value, 0)){
            valido = false;
            msg += '-'+ arrUsuarios[i].innerText;
            msg += '\n';
        }
    }

    var msgFim = 'Os Usu�rios listados abaixo j� est�o cadastrados como Usu�rios Participantes para esta Jornada: \n';

    if(msg != ''){
        msgFim += msg;
        alert(msgFim);
    }

    return msg == '';
}

function validarAdicionarParticipante(){
    var arrUsuarios = document.getElementById('selMembros').options;
    var isTriador   = document.getElementById('rdoTriador').checked;
    var isRevisor   = document.getElementById('rdoRevisor').checked;
    var isAnalista  = document.getElementById('rdoAnalista').checked;

    if(arrUsuarios.length == 0){
        var msg = setMensagemPersonalizada(msg12Padrao ['Usu�rio Participante']);
        alert(msg);
        return false;
    }

    if(!isTriador && !isRevisor && !isAnalista){
        var plural = arrUsuarios.length > 1 ? 's': '';
        var msg    = 'Informe ao menos um papel para o'+plural+' Usu�rio'+plural+' Participante'+plural+'.';
        alert(msg);
        return false;
    }

    if(!validarDuplicidade()){
        return false;
    }

    return true;
}

function adicionarMembros(){

   if(validarAdicionarParticipante()) {
        buscarNomeUsuario();
   }
}

function buscarNomeUsuario(){
    var arrUsuarios  = document.getElementById('selMembros').options;
    var arrIds       = new Array();
    for (var i = 0; i < arrUsuarios.length; i++) {
        var idVinculo = arrUsuarios[i].value;
        arrIds.push(idVinculo);
    }

  
   var params = {
       arrIdsVinculo: arrIds
    };

        $.ajax({
            url: '<?=$strUrlBuscarNomesUsuario?>',
            type: 'POST',
            data: params,
            dataType: 'XML',
            success: function (r) {
                realizarProcessoAddUsuarioPart(r);
            },
            error: function (e) {
                console.error('Erro ao buscar o nome do usu�rio: ' + e.responseText);
            }
        });
}

function realizarProcessoAddUsuarioPart(retornoAjax){
    var arrUsuarios  = document.getElementById('selMembros').options;
    var vlTriador    = document.getElementById('rdoTriador').checked ? 'Sim' : 'N�o';
    var sinTriador   = document.getElementById('rdoTriador').checked ? 'S' : 'N';
    var vlRevisor    = document.getElementById('rdoRevisor').checked ? 'Sim' : 'N�o';
    var sinRevisor   = document.getElementById('rdoRevisor').checked ? 'S' : 'N';
    var isAnalista   = document.getElementById('rdoAnalista').checked;
    var vlAnalista   = isAnalista ? 'Sim' : 'N�o';
    var sinAnalista  = isAnalista ? 'S' : 'N';
    var vlTipoRevisao = isAnalista ? document.getElementById('txtTipoRevisao').value : '0';

    for (var i = 0; i < arrUsuarios.length; i++) {
        var idVinculo   = arrUsuarios[i].value;
        var nomeUsuario = $.trim(document.getElementById('selMembros').options[i].text);
        //$(r).find('HTML').text()
        var nomeCampAjx = 'IdUsuario' + idVinculo;
        var htmlNomeUsu = $(retornoAjax).find(nomeCampAjx).text();

        var arrLinha = [
            idVinculo,
            htmlNomeUsu,
            vlTriador,
            sinTriador,
            vlAnalista,
            sinAnalista,
            vlTipoRevisao,
            vlRevisor,
            sinRevisor
        ];

        objTabelaDinamicaUsuParticipante.adicionar(arrLinha);
    }

    if (arrUsuarios.length > 0) {
        document.getElementById('divTabelaMembros').style.display = '';
    }

    zerarCamposMembrosParticipantes();
}

function zerarCamposMembrosParticipantes() {

    //Limpando Usu�rio Participante
    var selUsuParticipante =  document.getElementById('selMembros');
    for(var i = 0; i < selUsuParticipante.length; i++){
        selUsuParticipante.options[i].selected = true;
    }

   objLupaMembros.remover();

    //Limpando checks
    var divPapeis = document.getElementById("divPapeis");
    var checks = divPapeis.getElementsByTagName('input');
    for (var i = 0; i < checks.length; i++) {
        var isCheck = checks[0].type == 'checkbox';
        if (isCheck){
            checks[i].checked = false;
        }
    }

    //Limpando o TipoRevisao
    document.getElementById('txtTipoRevisao').setAttribute('disabled','disabled');
    document.getElementById('txtTipoRevisao').value = 0;
}




function validarValorPercentual(obj){
    var valorPercentual = obj.value;

    if(valorPercentual > 100){
        var msg = setMensagemPersonalizada(msg98Padrao, ['de Ajuste']);
        alert(msg);
        obj.value = '';
        obj.focus();
        return false;
    }
}

function habilitarUltimaJornada(obj){
    if(obj.checked){
        document.getElementById('rdoDstUltimaJornada').removeAttribute('disabled');
    }else{
        document.getElementById('rdoDstUltimaJornada').setAttribute('disabled','disabled');
        document.getElementById('rdoDstUltimaJornada').checked = false;
    }
}


<?php if(0){ ?>
<script>
<?php } ?>

        