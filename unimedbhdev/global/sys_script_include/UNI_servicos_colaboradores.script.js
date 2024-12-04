var UNI_servicos_colaboradores = Class.create();
UNI_servicos_colaboradores.prototype = Object.extendsObject(AbstractAjaxProcessor, {

    get_ficha_basica: function(tipoColaborador, codigoEmpresa, cpf, matricula) {
        if (!tipoColaborador) {
            tipoColaborador = this.getParameter('sysparm_tipoColaborador');
        }
        if (!codigoEmpresa) {
            codigoEmpresa = this.getParameter('sysparm_codigoEmpresa');
        }
        if (!cpf) {
            cpf = this.getParameter('sysparm_cpf');
        }
        if (!matricula) {
            matricula = this.getParameter('sysparm_matricula');
        }

        try {
            var r = new sn_ws.RESTMessageV2('scol ', 'ficha básica');
            var url = new UBHEnv('barramento').env.url;
            r.setStringParameterNoEscape('url', url);
            if (matricula)
                r.setStringParameterNoEscape('matricula', matricula);
            r.setStringParameterNoEscape('tipoColaborador', tipoColaborador);
            r.setStringParameterNoEscape('codigoEmpresa', codigoEmpresa);
            if (cpf)
                r.setStringParameterNoEscape('cpf', cpf);



            var response = r.execute();
            var responseBody = response.getBody();
            var httpStatus = response.getStatusCode();

            return response.getBody();

        } catch (ex) {
            var message = ex.message;
            gs.error(message, 'serviços colaboradores');
        }
    },
    get_planos: function() {

        try {
            var r = new sn_ws.RESTMessageV2('scol ', 'planos');
            var url = new UBHEnv('barramento').env.url;
            r.setStringParameterNoEscape('url', url);


            var response = r.execute();
            var responseBody = response.getBody();
            var httpStatus = response.getStatusCode();
            return response.getBody();
        } catch (ex) {
            var message = ex.message;
            gs.error(message, 'serviços colaboradores');
        }
    },
    get_colaborador_dependente: function(tipoColaborador, codigoEmpresa, matricula) {
        if (!tipoColaborador) {
            tipoColaborador = this.getParameter('sysparm_tipoColaborador');
        }
        if (!codigoEmpresa) {
            codigoEmpresa = this.getParameter('sysparm_codigoEmpresa');
        }
        if (!matricula) {
            matricula = this.getParameter('sysparm_matricula');
        }

        try {
            var r = new sn_ws.RESTMessageV2('scol ', 'dependentes_plano');
            var url = new UBHEnv('barramento').env.url;
            r.setStringParameterNoEscape('url', url);

            r.setStringParameterNoEscape('matricula', matricula);
            r.setStringParameterNoEscape('tipoColaborador', tipoColaborador);
            r.setStringParameterNoEscape('codigoEmpresa', codigoEmpresa);


            var response = r.execute();
            var responseBody = response.getBody();
            var httpStatus = response.getStatusCode();

            return response.getBody();

        } catch (ex) {
            var message = ex.message;
            gs.error(message, 'serviços colaboradores');
        }
    },
    get_ficha_complementar: function(matricula) {
        matricula = this.getParameter('sysparm_matricula');

        try {
            var r = new sn_ws.RESTMessageV2('scol ', 'ficha complementar');
            var url = new UBHEnv('barramento').env.url;
            r.setStringParameterNoEscape('url', url);
            r.setStringParameterNoEscape('matricula', matricula);

            var response = r.execute();
            var responseBody = response.getBody();
            var httpStatus = response.getStatusCode();

            if (httpStatus == '200')
                return response.getBody();

            gs.error(response.getBody(), 'serviços colaboradores');
        } catch (ex) {
            var message = ex.message;
            gs.error(message, 'serviços colaboradores');
        }
        return '';
    },
    //------------------------------------------------------
    get_escalas: function(codigoEscala, nomeEscala) {
        if (!codigoEscala) {
            codigoEscala = this.getParameter('sysparm_codigoEscala');
        }
        if (!nomeEscala) {
            nomeEscala = this.getParameter('sysparm_nomeEscala');
        }

        try {
            var r = new sn_ws.RESTMessageV2('scol ', 'escalas');
            var url = new UBHEnv('barramento').env.url;
            r.setStringParameterNoEscape('url', url);

            r.setStringParameterNoEscape('codigoescala', codigoEscala);
            r.setStringParameterNoEscape('nomeescala', nomeEscala);

            var response = r.execute();
            var responseBody = response.getBody();
            var httpStatus = response.getStatusCode();

            return response.getBody();

        } catch (ex) {
            var message = ex.message;
            gs.error(message, 'serviços colaboradores');
        }
    },
    put_editar_vale_combustivel: function(tipoColaborador, codigoEmpresa, dataInicio, matricula, kilometragem) {
        if (!tipoColaborador) {
            tipoColaborador = this.getParameter('sysparm_tipoColaborador');
        }
        if (!codigoEmpresa) {
            codigoEmpresa = this.getParameter('sysparm_codigoEmpresa');
        }
        if (!dataInicio) {
            dataInicio = this.getParameter('sysparm_dataInicio');
        }
        if (!matricula) {
            matricula = this.getParameter('sysparm_matricula');
        }
        if (!kilometragem) {
            kilometragem = this.getParameter('sysparm_kilometragem');
        }

        var source = this.getParameter('sysparm_source') || 'No Source';

        try {
            var r = new sn_ws.RESTMessageV2('scol ', 'editar vale combustivel');
            var url = new UBHEnv('barramento').env.url;
            r.setStringParameterNoEscape('url', url);
            if (matricula)
                r.setStringParameterNoEscape('matricula', matricula);
            if (tipoColaborador)
                r.setStringParameterNoEscape('tipoColaborador', tipoColaborador);
            if (codigoEmpresa)
                r.setStringParameterNoEscape('codigoEmpresa', codigoEmpresa);
            if (dataInicio)
                r.setStringParameterNoEscape('dataInicio', dataInicio);
            if (kilometragem)
                r.setStringParameterNoEscape('kilometragem', kilometragem);

            var response = r.execute();
            var requestBody = r.getRequestBody();
            var responseBody = response.getBody();
            var httpStatus = response.getStatusCode().toString();
            var errorMessage = response.getErrorMessage().toString();
            var result = {
                httpStatus: httpStatus,
                responseBody: responseBody
            };

            var log = 'Integração VT/VC ->' +
                '\nMethod: put_editar_vale_combustivel' +
                '\nSource: ' + source +
                '\nRequest Body: ' + requestBody +
                '\nHTTP Status: ' + httpStatus +
                '\nResponse Body: ' + responseBody +
                '\nError: ' + errorMessage || 'nenhum erro encontrado.';

            gs.log(log);

            return JSON.stringify(result);
        } catch (ex) {
            var message = 'Integração VT/VC ->' +
                '\nMethod: put_editar_vale_combustivel' +
                '\nSource: ' + source +
                '\nError: ' + ex.message || 'erro desconhecido.';

            gs.error(message, 'serviços colaboradores');
        }
    },
    put_editar_vale_transporte: function(tipoColaborador, codigoEmpresa, dataInicio, matricula, escalaValeTransporte) {
        if (!tipoColaborador) {
            tipoColaborador = this.getParameter('sysparm_tipoColaborador');
        }
        if (!codigoEmpresa) {
            codigoEmpresa = this.getParameter('sysparm_codigoEmpresa');
        }
        if (!dataInicio) {
            dataInicio = this.getParameter('sysparm_dataInicio');
        }
        if (!matricula) {
            matricula = this.getParameter('sysparm_matricula');
        }
        if (!escalaValeTransporte) {
            escalaValeTransporte = this.getParameter('sysparm_escalaValeTransporte');
        }

        var source = this.getParameter('sysparm_source') || 'No Source';

        try {
            var r = new sn_ws.RESTMessageV2('scol ', 'editar vale transporte');
            var url = new UBHEnv('barramento').env.url;
            r.setStringParameterNoEscape('url', url);
            if (matricula)
                r.setStringParameterNoEscape('matricula', matricula);
            if (tipoColaborador)
                r.setStringParameterNoEscape('tipoColaborador', tipoColaborador);
            if (codigoEmpresa)
                r.setStringParameterNoEscape('codigoEmpresa', codigoEmpresa);
            if (dataInicio)
                r.setStringParameterNoEscape('dataInicio', dataInicio);
            if (escalaValeTransporte)
                r.setStringParameterNoEscape('escalaValeTransporte', escalaValeTransporte);

            var response = r.execute();
            var requestBody = r.getRequestBody();
            var responseBody = response.getBody();
            var httpStatus = response.getStatusCode().toString();
            var errorMessage = response.getErrorMessage().toString();
            var result = {
                httpStatus: httpStatus,
                responseBody: responseBody
            };

            var log = 'Integração VT/VC ->' +
                '\nMethod: put_editar_vale_transporte' +
                '\nSource: ' + source +
                '\nRequest Body: ' + requestBody +
                '\nHTTP Status: ' + httpStatus +
                '\nResponse Body: ' + responseBody +
                '\nError: ' + errorMessage || 'nenhum erro encontrado.';

            gs.log(log);

            return JSON.stringify(result);
        } catch (ex) {
            var message = 'Integração VT/VC ->' +
                '\nMethod: put_editar_vale_transporte' +
                '\nSource: ' + source +
                '\nError: ' + ex.message || 'erro desconhecido.';

            gs.error(message, 'serviços colaboradores');
        }
    },
    post_criar_vale_transporte: function(tipoColaborador, codigoEmpresa, dataInicio, matricula, escalaValeTransporte) {
        if (!tipoColaborador) {
            tipoColaborador = this.getParameter('sysparm_tipoColaborador');
        }
        if (!codigoEmpresa) {
            codigoEmpresa = this.getParameter('sysparm_codigoEmpresa');
        }
        if (!dataInicio) {
            dataInicio = this.getParameter('sysparm_dataInicio');
        }
        if (!matricula) {
            matricula = this.getParameter('sysparm_matricula');
        }
        if (!escalaValeTransporte) {
            escalaValeTransporte = this.getParameter('sysparm_escalaValeTransporte');
        }

        var source = this.getParameter('sysparm_source') || 'No Source';

        try {
            var r = new sn_ws.RESTMessageV2('scol ', 'criar vale transporte');
            var url = new UBHEnv('barramento').env.url;
            r.setStringParameterNoEscape('url', url);
            if (matricula)
                r.setStringParameterNoEscape('matricula', matricula);
            if (tipoColaborador)
                r.setStringParameterNoEscape('tipoColaborador', tipoColaborador);
            if (codigoEmpresa)
                r.setStringParameterNoEscape('codigoEmpresa', codigoEmpresa);
            if (dataInicio)
                r.setStringParameterNoEscape('dataInicio', dataInicio);
            if (escalaValeTransporte)
                r.setStringParameterNoEscape('escalaValeTransporte', escalaValeTransporte);

            var response = r.execute();
            var requestBody = r.getRequestBody();
            var responseBody = response.getBody();
            var httpStatus = response.getStatusCode().toString();
            var errorMessage = response.getErrorMessage().toString();
            var result = {
                httpStatus: httpStatus,
                responseBody: responseBody
            };

            var log = 'Integração VT/VC ->' +
                '\nMethod: post_criar_vale_transporte' +
                '\nSource: ' + source +
                '\nRequest Body: ' + requestBody +
                '\nHTTP Status: ' + httpStatus +
                '\nResponse Body: ' + responseBody +
                '\nError: ' + errorMessage || 'nenhum erro encontrado.';

            gs.log(log);

            return JSON.stringify(result);
        } catch (ex) {
            var message = 'Integração VT/VC ->' +
                '\nMethod: post_criar_vale_transporte' +
                '\nSource: ' + source +
                '\nError: ' + ex.message || 'erro desconhecido.';

            gs.error(message, 'serviços colaboradores');
        }
    },
    post_criar_vale_combustivel: function(tipoColaborador, codigoEmpresa, dataInicio, matricula, kilometragem) {
        if (!tipoColaborador) {
            tipoColaborador = this.getParameter('sysparm_tipoColaborador');
        }
        if (!codigoEmpresa) {
            codigoEmpresa = this.getParameter('sysparm_codigoEmpresa');
        }
        if (!dataInicio) {
            dataInicio = this.getParameter('sysparm_dataInicio');
        }
        if (!matricula) {
            matricula = this.getParameter('sysparm_matricula');
        }
        if (!kilometragem) {
            kilometragem = this.getParameter('sysparm_kilometragem');
        }

        var source = this.getParameter('sysparm_source') || 'No Source';

        try {
            var r = new sn_ws.RESTMessageV2('scol ', 'criar vale combustivel');
            var url = new UBHEnv('barramento').env.url;
            r.setStringParameterNoEscape('url', url);
            if (matricula)
                r.setStringParameterNoEscape('matricula', matricula);
            if (tipoColaborador)
                r.setStringParameterNoEscape('tipoColaborador', tipoColaborador);
            if (codigoEmpresa)
                r.setStringParameterNoEscape('codigoEmpresa', codigoEmpresa);
            if (dataInicio)
                r.setStringParameterNoEscape('dataInicio', dataInicio);
            if (kilometragem)
                r.setStringParameterNoEscape('kilometragem', kilometragem);

            var response = r.execute();
            var requestBody = r.getRequestBody();
            var responseBody = response.getBody();
            var httpStatus = response.getStatusCode().toString();
            var errorMessage = response.getErrorMessage().toString();
            var result = {
                httpStatus: httpStatus,
                responseBody: responseBody
            };

            var log = 'Integração VT/VC ->' +
                '\nMethod: post_criar_vale_combustivel' +
                '\nSource: ' + source +
                '\nRequest Body: ' + requestBody +
                '\nHTTP Status: ' + httpStatus +
                '\nResponse Body: ' + responseBody +
                '\nError: ' + errorMessage || 'nenhum erro encontrado.';

            gs.log(log);

            return JSON.stringify(result);
        } catch (ex) {
            var message = 'Integração VT/VC ->' +
                '\nMethod: post_criar_vale_combustivel' +
                '\nSource: ' + source +
                '\nError: ' + ex.message || 'erro desconhecido.';

            gs.error(message, 'serviços colaboradores');
        }
    },
    delete_vale_transporte: function(codigoEmpresa, tipoColaborador, matricula) {
        try {
            // Recupera os parâmetros se não forem fornecidos diretamente
            codigoEmpresa = codigoEmpresa || this.getParameter('sysparm_codigoEmpresa');
            tipoColaborador = tipoColaborador || this.getParameter('sysparm_tipoColaborador');
            matricula = matricula || this.getParameter('sysparm_matricula');

            // Validação básica dos parâmetros obrigatórios
            if (!codigoEmpresa || !tipoColaborador || !matricula) {
                throw new Error('Parâmetros obrigatórios não fornecidos: códigoEmpresa, tipoColaborador, matricula.');
            }

            // Configura a chamada do RESTMessageV2
            var r = new sn_ws.RESTMessageV2('scol', 'cancela vale transporte');
            var url = new UBHEnv('barramento').env.url;
            r.setStringParameterNoEscape('url', url);
            r.setStringParameterNoEscape('matricula', matricula);
            r.setStringParameterNoEscape('tipoColaborador', tipoColaborador);
            r.setStringParameterNoEscape('codigoEmpresa', codigoEmpresa);

            // Executa o RESTMessageV2
            var response = r.execute();
            var result = {
                httpStatus: response.getStatusCode().toString(),
                responseBody: response.getBody(),
                requestBody: r.getRequestBody(),
                errorMessage: response.getErrorMessage() || null
            };

            // Log detalhado para auditoria
            gs.log('Integração VT/VC ->' +
                '\nMethod: delete_vale_transporte' +
                '\nRequest Body: ' + result.requestBody +
                '\nHTTP Status: ' + result.httpStatus +
                '\nResponse Body: ' + result.responseBody +
                '\nError Message: ' + (result.errorMessage || 'nenhum erro encontrado.'),
                'servicos_colaboradores');

            // Retorna o resultado como string JSON
            return JSON.stringify(result);

        } catch (ex) {
            // Tratamento de erro
            var errorResponse = {
                httpStatus: '500',
                responseBody: '',
                errorMessage: ex.message || 'Erro desconhecido na execução.'
            };

            // Log do erro
            gs.error('Erro na integração VT/VC ->' +
                '\nMethod: delete_vale_transporte' +
                '\nError Message: ' + errorResponse.errorMessage,
                'servicos_colaboradores');

            // Retorna o erro como string JSON
            return JSON.stringify(errorResponse);
        }
    },
	delete_vale_combustivel: function(codigoEmpresa, tipoColaborador, matricula) {
        try {
            // Recupera os parâmetros se não forem fornecidos diretamente
            codigoEmpresa = codigoEmpresa || this.getParameter('sysparm_codigoEmpresa');
            tipoColaborador = tipoColaborador || this.getParameter('sysparm_tipoColaborador');
            matricula = matricula || this.getParameter('sysparm_matricula');

            // Validação básica dos parâmetros obrigatórios
            if (!codigoEmpresa || !tipoColaborador || !matricula) {
                throw new Error('Parâmetros obrigatórios não fornecidos: códigoEmpresa, tipoColaborador, matricula.');
            }

            // Configura a chamada do RESTMessageV2
            var r = new sn_ws.RESTMessageV2('scol', 'cancela vale combustivel');
            var url = new UBHEnv('barramento').env.url;
            r.setStringParameterNoEscape('url', url);
            r.setStringParameterNoEscape('matricula', matricula);
            r.setStringParameterNoEscape('tipoColaborador', tipoColaborador);
            r.setStringParameterNoEscape('codigoEmpresa', codigoEmpresa);

            // Executa o RESTMessageV2
            var response = r.execute();
            var result = {
                httpStatus: response.getStatusCode().toString(),
                responseBody: response.getBody(),
                requestBody: r.getRequestBody(),
                errorMessage: response.getErrorMessage() || null
            };

            // Log detalhado para auditoria
            gs.log('Integração VT/VC ->' +
                '\nMethod: delete_vale_combustivel' +
                '\nRequest Body: ' + result.requestBody +
                '\nHTTP Status: ' + result.httpStatus +
                '\nResponse Body: ' + result.responseBody +
                '\nError Message: ' + (result.errorMessage || 'nenhum erro encontrado.'),
                'servicos_colaboradores');

            // Retorna o resultado como string JSON
            return JSON.stringify(result);

        } catch (ex) {
            // Tratamento de erro
            var errorResponse = {
                httpStatus: '500',
                responseBody: '',
                errorMessage: ex.message || 'Erro desconhecido na execução.'
            };

            // Log do erro
            gs.error('Erro na integração VT/VC ->' +
                '\nMethod: delete_vale_combustivel' +
                '\nError Message: ' + errorResponse.errorMessage,
                'servicos_colaboradores');

            // Retorna o erro como string JSON
            return JSON.stringify(errorResponse);
        }
    },
    //------------------------------------------------------
    get_cargo: function(user) {
        user = this.getParameter('sysparm_user');
        var gr = new GlideRecord('sys_user');
        gr.addQuery('sys_id', user);
        gr.query();

        if (gr.next()) {
            return gr.u_cargo;
        }

    },
    type: 'UNI_servicos_colaboradores'
});