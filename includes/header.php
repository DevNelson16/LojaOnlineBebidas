<?php
$tituloPagina = $tituloPagina ?? 'Loja de Bebidas'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $tituloPagina; ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <header class="site-header">
        <div class="container header-conteudo">
            <a href="index.php" class="logo">Loja Bebidas</a>
            <nav class="menu">
                <a href="index.php">Início</a>
                <a href="carrinho.php">Carrinho (<span id= "contador-carrinho">0</span>)</a>
                <a href="admin/index.php">Admin</a>
            </nav>
        </div>
    </header>
    <main class="container">
        <p>Aqui vão aparecer os produtos da loja!</p>
    </main>


</body>

</html>