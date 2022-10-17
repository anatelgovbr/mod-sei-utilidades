<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 14/09/2018
 * Time: 15:21
 */
?>
<script type="text/javascript">
    var objTabelaDinamicaGrpAtv = null;
    var isAlterar = false;
    var idMdUtlAdmPrmGrUsu = 0;
    var idsAlterarTemporario = '';
    var objLupaAtividade = null;
    var acao = null;
    var msg10 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_10, 'Atividade') ?>';
    var msg13 = '<?=MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_13, 'Tipo de Processo');?>';
    var msg16 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_16, 'Tipo de Processo') ?>';
    var msg18 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_18, 'Atividade') ?>';


    function inicializar() {
        if ('<?=$_GET['acao']?>' == 'md_utl_adm_grp_fl_proc_atv_cadastrar') {
            document.getElementById('txtTpProcesso').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_utl_adm_grp_fl_proc_atv_consultar') {
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }

        infraEfeitoTabelas(true);
        carregarComponenteAtividade();
        carregarComponenteTpProcesso();
        iniciarTabelaDinamicaGrpAtv();

    }

    function gerarToolTip(msg){
        return  '<img align="top" style="height:16px; width:16px;" id="imgAjuda" src="/infra_css/imagens/ajuda.gif" name="ajuda" ' +
            'onmouseover="return infraTooltipMostrar(\''+msg+'\'); "onmouseout="return infraTooltipOcultar();" alt="Ajuda" class="infraImg">';
    }

    function clonarAtividade(idTpProcesso){
        isAlterar = false;
        limparCampos();
        infraGetElementById('txtTpProcesso').disabled = false;

        var obj = objTabelaDinamicaGrpAtv.obterItens();

        var i;
        var j;

        for(i in obj){
            tpProcesso = obj[i];
            if(tpProcesso[0]== idTpProcesso) {

                dados = tpProcesso[3].split('§');
                for (j in dados) {
                    atividades = dados[j];
                    split = atividades.split('#');
                    objLupaAtividade.adicionar(split[0], split[1]);
                }
                

                document.getElementById('lblTpProcOrigem').innerHTML = 'Tipo de Processo Origem: ' + gerarToolTip('Tipo de Processo de origem que será clonado.');
                document.getElementById('lblOrigem').innerHTML = tpProcesso[1];
                document.getElementById('lblTpProcesso').innerHTML = 'Tipo de Processo Destino: ' + gerarToolTip('Tipo de Processo de destino a ser clonado.');
                document.getElementById('divTpProcOrigem').style.display = '';
                infraGetElementById('divOpUnica').style.display = "";
            }
        }
    }

    function iniciarTabelaDinamicaGrpAtv() {
        objTabelaDinamicaGrpAtv = new infraTabelaDinamica('tbGrpAtv', 'hdnTbGrpAtv', true, true);
        objTabelaDinamicaGrpAtv.gerarEfeitoTabela = true;

        if (objTabelaDinamicaGrpAtv.hdn.value != '') {
            objTabelaDinamicaGrpAtv.recarregar();

            //acoes
            hdnListaAtvPart = objTabelaDinamicaGrpAtv.hdn.value;
            arrListaAtvPart = hdnListaAtvPart.split('¥');

            //array
            if (arrListaAtvPart.length > 0) {
                for (i = 0; i < arrListaAtvPart.length; i++) {
                    hdnListaAtividade = arrListaAtvPart[i].split('±');
                    var btnClonar = "<a onclick='clonarAtividade("+hdnListaAtividade[0]+")'><img title='Clonar Atividades' alt='Clonar Atividades' src='<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . "/clonar.svg" ?>' class='infraImg'/></a><img src=\"/infra_css/imagens/espaco.gif\" class=\"\" border=\"0\">";

                    objTabelaDinamicaGrpAtv.adicionarAcoes(hdnListaAtividade[0], btnClonar);
                }
            }

        }

        objTabelaDinamicaGrpAtv.alterar = function (obj) {

            limparCampos();
            document.getElementById('hdnIdTpProcesso').value = obj[0];
            document.getElementById('txtTpProcesso').value = obj[1];
            var arrAtividade = obj[3].split('§');

            for (var i = 0; i < arrAtividade.length; i++) {
                var atividade = arrAtividade[i].split('#');
                objLupaAtividade.adicionar(atividade[0], atividade[1]);
            }



            infraGetElementById('divOpUnica').style.display = "none";
            infraGetElementById('txtTpProcesso').disabled = true;
            idMdUtlAdmPrmGrUsu = obj[4];
            isAlterar = true;

            if(isAlterar) {
                document.getElementById('lblTpProcesso').innerHTML = 'Tipo de Processo: ' + gerarToolTip('Selecionar um tipo de processo que será tratado no tipo de controle. ' +
                        'Se o tipo de processo estiver desabilitado, significa que ele esta em uso em outro tipo de controle com mesmo conjunto de unidades.');
            }
        };

        objTabelaDinamicaGrpAtv.remover = function (obj) {

            if (obj[4] != 'null') {

                var idsRemove = infraGetElementById('hdnIdsRegistroRemovido').value;
                if (idsRemove == '') {
                    infraGetElementById('hdnIdsRegistroRemovido').value = obj[4];
                } else {
                    infraGetElementById('hdnIdsRegistroRemovido').value = idsRemove + '-' + obj[4];
                }

            }

            infraGetElementById('divOpUnica').style.display = "inherit";
            infraGetElementById('txtTpProcesso').disabled = false;
            limparCampos();
            mostrarTabelaDinamica('remover');
            return true;
        };

        objTabelaDinamicaGrpAtv.procuraLinha = function (idTpProcesso) {

            var qtd;
            var linha;
            qtd = document.getElementById('tbGrpAtv').rows.length;

            for (i = 1; i < qtd; i++) {
                linha = document.getElementById('tbGrpAtv').rows[i];
                var valorLinha = $.trim(linha.cells[0].innerText);

                if (valorLinha == idTpProcesso) {
                    return i;
                }

            }
            return null;
        };


    }

    function carregarComponenteAtividade() {
        // ================= INICIO - JS para selecao de gestores =============================

        objAutoCompletarAtividade = new infraAjaxAutoCompletar('hdnIdAtividade', 'txtAtividade', '<?=$strLinkAjaxAtividade?>');
        objAutoCompletarAtividade.limparCampo = true;

        objAutoCompletarAtividade.prepararExecucao = function () {
            return 'palavras_pesquisa=' + document.getElementById('txtAtividade').value;
        };

        objAutoCompletarAtividade.tamanhoMinimo = 3;
        objAutoCompletarAtividade.processarResultado = function (id, descricao, complemento) {

            if (id != '') {
                var options = document.getElementById('selAtividade').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].value == id) {
                        alert(msg10);
                        break;
                    }
                }

                if (i == options.length) {

                    for (i = 0; i < options.length; i++) {
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selAtividade'), descricao, id);
                    objLupaAtividade.atualizar();
                    opt.selected = true;
                }

                document.getElementById('txtAtividade').value = '';
                document.getElementById('txtAtividade').focus();

            }
        };

        objLupaAtividade = new infraLupaSelect('selAtividade', 'hdnAtividade', '<?=$strLinkAtividadeSelecao?>');

        objLupaAtividade.processarRemocao = function () {

            if (isAlterar && idMdUtlAdmPrmGrUsu != 0) {
                var sel = infraGetElementById('selAtividade');
                var idAtividade = '';
                for (i = 0; i < sel.length; i++) {
                    if (sel.options[i].selected) {
                        if (idAtividade != '') {
                            idAtividade = idAtividade + '-';
                        }
                        idAtividade = idAtividade + sel.options[i].value;

                    }
                }

                var valRegistro = infraGetElementById('hdnIdsAtvRemovida').value;
                var concId = '';
                if (valRegistro != '') {
                    concId = "#"
                }

                infraGetElementById('hdnIdsAtvRemovida').value = valRegistro + concId + idMdUtlAdmPrmGrUsu + "," + idAtividade;
            }
            return true;
        };

    }

    function carregarComponenteTpProcesso() {

        objLupaTpProcesso = new infraLupaText('txtTpProcesso','hdnIdTpProcesso','<?=$strLinkTpProcessoSelecaoUnica?>');

        objLupaTpProcesso.finalizarSelecao = function(){
            objAutoCompletarTipoProcesso.selecionar(document.getElementById('hdnIdTpProcesso').value,document.getElementById('txtTpProcesso').value);
        }
  
        objAutoCompletarTipoProcesso = new infraAjaxAutoCompletar('hdnIdTpProcesso','txtTpProcesso','<?=$strLinkAjaxTpProcesso?>');
        objAutoCompletarTipoProcesso.limparCampo = false;
        objAutoCompletarTipoProcesso.tamanhoMinimo = 3;
        objAutoCompletarTipoProcesso.prepararExecucao = function(){
            return 'palavras_pesquisa='+ $.trim(document.getElementById('txtTpProcesso').value);
        };
  
        objAutoCompletarTipoProcesso.processarResultado = function(id,descricao,complemento){
            if (id!=''){
                document.getElementById('hdnIdTpProcesso').value = id;
                document.getElementById('txtTpProcesso').value = descricao;
            }
        }
        objAutoCompletarTipoProcesso.selecionar('<?=$strIdTipoProcesso?>','<?=PaginaSEI::getInstance()->formatarParametrosJavascript($strNomeRemetente);?>');
    }

    function OnSubmitForm() {
        if (document.getElementById('hdnTbGrpAtv').value == "") {
            alert(msg13);
            document.getElementById('btnAdicionar').focus();
            return false;
        }
        return true;
    }

    function validarCampoTabela() {

        var valTpProcesso = document.getElementById('txtTpProcesso').value;
        var arrAtividades = infraGetElementById('hdnAtividade').value;

        if (valTpProcesso == '') {
            var labelTpProcesso = document.getElementById('lblTpProcesso').innerHTML;
            var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Tipo de Processo']);
            alert(msg);
            return false;
        }

        if (arrAtividades == '') {
            alert(msg18);
            return false;
        }
        return true;
    }

    function adicionarRegistro() {

        if (validarCampoTabela()) {
            var arrTpProcesso = infraGetElementById('hdnTpProcesso');
            var idGrpAtv = isAlterar == true ? idMdUtlAdmPrmGrUsu : null;
            //var indexTpProcesso = document.getElementById('selTpProcesso').selectedIndex;
            var valTpProcesso = document.getElementById('hdnIdTpProcesso').value;
            var idVinculo = valTpProcesso;
            var txtTpProcesso = document.getElementById('txtTpProcesso').value;

            if (objTabelaDinamicaGrpAtv.procuraLinha(valTpProcesso) && !isAlterar) {
                alert(msg16);
                return false;
            }

            var arrAtividades = infraGetElementById('hdnAtividade').value.split('¥');
            if (arrAtividades.length > 1) {
                var txtAtividade = 'Múltiplas';
            } else {
                var txtAtividade = arrAtividades[0].split('±')[1];
            }

            var arrValIdAtividade = '';
            for (var i = 0; i < arrAtividades.length; i++) {
                if (i > 0) {
                    arrValIdAtividade += '§' + arrAtividades[i].split('±')[0] + '#' + arrAtividades[i].split('±')[1];
                } else {
                    arrValIdAtividade += arrAtividades[i].split('±')[0] + '#' + arrAtividades[i].split('±')[1];
                }
            }

            var arrLinha = [
                idVinculo,
                txtTpProcesso,
                txtAtividade,
                arrValIdAtividade,
                idGrpAtv
            ];

            objTabelaDinamicaGrpAtv.adicionar(arrLinha);
            mostrarTabelaDinamica();

            if(isAlterar) {
                infraGetElementById('divOpUnica').style.display="";
                infraGetElementById('txtTpProcesso').disabled= false;

                var hdnAtvAlterar = infraGetElementById('hdnIdsAtvAlterada').value;
                if (hdnAtvAlterar != '') {
                    infraGetElementById('hdnIdsAtvAlterada').value = hdnAtvAlterar + "#";
                }

                infraGetElementById('hdnIdsAtvAlterada').value += idMdUtlAdmPrmGrUsu;

                isAlterar = false;

            }else {
                var btnClonar = "<a onclick='clonarAtividade("+idVinculo+")'><img title='Clonar Atividades' alt='Clonar Atividades' src='<?= PaginaSEI::getInstance()->getDiretorioSvgGlobal() . "/clonar.svg" ?>' class='infraImg'/></a><img src=\"/infra_css/imagens/espaco.gif\" class=\"\" border=\"0\">";
                objTabelaDinamicaGrpAtv.adicionarAcoes(idVinculo, btnClonar);
            }

            document.getElementById('lblTpProcesso').innerHTML = 'Tipo de Processo: ' + gerarToolTip('Selecionar um tipo de processo que será tratado no tipo de controle. ' +
                    'Se o tipo de processo estiver desabilitado, significa que ele esta em uso em outro tipo de controle com mesmo conjunto de unidades.');
            limparCampos();
        }

    }

    function mostrarTabelaDinamica(acao) {

        if (acao == 'remover') {
            //Manipula o tamanho dinamico da tabela
            var tbGrpAtv = document.getElementById('tbGrpAtv').rows.length == 2;
            if (tbGrpAtv) {
                document.getElementById('tbGrpAtv').style.display = 'none';
            }

        }else {
            //Manipula o tamanho dinamico da tabela
            var tbGrpAtv = document.getElementById('tbGrpAtv').rows.length > 0;
            if (tbGrpAtv) {
                document.getElementById('tbGrpAtv').style.display = 'table';
            }

        }
    }

    function limparCampos() {
        document.getElementById('txtTpProcesso').value = '';
        infraSelectLimpar('selAtividade');
        document.getElementById('divTpProcOrigem').style.display = 'none';
    }
</script>