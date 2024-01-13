<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../dashboard/" class="brand-link text-center">
      <img src="../../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image float-left img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-bold h4">CES App</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-2 mb-3 text-center">
        <!-- <div class="image">
          <img src="../../dist/img/favicon.png" class="img-circle elevation-2" alt="User Image">
        </div> -->
        <div class="info">
          <span class="h6 text-white"><i class="fas fa-user-tag"></i>&nbsp;:&nbsp;&nbsp;Government of Gujarat</span>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu">
            <a href="../dashboard/" class="nav-link <?php if(($template == 'dashboard') or ($template == 'view_all_cases')){ ?>active<?php } ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

            <li class="nav-item menu">
              <a href="../case_details/" class="nav-link <?php if(($template == 'case_details') or ($template == "edit_case") or ($template == "update_case_details") or ($template == "update_case_status") or ($template == "access_permissions") or ($template == "update_assigned_pleader") or ($template == "hearings") or ($template == "view_cases")){ ?>active<?php } ?>">
                <i class="nav-icon fas fa-info-circle"></i>
                <p>
                  Case Details
                </p>
              </a>
            </li>

            <li class="nav-item menu">
              <a href="../new_case/" class="nav-link <?php if(($template == 'new_case')){ ?>active<?php } ?>">
                <i class="nav-icon fas fa-plus"></i>
                <p>
                  Add New Case
                </p>
              </a>
            </li>

          <li class="nav-item menu">
            <a href="../assigned_pleaders/" class="nav-link <?php if(($template == 'assigned_pleaders') or ($template == 'pleader_profile')){ ?>active<?php } ?>">
              <i class="nav-icon fas fa-tasks"></i>
              <p>
                Assigned Pleaders
              </p>
            </a>
          </li>

          <li class="nav-item menu">
            <a href="../privileged_users/" class="nav-link <?php if(($template == 'privileged_users') or ($template == 'involvement_profile')){ ?>active<?php } ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Privileged Users
              </p>
            </a>
          </li>

          <li class="nav-item menu">
            <a href="../totalhearings/" class="nav-link <?php if(($template == 'totalhearings')){ ?>active<?php } ?>">
              <i class="nav-icon fas fa-gavel"></i>
              <p>
                Total Hearings
              </p>
            </a>
          </li>

          <li class="nav-item menu">
            <a href="../logs/" class="nav-link <?php if(($template == 'logs')){ ?>active<?php } ?>">
              <i class="nav-icon fas fa-history"></i>
              <p>
                Log History
              </p>
            </a>
          </li>


        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>