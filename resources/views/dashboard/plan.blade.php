<x-app-layout>

@php
    $user    = auth()->user();
    $isPro   = $user->isPro();
    $inTrial = $user->isOnTrial();
    $planOk  = \App\Models\AppSetting::get('efi_plan_id_monthly') || config('services.efibank.plan_id_monthly');
@endphp

<div>

    {{-- Plano atual --}}
    <div class="mx-4 mb-4 bg-[#EBEBEA] rounded-[12px] p-4">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-[11px] text-[#888] mb-0.5">Plano atual</p>
                <p class="text-[16px] font-semibold text-gray-900">
                    @if($isPro) Pro @elseif($inTrial) Trial Pro @else Free @endif
                </p>
            </div>
            @if($isPro || $inTrial)
                <span class="bg-[#FCBF49] text-[#003049] text-[10px] font-semibold px-3 py-1 rounded-full">PRO</span>
            @endif
        </div>

        @if($inTrial)
        <div class="flex items-center gap-2 bg-yellow-100 rounded-[8px] p-2.5 text-[12px] text-yellow-800">
            <i data-lucide="clock" class="w-4 h-4 flex-shrink-0"></i>
            Trial encerra {{ $user->trial_ends_at->diffForHumans() }}
        </div>
        @endif

        @if($isPro && $user->plan_expires_at)
        <p class="text-[12px] text-[#666] mt-2">
            Próxima renovação: {{ $user->plan_expires_at->format('d/m/Y') }}
        </p>
        @endif
    </div>

    @if (request('status') === 'pago')
    <div class="mx-4 mb-4 flex items-center gap-2 bg-green-50 border border-green-200 rounded-[10px] p-3 text-[13px] text-green-800">
        <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
        Pagamento recebido! Seu plano será ativado em instantes. Atualize a página se necessário.
    </div>
    @endif

    @if (session('aviso_upgrade'))
    <div class="mx-4 mb-4 flex items-center gap-2 bg-yellow-50 border border-yellow-200 rounded-[10px] p-3 text-[13px] text-yellow-800">
        <i data-lucide="lock" class="w-4 h-4 flex-shrink-0"></i>
        {{ session('aviso_upgrade') }}
    </div>
    @endif

    @if (session('erro'))
    <div class="mx-4 mb-4 flex items-center gap-2 bg-red-50 border border-red-200 rounded-[10px] p-3 text-[13px] text-red-800">
        <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
        {{ session('erro') }}
    </div>
    @endif

    <div class="px-4 flex flex-col gap-3">

        {{-- Plano Free --}}
        <div class="border {{ !$isPro && !$inTrial ? 'border-[#003049]' : 'border-gray-200' }} rounded-[12px] p-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-[14px] font-semibold text-gray-900">Free</p>
                    <p class="text-[20px] font-medium text-gray-900">R$ 0 <span class="text-[12px] text-[#888]">/mês</span></p>
                </div>
                @if(!$isPro && !$inTrial)
                <span class="text-[10px] font-semibold text-[#003049] border border-[#003049] px-2 py-0.5 rounded-full">Plano atual</span>
                @endif
            </div>
            <ul class="flex flex-col gap-1.5 text-[12px] text-[#666]">
                <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i>1 cartão digital</li>
                <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i>Até 5 links</li>
                <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i>Até 3 fotos na galeria</li>
                <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i>PIX e QR Code</li>
                <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i>Até 3 serviços com PIX dinâmico</li>
                <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i>Formulário de contato</li>
                <li class="flex items-center gap-2"><i data-lucide="x" class="w-3.5 h-3.5 text-gray-300"></i>Cores personalizadas</li>
                <li class="flex items-center gap-2"><i data-lucide="x" class="w-3.5 h-3.5 text-gray-300"></i>Agenda</li>
                <li class="flex items-center gap-2 text-gray-400"><i data-lucide="credit-card" class="w-3.5 h-3.5"></i>Marca d'água obrigatória</li>
            </ul>
        </div>

        {{-- Plano Pro --}}
        <div class="border-2 border-[#FCBF49] rounded-[12px] p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-[#FCBF49] text-[#003049] text-[10px] font-semibold px-3 py-1 rounded-bl-[10px]">
                RECOMENDADO
            </div>
            <div class="mb-3 mt-2">
                <p class="text-[14px] font-semibold text-gray-900">Pro</p>
                <p class="text-[20px] font-medium text-gray-900">R$ 19,90 <span class="text-[12px] text-[#888]">/mês</span></p>
                <p class="text-[11px]" style="color: #F77F00;">ou R$ 179,90/ano — economize 25%</p>
            </div>
            <ul class="flex flex-col gap-1.5 text-[12px] text-[#666] mb-4">
                <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i>Tudo do Free</li>
                <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i>Links ilimitados</li>
                <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i>30 fotos na galeria</li>
                <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i>Até 20 serviços com PIX dinâmico</li>
                <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i>Cores de marca personalizadas</li>
                <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i>Agenda com confirmação manual</li>
                <li class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i>Sem marca d'água</li>
            </ul>

            @if(!$isPro)
                @if($planOk)
                <a href="{{ route('dashboard.checkout', 'monthly') }}"
                   class="flex items-center justify-center gap-2 w-full py-3 rounded-[10px] text-white text-[13px] font-medium"
                   style="background-color: #D62828;">
                    <i data-lucide="star" class="w-4 h-4"></i>
                    Assinar Pro — R$ 19,90/mês
                </a>
                <a href="{{ route('dashboard.checkout', 'annual') }}"
                   class="flex items-center justify-center gap-2 w-full mt-2 py-2.5 rounded-[10px] border text-[12px] font-medium"
                   style="border-color: #003049; color: #003049;">
                    Assinar anual — R$ 179,90/ano
                </a>
                @else
                <div class="flex items-center gap-2 bg-yellow-50 border border-yellow-200 rounded-[10px] p-3 text-[12px] text-yellow-800">
                    <i data-lucide="clock" class="w-4 h-4 flex-shrink-0"></i>
                    Pagamento em configuração — em breve disponível. Fale com o suporte se precisar ativar manualmente.
                </div>
                @endif
            @else
            <div class="flex items-center gap-2 bg-green-50 rounded-[10px] p-3 text-[13px] text-green-800">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                Você já tem o plano Pro ativo!
            </div>
            @endif
        </div>

    </div>
</div>

</x-app-layout>
