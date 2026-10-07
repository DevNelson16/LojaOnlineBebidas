<?php
$tituloPagina = $tituloPagina ?? 'Loja de Bebidas'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $tituloPagina; ?></title>
    <link rel="stylesheet" href="./../assets/css/styles.css">
</head>

<body>
    <header>
        <h1>
            Loja de bebidas
        </h1>
        <nav>
            <a href="/index.php">Inicio</a>
            <a href="">Carrinho</a>
            <a href="">Admin</a>
        </nav>
    </header>
    <main>
        
    </main>

    <footer>
         <p>&copy; <?php echo date('Y'); ?> Loja de Bebidas - Projeto de aprendizagem</p>
         <script src = "assets/js/main.js"></script>
    </footer>
</body>
</html>