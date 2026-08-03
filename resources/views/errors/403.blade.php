@include('errors.minimal', [
    'code' => 403,
    'title' => 'Acesso não permitido',
    'message' => 'Você não tem permissão para acessar esta página, ou sua sessão pode ter expirado. Tente fazer login novamente.',
])
