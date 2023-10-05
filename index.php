<!DOCTYPE html>
<html>
<head>
    <title>Atualização Automática</title>
</head>
<body>
    <h1>Hostname e Endereço IP do Servidor</h1>
    
    <div>
        <label for="tempoAtualizacao">Tempo de Atualização (segundos):</label>
        <input type="number" id="tempoAtualizacao" min="1" step="1">
        <button id="salvar" style="background-color: green;">Salvar</button>
        <button id="redefinir" style="background-color: red;">Redefinir</button>
    </div>
    
    <div id="contadorRegressivo"></div>
    
    <?php
    // Iniciar ou retomar a sessão PHP
    session_start();
    
    // Acompanhar o número de conexões simultâneas à aplicação usando a variável de sessão
    if (!isset($_SESSION['num_conexoes'])) {
        $_SESSION['num_conexoes'] = 1;
    } else {
        $_SESSION['num_conexoes']++;
    }
    
    // Função PHP para obter o hostname e o endereço IP do servidor
    function getServerInfo() {
        $hostname = gethostname();
        $server_ip = $_SERVER['SERVER_ADDR'];
        return array('hostname' => $hostname, 'server_ip' => $server_ip);
    }
    
    // Exibe o número de conexões simultâneas à aplicação
    echo "<p>Número de conexões simultâneas: {$_SESSION['num_conexoes']}</p>";
    
    // Se o cookie "acessou_servidor" não existir, significa que é a primeira vez que o servidor está sendo acessado
    if (!isset($_COOKIE['acessou_servidor'])) {
        echo '<p style="color: blue;">Servidor Novo</p>';
        // Define o cookie para indicar que o servidor foi acessado
        setcookie('acessou_servidor', 'true', time() + 365 * 24 * 60 * 60); // Valerá por 1 ano
    }
    
    // Obter informações do servidor
    $serverInfo = getServerInfo();
    $hostname = $serverInfo['hostname'];
    $server_ip = $serverInfo['server_ip'];
    
    // Exibe o hostname e o endereço IP do servidor
    echo "<p>O hostname do servidor é: $hostname</p>";
    echo "<p>O endereço IP do servidor é: $server_ip</p>";
    ?>

    <script>
        let intervalo;

        // Função para atualizar o contador regressivo
        function atualizarContador(tempo) {
            let contador = tempo;
            const contadorRegressivo = document.getElementById('contadorRegressivo');
            
            const atualizacao = () => {
                contadorRegressivo.textContent = `Atualização em ${contador} segundos`;
                contador--;

                if (contador < 0) {
                    clearInterval(intervalo);
                    window.location.reload(); // Atualizar a página após a contagem regressiva
                }
            };

            atualizacao(); // Atualizar imediatamente ao carregar a página
            clearInterval(intervalo); // Parar o intervalo anterior (se houver)
            intervalo = setInterval(atualizacao, 1000);
        }

        // Carregar o contador regressivo com o valor do cookie de tempo, se disponível
        let tempoAtualizacao = parseInt(getCookie('tempoAtualizacao')) || 30;
        document.getElementById('tempoAtualizacao').value = tempoAtualizacao;
        atualizarContador(tempoAtualizacao);

        // Adicionar evento de clique no botão "Salvar"
        document.getElementById('salvar').addEventListener('click', function () {
            tempoAtualizacao = parseInt(document.getElementById('tempoAtualizacao').value) || 30;
            setCookie('tempoAtualizacao', tempoAtualizacao, 365); // Armazenar o valor em um cookie
            
            atualizarContador(tempoAtualizacao);
        });

        // Adicionar evento de clique no botão "Redefinir"
        document.getElementById('redefinir').addEventListener('click', function () {
            tempoAtualizacao = 30; // Valor padrão
            document.getElementById('tempoAtualizacao').value = tempoAtualizacao;
            setCookie('tempoAtualizacao', tempoAtualizacao, 365); // Armazenar o valor em um cookie
            
            atualizarContador(tempoAtualizacao);
        });

        // Função para definir um cookie
        function setCookie(name, value, days) {
            const expires = new Date();
            expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = name + '=' + value + ';expires=' + expires.toUTCString();
        }

        // Função para obter o valor de um cookie
        function getCookie(name) {
            const keyValue = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
            return keyValue ? keyValue[2] : null;
        }
    </script>
</body>
</html>
