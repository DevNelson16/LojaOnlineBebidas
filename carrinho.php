<?php
$tituloPagina = 'Carrinho - Loja de Bebidas';
include 'includes/header.php';
?>

<h2 class="titulo-seccao">O seu carrinho</h2>

<section class="carrinho">
    <table class="tabela-carrinho">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>🥤 Coca-Cola 33cl</td>
                <td>1,20 €</td>
                <td>
                    <div class="controlo-quantidade">
                        <button class="btn-qtd">−</button>
                        <span>2</span>
                        <button class="btn-qtd">+</button>
                    </div>
                </td>
                <td>2,40 €</td>
                <td><button class="btn-remover">Remover</button></td>
            </tr>
            <tr>
                <td>🍺 Cerveja Lager 33cl</td>
                <td>1,10 €</td>
                <td>
                    <div class="controlo-quantidade">
                        <button class="btn-qtd">−</button>
                        <span>3</span>
                        <button class="btn-qtd">+</button>
                    </div>
                </td>
                <td>3,30 €</td>
                <td><button class="btn-remover">Remover</button></td>
            </tr>
        </tbody>
    </table>

    <aside class="resumo-carrinho">
        <h3>Resumo</h3>
        <p>Total: <strong id="total-carrinho">5,70 €</strong></p>
        <a href="checkout.php" class="btn btn-grande">Finalizar encomenda</a>
    </aside>
</section>

<?php include 'includes/footer.php'; ?>