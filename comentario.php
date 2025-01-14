<?php

function connect_local_mysqli($database = 'mefazumpix', $charset = "utf8")
{
    $host = 'localhost';
    $username = 'root';
    $password = 'resende123';
    $conn = new mysqli($host, $username, $password, $database);
    return $conn;
}


header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conn = connect_local_mysqli('mefazumpix');

    $name = $_POST['name'];
    $email = $_POST['email'];
    $value = $_POST['value'];
    $mensagem = $_POST['mensagem'];

    // Preparar inserção
    $stmt = $conn->prepare("INSERT INTO tb_comentario (nome, email, valor, comentario) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Erro ao preparar o cadastro"]);
        exit;
    }
    $stmt->bind_param("ssss", $name, $email, $value, $mensagem);
    $result = $stmt->execute();

    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao cadastrar usuário"]);
    }

    $stmt->close();
    $conn->close();
}
