<?php

namespace App\Models\Empresa;

use App\traits\ValidationsTrait;
use DateTime;

class Empresa implements EmpresaInterface
{
    use ValidationsTrait;

    private ?int $id = null;
    private ?DateTime $lastAcess;
    private ?DateTime $created_at;
    private ?DateTime $updated_at;

    public function __construct(
        private string $razaoSocial,
        private string $nomeFantasia,
        private string $cnpj,
        private string $userName,
        private string $email,
        private string $password,
    ) {

    }

    /**
     * GETTERS && SETTERS
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getId(): ?int 
    {
        return $this->id;
    }

    public function getLastAcess(): DateTime
    {
        return $this->lastAcess;
    }

    public function setLastAcess(DateTime $lastAcess): self
    {
        $this->lastAcess = $lastAcess;

        return $this;
    }

    public function getCreated_at(): DateTime
    {
        return $this->created_at;
    }

    public function setCreated_at(DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdated_at(): DateTime
    {
        return $this->updated_at;
    }

    public function setUpdated_at(DateTime $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getRazaoSocial(): string
    {
        return $this->razaoSocial;
    }

    public function setRazaoSocial(string $razaoSocial): self
    {
        $this->razaoSocial = $razaoSocial;

        return $this;
    }

    public function getNomeFantasia(): string
    {
        return $this->nomeFantasia;
    }

    public function setNomeFantasia(string $nomeFantasia): self
    {
        $this->nomeFantasia = $nomeFantasia;

        return $this;
    }

    public function getCnpj(): array
    {
        $cnpjPuro = preg_replace("/\D/", "", $this->cnpj);
        $cnpjFormatado = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d2)/", "\$1.\$2.\$3/\$4-\$5", $cnpjPuro);

        return [
            "cnpjPuro" => $cnpjPuro,
            "cnpjformatado" => $cnpjFormatado
        ];
    }

    public function setCnpj(string $cnpj): self | bool 
    {
        if(!$this->validateCNPJ($cnpj)) {
            return false;
        }

        $this->cnpj = $cnpj;
        return $this;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self | bool
    {
        if(!$this->validateUsername($userName)) {
            return false;
        }
        
        $this->userName = $userName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self | bool
    {   
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }

        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * FINAL GETTER && SETTERS
     */

    public function criarCupon(): bool {
        return false;  
    }

    public function deletarCupon(): bool {
        return false;
    }

    public function listarCupons(): array {
        return [];
    }

    public function ativarCupon(): bool {
        return false;
    }

    public function desativarCupon(): bool {
        return false;
    }

    public function autenticarCupon(): bool {
        return false;
    }

        

        

        
}