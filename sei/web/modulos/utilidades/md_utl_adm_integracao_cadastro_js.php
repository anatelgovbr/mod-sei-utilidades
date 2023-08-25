<script type="text/javascript">
    //variaveis globais
    var contNewReg        = 0;
    var strJson           = null;
    var isAlterar         = false;
    var idHeaderAlt       = null;
    var isREST            = document.querySelector('#hdnIsRest').value == 's' ? true : false;
    var isTelaAcaoAlterar = document.querySelector('#hdnTipoAcao').value == 'alterar' ? true : false;
    var isTpIntegOrigem   = document.querySelector('[name="rdnTpIntegracao"]').value;
    var vlrSelOperacao    = isTelaAcaoAlterar ? "<?= $vlrSelOperacao ?>" : '';
    var msgDef            = '<?= MdUtlMensagemINT::getMensagem( MdUtlMensagemINT::$MSG_UTL_11 ) ?>';

    var objOperacao       = {
        operacao: null,
        validado:false
    };

    var objTipoAcao       = {
        alterar: 'alterar',
        excluir: 'excluir'
    };

    /* *****************************
		* Codigo Jquery
		* ******************************/
    $('[name="rdnTpIntegracao"]').click(function() {
        if ( $(this).val() == "<?= MdUtlAdmIntegracaoRN::$TP_INTEGRACAO_SEM_AUTENTICACAO ?>" ) {
            $('.dvConteudo').hide();
            $('.selREST,.selSOAP').hide();
            isREST = false;
        } else {
            if ( $(this).val() == "<?= MdUtlAdmIntegracaoRN::$TP_INTEGRACAO_REST ?>" ) {
                $('.dvConteudo').show();
                $('.selREST').show();
                $('.selSOAP').hide();
                $('#divHeader').show();
                isREST = true;

                if ( isTelaAcaoAlterar && isTpIntegOrigem == "<?= MdUtlAdmIntegracaoRN::$TP_INTEGRACAO_SEM_AUTENTICACAO ?>" ) validarMapear();
            } else {
                alert('<?= MdUtlMensagemINT::$MSG_UTL_127 ?>');
                return false;
                $('.dvConteudo').hide();
                $('.selREST').hide();
                $('.selSOAP').hide();
                $('#divHeader').hide();
                isREST = false;
            }
        }
    });

    $('#selMetodoAutenticacao').change(function() {
        hideShowToken( this );
    });

    $('#selFuncionalidade').change(function (){
        let idFunc    = $( this ).val();
        let metAutent = $('#selMetodoAutenticacao');

        habilitarOuNaoCampos( idFunc );

        if( metAutent.val() != '' ){
            hideShowToken( metAutent );
        }
    });

    $('#selMetodoRequisicao').change( function(){
        if( $(this).val() != <?= MdUtlAdmIntegracaoRN::$REQUISICAO_POST ?> ){
            alert('O Método da Requisição "'+ $('option:selected',this).text() +'" ainda não está disponível nesta versão.');
            $(this).val(<?= MdUtlAdmIntegracaoRN::$REQUISICAO_POST ?>);
            return false;
        }
    });

    $('#selFormato').change( function(){
        if( $(this).val() != <?= MdUtlAdmIntegracaoRN::$FORMATO_JSON ?> ){
            alert('O Formato do Retorno da Operação "'+ $('option:selected',this).text() +'" ainda não está disponível nesta versão.');
            $(this).val(<?= MdUtlAdmIntegracaoRN::$FORMATO_JSON ?>);
            return false;
        }
    });

    function hideShowToken( metAutent ){
        let tpFuncionalidade = $('#selFuncionalidade').val();
        let table            = `#tbDadosEntrada${tpFuncionalidade}`;
        let ident            = "<?= MdUtlAdmIntegracaoRN::$ARR_IDENTIFICADORES['token'] ?>";

        if ( $( metAutent ).val() == <?= MdUtlAdmIntegracaoRN::$AUT_BODY_TOKEN ?> ) {
            $(table).find(`tbody > tr.${ident}`).show(); // habilita o Token
            $(`#rowTokenAut${tpFuncionalidade}`).show(); // habilita a row do input Conteudo Autenticacao
        } else {
            let row = $(table).find(`tbody > tr.${ident}`).hide(); // desabilita a row do Token
            $( row ).find('td:last > select').val(''); // reseta o valor do select Token

            $(`#rowTokenAut${tpFuncionalidade}`).hide(); // desabilita a row do Conteudo Autenticacao
            $(`#txtTokenAut${tpFuncionalidade}`).val(''); // reseta o valor do Conteudo Autenticacao
        }
    }

    function habilitarOuNaoCampos( idFunc ){
        let eleSaida   = `#dvDadosSaida${idFunc}`;
        let eleEntrada = `#dvDadosEntrada${idFunc}`;
        let habilitar   = idFunc;
        let desabilitar = null;

        $('.dvDadosSaida').hide();
        $('.dvDadosEntrada').hide();
        $( eleSaida ).show();
        $( eleEntrada ).show();

        if ( idFunc == <?= MdUtlAdmIntegracaoRN::$CHEFIA ?> )
            desabilitar = <?= MdUtlAdmIntegracaoRN::$AUSENCIA ?>;
        else
            desabilitar = <?= MdUtlAdmIntegracaoRN::$CHEFIA ?>;

        $('[dataform="entrada'+desabilitar+'"]').prop('disabled',true);
        $('[dataform="entrada'+habilitar+'"]').prop('disabled',false);

        $('[dataform="saida'+desabilitar+'"]').prop('disabled',true);
        $('[dataform="saida'+habilitar+'"]').prop('disabled',false);
    }

    /* *****************************
    * Codigo Javascript
    * ******************************/
    function inicializar() {
        iniciarTabelaDinamicaHeader();
        infraEfeitoTabelas( true );

        switch ( document.querySelector('#hdnTipoAcao').value ){
            case 'cadastrar':
                document.querySelector('#txtNome').focus();
                break;
            case 'alterar':
                let tpIntegracao = $('[name="rdnTpIntegracao"]:checked').val();
                if ( tpIntegracao != "<?= MdUtlAdmIntegracaoRN::$TP_INTEGRACAO_SEM_AUTENTICACAO ?>" ) validarMapear();
                let idFunc = $('#selFuncionalidade').val();
                habilitarOuNaoCampos( idFunc );
                break;
            case 'consultar':
                infraDesabilitarCamposAreaDados();
                ocultarColunaAcoes('tblHeaders');
                $('.btnFormulario').hide();
                break;
        }

        // verifica se tem que exibir o input Conteúdo de Autenticação:
        if (document.querySelector('#selMetodoAutenticacao').value == <?= MdUtlAdmIntegracaoRN::$AUT_BODY_TOKEN ?>) {
            $('#rowTokenAut').show();
        }
    }

    function OnSubmitForm() {
        return validarCadastro();
    }

    function validarCadastro() {
        let tpInteg = document.querySelector('input[name="rdnTpIntegracao"]:checked');
        let func    = document.querySelector('#selFuncionalidade').value;

        if (infraTrim(document.querySelector('#selFuncionalidade').value) == '') {
            alert(setMensagemPersonalizada(msgDef, ['Funcionalidade']));
            document.querySelector('#selFuncionalidade').focus();
            return false;
        }

        if (infraTrim(document.querySelector('#txtNome').value) == '') {
            alert(setMensagemPersonalizada(msgDef, ['Nome']));
            document.querySelector('#txtNome').focus();
            return false;
        }

        if ( tpInteg == null )
        {
            alert(setMensagemPersonalizada(msgDef, ['Tipo de Integração']));
            return false;
        }
        else if ( tpInteg.value == '<?= MdUtlAdmIntegracaoRN::$TP_INTEGRACAO_REST ?>' )
        {
            if (infraTrim(document.querySelector('#txtUrlServico').value) == '') {
                alert(setMensagemPersonalizada(msgDef, ['URL WebService']));
                document.querySelector('#txtUrlServico').focus();
                return false;
            }

            if (infraTrim(document.querySelector('#txtUrlDefServico').value) == '') {
                alert(setMensagemPersonalizada(msgDef, ['URL de Definição de Serviço']));
                document.querySelector('#txtUrlDefServico').focus();
                return false;
            }

            // preenchimento das combos entrada/saida que sao obrigatorios
            let nmCampo = `.obrigatorio${func}`;
            let valid   = true;
            const itens = document.querySelectorAll(nmCampo);

            if (itens.length > 0) {
                itens.forEach((v, i) => {
                    if (v.value == '') {
                        v.focus();
                        alert('Campo obrigatório não preenchido.');
                        valid = false;
                        return;
                    }
                });

                if (!valid) return false;
            }
        }
        else if( tpInteg.value == '<?= MdUtlAdmIntegracaoRN::$TP_INTEGRACAO_SOAP ?>' )
        {
            alert('<?= MdUtlMensagemINT::$MSG_UTL_127 ?>');
            return false;
        }

        if (!validarPreenchimentoHeaderAutentication()) {
            alert('<?= MdUtlMensagemINT::$MSG_UTL_128 ?>');
            return false;
        }

        if (!validarPreenchimentoConteudoAutenticacao( func )) {
            alert('<?= MdUtlMensagemINT::$MSG_UTL_129 ?>');
            return false;
        }

        return true;
    }

    function validarPreenchimentoHeaderAutentication()
    {
        let retorno = false;

        if (document.querySelector('#selMetodoAutenticacao').value == <?= MdUtlAdmIntegracaoRN::$AUT_HEADER_TOKEN ?>) {
            let qtd = document.querySelector('#tblHeaders').rows.length;
            let linha;
            for ( let i = 1 ; i < qtd ; i++ ) {
                linha = document.querySelector('#tblHeaders').rows[i];
                let dadoRestrito = $.trim( linha.cells[3].innerText );
                if (dadoRestrito == 'Sim') {
                    retorno = true;
                }
            }
        } else {
            retorno = true;
        }

        return retorno;
    }

    function validarPreenchimentoConteudoAutenticacao( func )
    {
        let retorno = false;
        if (document.querySelector('#selMetodoAutenticacao').value == <?= MdUtlAdmIntegracaoRN::$AUT_BODY_TOKEN ?> &&
             document.querySelector(`#txtTokenAut${func}`).value == '') {
            retorno = false;
        } else {
            retorno = true;
        }
        return retorno;
    }

    function validarPreenchimentoObrig(){
        const itens = document.querySelectorAll('.obrigatorio');
        itens.forEach( ( v , i ) => {
            if ( v.value == '' ) {
                alert('Campo obrigatório não preenchido.');
                v.focus();
                return false;
            }
        });
        return true;
    }

    function getSelectedText( el ){
        if ( el.selectedIndex === -1 ) return null;
        return el.options[el.selectedIndex].text;
    }

    function iniciarTabelaDinamicaHeader() {

        objTabelaDinamicaHeaders = new infraTabelaDinamica('tblHeaders', 'hdnTbHeaders', true, true);

        objTabelaDinamicaHeaders.gerarEfeitoTabela = true;

        if (objTabelaDinamicaHeaders.hdn.value != '') objTabelaDinamicaHeaders.recarregar();

        objTabelaDinamicaHeaders.procuraLinha = function( id ) {
            let qtd = document.querySelector('#tblHeaders').rows.length;;
            let linha;

            for ( let i = 1 ; i < qtd ; i++ ) {
                linha = document.querySelector('#tblHeaders').rows[i];
                let valorLinha = $.trim( linha.cells[0].innerText );
                if ( valorLinha == id ) return i;
            }
            return null;
        };

        objTabelaDinamicaHeaders.alterar = function( id ) {
            editarHeader( id[0] );
        };
    }

    function adicionarHeaderTable() {

        let idHeader     = idHeaderAlt !== null ? idHeaderAlt : 'novo_' + contNewReg;
        let atributo     = document.querySelector('#atributoHeader').value;
        let conteudo     = document.querySelector('#conteudoHeader').value;
        let dadoRestrito = document.querySelector('#ckbDadoRestrito').checked ? 'Sim' : 'Não';
        let objRetorno   = {
            atributo: atributo,
            conteudo: conteudo,
            cript: dadoRestrito == 'Sim' ? "<?= MdUtlAdmIntegracaoRN::$INFO_RESTRITO ?>" : conteudo
        };

        if ( atributo == '' || conteudo == '' ) {
            alert('Faltou preencher os campos Atributo ou Conteúdo.');
            return false;
        }

        let arrLinha = [
            idHeader,
            objRetorno.atributo,
            objRetorno.cript,
            dadoRestrito,
            objRetorno.conteudo
        ];

        // caso seja null, é um novo registro
        if( idHeaderAlt === null ) contNewReg += 1;

        if( isAlterar ){
            let row = objTabelaDinamicaHeaders.procuraLinha( idHeader );
            objTabelaDinamicaHeaders.removerLinha( row );
            isAlterar = false;
        }

        objTabelaDinamicaHeaders.adicionar( arrLinha );

        idHeaderAlt = null;

        limparCamposHeader();
    }

    function editarHeader( idHeader ) {
        objTabelaDinamicaHeaders.flagAlterar = true;
        let dadosHeader         = null;
        let hdnListaHeadersPart = objTabelaDinamicaHeaders.hdn.value;
        let arrListaHeadersPart = hdnListaHeadersPart.split('¥');

        for ( let i = 0 ; i < arrListaHeadersPart.length ; i++ ) {
            let hdnListaHeadPart = arrListaHeadersPart[i].split('±');
            if ( hdnListaHeadPart[0] == idHeader ) {
                idHeaderAlt = idHeader;
                dadosHeader = hdnListaHeadPart;
                break;
            }
        }

        document.querySelector('#atributoHeader').value = dadosHeader[1];
        document.querySelector('#conteudoHeader').value = dadosHeader[3] == 'Sim' ? '' : dadosHeader[4];

        if ( dadosHeader[3] == 'Sim' ){
            document.querySelector('#ckbDadoRestrito').checked = true;
            document.querySelector('#ckbDadoRestrito').setAttribute('checked',true);
        } else {
            document.querySelector('#ckbDadoRestrito').checked = false;
            document.querySelector('#ckbDadoRestrito').setAttribute('checked',false);
        }

        isAlterar = true;
    }

    function limparCamposHeader() {
        const list_inputs = document.querySelectorAll('.input_header');
        list_inputs.forEach( elem => {
            if ( elem.type == 'text' )
                elem.value = null;
            else if ( elem.type == 'checkbox' )
                elem.checked = false;
        });
    }

    /*
    * Função acionada pelo clique do botao # Mapear #
    * */
    function validarMapear(){
        if (infraTrim(document.querySelector('#txtUrlDefServico').value) == '') {
            alert(setMensagemPersonalizada(msgDef, ['URL Definição do Serviço']));
            document.querySelector('#txtUrlDefServico').focus();
            return false;
        }

        if (infraTrim(document.querySelector('#txtUrlServico').value) == '') {
            alert(setMensagemPersonalizada(msgDef, ['URL do Endpoint da Operação']));
            document.querySelector('#txtUrlServico').focus();
            return false;
        }

        let tpFuncionalidade = document.querySelector('#selFuncionalidade').value;
        let urlServico       = document.querySelector('#txtUrlServico').value;
        let arrUrlServico    = urlServico.split('/');

        objOperacao.operacao = arrUrlServico.pop();
        objOperacao.validado = false;

        //executa consulta no arquivo .json, definido no campo: URL Definição do Serviço
        validarWebService();
    }

    function validarWebService() {
        let tpInteg = document.querySelectorAll('input[name="rdnTpIntegracao"]:checked');
        let params  = {
            urlServico: document.querySelector('#txtUrlDefServico').value,
            tipoWs: '',
            definirServico:'',
            versaoSoap: '',
            tipoRequisicao: '',
            retorno: ''
        };

        if ( tpInteg[0].value == "<?= MdUtlAdmIntegracaoRN::$TP_INTEGRACAO_REST ?>" ) {
            params.tipoWs         = 'REST';
            params.tipoRequisicao = 'GET';
            params.retorno        = 'JSON';
            params.definirServico = true;
        } else {
            params.tipoWs     = 'SOAP';
            params.versaoSoap = document.querySelector('#selVersaoSOAP').value;
            params.retorno    = 'XML';
        }
        buscarOperacoeWs( params );
    }

    function buscarOperacoeWs( parametros ) {
        let path = "<?= $strLinkValidarWsdl ?>";
        $.ajax({
            type: "POST",
            url: path,
            dataType: 'xml',
            data: parametros,
            beforeSend: function() {
                infraExibirAviso(false);
            },
            success: function(result) {
                montaOperacao(result);
            },
            error: function(msgError) {
                msgCommit = "Erro ao processar o XML do SEI: " + msgError.responseText;
            },
            complete: function(result,opt) {
                infraAvisoCancelar();
            }
        });
    }

    function montaOperacao( result ) {
        if ( $(result).find('erros').length > 0 ) {
            console.error($(result).find('erro').attr('descricao'));
            return false;
        }

        let nmOperacao = '/' + objOperacao.operacao;

        //recupera o json que retorna do servico com os dados das operacoes existentes e dados de entrada/saida de cada operacao
        strJson = JSON.parse($(result).find('json').text());

        //valida se a operacao informada existe no arquivo .Json
        $.each( $(result).find('operacao'), function(key, value) {
            objOperacao.validado = true;
        });

        if( !objOperacao.validado ) return objOperacao.validado;

        showDadosTela();
    }

    function showDadosTela(){
        let tpFuncionalidade = document.querySelector('#selFuncionalidade').value;

        if ( objOperacao.validado === false ) {
            alert('Operação Inválida.');
            return false;
        }

        const txt                  = '/' + objOperacao.operacao;
        const txtSelEntrada        = `selDadosEntrada${tpFuncionalidade}`;
        const txtSelSaida          = `selDadosSaida${tpFuncionalidade}`;
        const combosEntrada        = document.querySelectorAll('[name^='+ txtSelEntrada +']');
        const combosSaida          = document.querySelectorAll('[name^='+ txtSelSaida +']');
        const arrLoopEntrada       = strJson.operacoes[txt].parametros.entrada.valores;
        const arrLoopSaida         = strJson.operacoes[txt].parametros.saida.valores;
        let dadosEntradaSaidaBanco = null;
        let ctrlLoop               = 1;
        let textoCampo             = '';

        if( isTelaAcaoAlterar ) dadosEntradaSaidaBanco = JSON.parse( '<?= $strDados ?>' );

        // Monta as combos de Entrada, mas primerio, eh removido os options, caso existam de outra ou da mesma operacao
        combosEntrada.forEach( ( v , i ) => {
            let opt          = document.createElement('option');
            v.options.length = 0;
            opt.value        = '';
            opt.innerHTML    = 'Selecione';
            v.appendChild( opt );
        });

        combosEntrada.forEach( ( v , i ) => { // loop nos elementos(combo) de entrada
            textoCampo = $( v ).closest('tr').find('td:eq(0) > label').text().trim();
            arrLoopEntrada.forEach( ( _v , _i ) => { // loop nos valores das options de cada combo
                let opt       = document.createElement('option');
                opt.value     = _v;
                opt.innerHTML = _v;

                if ( dadosEntradaSaidaBanco !== null ) {
                    dadosEntradaSaidaBanco.forEach( ( a , b ) => {
                        if( a.TpParametro == 'E' && textoCampo.trim() == a.nome.trim() && _v.trim() == a.nomeCampo.trim() ) opt.selected = true;
                    });
                }
                v.appendChild( opt );
            });
        });

        // Monta as combos de Saida, mas primerio, eh removido os options, caso existam de outra ou da mesma operacao
        combosSaida.forEach( ( v , i ) => {
            let opt          = document.createElement('option');
            v.options.length = 0;
            opt.value        = '';
            opt.innerHTML    = 'Selecione';
            v.appendChild( opt );
        });

        // Monta as combos de Saida
        combosSaida.forEach( ( v , i ) => { // loop nos elementos(combo) de saida
            textoCampo = $( v ).closest('tr').find('td:eq(0) > label').text().trim();
            arrLoopSaida.forEach( ( _v , _i ) => { // loop nos valores das options de cada combo
                let opt       = document.createElement('option');
                opt.value     = _v;
                opt.innerHTML = _v;
                if ( dadosEntradaSaidaBanco !== null ) {
                    dadosEntradaSaidaBanco.forEach( ( a , b ) => {
                        if( a.TpParametro == 'S' && textoCampo.trim() == a.nome.trim() && _v.trim() == a.nomeCampo.trim() ) opt.selected = true;
                    });
                }
                v.appendChild( opt );
            });
        });
    }
</script>