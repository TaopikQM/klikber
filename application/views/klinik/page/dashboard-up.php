<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/datatables.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/prism/prism.css">

<!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="harta/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="harta/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="harta/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="harta/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="harta/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="harta/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="harta/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="harta/plugins/summernote/summernote-bs4.min.css">

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js'></script>



<section class="section">

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card"> 
          <div class="card-header">
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info"  style="margin-bottom: 20px;">
                    <div class="inner">
                        <h3><?php echo $total_dokter; ?></h3>
                        
                        <p>Total Dokter</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>
                    <a href="dokter/tab" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success"  style="margin-bottom: 20px;">
                    <div class="inner">
                        <h3><?php echo $total_pasien; ?></h3>

                        <p>Total Pasien</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-body"></i>
                    </div>
                    <a href="pasien/tab" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning"  style="margin-bottom: 20px;">
                    <div class="inner">
                        <h3><?php echo $total_obat; ?></h3>

                        <p>Total Obat</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-medkit"></i>
                    </div>
                    <a href="obat" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger"  style="margin-bottom: 20px;">
                    <div class="inner">
                        <h3><?php echo $total_poli; ?></h3>

                        <p>Total Poli</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-home"></i>
                    </div>
                    <a href="poli/tab" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                 <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-light"  style="margin-bottom: 20px;">
                    <div class="inner">
                        <h3><?php echo $total_users; ?></h3>

                        <p>Total Users</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="users/tab" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-secondary"  style="margin-bottom: 20px;">
                    <div class="inner">
                        <h3><?php echo $total_admin; ?></h3>

                        <p>Total Admin</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="admin" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-purple"  style="margin-bottom: 20px;">
                    <div class="inner">
                        <h3><?php echo $total_role; ?></h3>

                        <p>Total Role</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-settings"></i>
                    </div>
                    <a href="role" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            
                    
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="<?php echo base_url()?>harta/morsip/assets/js/jquery-3.6.3.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script  type="text/javascript">
    //ini kalender
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: <?php echo json_encode($events); ?>,

            eventContent: function(info) {
                // Membuat elemen yang akan berisi teks di tengah event
                var titleEl = document.createElement('div');
                titleEl.innerHTML = info.event.title;
                titleEl.style.textAlign = 'center';

                return { 
                    domNodes: [titleEl]
                };
            },

            eventDidMount: function(info) {
                //  background berdasarkan jnspinjam
                info.el.style.backgroundColor = info.event.extendedProps.backgroundColor;
                
                //  lingkaran kecil di pojok untuk status
                var statusIndicator = document.createElement('div');
                statusIndicator.className = info.event.extendedProps.statusColor; // Gunakan className dari statusIndicator
                statusIndicator.style.position = 'absolute';
                statusIndicator.style.bottom = '2px';
                statusIndicator.style.right = '2px';
                statusIndicator.style.width = '12px';
                statusIndicator.style.height = '12px';
                statusIndicator.style.borderRadius = '50%';
                
                info.el.appendChild(statusIndicator);
            },

            eventClick: function(info) {
                alert('Peminjaman: ' + info.event.title + '\n' + 'Ket: ' + info.event.extendedProps.description);
            }
        });

        calendar.render();
    });/*
    function filterCalendar() {
        var jnspinjam = document.getElementById('jnspinjam').value;

        $.ajax({
            url: '< ?php echo site_url('pinjam/index'); ?>',
            type: 'POST',
            data: { jnspinjam: jnspinjam },
            dataType: 'json',
            success: function(data) {
                var calendar = FullCalendar.CalendarCalendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: < ?php echo json_encode($events); ?>,

                eventClick: function(info) {
                    alert('Peminjaman: ' + info.event.title + '\n' + 'Ket: ' + info.event.extendedProps.description);
                }
            });
                calendar.removeAllEvents(); // Clear existing events
                calendar.addEventSource(data); // Add new events
            },
            error: function(xhr, status, error) {
            console.error('Error:', status, error); // Log error details
            alert('Failed to load events.');
        }
            /*error: function() {
                alert('Failed to load events.');
            }* /
           
        });
    }*/

    //grafik data 
    var grafikData = <?php echo json_encode($grafik_data ); ?>;
    var jnspinjam_map = {
        'a1': 'Mobil',
        'a2': 'Ruangan',
        'a3': 'Alat'
    };

    /* var jnspinjam_colors = {
        'Mobil': '#FF5733', 
        'Ruangan': '#33FF57',
        'Alat': '#3357FF' 
    };*/

    
    var groupedData = {};
    var labels = [];
    var jnspinjamTypes = new Set();

    grafikData.forEach(function(item) {
        item.jnspinjam = jnspinjam_map[item.jnspinjam] || item.jnspinjam;
        
        item.idadmin = 'Admin ' + item.idadmin;


        if (!groupedData[item.idadmin]) {
            groupedData[item.idadmin] = {};
            labels.push(item.idadmin);
        }
        groupedData[item.idadmin][item.jnspinjam] = item.total_pinjam;
        jnspinjamTypes.add(item.jnspinjam);
    });

    jnspinjamTypes = Array.from(jnspinjamTypes);

    var datasets = jnspinjamTypes.map(function(type) {
        return {
            label: type,
            backgroundColor: getRandomColor(),
            //backgroundColor: [],
            data: labels.map(function(label) {
                return groupedData[label][type] || 0;
            })
        };
    });

    var ctxBar = document.getElementById('barChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: false, 
                },
                y: {
                    stacked: false
                }
            }
        }
    });

    // grafik lingkaran
    var pieChartData = {
        labels: [],
        datasets: [{
            label: 'Total Pinjam',
            backgroundColor: [],
            data: []
        }]
    };

    grafikData.forEach(function(item) {
        var label =  item.idadmin + ' - ' + item.jnspinjam;
        pieChartData.labels.push(label);
        pieChartData.datasets[0].data.push(item.total_pinjam);
        pieChartData.datasets[0].backgroundColor.push(getRandomColor());
    });

    var ctxPie = document.getElementById('pieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'pie',
        data: pieChartData,
        options: {
            responsive: true
        }
    });

    //warna acak
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
</script> 

</head>
<body>
