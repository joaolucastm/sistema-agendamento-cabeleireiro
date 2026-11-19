<?php 

session_start();

require_once 'api/src/Redirect.php';

if (empty($_SESSION['userEmail'])) Redirect::to('index.php');

function renderDates()
{
	$startDate = (new DateTimeImmutable('today'))->modify('+1 week');
	$endDate = $startDate->modify('+1 month');

	$timeSlots = [
	    '08:00', '09:00', '10:00', '11:00', '12:00',
	    '13:00', '14:00', '15:00', '16:00', '17:00'
	];

	for ($date = $startDate; $date <= $endDate; $date = $date->modify('+1 day'))
	{
	    $dayOfWeek = $date->format('N'); // 1 = Monday, 7 = Sunday

	    if ($dayOfWeek >= 6)
	    {
	        continue; // Skip Saturday and Sunday
	    }

	    foreach ($timeSlots as $time)
	    {
	        $value = $date->format('Y-m-d') . " $time";
	        $label = $date->format('d/m/Y') . ' às ' . $time;

	        echo '<option value="' . $value . '">' . $label . '</option>';
	    }
	}	
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupo 16 — Área do Cliente</title>
    <link rel="icon" href="assets/images/barber-pole.svg">
    <link rel="stylesheet" href="assets/style.css">
    <script defer src='assets/app.js'></script>
</head>
<body>

    <header class="topo">
        <div class="container topo-flex">
            <h2>Grupo 16 — Salão</h2>
            <div class="usuario-box">
                <span>Olá, <strong><?= $_SESSION['userName'] ?></strong></span>
                <a href="api/?logout" class="btn btn-outline-branco btn-sm">Sair</a>
            </div>
        </div>
    </header>

    <main class="container grid-painel">

        <!-- Novo agendamento -->
        <section class="card">
            <h3>Novo Agendamento</h3>
            <p class="subtitulo">Escolha um horário disponível</p>

            <form id='createAppointment'>
                <div class="campo">
    	           	<input type='hidden' name='action' value='createAppointment'>
                    <label for="horario">Qual horário você deseja?</label>
                    <select id="horario" name='schedule' required>
                    	<?php renderDates() ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-vermelho btn-full btn-lg">Agendar</button>
            </form>
        </section>

        <!-- Lista de agendamentos -->
        <section class="card">
            <div class="card-topo">
                <h3>Meus Agendamentos</h3>
            </div>

            <div class="tabela-box">
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Data / Hora</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id='clientAppointments'>
<!--                        <tr>
                            <td>1</td>
                            <td>18/11/2026 às 14:30</td>
                            <td><span class="badge st-pendente">Pendente</span></td>
                            <td>
                                <button type="button" class="btn btn-erro btn-sm">Cancelar</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>22/11/2026 às 10:30</td>
                            <td><span class="badge st-confirmado">Confirmado</span></td>
                            <td>
                                <button type="button" class="btn btn-erro btn-sm">Cancelar</button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>05/11/2026 às 16:00</td>
                            <td><span class="badge st-concluido">Concluído</span></td>
                            <td>N/A</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>
