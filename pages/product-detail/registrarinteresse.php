<?php 
    require_once  "../../database/conexaoMysql.php";
    require_once  "../../commons/php/baseResponse.php";
    $pdo = mysqlConnect();

    $codigo = $_POST['cod'] ?? "";

    $contato = $_POST['contato'] ?? "";
    $mensagem = $_POST['mensagem'] ?? "";
    $date = date('Y-m-d');
    
    $sql = <<<SQL
        INSERT INTO interesse (codigo_anuncio, mensagem, contato, data_hora)
        VALUES (?, ?, ?, ?)
    SQL;

    header("Content-Type: application/json");
    try{
        $pdo->beginTransaction();

        $stmt = $pdo->prepare($sql);
        if(!$stmt->execute([$codigo,$mensagem,$contato,$date])){
            throw new Exception('Erro ao registrar interesse');
        }

        $pdo->commit();

        echo json_encode(RequestResponse::basicResponse(true, "Interesse registrado com sucesso!"));

    }catch(Exception $err) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(RequestResponse::basicResponse(false, $err->getMessage()));
    }
    
?>