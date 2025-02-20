function limpaCampos() {
    document.getElementById("descricao").value = '';
    document.getElementById("quantasVezes").value = '';
    document.getElementById("valorTotalCompra").value = '';
    document.getElementById("valorParcela").value = '';
}

function verificaPreenchimento() {
    var check = document.querySelector('input[name=radioParc]:checked').value;
    var descricao = document.getElementById("descricao").value;
    var quantasVezes = document.getElementById("quantasVezes").value;
    var valorTotalCompra = document.getElementById("valorTotalCompra").value;
    var valorParcela = document.getElementById("valorParcela").value;
    
    if (descricao != '' && check == 'Sim' && quantasVezes >= 1 && valorTotalCompra != '' && valorParcela != '' && parseFloat(valorTotalCompra) >= quantasVezes || parseFloat(valorTotalCompra) >= quantasVezes && descricao != '' && check == 'Nao' && valorParcela != '') {
        return true;
    } else {
        return false;
    }
}

function valorExtratoCard() {
    var total = $('.box').get().reduce(function(tot, el) {
        var numero = el.innerHTML.split('.').join('').split(',').join('.');
        
        return tot + Number(numero);
    }, 0);
    $('#fatcredicard').html(total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
}

function adicionaLinha(idTabela) {
    if (verificaPreenchimento()) {
        var tabela = document.getElementById(idTabela);
        var numeroLinhas = tabela.rows.length;
        var linha = tabela.insertRow(numeroLinhas);

        var celula1 = linha.insertCell(0);
        var celula2 = linha.insertCell(1);   
        var celula3 = linha.insertCell(2); 
        var celula4 = linha.insertCell(3); 
        var celula5 = linha.insertCell(4);
        var celula6 = linha.insertCell(5);

        celula1.innerHTML = document.getElementById("descricao").value; 
        celula2.innerHTML = document.querySelector('input[name="radioParc"]:checked').value;
        celula3.innerHTML = document.getElementById("quantasVezes").value;
        celula4.innerHTML = document.getElementById("valorTotalCompra").value
        celula5.innerHTML = document.getElementById("valorParcela").value;
        celula6.innerHTML = "<button class='btn btn-danger btn-circle btn-sm' onclick='removeLinha(this)'><i class='fas fa-trash'></i></button>";

        //Adiciona Classe no <td>
        for (let index = 2; index <= numeroLinhas; index++) {
            var elementValor = document.querySelector('#dataTable > tbody > tr:nth-child('+index+') > td:nth-child(5)');
            elementValor.classList.add("box");
        }
        totalizaColunaExtrato();
        valorExtratoCard();
        limpaCampos();
    } else {
        alert("Preencha os dados corretamente!");
    }
}

function divideParcelas() {
    var quantasVezes = document.getElementById("quantasVezes").value;
    var valorTotalCompra = document.getElementById("valorTotalCompra").value;
    var total = 0;
    
    if(parseInt(quantasVezes) >= 1 && valorTotalCompra != '' && parseFloat(valorTotalCompra) >= 0){
        var numero = valorTotalCompra.split('.').join('').split(',').join('.');
        total = numero / quantasVezes;
        document.getElementById("valorParcela").value = total.toLocaleString('pt-br', {minimumFractionDigits: 2});
    } else {
        alert("O valor total da compra deve ser maior que R$ 1,00 !");
    }
}

function totalizaColunaExtrato() {
    var total = $('.box').get().reduce(function(tot, el) {
        var numero = el.innerHTML.split('.').join('').split(',').join('.');
        
        return tot + Number(numero);
    }, 0);
    $('#qtdtotal').html(total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
    $('#alimentacao').html((285-total).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
    $('#refeicao').html((225-total).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
}

function moeda(a, e, r, t) {
    let n = ""
    , h = j = 0
    , u = tamanho2 = 0
    , l = ajd2 = ""
    , o = window.Event ? t.which : t.keyCode;
    if (13 == o || 8 == o)
        return !0;
    if (n = String.fromCharCode(o),
    -1 == "0123456789".indexOf(n))
        return !1;
    for (u = a.value.length,
    h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
        ;
    for (l = ""; h < u; h++)
        -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
    if (l += n,
    0 == (u = l.length) && (a.value = ""),
    1 == u && (a.value = "0" + r + "0" + l),
    2 == u && (a.value = "0" + r + l),
    u > 2) {
        for (ajd2 = "",
        j = 0,
        h = u - 3; h >= 0; h--)
            3 == j && (ajd2 += e,
            j = 0),
            ajd2 += l.charAt(h),
            j++;
        for (a.value = "",
        tamanho2 = ajd2.length,
        h = tamanho2 - 1; h >= 0; h--)
            a.value += ajd2.charAt(h);
        a.value += r + l.substr(u - 2, u)
    }
    return !1
}

function removeLinha(linha) {
    var i=linha.parentNode.parentNode.rowIndex;
    document.getElementById('dataTable').deleteRow(i);
    totalizaColunaExtrato();
    valorExtratoCard();
}

function exibeOculta() {
    var check = document.querySelector('input[name=radioParc]:checked').value;
    if (check == 'Sim') {
        document.getElementById("quantasVezes").style.display = "block";
        document.getElementById("valorTotalCompra").style.display = "block";
        document.getElementById("valorParcela").disabled = true;
        document.getElementById("valorParcela").value = "";
    } else if (check == 'Nao') {
        document.getElementById("quantasVezes").style.display = "none";
        document.getElementById("valorTotalCompra").style.display = "none";
        document.getElementById("quantasVezes").value = "";
        document.getElementById("valorTotalCompra").value = "";
        document.getElementById("valorParcela").value = "";
        document.getElementById("valorParcela").disabled = false;
    }
}

