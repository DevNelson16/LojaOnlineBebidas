<?php
require_once __DIR__ . '/includes/funcoes.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id < 1) {
    header('Location: index.php');
    exit;
}

$tituloPagina = 'Encomenda registada - Loja de Bebidas';
include 'includes/header.php';
?>

<section class="mensagem-sucesso" id="encomenda-sucesso">
    <h2>Obrigado pela sua encomenda! ✅</h2>
    <p>A encomenda <strong>n.º <?php echo $id; ?></strong> foi registada com sucesso.</p>
    <a href="index.php" class="btn">Continuar a comprar</a>
</section>

<?php include 'includes/footer.php'; ?>