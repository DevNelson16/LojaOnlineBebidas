<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/auth.php';

exigir_admin();

$id = (int) ($_GET['id'] ?? 0);
$estadosValidos = ['pendente', 'enviada', 'concluida', 'cancelada'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoEstado = $_POST['estado'] ?? '';

    if (in_array($novoEstado, $estadosValidos, true)) {
        $stmt = $pdo->prepare('UPDATE encomendas SET estado = :estado WHERE id = :id');
        $stmt->execute(['estado' => $novoEstado, 'id' => $id]);
    }

    header('Location: encomenda.php?id=' . $id);
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM encomendas WHERE id = :id');
$stmt->execute(['id' => $id]);
$encomenda = $stmt->fetch();

$tituloPagina = 'Encomenda - Administração';
include __DIR__ . '/includes/header.php';

if (!$encomenda) {
    echo '<p class="sem-resultados">Encomenda não encontrada. <a href="encomendas.php">Voltar</a></p>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$stmt = $pdo->prepare(
    'SELECT p.nome, p.emoji, i.quantidade, i.preco_unitario,
            (i.quantidade * i.preco_unitario) AS subtotal
     FROM itens_encomenda i
     JOIN produtos p ON i.produto_id = p.id
     WHERE i.encomenda_id = :id'
);
$stmt->execute(['id' => $id]);
$itens = $stmt->fetchAll();
?>

<a href="encomendas.php" class="link-voltar">← Voltar às encomendas</a>

<h2 class="titulo-seccao">Encomenda #<?php echo (int) $encomenda['id']; ?></h2>

<section class="caixa-admin">
    <h3>Cliente</h3>
    <p><strong>Nome:</strong> <?php echo e($encomenda['nome_cliente']); ?></p>
    <p><strong>Email:</strong> <?php echo e($encomenda['email']); ?></p>
    <p><strong>Telefone:</strong> <?php echo e($encomenda['telefone'] ?? '—'); ?></p>
    <p><strong>Morada:</strong> <?php echo e($encomenda['morada']); ?></p>
    <p><strong>Data:</strong> <?php echo e(date('d/m/Y H:i', strtotime($encomenda['criada_em']))); ?></p>
</section>

<section class="caixa-admin">
    <h3>Produtos</h3>
    <table class="tabela-admin">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Preço unitário</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($itens as $item): ?>
                <tr>
                    <td><?php echo e($item['emoji']); ?> <?php echo e($item['nome']); ?></td>
                    <td><?php echo formatar_preco($item['preco_unitario']); ?></td>
                    <td><?php echo (int) $item['quantidade']; ?></td>
                    <td><?php echo formatar_preco($item['subtotal']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p class="total-admin">Total: <strong><?php echo formatar_preco($encomenda['total']); ?></strong></p>
</section>

<section class="caixa-admin">
    <h3>Estado da encomenda</h3>
    <form method="post" action="encomenda.php?id=<?php echo (int) $encomenda['id']; ?>" class="form-estado">
        <select name="estado">
            <?php foreach ($estadosValidos as $estado): ?>
                <option value="<?php echo e($estado); ?>" <?php echo $estado === $encomenda['estado'] ? 'selected' : ''; ?>>
                    <?php echo e(ucfirst($estado)); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn">Guardar estado</button>
    </form>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>