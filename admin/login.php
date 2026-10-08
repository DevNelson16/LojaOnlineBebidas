<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/auth.php';

if (admin_autenticado()) {
    header('Location: index.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utilizador = trim($_POST['utilizador'] ?? '');
    $palavraPasse = $_POST['palavra_passe'] ?? '';

    $stmt = $pdo->prepare('SELECT id, palavra_passe FROM administradores WHERE utilizador = :utilizador');
    $stmt->execute(['utilizador' => $utilizador]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($palavraPasse, $admin['palavra_passe'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int) $admin['id'];
        $_SESSION['admin_nome'] = $utilizador;

        header('Location: index.php');
        exit;
    }

    $erro = 'Utilizador ou palavra-passe incorretos.';
}

$tituloPagina = 'Login - Administração';
include __DIR__ . '/includes/header.php';
?>

<div class="caixa-login">
    <h2>Entrar na administração</h2>

    <?php if ($erro !== ''): ?>
        <div class="caixa-erros"><?php echo e($erro); ?></div>
    <?php endif; ?>

    <form method="post" action="login.php" class="formulario-checkout">
        <label for="utilizador">Utilizador</label>
        <input type="text" id="utilizador" name="utilizador" value="<?php echo e($_POST['utilizador'] ?? ''); ?>" required>

        <label for="palavra_passe">Palavra-passe</label>
        <input type="password" id="palavra_passe" name="palavra_passe" required>

        <button type="submit" class="btn btn-grande">Entrar</button>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>