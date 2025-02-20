| **Recurso**              | **Uso Ideal** | **Onde Roda?** | **Mais Adequado?** | **Suporta Dot-Walking?** |
|--------------------------|--------------|----------------|---------------------|--------------------------|
| **Business Rule**        | Executa no backend quando um registro é inserido, atualizado ou excluído. Bom para lógica de negócio. | **Servidor** | ❌ (Não recomendado para exibição em tempo real) | ✅ (Sem restrições) |
| **Script Include**       | Reutiliza funções JavaScript no backend. Útil para cálculos e chamadas externas. | **Servidor** | ✅ (Busca os dados para o Client Script) | ✅ (Sem restrições) |
| **Client Script**        | Executa no navegador do usuário. Bom para validações em tempo real. | **Cliente** | ✅ (Exibe a mensagem na interface) | ⚠️ (Apenas para campos de referência carregados no formulário via `g_form.getReference()`) |
| **UI Policy**            | Esconde ou torna campos obrigatórios sem precisar de código. | **Cliente** | ❌ (Não é útil para este caso) | ❌ (Não suporta dot-walking) |
| **Flow Designer**        | Automação sem código para aprovações, notificações e integrações simples. | **Servidor** | ❌ (Não se aplica ao caso) | ❌ (Não suporta dot-walking) |
| **ACLs (Access Control List)** | Controle de permissão para visualizar ou editar dados. | **Servidor** | ❌ (Só controla permissão, não busca dados) | ❌ (Não suporta dot-walking) |


Participei da expansão do ServiceNow para controle do CMDB, garantindo um melhor gerenciamento dos ativos de TI.

Implantei o cadastro de terceiros consumindo a API do Genesys, permitindo a sincronização eficiente de dados entre as plataformas.

Estudei sobre o Agent Virtual no ServiceNow e, apesar de ainda não ter tido a chance de colocar em prática, entendo bem como ele pode ajudar a automatizar processos e melhorar a experiência do usuário. Estou pronto para aplicar esse conhecimento assim que surgir uma oportunidade

Sou bem organizado e adoro documentar tudo o que faço. Isso tem me ajudado bastante no meu crescimento e aprendizado. Às vezes, quando volto nas minhas documentações, me pego pensando: 'Nossa, como eu escrevi um código assim?' 😅, mas é incrível ver o quanto evoluí e como esses registros ajudam a me corrigir e melhorar.

Criei um botão na Base de Conhecimento que gerava documentações técnicas de objetos criados a partir de UpdateSets ou modalidades/filas (como Catalog Items e Record Producers). O botão gerava um código em Markdown com todas as informações das alterações realizadas, e eu também criei um conversor de Markdown para HTML, o que facilitava a leitura e a visualização dessas documentações.

showdown.jsdbx se não me engano, é um gitHub
um outro colega de equipe fez a integração com o codemirror para poder escrever em markdown.
