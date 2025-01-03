<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/datatables.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>harta/morsip/assets/bundles/prism/prism.css">

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
                <div class="col-lg-4 col-md-2 col-lg-6 col-xl-6">
                    <div class="card card-statistic-2">
                        <div class="card-stats">
                            <div class="card-stats-title">Piminjaman 
                            <form method="get" action="<?= base_url('pinjam/index'); ?>">
                                <select id="bulan" name="bulan">
                                    <?php 
                                    $nama_bulan = [
                                        0 => 'Bulan',
                                        1 => 'Januari',
                                        2 => 'Februari',
                                        3 => 'Maret',
                                        4 => 'April',
                                        5 => 'Mei',
                                        6 => 'Juni',
                                        7 => 'Juli',
                                        8 => 'Agustus',
                                        9 => 'September',
                                        10 => 'Oktober',
                                        11 => 'November',
                                        12 => 'Desember'
                                    ];
                                    
                                    // Mendapatkan bulan saat ini jika tidak ada filter
                                    $current_bulan = $this->input->get('bulan') ;//date('m');
                                    
                                    foreach ($nama_bulan as $num => $name) {
                                        $selected = ($current_bulan == $num) ? 'selected' : '';
                                        echo "<option value='$num' $selected>$name</option>";
                                    }
                                    ?>
                                </select>
                                
                                <select id="tahun" name="tahun">
                                    <?php 
                                    // Mendapatkan tahun saat ini jika tidak ada filter
                                    $current_tahun = $this->input->get('tahun') ;//date('Y');
                                    
                                    echo "<option value=''>Pilih Tahun</option>";
                                    for ($i = 2023; $i <= date('Y'); $i++) { 
                                        $selected = ($current_tahun == $i) ? 'selected' : '';
                                        echo "<option value='$i' $selected>$i</option>";
                                    } 
                                    ?>
                                </select>
                                
                                <button type="submit">Filter</button>
                            </form>

                            </div>
                            <div class="card-stats-items  " style="display: flex; gap: 10px;  padding: 0 20px;">
                                <div class="card-stats-item btn-secondary btn-sm rounded">
                                    <div class="card-stats-item-count"><?php echo $status_summary['total_status_0']; ?></div>
                                    <div class="card-stats-item-label">Ajuan Awal</div>
                                </div>  
                                <div class="card-stats-item btn-success btn-sm rounded">
                                    <div class="card-stats-item-count"><?php echo $status_summary['total_status_1']; ?></div>
                                    <div class="card-stats-item-label">Disetujui</div>
                                </div>
                                <div class="card-stats-item btn-danger btn-sm rounded">
                                    <div class="card-stats-item-count"><?php echo $status_summary['total_status_2']; ?></div>
                                    <div class="card-stats-item-label">Ditolak</div>
                                </div>
                            </div><hr/> 
                            <div class="card-stats-items " style="display: flex; gap: 10px;  padding: 0 20px;">
                                <a class="nav-link card-stats-item btn-warning btn-sm rounded" href="<?php echo base_url('pinjam/mobil')?>">
                                    <div >
                                        <div class="card-stats-item-count"><?php echo $status_summary['total_a1']; ?></div>
                                        <div class="card-stats-item-label">Mobil</div>
                                    </div>
                                </a>
                                  
                                <a class="nav-link card-stats-item btn-info btn-sm rounded" href="<?php echo base_url('pinjam/ruangan')?>">
                                    <div >
                                        <div class="card-stats-item-count"><?php echo $status_summary['total_a2']; ?></div>
                                        <div class="card-stats-item-label">Ruangan</div>
                                    </div>
                                </a>
                                
                                <a class="nav-link card-stats-item btn-primary btn-sm rounded" href="<?php echo base_url('pinjam/alat')?>">
                                    <div>
                                        <div class="card-stats-item-count "><?php echo $status_summary['total_a3']; ?></div>
                                        <div class="card-stats-item-label">Alat</div>
                                    </div>
                                </a>
                                
                                <div class="card-stats-item btn-success btn-sm rounded">
                                    <div class="card-stats-item-count"><?php echo $status_summary['total_keseluruhan']; ?></div>
                                    <div class="card-stats-item-labe">Total</div>
                                </div>
                            </div><hr/>
                        </div>
                        
                    </div>
                </div>
                <!--grafik-->
                <div class="col-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="card">
                        <div class="card-body">
                        <!--isi -->
                        <h4>Grafik Peminjaman</h4>
                        <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
                 <!--grafik-->
                 <!-- <div class="col-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="card">
                        <div class="card-body">
                        isi -->
                        <!-- <h4>Grafik Peminjaman</h4>    
                        <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div> -->
                
            </div>
            <div class="row">
                
                <!-- Filter Dropdown -->
                <!--<form id="filterForm">
                    <select name="jnspinjam" id="jnspinjam" onchange="filterCalendar()">
                        <option value="all">Semua</option>
                        <option value="a1">A1</option>
                        <option value="a2">A2</option>
                        <option value="a3">A3</option>
                    </select>
                </form>-->

                <!-- Kalender -->
                <div id="calendar" style="padding: 0 20px;"></div>
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
