<?php

namespace App\Models\Empresa;

interface EmpresaInterface
{
    public function criarCupon(): bool;
    public function deletarCupon(): bool;
    public function listarCupons(): array;
    public function ativarCupon(): bool;
    public function desativarCupon(): bool;
    public function autenticarCupon(): bool;


    public function getId(): ?int;
    public function getRazaoSocial(): string;
    public function getNomeFantasia(): string;
    public function getCnpj(): array;
    public function getEmail(): string;
    public function getUserName(): string;
    public function getPassword(): string;

}