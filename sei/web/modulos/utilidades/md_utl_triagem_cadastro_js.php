<script type="text/javascript">

var objTabelaDinamicaAtividade = null;
var encAssociarFila            = '';
var isParametrizadoProcesso    = <?=$isJsTpProcParametrizado?>;
var msg46 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_46); ?>';
var msg47 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_47); ?>';
var msg10Padrao = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_10)?>';
var msg48 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_48); ?>';
var msg49 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_49, 'Atividade'); ?>';
var strAtvAnalisadas = document.getElementById('hdnAtividadesAnalisadas').value;
var arrAtvAnalisadas = new Array();
if( strAtvAnalisadas.length > 0 ){
    strAtvAnalisadas.split(',').forEach( it => arrAtvAnalisadas.push( it ) );
}

//Quando retriagem, recupera tipo de presenca e percentual desempenho do usuario para o qual o processo est� distribuido
var tpPresencaRef = document.getElementById('hndTpPresencaRef').value;
var percDsmpRef   = document.getElementById('hndPercDsmpRef').value;

function abrirModalRevisao() {
    infraAbrirJanelaModal('<?=$strLinkIniciarRevisao?>',1000,800);
}

function validarFormatoDataTriagem(obj){
    var validoFormato = infraValidarData(obj, false);
    if(!validoFormato){
        alert(msg46);
        obj.value = '';
    }

    if ( document.getElementById('txtPrazoResposta').value ) {
        var dataResposta = returnDateTime(document.getElementById('txtPrazoResposta').value, false);
        var dataAtual = new Date();
        dataAtual.setHours('00', '00', '00', '00');
        var valido = (dataResposta.getTime() >= dataAtual.getTime());

        if (!valido) {
            document.getElementById('txtPrazoResposta').value = '';
            document.getElementById('txtPrazoResposta').focus();
            alert(msg47);
            return false;
        }
    }
}

function inicializar(){
    var consultar = false;
    if ('<?=$_GET['acao']?>'=='md_utl_triagem_cadastrar'){
        document.getElementById('txtGrupoAtividade').focus();
    } else if ('<?=$_GET['acao']?>'=='md_utl_triagem_consultar'){
        consultar = true;
        infraDesabilitarCamposAreaDados();
        document.getElementById('btnFechar').focus();
    }

    carregarComponenteAtividade();
    carregarComponenteGrupoAtividade();
    inicializarTabelaDinamicaAtividade(consultar);
    carregarHiddenDominio();
}

function carregarHiddenDominio(){
    encAssociarFila = document.getElementById('hdnStaPermiteAssociarFila').value;
}

function inicializarTabelaDinamicaAtividade(consultar){
    if(consultar){
        objTabelaDinamicaAtividade = new infraTabelaDinamica('tbAtividade', 'hdnTbAtividade', false, false);
    }else {
        objTabelaDinamicaAtividade = new infraTabelaDinamica('tbAtividade', 'hdnTbAtividade', false, false);

        if (objTabelaDinamicaAtividade.hdn.value != '') {
            objTabelaDinamicaAtividade.recarregar();

            //acoes
            hdnLista = objTabelaDinamicaAtividade.hdn.value;
            arrhdnLista = hdnLista.split('�');

            //array
            if (arrhdnLista.length > 0) {
                for (i = 0; i < arrhdnLista.length; i++) {
                    var hdnListaTela = arrhdnLista[i].split('�');
                    for (j = 0; j < hdnListaTela.length; j++) {
                        var btnRemoverAtividade = "<img onclick=\"objTabelaDinamicaAtividade.removerAtividade('" +hdnListaTela[j]+ "')\"" + " title='Remover Item' alt='Remover Item' src='<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . "/remover.svg" ?>' class='infraImg'/> ";
                        objTabelaDinamicaAtividade.adicionarAcoes(hdnListaTela[j], btnRemoverAtividade);
                    }
                }
            }
        }
    }

    objTabelaDinamicaAtividade.gerarEfeitoTabela = true;

    objTabelaDinamicaAtividade.removerAtividade = function(id){
        var idAtv      = id.split('_'); // quebra em array o parametro => [indice , id da atividade]
        var bolRemover = true;

        // atividade clicada para excluir, se for uma que j� foi analisada, n�o permitir a remo��o, seguindo a regra ap�s o loop abaixo
        arrAtvAnalisadas.forEach( v => { if( v == idAtv[1] ) bolRemover = false; });

        <?php if ( !is_null( $idUsuarioFezAnalise ) && $idUsuarioFezAnalise == $idUsuarioDistrAnalise ): ?>
            if( !bolRemover ){
                alert('N�o � poss�vel remover Atividade analisada que est� em Corre��o de An�lise pelo mesmo Membro Respons�vel pela An�lise');
                return false;
            }
        <?php endif; ?>

        var row = objTabelaDinamicaAtividade.procuraLinha(id);

        if(row != null) {
            objTabelaDinamicaAtividade.removerLinha(row);
            controlarExibicaoEncaminhamento();
        }
    };

    objTabelaDinamicaAtividade.procuraLinha = function (id) {
        var qtd;
        var linha;
        qtd = document.getElementById('tbAtividade').rows.length;

        for (var i = 1; i < qtd; i++)
        {
            linha = document.getElementById('tbAtividade').rows[i];
            var valorLinha = $.trim(linha.cells[0].innerText);
            id = $.trim(id);
            if (valorLinha == id) {
                return i;
            }
        }
        return null;
    };

    objTabelaDinamicaAtividade.verificaExibicaoEncaminhamento = function (){
        var qtd = document.getElementById('tbAtividade').rows.length;
        var semAnalise = false;
        var comAnalise = false;

        for (var i = 1; i < qtd; i++)
        {
            var linha = document.getElementById('tbAtividade').rows[i];
            var valorAnalise = $.trim(linha.cells[4].innerText);

            if(valorAnalise == 'S'){
                comAnalise = true;
            }

            if(valorAnalise == 'N'){
                semAnalise = true;
            }
        }

        if((!semAnalise && comAnalise) || (comAnalise && semAnalise) || (!semAnalise && !comAnalise)) {
           return false;
        }

        return true;
    };

    objTabelaDinamicaAtividade.remover  = function(obj){
        var qtd = document.getElementById('tbAtividade').rows.length;
        if(qtd == 2){
            document.getElementById('divTbAtividade').style.display = 'none';
            controlarExibicaoEncaminhamento();
        }

        var valor = objTabelaDinamicaAtividade.somarTempoExecucao(3);

        // se o valor for composto somente por minutos soma o valor total dos minutos e caso tenha horas converte as horas em minutos para somar junto
        var arrValorCell = obj[3].split(" ");
        var valorText    = arrValorCell[0].toString();
        if(arrValorCell.length == 1){
            if(valorText.indexOf("h") == -1){
                valor = valor - (parseInt(arrValorCell) ? parseInt(arrValorCell) : 0);
            }else{
                var horas = parseInt(arrValorCell[0]);
                valor = valor - (horas * 60);
            }
        } else {
            var horas = parseInt(arrValorCell[0]);
            var min = parseInt(arrValorCell[1]);
            valor = valor - ((60 * horas) + min);
        }

       document.getElementById('lblVlTltAtividade').innerText = convertToHoursMins(valor);

       return true;
    }

    objTabelaDinamicaAtividade.somarAtividadeUE = function (posicao) {
        var linha;
        var valorTotal = 0;
        var qtdLinhas  = document.getElementById('tbAtividade').rows.length;

        for (i = 1; i < qtdLinhas; i++) {
            linha = document.getElementById('tbAtividade').rows[i];
            var valorText  = $.trim(linha.cells[posicao].innerText);
            var valorLinha = valorText != '' && valorText != 'undefined' ? parseInt(valorText) : 0;
            valorTotal += valorLinha;
        }

        return valorTotal;
    };

    objTabelaDinamicaAtividade.somarTempoExecucao = function (posicao) {
        var linha;
        var valorTotal = 0;
        var qtdLinhas  = document.getElementById('tbAtividade').rows.length;

        for (i = 1; i < qtdLinhas; i++) {
            linha = document.getElementById('tbAtividade').rows[i];
            var valorText  = $.trim(linha.cells[posicao].innerText);

            var arrValorCell = valorText.split(" ");
            // se o valor for composto somente por minutos soma o valor total dos minutos e caso tenha horas converte as horas em minutos para somar junto
            if(arrValorCell.length == 1){
                var valorLinha = valorText != '' && valorText != 'undefined' && valorText != 'min' ? parseInt(valorText) : 0;
                if(valorText.indexOf("h") != -1) {
                    valorLinha = parseInt(valorText) * 60;
                }
            } else {
                var horas = arrValorCell[0] != '' && arrValorCell[0] != 'undefined' ? parseInt(arrValorCell[0]) : 0;
                var min = arrValorCell[1] != '' && arrValorCell[1] != 'undefined' ? parseInt(arrValorCell[1]) : 0;
                var valorLinha = (60 * horas) + min;
            }

            valorTotal += valorLinha;
        }

        return valorTotal;
    };
    
}

function controlarExibicaoEncaminhamento(){
    var isExibeEnc = isParametrizadoProcesso == 1 ? objTabelaDinamicaAtividade.verificaExibicaoEncaminhamento() : false;
    var displayEnc = isExibeEnc ? '' : 'none';
    document.getElementById('divPrincipalEncaminhamento').style.display = displayEnc;
    if(<?= $tipoRevisao ?>  == "3") {
        document.getElementById('membroResponsavelAvaliacaoRow').style.display = "none";
    } else if(<?= $tipoRevisao ?>  == "1") {
        document.getElementById('membroResponsavelAvaliacaoRow').style.display = displayEnc;
    }  else {
        document.getElementById('membroResponsavelAvaliacaoRow').style.display = "none";
        var arrValTbAtividade       = $("#hdnTbAtividade").val().split('�');
        arrValTbAtividade.forEach(function(atividade) {
            var arrValTbAtividade2       = atividade.split('�');
            if(arrValTbAtividade2[8] == "true") {
                document.getElementById('membroResponsavelAvaliacaoRow').style.display = displayEnc;
            }
        });
    }

    if(isExibeEnc) {
        document.getElementById('selEncaminhamentoTriagem').setAttribute('utlCampoObrigatorio', 'o');
    }else{
        document.getElementById('selEncaminhamentoTriagem').removeAttribute('utlCampoObrigatorio');
        document.getElementById('selEncaminhamentoTriagem').value = '';
        document.getElementById('selFila').value = '';
        document.getElementById('divFila').style.display = 'none';
        document.getElementById('selFila').removeAttribute('utlCampoObrigatorio');
    }
}

function fechar() {
    location.href = "<?= $strLinkCancelar ?>";
}

function selecionarAtividade(){
    //objLupaAtividade.selecionar(850,500);
    infraAbrirJanelaModal('<?=$strLinkAtividadeSelecao?>',1000,650);
}


function carregarComponenteGrupoAtividade(){
    // ================= INICIO - JS para selecao de gestores =============================

    objAutoCompletarGrupoAtividade = new infraAjaxAutoCompletar('hdnIdGrupoAtividade','txtGrupoAtividade','<?=$strLinkAjaxGrupoAtividade?>');
    objAutoCompletarGrupoAtividade.limparCampo = true;
    objAutoCompletarGrupoAtividade.tamanhoMinimo = 3;
    objAutoCompletarGrupoAtividade.prepararExecucao = function(){
        validarAtividade();
        return 'palavras_pesquisa=' + document.getElementById('txtGrupoAtividade').value;
    };

    objAutoCompletarGrupoAtividade.processarResultado = function(id,descricao,complemento){
        
        if (id!=''){
            var options = document.getElementById('selGrupoAtividade').options;

            for(var i=0;i < options.length;i++){
                if (options[i].value == id){
                    var msg = setMensagemPersonalizada(msg10Padrao, ['Grupo de Atividade']);
                    alert(msg);
                    break;
                }
            }

            if (i==options.length){

                for(i=0;i < options.length;i++){
                    options[i].selected = false;
                }

                opt = infraSelectAdicionarOption(document.getElementById('selGrupoAtividade'), descricao ,id);
                objLupaGrupoAtividade.atualizar();
                opt.selected = true;
            }

            document.getElementById('txtGrupoAtividade').value = '';

        }
    };

    objLupaGrupoAtividade = new infraLupaSelect('selGrupoAtividade','hdnGrupoAtividade','<?=$strLinkGrupoAtividadeSelecao?>');

}

function getValuesSelected(select, selecionados) {
    var resultSelect = [];
    var resultNotSelect = [];
    var options = select.options;
    var opt;

    for (var i=0, iLen=options.length; i<iLen; i++) {
        opt = options[i];

        if (opt.selected) {
            resultSelect.push(opt.value);
        }else{
            resultNotSelect.push(opt.value);
        }
    }

    return (selecionados ? resultSelect : resultNotSelect);
}

function removerGrupoAtividade() {
    var idsRestantes = getValuesSelected(document.getElementById('selGrupoAtividade'), false);
    var possuiIdsAtv = document.getElementById('selAtividade').options.length > 0;

    if (idsRestantes.length > 0 && possuiIdsAtv) {
        realizarAjaxGrupoAtvAtividade(idsRestantes);
    }

    if(idsRestantes.length == 0){
        limparCamposDependentesTabela();
    }

    if(idsRestantes.length > 0 && !possuiIdsAtv){
        objLupaGrupoAtividade.remover();
    }
}


function realizarAjaxGrupoAtvAtividade(idsRestantes){

    var params = {
        idsGrupoAtividade : JSON.stringify(idsRestantes)
    };


   $.ajax({
        url : '<?=$strUrlAjaxValidarGrupoAtvAtividade ?>',
        type : 'POST',
         data : params, 
        dataType: 'XML',
        success: function(result){

            controlarOptionsAtividades(result);
        },
        error : function (e) {
            console.error('Erro ao validar Grupo de Atividade: ' + e.responseText);
        }
    });
}

function controlarOptionsAtividades(result){
    var selAtividade = document.getElementById('selAtividade').options;
    var removerAtv   = false;
    limparSelectedComponentes('selAtividade');

    for(var i = 0; i < selAtividade.length; i++){
        var value = selAtividade[i].value != '' ? ((selAtividade[i].value).split('_')[0]) : '';
        var id = 'idsAtividade' + value;
        var isId = $(result).find(id).text() != '';
        if(!isId){
            removerAtv = true;
            document.getElementById('selAtividade').options[i].selected = true;
        }
    }

    if(removerAtv) {
        objLupaAtividade.remover();
    }

    objLupaGrupoAtividade.remover();
}

function limparSelectedComponentes(select) {

    var objSelected = document.getElementById(select);
    var getOptions = objSelected.options.length;

    if (getOptions > 0) {
        for (var i = 0; i < getOptions; i++) {
            objSelected.options[i].selected = false;
        }
    }
}



function adicionarRegistroTabelaAtividade(){
    var arrAtividades = document.getElementById('selAtividade');

    if(arrAtividades.length == 0){
        alert(msg49);
        return false;
    }

    var hdnContador   = document.getElementById('hdnContadorTableAtv').value;
    var atvComAnalise = false;
    var atvSemAnalise = false;

    for (var i = 0; i < arrAtividades.length; i++) {       
        var idsAtividade       = arrAtividades[i].value;

        var nomeAtividadeTexto = arrAtividades[i].text;
        nomeAtividadeAux       = nomeAtividadeTexto.split('');
        var qtdCaracter        = nomeAtividadeAux.length;
        for(var j = qtdCaracter ; j > 1 ; j--){
            if(nomeAtividadeAux[j] == '(' ){
                nomeAtividadeTexto = nomeAtividadeTexto.substr( 0 , j - 1 );
                break;
            }
        }
        var nomeAtividade   = nomeAtividadeTexto;
        var hdnTbAtividade  = document.getElementById('hdnContadorTableAtv');
        var arrIdsAtv       = idsAtividade.split('_');
        var idMain          = hdnContador +'_'+ arrIdsAtv[0];
        hdnContador++;
        document.getElementById('hdnContadorTableAtv').value = hdnContador;
       
        var sinTipoAnalise  = arrIdsAtv[1] == 'S';
        var strTipoAnalise  = sinTipoAnalise ? 'Sim' : 'N�o';
        var vlAtvComAnalise = null;

        if(!sinTipoAnalise) {
            var sinAvaliacaoHabilitada  = arrIdsAtv[3] == 'S';
        }

        // Caso esteja Em Analise > Retriagem, verifica se a atividade eh ou nao para aplicar o percentual de desempenho
        <?php if( $objControleDsmpDTO->getStrStaAtendimentoDsmp() == 4  || 
                  $objControleDsmpDTO->getStrStaAtendimentoDsmp() == 10 || 
                  $objControleDsmpDTO->getStrStaAtendimentoDsmp() == 8 ){ 
        ?>
                if( sinTipoAnalise === true ){
                    if( tpPresencaRef != 'P' && arrIdsAtv[4] == 'N' ){                        
                        vlAtvComAnalise = retornaCalculoPercentual( arrIdsAtv[2] , percDsmpRef );
                    }else{
                        vlAtvComAnalise = arrIdsAtv[2];
                    }
                }

        <?php } else { ?>
            // Cadastro da Triagem
            vlAtvComAnalise = sinTipoAnalise ? arrIdsAtv[2] : '';

        <?php } ?>

        if(sinTipoAnalise){
            atvComAnalise = true;
        }else{
            atvSemAnalise = true;
        }

        var complexidade = arrIdsAtv.length > 3 ? ' ('+ arrIdsAtv[3] + ')' : '';

        /*
            No index 8, do array abaixo, sinaliza registro da data de execucao e o id da triagem x atividade
            mas como eh um novo item, o valor por default eh igual a _#_
        */

        idMain = idMain.trim();
        var arrLinha = [ idMain,
            arrIdsAtv[0],
            nomeAtividade + complexidade,
            convertToHoursMins(vlAtvComAnalise),
            arrIdsAtv[1],
            strTipoAnalise,
            arrIdsAtv[2],
            vlAtvComAnalise,
            '_#_'
            //sinAvaliacaoHabilitada,
        ]

        objTabelaDinamicaAtividade.adicionar(arrLinha);

        var btnRemoverAtividade = "<img onclick=\"objTabelaDinamicaAtividade.removerAtividade('" + idMain + "')\"" + " title='Remover Item' alt='Remover Item' src='<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . "/remover.svg" ?>' class='infraImg'/> ";
        objTabelaDinamicaAtividade.adicionarAcoes(idMain, btnRemoverAtividade, false, false);

    }

    document.getElementById('divTbAtividade').style.display = '';
    var valorAtividadeUe = convertToHoursMins(objTabelaDinamicaAtividade.somarTempoExecucao(3));
    document.getElementById('lblVlTltAtividade').innerText = valorAtividadeUe;

    var vlAtividadeTotal = objTabelaDinamicaAtividade.somarAtividadeUE(6);
    document.getElementById('hdnTmpExecucao').value = vlAtividadeTotal;

    limparCamposDependentesTabela();
    controlarExibicaoEncaminhamento();
}

function convertToHoursMins(time) {

    hours = Math.trunc(time / 60);
    minutes = (time % 60);
    if (time == 0 ) {
        format = '0min';
    } else {
        if (time < 60) {
            format = minutes + 'min';
        } else {
            if(minutes == 0)
                format = hours + 'h';
            else
                format = hours + 'h ' + minutes + 'min';
        }
    }

    return format;
}

function controlarExibicaoFila(obj){
    var isAssociacao = obj.value == encAssociarFila;
    var valorDisplay = isAssociacao ? '' : 'none';
    var elem = document.getElementById('selFila');

    document.getElementById('divFila').style.display = valorDisplay;
    
    if(isAssociacao) {
        $( elem ).attr('utlCampoObrigatorio', 'a');
    }else{        
        $( elem ).removeAttr('utlCampoObrigatorio').val('');
        desmarcarCkbDistAutoParaMim();
    }
}

function limparCamposDependentesTabela(){
    var isAtividade = document.getElementById('selAtividade').length > 0
    var isGrupoAtv  = document.getElementById('selGrupoAtividade').length > 0
    //Limpando Atividades
    if(isAtividade) {
        limparAtividade();
    }

    if(isGrupoAtv) {
        limparGrupoAtividade();
    }
}
function limparAtividade(){
    var selAtividade =  document.getElementById('selAtividade');
    for(var i = 0; i < selAtividade.length; i++){
        selAtividade.options[i].selected = true;
    }

    objLupaAtividade.remover();
}

function limparGrupoAtividade(){
    //Limpando Grupo de Ativdade
    var selGrupoAtividade =  document.getElementById('selGrupoAtividade');
    if(selGrupoAtividade.length > 0) {
        for (var i = 0; i < selGrupoAtividade.length; i++) {
            selGrupoAtividade.options[i].selected = true;
        }

        objLupaGrupoAtividade.remover();
    }
}

function carregarComponenteAtividade(){
    // ================= INICIO - JS para selecao de gestores =============================

    objAutoCompletarAtividade = new infraAjaxAutoCompletar('hdnIdAtividade','txtAtividade','<?=$strLinkAjaxAtividade?>');
    objAutoCompletarAtividade.limparCampo = true;
    objAutoCompletarAtividade.tamanhoMinimo = 3;
    objAutoCompletarAtividade.prepararExecucao = function(){
        var idsGrupoAtividade = document.getElementById('hdnGrupoAtividade').value;
        if(idsGrupoAtividade != '') {
            return 'palavras_pesquisa='+document.getElementById('txtAtividade').value + '&ids_grupo_atv=' + idsGrupoAtividade;
        }else{
            return 'palavras_pesquisa='+document.getElementById('txtAtividade').value;
        }
    };

    objAutoCompletarAtividade.processarResultado = function(id,descricao,complemento){

        if (id!=''){
            var options = document.getElementById('selAtividade').options;

            for(var i=0;i < options.length;i++){
                if (options[i].value == id){
                    var msg = setMensagemPersonalizada(msg10Padrao, ['Atividade']);
                    alert(msg);
                    break;
                }
            }

            if (i==options.length){

                for(i=0;i < options.length;i++){
                    options[i].selected = false;
                }

                opt = infraSelectAdicionarOption(document.getElementById('selAtividade'), descricao ,id);
                objLupaAtividade.atualizar();
                opt.selected = true;
            }

            document.getElementById('txtAtividade').value = '';
            document.getElementById('txtAtividade').focus();

        }
    };

    objLupaAtividade = new infraLupaSelect('selAtividade','hdnAtividade','<?=$strLinkAtividadeSelecao?>');

}

function validarTipoAnaliseAtividade(){
    var tabelaAtv = document.getElementById('tbAtividade');
    var valorInicial = '';

    for (var i = 0; i < tabelaAtv.rows.length; i++) {
        var linha      = tabelaAtv.rows[i];
        var valorText  = $.trim(linha.cells[4].innerText);

        if(valorInicial != '' && valorInicial != valorText){
            return false;
        }

        valorInicial = valorText;
    }

    return true;
}

function onSubmitForm(){
   var valido =  true;
   var isRetriagem = document.getElementById('hdnIdRetriagem').value;

   valido = utlValidarObrigatoriedade();

   if(valido){
       if(!validarTipoAnaliseAtividade()){
            alert(msg48);
            return false;
       }

       var txtInfoComplementar = document.getElementById('txaInformacaoComplementar');
       if( !validaQtdCaracteres(txtInfoComplementar,500) ){
           alert("<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_06, array('Informao Complementar', '500'))?>");
           txtInfoComplementar.focus();
           return false;
       }

       if(isRetriagem){
            var isAnalise = document.getElementById('tbAtividade').rows[1].cells[4].innerText;
            if(isAnalise == 'N'){
                if(!confirm('As atividade selecionadas na Retriagem s�o do Tipo sem An�lise, portanto o processo n�o est� mais em An�lise. Confirma a Retriagem?')){
                    return false;
                }
            }
        }

        bloquearBotaoSalvar();

        document.getElementById('hdnIsPossuiAnalise').value = document.getElementById('tbAtividade').rows[1].cells[4].innerText;
        var vlAtividadeTotal = objTabelaDinamicaAtividade.somarAtividadeUE(6);
        document.getElementById('hdnTmpExecucao').value = vlAtividadeTotal;

        var nomeFila      = document.getElementById('selFila').options[document.getElementById('selFila').selectedIndex].innerText;
        document.getElementById('hdnSelFila').value = nomeFila.trim();
    }

    return valido;
}

function validarAtividade(){
    var isGrAtividade = document.getElementById('selGrupoAtividade').length == 0;
    var isAtividade   = document.getElementById('selAtividade').length > 0

    if(isAtividade && isGrAtividade) {
        limparAtividade();
    }
}

function abrirGrupoAtividade(){
    validarAtividade();
    //objLupaGrupoAtividade.selecionar(700, 500);
    infraAbrirJanela('<?=$strLinkGrupoAtividadeSelecao?>','Selecionar Grupo de Atividade',900,650);
}
function preencherNomeHidden() {
    $("#hdnNomeMembroResponsavelAvaliacao").val($("#selUsuarioResponsavelAvaliacao option:selected").html());
}
</script>