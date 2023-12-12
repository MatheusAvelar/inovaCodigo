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
