<?php
$tituloPagina = 'Início - Loja de Bebidas';
include 'includes/header.php';
?>

<section class="barra-pesquisa">
    <input type="text" id="pesquisa" placeholder="pesquisar bebidas...">
</section>

<section>
    <button class="btn-categoria ativa" data-categorias="todas">Todos</button>
    <button class="btn-categoria ativa" data-categorias="refrigerantes">Refrigerantes</button>
    <button class="btn-categoria ativa" data-categorias="sumos">Sumos</button>
    <button class="btn-categoria ativa" data-categorias="aguas">Águas</button><button class="btn-categoria ativa" data-categorias="todas">Todos</button>
    <button class="btn-categoria ativa" data-categorias="cervejas">Cervejas</button>
    <button class="btn-categoria ativa" data-categorias="vinhos">Vinhos</button>
</section>

<section class="grelha-produtos" id="lista-produtos">

    <article class="card" data-categoria="refrigerantes" data-nome="Coca-Cola 33cl">
        <div class="card-imagem">🥤</div>
        <div class="card-corpo">
            <span class="card-categoria">Refrigerantes</span>
            <h3>Coca-Cola 33cl</h3>
            <p class="preco">1,20 €</p>
            <div class="card-botoes">
                <a href="produto.php" class="btn btn-secundario">Detalhes</a>
                <button class="btn">Adicionar</button>
            </div>
        </div>
    </article>

    <article class="card" data-categoria="sumos" data-nome="Sumo de Laranja 1L">
        <div class="card-imagem">🍊</div>
        <div class="card-corpo">
            <span class="card-categoria">Sumos</span>
            <h3>Sumo de Laranja 1L</h3>
            <p class="preco">2,50 €</p>
            <div class="card-botoes">
                <a href="produto.php" class="btn btn-secundario">Detalhes</a>
                <button class="btn">Adicionar</button>
            </div>
        </div>
    </article>

    <article class="card" data-categoria="aguas" data-nome="Água Mineral 1,5L">
        <div class="card-imagem">💧</div>
        <div class="card-corpo">
            <span class="card-categoria">Águas</span>
            <h3>Água Mineral 1,5L</h3>
            <p class="preco">0,70 €</p>
            <div class="card-botoes">
                <a href="produto.php" class="btn btn-secundario">Detalhes</a>
                <button class="btn">Adicionar</button>
            </div>
        </div>
    </article>

    <article class="card" data-categoria="cervejas" data-nome="Cerveja Lager 33cl">
        <div class="card-imagem">🍺</div>
        <div class="card-corpo">
            <span class="card-categoria">Cervejas</span>
            <h3>Cerveja Lager 33cl</h3>
            <p class="preco">1,10 €</p>
            <div class="card-botoes">
                <a href="produto.php" class="btn btn-secundario">Detalhes</a>
                <button class="btn">Adicionar</button>
            </div>
        </div>
    </article>

    <article class="card" data-categoria="vinhos" data-nome="Vinho Tinto Alentejo">
        <div class="card-imagem">🍷</div>
        <div class="card-corpo">
            <span class="card-categoria">Vinhos</span>
            <h3>Vinho Tinto Alentejo</h3>
            <p class="preco">6,90 €</p>
            <div class="card-botoes">
                <a href="produto.php" class="btn btn-secundario">Detalhes</a>
                <button class="btn">Adicionar</button>
            </div>
        </div>
    </article>

    <article class="card" data-id="1" data-categoria="refrigerantes" data-nome="Coca-Cola 33cl" data-preco="1.20">
    <div class="card-imagem">🥤</div>
    <div class="card-corpo">
        <span class="card-categoria">Refrigerantes</span>
        <h3>Coca-Cola 33cl</h3>
        <p class="preco">1,20 €</p>
        <div class="card-botoes">
            <a href="produto.php" class="btn btn-secundario">Detalhes</a>
            <button class="btn btn-adicionar">Adicionar</button>
        </div>
    </div>
</article>
</section>

<p class="sem-resultados" id="sem-resultados" hidden>Nenhum produto encontrado.</p>

<footer>
    <?php include 'includes/footer.php'; ?>
</footer>