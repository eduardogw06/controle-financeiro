# Controle Financeiro

Criar uma API para controle de saldo de pessoas identificadas pelo CPF.

## Funcionalidades
- Saldo: Deverá retornar o saldo de uma pessoa.
- Extrato: Deverá retornar as operações realizadas por uma pessoa.
- Débito: Essa operação deverá retirar do saldo de uma pessoa o valor informado na requisição.
- Crédito: Essa operação deverá adicionar ao saldo da pessoa o valor informado na requisição.
- Transferências: Essa operação deverá realizar o débito do saldo de uma pessoa e realizar um crédito na outra pessoa.

## Observações
- Não é permitido saldo negativo.
- Todas as operações devem ser registradas.
- Não se preocupe em validar o CPF.
- Utilizar git para controle de versão, fazendo vários commits explicando o que foi feito.
- Você poderá utilizar qualquer framework PHP.
- Usar um banco de dados relacional

## Especificações Utilizadas
- Linguagem Programação: PHP 7.2.25
- Banco de Dados: MySQL 14.14
- Framework: Zend Framework 3
- Bootstrap 5.0.0
- JQuery 3.6.0

## Como executar
- Instale o [composer](https://getcomposer.org/download/) em sua máquina
- Clone o projeto em uma pasta a seu critério
- Importe o banco de dados através do script presente no arquivo \db\controle-financeiro.sql
- Abrir o terminal na pasta do projeto e rodar o comando `composer install`
- Executar a instrução "Configuração da conexão com o banco de dados" logo abaixo
- Executar o comando `php -S localhost:8080 -t public public/index.php`
  - Mantenha o terminal aberto enquanto estiver utilizando o site. Ao fechar o terminal, o site é encerrado


## Configuração da conexão com o banco de dados
Acesse o diretório \config\autoload e crie o arquivo local.php. Nele será necessário incluir o seguinte trecho:
```
return [
    'db' => [
        'adapters' => [
            'db1' => [
                'driver' => 'Pdo_Mysql',
                'dsn' => "mysql:host=127.0.0.1;dbname=client_database",
                'username' => '[USUARIO_DO_BANCO]',
                'password' => '[SENHA_DO_BANCO]',
                'driver_options' => [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
                ],
            ],
        ],
    ],
];
```

## APIs

### /accounts
- /accounts/get-balance
  - Tem como função retornar o saldo da pessoa consultada
  - Parâmetro: 
    - `person`: ID da pessoa a ser consultada
- /accounts/get-statement
  - Tem como função retornar o extrato com todas as transações da pessoa consultada
  - Parâmetro: 
    - `person`: ID da pessoa a ser consultada

### /transactions
- /transactions/credit
  - Tem como função creditar saldo na conta de uma pessoa
  - Parâmetros: 
    - `person`: ID da pessoa a ser consultada
    - `value`: valor a ser creditado na conta
- /transactions/debit
  - Tem como função debitar saldo da conta de uma pessoa
  - Parâmetros: 
    - `person`: ID da pessoa a ser consultada
    - `value`: valor a ser creditado na conta
- /transactions/transfer
  - Tem como função transferir o saldo de uma pessoa para outra
  - Parâmetros: 
    - `from`: ID da pessoa a transferir o saldo
    - `to`: ID da pessoa a receber o saldo transferido
    - `value`: valor a ser transferido
