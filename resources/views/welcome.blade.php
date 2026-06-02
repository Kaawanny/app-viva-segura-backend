<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{url('css/home.css')}}">

    <title>DashBoard</title>
</head>

<body>

    <aside class="barra-lateral">
        <div class="item-navegacao"><img src="/imagens/icones/barraWhite.png"></div>
        <div class="item-navegacao"><img src="/imagens/icones/windowWhite.png"></div>
        <div class="item-navegacao"><img src="/imagens/icones/users.png"></div>
        <div class="item-navegacao"><img src="/imagens/icones/alertaWhite.png"></div>
        <div class="item-navegacao"><img src="/imagens/icones/suporte.png"></div>
        <div class="item-navegacao"><img src=""></div>
        <div class="item-navegacao"><img src=""></div>

        <div class="item-navegacao icone-configuracao">
            <img src="/imagens/icones/engrenagem.png">
        </div>
    </aside>

    <div class="container-principal">

        <header class="cabecalho">
            <div class="cabecalho-esquerda">
                <img src="/imagens/icones/logo.png" class="logo">
                <strong>DashBoard</strong>
            </div>

            <div class="cabecalho-direita">
                <img src="/imagens/icones/notificacaoWhite.png" class="icone">
                <img src="/imagens/icones/darkMode.png" class="icone">


                <div class="perfil-usuario">
                    <img src="/imagens/fotos/pato.jfif" class="avatar-usuario">

                    <div>
                        <div><strong>Nome</strong></div>
                        <div style="font-size: 0.7rem; opacity: 0.8;">admin</div>

                    </div> <img src="/imagens/icones/setaWhite.png" class="icone">
                </div>
            </div>
        </header>

        <main class="area-conteudo">

            <h2>Home</h2>
            <div class="cards">
                <div class="card">
                    <h3>Usuários ativos</h3>
                    <p class="numero">1.245</p>
                </div>

                <div class="card">
                    <h3>Servidores Ativos</h3>
                    <p class="numero">18</p>
                </div>

                <div class="card">
                    <h3>Alertas ultimas 24h</h3>
                    <p class="numero vermelho">23</p>
                </div>

                <div class="card">
                    <h3>Tickets</h3>
                    <p class="numero">28</p>
                </div>
            </div>

            <div class="graficos">

                <div class="grafico-box">
                    <h3>Uso de CPU</h3>
                    <div class="barra">
                        <div class="progresso" style="width: 70%"></div>
                    </div>
                    <span>70%</span>
                </div>

                <div class="grafico-box">
                    <h3>Uso de Memória</h3>
                    <div class="barra">
                        <div class="progresso roxo" style="width: 55%"></div>
                    </div>
                    <span>55%</span>
                </div>

                <div class="grafico-box">
                    <h3>Armazenamento</h3>
                    <div class="barra">
                        <div class="progresso verde" style="width: 80%"></div>
                    </div>
                    <span>80%</span>
                </div>

            </div>

            <div class="atividade">
                <h3>Atividades recentes</h3>

                <ul>
                    <li>✔️ Novo usuário cadastrado</li>
                    <li>⚠️ Pico de uso no servidor</li>
                    <li>✔️ Backup realizado com sucesso</li>
                    <li>❌ Falha em requisição API</li>
                </ul>
            </div>

            <div class="mapa">
                
            </div>

        </main>

    </div>

</body>

</html>