<?php 

session_start();

require_once 'api/src/Redirect.php';

if ($_SESSION['userEmail'] !== 'admin@email.com') Redirect::to('index.php');

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupo 16 — Painel do Salão</title>
	<link rel="icon" href="assets/images/barber-pole.svg">
    <link rel="stylesheet" href="assets/style.css">
    <script defer src='assets/app.js'></script>
</head>
<body>

    <header class="topo">
        <div class="container topo-flex">
            <div class="topo-admin">
                <h2>Grupo 16 — Salão</h2>
                <span class="tag-admin">Admin</span>
            </div>
            <div class="usuario-box">
                <span>Olá, <strong><?= $_SESSION['userEmail'] ?></strong></span>
                <a href="index.php?logout" class="btn btn-outline-branco btn-sm">Sair</a>
            </div>
        </div>
    </header>

    <main class="container">

        <!-- Resumo do dia -->
        <section class="grid-resumo">
            <div class="card-resumo">
                <span class="subtexto">Agendamentos Hoje</span>
                <strong class="num-resumo">6</strong>
            </div>
            <div class="card-resumo">
                <span class="subtexto">Aguardando Confirmação</span>
                <strong class="num-resumo cor-aviso">2</strong>
            </div>
            <div class="card-resumo">
                <span class="subtexto">Confirmados</span>
                <strong class="num-resumo cor-sucesso">4</strong>
            </div>
        </section>

        <div class="grid-painel">

            <!-- Agendamento rápido -->
            <section class="card">
                <h3>Agendamento Rápido</h3>
                <p class="subtitulo">Registre um horário para cliente via telefone ou balcão.</p>

                <form>
                    <div class="campo">
                        <label for="adm-cliente">Nome do Cliente</label>
                        <input type="text" id="adm-cliente" placeholder="Ex: Ana Paula" required>
                    </div>

                    <div class="campo">
                        <label for="adm-tel">Telefone / WhatsApp</label>
                        <input type="tel" id="adm-tel" placeholder="(11) 98888-7777" required>
                    </div>

                    <div class="campo">
                        <label for="adm-servico">Serviço</label>
                        <select id="adm-servico" required>
                            <option value="">Selecione um serviço...</option>
                            <option value="1">Corte Feminino — R$ 80,00 (60 min)</option>
                            <option value="2">Corte Masculino — R$ 45,00 (30 min)</option>
                            <option value="3">Escova e Hidratação — R$ 70,00 (50 min)</option>
                            <option value="4">Coloração / Mechas — R$ 180,00 (120 min)</option>
                            <option value="5">Barba e Acabamento — R$ 35,00 (30 min)</option>
                        </select>
                    </div>

                    <div class="campo">
                        <label for="adm-data">Data</label>
                        <input type="date" id="adm-data" required>
                    </div>

                    <div class="campo">
                        <label>Horário</label>
                        <div class="grid-horarios">
                            <label class="hora-btn">
                                <input type="radio" name="adm-hora" value="09:00">
                                <span>09:00</span>
                            </label>
                            <label class="hora-btn">
                                <input type="radio" name="adm-hora" value="10:30" checked>
                                <span>10:30</span>
                            </label>
                            <label class="hora-btn">
                                <input type="radio" name="adm-hora" value="13:00">
                                <span>13:00</span>
                            </label>
                            <label class="hora-btn">
                                <input type="radio" name="adm-hora" value="14:30">
                                <span>14:30</span>
                            </label>
                            <label class="hora-btn">
                                <input type="radio" name="adm-hora" value="16:00">
                                <span>16:00</span>
                            </label>
                            <label class="hora-btn">
                                <input type="radio" name="adm-hora" value="17:30">
                                <span>17:30</span>
                            </label>
                        </div>
                    </div>

                    <button type="button" class="btn btn-vermelho btn-full btn-lg">Salvar na Agenda</button>
                </form>
            </section>

            <!-- Agenda geral -->
            <section class="card">
                <div class="card-topo">
                    <h3>Agenda de Clientes</h3>
                    <input type="date" class="filtro-data" aria-label="Filtrar por data">
                </div>

                <div class="tabela-box">
                    <table class="tabela">
                        <thead>
                            <tr>
                                <th>Horário</th>
                                <th>Cliente</th>
                                <th>Serviço</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>18/11</strong> às 14:00</td>
                                <td>
                                    Maria Silva<br>
                                    <span class="subtexto">(11) 99999-1111</span>
                                </td>
                                <td>Corte Feminino</td>
                                <td><span class="badge st-agendado">Agendado</span></td>
                                <td class="acoes">
                                    <button type="button" class="btn btn-verde btn-sm">Confirmar</button>
                                    <button type="button" class="btn btn-erro btn-sm">Cancelar</button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>18/11</strong> às 15:30</td>
                                <td>
                                    Carlos Eduardo<br>
                                    <span class="subtexto">(11) 98888-2222</span>
                                </td>
                                <td>Corte Masculino</td>
                                <td><span class="badge st-confirmado">Confirmado</span></td>
                                <td class="acoes">
                                    <button type="button" class="btn btn-outline btn-sm">Concluir</button>
                                    <button type="button" class="btn btn-erro btn-sm">Cancelar</button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>18/11</strong> às 17:00</td>
                                <td>
                                    Juliana Costa<br>
                                    <span class="subtexto">(11) 97777-3333</span>
                                </td>
                                <td>Escova e Hidratação</td>
                                <td><span class="badge st-concluido">Concluído</span></td>
                                <td>—</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>

</body>
</html>
