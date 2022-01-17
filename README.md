# brasil-tecpar-teste

Desenvolvimento de uma aplicação que gera um Hash via CLI (Command Line Interface)

##Desafio (O teste é formado por 3 partes)
1. A criação de uma rota que encontra um Hash, de certo formato, para uma certa string forneccida como input.
2. A criação de um comando que consulta a rota criada e armazena os resultados na base de dados
3. Criação de rota que retorne os resultados que foram gravados.

## Tecnologias
- SQLite
- PHP / Symfony 5

## Dependencias
- PHP 8.1.1
- Composer

## Instalação

Clone o repositório abaixo

```bash
$ git clone https://github.com/osmaircoelho/brasil-tecpar-teste
```

Acesso o diretório

```bash
$ cd brasil-tecpar-teste
```
Instale as dependências com o composer

```bash
$ composer install
```

Execute o comando abaixo para subir o servidor web embutino no PHP 8

```bash
$ php -S localhost:8001 -t public  
```
Consulte a URL http://localhost:8001/ 

Irá aparecer uma tela de boas vindas do Symfony "Your application is now ready and you can start working on it."

### Acessos
- Esta disponível duas URLs para acesso
  Consultar todos os Hashes disponíveis `http://127.0.0.1:8001/hash`
- Para gerar o hash navegue até o  diretório raiz do projeto e execute o comando CLI`php bin/console avato:test "Avato" --requests=10 `

### Parametro query string para filtro
- Ainda é possível consultar o numero de tentativas utilizando para gerar a Key  com o parâmetro query string `http://localhost:8001/hash?number-attempts=50000`

### Comando para geração do hash CLI (Command Line Interface)

- No terminal CLI e na pasta raiz do projeto execute o comando
`php bin/conole avato:test "Avato" --requests=10`
  
Uma barra irá mostrar o progresso onde 
1. `Avato` é a string inicial que pode gerar o hash
2. `--requests=10` o número de requisições que devem ser feitas em sequência

`10/10 [============================] 100%`

Ao atingir o número de requests uma tabela mostrará os resultados.

```bash

+---------------------+------------+----------------------------------+------------------+----------------------------------+------------+
| Batch               | Num. bloco | String entrada                   | Chave encontrada | Hash gerado                      | Tentativas |
+---------------------+------------+----------------------------------+------------------+----------------------------------+------------+
| 2022-15-01 23:21:14 | 1          | Avato                            | Tyrl8eKN         | 0000c622d16f39faabfd776818d442aa | 40238      |
| 2022-15-01 23:21:16 | 2          | 0000c622d16f39faabfd776818d442aa | iT2uNhcj         | 000056e2c9e7587eea9ec5a2d9e80909 | 36492      |
| 2022-15-01 23:21:17 | 3          | 000056e2c9e7587eea9ec5a2d9e80909 | UHWE7n3N         | 00007a627f1db03890a8ec204c8b0e12 | 93606      |
| 2022-15-01 23:21:18 | 4          | 00007a627f1db03890a8ec204c8b0e12 | sxLFjirA         | 00006f7a5800820a50e5235fba47571a | 4068       |
| 2022-15-01 23:21:19 | 5          | 00006f7a5800820a50e5235fba47571a | 8EyoUCYJ         | 00001110fc155dd817be1ec8701cf734 | 39914      |
| 2022-15-01 23:21:21 | 6          | 00001110fc155dd817be1ec8701cf734 | XDP5Yg3r         | 00000b7208f0bf412238bcb150b0ab3b | 50434      |
| 2022-15-01 23:21:22 | 7          | 00000b7208f0bf412238bcb150b0ab3b | bFXj6NZ9         | 000029a09a47bbd954960de7ce2cc60d | 251739     |
| 2022-15-01 23:21:23 | 8          | 000029a09a47bbd954960de7ce2cc60d | wMAFYHQR         | 000087d7c692ff5619125d24d6da8c03 | 7681       |
| 2022-15-01 23:21:25 | 9          | 000087d7c692ff5619125d24d6da8c03 | G1fMVom5         | 0000b08088042d329dc638f0cb6fc200 | 23758      |
| 2022-15-01 23:21:26 | 10         | 0000b08088042d329dc638f0cb6fc200 | D02u8HcR         | 00006f02267795089830b1f2cdc7a04f | 1074       |
+---------------------+------------+----------------------------------+------------------+----------------------------------+------------+
```


