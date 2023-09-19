# CRUD API

API para criar uma CRUD de motorista e suas validações, utilizando mensageria.


## Máquina virtual (Docker)
## Pré-requisitos
Para execução, é necessário [Docker](https://docs.docker.com/) e [docker-compose](https://docs.docker.com/compose/install/), para subir aplicação.

## Configuração
O projecto está configurado por padrão para rodar com docker

## Execução
execute o comando na pasta local
```bash
docker-compose up -d
```

## Commando PHPUnit
Com docker o comando tem que ser realizado dentro do container do php, acesse o container com comando
```bash
docker exec -it php sh
```
Ou pode executar o comando sem precisar acessar o container com comando:
```bash
docker-compose exec php bin/phpunit
```

## Documentação

*Acesso a documentação*
```bash
NelmioApiDoc http://localhost:8080/api/doc
```

## Desenvolvido

Desenvolvido em 10 de Fevereiro de 2023.

Desenvolvedor: Pedro Érico.
Email: pedroerico.desenvolvedor@gmail.com
