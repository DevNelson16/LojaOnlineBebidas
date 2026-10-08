const CHAVE_CARRINHO = 'carrinho_bebidas';

function obterCarrinho() {
    const guardado = localStorage.getItem(CHAVE_CARRINHO);

    if (guardado === null) {
        return [];
    }

    try {
        return JSON.parse(guardado);
    } catch (erro) {
        return [];
    }
}

function guardarCarrinho(carrinho) {
    localStorage.setItem(CHAVE_CARRINHO, JSON.stringify(carrinho));
    atualizarContador();
}

function formatarPreco(valor) {
    return valor.toFixed(2).replace('.', ',') + ' €';
}

function atualizarContador() {
    const carrinho = obterCarrinho();
    let totalItens = 0;

    for (const item of carrinho) {
        totalItens += item.quantidade;
    }

    const contador = document.getElementById('contador-carrinho');
    if (contador) {
        contador.textContent = totalItens;
    }
}

let categoriaAtiva = 'todas';

function normalizarTexto(texto) {
    return texto
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .trim();
}

function filtrarProdutos() {
    const campoPesquisa = document.getElementById('pesquisa');
    const termo = normalizarTexto(campoPesquisa.value);
    const cards = document.querySelectorAll('#lista-produtos .card');
    let visiveis = 0;

    cards.forEach(function (card) {
        const nome = normalizarTexto(card.dataset.nome);
        const categoria = card.dataset.categoria;

        const categoriaOk = categoriaAtiva === 'todas' || categoria === categoriaAtiva;
        const nomeOk = nome.includes(termo);

        if (categoriaOk && nomeOk) {
            card.style.display = '';
            visiveis++;
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('sem-resultados').hidden = visiveis > 0;
}

function iniciarPesquisaEFiltros() {
    const campoPesquisa = document.getElementById('pesquisa');
    if (!campoPesquisa) {
        return;
    }

    campoPesquisa.addEventListener('input', filtrarProdutos);

    const botoes = document.querySelectorAll('.btn-categoria');
    botoes.forEach(function (botao) {
        botao.addEventListener('click', function () {
            categoriaAtiva = botao.dataset.categoria;

            botoes.forEach(function (b) {
                b.classList.remove('ativa');
            });
            botao.classList.add('ativa');

            filtrarProdutos();
        });
    });
}

function adicionarAoCarrinho(produto, quantidade) {
    const carrinho = obterCarrinho();
    const existente = carrinho.find(function (item) {
        return item.id === produto.id;
    });

    if (existente) {
        existente.quantidade += quantidade;
    } else {
        carrinho.push({
            id: produto.id,
            nome: produto.nome,
            preco: produto.preco,
            emoji: produto.emoji,
            quantidade: quantidade
        });
    }

    guardarCarrinho(carrinho);
}

function mostrarFeedback(botao) {
    const textoOriginal = botao.textContent;
    botao.textContent = 'Adicionado ✓';
    botao.disabled = true;

    setTimeout(function () {
        botao.textContent = textoOriginal;
        botao.disabled = false;
    }, 1000);
}

function iniciarBotoesAdicionar() {
    const botoes = document.querySelectorAll('.btn-adicionar');

    botoes.forEach(function (botao) {
        botao.addEventListener('click', function () {
            const card = botao.closest('.card');

            const produto = {
                id: Number(card.dataset.id),
                nome: card.dataset.nome,
                preco: parseFloat(card.dataset.preco),
                emoji: card.querySelector('.card-imagem').textContent.trim()
            };

            adicionarAoCarrinho(produto, 1);
            mostrarFeedback(botao);
        });
    });
}

function iniciarDetalheProduto() {
    const botao = document.getElementById('btn-adicionar-detalhe');
    if (!botao) {
        return;
    }

    botao.addEventListener('click', function () {
        const secao = document.getElementById('detalhe-produto');
        const campoQuantidade = document.getElementById('quantidade');
        const quantidade = parseInt(campoQuantidade.value, 10);

        if (isNaN(quantidade) || quantidade < 1 || quantidade > 99) {
            alert('Indique uma quantidade entre 1 e 99.');
            campoQuantidade.focus();
            return;
        }

        const produto = {
            id: Number(secao.dataset.id),
            nome: secao.dataset.nome,
            preco: parseFloat(secao.dataset.preco),
            emoji: secao.dataset.emoji
        };

        adicionarAoCarrinho(produto, quantidade);
        mostrarFeedback(botao);
    });
}

function desenharCarrinho() {
    const corpo = document.getElementById('corpo-carrinho');
    if (!corpo) {
        return;
    }

    const carrinho = obterCarrinho();
    const conteudo = document.getElementById('conteudo-carrinho');
    const vazio = document.getElementById('carrinho-vazio');

    conteudo.hidden = carrinho.length === 0;
    vazio.hidden = carrinho.length > 0;

    let html = '';
    let total = 0;

    for (const item of carrinho) {
        const subtotal = item.preco * item.quantidade;
        total += subtotal;

        html += `
            <tr>
                <td>${item.emoji} ${item.nome}</td>
                <td>${formatarPreco(item.preco)}</td>
                <td>
                    <div class="controlo-quantidade">
                        <button class="btn-qtd" data-acao="menos" data-id="${item.id}">−</button>
                        <span>${item.quantidade}</span>
                        <button class="btn-qtd" data-acao="mais" data-id="${item.id}">+</button>
                    </div>
                </td>
                <td>${formatarPreco(subtotal)}</td>
                <td><button class="btn-remover" data-acao="remover" data-id="${item.id}">Remover</button></td>
            </tr>
        `;
    }

    corpo.innerHTML = html;
    document.getElementById('total-carrinho').textContent = formatarPreco(total);
}

function iniciarCarrinho() {
    const corpo = document.getElementById('corpo-carrinho');
    if (!corpo) {
        return;
    }

    corpo.addEventListener('click', function (evento) {
        const botao = evento.target.closest('button');
        if (!botao) {
            return;
        }

        const id = Number(botao.dataset.id);
        const acao = botao.dataset.acao;
        let carrinho = obterCarrinho();

        if (acao === 'remover') {
            carrinho = carrinho.filter(function (item) {
                return item.id !== id;
            });
        } else {
            const item = carrinho.find(function (i) {
                return i.id === id;
            });

            if (acao === 'mais' && item.quantidade < 99) {
                item.quantidade++;
            }
            if (acao === 'menos' && item.quantidade > 1) {
                item.quantidade--;
            }
        }

        guardarCarrinho(carrinho);
        desenharCarrinho();
    });

    desenharCarrinho();
}

function iniciarCheckout() {
    const formulario = document.getElementById('form-checkout');
    if (!formulario) {
        return;
    }

    const carrinho = obterCarrinho();
    const lista = document.getElementById('lista-resumo');
    let html = '';
    let total = 0;

    for (const item of carrinho) {
        const subtotal = item.preco * item.quantidade;
        total += subtotal;
        html += `<li><span>${item.emoji} ${item.nome} × ${item.quantidade}</span><span>${formatarPreco(subtotal)}</span></li>`;
    }

    if (carrinho.length === 0) {
        html = '<li>O carrinho está vazio. <a href="index.php">Ver produtos</a></li>';
    }

    lista.innerHTML = html;
    document.getElementById('total-checkout').textContent = formatarPreco(total);

    formulario.addEventListener('submit', function (evento) {
        const carrinhoAtual = obterCarrinho();

        if (carrinhoAtual.length === 0) {
            evento.preventDefault();
            alert('O carrinho está vazio.');
            return;
        }

        const itens = carrinhoAtual.map(function (item) {
            return { id: item.id, quantidade: item.quantidade };
        });

        document.getElementById('campo-carrinho').value = JSON.stringify(itens);
    });
}

function limparCarrinhoAposEncomenda() {
    if (!document.getElementById('encomenda-sucesso')) {
        return;
    }

    localStorage.removeItem(CHAVE_CARRINHO);
    atualizarContador();
}

atualizarContador();
iniciarPesquisaEFiltros();
iniciarBotoesAdicionar();
iniciarDetalheProduto();
iniciarCarrinho();
iniciarCheckout();
limparCarrinhoAposEncomenda();