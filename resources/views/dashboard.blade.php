<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
                              <!-- item-->
                            <!-- item-->
 <a href="javascript:void(0)" onclick="document.getElementById('logout-form').submit();"
                                class="flex items-center gap-2 py-1.5 px-4 text-sm text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300">
                                <i class="ri-logout-box-line text-lg align-middle"></i>
                                <span>Logout</span>
                            </a>

                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                @csrf

                            </form>
</body>
</html>