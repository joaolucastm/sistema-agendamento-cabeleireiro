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
                <a href="api/?logout" class="btn btn-outline-branco btn-sm">Sair</a>
            </div>
        </div>
    </header>

    <main class="container">
            <!-- Agenda geral -->
            <section class="card admin-card">
                <div class="card-topo">
                    <h3>Agenda de Clientes</h3>
                </div>

                <div class="tabela-box">
                    <table class="tabela">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Horário</th>
                                <th>Cliente</th>
                                <th>Telefone</th>
                                <th>Status</th>
                                <th>Confirmar</th>
                                <th>Cancelar</th>
                            </tr>
                        </thead>
                        <tbody id='appointments'>
                            <tr>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>

</body>
</html>
