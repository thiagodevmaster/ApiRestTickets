<?php

namespace App\Models\Cupom;

use DateTime;

class Cupom implements CupomInterface
{
    private ?int $id = null;

    public function __construct(
        private int $empresa_id,
        private string $codigo,
        private ?string $descricao,
        private string $tipo,
        private float $valor,
        private ?int $quantidade_maxima_uso = null,
        private int $quantidade_uso_atual = 0,
        private ?int $quantidade_maxima_por_usuario = null,
        private ?float $valor_minimo = null,
        private bool $reutilizavel = true,
        private ?DateTime $validade_inicio = null,
        private ?DateTime $validade_fim = null,
        private bool $ativo = true,
        private ?DateTime $criado_em = null,
        private ?DateTime $atualizado_em = null
    ) {
        $this->validade_inicio ??= new DateTime();
        $this->criado_em ??= new DateTime();
        $this->atualizado_em ??= new DateTime();
    }

    // Getters e Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getEmpresaId(): int
    {
        return $this->empresa_id;
    }

    public function setEmpresaId(int $empresa_id): void
    {
        $this->empresa_id = $empresa_id;
    }

    public function getCodigo(): string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): void
    {
        $this->codigo = $codigo;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function setValor(float $valor): void
    {
        $this->valor = $valor;
    }

    public function getQuantidadeMaximaUso(): ?int
    {
        return $this->quantidade_maxima_uso;
    }

    public function setQuantidadeMaximaUso(?int $qtd): void
    {
        $this->quantidade_maxima_uso = $qtd;
    }

    public function getQuantidadeUsoAtual(): int
    {
        return $this->quantidade_uso_atual;
    }

    public function setQuantidadeUsoAtual(int $qtd): void
    {
        $this->quantidade_uso_atual = $qtd;
    }

    public function incrementarUso(): void
    {
        $this->quantidade_uso_atual++;
    }

    public function getQuantidadeMaximaPorUsuario(): ?int
    {
        return $this->quantidade_maxima_por_usuario;
    }

    public function setQuantidadeMaximaPorUsuario(?int $qtd): void
    {
        $this->quantidade_maxima_por_usuario = $qtd;
    }

    public function getValorMinimo(): ?float
    {
        return $this->valor_minimo;
    }

    public function setValorMinimo(?float $valor): void
    {
        $this->valor_minimo = $valor;
    }

    public function isReutilizavel(): bool
    {
        return $this->reutilizavel;
    }

    public function setReutilizavel(bool $reutilizavel): void
    {
        $this->reutilizavel = $reutilizavel;
    }

    public function getValidadeInicio(): DateTime
    {
        return $this->validade_inicio;
    }

    public function setValidadeInicio(DateTime $data): void
    {
        $this->validade_inicio = $data;
    }

    public function getValidadeFim(): ?DateTime
    {
        return $this->validade_fim;
    }

    public function setValidadeFim(?DateTime $data): void
    {
        $this->validade_fim = $data;
    }

    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): void
    {
        $this->ativo = $ativo;
    }

    public function getCriadoEm(): DateTime
    {
        return $this->criado_em;
    }

    public function getAtualizadoEm(): DateTime
    {
        return $this->atualizado_em;
    }

    public function setAtualizadoEm(DateTime $data): void
    {
        $this->atualizado_em = $data;
    }

    // Método de utilidade
    public function estaValidoParaUso(?DateTime $agora = null): bool
    {
        $agora = $agora ?? new DateTime();

        return $this->ativo &&
            $this->validade_inicio <= $agora &&
            (!$this->validade_fim || $this->validade_fim >= $agora) &&
            (!$this->quantidade_maxima_uso || $this->quantidade_uso_atual < $this->quantidade_maxima_uso);
    }
}
