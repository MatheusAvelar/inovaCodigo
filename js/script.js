function submitHours() {
    var task = document.getElementById("task").value;
    var hours = document.getElementById("hours").value;

    if (task.trim() === '' || hours.trim() === '') {
        alert("Por favor, preencha todos os campos.");
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "save_hours.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("response").innerHTML = xhr.responseText;
        }
    };
    xhr.send("task=" + task + "&hours=" + hours);
}