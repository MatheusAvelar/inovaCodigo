function submitHours() {
    var task = document.getElementById("task").value;
    var hours = document.getElementById("hours").value;

    if (task.trim() === '' || hours.trim() === '') {
        alert("Por favor, preencha todos os campos.");
        return;
    }

    var totalHours = calculateTotalHours(startTime, endTime);
    document.getElementById("total").textContent = totalHours;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "save_hours.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("response").innerHTML = xhr.responseText;
        }
    };
    xhr.send("task=" + task + "&hours=" + totalHours);
}

function calculateTotalHours(startTime, endTime) {
    var milliseconds = endTime - startTime;
    var totalSeconds = milliseconds / 1000;
    var hours = Math.floor(totalSeconds / 3600);
    var minutes = Math.floor((totalSeconds % 3600) / 60);
    hours = String(hours).padStart(2, '0');
    minutes = String(minutes).padStart(2, '0');
    return hours + ":" + minutes;
}