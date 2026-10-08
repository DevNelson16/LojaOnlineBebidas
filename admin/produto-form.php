<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/auth.php';

exigir_admin();

$id = (int) ($_GET['id'] ?? 0);
$editar = $id > 0;

$categorias = $pdo->query('SELECT id, nome FROM categorias ORDER BY nome')->fetchAll();
$idsCategorias = array_map('intval', array_column($categorias, 'id'));

$dados = [
    'nome' => '',
    'categoria_id' => 0,
    'descricao' => '',
    'preco' => '',
    'stock' => '0',
    'emoji' => '🥤',
    'ativo' => 1,
];
$erros = [];

if ($editar) {
    $stmt = $pdo->prepare(
        'SELECT nome, categoria_id, descricao, preco, stock, emoji, ativo FROM produtos WHERE id = :id'
    );
    $stmt->execute(['id' => $id]);
    $existente = $stmt->fetch();

    if (!$existente) {
        http_response_code(404);
        $tituloPagina = 'Produto não encontrado - Administração';
        include __DIR__ . '/includes/header.php';
        echo '<p class="sem-resultados">Produto não encontrado. <a href="produtos.php">Voltar</a></p>';
        include __DIR__ . '/includes/footer.php';
        exit;
    }

    $dados = $existente;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados['nome'] = trim($_POST['nome'] ?? '');
    $dados['categoria_id'] = (int) ($_POST['categoria_id'] ?? 0);
    $dados['descricao'] = trim($_POST['descricao'] ?? '');
    $dados['preco'] = trim($_POST['preco'] ?? '');
    $dados['stock'] = trim($_POST['stock'] ?? '');
    $dados['emoji'] = trim($_POST['emoji'] ?? '');
    $dados['ativo'] = isset($_POST['ativo']) ? 1 : 0;

    $precoNumero = str_replace(',', '.', $dados['preco']);
    $stockNumero = filter_var(
        $dados['stock'],
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 0, 'max_range' => 100000]]
    );

    if (mb_strlen($dados['nome']) < 2 || mb_strlen($dados['nome']) > 100) {
        $erros[] = 'O nome deve ter entre 2 e 100 caracteres.';
    }

    if (!in_array($dados['categoria_id'], $idsCategorias, true)) {
        $erros[] = 'Escolha uma categoria válida.';
    }

    if (!is_numeric($precoNumero) || $precoNumero <= 0 || $precoNumero > 9999) {
        $erros[] = 'Indique um preço válido (maior que 0).';
    }

    if ($stockNumero === false) {
        $erros[] = 'O stock tem de ser um número inteiro (0 ou mais).';
    }

    if (mb_strlen($dados['emoji']) < 1 || mb_strlen($dados['emoji']) > 10) {
        $erros[] = 'Indique um emoji (até 10 caracteres).';
    }

    if (empty($erros)) {
        $parametros = [
            'categoria_id' => $dados['categoria_id'],
            'nome' => $dados['nome'],
            'descricao' => $dados['descricao'] !== '' ? $dados['descricao'] : null,
            'preco' => round((float) $precoNumero, 2),
            'stock' => $stockNumero,
            'emoji' => $dados['emoji'],
            'ativo' => $dados['ativo'],
        ];

        try {
            if ($editar) {
                $sql = 'UPDATE produtos
                        SET categoria_id = :categoria_id, nome = :nome, descricao = :descricao,
                            preco = :preco, stock = :stock, emoji = :emoji, ativo = :ativo
                        WHERE id = :id';
                $parametros['id'] = $id;
                $msg = 'atualizado';
            } else {
                $sql = 'INSERT INTO produtos (categoria_id, nome, descricao, preco, stock, emoji, ativo)
                        VALUES (:categoria_id, :nome, :descricao, :preco, :stock, :emoji, :ativo)';
                $msg = 'criado';
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($parametros);

            header('Location: produtos.php?msg=' . $msg);
            exit;
        } catch (PDOException $erro) {
            $erros[] = 'Erro ao guardar o produto. Tente novamente.';
        }
    }
}

$tituloPagina = ($editar ? 'Editar produto' : 'Novo produto') . ' - Administração';
include __DIR__ . '/includes/header.php';
?>

<a href="produtos.php" class="link-voltar">← Voltar aos produtos</a>

<h2 class="titulo-seccao"><?php echo $editar ? 'Editar produto' : 'Novo produto'; ?></h2>

<?php if (!empty($erros)): ?>
    <div class="caixa-erros">
        <ul>
            <?php foreach ($erros as $erro): ?>
                <li><?php echo e($erro); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="caixa-form">
    <form method="post" action="produto-form.php<?php echo $editar ? '?id=' . $id : ''; ?>" class="formulario-checkout">
        <label for="nome">Nome *</label>
        <input type="text" id="nome" name="nome" maxlength="100" value="<?php echo e($dados['nome']); ?>" required>

        <label for="categoria_id">Categoria *</label>
        <select id="categoria_id" name="categoria_id" required>
            <option value="">— Escolha —</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?php echo (int) $categoria['id']; ?>"
                    <?php echo (int) $dados['categoria_id'] === (int) $categoria['id'] ? 'selected' : ''; ?>>
                    <?php echo e($categoria['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao" rows="3"><?php echo e($dados['descricao']); ?></textarea>

        <label for="preco">Preço (€) *</label>
        <input type="text" inputmode="decimal" id="preco" name="preco" value="<?php echo e($dados['preco']); ?>" required>

        <label for="stock">Stock *</label>
        <input type="number" id="stock" name="stock" min="0" value="<?php echo e($dados['stock']); ?>" required>

        <label for="emoji">Emoji *</label>
        <input type="text" id="emoji" name="emoji" maxlength="10" value="<?php echo e($dados['emoji']); ?>" required>

        <label class="label-checkbox">
            <input type="checkbox" name="ativo" value="1" <?php echo $dados['ativo'] ? 'checked' : ''; ?>>
            Produto ativo (visível na loja)
        </label>

        <button type="submit" class="btn btn-grande">
            <?php echo $editar ? 'Guardar alterações' : 'Criar produto'; ?>
        </button>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>