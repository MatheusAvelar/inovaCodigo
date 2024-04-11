<?php
// Executa o script Python
$output = shell_exec('python seu_script.py');
echo "<pre>$output</pre>";
?>