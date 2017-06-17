<?php 
session_start();
include "config.php";
if (isset($_GET['cat'])) {
    $category = $_GET['cat'];
    if (($category != 'Apparel') && ($category != 'Textile') && ($category != 'Fashion') && ($category != 'Technical textile')&& ($category != 'Technology') && ($category != 'Corporate') && ($category != 'Innovation') && ($category != 'Events') && ($category != 'Retail') && ($category != 'E-Commerce') && ($category != 'Institutional') && ($category != 'Denim')) 
    {

        echo '<script type="text/javascript">
           window.location = "./error_pages/errors.html"
      </script>';
    }
    $today = date("Y-m-d");
    //echo $today;

    // ---------- FOR RECENTS AND POPOLAR NEWS------------------------
    $stmt = $conn->prepare("SELECT a.pk_i_id , a.s_headline, a.s_create_time, a.s_slug, a.s_content, b.s_source from t_news_item as a, t_media as b where a.pk_i_id = b.fk_i_item_id and b_active = 1 group by a.pk_i_id order by a.pk_i_id desc LIMIT 100");
    $stmt->execute();
    $ids         = array();
    $createTimes = array();
    $headlines   = array();
    $slugs       = array();
    $sources     = array();
    $contents = array();
    $card_date = array();
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                    $ids[]         = $result['pk_i_id'];
                    $createTimes[] = $result['s_create_time'];
                    $headlines[]   = $result['s_headline'];
                    $slugs[]       = $result['s_slug'];
                    $sources[]     = $result['s_source'];
                    $contents[]     = $result['s_content'];
                    $card_date[] = date('j M', strtotime($result['s_create_time']));
            }
    $totalIds = count($ids);
    // ---------- FOR CATEGORY IN INDEX MENU------------------------
    $stmt1 = $conn->prepare("SELECT * FROM s_categories");
    $stmt1->execute();
    $categories = array();
    while ($cat = $stmt1->fetch(PDO::FETCH_ASSOC)) {
        $categories[] = $cat['categories_name'];
    }
    $totalCats = count($categories);
    //echo $totalIds;
    // print_r($ids);

    // ---------- FOR FEATURED NEWS------------------------

     $stmt1 = $conn->prepare("SELECT a.pk_i_id , a.s_headline, a.s_create_time, a.s_slug, a.s_content, b.s_source from t_news_item as a, t_media as b where a.pk_i_id = b.fk_i_item_id and b_publish = 1 group by a.pk_i_id order by a.pk_i_id desc LIMIT 10");
    $stmt1->execute();
    $featured_ids         = array();
    $featured_createTimes = array();
    $featured_headlines   = array();
    $featured_slugs       = array();
    $featured_sources     = array();
    $featured_contents = array();
    $featured_card_date = array();
    while ($result1 = $stmt1->fetch(PDO::FETCH_ASSOC))
            {
                    $featured_ids[]         = $result1['pk_i_id'];
                    $featured_createTimes[] = $result1['s_create_time'];
                    $featured_headlines[]   = $result1['s_headline'];
                    $featured_slugs[]       = $result1['s_slug'];
                    $featured_sources[]     = $result1['s_source'];
                    $featured_contents[]     = $result1['s_content'];
                    $featured_card_date[] = date('j M', strtotime($result1['s_create_time']));
            }
    // ---------- FOR EVENT MARKEE------------------------

    $stmt2 = $conn->prepare("SELECT a.pk_i_id , a.event_name,a.date_start, a.date_end, a.fk_country_id, a.city, a.venue, a.details ,a.product , a.fk_category_id, a.attendee , a.link ,a.media_link, a.tags, a.fk_user_id, a.b_active, a.b_publish ,b.s_source ,b.fk_event_id, c.categories_name, c.pk_i_id ,d.country_name,d.pk_i_id, e.s_fname, e.s_lname ,e.pk_i_id from t_events as a, t_media_events as b, s_categories as c, s_country as d, t_user as e where a.pk_i_id = b.fk_event_id and a.fk_country_id = d.pk_i_id and a.fk_category_id = c.pk_i_id and a.fk_user_id = e.pk_i_id and a.date_start >= :today and a.b_active=1 and a.b_publish = 1 group by a.pk_i_id order by a.pk_i_id DESC  ");
    $stmt2->bindparam(":today", $today);

    $stmt2->execute();

    $ev_id         = array();
    $ev_event = array();
    $ev_country   = array();
    $ev_tag = array();
    $ev_source = array();
    $ev_card_date_start = array();
    $ev_card_date_end = array();

    while ($res1 = $stmt2->fetch(PDO::FETCH_ASSOC))
            {
                    $ev_id[]         = $res1['pk_i_id'];
                    $ev_event[] = $res1['event_name'];
                    $ev_country[]     = $res1['country_name'];
                    $ev_tag[] = $res1['tags'];
                    $ev_source[] = $res1['s_source'];
                    $ev_card_date_start[] = date('j M', strtotime($res1['date_start']));
                    $ev_card_date_end[] = date('j M', strtotime($res1['date_end']));
            }


      $stmt3 = $conn->prepare("SELECT a.pk_i_id , a.event_name, a.date_start, a.date_end, a.fk_country_id, a.details, a.venue, a.tags, a.b_active, b.s_source , c.pk_i_id, c.country_name  from t_events as a, t_media_events as b , s_country as c where a.pk_i_id = b.fk_event_id and a.b_active = 1 and a.fk_country_id = c.pk_i_id group by a.pk_i_id ORDER BY a.pk_i_id DESC LIMIT 20");
    $stmt3->execute();
    $ev_date_start = array();
    $ev_date_end = array();
    $ev_name   = array();
    $ev_countries = array();
    $ev_tags = array();
    while ($res = $stmt3->fetch(PDO::FETCH_ASSOC))
            {
                    $ev_name[]   = $res['event_name'];
                    $ev_countries[]       = $res['country_name'];
                    $ev_tags[] = $res['tags'];
                    $ev_date_start[] = date('j M', strtotime($res['date_start']));
                    $ev_date_end[] = date('j M', strtotime($res['date_end']));
                }

    $stmt4 = $conn->prepare("SELECT a.pk_i_id , a.event_name, a.date_start, a.date_end, a.fk_country_id, a.details, a.venue, a.tags, a.b_active, a.fk_category_id, b.s_source , c.pk_i_id, c.country_name  from t_events as a, t_media_events as b , s_country as c where a.pk_i_id = b.fk_event_id and a.b_active = 1 and  a.fk_country_id = c.pk_i_id and a.fk_category_id = 1 group by a.pk_i_id ORDER BY a.pk_i_id DESC LIMIT 8");
    $stmt4->execute();
    $a_date_start = array();
    $a_date_end = array();
    $a_name   = array();
    $a_sources = array();
    $a_countries = array();
    $a_venue = array();
    $a_tags = array();
    while ($Apparel = $stmt4->fetch(PDO::FETCH_ASSOC))
            {
                    $a_name[]   = $Apparel['event_name'];
                    $a_countries[]       = $Apparel['country_name'];
                    $a_sources[] = $Apparel['s_source'];
                    $a_tags[] = $Apparel['tags'];
                    $a_venue[] = $Apparel['venue'];
                    $a_date_start[] = date('j M', strtotime($Apparel['date_start']));
                    $a_date_end[] = date('j M', strtotime($Apparel['date_end']));
                }
    
$stmt5 = $conn->prepare("SELECT a.pk_i_id , a.event_name, a.date_start, a.date_end, a.fk_country_id, a.details, a.venue, a.tags, a.b_active,  a.fk_category_id, b.s_source , c.pk_i_id, c.country_name  from t_events as a, t_media_events as b , s_country as c where a.pk_i_id = b.fk_event_id and a.b_active=1 and a.fk_country_id = c.pk_i_id and a.fk_category_id =2 group by a.pk_i_id ORDER BY a.pk_i_id DESC LIMIT 12");
    $stmt5->execute();
    $t_date_start = array();
    $t_date_end = array();
    $t_name   = array();
    $t_sources = array();
    $t_countries = array();
    $t_venue = array();
    $t_tags = array();
    while ($Textile = $stmt5->fetch(PDO::FETCH_ASSOC))
            {
                    $t_name[]   = $Textile['event_name'];
                    $t_countries[]       = $Textile['country_name'];
                    $t_sources[] = $Textile['s_source'];
                    $t_tags[] = $Textile['tags'];
                    $t_venue[] = $Textile['venue'];
                    $t_date_start[] = date('j M', strtotime($Textile['date_start']));
                    $t_date_end[] = date('j M', strtotime($Textile['date_end']));
                }

    $stmt6 = $conn->prepare("SELECT a.pk_i_id , a.event_name, a.date_start, a.date_end, a.fk_country_id, a.details, a.venue, a.tags, a.b_active, a.fk_category_id, b.s_source , c.pk_i_id, c.country_name  from t_events as a, t_media_events as b , s_country as c where a.pk_i_id = b.fk_event_id and a.b_active=1 and a.fk_country_id = c.pk_i_id and a.fk_category_id =3 group by a.pk_i_id ORDER BY a.pk_i_id DESC LIMIT 8");
    $stmt6->execute();
    $f_date_start = array();
    $f_date_end = array();
    $f_name   = array();
    $f_sources = array();
    $f_countries = array();
    $f_venue = array();
    $f_tags = array();
    while ($Fashion = $stmt6->fetch(PDO::FETCH_ASSOC))
            {
                    $f_name[]   = $Fashion['event_name'];
                    $f_countries[]       = $Fashion['country_name'];
                    $f_sources[] = $Fashion['s_source'];
                    $f_tags[] = $Fashion['tags'];
                    $f_venue[] = $Fashion['venue'];
                    $f_date_start[] = date('j M', strtotime($Fashion['date_start']));
                    $f_date_end[] = date('j M', strtotime($Fashion['date_end']));
                }  
    // ---------- FOR BANNERS--------------------------------

    $stmt7 = $conn->prepare("SELECT a.pk_i_id, a.Campaign_name ,a.link , b.s_source, b.fk_ads_id FROM t_ads as a , t_media_ads as b WHERE a.pk_i_id = b.fk_ads_id order by a.pk_i_id  desc LIMIT 2");
    $stmt7->execute();
    $images_ad = array();
    $links_ad = array();
    $Campaign_name = array();
    while ($videos = $stmt7->fetch(PDO::FETCH_ASSOC))
            {
                    $images_ad[]   = $videos['s_source'];
                    $links_ad[]   = $videos['link'];
                    $Campaign_name[] = $videos['Campaign_name'];
            } 
 
 
} ?>
<!DOCTYPE html>

<!--[if IE 7]>
<html class="ie ie7" lang="en-US">
<![endif]-->

<!--[if IE 8]>
<html class="ie ie8" lang="en-US">
<![endif]-->

<!--[if IE 9]>
<html class="ie ie9" lang="en-US">
<![endif]-->

<!--[if !(IE 7) | !(IE 8) | !(IE 9)  ]><!-->
<html lang="en-US">
<!--<![endif]-->

<head>
<title>texTIMZ | Textile Information Network</title>
<meta charset="UTF-8" />
<link rel="shortcut icon" href="images/favicon.png" title="Favicon"/>
<meta name="viewport" content="width=device-width" />

<link rel='stylesheet' id='magz-style-css'  href='style.css' type='text/css' media='all' />
<link rel='stylesheet' id='swipemenu-css'  href='css/swipemenu.css' type='text/css' media='all' />
<link rel='stylesheet' id='flexslider-css'  href='css/flexslider.css' type='text/css' media='all' />
<link rel='stylesheet' id='bootstrap-css'  href='css/bootstrap.css' type='text/css' media='all' />
<link rel='stylesheet' id='bootstrap-responsive-css'  href='css/bootstrap-responsive.css' type='text/css' media='all' />
<link rel='stylesheet' id='simplyscroll-css'  href='css/jquery.simplyscroll.css' type='text/css' media='all' />
<link rel='stylesheet' id='jPages-css'  href='css/jPages.css' type='text/css' media='all' />
<link rel='stylesheet' id='rating-css'  href='css/jquery.rating.css' type='text/css' media='all' />
<link rel='stylesheet' id='ie-styles-css'  href='css/ie.css' type='text/css' media='all' />
<link rel='stylesheet' id='Roboto-css'  href='http://fonts.googleapis.com/css?family=Roboto' type='text/css' media='all' />

<script type='text/javascript' src="js/jquery-1.10.2.min.js"></script>
<script type='text/javascript' src='js/html5.js'></script>
<script type='text/javascript' src='js/bootstrap.min.js'></script>
<script type='text/javascript' src='js/jquery.flexslider.js'></script>
<script type='text/javascript' src='js/jquery.flexslider.init.js'></script>
<script type='text/javascript' src='js/jquery.bxslider.js'></script>
<script type='text/javascript' src='js/jquery.bxslider.init.js'></script>
<script type='text/javascript' src='js/jquery.rating.js'></script>
<script type='text/javascript' src='js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='js/jquery.simplyscroll.js'></script>
<script type='text/javascript' src='js/fluidvids.min.js'></script>
<script type='text/javascript' src='js/jPages.js'></script>
<script type='text/javascript' src='js/jquery.sidr.min.js'></script>
<script type='text/javascript' src='js/jquery.touchSwipe.min.js'></script>
<script type='text/javascript' src='js/custom.js'></script>

        <!-- END -->

</head>

<body>

<div id="page">

    <header id="header" class="container">
        <div id="mast-head" style="margin-left: 40%">
            <div id="logo">
            <a href="index.php" title="Magazine" rel="home"><img src="images/logo.png" alt="Magazine" /></a>
            </div>
        </div>
 </header>

<header id="header" class="container">

                
        <nav class="navbar navbar-inverse clearfix nobot">    

            <!-- Responsive Navbar Part 2: Place all navbar contents you want collapsed withing .navbar-collapse.collapse. -->
            <div class="nav-collapse" id="swipe-menu-responsive">

            <ul class="nav" style="float: left;margin-left: 34%">
                                
                <li style="bottom: 0px;"><a href="index.php"><img src="images/home.png" alt="Magazine"></a></li>
                <li><a href="all_news.php?p=10">Highlights</a></li>
                <li><a href="all_events.php?p=4">Events</a></li>
                <!--
                TODO: jobs section
                <li><a href="contact.html">Jobs</a></li>
                -->
                </li>
                <li class="dropdown"><a href="##">Categories</a>
                    <ul class="sub-menu">
                    <?php for ($i=0; $i < $totalCats; $i++) { ?>
                        <li><a href="category.php?cat=<?php echo $categories[$i]; ?>"><?php echo $categories[$i]; ?></a></li>
                    <?php } ?>
                    </ul>
                </li>
               <!-- TODO: add pages  <li><a href="terms.html">Terms of Use</a></li>
                TODO: add pages  <li><a href="contact.html">Career</a></li>
              <li><a href="contact.html">Team</a></li> -->
                <li><a href="contact.html">Contact</a></li>
            </ul>
            </div><!--/.nav-collapse -->
            
        </nav><!-- /.navbar -->
            
    </header>

    <div id="headline" class="container">
    <div class="row-fluid">
        <?php for ($i=0; $i < 2 ; $i++) { ?>
        <div class="span6" style="padding-bottom: 2em; display: inline-block;">
                <a href="<?php echo $links_ad[$i]; ?>"> <!--TODO: Put link php-->
                    <img src="<?php echo $images_ad[$i]; ?>"> <!--TODO: Put banner image php, create database for ad banners-->
                </a>
        </div>
        <?php } ?>
        
    </div>
    </div>

<div id="intr" class="container">
        <div class="row-fluid">
            <div class="brnews span9">
                <h3>Upcoming Events</h3>
                <ul id="scroller">
                <?php 
                for ($i = 0; $i < 3; $i++) { ?>
                    <li><p><a href="events.php?event=<?php echo $ev_tag[$i]; ?>" title="<?php echo $ev_event[$i];?>" rel="bookmark"><span class="title"><?php echo $ev_event[$i];?> </span> <?php echo $ev_card_date_start[$i]." to ".$ev_card_date_end[$i]." ".$ev_country[$i]; ?></a></p></li>
                    <?php }  ?>
                </ul>
            </div>
        
        <div class="search span3"><div class="offset1">
            <form method="get" id="searchform" action="#"><!--TODO: put searcg feature-->
                <p><input type="text" value="Search here..." onfocus="if ( this.value == 'Search here...' ) { this.value = ''; }" onblur="if ( this.value == '' ) { this.value = 'Search here...'; }" name="s" id="s" />
                <input type="submit" id="searchsubmit" value="Search" /></p>
            </form>
        </div></div>
        </div>
    </div>

    <div id="content" class="container">

        <div id="main" class="row-fluid">

    <header class="entry-header">
        <h1 class="entry-title"><span><?php echo $category; ?></span></h1>
    </header><!-- .entry-header -->
        <div class="row-fluid">
          <?php if($category =='Apparel') {
            for ($i=0; $i <8 ; $i++) { 
                 ?> 
            <div class="kontengal4 span3" style="background-color: white; padding: 9px 9px 9px 9px">
                <article class="galleries">
                    <a href="events.php?event=<?php echo $a_tags[$i]; ?>" title="" rel="prettyPhoto"><img width="570" height="360" src="<?php echo $a_sources[$i]; ?>" alt="" /></a>
                    <h3 class="gal-title" style = "height: 50px;"><a href="events.php?event=<?php echo $a_tags[$i]; ?>" title="<?php echo $a_name[$i]; ?>" rel="bookmark" ><?php $small = $a_name[$i]; echo $small; ?></a></h3>
                    <div style = " height: 50px; border-bottom: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE;">
                    <p><?php echo $a_venue[$i] ?></p>
                    </div >
                    <div style = "border-bottom: 1px solid #EEEEEE;">
                    <p><?php echo $a_date_start[$i]." to ".$a_date_end[$i]; ?></p>
                    </div>
                </article>
            </div>
            <?php }}

            if($category =='Textile') {
            for ($i=0; $i <8 ; $i++) { 
                 ?> 
            <div class="kontengal4 span3" style="background-color: white; padding: 9px 9px 9px 9px">
                <article class="galleries">
                    <a href="events.php?event=<?php echo $t_tags[$i]; ?>" title="" rel="prettyPhoto"><img width="570" height="360" src="<?php echo $t_sources[$i]; ?>" alt="" /></a>
                    <h3 class="gal-title"><a href="events.php?event=<?php echo $t_tags[$i]; ?>" title="<?php echo $t_name[$i]; ?>" rel="bookmark"><?php $small = $t_name[$i]; echo $small; ?></a></h3>
                    <div style = " height: 50px; border-bottom: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE;">
                    <p><?php echo $t_venue[$i] ?></p>
                    </div>
                    <div style = "border-bottom: 1px solid #EEEEEE;">
                    <p><?php echo $t_date_start[$i]." to ".$t_date_end[$i]; ?></p>
                    </div>
                </article>
            </div>
            <?php }}

            if($category =='Fashion') {
            for ($i=0; $i <8 ; $i++) { 
                 ?> 
            <div class="kontengal4 span3" style="background-color: white; padding: 9px 9px 9px 9px">
                <article class="galleries">
                    <a href="events.php?event=<?php echo $f_tags[$i]; ?>" title="" rel="prettyPhoto"><img width="570" height="360" src="<?php echo $f_sources[$i]; ?>" alt="" /></a>
                    <h3 class="gal-title"><a href="events.php?event=<?php echo $f_tags[$i]; ?>" title="<?php echo $f_name[$i]; ?>" rel="bookmark"><?php $small = $f_name[$i]; echo $small; ?></a></h3>
                    <div style = " height: 50px; border-bottom: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE;">
                    <p><?php echo $f_venue[$i] ?></p>
                    </div>
                    <div style = "border-bottom: 1px solid #EEEEEE;">
                    <p><?php echo $f_date_start[$i]." to ".$f_date_end[$i]; ?></p>
                    </div>
                </article>
            </div>
            <?php }}

             ?>     
        </div>
        
            <div class="clearfix"></div>
        </div><!-- #main -->

    </div><!-- #content -->

    <footer id="footer" class="row-fluid">
        <div id="footer-widgets" class="container">

            <div class="footer-widget span3 block3">
                <div class="widget">
                    <h3 class="title"><span>Tag Cloud</span></h3>
                    <div class="tagcloud">
                        <a href='#'>Yarn</a>
                        <a href='#'>Cotton</a>
                        <a href='#'>Home Textile</a>
                        <a href='category.php?cat=Institutional'>Institutional</a>
                        <a href='category.php?cat=Fashion'>Fashion</a>
                        <a href='#'>Machinery</a>
                        <a href='category.php?cat=Technical textile'>Technical Textile</a>
                        <a href='category.php?cat=Apparel'>Apparel</a>
                        <a href='category.php?cat=Textile'>Textile</a>
                    </div>
                </div>
            </div>
            
            <div class="footer-widget span3 block4">
                <div class="widget">
                    <h3 class="title"><span>Social Media</span></h3>
                        <div class="socmed clearfix">       
                            <ul>
                                <li>
                                    <a href="http://textimz.com/rss"><img src="images/rss-icon.png" alt=""></a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/textimz"><img src="images/twitter-icon.png" alt=""></a>
                                </li>
                                <li>
                                    <a href="https://www.facebook.com/textimz"><img src="images/fb-icon.png" alt=""></a>
                                </li>
                            </ul>
                        </div>
                </div>
            </div>
            
            <div class="footer-widget span6 block5">
                <img class="footer-logo" src="images/logo.png" alt="texTIMZ">
                    <div class="footer-text">
                        <h4>About texTIMZ</h4>
                        <p>texTIMZ is a knowledge portal for textile industry, we provide news on the go.</p>
                    </div><div class="clearfix"></div>
            </div>

        </div><!-- footer-widgets -->

    
        <div id="site-info" class="container">
        
            <div id="footer-nav" class="fr">
                <ul class="menu">
                    <li><a href="index.php">Home</a></li>
                   <!-- TODO: add pages  <li><a href="terms.html">Blog</a></li> -->
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </div>

            <div id="credit" class="fl">
                <p>All Right Reserved by texTIMZ, 2017</p>
            </div>

        </div><!-- .site-info -->
        
    </footer>

</div><!-- #wrapper -->

</body>
</html>
