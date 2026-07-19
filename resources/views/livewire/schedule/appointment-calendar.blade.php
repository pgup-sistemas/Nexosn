<div class="px-4 pb-6 pt-4 border-t border-gray-100">

    <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
        <svg data-lucide="calendar" class="w-4 h-4" style="color: var(--card-primary);"></svg>
        Agendar atendimento
    </h2>

    @if ($step === 'success')

        <div class="flex flex-col items-center gap-3 py-6 text-center">
            <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #e6f4ea;">
                <svg data-lucide="check-circle" class="w-6 h-6 text-green-600"></svg>
            </div>
            <p class="text-[14px] font-semibold text-gray-900">Solicitação enviada!</p>
            <p class="text-[13px] text-gray-500">Aguarde a confirmação por e-mail.</p>
            <button wire:click="$set('step', 'calendar')"
                    class="mt-2 px-4 py-2 rounded-[10px] text-[12px] font-medium border border-gray-200 text-gray-600 hover:bg-gray-50">
                Fazer outro agendamento
            </button>
        </div>

    @elseif ($step === 'form')

        <div class="space-y-3">
            <div class="flex items-center gap-2 mb-3">
                <button wire:click="back" class="text-gray-400 hover:text-gray-600">
                    <svg data-lucide="arrow-left" class="w-4 h-4"></svg>
                </button>
                <p class="text-[13px] font-medium text-gray-700">
                    {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }} às {{ $selectedTime }}
                </p>
            </div>

            @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-[10px] p-3 text-[12px] text-red-700">
                {{ $errors->first() }}
            </div>
            @endif

            <div class="space-y-1">
                <label class="block text-[12px] font-medium text-gray-700">Seu nome *</label>
                <input wire:model="visitor_name" type="text" placeholder="Nome completo"
                       class="w-full border border-gray-200 rounded-[10px] px-3 py-2.5 text-[13px] focus:outline-none focus:ring-2"
                       style="--tw-ring-color: var(--card-primary);">
                @error('visitor_name') <p class="text-[11px] text-red-500 mt-0.5">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-[12px] font-medium text-gray-700">E-mail *</label>
                <input wire:model="visitor_email" type="email" placeholder="seu@email.com"
                       class="w-full border border-gray-200 rounded-[10px] px-3 py-2.5 text-[13px] focus:outline-none focus:ring-2"
                       style="--tw-ring-color: var(--card-primary);">
                @error('visitor_email') <p class="text-[11px] text-red-500 mt-0.5">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-[12px] font-medium text-gray-700">Telefone</label>
                <input wire:model="visitor_phone" type="text" placeholder="(69) 99999-9999"
                       class="w-full border border-gray-200 rounded-[10px] px-3 py-2.5 text-[13px] focus:outline-none focus:ring-2"
                       style="--tw-ring-color: var(--card-primary);">
            </div>

            <div class="space-y-1">
                <label class="block text-[12px] font-medium text-gray-700">Observações</label>
                <textarea wire:model="notes" rows="2" placeholder="Descreva brevemente o motivo..."
                          class="w-full border border-gray-200 rounded-[10px] px-3 py-2.5 text-[13px] focus:outline-none focus:ring-2 resize-none"
                          style="--tw-ring-color: var(--card-primary);"></textarea>
            </div>

            <button wire:click="submit"
                    wire:loading.attr="disabled"
                    class="w-full py-3 rounded-[10px] text-white text-[13px] font-medium flex items-center justify-center gap-2"
                    style="background-color: var(--card-button);">
                <span wire:loading.remove>Solicitar agendamento</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg data-lucide="loader-2" class="w-4 h-4 animate-spin"></svg>
                    Enviando...
                </span>
            </button>
        </div>

    @else

        {{-- Calendário --}}
        <div>
            {{-- Header do mês --}}
            <div class="flex items-center justify-between mb-4">
                <button wire:click="prevMonth"
                        class="p-1.5 rounded-[8px] hover:bg-gray-100 transition text-gray-500">
                    <svg data-lucide="chevron-left" class="w-4 h-4"></svg>
                </button>
                <p class="text-[13px] font-semibold text-gray-700 capitalize">{{ $monthLabel }}</p>
                <button wire:click="nextMonth"
                        class="p-1.5 rounded-[8px] hover:bg-gray-100 transition text-gray-500">
                    <svg data-lucide="chevron-right" class="w-4 h-4"></svg>
                </button>
            </div>

            {{-- Dias da semana --}}
            <div class="grid grid-cols-7 mb-1">
                @foreach (['D','S','T','Q','Q','S','S'] as $d)
                <div class="text-center text-[11px] font-medium text-gray-400 py-1">{{ $d }}</div>
                @endforeach
            </div>

            {{-- Dias --}}
            <div class="grid grid-cols-7 gap-0.5">
                @foreach ($days as $day)
                    @if ($day === null)
                        <div></div>
                    @elseif ($day['available'])
                        <button wire:click="selectDate('{{ $day['date'] }}')"
                                class="aspect-square flex items-center justify-center rounded-[8px] text-[13px] font-medium transition
                                       {{ $selectedDate === $day['date'] ? 'text-white' : 'text-gray-800 hover:opacity-80' }}"
                                style="{{ $selectedDate === $day['date'] ? 'background-color: var(--card-primary);' : 'background-color: color-mix(in srgb, var(--card-primary) 12%, white);' }}">
                            {{ $day['day'] }}
                        </button>
                    @else
                        <div class="aspect-square flex items-center justify-center rounded-[8px] text-[13px] text-gray-300">
                            {{ $day['day'] }}
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Slots do dia selecionado --}}
            @if ($selectedDate)
            <div class="mt-4">
                <p class="text-[12px] font-medium text-gray-600 mb-2">
                    Horários — {{ \Carbon\Carbon::parse($selectedDate)->format('d/m') }}
                </p>

                @if (count($availableSlots) > 0)
                <div class="grid grid-cols-3 gap-2">
                    @foreach ($availableSlots as $slot)
                    <button wire:click="selectTime('{{ $slot }}')"
                            class="py-2 rounded-[8px] text-[12px] font-medium border transition hover:opacity-90"
                            style="border-color: var(--card-primary); color: var(--card-primary); background: white;">
                        {{ $slot }}
                    </button>
                    @endforeach
                </div>
                @else
                <p class="text-[12px] text-gray-400 text-center py-3">Nenhum horário disponível neste dia.</p>
                @endif
            </div>
            @endif

        </div>

    @endif

</div>
