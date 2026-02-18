<?php

namespace App\Support\Jwt;

use App\Models\User;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;

class JwtService
{
    /**
     * Gerar novo JWT Token para autenticação
     * 
     * @param string $email
     * @param string|null $password
     * @param bool $forceNew Se true, cria token sem validar senha
     * @return string
     * @throws \Exception
     */
    public function gerarToken(string $email, ?string $password = null, bool $forceNew = false): string
    {
        $user = User::where('email', $email)->with('role')->first();

        if (!$user) {
            throw new \Exception('Usuário não encontrado');
        }

        // Validar senha se não for renovação forçada
        if (!$forceNew && $password && !Hash::check($password, $user->password)) {
            throw new \Exception('Credenciais inválidas');
        }

        if (!$forceNew && !$password) {
            throw new \Exception('Senha é obrigatória');
        }

        $agora = time();
        $expiracao = $agora + config('jwt.ttl', 3600);

        $payload = [
            'sub' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'role' => $user->role?->nome ?? 'usuario',
            'iat' => $agora,
            'exp' => $expiracao,
            'nbf' => $agora,
        ];

        return JWT::encode(
            $payload,
            config('jwt.secret'),
            config('jwt.algorithm', 'HS256')
        );
    }

    /**
     * Validar e decodificar JWT Token
     * 
     * @param string $token
     * @return object Payload decodificado
     * @throws \Firebase\JWT\ExpiredException
     * @throws \Firebase\JWT\BeforeValidException
     * @throws \Firebase\JWT\SignatureInvalidException
     */
    public function validarToken(string $token): object
    {
        try {
            return JWT::decode(
                $token,
                new Key(config('jwt.secret'), config('jwt.algorithm', 'HS256'))
            );
        } catch (\Firebase\JWT\ExpiredException $e) {
            throw new \Exception('Token expirado');
        } catch (\Firebase\JWT\BeforeValidException $e) {
            throw new \Exception('Token ainda não é válido');
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            throw new \Exception('Assinatura do token inválida');
        } catch (\Exception $e) {
            throw new \Exception('Token inválido: ' . $e->getMessage());
        }
    }

    /**
     * Extrair payload do token sem validação
     * Usado para informações públicas (não seguro)
     * 
     * @param string $token
     * @return object|null
     */
    public function extrairPayload(string $token): ?object
    {
        try {
            // Decodificar sem validar assinatura
            $parts = explode('.', $token);
            if (count($parts) !== 3) {
                return null;
            }

            $payload = json_decode(
                base64_decode(strtr($parts[1], '-_', '+/'))
            );

            return $payload;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Verificar se token está próximo de expirar
     * 
     * @param string $token
     * @param int $minutos Margem em minutos antes da expiração
     * @return bool
     */
    public function estaProximoDeExpirar(string $token, int $minutos = 5): bool
    {
        try {
            $payload = $this->extrairPayload($token);
            if (!$payload || !isset($payload->exp)) {
                return true;
            }

            $tempoExpiracao = $payload->exp;
            $tempoAgora = time();
            $margemSegundos = $minutos * 60;

            return ($tempoExpiracao - $tempoAgora) < $margemSegundos;
        } catch (\Exception $e) {
            return true;
        }
    }
}

