<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\EfiBankService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class CheckoutController extends Controller
{
    public function redirect(string $type, EfiBankService $efiBank): RedirectResponse
    {
        $planType = $type === 'annual' ? 'annual' : 'monthly';

        try {
            $url = $efiBank->createCheckoutLink(auth()->user(), $planType);
        } catch (RuntimeException $e) {
            Log::error('checkout.efibank_error', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
                'plan'    => $planType,
            ]);

            $msg = str_contains($e->getMessage(), 'não configurado')
                ? 'O sistema de pagamentos ainda está em configuração. Por favor, aguarde ou entre em contato com o suporte.'
                : 'Não foi possível gerar o link de pagamento. Tente novamente em instantes ou contate o suporte.';

            return redirect()->route('dashboard.plan')->with('erro', $msg);
        }

        return redirect()->away($url);
    }
}
