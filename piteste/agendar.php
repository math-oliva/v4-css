<?php
session_start();
include('conexao.php'); // Conexão com o banco de dados

// Verificar se o cliente está logado
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    header('Location: login_cliente.php'); // Se não estiver logado ou não for cliente, redireciona para o login
    exit();
}

// Buscar todos os tatuadores
$query_tatuadores = "SELECT * FROM tatuadores";
$result_tatuadores = mysqli_query($conn, $query_tatuadores);

// Buscar todas as especialidades
$query_especialidades = "SELECT * FROM especialidades";
$result_especialidades = mysqli_query($conn, $query_especialidades);

// Se o formulário for enviado, salva o agendamento
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = $_SESSION['id_usuario'];
    $id_tatuador = $_POST['id_tatuador'];
    $data_agendamento = $_POST['data_agendamento'];
    $especialidades = implode(", ", $_POST['especialidades']); // Convertendo o array em uma string

    // Inserir o agendamento no banco de dados
    $query_agendamento = "INSERT INTO agendamentos (id_cliente, id_tatuador, data_agendamento, especialidades, status) 
                          VALUES ('$id_cliente', '$id_tatuador', '$data_agendamento', '$especialidades', 'pendente')";
    
    if (mysqli_query($conn, $query_agendamento)) {
        header("Location: painel_cliente.php"); // Redireciona para o painel do cliente após o agendamento
        exit();
    } else {
        echo "Erro ao agendar o tatuagem.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Agendar Sessão de Tatuagem</title>
</head>
<body>
<header>
        <img src="LOGO_XOGUN1.jpg" alt="Logo" class="logo">
        <h1><u>SHOGUN STUDIO</u></h1>
        
</header>
    <section>


<!-- Formulário de agendamento -->
<form action="agendar.php" method="POST" class="formagendar">

    <!-- Seleção do Tatuador -->
    <h1 class="title">Agendar Sessão de Tatuagem</h1>

        <label class="aa" for="id_tatuador">Escolha o Tatuador:</label>
        <select class="bb" name="id_tatuador" id="id_tatuador" required>
            <?php while ($tatuador = mysqli_fetch_assoc($result_tatuadores)): ?>
                <option value="<?php echo $tatuador['id']; ?>">
                    <?php echo $tatuador['nome']; ?>
                </option>
            <?php endwhile; ?>
        </select>
   

    <!-- Data do Agendamento -->
   
        <label class="cc" for="data_agendamento">Escolha a Data e Hora:</label>
        <input class="dd" type="datetime-local" name="data_agendamento" id="data_agendamento" required>
   

    <!-- Especialidades -->
    
        <label class="ee" for="especialidades">Escolha as Especialidades:</label>
        <select class="ff" name="especialidades[]" id="especialidades"  multiple required >
            <?php while ($especialidade = mysqli_fetch_assoc($result_especialidades)): ?>
                <option value="<?php echo $especialidade['nome']; ?>">
                    <?php echo $especialidade['nome']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    

    <!-- Botão de Envio -->
    <button class="fut" type="submit">Agendar</button>
</form>
</section>
<footer>
<!-- Link para voltar ao painel do cliente -->
<p><a href="painel_cliente.php">Voltar ao painel</a></p>
</footer>
</body>
</html>
