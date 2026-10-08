<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$erros = [];
$dados = [
    'nome' => '',
    'email' => '',
    'telefone' => '',
    'morada' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados['nome'] = trim($_POST['nome'] ?? '');
    $dados['email'] = trim($_POST['email'] ?? '');
    $dados['telefone'] = trim($_POST['telefone'] ?? '');
    $dados['morada'] = trim($_POST['morada'] ?? '');
    $itensCarrinho = json_decode($_POST['carrinho'] ?? '[]', true);
        if (mb_strlen($dados['nome']) < 3) {
        $erros[] = 'Indique o seu nome (mínimo 3 caracteres).';
    }

    if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Indique um email válido.';
    }

    if ($dados['telefone'] !== '' && !preg_match('/^[0-9+ ]{9,20}$/', $dados['telefone'])) {
        $erros[] = 'O telefone só pode ter números, espaços e +.';
    }

    if (mb_strlen($dados['morada']) < 10) {
        $erros[] = 'Indique a morada completa (mínimo 10 caracteres).';
    }

    if (!is_array($itensCarrinho) || count($itensCarrinho) === 0) {
        $erros[] = 'O carrinho está vazio.';
    }

        $linhas = [];
    $total = 0;

    if (empty($erros)) {
        $stmtProduto = $pdo->prepare(
            'SELECT id, nome, preco FROM produtos WHERE id = :id AND ativo = 1'
        );

        foreach ($itensCarrinho as $item) {
            if (!is_array($item)) {
                $erros[] = 'Carrinho inválido.';
                break;
            }

            $produtoId = (int) ($item['id'] ?? 0);
            $quantidade = (int) ($item['quantidade'] ?? 0);

            if ($quantidade < 1 || $quantidade > 99) {
                $erros[] = 'Quantidade inválida no carrinho.';
                break;
            }

            $stmtProduto->execute(['id' => $produtoId]);
            $produto = $stmtProduto->fetch();

            if (!$produto) {
                $erros[] = 'Um dos produtos do carrinho já não está disponível.';
                break;
            }

            $linhas[] = [
                'id' => $produto['id'],
                'nome' => $produto['nome'],
                'preco' => $produto['preco'],
                'quantidade' => $quantidade,
            ];
            $total += $produto['preco'] * $quantidade;
        }
    }
        if (empty($erros)) {
        try {
            $pdo->beginTransaction();

            $stmtEncomenda = $pdo->prepare(
                'INSERT INTO encomendas (nome_cliente, email, telefone, morada, total)
                 VALUES (:nome, :email, :telefone, :morada, :total)'
            );
            $stmtEncomenda->execute([
                'nome' => $dados['nome'],
                'email' => $dados['email'],
                'telefone' => $dados['telefone'] !== '' ? $dados['telefone'] : null,
                'morada' => $dados['morada'],
                'total' => round($total, 2),
            ]);
            $idEncomenda = (int) $pdo->lastInsertId();

            $stmtStock = $pdo->prepare(
                'UPDATE produtos SET stock = stock - :qtd WHERE id = :id AND stock >= :minimo'
            );
            $stmtItem = $pdo->prepare(
                'INSERT INTO itens_encomenda (encomenda_id, produto_id, quantidade, preco_unitario)
                 VALUES (:encomenda, :produto, :quantidade, :preco)'
            );

            foreach ($linhas as $linha) {
                $stmtStock->execute([
                    'qtd' => $linha['quantidade'],
                    'id' => $linha['id'],
                    'minimo' => $linha['quantidade'],
                ]);

                if ($stmtStock->rowCount() === 0) {
                    throw new RuntimeException('Stock insuficiente para ' . $linha['nome'] . '.');
                }

                $stmtItem->execute([
                    'encomenda' => $idEncomenda,
                    'produto' => $linha['id'],
                    'quantidade' => $linha['quantidade'],
                    'preco' => $linha['preco'],
                ]);
            }

            $pdo->commit();

            header('Location: encomenda-sucesso.php?id=' . $idEncomenda);
            exit;
        } catch (RuntimeException $erro) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $erros[] = $erro->getMessage();
        } catch (PDOException $erro) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $erros[] = 'Ocorreu um erro ao guardar a encomenda. Tente novamente.';
        }
    }
}
<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$erros = [];
$dados = [
    'nome' => '',
    'email' => '',
    'telefone' => '',
    'morada' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // A1: ler os dados do formulário
    $dados['nome'] = trim($_POST['nome'] ?? '');
    $dados['email'] = trim($_POST['email'] ?? '');
    $dados['telefone'] = trim($_POST['telefone'] ?? '');
    $dados['morada'] = trim($_POST['morada'] ?? '');
    $itensCarrinho = json_decode($_POST['carrinho'] ?? '[]', true);

    // A2: validar os campos
    if (mb_strlen($dados['nome']) < 3) {
        $erros[] = 'Indique o seu nome (mínimo 3 caracteres).';
    }

    if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Indique um email válido.';
    }

    if ($dados['telefone'] !== '' && !preg_match('/^[0-9+ ]{9,20}$/', $dados['telefone'])) {
        $erros[] = 'O telefone só pode ter números, espaços e +.';
    }

    if (mb_strlen($dados['morada']) < 10) {
        $erros[] = 'Indique a morada completa (mínimo 10 caracteres).';
    }

    if (!is_array($itensCarrinho) || count($itensCarrinho) === 0) {
        $erros[] = 'O carrinho está vazio.';
    }

    // A3: confirmar os produtos na BD e calcular o total
    $linhas = [];
    $total = 0;

    if (empty($erros)) {
        $stmtProduto = $pdo->prepare(
            'SELECT id, nome, preco FROM produtos WHERE id = :id AND ativo = 1'
        );

        foreach ($itensCarrinho as $item) {
            if (!is_array($item)) {
                $erros[] = 'Carrinho inválido.';
                break;
            }

            $produtoId = (int) ($item['id'] ?? 0);
            $quantidade = (int) ($item['quantidade'] ?? 0);

            if ($quantidade < 1 || $quantidade > 99) {
                $erros[] = 'Quantidade inválida no carrinho.';
                break;
            }

            $stmtProduto->execute(['id' => $produtoId]);
            $produto = $stmtProduto->fetch();

            if (!$produto) {
                $erros[] = 'Um dos produtos do carrinho já não está disponível.';
                break;
            }

            $linhas[] = [
                'id' => $produto['id'],
                'nome' => $produto['nome'],
                'preco' => $produto['preco'],
                'quantidade' => $quantidade,
            ];
            $total += $produto['preco'] * $quantidade;
        }
    }

    // A4: guardar a encomenda (transação)
    if (empty($erros)) {
        try {
            $pdo->beginTransaction();

            $stmtEncomenda = $pdo->prepare(
                'INSERT INTO encomendas (nome_cliente, email, telefone, morada, total)
                 VALUES (:nome, :email, :telefone, :morada, :total)'
            );
            $stmtEncomenda->execute([
                'nome' => $dados['nome'],
                'email' => $dados['email'],
                'telefone' => $dados['telefone'] !== '' ? $dados['telefone'] : null,
                'morada' => $dados['morada'],
                'total' => round($total, 2),
            ]);
            $idEncomenda = (int) $pdo->lastInsertId();

            $stmtStock = $pdo->prepare(
                'UPDATE produtos SET stock = stock - :qtd WHERE id = :id AND stock >= :minimo'
            );
            $stmtItem = $pdo->prepare(
                'INSERT INTO itens_encomenda (encomenda_id, produto_id, quantidade, preco_unitario)
                 VALUES (:encomenda, :produto, :quantidade, :preco)'
            );

            foreach ($linhas as $linha) {
                $stmtStock->execute([
                    'qtd' => $linha['quantidade'],
                    'id' => $linha['id'],
                    'minimo' => $linha['quantidade'],
                ]);

                if ($stmtStock->rowCount() === 0) {
                    throw new RuntimeException('Stock insuficiente para ' . $linha['nome'] . '.');
                }

                $stmtItem->execute([
                    'encomenda' => $idEncomenda,
                    'produto' => $linha['id'],
                    'quantidade' => $linha['quantidade'],
                    'preco' => $linha['preco'],
                ]);
            }

            $pdo->commit();

            header('Location: encomenda-sucesso.php?id=' . $idEncomenda);
            exit;
        } catch (RuntimeException $erro) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $erros[] = $erro->getMessage();
        } catch (PDOException $erro) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $erros[] = 'Ocorreu um erro ao guardar a encomenda. Tente novamente.';
        }
    }
}
?>

<?php
$tituloPagina = 'Finalizar encomenda - Loja de Bebidas';
include 'includes/header.php';
?>

<h2 class="titulo-seccao">Finalizar encomenda</h2>

<?php if (!empty($erros)): ?>
    <div class="caixa-erros">
        <ul>
            <?php foreach ($erros as $erro): ?>
                <li><?php echo e($erro); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<section class="checkout">
    <form method="post" action="checkout.php" id="form-checkout" class="formulario-checkout">
        <label for="nome">Nome *</label>
        <input type="text" id="nome" name="nome" maxlength="100"
               value="<?php echo e($dados['nome']); ?>" required>

        <label for="email">Email *</label>
        <input type="email" id="email" name="email" maxlength="100"
               value="<?php echo e($dados['email']); ?>" required>

        <label for="telefone">Telefone</label>
        <input type="tel" id="telefone" name="telefone" maxlength="20"
               value="<?php echo e($dados['telefone']); ?>">

        <label for="morada">Morada de entrega *</label>
        <textarea id="morada" name="morada" rows="3" maxlength="255" required><?php echo e($dados['morada']); ?></textarea>

        <input type="hidden" name="carrinho" id="campo-carrinho">

        <button type="submit" class="btn btn-grande">Confirmar encomenda</button>
    </form>

    <aside class="resumo-carrinho">
        <h3>A sua encomenda</h3>
        <ul class="lista-resumo" id="lista-resumo"></ul>
        <p>Total: <strong id="total-checkout">0,00 €</strong></p>
        <a href="carrinho.php" class="link-voltar">← Alterar carrinho</a>
    </aside>
</section>

<?php include 'includes/footer.php'; ?>