 <a href="index.html" class="logo">
      <?php 
        session_cache_limiter(FALSE); 
        session_start();
		include "inc.koneksi.php";
		include "fungsi_hdt.php";

        $app = mysql_fetch_array(mysql_query("SELECT nama FROM appname ORDER BY tglupdate LIMIT 1"));

        echo $app[nama];

       ?>
</a>
<nav class="navbar navbar-static-top" role="navigation">
    <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </a>
    <div class="navbar-right">
        <ul class="nav navbar-nav">
		
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-user"></i>
                    <span>
						<?php echo $_SESSION[namalengkap];?>
					<i class="caret"></i></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="user-header bg-light-blue">
                        <img src="<?php echo $_SESSION[pathavatar];?>" class="img-circle" alt="User Image" />
                        <p>									
							<?php echo $_SESSION[posisi];?>
                            <small>Join since Mei 2019</small>
                        </p>
                    </li>
					
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                        </div>
                        <div class="pull-right">
                            <a href="?mod=exit" class="btn btn-default btn-flat">Sign out</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>