<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 12/07/2018
 * Time: 10:59
 */

if (0){ ?>
    <script type="text/javascript"><?}?>

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
        var msg11 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11, 'Carga Padrão de Unidade de Esforço'); ?>';
        var msg12 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_12, 'Tipo de Processo'); ?>'
        var msg13 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_13, 'Usuário Participante'); ?>'
        var msg14 = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_14); ?>'
        var msg11Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11); ?>';
        var msg10Padrao = '<?php echo MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_10); ?>';

        function carregarComponenteUsuario(){
            // ================= INICIO - JS para selecao de usuarios participantes =============================

            objAutoCompletarUsuario = new infraAjaxAutoCompletar('hdnIdUsuario','txtUsuario','<?=$strLinkAjaxUsuario?>');
            objAutoCompletarUsuario.limparCampo = true;

            objAutoCompletarUsuario.prepararExecucao = function(){
                return 'palavras_pesquisa='+document.getElementById('txtUsuario').value;
            };

            objAutoCompletarUsuario.processarResultado = function(id,descricao,complemento){

                if (id!=''){
                    var options = document.getElementById('selUsuario').options;

                    for(var i=0;i < options.length;i++){
                        if (options[i].value == id){
                            var msg = setMensagemPersonalizada(msg10Padrao, ['Usuário']);
                            alert(msg);
                            break;
                        }
                    }

                    if (i==options.length){

                        for(i=0;i < options.length;i++){
                            options[i].selected = false;
                        }


                        opt = infraSelectAdicionarOption(document.getElementById('selUsuario'), descricao ,id);
                        objLupaUsuario.atualizar();
                        opt.selected = true;
                    }


                    document.getElementById('txtUsuario').value = '';
                    document.getElementById('txtUsuario').focus();

                }
            };

            objLupaUsuario = new infraLupaSelect('selUsuario','hdnUsuario','<?=$strLinkUsuarioSelecao?>');
        }

        function carregarComponenteTpProcesso(){
            // ================= INICIO - JS para selecao de Tipo Processo =============================

            objAutoCompletarTpProcesso = new infraAjaxAutoCompletar('hdnIdTpProcesso','txtTpProcesso','<?=$strLinkAjaxTipoProcesso?>');
            objAutoCompletarTpProcesso.limparCampo = true;

            objAutoCompletarTpProcesso.prepararExecucao = function(){
                return 'palavras_pesquisa='+document.getElementById('txtTpProcesso').value;
            };

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

            heigthTamanhoDivAreaPart= parseInt(infraGetElementById('divInfraAreaDados1').style.height.split('em').join(''));

        }

        function validarCadastro() {
            var cargaPadrao = document.getElementById('txtCargaPadrao').value;
            var tpProcesso  = document.getElementById('hdnTpProcesso').value;
            var tbUsuario   = document.getElementById('tbUsuario');


            if(cargaPadrao == '' || cargaPadrao <1 ){
                alert(msg11);
                document.getElementById('txtCargaPadrao').focus();
                return false;
            }
            if(tpProcesso == '' ){

                alert(msg12);
                document.getElementById('txtTpProcesso').focus();
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
                                    infraGetElementById('selUsuario').disabled = false;
                                    infraGetElementById('txtUsuario').disabled = false;
                                    infraGetElementById('divOpcoesUsuario').hidden = false;
                                    objTabelaDinamicaUsuario.flagAlterar = false;
                                    isBolAlterar = false;
                                }
                            }

                            objTabelaDinamicaUsuario.atualizaHdn();
                            var linha = objTabelaDinamicaUsuario.procuraLinha(idUsuario);
                            objTabelaDinamicaUsuario.controlarFluxoUsuarioPart(idUsuario);

                            objTabelaDinamicaUsuario.removerLinha(linha);
                            verificaTabela(1);
                            infraGetElementById('divInfraAreaDados1').style.height=heigthTamanhoDivAreaPart+'em';
                        }


                    },
                    error: function (msgError) {
                        msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
                    }
                });

            }else{

                var linha = objTabelaDinamicaUsuario.procuraLinha(idUsuario);
                objTabelaDinamicaUsuario.removerLinha(linha);
                objTabelaDinamicaUsuario.atualizaHdn();
                verificaTabela(1);
                infraGetElementById('divInfraAreaDados1').style.height=heigthTamanhoDivAreaPart+'em';

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

                    //array
                    if (arrListaUsuariosPart.length > 0) {
                        for (i = 0; i < arrListaUsuariosPart.length; i++) {

                            hdnListaUsuPart = arrListaUsuariosPart[i].split('±');

                            var btnAlterar = "<img onclick=\"editarUsuarioPart("+hdnListaUsuPart[0] +","+hdnListaUsuPart[8]+")\""+" title='Alterar Usuário Participante' alt='Alterar Usuário Participante' src='/infra_css/imagens/alterar.gif' class='infraImg'/> ";
                            var btnRemover = "<img onclick=\"verificarVinculoUsuario(" + hdnListaUsuPart[0] + ","+hdnListaUsuPart[8]+")\""+" title='Remover Usuário Participante' alt='Remover Usuário Participante' src='/infra_css/imagens/remover.gif' class='infraImg'/> ";

                            objTabelaDinamicaUsuario.adicionarAcoes(hdnListaUsuPart[0], btnAlterar + btnRemover , false, false);

                        }
                    } else {
                        hdnListaUsuPart = hdnListaUsuariosPart.split('±');
                        var btnAlterar = "<img onclick=\"editarUsuarioPart(" + hdnListaUsuPart[0] + ","+hdnListaUsuPart[8]+")\""+" title='Alterar Usuário Participante' alt='Alterar Usuário Participante' src='/infra_css/imagens/alterar.gif' class='infraImg'/> ";
                        var btnRemover = "<img onclick=\"verificarVinculoUsuario(" + hdnListaUsuPart[0] + ","+hdnListaUsuPart[8]+")\""+" title='Remover Usuário Participante' alt='Remover Usuário Participante' src='/infra_css/imagens/remover.gif' class='infraImg'/> ";

                        objTabelaDinamicaUsuario.adicionarAcoes(hdnListaUsuPart[0], btnAlterar + btnRemover , false, false);
                        objTabelaDinamicaUsuario.adicionarAcoes(hdnListaUsuPart[0], "<img onclick=\"editarUsuarioPart(" + hdnListaUsuPart[0] + ","+hdnListaUsuPart[8]+")\" title='Alterar Usuário Participante' alt='Alterar Usuário Participante' src='/infra_css/imagens/alterar.gif' class='infraImg'/>", false, false);

                    }
                }
            objTabelaDinamicaUsuario.alterar=function(id){
                editarUsuarioPart(id[0],id[8]);
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




        }

        function editarUsuarioPart(idUsuario,idVinculo){

            selUsuario = document.getElementById('selUsuario').value;

            if(selUsuario!=''){
                limparCamposControleParticipante();
            }
            objTabelaDinamicaUsuario.flagAlterar = true;
            var dadosUsuario = null;


            //acoes
            hdnListaUsuariosPart = objTabelaDinamicaUsuario.hdn.value;
            arrListaUsuariosPart = hdnListaUsuariosPart.split('¥');

            for (i = 0; i < arrListaUsuariosPart.length; i++) {

               hdnListaUsuPart = arrListaUsuariosPart[i].split('±');

               if(hdnListaUsuPart[0] == idUsuario){
                   dadosUsuario =hdnListaUsuPart ;

                   break;
               }

            }

            document.getElementById('selTpPresenca').value = dadosUsuario[3];
                                                            // Remove o simbolo de porcentagem com o split
            document.getElementById('txtFtDesemp').value = dadosUsuario[4] != "null"?dadosUsuario[4].split('%')[0] : "";
           // ftDesemp = ftDesemp != '' ? ftDesemp+'%':'';
            document.getElementById('selTpJornada').value = dadosUsuario[6] ;
                                                            // Remove o simbolo de porcentagem com o split
            document.getElementById('txtFtReduc').value = dadosUsuario[7]!= "null" ?dadosUsuario[7].split('%')[0] : "";

            if(dadosUsuario[3] == 'D') {
                document.getElementById('divFtDesemp').style.display = 'inherit';
                bolFatorDesempenho = true;
            }
            if(dadosUsuario[6] == 'R') {
                document.getElementById('divRedJornada').style.display = 'inherit';
                bolFatorReducao = true;
            }

            infraGetElementById('selUsuario').disabled = true;
            infraGetElementById('txtUsuario').disabled = true;
            infraGetElementById('divOpcoesUsuario').hidden = true;
            infraGetElementById('selUsuario').value = idUsuario;

            if(idVinculo!= 0) {
                idMdUtlAdmPrmGrUsu = idVinculo;

            }

            objAutoCompletarUsuario.processarResultado(idUsuario,dadosUsuario[9]);

            isBolAlterar = true;
        }

        function validarFatorObrigatorio(){

            var indexPresenca = document.getElementById('selTpPresenca').selectedIndex;
            var indexTpJornada = document.getElementById('selTpJornada').selectedIndex;

            var usuarioParticipante  = document.getElementById('hdnUsuario').value;

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
            var arrUsuarios  = document.getElementById('selUsuario').options;
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
                    adicionarRegistroTabelaUsuario(r);
                },
                error: function (e) {
                    console.error('Erro ao buscar o nome do usuário: ' + e.responseText);
                }
            });
        }


        function adicionarRegistroTabelaUsuario(retornoAjax){
            var msg ='';

            if(isBolAlterar){
                msg ='1';
            }else {
              msg  = validarDuplicidade();
            }
            if(msg!='') {
                if(validarFatorObrigatorio()) {
                    var arrUsuarios = document.getElementById('selUsuario').options;

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

                        var ftReduc = "";
                        if(valTpJornada == 'R') {
                            ftReduc = document.getElementById('txtFtReduc').value;
                            ftReduc = ftReduc != '' ? ftReduc + '%' : '';
                        }



                        for (var i = 0; i < arrUsuarios.length; i++) {
                            var idUsuario = arrUsuarios[i].value;

                            var nomeCampAjx = 'IdUsuario' + idUsuario;
                            var htmlNomeUsu = '<div style="text-align:center;">'+$(retornoAjax).find(nomeCampAjx).text()+'</div>';
                            var nomeSigla   = $.trim(document.getElementById('selUsuario').options[i].text) ;

                            var arrLinha = [
                                idUsuario,
                                "",
                                txtPresenca,
                                valPresenca,
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
                                objTabelaDinamicaUsuario.atualizaHdn();
                                isBolAlterar = false;
                                flag=false;
                                infraGetElementById('selUsuario').disabled = false;
                                infraGetElementById('txtUsuario').disabled = false;
                                infraGetElementById('divOpcoesUsuario').hidden = false;

                                objTabelaDinamicaUsuario.flagAlterar = false;
                            }

                            objTabelaDinamicaUsuario.adicionar(arrLinha);

                            var btnAlterar = "<img onclick=\"editarUsuarioPart(" + idUsuario + "," + 0 + ")\"" + " title='Alterar Usuário Participante' alt='Alterar Usuário Participante' src='/infra_css/imagens/alterar.gif' class='infraImg'/> ";
                            var btnRemover = "<img onclick=\"verificarVinculoUsuario(" + idUsuario + "," + 0 + ")\"" + " title='Remover Usuário Participante' alt='Remover Usuário Participante' src='/infra_css/imagens/remover.gif' class='infraImg'/> ";

                            objTabelaDinamicaUsuario.adicionarAcoes(idUsuario,btnAlterar + btnRemover, false, false);

                            //Corrrigindo o problema do core do Sei que não aceita HTML para alteração (função remover XML)
                            var row = objTabelaDinamicaUsuario.procuraLinha(idUsuario);

                            document.getElementById('tbUsuario').rows[row].cells[1].innerHTML = htmlNomeUsu;
                            infraGetElementById('divInfraAreaDados1').style.height=heigthTamanhoDivAreaPart+'em';

                        }


                        limparCamposControleParticipante();
                }
            }
        }

        function limparCamposControleParticipante(){

            var blocoControleParticipante = document.getElementById('blocoUsuario');
            // seleciona a primeira opcao de todos os selects

            // limpa todos os inputs do tipo text
            var inputs = blocoControleParticipante.getElementsByTagName('input');
            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].type != 'checkbox' && inputs[i].type != 'radio') {
                    inputs[i].value = '';
                }
            }

            var options = document.getElementById('selUsuario').options;

            for(i=0;i < options.length;i++){
                options[i].selected = true;
            }
            //remove os usuarios do select
            objLupaUsuario.remover();

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
            var arrUsuarios = document.getElementById('selUsuario').options;
            var msg         = '';

            for (var i = 0; i < arrUsuarios.length; i++) {
                if(objTabelaDinamicaUsuario.existeIdUsuario(arrUsuarios[i].value)){
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
                alert('O '+campo+' deve ser entre 0 e 100.');
                obj.value = '';
                obj.focus();
                return false;
            }

        }

        function validarTpPresenca(val){
            if(val == 'D'){
                document.getElementById('divFtDesemp').style.display='inherit';
                bolFatorDesempenho = true;
            }else{
                document.getElementById('divFtDesemp').style.display='none';
                if(bolFatorDesempenho)
                    bolFatorDesempenho = false;
            }
        }

        function validarTpJornada(val){
            if(val == 'R'){
                document.getElementById('divRedJornada').style.display='inherit';
                bolFatorReducao = true;
            }else{
                document.getElementById('divRedJornada').style.display='none';
                if(bolFatorReducao)
                    bolFatorReducao = false;
            }
        }

        <?
        if (0){ ?></script><?
} ?>