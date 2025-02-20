function idadeAtualizada() {
    var ano_atual = new Date().getFullYear();
    var data_aniversario = "09/07/1995";
    var ano_informado = data_aniversario.split('/')[2];
    var idade = ano_atual - ano_informado;
    document.getElementsByClassName("pb-1 text-secondary")[0].innerText = idade;
}