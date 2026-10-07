<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$tituloPagina = 'Início - Loja de Bebidas';

$categorias = $pdo->query('SELECT nome, slug FROM categorias ORDER BY nome')->fetchAll();

$sql = 'SELECT p.id, p.nome, p.preco, p.emoji,
               c.nome AS categoria_nome, c.slug AS categoria_slug
        FROM produtos p
        JOIN categorias c ON p.categoria_id = c.id
        WHERE p.ativo = 1
        ORDER BY p.nome';
$produtos = $pdo->query($sql)->fetchAll();

include 'includes/header.php';
?>

<section class="barra-pesquisa">
    <input type="text" id="pesquisa" placeholder="Pesquisar bebidas...">
</section>

<section class="categorias">
    <button class="btn-categoria ativa" data-categoria="todas">Todas</button>
    <?php foreach ($categorias as $categoria): ?>
        <button class="btn-categoria" data-categoria="<?php echo e($categoria['slug']); ?>">
            <?php echo e($categoria['nome']); ?>
        </button>
    <?php endforeach; ?>
</section>

<h2 class="titulo-seccao">Os nossos produtos</h2>

<section class="grelha-produtos" id="lista-produtos">
    <?php foreach ($produtos as $produto): ?>
        <article class="card"
            data-id="<?php echo (int) $produto['id']; ?>"
            data-categoria="<?php echo e($produto['categoria_slug']); ?>"
            data-nome="<?php echo e($produto['nome']); ?>"
            data-preco="<?php echo e($produto['preco']); ?>">
            <div class="card-imagem"><?php echo e($produto['emoji']); ?></div>
            <div class="card-corpo">
                <span class="card-categoria"><?php echo e($produto['categoria_nome']); ?></span>
                <h3><?php echo e($produto['nome']); ?></h3>
                <p class="preco"><?php echo formatar_preco($produto['preco']); ?></p>
                <div class="card-botoes">
                    <a href="produto.php?id=<?php echo (int) $produto['id']; ?>" class="btn btn-secundario">Detalhes</a>
                    <button class="btn btn-adicionar">Adicionar</button>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
</section>

<p class="sem-resultados" id="sem-resultados" <?php echo empty($produtos) ? '' : 'hidden'; ?>>
    Nenhum produto encontrado.
</p>

<?php include 'includes/footer.php'; ?>