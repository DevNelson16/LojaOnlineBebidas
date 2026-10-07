<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare(
    'SELECT p.id, p.nome, p.descricao, p.preco, p.stock, p.emoji,
            c.nome AS categoria_nome
     FROM produtos p
     JOIN categorias c ON p.categoria_id = c.id
     WHERE p.id = :id AND p.ativo = 1'
);
$stmt->execute(['id' => $id]);
$produto = $stmt->fetch();

if (!$produto) {
    http_response_code(404);
    $tituloPagina = 'Produto não encontrado - Loja de Bebidas';
    include 'includes/header.php';
    echo '<p class="sem-resultados">Produto não encontrado. <a href="index.php">Voltar aos produtos</a></p>';
    include 'includes/footer.php';
    exit;
}

$tituloPagina = $produto['nome'] . ' - Loja de Bebidas';
include 'includes/header.php';
?>

<a href="index.php" class="link-voltar">← Voltar aos produtos</a>

<section class="detalhe-produto" id="detalhe-produto"
         data-id="<?php echo (int) $produto['id']; ?>"
         data-nome="<?php echo e($produto['nome']); ?>"
         data-preco="<?php echo e($produto['preco']); ?>"
         data-emoji="<?php echo e($produto['emoji']); ?>">

    <div class="detalhe-imagem"><?php echo e($produto['emoji']); ?></div>

    <div class="detalhe-info">
        <span class="card-categoria"><?php echo e($produto['categoria_nome']); ?></span>
        <h2><?php echo e($produto['nome']); ?></h2>
        <p class="preco preco-grande"><?php echo formatar_preco($produto['preco']); ?></p>
        <p class="descricao"><?php echo e($produto['descricao']); ?></p>

        <?php if ($produto['stock'] > 0): ?>
            <p class="stock">Em stock: <?php echo (int) $produto['stock']; ?> unidades</p>

            <div class="quantidade-bloco">
                <label for="quantidade">Quantidade:</label>
                <input type="number" id="quantidade" value="1" min="1" max="99">
            </div>

            <button class="btn btn-grande" id="btn-adicionar-detalhe">Adicionar ao carrinho</button>
        <?php else: ?>
            <p class="stock">Produto esgotado.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>