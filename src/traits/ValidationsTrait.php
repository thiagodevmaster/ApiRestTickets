<?php

namespace App\traits;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

trait ValidationsTrait
{
    public function validateCNPJ(string $cnpj): bool 
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
	
        // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;

        // Verifica se todos os digitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj))
            return false;	

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    public function validateUsername(string $username):bool 
    {
        // A expressão regular abaixo verifica se o nome de usuário:
        // [a-zA-Z0-9]  => Apenas letras (maiúsculas e minúsculas) e números
        // {5,}         => Com no mínimo 5 caracteres (sem limite superior)
        return preg_match('/^[a-zA-Z0-9]{5,}$/', $username);
    }

    public function respondWithError(string $message, int $statusCode): ResponseInterface
    {
        return new Response(
            $statusCode,
            ['Content-Type' => 'application/json'],
            json_encode(['error' => $message])
        );
    }

    public function respondWithSuccess(string $message): ResponseInterface
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['message' => $message])
        );
    }
}