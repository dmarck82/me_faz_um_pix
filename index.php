<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deixe um recado!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

</head>

<style>
    .btn-pix,
    .apresentacao,
    .titulo {
        text-align: center;
    }

    .center-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
</style>

<?php

$host = 'localhost';
$username = 'root';
$password = 'resende123';
$database = 'mefazumpix';
$conn = new mysqli($host, $username, $password, $database);

?>

<body>

    <div class="container mt-4">
        <div class="container-fluid titulo">
            <h1>Deixe um recado!</h1>
        </div>

        <div class="container-fluid apresentacao">
            <p>Use o espaço abaixo para deixar um recado para alguém.</p>
            <p><strong>Comentários ofensivos não serão tolerados!</strong></p>
        </div>

        <div class="container-fluid btn-pix">
            <button type="button" class="btn btn-primary" id="btnComentario">Deixe um comentário</button>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <!-- Tabela 1 -->
            <div class="col-lg-6 col-md-6 col-12">
                <h4 class="titulo">Top</h4>
                <table class="table mt-2" id="tabelaRank">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nome</th>
                            <th>Comentário</th>
                            <th>Valor</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        // Consulta SQL corrigida
                        $sql = 'SELECT * FROM tb_comentario ORDER BY valor DESC LIMIT 25';

                        // Executa a consulta
                        $result = $conn->query($sql);

                        // Verifica se a consulta foi bem-sucedida
                        if ($result && $result->num_rows > 0) {
                            // Itera sobre os resultados
                            while ($row = $result->fetch_assoc()) {
                                $valor = $row["valor"];

                                $valor = $row["valor"];

                                if ($valor < 1) {
                                    $emoji = '😐';
                                } else if ($valor >= 1 && $valor < 10) {
                                    $emoji = '☺️';
                                } else if ($valor >= 10 && $valor < 50) {
                                    $emoji = '😎';
                                } else if ($valor >= 50 && $valor < 100) {
                                    $emoji = '😍';
                                } else if ($valor >= 100 && $valor < 1000) {
                                    $emoji = '🤑';
                                } else {
                                    $emoji = '😈';
                                }

                                // Verifica se o valor é numérico antes de aplicar a formatação
                                $valor_formatado = is_numeric($valor) ? 'R$ ' . number_format($valor, 2, ',', '.') : 'R$ 0,00';

                                echo "<tr>" . PHP_EOL;
                                echo "<td>" . $emoji . "</td>" . PHP_EOL;
                                echo "<td>" . htmlspecialchars($row["nome"]) . "</td>" . PHP_EOL;
                                echo "<td>" . htmlspecialchars($row["comentario"]) . "</td>" . PHP_EOL;
                                echo "<td>" . $valor_formatado . "</td>" . PHP_EOL;
                                echo "</tr>" . PHP_EOL;
                            }
                        } else {
                            // Mensagem caso não haja resultados
                            echo "<tr><td colspan='3'>Nenhum comentário encontrado.</td></tr>" . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Tabela 2 -->
            <div class="col-lg-6 col-md-6 col-12">
                <h4 class="titulo">Recente</h4>
                <table class="table mt-2" id="tabelaRecente">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nome</th>
                            <th>Comentário</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Consulta SQL corrigida
                        $sql = 'SELECT * FROM tb_comentario ORDER BY id DESC LIMIT 25';

                        // Executa a consulta
                        $result = $conn->query($sql);

                        // Verifica se a consulta foi bem-sucedida
                        if ($result && $result->num_rows > 0) {
                            // Itera sobre os resultados
                            while ($row = $result->fetch_assoc()) {
                                $valor = $row["valor"];

                                if ($valor < 1) {
                                    $emoji = '😐';
                                } else if ($valor >= 1 && $valor < 10) {
                                    $emoji = '☺️';
                                } else if ($valor >= 10 && $valor < 50) {
                                    $emoji = '😎';
                                } else if ($valor >= 50 && $valor < 100) {
                                    $emoji = '😍';
                                } else if ($valor >= 100 && $valor < 1000) {
                                    $emoji = '🤑';
                                } else {
                                    $emoji = '😈';
                                }

                                // Verifica se o valor é numérico antes de aplicar a formatação
                                $valor_formatado = is_numeric($valor) ? 'R$ ' . number_format($valor, 2, ',', '.') : 'R$ 0,00';

                                echo "<tr>" . PHP_EOL;
                                echo "<td>" . $emoji . "</td>" . PHP_EOL;
                                echo "<td>" . htmlspecialchars($row["nome"]) . "</td>" . PHP_EOL;
                                echo "<td>" . htmlspecialchars($row["comentario"]) . "</td>" . PHP_EOL;
                                echo "<td>" . $valor_formatado . "</td>" . PHP_EOL;
                                echo "</tr>" . PHP_EOL;
                            }
                        } else {
                            // Mensagem caso não haja resultados
                            echo "<tr><td colspan='3'>Nenhum comentário encontrado.</td></tr>" . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalComentario" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Deixe seu comentário!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formComentario" method="post">
                        <div class="mb-3">
                            <label for="nome" class="col-form-label">Nome:</label>
                            <input type="text" class="form-control" id="nome" name="nome" required maxlength="50" />
                            <div class="invalid-feedback">O nome é obrigatório e deve ter no máximo 50 caracteres.</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="col-form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required maxlength="50" />
                            <div class="invalid-feedback">Por favor, insira um email válido.</div>
                        </div>
                        <div class="mb-3">
                            <label for="mensagem" class="col-form-label">Mensagem:</label>
                            <textarea class="form-control" id="mensagem" name="mensagem" required
                                maxlength="100"></textarea>
                            <div class="invalid-feedback">A mensagem é obrigatória e deve ter no máximo 100 caracteres.
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">R$</span>
                            <input type="number" class="form-control" id="valor" name="valor" required min="0.01"
                                step="0.01" />
                            <div class="invalid-feedback">O valor é obrigatório e deve ser maior que R$0,00.</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="btnEnviarComentario">Enviar comentário</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalQR" tabindex="-1" aria-labelledby="modalQRLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalQRLabel">Pagamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="center-content">
                        <div class="paymentData">
                            <h1>Escaneie o QR Code abaixo</h1>
                            <img src="" id="qr_code_img" style="max-width:250px"><br>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let stopPaymentCheck = false;

        $('#modalQR').on('hidden.bs.modal', function () {
            location.reload(); // Recarrega a página
        });

        document.getElementById("btnComentario").addEventListener("click", function () {
            $('#modalComentario').modal('show');
        });

        document.getElementById("btnEnviarComentario").addEventListener("click", function (event) {
            event.preventDefault();
            let valor = $("#valor").val();
            let name = $("#nome").val();
            let email = $("#email").val();
            let mensagem = $("#mensagem").val();

            if (valor !== "" && name !== "" && email !== "" && mensagem !== "") {
                criarPagamento();
            }
        });

        function createPayment(value, name, email, mensagem, produto) {
            const body = {
                name: name,
                email: email,
                value: value,
                produto: produto
            };

            $.post("create.php", body, (data, status) => {
                data = JSON.parse(data);
                if (data.id != undefined) {
                    console.log(data);
                    document.getElementById("qr_code_img").src = "data:image/png;base64," + data.qr_code_base64;
                    $('#modalComentario').modal('hide');
                    $('#modalQR').modal('show');
                    const paymentReference = data.ref;
                    checkPaymentInterval(paymentReference);
                }
            });

            function checkPaymentInterval(ref) {
                setTimeout(() => {
                    $.post("verifica.php", {
                        ref: ref
                    }, (data, status) => {
                        data = JSON.parse(data);
                        if (data && data.status == "approved") {
                            document.querySelector(".paymentData").hidden = true;
                            Swal.fire({
                                icon: 'success',
                                title: 'Pagamento aprovado!',
                                text: 'Seu pagamento foi aprovado com sucesso.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $.ajax({
                                    url: 'comentario.php',
                                    type: 'POST',
                                    data: {
                                        name: name,
                                        email: email,
                                        value: value,
                                        mensagem: mensagem
                                    },
                                    dataType: 'json',
                                    success: function (data) {
                                        if (data.success) {
                                            location.reload();
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Erro ao renovar a assinatura',
                                                text: data.message || 'Não foi possível completar a renovação. Tente novamente.',
                                                confirmButtonText: 'OK'
                                            });
                                        }
                                    },
                                    error: function () {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Erro ao realizar a renovação',
                                            text: 'Houve um problema ao processar a renovação. Entre em contato com o suporte.',
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                });
                                console.log("sucesso");
                            });
                        } else if (data && data.status == "rejected") {
                            Swal.fire({
                                icon: 'error',
                                title: 'Pagamento rejeitado!',
                                text: 'O pagamento foi rejeitado. Tente novamente.',
                                confirmButtonText: 'Tentar novamente'
                            });
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Aguardando pagamento',
                                text: 'Sem pix, sem comentário!',
                                confirmButtonText: 'OK'
                            });
                            checkPaymentInterval(ref);
                        }
                    });
                }, 5000);
            }
        }

        function criarPagamento() {
            valor = $("#valor").val();
            name = $("#nome").val();
            email = $("#email").val();
            mensagem = $("#mensagem").val();
            createPayment(valor, name, email, mensagem, 'Paguei para comentar em um site!');
        }
    </script>
</body>

</html>