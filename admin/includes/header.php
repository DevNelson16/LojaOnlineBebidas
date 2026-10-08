<?php $tituloPagina = $tituloPagina ?? 'Administração'; ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($tituloPagina); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <header class="site-header">
        <div class="container header-conteudo">
            <a href="index.php" class="logo">⚙️ Administração</a>
            <?php if (admin_autenticado()): ?>
                <nav class="menu">
                    <a href="index.php">Painel</a>
                    <a href="encomendas.php">Encomendas</a>
                    <a href="produtos.php">Produtos</a>
                    <a href="../index.php">Ver loja</a>
                    <a href="logout.php">Sair</a>
                </nav>
            <?php endif; ?>
        </div>
    </header>
    <main class="container">
    </main>
</body>

</html>