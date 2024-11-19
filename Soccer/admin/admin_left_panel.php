 <!-- Left navbar-header on the dashboard-->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse slimscrollsidebar">
                <ul class="nav" id="side-menu">
                    <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                        <!-- input-group -->
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search..."> <span class="input-group-btn">
                            <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
                            </span> 
                        </div>
                        <!-- /input-group -->
                    </li>
                    <li class="user-pro">
                        <a href="#" class="waves-effect"><img src="../plugins/images/user.jpg" alt="user-img" class="img-circle"> <span class="hide-menu"> <?php echo $username; ?><span class="fa arrow"></span></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li><a href="settings.php"><i class="ti-settings"></i> Account Setting</a></li>
                            <li><a href="functions/logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                    </li>
                    <li class="nav-small-cap m-t-10">--- Main Menu</li>
                    
                    <li><a href="#" class="waves-effect"><i class="fa fa-users fa-2x"></i> <span class="hide-menu"> || Players<span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="new-tenant.php">Add player</a></li>
                            <li><a href="tenants.php">View Players</a></li>
                            
                        </ul>
                    </li>

                    <li><a href="new-location.php" class="waves-effect"><i class="fa fa-map-marker fa-2x"></i> <span class="hide-menu"> || Clubs</span></a>
                    </li>
                    
                     <li class="nav-small-cap">--- Other</li>

                    <li> <a href="#" class="waves-effect"><i class="fa fa-cogs fa-2x"></i> <span class="hide-menu">Accounts<span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="users.php">Administrators</a></li>
                            <li><a href="new-user.php">Create Admin</a></li>
                            
                        </ul>
                    </li>
                    
                    <li><a href="functions/logout.php" class="waves-effect"><i class="fa fa-sign-out fa-2x"></i> <span class="hide-menu">Log out</span></a></li>
                   
                </ul>
            </div>
        </div>
        <!-- Left navbar-header end -->