@include('errors.minimal', [
    'code' => 429,
    'title' => 'Muitas tentativas',
    'message' => 'Você fez várias tentativas em pouco tempo. Aguarde alguns instantes antes de tentar novamente.',
])
