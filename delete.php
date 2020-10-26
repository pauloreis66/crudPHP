<?php
// Processa a operação de exclusão após confirmação
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Incluir ficheiro de configuração
    require_once "config.php";
    
    // Prepare uma declaração de Delete
    $sql = "DELETE FROM funcionarios WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Vincular as variáveis como parâmetros à instrução preparada
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Definir parâmetros
        $param_id = trim($_POST["id"]);
        
        // Tentar executar a declaração preparada
        if(mysqli_stmt_execute($stmt)){
            // Registros excluídos com sucesso. Redirecionar para a página de destino
            header("location: index.php");
            exit();
        } else{
            echo "Algo errado não está certo. Por favor, tente novamente mais tarde.";
        }
    }
     
    // Fechar declaração
    mysqli_stmt_close($stmt);
    
    // Fechar conexãon
    mysqli_close($link);
} else{
    // Verifique a existência do parâmetro id antes de processar mais
    if(empty(trim($_GET["id"]))){
        // URL não contém parâmetro de id válido. Redirecionar para a página de erro
        header("location: error.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Excluir Registo (Delete Record)</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Eliminar Registo</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Tem certeza de que deseja excluir este registo?</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="index.php" class="btn btn-default">Não</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>