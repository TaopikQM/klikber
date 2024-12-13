<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Login DUPAK</title>
    <?php 

    ?>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url()?>c0r3/img/jjat.png">
    <!-- Google Fonts
        ============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Bootstrap CSS
        ============================================ -->
    <link rel="stylesheet" href="c0r3/css/bootstrap.min.css">
    <!-- font awesome CSS
        ============================================ -->
    <link rel="stylesheet" href="c0r3/css/font-awesome.min.css">
    <!-- owl.carousel CSS
        ============================================ -->
    <link rel="stylesheet" href="c0r3/css/owl.carousel.css">
    <link rel="stylesheet" href="c0r3/css/owl.theme.css">
    <link rel="stylesheet" href="c0r3/css/owl.transitions.css">
    <!-- animate CSS
        ============================================ -->
    <link rel="stylesheet" href="c0r3/css/animate.css">
    <!-- normalize CSS
        ============================================ -->
    <link rel="stylesheet" href="c0r3/css/normalize.css">
    <!-- mCustomScrollbar CSS
        ============================================ -->
    <link rel="stylesheet" href="c0r3/css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- wave CSS
        ============================================ -->
    <link rel="stylesheet" href="c0r3/css/wave/waves.min.css">
    <!-- Notika icon CSS
        ============================================ -->
    <link rel="stylesheet" href="c0r3/css/notika-custom-icon.css">
    <!-- main CSS
        ============================================ -->
    <link rel="stylesheet" href="c0r3/css/main.css">
    <!-- style CSS
        ============================================ -->
    <link rel="stylesheet" href="c0r3/style.css">
    <!-- responsive CSS
        ============================================ -->
    <link rel="stylesheet" href="c0r3/css/responsive.css">
    <!-- modernizr JS
        ============================================ -->
    <script src="c0r3/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- Login Register area Start-->
    <div class="login-content">
        <!-- Login -->
        <div class="nk-block toggled" id="l-login">
            <form name="login" action="<?php echo base_url('login/auth');?>" method="post" enctype="multipart/form-data" >

            <div class="nk-form">
                <?php
                    if($this->session->flashdata('notif_login') == TRUE){
                ?>
                <div class="alert alert-danger alert-mg-b-0" role="alert"><?php echo $this->session->flashdata('notif_login');?></div>
                <?php
                }
                ?>
                <h2>Login Admin Sekretariat</h2>
                <div class="input-group">
                    <span class="input-group-addon nk-ic-st-pro"><i class="notika-icon notika-support"></i></span>
                    <div class="nk-int-st">
                        <input type="text" name="uss" class="form-control" placeholder="Username">
                    </div>
                </div>
                <div class="input-group mg-t-15">
                    <span class="input-group-addon nk-ic-st-pro"><i class="notika-icon notika-edit"></i></span>
                    <div class="nk-int-st">
                        <input type="password" name="pass" class="form-control" placeholder="Password">
                    </div>
                </div>
                <div class="input-group">
                    <span class="input-group-addon nk-ic-st-pro"></span>
                    <div class="nk-int-st ">
                       <span id="captcha"><?php echo $captcha;?></span><button class="btn  btn-primary buton-refresh"><i class="notika-icon notika-refresh"></i></button>
                    </div> 
                </div>
                <div class="input-group mg-t-15">
                    <span class="input-group-addon nk-ic-st-pro"><i class="notika-icon notika-edit"></i></span>
                    <div class="nk-int-st">
                        <input type="text" name="capca" class="form-control" placeholder="Captcha" required="required">
                    </div>
                </div>
                <button class="btn btn-login btn-success btn-float"><i class="notika-icon notika-right-arrow"></i></button>
            </div>


            </form>
        </div>

    </div>
    <!-- Login Register area End-->
    <!-- jquery
		============================================ -->
    <script src="c0r3/js/vendor/jquery-1.12.4.min.js"></script>
    <!-- bootstrap JS
        ============================================ -->
    <script src="c0r3/js/bootstrap.min.js"></script>
    <!-- wow JS
        ============================================ -->
    <script src="c0r3/js/wow.min.js"></script>
    <!-- price-slider JS
        ============================================ -->
    <script src="c0r3/js/jquery-price-slider.js"></script>
    <!-- owl.carousel JS
        ============================================ -->
    <script src="c0r3/js/owl.carousel.min.js"></script>
    <!-- scrollUp JS
        ============================================ -->
    <script src="c0r3/js/jquery.scrollUp.min.js"></script>
    <!-- meanmenu JS
        ============================================ -->
    <script src="c0r3/js/meanmenu/jquery.meanmenu.js"></script>
    <!-- counterup JS
        ============================================ -->
    <script src="c0r3/js/counterup/jquery.counterup.min.js"></script>
    <script src="c0r3/js/counterup/waypoints.min.js"></script>
    <script src="c0r3/js/counterup/counterup-active.js"></script>
    <!-- mCustomScrollbar JS
        ============================================ -->
    <script src="c0r3/js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <!-- sparkline JS
        ============================================ -->
    <script src="c0r3/js/sparkline/jquery.sparkline.min.js"></script>
    <script src="c0r3/js/sparkline/sparkline-active.js"></script>
    <!-- flot JS
        ============================================ -->
    <script src="c0r3/js/flot/jquery.flot.js"></script>
    <script src="c0r3/js/flot/jquery.flot.resize.js"></script>
    <script src="c0r3/js/flot/flot-active.js"></script>
    <!-- knob JS
        ============================================ -->
    <script src="c0r3/js/knob/jquery.knob.js"></script>
    <script src="c0r3/js/knob/jquery.appear.js"></script>
    <script src="c0r3/js/knob/knob-active.js"></script>
    <!--  Chat JS
        ============================================ -->
    <script src="c0r3/js/chat/jquery.chat.js"></script>
    <!--  wave JS
        ============================================ -->
    <script src="c0r3/js/wave/waves.min.js"></script>
    <script src="c0r3/js/wave/wave-active.js"></script>
    <!-- icheck JS
        ============================================ -->
    <script src="c0r3/js/icheck/icheck.min.js"></script>
    <script src="c0r3/js/icheck/icheck-active.js"></script>
    <!--  todo JS
        ============================================ -->
    <script src="c0r3/js/todo/jquery.todo.js"></script>
    <!-- Login JS
        ============================================ -->
    <script src="c0r3/js/login/login-action.js"></script>
    <!-- plugins JS
        ============================================ -->
    <script src="c0r3/js/plugins.js"></script>
    <!-- main JS
        ============================================ -->
    <script src="c0r3/js/main.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            $('.buton-refresh').on('click', function(){
                       $.ajax({
                        url : "<?php echo base_url();?>login/refresh_captcha",
                        method : "POST",
                        async : false,
                        success: function(data){
                           $('#captcha').html(data);
                                  
                        }
                    });
                });

        });
    </script>
</body>

</html>