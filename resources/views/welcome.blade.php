@extends('layouts.app')


@section('content')
{{-- ==== HERO SECTION ==== --}}
<section class="relative overflow-hidden bg-gradient-to-br from-primary/5 via-white to-primary/5 dark:from-gray-800 dark:via-gray-900 dark:to-gray-800 py-20 lg:py-15">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Text -->
            <div class="space-y-6">
                <p class="text-primary font-medium text-sm uppercase tracking-wider">Simple. Quick. Secure.</p>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white leading-tight">
                    Make Online Banking<br><span class="text-primary">Easier & Comfortable</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">
                    An international account to send money to over 60 countries around the world, up to <strong>7x cheaper</strong> than banks. 
                    <a href="/about" class="text-primary hover:underline">Learn more →</a>
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/getting-started" class="px-6 py-3 bg-primary hover:bg-primary/90 text-white font-medium rounded-xl shadow-md transition-all hover:scale-105 hover:shadow-lg text-center">
                        Get Started
                    </a>
                    <a href="/contact" class="px-6 py-3 border-2 border-primary text-primary hover:bg-primary hover:text-white font-medium rounded-xl transition-all hover:scale-105 text-center">
                        Contact Us
                    </a>
                </div>
            </div>

            <!-- Image -->
            <div class="relative">
                <img src="{{ asset('assets/images/main-banner/banner.jpg') }}" alt="Online Banking" class="rounded-2xl shadow-xl w-full">
                <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-primary/10 rounded-full blur-3xl"></div>
                <div class="absolute -top-6 -right-6 w-40 h-40 bg-primary/20 rounded-full blur-3xl"></div>
            </div>
        </div>
    </div>
</section>

{{-- ==== RELIABLE SECTION ==== --}}
<section class="py-20 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="order-2 lg:order-1">
                <img src="{{ asset('assets/images/reliable/reliable.png') }}" alt="Reliable Platform" class="rounded-2xl shadow-lg w-full">
            </div>
            <div class="space-y-6 order-1 lg:order-2">
                <p class="text-primary font-medium">Reliable Online Payment Platform</p>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                    Transfer & Deposit Money Anytime, Anywhere In The World
                </h2>
                <p class="text-gray-600 dark:text-gray-300">
                    Lorem ipsum dolor sit amet consectetur adipiscing elit. Volutpat nisl bibendum vitae consequat.
                </p>
                <div class="grid sm:grid-cols-2 gap-6">
                    @foreach ([
                        'Powerful Mobile & Online App', 'Commitment Free', 'Full Data Privacy Compliance',
                        'Free Plan Available', '100% Transparent Cost', 'Debit Mastercard Included'
                    ] as $feature)
                        <div class="flex items-center space-x-3">
                            <i class="ti ti-check text-primary text-xl"></i>
                            <span class="text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==== GLOBAL TRANSFERS ==== --}}
<section class="py-20 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-primary font-medium">Global Transfers</p>
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mt-2">
            We Charge As Little As Possible. No Subscription Fee
        </h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mt-12">
            @php
                $cards = [
                    ['icon' => 'ti ti-mail', 'title' => 'Send Money Cheaper', 'btn' => 'Send Money'],
                    ['icon' => 'ti ti-transfer', 'title' => 'Spend Abroad Without Fees', 'btn' => 'Get Started'],
                    ['icon' => 'ti ti-currency-dollar', 'title' => 'Receive in 9 Currencies', 'btn' => 'Available Accounts'],
                    ['icon' => 'ti ti-exchange', 'title' => 'Hold 54 Currencies', 'btn' => 'See All Currencies'],
                ];
            @endphp
            @foreach ($cards as $card)
                <div class="bg-white dark:bg-gray-700 p-6 rounded-2xl shadow-md hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-4">
                        <i class="{{ $card['icon'] }} text-primary text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $card['title'] }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Adipiscing eliId neque mi, diam nim etus arcu porta viverra.
                    </p>
                    <a href="#" class="text-primary font-medium hover:underline">{{ $card['btn'] }} →</a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ==== PAYMENT SERVICES ==== --}}
<section class="py-20 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <p class="text-primary font-medium">Payment Services Worldwide</p>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                    Easily Grow Your Business Save More Money
                </h2>
                <p class="text-gray-600 dark:text-gray-300">
                    Lorem ipsum dolor sit amet consectetur adipiscing elit. Volutpat nisl bibendum vitae consequat.
                </p>
                <div class="grid sm:grid-cols-2 gap-6">
                    @foreach ([
                        'Reliable Online Payment', 'Low Transaction Fee', 'Affordable Security Packages',
                        'Best Networking', 'Secure Payment Service', 'Real Time Money Tracking'
                    ] as $item)
                        <div class="flex items-center space-x-3">
                            <i class="ti ti-check text-primary text-xl"></i>
                            <span class="text-gray-700 dark:text-gray-300">{{ $item }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div>
                <img src="{{ asset('assets/images/paiement.png') }}" alt="Payment" class="rounded-2xl shadow-lg w-full">
            </div>
        </div>
    </div>
</section>

{{-- ==== BENEFITS ==== --}}
<section class="py-20 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-primary font-medium">Your Benefits</p>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mt-2">
                Take The Stress Out Of Managing Property And Money
            </h2>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $benefits = [
                    ['img' => 'benefits-1.png', 'title' => 'Global Coverage'],
                    ['img' => 'benefits-2.png', 'title' => 'Lowest Fee'],
                    ['img' => 'benefits-3.png', 'title' => 'Simple Transfer Methods'],
                    ['img' => 'benefits-4.png', 'title' => 'Instant Processing'],
                    ['img' => 'benefits-5.png', 'title' => 'Bank-level Security'],
                    ['img' => 'benefits-6.png', 'title' => 'Global 24/7 Support'],
                ];
            @endphp
            @foreach ($benefits as $b)
                <div class="bg-white dark:bg-gray-700 p-6 rounded-2xl text-center shadow-md hover:shadow-lg transition-all">
                    <img src="{{ asset('assets/images/benefits/' . $b['img']) }}" alt="{{ $b['title'] }}" class="w-16 h-16 mx-auto mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $b['title'] }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        Adipiscing eli neque mi diam nim etus arcu porta viverra pretium auctor ut nam sed.
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ==== FEATURES ==== --}}
<section class="py-20 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <img src="{{ asset('assets/images/features/features-2.png') }}" alt="Features" class="rounded-2xl shadow-lg w-full">
            </div>
            <div class="space-y-6">
                <p class="text-primary font-medium">Our Features</p>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                    The Reliable, Cheap & Fastest Way To Send Money Abroad
                </h2>
                <p class="text-gray-600 dark:text-gray-300">
                    Lorem ipsum dolor sit amet consectetur adipiscing volutpat nisl bibendum vitae consequat.
                </p>
                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                <i class="ti ti-clock text-primary"></i>
                            </div>
                            <h3 class="font-semibold">Faster And Cheaper</h3>
                        </div>
                        <ul class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                            <li class="flex items-center space-x-2"><i class="ti ti-circle-check text-primary"></i><span>Lorem neque diam nim etus porta viverra.</span></li>
                            <li class="flex items-center space-x-2"><i class="ti ti-circle-check text-primary"></i><span>Adipiscing eliId neque, diam nim tus porta.</span></li>
                        </ul>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                <i class="ti ti-shield text-primary"></i>
                            </div>
                            <h3 class="font-semibold">Trusted & Secure</h3>
                        </div>
                        <ul class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                            <li class="flex items-center space-x-2"><i class="ti ti-circle-check text-primary"></i><span>Lorem neque diam nim etus porta viverra.</span></li>
                            <li class="flex items-center space-x-2"><i class="ti ti-circle-check text-primary"></i><span>Adipiscing eliId neque, diam nim tus porta.</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==== REVIEWS ==== --}}
<section class="py-20 bg-gradient-to-b from-gray-50 to-white dark:from-gray-800 dark:to-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-primary font-medium">Our Review</p>
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mt-2">
            More Than 4,405,28 Happy Customers Trust Our Services
        </h2>
        <div class="grid md:grid-cols-2 gap-8 mt-12">
            @foreach ([
                ['name' => 'Thomoson Piterson', 'role' => 'Endemycon Leader', 'img' => 'review-1.jpg'],
                ['name' => 'Maxwel Warner', 'role' => 'Endemycon Leader', 'img' => 'review-2.jpg'],
            ] as $review)
                <div class="bg-white dark:bg-gray-700 p-8 rounded-2xl shadow-lg">
                    <div class="flex justify-center space-x-1 mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="ti ti-star-filled text-yellow-500"></i>
                        @endfor
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 italic">
                        "Vitae cras leo tellus lectus non fusce tate nibh massa. Quis ut odio quam in lorem nam felis sed."
                    </p>
                    <div class="flex items-center justify-center mt-6 space-x-3">
                        <img src="{{ asset('assets/images/review/' . $review['img']) }}" alt="{{ $review['name'] }}" class="w-12 h-12 rounded-full">
                        <div class="text-left">
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $review['name'] }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $review['role'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <p class="mt-8 text-lg text-gray-600 dark:text-gray-300">
            <strong>Excellent</strong> <span class="text-sm">Based on 25,454 reviews</span>
        </p>
    </div>
</section>


{{-- ==== OVERVIEW CTA ==== --}}
<section class="py-20 bg-primary text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="font-medium">Connect Us</p>
        <h2 class="text-3xl lg:text-4xl font-bold mt-2">
            Sending International Business Payments or Sending Money To Family Overseas?<br>
            <span class="text-white/90">Credify Are Your Fast And Simple Solution.</span>
        </h2>
        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
            <a href="/help-center" class="px-8 py-3 bg-white text-primary font-medium rounded-xl hover:bg-gray-100 transition">
                Personal Account
            </a>
            <a href="/help-center" class="px-8 py-3 border-2 border-white text-white font-medium rounded-xl hover:bg-white/10 transition">
                Business Account
            </a>
        </div>
    </div>
</section>

{{-- ==== BLOG ==== --}}
<section class="py-20 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-primary font-medium">News And Blog</p>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mt-2">
                Get Up To Dates With Our Latest Blog And Banking News
            </h2>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach ([
                ['date' => 'September 15, 2025', 'title' => 'The Security Risks Of Changing Package Owners', 'img' => 'blog-1.jpg'],
                ['date' => 'September 16, 2025', 'title' => 'You Can Trust Me And Business With Together', 'img' => 'blog-2.jpg'],
                ['date' => 'September 17, 2025', 'title' => 'Consumer Expectations For Experience In The Climate', 'img' => 'blog-3.jpg'],
            ] as $post)
                <article class="bg-white dark:bg-gray-700 rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all">
                    <img src="{{ asset('assets/images/blog/' . $post['img']) }}" alt="{{ $post['title'] }}" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $post['date'] }}</p>
                        <h3 class="mt-2 text-lg font-semibold text-gray-900 dark:text-white line-clamp-2">
                            <a href="/blog-details" class="hover:text-primary transition">{{ $post['title'] }}</a>
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                            Lorem ipsum dolor sit amet consectetur adipisc elit vitae commodo nunc vel quis edout.
                        </p>
                        <a href="/blog-details" class="mt-4 inline-block text-primary font-medium hover:underline">
                            Read More →
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection