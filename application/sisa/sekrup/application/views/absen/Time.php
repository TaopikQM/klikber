<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Magang KOMINFO</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const timeElement = document.getElementById("currentTime");
            const dateElement = document.getElementById("currentDate");
            const greetingElement = document.getElementById("greeting");

            const updateTime = () => {
                // Mengambil waktu sekarang di zona waktu Jakarta
                const now = new Date();
                const options = { timeZone: 'Asia/Jakarta' };
                
                // Format hari, tanggal, bulan, dan tahun
                const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const formattedDate = new Intl.DateTimeFormat('id-ID', optionsDate).format(now);

                // Ambil jam, menit, dan detik di zona waktu Jakarta
                const formattedTime = new Intl.DateTimeFormat('id-ID', { 
                    ...options, 
                    hour: '2-digit', 
                    minute: '2-digit', 
                    second: '2-digit', 
                    hour12: false 
                }).format(now);

                const currentHour = now.toLocaleString('en-US', { timeZone: 'Asia/Jakarta', hour: 'numeric', hour12: false });

                timeElement.textContent = `${formattedTime} WIB`;
                dateElement.textContent = formattedDate;

                // Menentukan ucapan selamat berdasarkan jam
                if (currentHour >= 5 && currentHour < 10) {
                    greetingElement.textContent = 'Selamat Pagi';
                } else if (currentHour >= 10 && currentHour < 14) {
                    greetingElement.textContent = 'Selamat Siang';
                } else if (currentHour >= 14 && currentHour < 18) {
                    greetingElement.textContent = 'Selamat Sore';
                } else {
                    greetingElement.textContent = 'Selamat Malam';
                }
            };      

            // Update waktu setiap detik
            setInterval(updateTime, 1000);
            updateTime(); // Memanggil fungsi sekali untuk mengatur nilai awal
        });
    </script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-200">
    <div class="bg-white shadow-lg rounded-lg p-6 w-full text-center">
        <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 mb-4">PRESENSI MAGANG KOMINFO</h1>
        <h2 id="greeting" class="text-lg md:text-xl font-semibold text-gray-700 mb-2"></h2> <!-- Ucapan Selamat -->
        <h2 id="currentDate" class="text-lg md:text-xl font-semibold text-gray-700 mb-2"></h2> <!-- Tanggal dan Hari -->
        <h3 id="currentTime" class="text-2xl md:text-3xl font-bold text-gray-900"></h3> <!-- Waktu + WIB -->
    </div>
</body>
</html>
