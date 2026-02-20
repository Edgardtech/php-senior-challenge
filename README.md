# 🚀 API de Produtos - Desafio Técnico Senior

Uma API RESTful completa, desenvolvida com **Laravel 11** e **PHP 8.2**, focada em performance, escalabilidade e boas práticas de engenharia de software. O projeto inclui implementação de cache, busca full-text resiliente e containerização completa.

## 🛠️ Tecnologias Utilizadas

- **Backend:** PHP 8.2, Laravel 11
- **Banco de Dados:** MariaDB 10.6 (Compatível com MySQL)
- **Cache:** Redis (para otimização de listas e sessões)
- **Busca Full-Text:** Laravel Scout (Driver Database para alta disponibilidade)
- **Infraestrutura:** Docker & Docker Compose
- **Ferramentas:** Composer, Laravel Sail, Tinker

## ✨ Funcionalidades Principais

### 1. CRUD Completo de Produtos
- **Criação:** Validação rigorosa de dados (SKU único, preço positivo, etc.).
- **Leitura:** Listagem paginada com filtros por status e categoria.
- **Atualização:** Edição parcial ou total com regras de negócio aplicadas.
- **Exclusão Lógica (Soft Delete):** Os registros não são removidos fisicamente, permitindo restauração futura.

### 2. Busca Full-Text Avançada
- Implementação de busca textual rápida utilizando **Laravel Scout**.
- **Arquitetura Resiliente:** Configurado com driver de banco de dados para garantir estabilidade e performance em ambientes com restrições de recursos, mantendo a compatibilidade para migração futura para ElasticSearch ou Algolia sem alteração de código.

### 3. Performance e Cache
- Integração com **Redis** para cache de consultas frequentes (ex: listagem de produtos), reduzindo a carga no banco de dados.

### 4. Ambiente Containerizado
- Orquestração completa via **Docker Compose**, garantindo consistência entre ambientes de desenvolvimento e produção.
- Isolamento de serviços: App PHP, Banco de Dados, Cache e Motor de Busca.

## 📦 Instalação e Execução

Pré-requisitos: Ter o **Docker Desktop** instalado e rodando.

1. **Clone o repositório:**
   ```bash
   git clone <url-do-seu-repositorio>
   cd php-senior-challenge