<script type="text/javascript">

    var objLupaTpProcesso = null;
    var objAutoCompletarTpProcesso = null;
    var idMdUtlAdmPrmGrUsu = null;
    var objLupaUsuario  = null;
    var objAutoCompletarUsuario = null;
    var objTabelaDinamicaUsuario= null;
    var bolFatorDesempenho = false;
    var bolFatorReducao = false;
    var isBolAlterar = false;
    var heigthTamanhoDivAreaPart = null ;
    var msg11 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11); ?>';
    var msg14 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_14); ?>';
    var msg11Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11); ?>';
    var msg12 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_12, 'Tipo de Processo'); ?>';
    var msg13 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_13, 'Usuário Participante'); ?>';
    var msg14 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_14); ?>';
    var msg10Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_10); ?>';
    var msg15Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_15); ?>';
    var msg98Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_98); ?>';

    function carregarComponenteUsuario(){
        // ================= INICIO - JS para selecao de usuarios participantes =============================
        objLupaUsuario = new infraLupaText('txtUsuario','hdnIdUsuario','<?=$strLinkUsuarioSelecao?>');

        objAutoCompletarUsuario = new infraAjaxAutoCompletar('hdnIdUsuario','txtUsuario','<?=$strLinkAjaxUsuario?>');
        objAutoCompletarUsuario.limparCampo = true;
        objAutoCompletarUsuario.tamanhoMinimo = 3;

        objAutoCompletarUsuario.prepararExecucao = function(){
            return 'palavras_pesquisa='+document.getElementById('txtUsuario').value;
        };

        objAutoCompletarUsuario.processarResultado = function(id,descricao,complemento){

            if ( id != '' ){
                document.getElementById('hdnIdUsuario').value = id;
                document.getElementById('txtUsuario').value   = descricao;
                document.getElementById('txtUsuario').focus();
            }
        };

        objLupaUsuario.finalizarSelecao = function(){
            objAutoCompletarUsuario.selecionar(document.getElementById('hdnIdUsuario').value,document.getElementById('txtUsuario').value);
            let nomeAux = document.getElementById('txtUsuario').value;
            nomeAux = nomeAux.split(' - ');
            document.getElementById('txtUsuario').value = nomeAux[1] +' ('+ nomeAux[0] +')';
        }

        objLupaUsuario.processarRemocao = function () {
            limparCamposControleParticipante();
            return true;
        }
    }

    function carregarComponenteTpProcesso(){
        // ================= INICIO - JS para selecao de Tipo Processo =============================

        objAutoCompletarTpProcesso = new infraAjaxAutoCompletar('hdnIdTpProcesso','txtTpProcesso','<?=$strLinkAjaxTipoProcesso?>');
        objAutoCompletarTpProcesso.limparCampo = true;

        objAutoCompletarTpProcesso.prepararExecucao = function(){
            return 'palavras_pesquisa='+document.getElementById('txtTpProcesso').value;
        };

        objAutoCompletarTpProcesso.tamanhoMinimo = 3;
        objAutoCompletarTpProcesso.processarResultado = function(id,descricao,complemento){

            if (id!=''){
                var options = document.getElementById('selTpProcesso').options;

                for(var i=0;i < options.length;i++){
                    if (options[i].value == id){
                        var msg = setMensagemPersonalizada(msg10Padrao, ['Tipo de Processo']);
                        alert(msg);
                        break;
                    }
                }

                if (i==options.length){

                    for(i=0;i < options.length;i++){
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selTpProcesso'), descricao ,id);
                    objLupaTpProcesso.atualizar();
                    opt.selected = true;
                }

                document.getElementById('txtTpProcesso').value = '';
                document.getElementById('txtTpProcesso').focus();

            }
        };

        objLupaTpProcesso = new infraLupaSelect('selTpProcesso','hdnTpProcesso','<?=$strLinkTipoProcessoSelecao?>');
    }

    function inicializar() {
        if ('<?=$_GET['acao']?>' == 'md_utl_adm_prm_gr_cadastrar') {
            document.getElementById('txtCargaPadrao').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_utl_adm_prm_gr_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }

        $('input').on('drop', function() {
            return false;
        });

        infraEfeitoTabelas(true);
        carregarComponenteTpProcesso();
        carregarComponenteUsuario();
        iniciarTabelaDinamicaUsuario();

        //heigthTamanhoDivAreaPart= parseInt(infraGetElementById('divInfraAreaDados1').style.height.split('em').join(''));

        objLupaTpProcesso.procuraLinha = function (id) {
            var qtd;
            var linha;
            qtd = objLupaTpProcesso.sel.length;
            for (i = 1; i < qtd; i++) {
                linha = objLupaTpProcesso.sel[i].value;
                if (linha == id) {
                    return i;
                }
            }
            return null;
        };

        objLupaTpProcesso.removerLinha = function (index) {
            var listaTpProcesso = document.getElementById('selTpProcesso');
            listaTpProcesso.remove(index);
            objLupaTpProcesso.atualizar();
        }

        // Sobrescrevendo o método para remover verificando vinculo
        objLupaTpProcesso.processarRemocao = function (valor) {
            verificarVinculoTpProcesso(valor[0].value);
        };
    }

    function validarCadastro() {
        var dtaCorte    = document.getElementById('txtDtCorte').value;
        var cargaPadrao = document.getElementById('txtCargaPadrao').value;
        var tpProcesso  = document.getElementById('hdnTpProcesso').value;
        var tbUsuario   = document.getElementById('tbUsuario');
        var cargaPadrao        = document.getElementById('txtCargaPadrao').value;
        var tpProcesso         = document.getElementById('hdnTpProcesso').value;
        var retornoUltFila     = document.getElementById('selRetorno').selectedIndex;
        var tbUsuario          = document.getElementById('tbUsuario');
        var selDilacao         = document.getElementById('selDilacao').selectedIndex;
        var selSuspensao       = document.getElementById('selSuspensao').selectedIndex;
        var iptPrzSuspensao    = document.getElementById('przSuspensao').value;
        var selInterrupcao     = document.getElementById('selInterrupcao').selectedIndex;
        var iptPrzInterrupcao  = document.getElementById('przInterrupcao').value;
        var przSuspensao       = document.getElementById('przSuspensao').value;
        var przInterrupcao     = document.getElementById('przInterrupcao').value;
        var msg = '';
        var selFrequencia = document.getElementById('selStaFrequencia').value;
        var inicioPeriodo = document.getElementById('selInicioPeriodo').value;

        if(dtaCorte == ''){
            var msg = setMensagemPersonalizada(msg11, ['Data de Corte']);
            alert(msg);
            document.getElementById('txtDtCorte').focus();
            return false;
        }

        if(cargaPadrao == '' || cargaPadrao <1 ){
            var msg = setMensagemPersonalizada(msg11, ['Carga Padrão Diária (em minutos)']);
            alert(msg);
            document.getElementById('txtCargaPadrao').focus();
            return false;
        }

        if(selFrequencia == 0){
            var msg = setMensagemPersonalizada(msg11, ['Frequência de distribuição']);
            alert(msg);
            document.getElementById('selStaFrequencia').focus();
            return false;
        }

        if(inicioPeriodo == '' || inicioPeriodo == 0){
            var msg = setMensagemPersonalizada(msg11, ['Início do Período']);
            alert(msg);
            document.getElementById('selInicioPeriodo').focus();
            return false;
        }

        if(retornoUltFila == ''){
            msg = setMensagemPersonalizada(msg11Padrao, ['Retorno para Última Fila ']);
            alert(msg);
            document.getElementById('selRetorno').focus();
            return false;
        }

        if(tpProcesso == '' ){

            alert(msg12);
            document.getElementById('txtTpProcesso').focus();
            return false;
        }

        if(selDilacao == ''){
            msg = setMensagemPersonalizada(msg11Padrao, ['Resposta Tácita para Dilação de Prazo']);
            alert(msg);
            document.getElementById('selDilacao').focus();
            return false;
        }

        if(selSuspensao == ''){
            msg = setMensagemPersonalizada(msg11Padrao, ['Resposta Tácita para Suspensão de Prazo']);
            alert(msg);
            document.getElementById('selSuspensao').focus();
            return false;
        }

        if(selInterrupcao == ''){
            msg = setMensagemPersonalizada(msg11Padrao, ['Resposta Tácita para Interrupção de Prazo']);
            alert(msg);
            document.getElementById('selInterrupcao').focus();
            return false;
        }

        if(iptPrzSuspensao == ''){
            msg = setMensagemPersonalizada(msg11Padrao, ['Prazo máximo de Suspensão']);
            alert(msg);
            document.getElementById('przSuspensao').focus();
            return false;
        }

        if(iptPrzInterrupcao == ''){
            msg = setMensagemPersonalizada(msg11Padrao, ['Prazo máximo de Interrupção']);
            alert(msg);
            document.getElementById('przInterrupcao').focus();
            return false;
        }

        if(przSuspensao == 0){
            msg = setMensagemPersonalizada(msg15Padrao, ['Prazo máximo de Suspensão']);
            alert(msg);
            return false;
        }

        if(przInterrupcao == 0){
            msg = setMensagemPersonalizada(msg15Padrao, ['Prazo máximo de Interrupção']);
            alert(msg);
            return false;
        }

        if(tbUsuario.rows.length == 1){
            alert(msg13);
            document.getElementById('txtUsuario').focus();
            return false;
        }

        return true;
    }

    function onSubmitForm() {
        if(validarCadastro()){
            document.getElementById("frmMdUtlAdmPrmGrCadastro").submit();
            return true;
        }
    }


    function verificarVinculoUsuario(idUsuario,idVinculo){
        //se idVinculo = 0, novo usuario não cadastrado ainda , que foi excluido
        if(idVinculo != 0) {

            $.ajax({
                type: "POST",
                url: "<?= $strLinkAjaxVincUsuFila?>",
                //dataType: "json",
                dataType: "xml",
                data: {
                    idUsuario: idUsuario,
                    idVinculo: idVinculo
                },
                success: function (result) {
                    var valido = $(result).find('sucesso').text();
                    if (valido == 1) {
                        var msg = $(result).find('msg').text();
                        alert(msg);
                        return false;
                    } else {
                        if(isBolAlterar) {
                            var usuario = infraGetElementById('hdnUsuario').value;
                            usuario = usuario.split('±')[0];

                            if(usuario ==idUsuario){
                                limparCamposControleParticipante();
                                //infraGetElementById('selUsuario').disabled = false;
                                infraGetElementById('txtUsuario').disabled = false;
                                infraGetElementById('divOpcoesUsuario').hidden = false;
                                objTabelaDinamicaUsuario.flagAlterar = false;
                                isBolAlterar = false;
                            }
                        }

                        //objTabelaDinamicaUsuario.atualizaHdn();
                        //var linha = objTabelaDinamicaUsuario.procuraLinha(idUsuario);
                        //objTabelaDinamicaUsuario.controlarFluxoUsuarioPart(idUsuario);

                        var row = objTabelaDinamicaUsuario.procuraLinha(idUsuario);
                        objTabelaDinamicaUsuario.removerLinha(row);

                        verificaTabela(1);
                    }
                },
                error: function (msgError) {
                    msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                }
            });

        }else{

            var linha = objTabelaDinamicaUsuario.procuraLinha(idUsuario);
            objTabelaDinamicaUsuario.removerLinha(linha);          
            atualizarUsuariosHndTabela( idUsuario );
            verificaTabela(1);
        }
    }

    function iniciarTabelaDinamicaUsuario() {
        objTabelaDinamicaUsuario = new infraTabelaDinamica('tbUsuario', 'hdnTbUsuario');
        objTabelaDinamicaUsuario.gerarEfeitoTabela = true;

        if (objTabelaDinamicaUsuario.hdn.value != '') {
            objTabelaDinamicaUsuario.recarregar();

            //acoes
            hdnListaUsuariosPart = objTabelaDinamicaUsuario.hdn.value;
            arrListaUsuariosPart = hdnListaUsuariosPart.split('¥');

            var pathIconeAlt = "<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg' ?>";
            var pathIconeExc = "<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg' ?>";

            //array
            if (arrListaUsuariosPart.length > 0) {
                for (i = 0; i < arrListaUsuariosPart.length; i++) {

                    hdnListaUsuPart = arrListaUsuariosPart[i].split('±');

                    var btnAlterar = "<img onclick=\"editarUsuarioPart("+hdnListaUsuPart[0] +","+hdnListaUsuPart[9]+")\""+" title='Alterar Usuário Participante' alt='Alterar Usuário Participante' src='"+pathIconeAlt+"' class='infraImg'/> ";
                    var btnRemover = "<img onclick=\"verificarVinculoUsuario(" + hdnListaUsuPart[0] + ","+hdnListaUsuPart[9]+")\""+" title='Remover Usuário Participante' alt='Remover Usuário Participante' src='"+pathIconeExc+"' class='infraImg'/> ";

                    objTabelaDinamicaUsuario.adicionarAcoes(hdnListaUsuPart[0], btnAlterar + btnRemover , false, false);

                }
            } else {
                hdnListaUsuPart = hdnListaUsuariosPart.split('±');
                var btnAlterar = "<img onclick=\"editarUsuarioPart(" + hdnListaUsuPart[0] + ","+hdnListaUsuPart[9]+")\""+" title='Alterar Usuário Participante' alt='Alterar Usuário Participante' src='"+pathIconeAlt+"' class='infraImg'/> ";
                var btnRemover = "<img onclick=\"verificarVinculoUsuario(" + hdnListaUsuPart[0] + ","+hdnListaUsuPart[9]+")\""+" title='Remover Usuário Participante' alt='Remover Usuário Participante' src='"+pathIconeExc+"' class='infraImg'/> ";

                objTabelaDinamicaUsuario.adicionarAcoes(hdnListaUsuPart[0], btnAlterar + btnRemover , false, false);
                objTabelaDinamicaUsuario.adicionarAcoes(hdnListaUsuPart[0], "<img onclick=\"editarUsuarioPart(" + hdnListaUsuPart[0] + ","+hdnListaUsuPart[9]+")\" title='Alterar Usuário Participante' alt='Alterar Usuário Participante' src='"+pathIconeAlt+"' class='infraImg'/>", false, false);

            }
        }

        objTabelaDinamicaUsuario.alterar=function(id){
            editarUsuarioPart(id[0],id[9]);
        };


        objTabelaDinamicaUsuario.existeIdUsuario = function (id) {
            var qtd;
            var linha;
            qtd = document.getElementById('tbUsuario').rows.length;

            for (i = 1; i < qtd; i++) {
                linha = document.getElementById('tbUsuario').rows[i];
                var valorLinha = $.trim(linha.cells[0].innerText);
                id = $.trim(id);

                if (valorLinha == id) {
                    return true;
                }
            }
            return false;
        };

        objTabelaDinamicaUsuario.procuraLinha = function (id) {

            var qtd;
            var linha;
            qtd = document.getElementById('tbUsuario').rows.length;

            for (i = 1; i < qtd; i++) {
                linha = document.getElementById('tbUsuario').rows[i];
                var valorLinha = $.trim(linha.cells[0].innerText);
                if (valorLinha == id) {
                   return i;
                }

            }
            return null;
        };

        objTabelaDinamicaUsuario.controlarFluxoUsuarioPart = function (idUsuario){

            var hdnTbUsuario = infraGetElementById('hdnTbUsuario').value;
            var hdnTbUsuarioRemove = infraGetElementById('hdnTbUsuarioRemove').value;

            var arrRegistros = hdnTbUsuario.split('¥');
            var usuarioRemove = "";

            for(var i=0; i< arrRegistros.length ; i++){

                var dadosRegistro = arrRegistros[i].split('±');

                if(dadosRegistro[0] == idUsuario){
                    if(hdnTbUsuarioRemove != "") {
                        hdnTbUsuarioRemove = hdnTbUsuarioRemove +'¥' + arrRegistros[i];
                       // usuarioRemove = '¥' + arrRegistros[i];

                    }else{
                        hdnTbUsuarioRemove = hdnTbUsuarioRemove + arrRegistros[i];
                        //usuarioRemove = '¥' + arrRegistros[i];
                    }
                    usuarioRemove = arrRegistros[i];
                }

            }

            var tbUsuario = infraGetElementById('hdnTbUsuario').value;
            var arrUsuario = tbUsuario.split('¥');

            // remove os usuarios q foram excluidos da hdnTbUsuario
            var index = arrUsuario.indexOf(usuarioRemove);
            if(index !== -1){
                arrUsuario.splice(index,1);
            }

            infraGetElementById('hdnTbUsuario').value = arrUsuario;
            infraGetElementById('hdnTbUsuarioRemove').value = hdnTbUsuarioRemove;
        };

        //debug();
    }

    function editarUsuarioPart(idUsuario,idVinculo){

        objTabelaDinamicaUsuario.flagAlterar = true;
        var dadosUsuario = null;

        //acoes
        var hdnListaUsuariosPart = objTabelaDinamicaUsuario.hdn.value;
        var arrListaUsuariosPart = hdnListaUsuariosPart.split('¥');

        for (i = 0; i < arrListaUsuariosPart.length; i++) {

           var hdnListaUsuPart = arrListaUsuariosPart[i].split('±');

           if(hdnListaUsuPart[0] == idUsuario){
               dadosUsuario =hdnListaUsuPart; break;
           }
        }

        document.getElementById('txtUsuario').value = dadosUsuario[10];

        document.getElementById('hdnIdUsuario').value = dadosUsuario[0];
        
        document.getElementById('selTpPresenca').value = dadosUsuario[3];

        document.getElementById('txtPlanoTrabalho').value = removerTags( dadosUsuario[4] );
        
        // Remove o simbolo de porcentagem com o split
        document.getElementById('txtFtDesemp').value = dadosUsuario[5] != "null"?dadosUsuario[5].split('%')[0] : "";
        
        // ftDesemp = ftDesemp != '' ? ftDesemp+'%':'';
        document.getElementById('selTpJornada').value = dadosUsuario[7] ;
        
        // Remove o simbolo de porcentagem com o split
        document.getElementById('txtFtReduc').value = dadosUsuario[8]!= "null" ?dadosUsuario[8].split('%')[0] : "";

        if(dadosUsuario[3] == 'D') {
            document.getElementById('divFtDesemp').style.display = 'inline-block';
            bolFatorDesempenho = true;
        }
        if(dadosUsuario[7] == 'R') {
            document.getElementById('divRedJornada').style.display = 'inline-block';
            bolFatorReducao = true;
        }

        infraGetElementById('txtUsuario').disabled = true;
        infraGetElementById('divOpcoesUsuario').hidden = true;

        if(idVinculo!= 0) idMdUtlAdmPrmGrUsu = idVinculo;

        objAutoCompletarUsuario.processarResultado(idUsuario,dadosUsuario[10]);

        isBolAlterar = true;

        scrollTela('blocoUsuario' , 0);
    }

    function validarFatorObrigatorio(){

        var indexPresenca = document.getElementById('selTpPresenca').selectedIndex;
        var indexTpJornada = document.getElementById('selTpJornada').selectedIndex;

        var usuarioParticipante  = document.getElementById('hdnIdUsuario').value;

        if(usuarioParticipante == ''){
            var msg = setMensagemPersonalizada(msg11Padrao, ['Usuário Participante']);
            alert(msg);
            document.getElementById('txtUsuario').focus();
            return false;
        }

        if(indexPresenca == 0){
            var msg = setMensagemPersonalizada(msg11Padrao, ['Tipo de Presença']);
            alert(msg);
            document.getElementById('selTpPresenca').focus();
            return false;
        }

        if(indexTpJornada == 0){
            var msg = setMensagemPersonalizada(msg11Padrao, ['Tipo de Jornada']);
            alert(msg);
            document.getElementById('selTpJornada').focus();
            return false;
        }

        if(bolFatorDesempenho){
            var ftDesempenho = document.getElementById('txtFtDesemp').value;
            if(ftDesempenho == '' || ftDesempenho== 0){
                var msg = setMensagemPersonalizada(msg11Padrao, ['Fator de Desempenho Diferenciado']);
                alert(msg);
                document.getElementById('txtFtDesemp').focus();
                return false;
            }
        }

        if(bolFatorReducao){
            var ftReducao = document.getElementById('txtFtReduc').value;
            if(ftReducao == '' || ftReducao== 0){
                var msg = setMensagemPersonalizada(msg11Padrao, ['Fator de Redução de Jornada']);
                alert(msg);
                document.getElementById('txtFtReduc').focus();
                return false;
            }
        }

        return true;
    }

    function buscarNomeUsuario(){
        var planoTrab = document.getElementById('txtPlanoTrabalho');

        if( planoTrab.value != '' ) {
            var valid = validaPlanoTrabalho( document.getElementById('txtPlanoTrabalho') );        
            if( !valid[0] ) return false;
            planoTrab = valid[1];
        }else{
            planoTrab = null;
        }

        var arrIds = new Array( document.getElementById('hdnIdUsuario').value );

        var params = {
            arrIdsVinculo: arrIds
        };

        $.ajax({
            url: '<?= $strUrlBuscarNomesUsuario ?>',
            type: 'POST',
            data: params,
            dataType: 'XML',
            success: function (r) {
                adicionarRegistroTabelaUsuario(r,planoTrab);
            },
            error: function (e) {
                console.error('Erro ao buscar o nome do usuário: ' + e.responseText);
            }
        });
    }

    
    function adicionarRegistroTabelaUsuario(retornoAjax,linkNumSei){
        var msg ='';

        if(isBolAlterar){
            msg ='1';
        }else {
          msg  = validarDuplicidade();
        }
        if(msg!='') {
            if(validarFatorObrigatorio()) {
                var arrUsuarios = new Array( document.getElementById('hdnIdUsuario').value ); //document.getElementById('selUsuario').options;

                var indexPresenca = document.getElementById('selTpPresenca').selectedIndex;
                var valPresenca = document.getElementById('selTpPresenca').value;
                var txtPresenca = document.getElementById('selTpPresenca').options[indexPresenca].text;

                var ftDesemp = "";
                if(valPresenca == 'D') {
                    ftDesemp = infraGetElementById('txtFtDesemp').value;
                    ftDesemp = ftDesemp != '' ? ftDesemp + '%' : '';
                }

                var indexTpJornada = document.getElementById('selTpJornada').selectedIndex;
                var valTpJornada = document.getElementById('selTpJornada').value;
                var txtTpJornada = document.getElementById('selTpJornada').options[indexTpJornada].text;
                var numSeiPlanoTrab = '';
                var planoTrab       = '';

                if( linkNumSei !== null ){
                    numSeiPlanoTrab = document.getElementById('txtPlanoTrabalho').value;
                    planoTrab       = '<a alt="'+numSeiPlanoTrab+'" href="'+linkNumSei+'" target="_blank" style="text-decoration: underline;">'+numSeiPlanoTrab+'</a>';
                }

                var ftReduc = "";
                if(valTpJornada == 'R') {
                    ftReduc = document.getElementById('txtFtReduc').value;
                    ftReduc = ftReduc != '' ? ftReduc + '%' : '';
                }



                for (var i = 0; i < arrUsuarios.length; i++) {
                    var idUsuario = arrUsuarios[i];

                    var nomeCampAjx = 'IdUsuario' + idUsuario;
                    var htmlNomeUsu = '<div style="text-align:center;">'+$(retornoAjax).find(nomeCampAjx).text()+'</div>';                    
                    var nomeSigla   = $.trim(document.getElementById('txtUsuario').value);

                    var arrLinha = [
                        idUsuario,
                        "",
                        txtPresenca,
                        valPresenca,
                        planoTrab,
                        ftDesemp,
                        txtTpJornada,
                        valTpJornada,
                        ftReduc,
                        idMdUtlAdmPrmGrUsu,
                        nomeSigla
                    ];


                    //Manipula o tamanho dinamico da tabela
                    if(!isBolAlterar){
                        var tbUsuario = document.getElementById('tbUsuario').rows.length == 1;
                        if(tbUsuario) {
                            document.getElementById('tbUsuario').style.display = 'table';
                            heigthTamanhoDivAreaPart +=8;
                        }else{
                            heigthTamanhoDivAreaPart +=2;
                        }

                    }

                    if(isBolAlterar){

                        var row = objTabelaDinamicaUsuario.procuraLinha(idUsuario);
                        objTabelaDinamicaUsuario.removerLinha(row);
                    
                        isBolAlterar = false;
                        flag=false;

                        infraGetElementById('txtUsuario').disabled = false;
                        infraGetElementById('divOpcoesUsuario').hidden = false;

                        objTabelaDinamicaUsuario.flagAlterar = false;
                    }

                    objTabelaDinamicaUsuario.adicionar(arrLinha);

                    var pathIconeAlt = "<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg' ?>";
                    var pathIconeExc = "<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg' ?>";

                    var btnAlterar = "<img onclick=\"editarUsuarioPart(" + idUsuario + "," + 0 + ")\"" + " title='Alterar Usuário Participante' alt='Alterar Usuário Participante' src='"+pathIconeAlt+"' class='infraImg'/> ";
                    var btnRemover = "<img onclick=\"verificarVinculoUsuario(" + idUsuario + "," + 0 + ")\"" + " title='Remover Usuário Participante' alt='Remover Usuário Participante' src='"+pathIconeExc+"' class='infraImg'/> ";

                    objTabelaDinamicaUsuario.adicionarAcoes(idUsuario,btnAlterar + btnRemover, false, false);

                    //Corrrigindo o problema do core do Sei que não aceita HTML para alteração (função remover XML)
                    var row = objTabelaDinamicaUsuario.procuraLinha(idUsuario);

                    document.getElementById('tbUsuario').rows[row].cells[1].innerHTML = htmlNomeUsu;
                    //infraGetElementById('divInfraAreaDados1').style.height=heigthTamanhoDivAreaPart+'em';
                }

                limparCamposControleParticipante();                   
            }
        }
        //debug();
    }

    function limparCamposControleParticipante(){

        var blocoControleParticipante = document.getElementById('blocoUsuario');

        // limpa todos os inputs do tipo text
        var inputs = blocoControleParticipante.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].type != 'checkbox' && inputs[i].type != 'radio') {
                inputs[i].value = '';
            }
        }

        // limpa todos os elementos do tipo select
        var selects = blocoControleParticipante.getElementsByTagName('select');

        for (i = 0; i < selects.length; i++) {
            var options = selects[i].querySelectorAll('option');
            if (options.length > 0) {
                selects[i].value = options[0].value;
            }
        }

        // remove os campos de fatores
        if(bolFatorDesempenho) {
            document.getElementById('divFtDesemp').style.display = 'none';
            bolFatorDesempenho = false;
        }

        if(bolFatorReducao) {
            document.getElementById('divRedJornada').style.display = 'none';
            bolFatorReducao = false;
        }
    }

    function verificaTabela(qtdLinha) {

        var tbUsuario = document.getElementById('tbUsuario');
        var ultimoRegistro = tbUsuario.rows.length == qtdLinha;

        if (ultimoRegistro) {
            document.getElementById('tbUsuario').style.display = 'none';
            heigthTamanhoDivAreaPart -=8;
        }else{
            heigthTamanhoDivAreaPart -=1.8;
        }
    }

    function validarDuplicidade(){
        var arrUsuarios = new Array( document.getElementById('hdnIdUsuario').value );
        var msg         = '';

        for (var i = 0; i < arrUsuarios.length; i++) {
            if(objTabelaDinamicaUsuario.existeIdUsuario(arrUsuarios[i])){
                valido = false;
                msg += '-'+ arrUsuarios[i].innerHTML.split(' ')[0];
                msg += '\n';
            }
        }

        var msgFim = msg14 + '\n';

        if(msg != ''){
            msgFim += msg;
            alert(msgFim);
        }

        return msg == '';
    };

    function validarPercentual(obj,campo){

        if(obj.value > 100 ){

            var msg = setMensagemPersonalizada(msg98Padrao, [campo]);
            alert(msg);
            obj.value = '';
            obj.focus();
            return false;
        }

    }

    function validarTpPresenca(val){
        if(val == 'D'){
            document.getElementById('divFtDesemp').style.display='inline-block';
            bolFatorDesempenho = true;
        }else{
            document.getElementById('divFtDesemp').style.display='none';
            if(bolFatorDesempenho)
                bolFatorDesempenho = false;
        }
    }

    function validarTpJornada(val){
        if(val == 'R'){
            document.getElementById('divRedJornada').style.display='inline-block';
            bolFatorReducao = true;
        }else{
            document.getElementById('divRedJornada').style.display='none';
            if(bolFatorReducao)
                bolFatorReducao = false;
        }
    }

    function validarValorDosPrazos(obj){
        var valor = parseInt(obj.value);
        var id    = obj.id;
        var msg = '';

        if(valor == 0){

            if(id == 'przSuspensao'){
                msg = setMensagemPersonalizada(msg15Padrao, ['Prazo máximo de Suspensão']);
                alert(msg);
            }

            if(id == 'przInterrupcao'){
                msg = setMensagemPersonalizada(msg15Padrao, ['Prazo máximo de Interrupção']);
                alert(msg);
            }

            obj.value = '';
            obj.focus;
            return false;
        }

        return true;
    }

    function montarPeriodo(){
        var frequencia = document.getElementById('selStaFrequencia').value;
        var inicioPeriodo = document.getElementById('selInicioPeriodo');
        var fimPeriodo = document.getElementById('selFimPeriodo');
        fimPeriodo.value = '0';
        inicioPeriodo.disabled = false;

        if(frequencia == '0'){
            inicioPeriodo.value = '0';
            inicioPeriodo.disabled = true;
            fimPeriodo.value = '0';
            fimPeriodo.disabled = true;
        }

        if(frequencia != '0'){

            if(frequencia == '<?= MdUtlAdmPrmGrRN::$FREQUENCIA_DIARIO?>'){
                inicioPeriodo.innerHTML = '<?= MdUtlAdmPrmGrINT::montarSelectInicioPeriodo(MdUtlAdmPrmGrRN::$FREQUENCIA_DIARIO)?>';
            }

            if(frequencia == '<?= MdUtlAdmPrmGrRN::$FREQUENCIA_SEMANAL?>'){
                inicioPeriodo.innerHTML = '<?= MdUtlAdmPrmGrINT::montarSelectInicioPeriodo(MdUtlAdmPrmGrRN::$FREQUENCIA_SEMANAL)?>';
            }

            if(frequencia == '<?= MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL?>'){
                inicioPeriodo.innerHTML = '<?= MdUtlAdmPrmGrINT::montarSelectInicioPeriodo(MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL)?>';
            }
        }
        montarFimPeriodo();
    }

    function montarFimPeriodo(){
        var inicioPeriodo = document.getElementById('selInicioPeriodo');
        var indexInicioPeriodo = document.getElementById('selInicioPeriodo').selectedIndex;
        var valInicioPeriodo = document.getElementById('selInicioPeriodo').options[indexInicioPeriodo].value;
        var fimPeriodo = document.getElementById('selFimPeriodo');

        if(inicioPeriodo.value == '0'){
            fimPeriodo.value = '0';
        }

        if(valInicioPeriodo == '<?=MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_DIARIO?>'){
            fimPeriodo.innerHTML = '<?= MdUtlAdmPrmGrINT::montarSelectFimPeriodo(MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_DIARIO)?>';
        }

        if(valInicioPeriodo == '<?=MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_SEMANAL_DOMINGO?>'){
            fimPeriodo.innerHTML = '<?= MdUtlAdmPrmGrINT::montarSelectFimPeriodo(MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_SEMANAL_DOMINGO)?>';
        }

        if(valInicioPeriodo == '<?=MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_SEMANAL_SEGUNDA?>'){
            fimPeriodo.innerHTML = '<?= MdUtlAdmPrmGrINT::montarSelectFimPeriodo(MdUtlAdmPrmGrRN::$FREQUENCIA_INICIO_SEMANAL_SEGUNDA)?>';
        }

        if(valInicioPeriodo == '<?=MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRO_DIA_MES?>'){
            fimPeriodo.innerHTML = '<?= MdUtlAdmPrmGrINT::montarSelectFimPeriodo(MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRO_DIA_MES)?>';
        }

        if(valInicioPeriodo == '<?=MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRO_DIA_UTIL_MES?>'){
            fimPeriodo.innerHTML = '<?= MdUtlAdmPrmGrINT::montarSelectFimPeriodo(MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRO_DIA_UTIL_MES)?>';
        }

        if(valInicioPeriodo == '<?=MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRA_SEGUNDA_MES?>'){
            fimPeriodo.innerHTML = '<?= MdUtlAdmPrmGrINT::montarSelectFimPeriodo(MdUtlAdmPrmGrRN::$FREQUENCIA_MENSAL_PRIMEIRA_SEGUNDA_MES)?>';
        }

        fimPeriodo.disabled = true;
    }

    function verificarVinculoTpProcesso(obj){

        var idControle = document.getElementById('hdnIdTipoControleUtl').value;

        var retorno = '';
            $.ajax({
                type: "POST",
                url: "<?= $strLinkAjaxVincDesProc ?>",
                //dataType: "json",
                dataType: "xml",
                data: {
                    idControle: idControle,
                    idVinculo: obj
                },
                success: function (result) {
                    var valido = $(result).find('sucesso').text();
                    if (valido == 1) {
                        var msg = $(result).find('msg').text();
                        alert(msg);
                        return false;
                    } else {
                        var row = objLupaTpProcesso.procuraLinha(obj);
                        objLupaTpProcesso.removerLinha(row);
                    }
                },
                error: function (msgError) {
                    msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                }
            });
            return retorno;
    }
    
    function validaPlanoTrabalho( input ){
        var ret = new Array(true,'');
        $.ajax({
            type: "post",
            url: "<?= $strLinkAjaxValidaNumPlanoTrab ?>",
            async: false,
            dataType: "xml",
            data: {
                id_serie: <?= $objMdUtlAdmTpCtrlDesemp->getNumIdSerie() ?: 0 ?>,
                num_sei: input.value
            },
            success: function( result ) {
                var erro = $( result ).find('Erro').text();

                if( erro == '1' ){                
                    alert( $( result ).find('Msg').text() );
                    ret[0] = false;
                }else{
                    ret[1] = $( result ).find('Msg').text();
                }            
            },
            error: function( msgError ) {
                console.error( msgError );
            }
        });
        return ret;
    }

    function atualizarUsuariosHndTabela( idUsuario ){
        let it = document.getElementById('hdnTbUsuario').value;
        let arr = it.split('¥'); // quebra em array o que seria as linhas da tabela
        let novaListaUsuario = new Array();

        if( arr.length > 0 ){

            // percorre cada linha para excluir do input hidden o usuario selecionado
            for( i in arr ){ 

                // quebra em array o que seria as colunas da cada linha da tabela
                let arrColunas = arr[i].split('±');
               
                // arrColunas[0] => id do usuario               
                if( idUsuario != arrColunas[0] ) novaListaUsuario.push( arr[i] );
            }
            document.getElementById('hdnTbUsuario').value = novaListaUsuario.join('¥');
        }
    }
</script>