<script type="text/javascript">

    var objLupaTpProcesso = null;
    var objAutoCompletarTpProcesso = null;
    var idMdUtlAdmPrmGrUsu = null;
    var objLupaUsuario  = null;
    var objAutoCompletarUsuario = null;
    var objTabelaDinamicaUsuario= null;
    var bolFatorReducao = false;
    var isBolAlterar = false;
    var heigthTamanhoDivAreaPart = null ;
    var objPlanoTrabalhoValidado = { valido: false , msg: '' };
    var bolTemIntegracao = document.querySelector('#hdnTemIntegracao').value;

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
                let arrUsuario = descricao.split(' ');
                let lastItem = arrUsuario.pop().replace('(','').replace(')','');

                document.getElementById('hdnIdUsuario').value = id;
                document.getElementById('txtUsuario').value   = descricao;

                document.getElementById('hdnSiglaUsuario').value = lastItem;
                document.getElementById('txtUsuario').focus();

                verificaMembroParticipante();
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
        //se idVinculo = 0, novo usuario não cadastrado ainda

        if(idVinculo != 0) {
            $.ajax({
                type: "POST",
                url: "<?= $strLinkAjaxVincUsuFila?>",
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
                        if( ! validaPreenchimentoDatasParticipacao(idUsuario) ) return false;

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
                        removeRegistroHdnTbUsuario( idUsuario ); //forca a remocao do usuario caso nao tenha ocorrido
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

    function validaPreenchimentoDatasParticipacao(idUsuario){
        let valid = true;
        let arrUsuarios = objTabelaDinamicaUsuario.obterItens();
        arrUsuarios.forEach( (user,idx) => {
            if( user[0] == idUsuario ) {
                if ( user[13] == '' || user[13] === null ) {
                    alert('<?= MdUtlMensagemINT::$MSG_UTL_130 ?>');
                    valid = false;
                } else {
                    let strUserRemover  = user[9] +'±'+ idUsuario +'±'+ user[13];
                    let strItensRemover = document.querySelector('#hdnTbUsuarioRemove').value;
                    strItensRemover = strItensRemover == '' ? strUserRemover : strItensRemover.concat('¥' + strUserRemover);
                    document.querySelector('#hdnTbUsuarioRemove').value = strItensRemover;
                }
            }
        });
        return valid;
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
                var nm_user    = $.trim(linha.cells[1].innerText);
                id = $.trim(id);

                if (valorLinha == id) {
                    return nm_user;
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
    }

    function editarUsuarioPart(idUsuario,idVinculo){
        configOriginalMembroParticipante(true);

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

        document.getElementById('selTpJornada').value = dadosUsuario[7] ;
        
        // Remove o simbolo de porcentagem com o split
        document.getElementById('txtFtReduc').value = dadosUsuario[8]!= "null" ?dadosUsuario[8].split('%')[0] : "";

        if(dadosUsuario[7] == 'R') {
            document.getElementById('divRedJornada').style.display = 'inline-block';
            bolFatorReducao = true;
        }

        if( dadosUsuario[11] == 'Sim') $('#ckbChefiaImediata').click();

        if( dadosUsuario[12] != '' ) document.querySelector('#txtIniParticipacao').value = dadosUsuario[12];

        if( dadosUsuario[13] != '' ) document.querySelector('#txtFimParticipacao').value = dadosUsuario[13];

        document.querySelector('#hdnCargaHoraria').value = dadosUsuario[14];

        if( dadosUsuario[4] != '' ) objPlanoTrabalhoValidado.valido = true;

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
        //valida se foi selecionado algum usuario
        if( document.getElementById('hdnIdUsuario').value == '' ){
            alert( setMensagemPersonalizada(msg11Padrao, ['Usuário Participante']) );
            document.getElementById('txtUsuario').focus();
            return false;
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
                adicionarRegistroTabelaUsuario( r , objPlanoTrabalhoValidado.msg );
            },
            error: function (e) {
                console.error('Erro ao buscar o nome do usuário: ' + e.responseText);
            }
        });
    }

    function validaPreenchimentoCampos(){

        let planoTrab = document.getElementById('txtPlanoTrabalho');

        //se nao foi marcado o checkbox "chefia imediata", verifica se o Plano de Trabalho já foi validado
        if( !document.querySelector('[name="ckbChefiaImediata"]').checked ) {
            if( !objPlanoTrabalhoValidado.valido ) {
                alert("Não foi validado o Plano de Trabalho.\nPor favor, caso não tenha informado o Plano de Trablho, preenchê-lo e clicar no botão Validar.");
                planoTrab.focus();
                return false;
            }
        }

        // se preenchido o Plano de Trabalho e validado, verifica preenchimento do campo Inicio Participacao, pois será obrigatório
        //if( planoTrab.value && objPlanoTrabalhoValidado.valido ){}
        if( ! document.querySelector('#txtIniParticipacao').value ){
            alert( setMensagemPersonalizada(msg11Padrao, ['Início Participação']) );
            document.querySelector('#txtIniParticipacao').focus();
            return false;
        }

        //valida preenchimento dos campos de data
        if( document.querySelector('#txtIniParticipacao').value && document.querySelector('#txtFimParticipacao').value ){

            let dti = returnDateTime( document.querySelector('#txtIniParticipacao').value );
            let dtf = returnDateTime( document.querySelector('#txtFimParticipacao').value );

            if ( dtf <= dti ){
                alert('A data informada no campo "Fim Participação" não pode ser menor ou igual ao campo "Início Participação"');
                return false;
            }
        }

        return true;
    }
    
    function adicionarRegistroTabelaUsuario(retornoAjax,linkNumSei){
        //executa algumas validacoes antes
        if(! validaPreenchimentoCampos() ) return false;
        
        var msg ='';

        if(isBolAlterar){
            msg ='1';
        }else {
          msg  = validarDuplicidade();
        }

        if(msg!='') {
            if(validarFatorObrigatorio()) {

                var arrUsuarios = new Array( document.getElementById('hdnIdUsuario').value );

                var indexPresenca = document.getElementById('selTpPresenca').selectedIndex;
                var valPresenca = document.getElementById('selTpPresenca').value;
                var txtPresenca = document.getElementById('selTpPresenca').options[indexPresenca].text;
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

                let chkChefiaImediata = 'Não';
                if( document.querySelector('[name="ckbChefiaImediata"]').checked ) chkChefiaImediata = 'Sim';

                let dt_ini_part = document.querySelector('#txtIniParticipacao').value;
                let dt_fim_part = document.querySelector('#txtFimParticipacao').value;
                let cargHr      = document.querySelector('#hdnCargaHoraria').value;

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
                        "",
                        txtTpJornada,
                        valTpJornada,
                        ftReduc,
                        idMdUtlAdmPrmGrUsu,
                        nomeSigla,
                        chkChefiaImediata,
                        dt_ini_part,
                        dt_fim_part,
                        cargHr
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

                    let pathIconeAlt = "<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/alterar.svg' ?>";
                    let pathIconeExc = "<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . '/excluir.svg' ?>";
                    let btnAlterar   = '';
                    let btnRemover   = '';

                    if(isBolAlterar){

                        var row = objTabelaDinamicaUsuario.procuraLinha(idUsuario);
                        objTabelaDinamicaUsuario.removerLinha(row);

                        //forçar a exclusao da linha no input hidden hdnTbUsuario, caso não tenha ocorrido
                        removeRegistroHdnTbUsuario( idUsuario );
                    
                        isBolAlterar = false;

                        infraGetElementById('txtUsuario').disabled = false;
                        infraGetElementById('divOpcoesUsuario').hidden = false;

                        objTabelaDinamicaUsuario.flagAlterar = false;

                        btnAlterar = "<img onclick=\"editarUsuarioPart(" + idUsuario + "," + idMdUtlAdmPrmGrUsu + ")\"" + " title='Alterar Usuário Participante' alt='Alterar Usuário Participante' src='"+pathIconeAlt+"' class='infraImg'/> ";
                        btnRemover = "<img onclick=\"verificarVinculoUsuario(" + idUsuario + "," + idMdUtlAdmPrmGrUsu + ")\"" + " title='Remover Usuário Participante' alt='Remover Usuário Participante' src='"+pathIconeExc+"' class='infraImg'/> ";
                    } else {
                        btnAlterar = "<img onclick=\"editarUsuarioPart(" + idUsuario + "," + 0 + ")\"" + " title='Alterar Usuário Participante' alt='Alterar Usuário Participante' src='"+pathIconeAlt+"' class='infraImg'/> ";
                        btnRemover = "<img onclick=\"verificarVinculoUsuario(" + idUsuario + "," + 0 + ")\"" + " title='Remover Usuário Participante' alt='Remover Usuário Participante' src='"+pathIconeExc+"' class='infraImg'/> ";
                    }

                    objTabelaDinamicaUsuario.adicionar(arrLinha);

                    objTabelaDinamicaUsuario.adicionarAcoes(idUsuario,btnAlterar + btnRemover, false, false);

                    //Corrrigindo o problema do core do Sei que não aceita HTML para alteração (função remover XML)
                    var row = objTabelaDinamicaUsuario.procuraLinha(idUsuario);

                    document.getElementById('tbUsuario').rows[row].cells[1].innerHTML = htmlNomeUsu;
                }
                limparCamposControleParticipante();
            }
        }
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

        if(bolFatorReducao) {
            document.getElementById('divRedJornada').style.display = 'none';
            bolFatorReducao = false;
        }

        document.querySelector('#hdnCargaHoraria').value = '';
        configOriginalMembroParticipante(true);
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
            let result = objTabelaDinamicaUsuario.existeIdUsuario(arrUsuarios[i]);
            if( result ){
                valido = false;
                msg += '- ' + result;
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

    function validarMembroOutroTpCtrl(){
        let ret = { suc: true , msg: '' };

        $.ajax({
            url: '<?= $strLinkValidaMembroOutroTpCtrl ?>',
            type: 'post',
            dataType: 'xml',
            async: false,
            data: {
                id_usuario: document.querySelector('#hdnIdUsuario').value,
                id_prm_gr: <?= $objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr() ?: 0 ?>,
                tp_presenca: document.querySelector('#selTpPresenca').value,
                tp_jornada: document.querySelector('#selTpJornada').value,
                fator_jornada_red: document.querySelector('#txtFtReduc').value
            },
            beforeSend: function () {
                infraExibirAviso( false );
            },
            success: function ( rs ) {
                let data = $( rs ).find('Validado').text();

                if( data == 'N' ) {
                    ret.suc = false;
                    ret.msg = $( rs ).find('Msg').text();
                }
            },
            error: function ( xhr ) {
                ret.suc = false;
                ret.msg = xhr.responseText;
            },
            complete: function () {
                infraAvisoCancelar();
            }
        });
        return ret;
    }

    function verificarVinculoTpProcesso(obj){

        var idControle = document.getElementById('hdnIdTipoControleUtl').value;

        var retorno = '';
            $.ajax({
                type: "POST",
                url: "<?= $strLinkAjaxVincDesProc ?>",
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
    
    function validaPlanoTrabalho( input , exec_loading = false ){
        $.ajax({
            type: "post",
            url: "<?= $strLinkAjaxValidaNumPlanoTrab ?>",
            async: false,
            dataType: "xml",
            data: {
                id_serie: <?= $objMdUtlAdmTpCtrlDesemp->getNumIdSerie() ?: 0 ?>,
                num_sei: input.value,
                id_usuario: document.querySelector('#hdnIdUsuario').value,
                id_prm_gr: <?= $objMdUtlAdmTpCtrlDesemp->getNumIdMdUtlAdmPrmGr() ?: 0 ?>,
                sigla_usuario: document.querySelector('#hdnSiglaUsuario').value
            },
            beforeSend: () => {
                if( exec_loading ) infraExibirAviso( false );
            },
            success: ( result ) => {

                if ( $( result ).find('Erro').text() != '1' ) {
                    objPlanoTrabalhoValidado.valido = true;
                } else {
                    objPlanoTrabalhoValidado.valido = false;
                    objPlanoTrabalhoValidado.msg    = $( result ).find('Msg').text();
                }
            },
            error: ( msgError ) => {
                console.error( msgError.responseText );
            },
            complete: () => {
                infraAvisoCancelar();
            }
        });
    }

    function atualizarUsuariosHndTabela( idUsuario ){
        let it  = document.getElementById('hdnTbUsuario').value;
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

    function removeRegistroHdnTbUsuario( idUsuario ){        
        atualizarUsuariosHndTabela( idUsuario );
    }

    function acionaValidacaoPlanoTrab(){
        let user = document.querySelector('#hdnIdUsuario');
        if( user.value == '' ){
            alert( setMensagemPersonalizada(msg11Padrao, ['Usuário Participante']) );
            document.querySelector('#txtUsuario').focus();
            return false;
        }

        let planoTrab = document.querySelector('#txtPlanoTrabalho');
        if ( !document.querySelector('#ckbChefiaImediata').checked ) {
            if (planoTrab.value == '') {
                alert(setMensagemPersonalizada(msg11Padrao, ['Plano de Trabalho (Número SEI)']));
                planoTrab.focus();
                return false;
            }
        }

        validaPlanoTrabalho( planoTrab , true );

        if( objPlanoTrabalhoValidado.valido === false )
            alert( objPlanoTrabalhoValidado.msg );
        else
            alert('Plano de Trabalho validado.');
    }

    function addRemoveObrigatoriedade( input ){
        if( input.target.checked )
            $('.labelPlanoTrab').removeClass('infraLabelObrigatorio').addClass('infraLabelOpcional');
        else
            $('.labelPlanoTrab').removeClass('infraLabelOpcional').addClass('infraLabelObrigatorio');
    }

    $('.acionaCalendario').click( function (){
        let largTela = document.documentElement.scrollWidth;
        let largRef  = $(this).data('param') == 'Ini' ? 576 : 992;

        if( largTela <= largRef )
        {
            $('#divInfraCalendario').css({
                left: '',
                right: 0
            });
        }
        else
        {
            $('#divInfraCalendario').css({
                width: 290
            });
        }
    });

    function openModalUsuariosInativos(){
        infraAbrirJanelaModal("<?= $strUrlUsuariosInativos ?>" , 1000 , 800 );
    }

    function configOriginalMembroParticipante(clearPart = false){
        $('#ckbChefiaImediata')
            .prop('disabled',false)
            .prop('checked',false);

        $('.labelPlanoTrab')
            .addClass('infraLabelObrigatorio')
            .removeClass('infraLabelOpcional');

        if ( clearPart ){
            $('#txtFimParticipacao').val('');
            $('#txtIniParticipacao').val('');
        }
    }

    function verificaMembroParticipante(){
        $.ajax({
            type: "post",
            url: "<?= $strLinkVerificaMembroPart ?>",
            dataType: "xml",
            data: {
                id_usuario: document.querySelector('#hdnIdUsuario').value,
                login_usuario: document.querySelector('#hdnSiglaUsuario').value
            },
            beforeSend: () => {
               infraExibirAviso( false );
            },
            success: ( result ) => {
              let strChefe    = $( result ).find('ChefiaImediata').text();
              let isEditChefe = $( result ).find('isEditavelChefe').text();

              if ( isEditChefe == 'N' ) {
                  if( strChefe.length > 0 ) $('#ckbChefiaImediata').click();
                  $('#ckbChefiaImediata').prop('disabled',true);
              } else if ( isEditChefe == 'S' ) {
                  $('#ckbChefiaImediata').prop('disabled',false);
              }
            },
            error: ( msgError ) => {
                alert( msgError.responseText );
                console.error( msgError.responseText );
            },
            complete: () => {
                infraAvisoCancelar();
            }
        });
    }

    function validarCheckChefia( el ){
        if( el.checked ){
            document.querySelector('.labelPlanoTrab').classList.remove('infraLabelObrigatorio');
            document.querySelector('.labelPlanoTrab').classList.add('infraLabelOpcional');
        } else {
            configOriginalMembroParticipante();
        }
    }

    function debugTable(){
        let tb = document.querySelector('#hdnTbUsuario').value;
        let arrLinhas = tb.split('¥');
        console.log(arrLinhas);
    }
</script>