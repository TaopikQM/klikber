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
