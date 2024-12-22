<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            
                
                    <button id="registerButton" class="mt-4 px-5 py-2 bg-green-700 text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm" type="button" style="display: none;" onclick="handleRegister()">
                        Registrasi
                    </button>
                    <button id="closeButton" class="mt-4 px-5 py-2 bg-gray-700 text-white hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm" type="button" style="display: none;" onclick="handleClose()">
                        Close Kamera
                    </button>
                    
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
            
            document.getElementById('registerButton').style.display = 'block'; 
            // updateActionButtons(); // Menentukan tombol yang akan ditampilkan sesuai waktu saat ini
       
        };

        const loadModelsAndStartVideo = async () => {
            const MODEL_URL = 'https://cdn.jsdelivr.net/gh/vladmandic/face-api/model/';
            faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
            faceapi.nets.faceExpressionNet.loadFromUri(MODEL_URL);
            faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL);

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
                if (distanceToKominfo > 1) {
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
        
                    // updateActionButtons();
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
        
                    // updateActionButtons();
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







