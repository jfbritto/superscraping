$(document).ready(function () {

    indicesDisponiveis();

    setInterval(() => {
        indicesDisponiveis();
    }, 600000); // busca a cada 5 minutos

    function indicesDisponiveis()
    {
        console.log('Função chamada...');
        var dataAtual = new Date().getTime(); // Obtém o timestamp atual em milissegundos
        var tempoValidade = 10 * 60 * 1000; // Converte minutos para milissegundos

        // Recuperando dado do localStorage
        var indicesDisponiveis = JSON.parse(localStorage.getItem("indicesDisponiveis"));

        if (indicesDisponiveis) {
            let diferenca = dataAtual - indicesDisponiveis.data
            if (indicesDisponiveis !== null) {
                if (diferenca < tempoValidade) {
                    console.log('Tinha cache...');
                    habilitandoIcones(indicesDisponiveis.dado)
                    return false
                }
            }
        }
        console.log('Fazendo scraping...');

        $.ajax({
            url: '/indicesdisponiveis/json',
            method: 'GET',
            dataType: 'json',
            success: function(response) {

                var dadoComValidade = {
                    data: dataAtual,
                    dado: response
                };

                localStorage.setItem("indicesDisponiveis", JSON.stringify(dadoComValidade));

                habilitandoIcones(response)
            },
            error: function(xhr, status, error) {
                // Lidar com erros na solicitação
                console.error('Erro na solicitação: ' + status);
            }
        });

    }

    function habilitandoIcones(response)
    {
        console.log('Escrevendo na tela...');

        if (response.tjsp) $("#icon-tjsp-true").show(); else $("#icon-tjsp-false").show();
        if (response.ortn) $("#icon-ortn-true").show(); else $("#icon-ortn-false").show();
        if (response.ufir) $("#icon-ufir-true").show(); else $("#icon-ufir-false").show();
        if (response.caderneta) $("#icon-caderneta-true").show(); else $("#icon-caderneta-false").show();
        if (response.igpdi) $("#icon-igpdi-true").show(); else $("#icon-igpdi-false").show();
        if (response.igpm) $("#icon-igpm-true").show(); else $("#icon-igpm-false").show();
        if (response.inpc) $("#icon-inpc-true").show(); else $("#icon-inpc-false").show();
        if (response.ipca) $("#icon-ipca-true").show(); else $("#icon-ipca-false").show();
        if (response.selic) $("#icon-selic-true").show(); else $("#icon-selic-false").show();
        if (response.ipc) $("#icon-ipc-true").show(); else $("#icon-ipc-false").show();
        if (response.ipcfgv) $("#icon-ipcfgv-true").show(); else $("#icon-ipcfgv-false").show();
        if (response.tr) $("#icon-tr-true").show(); else $("#icon-tr-false").show();
        if (response.tjmg) $("#icon-tjmg-true").show(); else $("#icon-tjmg-false").show();
        if (response.cubsp) $("#icon-cubsp-true").show(); else $("#icon-cubsp-false").show();
    }


});
