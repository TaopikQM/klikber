<style>

.card-box.profile-header {
    margin: 0;
}
.card-box {
    background-color: #fff;
    border-radius: 4px;
    margin-bottom: 30px;
    padding: 20px;
    position: relative;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1);
}
.profile-view .profile-img .avatar {
    font-size: 24px;
    height: 120px;
    line-height: 120px;
    margin: 0;
    width: 120px;
}
.profile-img .avatar {
    font-size: 24px;
    height: 80px;
    line-height: 80px;
    margin: 0;
    width: 80px;
}
.profile-img-wrap img {
    width: 120px;
    height: 120px;
}
.avatar {
    background-color: #aaa;
    border-radius: 100%;
    color: #fff;
    display: inline-block;
    font-weight: 500;
    height: 38px;
    line-height: 38px;
    margin: 0 10px 0 0;
    overflow: hidden;
    text-align: center;
    text-decoration: none;
    text-transform: uppercase;
    vertical-align: middle;
    width: 38px;
    position: relative;
    white-space: nowrap;
}
img {
    vertical-align: middle;
    border-style: none;
}
.staff-id {
    margin-top: 5px;
}
.staff-msg {
    margin-top: 30px;
}
.profile-info-left {
    border-right: 2px dashed #ccc;
}
.profile-view .profile-img-wrap {
    height: 150px;
    width: 150px;
}
.profile-img-wrap {
    height: 120px;
    position: absolute;
    width: 120px;
    overflow: hidden;
}
.profile-view .profile-basic {
    margin-left: 170px;
}
li .text {
    flex: 1;
}
</style>
<div class="page-wrapper">
<?php
if($this->session->flashdata('notif') != NULL){
    $tep=$this->session->flashdata('notif')['tipe'];
    $is=$this->session->flashdata('notif')['isi'];
    $cs = array('1' =>'alert-primary' ,'2' =>'alert-warning','3' =>'alert-danger');
?>
<div class="alert <?php echo $cs[$tep];?> alert-dismissible show fade">
  <div class="alert-body">
    <button class="close" data-dismiss="alert">
      <span>&times;</span>
    </button>
    <?php echo $is;?>
  </div>
</div>
<?php }
?>
    <div class="content">
        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">My Profile</h4>
            </div>
            <div class="col-sm-5 col-6 text-right m-b-30">
                <a href="<?php 
                    $dxc = $this->encryption->encrypt(base64_encode($id)); 
                    $ff = str_replace(array('+', '/', '='), array('-', '_', '~'), $dxc); 
                    echo base_url() . strtolower($role) . '/edit/' . $ff; 
                ?>" class="btn btn-primary btn-rounded">

                              
                    <i class="fa fa-pencil"></i> Edit Profile
                </a>

                
            </div>
            
           
                
            </div>
        </div>
        
        <div class="card-box profile-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="profile-view">
                        <div class="profile-img-wrap">
                       

                            <div class="profile-img">
                                <a href="#"><img class="avatar" src="<?php echo base_url()?>harta/morsip/assets/img/users/user.png" alt=""></a>
                            </div>
                            
                        </div>
                        <div class="profile-basic">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="profile-info-left">
                                        <h3 class="user-name m-t-0 mb-0"><?php echo $name; ?></h3>
                                        <!-- <small class="text-muted">Username : <?php echo $username; ?></small><br/> -->
                                        <small class="text-muted"><?php echo $role; ?></small>
                                        <div class="staff-id">Username: <?php echo $username; ?></div>
                                        <br/>
                                        <div class="staff-msg">
                                            <a href="<?php 
                                                $dxc = $this->encryption->encrypt(base64_encode($idus)); 
                                                $ff = str_replace(array('+', '/', '='), array('-', '_', '~'), $dxc); 
                                                echo base_url() . 'landing/edit_pass/' . $ff; 
                                            ?>" class="btn btn-primary btn-rounded">
                                                <i class="fa fa-pencil"></i> Edit Password
                                            </a>
                                        </div>
                                        
                                        <!-- <?php echo $idus; ?> -->
                                        <!-- <div class="staff-id">Employee ID: DR-0001</div> -->
                                        <!-- <div class="staff-msg">
                                            <a href="chat.html" class="btn btn-primary">Send Message</a>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <!-- <ul class="personal-info">
                                        <?php if (isset($user_data)): ?>
                                            <?php foreach ($user_data as $key => $value): ?>
                                                <li>
                                                    <span class="title"><?php echo ucfirst($key); ?>:</span>
                                                    <span class="text"><?php echo $value; ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                       
                                    </ul> -->
                                    <!-- <div id="dynamic-content"></div> -->
                                    
                                    <div id="template-container" >
                                        <div class="data-template" data-id="1" data-name="John Doe" data-role="Dokter">
                                            <?php if (isset($user_data) && isset($role)): ?>
                                                <!-- <small class="text-muted">Role : <?php echo $role; ?></small> -->
                                                <ul style="list-style: none; padding: 0;">
                                                
                                                <?php if ($role === 'Admin'): ?>
                                                    
                                                    <li class="d-flex justify-content-between align-items-baseline">
                                                        <span class="text" style="flex: 0 0 60px;">Nama</span>
                                                        <span class="title" style="flex: 0 0 20px;">:</span>
                                                        <span class="text"><?php echo $user_data->nama; ?></span>
                                                    </li>
                                                    <li class="d-flex justify-content-between align-items-baseline">
                                                        <span class="text" style="flex: 0 0 60px;">Alamat</span>
                                                        <span class="title" style="flex: 0 0 20px;">:</span>
                                                        <span class="text"><?php echo $user_data->alamat; ?></span>
                                                    </li>
                                                    <li class="d-flex justify-content-between align-items-baseline">
                                                        <span class="text" style="flex: 0 0 60px;">No Hp</span>
                                                        <span class="title" style="flex: 0 0 20px;">:</span>
                                                        <span class="text"><?php echo $user_data->no_hp; ?></span>
                                                    </li>
                                                <?php elseif ($role === 'Dokter'): ?>
                                                    
                                                    <li class="d-flex justify-content-between align-items-baseline">
                                                        <span class="text" style="flex: 0 0 60px;">Nama</span>
                                                        <span class="title" style="flex: 0 0 20px;">:</span>
                                                        <span class="text"><?php echo $user_data->nama; ?></span>
                                                    </li>
                                                    <li class="d-flex justify-content-between align-items-baseline">
                                                        <span class="text" style="flex: 0 0 60px;">Alamat</span>
                                                        <span class="title" style="flex: 0 0 20px;">:</span>
                                                        <span class="text"><?php echo $user_data->alamat; ?></span>
                                                    </li>
                                                    <li class="d-flex justify-content-between align-items-baseline">
                                                        <span class="text" style="flex: 0 0 60px;">No HP</span>
                                                        <span class="title" style="flex: 0 0 20px;">:</span>
                                                        <span class="text"><?php echo $user_data->no_hp; ?></span>
                                                    </li>
                                                    <li class="d-flex justify-content-between align-items-baseline">
                                                        <span class="text" style="flex: 0 0 60px;">Poli</span>
                                                        <span class="title" style="flex: 0 0 20px;">:</span>
                                                        <span class="text"><?php echo $user_data->nama_poli; ?></span>
                                                        
                                                    </li>
                                                <?php elseif ($role === 'Pasien'): ?>
                                                    
                                                    <li class="d-flex justify-content-between align-items-baseline">
                                                        <span class="text" style="flex: 0 0 60px;">Nama</span>
                                                        <span class="title" style="flex: 0 0 20px;">:</span>
                                                        <span class="text"><?php echo $user_data->nama; ?></span>
                                                    </li>
                                                    <li class="d-flex justify-content-between align-items-baseline">
                                                        <span class="text" style="flex: 0 0 60px;">Alamat</span>
                                                        <span class="title" style="flex: 0 0 20px;">:</span>
                                                        <span class="text"><?php echo $user_data->alamat; ?></span>
                                                    </li>
                                                    <li class="d-flex justify-content-between align-items-baseline">
                                                        <span class="text" style="flex: 0 0 60px;">No KTP</span>
                                                        <span class="title" style="flex: 0 0 20px;">:</span>
                                                        <span class="text"><?php echo $user_data->no_ktp; ?></span>
                                                    </li>
                                                    <li class="d-flex justify-content-between align-items-baseline">
                                                        <span class="text" style="flex: 0 0 60px;">No Hp</span>
                                                        <span class="title" style="flex: 0 0 20px;">:</span>
                                                        <span class="text"><?php echo $user_data->no_hp; ?></span>
                                                    </li>
                                                    <li class="d-flex justify-content-between align-items-baseline">
                                                        <span class="text" style="flex: 0 0 60px;">NO RM</span>
                                                        <span class="title" style="flex: 0 0 20px;">:</span>
                                                        <span class="text"><?php echo $user_data->no_rm; ?></span>
                                                    </li>
                                                <?php else: ?>
                                                    <li>Role tidak dikenali.</li>
                                                <?php endif; ?>
                                            </ul>
                                            <?php else: ?>
                                                <p>Data user atau role tidak tersedia.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    echo "<pre>";
                                    print_r($user_data);
                                    echo "</pre>";
                                ?>
                                
                            </div>
                        </div>
                    </div>                        
                </div>
            </div>
        </div>
        <div class="profile-tabs">
            <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="nav-item"><a class="nav-link active" href="#about-cont" data-toggle="tab">Profile</a></li>
                <!-- <li class="nav-item"><a class="nav-link" href="#bottom-tab2" data-toggle="tab">Profile</a></li> -->
                </ul>
            <div class="tab-content">
                <div class="tab-pane show active" id="about-cont">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-box">
                                 <div id="dynamic-content"></div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-12">
                            <div class="card-box">
                                <h3 class="card-title">Education Informations</h3>
                                <div class="experience-box">
                                    <ul class="experience-list">
                                        <li>
                                            <div class="experience-user">
                                                <div class="before-circle"></div>
                                            </div>
                                            <div class="experience-content">
                                                <div class="timeline-content">
                                                    <a href="#/" class="name">International College of Medical Science (UG)</a>
                                                    <div>MBBS</div>
                                                    <span class="time">2001 - 2003</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="experience-user">
                                                <div class="before-circle"></div>
                                            </div>
                                            <div class="experience-content">
                                                <div class="timeline-content">
                                                    <a href="#/" class="name">International College of Medical Science (PG)</a>
                                                    <div>MD - Obstetrics & Gynaecology</div>
                                                    <span class="time">1997 - 2001</span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-box mb-0">
                                <h3 class="card-title">Experience</h3>
                                <div class="experience-box">
                                    <ul class="experience-list">
                                        <li>
                                            <div class="experience-user">
                                                <div class="before-circle"></div>
                                            </div>
                                            <div class="experience-content">
                                                <div class="timeline-content">
                                                    <a href="#/" class="name">Consultant Gynecologist</a>
                                                    <span class="time">Jan 2014 - Present (4 years 8 months)</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="experience-user">
                                                <div class="before-circle"></div>
                                            </div>
                                            <div class="experience-content">
                                                <div class="timeline-content">
                                                    <a href="#/" class="name">Consultant Gynecologist</a>
                                                    <span class="time">Jan 2009 - Present (6 years 1 month)</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="experience-user">
                                                <div class="before-circle"></div>
                                            </div>
                                            <div class="experience-content">
                                                <div class="timeline-content">
                                                    <a href="#/" class="name">Consultant Gynecologist</a>
                                                    <span class="time">Jan 2004 - Present (5 years 2 months)</span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
                <!-- <div class="tab-pane" id="bottom-tab2">
                    <ul class="personal-info">
                        <?php if (isset($user_data)): ?>
                            <?php foreach ($user_data as $key => $value): ?>
                                <li>
                                    <span class="title"><?php echo ucfirst($key); ?>:</span>
                                    <span class="text"><?php echo $value; ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div> -->
                
            </div>
        </div>
    </div>
</div>


<script>
    // Fungsi untuk menampilkan semua data dari template
    function loadAllTemplates() {
        // Ambil elemen container template
        const templateContainer = document.getElementById('template-container');
        
        // Ambil semua elemen dengan class 'data-template' dari container
        const templates = templateContainer.querySelectorAll('.data-template');
        
        // Target tempat untuk menampilkan data
        const dynamicContent = document.getElementById('dynamic-content');
        
        // Loop melalui setiap template dan tampilkan di dynamic-content
        templates.forEach(template => {
            // Clone elemen template
            const clonedTemplate = template.cloneNode(true);
            
            // Pastikan elemen yang ditampilkan terlihat
            clonedTemplate.style.display = 'block';
            
            // Tambahkan ke dalam dynamic-content
            dynamicContent.appendChild(clonedTemplate);
        });
    }

    // Jalankan fungsi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', loadAllTemplates);
</script>