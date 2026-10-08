<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/auth.php';

exigir_admin();

$mensagens = [
    'criado' => 'Produto criado com sucesso.',
    'atualizado' => 'Produto atualizado com sucesso.',
    'estado' => 'Estado do produto alterado.',
    'eliminado' => 'Produto eliminado.',
    'vendido' => 'Este produto já foi vendido e não pode ser eliminado. Desative-o em vez disso.',
    'invalido' => 'Pedido inválido.',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);
    $msg = 'invalido';

    if ($id > 0 && $acao === 'alternar') {
        $stmt = $pdo->prepare('UPDATE produtos SET ativo = 1 - ativo WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $msg = 'estado';
    } elseif ($id > 0 && $acao === 'eliminar') {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM itens_encomenda WHERE produto_id = :id');
        $stmt->execute(['id' => $id]);

        if ($stmt->fetchColumn() > 0) {
            $msg = 'vendido';
        } else {
            $stmt = $pdo->prepare('DELETE FROM produtos WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $msg = 'eliminado';
        }
    }

    header('Location: produtos.php?msg=' . $msg);
    exit;
}

$chaveMsg = $_GET['msg'] ?? '';
$mensagem = $mensagens[$chaveMsg] ?? '';
$classeMsg = in_array($chaveMsg, ['vendido', 'invalido'], true) ? 'caixa-erros' : 'caixa-sucesso';

$produtos = $pdo->query(
    'SELECT p.id, p.nome, p.preco, p.stock, p.emoji, p.ativo, c.nome AS categoria
     FROM produtos p
     JOIN categorias c ON p.categoria_id = c.id
     ORDER BY p.nome'
)->fetchAll();

$tituloPagina = 'Produtos - Administração';
include __DIR__ . '/includes/header.php';
?>

<div class="barra-acoes">
    <h2>Produtos</h2>
    <a href="produto-form.php" class="btn">+ Novo produto</a>
</div>

<?php if ($mensagem !== ''): ?>
    <div class="<?php echo $classeMsg; ?>"><?php echo e($mensagem); ?></div>
<?php endif; ?>

<table class="tabela-admin">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Categoria</th>
            <th>Preço</th>
            <th>Stock</th>
            <th>Estado</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $produto): ?>
            <tr class="<?php echo $produto['ativo'] ? '' : 'linha-inativa'; ?>">
                <td><?php echo e($produto['emoji']); ?> <?php echo e($produto['nome']); ?></td>
                <td><?php echo e($produto['categoria']); ?></td>
                <td><?php echo formatar_preco($produto['preco']); ?></td>
                <td><?php echo (int) $produto['stock']; ?></td>
                <td>
                    <?php if ($produto['ativo']): ?>
                        <span class="estado estado-ativo">Ativo</span>
                    <?php else: ?>
                        <span class="estado estado-inativo">Inativo</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="acoes-tabela">
                        <a href="produto-form.php?id=<?php echo (int) $produto['id']; ?>" class="btn btn-secundario btn-pequeno">Editar</a>

                        <form method="post" action="produtos.php">
                            <input type="hidden" name="id" value="<?php echo (int) $produto['id']; ?>">
                            <input type="hidden" name="acao" value="alternar">
                            <button type="submit" class="btn btn-secundario btn-pequeno">
                                <?php echo $produto['ativo'] ? 'Desativar' : 'Ativar'; ?>
                            </button>
                        </form>

                        <form method="post" action="produtos.php" onsubmit="return confirm('Eliminar este produto?');">
                            <input type="hidden" name="id" value="<?php echo (int) $produto['id']; ?>">
                            <input type="hidden" name="acao" value="eliminar">
                            <button type="submit" class="btn btn-perigo btn-pequeno">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/includes/footer.php'; ?>