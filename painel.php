<?php 

session_start();

require_once 'api/src/Redirect.php';

if (empty($_SESSION['loggedUser'])) Redirect::to('index.php');

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupo 16 — Área do Cliente</title>
    <link rel="icon" href="assets/images/barber-pole.svg">
    <link rel="stylesheet" href="assets/style.css">
    <script defer src='app.js'></script>
</head>
<body>

    <header class="topo">
        <div class="container topo-flex">
            <h2>Grupo 16 — Salão</h2>
            <div class="usuario-box">
                <span>Olá, <strong><?= $_SESSION['loggedUser'] ?></strong></span>
                <a href="api/?logout" class="btn btn-outline-branco btn-sm">Sair</a>
            </div>
        </div>
    </header>

    <main class="container grid-painel">

        <!-- Novo agendamento -->
        <section class="card">
            <h3>Novo Agendamento</h3>
            <p class="subtitulo">Escolha um horário disponível</p>

            <form id='agendamento'>
                <div class="campo">
                    <label for="horario">Qual horário você deseja?</label>
                    <select id="horario" required>
                        <option value="1">Corte Feminino — R$ 80,00 (60 min)</option>
                        <option value="2">Corte Masculino — R$ 45,00 (30 min)</option>
                        <option value="3">Escova e Hidratação — R$ 70,00 (50 min)</option>
                        <option value="4">Coloração / Mechas — R$ 180,00 (120 min)</option>
                        <option value="5">Barba e Acabamento — R$ 35,00 (30 min)</option>
                    </select>
                </div>

                <button type="button" class="btn btn-vermelho btn-full btn-lg">Confirmar Agendamento</button>
            </form>
        </section>

        <!-- Lista de agendamentos -->
        <section class="card">
            <div class="card-topo">
                <h3>Meus Agendamentos</h3>
                <span class="subtexto">Novembro / 2026</span>
            </div>

            <div class="tabela-box">
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>Data / Hora</th>
                            <th>Serviço</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>18/11/2026 às 14:30</td>
                            <td>Corte Feminino</td>
                            <td>R$ 80,00</td>
                            <td><span class="badge st-agendado">Agendado</span></td>
                            <td>
                                <button type="button" class="btn btn-erro btn-sm">Cancelar</button>
                            </td>
                        </tr>
                        <tr>
                            <td>22/11/2026 às 10:30</td>
                            <td>Escova e Hidratação</td>
                            <td>R$ 70,00</td>
                            <td><span class="badge st-confirmado">Confirmado</span></td>
                            <td>
                                <button type="button" class="btn btn-erro btn-sm">Cancelar</button>
                            </td>
                        </tr>
                        <tr>
                            <td>05/11/2026 às 16:00</td>
                            <td>Coloração / Mechas</td>
                            <td>R$ 180,00</td>
                            <td><span class="badge st-concluido">Concluído</span></td>
                            <td>—</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>
