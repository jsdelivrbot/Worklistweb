<?php

class bd {

    private $banco;
    private $usuario;
    private $senha;
    private $host;
    private $conexao;
    private $porta;

    public function getBanco() {
        return $this->banco;
    }

    public function setBanco($banco) {
        $this->banco = $banco;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getHost() {
        return $this->host;
    }

    public function setHost($host) {
        $this->host = $host;
    }

    public function getConexao() {
        return $this->conexao;
    }

    public function setConexao($conexao) {
        $this->conexao = $conexao;
    }

    public function getPorta() {
        return $this->porta;
    }

    public function setPorta($porta) {
        $this->porta = $porta;
    }

    //metodo construtor da clase banco_dados, toda vez que esta classe for invocada, os atributos banco, host,senha, usuario
    function __construct() {
          $this->banco = 'dbname=worklistgo';
          $this->host = 'host=187.72.34.18';
          $this->porta = 'port=5432';
          $this->usuario = 'user=df';
          $this->senha = 'password=tidf123';
          $this->abreConexao(); 
    }

    //metodo de abertura de conexao com o postgres
    public function abreConexao() {

        if (!@($conexao = pg_connect("$this->host $this->banco $this->porta $this->usuario $this->senha"))) {
            print "Não foi possível estabelecer uma conexão com o banco de dados.";
        } 
    }

    //metodo 
    public function fechaConexao() {
        pg_close($this->conexao);
    }

}
