<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
            <div class="mx-auto max-w-screen-md text-center mb-8 lg:mb-12">
                <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Designed for
                    business teams like yours</h2>
                <p class="mb-5 font-light text-gray-500 sm:text-xl dark:text-gray-400">Here at Flowbite we focus on
                    markets where technology, innovation, and capital can unlock long-term value and drive economic
                    growth.</p>
            </div>
            <div class="space-y-8 lg:grid lg:grid-cols-3 sm:gap-6 xl:gap-10 lg:space-y-0">
                <!-- Pricing Card -->
                <div
                    class="flex flex-col p-6 mx-auto max-w-lg text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                    <h3 class="mb-4 text-2xl font-semibold">Membresía Básica</h3>
                    <p class="font-light text-gray-500 sm:text-lg dark:text-gray-400">Flexible sin compromisos a largo
                        plazo.</p>
                    <div class="flex justify-center items-baseline my-8">
                        <span class="mr-2 text-5xl font-extrabold">$29</span>
                        <span class="text-gray-500 dark:text-gray-400">/mensual</span>
                    </div>
                    <!-- List -->
                    <ul role="list" class="mb-8 space-y-4 text-left">
                        <li class="flex items-center space-x-3">
                            <!-- Icon -->
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Individual configuration</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <!-- Icon -->
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>No setup, or hidden fees</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <!-- Icon -->
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Team size: <span class="font-semibold">1 developer</span></span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <!-- Icon -->
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Premium support: <span class="font-semibold">6 months</span></span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <!-- Icon -->
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Free updates: <span class="font-semibold">6 months</span></span>
                        </li>
                    </ul>

                    @auth
                        @if (auth()->user()->subscribedToPrice('price_1NUcHCDwifqCcTXtK0tyicxS', 'Membresia Sport'))
                            @if (auth()->user()->subscription('Membresia Sport')->onGracePeriod())
                                <x-secondary-button wire:click="resumeSubscription" wire:target="resumeSubscription"
                                    wire:loading.attr="disabled">

                                    <x-spinner size="4" wire:target="resumeSubscription" wire:loading />
                                    Reanudar
                                </x-secondary-button>
                            @else
                                <x-danger-button wire:click="cancelSubscription" wire:target="cancelSubscription"
                                    wire:loading.attr="disabled">

                                    <x-spinner size="4" wire:target="cancelSubscription" wire:loading />

                                    Cancelar

                                </x-danger-button>
                            @endif
                        @else
                            <x-button wire:click="newSubscription('price_1NUcHCDwifqCcTXtK0tyicxS')"
                                wire:target="newSubscription('price_1NUcHCDwifqCcTXtK0tyicxS')"
                                wire:loading.attr="disabled">
                                <x-spinner size="4" wire:target="newSubscription('price_1NUcHCDwifqCcTXtK0tyicxS')"
                                    wire:loading />
                                Get started
                            </x-button>
                        @endif
                    @else
                        <!-- Contenido para usuarios no autenticados -->
                        Inicia sesión para comprar
                    @endauth

                </div>
                <!-- Pricing Card -->
                <div
                    class="flex flex-col p-6 mx-auto max-w-lg text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                    <h3 class="mb-4 text-2xl font-semibold">Membresía Estándar</h3>
                    <p class="font-light text-gray-500 sm:text-lg dark:text-gray-400">Alcanza tus objetivos de salud y
                        bienestar con nuestros servicios.</p>
                    <div class="flex justify-center items-baseline my-8">
                        <span class="mr-2 text-5xl font-extrabold">$49</span>
                        <span class="text-gray-500 dark:text-gray-400" dark:text-gray-400>/trimestral</span>
                    </div>
                    <!-- List -->
                    <ul role="list" class="mb-8 space-y-4 text-left">
                        <li class="flex items-center space-x-3">
                            <!-- Icon -->
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Individual configuration</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <!-- Icon -->
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>No setup, or hidden fees</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <!-- Icon -->
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Team size: <span class="font-semibold">10 developers</span></span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <!-- Icon -->
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Premium support: <span class="font-semibold">24 months</span></span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <!-- Icon -->
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Free updates: <span class="font-semibold">24 months</span></span>
                        </li>
                    </ul>
                    @auth
                        @if (auth()->user()->subscribedToPrice('price_1NUcHCDwifqCcTXtb91zhGoQ', 'Membresia Sport'))
                            @if (auth()->user()->subscription('Membresia Sport')->onGracePeriod())
                                <x-secondary-button wire:click="resumeSubscription" wire:target="resumeSubscription"
                                    wire:loading.attr="disabled">

                                    <x-spinner size="4" wire:target="resumeSubscription" wire:loading />
                                    Reanudar
                                </x-secondary-button>
                            @else
                                <x-danger-button wire:click="cancelSubscription" wire:target="cancelSubscription"
                                    wire:loading.attr="disabled">

                                    <x-spinner size="4" wire:target="cancelSubscription" wire:loading />

                                    Cancelar

                                </x-danger-button>
                            @endif
                        @else
                            <x-button wire:click="newSubscription('price_1NUcHCDwifqCcTXtb91zhGoQ')"
                                wire:target="newSubscription('price_1NUcHCDwifqCcTXtb91zhGoQ')"
                                wire:loading.attr="disabled">
                                <x-spinner size="4" wire:target="newSubscription('price_1NUcHCDwifqCcTXtb91zhGoQ')"
                                    wire:loading />
                                Get started
                            </x-button>
                        @endif
                    @else
                        <!-- Contenido para usuarios no autenticados -->
                        Inicia sesión para comprar
                    @endauth

                </div>
                <!-- Pricing Card -->
                <div
                    class="flex flex-col p-6 mx-auto max-w-lg text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                    <h3 class="mb-4 text-2xl font-semibold">Membresía Plus</h3>
                    <p class="font-light text-gray-500 sm:text-lg dark:text-gray-400">Disfrutar de nuestros beneficios
                        durante más tiempo.</p>
                    <div class="flex justify-center items-baseline my-8">
                        <span class="mr-2 text-5xl font-extrabold">$99</span>
                        <span class="text-gray-500 dark:text-gray-400">/semestral</span>
                    </div>
                    <!-- List -->
                    <ul role="list" class="mb-8 space-y-4 text-left">
                        <li class="flex items-center space-x-3">
                            <!-- Icon -->
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Individual configuration</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <!-- Icon -->
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>No setup, or hidden fees</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <!-- Icon -->
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Team size: <span class="font-semibold">100+ developers</span></span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <!-- Icon -->
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Premium support: <span class="font-semibold">36 months</span></span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <!-- Icon -->
                            <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Free updates: <span class="font-semibold">36 months</span></span>
                        </li>
                    </ul>

                    @auth
                        @if (auth()->user()->subscribedToPrice('price_1NUcHCDwifqCcTXth7si5F6n', 'Membresia Sport'))
                            @if (auth()->user()->subscription('Membresia Sport')->onGracePeriod())
                                <x-secondary-button wire:click="resumeSubscription" wire:target="resumeSubscription"
                                    wire:loading.attr="disabled">

                                    <x-spinner size="4" wire:target="resumeSubscription" wire:loading />
                                    Reanudar
                                </x-secondary-button>
                            @else
                                <x-danger-button wire:click="cancelSubscription" wire:target="cancelSubscription"
                                    wire:loading.attr="disabled">

                                    <x-spinner size="4" wire:target="cancelSubscription" wire:loading />

                                    Cancelar

                                </x-danger-button>
                            @endif
                        @else
                            <x-button wire:click="newSubscription('price_1NUcHCDwifqCcTXth7si5F6n')"
                                wire:target="newSubscription('price_1NUcHCDwifqCcTXth7si5F6n')"
                                wire:loading.attr="disabled">
                                <x-spinner size="4" wire:target="newSubscription('price_1NUcHCDwifqCcTXth7si5F6n')"
                                    wire:loading />
                                Get started
                            </x-button>
                        @endif
                    @else
                        Iniciar sesión para comprar
                    @endauth

                </div>
            </div>
        </div>
    </section>

    @push('js')
        <script>
            //pedimos que escuche el evento error
            Livewire.on('error', function(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                    //footer: '<a href="{{ route('billings.index') }}">Agregar método de pago</a>'
                })
            })
        </script>
    @endpush

</div>
