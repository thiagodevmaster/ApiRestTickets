<?php

namespace App\Controller;

use App\Models\Empresa\Empresa;
use App\Repositories\EmpresaRepository;
use App\Services\JwtServices;
use App\traits\ValidationsTrait;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;


class AuthController
{
    use ValidationsTrait;

    public function __construct(private EmpresaRepository $repository, private JwtServices $jwt)
    {}

    public function register(ServerRequestInterface $request): ResponseInterface
    {
        $params = json_decode($request->getBody()->getContents(), true);
        if(empty($params) || !$params) {
            return $this->respondWithError("Nenhum dado recebido", 500);
        }

        $requiredFields = ['razao_social', 'nome_fantasia', 'cnpj', 'email', 'username', 'password'];
        if ($error = $this->validaCampos($requiredFields, $params)) {
            return $error;
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

    public function login(ServerRequestInterface $request): ResponseInterface
    {
        $params = json_decode($request->getBody()->getContents(), true);
        if(empty($params) || !$params) {
            return $this->respondWithError("Nenhum dado recebido", 500);
        }

        $requiredFields = ['username', 'password'];
        if ($error = $this->validaCampos($requiredFields, $params)) {
            return $error;
        }

        $username = filter_var($params['username']);
        $password = filter_var($params['password']);

        try{
            $empresa = $this->repository->getEmpresaByUsername($username);
        }catch(Exception $e) {
            return $this->respondWithError("username ou password incorretos.", 500);
        }
        
        if(!password_verify($password, $empresa->getPassword())){
            return $this->respondWithError("username ou password incorretos.", 500);
        }

        $token = $this->jwt->generateJwt([
            'sub' => $empresa->getId(),
            'username' => $empresa->getUserName()
        ]);

        return $this->respondWithSuccess("Login realizado com sucesso.", ['token' => $token]);
    }

    
}
