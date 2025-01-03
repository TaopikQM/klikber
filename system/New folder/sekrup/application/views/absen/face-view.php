<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aabsensi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js" id="face-api-script"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="relative flex flex-col overflow-x-auto items-center justify-center shadow-md sm:rounded-lg">
            <div class="relative w-full ">
                <?php $this->load->view('absen/Time'); ?>
            </div>

            <div class="relative text-center">
                <div class="relative w-full max-w-lg">
                    <video id="video" autoplay muted class="w-full h-auto" style="display: none;"></video>
                    <canvas id="canvas" class="absolute top-0 left-0 w-full h-auto" style="display: none;"></canvas>
                
                    <button id="startButton" class="mt-4 px-5 py-2 bg-red-700 text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm" type="button" onclick="startVideo()">
                        Start Kamera
                    </button>
                </div>
            </div>
            <div class="bg-gray-100 flex flex-col items-center justify-center">
                <div id="actionButtons" style="display: none;">
                    </div>
                        <button id="HadirButton" class="mt-4 px-5 py-2 bg-green-700 text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm" type="button" style="display: none;" onclick="handleScan()">
                            Hadir
                        </button>

                        <button id="PulangButton" class="mt-4 px-5 py-2 bg-yellow-700 text-white hover:bg-yellow-800 focus:outline-none focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm" type="button" style="display: none;" onclick="handlePulang()">
                            Pulang
                        </button>

                        <button id="closeButton" class="mt-4 px-5 py-2 bg-gray-700 text-white hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm" type="button" style="display: none;" onclick="handleClose()">
                            Close Kamera
                        </button>
                        <button id="registerButton" class="mt-4 px-5 py-2 bg-green-700 text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm" type="button" style="display: none;" onclick="handleRegister()">
                            Registrasi
                        </button>
                    </div> 
                </div>
            </div>
        </div>
    </div>
    <script>
        // Variabel status
        let loading = false;
        let cameraStarted = false;
        let isRegistered = false;
        let ipAddress = '';
        let latitude = null;
        let longitude = null;

        const kominfoLocation = { lat:-6.9948815, lon: 110.415935 }; // Ganti dengan lokasi yang sesuai
        // -6.9948815,110.415935,

        const videoElement = document.getElementById('video');
        const canvasElement = document.getElementById('canvas');

        const startVideo = async () => {
            loading = true; 
            const startButton = document.getElementById('startButton');
            startButton.innerText = 'Loading...';

            await loadModelsAndStartVideo();
            loading = false;
            // startButton.innerText = 'Start Kamera';
            // updateActionButtons();
            document.getElementById('startButton').style.display = 'none'; // Sembunyikan tombol Start Kamera
            document.getElementById('closeButton').style.display = 'block'; // Tampilkan tombol Close Kamera

            updateActionButtons(); // Menentukan tombol yang akan ditampilkan sesuai waktu saat ini
       
        };

        const updateActionButtons = () => {
            const currentHour = new Date().toLocaleString('en-US', { timeZone: 'Asia/Jakarta', hour: 'numeric', hour12: false });
            if (currentHour < 12) {
                // Sebelum jam 12, tampilkan tombol "Hadir" atau "Registrasi" jika belum terdaftar
               
                // document.getElementById('registerButton').style.display = 'block';
                document.getElementById('HadirButton').style.display = 'block';
                document.getElementById('PulangButton').style.display = 'none';
                
                document.getElementById('PulangButton').style.display = 'none';
            } else {
                // document.getElementById('registerButton').style.display = 'none';
                document.getElementById('HadirButton').style.display = 'none';
                document.getElementById('PulangButton').style.display = 'block';
            }
        }; 

        const loadModelsAndStartVideo = async () => {
            const MODEL_URL = 'https://cdn.jsdelivr.net/gh/vladmandic/face-api/model/';
            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
            // faceapi.nets.faceExpressionNet.loadFromUri(MODEL_URL);
            await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL);

            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
                videoElement.srcObject = stream;
                cameraStarted = true;
                videoElement.style.display = 'block';
                canvasElement.style.display = 'block';
                detectFaces();
            } catch (error) {
                console.error("Error accessing webcam:", error);
                alert('Gagal memulai kamera, pastikan perangkat memiliki kamera dan izinkan akses.');
            }
        };

        const detectFaces = () => {
            videoElement.addEventListener('play', () => {
                const displaySize = { width: videoElement.videoWidth, height: videoElement.videoHeight };
                canvasElement.width = displaySize.width;
                canvasElement.height = displaySize.height;

                const ctx = canvasElement.getContext('2d');

                const intervalId = setInterval(async () => {
                    if (!videoElement.paused && !videoElement.ended) {
                        const detections = await faceapi.detectAllFaces(
                            videoElement,
                            new faceapi.TinyFaceDetectorOptions()
                        ).withFaceLandmarks().withFaceDescriptors();

                        const resizedDetections = faceapi.resizeResults(detections, displaySize);
                        ctx.clearRect(0, 0, canvasElement.width, canvasElement.height);
                        faceapi.draw.drawDetections(canvasElement, resizedDetections);
                        faceapi.draw.drawFaceLandmarks(canvasElement, resizedDetections);

                        //menampilkan name terus
                        // for (const detection of resizedDetections) {
                        //     const userDescriptor = detection.descriptor;
                        //     const descriptorResponse = await fetch('absen/AttendanceController/checkDescriptor', {
                        //         method: 'POST',
                        //         headers: {
                        //             'Content-Type': 'application/json'
                        //         },
                        //         body: JSON.stringify({
                        //             action: 'checkDescriptor',
                        //             descriptor: Array.from(userDescriptor)
                        //         })
                        //     });

                        //     const responseData = await descriptorResponse.json();
                        //     if (responseData.exists) {
                        //         const matchedName = responseData.name;
                        //         ctx.font = '24px Arial';
                        //         ctx.fillStyle = 'red';
                        //         ctx.fillText(matchedName, detection.detection.box.x, detection.detection.box.y);
                        //     }
                        // }
                    }
                }, 100);

                videoElement.addEventListener('pause', () => clearInterval(intervalId));
                videoElement.addEventListener('ended', () => clearInterval(intervalId));
            });
        };

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
                console.error("Geolocation is not supported by this browser.");
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

        const isWithinAllowedTime = () => {
            const now = new Date();
            const hours = now.getUTCHours() + 7; // Convert to WIB (UTC+7)
            const day = now.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday

            // Batasan waktu Datang
            if (day >= 1 && day  >= day <=5) { //jam kerja
            return hours >= 5 && hours <= 9;
            } 
            return false;
        };

        const isWithinAllowedTimeForPulang = () => {
            const now = new Date();
            const hours = now.getUTCHours() + 7; // Convert to WIB (UTC+7)
            const day = now.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday

            // Batasan waktu pulang
            if (day === 5) { // Jumat
            return hours >= 13 && hours < 24;
            } else if (day >= 1 && day <= 4) { // Senin - Kamis
            return hours >= 12 && hours <= 24;
            }
            return false;
        };

        const handleRegister = async () => {
            loading = true;
            document.getElementById('registerButton').innerText = 'Loading...'; // Tampilkan loading pada tombol Hadir
            
            const video = videoElement; // Ambil elemen video
            const detections = await faceapi.detectAllFaces(video).withFaceLandmarks().withFaceDescriptors();

            if (detections.length === 0) {
                alert('Wajah tidak terdeteksi.');
                loading = false;
                document.getElementById('registerButton').innerText = 'Registrasi'; // Kembalikan teks tombol
                return;
            }

            const userDescriptor = detections[0].descriptor;

            try {
                // Cek apakah deskriptor sudah ada 
                const descriptorResponse = await axios.post('AttendanceController/checkDescriptor', {
                    // action: 'checkDescriptor',
                    descriptor: Array.from(userDescriptor)
                });

                if (descriptorResponse.data.exists) {
                    alert('Wajah sudah terdaftar. Silakan coba lagi nanti.');
                    loading = false;
                    // document.getElementById('registerButton').innerText = 'Registrasi'; // Kembalikan teks tombol
                    isRegistered = true;
                    document.getElementById('registerButton').style.display = 'none';
        
                    updateActionButtons();
                    return;
                }

                // Minta input nama melalui prompt
                const userName = prompt('Masukkan nama Anda:');
                if (!userName) {
                    alert('Nama harus diisi.');
                    loading = false;
                    document.getElementById('registerButton').innerText = 'Registrasi'; // Kembalikan teks tombol
                    return;
                }
                // Cek apakah nama ada dan aktif 
                const userCheckResponse = await axios.post('AttendanceController/checkUser', {
                    // action: 'checkUser',
                    name: userName
                });

                if (userCheckResponse.data.exists) {
                    
                    // const isLocationValid = await checkLocationAndDistance();
                    // if (!isLocationValid) {
                    //     loading = false;
                    //     document.getElementById('registerButton').innerText = 'Registrasi'; // Kembalikan teks tombol
                    //     return;
                    // }

                    nimD=userCheckResponse.data.nim;
                    nameD=userCheckResponse.data.name;
                    const data = await axios.post('AttendanceController/register', {
                        nim: nimD,
                        name: nameD,
                        descriptor: Array.from(userDescriptor),
                    });

                    alert('Registrasi berhasil dan data disimpan. Silahkan Melakukan Presensi');
                    loading = false;
                    isRegistered = true;
                    document.getElementById('registerButton').style.display = 'none';
        
                    updateActionButtons();
                } else {
                    alert('Nama tidak ditemukan di sistem. Silakan hubungi admin.');
                    loading = false;
                    document.getElementById('registerButton').innerText = 'Registrasi'; // Kembalikan teks tombol
                    return;
                }

            } catch (error) {
                if (error.response) {
                    if (error.response.status === 400) {
                        alert('Data tidak sesuai, silakan periksa kembali input Anda.');
                    } else {
                        alert(`Kesalahan saat registrasi: ${error.response.data.message || 'Silakan coba lagi.'}`);
                    }
                } else {
                    alert('Terjadi kesalahan, silakan coba lagi.');
                }
            } finally {
                loading = false;
                document.getElementById('registerButton').innerText = 'Registrasi'; // Kembalikan teks tombol
            }
        };

        const handleScan = async () => {
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
            // Cek apakah waktu dalam range absensi (06:00 - 09:00 WIB Senin - Jumat)
                if (!isWithinAllowedTime()) {
                    alert('Absensi hanya bisa dilakukan antara pukul 06:00 WIB dan 09:00 WIB pada hari Senin hingga Jumat.');
                    loading = false;
                    document.getElementById('HadirButton').innerText = 'Hadir'; // Kembalikan teks tombol
                    return;
                }

                const video = document.getElementById('video');
                const detections = await faceapi.detectAllFaces(video).withFaceLandmarks().withFaceDescriptors();

                if (detections.length === 0) {
                    alert('Wajah tidak terdeteksi.');
                    loading = false;
                    document.getElementById('HadirButton').innerText = 'Hadir'; // Kembalikan teks tombol
                    return;
                }

                const userDescriptor = detections[0].descriptor;
                const locationLat = latitude ;
                const locationLong= longitude ;
                // console.log(locationLat);
                // console.log(locationLong);
                await checkLocationAndDistance();
                // console.log(checkLocationAndDistance());
               
                const descriptorResponse = await fetch('AttendanceController/checkDescriptor', {
                    // action: 'checkDescriptor',
                    // descriptor: Array.from(userDescriptor)
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'checkDescriptor',
                        descriptor: Array.from(userDescriptor)
                    })
                });
                const responseData = await descriptorResponse.json();
            

                if (responseData.exists) {
                    const matchedName = responseData.name;
                
                    const userCheckResponse = await axios.post('AttendanceController/checkUser', 
                       
                        JSON.stringify({
                            action: 'checkUser',
                            name: responseData.name//Array.from(userDescriptor)
                        }),
                        {
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        }
                    );

                    if (userCheckResponse.data.exists === false) {
                        alert('Pengguna tidak ditemukan di sistem. Silakan hubungi admin.');
                        loading = false;
                        document.getElementById('HadirButton').innerText = 'Hadir'; // Kembalikan teks tombol
                        return;
                    }
                    else{
                        // const registeredUser = userCheckResponse.data; // Data user yang terdaftar
                        const userName = userCheckResponse.data.name; // Nama diambil dari data user yang terdaftar

                        const attendanceCheckResponse = await axios.post('AttendanceController/checkAttendance', {
                            // action: 'checkAttendance',
                            name: userName,
                            date: formattedDate
                        });
                    
                        if (!attendanceCheckResponse.data.exists) {
                            const saveAttendanceResponse = await axios.post('AttendanceController/saveAttendance', {
                            // action: 'saveAttendance',
                            //id: generedId,//generateBase64Id(),
                            date: formattedDate,
                            ip: ipAddress,
                            kegiatan: '', // Kegiatan default kosong
                            latitude: locationLat,
                            longitude: locationLong, // Lokasi pengguna saat ini
                            name: userName,
                            timein: formattedTime, // Waktu masuk
                            timeot: '' // Waktu keluar default kosong
                            });
                                            alert(`Absensi berhasil untuk: ${userName}\nSemangat menjalani hari ini!`);
                            loading = false;
                            document.getElementById('HadirButton').innerText = 'Hadir'; // Kembalikan teks tombol
                        }//(attendanceCheckResponse.data.exists)
                        else {
                    
                            alert(`Anda sudah melakukan Kehadiran untuk hari ini.\n${userName}\nSemangat menjalani hari ini!`);
                            loading = false;
                            document.getElementById('HadirButton').innerText = 'Hadir'; // Kembalikan teks tombol
                            return;
                        }
                    }

                    
                } else {
                    alert('Pengguna tidak ditemukan. Silakan registrasi terlebih dahulu.');
                    isRegistered=false;
                    loading = false;
                    document.getElementById('registerButton').style.display = 'block'; // Tampilkan tombol Registrasi
                    document.getElementById('HadirButton').style.display = 'block'; // Sembunyikan tombol Hadir
                    document.getElementById('PulangButton').style.display = 'none'; // Sembunyikan tombol Pulang
                }

                } catch (error) {
                console.error('Error during scan:', error);
                alert('Terjadi kesalahan, silakan coba lagi.');
                loading = false;
                document.getElementById('HadirButton').innerText = 'Hadir'; // Kembalikan teks tombol
            } finally {
            loading = false;
            document.getElementById('HadirButton').innerText = 'Hadir'; // Kembalikan teks tombol
            }
        };

        const handlePulang = async () => {
            loading = true;
            document.getElementById('PulangButton').innerText = 'Loading...'; // Kembalikan teks tombol
            const date = new Date();

            const formattedDate = new Intl.DateTimeFormat('en-CA', { timeZone: 'Asia/Jakarta' }).format(date);

            const formattedTime = new Intl.DateTimeFormat('en-GB', { 
                timeZone: 'Asia/Jakarta', 
                hour: '2-digit', 
                minute: '2-digit', 
                hour12: false
            }).format(date) + ' WIB';
            
            try {
                // if (!isWithinAllowedTimeForPulang()) {
                //     alert('Pulang hanya bisa dilakukan antara pukul 14:00 WIB dan 18:00 WIB pada hari Senin hingga Jumat.');
                //     loading = false;
                //     document.getElementById('PulangButton').innerText = 'Pulang'; // Kembalikan teks tombol
                //     return;
                // }

                const video = document.getElementById('video');

                const detections = await faceapi.detectAllFaces(video).withFaceLandmarks().withFaceDescriptors();

                if (detections.length === 0) {
                    alert('Wajah tidak terdeteksi.');
                    loading = false;
                    document.getElementById('PulangButton').innerText = 'Pulang'; // Kembalikan teks tombol
                    return;
                }

                const userDescriptor = detections[0].descriptor;
                const locationLat = latitude ;
                const locationLong= longitude ;
                await checkLocationAndDistance();

                const descriptorResponse = await fetch('AttendanceController/checkDescriptor', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'checkDescriptor',
                        descriptor: Array.from(userDescriptor)
                    })
                });
                const responseData = await descriptorResponse.json();

                if (!responseData.exists) {
                    alert('Pengguna tidak ditemukan. Silakan registrasi terlebih dahulu.');
                    return;
                    document.getElementById('registerButton').style.display = 'block'; // Tampilkan tombol Registrasi
                    document.getElementById('HadirButton').style.display = 'none'; // Sembunyikan tombol Hadir
                    document.getElementById('PulangButton').style.display = 'block'; // Sembunyikan tombol Pulang
                }
                if (responseData.exists) {
                    const userCheckResponse = await fetch('AttendanceController/checkUser', {
                        
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: 'checkUser',
                            name: responseData.name
                        })
                    });

                    const userCheckData = await userCheckResponse.json();

                    if (!userCheckData.exists) {
                        alert('Pengguna tidak ditemukan di sistem. Silakan hubungi admin.');
                        loading = false;
                        document.getElementById('PulangButton').innerText = 'Pulang'; // Kembalikan teks tombol
                        return;
                    }

                    const userName = userCheckData.name;
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
                    // console.log(attendanceExist)
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
                                // console.log(responseData);

                                if (saveAttendanceResponse.ok) {
                                    alert(`Absensi baru berhasil disimpan untuk hari ini.\n${responseData.name}\nKegiatan: ${kegiatan}\nSelamat istirahat!`);
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
                }
                            
            }
            catch (error) {
                console.error('Error during check-out:', error);
                loading = false;
                document.getElementById('PulangButton').innerText = 'Pulang';
            } 
            finally {
                loading = false;
                document.getElementById('PulangButton').innerText = 'Pulang';
            }
        };
        const closeModal = () => {
            document.getElementById('kegiatanModal').remove();
            document.getElementById('modalOverlay').remove();
        };

        const handleClose = async () => {
            loading = true;
            document.getElementById('closeButton').innerText = 'Loading...'; 
            
            window.location.reload();
            loading = false;
        };
        //  setInterval(updateActionButtons, 300000);

   
   </script>
</body>
</html>








