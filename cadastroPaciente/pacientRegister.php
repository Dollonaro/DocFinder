<?php 

include('../db.php'); // Inclui o arquivo de conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receber os dados do formulário
    $nome = $mysqli->real_escape_string($_POST['nome']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $bairro = $mysqli->real_escape_string($_POST['bairro']);
    $cidade = $mysqli->real_escape_string($_POST['cidade']);
    $genero = $mysqli->real_escape_string($_POST['genero']);
    $telefone = $mysqli->real_escape_string($_POST['telefone']);
    $cpf = $mysqli->real_escape_string($_POST['cpf']);
    $data_nascimento = $mysqli->real_escape_string($_POST['data_nascimento']);

    // Validações básicas
    $valores_genero = ['Masculino', 'Feminino'];
    if (!in_array($genero, $valores_genero)) {
        die("Valor inválido para o campo gênero.");
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_nascimento)) {
        die("Data de nascimento inválida.");
    }

    if (strtotime($data_nascimento) > time()) {
        die("Data de nascimento não pode ser no futuro.");
    }

    // Verificar se as senhas coincidem
    if ($senha !== $confirmar_senha) {
        die("Erro: As senhas não coincidem.");
    }

    // Hash da senha para segurança
    $senha_hashed = password_hash($senha, PASSWORD_BCRYPT);

    // Prepara e executa a inserção no banco de dados
    $sql = "INSERT INTO usuarios_pacientes (nome, email, senha, bairro, cidade, genero, telefone, cpf, data_nascimento) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssssssss", $nome, $email, $senha_hashed, $bairro, $cidade, $genero, $telefone, $cpf, $data_nascimento);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
        header("Location: ../loginPaciente/pacientLogin.html");
        exit();
    } else {
        echo "Erro ao cadastrar: " . $mysqli->error;
    }

    $stmt->close();
}
?>
