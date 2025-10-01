@extends('layouts.user')

@section('title', 'Make Deposit - VideoEarn')
@section('page-title', 'Make Deposit')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Initial Deposit Required</h3>
                        <p class="text-gray-600">To start earning from video tasks, you need to make an initial deposit of $100.</p>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-blue-800">Why do I need to deposit?</h4>
                                <p class="text-sm text-blue-700 mt-1">
                                    This one-time deposit ensures you're committed to the platform and helps us provide quality video content for monetization.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('payment.deposit') }}" method="POST" id="payment-form">
                        @csrf
                        <div class="space-y-6">
                            <!-- Amount -->
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Deposit Amount</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" 
                                           name="amount" 
                                           id="amount" 
                                           value="100.00" 
                                           step="0.01" 
                                           min="100" 
                                           max="100"
                                           readonly
                                           class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md bg-gray-100">
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Fixed deposit amount: $100.00</p>
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="stripe">Credit/Debit Card (Stripe)</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                </select>
                            </div>

                            <!-- Stripe Payment Element -->
                            <div id="stripe-payment-element" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Card Details</label>
                                <div id="card-element" class="p-3 border border-gray-300 rounded-md">
                                    <!-- Stripe Elements will create form elements here -->
                                </div>
                                <div id="card-errors" role="alert" class="text-red-600 text-sm mt-2"></div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="terms" name="terms" type="checkbox" required class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="terms" class="text-gray-700">
                                        I agree to the <a href="#" class="text-blue-600 hover:text-blue-500">Terms and Conditions</a> and <a href="#" class="text-blue-600 hover:text-blue-500">Privacy Policy</a>
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-400">
                                    Cancel
                                </a>
                                <button type="submit" id="submit-button" class="bg-blue-600 text-white px-6 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <span id="button-text">Pay $100.00</span>
                                    <span id="spinner" class="hidden">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Processing...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Security Notice -->
                    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-shield-alt text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-800">Secure Payment</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    Your payment information is encrypted and secure. We use industry-standard SSL encryption to protect your data.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    
    const paymentMethodSelect = document.getElementById('payment_method');
    const stripePaymentElement = document.getElementById('stripe-payment-element');
    const cardElement = elements.create('card');
    
    // Show/hide Stripe payment element based on payment method
    paymentMethodSelect.addEventListener('change', function() {
        if (this.value === 'stripe') {
            stripePaymentElement.style.display = 'block';
            cardElement.mount('#card-element');
        } else {
            stripePaymentElement.style.display = 'none';
        }
    });

    // Handle form submission
    document.getElementById('payment-form').addEventListener('submit', async function(event) {
        event.preventDefault();
        
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');
        
        // Show loading state
        submitButton.disabled = true;
        buttonText.style.display = 'none';
        spinner.classList.remove('hidden');
        
        const paymentMethod = paymentMethodSelect.value;
        
        if (paymentMethod === 'stripe') {
            // Handle Stripe payment
            const {error} = await stripe.confirmCardPayment('{{ $clientSecret ?? "" }}', {
                payment_method: {
                    card: cardElement,
                }
            });
            
            if (error) {
                // Show error to customer
                console.error('Error:', error);
                alert('Payment failed: ' + error.message);
                
                // Reset button state
                submitButton.disabled = false;
                buttonText.style.display = 'inline';
                spinner.classList.add('hidden');
            } else {
                // Payment succeeded
                this.submit();
            }
        } else {
            // Handle other payment methods
            this.submit();
        }
    });
</script>
@endsection
