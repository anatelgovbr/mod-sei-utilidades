<?php
/**
 * Created by PhpStorm.
 * User: jhon.carvalho
 * Date: 04/09/2018
 * Time: 12:00
 */
 if(0){ ?>
    <script>
<?php } ?>
var msg55 = '<?php MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_55); ?>';
var msg46 = '<?php MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_46); ?>';
var msg56 = '<?php MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_56); ?>';

 function inicializar() {
        if ('<?= $_GET['acao'] ?>' == 'md_utl_adm_jornada_selecionar') {
            infraReceberSelecao();
            document.getElementById('btnFecharSelecao').focus();
        } else {
            infraEfeitoTabelas();
        }

        addEventoEnter();
    }

    function validarDataJornada(obj){
        var validar = infraValidarData(obj, false);
        if(!validar){
            alert(msg46);
            obj.value = '';
            obj.focus();
        }

        var dtInicio = infraGetElementById('txtDtInicio');
        var dtFim    = infraGetElementById('txtDtFim');
        if(dtInicio.value != '' && dtFim.value != ''){

            var dtTimeInicio = returnDateTime(dtInicio.value);
            var dtTimeFim    = returnDateTime(dtFim.value);

            var valido = (dtTimeInicio.getTime() <= dtTimeFim.getTime());

            if(!valido)
            {
                dtInicio.value = '';
                dtFim.value = '';
                alert(msg55);
                dtInicio.focus();
                return false;
            }
        }
    }

    function returnDateTime(valor){

        var valorArray = valor != '' ? valor.split(" ") : '';

        if(Array.isArray(valorArray)){
            var data = valorArray[0]
            data = data.split('/');
            var mes = parseInt(data[1]) - 1;

            var dataCompleta = new Date(data[2], mes  ,data[0], '00', '00', '00');
            return dataCompleta;
        }

        return false;
    }

    function addEventoEnter() {
        var obj1 = document.getElementById('txtNomeTpControle');
        var obj2 = document.getElementById('txtDescricaoTpControle');
        var obj3 = document.getElementById('txtDtInicio');
        var obj4 = document.getElementById('txtDtFim');
        obj1.addEventListener("keypress", function (evt) {
            addPesquisarEnter(evt);
        });

        obj2.addEventListener("keypress", function (evt) {
        addPesquisarEnter(evt);
        });

        obj3.addEventListener("keypress", function (evt) {
        addPesquisarEnter(evt);
        });

        obj4.addEventListener("keypress", function (evt) {
        addPesquisarEnter(evt);
        });
    }


    function addPesquisarEnter(evt) {
        var key_code = evt.keyCode ? evt.keyCode :
            evt.charCode ? evt.charCode :
                evt.which ? evt.which : void 0;

        if (key_code == 13) {
            pesquisar();
        }

    }

    function pesquisar(){
    document.getElementById('frmTpControleLista').action='<?= $strUrlPesquisar ?>';
    document.getElementById('frmTpControleLista').submit();
    }

    function desativar(id, desc) {
    if (confirm("Confirma desativação do Ajuste de Jornada \"" + desc + "\"?")) {
    document.getElementById('hdnInfraItemId').value = id;
    document.getElementById('frmTpControleLista').action = '<?= $strUrlDesativar ?>';
    document.getElementById('frmTpControleLista').submit();
    }
    }

    function reativar(id, desc){
    if (confirm("Confirma reativação do Ajuste de Jornada \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTpControleLista').action='<?= $strUrlReativar ?>';
    document.getElementById('frmTpControleLista').submit();
    }
    }

    function excluir(id, desc){
    if (confirm("Confirma exclusão do Ajuste de Jornada \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTpControleLista').action='<?= $strUrlExcluir ?>';
    document.getElementById('frmTpControleLista').submit();
    }
    }

    function novo(){
    <?php if(is_array($isGestor)){ ?>
    location.href="<?= $strUrlNovo ?>";
    <?php }else {?>
        alert(msg56);
    <?php } ?>
    }

    function imprimir(){
    infraImprimirTabela();
    }

    function fechar(){
    location.href="<?= $strUrlFechar ?>";
    }

<?php if(0){ ?>
<script>
<?php } ?>