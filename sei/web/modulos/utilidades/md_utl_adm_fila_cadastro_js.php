<?php if(0){ ?>
    <script>
<?php } ?>

//variaveis globais - declarar fora do escopo das funçoes da pagina
var objLupaUsuarioParticipante          = null;
var objAutoCompletarUsuarioParticipante = null;
var objTabelaDinamicaUsuParticipante    = null;
var viewConsulta                        = false;
var msgPadrao12 = '<?php echo  MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_12); ?>';
var msgPadrao15 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_15); ?>';


function inicializar() {
    if ('<?=$_GET['acao']?>'=='md_utl_adm_fila_cadastrar'){
        document.getElementById('txtNome').focus();
    } else if ('<?=$_GET['acao']?>'=='md_utl_adm_fila_consultar'){
        viewConsulta = true;
        infraDesabilitarCamposAreaDados();
    }else{
        document.getElementById('btnCancelar').focus();
    }

    iniciarTabelaDinamicaUsuarioParticipante();
    carregarComponenteUsuarioParticipante();

    $('input').on('drop', function() {
        return false;
    });

}

function iniciarTabelaDinamicaUsuarioParticipante(){
    var exibirRemover = viewConsulta ? false : true;
    objTabelaDinamicaUsuParticipante = new infraTabelaDinamica('tbUsuarioParticipante', 'hdnUsuarioParticipante');
    objTabelaDinamicaUsuParticipante.gerarEfeitoTabela = true;

        if (objTabelaDinamicaUsuParticipante.hdn.value != '') {
            objTabelaDinamicaUsuParticipante.recarregar();

            //acoes
            hdnListaAtvPart = objTabelaDinamicaUsuParticipante.hdn.value;
            arrListaAtvPart = hdnListaAtvPart.split('¥');

            //array
            if (arrListaAtvPart.length > 0) {
                for (i = 0; i < arrListaAtvPart.length; i++) {
                    hdnListaAtividade = arrListaAtvPart[i].split('±');
                    var btnRemover = "<a onclick='validarExclusao("+hdnListaAtividade[0]+")'><img title='Remover' alt='Remover' src='<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/remover.gif' class='infraImg'/></a>";
                    objTabelaDinamicaUsuParticipante.adicionarAcoes(hdnListaAtividade[0], btnRemover);
                }
            }

        }

    objTabelaDinamicaUsuParticipante.remover = function () {
        var qtd = document.getElementById('tbUsuarioParticipante').rows.length;
        if(qtd == 2){
            document.getElementById('divTabelaUsuarioParticipante').style.display = 'none';
        }
        return true;
        
    };

    objTabelaDinamicaUsuParticipante.existeIdUsuario = function (id, posicaoTabela) {
        var qtd;
        var linha;
        qtd = document.getElementById('tbUsuarioParticipante').rows.length;
        
        for (i = 1; i < qtd; i++) {
            linha = document.getElementById('tbUsuarioParticipante').rows[i];
            var valorLinha = $.trim(linha.cells[posicaoTabela].innerText);
            id = $.trim(id);
            
            if (valorLinha == id) {
                return true;
            }
        }
        return false;
    }

    objTabelaDinamicaUsuParticipante.procuraLinha = function (id) {

        var qtd;
        var linha;
        qtd = document.getElementById('tbUsuarioParticipante').rows.length;

        for (i = 1; i < qtd; i++) {
            linha = document.getElementById('tbUsuarioParticipante').rows[i];
            var valorLinha = $.trim(linha.cells[0].innerText);

            if (valorLinha == id) {
                return i;
            }

        }
        return null;
        };
    }

function validarExclusao(dados){
    $.ajax({
        type: "POST",
        url: "<?=$strUrlValidarVinculoUsuario?>",
        dataType: "xml",
        data: {
            idVinculo: dados,
            idFila   : document.getElementById('hdnIdFila').value
        },
        success: function (result) {
            check = result.getElementsByTagName('sucesso')[0].innerHTML;
            if(check == 0){
                alert('Não é possível remover este usuário , pois o mesmo possui vinculo com uma ou mais Distribuições.');
                return false;
            } else {
               removerLinhaUsuarioParticipante(dados);
            }
            
        },
        error: function (msgError) {
            alert('Erro ao buscar o nome do usuário: ' + msgError.responseText);
        }
    });
}

function removerLinhaUsuarioParticipante(idVinculo){
    var linha = objTabelaDinamicaUsuParticipante.procuraLinha(idVinculo);
    objTabelaDinamicaUsuParticipante.removerLinha(linha);
}

function onSubmitForm(){

  var txtNome = document.getElementById('txtNome');
    if($.trim(txtNome.value) == '')
    {
        var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Nome']);
        alert(msg);
        txtNome.focus();
        return false;
    }

    var txtDesc = document.getElementById('txaDescricao');
    if($.trim(txtDesc.value) == '')
    {
        var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Descrição']);
        alert(msg);
        txtDesc.focus();
        return false;
    }

    var txtEsfTriagem = document.getElementById('txtUndEsforcoTriagem');
    if($.trim(txtEsfTriagem.value) == '')
    {
        var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Unidade de Esforço de Triagem']);
        alert(msg);
        txtEsfTriagem.focus();
        return false;
    }

    var txtPrazoTarefa = document.getElementById('txtPrazoTarefa');
    if($.trim(txtPrazoTarefa.value) == '')
    {
        var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Prazo para Execução da Triagem']);
        alert(msg);
        txtPrazoTarefa.focus();
        return false;
    }

    var intPrazoTarefa = parseInt(txtPrazoTarefa.value);
    if($.trim(intPrazoTarefa) == 0){
        var msg = setMensagemPersonalizada(msgPadrao15, ['Prazo para Execução da Triagem']);
        alert(msg);
        txtPrazoTarefa.focus();
        return false;
    }


    var isTabelaVazia = document.getElementById('tbUsuarioParticipante').rows.length < 2
    if(isTabelaVazia)
    {
        var msg = setMensagemPersonalizada(msgPadrao12, ['Usuário Participante']);
        alert(msg);
        document.getElementById('txtUsuarioParticipante').focus();
        return false;
    }

    var existeTriador = objTabelaDinamicaUsuParticipante.existeIdUsuario('S', 3);
    if(!existeTriador){
        var msg = setMensagemPersonalizada(msgPadrao12, ['Triador para a Fila']);
        alert(msg);
        infraGetElementById('txtUsuarioParticipante').focus();
        return false;
    }

}

function carregarComponenteUsuarioParticipante(){
    // ================= INICIO - JS para selecao de gestores =============================

    objAutoCompletarUsuarioParticipante = new infraAjaxAutoCompletar('hdnIdUsuarioParticipanteLupa','txtUsuarioParticipante','<?=$strLinkAjaxUsuarioParticipante?>');
    objAutoCompletarUsuarioParticipante.limparCampo = true;

    objAutoCompletarUsuarioParticipante.prepararExecucao = function(){
        return 'palavras_pesquisa='+document.getElementById('txtUsuarioParticipante').value;
    };

    objAutoCompletarUsuarioParticipante.processarResultado = function(id,descricao,complemento){

        if (id!=''){
            var options = document.getElementById('selUsuarioParticipante').options;

            for(var i=0;i < options.length;i++){
                if (options[i].value == id){
                    alert('Usuário Participante já consta na lista.');
                    break;
                }
            }

            if (i==options.length){

                for(i=0;i < options.length;i++){
                    options[i].selected = false;
                }

                opt = infraSelectAdicionarOption(document.getElementById('selUsuarioParticipante'), descricao ,id);
                objLupaUsuarioParticipante.atualizar();
                opt.selected = true;
            }

            document.getElementById('txtUsuarioParticipante').value = '';
            document.getElementById('txtUsuarioParticipante').focus();

        }
    };

    objLupaUsuarioParticipante = new infraLupaSelect('selUsuarioParticipante','hdnUsuarioParticipanteLupa','<?=$strLinkUsuarioParticipante?>');
}


function controlarVisualizacaoPercentual(){
    var triador = infraGetElementById('rdoTriador');
    var analista = infraGetElementById('rdoAnalista');
    var txtPercentualRevisao = document.getElementById('txtPercentualRevisao');
    if(triador.checked || analista.checked){
        txtPercentualRevisao.removeAttribute('disabled');
    }else {
        txtPercentualRevisao.setAttribute('disabled', 'disabled');
        txtPercentualRevisao.value = '0';
    }

}

function validarDuplicidade(){
    var arrUsuarios = document.getElementById('selUsuarioParticipante').options;
    var msg         = '';

    for (var i = 0; i < arrUsuarios.length; i++) {
        if(objTabelaDinamicaUsuParticipante.existeIdUsuario(arrUsuarios[i].value, 0)){
            valido = false;
            msg += '-'+ arrUsuarios[i].innerHTML.split(' ')[0];
            msg += '\n';
        }
    }

    var msgFim = 'Os Usuários listados abaixo já estão cadastrados como Usuários Participantes para esta fila: \n';

    if(msg != ''){
        msgFim += msg;
        alert(msgFim);
    }

   return msg == '';
}

function validarAdicionarParticipante(){
    var arrUsuarios = document.getElementById('selUsuarioParticipante').options;
    var isTriador   = document.getElementById('rdoTriador').checked;
    var isRevisor   = document.getElementById('rdoRevisor').checked;
    var isAnalista  = document.getElementById('rdoAnalista').checked;

    if(arrUsuarios.length == 0){
        alert('Informe ao menos um Usuário Participante.');
        return false;
    }

    if(!isTriador && !isRevisor && !isAnalista){
        var plural = arrUsuarios.length > 1 ? 's': '';
        var msg    = 'Informe ao menos um papel para o'+plural+' Usuário'+plural+' Participante'+plural+'.';
        alert(msg);
        return false;
    }

    if(!validarDuplicidade()){
        return false;
    }

    return true;
}

function adicionarUsuarioParticipante(){

   if(validarAdicionarParticipante()) {
        buscarNomeUsuario();
   }
}

function buscarNomeUsuario(){
    var arrUsuarios  = document.getElementById('selUsuarioParticipante').options;
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
                console.error('Erro ao buscar o nome do usuário: ' + e.responseText);
            }
        });
}

function realizarProcessoAddUsuarioPart(retornoAjax){
    var arrUsuarios  = document.getElementById('selUsuarioParticipante').options;
    var vlTriador    = document.getElementById('rdoTriador').checked ? 'Sim' : 'Não';
    var sinTriador   = document.getElementById('rdoTriador').checked ? 'S' : 'N';
    var isTriador    = document.getElementById('rdoTriador').checked;
    var vlRevisor    = document.getElementById('rdoRevisor').checked ? 'Sim' : 'Não';
    var sinRevisor   = document.getElementById('rdoRevisor').checked ? 'S' : 'N';
    var isAnalista   = document.getElementById('rdoAnalista').checked;
    var vlAnalista   = isAnalista ? 'Sim' : 'Não';
    var sinAnalista  = isAnalista ? 'S' : 'N';
    var vlPercentual = (isAnalista || isTriador) ? document.getElementById('txtPercentualRevisao').value : '0';
    
    for (var i = 0; i < arrUsuarios.length; i++) {
        var idVinculo   = arrUsuarios[i].value;
        var nomeUsuario = $.trim(document.getElementById('selUsuarioParticipante').options[i].text);
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
            vlPercentual,
            vlRevisor,
            sinRevisor
        ];
        
        objTabelaDinamicaUsuParticipante.adicionar(arrLinha);
        var isAlterar  = document.getElementById('hdnIsAlterar').value;
        var btnRemover = '';

        if(isAlterar == 0){
            btnRemover = "<a><img  onclick='removerLinhaUsuarioParticipante("+idVinculo+")' title='Remover' alt='Remover' src='<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/remover.gif' class='infraImg'/></a>";
        }else{
            btnRemover = "<a onclick='validarExclusao("+idVinculo+")'><img title='Remover' alt='Remover' src='<?=PaginaSEI::getInstance()->getDiretorioImagensGlobal()?>/remover.gif' class='infraImg'/></a>";
        }

        objTabelaDinamicaUsuParticipante.adicionarAcoes(idVinculo, btnRemover);
    }

    if (arrUsuarios.length > 0) {
        document.getElementById('divTabelaUsuarioParticipante').style.display = '';
    }

    zerarCamposMembrosParticipantes();
}

function zerarCamposMembrosParticipantes() {

    //Limpando Usuário Participante
    var selUsuParticipante =  document.getElementById('selUsuarioParticipante');
    for(var i = 0; i < selUsuParticipante.length; i++){
        selUsuParticipante.options[i].selected = true;
    }

   objLupaUsuarioParticipante.remover();

    //Limpando checks
    var divPapeis = document.getElementById("divPapeis");
    var checks = divPapeis.getElementsByTagName('input');
    for (var i = 0; i < checks.length; i++) {
        var isCheck = checks[0].type == 'checkbox';
        if (isCheck){
            checks[i].checked = false;
        }
    }

    //Limpando o percentual
    document.getElementById('txtPercentualRevisao').setAttribute('disabled','disabled');
    document.getElementById('txtPercentualRevisao').value = 0;
}




function validarValorPercentual(obj){
    var valorPercentual = obj.value;

    if(valorPercentual > 100){
        alert('O Percentual da Revisão deve ser entre 0 e 100.');
        obj.value = '';
        obj.focus();
        return false;
    }
}

function habilitarUltimaFila(obj){
    if(obj.checked){
        document.getElementById('rdoDstUltimaFila').removeAttribute('disabled');
    }else{
        document.getElementById('rdoDstUltimaFila').setAttribute('disabled','disabled');
        document.getElementById('rdoDstUltimaFila').checked = false;
    }
}





<?php if(0){ ?>
<script>
<?php } ?>

        