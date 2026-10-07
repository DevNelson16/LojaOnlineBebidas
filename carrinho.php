<p class="carrinho-vazio" id="carrinho-vazio" hidden>
    O seu carrinho está vazio. <a href="index.php">Ver produtos</a>
</p>

<section class="carrinho" id="conteudo-carrinho">
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
        <tbody id="corpo-carrinho">
            <!-- as linhas são criadas pelo JavaScript -->
        </tbody>
    </table>

    <aside class="resumo-carrinho">
        <h3>Resumo</h3>
        <p>Total: <strong id="total-carrinho">0,00 €</strong></p>
        <a href="checkout.php" class="btn btn-grande">Finalizar encomenda</a>
    </aside>
</section>