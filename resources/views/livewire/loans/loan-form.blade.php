<div>
    <form wire:submit="submit" class="space-y-6">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <!-- Loan Amount -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700">Loan Amount *</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input type="number"
                           wire:model="amount"
                           id="amount"
                           class="block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('amount') border-red-300 @enderror"
                           placeholder="0.00"
                           min="100"
                           max="100000"
                           step="0.01">
                </div>
                @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Monthly Income -->
            <div>
                <label for="income" class="block text-sm font-medium text-gray-700">Monthly Income *</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input type="number"
                           wire:model="income"
                           id="income"
                           class="block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('income') border-red-300 @enderror"
                           placeholder="0.00"
                           min="1000"
                           step="0.01">
                </div>
                @error('income') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Loan Purpose -->
        <div>
            <label for="purpose" class="block text-sm font-medium text-gray-700">Loan Purpose *</label>
            <div class="mt-1">
                <input type="text"
                       wire:model="purpose"
                       id="purpose"
                       class="block w-full sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('purpose') border-red-300 @enderror"
                       placeholder="e.g., Home improvement, Car purchase, Education">
            </div>
            @error('purpose') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <!-- Duration -->
            <div>
                <label for="duration_months" class="block text-sm font-medium text-gray-700">Duration (Months) *</label>
                <div class="mt-1">
                    <select wire:model="duration_months"
                            id="duration_months"
                            class="block w-full sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('duration_months') border-red-300 @enderror">
                        <option value="">Select duration</option>
                        @for($i = 6; $i <= 60; $i += 6)
                            <option value="{{ $i }}">{{ $i }} months</option>
                        @endfor
                    </select>
                </div>
                @error('duration_months') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Interest Rate -->
            <div>
                <label for="interest_rate" class="block text-sm font-medium text-gray-700">Interest Rate (%)</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="number"
                           wire:model="interest_rate"
                           id="interest_rate"
                           class="block w-full pr-12 sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('interest_rate') border-red-300 @enderror"
                           placeholder="5.0"
                           min="0"
                           max="20"
                           step="0.1">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">%</span>
                    </div>
                </div>
                @error('interest_rate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Monthly Payment Calculation -->
        @if($monthlyPayment > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
            <h3 class="text-sm font-medium text-blue-800">Estimated Monthly Payment</h3>
            <p class="text-2xl font-bold text-blue-900">${{ number_format($monthlyPayment, 2) }}</p>
            <p class="text-sm text-blue-700">Based on your loan amount and selected duration</p>
        </div>
        @endif

        <!-- Submit Button -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Submit Application
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('loan-submitted', (event) => {
                alert(event[0]);
                window.location.href = '{{ route("dashboard") }}';
            });

            Livewire.on('loan-error', (event) => {
                alert('Error: ' + event[0]);
            });
        });
    </script>
</div>
