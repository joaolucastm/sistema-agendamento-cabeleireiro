<?php 

session_start();

require_once 'api/src/Notification.php';

Notification::show();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupo 16 — Salão de Beleza & Barbearia</title>
    <link rel="icon" href="assets/images/barber-pole.svg">
    <link rel="stylesheet" href="assets/style.css">
    <script defer src='assets/app.js'></script>
</head>
<body>

    <input type="checkbox" id="modal-login" class="oculto">

    <!-- Modal de acesso -->
    <div class="modal-bg">
        <div class="modal-box">
            <div class="modal-topo">

                <div>
                    <h3>Acesse sua conta</h3>
                    <p class="subtexto">Entre para agendar ou gerenciar horários</p>
                </div>

                <label for="modal-login" class="btn-fechar" title="Fechar">&times;</label>
            </div>

            <input type="radio" name="acesso" id="tab-cliente" class="oculto" checked>
            <input type="radio" name="acesso" id="tab-cadastro" class="oculto">
            <input type="radio" name="acesso" id="tab-admin" class="oculto">

            <div class="tabs">
                <label for="tab-cliente" class="tab lb-cliente">Entrar</label>
                <label for="tab-cadastro" class="tab lb-cadastro">Criar Conta</label>
            </div>

            <!-- Login Cliente -->
            <form id='login' class="form-tab f-cliente">
           		<input type='hidden' name='action' value='login'>
                <div class="campo">
                    <label for="cli-email">E-mail do Cliente</label>
                    <input name='email' type="email" id="cli-email" placeholder="cliente@email.com" required>
                </div>
                <div class="campo">
                    <label for="cli-senha">Senha</label>
                    <input name='password' type="password" id="cli-senha" placeholder="••••••••" required>
                </div>
                <button class="btn btn-vermelho btn-full" type='submit'>Entrar</button>
            </form>

            <!-- Cadastro Cliente -->
            <form class="form-tab f-cadastro" id='signUp'>
           		<input type='hidden' name='action' value='signUp'>
                <div class="campo">
                    <label for="cad-nome">Nome completo</label>
                    <input name='name' type="text" id="cad-nome" placeholder="Ex: Maria Silva" required>
                </div>
                <div class="campo">
                    <label for="cad-tel">WhatsApp / Celular</label>
                    <input name='phone' type="tel" id="cad-tel" placeholder="(11) 99999-9999" required>
                </div>
                <div class="campo">
                    <label for="cad-email">E-mail</label>
                    <input name='email' type="email" id="cad-email" placeholder="seu@email.com" required>
                </div>
                <div class="campo">
                    <label for="cad-senha">Crie uma senha</label>
                    <input name='password' type="password" id="cad-senha" placeholder="Mínimo 6 caracteres" required>
                </div>
                <button type='submit' href="painel.html" class="btn btn-vermelho btn-full">Concluir Cadastro</button>
            </form>
        </div>
    </div>

    <header class="topo">
        <div class="container topo-flex">
            <h2>Grupo 16 — Salão</h2>
            <label for="modal-login" class="btn btn-vermelho btn-sm">Entrar / Agendar</label>
        </div>
    </header>

    <section class="banner">
        <div class="container banner-info">
            <span class="tag-banner">Agendamento Online 24h</span>
            <h1>Seu horário marcado em poucos minutos.</h1>
            <p>Escolha o serviço, veja os horários disponíveis e garanta seu atendimento sem precisar ligar ou esperar resposta.</p>
            <div class="banner-btns">
                <label for="modal-login" class="btn btn-vermelho btn-lg">Agendar Meu Horário</label>
                <a href="#servicos" class="btn btn-outline-branco btn-lg">Ver Serviços e Preços</a>
            </div>
        </div>
    </section>

    <main id="servicos" class="container area-servicos">
        <div class="titulo-secao">
            <h2>Nossos Serviços</h2>
            <p class="subtexto">Valores transparentes e tempo estimado de atendimento</p>
        </div>

        <div class="grid-servicos">
			<article class="card-servico">
			    <div class="servico-topo">
			        <h3>Corte Feminino</h3>
			        <span class="preco">R$ 80,00</span>
			    </div>
			    <p class="subtexto">Lavagem, corte personalizado e finalização com secador.</p>
			    <div class="servico-rodape">
			        <label for="modal-login" class="link-agendar">Agendar &rarr;</label>
			    </div>
			</article>

			<article class="card-servico">
			    <div class="servico-topo">
			        <h3>Corte Masculino</h3>
			        <span class="preco">R$ 45,00</span>
			    </div>
			    <p class="subtexto">Corte na tesoura ou máquina com acabamento na navalha.</p>
			    <div class="servico-rodape">
			        <label for="modal-login" class="link-agendar">Agendar &rarr;</label>
			    </div>
			</article>

			<article class="card-servico">
			    <div class="servico-topo">
			        <h3>Escova e Hidratação</h3>
			        <span class="preco">R$ 70,00</span>
			    </div>
			    <p class="subtexto">Tratamento nutritivo profundo + escova modelada.</p>
			    <div class="servico-rodape">
			        <label for="modal-login" class="link-agendar">Agendar &rarr;</label>
			    </div>
			</article>

			<article class="card-servico">
			    <div class="servico-topo">
			        <h3>Coloração / Mechas</h3>
			        <span class="preco">R$ 180,00</span>
			    </div>
			    <p class="subtexto">Aplicação completa, tonalização e tratamento pós-química.</p>
			    <div class="servico-rodape">
			        <label for="modal-login" class="link-agendar">Agendar &rarr;</label>
			    </div>
			</article>

			<article class="card-servico">
			    <div class="servico-topo">
			        <h3>Barba e Acabamento</h3>
			        <span class="preco">R$ 35,00</span>
			    </div>
			    <p class="subtexto">Toalha quente, alinhamento e hidratação dos fios.</p>
			    <div class="servico-rodape">
			        <label for="modal-login" class="link-agendar">Agendar &rarr;</label>
			    </div>
			</article>
        </div>
    </main>

    <footer class="rodape">
        <div class="container">
            <p><strong>Grupo 16 — Salão</strong> • Segunda a Sexta, das 08h às 17h</p>
        </div>
    </footer>

</body>
</html>
