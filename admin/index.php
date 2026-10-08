<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/auth.php';

exigir_admin();

$totalProdutos = $pdo->query('SELECT COUNT(*) FROM produtos WHERE ativo = 1')->fetchColumn();
$totalEncomendas = $pdo->query('SELECT COUNT(*) FROM encomendas')->fetchColumn();
$pendentes = $pdo->query("SELECT COUNT(*) FROM encomendas WHERE estado = 'pendente'")->fetchColumn();
$totalVendas = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM encomendas WHERE estado <> 'cancelada'")->fetchColumn();

$tituloPagina = 'Painel - Administração';
include __DIR__ . '/includes/header.php';
?>

<h2 class="titulo-seccao">Painel</h2>
<p>Bem-vindo, <?php echo e($_SESSION['admin_nome']); ?>.</p>

<section class="cartoes-resumo">
    <div class="cartao-resumo">
        <span class="cartao-numero"><?php echo (int) $totalProdutos; ?></span>
        <span>Produtos ativos</span>
    </div>
    <div class="cartao-resumo">
        <span class="cartao-numero"><?php echo (int) $totalEncomendas; ?></span>
        <span>Encomendas</span>
    </div>
    <div class="cartao-resumo">
        <span class="cartao-numero"><?php echo (int) $pendentes; ?></span>
        <span>Pendentes</span>
    </div>
    <div class="cartao-resumo">
        <span class="cartao-numero"><?php echo formatar_preco($totalVendas); ?></span>
        <span>Total de vendas</span>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>