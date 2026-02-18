<?php

return [
    'secret' => env('JWT_SECRET', 'sua-chave-secreta-super-segura-aqui'),
    'ttl' => (int) env('JWT_TTL', 3600), // 1 hora
    'refresh_ttl' => (int) env('JWT_REFRESH_TTL', 604800), // 7 dias
    'algorithm' => env('JWT_ALGORITHM', 'HS256'),
];
