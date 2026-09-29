# Memória do Projeto — com_horarios (Horários e Circuitos)

> Documento de contexto para retomar o trabalho neste componente Joomla 5
> noutro computador. Última atualização: 2026-09-18 (versão do componente:
> **1.3.0**).

## O que é

Componente Joomla 5 (`com_horarios`) para o site do Médio Tejo
(`support.mediotejo.pt` / template "cimtSupport"), que reproduz a página
"Circuitos, horários e tarifários" (imagem de referência fornecida pelo
utilizador). Tem backoffice completo para gerir:

- **Municípios** — título, imagem de cartão, link "ver no mapa da região",
  ficheiro de Mapa, ficheiro de Brochura, URL de Reservas Online.
- **Circuitos e Localidades** — mesma tabela, usando `parent_id`: um registo
  com `parent_id = 0` é um Circuito; um registo com `parent_id` apontando
  para outro é uma Localidade/paragem desse circuito. Cada um tem: folheto
  informativo (PDF/imagem) + 3 separadores (Circuito, Horário, Tarifário),
  cada um com upload de imagem ou PDF.
- **Faixa de Alerta** — banner de topo (imagem única + link opcional +
  janela de publicação `publish_up`/`publish_down`).
- **Caixas de Reservas** — N caixas (imagem única + link opcional).
- **Opções do componente** — título/descrição da página, texto do aviso de
  atraso, mostrar/ocultar carrossel, itens do carrossel, e um campo de
  **CSS personalizado** livre para ajustar o aspeto sem mexer em ficheiros.

No site, tudo aparece numa única página (`view=municipios`): faixa de
alerta → caixas de reservas → título/descrição → aviso → pesquisa →
carrossel de municípios → sidebar de municípios + painel principal (Mapa/
Brochura/Reservas online + acordeão de Circuitos, cada um com folheto +
separadores + acordeão aninhado de Localidades).

## Decisões de arquitetura tomadas com o utilizador

- **Componente completo**, não um plugin (precisa de menus de admin e views
  de frontend próprias).
- Uploads de imagem/PDF usam o campo nativo `type="media"` do Joomla
  (drag-and-drop no seletor de multimédia, sem JS custom).
- **Também aceitam um URL externo completo** digitado diretamente no campo
  (em vez de upload) — ver secção de segurança abaixo sobre como isto foi
  protegido.
- Circuito e Localidade partilham a mesma tabela/entidade (`parent_id`), só
  1 nível de aninhamento (tal como a imagem de referência).
- Faixa de alerta e caixas de Reservas são **imagem única**, sem texto
  estruturado.
- Só Português (sem multilíngue nativo do Joomla).
- Ícones: **Bootstrap Icons** via CDN (`cdn.jsdelivr.net/npm/bootstrap-icons`).
- Acordeão e separadores do frontend são **100% independentes do Bootstrap
  do template** (classes e JS próprios, `horarios-acc-*` / `horarios-tab-*`)
  — importante, ver changelog v1.1.0.

## Estrutura de ficheiros

```
com_horarios/
  horarios.xml              # manifesto (versão atual: 1.1.5)
  admin/                    # backoffice (administrator/components/com_horarios)
    config.xml              # Opções do componente
    forms/                  # municipio.xml, circuito.xml, banner.xml, reserva.xml, filter_*.xml
    services/provider.php   # ÚNICO provider.php realmente carregado pelo Joomla (ver changelog v1.0.1)
    src/
      Extension/HorariosComponent.php   # implementa RouterServiceInterface
      Controller/, Model/, Table/, View/, Helper/ (MediaHelper, UrlHelper)
    tmpl/                   # templates admin (listas + formulários de edição)
  site/                     # frontend (components/com_horarios)
    src/
      Controller/, Model/, View/Municipios/
      Service/Router.php    # router SEF (classe "Router", não "RouterView"!)
      Helper/MediaHelper.php
    tmpl/municipios/        # default.php + partials (banner, reservas, search, carousel, sidebar, circuito)
    language/pt-PT/
  media/
    css/horarios.css
    js/horarios.js
    joomla.asset.json       # não usado para carregar CSS/JS (ver changelog v1.1.1), mas mantido instalado
```

## Como reinstalar / testar

1. Zipar a pasta `com_horarios/` (o `horarios.xml` tem de ficar na raiz do
   zip) e instalar via Extensões → Gerir → Instalar, por cima da instalação
   atual — não apaga dados.
2. Depois de qualquer alteração ao `media/css/horarios.css` ou
   `media/js/horarios.js`, não é preciso subir número de versão à mão: o
   `site/src/View/Municipios/HtmlView.php` usa `filemtime()` desses
   ficheiros como cache-buster automático (`?v=...`).
3. Testar sempre com hard refresh (Ctrl+Shift+R) e, se o site tiver cache de
   páginas, limpar essa cache também.

## Changelog / lições aprendidas (Joomla 5.4)

Muitos dos bugs encontrados vieram de APIs do Joomla que mudaram desde as
versões 3.x/4.x mais "clássicas" — fica aqui registado para não repetir os
mesmos erros:

- **v1.0.1** — `admin/services/provider.php` é o **único** ficheiro que o
  Joomla carrega para arrancar o componente, mesmo em pedidos do site
  (`ExtensionManagerTrait::bootComponent()` usa sempre o caminho do admin).
  Um `site/services/provider.php` separado nunca é lido. Além disso, o
  container só disponibiliza as **interfaces**
  (`ComponentDispatcherFactoryInterface`, `MVCFactoryInterface`), não as
  classes concretas do `Service\Provider\*`.
- **v1.0.2** — `HTMLHelper::_('searchtools.render', ...)` e
  `searchtools.order` **já não existem** no Joomla 5.4. Usa-se
  `LayoutHelper::render('joomla.searchtools.default', ['view' => $this])`
  e a pega de arrastar é markup manual. `$filterForm`/`$activeFilters` têm
  de ser **públicos** na View (não `protected`). `FormField::renderLabel()`/
  `renderInput()` também deixaram de existir — usar
  `$this->form->renderFieldset('nome')`. `jgrid.published` foi substituído
  por `Joomla\CMS\Button\PublishedButton`.
- **v1.0.3** — `type="ordering"` deixou de ser um campo genérico: agora
  assume que a tabela está registada no UCM (`#__content_types`) e tem uma
  coluna `catid`. Para tabelas simples como as nossas, usar `type="hidden"`
  e atribuir a posição no `Model::prepareTable()` via
  `$table->getNextOrder($where)`.
- **v1.0.5** — `Table::publish()` (usado por Publicar/Despublicar/Arquivar/
  Reciclagem) assume por omissão uma coluna chamada `published`. Como as
  nossas tabelas usam `state`, é preciso
  `$this->setColumnAlias('published', 'state')` no construtor de cada Table.
- **v1.0.6** — o router SEF (`site/src/Service/Router.php`, classe
  `Router`, **não** `RouterView`) só deve usar `setKey('id')` numa
  `RouterViewConfiguration` se também se implementarem
  `getXxxId()`/`getXxxSegment()`. Sem isso, o Joomla monta uma tabela de
  Itemid em formato de array e rebenta com "Cannot access offset of type
  array" ao construir links (`Route::_()`) para um item específico.
- **v1.0.7** — o valor de um campo `type="media"` pode vir com o sufixo
  `#joomlaImage://...`; para converter em URL usar sempre
  `Joomla\CMS\Helper\MediaHelper::getCleanMediaFieldValue()`.
- **v1.1.0 / v1.1.1** — o template do site ("cimtSupport") não invoca
  corretamente o pipeline moderno do "Web Asset Manager" do Joomla
  (`useStyle()`/`useScript()` simplesmente não carregavam nada, sem erro).
  A solução foi carregar o CSS/JS do componente da forma clássica
  (`Document::addStyleSheet()`/`addScript()` com URL direto), tal como já
  funcionava para o CDN dos ícones. Por esta razão, o acordeão/separadores
  também deixaram de usar classes genéricas do Bootstrap (`accordion`,
  `nav-tabs`, ...) — um template tão customizado como este reestiliza essas
  classes partilhadas, o que desvirtuava o layout.
- **v1.1.3** — o CSS do próprio template tinha uma regra genérica
  `input:not(.form-control), select:not(.form-select) { padding: .5rem
  !important; ... }`. Corrigido dando essas classes Bootstrap ao input/
  select da pesquisa, para ficarem isentos dessa regra.
- **v1.1.5** — auditoria de segurança: os campos de URL livre (`map_link`,
  `reservas_url`, `link_url` da Faixa/Reservas) permitiam gravar
  `javascript:...` e isso executava no browser de quem clicasse (XSS
  armazenada). Corrigido em duas camadas — `Table::check()` (gravação) e
  `Site\Helper\MediaHelper::safeUrl()` (exibição) — só aceitam
  `http(s)://` ou caminhos relativos.

- **v1.2.0** — pedido: imagens extra "como a faixa de alerta mas com outro
  nome" + um bloco de texto editável com o editor padrão do Joomla, ambos
  com posição/ordem controlável. Em vez de criar mais uma entidade/ecrã
  dentro do componente, optou-se (decisão tomada com o utilizador) por usar
  o sistema de **Módulos nativos do Joomla**: o componente agora renderiza
  duas posições de módulo, `horarios-topo` (antes do container, junto à
  faixa/reservas) e `horarios-rodape` (fim do container, dentro da área de
  conteúdo). Para usar: Conteúdo → Módulos do Site → Novo → **Personalizado**
  (dá o editor padrão TinyMCE, onde também se pode inserir imagem) → no
  campo Posição escrever manualmente `horarios-topo` ou `horarios-rodape`
  (não aparecem na lista pois não pertencem ao template ativo, mas
  funcionam à mesma) → na aba "Atribuição de Menus" escolher "Nas páginas
  selecionadas" e marcar o menu "Horários" (para não aparecer no resto do
  site) → Guardar. Vários módulos na mesma posição reordenam-se arrastando
  na lista de Módulos do Site (tal como o Joomla já permite nativamente).

- **v1.3.0** — novo campo `sidebar_image` no Município ("Imagem do menu
  lateral"): quando preenchido, substitui o botão de texto padrão da barra
  lateral por uma imagem completa (o utilizador desenha o botão inteiro,
  texto e seta incluídos, num editor de imagem à parte). Primeira alteração
  de esquema da base de dados desde a instalação inicial — introduzido o
  ficheiro `admin/sql/updates/mysql/1.3.0.sql` (o Joomla só corre este tipo
  de ficheiro numa atualização por cima de uma instalação existente; nunca
  numa instalação nova, por isso a coluna também foi acrescentada ao
  `install.mysql.utf8.sql`). Ficheiros de atualização de esquema usam
  `version_compare()` sobre o nome do ficheiro (sem `.sql`), por isso têm de
  se chamar exatamente como a versão do manifesto em que introduzem a
  alteração.

## Limitações conhecidas (por decisão, não bug)

- O URL SEF do município usa `?id=N` (parâmetro de query), não um segmento
  bonito `/horarios/abrantes` — implementar isso exigiria
  `getMunicipiosId()`/`getMunicipiosSegment()` no router, mais arriscado de
  acertar sem um Joomla real para testar cada alteração.
- O seletor "Circuito-pai" no formulário de Circuito lista todos os
  circuitos de topo de todos os municípios (não filtrado por JS ao
  município escolhido).
- Sem bloqueio de edição concorrente (checkout) — omitido para simplificar
  o esquema da base de dados.
- Sem suporte a categorias/UCM/tags/associação de idiomas.

## Créditos

Autor: **Leo Costa** — www.leocostadeveloper.com (@leocostadeveloper),
presente no manifesto (`horarios.xml`), na descrição da extensão e no
cabeçalho de todos os ficheiros.
