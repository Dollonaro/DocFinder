<?php

include('../db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $mysqli->real_escape_string($_POST['nome']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT); // Criptografia da senha
    $bairro = $mysqli->real_escape_string($_POST['bairro']);
    $cidade = $mysqli->real_escape_string($_POST['cidade']);
    $genero = $mysqli->real_escape_string($_POST['genero']);
    $telefone = $mysqli->real_escape_string($_POST['telefone']);
    $cpf = $mysqli->real_escape_string($_POST['cpf']);
    $data_nascimento = $mysqli->real_escape_string($_POST['data_nascimento']);

    $valores_genero = ['Masculino', 'Feminino'];
    if (!in_array($genero, $valores_genero)) {
        die("Valor inválido para o campo gênero.");
    }

   
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_nascimento)) {
        die("Data de nascimento inválida.");
    }

    // Verificaçar se a data não é futura
    if (strtotime($data_nascimento) > time()) {
        die("Data de nascimento não pode ser no futuro.");
    }    

    // Usando prepared statements para inserir os dados de forma segura
    $sql = "INSERT INTO usuarios_pacientes (nome, email, senha, bairro, cidade, genero, telefone, cpf, data_nascimento) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        die("Erro na preparação da consulta: " . $mysqli->error);
    }

    // Ligando os parâmetros para a execução da consulta
    $stmt->bind_param("sssssssss", $nome, $email, $senha, $bairro, $cidade, $genero, $telefone, $cpf, $data_nascimento);

    // Executando a consulta
    if ($stmt->execute()) {
        // Redireciona para a página inicial com uma mensagem de sucesso
        header("Location: ../loginPaciente/pacientLogin.html");
        exit;
    } else {
        // Exibe o erro caso a execução falhe
        die("Erro na execução da consulta: " . $stmt->error);
    }

    // Fechando o prepared statement
    $stmt->close();
}
?>
