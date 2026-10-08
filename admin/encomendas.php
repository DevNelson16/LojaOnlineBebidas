<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/auth.php';

exigir_admin();

$encomendas = $pdo->query(
    'SELECT id, nome_cliente, total, estado, criada_em
     FROM encomendas
     ORDER BY criada_em DESC, id DESC'
)->fetchAll();

$tituloPagina = 'Encomendas - Administração';
include __DIR__ . '/includes/header.php';
?>

<h2 class="titulo-seccao">Encomendas</h2>

<?php if (empty($encomendas)): ?>
    <p class="sem-resultados">Ainda não há encomendas.</p>
<?php else: ?>
    <table class="tabela-admin">
        <thead>
            <tr>
                <th>N.º</th>
                <th>Data</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($encomendas as $encomenda): ?>
                <tr>
                    <td>#<?php echo (int) $encomenda['id']; ?></td>
                    <td><?php echo e(date('d/m/Y H:i', strtotime($encomenda['criada_em']))); ?></td>
                    <td><?php echo e($encomenda['nome_cliente']); ?></td>
                    <td><?php echo formatar_preco($encomenda['total']); ?></td>
                    <td><span class="estado estado-<?php echo e($encomenda['estado']); ?>"><?php echo e($encomenda['estado']); ?></span></td>
                    <td><a href="encomenda.php?id=<?php echo (int) $encomenda['id']; ?>" class="btn btn-secundario">Ver</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>