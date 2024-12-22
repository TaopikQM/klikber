<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>

<div class="flex flex-col justify-center items-center">
    <form id="addUserForm" class="space-y-4 w-full max-w-md">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama:</label>
            <input
                id="name"
                type="text"
                class="mt-1 p-2 border border-gray-300 rounded-md w-full"
                required
            />
        </div>
        <div>
            <label for="nim" class="block text-sm font-medium text-gray-700">NIM:</label>
            <input
                id="nim"
                type="text"
                class="mt-1 p-2 border border-gray-300 rounded-md w-full"
                required
            />
        </div>
        <button
            type="submit"
            class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300 w-full"
            id="submitButton"
        >
            Simpan
        </button>
    </form>
</div>

<script>
    
    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah pengiriman form secara default

        // Ambil nilai dari input
        const name = document.getElementById('name').value;
        const nim = document.getElementById('nim').value;

        // Buat data untuk dikirim
        const formData = new URLSearchParams();
        formData.append('name', name);
        formData.append('nim', nim);

        // Kirim data menggunakan fetch
        fetch('<?= site_url('absen/adduser/add') ?>', { // Sesuaikan URL dengan path controller
            method: 'POST',
            body: formData,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded', // Tipe konten yang sesuai
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            alert(data.message); // Tampilkan pesan dari server
            // Reset form jika perlu
            document.getElementById('addUserForm').reset();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
        });
    });


</script>

</body>
</html>
