<x-app-layout>
    <div class="min-h-screen bg-white">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <svg class="h-8 w-8 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-2 text-xl font-bold text-gray-900">MiniBank</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                        <a href="{{ route('about') }}" class="text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">About</a>
                        <a href="{{ route('faq') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">FAQ</a>
                        <a href="{{ route('contact') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Contact</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-extrabold text-white sm:text-5xl md:text-6xl">
                        About MiniBank
                    </h1>
                    <p class="mt-3 max-w-md mx-auto text-base text-indigo-200 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                        Revolutionizing banking with technology, transparency, and trust.
                    </p>
                </div>
            </div>
        </div>

        <!-- Mission Section -->
        <div class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Our Mission</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Making banking accessible to everyone
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                        We believe that financial services should be simple, transparent, and accessible to everyone, regardless of their background or financial situation.
                    </p>
                </div>

                <div class="mt-10">
                    <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
                        <div class="relative">
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="ml-16 text-lg leading-6 font-medium text-gray-900">Innovation</h3>
                            <p class="mt-2 ml-16 text-base text-gray-500">
                                We leverage cutting-edge technology to provide faster, more efficient banking services.
                            </p>
                        </div>

                        <div class="relative">
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="ml-16 text-lg leading-6 font-medium text-gray-900">Trust</h3>
                            <p class="mt-2 ml-16 text-base text-gray-500">
                                Your security and privacy are our top priorities. We use bank-level encryption and security measures.
                            </p>
                        </div>

                        <div class="relative">
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="ml-16 text-lg leading-6 font-medium text-gray-900">Community</h3>
                            <p class="mt-2 ml-16 text-base text-gray-500">
                                We're committed to building a community where everyone can achieve their financial goals.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Story Section -->
        <div class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:grid lg:grid-cols-2 lg:gap-8 lg:items-center">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                            Our Story
                        </h2>
                        <p class="mt-3 text-lg text-gray-500">
                            Founded in 2024, MiniBank was born from a simple idea: banking should be as easy as using your smartphone.
                            Our team of financial experts and technology innovators came together to create a platform that puts
                            the power of banking directly in your hands.
                        </p>
                        <p class="mt-4 text-lg text-gray-500">
                            We started with a vision to democratize financial services, making them accessible to individuals and
                            businesses of all sizes. Today, we're proud to serve thousands of customers who trust us with their
                            most important financial decisions.
                        </p>
                        <div class="mt-8">
                            <div class="inline-flex rounded-md shadow">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        Get Started
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        Join Us Today
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 lg:mt-0">
                        <div class="bg-white rounded-lg shadow-lg p-8">
                            <div class="grid grid-cols-2 gap-8">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-indigo-600">2024</div>
                                    <div class="text-sm text-gray-500">Founded</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-indigo-600">1000+</div>
                                    <div class="text-sm text-gray-500">Happy Customers</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-indigo-600">$50M+</div>
                                    <div class="text-sm text-gray-500">Loans Processed</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-indigo-600">24/7</div>
                                    <div class="text-sm text-gray-500">Customer Support</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Section -->
        <div class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        Meet Our Team
                    </h2>
                    <p class="mt-4 text-lg text-gray-500">
                        The passionate people behind MiniBank
                    </p>
                </div>

                <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="text-center">
                        <div class="space-y-4">
                            <div class="mx-auto h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center">
                                <svg class="h-10 w-10 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="space-y-2">
                                <div class="text-lg leading-6 font-medium">
                                    <h3>Sarah Johnson</h3>
                                    <p class="text-indigo-600">CEO & Founder</p>
                                </div>
                                <p class="text-gray-500">
                                    Former Goldman Sachs executive with 15 years of experience in financial services.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="space-y-4">
                            <div class="mx-auto h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center">
                                <svg class="h-10 w-10 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="space-y-2">
                                <div class="text-lg leading-6 font-medium">
                                    <h3>Michael Chen</h3>
                                    <p class="text-indigo-600">CTO</p>
                                </div>
                                <p class="text-gray-500">
                                    Technology leader with expertise in fintech and secure payment systems.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="space-y-4">
                            <div class="mx-auto h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center">
                                <svg class="h-10 w-10 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="space-y-2">
                                <div class="text-lg leading-6 font-medium">
                                    <h3>Emily Rodriguez</h3>
                                    <p class="text-indigo-600">Head of Risk</p>
                                </div>
                                <p class="text-gray-500">
                                    Risk management expert ensuring the security and stability of our platform.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Values Section -->
        <div class="bg-indigo-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        Our Values
                    </h2>
                    <p class="mt-4 text-lg text-gray-500">
                        The principles that guide everything we do
                    </p>
                </div>

                <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="text-center">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Integrity</h3>
                        <p class="mt-2 text-base text-gray-500">
                            We do what's right, even when no one is watching.
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Innovation</h3>
                        <p class="mt-2 text-base text-gray-500">
                            We constantly push boundaries to improve our services.
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Community</h3>
                        <p class="mt-2 text-base text-gray-500">
                            We're committed to serving our community's financial needs.
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Excellence</h3>
                        <p class="mt-2 text-base text-gray-500">
                            We strive for excellence in everything we do.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
                <div class="flex justify-center space-x-6 md:order-2">
                    <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">Home</a>
                    <a href="{{ route('about') }}" class="text-gray-400 hover:text-gray-500">About</a>
                    <a href="{{ route('faq') }}" class="text-gray-400 hover:text-gray-500">FAQ</a>
                    <a href="{{ route('contact') }}" class="text-gray-400 hover:text-gray-500">Contact</a>
                </div>
                <div class="mt-8 md:mt-0 md:order-1">
                    <p class="text-center text-base text-gray-400">
                        &copy; {{ date('Y') }} MiniBank. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</x-app-layout>
