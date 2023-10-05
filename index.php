<!DOCTYPE html>
<html>
<head>
    <title>Atualização Automática</title>
</head>
<body>
    <h1>Hostname e Endereço IP do Servidor</h1>
    
    <div>
        <label for="tempoAtualizacao">Tempo de Atualização (segundos):</label>
        <input type="number" id="tempoAtualizacao" min="1" step="1" value="30">
        <button id="salvar" style="background-color: green;">Salvar</button>
        <button id="redefinir" style="background-color: red;">Redefinir</button>
    </div>
    
    <div id="contadorRegressivo"></div>
    
    <?php
    // Função PHP para obter o hostname e o endereço IP do servidor
    function getServerInfo() {
        $hostname = gethostname();
        $server_ip = $_SERVER['SERVER_ADDR'];
        return array('hostname' => $hostname, 'server_ip' => $server_ip);
    }
    
    // Exibe o hostname e o endereço IP do servidor
    $serverInfo = getServerInfo();
    echo "<p>O hostname do servidor é: {$serverInfo['hostname']}</p>";
    echo "<p>O endereço IP do servidor é: {$serverInfo['server_ip']}</p>";
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

        // Carregar o contador regressivo com o valor padrão
        let tempoAtualizacao = parseInt(document.getElementById('tempoAtualizacao').value) || 30;
        atualizarContador(tempoAtualizacao);

        // Adicionar evento de clique no botão "Salvar"
        document.getElementById('salvar').addEventListener('click', function () {
            tempoAtualizacao = parseInt(document.getElementById('tempoAtualizacao').value) || 30;
            atualizarContador(tempoAtualizacao);
        });

        // Adicionar evento de clique no botão "Redefinir"
        document.getElementById('redefinir').addEventListener('click', function () {
            document.getElementById('tempoAtualizacao').value = tempoAtualizacao;
            atualizarContador(tempoAtualizacao);
        });
    </script>
</body>
</html>





