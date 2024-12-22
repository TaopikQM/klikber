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
                const response = await fetch('<?= site_url('absen/user/getall') ?>'); // Replace with your API endpoint
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
                            <button onclick="handleDeleteUser('${user.id}')" class="bg-red-700 text-white px-4 py-2 rounded-md">Hapus</button>`}
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

            document.getElementById("totalUserCount").textContent = `Total User: ${filteredUsers.length}`;
            
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


 </script>

</body>
</html>




const fetchUsers = async () => {
    try {
        const response = await fetch('<?= site_url('absen/user/getall') ?>'); // Ganti dengan endpoint API Anda
        if (response.ok) {
            users = await response.json();
            totalItems = users.length;

            // Filter untuk tanggal hari ini
            const today = new Date().toISOString().split('T')[0]; // Format YYYY-MM-DD
            users = users.filter(user => user.date === today);

            renderUsers();
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
    const displayedUsers = filteredUsers.slice(start, start + itemsPerPage);

    userList.innerHTML = displayedUsers.map((user, index) => `
        <tr>
            <td class="py-2 px-4 text-left">${start + index + 1}</td>
            <td class="py-2 px-4 text-left">${user.name}</td>
            <td class="py-2 px-4 text-left">${user.date}</td>
            <td class="py-2 px-4 text-center">${user.jam_masuk}</td>
            <td class="py-2 px-4 text-center">${user.jam_keluar}</td>
            <td class="py-2 px-4 text-center">${user.keterangan}</td>
        </tr>
    `).join('');

    document.getElementById("totalUserCount").textContent = `Total User: ${filteredUsers.length}`;
};











<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <title>Attendance Table</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex justify-between items-center px-4 py-3">
                <div class="text-xl font-bold mb-4">Daftar Kehadiran</div>
            </div>

            <div class="flex justify-between items-center px-4 py-3">
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

            <div class="flex justify-between items-center px-4 py-3">
                <button class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 mr-2 mb-2 dark:text-gray-400">
                    <select id="filterBy" class="border rounded px-2 py-1">
                        <option value="">Select</option>
                        <option value="date">By Date</option>
                        <option value="name">By Name</option>
                    </select>
                </button>

                <div id="dateFilter" class="hidden">
                    <input type="date" id="selectedDate" class="border rounded px-2 py-1" />
                </div>

                <div id="nameFilter" class="hidden">
                    <select id="selectedName" class="border rounded px-2 py-1">
                        <option value="">Select</option>
                        <!-- Populate names dynamically -->
                    </select>
                    <select id="selectedMonth" class="border rounded px-2 py-1">
                        <option value="">Select Month</option>
                        {[...Array(12).keys()].map(month => (
                            `<option value="${month + 1}">${getMonthName(month + 1)}</option>`
                        )).join('')}
                    </select>
                    <select id="selectedYear" class="border rounded px-2 py-1">
                        <option value="">Select Year</option>
                        <!-- Year options -->
                        {getCurrentYearRange().map(year => (
                            `<option value="${year}">${year}</option>`
                        )).join('')}
                    </select>
                </div>

                <div class="flex">
                    <button id="exportPDF" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">PDF</button>
                    <button id="exportExcel" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">EXCEL</button>
                </div>
            </div>

            <table id="userTable" class="min-w-full bg-white border border-gray-300">
                <thead class="border">
                    <tr>
                        <th rowSpan="2" class="text-center md:p-4 p-0 border-r">
                            Nama
                            <button onClick="sortByName()" class="ml-2">
                                <span id="nameSortIndicator">↑</span>
                            </button>
                        </th>
                        <th rowSpan="2" class="text-center md:p-4 p-0 border-r">
                            Date
                            <button onClick="sortByDate()" class="ml-2">
                                <span id="dateSortIndicator">↑</span>
                            </button>
                        </th>
                        <th colSpan="2" class="text-center p-4 border border-t-0">
                            Jam Kerja
                        </th>
                        <th rowSpan="2" class="text-center md:p-4 p-0 border-r">
                            Keterangan
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center border border-t-0">Masuk</th>
                        <th class="text-center border border-t-0">Keluar</th>
                    </tr>
                </thead>
                <tbody id="userList"></tbody>
            </table>
            <nav id="pagination" class="m-4 flex items-center justify-between pt-4"></nav>
        </div>
    </div>

    <script>
        let users = [];
        let currentPage = 1;
        let itemsPerPage = 10;
        let totalItems = 0;
        let sortOrderName = 'asc';
        let sortOrderDate = 'asc';
        let searchTerm = '';

        document.addEventListener('DOMContentLoaded', () => {
            populateItemsPerPage();
            fetchUsers();
            document.getElementById('searchInput').addEventListener('input', handleSearch);
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
                const response = await fetch('<?= site_url('absen/user/getall') ?>'); // Replace with your API endpoint
                if (response.ok) {
                    users = await response.json();
                    totalItems = users.length;
                    renderUsers();
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
            const displayedUsers = filteredUsers.slice(start, start + itemsPerPage);

            userList.innerHTML = displayedUsers.map((user, index) => `
                <tr>
                    <td class="py-2 px-4 text-left">${start + index + 1}</td>
                    <td class="py-2 px-4 text-left">${user.name}</td>
                    <td class="py-2 px-4 text-left">${user.date}</td>
                    <td class="py-2 px-4 text-center">${user.jam_masuk}</td>
                    <td class="py-2 px-4 text-center">${user.jam_keluar}</td>
                    <td class="py-2 px-4 text-center">${user.keterangan}</td>
                </tr>
            `).join('');

            document.getElementById("totalUserCount").textContent = `Total User: ${filteredUsers.length}`;
        };

        const handleSearch = (e) => {
            searchTerm = e.target.value;
            currentPage = 1; // Reset to the first page on search change
            renderUsers();
        };

        const sortByName = () => {
            users.sort((a, b) => {
                const comparison = a.name.localeCompare(b.name);
                return sortOrderName === 'asc' ? comparison : -comparison;
            });
            sortOrderName = sortOrderName === 'asc' ? 'desc' : 'asc';
            document.getElementById('nameSortIndicator').textContent = sortOrderName === 'asc' ? '↑' : '↓';
            renderUsers();
        };

        const sortByDate = () => {
            users.sort((a, b) => {
                const dateA = new Date(a.date);
                const dateB = new Date(b.date);
                return sortOrderDate === 'asc' ? dateA - dateB : dateB - dateA;
            });
            sortOrderDate = sortOrderDate === 'asc' ? 'desc' : 'asc';
            document.getElementById('dateSortIndicator').textContent = sortOrderDate === 'asc' ? '↑' : '↓';
            renderUsers();
        };

        const handleItemsPerPageChange = (e) => {
            itemsPerPage = parseInt(e.target.value);
            currentPage = 1; // Reset to the first page
            renderUsers();
        };

        const renderPagination = () => {
            const pagination = document.getElementById('pagination');
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            pagination.innerHTML = `
                <button ${currentPage === 1 ? 'disabled' : ''} class="border rounded px-4 py-2" onclick="changePage(${currentPage - 1})">Previous</button>
                ${Array.from({length: totalPages}, (_, index) => `
                    <button ${currentPage === index + 1 ? 'disabled' : ''} class="border rounded px-4 py-2" onclick="changePage(${index + 1})">${index + 1}</button>
                `).join('')}
                <button ${currentPage === totalPages ? 'disabled' : ''} class="border rounded px-4 py-2" onclick="changePage(${currentPage + 1})">Next</button>
            `;
        };

        const changePage = (newPage) => {
            currentPage = newPage;
            renderUsers();
            renderPagination();
        };

        const getCurrentYearRange = () => {
            const currentYear = new Date().getFullYear();
            return Array.from({length: 11}, (_, index) => currentYear - 5 + index);
        };

        const getMonthName = (month) => {
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            return monthNames[month - 1];
        };
    </script>
</body>
</html>







<script type="text/babel">
        const { useState, useEffect } = React;
        const { jsPDF } = window.jspdf;
        const autoTable = window.jspdf.autoTable;
        

        const VdataAttendances = () => {
            const [registerData, setRegisterData] = useState([]);
            const [attendanceData, setAttendanceData] = useState([]);
            const [loading, setLoading] = useState(true);
            const [editUserId, setEditUserId] = useState(null);
            const [editUserTimeIn, setEditUserTimeIn] = useState('');
            const [editUserTimeOut, setEditUserTimeOut] = useState('');
            const [editUserKegiatan, setEditUserKegiatan] = useState('');
            const [filterBy, setFilterBy] = useState('');
            const [selectedDate, setSelectedDate] = useState('');
            const [selectedName, setSelectedName] = useState('');
            const [selectedNIM, setSelectedNIM] = useState('');
            const [selectedMonth, setSelectedMonth] = useState('');
            const [selectedYear, setSelectedYear] = useState('');
            const [selectedItems, setSelectedItems] = useState([]);
            const [itemsPerPage, setItemsPerPage] = useState(10);
            const [currentPage, setCurrentPage] = useState(1);
            const [sortOrderName, setSortOrderName] = useState('asc');
            const [sortOrderDate, setSortOrderDate] = useState('asc');
            const [showExcelButtons, setShowExcelButtons] = useState(false);
            const [filteredRegisterData, setFilteredRegisterData] = useState([]);
            const [searchDate, setSearchDate] = useState('');

            // Fungsi untuk mengurutkan berdasarkan nama
            const sortByName = () => {
                const sortedData = [...attendanceData].sort((a, b) => {
                    const comparison = a.name.localeCompare(b.name);
                    return sortOrderName === 'asc' ? comparison : -comparison;
                });
                setAttendanceData(sortedData);
                setSortOrderName(sortOrderName === 'asc' ? 'desc' : 'asc');
            };

            // Fungsi untuk mengurutkan berdasarkan tanggal
            const sortByDate = () => {
                const sortedData = [...attendanceData].sort((a, b) => {
                    const comparison = new Date(a.date) - new Date(b.date);
                    return sortOrderDate === 'asc' ? comparison : -comparison;
                });
                setAttendanceData(sortedData);
                setSortOrderDate(sortOrderDate === 'asc' ? 'desc' : 'asc');
            };

            // Ubah format tanggal
            const formatDate = (dateString) => {
                const date = new Date(dateString);
                return date.toISOString().split('T')[0];
            };

            // Menampilkan semua data register dan attendances
            useEffect(() => {
                const fetchData = async () => { 
                    try {
                        const response = await axios.post('AttendanceController/getAllData', {
                            action: 'getAllData'
                        });
                        setRegisterData(response.data.register);
                        setAttendanceData(response.data.attendance);
                        setLoading(false);
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    }
                };
                fetchData();
            }, []);

                // Selected edit dan save
            const handleEditId = (item) => {
                setEditUserId(item.id);
                setEditUserTimeIn(item.timein);
                setEditUserTimeOut(item.timeot);
                setEditUserKegiatan(item.kegiatan);
            };
 
            const handleSave = async (id) => {
                try {
                    await axios.post('AttendanceController/updateData', {
                        action: 'updateData',
                        id,
                        updatedData: {
                            timein: editUserTimeIn,
                            timeot: editUserTimeOut,
                            kegiatan: editUserKegiatan,
                        }
                    });
                    alert('Data berhasil diperbarui.');
                    // Refresh data after update
                    const response = await axios.post('AttendanceController/getAllData', { action: 'getAllData' });
                    setAttendanceData(response.data.attendance);
                    setEditUserId(null); // Reset edit mode
                } catch (error) {
                    console.error('Error updating data:', error);
                    alert('Gagal memperbarui data.');
                }
            };

            // Filter data 
            const handleFilterByChange = (e) => {
                setFilterBy(e.target.value);
                setSelectedDate(''); // Reset date selection
                setSelectedName(''); // Reset name selection
                setSelectedMonth(''); // Reset month selection
                setSelectedYear(''); // Reset year selection
            };

            const handleDateChange = (e) => {
                setSelectedDate(e.target.value);
                setSelectedMonth(''); // Reset month selection when filtering by date
                setSelectedYear('');
            };

            const handleNameChange = (e) => {
                setSelectedName(e.target.value);
            };

            const handleMonthChange = (e) => {
                setSelectedMonth(e.target.value);
                setSelectedDate('');
            };

            const handleYearChange = (e) => {
                setSelectedYear(e.target.value);
            };

            const handleItemsPerPageChange = (e) => {
                setItemsPerPage(e.target.value);
                setCurrentPage(1);
            };

            const getCurrentYearRange = () => {
                const currentYear = new Date().getFullYear();
                return Array.from({ length: 3 }, (_, i) => currentYear - i);
            };

            const getMonthName = (monthNumber) => {
                const date = new Date();
                date.setMonth(monthNumber - 1);
                return date.toLocaleString('default', { month: 'long' });
            };

            // Fungsi untuk menangani perubahan pada checkbox
            const handleCheckboxChange = (id) => {
                if (selectedItems.includes(id)) {
                    setSelectedItems(selectedItems.filter(item => item !== id)); // Hapus id dari daftar jika sudah ada
                } else {
                    setSelectedItems([...selectedItems, id]); // Tambah id ke daftar
                }
            };

            // Fungsi untuk memilih semua checkbox
            const handleSelectAll = (event) => {
                if (event.target.checked) {
                    const allIds = attendanceData.map(item => item.id);
                    setSelectedItems(allIds);
                } else {
                    setSelectedItems([]);
                }
            };

            const getDaysInMonth = (month, year) => {
                return new Date(year, month, 0).getDate();
            };

            
            const handleDeleteSelected = () => {
                if (selectedItems.length === 0) return; // Tidak melakukan apa-apa jika tidak ada yang dipilih
                    // Loop melalui setiap ID yang dipilih
                    selectedItems.forEach(id => {
                    const itemToDelete = filteredAttendanceData.find(item => item.id === id);
                    
                    // Tampilkan dialog konfirmasi untuk setiap item
                    const confirmed = window.confirm(`Yakin ingin menghapus data atas nama ${itemToDelete.name} pada tanggal ${formatDate(itemToDelete.date)}?`);
                    
                    if (confirmed) {
                        const confirmSecond = window.confirm("Apakah Anda benar-benar yakin ingin menghapus data ini?");
                        if (confirmSecond) {
                        // Hapus item jika dikonfirmasi
                        axios.post('AttendanceController/deleteItems', {
                            action: 'deleteItems',
                            ids: itemToDelete,//[id], // Hapus hanya satu item pada satu waktu
                        })
                        .then((response) => {
                            console.log(`Deleted: ${itemToDelete.name}`);
                            // Lakukan update data jika perlu
                            setAttendanceData(prevData => prevData.filter(item => item.id !== id)); // Perbarui data setelah penghapusan
                        })
                        .catch((error) => {
                            console.error('Error deleting item:', error);
                        }); 
                        }
                    }
                    }); 
                    // Reset selectedItems setelah proses selesai
                setSelectedItems([]);
                };


            const filteredAttendanceData = attendanceData.filter(item => {
            const itemDate = new Date(item.date);
            const formattedDate = formatDate(item.date);
            const dateMatch = selectedDate ? formattedDate === selectedDate : true;

            const itemMonth = itemDate.getMonth() + 1;
            const itemYear = itemDate.getFullYear();

            const monthMatch = selectedMonth ? itemMonth === parseInt(selectedMonth) : true;
            const yearMatch = selectedYear ? itemYear === parseInt(selectedYear) : true;

            const nameMatch = selectedName ? item.name === selectedName : true;

            return dateMatch && monthMatch && yearMatch && nameMatch;
        });

        const generateDatesInMonth = (month, year) => {
            const daysInMonth = getDaysInMonth(month, year);
            const dates = [];
            for (let day = 1; day <= daysInMonth; day++) {
                dates.push(`${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`);
            }
            return dates;
        };

        // PDF Export Function
        const exportToPDF = async () => {
            const doc = new jsPDF();
            doc.setFontSize(12);
            const marginTop = 10;
            const marginLeft = 10;

            try {
                const response = await fetch('/sekrup/harta/assets/jateng.png');
                const blob = await response.blob();
                
                const reader = new FileReader();
                reader.onloadend = function () {
                    const base64data = reader.result;

                    doc.addImage(base64data, 'PNG', marginLeft, marginTop - 5, 20, 20);

                    // Title and Subtitle 
                    doc.setFontSize(13);
                    doc.text('DINAS KOMUNIKASI DAN INFORMATIKA PROVINSI JAWA TENGAH', doc.internal.pageSize.getWidth() / 2, marginTop, { align: 'center' });
                    doc.setFontSize(12);
                    doc.text('SUBBAG UMUM DAN KEPEGAWAIAN', doc.internal.pageSize.getWidth() / 2, marginTop + 7, { align: 'center' });

                    // Footer
                    doc.setFontSize(10);
                    if (filterBy === 'name') {
                        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        const selectedMonthName = monthNames[parseInt(selectedMonth) - 1] || '';
                        doc.text(`Nama: ${selectedName}`, marginLeft, marginTop + 19);
                        doc.text(`Bulan: ${selectedMonthName} ${selectedYear}`, marginLeft, marginTop + 23);
                    } else if (filterBy === 'date') {
                        const formattedDate = new Date(selectedDate).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                        });
                        doc.text(`Tanggal: ${formattedDate}`, marginLeft, marginTop + 19);
                        doc.text(`Total Data: ${filteredAttendanceData.length}`, marginLeft, marginTop + 23);
                    } else {
                        alert('Tidak ada data untuk diekspor.');
                        return;
                    }

                    // Table based on filter
                    if (filterBy === 'name') {
                        const datesInMonth = generateDatesInMonth(parseInt(selectedMonth), parseInt(selectedYear));

                        autoTable(doc, {
                            startY: marginTop + 30,
                            head: [
                                [
                                    { content: 'No', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Date', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Jam', colSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Kegiatan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                ],
                                [
                                    { content: 'Datang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Pulang', styles: { halign: 'center', valign: 'middle' } },
                                ]
                            ],
                            body: datesInMonth.map((date, index) => {
                                const attendanceOnDate = filteredAttendanceData.find(item => formatDate(item.date) === date);
                                return [
                                    index + 1,
                                    date,
                                    attendanceOnDate ? attendanceOnDate.timein : '-',
                                    attendanceOnDate ? attendanceOnDate.timeot : '-',
                                    attendanceOnDate ? attendanceOnDate.kegiatan : '-'
                                ];
                            }),
                            styles: { fontSize: 10, halign: 'left', valign: 'middle', cellPadding: 1.6, lineWidth: 0.2, lineColor: [0, 0, 0] },
                            theme: 'grid',
                            tableWidth: 'auto',
                            margin: marginTop,
                            headStyles: { fillColor: [86, 156, 214] },
                            tableLineColor: [0, 0, 0],
                            tableLineWidth: 0.2,
                        });

                    } else if (filterBy === 'date') {
                        autoTable(doc, {
                            startY: marginTop + 27,
                            head: [
                                [
                                    { content: 'No', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Nama', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Date', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Jam', colSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Kegiatan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                ],
                                [
                                    { content: 'Datang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Pulang', styles: { halign: 'center', valign: 'middle' } },
                                ]
                            ],
                            body: filteredAttendanceData.map((item, index) => [
                                index + 1,
                                item.name,
                                formatDate(item.date),
                                item.timein,
                                item.timeot,
                                item.kegiatan
                            ]),
                            styles: { fontSize: 10, halign: 'left', valign: 'middle', cellPadding: 1.6, lineWidth: 0.2, lineColor: [0, 0, 0] },
                            theme: 'grid',
                            tableWidth: 'auto',
                            margin: marginTop,
                            headStyles: { fillColor: [86, 156, 214] },
                            tableLineColor: [0, 0, 0],
                            tableLineWidth: 0.2,
                        });

                    } else if (filterBy === '') {
                        alert('Tidak ada data untuk diekspor.');
                        return;
                    }

                    // Download PDF
                    doc.save('attendance_report.pdf');
                };
                reader.readAsDataURL(blob);
            } catch (error) {
                console.error('Error fetching image:', error);
            }
        };

        // Excel Export Functionality
        const handleExport = () => {
            setShowExcelButtons(true);
            const month = parseInt(selectedMonth, 10);
            const year = parseInt(selectedYear, 10);

            if (!month || !year) {
                alert("Please select both month and year");
                return;
            }

            exportToOnlineSpreadsheet(month, year);
            setShowExcelButtons(false);
        };

        const handleBatalExport = () => {
            setShowExcelButtons(false);
        };

        const exportToOnlineSpreadsheet = (selectedMonth, selectedYear) => {
            const workbook = XLSX.utils.book_new();
            const startDate = new Date(selectedYear, selectedMonth - 1, 1);
            const endDate = new Date(selectedYear, selectedMonth, 0);

            const dateRange = [];
            for (let date = new Date(startDate); date <= endDate; date.setDate(date.getDate() + 1)) {
                dateRange.push(new Date(date));
            }

            const uniqueNames = [...new Set(attendanceData.map(item => item.name))];

            const summaryData = dateRange.map(date => {
                const formattedDate = date.toISOString().split('T')[0];
                const totalPresent = attendanceData.filter(att => att.date === formattedDate).length;

                return {
                    Date: formattedDate,
                    TotalRegistered: registerData.length,
                    TotalPresent: totalPresent,
                    TotalAbsent: registerData.length - totalPresent,
                };
            });

            const totalSheet = XLSX.utils.json_to_sheet(summaryData);
            const headers = Object.keys(summaryData[0]);
            const colWidths = headers.map(header => ({ wch: header.length + 5 }));
            totalSheet['!cols'] = colWidths;
            XLSX.utils.book_append_sheet(workbook, totalSheet, 'Attendance Summary');

            uniqueNames.forEach(name => {
                const monthlyAttendanceData = dateRange.map(date => {
                    const formattedDate = date.toISOString().split('T')[0];
                    const dailyAttendance = attendanceData.filter(att => {
                        const date = new Date(att.date).toISOString().split('T')[0];
                        return att.name === name && date === formattedDate;
                    });
                    return {
                        date: formattedDate,
                        timein: dailyAttendance.length > 0 ? dailyAttendance[0].timein : 'N/A',
                        timeot: dailyAttendance.length > 0 ? dailyAttendance[0].timeot : 'N/A',
                        kegiatan: dailyAttendance.length > 0 ? dailyAttendance[0].kegiatan : 'N/A',
                    };
                });

                const monthlySheet = XLSX.utils.json_to_sheet(monthlyAttendanceData);
                const headersMonthly = Object.keys(monthlyAttendanceData[0]);
                const colWidthsMonthly = headersMonthly.map(header => ({ wch: header.length + 5 }));
                monthlySheet['!cols'] = colWidthsMonthly;
                XLSX.utils.book_append_sheet(workbook, monthlySheet, name);
            });

            const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
            const blob = new Blob([excelBuffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'Attendance_Report.xlsx');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };

        // Pagination Logic
        const totalItems = filteredAttendanceData.length;
        const indexOfLastAttendance = currentPage * itemsPerPage;
        const indexOfFirstAttendance = indexOfLastAttendance - itemsPerPage;
        const displayedAttendanceData = filteredAttendanceData.slice(indexOfFirstAttendance, indexOfLastAttendance);
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        const getPagination = () => {
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



            return (
                // <div>
                //     <h1>Data Attendances</h1>
                //     {/* Tambahkan komponen dan UI lainnya di sini */}
                // </div>
                <div>
                    <div className="relative overflow-x-auto shadow-md sm:rounded-lg ">
                        <div className="flex justify-between items-center px-4 py-3 ">
                            <h2 className="text-xl font-bold mb-4">Laporan Data</h2>
                            <div className="relative ">
                                {filterBy === 'date' && (
                                    <button onClick={exportToPDF} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        PDF
                                    </button>
                                )}
                                {filterBy === 'name' && (
                                    <button onClick={exportToPDF} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        PDF
                                    </button>
                                )}
                                {!showExcelButtons && (
                                    <button id="exportBtn" onClick={handleExport} type="button" className="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">EXCEL</button>
                                )}
                                {showExcelButtons && (
                                    <div>
                                        <button onClick={handleBatalExport} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                            Batal
                                        </button>
                                        <button id="exportBtn" onClick={handleExport} type="button" className="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                            EXCEL
                                        </button>
                                    </div>
                                )}
                                {showExcelButtons && (
                                    <div>
                                        <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                            <select value={selectedMonth} onChange={handleMonthChange}>
                                                <option value="">Select</option>
                                                {[...Array(12).keys()].map(month => (
                                                    <option key={month + 1} value={month + 1}>{getMonthName(month + 1)}</option>
                                                ))}
                                            </select>
                                        </button>
                                        <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                            <select value={selectedYear} onChange={handleYearChange}>
                                                <option value="">Select Year</option>
                                                {getCurrentYearRange().map(year => (
                                                    <option key={year} value={year}>{year}</option>
                                                ))}
                                            </select>
                                        </button>
                                    </div>
                                )}
                            </div>
                        </div>

                        <div className="flex justify-between items-center px-4 py-3 ">
                            <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 mr-2 mb-2 dark:text-gray-400 '>
                                <select value={filterBy} onChange={handleFilterByChange}>
                                    <option value="">Select</option>
                                    <option value="date">By Date</option>
                                    <option value="name">By Name</option>
                                </select>
                            </button>

                            {filterBy === 'date' && (
                                <div>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                        <input type="date" value={selectedDate} onChange={handleDateChange} />
                                    </button>
                                </div>
                            )}

                            {filterBy === 'name' && (
                                <div>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:text-gray-400'>
                                        <select value={selectedName} onChange={handleNameChange}>
                                            <option value="">Select</option>
                                            {Array.from(new Set(attendanceData.map(item => item.name)))
                                                .sort()
                                                .map((name, index) => (
                                                    <option key={index} value={name}>{name}</option>
                                                ))}
                                        </select>
                                    </button>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                        <select value={selectedMonth} onChange={handleMonthChange}>
                                            <option value="">Select</option>
                                            {[...Array(12).keys()].map(month => (
                                                <option key={month + 1} value={month + 1}>{getMonthName(month + 1)}</option>
                                            ))}
                                        </select>
                                    </button>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                        <select value={selectedYear} onChange={handleYearChange}>
                                            <option value="">Select Year</option>
                                            {getCurrentYearRange().map(year => (
                                                <option key={year} value={year}>{year}</option>
                                            ))}
                                        </select>
                                    </button>
                                </div>
                            )}
                        </div>

                        <div className="flex justify-between  px-4 py-3">
                            <div className="flex">
                                <select
                                    id="itemsPerPage"
                                    value={itemsPerPage}
                                    onChange={handleItemsPerPageChange}
                                    className="border rounded px-2 py-1"
                                >
                                    <option value={10}>10</option>
                                    <option value={25}>25</option>
                                    <option value={50}>50</option>
                                    <option value={100}>100</option>
                                </select>
                                <div className="mt-4 text-sm text-gray-500">
                                    Total User: {filteredAttendanceData.length}
                                </div>
                            </div>
                        </div>
                        <br />
                        
                        {selectedItems.length > 0 && (
                            <React.Fragment>
                                <button
                                    onClick={handleDeleteSelected}
                                    className="bg-red-600 text-white px-4 py-2 rounded mb-4"
                                >
                                    Delete Selected
                                </button>
                            </React.Fragment>
                        )}
                        
                        <table className="w-full text-sm bg-white border border-gray-300 rounded-lg">
                            <thead>
                                <tr style={{ backgroundColor: "#569cd6" }} className="border-gray-400 text-white font-bold h-10 ">
                                    
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Nama
                                        <button onClick={sortByName} className="ml-2">
                                            {sortOrderName === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Date
                                        <button onClick={sortByDate} className="ml-2">
                                            {sortOrderDate === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                    <th colSpan={2} className="text-center p-4 border border-t-0">
                                        Jam Kerja
                                    </th>
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Keterangan
                                    </th>
                                </tr>
                                <tr>
                                    <th className="text-center border border-t-0">Masuk</th>
                                    <th className="text-center border border-t-0">Keluar</th>
                                </tr>
                            </thead>
                            <tbody>
                                {filteredAttendanceData.slice(0, itemsPerPage).map((data) => (
                                    <tr key={data.id} className="border-b hover:bg-gray-100">
                                        
                                        <td className="text-center md:p-4 p-0 border-r">{data.name}</td>
                                        <td className="text-center md:p-4 p-0 border-r">{data.date}</td>
                                        <td className="text-center md:p-4 p-0 border-r">{data.timein}</td>
                                        <td className="text-center md:p-4 p-0 border-r">{data.timeot}</td>
                                        <td className="text-center md:p-4 p-0 border-r">{data.kegiatan}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>

                       
                    </div>
                </div>

            );
        };

        // ReactDOM.render(<VdataAttendances />, document.getElementById('root'));
        const root = ReactDOM.createRoot(document.getElementById('root'));
        root.render(<VdataAttendances />);

    </script>



    <script>
        // const axios = require('axios');
        // const jsPDF = require('jspdf').jsPDF;
        // const autoTable = require('jspdf-autotable');
        // const XLSX = require('xlsx');

        let registerData = [];
        let attendanceData = [];
        // let loading = true;

        // let editUserId = null;
        let editUserTimeIn = '';
        let editUserTimeOut = '';
        let editUserKegiatan = '';

        let filterBy = '';
        let selectedDate = '';
        let selectedName = '';

        let selectedNIM = '';
        let selectedMonth = '';
        let selectedYear = '';
        let selectedItems = [];
        let itemsPerPage = 10;
        let currentPage = 1;
        let sortOrderName = 'asc'; // State untuk urutan nama
        let sortOrderDate = 'asc'; // State untuk urutan tanggal

        let showExcelButtons = false;

        let filteredRegisterData = [];

        // Fungsi untuk mengurutkan berdasarkan nama
        const sortByName = () => {
            const sortedData = [...attendanceData].sort((a, b) => {
                const comparison = a.name.localeCompare(b.name);
                return sortOrderName === 'asc' ? comparison : -comparison;
            });
            attendanceData = sortedData;
            sortOrderName = sortOrderName === 'asc' ? 'desc' : 'asc'; // Toggle urutan
        };

        // Fungsi untuk mengurutkan berdasarkan tanggal
        const sortByDate = () => {
            const sortedData = [...attendanceData].sort((a, b) => {
                const comparison = new Date(a.date) - new Date(b.date);
                return sortOrderDate === 'asc' ? comparison : -comparison;
            });
            attendanceData = sortedData;
            sortOrderDate = sortOrderDate === 'asc' ? 'desc' : 'asc'; // Toggle urutan
        };

        // Ubah format tanggal
        const formatDate = (dateString) => {
            const date = new Date(dateString);
            return date.toISOString().split('T')[0]; // Format YYYY-MM-DD
        };

        // Menampilkan semua data register dan attendances
        const fetchData = async () => {
            try {
                const response = await axios.post('/api/attendances', {
                    action: 'getAllData'
                });
                registerData = response.data.register;
                attendanceData = response.data.attendance;
                loading = false;

                // filterTodayData(response.data.register, response.data.attendance);
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        };

        // Panggil fetchData untuk memulai pengambilan data
        fetchData();

        // Selected edit and save
        const handleEditId = (item) => {
            editUserId = item.id;
            editUserTimeIn = item.timein;
            editUserTimeOut = item.timeot;
            editUserKegiatan = item.kegiatan;
        };

        const handleSave = async (id) => {
            try {
                await axios.post('/api/attendances', {
                    action: 'updateData',
                    id,
                    updatedData: {
                        timein: editUserTimeIn,
                        timeot: editUserTimeOut,
                        kegiatan: editUserKegiatan,
                    }
                });
                alert('Data berhasil diperbarui.');
                // Refresh data after update
                const response = await axios.post('/api/attendances', { action: 'getAllData' });
                attendanceData = response.data.attendance;
                editUserId = null; // Reset edit mode
            } catch (error) {
                console.error('Error updating data:', error);
                alert('Gagal memperbarui data.');
            }
        };

        // Filter data
        const handleFilterByChange = (e) => {
            filterBy = e.target.value;
            selectedDate = ''; // Reset date selection
            selectedName = ''; // Reset name selection
            selectedMonth = ''; // Reset month selection
            selectedYear = ''; // Reset year selection
        };

        const handleDateChange = (e) => {
            selectedDate = e.target.value;
            selectedMonth = ''; // Reset month selection when filtering by date
            selectedYear = '';
        };

        const handleNameChange = (e) => {
            selectedName = e.target.value;
        };

        const handleMonthChange = (e) => {
            selectedMonth = e.target.value;
            selectedDate = '';
        };

        const handleYearChange = (e) => {
            selectedYear = e.target.value;
        };

        const handleItemsPerPageChange = (e) => {
            itemsPerPage = e.target.value;
            currentPage = 1; 
        };

        const getCurrentYearRange = () => {
            const currentYear = new Date().getFullYear();
            return Array.from({ length: 3 }, (_, i) => currentYear - i);
        };

        const getMonthName = (monthNumber) => {
            const date = new Date();
            date.setMonth(monthNumber - 1);
            return date.toLocaleString('default', { month: 'long' });
        };

        // Fungsi untuk menangani perubahan pada checkbox
        const handleCheckboxChange = (id) => {
            if (selectedItems.includes(id)) {
                selectedItems = selectedItems.filter(item => item !== id); // Hapus id dari daftar jika sudah ada
            } else {
                selectedItems.push(id); // Tambah id ke daftar
            }
        };

        // Fungsi untuk memilih semua checkbox
        const handleSelectAll = (event) => {
            if (event.target.checked) {
                const allIds = attendanceData.map(item => item.id);
                selectedItems = allIds;
            } else {
                selectedItems = [];
            }
        };

        const getDaysInMonth = (month, year) => {
            return new Date(year, month, 0).getDate();
        };

        // Fungsi untuk menghapus item yang dipilih
        const handleDeleteSelected = () => {
            if (selectedItems.length === 0) return; // Tidak melakukan apa-apa jika tidak ada yang dipilih
            // Loop melalui setiap ID yang dipilih
            selectedItems.forEach(id => {
                const itemToDelete = filteredAttendanceData.find(item => item.id === id);
                
                // Tampilkan dialog konfirmasi untuk setiap item
                const confirmed = window.confirm(`Yakin ingin menghapus data atas nama ${itemToDelete.name} pada tanggal ${formatDate(itemToDelete.date)}?`);
                
                if (confirmed) {
                    const confirmSecond = window.confirm("Apakah Anda benar-benar yakin ingin menghapus data ini?");
                    if (confirmSecond) {
                        // Hapus item jika dikonfirmasi
                        axios.post('/api/attendances', {
                            action: 'deleteItems',
                            ids: [id], // Hapus hanya satu item pada satu waktu
                        })
                        .then((response) => {
                            console.log(`Deleted: ${itemToDelete.name}`);
                            // Lakukan update data jika perlu
                            attendanceData = attendanceData.filter(item => item.id !== id); // Perbarui data setelah penghapusan
                        })
                        .catch((error) => {
                            console.error('Error deleting item:', error);
                        });
                    }
                }
            }); 
            // Reset selectedItems setelah proses selesai
            selectedItems = [];
        };

        const filteredAttendanceData = attendanceData.filter(item => {
            const itemDate = new Date(item.date); // Pastikan kita mendapatkan objek Date
            const formattedDate = formatDate(item.date); // Pastikan format item.date sama dengan selectedDate
            const dateMatch = selectedDate ? formattedDate === selectedDate : true; // Memfilter berdasarkan selectedDate

            const itemMonth = itemDate.getMonth() + 1; // Mendapatkan bulan (1-12)
            const itemYear = itemDate.getFullYear(); // Mendapatkan tahun

            // Cek filter berdasarkan bulan dan tahun
            const monthMatch = selectedMonth ? itemMonth === parseInt(selectedMonth) : true;
            const yearMatch = selectedYear ? itemYear === parseInt(selectedYear) : true;

            // Jika filter berdasarkan nama
            const nameMatch = selectedName ? item.name === selectedName : true;

            // Kembalikan true hanya jika semua kondisi terpenuhi
            return dateMatch && monthMatch && yearMatch && nameMatch;
        });

        const generateDatesInMonth = (month, year) => {
            const daysInMonth = getDaysInMonth(month, year);
            const dates = [];
            for (let day = 1; day <= daysInMonth; day++) {
                dates.push(`${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`);
            }
            return dates;
        };

        const exportToPDF = async () => {
            const doc = new jsPDF();
            const marginTop = 10;
            const marginLeft = 10;

            try {
                const response = await fetch('/assets/jateng.png');
                if (!response.ok) throw new Error('Image fetch failed');
                const blob = await response.blob();
                const reader = new FileReader();
                
                reader.onloadend = function () {
                    const base64data = reader.result;

                    // Add image
                    doc.addImage(base64data, 'PNG', marginLeft, marginTop - 5, 20, 20);

                    // Header
                    doc.setFontSize(13);
                    doc.text('DINAS KOMUNIKASI DAN INFORMATIKA PROVINSI JAWA TENGAH', doc.internal.pageSize.getWidth() / 2, marginTop, { align: 'center' });
                    doc.setFontSize(12);
                    doc.text('SUBBAG UMUM DAN KEPEGAWAIAN', doc.internal.pageSize.getWidth() / 2, marginTop + 7, { align: 'center' });

                    // Footer
                    doc.setFontSize(10);
                    if (filterBy === 'name') {
                        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        const selectedMonthName = monthNames[parseInt(selectedMonth) - 1] || '';
                        doc.text(`Nama: ${selectedName}`, marginLeft, marginTop + 19);
                        doc.text(`Bulan: ${selectedMonthName} ${selectedYear}`, marginLeft, marginTop + 23);
                    } else if (filterBy === 'date') {
                        const formattedDate = new Date(selectedDate).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
                        doc.text(`Tanggal: ${formattedDate}`, marginLeft, marginTop + 19);
                        doc.text(`Total Data: ${filteredAttendanceData.length}`, marginLeft, marginTop + 23);
                    } else {
                        alert('Tidak ada data untuk diekspor.');
                        return;
                    }

                    // Table generation based on filter
                    if (filterBy === 'name') {
                        const datesInMonth = generateDatesInMonth(parseInt(selectedMonth), parseInt(selectedYear));
                        autoTable(doc, {
                            startY: marginTop + 30,
                            head: [
                                [
                                    { content: 'No', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Date', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Jam', colSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Kegiatan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                ],
                                [
                                    { content: 'Datang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Pulang', styles: { halign: 'center', valign: 'middle' } },
                                ]
                            ],
                            body: datesInMonth.map((date, index) => {
                                const attendanceOnDate = filteredAttendanceData.find(item => formatDate(item.date) === date);
                                return [
                                    index + 1,
                                    date,
                                    attendanceOnDate ? attendanceOnDate.timein : '-',
                                    attendanceOnDate ? attendanceOnDate.timeot : '-',
                                    attendanceOnDate ? attendanceOnDate.kegiatan : '-'
                                ];
                            }),
                            styles: {
                                fontSize: 10,
                                halign: 'left',
                                valign: 'middle',
                                cellPadding: 1.6,
                                lineWidth: 0.2,
                                lineColor: [0, 0, 0],
                            },
                            theme: 'grid',
                            tableWidth: 'auto',
                            headStyles: { fillColor: [86, 156, 214] },
                            tableLineColor: [0, 0, 0],
                            tableLineWidth: 0.2,
                        });
                    } else if (filterBy === 'date') {
                        autoTable(doc, {
                            startY: marginTop + 27,
                            head: [
                                [
                                    { content: 'No', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Nama', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Date', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Jam', colSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Kegiatan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                ],
                                [
                                    { content: 'Datang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Pulang', styles: { halign: 'center', valign: 'middle' } },
                                ]
                            ],
                            body: filteredAttendanceData.map((item, index) => [
                                index + 1,
                                item.name,
                                formatDate(item.date),
                                item.timein,
                                item.timeot,
                                item.kegiatan
                            ]),
                            styles: {
                                fontSize: 10,
                                halign: 'left',
                                valign: 'middle',
                                cellPadding: 1.6,
                                lineWidth: 0.2,
                                lineColor: [0, 0, 0],
                            },
                            theme: 'grid',
                            tableWidth: 'auto',
                            headStyles: { fillColor: [86, 156, 214] },
                            tableLineColor: [0, 0, 0],
                            tableLineWidth: 0.2,
                        });
                    } else {
                        alert('Tidak ada data untuk diekspor.');
                        return;
                    }

                    // Download PDF
                    doc.save('attendance_report.pdf');
                };
                reader.readAsDataURL(blob);
            } catch (error) {
                console.error('Error fetching image:', error);
                alert('Gagal memuat gambar. Silakan coba lagi.');
            }
        };

        // Handle export button click
        const handleExport = () => {
            setShowExcelButtons(true);
            // Check the selected month and year before proceeding
            const month = parseInt(selectedMonth, 10);
            const year = parseInt(selectedYear, 10);

            // Ensure both values are filled
            if (!month || !year) {
                alert("Please select both month and year");
                return;
            }
            // Call the export function with the selected month and year
            exportToOnlineSpreadsheet(month, year);
            setShowExcelButtons(false);
        };

        // Handle canceling the export
        const handleBatalExport = () => {
            setShowExcelButtons(false);
        };

        // Function to export data to an online spreadsheet
        const exportToOnlineSpreadsheet = (selectedMonth, selectedYear) => {
            const workbook = XLSX.utils.book_new(); // Create a new workbook

            // Get the start and end date for the selected month and year
            const startDate = new Date(selectedYear, selectedMonth - 1, 1);
            const endDate = new Date(selectedYear, selectedMonth, 0); // Last day of the month

            // Generate an array of dates for the selected month
            const dateRange = [];
            for (let date = new Date(startDate); date <= endDate; date.setDate(date.getDate() + 1)) {
                dateRange.push(new Date(date));
            }

            // Extract unique names from the attendance data
            const uniqueNames = [...new Set(attendanceData.map(item => item.name))];

            // Prepare data for the summary sheet
            const summaryData = dateRange.map(date => {
                const formattedDate = date.toISOString().split('T')[0]; // Format date as YYYY-MM-DD
                const totalPresent = attendanceData.filter(att => att.date === formattedDate).length;

                return {
                    Date: formattedDate,
                    TotalRegistered: registerData.length,
                    TotalPresent: totalPresent,
                    TotalAbsent: registerData.length - totalPresent,
                };
            });

            const totalSheet = XLSX.utils.json_to_sheet(summaryData);

            // Automatically set column width (auto-width)
            const headers = Object.keys(summaryData[0]);
            const colWidths = headers.map(header => ({ wch: header.length + 5 })); // Adjust column width based on header length

            // Adding column widths
            totalSheet['!cols'] = colWidths;

            XLSX.utils.book_append_sheet(workbook, totalSheet, 'Attendance Summary');

            // Create a separate sheet for each unique name
            uniqueNames.forEach(name => {
                // Prepare the monthly attendance data for the current name
                const monthlyAttendanceData = dateRange.map(date => {
                    const formattedDate = date.toISOString().split('T')[0]; // Format date as YYYY-MM-DD
                    const dailyAttendance = attendanceData.filter(att => {
                        const date = new Date(att.date).toISOString().split('T')[0]; // Extract just the date part
                        return att.name === name && date === formattedDate;
                    });
                    // Create an entry for each date
                    return {
                        date: formattedDate,
                        timein: dailyAttendance.length > 0 ? dailyAttendance[0].timein : 'N/A', // Show time in if present
                        timeot: dailyAttendance.length > 0 ? dailyAttendance[0].timeot : 'N/A', // Show time out if present
                        kegiatan: dailyAttendance.length > 0 ? dailyAttendance[0].kegiatan : 'N/A', // Show activity if present
                    };
                });

                // Convert monthly attendance data to sheet format
                const monthlySheet = XLSX.utils.json_to_sheet(monthlyAttendanceData);
                // Set column width based on header length (for each sheet)
                const headersMonthly = Object.keys(monthlyAttendanceData[0]);
                const colWidthsMonthly = headersMonthly.map(header => ({
                    wch: header.length + 5
                }));
                monthlySheet['!cols'] = colWidthsMonthly;

                XLSX.utils.book_append_sheet(workbook, monthlySheet, name); // Use name as sheet name
            });

            // Save workbook as data URL
            const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
            const blob = new Blob([excelBuffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            const url = window.URL.createObjectURL(blob);

            // Create a link to download the spreadsheet
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'Attendance_Report.xlsx');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };

    </script>







    <!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>React App with CDN</title>
    <script src="https://unpkg.com/react/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/babel-standalone/babel.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.10/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
</head>
<body>
    <div id="root"></div>
    <script type="text/babel">
        const { useState, useEffect } = React;
        const { jsPDF } = window.jspdf;
        const autoTable = window.jspdf.autoTable;
        

        const VdataAttendances = () => {
            const [registerData, setRegisterData] = useState([]);
            const [attendanceData, setAttendanceData] = useState([]);
            const [loading, setLoading] = useState(true);
            const [editUserId, setEditUserId] = useState(null);
            const [editUserTimeIn, setEditUserTimeIn] = useState('');
            const [editUserTimeOut, setEditUserTimeOut] = useState('');
            const [editUserKegiatan, setEditUserKegiatan] = useState('');
            const [filterBy, setFilterBy] = useState('');
            const [selectedDate, setSelectedDate] = useState('');
            const [selectedName, setSelectedName] = useState('');
            const [selectedNIM, setSelectedNIM] = useState('');
            const [selectedMonth, setSelectedMonth] = useState('');
            const [selectedYear, setSelectedYear] = useState('');
            const [selectedItems, setSelectedItems] = useState([]);
            const [itemsPerPage, setItemsPerPage] = useState(10);
            const [currentPage, setCurrentPage] = useState(1);
            const [sortOrderName, setSortOrderName] = useState('asc');
            const [sortOrderDate, setSortOrderDate] = useState('asc');
            const [showExcelButtons, setShowExcelButtons] = useState(false);
            const [filteredRegisterData, setFilteredRegisterData] = useState([]);
            const [searchDate, setSearchDate] = useState('');

            // Fungsi untuk mengurutkan berdasarkan nama
            const sortByName = () => {
                const sortedData = [...attendanceData].sort((a, b) => {
                    const comparison = a.name.localeCompare(b.name);
                    return sortOrderName === 'asc' ? comparison : -comparison;
                });
                setAttendanceData(sortedData);
                setSortOrderName(sortOrderName === 'asc' ? 'desc' : 'asc');
            };

            // Fungsi untuk mengurutkan berdasarkan tanggal
            const sortByDate = () => {
                const sortedData = [...attendanceData].sort((a, b) => {
                    const comparison = new Date(a.date) - new Date(b.date);
                    return sortOrderDate === 'asc' ? comparison : -comparison;
                });
                setAttendanceData(sortedData);
                setSortOrderDate(sortOrderDate === 'asc' ? 'desc' : 'asc');
            };

            // Ubah format tanggal
            const formatDate = (dateString) => {
                const date = new Date(dateString);
                return date.toISOString().split('T')[0];
            };

            // Menampilkan semua data register dan attendances
            useEffect(() => {
                const fetchData = async () => { 
                    try {
                        const response = await axios.post('AttendanceController/getAllData', {
                            action: 'getAllData'
                        });
                        setRegisterData(response.data.register);
                        setAttendanceData(response.data.attendance);
                        setLoading(false);
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    }
                };
                fetchData();
            }, []);

                // Selected edit dan save
            const handleEditId = (item) => {
                setEditUserId(item.id);
                setEditUserTimeIn(item.timein);
                setEditUserTimeOut(item.timeot);
                setEditUserKegiatan(item.kegiatan);
            };
 
            const handleSave = async (id) => {
                try {
                    await axios.post('AttendanceController/updateData', {
                        action: 'updateData',
                        id,
                        updatedData: {
                            timein: editUserTimeIn,
                            timeot: editUserTimeOut,
                            kegiatan: editUserKegiatan,
                        }
                    });
                    alert('Data berhasil diperbarui.');
                    // Refresh data after update
                    const response = await axios.post('AttendanceController/getAllData', { action: 'getAllData' });
                    setAttendanceData(response.data.attendance);
                    setEditUserId(null); // Reset edit mode
                } catch (error) {
                    console.error('Error updating data:', error);
                    alert('Gagal memperbarui data.');
                }
            };

            // Filter data 
            const handleFilterByChange = (e) => {
                setFilterBy(e.target.value);
                setSelectedDate(''); // Reset date selection
                setSelectedName(''); // Reset name selection
                setSelectedMonth(''); // Reset month selection
                setSelectedYear(''); // Reset year selection
            };

            const handleDateChange = (e) => {
                setSelectedDate(e.target.value);
                setSelectedMonth(''); // Reset month selection when filtering by date
                setSelectedYear('');
            };

            const handleNameChange = (e) => {
                setSelectedName(e.target.value);
            };

            const handleMonthChange = (e) => {
                setSelectedMonth(e.target.value);
                setSelectedDate('');
            };

            const handleYearChange = (e) => {
                setSelectedYear(e.target.value);
            };

            const handleItemsPerPageChange = (e) => {
                setItemsPerPage(e.target.value);
                setCurrentPage(1);
            };

            const getCurrentYearRange = () => {
                const currentYear = new Date().getFullYear();
                return Array.from({ length: 3 }, (_, i) => currentYear - i);
            };

            const getMonthName = (monthNumber) => {
                const date = new Date();
                date.setMonth(monthNumber - 1);
                return date.toLocaleString('default', { month: 'long' });
            };

            // Fungsi untuk menangani perubahan pada checkbox
            const handleCheckboxChange = (id) => {
                if (selectedItems.includes(id)) {
                    setSelectedItems(selectedItems.filter(item => item !== id)); // Hapus id dari daftar jika sudah ada
                } else {
                    setSelectedItems([...selectedItems, id]); // Tambah id ke daftar
                }
            };

            // Fungsi untuk memilih semua checkbox
            const handleSelectAll = (event) => {
                if (event.target.checked) {
                    const allIds = attendanceData.map(item => item.id);
                    setSelectedItems(allIds);
                } else {
                    setSelectedItems([]);
                }
            };

            const getDaysInMonth = (month, year) => {
                return new Date(year, month, 0).getDate();
            };

            
            const handleDeleteSelected = () => {
                if (selectedItems.length === 0) return; // Tidak melakukan apa-apa jika tidak ada yang dipilih
                    // Loop melalui setiap ID yang dipilih
                    selectedItems.forEach(id => {
                    const itemToDelete = filteredAttendanceData.find(item => item.id === id);
                    
                    // Tampilkan dialog konfirmasi untuk setiap item
                    const confirmed = window.confirm(`Yakin ingin menghapus data atas nama ${itemToDelete.name} pada tanggal ${formatDate(itemToDelete.date)}?`);
                    
                    if (confirmed) {
                        const confirmSecond = window.confirm("Apakah Anda benar-benar yakin ingin menghapus data ini?");
                        if (confirmSecond) {
                        // Hapus item jika dikonfirmasi
                        axios.post('AttendanceController/deleteItems', {
                            action: 'deleteItems',
                            ids: itemToDelete,//[id], // Hapus hanya satu item pada satu waktu
                        })
                        .then((response) => {
                            console.log(`Deleted: ${itemToDelete.name}`);
                            // Lakukan update data jika perlu
                            setAttendanceData(prevData => prevData.filter(item => item.id !== id)); // Perbarui data setelah penghapusan
                        })
                        .catch((error) => {
                            console.error('Error deleting item:', error);
                        }); 
                        }
                    }
                    }); 
                    // Reset selectedItems setelah proses selesai
                setSelectedItems([]);
                };


            const filteredAttendanceData = attendanceData.filter(item => {
            const itemDate = new Date(item.date);
            const formattedDate = formatDate(item.date);
            const dateMatch = selectedDate ? formattedDate === selectedDate : true;

            const itemMonth = itemDate.getMonth() + 1;
            const itemYear = itemDate.getFullYear();

            const monthMatch = selectedMonth ? itemMonth === parseInt(selectedMonth) : true;
            const yearMatch = selectedYear ? itemYear === parseInt(selectedYear) : true;

            const nameMatch = selectedName ? item.name === selectedName : true;

            return dateMatch && monthMatch && yearMatch && nameMatch;
        });

        const generateDatesInMonth = (month, year) => {
            const daysInMonth = getDaysInMonth(month, year);
            const dates = [];
            for (let day = 1; day <= daysInMonth; day++) {
                dates.push(`${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`);
            }
            return dates;
        };

        // PDF Export Function
        const exportToPDF = async () => {
            const doc = new jsPDF();
            doc.setFontSize(12);
            const marginTop = 10;
            const marginLeft = 10;

            try {
                const response = await fetch('/sekrup/harta/assets/jateng.png');
                const blob = await response.blob();
                
                const reader = new FileReader();
                reader.onloadend = function () {
                    const base64data = reader.result;

                    doc.addImage(base64data, 'PNG', marginLeft, marginTop - 5, 20, 20);

                    // Title and Subtitle 
                    doc.setFontSize(13);
                    doc.text('DINAS KOMUNIKASI DAN INFORMATIKA PROVINSI JAWA TENGAH', doc.internal.pageSize.getWidth() / 2, marginTop, { align: 'center' });
                    doc.setFontSize(12);
                    doc.text('SUBBAG UMUM DAN KEPEGAWAIAN', doc.internal.pageSize.getWidth() / 2, marginTop + 7, { align: 'center' });

                    // Footer
                    doc.setFontSize(10);
                    if (filterBy === 'name') {
                        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        const selectedMonthName = monthNames[parseInt(selectedMonth) - 1] || '';
                        doc.text(`Nama: ${selectedName}`, marginLeft, marginTop + 19);
                        doc.text(`Bulan: ${selectedMonthName} ${selectedYear}`, marginLeft, marginTop + 23);
                    } else if (filterBy === 'date') {
                        const formattedDate = new Date(selectedDate).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                        });
                        doc.text(`Tanggal: ${formattedDate}`, marginLeft, marginTop + 19);
                        doc.text(`Total Data: ${filteredAttendanceData.length}`, marginLeft, marginTop + 23);
                    } else {
                        alert('Tidak ada data untuk diekspor.');
                        return;
                    }

                    // Table based on filter
                    if (filterBy === 'name') {
                        const datesInMonth = generateDatesInMonth(parseInt(selectedMonth), parseInt(selectedYear));

                        autoTable(doc, {
                            startY: marginTop + 30,
                            head: [
                                [
                                    { content: 'No', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Date', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Jam', colSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Kegiatan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                ],
                                [
                                    { content: 'Datang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Pulang', styles: { halign: 'center', valign: 'middle' } },
                                ]
                            ],
                            body: datesInMonth.map((date, index) => {
                                const attendanceOnDate = filteredAttendanceData.find(item => formatDate(item.date) === date);
                                return [
                                    index + 1,
                                    date,
                                    attendanceOnDate ? attendanceOnDate.timein : '-',
                                    attendanceOnDate ? attendanceOnDate.timeot : '-',
                                    attendanceOnDate ? attendanceOnDate.kegiatan : '-'
                                ];
                            }),
                            styles: { fontSize: 10, halign: 'left', valign: 'middle', cellPadding: 1.6, lineWidth: 0.2, lineColor: [0, 0, 0] },
                            theme: 'grid',
                            tableWidth: 'auto',
                            margin: marginTop,
                            headStyles: { fillColor: [86, 156, 214] },
                            tableLineColor: [0, 0, 0],
                            tableLineWidth: 0.2,
                        });

                    } else if (filterBy === 'date') {
                        autoTable(doc, {
                            startY: marginTop + 27,
                            head: [
                                [
                                    { content: 'No', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Nama', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Date', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Jam', colSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Kegiatan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                ],
                                [
                                    { content: 'Datang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Pulang', styles: { halign: 'center', valign: 'middle' } },
                                ]
                            ],
                            body: filteredAttendanceData.map((item, index) => [
                                index + 1,
                                item.name,
                                formatDate(item.date),
                                item.timein,
                                item.timeot,
                                item.kegiatan
                            ]),
                            styles: { fontSize: 10, halign: 'left', valign: 'middle', cellPadding: 1.6, lineWidth: 0.2, lineColor: [0, 0, 0] },
                            theme: 'grid',
                            tableWidth: 'auto',
                            margin: marginTop,
                            headStyles: { fillColor: [86, 156, 214] },
                            tableLineColor: [0, 0, 0],
                            tableLineWidth: 0.2,
                        });

                    } else if (filterBy === '') {
                        alert('Tidak ada data untuk diekspor.');
                        return;
                    }

                    // Download PDF
                    doc.save('attendance_report.pdf');
                };
                reader.readAsDataURL(blob);
            } catch (error) {
                console.error('Error fetching image:', error);
            }
        };

        // Excel Export Functionality
        const handleExport = () => {
            setShowExcelButtons(true);
            const month = parseInt(selectedMonth, 10);
            const year = parseInt(selectedYear, 10);

            if (!month || !year) {
                alert("Please select both month and year");
                return;
            }

            exportToOnlineSpreadsheet(month, year);
            setShowExcelButtons(false);
        };

        const handleBatalExport = () => {
            setShowExcelButtons(false);
        };

        const exportToOnlineSpreadsheet = (selectedMonth, selectedYear) => {
            const workbook = XLSX.utils.book_new();
            const startDate = new Date(selectedYear, selectedMonth - 1, 1);
            const endDate = new Date(selectedYear, selectedMonth, 0);

            const dateRange = [];
            for (let date = new Date(startDate); date <= endDate; date.setDate(date.getDate() + 1)) {
                dateRange.push(new Date(date));
            }

            const uniqueNames = [...new Set(attendanceData.map(item => item.name))];

            const summaryData = dateRange.map(date => {
                const formattedDate = date.toISOString().split('T')[0];
                const totalPresent = attendanceData.filter(att => att.date === formattedDate).length;

                return {
                    Date: formattedDate,
                    TotalRegistered: registerData.length,
                    TotalPresent: totalPresent,
                    TotalAbsent: registerData.length - totalPresent,
                };
            });

            const totalSheet = XLSX.utils.json_to_sheet(summaryData);
            const headers = Object.keys(summaryData[0]);
            const colWidths = headers.map(header => ({ wch: header.length + 5 }));
            totalSheet['!cols'] = colWidths;
            XLSX.utils.book_append_sheet(workbook, totalSheet, 'Attendance Summary');

            uniqueNames.forEach(name => {
                const monthlyAttendanceData = dateRange.map(date => {
                    const formattedDate = date.toISOString().split('T')[0];
                    const dailyAttendance = attendanceData.filter(att => {
                        const date = new Date(att.date).toISOString().split('T')[0];
                        return att.name === name && date === formattedDate;
                    });
                    return {
                        date: formattedDate,
                        timein: dailyAttendance.length > 0 ? dailyAttendance[0].timein : 'N/A',
                        timeot: dailyAttendance.length > 0 ? dailyAttendance[0].timeot : 'N/A',
                        kegiatan: dailyAttendance.length > 0 ? dailyAttendance[0].kegiatan : 'N/A',
                    };
                });

                const monthlySheet = XLSX.utils.json_to_sheet(monthlyAttendanceData);
                const headersMonthly = Object.keys(monthlyAttendanceData[0]);
                const colWidthsMonthly = headersMonthly.map(header => ({ wch: header.length + 5 }));
                monthlySheet['!cols'] = colWidthsMonthly;
                XLSX.utils.book_append_sheet(workbook, monthlySheet, name);
            });

            const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
            const blob = new Blob([excelBuffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'Attendance_Report.xlsx');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };

        // Pagination Logic
        const totalItems = filteredAttendanceData.length;
        const indexOfLastAttendance = currentPage * itemsPerPage;
        const indexOfFirstAttendance = indexOfLastAttendance - itemsPerPage;
        const displayedAttendanceData = filteredAttendanceData.slice(indexOfFirstAttendance, indexOfLastAttendance);
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        const getPagination = () => {
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



            return (
                // <div>
                //     <h1>Data Attendances</h1>
                //     {/* Tambahkan komponen dan UI lainnya di sini */}
                // </div>
                <div>
                    <div className="relative overflow-x-auto shadow-md sm:rounded-lg ">
                        <div className="flex justify-between items-center px-4 py-3 ">
                            <h2 className="text-xl font-bold mb-4">Laporan Data</h2>
                            <div className="relative ">
                                {filterBy === 'date' && (
                                    <button onClick={exportToPDF} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        PDF
                                    </button>
                                )}
                                {filterBy === 'name' && (
                                    <button onClick={exportToPDF} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        PDF
                                    </button>
                                )}
                                {!showExcelButtons && (
                                    <button id="exportBtn" onClick={handleExport} type="button" className="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">EXCEL</button>
                                )}
                                {showExcelButtons && (
                                    <div>
                                        <button onClick={handleBatalExport} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                            Batal
                                        </button>
                                        <button id="exportBtn" onClick={handleExport} type="button" className="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                            EXCEL
                                        </button>
                                    </div>
                                )}
                                {showExcelButtons && (
                                    <div>
                                        <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                            <select value={selectedMonth} onChange={handleMonthChange}>
                                                <option value="">Select</option>
                                                {[...Array(12).keys()].map(month => (
                                                    <option key={month + 1} value={month + 1}>{getMonthName(month + 1)}</option>
                                                ))}
                                            </select>
                                        </button>
                                        <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                            <select value={selectedYear} onChange={handleYearChange}>
                                                <option value="">Select Year</option>
                                                {getCurrentYearRange().map(year => (
                                                    <option key={year} value={year}>{year}</option>
                                                ))}
                                            </select>
                                        </button>
                                    </div>
                                )}
                            </div>
                        </div>

                        <div className="flex justify-between items-center px-4 py-3 ">
                            <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 mr-2 mb-2 dark:text-gray-400 '>
                                <select value={filterBy} onChange={handleFilterByChange}>
                                    <option value="">Select</option>
                                    <option value="date">By Date</option>
                                    <option value="name">By Name</option>
                                </select>
                            </button>

                            {filterBy === 'date' && (
                                <div>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                        <input type="date" value={selectedDate} onChange={handleDateChange} />
                                    </button>
                                </div>
                            )}

                            {filterBy === 'name' && (
                                <div>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:text-gray-400'>
                                        <select value={selectedName} onChange={handleNameChange}>
                                            <option value="">Select</option>
                                            {Array.from(new Set(attendanceData.map(item => item.name)))
                                                .sort()
                                                .map((name, index) => (
                                                    <option key={index} value={name}>{name}</option>
                                                ))}
                                        </select>
                                    </button>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                        <select value={selectedMonth} onChange={handleMonthChange}>
                                            <option value="">Select</option>
                                            {[...Array(12).keys()].map(month => (
                                                <option key={month + 1} value={month + 1}>{getMonthName(month + 1)}</option>
                                            ))}
                                        </select>
                                    </button>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                        <select value={selectedYear} onChange={handleYearChange}>
                                            <option value="">Select Year</option>
                                            {getCurrentYearRange().map(year => (
                                                <option key={year} value={year}>{year}</option>
                                            ))}
                                        </select>
                                    </button>
                                </div>
                            )}
                        </div>

                        <div className="flex justify-between  px-4 py-3">
                            <div className="flex">
                                <select
                                    id="itemsPerPage"
                                    value={itemsPerPage}
                                    onChange={handleItemsPerPageChange}
                                    className="border rounded px-2 py-1"
                                >
                                    <option value={10}>10</option>
                                    <option value={25}>25</option>
                                    <option value={50}>50</option>
                                    <option value={100}>100</option>
                                </select>
                                <div className="mt-4 text-sm text-gray-500">
                                    Total User: {filteredAttendanceData.length}
                                </div>
                            </div>
                        </div>
                        <br />
                        
                        {selectedItems.length > 0 && (
                            <React.Fragment>
                                <button
                                    onClick={handleDeleteSelected}
                                    className="bg-red-600 text-white px-4 py-2 rounded mb-4"
                                >
                                    Delete Selected
                                </button>
                            </React.Fragment>
                        )}
                        
                        <table className="w-full text-sm bg-white border border-gray-300 rounded-lg">
                            <thead>
                                <tr style={{ backgroundColor: "#569cd6" }} className="border-gray-400 text-white font-bold h-10 ">
                                    
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Nama
                                        <button onClick={sortByName} className="ml-2">
                                            {sortOrderName === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Date
                                        <button onClick={sortByDate} className="ml-2">
                                            {sortOrderDate === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                    <th colSpan={2} className="text-center p-4 border border-t-0">
                                        Jam Kerja
                                    </th>
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Keterangan
                                    </th>
                                </tr>
                                <tr>
                                    <th className="text-center border border-t-0">Masuk</th>
                                    <th className="text-center border border-t-0">Keluar</th>
                                </tr>
                            </thead>
                            <tbody>
                                {filteredAttendanceData.slice(0, itemsPerPage).map((data) => (
                                    <tr key={data.id} className="border-b hover:bg-gray-100">
                                        
                                        <td className="text-center md:p-4 p-0 border-r">{data.name}</td>
                                        <td className="text-center md:p-4 p-0 border-r">{data.date}</td>
                                        <td className="text-center md:p-4 p-0 border-r">{data.timein}</td>
                                        <td className="text-center md:p-4 p-0 border-r">{data.timeot}</td>
                                        <td className="text-center md:p-4 p-0 border-r">{data.kegiatan}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>

                       
                    </div>
                </div>

            );
        };

        // ReactDOM.render(<VdataAttendances />, document.getElementById('root'));
        const root = ReactDOM.createRoot(document.getElementById('root'));
        root.render(<VdataAttendances />);

    </script>
</body>
</html> -->
{filterBy === 'date' && (
    <button onClick={exportToPDF} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
        PDF
    </button>
)}
<!-- 
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
                <div class="flex items-center">
                    <label for="itemsPerPage" class="mr-2">Items per page:</label>
                    <select id="itemsPerPage" class="border rounded px-2 py-1"></select>
                    <div id="totalUserCount" class="mt-4 text-sm text-gray-500 ml-4">Total User: 0</div>
                </div>
                <div class="relative">
                    <form class="max-w-md mx-auto ml-2">
                        <input id="searchInput" type="search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50" placeholder="Search" required />
                    </form>
                </div>
            </div>
            <table id="userTable" class="min-w-full bg-white border border-gray-300">
                <thead class="border">
                    <tr>
                        <th rowSpan="2" class="text-center md:p-4 p-0 border-r">
                            Nama
                            <button id="sortNameBtn" class="ml-2" onclick="sortByName()">
                                <span id="nameSortOrder">↑</span>
                            </button>
                        </th>
                        <th rowSpan="2" class="text-center md:p-4 p-0 border-r">
                            Date
                            <button id="sortDateBtn" class="ml-2" onclick="sortByDate()">
                                <span id="dateSortOrder">↑</span>
                            </button>
                        </th>
                        <th colSpan="2" class="text-center p-4 border border-t-0">
                            Jam Kerja
                        </th>
                        <th rowSpan="2" class="text-center md:p-4 p-0 border-r">
                            Keterangan
                        </th>
                        <th rowSpan="2" class="text-center md:p-4 p-0 border-r">Uni</th>
                        <th rowSpan="2" class="text-center md:p-4 p-0 border-r">Jurusan</th>
                    </tr>
                    <tr>
                        <th class="text-center border border-t-0">Masuk</th>
                        <th class="text-center border border-t-0">Keluar</th>
                    </tr>
                </thead>
                <tbody id="userList"></tbody>
            </table>
            <nav id="pagination" class="m-4 flex items-center justify-between pt-4"></nav>
        </div>
    </div> 

    <script>
        let registerData = [];
        let currentPage = 1;
        let itemsPerPage = 10;
        let totalItems = 0;
        let sortOrderName = 'asc';
        let sortOrderDate = 'asc';
        let searchTerm = '';

        document.addEventListener('DOMContentLoaded', () => {
            populateItemsPerPage();
            fetchData();
            document.getElementById('searchInput').addEventListener('input', handleSearch);
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

        const fetchData = async () => { 
            try {
                const response = await axios.post('<?= site_url('AttendanceController/getAllData') ?>', {
                    action: 'getAllData'
                });
                registerData = response.data.t_attendances.map(attendance => {
                    const student = response.data.t_mahasiswa.find(m => m.nim === attendance.nim);
                    return {
                        name: student ? student.name : 'Unknown', // Assuming the student object has a 'name' field
                        nim: attendance.nim,
                        timein: attendance.timein,
                        timot: attendance.timot,
                        kegiatan: attendance.kegiatan,
                        univ: student ? student.mhs_univ : 'Unknown',
                        jurusan: student ? student.mhs_jurusan : 'Unknown',
                        date: attendance.date // Assuming the date is also part of the attendance object
                    };
                });
                totalItems = registerData.length;
                renderUsers();
                renderPagination();
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        };

        const renderUsers = () => {
            const userList = document.getElementById("userList");
            const filteredUsers = registerData.filter(user => 
                user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                user.nim.includes(searchTerm)
            );
            const start = (currentPage - 1) * itemsPerPage;
            const displayedUsers = filteredUsers.slice(start, start + itemsPerPage);

            userList.innerHTML = displayedUsers.map((user, index) => {
                const totalHours = calculateTotalHours(user.timein, user.timot);
                return `
                    <tr>
                        <td class="py-2 px-4 text-left">${start + index + 1}</td>
                        <td class="py-2 px-4 text-left">${user.name}</td>
                        <td class="py-2 px-4 text-left">${user.date}</td>
                        <td class="py-2 px-4 text-center">${user.timein}</td>
                        <td class="py-2 px-4 text-center">${user.timot}</td>
                        <td class="py-2 px-4 text-left">${totalHours} jam</td>
                        <td class="py-2 px-4 text-left">${user.kegiatan}</td>
                        <td class="py-2 px-4 text-left">${user.univ}</td>
                        <td class="py-2 px-4 text-left">${user.jurusan}</td>
                    </tr>
                `;
            }).join('');

            document.getElementById("totalUserCount").textContent = `Total User: ${filteredUsers.length}`;
        };

        const calculateTotalHours = (timein, timot) => {
            const startTime = new Date(`1970-01-01T${timein}:00`);
            const endTime = new Date(`1970-01-01T${timot}:00`);
            const hoursWorked = (endTime - startTime) / (1000 * 60 * 60);
            return hoursWorked >= 0 ? hoursWorked.toFixed(2) : '0.00'; // Avoid negative hours
        };

        const handleSearch = (e) => {
            searchTerm = e.target.value;
            currentPage = 1; // Reset to the first page on search change
            renderUsers();
            renderPagination();
        };

        const sortByName = () => {
            registerData.sort((a, b) => {
                const comparison = a.name.localeCompare(b.name);
                return sortOrderName === 'asc' ? comparison : -comparison;
            });
            sortOrderName = sortOrderName === 'asc' ? 'desc' : 'asc';
            document.getElementById("nameSortOrder").textContent = sortOrderName === 'asc' ? '↑' : '↓';
            renderUsers();
            renderPagination();
        };

        const sortByDate = () => {
            registerData.sort((a, b) => new Date(a.date) - new Date(b.date));
            sortOrderDate = sortOrderDate === 'asc' ? 'desc' : 'asc';
            document.getElementById("dateSortOrder").textContent = sortOrderDate === 'asc' ? '↑' : '↓';
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
            const filteredUsers = registerData.filter(user => 
                user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                user.nim.includes(searchTerm)
            );
            const pagination = document.getElementById("pagination");
            pagination.innerHTML = ''; // Clear existing pagination
            const totalPages = Math.ceil(filteredUsers.length / itemsPerPage);

            if (totalPages > 1) {
                const ul = document.createElement('ul');
                ul.className = 'flex justify-center space-x-2';
                for (let i = 1; i <= totalPages; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    const a = document.createElement('a');
                    a.href = '#';
                    a.textContent = i;
                    a.className = "block px-3 py-2 text-sm font-medium text-gray-500 border border-gray-300";
                    a.addEventListener('click', () => {
                        currentPage = i;
                        renderUsers();
                    });
                    li.appendChild(a);
                    ul.appendChild(li);
                }

                pagination.appendChild(ul);
            }
        };

        const exportToPDF = async () => {
    const doc = new jsPDF();
    doc.setFontSize(12);
    const marginTop = 10;
    const marginLeft = 10;

    try {
        const response = await fetch('/sekrup/harta/assets/jateng.png');
        const blob = await response.blob();
        
        const reader = new FileReader();
        reader.onloadend = function () {
            const base64data = reader.result;

            doc.addImage(base64data, 'PNG', marginLeft, marginTop - 5, 20, 20);

            // Title and Subtitle 
            doc.setFontSize(13);
            doc.text('DINAS KOMUNIKASI DAN INFORMATIKA PROVINSI JAWA TENGAH', doc.internal.pageSize.getWidth() / 2, marginTop, { align: 'center' });
            doc.setFontSize(12);
            doc.text('SUBBAG UMUM DAN KEPEGAWAIAN', doc.internal.pageSize.getWidth() / 2, marginTop + 7, { align: 'center' });

            // Footer
            doc.setFontSize(10);
            if (filterBy === 'name') {
                const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                const selectedMonthName = monthNames[parseInt(selectedMonth) - 1] || '';
                doc.text(`Nama: ${selectedName}`, marginLeft, marginTop + 19);
                doc.text(`Bulan: ${selectedMonthName} ${selectedYear}`, marginLeft, marginTop + 23);
                doc.text(`Jurusan: ${selectedDepartment}`, marginLeft, marginTop + 27);
                doc.text(`Universitas: ${selectedUniversity}`, marginLeft, marginTop + 31);
            } else if (filterBy === 'date') {
                const formattedDate = new Date(selectedDate).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                });
                doc.text(`Tanggal: ${formattedDate}`, marginLeft, marginTop + 19);
                doc.text(`Total Data: ${filteredAttendanceData.length}`, marginLeft, marginTop + 23);
            } else {
                alert('Tidak ada data untuk diekspor.');
                return;
            }

            // Table based on filter
            let totalHours = 0; // To keep track of total working hours
            if (filterBy === 'name') {
                const datesInMonth = generateDatesInMonth(parseInt(selectedMonth), parseInt(selectedYear));

                autoTable(doc, {
                    startY: marginTop + 40,
                    head: [
                        [
                            { content: 'No', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                            { content: 'Date', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                            { content: 'Jam', colSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                            { content: 'Kegiatan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                            { content: 'Total Jam Kerja', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                        ],
                        [
                            { content: 'Datang', styles: { halign: 'center', valign: 'middle' } },
                            { content: 'Pulang', styles: { halign: 'center', valign: 'middle' } },
                        ]
                    ],
                    body: datesInMonth.map((date, index) => {
                        const attendanceOnDate = filteredAttendanceData.find(item => formatDate(item.date) === date);
                        const timein = attendanceOnDate ? attendanceOnDate.timein : '-';
                        const timeot = attendanceOnDate ? attendanceOnDate.timeot : '-';
                        const kegiatan = attendanceOnDate ? attendanceOnDate.kegiatan : '-';
                        
                        // Calculate total hours
                        const hours = attendanceOnDate ? calculateHours(attendanceOnDate.timein, attendanceOnDate.timeot) : 0;
                        totalHours += hours;

                        return [
                            index + 1,
                            date,
                            timein,
                            timeot,
                            kegiatan,
                            hours || '-'
                        ];
                    }),
                    styles: { fontSize: 10, halign: 'left', valign: 'middle', cellPadding: 1.6, lineWidth: 0.2, lineColor: [0, 0, 0] },
                    theme: 'grid',
                    tableWidth: 'auto',
                    margin: marginTop,
                    headStyles: { fillColor: [86, 156, 214] },
                    tableLineColor: [0, 0, 0],
                    tableLineWidth: 0.2,
                });

            } else if (filterBy === 'date') {
                autoTable(doc, {
                    startY: marginTop + 27,
                    head: [
                        [
                            { content: 'No', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                            { content: 'Nama', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                            { content: 'Date', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                            { content: 'Jam', colSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                            { content: 'Kegiatan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                            { content: 'Total Jam Kerja', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                        ],
                        [
                            { content: 'Datang', styles: { halign: 'center', valign: 'middle' } },
                            { content: 'Pulang', styles: { halign: 'center', valign: 'middle' } },
                        ]
                    ],
                    body: filteredAttendanceData.map((item, index) => {
                        const hours = calculateHours(item.timein, item.timeot);
                        totalHours += hours;

                        return [
                            index + 1,
                            item.name,
                            formatDate(item.date),
                            item.timein,
                            item.timeot,
                            item.kegiatan,
                            hours || '-'
                        ];
                    }),
                    styles: { fontSize: 10, halign: 'left', valign: 'middle', cellPadding: 1.6, lineWidth: 0.2, lineColor: [0, 0, 0] },
                    theme: 'grid',
                    tableWidth: 'auto',
                    margin: marginTop,
                    headStyles: { fillColor: [86, 156, 214] },
                    tableLineColor: [0, 0, 0],
                    tableLineWidth: 0.2,
                });

            } else {
                alert('Tidak ada data untuk diekspor.');
                return;
            }

            // Add total working hours at the end
            doc.text(`Total Jam Kerja: ${totalHours} jam`, marginLeft, doc.lastAutoTable.finalY + 10);

            // Signature
            doc.text('Ditetapkan di: [Lokasi]', marginLeft, doc.lastAutoTable.finalY + 20);
            doc.text('Kasubag Umpeg', marginLeft, doc.lastAutoTable.finalY + 30);
            doc.text('Galih', marginLeft, doc.lastAutoTable.finalY + 40);

            // Download PDF
            doc.save('attendance_report.pdf');
        };
        reader.readAsDataURL(blob);
    } catch (error) {
        console.error('Error fetching image:', error);
    }
};

// Function to calculate hours from time strings
const calculateHours = (timein, timeot) => {
    if (!timein || !timeot || timein === '-' || timeot === '-') return 0;

    const [inHour, inMinute] = timein.split(':').map(Number);
    const [outHour, outMinute] = timeot.split(':').map(Number);
    
    const start = new Date();
    start.setHours(inHour, inMinute);

    const end = new Date();
    end.setHours(outHour, outMinute);

    const diff = (end - start) / (1000 * 60 * 60); // Difference in hours
    return diff < 0 ? 0 : diff; // Return 0 if negative
};

    </script>
</body>
</html> -->








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>React App with CDN</title>
    <script src="https://unpkg.com/react/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/babel-standalone/babel.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.10/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
</head>
<body>
    <div id="root"></div>
    <script type="text/babel">
        const { useState, useEffect } = React;
        const { jsPDF } = window.jspdf;
        const autoTable = window.jspdf.autoTable;
        

        const VdataAttendances = () => {
            const [registerData, setRegisterData] = useState([]);
            const [attendanceData, setAttendanceData] = useState([]);
            const [loading, setLoading] = useState(true);
            const [editUserId, setEditUserId] = useState(null);
            const [editUserTimeIn, setEditUserTimeIn] = useState('');
            const [editUserTimeOut, setEditUserTimeOut] = useState('');
            const [editUserKegiatan, setEditUserKegiatan] = useState('');
            const [filterBy, setFilterBy] = useState('');
            const [selectedDate, setSelectedDate] = useState('');
            const [selectedName, setSelectedName] = useState('');
            const [selectedNIM, setSelectedNIM] = useState('');
            const [selectedMonth, setSelectedMonth] = useState('');
            const [selectedYear, setSelectedYear] = useState('');
            const [selectedItems, setSelectedItems] = useState([]);
            const [itemsPerPage, setItemsPerPage] = useState(10);
            const [currentPage, setCurrentPage] = useState(1);
            const [sortOrderName, setSortOrderName] = useState('asc');
            const [sortOrderDate, setSortOrderDate] = useState('asc');
            const [showExcelButtons, setShowExcelButtons] = useState(false);
            const [filteredRegisterData, setFilteredRegisterData] = useState([]);
            const [searchDate, setSearchDate] = useState('');

            // Fungsi untuk mengurutkan berdasarkan nama
            const sortByName = () => {
                const sortedData = [...attendanceData].sort((a, b) => {
                    const comparison = a.name.localeCompare(b.name);
                    return sortOrderName === 'asc' ? comparison : -comparison;
                });
                setAttendanceData(sortedData);
                setSortOrderName(sortOrderName === 'asc' ? 'desc' : 'asc');
            };

            // Fungsi untuk mengurutkan berdasarkan tanggal
            const sortByDate = () => {
                const sortedData = [...attendanceData].sort((a, b) => {
                    const comparison = new Date(a.date) - new Date(b.date);
                    return sortOrderDate === 'asc' ? comparison : -comparison;
                });
                setAttendanceData(sortedData);
                setSortOrderDate(sortOrderDate === 'asc' ? 'desc' : 'asc');
            };

            // Ubah format tanggal
            const formatDate = (dateString) => {
                const date = new Date(dateString);
                return date.toISOString().split('T')[0];
            };

            // Menampilkan semua data register dan attendances
            useEffect(() => {
                const fetchData = async () => { 
                    try {
                        const response = await axios.post('AttendanceController/getAllData', {
                            action: 'getAllData'
                        });
                        setRegisterData(response.data.register);
                        setAttendanceData(response.data.attendance);
                        setLoading(false);
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    }
                };
                fetchData();
            }, []);

                // Selected edit dan save
            const handleEditId = (item) => {
                setEditUserId(item.id);
                setEditUserTimeIn(item.timein);
                setEditUserTimeOut(item.timeot);
                setEditUserKegiatan(item.kegiatan);
            };
 
            const handleSave = async (id) => {
                try {
                    await axios.post('AttendanceController/updateData', {
                        action: 'updateData',
                        id,
                        updatedData: {
                            timein: editUserTimeIn,
                            timeot: editUserTimeOut,
                            kegiatan: editUserKegiatan,
                        }
                    });
                    alert('Data berhasil diperbarui.');
                    // Refresh data after update
                    const response = await axios.post('AttendanceController/getAllData', { action: 'getAllData' });
                    setAttendanceData(response.data.attendance);
                    setEditUserId(null); // Reset edit mode
                } catch (error) {
                    console.error('Error updating data:', error);
                    alert('Gagal memperbarui data.');
                }
            };

            // Filter data 
            const handleFilterByChange = (e) => {
                setFilterBy(e.target.value);
                setSelectedDate(''); // Reset date selection
                setSelectedName(''); // Reset name selection
                setSelectedMonth(''); // Reset month selection
                setSelectedYear(''); // Reset year selection
            };

            const handleDateChange = (e) => {
                setSelectedDate(e.target.value);
                setSelectedMonth(''); // Reset month selection when filtering by date
                setSelectedYear('');
            };

            const handleNameChange = (e) => {
                setSelectedName(e.target.value);
            };

            const handleMonthChange = (e) => {
                setSelectedMonth(e.target.value);
                setSelectedDate('');
            };

            const handleYearChange = (e) => {
                setSelectedYear(e.target.value);
            };

            const handleItemsPerPageChange = (e) => {
                setItemsPerPage(e.target.value);
                setCurrentPage(1);
            };

            const getCurrentYearRange = () => {
                const currentYear = new Date().getFullYear();
                return Array.from({ length: 3 }, (_, i) => currentYear - i);
            };

            const getMonthName = (monthNumber) => {
                const date = new Date();
                date.setMonth(monthNumber - 1);
                return date.toLocaleString('default', { month: 'long' });
            };

            // Fungsi untuk menangani perubahan pada checkbox
            const handleCheckboxChange = (id) => {
                if (selectedItems.includes(id)) {
                    setSelectedItems(selectedItems.filter(item => item !== id)); // Hapus id dari daftar jika sudah ada
                } else {
                    setSelectedItems([...selectedItems, id]); // Tambah id ke daftar
                }
            };

            // Fungsi untuk memilih semua checkbox
            const handleSelectAll = (event) => {
                if (event.target.checked) {
                    const allIds = attendanceData.map(item => item.id);
                    setSelectedItems(allIds);
                } else {
                    setSelectedItems([]);
                }
            };

            const getDaysInMonth = (month, year) => {
                return new Date(year, month, 0).getDate();
            };

            
            const handleDeleteSelected = () => {
                if (selectedItems.length === 0) return; // Tidak melakukan apa-apa jika tidak ada yang dipilih
                    // Loop melalui setiap ID yang dipilih
                    selectedItems.forEach(id => {
                    const itemToDelete = filteredAttendanceData.find(item => item.id === id);
                    
                    // Tampilkan dialog konfirmasi untuk setiap item
                    const confirmed = window.confirm(`Yakin ingin menghapus data atas nama ${itemToDelete.name} pada tanggal ${formatDate(itemToDelete.date)}?`);
                    
                    if (confirmed) {
                        const confirmSecond = window.confirm("Apakah Anda benar-benar yakin ingin menghapus data ini?");
                        if (confirmSecond) {
                        // Hapus item jika dikonfirmasi
                        axios.post('AttendanceController/deleteItems', {
                            action: 'deleteItems',
                            ids: itemToDelete,//[id], // Hapus hanya satu item pada satu waktu
                        })
                        .then((response) => {
                            console.log(`Deleted: ${itemToDelete.name}`);
                            // Lakukan update data jika perlu
                            setAttendanceData(prevData => prevData.filter(item => item.id !== id)); // Perbarui data setelah penghapusan
                        })
                        .catch((error) => {
                            console.error('Error deleting item:', error);
                        }); 
                        }
                    }
                    }); 
                    // Reset selectedItems setelah proses selesai
                setSelectedItems([]);
                };


            const filteredAttendanceData = attendanceData.filter(item => {
            const itemDate = new Date(item.date);
            const formattedDate = formatDate(item.date);
            const dateMatch = selectedDate ? formattedDate === selectedDate : true;

            const itemMonth = itemDate.getMonth() + 1;
            const itemYear = itemDate.getFullYear();

            const monthMatch = selectedMonth ? itemMonth === parseInt(selectedMonth) : true;
            const yearMatch = selectedYear ? itemYear === parseInt(selectedYear) : true;

            const nameMatch = selectedName ? item.name === selectedName : true;

            return dateMatch && monthMatch && yearMatch && nameMatch;
        });

        const generateDatesInMonth = (month, year) => {
            const daysInMonth = getDaysInMonth(month, year);
            const dates = [];
            for (let day = 1; day <= daysInMonth; day++) {
                dates.push(`${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`);
            }
            return dates;
        };

        // PDF Export Function
        const exportToPDF = async () => {
            const doc = new jsPDF();
            doc.setFontSize(12);
            const marginTop = 10;
            const marginLeft = 10;

            try {
                const response = await fetch('/sekrup/harta/assets/jateng.png');
                const blob = await response.blob();
                
                const reader = new FileReader();
                reader.onloadend = function () {
                    const base64data = reader.result;

                    doc.addImage(base64data, 'PNG', marginLeft, marginTop - 5, 20, 20);

                    // Title and Subtitle 
                    doc.setFontSize(13);
                    doc.text('DINAS KOMUNIKASI DAN INFORMATIKA PROVINSI JAWA TENGAH', doc.internal.pageSize.getWidth() / 2, marginTop, { align: 'center' });
                    doc.setFontSize(12);
                    doc.text('SUBBAG UMUM DAN KEPEGAWAIAN', doc.internal.pageSize.getWidth() / 2, marginTop + 7, { align: 'center' });

                    // Footer
                    doc.setFontSize(10);
                    if (filterBy === 'name') {
                        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        const selectedMonthName = monthNames[parseInt(selectedMonth) - 1] || '';
                        doc.text(`Nama: ${selectedName}`, marginLeft, marginTop + 19);
                        doc.text(`Bulan: ${selectedMonthName} ${selectedYear}`, marginLeft, marginTop + 23);
                    } else if (filterBy === 'date') {
                        const formattedDate = new Date(selectedDate).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                        });
                        doc.text(`Tanggal: ${formattedDate}`, marginLeft, marginTop + 19);
                        doc.text(`Total Data: ${filteredAttendanceData.length}`, marginLeft, marginTop + 23);
                    } else {
                        alert('Tidak ada data untuk diekspor.');
                        return;
                    }

                    // Table based on filter
                    if (filterBy === 'name') {
                        const datesInMonth = generateDatesInMonth(parseInt(selectedMonth), parseInt(selectedYear));

                        autoTable(doc, {
                            startY: marginTop + 30,
                            head: [
                                [
                                    { content: 'No', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Date', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Jam', colSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Kegiatan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                ],
                                [
                                    { content: 'Datang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Pulang', styles: { halign: 'center', valign: 'middle' } },
                                ]
                            ],
                            body: datesInMonth.map((date, index) => {
                                const attendanceOnDate = filteredAttendanceData.find(item => formatDate(item.date) === date);
                                return [
                                    index + 1,
                                    date,
                                    attendanceOnDate ? attendanceOnDate.timein : '-',
                                    attendanceOnDate ? attendanceOnDate.timeot : '-',
                                    attendanceOnDate ? attendanceOnDate.kegiatan : '-'
                                ];
                            }),
                            styles: { fontSize: 10, halign: 'left', valign: 'middle', cellPadding: 1.6, lineWidth: 0.2, lineColor: [0, 0, 0] },
                            theme: 'grid',
                            tableWidth: 'auto',
                            margin: marginTop,
                            headStyles: { fillColor: [86, 156, 214] },
                            tableLineColor: [0, 0, 0],
                            tableLineWidth: 0.2,
                        });

                    } else if (filterBy === 'date') {
                        autoTable(doc, {
                            startY: marginTop + 27,
                            head: [
                                [
                                    { content: 'No', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Nama', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Date', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Jam', colSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Kegiatan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                ],
                                [
                                    { content: 'Datang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Pulang', styles: { halign: 'center', valign: 'middle' } },
                                ]
                            ],
                            body: filteredAttendanceData.map((item, index) => [
                                index + 1,
                                item.name,
                                formatDate(item.date),
                                item.timein,
                                item.timeot,
                                item.kegiatan
                            ]),
                            styles: { fontSize: 10, halign: 'left', valign: 'middle', cellPadding: 1.6, lineWidth: 0.2, lineColor: [0, 0, 0] },
                            theme: 'grid',
                            tableWidth: 'auto',
                            margin: marginTop,
                            headStyles: { fillColor: [86, 156, 214] },
                            tableLineColor: [0, 0, 0],
                            tableLineWidth: 0.2,
                        });

                    } else if (filterBy === '') {
                        alert('Tidak ada data untuk diekspor.');
                        return;
                    }

                    // Download PDF
                    doc.save('attendance_report.pdf');
                };
                reader.readAsDataURL(blob);
            } catch (error) {
                console.error('Error fetching image:', error);
            }
        };

        // Excel Export Functionality
        const handleExport = () => {
            setShowExcelButtons(true);
            const month = parseInt(selectedMonth, 10);
            const year = parseInt(selectedYear, 10);

            if (!month || !year) {
                alert("Please select both month and year");
                return;
            }

            exportToOnlineSpreadsheet(month, year);
            setShowExcelButtons(false);
        };

        const handleBatalExport = () => {
            setShowExcelButtons(false);
        };

        const exportToOnlineSpreadsheet = (selectedMonth, selectedYear) => {
            const workbook = XLSX.utils.book_new();
            const startDate = new Date(selectedYear, selectedMonth - 1, 1);
            const endDate = new Date(selectedYear, selectedMonth, 0);

            const dateRange = [];
            for (let date = new Date(startDate); date <= endDate; date.setDate(date.getDate() + 1)) {
                dateRange.push(new Date(date));
            }

            const uniqueNames = [...new Set(attendanceData.map(item => item.name))];

            const summaryData = dateRange.map(date => {
                const formattedDate = date.toISOString().split('T')[0];
                const totalPresent = attendanceData.filter(att => att.date === formattedDate).length;

                return {
                    Date: formattedDate,
                    TotalRegistered: registerData.length,
                    TotalPresent: totalPresent,
                    TotalAbsent: registerData.length - totalPresent,
                };
            });

            const totalSheet = XLSX.utils.json_to_sheet(summaryData);
            const headers = Object.keys(summaryData[0]);
            const colWidths = headers.map(header => ({ wch: header.length + 5 }));
            totalSheet['!cols'] = colWidths;
            XLSX.utils.book_append_sheet(workbook, totalSheet, 'Attendance Summary');

            uniqueNames.forEach(name => {
                const monthlyAttendanceData = dateRange.map(date => {
                    const formattedDate = date.toISOString().split('T')[0];
                    const dailyAttendance = attendanceData.filter(att => {
                        const date = new Date(att.date).toISOString().split('T')[0];
                        return att.name === name && date === formattedDate;
                    });
                    return {
                        date: formattedDate,
                        timein: dailyAttendance.length > 0 ? dailyAttendance[0].timein : 'N/A',
                        timeot: dailyAttendance.length > 0 ? dailyAttendance[0].timeot : 'N/A',
                        kegiatan: dailyAttendance.length > 0 ? dailyAttendance[0].kegiatan : 'N/A',
                    };
                });

                const monthlySheet = XLSX.utils.json_to_sheet(monthlyAttendanceData);
                const headersMonthly = Object.keys(monthlyAttendanceData[0]);
                const colWidthsMonthly = headersMonthly.map(header => ({ wch: header.length + 5 }));
                monthlySheet['!cols'] = colWidthsMonthly;
                XLSX.utils.book_append_sheet(workbook, monthlySheet, name);
            });

            const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
            const blob = new Blob([excelBuffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'Attendance_Report.xlsx');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };

        // Pagination Logic
        const totalItems = filteredAttendanceData.length;
        const indexOfLastAttendance = currentPage * itemsPerPage;
        const indexOfFirstAttendance = indexOfLastAttendance - itemsPerPage;
        const displayedAttendanceData = filteredAttendanceData.slice(indexOfFirstAttendance, indexOfLastAttendance);
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        const getPagination = () => {
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



            return (
                // <div>
                //     <h1>Data Attendances</h1>
                //     {/* Tambahkan komponen dan UI lainnya di sini */}
                // </div>
                <div>
                    <div className="relative overflow-x-auto shadow-md sm:rounded-lg ">
                        <div className="flex justify-between items-center px-4 py-3 ">
                            <h2 className="text-xl font-bold mb-4">Laporan Data</h2>
                            <div className="relative ">
                                {filterBy === 'date' && (
                                    <button onClick={exportToPDF} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        PDF
                                    </button>
                                )}
                                {filterBy === 'name' && (
                                    <button onClick={exportToPDF} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        PDF
                                    </button>
                                )}
                                {!showExcelButtons && (
                                    <button id="exportBtn" onClick={handleExport} type="button" className="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">EXCEL</button>
                                )}
                                {showExcelButtons && (
                                    <div>
                                        <button onClick={handleBatalExport} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                            Batal
                                        </button>
                                        <button id="exportBtn" onClick={handleExport} type="button" className="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                            EXCEL
                                        </button>
                                    </div>
                                )}
                                {showExcelButtons && (
                                    <div>
                                        <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                            <select value={selectedMonth} onChange={handleMonthChange}>
                                                <option value="">Select</option>
                                                {[...Array(12).keys()].map(month => (
                                                    <option key={month + 1} value={month + 1}>{getMonthName(month + 1)}</option>
                                                ))}
                                            </select>
                                        </button>
                                        <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                            <select value={selectedYear} onChange={handleYearChange}>
                                                <option value="">Select Year</option>
                                                {getCurrentYearRange().map(year => (
                                                    <option key={year} value={year}>{year}</option>
                                                ))}
                                            </select>
                                        </button>
                                    </div>
                                )}
                            </div>
                        </div>

                        <div className="flex justify-between items-center px-4 py-3 ">
                            <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 mr-2 mb-2 dark:text-gray-400 '>
                                <select value={filterBy} onChange={handleFilterByChange}>
                                    <option value="">Select</option>
                                    <option value="date">By Date</option>
                                    <option value="name">By Name</option>
                                </select>
                            </button>

                            {filterBy === 'date' && (
                                <div>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                        <input type="date" value={selectedDate} onChange={handleDateChange} />
                                    </button>
                                </div>
                            )}

                            {filterBy === 'name' && (
                                <div>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:text-gray-400'>
                                        <select value={selectedName} onChange={handleNameChange}>
                                            <option value="">Select</option>
                                            {Array.from(new Set(attendanceData.map(item => item.name)))
                                                .sort()
                                                .map((name, index) => (
                                                    <option key={index} value={name}>{name}</option>
                                                ))}
                                        </select>
                                    </button>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                        <select value={selectedMonth} onChange={handleMonthChange}>
                                            <option value="">Select</option>
                                            {[...Array(12).keys()].map(month => (
                                                <option key={month + 1} value={month + 1}>{getMonthName(month + 1)}</option>
                                            ))}
                                        </select>
                                    </button>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                        <select value={selectedYear} onChange={handleYearChange}>
                                            <option value="">Select Year</option>
                                            {getCurrentYearRange().map(year => (
                                                <option key={year} value={year}>{year}</option>
                                            ))}
                                        </select>
                                    </button>
                                </div>
                            )}
                        </div>

                        <div className="flex justify-between  px-4 py-3">
                            <div className="flex">
                                <select
                                    id="itemsPerPage"
                                    value={itemsPerPage}
                                    onChange={handleItemsPerPageChange}
                                    className="border rounded px-2 py-1"
                                >
                                    <option value={10}>10</option>
                                    <option value={25}>25</option>
                                    <option value={50}>50</option>
                                    <option value={100}>100</option>
                                </select>
                                <div className="mt-4 text-sm text-gray-500">
                                    Total User: {filteredAttendanceData.length}
                                </div>
                            </div>
                        </div>
                        <br />
                        
                        {selectedItems.length > 0 && (
                            <React.Fragment>
                                <button
                                    onClick={handleDeleteSelected}
                                    className="bg-red-600 text-white px-4 py-2 rounded mb-4"
                                >
                                    Delete Selected
                                </button>
                            </React.Fragment>
                        )}
                        
                        <table className="w-full text-sm bg-white border border-gray-300 rounded-lg">
                            <thead>
                                <tr style={{ backgroundColor: "#569cd6" }} className="border-gray-400 text-white font-bold h-10 ">
                                    
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Nama
                                        <button onClick={sortByName} className="ml-2">
                                            {sortOrderName === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Date
                                        <button onClick={sortByDate} className="ml-2">
                                            {sortOrderDate === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                    <th colSpan={2} className="text-center p-4 border border-t-0">
                                        Jam Kerja
                                    </th>
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Keterangan
                                    </th>
                                </tr>
                                <tr>
                                    <th className="text-center border border-t-0">Masuk</th>
                                    <th className="text-center border border-t-0">Keluar</th>
                                </tr>
                            </thead>
                            <tbody>
                                {filteredAttendanceData.slice(0, itemsPerPage).map((data) => (
                                    <tr key={data.id} className="border-b hover:bg-gray-100">
                                        
                                        <td className="text-center md:p-4 p-0 border-r">{data.name}</td>
                                        <td className="text-center md:p-4 p-0 border-r">{data.date}</td>
                                        <td className="text-center md:p-4 p-0 border-r">{data.timein}</td>
                                        <td className="text-center md:p-4 p-0 border-r">{data.timeot}</td>
                                        <td className="text-center md:p-4 p-0 border-r">{data.kegiatan}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>

                       
                    </div>
                </div>

            );
        };

        // ReactDOM.render(<VdataAttendances />, document.getElementById('root'));
        const root = ReactDOM.createRoot(document.getElementById('root'));
        root.render(<VdataAttendances />);

    </script>
</body>
</html>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Attendances</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
   
    <script src="https://unpkg.com/react/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/babel-standalone/babel.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
</head>
<body>
    <div id="root"></div>
    <button id="toggleButton">Buka Halaman Baru</button>

    <script>
        document.getElementById('toggleButton').addEventListener('click', function() {
            window.open('');
        });
    </script>

    
    <script type="text/babel">
        const { useState, useEffect } = React;

        const VdataAttendances = () => {
            const [registerData, setRegisterData] = useState([]);
            const [attendanceData, setAttendanceData] = useState([]);
            const [mahasiswaData, setMahasiswaData] = useState([]);
            const [loading, setLoading] = useState(true);
            const [editUserId, setEditUserId] = useState(null);
            const [editUserTimeIn, setEditUserTimeIn] = useState('');
            const [editUserTimeOut, setEditUserTimeOut] = useState('');
            const [editUserKegiatan, setEditUserKegiatan] = useState('');
            const [filterBy, setFilterBy] = useState('');
            const [selectedDate, setSelectedDate] = useState('');
            const [selectedName, setSelectedName] = useState('');
            const [selectedNIM, setSelectedNIM] = useState('');
            const [selectedMonth, setSelectedMonth] = useState('');
            const [selectedYear, setSelectedYear] = useState('');
            const [selectedItems, setSelectedItems] = useState([]);
            const [itemsPerPage, setItemsPerPage] = useState(10);
            const [currentPage, setCurrentPage] = useState(1);
            const [sortOrderName, setSortOrderName] = useState('asc');
            const [sortOrderDate, setSortOrderDate] = useState('asc');
            const [showExcelButtons, setShowExcelButtons] = useState(false);
            const [filteredRegisterData, setFilteredRegisterData] = useState([]);
            const [searchDate, setSearchDate] = useState('');

            // Fungsi untuk mengurutkan berdasarkan nama
            const sortByName = () => {
                const sortedData = [...attendanceData].sort((a, b) => {
                    const comparison = a.name.localeCompare(b.name);
                    return sortOrderName === 'asc' ? comparison : -comparison;
                });
                setAttendanceData(sortedData);
                setSortOrderName(sortOrderName === 'asc' ? 'desc' : 'asc');
            };

            // Fungsi untuk mengurutkan berdasarkan tanggal
            const sortByDate = () => {
                const sortedData = [...attendanceData].sort((a, b) => {
                    const comparison = new Date(a.date) - new Date(b.date);
                    return sortOrderDate === 'asc' ? comparison : -comparison;
                });
                setAttendanceData(sortedData);
                setSortOrderDate(sortOrderDate === 'asc' ? 'desc' : 'asc');
            };

            // Ubah format tanggal
            const formatDate = (dateString) => {
                const date = new Date(dateString);
                return date.toISOString().split('T')[0];
            };

            // Menampilkan semua data register dan attendances
            useEffect(() => {
                const fetchData = async () => { 
                    try {
                        const response = await axios.post('AttendanceController/getAllData', {
                            action: 'getAllData'
                        });
                        // setRegisterData(response.data.register);
                        setAttendanceData(response.data.attendance);
                        // setMahasiswaData(response.data.mahasiswa);
                        setLoading(false);
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    }
                };
                fetchData();
            }, []);

                // Selected edit dan save
            const handleEditId = (item) => {
                setEditUserId(item.id);
                setEditUserTimeIn(item.timein);
                setEditUserTimeOut(item.timeot);
                setEditUserKegiatan(item.kegiatan);
            };
 
            const handleSave = async (id) => {
                try {
                    await axios.post('AttendanceController/updateData', {
                        action: 'updateData',
                        id,
                        updatedData: {
                            timein: editUserTimeIn,
                            timeot: editUserTimeOut,
                            kegiatan: editUserKegiatan,
                        }
                    });
                    alert('Data berhasil diperbarui.');
                    // Refresh data after update
                    const response = await axios.post('AttendanceController/getAllData', { action: 'getAllData' });
                    setAttendanceData(response.data.attendance);
                    setEditUserId(null); // Reset edit mode
                } catch (error) {
                    console.error('Error updating data:', error);
                    alert('Gagal memperbarui data.');
                }
            };

            // Filter data 
            const handleFilterByChange = (e) => {
                setFilterBy(e.target.value);
                setSelectedDate(''); // Reset date selection
                setSelectedName(''); // Reset name selection
                setSelectedMonth(''); // Reset month selection
                setSelectedYear(''); // Reset year selection
            };

            const handleDateChange = (e) => {
                setSelectedDate(e.target.value);
                setSelectedMonth(''); // Reset month selection when filtering by date
                setSelectedYear('');
            };

            const handleNameChange = (e) => {
                setSelectedName(e.target.value);
            };

            const handleMonthChange = (e) => {
                setSelectedMonth(e.target.value);
                setSelectedDate('');
            };

            const handleYearChange = (e) => {
                setSelectedYear(e.target.value);
            };

            const handleItemsPerPageChange = (e) => {
                setItemsPerPage(e.target.value);
                setCurrentPage(1);
            };

            const getCurrentYearRange = () => {
                const currentYear = new Date().getFullYear();
                return Array.from({ length: 3 }, (_, i) => currentYear - i);
            };

            const getMonthName = (monthNumber) => {
                const date = new Date();
                date.setMonth(monthNumber - 1);
                return date.toLocaleString('default', { month: 'long' });
            };

            // Fungsi untuk menangani perubahan pada checkbox
            const handleCheckboxChange = (id) => {
                if (selectedItems.includes(id)) {
                    setSelectedItems(selectedItems.filter(item => item !== id)); // Hapus id dari daftar jika sudah ada
                } else {
                    setSelectedItems([...selectedItems, id]); // Tambah id ke daftar
                }
            };

            // Fungsi untuk memilih semua checkbox
            const handleSelectAll = (event) => {
                if (event.target.checked) {
                    const allIds = attendanceData.map(item => item.id);
                    setSelectedItems(allIds);
                } else {
                    setSelectedItems([]);
                }
            };

            const getDaysInMonth = (month, year) => {
                return new Date(year, month, 0).getDate();
            };

            
            const handleDeleteSelected = () => {
                if (selectedItems.length === 0) return; // Tidak melakukan apa-apa jika tidak ada yang dipilih
                    // Loop melalui setiap ID yang dipilih
                    selectedItems.forEach(id => {
                    const itemToDelete = filteredAttendanceData.find(item => item.id === id);
                    
                    // Tampilkan dialog konfirmasi untuk setiap item
                    const confirmed = window.confirm(`Yakin ingin menghapus data atas nama ${itemToDelete.name} pada tanggal ${formatDate(itemToDelete.date)}?`);
                    
                    if (confirmed) {
                        const confirmSecond = window.confirm("Apakah Anda benar-benar yakin ingin menghapus data ini?");
                        if (confirmSecond) {
                        // Hapus item jika dikonfirmasi
                        axios.post('AttendanceController/deleteItems', {
                            action: 'deleteItems',
                            ids: itemToDelete,//[id], // Hapus hanya satu item pada satu waktu
                        })
                        .then((response) => {
                            console.log(`Deleted: ${itemToDelete.name}`);
                            // Lakukan update data jika perlu
                            setAttendanceData(prevData => prevData.filter(item => item.id !== id)); // Perbarui data setelah penghapusan
                        })
                        .catch((error) => {
                            console.error('Error deleting item:', error);
                        }); 
                        }
                    }
                    }); 
                    // Reset selectedItems setelah proses selesai
                setSelectedItems([]);
                };


                const calculateTotalHours = (timein, timeot) => {
                    // Menghapus "WIB" dari waktu dan memisahkan jam dan menit
                    const [inHour, inMinute] = timein.replace(" WIB", "").split(':').map(Number);
                    const [otHour, otMinute] = timeot.replace(" WIB", "").split(':').map(Number);
                    
                    // Membuat objek Date untuk perhitungan
                    const startTime = new Date();
                    startTime.setHours(inHour, inMinute, 0);

                    const endTime = new Date();
                    endTime.setHours(otHour, otMinute, 0);

                    // Menghitung selisih waktu dalam milidetik
                    const diffInMilliseconds = endTime - startTime;

                    // Menghitung jam dan menit
                    const totalHours = Math.floor(diffInMilliseconds / (1000 * 60 * 60));
                    const totalMinutes = Math.floor((diffInMilliseconds % (1000 * 60 * 60)) / (1000 * 60));

                    // Menentukan status jam kerja
                    let status;
                    if (totalHours < 8) {
                        status = "Kurang Jam Kerja";
                    } else if (totalHours > 8) {
                        status = "Overtime";
                    } else {
                        status = "Jam Kerja Normal"; // jika persis 8 jam
                    }

                    // Mengembalikan hasil dalam format "X jam Y menit - Status"
                    return `${totalHours} jam ${totalMinutes} menit - ${status}`;
                };

        const filteredAttendanceData = Array.isArray(attendanceData) ? attendanceData.filter(item => {
            const itemDate = new Date(item.date);
            const formattedDate = formatDate(item.date);
            const dateMatch = selectedDate ? formattedDate === selectedDate : true;

            const itemMonth = itemDate.getMonth() + 1;
            const itemYear = itemDate.getFullYear();

            const monthMatch = selectedMonth ? itemMonth === parseInt(selectedMonth, 10) : true;
            const yearMatch = selectedYear ? itemYear === parseInt(selectedYear, 10) : true;

            const nameMatch = selectedName ? item.name === selectedName : true;
            // const univMatch = selectedUniv ? item.mhs_univ === selectedUniv : true; // Filter for university
            // const jurusanMatch = selectedJurusan ? item.mhs_jurusan === selectedJurusan : true; // Filter for major
            // const nimMatch = selectedNim ? item.mhs_nim === selectedNim : true; // Filter for NIM

            return dateMatch && monthMatch && yearMatch && nameMatch;// && univMatch && jurusanMatch && nimMatch;
        }) : [];

        const generateDatesInMonth = (month, year) => {
            const daysInMonth = getDaysInMonth(month, year);
            const dates = [];
            for (let day = 1; day <= daysInMonth; day++) {
                dates.push(`${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`);
            }
            return dates;
        };

        const exportToPDF = async () => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.setFontSize(12);
            const marginTop = 10;
            const marginLeft = 10;

            try {
                const response = await fetch('/sekrup/harta/assets/jateng.png');
                const blob = await response.blob();

                const reader = new FileReader();
                reader.onloadend = function () {
                    const base64data = reader.result;

                    doc.addImage(base64data, 'PNG', marginLeft, marginTop - 5, 20, 20);

                    // Title and Subtitle 
                    doc.setFontSize(13);
                    doc.text('DINAS KOMUNIKASI DAN INFORMATIKA PROVINSI JAWA TENGAH', doc.internal.pageSize.getWidth() / 2, marginTop, { align: 'center' });
                    doc.setFontSize(12);
                    doc.text('SUBBAG UMUM DAN KEPEGAWAIAN', doc.internal.pageSize.getWidth() / 2, marginTop + 7, { align: 'center' });

                    // Footer
                    doc.setFontSize(10);
                    let info = '';
                    if (filterBy === 'name') {
                        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        const selectedMonthName = monthNames[parseInt(selectedMonth) - 1] || '';
                        const mahasiswa = filteredAttendanceData.find(item => item.name === selectedName);
                        info = `Nama: ${selectedName} - ${mahasiswa.mhs_nim} - ${mahasiswa.mhs_jurusan } - ${mahasiswa.mhs_univ}`;
                        doc.text(info, marginLeft, marginTop + 19);
                        doc.text(`Bulan: ${selectedMonthName} ${selectedYear}`, marginLeft, marginTop + 23);
                    } else if (filterBy === 'date') {
                        const formattedDate = new Date(selectedDate).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                        });
                        info = `Tanggal: ${formattedDate}`;
                        doc.text(info, marginLeft, marginTop + 19);
                    } else {
                        alert('Tidak ada data untuk diekspor.');
                        return;
                    }

                    // Prepare data for the table
                    let tableData = [];
                    let startY = marginTop + 26; // Position for table
                    if (filterBy === 'name') {
                        const datesInMonth = generateDatesInMonth(parseInt(selectedMonth), parseInt(selectedYear));
                        datesInMonth.forEach((date, index) => {
                            const attendanceOnDate = filteredAttendanceData.find(item => formatDate(item.date) === date && item.name === selectedName);
                            tableData.push([
                                index + 1,
                                date,
                                attendanceOnDate ? attendanceOnDate.timein : '-',
                                attendanceOnDate ? attendanceOnDate.timeot : '-',
                                attendanceOnDate ? calculateTotalHours(attendanceOnDate.timein, attendanceOnDate.timeot) : '-',
                                attendanceOnDate ? attendanceOnDate.kegiatan : '-'
                            ]);
                        });

                        // Use autoTable to draw the table
                        doc.autoTable({
                            startY: startY,
                            // head: [['No', 'Date', 'Datang', 'Pulang','Total Jam Kerja', 'Kegiatan']],
                            head: [
                                [
                                    { content: 'No', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                     { content: 'Date', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Jam', colSpan: 3, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Kegiatan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                ],
                                [
                                    { content: 'Datang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Pulang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'T.Jam', styles: { halign: 'center', valign: 'middle' } },
                                ]
                            ],
                            body: tableData,
                            theme: 'grid',
                            margin: { left: marginLeft },
                            styles: { fontSize: 10}, // Set gray color for rows
                            headStyles: { fillColor: [100, 100, 100], textColor: [255, 255, 255] } // Header gray with white text
                    
                            // styles: { fontSize: 10 }
                        });

                        // Signature section below the table
                        const finalY = doc.lastAutoTable.finalY;
                        doc.setFontSize(10);
                        
                        doc.text(`Semarang, ${new Date().toLocaleDateString('id-ID')}`, marginLeft, finalY + 10);
                        doc.text(`Telah Disetujui,`, marginLeft, finalY + 15);
                        doc.text(`Kasubag Umpeg `, marginLeft, finalY + 20);
                        doc.text(`Dr. GALIH WIBOWO,S.Sos,MA`, marginLeft, finalY + 40);
                    } else if (filterBy === 'date') {
                        // Footer jika filter By Date
                        const formattedDate = new Date(selectedDate).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                        });

                        // Add formatted date and total data to the PDF
                        doc.text(`Tanggal: ${formattedDate}`, marginLeft, marginTop + 19);
                        doc.text(`Total Data: ${filteredAttendanceData.length}`, marginLeft, marginTop + 23);

                        // Prepare data for the table
                        const tableData = filteredAttendanceData.map((item, index) => {
                            // const mahasiswa = filteredDataWithMahasiswa.find(mhs => mhs.name === item.name);
                            return [
                                index + 1,
                                item.name ,
                                item.mhs_nim ,
                                item.mhs_jurusan : '0',
                                item.mhs_univ : '0',
                                formatDate(item.date),
                                item.timein,
                                item.timeot,
                                item ? calculateTotalHours(item.timein, item.timeot) : '-',
                                item.kegiatan
                            ];
                        });

                        // Use autoTable to draw the table
                        doc.autoTable({
                            startY: marginTop + 27, // Position table below the text
                            head: [
                                [
                                    { content: 'No', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Nama', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'NIM', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Universitas', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Jurusan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Date', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Jam', colSpan: 3, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Kegiatan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                ],
                                [
                                    { content: 'Datang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Pulang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'T.Jam', styles: { halign: 'center', valign: 'middle' } },
                                ]
                            ],
                            body: tableData,
                            styles: {
                                fontSize: 10,
                                halign: 'left',
                                valign: 'middle',
                                cellPadding: 1.6,
                                lineWidth: 0.2,
                                lineColor: [0, 0, 0],
                            },
                            theme: 'grid', // Tabel grid
                            tableWidth: 'auto', // Ukuran tabel otomatis
                            margin: { top: marginTop + 27, left: marginLeft }, // Margin atas dan kiri
                            headStyles: { fillColor: [100, 100, 100], textColor: [255, 255, 255] }, // Header color
                            tableLineColor: [0, 0, 0],  // Border color for table outline
                            tableLineWidth: 0.2,
                        });

                        // Signature section below the table
                        const finalY = doc.lastAutoTable.finalY;
                        doc.setFontSize(10);
                        doc.text(`Semarang, ${new Date().toLocaleDateString('id-ID')}`, marginLeft, finalY + 10);
                        doc.text(`Telah Disetujui,`, marginLeft, finalY + 15);
                        doc.text(`Kasubag Umpeg `, marginLeft, finalY + 20);
                        doc.text(`Dr. GALIH WIBOWO,S.Sos,MA`, marginLeft, finalY + 40);
                    }

                    else {
                        alert('Tidak ada data untuk diekspor.');
                        return;
                    }

                    // Download PDF
                    doc.save('attendance_report.pdf');
                };
                reader.readAsDataURL(blob);
            } catch (error) {
                console.error('Error fetching image:', error);
            }
        };

        // Excel Export Functionality
        const handleExport = () => {
            setShowExcelButtons(true);
            const month = parseInt(selectedMonth, 10);
            const year = parseInt(selectedYear, 10);

            if (!month || !year) {
                alert("Please select both month and year");
                return;
            }

            exportToOnlineSpreadsheet(month, year);
            setShowExcelButtons(false);
        };

        const handleBatalExport = () => {
            setShowExcelButtons(false);
        };

        const exportToOnlineSpreadsheet = (selectedMonth, selectedYear) => {
            const workbook = XLSX.utils.book_new();
            const startDate = new Date(selectedYear, selectedMonth - 1, 1);
            const endDate = new Date(selectedYear, selectedMonth, 0);

            const dateRange = [];
            for (let date = new Date(startDate); date <= endDate; date.setDate(date.getDate() + 1)) {
                dateRange.push(new Date(date));
            }

            const uniqueNames = [...new Set(attendanceData.map(item => item.name))];

            const summaryData = dateRange.map(date => {
                const formattedDate = date.toISOString().split('T')[0];
                const totalPresent = attendanceData.filter(att => att.date === formattedDate).length;

                return {
                    Date: formattedDate,
                    TotalRegistered: registerData.length,
                    TotalPresent: totalPresent,
                    TotalAbsent: registerData.length - totalPresent,
                };
            });

            const totalSheet = XLSX.utils.json_to_sheet(summaryData);
            const headers = Object.keys(summaryData[0]);
            const colWidths = headers.map(header => ({ wch: header.length + 5 }));
            totalSheet['!cols'] = colWidths;
            XLSX.utils.book_append_sheet(workbook, totalSheet, 'Attendance Summary');

            uniqueNames.forEach(name => {
                const monthlyAttendanceData = dateRange.map(date => {
                    const formattedDate = date.toISOString().split('T')[0];
                    const dailyAttendance = attendanceData.filter(att => {
                        const date = new Date(att.date).toISOString().split('T')[0];
                        return att.name === name && date === formattedDate;
                    });
                    return {
                        date: formattedDate,
                        timein: dailyAttendance.length > 0 ? dailyAttendance[0].timein : 'N/A',
                        timeot: dailyAttendance.length > 0 ? dailyAttendance[0].timeot : 'N/A',
                        kegiatan: dailyAttendance.length > 0 ? dailyAttendance[0].kegiatan : 'N/A',
                    };
                });

                const monthlySheet = XLSX.utils.json_to_sheet(monthlyAttendanceData);
                const headersMonthly = Object.keys(monthlyAttendanceData[0]);
                const colWidthsMonthly = headersMonthly.map(header => ({ wch: header.length + 5 }));
                monthlySheet['!cols'] = colWidthsMonthly;
                XLSX.utils.book_append_sheet(workbook, monthlySheet, name);
            });

            const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
            const blob = new Blob([excelBuffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'Attendance_Report.xlsx');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };

        // Pagination Logic
        const totalItems = filteredAttendanceData.length;
        const indexOfLastAttendance = currentPage * itemsPerPage;
        const indexOfFirstAttendance = indexOfLastAttendance - itemsPerPage;
        const displayedAttendanceData = filteredAttendanceData.slice(indexOfFirstAttendance, indexOfLastAttendance);
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        const getPagination = () => {
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



            return (
                // <div>
                //     <h1>Data Attendances</h1>
                //     {/* Tambahkan komponen dan UI lainnya di sini */}
                // </div>
                <div>
                    <div className="relative overflow-x-auto shadow-md sm:rounded-lg ">
                        <div className="flex justify-between items-center px-4 py-3 ">
                        
                            <h2 className="text-xl font-bold mb-4">Laporan Data</h2>
                            <div className="relative ">
                                
                                {filterBy === 'date' && (
                                    <button onClick={exportToPDF} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        PDF
                                    </button>
                                )}
                                {filterBy === 'name' && (
                                    <button onClick={exportToPDF} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        PDF
                                    </button>
                                )}
                                {!showExcelButtons && (
                                    <button id="exportBtn" onClick={handleExport} type="button" className="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">EXCEL</button>
                                )}
                                {showExcelButtons && (
                                    <div>
                                        <button onClick={handleBatalExport} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                            Batal
                                        </button>
                                        <button id="exportBtn" onClick={handleExport} type="button" className="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                            EXCEL
                                        </button>
                                    </div>
                                )}
                                {showExcelButtons && (
                                    <div>
                                        <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                            <select value={selectedMonth} onChange={handleMonthChange}>
                                                <option value="">Select</option>
                                                {[...Array(12).keys()].map(month => (
                                                    <option key={month + 1} value={month + 1}>{getMonthName(month + 1)}</option>
                                                ))}
                                            </select>
                                        </button>
                                        <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                            <select value={selectedYear} onChange={handleYearChange}>
                                                <option value="">Select Year</option>
                                                {getCurrentYearRange().map(year => (
                                                    <option key={year} value={year}>{year}</option>
                                                ))}
                                            </select>
                                        </button>
                                    </div>
                                )}
                            </div>
                        </div>

                        <div className="flex justify-between items-center px-4 py-3 ">
                            <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 mr-2 mb-2 dark:text-gray-400 '>
                                <select value={filterBy} onChange={handleFilterByChange}>
                                    <option value="">Select</option>
                                    <option value="date">By Date</option>
                                    <option value="name">By Name</option>
                                </select>
                            </button>

                            {filterBy === 'date' && (
                                <div>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                        <input type="date" value={selectedDate} onChange={handleDateChange} />
                                    </button>
                                </div>
                            )}

                            {filterBy === 'name' && (
                                <div>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:text-gray-400'>
                                        <select value={selectedName} onChange={handleNameChange}>
                                            <option value="">Select</option>
                                            {Array.from(new Set(attendanceData.map(item => item.name)))
                                                .sort()
                                                .map((name, index) => (
                                                    <option key={index} value={name}>{name}</option>
                                                ))}
                                        </select>
                                    </button>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                        <select value={selectedMonth} onChange={handleMonthChange}>
                                            <option value="">Select</option>
                                            {[...Array(12).keys()].map(month => (
                                                <option key={month + 1} value={month + 1}>{getMonthName(month + 1)}</option>
                                            ))}
                                        </select>
                                    </button>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                        <select value={selectedYear} onChange={handleYearChange}>
                                            <option value="">Select Year</option>
                                            {getCurrentYearRange().map(year => (
                                                <option key={year} value={year}>{year}</option>
                                            ))}
                                        </select>
                                    </button>
                                </div>
                            )}
                        </div>

                        <div className="flex justify-between  px-4 py-3">
                            <div className="flex">
                                <select
                                    id="itemsPerPage"
                                    value={itemsPerPage}
                                    onChange={handleItemsPerPageChange}
                                    className="border rounded px-2 py-1"
                                >
                                    <option value={10}>10</option>
                                    <option value={25}>25</option>
                                    <option value={50}>50</option>
                                    <option value={100}>100</option>
                                </select>
                                <div className="mt-4 text-sm text-gray-500">
                                    Total User: {filteredAttendanceData.length}
                                </div>
                            </div>
                        </div>
                        <br />
                        
                        {selectedItems.length > 0 && (
                            <React.Fragment>
                                <button
                                    onClick={handleDeleteSelected}
                                    className="bg-red-600 text-white px-4 py-2 rounded mb-4"
                                >
                                    Delete Selected
                                </button>
                            </React.Fragment>
                        )}
                        
                        <table className="w-full text-sm bg-white border border-gray-300 rounded-lg">
                            <thead>
                                <tr style={{ backgroundColor: "#569cd6" }} className="border-gray-400 text-white font-bold h-10 ">
                                
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Nama
                                        <button onClick={sortByName} className="ml-2">
                                            {sortOrderName === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Date
                                        <button onClick={sortByDate} className="ml-2">
                                            {sortOrderDate === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                    <th colSpan={3} className="text-center p-4 border border-t-0">
                                        Jam Kerja
                                    </th>
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Univ
                                        <button onClick={sortByName} className="ml-2">
                                            {sortOrderName === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Prodi
                                        <button onClick={sortByName} className="ml-2">
                                            {sortOrderName === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Keterangan
                                        <button onClick={sortByName} className="ml-2">
                                            {sortOrderName === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                </tr>
                                <tr>
                                    <th className="text-center border border-t-0">Masuk</th>
                                    <th className="text-center border border-t-0">Keluar</th>
                                    <th className="text-center border border-t-0">Total jam</th>
                                </tr>
                            </thead>
                            <tbody>
                            {filteredAttendanceData.slice(0, itemsPerPage).map((data) => (
                                <tr key={data.id} className="border-b hover:bg-gray-100">
                                    <td className="text-center md:p-4 p-0 border-r">{data.name}</td>
                                    <td className="text-center md:p-4 p-0 border-r">{data.date}</td>
                                    <td className="text-center md:p-4 p-0 border-r">{data.timein}</td>
                                    <td className="text-center md:p-4 p-0 border-r">{data.timeot}</td>
                                    <td className="text-center md:p-4 p-0 border-r">{calculateTotalHours(data.timein, data.timeot)}</td> {/* Total Jam Kerja */}
                                     <td className="text-center md:p-4 p-0 border-r">{data.mhs_univ}</td> {/* Universitas */}
                                    <td className="text-center md:p-4 p-0 border-r">{data.mhs_jurusan}</td> {/* Jurusan */}
                                    <td className="text-center md:p-4 p-0 border-r">{data.kegiatan}</td>
                                </tr>
                            ))}
                            </tbody>
                        </table>

                       
                    </div>
                </div>

            );
        };

        // ReactDOM.render(<VdataAttendances />, document.getElementById('root'));
        const root = ReactDOM.createRoot(document.getElementById('root'));
        root.render(<VdataAttendances />);

    </script>
</body>
</html>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Attendances</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
   
    <script src="https://unpkg.com/react/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/babel-standalone/babel.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="relative flex-col overflow-x-auto items-center justify-center shadow-md sm:rounded-lg">
         
            <div id="root"></div>
        </div>
    </div>
     <!-- Tombol untuk menampilkan data kehadiran -->
     <!-- <a href="<?= site_url('absen?show=face-register'); ?>" class="button">Register</a>
    
    
    <div id="attendance-data" style="margin-top: 20px;">
        <?php if (isset($show_form)): ?>
            
            <?php if ($show_form === 'register'): ?>
                Menampilkan formulir pendaftaran
                <?php $this->load->view('absen/face-register'); ?>
            <?php endif; ?>
        <?php endif; ?>
    </div> -->
    
    <script type="text/babel">
        const { useState, useEffect } = React;

        const VdataAttendances = () => {
            const [registerData, setRegisterData] = useState([]);
            const [attendanceData, setAttendanceData] = useState([]);
            const [mahasiswaData, setMahasiswaData] = useState([]);
            const [loading, setLoading] = useState(true);
            const [editUserId, setEditUserId] = useState(null);
            const [editUserTimeIn, setEditUserTimeIn] = useState('');
            const [editUserTimeOut, setEditUserTimeOut] = useState('');
            const [editUserKegiatan, setEditUserKegiatan] = useState('');
            const [filterBy, setFilterBy] = useState('');
            const [selectedDate, setSelectedDate] = useState('');
            const [selectedName, setSelectedName] = useState('');
            const [selectedNIM, setSelectedNIM] = useState('');
            const [selectedMonth, setSelectedMonth] = useState('');
            const [selectedYear, setSelectedYear] = useState('');
            const [selectedItems, setSelectedItems] = useState([]);
            const [itemsPerPage, setItemsPerPage] = useState(10);
            const [currentPage, setCurrentPage] = useState(1);
            const [sortOrderName, setSortOrderName] = useState('asc');
            const [sortOrderDate, setSortOrderDate] = useState('asc');
            const [showExcelButtons, setShowExcelButtons] = useState(false);
            const [filteredRegisterData, setFilteredRegisterData] = useState([]);
            const [searchDate, setSearchDate] = useState('');

            // Fungsi untuk mengurutkan berdasarkan nama
            const sortByName = () => {
                const sortedData = [...attendanceData].sort((a, b) => {
                    const comparison = a.name.localeCompare(b.name);
                    return sortOrderName === 'asc' ? comparison : -comparison;
                });
                setAttendanceData(sortedData);
                setSortOrderName(sortOrderName === 'asc' ? 'desc' : 'asc');
            };

            // Fungsi untuk mengurutkan berdasarkan tanggal
            const sortByDate = () => {
                const sortedData = [...attendanceData].sort((a, b) => {
                    const comparison = new Date(a.date) - new Date(b.date);
                    return sortOrderDate === 'asc' ? comparison : -comparison;
                });
                setAttendanceData(sortedData);
                setSortOrderDate(sortOrderDate === 'asc' ? 'desc' : 'asc');
            };

            // Ubah format tanggal
            const formatDate = (dateString) => {
                const date = new Date(dateString);
                return date.toISOString().split('T')[0];
            };

            // Menampilkan semua data register dan attendances
            useEffect(() => {
                const fetchData = async () => { 
                    try {
                        const response = await axios.post('AttendanceController/getAllData', {
                            action: 'getAllData'
                        });
                        // setRegisterData(response.data.register);
                        setAttendanceData(response.data.attendance);
                        // setMahasiswaData(response.data.mahasiswa);
                        setLoading(false);
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    }
                };
                fetchData();
            }, []);

                // Selected edit dan save
            const handleEditId = (item) => {
                setEditUserId(item.id);
                setEditUserTimeIn(item.timein);
                setEditUserTimeOut(item.timeot);
                setEditUserKegiatan(item.kegiatan);
            };
 
            const handleSave = async (id) => {
                try {
                    await axios.post('AttendanceController/updateData', {
                        action: 'updateData',
                        id,
                        updatedData: {
                            timein: editUserTimeIn,
                            timeot: editUserTimeOut,
                            kegiatan: editUserKegiatan,
                        }
                    });
                    alert('Data berhasil diperbarui.');
                    // Refresh data after update
                    const response = await axios.post('AttendanceController/getAllData', { action: 'getAllData' });
                    setAttendanceData(response.data.attendance);
                    setEditUserId(null); // Reset edit mode
                } catch (error) {
                    console.error('Error updating data:', error);
                    alert('Gagal memperbarui data.');
                }
            };

            // Filter data 
            const handleFilterByChange = (e) => {
                setFilterBy(e.target.value);
                setSelectedDate(''); // Reset date selection
                setSelectedName(''); // Reset name selection
                setSelectedMonth(''); // Reset month selection
                setSelectedYear(''); // Reset year selection
            };

            const handleDateChange = (e) => {
                setSelectedDate(e.target.value);
                setSelectedMonth(''); // Reset month selection when filtering by date
                setSelectedYear('');
            };

            const handleNameChange = (e) => {
                setSelectedName(e.target.value);
            };

            const handleMonthChange = (e) => {
                setSelectedMonth(e.target.value);
                setSelectedDate('');
            };

            const handleYearChange = (e) => {
                setSelectedYear(e.target.value);
            };

            const handleItemsPerPageChange = (e) => {
                setItemsPerPage(e.target.value);
                setCurrentPage(1);
            };

            const getCurrentYearRange = () => {
                const currentYear = new Date().getFullYear();
                return Array.from({ length: 3 }, (_, i) => currentYear - i);
            };

            const getMonthName = (monthNumber) => {
                const date = new Date();
                date.setMonth(monthNumber - 1);
                return date.toLocaleString('default', { month: 'long' });
            };

            // Fungsi untuk menangani perubahan pada checkbox
            const handleCheckboxChange = (id) => {
                if (selectedItems.includes(id)) {
                    setSelectedItems(selectedItems.filter(item => item !== id)); // Hapus id dari daftar jika sudah ada
                } else {
                    setSelectedItems([...selectedItems, id]); // Tambah id ke daftar
                }
            };

            // Fungsi untuk memilih semua checkbox
            const handleSelectAll = (event) => {
                if (event.target.checked) {
                    const allIds = attendanceData.map(item => item.id);
                    setSelectedItems(allIds);
                } else {
                    setSelectedItems([]);
                }
            };

            const getDaysInMonth = (month, year) => {
                return new Date(year, month, 0).getDate();
            };

            
            const handleDeleteSelected = () => {
                if (selectedItems.length === 0) return; // Tidak melakukan apa-apa jika tidak ada yang dipilih
                    // Loop melalui setiap ID yang dipilih
                    selectedItems.forEach(id => {
                    const itemToDelete = filteredAttendanceData.find(item => item.id === id);
                    
                    // Tampilkan dialog konfirmasi untuk setiap item
                    const confirmed = window.confirm(`Yakin ingin menghapus data atas nama ${itemToDelete.name} pada tanggal ${formatDate(itemToDelete.date)}?`);
                    
                    if (confirmed) {
                        const confirmSecond = window.confirm("Apakah Anda benar-benar yakin ingin menghapus data ini?");
                        if (confirmSecond) {
                        // Hapus item jika dikonfirmasi
                        axios.post('AttendanceController/deleteItems', {
                            action: 'deleteItems',
                            ids: itemToDelete,//[id], // Hapus hanya satu item pada satu waktu
                        })
                        .then((response) => {
                            console.log(`Deleted: ${itemToDelete.name}`);
                            // Lakukan update data jika perlu
                            setAttendanceData(prevData => prevData.filter(item => item.id !== id)); // Perbarui data setelah penghapusan
                        })
                        .catch((error) => {
                            console.error('Error deleting item:', error);
                        }); 
                        }
                    }
                    }); 
                    // Reset selectedItems setelah proses selesai
                setSelectedItems([]);
                };


                const calculateTotalHours = (timein, timeot) => {
                    // Menghapus "WIB" dari waktu dan memisahkan jam dan menit
                    const [inHour, inMinute] = timein.replace(" WIB", "").split(':').map(Number);
                    const [otHour, otMinute] = timeot.replace(" WIB", "").split(':').map(Number);
                    
                    // Membuat objek Date untuk perhitungan
                    const startTime = new Date();
                    startTime.setHours(inHour, inMinute, 0);

                    const endTime = new Date();
                    endTime.setHours(otHour, otMinute, 0);

                    // Menghitung selisih waktu dalam milidetik
                    const diffInMilliseconds = endTime - startTime;

                    // Menghitung jam dan menit
                    const totalHours = Math.floor(diffInMilliseconds / (1000 * 60 * 60));
                    const totalMinutes = Math.floor((diffInMilliseconds % (1000 * 60 * 60)) / (1000 * 60));

                    // Menentukan status jam kerja
                    let status;
                    if (totalHours < 8) {
                        status = "Kurang Jam Kerja";
                    } else if (totalHours > 8) {
                        status = "Overtime";
                    } else {
                        status = "Jam Kerja Normal"; // jika persis 8 jam
                    }

                    // Mengembalikan hasil dalam format "X jam Y menit - Status"
                    return `${totalHours} jam ${totalMinutes} menit - ${status}`;
                };

            
        // const filteredAttendanceData = Array.isArray(attendanceData) ?attendanceData.filter(item => {
        //     const itemDate = new Date(item.date);
        //     const formattedDate = formatDate(item.date);
        //     const dateMatch = selectedDate ? formattedDate === selectedDate : true;

        //     const itemMonth = itemDate.getMonth() + 1;
        //     const itemYear = itemDate.getFullYear();

        //     const monthMatch = selectedMonth ? itemMonth === parseInt(selectedMonth) : true;
        //     const yearMatch = selectedYear ? itemYear === parseInt(selectedYear) : true;

        //     const nameMatch = selectedName ? item.name === selectedName : true;

        //     return dateMatch && monthMatch && yearMatch && nameMatch;
        // }): [];
        const filteredAttendanceData = Array.isArray(attendanceData) ? attendanceData.filter(item => {
            const itemDate = new Date(item.date);
            const formattedDate = formatDate(item.date);
            const dateMatch = selectedDate ? formattedDate === selectedDate : true;

            const itemMonth = itemDate.getMonth() + 1;
            const itemYear = itemDate.getFullYear();

            const monthMatch = selectedMonth ? itemMonth === parseInt(selectedMonth, 10) : true;
            const yearMatch = selectedYear ? itemYear === parseInt(selectedYear, 10) : true;

            const nameMatch = selectedName ? item.name === selectedName : true;
            // const univMatch = selectedUniv ? item.mhs_univ === selectedUniv : true; // Filter for university
            // const jurusanMatch = selectedJurusan ? item.mhs_jurusan === selectedJurusan : true; // Filter for major
            // const nimMatch = selectedNim ? item.mhs_nim === selectedNim : true; // Filter for NIM

            return dateMatch && monthMatch && yearMatch && nameMatch;// && univMatch && jurusanMatch && nimMatch;
        }) : [];


        // Langkah 2: Gabungkan dengan data mahasiswa
        const filteredDataWithMahasiswa = Array.isArray(attendanceData) && Array.isArray(mahasiswaData) 
    ? filteredAttendanceData.map(attendanceItem => {
        const mahasiswaItem = mahasiswaData.find(mhs => mhs.mhs_nama === attendanceItem.name);
        return {
            ...attendanceItem,
            mahasiswa: mahasiswaItem || null
        };
    }) 
    : [];


        const generateDatesInMonth = (month, year) => {
            const daysInMonth = getDaysInMonth(month, year);
            const dates = [];
            for (let day = 1; day <= daysInMonth; day++) {
                dates.push(`${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`);
            }
            return dates;
        };

        const exportToPDF = async () => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.setFontSize(12);
            const marginTop = 10;
            const marginLeft = 10;

            try {
                const response = await fetch('/sekrup/harta/assets/jateng.png');
                const blob = await response.blob();

                const reader = new FileReader();
                reader.onloadend = function () {
                    const base64data = reader.result;

                    doc.addImage(base64data, 'PNG', marginLeft, marginTop - 5, 20, 20);

                    // Title and Subtitle 
                    doc.setFontSize(13);
                    doc.text('DINAS KOMUNIKASI DAN INFORMATIKA PROVINSI JAWA TENGAH', doc.internal.pageSize.getWidth() / 2, marginTop, { align: 'center' });
                    doc.setFontSize(12);
                    doc.text('SUBBAG UMUM DAN KEPEGAWAIAN', doc.internal.pageSize.getWidth() / 2, marginTop + 7, { align: 'center' });

                    // Footer
                    doc.setFontSize(10);
                    let info = '';
                    if (filterBy === 'name') {
                        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        const selectedMonthName = monthNames[parseInt(selectedMonth) - 1] || '';
                        const mahasiswa = filteredAttendanceData.find(item => item.name === selectedName);
                        info = `Nama: ${selectedName} - ${mahasiswa.mhs_nim} - ${mahasiswa.mhs_jurusan } - ${mahasiswa.mhs_univ}`;
                        doc.text(info, marginLeft, marginTop + 19);
                        doc.text(`Bulan: ${selectedMonthName} ${selectedYear}`, marginLeft, marginTop + 23);
                    } else if (filterBy === 'date') {
                        const formattedDate = new Date(selectedDate).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                        });
                        info = `Tanggal: ${formattedDate}`;
                        doc.text(info, marginLeft, marginTop + 19);
                    } else {
                        alert('Tidak ada data untuk diekspor.');
                        return;
                    }

                    // Prepare data for the table
                    let tableData = [];
                    let startY = marginTop + 26; // Position for table
                    if (filterBy === 'name') {
                        const datesInMonth = generateDatesInMonth(parseInt(selectedMonth), parseInt(selectedYear));
                        datesInMonth.forEach((date, index) => {
                            const attendanceOnDate = filteredAttendanceData.find(item => formatDate(item.date) === date && item.name === selectedName);
                            tableData.push([
                                index + 1,
                                date,
                                attendanceOnDate ? attendanceOnDate.timein : '-',
                                attendanceOnDate ? attendanceOnDate.timeot : '-',
                                attendanceOnDate ? calculateTotalHours(attendanceOnDate.timein, attendanceOnDate.timeot) : '-',
                                attendanceOnDate ? attendanceOnDate.kegiatan : '-'
                            ]);
                        });

                        // Use autoTable to draw the table
                        doc.autoTable({
                            startY: startY,
                            // head: [['No', 'Date', 'Datang', 'Pulang','Total Jam Kerja', 'Kegiatan']],
                            head: [
                                [
                                    { content: 'No', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                     { content: 'Date', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Jam', colSpan: 3, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Kegiatan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                ],
                                [
                                    { content: 'Datang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Pulang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'T.Jam', styles: { halign: 'center', valign: 'middle' } },
                                ]
                            ],
                            body: tableData,
                            theme: 'grid',
                            margin: { left: marginLeft },
                            styles: { fontSize: 10}, // Set gray color for rows
                            headStyles: { fillColor: [100, 100, 100], textColor: [255, 255, 255] } // Header gray with white text
                    
                            // styles: { fontSize: 10 }
                        });

                        // Signature section below the table
                        const finalY = doc.lastAutoTable.finalY;
                        doc.setFontSize(10);
                        
                        doc.text(`Semarang, ${new Date().toLocaleDateString('id-ID')}`, marginLeft, finalY + 10);
                        doc.text(`Telah Disetujui,`, marginLeft, finalY + 15);
                        doc.text(`Kasubag Umpeg `, marginLeft, finalY + 20);
                        doc.text(`Dr. GALIH WIBOWO,S.Sos,MA`, marginLeft, finalY + 40);
                    } else if (filterBy === 'date') {
                        // Footer jika filter By Date
                        const formattedDate = new Date(selectedDate).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                        });

                        // Add formatted date and total data to the PDF
                        doc.text(`Tanggal: ${formattedDate}`, marginLeft, marginTop + 19);
                        doc.text(`Total Data: ${filteredAttendanceData.length}`, marginLeft, marginTop + 23);

                        // Prepare data for the table
                        const tableData = filteredAttendanceData.map((item, index) => {
                            // const mahasiswa = filteredDataWithMahasiswa.find(mhs => mhs.name === item.name);
                            return [
                                index + 1,
                                item.name ,
                                item.mhs_nim ,
                                item.mhs_jurusan : '0',
                                item.mhs_univ : '0',
                                formatDate(item.date),
                                item.timein,
                                item.timeot,
                                item ? calculateTotalHours(item.timein, item.timeot) : '-',
                                item.kegiatan
                            ];
                        });

                        // Use autoTable to draw the table
                        doc.autoTable({
                            startY: marginTop + 27, // Position table below the text
                            head: [
                                [
                                    { content: 'No', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Nama', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'NIM', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Universitas', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Jurusan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Date', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Jam', colSpan: 3, styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Kegiatan', rowSpan: 2, styles: { halign: 'center', valign: 'middle' } },
                                ],
                                [
                                    { content: 'Datang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'Pulang', styles: { halign: 'center', valign: 'middle' } },
                                    { content: 'T.Jam', styles: { halign: 'center', valign: 'middle' } },
                                ]
                            ],
                            body: tableData,
                            styles: {
                                fontSize: 10,
                                halign: 'left',
                                valign: 'middle',
                                cellPadding: 1.6,
                                lineWidth: 0.2,
                                lineColor: [0, 0, 0],
                            },
                            theme: 'grid', // Tabel grid
                            tableWidth: 'auto', // Ukuran tabel otomatis
                            margin: { top: marginTop + 27, left: marginLeft }, // Margin atas dan kiri
                            headStyles: { fillColor: [100, 100, 100], textColor: [255, 255, 255] }, // Header color
                            tableLineColor: [0, 0, 0],  // Border color for table outline
                            tableLineWidth: 0.2,
                        });

                        // Signature section below the table
                        const finalY = doc.lastAutoTable.finalY;
                        doc.setFontSize(10);
                        doc.text(`Semarang, ${new Date().toLocaleDateString('id-ID')}`, marginLeft, finalY + 10);
                        doc.text(`Telah Disetujui,`, marginLeft, finalY + 15);
                        doc.text(`Kasubag Umpeg `, marginLeft, finalY + 20);
                        doc.text(`Dr. GALIH WIBOWO,S.Sos,MA`, marginLeft, finalY + 40);
                    }

                    else {
                        alert('Tidak ada data untuk diekspor.');
                        return;
                    }

                    // Download PDF
                    doc.save('attendance_report.pdf');
                };
                reader.readAsDataURL(blob);
            } catch (error) {
                console.error('Error fetching image:', error);
            }
        };

        // Excel Export Functionality
        const handleExport = () => {
            setShowExcelButtons(true);
            const month = parseInt(selectedMonth, 10);
            const year = parseInt(selectedYear, 10);

            if (!month || !year) {
                alert("Please select both month and year");
                return;
            }

            exportToOnlineSpreadsheet(month, year);
            setShowExcelButtons(false);
        };

        const handleBatalExport = () => {
            setShowExcelButtons(false);
        };

        const exportToOnlineSpreadsheet = (selectedMonth, selectedYear) => {
            const workbook = XLSX.utils.book_new();
            const startDate = new Date(selectedYear, selectedMonth - 1, 1);
            const endDate = new Date(selectedYear, selectedMonth, 0);

            const dateRange = [];
            for (let date = new Date(startDate); date <= endDate; date.setDate(date.getDate() + 1)) {
                dateRange.push(new Date(date));
            }

            const uniqueNames = [...new Set(attendanceData.map(item => item.name))];

            const summaryData = dateRange.map(date => {
                const formattedDate = date.toISOString().split('T')[0];
                const totalPresent = attendanceData.filter(att => att.date === formattedDate).length;

                return {
                    Date: formattedDate,
                    TotalRegistered: registerData.length,
                    TotalPresent: totalPresent,
                    TotalAbsent: registerData.length - totalPresent,
                };
            });

            const totalSheet = XLSX.utils.json_to_sheet(summaryData);
            const headers = Object.keys(summaryData[0]);
            const colWidths = headers.map(header => ({ wch: header.length + 5 }));
            totalSheet['!cols'] = colWidths;
            XLSX.utils.book_append_sheet(workbook, totalSheet, 'Attendance Summary');

            uniqueNames.forEach(name => {
                const monthlyAttendanceData = dateRange.map(date => {
                    const formattedDate = date.toISOString().split('T')[0];
                    const dailyAttendance = attendanceData.filter(att => {
                        const date = new Date(att.date).toISOString().split('T')[0];
                        return att.name === name && date === formattedDate;
                    });
                    return {
                        date: formattedDate,
                        timein: dailyAttendance.length > 0 ? dailyAttendance[0].timein : 'N/A',
                        timeot: dailyAttendance.length > 0 ? dailyAttendance[0].timeot : 'N/A',
                        kegiatan: dailyAttendance.length > 0 ? dailyAttendance[0].kegiatan : 'N/A',
                    };
                });

                const monthlySheet = XLSX.utils.json_to_sheet(monthlyAttendanceData);
                const headersMonthly = Object.keys(monthlyAttendanceData[0]);
                const colWidthsMonthly = headersMonthly.map(header => ({ wch: header.length + 5 }));
                monthlySheet['!cols'] = colWidthsMonthly;
                XLSX.utils.book_append_sheet(workbook, monthlySheet, name);
            });

            const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
            const blob = new Blob([excelBuffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'Attendance_Report.xlsx');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };

        // Pagination Logic
        const totalItems = filteredAttendanceData.length;
        const indexOfLastAttendance = currentPage * itemsPerPage;
        const indexOfFirstAttendance = indexOfLastAttendance - itemsPerPage;
        const displayedAttendanceData = filteredAttendanceData.slice(indexOfFirstAttendance, indexOfLastAttendance);
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        const getPagination = () => {
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



            return (
                // <div>
                //     <h1>Data Attendances</h1>
                //     {/* Tambahkan komponen dan UI lainnya di sini */}
                // </div>
                <div>
                    <div className="relative overflow-x-auto shadow-md sm:rounded-lg ">
                        <div className="flex justify-between items-center px-4 py-3 ">
                        
                            <h2 className="text-xl font-bold mb-4">Laporan Data</h2>
                            <div className="relative ">
                                
                                {filterBy === 'date' && (
                                    <button onClick={exportToPDF} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        PDF
                                    </button>
                                )}
                                {filterBy === 'name' && (
                                    <button onClick={exportToPDF} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        PDF
                                    </button>
                                )}
                                {!showExcelButtons && (
                                    <button id="exportBtn" onClick={handleExport} type="button" className="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">EXCEL</button>
                                )}
                                {showExcelButtons && (
                                    <div>
                                        <button onClick={handleBatalExport} type="button" className="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                            Batal
                                        </button>
                                        <button id="exportBtn" onClick={handleExport} type="button" className="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 m-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                            EXCEL
                                        </button>
                                    </div>
                                )}
                                {showExcelButtons && (
                                    <div>
                                        <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                            <select value={selectedMonth} onChange={handleMonthChange}>
                                                <option value="">Select</option>
                                                {[...Array(12).keys()].map(month => (
                                                    <option key={month + 1} value={month + 1}>{getMonthName(month + 1)}</option>
                                                ))}
                                            </select>
                                        </button>
                                        <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                            <select value={selectedYear} onChange={handleYearChange}>
                                                <option value="">Select Year</option>
                                                {getCurrentYearRange().map(year => (
                                                    <option key={year} value={year}>{year}</option>
                                                ))}
                                            </select>
                                        </button>
                                    </div>
                                )}
                            </div>
                        </div>

                        <div className="flex justify-between items-center px-4 py-3 ">
                            <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 mr-2 mb-2 dark:text-gray-400 '>
                                <select value={filterBy} onChange={handleFilterByChange}>
                                    <option value="">Select</option>
                                    <option value="date">By Date</option>
                                    <option value="name">By Name</option>
                                </select>
                            </button>

                            {filterBy === 'date' && (
                                <div>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                        <input type="date" value={selectedDate} onChange={handleDateChange} />
                                    </button>
                                </div>
                            )}

                            {filterBy === 'name' && (
                                <div>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:text-gray-400'>
                                        <select value={selectedName} onChange={handleNameChange}>
                                            <option value="">Select</option>
                                            {Array.from(new Set(attendanceData.map(item => item.name)))
                                                .sort()
                                                .map((name, index) => (
                                                    <option key={index} value={name}>{name}</option>
                                                ))}
                                        </select>
                                    </button>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                        <select value={selectedMonth} onChange={handleMonthChange}>
                                            <option value="">Select</option>
                                            {[...Array(12).keys()].map(month => (
                                                <option key={month + 1} value={month + 1}>{getMonthName(month + 1)}</option>
                                            ))}
                                        </select>
                                    </button>
                                    <button className='inline-flex items-center text-gray-500 bg-white border border-gray-300 mr-2 mb-2 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5  dark:text-gray-400 '>
                                        <select value={selectedYear} onChange={handleYearChange}>
                                            <option value="">Select Year</option>
                                            {getCurrentYearRange().map(year => (
                                                <option key={year} value={year}>{year}</option>
                                            ))}
                                        </select>
                                    </button>
                                </div>
                            )}
                        </div>

                        <div className="flex justify-between  px-4 py-3">
                            <div className="flex">
                                <select
                                    id="itemsPerPage"
                                    value={itemsPerPage}
                                    onChange={handleItemsPerPageChange}
                                    className="border rounded px-2 py-1"
                                >
                                    <option value={10}>10</option>
                                    <option value={25}>25</option>
                                    <option value={50}>50</option>
                                    <option value={100}>100</option>
                                </select>
                                <div className="mt-4 text-sm text-gray-500">
                                    Total User: {filteredAttendanceData.length}
                                </div>
                            </div>
                        </div>
                        <br />
                        
                        {selectedItems.length > 0 && (
                            <React.Fragment>
                                <button
                                    onClick={handleDeleteSelected}
                                    className="bg-red-600 text-white px-4 py-2 rounded mb-4"
                                >
                                    Delete Selected
                                </button>
                            </React.Fragment>
                        )}
                        
                        <table className="w-full text-sm bg-white border border-gray-300 rounded-lg">
                            <thead>
                                <tr style={{ backgroundColor: "#569cd6" }} className="border-gray-400 text-white font-bold h-10 ">
                                
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Nama
                                        <button onClick={sortByName} className="ml-2">
                                            {sortOrderName === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Date
                                        <button onClick={sortByDate} className="ml-2">
                                            {sortOrderDate === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                    <th colSpan={3} className="text-center p-4 border border-t-0">
                                        Jam Kerja
                                    </th>
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Univ
                                        <button onClick={sortByName} className="ml-2">
                                            {sortOrderName === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Prodi
                                        <button onClick={sortByName} className="ml-2">
                                            {sortOrderName === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                    <th rowSpan={2} className="text-center md:p-4 p-0 border-r">
                                        Keterangan
                                        <button onClick={sortByName} className="ml-2">
                                            {sortOrderName === 'asc' ? '↑' : '↓'}
                                        </button>
                                    </th>
                                </tr>
                                <tr>
                                    <th className="text-center border border-t-0">Masuk</th>
                                    <th className="text-center border border-t-0">Keluar</th>
                                    <th className="text-center border border-t-0">Total jam</th>
                                </tr>
                            </thead>
                            <tbody>
                            {filteredAttendanceData.slice(0, itemsPerPage).map((data) => (
                                <tr key={data.id} className="border-b hover:bg-gray-100">
                                    <td className="text-center md:p-4 p-0 border-r">{data.name}</td>
                                    <td className="text-center md:p-4 p-0 border-r">{data.date}</td>
                                    <td className="text-center md:p-4 p-0 border-r">{data.timein}</td>
                                    <td className="text-center md:p-4 p-0 border-r">{data.timeot}</td>
                                    <td className="text-center md:p-4 p-0 border-r">{calculateTotalHours(data.timein, data.timeot)}</td> {/* Total Jam Kerja */}
                                     <td className="text-center md:p-4 p-0 border-r">{data.mhs_univ}</td> {/* Universitas */}
                                    <td className="text-center md:p-4 p-0 border-r">{data.mhs_jurusan}</td> {/* Jurusan */}
                                    <td className="text-center md:p-4 p-0 border-r">{data.kegiatan}</td>
                                </tr>
                            ))}
                            </tbody>
                        </table>

                       
                    </div>
                </div>

            );
        };

        // ReactDOM.render(<VdataAttendances />, document.getElementById('root'));
        const root = ReactDOM.createRoot(document.getElementById('root'));
        root.render(<VdataAttendances />);
       

    </script>
</body>
</html>



                            // {filteredAttendanceData.slice(0, itemsPerPage).map((data) => (
                            //     <tr key={data.id} className="border-b hover:bg-gray-100">
                            //         <td className="py-2 px-4 text-center border-r">
                            //             <input
                            //                 type="checkbox" 
                            //                 checked={selectedItems.includes(item.id)} // Cek apakah id ada di daftar terpilih
                            //                 onChange={() => handleCheckboxChange(item.id)} 
                            //             />
                            //             {/* checked={selectedItems.includes(index)}
                            //             onChange={() => handleCheckboxChange(index)} */}
                            //         </td>
                            //         <td className="text-center md:p-4 p-0 border-r">{data.name}</td>
                            //         <td className="text-center md:p-4 p-0 border-r">{data.date}</td>
                            //         <td className="text-center md:p-4 p-0 border-r">{data.timein}</td>
                            //         <td className="text-center md:p-4 p-0 border-r">{data.timeot}</td>
                            //         <td className="text-center md:p-4 p-0 border-r">{calculateTotalHours(data.timein, data.timeot)}</td> {/* Total Jam Kerja */}
                            //          <td className="text-center md:p-4 p-0 border-r">{data.mhs_univ}</td> {/* Universitas */}
                            //         <td className="text-center md:p-4 p-0 border-r">{data.mhs_jurusan}</td> {/* Jurusan */}
                            //         <td className="text-center md:p-4 p-0 border-r">{data.kegiatan}</td>
                            //     </tr>
                            // ))}