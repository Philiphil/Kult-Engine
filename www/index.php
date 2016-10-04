<?php
include("invoker.class.php");
invoker::require_basics();
page::standardpage_head("accueil");


page::standardpage_header(); 
page::standardpage_body_begin();

membre::login_required();


?>

<div class="k_spacer"></div>

<div class="row">
    <?php
    if(membre::has_module(module::$_gestion_utilisateur))
    {
        echo '
        <div class="col-md-4 col-sm-6 col-xs-12">
            <a href="'.constant("htmlpath").'user_list.php" class="k_no_decoration">
                <div class="dashboard-stat nice_green">
                    <div class="visual">
                        <i class="fa fa-users fa-icon-medium"></i>
                    </div>
                    <div class="details">
                        <div class="number">'.get_texte("bloc_gestion_user_title").'</div>
                        <div class="desc">'.get_texte("bloc_gestion_user_desc").'</div>
                    </div>
                    <span class="more">'.get_texte("acceder").'
                        <i class="m-icon-swapright m-icon-white"></i>
                    </span>
                </div>
            </a>
        </div>
        ';
    }
    ?>

    <?php
    if(membre::has_module(module::$_gestion_ip))
    {
        echo '
        <div class="col-md-4 col-sm-6 col-xs-12">
            <a href="'.constant("htmlpath").'network_list.php" class="k_no_decoration">
                <div class="dashboard-stat nice_grey">
                    <div class="visual">
                        <i class="fa fa-list fa-icon-medium"></i>
                    </div>
                    <div class="details">
                        <div class="number">'.get_texte("bloc_gestion_ip_title").'</div>
                        <div class="desc">'.get_texte("bloc_gestion_ip_desc").'</div>
                    </div>
                    <span class="more">'.get_texte("acceder").'
                        <i class="m-icon-swapright m-icon-white"></i>
                    </span>
                </div>
            </a>
        </div>
        ';
    }
    ?>

    <?php
    if(membre::has_module(module::$_switch_access))
    {
        echo '
        <div class="col-md-4 col-sm-6 col-xs-12">
            <a href="'.constant("htmlpath").'switch_list.php" class="k_no_decoration">
                <div class="dashboard-stat blue">
                    <div class="visual">
                        <i class="fa fa-gears fa-icon-medium"></i>
                    </div>
                    <div class="details">
                        <div class="number">'.get_texte("bloc_gestion_switch_title").'</div>
                        <div class="desc">'.get_texte("bloc_gestion_switch_desc").'</div>
                    </div>
                    <span class="more">'.get_texte("acceder").'
                        <i class="m-icon-swapright m-icon-white"></i>
                    </span>
                </div>
            </a>
        </div>
        ';
    }
    ?>


    <?php
    if(membre::has_module(module::$_infratool_all))
    {
        $x = connector::get_new_bugs();
        echo '
        <div class="col-md-4 col-sm-6 col-xs-12">
            <a href="'.constant("htmlpath").'bug_list.php" class="k_no_decoration">
                <div class="dashboard-stat grey-cascade mt-element-ribbon">
                    <div class="ribbon ribbon-vertical-right ribbon-color-danger uppercase ">'.$x.'</div>
                    <div class="visual">
                        <i class="fa fa-bug fa-spin fa-icon-medium"></i>
                    </div>
                    <div class="details">
                        <div class="number">'.get_texte("bloc_gestion_bugtracker_title").'</div>
                        <div class="desc ">'.get_texte("bloc_gestion_bugtracker_desc").'</div>
                    </div>
                    <span class="more">'.get_texte("acceder").'
                        <i class="m-icon-swapright m-icon-white"></i>
                    </span>
                </div>
            </a>
        </div>
        ';
    }
    ?>
</div><!--ROW-->




<?php 
page::standardpage_body_end();
page::standardpage_footer(); ?>
