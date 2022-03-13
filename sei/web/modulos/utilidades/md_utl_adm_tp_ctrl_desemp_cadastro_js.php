<?php if(0){ ?>
    <script>
<?php } ?>

//variaveis globais - declarar fora do escopo das funçoes da pagina
var objLupaUnidades = null;
var objAutoCompletarUnidade = null;

var objLupaGestores = null;
var objAutoCompletarGestor = null;

var objLupaTipoProcessos = null;
var objAutoCompletarTipoProcesso = null;
var msg10Padrao  = '<?=  MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_10); ?>';
var msg11Padrao  = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_11) ?>';
var msg12        = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_12, 'Gestor') ?>';
var msg18        = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_18, 'Unidade') ?>';


function inicializar() {
    if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_ctrl_desemp_cadastrar'){
        document.getElementById('txtNome').focus();
    } else if ('<?=$_GET['acao']?>'=='md_utl_adm_tp_ctrl_desemp_consultar'){
        infraDesabilitarCamposAreaDados();
    }else{
        document.getElementById('btnCancelar').focus();
    }

    carregarComponenteGestores();
    carregarComponenteUnidades();
}

function onSubmitForm(){

    var txtNome = document.getElementById('txtNome');
    if($.trim(txtNome.value) == ''){
        var msg = setMensagemPersonalizada(msg11Padrao, ['Nome']);
        alert(msg);
        document.getElementById('txtNome').focus();
        return false;
    }



    var txtDesc = document.getElementById('txaDescricao');
    if($.trim(txtDesc.value) == ''){
        var msg = setMensagemPersonalizada(msg11Padrao, ['Descrição']);
        alert(msg);
        document.getElementById('txaDescricao').focus();
        return false;
    }

    var optionsGestores = document.getElementById('selGestores').options;
    var optionsUnidades = document.getElementById('selUnidades').options;

    if( optionsGestores.length == 0 ){
        alert(msg12);
        document.getElementById('txtGestor').focus();
        return false;
    }

    if( optionsUnidades.length == 0 ){
        alert(msg18);
        document.getElementById('txtUnidade').focus();
        return false;
    }
}


function carregarComponenteGestores(){
    // ================= INICIO - JS para selecao de gestores =============================

    objAutoCompletarGestor = new infraAjaxAutoCompletar('hdnIdGestor','txtGestor','<?=$strLinkAjaxGestor?>');
    objAutoCompletarGestor.limparCampo = true;
    objAutoCompletarGestor.tamanhoMinimo = 3;

    objAutoCompletarGestor.prepararExecucao = function(){
        return 'palavras_pesquisa='+document.getElementById('txtGestor').value;
    };

    objAutoCompletarGestor.processarResultado = function(id,descricao,complemento){

        if (id!=''){
            var options = document.getElementById('selGestores').options;

            for(var i=0;i < options.length;i++){
                if (options[i].value == id){
                    var msg = setMensagemPersonalizada(msg10Padrao, ['Gestor']);
                    alert(msg);
                    break;
                }
            }

            if (i==options.length){

                for(i=0;i < options.length;i++){
                    options[i].selected = false;
                }

                opt = infraSelectAdicionarOption(document.getElementById('selGestores'), descricao ,id);
                objLupaGestores.atualizar();
                opt.selected = true;
            }

            document.getElementById('txtGestor').value = '';
            document.getElementById('txtGestor').focus();

        }
    };

    objLupaGestores = new infraLupaSelect('selGestores','hdnGestores','<?=$strLinkGestoresSelecao?>');
}


function carregarComponenteUnidades(){
    objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade','txtUnidade','<?=$strLinkAjaxUnidade?>');
    objAutoCompletarUnidade.limparCampo = true;
    objAutoCompletarUnidade.tamanhoMinimo = 3;

    objAutoCompletarUnidade.prepararExecucao = function(){
        return 'palavras_pesquisa='+document.getElementById('txtUnidade').value;
    };

    objAutoCompletarUnidade.processarResultado = function(id,descricao,complemento){

        if (id!=''){
            var options = document.getElementById('selUnidades').options;

            for(var i=0;i < options.length;i++){
                if (options[i].value == id){
                    var msg = setMensagemPersonalizada(msg10Padrao, ['Unidade']);
                    alert(msg);
                    break;
                }
            }

            if (i==options.length){

                for(i=0;i < options.length;i++){
                    options[i].selected = false;
                }

                opt = infraSelectAdicionarOption(document.getElementById('selUnidades'),descricao,id);

                objLupaUnidades.atualizar();

                opt.selected = true;
            }

            document.getElementById('txtUnidade').value = '';
            document.getElementById('txtUnidade').focus();

        }
    };

    objLupaUnidades = new infraLupaSelect('selUnidades','hdnUnidades','<?=$strLinkUnidadesSelecao?>');

    objLupaUnidades.processarRemocao = function(itens){
        <?php echo $strUnidadesComProcessoVinculado ?>
        var msgCorpo = '';
        var qtde = 0;
        var qtdeTotal = 0;
        var idUnidadeQualquer = null;
        var existeMaisDeUma = false;
        var idUnidadeQualquer = null;

        for(var i=0;i < itens.length;i++){
            for(var j=0;j < arrUnidadesComProcessoVinculado.length; j++){
                if (itens[i].value == arrUnidadesComProcessoVinculado[j].id_unidade) {

                    // limitar a lista em 15 registros
                    if (qtde < 15) {
                        msgCorpo += arrUnidadesComProcessoVinculado[j].sigla_unidade + ": " + arrUnidadesComProcessoVinculado[j].processo_formatado + "\n";
                        qtde++;
                    }

                    // logica para adequar plural de texto de unidade
                    if (!idUnidadeQualquer) {
                        idUnidadeQualquer = arrUnidadesComProcessoVinculado[j].id_unidade;
                    }
                    if (itens[i].value != idUnidadeQualquer){
                        existeMaisDeUma = true;
                    }
                    qtdeTotal++;
                }
            }
        }

        // Adequaçoes de mensagem para exibir na tela levando em consideração plural/singular e se possui mais de 15 registros
        var msgInicio = "A Unidade não pode ser removida, pois está vinculada com processos em fluxo de atendimento em andamento.\n \n";
        if (existeMaisDeUma){
            msgInicio = "As Unidades não podem ser removidas, pois está vinculada com processos em fluxo de atendimento em andamento.\n \n";
        }
        if (qtdeTotal > 15) {
            msgCorpo += '...'
        }

        if (qtde > 0){
            alert(msgInicio + msgCorpo);
            return false;
        }

        return true;
    }
}


<?php if(0){ ?>
<script>
<?php } ?>

        