
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Data</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.11/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
</head>
<body>
    <div id="app">
        <h1>Attendance Data</h1>
        
        <div>
            <input type="text" id="searchDate" placeholder="Search by date" onchange="handleDateChange(event)">
            <select id="monthFilter" onchange="handleMonthChange(event)">
                <option value="">Select Month</option>
                <script>
                    for (let i = 1; i <= 12; i++) {
                        document.write(`<option value="${i}">${getMonthName(i)}</option>`);
                    }
                </script>
            </select>
            <select id="yearFilter" onchange="handleYearChange(event)">
                <option value="">Select Year</option>
                <script>
                    const years = getCurrentYearRange();
                    years.forEach(year => {
                        document.write(`<option value="${year}">${year}</option>`);
                    });
                </script>
            </select>
            <button onclick="fetchData()">Fetch Data</button>
            <button onclick="sortByName()">Sort by Name</button>
            <button onclick="sortByDate()">Sort by Date</button>
            <button onclick="handleDeleteSelected()">Delete Selected</button>
            <button onclick="exportToPDF()">Export to PDF</button>
        </div>

        <table id="attendanceTable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll" onclick="handleSelectAll(event)"></th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Kegiatan</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="attendanceBody"></tbody>
        </table>

        <div>
            <input type="text" id="editTimeIn" placeholder="Time In">
            <input type="text" id="editTimeOut" placeholder="Time Out">
            <input type="text" id="editKegiatan" placeholder="Kegiatan">
            <button id="saveButton" onclick="handleSave()">Save</button>
        </div>
    </div>

    <script>
        let attendanceData = [];
        let sortOrderName = 'asc';
        let sortOrderDate = 'asc';
        let editUserId = null;
        let selectedItems = [];
        let selectedDate = '';
        let selectedMonth = '';
        let selectedYear = '';
        let selectedName = '';
        let filterBy = ''; // Track the current filter

        const fetchData = async () => {
            try {
                const response = await axios.post('AttendanceController/getAllData', { action: 'getAllData' });
                attendanceData = response.data.attendance;
                renderTable(attendanceData);
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        };

        const renderTable = (data) => {
            const attendanceBody = document.getElementById('attendanceBody');
            attendanceBody.innerHTML = ''; // Clear existing rows
            const filteredData = filterAttendanceData(data);
            filteredData.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><input type="checkbox" onchange="handleCheckboxChange(${item.id})"></td>
                    <td>${item.name}</td>
                    <td>${formatDate(item.date)}</td>
                    <td>${item.timeIn}</td>
                    <td>${item.timeOut}</td>
                    <td>${item.kegiatan}</td>
                    <td>
                        <button onclick="handleEditId(${JSON.stringify(item)})">Edit</button>
                    </td>
                `;
                attendanceBody.appendChild(row);
            });
        };

        const filterAttendanceData = (data) => {
            return data.filter(item => {
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
        };

        const handleDateChange = (e) => {
            selectedDate = e.target.value;
            filterBy = 'date'; // Set filter type
            renderTable(attendanceData); // Refresh table with filtered data
        };

        const handleMonthChange = (e) => {
            selectedMonth = e.target.value;
            filterBy = 'name'; // Set filter type
            renderTable(attendanceData); // Refresh table with filtered data
        };

        const handleYearChange = (e) => {
            selectedYear = e.target.value;
            renderTable(attendanceData); // Refresh table with filtered data
        };

        const handleEditId = (item) => {
            editUserId = item.id;
            document.getElementById('editTimeIn').value = item.timeIn;
            document.getElementById('editTimeOut').value = item.timeOut;
            document.getElementById('editKegiatan').value = item.kegiatan;
        };

        const handleSave = async () => {
            if (editUserId) {
                try {
                    await axios.post('AttendanceController/updateData', {
                        action: 'updateData',
                        id: editUserId,
                        updatedData: {
                            timein: document.getElementById('editTimeIn').value,
                            timeot: document.getElementById('editTimeOut').value,
                            kegiatan: document.getElementById('editKegiatan').value,
                        }
                    });
                    alert('Data berhasil diperbarui.');
                    fetchData(); // Refresh data after update
                    editUserId = null; // Reset edit mode
                } catch (error) {
                    console.error('Error updating data:', error);
                    alert('Gagal memperbarui data.');
                }
            }
        };

        const exportToPDF = async () => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const marginTop = 10;
            const marginLeft = 10;

            try {
                const response = await fetch('/assets/jateng.png');
                const blob = await response.blob();
                
                const reader = new FileReader();
                reader.onloadend = async function () {
                    const base64data = reader.result;

                    doc.addImage(base64data, 'PNG', marginLeft, marginTop - 5, 20, 20);
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
                        doc.text(`Total Data: ${attendanceData.length}`, marginLeft, marginTop + 23);
                    } else {
                        alert('Tidak ada data untuk diekspor.');
                        return;
                    }

                    // Add table based on filter
                    if (filterBy === 'name') {
                        const datesInMonth = generateDatesInMonth(parseInt(selectedMonth), parseInt(selectedYear));
                        autoTable(doc, {
                            startY: marginTop + 30,
                            head: [
                                ['No', 'Date', 'Jam Datang', 'Jam Pulang', 'Kegiatan']
                            ],
                            body: datesInMonth.map((date, index) => {
                                const attendanceOnDate = attendanceData.find(item => formatDate(item.date) === date);
                                return [
                                    index + 1,
                                    date,
                                    attendanceOnDate ? attendanceOnDate.timein : '-',
                                    attendanceOnDate ? attendanceOnDate.timeot : '-',
                                    attendanceOnDate ? attendanceOnDate.kegiatan : '-'
                                ];
                            }),
                        });
                    } else if (filterBy === 'date') {
                        autoTable(doc, {
                            startY: marginTop + 30,
                            head: [
                                ['No', 'Nama', 'Date', 'Jam Datang', 'Jam Pulang', 'Kegiatan']
                            ],
                            body: attendanceData.map((item, index) => [
                                index + 1,
                                item.name,
                                formatDate(item.date),
                                item.timein,
                                item.timeot,
                                item.kegiatan
                            ]),
                        });
                    }

                    doc.save('attendance_report.pdf');
                };
                reader.readAsDataURL(blob);
            } catch (error) {
                console.error('Error fetching image:', error);
            }
        };

        const handleExport = () => {
            showExcelButtons = true;
            const month = parseInt(selectedMonth, 10);
            const year = parseInt(selectedYear, 10);

            if (!month || !year) {
                alert("Please select both month and year");
                showExcelButtons = false;
                return;
            }
            exportToOnlineSpreadsheet(month, year);
            showExcelButtons = false;
        };

        const handleBatalExport = () => {
            showExcelButtons = false;
            document.getElementById('batalExportButton').style.display = 'none'; // Hide the cancel button
        };


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
                    TotalRegistered: attendanceData.length, // Update to actual registered data
                    TotalPresent: totalPresent,
                    TotalAbsent: attendanceData.length - totalPresent,
                };
            });

            const totalSheet = XLSX.utils.json_to_sheet(summaryData);
            const headers = Object.keys(summaryData[0]);
            const colWidths = headers.map(header => ({ wch: header.length + 5 })); // Adjust column widths

            totalSheet['!cols'] = colWidths;
            XLSX.utils.book_append_sheet(workbook, totalSheet, 'Attendance Summary');

            // Create a separate sheet for each unique name
            uniqueNames.forEach(name => {
                const monthlyAttendanceData = dateRange.map(date => {
                    const formattedDate = date.toISOString().split('T')[0]; // Format date as YYYY-MM-DD
                    const dailyAttendance = attendanceData.filter(att => {
                        const date = new Date(att.date).toISOString().split('T')[0]; 
                        return att.name === name && date === formattedDate;
                    });
                    return {
                        date: formattedDate,
                        timein: dailyAttendance.length > 0 ? dailyAttendance[0].timeIn : 'N/A',
                        timeot: dailyAttendance.length > 0 ? dailyAttendance[0].timeOut : 'N/A',
                        kegiatan: dailyAttendance.length > 0 ? dailyAttendance[0].kegiatan : 'N/A',
                    };
                });

                const monthlySheet = XLSX.utils.json_to_sheet(monthlyAttendanceData);
                const headersMonthly = Object.keys(monthlyAttendanceData[0]);
                const colWidthsMonthly = headersMonthly.map(header => ({ wch: header.length + 5 }));
                monthlySheet['!cols'] = colWidthsMonthly;

                XLSX.utils.book_append_sheet(workbook, monthlySheet, name); // Use name as sheet name
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

        const getCurrentYearRange = () => {
            const currentYear = new Date().getFullYear();
            return Array.from({ length: 3 }, (_, i) => currentYear - i);
        };

        const getMonthName = (monthNumber) => {
            const date = new Date();
            date.setMonth(monthNumber - 1);
            return date.toLocaleString('default', { month: 'long' });
        };

        const generateDatesInMonth = (month, year) => {
            const daysInMonth = new Date(year, month, 0).getDate();
            const dates = [];
            for (let day = 1; day <= daysInMonth; day++) {
                dates.push(`${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`);
            }
            return dates;
        };

        const formatDate = (dateString) => {
            const date = new Date(dateString);
            return `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')}`;
        };

        const handleCheckboxChange = (id) => {
            if (selectedItems.includes(id)) {
                selectedItems = selectedItems.filter(item => item !== id);
            } else {
                selectedItems.push(id);
            }
        };

        const handleSelectAll = (event) => {
            if (event.target.checked) {
                selectedItems = attendanceData.map(item => item.id);
            } else {
                selectedItems = [];
            }
        };

        const handleDeleteSelected = async () => {
            if (selectedItems.length === 0) return;

            for (const id of selectedItems) {
                const itemToDelete = attendanceData.find(item => item.id === id);
                const confirmed = window.confirm(`Yakin ingin menghapus data atas nama ${itemToDelete.name} pada tanggal ${formatDate(itemToDelete.date)}?`);
                if (confirmed) {
                    await axios.post('/api/attendances', {
                        action: 'deleteItems',
                        ids: [id],
                    });
                }
            }
            fetchData(); // Refresh data after deletion
            selectedItems = []; // Reset selected items
        };

        const renderTable = (data) => {
            const attendanceBody = document.getElementById('attendanceBody');
            attendanceBody.innerHTML = ''; // Clear existing rows

            const totalItems = data.length; 
            const indexOfLastAttendance = currentPage * itemsPerPage;
            const indexOfFirstAttendance = indexOfLastAttendance - itemsPerPage;
            const displayedAttendanceData = data.slice(indexOfFirstAttendance, indexOfLastAttendance);

            displayedAttendanceData.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><input type="checkbox" onchange="handleCheckboxChange(${item.id})"></td>
                    <td>${item.name}</td>
                    <td>${formatDate(item.date)}</td>
                    <td>${item.timeIn}</td>
                    <td>${item.timeOut}</td>
                    <td>${item.kegiatan}</td>
                    <td>
                        <button onclick="handleEditId(${JSON.stringify(item)})">Edit</button>
                    </td>
                `;
                attendanceBody.appendChild(row);
            });
        };

        const renderPagination = () => {
            const paginationControls = document.getElementById('paginationControls');
            paginationControls.innerHTML = ''; // Clear existing pagination

            const totalItems = filteredAttendanceData.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const pages = getPagination(totalPages);

            pages.forEach(page => {
                const pageButton = document.createElement('button');
                pageButton.innerHTML = page;
                pageButton.onclick = () => {
                    if (page !== '...') {
                        currentPage = page;
                        renderTable(filteredAttendanceData);
                    }
                };
                paginationControls.appendChild(pageButton);
            });
        };

        const getPagination = (totalPages) => {
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

        // Initialize data on load
        fetchData();
    </script>
</body>
</html>
