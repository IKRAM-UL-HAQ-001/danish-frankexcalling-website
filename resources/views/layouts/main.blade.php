<!DOCTYPE html>
<html lang="en">
@include("layouts.header")
<body class="g-sidenav-show">
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100vh; z-index: 0;">
        <div style="width: 100%; height: 100%; 
            background-image: url('../assets/img/frankcalling.jpg'); 
            background-size: cover; 
            background-position: center; 
            /* opacity: 0.7;"*/
            > 
        </div>
    </div>   
    <div id="preloader" style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
">
    <div class="loader"></div>
</div>

    <div class="min-height-300 position-absolute w-100" style="background:#acc301;"></div>
    @include("layouts.sideNavBar")
    <main class="main-content position-relative border-radius-lg pt-1" style="">
        @include("layouts.topNavBar")
        @yield("content")
        @include("layouts.footer")
    </main>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>

</body>
</html>
