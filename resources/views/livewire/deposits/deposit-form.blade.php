<div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Make a Deposit</h2>

    <form wire:submit.prevent="initiateDeposit" class="space-y-4">
        <div>
            <x-label for="amount" :value="__('Amount')" />
            <x-input wire:model="amount" id="amount" class="block mt-1 w-full" type="number" step="0.01" min="1" max="10000" required autofocus />
            <x-error name="amount" />
        </div>

        <div class="flex items-center gap-4">
            <x-button type="submit">
                {{ __('Deposit') }}
            </x-button>

            <x-action-message class="me-3" on="deposit-initiated">
                {{ __('Redirecting to Stripe...') }}
            </x-action-message>
        </div>
    </form>
</div>
