<script type="text/javascript">

    //variaveis globais - declarar fora do escopo das funçoes da pagina
    var objLupaUsuarioParticipante          = null;
    var objAutoCompletarUsuarioParticipante = null;
    var objTabelaDinamicaUsuParticipante    = null;
    var viewConsulta                        = false;
    var msgPadrao10 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_10); ?>';
    var msgPadrao11 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11); ?>';

    var msgPadrao12 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_12); ?>';
    var msgPadrao15 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_15); ?>';
    var msgPadrao83 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_83); ?>';
    var msgPadrao97 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_97); ?>';
    var idAparente  = '<?php echo $idAparente; ?>';
    var idAparenteAlteracao = 0;
    var idVinculoControleAlt = 0;

    var isAlterar   = false;
    var objRemoverLinha = {
        sim: false
    };

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
        
        <?php if( ! $isConsultar ){ ?>
            carregarComponenteUsuarioParticipante();
        <?php } ?>

        $('input').on('drop', function() {
            return false;
        });

    }

    function iniciarTabelaDinamicaUsuarioParticipante(){
        var exibirRemover = viewConsulta ? false : true;
        objTabelaDinamicaUsuParticipante = new infraTabelaDinamica('tbUsuarioParticipante', 'hdnUsuarioParticipante', true, true);
        objTabelaDinamicaUsuParticipante.gerarEfeitoTabela = true;

        objTabelaDinamicaUsuParticipante.remover = function(r){
            return validarExclusao(r[0]);
        }

        objTabelaDinamicaUsuParticipante.alterar = function(dados){
            editarUsuarioPart(dados);
        }

        objTabelaDinamicaUsuParticipante.existeIdUsuario = function (id, posicaoTabela) {
            var qtd;
            var linha;
            qtd = document.getElementById('tbUsuarioParticipante').rows.length;

            for (var i = 1; i < qtd; i++) {
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

        objTabelaDinamicaUsuParticipante.procuraLinhaIdAparente = function (id) {
            var qtd;
            var linha;
            qtd = document.getElementById('tbUsuarioParticipante').rows.length;

            for (var i = 1; i < qtd; i++) {
                linha = document.getElementById('tbUsuarioParticipante').rows[i];
                var valorLinha = $.trim(linha.cells[11].innerText);

                if (valorLinha == id) {
                    return i;
                }

            }
            return null;
        };
    }

    function validarExclusao(idVinculo){
        $.ajax({
            type: "post",
            url: "<?= $strUrlValidarVinculoUsuario ?>",
            dataType: "xml",
            async: false,
            data: {
                idVinculo: idVinculo,
                idFila   : document.getElementById('hdnIdFila').value
            },
            success: function (result) {
                check = $( result ).find('sucesso').text();
                if (check == 0) {
                    objRemoverLinha.sim = false;
                    alert( $( result ).find('msg').text() );
                } else {
                    objRemoverLinha.sim = true;
                }
            },
            error: function (msgError) {
                objRemoverLinha.sim = false;
                alert('Erro ao buscar o nome do usuário: ' + msgError.responseText);
            }
        });
        return objRemoverLinha.sim;
    }
    
    function limparCamposMembrosPart() {

        var options = document.getElementById('selUsuarioParticipante').options;
        for(i = 0; i < options.length;i++){
            options[i].selected = true;
        }

        //remove os usuarios do select
        objLupaUsuarioParticipante.remover();

    }

    function limparCamposPapelUsuPart() {

        var papelMembros = document.getElementsByClassName('checkedPapel');
        for (i = 0; i < papelMembros.length; i++) {
            if (papelMembros.length > 0) {
                papelMembros[i].checked = false;
            }
        }

        var selected = document.getElementById('selTipoRevisao');
        selected.value = '';
    }

    function editarUsuarioPart(dadosUsuario) {

        isAlterar = true;
        var idVinculo = dadosUsuario[0]
        idAparenteAlteracao = 0;
        idAparenteAlteracao = parseInt(dadosUsuario[11]);
        idVinculoControleAlt = dadosUsuario[10];

        limparCamposPapelUsuPart();

        selUsuario = document.getElementById('selUsuarioParticipante').value;
        if(selUsuario!=''){
            limparCamposMembrosPart();
        }


        objTabelaDinamicaUsuParticipante.flagAlterar = true;
        var dadosUsuario = null;


        //acoes
         hdnListaAtvPart = objTabelaDinamicaUsuParticipante.hdn.value;
         arrListaAtvPart = hdnListaAtvPart.split('¥');


        for (i = 0; i < arrListaAtvPart.length; i++) {

            hdnListaAtividade = arrListaAtvPart[i].split('±');

            if(hdnListaAtividade[0] == idVinculo){
                dadosUsuario = hdnListaAtividade ;

                break;
            }

        }

        if (dadosUsuario[6] == 'Total') {
            document.getElementById('selTipoRevisao').value = 1;
        }
        if (dadosUsuario[6] == 'Por Atividade') {
            document.getElementById('selTipoRevisao').value = 2;
        }
        if (dadosUsuario[6] == 'Sem Avaliação') {
            document.getElementById('selTipoRevisao').value = 3;
        }


        dadosUsuario[3] == "S" ? document.getElementById('rdoTriador').checked  = true : "";
        dadosUsuario[5] == "S" ? document.getElementById('rdoAnalista').checked = true : "";
        dadosUsuario[8] == "S" ? document.getElementById('rdoRevisor').checked = true : "";

        infraGetElementById('selUsuarioParticipante').disabled = true;
        infraGetElementById('txtUsuarioParticipante').disabled = true;
        infraGetElementById('divOpcoesUsuarioParticipante').hidden = true;
        infraGetElementById('selUsuarioParticipante').value = idVinculo;

        controlarVisualizacaoPercentual();
        objAutoCompletarUsuarioParticipante.processarResultado(idVinculo, dadosUsuario[9]);

        // scroll barra de rolagem automatico
        scrollTela('blocoMembrosPart');
    }

    function removerLinhaUsuarioParticipante(idVinculo){

        if(isAlterar){
            limparCamposMembrosPart();
            limparCamposPapelUsuPart();
            infraGetElementById('selUsuarioParticipante').disabled = false;
            infraGetElementById('txtUsuarioParticipante').disabled = false;
            infraGetElementById('divOpcoesUsuarioParticipante').hidden = false;
        }

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

        var txtEsfTriagem = document.getElementById('txtTmpExecucaoTriagem');
        if($.trim(txtEsfTriagem.value) == '')
        {
            var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Tempo de execução de Triagem (em minutos)']);
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
        objAutoCompletarUsuarioParticipante.tamanhoMinimo = 3;
        objAutoCompletarUsuarioParticipante.limparCampo = true;

        objAutoCompletarUsuarioParticipante.prepararExecucao = function(){
            return 'palavras_pesquisa='+document.getElementById('txtUsuarioParticipante').value;
        };

        objAutoCompletarUsuarioParticipante.processarResultado = function(id,descricao,complemento){

            if (id!=''){
                var options = document.getElementById('selUsuarioParticipante').options;

                for(var i=0;i < options.length;i++){
                    if (options[i].value == id){
                        var msg = setMensagemPersonalizada(msgPadrao10, ['Usuário Participante']);
                        alert(msg);
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
        var selTipoRevisao = document.getElementById('selTipoRevisao');

        if(triador.checked || analista.checked){
            selTipoRevisao.removeAttribute('disabled');
        }else {
            selTipoRevisao.setAttribute('disabled', 'disabled');
            selTipoRevisao.value = '0';
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
        var tipoRevisao  = document.getElementById('selTipoRevisao').value;
        var isTriador   = document.getElementById('rdoTriador').checked;
        var isRevisor   = document.getElementById('rdoRevisor').checked;
        var isAnalista  = document.getElementById('rdoAnalista').checked;

        if(arrUsuarios.length == 0){
            var msg = setMensagemPersonalizada(msgPadrao12, ['Usuário Participante']);
            alert(msg);
            return false;
        }

        if(!isTriador && !isRevisor && !isAnalista){
            var plural = arrUsuarios.length > 1 ? 's': '';
            var msg    = 'Informe ao menos um papel para o'+plural+' Usuário'+plural+' Participante'+plural+'.';
            alert(msg);
            return false;
        }

        if(tipoRevisao == '' && !isRevisor){
            var msg = setMensagemPersonalizada(msgPadrao11, ['Tipo de Avaliação']);
            alert(msg);
            return false;
        }

        if(!isAlterar) {
            if (!validarDuplicidade()) {
                return false;
            }
        }




        return true;
    }

    function adicionarUsuarioParticipante(){;

        if(validarAdicionarParticipante()) {
            infraGetElementById('selUsuarioParticipante').disabled = false;
            infraGetElementById('txtUsuarioParticipante').disabled = false;
            infraGetElementById('divOpcoesUsuarioParticipante').hidden = false;
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
        var vlRevisor    = document.getElementById('rdoRevisor').checked ? 'Sim' : 'Não';
        var sinRevisor   = document.getElementById('rdoRevisor').checked ? 'S' : 'N';
        var isAnalista   = document.getElementById('rdoAnalista').checked;
        var tipoRevisao   = document.getElementById('selTipoRevisao');
        var vlAnalista   = isAnalista ? 'Sim' : 'Não';
        var sinAnalista  = isAnalista ? 'S' : 'N';
        var vlTipoRevisao = tipoRevisao.value != "" ? tipoRevisao.options[tipoRevisao.selectedIndex].innerText.trim() : ""

        for (var i = 0; i < arrUsuarios.length; i++) {

            if(!isAlterar){
                idAparente = parseInt(idAparente) + 1;
            }

            var idVinculo   = arrUsuarios[i].value;
            var nomeCampAjx = 'IdUsuario' + idVinculo;
            var htmlNomeUsu = '<div style="text-align:center;">'+$(retornoAjax).find(nomeCampAjx).text()+'</div>';
            var nomeSigla   = $.trim(document.getElementById('selUsuarioParticipante').options[i].text) ;
            var addEmBranco = isAlterar ? '' : htmlNomeUsu;
            var idAparenteAtual = isAlterar ? idAparenteAlteracao : idAparente;

            var arrLinha = [
                idVinculo,
                addEmBranco,
                vlTriador,
                sinTriador,
                vlAnalista,
                sinAnalista,
                vlTipoRevisao,
                vlRevisor,
                sinRevisor,
                nomeSigla,
                idVinculoControleAlt,
                idAparenteAtual
            ];


            objTabelaDinamicaUsuParticipante.recarregar();
            objTabelaDinamicaUsuParticipante.adicionar(arrLinha);

            var linha = objTabelaDinamicaUsuParticipante.procuraLinhaIdAparente(idAparenteAtual);


            if(isAlterar) {
                document.getElementById('tbUsuarioParticipante').rows[linha].cells[1].innerHTML = htmlNomeUsu;
                objTabelaDinamicaUsuParticipante.atualizaHdn();
            }





            idVinculoControleAlt = 0;
            isAlterar = false;

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

        //Limpando o TipoRevisao
        document.getElementById('selTipoRevisao').setAttribute('disabled','disabled');
        document.getElementById('selTipoRevisao').value = 0;
    }



    function habilitarUltimaFila(obj){
        if(obj.checked){
            document.getElementById('rdoDstUltimaFila').removeAttribute('disabled');
        }else{
            document.getElementById('rdoDstUltimaFila').setAttribute('disabled','disabled');
            document.getElementById('rdoDstUltimaFila').checked = false;
        }
    }
</script>