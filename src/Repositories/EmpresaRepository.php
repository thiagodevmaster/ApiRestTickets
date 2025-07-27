<?php

namespace App\Repositories;

use App\Models\Empresa\Empresa;
use App\Models\Empresa\EmpresaInterface;
use Exception;
use PDO;
use PDOStatement;

class EmpresaRepository
{
    protected string $table = "empresas";

    public function __construct(private PDO $pdo)
    {}

    public function save(EmpresaInterface $empresa): bool
    {
        $id = $empresa->getId();
        
        if (empty($id)) {
            return $this->createEmpresa($empresa);
        }

        return $this->editEmpresa($empresa);
    }

    protected function createEmpresa(EmpresaInterface $empresa): bool
    {
        $sql = "INSERT INTO $this->table (razao_social, nome_fantasia, cnpj, email, username, password) 
            VALUES (:razao_social, :nome_fantasia, :cnpj, :email, :username, :password);";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":razao_social", $empresa->getRazaoSocial(), PDO::PARAM_STR);
        $stmt->bindValue(":nome_fantasia", $empresa->getNomeFantasia(), PDO::PARAM_STR);
        $stmt->bindValue(":cnpj", $empresa->getCnpj()['cnpjPuro'], PDO::PARAM_STR);
        $stmt->bindValue(":email", $empresa->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(":username", $empresa->getUserName(), PDO::PARAM_STR);
        $stmt->bindValue(":password", $empresa->getPassword(), PDO::PARAM_STR);

        return $stmt->execute();
    }

    protected function editEmpresa(EmpresaInterface $empresa): bool
    {
        $sql = "UPDATE $this->table SET 
                    razao_social = :razao_social, 
                    nome_fantasia = :nome_fantasia, 
                    cnpj = :cnpj, 
                    email = :email, 
                    username = :username, 
                    password = :password 
                WHERE id = :id;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":razao_social", $empresa->getRazaoSocial(), PDO::PARAM_STR);
        $stmt->bindValue(":nome_fantasia", $empresa->getNomeFantasia(), PDO::PARAM_STR);
        $stmt->bindValue(":cnpj", $empresa->getCnpj()['cnpjPuro'], PDO::PARAM_STR);
        $stmt->bindValue(":email", $empresa->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(":username", $empresa->getUserName(), PDO::PARAM_STR);
        $stmt->bindValue(":password", $empresa->getPassword(), PDO::PARAM_STR);
        $stmt->bindValue(":id", $empresa->getId(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function existsByCnpj(string $cnpj): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE cnpj = :cnpj";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":cnpj", $cnpj, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }
   
    public function existsByEmail(string $email): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public function hydrateEmpresa(PDOStatement $stmt): array
    {
        $empresaDataList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $empresaList = [];

        foreach($empresaDataList as $empresa) {
            $item = new Empresa(
                $empresa['razao_social'],
                $empresa['nome_fantasia'],
                $empresa['cnpj'],
                $empresa['email'],
                $empresa['username'],
                $empresa['password']
            );

            $item->setId($empresa['id']);

            $empresaList[] = $item;
        }

        return $empresaList;
    }

    public function getEmpresaByUsername(string $username): ?EmpresaInterface 
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':username', $username);

        if(!$stmt->execute()){
            throw new Exception("Não encontrado empresa com este username");
        }

        $empresaData = self::hydrateEmpresa($stmt);

        if(empty($empresaData)){
            throw new Exception("Não foi possivel tratar os dados desta empresa");
        }
        
        return $empresaData[0];
    }

}