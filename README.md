# Curitiba Comedy Club

Base de projeto WordPress do Curitiba Comedy Club com arquitetura orientada a:

- design system proprietario
- shortcodes reutilizaveis
- plugins customizados com responsabilidades separadas

## Visao Geral

Este repositorio separa a camada de agenda/eventos da camada institucional/UI:

- `ccc-eventos-standapp`: plugin de programacao (API Standapp + cards + filtros)
- `ccc-ui-kit`: plugin institucional (design system + blocos de pagina via shortcode)

Objetivo: reduzir dependencia de montagem manual no Elementor e evoluir para uma base escalavel e orientada a codigo.

## Estrutura Atual

```text
curitiba-comedy-club/
	README.md
	docs/
		architecture.md
		design-system.md
		page-map.md
	ccc-eventos-standapp/
		ccc-eventos-standapp.php
	ccc-ui-kit/
		ccc-ui-kit.php
		includes/
			class-ccc-ui-kit.php
			class-ccc-ui-assets.php
			class-ccc-ui-shortcodes.php
			shortcodes/
				class-ccc-ui-shortcode-page-hero.php
				class-ccc-ui-shortcode-cta-ingressos.php
				class-ccc-ui-shortcode-contact-section.php
		assets/
			css/
				ccc-ui-tokens.css
				ccc-ui-base.css
				ccc-ui-components.css
			js/
				ccc-ui-kit.js
```

## Metodo de Implementacao no WordPress

### 1) Tema e construtor

- **Astra** como base leve de tema
- **Elementor** para macro-layout (secoes e colunas)
- Conteudo recorrente e visual padronizado via shortcodes dos plugins

### 2) Separacao de responsabilidades

- **Agenda/Programacao** fica no `ccc-eventos-standapp`
- **Institucional/UI** fica no `ccc-ui-kit`

Esse desacoplamento evita acoplamento de regra de negocio de eventos com blocos institucionais, facilitando manutencao.

### 3) Fluxo de montagem de paginas

1. Criar/editar pagina no WordPress.
2. Usar Elementor apenas para estrutura macro.
3. Inserir shortcodes nos blocos de conteudo.
4. Manter identidade visual via assets do `ccc-ui-kit`.

### 4) Enqueue e assets

No `ccc-ui-kit`, os assets sao enfileirados via `wp_enqueue_scripts` com handles prefixados:

- `ccc-ui-tokens`
- `ccc-ui-base`
- `ccc-ui-components`
- `ccc-ui-kit` (JS)

Todos os estilos seguem prefixo `ccc-ui-` para minimizar conflitos com Astra/Elementor.

## Shortcodes Disponiveis (v0.1.0)

### Plugin `ccc-ui-kit`

- `[ccc_page_hero]`
- `[ccc_cta_ingressos]`
- `[ccc_contact_section]`

#### Exemplos rapidos

```text
[ccc_page_hero title="Curitiba Comedy Club" subtitle="Noites premium de stand-up" heading_level="h1"]

[ccc_cta_ingressos title="Garanta seu ingresso" button_url="/programacao/"]

[ccc_contact_section
	address="Rua Exemplo, 123 - Curitiba/PR"
	whatsapp="(41) 99999-9999"
	instagram="@curitibacomedy"]
```

### Plugin `ccc-eventos-standapp`

- `[eventos_standapp]`

## Instalacao (ambiente WordPress)

1. Copiar as pastas `ccc-eventos-standapp` e `ccc-ui-kit` para `wp-content/plugins/` do site.
2. Ativar ambos os plugins no painel WordPress.
3. Criar paginas institucionais e inserir shortcodes conforme `docs/page-map.md`.

## Compatibilidade

- WordPress com plugins custom em PHP nativo
- Sem Composer obrigatorio
- Compatibilidade com Astra e Elementor preservada por escopo de classes e responsabilidades

## Convencoes do Projeto

- Prefixo `ccc-ui-` para classes, handles, data attributes e componentes do UI Kit
- Prefixo `ccc-standapp-` para elementos do plugin de agenda
- Evitar frameworks pesados e dependencias desnecessarias

## Documentacao Tecnica

- Arquitetura: `docs/architecture.md`
- Design system: `docs/design-system.md`
- Mapa de paginas: `docs/page-map.md`

## Proximos Passos

1. Adicionar shortcodes institucionais da segunda leva (`ccc_about_block`, `ccc_section_heading`).
2. Refinar tipografia e contraste visual em ambiente real.
3. Evoluir fluxo Git com branches por feature (`feat/*`) e PRs.
