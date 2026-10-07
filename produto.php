<?php
$tituloPagina = 'Detalhes do produto - Loja de Bebidas';
include 'includes/header.php';
?>

<a href="index.php" class="link-voltar">Voltar aos produtos</a>

<section class="detalhe-produto" id="detalhe-produto"
         data-id="1" data-nome="Coca-Cola 33cl" data-preco="1.20" data-emoji="🥤">
    <div class="detalhe-imagem">🥤</div>

    <div class="detalhe-info">
        <span class="card-categoria">Refrigerantes</span>
        <h2>Coca-Cola 33cl</h2>
        <p class="preco preco-grande">1,20 €</p>
        <p class="descricao">
            Refrigerante de cola em lata de 33cl. Ideal para servir bem fresco
            em refeições, festas e encontros com amigos.
        </p>
        <p class="stock">Em stock: 120 unidades</p>

        <div class="quantidade-bloco">
            <label for="quantidade">Quantidade:</label>
            <input type="number" id="quantidade" value="1" min="1">
        </div>

        <button class="btn btn-grande" id="btn-adicionar-detalhe">Adicionar ao carrinho</button>
    </div>
</section>

<?php include 'includes/footer.php'; ?>