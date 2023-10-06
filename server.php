<?php
// Lado do servidor PHP

// Função para iniciar conexões usando o comando cURL
function startConnections($url) {
    for ($i = 0; $i < 20; $i++) {
        // Execute o comando cURL para iniciar a conexão com a URL
        // Substitua o comentário abaixo pelo comando real do cURL
        // Certifique-se de configurar as opções do cURL conforme necessário.
        // Exemplo de comando cURL: exec("curl -i -X GET $url");
    }
}

// Iniciar o teste de carga se o formulário for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $url = $_POST["url"];
    startConnections($url);
}

// Função para obter as informações do servidor em JSON
function getServerInfo() {
    $hostname = gethostname(); // Obtenha o hostname do servidor
    $ip = $_SERVER['SERVER_ADDR']; // Obtenha o endereço IP do servidor
    $numConnections = 0; // Inicialize o número total de conexões (atualizado em tempo real)

    // Execute o comando para obter o número total de conexões aqui (atualizado em tempo real)
    // Substitua o comentário acima pelo comando real para obter o número de conexões

    return json_encode(array(
        "hostname" => $hostname,
        "ip" => $ip,
        "numConnections" => $numConnections
    ));
}

// Responda com as informações do servidor em formato JSON
header('Content-Type: application/json');
echo getServerInfo();
?>
