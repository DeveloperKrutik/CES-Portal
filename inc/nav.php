<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark bg-custom fixed-top" id="sideNav">
    <a class="navbar-brand js-scroll-trigger" href="../home">
        <span class="d-block d-lg-none">CES Portal</span>
        <span class="d-none d-lg-block"><img class="img-fluid img-profile rounded-circle mx-auto mb-2" src="../assets/img/logo.png" alt="logo" /></span>
    </a>
        <h3 class="active d-none d-lg-block">CES Portal</h3> <br>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="<?php if(($template == 'home')){ ?>navactive active<?php } ?> nav-link" href="../home"><i class="fa fa-home" aria-hidden="true"></i>  Dashboard</a></li>
            <li class="nav-item"><a class="nav-link <?php if(($template == 'cases')){ ?>navactive active<?php } ?>" href="../cases"><i class="fa fa-gavel" aria-hidden="true"></i>  View Cases</a></li>
            <li class="nav-item"><a class="nav-link <?php if(($template == 'update_profile')){ ?>navactive active<?php } ?>" href="../profile"><i class="fa fa-user" aria-hidden="true"></i>  Update Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="../logout"><i class="fa fa-sign-out" aria-hidden="true"></i>  Logout</a></li>
        </ul>
    </div>
</nav>