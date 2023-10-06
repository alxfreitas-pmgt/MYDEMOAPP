<!DOCTYPE html>
<html>
<head>
    <title>Simulação de Carga</title>
    <meta http-equiv="refresh" content="10">
</head>
<body>
    <h1>Informações do Servidor:</h1>
    <?php
        // Obtém o hostname do servidor hospedeiro
        $hostname = gethostname();
        echo "Hostname: " . $hostname . "<br>";

        // Obtém o IP do servidor hospedeiro
        $ip = gethostbyname($hostname);
        echo "IP do Servidor: " . $ip . "<br>";
    ?>

    <h2>Simular Carga:</h2>
    <form method="post">
        <input type="submit" name="simular_carga" value="Simular Carga">
    </form>

    <?php
        if (isset($_POST['simular_carga'])) {
            // Simula carga fazendo 20 conexões com o servidor local na porta 8080 usando cURL
            for ($i = 0; $i < 20; $i++) {
                $ch = curl_init("http://localhost:8080");
                -curl_exec($ch);
                curl_close($ch);
            }
            echo "Carga simulada com sucesso!";
        }
 
</html>
