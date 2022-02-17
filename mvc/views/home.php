
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Blog</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--===============================================================================================-->
  <link rel="icon" type="image/png" href="../../public/image/icons/favicon.png"/>
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../../public/vendor/bootstrap/css/bootstrap.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../../public/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../../public/fonts/iconic/css/material-design-iconic-font.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../../public/fonts/linearicons-v1.0.0/icon-font.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../../public/vendor/animate/animate.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../../public/vendor/css-hamburgers/hamburgers.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../../public/vendor/animsition/css/animsition.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../../public/vendor/select2/select2.min.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../../public/vendor/perfect-scrollbar/perfect-scrollbar.css">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="../../public/css/util.css">
  <link rel="stylesheet" type="text/css" href="../../public/css/main.css">
  <link rel="stylesheet" type="text/css" href="../../public/css/style.css">
  <!--===============================================================================================-->
</head>
<body class="animsition">

<!-- Header -->
<header class="header-v4">
  <!-- Header desktop -->
  <div class="container-menu-desktop">
    <!-- Topbar -->
    <div class="top-bar">
      <div class="content-topbar flex-sb-m h-full container">
        <div class="left-top-bar">
          Free shipping for standard order over $100
        </div>

        <div class="right-top-bar flex-w h-full">

          <a href="login.php" class="flex-c-m trans-04 p-lr-25">
            My Account
          </a>

        </div>
      </div>
    </div>

    <div class="wrap-menu-desktop how-shadow1">
      <nav class="limiter-menu-desktop container">

        <!-- Logo desktop -->
        <a href="" class="logo">
          <img src="/public/images/favicon.png" alt="IMG-LOGO">
        </a>

        <!-- Menu desktop -->
        <div class="menu-desktop">
          <ul class="main-menu">


          </ul>
        </div>


      </nav>
    </div>
  </div>

  <!-- Header Mobile -->
  <div class="wrap-header-mobile">
    <!-- Logo moblie -->
    <div class="logo-mobile">
      <a href=""><img src="/public/images/favicon.png" alt="IMG-LOGO"></a>
    </div>

    <!-- Button show menu -->
    <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
				<span class="hamburger-box">
					<span class="hamburger-inner"></span>
				</span>
    </div>
  </div>

  <!-- Menu Mobile -->
  <div class="menu-mobile">

    <ul class="main-menu-m">

    </ul>
  </div>

  <!-- Modal Search -->
  <div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
    <div class="container-search-header">
      <button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
        <img src="/public/images/favicon.png" alt="CLOSE">
      </button>

      <form class="wrap-search-header flex-w p-l-15">
        <button class="flex-c-m trans-04">
          <i class="zmdi zmdi-search"></i>
        </button>
        <input class="plh3" type="text" name="search" placeholder="Search...">
      </form>
    </div>
  </div>
</header>

<!-- Title page -->
<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('../../public/images/bg-02.jpg');">
  <h2 class="ltext-105 cl0 txt-center">
    Blog
  </h2>
</section>


<!-- Content page -->
<section class="bg0 p-t-62 p-b-60">
  <div class="container">
    <div class="row">
      <div id="detail-news" class="col-md-8 col-lg-8 p-b-80">
        <div  class="p-r-45 p-r-0-lg">
          <!-- item blog -->
          <div id="main-home-news">

          </div>



          <!-- Pagination -->
          <div id="page" class="flex-l-m flex-w w-full p-t-10 m-lr--7">

          </div>
        </div>
      </div>

      <div class="col-md-4 col-lg-4 p-b-80">
        <div class="side-menu">
          <div class="bor17 of-hidden pos-relative">
            <input class="stext-103 cl2 plh4 size-116 p-l-28 p-r-55" type="text" id="keyword" name="search" placeholder="Search">

            <button class="flex-c-m size-122 ab-t-r fs-18 cl4 hov-cl1 trans-04">
              <i class="zmdi zmdi-search"></i>
            </button>
            <div  class="dropdown">
              <ul id="list-search">

              </ul>
            </div>

          </div>

          <div class="p-t-55">
            <h4 class="mtext-112 cl2 p-b-33">
              LOẠI TIN
            </h4>

            <ul id="list-categorys">

            </ul>
          </div>

          <div class="p-t-65">
            <h4 class="mtext-112 cl2 p-b-33">
              TIN TỨC HOT
            </h4>

            <ul id="hot-news">

            </ul>
          </div>

          <div class="p-t-55">
            <h4 class="mtext-112 cl2 p-b-20">
              Tin tức liên quan
            </h4>
            <ul id="random-news">

            </ul>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>



<!-- Footer -->
<footer class="bg3 p-t-75 p-b-32">
  <div class="container">
    <div class="row">
      <div class="col-sm-6 col-lg-3 p-b-50">
        <h4 class="stext-301 cl0 p-b-30">
          Loại tin
        </h4>

        <ul id="footer-category">
          <li class="p-b-10">
            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
              Women
            </a>
          </li>

          <li class="p-b-10">
            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
              Men
            </a>
          </li>

          <li class="p-b-10">
            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
              Shoes
            </a>
          </li>

          <li class="p-b-10">
            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
              Watches
            </a>
          </li>
        </ul>
      </div>

      <div class="col-sm-6 col-lg-3 p-b-50">

      </div>

      <div class="col-sm-6 col-lg-3 p-b-50">
        <h4 class="stext-301 cl0 p-b-30">
          GET IN TOUCH
        </h4>

        <p class="stext-107 cl7 size-201">
          Any questions? call me on 0856800200        </p>

        <div class="p-t-27">
          <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
            <i class="fa fa-facebook"></i>
          </a>

          <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
            <i class="fa fa-instagram"></i>
          </a>

          <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
            <i class="fa fa-pinterest-p"></i>
          </a>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3 p-b-50">
        <h4 class="stext-301 cl0 p-b-30">
          Newsletter
        </h4>

        <form>
          <div class="wrap-input1 w-full p-b-4">
            <input class="input1 bg-none plh1 stext-107 cl7" type="text" name="email" placeholder="buiducphu.266@gmail.com">
            <div class="focus-input1 trans-04"></div>
          </div>

        </form>
      </div>
    </div>

    </div>
  </div>
</footer>


<!-- Back to top -->
<div class="btn-back-to-top" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="zmdi zmdi-chevron-up"></i>
		</span>
</div>

<!--===============================================================================================-->
<script src="../../public/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="../../public/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
<script src="../../public/vendor/bootstrap/js/popper.js"></script>
<script src="../../public/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="../../public/vendor/select2/select2.min.js"></script>
<script>
  $(".js-select2").each(function(){
    $(this).select2({
      minimumResultsForSearch: 20,
      dropdownParent: $(this).next('.dropDownSelect2')
    });
  })
</script>
<!--===============================================================================================-->
<script src="../../public/vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
<!--===============================================================================================-->
<script src="../../public/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script>
  $('.js-pscroll').each(function(){
    $(this).css('position','relative');
    $(this).css('overflow','hidden');
    var ps = new PerfectScrollbar(this, {
      wheelSpeed: 1,
      scrollingThreshold: 1000,
      wheelPropagation: false,
    });

    $(window).on('resize', function(){
      ps.update();
    })
  });
</script>
<!--===============================================================================================-->
<script src="/public/js/main.js"></script>
<script src="/public/js/home.js"></script>

</body>
</html>