<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <title>Users Table</title>
</head>
<body class="bg-gray-100">
<div class="container mx-auto mt-10">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex justify-between items-center px-4 py-3">
                <div class="text-xl font-bold mb-4">Daftar Pengguna</div>
            </div>
            <div class="flex justify-between px-4 py-3">
                <div class="flex">
                    <select id="itemsPerPage" class="border rounded px-2 py-1"></select>
                    <div id="totalUserCount" class="mt-4 text-sm text-gray-500">Total User: 0</div>
                </div>
                <div class="relative">
                    <form class="max-w-md mx-auto ml-2">
                        <label class="mb-2 text-sm font-medium text-gray-00 sr-only">Search</label>
                        <input id="searchInput" type="search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50" placeholder="Search" required />
                    </form>
                </div>
            </div>
            <table id="userTable" class="min-w-full bg-white border border-gray-300">
                <thead class="border">
                    <tr>
                        <th class="py-2 px-4">No</th>
                        <th class="text-center">Nama <button id="sortNameBtn" class="ml-2">↑</button></th>
                        <th class="py-2 px-4">NIM</th>
                        <th class="py-2 px-4">Status</th>
                        <th class="py-2 px-4">Register</th>
                        <th class="py-2 px-4">Aksi</th>
                        
                        <th class="py-2 px-4">Darurat</th>
                    </tr>
                </thead>
                <tbody id="userList"></tbody>
            </table>
            <nav id="pagination" class="m-4 flex items-center justify-between pt-4"></nav>
        </div>
    </div> 
    <!-- <div class="container mx-auto mt-10">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            
            < ?php $this->load->view('absen/VdataAttendances'); ?>
        </div>
    </div> -->
    <script>  
        let users = [];
        let currentPage = 1;
        let itemsPerPage = 10;
        let totalItems = 0;
        let sortOrderName = 'asc';
        let searchTerm = '';
        let editUserId = null;
        let currentUsers = [];

        document.addEventListener('DOMContentLoaded', () => {
            populateItemsPerPage();
            fetchUsers();
            document.getElementById('searchInput').addEventListener('input', handleSearch);
            document.getElementById('sortNameBtn').addEventListener('click', sortByName);
        });

        const populateItemsPerPage = () => {
            const select = document.getElementById('itemsPerPage');
            [10, 25, 50, 100].forEach(value => {
                const option = document.createElement('option');
                option.value = value;
                option.text = value;
                select.add(option);
            });
            select.value = itemsPerPage;
            select.addEventListener('change', handleItemsPerPageChange);
        };

        const fetchUsers = async () => {
            try {
                const response = await fetch('<?= site_url('UserController/getall') ?>'); // Replace with your API endpoint
                if (response.ok) {
                    users = await response.json();
                    totalItems = users.length;
                    currentUsers = users;
                    renderUsers();
                    renderPagination();
                }
            } catch (error) {
                console.error("Error fetching users:", error);
            } 
        };

        const renderUsers = () => {
            const userList = document.getElementById("userList");
            const filteredUsers = users.filter(user => 
                user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                user.nim.includes(searchTerm)
            );
            const start = (currentPage - 1) * itemsPerPage;
            const displayedUsers  = filteredUsers.slice(start, start + itemsPerPage);

            userList.innerHTML = displayedUsers.map((user, index) => `
                <tr>
                    <td class="py-2 px-4 text-left">${start + index + 1}</td>
                    <td class="py-2 px-4 text-left">
                        ${editUserId === user.id ? 
                            `<input type="text" value="${user.name}" id="editName${user.id}" class="border border-gray-400 px-2 py-1 rounded-md" />`
                            : user.name}
                    </td>
                    <td class="py-2 px-4 text-left">
                        ${editUserId === user.id ? 
                            `<input type="text" value="${user.nim}" id="editNim${user.id}" class="border border-gray-400 px-2 py-1 rounded-md" />`
                            : user.nim}
                    </td>
                    <td class="py-2 px-4 text-center">
                        <span onclick="handleStatusClick('${user.id}', '${user.status}' , '${user.name}', '${user.nim}')" class="cursor-pointer text-blue-500 underline">
                           
                           ${user.status ===  'Active'//'active' ? 'active' : 'inactive'
                            ? `<button class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Active</button>` 
                            : `<button class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Inactive</button>`}
                         </span>

                    </td>
                    
                    <td class="py-2 px-4 text-center">${user.registration_status === `Registered`
                        ? `<span type="button" class="text-green-500  border border-green-500  focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm py-2 px-4 text-center me-2 mb-2">Registered</span>`
                        :`<span type="button" class="text-red-500  border border-red-500  focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm py-2 px-4 text-center me-2 mb-2 ">Not Registered</span>`}
                    </td>
                       
                    <td class="py-2 px-4 text-center">
                        ${editUserId === user.id ? 
                            `<button onclick="handleUpdateUser('${user.id}')" class="bg-blue-500 text-white px-4 py-2 rounded-md">Simpan</button>
                            <button onclick="cancelEdit()" class="bg-gray-500 text-white px-4 py-2 rounded-md">Batal</button>` :
                            `<button onclick="handleEditClick('${user.id}')" class="bg-yellow-400 text-white px-4 py-2 rounded-md">Edit</button>
                            `}
                            
                    </td>
                    <td class="py-2 px-4 text-center">
                        <button id="HadirButton" class="mt-4 px-5 py-2 bg-green-700 text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm" type="button"  onclick="handleAttendance('${user.name}','${user.status}')">
                            Hadir
                        </button>
                        <button id="PulangButton" class="mt-4 px-5 py-2 bg-yellow-700 text-white hover:bg-yellow-800 focus:outline-none focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm" type="button"  onclick="handlePulang('${user.name}','${user.status}')">
                            Pulang
                        </button>
                    </td>

                </tr>
            `).join('');
            // <button onclick="handleDeleteUser('${user.id}')" class="bg-red-700 text-white px-4 py-2 rounded-md">Hapus</button>

            document.getElementById("totalUserCount").textContent = `Total User: ${filteredUsers.length}`;
            
        }; 

        // Fungsi untuk mengubah status
        const handleStatusClick = async (userId, currentStatus, userName, userNim) => {
            const newStatus = currentStatus === "Active" ? "Inactive" : "Active";

            // Konfirmasi pengguna sebelum melanjutkan
            const confirmation = confirm(`Apakah Anda yakin ingin mengubah status?\nNama: ${userName}\nNIM: ${userNim}\nMenjadi: ${newStatus}`);
            if (!confirmation) {
                return; // Jika pengguna membatalkan, keluar dari fungsi
            }

            try {
                const response = await fetch('<?= site_url('UserController/updateS') ?>', { // Update status endpoint
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        id: userId,
                        status: newStatus,
                    }),
                });

                if (response.ok) {
                    // Update status di frontend
                    users = users.map(u => u.id === userId ? { ...u, status: newStatus } : u);
                    renderUsers(); // Segarkan tampilan pengguna
                    alert(`Status berhasil diperbarui menjadi ${newStatus}`); // Notifikasi berhasil
                } else {
                    const error = await response.json();
                    alert(error.error || 'Gagal memperbarui status');
                }
            } catch (error) {
                console.error('Error updating status:', error);
                alert('Terjadi kesalahan saat memperbarui status');
            }
        };

        const handleEditClick = (userId) => {
            editUserId = userId;
            renderUsers();
        };

        const cancelEdit = () => {
            editUserId = null;
            renderUsers();
        };

        // Fungsi untuk menyimpan perubahan
        const handleUpdateUser = async (userId) => {
            const oldName = currentUsers.find(user => user.id === userId).name; // Ambil nama lama
            const oldNim = currentUsers.find(user => user.id === userId).nim; // Ambil NIM lama

            const newName = document.getElementById(`editName${userId}`).value;
            const newNim = document.getElementById(`editNim${userId}`).value;

            // Validasi input
            if (!newName || !newNim) {
                alert('Silakan masukkan nama dan NIM yang valid');
                return;
            }

            // Konfirmasi pengguna sebelum melanjutkan
            const confirmation = confirm(`Apakah Anda yakin ingin mengubah data?\nNama Lama: ${oldName} menjadi Nama Baru: ${newName}\nNIM Lama: ${oldNim} menajadi NIM Baru: ${newNim}`);
            if (!confirmation) {
                return; // Jika pengguna membatalkan, keluar dari fungsi
            }

            try {
                const response = await fetch('<?= site_url('UserController/updateUser') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        id: userId,
                        name: newName,
                        nim: newNim,
                    }),
                });

                if (response.ok) {
                    const result = await response.json();
                    alert(result.message || 'User updated successfully');
                    editUserId = null; // Reset editUserId setelah berhasil
                    location.reload();  // Refresh data pengguna
                } else {
                    const error = await response.json();
                    alert(error.error || 'Failed to update user');
                }
            } catch (error) {
                console.error('Error updating user:', error);
                alert('Terjadi kesalahan saat memperbarui pengguna');
            }
        };

        const handleSearch = (e) => {
            searchTerm = e.target.value;
            currentPage = 1; // Reset to the first page on search change
            renderUsers();
            renderPagination();
        };

        const sortByName = () => {
            users.sort((a, b) => {
                const comparison = a.name.localeCompare(b.name);
                return sortOrderName === 'asc' ? comparison : -comparison;
            });
            sortOrderName = sortOrderName === 'asc' ? 'desc' : 'asc';
            renderUsers();
            renderPagination();
        };

        const handleItemsPerPageChange = (e) => {
            itemsPerPage = parseInt(e.target.value);
            currentPage = 1; // Reset to the first page on items per page change
            renderUsers();
            renderPagination();
        };

        const renderPagination = () => {
            const filteredUsers = users.filter(user => 
                user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                user.nim.includes(searchTerm)
            );

            const totalPages = Math.ceil(filteredUsers.length / itemsPerPage);
            const pagination = document.getElementById("pagination");

            // Clear existing pagination
            pagination.innerHTML = '';

            // Only show pagination if there are more users than items per page
            if (filteredUsers.length > itemsPerPage) {
                const span = document.createElement('span');
                span.className = "text-sm font-normal text-gray-500";
                span.textContent = `Page ${currentPage} of ${totalPages}`;
                pagination.appendChild(span);

                const ul = document.createElement('ul');
                ul.className = "inline-flex items-center -space-x-px";

                // Previous button
                const prevLi = document.createElement('li');
                const prevButton = document.createElement('button');
                prevButton.textContent = "Previous";
                prevButton.className = "py-2 px-4 border border-gray-300 rounded-l-lg";
                prevButton.disabled = currentPage === 1;
                prevButton.onclick = () => setCurrentPage(currentPage - 1);
                prevLi.appendChild(prevButton);
                ul.appendChild(prevLi);

                // Page buttons using getPagination
                const paginationArray = getPagination(totalPages, currentPage);
                paginationArray.forEach(page => {
                    const li = document.createElement('li');
                    const button = document.createElement('button');

                    if (page === '...') {
                        button.textContent = page;
                        button.disabled = true; // Disable the button for "..."
                        button.className = "py-2 px-4 border border-gray-300 text-gray-500";
                    } else {
                        button.textContent = page;
                        button.className = `py-2 px-4 border border-gray-300 ${currentPage === page ? 'bg-gray-600 text-white' : 'text-gray-500'}`;
                        button.onclick = () => setCurrentPage(page);
                    }

                    li.appendChild(button);
                    ul.appendChild(li);
                });

                // Next button
                const nextLi = document.createElement('li');
                const nextButton = document.createElement('button');
                nextButton.textContent = "Next";
                nextButton.className = "py-2 px-4 border border-gray-300 rounded-r-lg";
                nextButton.disabled = currentPage === totalPages;
                nextButton.onclick = () => setCurrentPage(currentPage + 1);
                nextLi.appendChild(nextButton);
                ul.appendChild(nextLi);

                pagination.appendChild(ul);
            }
        };

        const setCurrentPage = (page) => {
            const filteredUsers = users.filter(user => 
                user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                user.nim.includes(searchTerm)
            );

            // Validate the page number
            if (page < 1 || page > Math.ceil(filteredUsers.length / itemsPerPage)) return;

            currentPage = page;
            renderUsers(); // Re-render users for the current page
            renderPagination(); // Update pagination
        };
        // Function to get pagination array
        const getPagination = (totalPages, currentPage) => {
            let pages = [];
            
            if (totalPages <= 5) {
                pages = Array.from({ length: totalPages }, (_, i) => i + 1);
            } else {
                if (currentPage <= 3) {
                    pages = [1, 2, 3, 4, 5, '...'];
                } else if (currentPage >= totalPages - 2) {
                    pages = [1, '...', totalPages - 4, totalPages - 3, totalPages - 2, totalPages - 1, totalPages];
                } else {
                    pages = [1, '...', currentPage - 1, currentPage, currentPage + 1, '...', totalPages];
                }
            }
            return pages;
        };

        // Fungsi untuk menghapus pengguna
        const handleDeleteUser = async (userId) => {
            if (!window.confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                return; // Jika pengguna membatalkan, hentikan eksekusi fungsi
            }

            try {
                const response = await fetch('UserController/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ mhs_id: userId }),
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`Error deleting user: ${errorText}`);
                }

                const result = await response.json();
                alert(result.message);
            } catch (error) {
                console.error(error);
                alert('Failed to delete user. Please try again.');
            }
        };
 
        renderUsers();
        renderPagination();

        let loading = false;
        let ipAddress = '';
        let latitude = null;
        let longitude = null;

        const kominfoLocation = { lat:-6.9948815, lon: 110.415935 }; // Ganti dengan lokasi yang sesuai
        

        const fetchIpAndLocation = async () => {
            try {
                const ipRes = await axios.get('https://api.ipify.org?format=json');
                ipAddress = ipRes.data.ip;
               } catch (error) {
                console.error('Failed to fetch IP and location', error);
            }
        };

        const fetchGpsLocation = async () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        latitude = position.coords.latitude;
                        longitude = position.coords.longitude;
                        checkLocationAndDistance();
                        // console.log(latitude,longitude);
                    },
                    (error) => {
                        console.error("Error fetching GPS location:", error);
                        // Fallback jika gagal mendapat GPS, bisa Anda tambahkan logic lain di sini
                    }
                );
            } else {
                // console.error("Geolocation is not supported by this browser.");
            }
        };

        const calculateDistance = (lat1, lon1, lat2, lon2) => {
            const toRadians = (degree) => degree * (Math.PI / 180);
            const R = 6371; // Radius bumi dalam kilometer
            const dLat = toRadians(lat2 - lat1);
            const dLon = toRadians(lon2 - lon1);
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c; // Jarak dalam kilometer
        };

        const checkLocationAndDistance = () => {
            if (latitude && longitude) {
                const distanceToKominfo = calculateDistance(latitude, longitude, kominfoLocation.lat, kominfoLocation.lon);
                // console.log(distanceToKominfo);
                if (distanceToKominfo > 0.1) {
                    // alert('Lokasi Anda tidak sesuai dengan lokasi kantor.');
                    loading = false; 
                }
            }
        };

        // Panggil fungsi untuk mendapatkan IP dan lokasi saat halaman dimuat
        window.onload = () => {
            fetchIpAndLocation();
            fetchGpsLocation();
        };

        const handleAttendance = async (userName, userStatus) => {
            loading = true;
            document.getElementById('HadirButton').innerText = 'Loading...'; // Tampilkan loading pada tombol Hadir

            const date = new Date();

            const formattedDate = new Intl.DateTimeFormat('en-CA', { timeZone: 'Asia/Jakarta' }).format(date);

            const formattedTime = new Intl.DateTimeFormat('en-GB', { 
                timeZone: 'Asia/Jakarta', 
                hour: '2-digit', 
                minute: '2-digit', 
                hour12: false
            }).format(date) + ' WIB';
            
            try {
                const locationLat = latitude;
                const locationLong = longitude;
                // console.log(locationLat);
                // console.log(locationLong);
                // await checkLocationAndDistance();
                // console.log(checkLocationAndDistance());

                if (userStatus !== 'Active') {
                    alert('Pengguna tidak ditemukan di sistem. Silakan hubungi admin.');
                    loading = false;
                    document.getElementById('HadirButton').innerText = 'Hadir'; // Kembalikan teks tombol
                    return;
                } else {
                    // const userName = userCheckResponse.data.name; // Nama pengguna yang ditemukan

                    // Cek apakah sudah melakukan absensi hari ini
                    const attendanceCheckResponse = await axios.post('AttendanceController/checkAttendance', {
                        name: userName,
                        date: formattedDate
                    });

                    if (!attendanceCheckResponse.data.exists) {
                        // Simpan absensi
                        const saveAttendanceResponse = await axios.post('AttendanceController/saveAttendance', {
                            date: formattedDate,
                            ip: ipAddress,
                            kegiatan: '', // Kegiatan default kosong
                            latitude: locationLat,
                            longitude: locationLong,
                            name: userName,
                            timein: formattedTime, // Waktu masuk
                            timeot: '' // Waktu keluar default kosong
                        });

                        // console.log(saveAttendanceResponse.data.exists);
                        alert(`Absensi berhasil untuk: ${userName}\nSemangat menjalani hari ini!`);
                        loading = false;
                        document.getElementById('HadirButton').innerText = 'Hadir'; // Kembalikan teks tombol
                    } else {
                        alert(`Anda sudah melakukan Kehadiran untuk hari ini.\n${userName}\nSemangat menjalani hari ini!`);
                        loading = false;
                        document.getElementById('HadirButton').innerText = 'Hadir'; // Kembalikan teks tombol
                        return;
                    }
                }
            } catch (error) {
                console.error('Error during attendance:', error);
                alert('Terjadi kesalahan, silakan coba lagi.');
                loading = false;
                document.getElementById('HadirButton').innerText = 'Hadir'; // Kembalikan teks tombol
            } finally {
                loading = false;
                document.getElementById('HadirButton').innerText = 'Hadir'; // Kembalikan teks tombol
            }
        };

        
        const handlePulang = async (userName, userStatus) => {
            loading = true;
            document.getElementById('PulangButton').innerText = 'Loading...'; // Menampilkan loading

            const date = new Date();
            const formattedDate = new Intl.DateTimeFormat('en-CA', { timeZone: 'Asia/Jakarta' }).format(date);
            const formattedTime = new Intl.DateTimeFormat('en-GB', { 
                timeZone: 'Asia/Jakarta', 
                hour: '2-digit', 
                minute: '2-digit', 
                hour12: false 
            }).format(date) + ' WIB';

            const locationLat = latitude ;
            const locationLong= longitude ;

            try {
                if (userStatus !== 'Active') {
                    alert('Pengguna tidak ditemukan di sistem. Silakan hubungi admin.');
                    return;
                }

                const attendanceCheckResponse = await fetch('AttendanceController/checkAttendanceP', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'checkAttendanceP',
                        name: userName,
                        date: formattedDate,
                    })
                });

                const attendanceExist = await attendanceCheckResponse.json();

                if (attendanceExist && attendanceExist.exists) {
                    let id = attendanceExist.id;

                    // Jika kegiatan dan timeot belum diisi
                    if (attendanceExist.kegiatan === "" && attendanceExist.timeot === "") {
                        // Menyisipkan modal untuk update kegiatan
                        const modalHTML = `
                        <div id="kegiatanModal" style="display:block; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); width:400px; background:white; border:1px solid #ccc; padding:20px; box-shadow:0 4px 8px rgba(0,0,0,0.2); z-index:1000;">
                            <h3 style="text-align: center;"><strong>Isi Kegiatan Hari Ini</strong></h3>
                            <p style="text-align: center;">Silakan jelaskan kegiatan yang Anda lakukan hari ini sebelum pulang. Deskripsi minimal satu paragraf.</p>
                            <textarea id="kegiatanTextarea" rows="5" style="width:100%;"></textarea>
                            <br><br>
                            <button id="submitKegiatanBtn" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Submit</button>
                        </div>
                        <div id="modalOverlay" style="display:block; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;"></div>
                        `;
                        document.body.insertAdjacentHTML('beforeend', modalHTML); // Menambahkan modal ke body

                        // Event listener untuk tombol Submit
                        document.getElementById('submitKegiatanBtn').addEventListener('click', async () => {
                            const kegiatan = document.getElementById('kegiatanTextarea').value.trim();
                            
                            if (kegiatan === '' || kegiatan.length < 20) {
                                alert('Anda harus memasukkan kegiatan minimal satu paragraf.');
                                return;
                            }

                            closeModal(); // Tutup modal

                            try {
                                const updateAttendanceResponse = await fetch('AttendanceController/updateAttendance', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({
                                        action: 'updateAttendance',
                                        id: id, // Menggunakan ID dari kehadiran yang ada
                                        date: formattedDate,
                                        timeot: formattedTime,
                                        kegiatan: kegiatan,
                                    })
                                });

                                const updateResult = await updateAttendanceResponse.json();

                                if (updateAttendanceResponse.ok) {
                                    alert(`Absensi pulang berhasil diperbarui.\n${userName}\nKegiatan: ${kegiatan}\nSelamat istirahat!`);
                                } else {
                                    throw new Error(updateResult.message || 'Gagal memperbarui absensi.');
                                }
                            } catch (error) {
                                console.error('Error during update:', error);
                                alert('Terjadi kesalahan saat memperbarui absensi.');
                            } finally {
                                loading = false;
                                document.getElementById('PulangButton').innerText = 'Pulang';
                            }
                        });
                    } else {
                        // Jika sudah ada kegiatan yang tercatat
                        alert(`Anda sudah melakukan absensi pulang untuk hari ini.\n${userName}\nSelamat istirahat!`);
                    }
                } else {
                    // Jika pengguna tidak ditemukan, simpan baru
                    const modalHTML = `
                    <div id="kegiatanModal" style="display:block; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); width:400px; background:white; border:1px solid #ccc; padding:20px; box-shadow:0 4px 8px rgba(0,0,0,0.2); z-index:1000;">
                        <h3 style="text-align: center;"><strong>Isi Kegiatan Hari Ini</strong></h3>
                        <p style="text-align: center;">Silakan jelaskan kegiatan yang Anda lakukan hari ini sebelum pulang. Deskripsi minimal satu paragraf.</p>
                        <textarea id="kegiatanTextarea" rows="5" style="width:100%;"></textarea>
                        <br><br>
                        <button id="submitKegiatanBtn" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Submit</button>
                    </div>
                    <div id="modalOverlay" style="display:block; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;"></div>
                    `;
                    document.body.insertAdjacentHTML('beforeend', modalHTML); // Menambahkan modal ke body

                    // Event listener untuk tombol Submit
                    document.getElementById('submitKegiatanBtn').addEventListener('click', async () => {
                        const kegiatan = document.getElementById('kegiatanTextarea').value.trim();

                        if (kegiatan === '' || kegiatan.length < 20) {
                            alert('Anda harus memasukkan kegiatan minimal satu paragraf.');
                            return;
                        }

                        closeModal(); // Tutup modal

                        try {
                            const saveAttendanceResponse = await fetch('AttendanceController/saveAttendanceUP', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    action: 'saveAttendanceUP',
                                    date: formattedDate,
                                    ip: ipAddress,
                                    kegiatan: kegiatan,
                                    latitude: locationLat,
                                    longitude: locationLong,
                                    name: userName,
                                    timein: '',  
                                    timeot: formattedTime 
                                })
                            });

                            const responseData = await saveAttendanceResponse.json();
                            //  console.log(responseData);

                            if (saveAttendanceResponse.ok) {
                                alert(`Absensi baru berhasil disimpan untuk hari ini.\n${userName}\nKegiatan: ${kegiatan}\nSelamat istirahat!`);
                            } else {
                                throw new Error(responseData.message || 'Gagal menyimpan absensi baru.');
                            }
                        } catch (error) {
                            console.error('Error during save attendance:', error);
                            alert('Terjadi kesalahan saat menyimpan absensi.');
                        } finally {
                            loading = false;
                            document.getElementById('PulangButton').innerText = 'Pulang';
                        }
                    });
                }
            } catch (error) {
                console.error('Error during check-out:', error);
                alert('Terjadi kesalahan saat memeriksa absensi.');
            } finally {
                loading = false;
                document.getElementById('PulangButton').innerText = 'Pulang';
            }
        };

        // Fungsi untuk menutup modal
        function closeModal() {
            const modal = document.getElementById('kegiatanModal');
            const overlay = document.getElementById('modalOverlay');
            if (modal) modal.remove(); // Menghapus modal
            if (overlay) overlay.remove(); // Menghapus overlay
        }

 </script>

</body>
</html>







