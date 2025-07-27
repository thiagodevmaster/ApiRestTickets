<?php

namespace App\Controller;

use App\Models\Empresa\Empresa;
use App\Repositories\EmpresaRepository;
use App\traits\ValidationsTrait;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class AuthController
{
    use ValidationsTrait;

    public function __construct(private EmpresaRepository $repository)
    {}

    public function register(ServerRequestInterface $request): ResponseInterface
    {
        $params = json_decode($request->getBody()->getContents(), true);

        $requiredFields = ['razao_social', 'nome_fantasia', 'cnpj', 'email', 'username', 'password'];
        foreach ($requiredFields as $field) {
            if (empty($params[$field])) {
                return $this->respondWithError("Campo '$field' é obrigatório.", 400);
            }
        }

        $razaoSocial = filter_var($params['razao_social']);
        $nomeFantasia = filter_var($params['nome_fantasia']);
        $cnpj = filter_var($params['cnpj']);
        $email = filter_var($params['email'], FILTER_VALIDATE_EMAIL);
        $username = filter_var($params['username']);
        $password = $params['password'];

        if (!$email) {
            return $this->respondWithError("E-mail inválido.", 400);
        }

        if (!$this->validateCNPJ($cnpj)) {
            return $this->respondWithError("CNPJ inválido.", 400);
        }

        $cnpjPuro = preg_replace("/\D/", "", $cnpj);
        if ($this->repository->existsByCnpj($cnpjPuro)) {
            return $this->respondWithError("Já existe uma empresa com este CNPJ.", 409);
        }
        
        if ($this->repository->existsByEmail($email)) {
            return $this->respondWithError("Já existe uma empresa com este email.", 409);
        }

        $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

        $empresa = new Empresa(
            $razaoSocial,
            $nomeFantasia,
            $cnpj,
            $email,
            $username,
            $hashedPassword
        );

        try {
            $this->repository->save($empresa);
        } catch (Exception $e) {
            return $this->respondWithError("Erro ao salvar empresa: " . $e->getMessage(), 500);
        }

        return $this->respondWithSuccess("Empresa registrada com sucesso.");
    }

    public function Login(ServerRequestInterface $request): ResponseInterface
    {
        //TODO
    }

    
}
