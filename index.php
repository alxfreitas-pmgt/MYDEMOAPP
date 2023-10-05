<!DOCTYPE html>
<html>
<head>
    <title>Teste de Carga</title>
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

    <hr>

    <h2>Teste de Carga</h2>
    
    <div>
        <label for="modoTesteCarga">Modo de Teste de Carga:</label>
        <select id="modoTesteCarga">
            <option value="local">Local</option>
            <option value="remoto">Remoto</option>
        </select>
    </div>
    
    <div>
        <label for="urlRemota">URL Remota:</label>
        <input type="text" id="urlRemota" disabled>
    </div>

    <div>
        <label for="quantidadeConexoes">Quantidade de Conexões:</label>
        <input type="number" id="quantidadeConexoes" min="1" step="1">
    </div>
    
    <div>
        <button id="iniciarTeste" style="background-color: green;">Iniciar Teste</button>
        <button id="pararTeste" style="background-color: red;" disabled>Parar Teste</button>
    </div>

    <hr>

    <h2>Log do Teste de Carga</h2>
    
    <pre id="logTesteCarga"></pre>

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

    // Função para iniciar o teste de carga
    function iniciarTesteCarga($modo, $urlRemota, $quantidade) {
        // Lógica para iniciar o teste de carga aqui
        // Pode usar a função fsockopen() do PHP para criar conexões simuladas
        
        // Exemplo simples de log
        $logTesteCarga = '';

        // Determinar o alvo com base no modo de teste
        $alvo = '';
        if ($modo === 'local') {
            $alvo = '127.0.0.1'; // Endereço IP do servidor local
        } elseif ($modo === 'remoto') {
            $alvo = $urlRemota; // URL remota informada pelo usuário
        }

        $portaAlvo = 8080; // Porta 8080 para o destino

        for ($i = 1; $i <= $quantidade; $i++) {
            $origem = $modo === 'local' ? 'Local' : 'Remoto';
            $destino = $alvo . ':' . $portaAlvo;
            $status = realizarConexao($destino); // Realizar a conexão e obter o status

            $logTesteCarga .= "Conexão $i: Origem: $origem, Destino: $destino, Status: $status\n";
        }

        return $logTesteCarga;
    }

    // Função para realizar a conexão
    function realizarConexao($destino) {
        // Iniciar a conexão com o servidor de destino na porta 8080
        $socket = fsockopen($destino, 8080, $errno, $errstr, 5);

        if ($socket) {
            // Conexão bem-sucedida
            fclose($socket);
            return 'Conexão bem-sucedida';
        } else {
            // Erro na conexão
            return "Erro na conexão: $errstr ($errno)";
        }
    }

    // Verificar se o formulário de início do teste foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['iniciarTeste'])) {
        $modoTesteCarga = $_POST['modoTesteCarga'];
        $urlRemota = $_POST['urlRemota'];
        $quantidadeConexoes = intval($_POST['quantidadeConexoes']);

        if ($modoTesteCarga && $quantidadeConexoes > 0) {
            $logTesteCarga = iniciarTesteCarga($modoTesteCarga, $urlRemota, $quantidadeConexoes);
            echo "<pre>$logTesteCarga</pre>";
        }
    }
    ?>

    <script>
        let intervalo;
        let testeCargaAtivo = false;

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

        // Adicionar evento de alteração no modo de teste de carga
        document.getElementById('modoTesteCarga').addEventListener('change', function () {
            const modoTesteCarga = document.getElementById('modoTesteCarga').value;
            const urlRemotaInput = document.getElementById('urlRemota');
            
            if (modoTesteCarga === 'remoto') {
                urlRemotaInput.disabled = false;
            } else {
                urlRemotaInput.disabled = true;
            }
        });

        // Adicionar evento de clique no botão "Iniciar Teste"
        document.getElementById('iniciarTeste').addEventListener('click', function () {
            const modoTesteCarga = document.getElementById('modoTesteCarga').value;
            const urlRemota = document.getElementById('urlRemota').value;
            const quantidadeConexoes = parseInt(document.getElementById('quantidadeConexoes').value) || 1;

            if (modoTesteCarga === 'remoto' && urlRemota === '') {
                alert('A URL remota é obrigatória no modo remoto.');
                return;
            }

            iniciarTesteCarga(modoTesteCarga, urlRemota, quantidadeConexoes);
        });

        // Adicionar evento de clique no botão "Parar Teste"
        document.getElementById('pararTeste').addEventListener('click', function () {
            pararTesteCarga();
        });

        // Função para iniciar o teste de carga
        function iniciarTesteCarga(modo, urlRemota, quantidade) {
            if (testeCargaAtivo) {
                alert('O teste de carga já está em andamento.');
                return;
            }

            testeCargaAtivo = true;
            document.getElementById('iniciarTeste').disabled = true;
            document.getElementById('pararTeste').disabled = false;

            // Enviar solicitação POST para iniciar o teste de carga no servidor
            const xhr = new XMLHttpRequest();
            xhr.open('POST', window.location.href, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const logTesteCarga = xhr.responseText;
                    const logTesteCargaElement = document.getElementById('logTesteCarga');
                    logTesteCargaElement.textContent = logTesteCarga;
                }
            };

            const formData = new FormData();
            formData.append('iniciarTeste', 'true');
            formData.append('modoTesteCarga', modo);
            formData.append('urlRemota', urlRemota);
            formData.append('quantidadeConexoes', quantidade);

            xhr.send(formData);
        }

        // Função para parar o teste de carga
        function pararTesteCarga() {
            if (!testeCargaAtivo) {
                alert('Não há teste de carga em andamento.');
                return;
            }

            testeCargaAtivo = false;
            document.getElementById('iniciarTeste').disabled = false;
            document.getElementById('pararTeste').disabled = true;

            // Lógica para parar o teste de carga (se necessário)
        }

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
