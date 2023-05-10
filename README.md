# Setup Docker

### Passo a passo

Clone o Repositório

```sh
git clone https://github.com/jfbritto/superscraping.git
```

Suba os containers

```sh
docker-compose up -d
```

Dê permissão

```sh
sudo chmod 777 -R superscraping/
```

Entre no diretório

```sh
cd superscraping/
```

Acesse o container

```sh
docker-compose exec php bash
```

Instale as dependências

```sh
composer update
```

Acesse o projeto
[http://localhost:8989](http://localhost:8989)
