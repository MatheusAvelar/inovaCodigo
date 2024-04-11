<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executar script Python com AJAX</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="output"></div>
    <script>
        $(document).ready(function(){
            $.ajax({
                url: 'seu_script.py',
                success: function(response) {
                    $('#output').html('<pre>' + response + '</pre>');
                },
                error: function(xhr, status, error) {
                    $('#output').text('Erro ao executar o script Python.');
                }
            });
        });
    </script>
</body>
</html>