<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <title>AbsenM</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<nav class="bg-white border-gray-200 dark:bg-gray-900">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse" id="logo">
            <!-- <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" /> -->
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">AbsenM</span>
        </a>
        <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            <!-- <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Get started</button> -->
            <button data-collapse-toggle="navbar-cta" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-cta" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                </svg>
            </button>
        </div>
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-cta">
            <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                <?php
                // Mendapatkan nilai show dari query parameter
                $show_form = isset($_GET['show']) ? $_GET['show'] : '';

                // Array untuk menu
                $menu_items = [
                    'face-view' => 'F.View',
                    'face-register' => 'F.Regis',
                    'VdataAttendances' => 'T.Kehadiran',
                    'userTabel' => 'T.User',
                ];

                foreach ($menu_items as $key => $value) {
                    // Menentukan kelas aktif untuk tautan yang dipilih
                    $active_class = $show_form === $key ? 'text-white bg-blue-700 rounded' : 'text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white';
                    echo "<li><a href='" . site_url("absen?show=$key") . "' class='menu-item block py-2 px-3 md:p-0 $active_class'>$value</a></li>";
                }
                ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mx-auto p-4">
    <?php 

    $show_form = isset($_GET['show']) ? $_GET['show'] : 'face-view';

        if ($show_form === 'face-view') {
            $this->load->view('absen/face-view');
        } elseif ($show_form === 'face-register') {
            $this->load->view('absen/face-register');
        } elseif ($show_form === 'VdataAttendances') {
            $this->load->view('absen/data');
        } elseif ($show_form === 'userTabel') {
            $this->load->view('absen/userTabel');
        }
    ?>
</div>
</body>
</html>
