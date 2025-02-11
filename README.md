# Bifrost

## 📌 Sobre o projeto
Este projeto é uma API para gerenciamento de solicitações de viagens. A API fornece endpoints para autenticação e manipulação de pedidos de viagem.

## 🚀 Subindo o ambiente
Para configurar e rodar o ambiente de desenvolvimento, siga os passos abaixo:

### 1️⃣ Pré-requisitos
- Docker e Docker Compose instalados
- Make instalado no sistema

### 2️⃣ Configuração e execução
Na raiz do projeto, execute o comando abaixo para copiar as variáveis de ambiente, subir os containers e rodar as migrations:

```sh
make build
```

Este comando executa os seguintes passos:
- Copia o arquivo `.env.example` para `.env` (caso não exista)
- Sobe os containers Docker
- Executa as migrations e seeds do banco de dados

Para parar os containers, utilize:

```sh
make down
```

Para reiniciar o ambiente, utilize:

```sh
make restart
```

## 🔑 Autenticação
A API utiliza autenticação baseada em token. Para acessar os endpoints protegidos, primeiro obtenha um token de autenticação.

### 🔹 Login
**Endpoint:**
```
POST /v1/auth/login
```

**Exemplo de requisição:**
```sh
curl -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "pablo.carvalho@bifrost.com", "password": "Valid123."}'
```

**Resposta esperada:**
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

O token retornado deve ser utilizado no cabeçalho `Authorization` para acessar os endpoints protegidos.

## ✈️ Gerenciamento de Solicitações de Viagem
Os endpoints abaixo requerem autenticação. Adicione o cabeçalho `Authorization: Bearer <TOKEN>` nas requisições.

### 🔹 Criar uma solicitação de viagem
**Endpoint:**
```
POST /v1/travel-requests/
```

## 🛠️ Comandos úteis
Caso precise rodar manualmente as migrations e seeds, utilize:
```sh
make migrate
```
