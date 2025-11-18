<footer class="bg-white dark:bg-gray-800 border-t dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">

            <!-- Branding + Newsletter -->
            <div class="space-y-7">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center shadow-md">
                        <span class="text-white font-bold text-2xl">CB</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-primary">Credify Bank</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Secure. Modern. Trusted.</p>
                    </div>
                </div>

                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Stay ahead with exclusive banking insights, security tips, and personalized offers.
                </p>

                <form class="flex flex-col sm:flex-row gap-3">
                    <input type="email" placeholder="Your email address" required
                           class="flex-1 px-4 py-3 rounded-xl border bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                    <button type="submit" class="px-6 py-3 bg-primary hover:bg-primary/90 text-white font-medium rounded-xl shadow-md flex items-center space-x-1.5">
                        <span>Subscribe</span>
                        <i class="ti ti-arrow-right text-sm"></i>
                    </button>
                </form>

                <div class="flex space-x-3">
                    @foreach (['facebook', 'twitter', 'instagram', 'linkedin'] as $social)
                        <a href="#" class="p-3 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-primary hover:text-white transition-all">
                            <i class="ti ti-brand-{{ $social }} text-lg"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Company -->
            <div class="space-y-5">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Company & Team</h3>
                <ul class="space-y-2.5 text-sm">
                    @foreach (['Company & Team', 'News & Blog', 'About Us', 'Affiliates', 'Careers'] as $item)
                        <li><a href="#" class="flex items-center space-x-1 text-gray-600 dark:text-gray-400 hover:text-primary"><i class="ti ti-chevron-right text-xs"></i><span>{{ $item }}</span></a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Resources -->
            <div class="space-y-5">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Resources</h3>
                <ul class="space-y-2.5 text-sm">
                    @foreach (['Security', "FAQ's", 'Community', 'Privacy Policy', 'Contact Us'] as $item)
                        <li><a href="#" class="flex items-center space-x-1 text-gray-600 dark:text-gray-400 hover:text-primary"><i class="ti ti-chevron-right text-xs"></i><span>{{ $item }}</span></a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Contact -->
            <div class="space-y-5">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Contact Info</h3>
                <ul class="space-y-4 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-start space-x-3"><i class="ti ti-map-pin text-primary mt-0.5"></i><span>27 Division St, Suite 1100<br>E Denver, CO 80237, USA</span></li>
                    <li class="flex items-center space-x-3"><i class="ti ti-mail text-primary"></i><a href="mailto:support@credify.com" class="hover:text-primary">support@credify.com</a></li>
                    <li class="flex items-center space-x-3"><i class="ti ti-phone text-primary"></i><a href="tel:+44789289524329" class="hover:text-primary">+44 7892 8952 4329</a></li>
                    <li class="flex items-center space-x-3"><i class="ti ti-printer text-primary"></i><a href="tel:+12129876543" class="hover:text-primary">+1-212-987-6543</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t dark:border-gray-700 text-center text-sm text-gray-500 dark:text-gray-400">
            © {{ date('Y') }} <span class="text-primary font-semibold">Credify Bank</span>. All rights reserved.
            <span class="block mt-1 sm:inline sm:ml-2">Crafted with <i class="ti ti-heart text-red-500"></i> for secure banking</span>
        </div>
    </div>
</footer>