<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Attendances</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
   
    <!-- <script src="https://unpkg.com/react/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom/umd/react-dom.development.js"></script> -->
    <script src="https://unpkg.com/babel-standalone/babel.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
    <script src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>


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

            
            // const handleDeleteSelected = () => {
            //     if (selectedItems.length === 0) return; // Tidak melakukan apa-apa jika tidak ada yang dipilih
            //         // Loop melalui setiap ID yang dipilih
            //         selectedItems.forEach(id => {
            //         const itemToDelete = filteredAttendanceData.find(item => item.id === id);
                    
            //         // Tampilkan dialog konfirmasi untuk setiap item
            //         const confirmed = window.confirm(`Yakin ingin menghapus data atas nama ${itemToDelete.name} pada tanggal ${formatDate(itemToDelete.date)}?`);
                    
            //         if (confirmed) {
            //             const confirmSecond = window.confirm("Apakah Anda benar-benar yakin ingin menghapus data ini?");
            //             if (confirmSecond) {
            //             // Hapus item jika dikonfirmasi
            //             axios.post('AttendanceController/deleteItems', {
            //                 action: 'deleteItems',
            //                 ids: itemToDelete,//[id], // Hapus hanya satu item pada satu waktu
            //             })
            //             .then((response) => {
            //                 console.log(`Deleted: ${itemToDelete.name}`);
            //                 // Lakukan update data jika perlu
            //                 setAttendanceData(prevData => prevData.filter(item => item.id !== id)); // Perbarui data setelah penghapusan
            //             })
            //             .catch((error) => {
            //                 console.error('Error deleting item:', error);
            //             }); 
            //             }
            //         }
            //         }); 
            //         // Reset selectedItems setelah proses selesai
            //     setSelectedItems([]);
            // };
//             const handleDeleteSelected = () => {
//     if (selectedItems.length === 0) return; // Tidak melakukan apa-apa jika tidak ada yang dipilih
    
//     // Loop melalui setiap ID yang dipilih
//     selectedItems.forEach(id => {
//         const itemToDelete = filteredAttendanceData.find(item => item.id === id);
        
//         // Tampilkan dialog konfirmasi untuk setiap item
//         const confirmed = window.confirm(`Yakin ingin menghapus data atas nama ${itemToDelete.name} pada tanggal ${formatDate(itemToDelete.date)}?`);
        
//         if (confirmed) {
//             const confirmSecond = window.confirm("Apakah Anda benar-benar yakin ingin menghapus data ini?");
//             if (confirmSecond) {
//                 console.log('Menghapus item dengan data:', {
//                     action: 'deleteItems',
//                     ids: itemToDelete
//                 }); // Debug data yang akan dikirim

//                 // Hapus item jika dikonfirmasi
//                 axios.post('http://localhost:8081/sekrup/AttendanceController/deleteItems', {
//                     action: 'deleteItems',
//                     ids: [itemToDelete], // [id] => Mengirimkan array ID
//                 })
//                 .then((response) => {
//                     console.log(`Deleted: ${itemToDelete.name}`);
//                     setAttendanceData(prevData => prevData.filter(item => item.id !== id)); // Perbarui data setelah penghapusan
//                 })
//                 .catch((error) => {
//                     console.error('Error deleting item:', error);
//                 }); 
//             }
//         }
//     }); 
//     setSelectedItems([]);
// };
const handleDeleteSelected = () => {
    if (selectedItems.length === 0) return; // Tidak melakukan apa-apa jika tidak ada yang dipilih

    // Loop melalui setiap ID yang dipilih
    selectedItems.forEach(id => {
        const itemToDelete = filteredAttendanceData.find(item => item.id === id);

        // Pastikan item ditemukan sebelum melanjutkan
        if (!itemToDelete) {
            console.warn(`Item dengan ID ${id} tidak ditemukan.`);
            return;
        }

        // Tampilkan dialog konfirmasi untuk setiap item
        const confirmed = window.confirm(`Yakin ingin menghapus data atas nama ${itemToDelete.name} pada tanggal ${formatDate(itemToDelete.date)}?`);

        if (confirmed) {
            const confirmSecond = window.confirm("Apakah Anda benar-benar yakin ingin menghapus data ini?");
            if (confirmSecond) {
                // Debug data yang akan dikirim
                console.log('Menghapus item dengan data:', {
                    action: 'deleteItems',
                    ids: itemToDelete.id // Kirim hanya ID
                });

                // Kirim permintaan penghapusan berdasarkan ID saja
                axios.post('AttendanceController/deleteItems', {
                    action: 'deleteItems',
                    ids: [itemToDelete.id] // Hanya mengirimkan array ID
                })
                .then((response) => {
                    console.log(`Deleted: ${itemToDelete.name}`);
                    setAttendanceData(prevData => prevData.filter(item => item.id !== id)); // Perbarui data setelah penghapusan
                })
                .catch((error) => {
                    console.error('Error deleting item:', error);
                });
            }
        }
    });

    // Reset pilihan setelah selesai
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
                            { displayedAttendanceData.length > 0 ?
                                displayedAttendanceData.map(item => (
                                    <tr key={item.id} className="border-b hover:bg-gray-100">
                                       
                                        <td className="text-center md:p-4 p-0 border-r">{item.name}</td>
                                        <td className="text-center md:p-4 p-0 border-r">{item.date}</td>
                                        <td className="py-2 px-4 text-left border-r"> 
                                        {editUserId === item.id ? (
                                            <input
                                                type="time"
                                                value={editUserTimeIn}
                                                onChange={(e) => setEditUserTimeIn(e.target.value)}
                                                className="border border-gray-400 px-2 py-1 rounded-md"
                                            />
                                        ) : (
                                            item.timein
                                        )}
                                        </td>
                                        <td className="py-2 px-4 text-left border-r">
                                        {editUserId === item.id ? (
                                            <input
                                                type="time"
                                                value={editUserTimeOut}
                                                onChange={(e) => setEditUserTimeOut(e.target.value)}
                                                className="border border-gray-400 px-2 py-1 rounded-md"
                                            />
                                        ) : (
                                            item.timeot
                                        )}
                                        </td>
                                        
                                        <td className="text-center md:p-4 p-0 border-r">{calculateTotalHours(item.timein, item.timeot)}</td> {/* Total Jam Kerja */}
                                        <td className="text-center md:p-4 p-0 border-r">{item.mhs_univ}</td> {/* Universitas */}
                                        <td className="text-center md:p-4 p-0 border-r">{item.mhs_jurusan}</td> {/* Jurusan */}
                                        <td className="py-2 px-4 text-left border-r">{editUserId === item.id ? (
                                        <input
                                            type="text"
                                            value={editUserKegiatan}
                                            onChange={(e) => setEditUserKegiatan(e.target.value)}
                                            className="border border-gray-400 px-2 py-1 rounded-md"
                                        />
                                        ) : (
                                            item.kegiatan
                                        )}
                                        </td>
                                        
                                    </tr>
                                )): (
                                    <tr>
                                        <td colSpan="5" className="py-2 px-4 text-center">Data tidak ditemukan</td>
                                    </tr>
                                    )
                                }
                                    

                            </tbody>
                        </table>
                        {totalItems > itemsPerPage && (
                            <nav className="m-4 flex items-center flex-column flex-wrap md:flex-row justify-between pt-4" aria-label="Table navigation">
                                <span className="text-sm font-normal text-gray-500 dark:text-gray-400">
                                    Page {currentPage} of {totalPages}
                                </span>
                                
                                <ul className="inline-flex items-center -space-x-px">
                                    <li>
                                        <button
                                            onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))}
                                            className="py-2 px-4 border border-gray-300 rounded-l-lg dark:border-gray-600 dark:bg-gray-400 dark:text-white"
                                            disabled={currentPage === 1}
                                        >
                                            Previous
                                        </button>
                                    </li>
                                    {/* {[...Array(totalPages)].map((_, i) => (
                                        <li key={i + 1}>
                                            <button
                                                onClick={() => setCurrentPage(i + 1)}
                                                className={`py-2 px-4 border border-gray-300 ${currentPage === i + 1? 'border-gray-600 text-gray-600' : 'text-gray-500 dark:text-gray-400'}`}
                                                > {i +1}</button>
                                                </li>
                                    ))} */}
                                    {getPagination().map((page, index) => (
                                        <li key={index}>
                                            {page === '...' ? (
                                                <span className="py-2 px-4">...</span>
                                            ) : (
                                                <button
                                                    onClick={() => setCurrentPage(page)}
                                                    className={`py-2 px-4 border border-gray-300 ${currentPage === page ? 'border-gray-600 text-gray-600' : 'text-gray-500 dark:text-gray-400'}`}
                                                >
                                                    {page}
                                                </button>
                                            )}
                                        </li>
                                    ))}
                                    <li>
                                        <button
                                            onClick={() => setCurrentPage(prev => Math.min(prev + 1, totalPages))}
                                            className="py-2 px-4 border border-gray-300 rounded-r-lg dark:border-gray-600 dark:bg-gray-400 dark:text-white"
                                            disabled={currentPage === totalPages}
                                        >
                                            Next
                                        </button>
                                    </li>
                                </ul>
                            </nav>
                        )}

                       
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