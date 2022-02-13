<?php

if (0){
    ?><script type="text/javascript"><?
}?>

    //vars
    var msgPadrao10  = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_10); ?>';
    var msg13        = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_13); ?>';
    var msg14        = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_14); ?>';
    var msg18        = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_18); ?>';
    var msg109       = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_109); ?>';
    var msg110       = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_110); ?>';
    var msg111       = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_111); ?>';
    var msg112       = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_112); ?>';
    var msg113       = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_113); ?>';
    var msg116       = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_116); ?>';
    var heigthTamanhoDivAreaPart        = null;
    var objTabelaDinamicaFila           = null;
    var objTabelaDinamicaStatus         = null;
    var objTabelaDinamicaAtividade      = null;
    var objTabelaDinamicaTipoProcesso   = null;
    var arrFila         = new Array();
    var arrStatus       = new Array();
    var arrAtividade    = new Array();
    var arrTipoProcesso = new Array();

    function inicializar() {
        document.getElementById('selDistribuicao').focus();

        sinPriorizar();
        iniciarTabelaDinamicaFila();
        iniciarTabelaDinamicaStatus();
        iniciarTabelaDinamicaAtividade();
        iniciarTabelaDinamicaTipoProcesso();

        carregarComponenteFila();
        carregarComponenteStatus();
        carregarComponenteAtividade();
        carregarComponenteTipoProcesso();

        carregaPrioridadeGeral();
    }

    function sinPriorizar() {
        //select priorizar sim, n�o
        var selfila         = document.getElementById('selFila').value == 'S';
        var selStatus       = document.getElementById('selStatus').value == 'S';
        var selAtividade    = document.getElementById('selAtividade').value == 'S';
        var selDistribuicao = document.getElementById('selDistribuicao').value == 'S';
        var selTipoProcesso = document.getElementById('selTipoProcesso').value == 'S';
        var selDiasUteis    = document.getElementById('selDiasUteis').value == 'S';
        var contador = 0;

        selfila ? document.getElementById('divBtnAdicionarFila').style.display = 'block' : document.getElementById('divBtnAdicionarFila').style.display = 'none';
        selfila ? document.getElementById('divPrioridadeFila').style.display = 'block' : document.getElementById('divPrioridadeFila').style.display = 'none';
        selfila ? document.getElementById('divFila').style.display = 'block' : document.getElementById('divFila').style.display = 'none';
        selfila ? document.getElementById('tbFila').style.display = 'block' : document.getElementById('tbFila').style.display = 'none';
        selfila ? contador ++ : contador;

        selStatus ? document.getElementById('divBtnAdicionarStatus').style.display = 'block' : document.getElementById('divBtnAdicionarStatus').style.display = 'none';
        selStatus ? document.getElementById('divPrioridadeStatus').style.display = 'block' : document.getElementById('divPrioridadeStatus').style.display = 'none';
        selStatus ? document.getElementById('divStatus').style.display = 'block' : document.getElementById('divStatus').style.display = 'none';
        selStatus ? document.getElementById('tbStatus').style.display = 'block' : document.getElementById('tbStatus').style.display = 'none';
        selStatus ? contador ++ : contador;

        selAtividade ? document.getElementById('divBtnAdicionarAtividade').style.display = 'block' : document.getElementById('divBtnAdicionarAtividade').style.display = 'none';
        selAtividade ? document.getElementById('divPrioridadeAtividade').style.display = 'block' : document.getElementById('divPrioridadeAtividade').style.display = 'none';
        selAtividade ? document.getElementById('divAtividade').style.display = 'block' : document.getElementById('divAtividade').style.display = 'none';
        selAtividade ? document.getElementById('tbAtividade').style.display = 'block' : document.getElementById('tbAtividade').style.display = 'none';
        selAtividade ? contador ++ : contador;

        selDistribuicao ? document.getElementById('divPrioridadeDistribuicao').style.display = 'block' : document.getElementById('divPrioridadeDistribuicao').style.display = 'none';
        selDistribuicao ? contador ++ : contador;

        selTipoProcesso ? document.getElementById('divBtnAdicionarTipoProcesso').style.display = 'block' : document.getElementById('divBtnAdicionarTipoProcesso').style.display = 'none';
        selTipoProcesso ? document.getElementById('divPrioridadeTipoProcesso').style.display = 'block' : document.getElementById('divPrioridadeTipoProcesso').style.display = 'none';
        selTipoProcesso ? document.getElementById('divTipoProcesso').style.display = 'block' : document.getElementById('divTipoProcesso').style.display = 'none';
        selTipoProcesso ? document.getElementById('tbTipoProcesso').style.display = 'block' : document.getElementById('tbTipoProcesso').style.display = 'none';
        selTipoProcesso ? contador ++ : contador;

        selDiasUteis ? document.getElementById('divPrioridadeDiasUteis').style.display = 'block' : document.getElementById('divPrioridadeDiasUteis').style.display = 'none';
        selDiasUteis ? contador ++ : contador;

        this.popularSelectPrioridadeGeral(contador);
    }

    function popularSelectPrioridadeGeral(contador) {
        var selectPrioridadeDistribuicao = document.getElementById("selPrioridadeDistribuicao");
        var selectPrioridadeFila = document.getElementById("selPrioridadeFila");
        var selectPrioridadeStatus = document.getElementById("selPrioridadeStatus");
        var selectPrioridadeAtividade = document.getElementById("selPrioridadeAtividade");
        var selectPrioridadeTipoProcesso = document.getElementById("selPrioridadeTipoProcesso");
        var selectPrioridadeDiasUteis = document.getElementById("selPrioridadeDiasUteis");

        selectPrioridadeDistribuicao.options.length = 0;
        selectPrioridadeFila.options.length = 0;
        selectPrioridadeStatus.options.length = 0;
        selectPrioridadeAtividade.options.length = 0;
        selectPrioridadeTipoProcesso.options.length = 0;
        selectPrioridadeDiasUteis.options.length = 0;

        criarSelectPrioridadeGeral();

        for (i = 0; i < contador; i++) {
            selectPrioridadeDistribuicao.options[selectPrioridadeDistribuicao.options.length] = new Option(i+1, i+1);
            selectPrioridadeFila.options[selectPrioridadeFila.options.length] = new Option(i+1, i+1);
            selectPrioridadeStatus.options[selectPrioridadeStatus.options.length] = new Option(i+1, i+1);
            selectPrioridadeAtividade.options[selectPrioridadeAtividade.options.length] = new Option(i+1, i+1);
            selectPrioridadeTipoProcesso.options[selectPrioridadeTipoProcesso.options.length] = new Option(i+1, i+1);
            selectPrioridadeDiasUteis.options[selectPrioridadeDiasUteis.options.length] = new Option(i+1, i+1);
        }
    }

    /*Fila*/
    function iniciarTabelaDinamicaFila() {
        objTabelaDinamicaFila = new infraTabelaDinamica('tbFila', 'hdnFila', false, true); //editar->false, excluir->true
        objTabelaDinamicaFila.gerarEfeitoTabela = true;

        objTabelaDinamicaFila.remover = function (arr) {
            var isHideGrid = document.getElementById('tbFila').rows.length == 2;

            if(isHideGrid){
                document.getElementById('divTabelaFila').style.display = 'none';
                excluirTodosSelects('selPriFila', 'selectFila', arrFila);
                arrFila = new Array();
            }else {
                controlarComboPrioridade(true, 1);
            }

            return true;
        }

        objTabelaDinamicaFila.existeIdFila = function (id) {
            var qtd;
            var linha;
            qtd = document.getElementById('tbFila').rows.length;

            for (i = 1; i < qtd; i++) {
                linha = document.getElementById('tbFila').rows[i];
                var valorLinha = $.trim(linha.cells[0].innerText);
                id = $.trim(id);

                if (valorLinha == id) {
                    return true;
                }
            }
            return false;
        };
    }

    function carregarComponenteFila() {
        //filtro autocompletar
        objAutoCompletarFila = new infraAjaxAutoCompletar('hdnFilaLupa', 'txtFila', '<?=$strLinkAjaxFila?>');
        objAutoCompletarFila.tamanhoMinimo = 3;
        objAutoCompletarFila.limparCampo   = true;
        objAutoCompletarFila.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtFila').value;
        };

        objAutoCompletarFila.processarResultado = function (id, descricao, complemento) {

            if (id != '' || descricao != '') {
                var options = document.getElementById('selItensFila').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        var msg = setMensagemPersonalizada(msgPadrao10, ['Fila']); // Fila j� consta na lista
                        alert(msg);
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selItensFila'), descricao, id);
                    objLupaFila.atualizar();
                    opt.selected = true;
                }

                document.getElementById('txtFila').value = '';
                document.getElementById('txtFila').focus();

            }
        };

        //modal transportarmd_utl_adm_prm_gr_cadastrar
        objLupaFila = new infraLupaSelect('selItensFila', 'hdnFilaLupa', '<?=$strLinkFilaSelecao?>');

    }

    function adicionarTabelaFila() {

        var valido = realizarValidacoesFila();

        if(valido) {
            var arrFilasAdd = document.getElementById('selItensFila').options;
            for (i = 0; i < arrFilasAdd.length; i++) {
                var idFila = arrFilasAdd[i].value;
                var nome = arrFilasAdd[i].text;
                var selectPriFila = montarSelectPrioridade('selPriFila', idFila, 'selectFila');
                var arrLinha = [
                    idFila,
                    nome,
                    selectPriFila
                ];

                objTabelaDinamicaFila.adicionar(arrLinha);
            }
            controlarComboPrioridade(false, 1);
            //objTabelaDinamicaFila.recarregar();

            if (arrFilasAdd.length > 0) {
                document.getElementById('divTabelaFila').style.display = '';
            }

            zerarSelectLupa(1);
        }
    }

    /*Status*/
    function iniciarTabelaDinamicaStatus() {
        objTabelaDinamicaStatus = new infraTabelaDinamica('tbStatus', 'hdnStatus', false, true);
        objTabelaDinamicaStatus.gerarEfeitoTabela = true;

        objTabelaDinamicaStatus.remover = function (arr) {
            var isHideGrid = document.getElementById('tbStatus').rows.length == 2;
            if(isHideGrid){
                document.getElementById('divTabelaStatus').style.display = 'none';
                excluirTodosSelects('selPriStatus', 'selectStatus', arrStatus);
                arrStatus = new Array();
            }else {
                controlarComboPrioridade(true, 2);
            }
            return true;
        }

        objTabelaDinamicaStatus.existeIdStatus = function (id) {
            var qtd;
            var linha;
            qtd = document.getElementById('tbStatus').rows.length;

            for (i = 1; i < qtd; i++) {
                linha = document.getElementById('tbStatus').rows[i];
                var valorLinha = $.trim(linha.cells[0].innerText);
                id = $.trim(id);

                if (valorLinha == id) {
                    return true;
                }
            }
            return false;
        };

    }

    function carregarComponenteStatus() {
        //filtro autocompletar
        objAutoCompletarStatus = new infraAjaxAutoCompletar('hdnStatusLupa', 'txtStatus', '<?=$strLinkAjaxStatus?>');
        objAutoCompletarStatus.tamanhoMinimo = 3;
        objAutoCompletarStatus.limparCampo   = true;
        objAutoCompletarStatus.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtStatus').value;
        };

        objAutoCompletarStatus.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selItensStatus').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        var msg = setMensagemPersonalizada(msgPadrao10, ['Status']); // Tipo Status j� consta na lista
                        alert(msg);
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selItensStatus'), descricao, id);
                    objLupaStatus.atualizar();
                    opt.selected = true;
                }

                document.getElementById('txtStatus').value = '';
                document.getElementById('txtStatus').focus();

            }
        };

        objLupaStatus = new infraLupaSelect('selItensStatus', 'hdnStatusLupa', '<?=$strLinkStatusSelecao?>');
    }

    function adicionarTabelaStatus() {

        var valido = realizarValidacoesStatus();

        if(valido) {
            var arrStatusAdd = document.getElementById('selItensStatus').options;
            for (i = 0; i < arrStatusAdd.length; i++) {
                var idStatus = arrStatusAdd[i].value;
                var nome = arrStatusAdd[i].text;
                var selectPriStatus = montarSelectPrioridade('selPriStatus', idStatus, 'selectStatus');
                var arrLinha = [
                    idStatus,
                    nome,
                    selectPriStatus
                ];

                objTabelaDinamicaStatus.adicionar(arrLinha);
            }

            controlarComboPrioridade(false, 2);

            if (arrStatusAdd.length > 0) {
                document.getElementById('divTabelaStatus').style.display = '';
            }

            zerarSelectLupa(2);
        }
    }

    /* Atividade */
    function iniciarTabelaDinamicaAtividade() {
        objTabelaDinamicaAtividade = new infraTabelaDinamica('tbAtividade', 'hdnAtividade', false, true);
        objTabelaDinamicaAtividade.gerarEfeitoTabela = true;

        objTabelaDinamicaAtividade.remover = function (arr) {
            var isHideGrid = document.getElementById('tbAtividade').rows.length == 2;
            if(isHideGrid){
                document.getElementById('divTabelaAtividade').style.display = 'none';
                excluirTodosSelects('selPriAtividade', 'selectAtividade', arrAtividade);
                arrAtividade = new Array();
            }else {
                controlarComboPrioridade(true, 3);
            }
            return true;
        }

        objTabelaDinamicaAtividade.existeIdAtividade = function (id) {
            var qtd;
            var linha;
            qtd = document.getElementById('tbAtividade').rows.length;

            for (i = 1; i < qtd; i++) {
                linha = document.getElementById('tbAtividade').rows[i];
                var valorLinha = $.trim(linha.cells[0].innerText);
                id = $.trim(id);

                if (valorLinha == id) {
                    return true;
                }
            }
            return false;
        };
    }

    function carregarComponenteAtividade() {
        //filtro autocompletar
        objAutoCompletarAtividade = new infraAjaxAutoCompletar('hdnAtividadeLupa', 'txtAtividade', '<?=$strLinkAjaxAtividade?>');
        objAutoCompletarAtividade.tamanhoMinimo = 3;
        objAutoCompletarAtividade.limparCampo   = true;
        objAutoCompletarAtividade.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtAtividade').value;
        };

        objAutoCompletarAtividade.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selItensAtividade').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        var msg = setMensagemPersonalizada(msgPadrao10, ['Atividade']); //Tipo de Atividade j� consta na lista
                        alert(msg);
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selItensAtividade'), descricao, id);
                    objLupaAtividade.atualizar();
                    opt.selected = true;
                }

                document.getElementById('txtAtividade').value = '';
                document.getElementById('txtAtividade').focus();

            }
        };

        objLupaAtividade = new infraLupaSelect('selItensAtividade', 'hdnAtividadeLupa', '<?=$strLinkAtividadeSelecao?>');

    }

    function adicionarTabelaAtividade() {

        var valido = realizarValidacoesAtividade();

        if(valido) {
            var arrAtividadesAdd = document.getElementById('selItensAtividade').options;
            for (i = 0; i < arrAtividadesAdd.length; i++) {
                var idAtividade = arrAtividadesAdd[i].value;
                var nome = arrAtividadesAdd[i].text;
                var selectPriAtividade = montarSelectPrioridade('selPriAtividade', idAtividade, 'selectAtividade');

                var arrLinha = [
                    idAtividade,
                    nome,
                    selectPriAtividade
                ];

                objTabelaDinamicaAtividade.adicionar(arrLinha);
            }

            controlarComboPrioridade(false, 3);

            if (arrAtividadesAdd.length > 0) {
                document.getElementById('divTabelaAtividade').style.display = '';
            }

            zerarSelectLupa(3);
        }
    }

    /*fun��o padrao*/
    function zerarSelectLupa(tipoSelectLupa) {
        switch (tipoSelectLupa) {
            case 1:
                objLupa = objLupaFila;
                idSelectLupa = 'selItensFila';
                break;
            case 2:
                objLupa = objLupaStatus;
                idSelectLupa = 'selItensStatus';
                break;
            case 3:
                objLupa = objLupaAtividade;
                idSelectLupa = 'selItensAtividade';
                break;
            case 4:
                objLupa = objLupaTipoProcesso;
                idSelectLupa = 'selItensTipoProcesso';
                break;
        }

        //limpar o select lupa
        var selItens = document.getElementById(idSelectLupa);
        for (var i = 0; i < selItens.length; i++){
            selItens.options[i].selected = true;
        }
        objLupa.remover();
    }

    function excluirTodosSelects(idSelect, classePadrao, arraySelecionado){
        var objs = document.getElementsByClassName(classePadrao);

        if(objs.length > 0) {
            for (var i = 0; i < objs.length; i++) {
                var selecionado = objs[i].value;
                var idSelectCompl = idSelect + '_';
                var id      = (objs[i].id).split(idSelectCompl)[1];
                if(selecionado != '') {
                    arraySelecionado[id] = selecionado;
                    objs[i].innerHTML = '';
                }
            }
        }
    }

    function controlarComboPrioridade(isRemover, tipoFieldset) {

        var idTabela;
        var idSelect;
        var classePadrao;
        var linha;
        var qtdLinhas;
        var valores;
        var arrAtual = new Array();

        switch (tipoFieldset) {
            case 1:
                idTabela = 'tbFila';
                idSelect = 'selPriFila';
                classePadrao = 'selectFila';
                arrAtual = arrFila;
                excluirTodosSelects(idSelect, classePadrao, arrAtual);
                arrFila = new Array();
                break;
            case 2:
                idTabela = 'tbStatus';
                idSelect = 'selPriStatus';
                classePadrao = 'selectStatus';
                arrAtual = arrStatus;
                excluirTodosSelects(idSelect, classePadrao, arrAtual);
                arrStatus = new Array();
                break;
            case 3:
                idTabela = 'tbAtividade';
                idSelect = 'selPriAtividade';
                classePadrao = 'selectAtividade';
                arrAtual = arrAtividade;
                excluirTodosSelects(idSelect, classePadrao, arrAtual);
                arrAtividade = new Array();
                break;
            case 4:
                idTabela = 'tbTipoProcesso';
                idSelect = 'selPriTipoProcesso';
                classePadrao = 'selectTipoProcesso';
                arrAtual = arrTipoProcesso;
                excluirTodosSelects(idSelect, classePadrao, arrAtual);
                arrTipoProcesso = new Array();
                break;
        }

        var qtd = (document.getElementById(idTabela).rows.length);
        qtdLinhas = qtd;
        valores   = isRemover ? (qtd - 1) : qtd;

        var opcoes = '';
        for (var i = 1; i < qtdLinhas; i++) {
            linha = document.getElementById(idTabela).rows[i];
            var pkLinha = $.trim(linha.cells[0].innerText);
            var idComboPri = idSelect + '_' + pkLinha;
            var comboPri = document.getElementById(idComboPri);
            getOptionsSelectPrioridade(comboPri, valores);

            if(arrAtual.length > 0 && arrAtual[pkLinha] != undefined){
                var valor = arrAtual[pkLinha];
                if(isExistsValorSelect(comboPri,valor)) {
                    comboPri.value = arrAtual[pkLinha];
                }else{
                    comboPri.value = 1;
                }
            }
        }
    }

    function isExistsValorSelect(combo, valor){
        var isExists = false;
        var ddloption = combo.options;
        for (var i = 0; i < ddloption.length; i++) {
            if (ddloption[i].value === valor) {
                isExists = true;
                break;
            }
        }
        return isExists;
    }

    function montarSelectPrioridade(nomeSelect, idPk, classSelect) {
        var idSelect = nomeSelect + '_' + idPk;
        var combo = '<select style="width: 100%;" class="infraSelect ' + classSelect + '" id=' + idSelect + ' name=' + idSelect + '></select>';
        return combo;
    }

    function getOptionsSelectPrioridade(comboPri, qtd) {

        for (var i = 1; i < qtd; i++) {
            var option = document.createElement("option");
            option.text = i;
            comboPri.add(option);
        }
    }

    function validarSequencia(nomeClasse){
        var combos          = document.getElementsByClassName(nomeClasse);
        var sequenciaValida = true;
        var arrSequencia    = new Array();

        arrSequencia[0] = 0;
        for (var i = 0; i < combos.length; i++) {
            arrSequencia[combos[i].value] = i;
        }


        for (var i = 0; i < arrSequencia.length; i++) {
            var isVazio = (typeof arrSequencia[i] == 'undefined');
            if(isVazio){
                sequenciaValida = false;
                break;
            }
        }

        return sequenciaValida;
    }

    function validarCadastro() {
        var tbFila       = document.getElementById('tbFila');
        var tbStatus     = document.getElementById('tbStatus');
        var tbAtividade  = document.getElementById('tbAtividade');
        var qtdDiasUteis = document.getElementById('qtdDiasUteis');

        var selfila             = document.getElementById('selFila').value == 'S';
        var selStatus           = document.getElementById('selStatus').value == 'S';
        var selAtividade        = document.getElementById('selAtividade').value == 'S';
        var selTipoProcesso     = document.getElementById('selTipoProcesso').value === 'S';
        var selDiasUteis        = document.getElementById('selDiasUteis').value === 'S';
        var selDistribuicao     = document.getElementById('selDistribuicao').value === 'S';

        if (selDistribuicao) {
            var selPrioridadeDistribuicao = document.getElementById('selPrioridadeDistribuicao');

            if (selPrioridadeDistribuicao.value === 'null') {
                var msg = setMensagemPersonalizada(msg13, ['valor em Prioridade geral para o Prazo para Resposta']);
                alert(msg);
                document.getElementById('selPrioridadeDistribuicao').focus();
                return false;
            }
        }

        if(selDiasUteis) {
            var selPrioridadeDiasUteis = document.getElementById('selPrioridadeDiasUteis');
            if (selPrioridadeDiasUteis.value === 'null') {
                var msg = setMensagemPersonalizada(msg13, ['valor em Prioridade geral para Dias �teis no Status']);
                alert(msg);
                document.getElementById('selPrioridadeDiasUteis').focus();
                return false;
            }

            if (qtdDiasUteis.value === "") {
                var msg = setMensagemPersonalizada(msg13, ['valor em Quantidade Dias �teis']);
                alert(msg);
                document.getElementById('qtdDiasUteis').focus();
                return false;
            }
        }

        if(selfila) {
            var selPrioridadeFila = document.getElementById('selPrioridadeFila');
            if (selPrioridadeFila.value === 'null') {
                var msg = setMensagemPersonalizada(msg13, ['valor em Prioridade geral para Fila']);
                alert(msg);
                document.getElementById('selPrioridadeFila').focus();
                return false;
            }

            if(tbFila.rows.length == 1){
                var msg = setMensagemPersonalizada(msg18, ['Fila']);
                alert(msg);
                document.getElementById('txtFila').focus();
                return false;
            }

            if(!validarSequencia('selectFila')){
                alert(setMensagemPersonalizada(msg112, ['Fila']));
                return false;
            }
            //isDuplicada = validarPrioridadeDuplicada(1);
        }

        if(selStatus) {
            var selPrioridadeStatus = document.getElementById('selPrioridadeStatus');
            if (selPrioridadeStatus.value === 'null') {
                var msg = setMensagemPersonalizada(msg13, ['valor em Prioridade geral para Status']);
                alert(msg);
                document.getElementById('selPrioridadeStatus').focus();
                return false;
            }

            if(tbStatus.rows.length == 1){
                var msg = setMensagemPersonalizada(msg13, ['Status']);
                alert(msg);
                document.getElementById('txtStatus').focus();
              return false;
            }
            if(!validarSequencia('selectStatus')){
                alert(setMensagemPersonalizada(msg112, ['Status']));
                return false;
            }
            //    isDuplicada = validarPrioridadeDuplicada(2);
        }

        if(selAtividade) {
            var selPrioridadeAtividade = document.getElementById('selPrioridadeAtividade');
            if (selPrioridadeAtividade.value === 'null') {
                var msg = setMensagemPersonalizada(msg13, ['valor em Prioridade geral para Atividade']);
                alert(msg);
                document.getElementById('selPrioridadeAtividade').focus();
                return false;
            }

            if(tbAtividade.rows.length == 1){
                var msg = setMensagemPersonalizada(msg18, ['Atividade']);
                alert(msg);
                document.getElementById('txtAtividade').focus();
                return false;
            }
            if(!validarSequencia('selectAtividade')){
                alert(setMensagemPersonalizada(msg112, ['Atividade']));
                return false;
            }
            // isDuplicada = validarPrioridadeDuplicada(3);
        }

        if(selTipoProcesso) {
            var selPrioridadeTipoProcesso = document.getElementById('selPrioridadeTipoProcesso');
            if (selPrioridadeTipoProcesso.value === 'null') {
                var msg = setMensagemPersonalizada(msg13, ['valor em Prioridade geral para Tipo de Processo']);
                alert(msg);
                document.getElementById('selPrioridadeTipoProcesso').focus();
                return false;
            }

            if(tbTipoProcesso.rows.length === 1){
                var msg = setMensagemPersonalizada(msg18, ['Tipo Processo']);
                alert(msg);
                document.getElementById('txtTipoProcesso').focus();
                return false;
            }
            if(!validarSequencia('selectTipoProcesso')){
                alert(setMensagemPersonalizada(msg112, ['Tipo Processo']));
                return false;
            }
        }

        var validaPrioridadeGeral = validarPrioridadeGeral();

        if (validaPrioridadeGeral) {
            return true;
        }
    }

    function validarPrioridadeDuplicada(tpSelect) { //n�o chama
        var nomeSelect = '';
        var nomeClasse = '';
        var msg = '';

        switch (tpSelect) {
            case 1:
                nomeSelect = 'selectFila';
                nomeClasse = 'selPriFila';
                msg = 'Fila';
                break;
            case 2:
                nomeSelect = 'selectStatus';
                nomeClasse = 'selPriStatus';
                msg = 'Status';
                break;
            case 3:
                nomeSelect = 'selectAtividade';
                nomeClasse = 'selPriAtividade';
                msg = 'Atividade';
                break;
            case 4:
                nomeSelect = 'selectTipoProcesso';
                nomeClasse = 'selPriTipoProcesso';
                msg = 'Tipo Processo';
                break;
        }

        var valores = [];

        var arrSelect = document.getElementsByClassName(nomeSelect);

        for (var i = 0; i < arrSelect.length; i++) {
            console.log(i);
            var className = document.getElementsByClassName(nomeSelect);
            console.log(className[i].id);
                valores.push(document.getElementById(className[i].id).value);
        }

        if(prioridadeDuplicada(valores)){
            var msgAlert = setMensagemPersonalizada(msg113, [msg]);
            alert(msgAlert)
            return false;
        }

        return true;
    }

    function prioridadeDuplicada(array) { //n�o chama
        return (new Set(array)).size !== array.length;
    }


    function realizarValidacoesFila() {
        var arrFila = document.getElementById('selItensFila').options;
        var msg = '';
        var valido = true;


        if (arrFila.length > 0) {
            for (var i = 0; i < arrFila.length; i++) {
                if (objTabelaDinamicaFila.existeIdFila(arrFila[i].value)) {
                    valido = false;
                    msg += ' - ' + arrFila[i].innerHTML.split()[0];
                    msg += '\n';
                }
            }

            var msgFim = setMensagemPersonalizada(msg110, ['Filas', 'Filas']) + '\n';

            if (msg != '') {
                msgFim += msg;
                alert(msgFim);
            }

            return valido;
        }
        var msgAlert = setMensagemPersonalizada(msg109, ['uma Fila']);
        alert(msgAlert)
        return false;
    };

    function realizarValidacoesStatus(){
        var arrStatus = document.getElementById('selItensStatus').options;
        var msg = '';
        var valido = true;


        if (arrStatus.length > 0) {
            for (var i = 0; i < arrStatus.length; i++) {
                if (objTabelaDinamicaStatus.existeIdStatus(arrStatus[i].value)) {
                    valido = false;
                    msg += ' - ' + arrStatus[i].innerHTML.split()[0];
                    msg += '\n';
                }
            }

            var msgFim = setMensagemPersonalizada(msg111, ['Status', 'Status']) + '\n';

            if (msg != '') {
                msgFim += msg;
                alert(msgFim);
            }

            return valido;
        }
        var msgAlert = setMensagemPersonalizada(msg109, ['um Status']);
        alert(msgAlert)
        return false;
    };

    function realizarValidacoesAtividade(){
        var arrAtividade = document.getElementById('selItensAtividade').options;
        var msg = '';
        var valido = true;


        if (arrAtividade.length > 0) {
            for (var i = 0; i < arrAtividade.length; i++) {
                if (objTabelaDinamicaAtividade.existeIdAtividade(arrAtividade[i].value)) {
                    valido = false;
                    msg += ' - ' + arrAtividade[i].innerHTML.split()[0];
                    msg += '\n';
                }
            }

            var msgFim = setMensagemPersonalizada(msg111, ['Atividades', 'Atividades']) + '\n';

            if (msg != '') {
                msgFim += msg;
                alert(msgFim);
            }

            return valido;
        }
        var msgAlert = setMensagemPersonalizada(msg109, ['uma Atividade']);
        alert(msgAlert)
        return false;
    };

    function controlarTabela(nomeFieldset) {
        var htmlRetorno = '';
        var nomeTabela = 'tb' + nomeFieldset;
        var tabelaStatus = document.getElementById(nomeTabela);
        var corpoHtml = tabelaStatus.children[1].children;
        for (var i = 1; i < corpoHtml.length; i++) {

            if(htmlRetorno != ''){
                htmlRetorno+= '�';
            }

            var linhas = corpoHtml[i].children;
            for (var j = 0; j < linhas.length; j++) {
                var valor = '';
                var coluna = linhas[j];

                if(j == 0){
                    valor = coluna.innerText;
                }

                if(j == 1){
                    valor = '�' + coluna.innerText;
                }

                if (j == 2) {
                    valor = '�' + coluna.children[0].children[0].value;
                }

                htmlRetorno+= valor;
            }
        }
        var hdn = 'hdn' + nomeFieldset;
        document.getElementById(hdn).value = '';
        document.getElementById(hdn).value = htmlRetorno;

    }

    // Tipo de Processo
    function iniciarTabelaDinamicaTipoProcesso() {
        objTabelaDinamicaTipoProcesso = new infraTabelaDinamica('tbTipoProcesso', 'hdnTipoProcesso', false, true);
        objTabelaDinamicaTipoProcesso.gerarEfeitoTabela = true;

        objTabelaDinamicaTipoProcesso.remover = function (arr) {
            var isHideGrid = document.getElementById('tbTipoProcesso').rows.length == 2;
            if(isHideGrid){
                document.getElementById('divTabelaTipoProcesso').style.display = 'none';
                excluirTodosSelects('selPriTipoProcesso', 'selectTipoProcesso', arrTipoProcesso);
                arrTipoProcesso = new Array();
            }else {
                controlarComboPrioridade(true, 4);
            }
            return true;
        }

        objTabelaDinamicaTipoProcesso.existeIdTipoProcesso = function (id) {
            var qtd;
            var linha;
            qtd = document.getElementById('tbTipoProcesso').rows.length;

            for (i = 1; i < qtd; i++) {
                linha = document.getElementById('tbTipoProcesso').rows[i];
                var valorLinha = $.trim(linha.cells[0].innerText);
                id = $.trim(id);

                if (valorLinha == id) {
                    return true;
                }
            }
            return false;
        };
    }

    function carregarComponenteTipoProcesso() {
        //filtro autocompletar
        objAutoCompletarTipoProcesso = new infraAjaxAutoCompletar('hdnTipoProcessoLupa', 'txtTipoProcesso', '<?=$strLinkAjaxTipoProcesso?>');
        objAutoCompletarTipoProcesso.tamanhoMinimo = 3;
        objAutoCompletarTipoProcesso.limparCampo   = true;
        objAutoCompletarTipoProcesso.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtTipoProcesso').value;
        };

        objAutoCompletarTipoProcesso.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selItensTipoProcesso').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        var msg = setMensagemPersonalizada(msgPadrao10, ['Tipo Processo']); //Tipo de Processo j� consta na lista
                        alert(msg);
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selItensTipoProcesso'), descricao, id);
                    objLupaTipoProcesso.atualizar();
                    opt.selected = true;
                }

                document.getElementById('txtTipoProcesso').value = '';
                document.getElementById('txtTipoProcesso').focus();

            }
        };

        objLupaTipoProcesso = new infraLupaSelect('selItensTipoProcesso', 'hdnTipoProcessoLupa', '<?=$strLinkTipoProcessoSelecao?>');
    }

    function adicionarTabelaTipoProcesso() {

        var valido = realizarValidacoesTipoProcesso();

        if(valido) {
            var arrTipoProcessoAdd = document.getElementById('selItensTipoProcesso').options;
            for (i = 0; i < arrTipoProcessoAdd.length; i++) {
                var idTipoProcesso = arrTipoProcessoAdd[i].value;
                var nome = arrTipoProcessoAdd[i].text;
                var selectPriTipoProcesso = montarSelectPrioridade('selPriTipoProcesso', idTipoProcesso, 'selectTipoProcesso');

                var arrLinha = [
                    idTipoProcesso,
                    nome,
                    selectPriTipoProcesso
                ];

                objTabelaDinamicaTipoProcesso.adicionar(arrLinha);
            }

            controlarComboPrioridade(false, 4);

            if (arrTipoProcessoAdd.length > 0) {
                document.getElementById('divTabelaTipoProcesso').style.display = '';
            }

            zerarSelectLupa(4);
        }
    }

    function realizarValidacoesTipoProcesso() {
        var arrTipoProcesso = document.getElementById('selItensTipoProcesso').options;
        var msg = '';
        var valido = true;


        if (arrTipoProcesso.length > 0) {
            for (var i = 0; i < arrTipoProcesso.length; i++) {
                if (objTabelaDinamicaTipoProcesso.existeIdTipoProcesso(arrTipoProcesso[i].value)) {
                    valido = false;
                    msg += ' - ' + arrTipoProcesso[i].innerHTML.split()[0];
                    msg += '\n';
                }
            }

            var msgFim = setMensagemPersonalizada(msg111, ['Tipos de Processos', 'Tipos de Processos']) + '\n';

            if (msg != '') {
                msgFim += msg;
                alert(msgFim);
            }

            return valido;
        }
        var msgAlert = setMensagemPersonalizada(msg109, ['um Tipo de Processo']);
        alert(msgAlert)
        return false;
    }

    function carregaPrioridadeGeral() {
        var idTipoControleUtl   = document.getElementById("hdnIdTipoControleUtl").value;

        if (idTipoControleUtl > 0) {
            // Valores select prioridade
            var hdnPrioridadeDistribuicao    = document.getElementById("hdnPrioridadeDistribuicao");
            var hdnPrioridadeTipoProcesso    = document.getElementById("hdnPrioridadeTipoProcesso");
            var hdnPrioridadeAtividade       = document.getElementById("hdnPrioridadeAtividade");
            var hdnPrioridadeDiasUteis       = document.getElementById("hdnPrioridadeDiasUteis");
            var hdnPrioridadeStatus          = document.getElementById("hdnPrioridadeStatus");
            var hdnPrioridadeFila            = document.getElementById("hdnPrioridadeFila");

            var selectPrioridadeDistribuicao    = document.getElementById("selPrioridadeDistribuicao");
            var selectPrioridadeTipoProcesso    = document.getElementById("selPrioridadeTipoProcesso");
            var selectPrioridadeAtividade       = document.getElementById("selPrioridadeAtividade");
            var selectPrioridadeDiasUteis       = document.getElementById("selPrioridadeDiasUteis");
            var selectPrioridadeStatus          = document.getElementById("selPrioridadeStatus");
            var selectPrioridadeFila            = document.getElementById("selPrioridadeFila");

            hdnPrioridadeDistribuicao.value !== "" ? selectPrioridadeDistribuicao.options[hdnPrioridadeDistribuicao.value].selected = true : '';
            hdnPrioridadeTipoProcesso.value !== "" ? selectPrioridadeTipoProcesso.options[hdnPrioridadeTipoProcesso.value].selected = true : '';
            hdnPrioridadeAtividade.value !== "" ? selectPrioridadeAtividade.options[hdnPrioridadeAtividade.value].selected = true : '';
            hdnPrioridadeDiasUteis.value !== "" ? selectPrioridadeDiasUteis.options[hdnPrioridadeDiasUteis.value].selected = true : '';
            hdnPrioridadeStatus.value !== "" ? selectPrioridadeStatus.options[hdnPrioridadeStatus.value].selected = true : '';
            hdnPrioridadeFila.value !== "" ? selectPrioridadeFila.options[hdnPrioridadeFila.value].selected = true : '';
        }
    }

    // function controlarTabelaFila() { //n�o chama
    //     var htmlRetorno = '';
    //     var tabelaFila = document.getElementById('tbFila');
    //     var corpoHtml = tabelaFila.children[1].children;
    //     for (var i = 1; i < corpoHtml.length; i++) {
    //
    //         if(htmlRetorno != ''){
    //             htmlRetorno+= '�';
    //         }
    //
    //         var linhas = corpoHtml[i].children;
    //         for (var j = 0; j < linhas.length; j++) {
    //             var valor = '';
    //             var coluna = linhas[j];
    //
    //             if(j == 0){
    //                 valor = coluna.innerText;
    //             }
    //
    //             if(j == 1){
    //                valor = '�' + coluna.innerText;
    //             }
    //
    //             if (j == 2) {
    //                 valor = '�' + coluna.children[0].children[0].value;
    //             }
    //
    //              htmlRetorno+= valor;
    //         }
    //     }
    //
    //     document.getElementById('hdnFila').value = '';
    //     document.getElementById('hdnFila').value = htmlRetorno;
    //
    // }
    //
    // function controlarTabelaStatus() {  //n�o chama
    //     var htmlRetorno = '';
    //     var tabelaStatus = document.getElementById('tbStatus');
    //     var corpoHtml = tabelaStatus.children[1].children;
    //     for (var i = 1; i < corpoHtml.length; i++) {
    //
    //         if(htmlRetorno != ''){
    //             htmlRetorno+= '�';
    //         }
    //
    //         var linhas = corpoHtml[i].children;
    //         for (var j = 0; j < linhas.length; j++) {
    //             var valor = '';
    //             var coluna = linhas[j];
    //
    //             if(j == 0){
    //                 valor = coluna.innerText;
    //             }
    //
    //             if(j == 1){
    //                 valor = '�' + coluna.innerText;
    //             }
    //
    //             if (j == 2) {
    //                 valor = '�' + coluna.children[0].children[0].value;
    //             }
    //
    //             htmlRetorno+= valor;
    //         }
    //     }
    //
    //     document.getElementById('hdnStatus').value = '';
    //     document.getElementById('hdnStatus').value = htmlRetorno;
    //
    // }
    //
    // function controlarTabelaAtividade() {
    //     var htmlRetorno = '';
    //     var tabelaAtividade = document.getElementById('tbAtividade');
    //     var corpoHtml = tabelaAtividade.children[1].children;
    //     for (var i = 1; i < corpoHtml.length; i++) {
    //
    //         if(htmlRetorno != ''){
    //             htmlRetorno+= '�';
    //         }
    //
    //         var linhas = corpoHtml[i].children;
    //         for (var j = 0; j < linhas.length; j++) {
    //             var valor = '';
    //             var coluna = linhas[j];
    //
    //             if(j == 0){
    //                 valor = coluna.innerText;
    //             }
    //
    //             if(j == 1){
    //                 valor = '�' + coluna.innerText;
    //             }
    //
    //             if (j == 2) {
    //                 valor = '�' + coluna.children[0].children[0].value;
    //             }
    //
    //             htmlRetorno+= valor;
    //         }
    //     }
    //
    //     document.getElementById('hdnAtividade').value = '';
    //     document.getElementById('hdnAtividade').value = htmlRetorno;
    //
    // }

    function validarPrioridadeGeral() { //n�o chama
        var msg = 'Fila';
        var valores = [];
        var arrSelect = document.getElementsByClassName("prioridadeGeral");

        for (var i = 0; i < arrSelect.length; i++) {
            var className = document.getElementsByClassName("prioridadeGeral");
            if (document.getElementById(className[i].id).value !== 'null') {
                valores.push(document.getElementById(className[i].id).value);
            }
        }

        var validPrioridadeGeral = checkPrioridadeGeral(valores);

        if (validPrioridadeGeral.length > 0) {
            var repetidos = validPrioridadeGeral.filter(function(elem, index, self) {
                return index === self.indexOf(elem);
            });

            for (var i = 0; i < repetidos.length; i++) {
                if (i === 0) {
                    msg = repetidos[i];
                } else {
                    msg += ', ' + repetidos[i];
                }
            }

            var msgAlert = setMensagemPersonalizada(msg116, [msg]);
            alert(msgAlert);

            return false;
        } else {
            return true;
        }

    }

    function checkPrioridadeGeral(itens) {
        var repetido = [];

        var aux = itens.filter(function (elemento, i) {
            if (itens.indexOf(elemento) !== i) {

                var arrSelect = document.getElementsByClassName("prioridadeGeral");

                for (var iterator = 0; iterator < arrSelect.length; iterator++) {
                    var className = document.getElementsByClassName("prioridadeGeral");
                    if (document.getElementById(className[iterator].id).value === elemento) {
                        switch (document.getElementById(className[iterator].id).id) {
                            case 'selPrioridadeDiasUteis':
                                fildSet = 'Dias �teis no Status';
                                break;
                            case 'selPrioridadeTipoProcesso':
                                fildSet = 'Tipo de Processo';
                                break;
                            case 'selPrioridadeStatus':
                                fildSet = 'Status';
                                break;
                            case 'selPrioridadeAtividade':
                                fildSet = 'Atividade';
                                break;
                            case 'selPrioridadeFila':
                                fildSet = 'Fila';
                                break;
                            case 'selPrioridadeDistribuicao':
                                fildSet = 'Prazo para Resposta';
                                break;
                        }
                        repetido.push(fildSet);
                    }
                }
            }
        })

        return repetido;
    }

    function criarSelectPrioridadeGeral() {
        var selectPrioridadeDistribuicao = document.getElementById("selPrioridadeDistribuicao");
        var selectPrioridadeFila = document.getElementById("selPrioridadeFila");
        var selectPrioridadeStatus = document.getElementById("selPrioridadeStatus");
        var selectPrioridadeAtividade = document.getElementById("selPrioridadeAtividade");
        var selectPrioridadeTipoProcesso = document.getElementById("selPrioridadeTipoProcesso");
        var selectPrioridadeDiasUteis = document.getElementById("selPrioridadeDiasUteis");

        selectPrioridadeDistribuicao.options[selectPrioridadeDistribuicao.options.length] = new Option('', null);
        selectPrioridadeFila.options[selectPrioridadeFila.options.length] = new Option('', null);
        selectPrioridadeStatus.options[selectPrioridadeStatus.options.length] = new Option('', null);
        selectPrioridadeAtividade.options[selectPrioridadeAtividade.options.length] = new Option('', null);
        selectPrioridadeTipoProcesso.options[selectPrioridadeTipoProcesso.options.length] = new Option('', null);
        selectPrioridadeDiasUteis.options[selectPrioridadeDiasUteis.options.length] = new Option('', null);
    }

    function OnSubmitForm() {
        var valido = utlValidarObrigatoriedade();

        controlarTabela('Fila');
        controlarTabela('Status');
        controlarTabela('Atividade');
        controlarTabela('TipoProcesso');

        if(!valido) {
            return false
        } else {
            var isvalido = validarCadastro();
            if(isvalido) {
                return true
            }
        }

        return false;
    }


<? if (0){
    ?></script><?
} ?>
