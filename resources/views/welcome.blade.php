<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="{{ asset('welcome/src/output.css') }}" rel="stylesheet">

    <!-- C3.js CSS -->


</head>

<body class="bg-cover bg-no-repeat bg-center relative px-4" style="background-image: url('{{ asset('welcome/public/slider-bg-01.jpg') }}')">
    <div class="relative min-h-screen flex justify-between flex-col container mx-auto z-20 text-white">
        <div class="md:flex justify-between pt-3">
            <div class="text-2xl md:text-start text-center pb-3 md:pb-0">member.org.in</div>
            <div class="md:flex items-center space-x-2 md:text-start text-center">
                <a href="#"
                    class="border border-white py-1.5 md:py-2.5 px-4 md:px-6 capitalize hover:bg-white hover:text-black transition-all">contact
                    us</a>
                <a href="#"
                    class="border border-white  py-1.5 md:py-2.5 px-4 md:px-6 capitalize hover:bg-white hover:text-black transition-all">login</a>
            </div>
        </div>
        <div class="text-center md:space-y-9 space-y-2">
            <h1 class="font-bold text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl 2xl:text-7xl capitalize">welcome to
                community</h1>
            <h2 class="font-medium text-lg md:text-2xl capitalize">connect, share & engage</h2>

        </div>
        <div class="text-center text-gray-500 text-sm py-2">copyright &copy; all rights reserved</div>
    </div>
    <div class="fixed inset-0 z-10 bg-black/60 "></div>
    <script>

    </script>
</body>

</html>