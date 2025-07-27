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

}