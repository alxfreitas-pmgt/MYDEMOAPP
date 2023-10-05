<!DOCTYPE html>
<html>
<head>
    <title>Hostname e Endereço IP do Servidor</title>
</head>
<body>
    <h1>Hostname e Endereço IP do Servidor</h1>
    <?php
    // Obtém o nome do host do servidor
    $hostname = gethostname();
    
    // Obtém o endereço IP do servidor
    $server_ip = $_SERVER['SERVER_ADDR'];
    
    // Exibe o hostname e o endereço IP na página web
    echo "<p>O hostname do servidor é: $hostname</p>";
    echo "<p>O endereço IP do servidor é: $server_ip</p>";
    ?>
</body>
</html>