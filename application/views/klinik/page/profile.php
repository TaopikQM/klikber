<div class="page-wrapper">
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
            <!-- <a href="<?php 
                $dxc = $this->encryption->encrypt(base64_encode($this->session->userdata('idus'))); 
                $ff = str_replace(array('+', '/', '='), array('-', '_', '~'), $dxc); 
                echo base_url() . strtolower($role) . '/editUs/' . $ff; 
            ?>" class="btn btn-primary btn-rounded">

                              
                    <i class="fa fa-pencil"></i> Edit Pass
                </a>
            </div> -->
        </div>
        
        <div class="card-box profile-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="profile-view">
                        <div class="profile-img-wrap">
                            <div class="profile-img">
                                <a href="#"><img class="avatar" src="assets/img/doctor-03.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="profile-basic">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="profile-info-left">
                                        <h3 class="user-name m-t-0 mb-0">Username : <?php echo $username; ?></h3>
                                        <small class="text-muted">Role : <?php echo $role; ?></small>
                                        <!-- <?php echo $id; ?> -->
                                        <!-- <div class="staff-id">Employee ID: DR-0001</div> -->
                                        <!-- <div class="staff-msg">
                                            <a href="chat.html" class="btn btn-primary">Send Message</a>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="col-md-7">
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
                                </div>
                                
                            </div>
                        </div>
                    </div>                        
                </div>
            </div>
        </div>
        <div class="profile-tabs">
            <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="nav-item"><a class="nav-link active" href="#about-cont" data-toggle="tab">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#bottom-tab2" data-toggle="tab">Profile</a></li>
                </ul>
            <div class="tab-content">
                <div class="tab-pane show active" id="about-cont">
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
                <div class="tab-pane" id="bottom-tab2">
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
                </div>
                
            </div>
        </div>
    </div>
</div>
