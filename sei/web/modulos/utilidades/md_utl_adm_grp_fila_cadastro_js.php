<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 11/09/2018
 * Time: 11:29
 */
if(0){?><script type="text/javascript"><?}?>
    var msg10 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_10, 'Fila') ?>';
    var msg18 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_18, 'Fila') ?>';

    function inicializar() {
        var idAlteracao = '<?php echo array_key_exists('id_md_utl_adm_grp_fila', $_GET) ? $_GET['id_md_utl_adm_grp_fila'] : 0?>';
        if ('<?=$_GET['acao']?>' == 'md_utl_adm_grp_fila_cadastrar') {

            if(idAlteracao == 0) {
                carregarComponenteFila();
            }else{
                carregarComponenteFilaUnica();
            }

            document.getElementById('txtNome').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_utl_adm_grp_fila_alterar') {
            carregarComponenteFilaUnica();
            document.getElementById('txtNome').focus();
        } else if ('<?=$_GET['acao']?>' == 'md_utl_adm_grp_fila_consultar') {
            carregarComponenteFilaUnica();
            infraDesabilitarCamposAreaDados();
        } else {
            document.getElementById('btnCancelar').focus();
        }
        infraEfeitoTabelas(true);
    }

    function validarCadastro() {
        if (infraTrim(document.getElementById('txtNome').value)=='') {
            var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Grupo de Atividade']);
            alert(msg);
            document.getElementById('txtNome').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txaDescricao').value)=='') {
            var msg = setMensagemPersonalizada(msgPadraoObrigatoriedade, ['Descrição']);
            alert(msg);
            document.getElementById('txaDescricao').focus();
            return false;
        }
        if (document.getElementById('hdnFila').value == '') {
            alert(msg18);
            document.getElementById('selFila').focus();
            return false;
        }

        return true;
    }

    function carregarComponenteFila(){
        // ================= INICIO - JS para selecao de gestores =============================

        objAutoCompletarFila = new infraAjaxAutoCompletar('hdnIdFila','txtFila','<?=$strLinkAjaxFila?>');
        objAutoCompletarFila.limparCampo = true;
        objAutoCompletarFila.tamanhoMinimo = 3;
        objAutoCompletarFila.prepararExecucao = function(){
            return 'palavras_pesquisa='+document.getElementById('txtFila').value;
        };

        objAutoCompletarFila.processarResultado = function(id,descricao,complemento){

            if (id!=''){
                var options = document.getElementById('selFila').options;

                for(var i=0;i < options.length;i++){
                    if (options[i].value == id){
                        alert(msg10);
                        break;
                    }
                }

                if (i==options.length){

                    for(i=0;i < options.length;i++){
                        options[i].selected = false;
                    }

                    opt = infraSelectAdicionarOption(document.getElementById('selFila'), descricao ,id);
                    objLupaFila.atualizar();
                    opt.selected = true;
                }

                document.getElementById('txtFila').value = '';
                document.getElementById('txtFila').focus();

            }
        };

        objLupaFila = new infraLupaSelect('selFila','hdnFila','<?=$strLinkFilaSelecao?>');
    }

    function carregarComponenteFilaUnica() {
        objLupaFilaUnica = new infraLupaText('selFila', 'hdnFila', '<?=$strLinkFilaSelecaoUnica?>');

        objLupaFilaUnica.finalizarSelecao = function () {
            objAutoCompletarTipoProcesso.selecionar(document.getElementById('hdnFila').value, document.getElementById('selFila').value);
        }

        objAutoCompletarFilaUnica = new infraAjaxAutoCompletar('hdnFila', 'selFila', '<?=$strLinkAjaxFila?>');
        objAutoCompletarFilaUnica.limparCampo = false;
        objAutoCompletarFilaUnica.tamanhoMinimo = 3;
        objAutoCompletarFilaUnica.prepararExecucao = function () {
            return 'palavras_pesquisa=' + $.trim(document.getElementById('selFila').value);
        };

        objAutoCompletarFilaUnica.processarResultado = function (id, descricao, complemento) {
            if (id != '') {
                document.getElementById('hdnFila').value = id;
                document.getElementById('selFila').value = descricao;
            }

        }

        objAutoCompletarFilaUnica.selecionar('<?=$strIdFila?>','<?=PaginaSEI::getInstance()->formatarParametrosJavascript($strNomeFila);?>');

    }

    function OnSubmitForm() {
        return validarCadastro();
    }

    <?if(0){?></script><?}?>