<!DOCTYPE html>
<html>
<head>
    <title>Hostname do Servidor</title>
</head>
<body>
    <h1>Hostname do Servidor</h1>
    <?php
    // Obtém o nome do host do servidor
    $hostname = gethostname();
    
    // Exibe o hostname na página web
    echo "<p>O hostname do servidor é: $hostname</p>";
    ?>
</body>
</html>