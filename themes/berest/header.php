<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package berest
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <!-- Custom Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap"
          rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.min.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    
    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<div class="body-div">
    <header data-spy="affix" data-offset-top="60">
        <div class="custom-navbar" data-aos="fade-down">
            <div class="container">
                <div class="row">
                    <div class="col pull-right">
                        <div class="header-right">
                            <?php
                            if (is_active_sidebar('header')) {
                                dynamic_sidebar('header');
                            }
                            ?>
                        </div>
                    </div>
                    
                    <div class="col-lg-9 col-md-9 col-sm-9 pull-left">
                        <nav class="navbar" role="navigation">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <div class="container-fluid">
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                            data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                    
                                    <a class="navbar-brand" href="<?php echo get_home_url(); ?>">
                                        <img class="logo"
                                             src="<?php echo get_template_directory_uri(); ?>/images/logo-big.jpg"
                                             alt=""/>
                                    </a>
                                </div>
                                
                                <!-- Collect the nav links, forms, and other content for toggling -->
                                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                    <?php
                                    wp_nav_menu(array(
                                        'theme_location' => 'menu-1',
                                        'menu_class' => 'nav navbar-nav navbar-right',
                                    ));
                                    ?>
                                
                                </div><!-- /.navbar-collapse -->
                            </div> <!-- /.container-fluid -->
                        
                        </nav>
                    
                    </div>
                
                </div>
            </div>
        </div>
    
    </header>