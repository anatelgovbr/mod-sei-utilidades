<script type="text/javascript">
        var msg71 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_71); ?>';
        var msg73 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_73); ?>';
        var msg75 = '<?= MdUtlMensagemINT::getMensagem(MdUtlMensagemINT::$MSG_UTL_75); ?>';

        function inicializar() {
            if ('<?=$_GET['acao']?>' == 'md_utl_adm_just_prazo_selecionar') {
                infraReceberSelecao();
                document.getElementById('btnFecharSelecao').focus();
            } else {
                document.getElementById('btnFechar').focus();
            }
            infraEfeitoTabelas(true);
        }

        <? if ($bolAcaoDesativar){ ?>
            function acaoDesativar(id, desc) {
                var msg = setMensagemPersonalizada(msg71, ['Justificativa de Ajuste de Prazo', desc]);
                if (confirm(msg)) {
                    document.getElementById('hdnInfraItemId').value = id;
                    document.getElementById('frmMdUtlAdmJustPrazoLista').action = '<?=$strLinkDesativar?>';
                    document.getElementById('frmMdUtlAdmJustPrazoLista').submit();
                }
            }
        <? } ?>

        <? if ($bolAcaoReativar){ ?>
            function acaoReativar(id, desc) {
                var msg = setMensagemPersonalizada(msg73, ['Justificativa de Ajuste de Prazo', desc]);
                if (confirm(msg)) {
                    document.getElementById('hdnInfraItemId').value = id;
                    document.getElementById('frmMdUtlAdmJustPrazoLista').action = '<?=$strLinkReativar?>';
                    document.getElementById('frmMdUtlAdmJustPrazoLista').submit();
                }
            }
        <? } ?>

        <? if ($bolAcaoExcluir){ ?>
            function acaoExcluir(id, desc) {
                $.ajax({
                    url: '<?= $strLinkValidarExcluir?>',
                    type: 'post',
                    data: { id: id },
                    dataType: 'xml'
                })
                .done( function( rs ){
                    if( $( rs ).find('Resultado').text() == 'N' ){
                        alert('Não é possível excluir esta Justificativa, pois está vinculada há uma Solicitação de Ajuste de Prazo.');
                    }else{
                        execExcluir(id, desc);
                    }
                })
                .fail( function( xhr ){
                    console.error('Erro ao processar o XML do SEI: ' + xhr.responseText);
                });
            }

            function execExcluir( id, desc ){
                var msg = setMensagemPersonalizada(msg75, ['Justificativa de Ajuste de Prazo', desc]);
                if (confirm(msg)) {
                    document.getElementById('hdnInfraItemId').value = id;
                    document.getElementById('frmMdUtlAdmJustPrazoLista').action = '<?=$strLinkExcluir?>';
                    document.getElementById('frmMdUtlAdmJustPrazoLista').submit();
                }
            }
        <? } ?>
</script>