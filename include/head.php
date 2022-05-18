<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?=$seccion;?> | <?=$title;?></title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- endinject -->
    <link rel="shortcut icon" href="images/manifest0.png" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="manifest" href="manifest.json">
    
    <style>
        .sidebar .nav .nav-item.active > .nav-link {
            background: <?= $color;?>;
        }
        .sidebar .nav:not(.sub-menu) > .nav-item:hover > .nav-link, .sidebar .nav:not(.sub-menu) > .nav-item:hover[aria-expanded="true"] {
            background: <?= $color;?>;
        }
        .sidebar .nav.sub-menu {
            background: <?= $color;?>;
        }
        .sidebar .nav:not(.sub-menu) > .nav-item > .nav-link[aria-expanded="true"] {
            background: <?= $color;?>;
        }
        .sidebar .nav:not(.sub-menu) > .nav-item.active {
            background: <?= $color;?>;
        }
        .sidebar .nav:not(.sub-menu) > .nav-item:hover > .nav-link, .sidebar .nav:not(.sub-menu) > .nav-item:hover[aria-expanded="true"] {
            background: <?= $color;?>;
        }
        .fs-20 {
            font-size: 30px!important;
            font-weight: bold!important;
            text-align: center!important;
        }
        .auth .auth-form-light {
            background: #ffffff;
            border-radius: 20px;
        }
        .text-primary, .list-wrapper .completed .remove {
            color: <?= $color;?> !important;
            font-weight: bold;
            text-decoration: none;
        }
        a:hover {
            text-decoration: none;
        }
        .navbar .navbar-brand-wrapper .navbar-brand img {
            height: 60px;
        }
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url('images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
            opacity: .8;
            display: none;
        }
        .navbar .navbar-brand-wrapper .navbar-brand img {
            height: 1.5em;
        }
        @media (min-width: 992px){
            .sidebar-icon-only .sidebar .nav .nav-item .nav-link .menu-title {
                border-radius: 0 5px 5px 0px;
                background: <?= $color;?>;
            }
            .sidebar-icon-only .sidebar .nav .nav-item.hover-open .nav-link:hover .menu-title {
                background: <?= $color;?>;
            }
        }
        .footer a {
            color: <?= $color;?>;
            font-size: inherit;
            font-weight: 600;
        }
        .footer a:hover {
            color: #000;
        }
        .sidebar .nav .nav-item.active > .nav-link {
            background: <?= $color;?>;
        }
        .swal2-modal .swal2-icon, .swal2-modal .swal2-success-ring {
            margin-top: 30px;
            margin-bottom: 10px;
        }
    </style>
    <script>
        function cargando(abierta){
            if (abierta) {
                $(".loader").show();
            }else{
                $(".loader").hide();
            }
        }
    </script>
</head>