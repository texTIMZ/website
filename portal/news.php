<!<?php
session_start();
    include "config.php";
    if (isset($_GET['id'])) {
    //echo $_GET['id'];
    $slugs = $_GET['id'];

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
    $query0 = $conn->prepare("SELECT * FROM s_categories");
    $query0->execute();
    $cats = array();
    while ($cat = $query0->fetch(PDO::FETCH_ASSOC)) {
        $cats[] = $cat['categories_name'];
    }
    $totalCats = count($cats);
    //echo $totalIds;
    // print_r($ids);

    // ---------- FOR EVENT MARKEE------------------------

    $query2 = $conn->prepare("SELECT a.pk_i_id , a.event_name,a.date_start, a.date_end, a.fk_country_id, a.city, a.venue, a.details ,a.product , a.fk_category_id, a.attendee , a.link ,a.media_link, a.tags, a.fk_user_id, a.b_active, a.b_publish ,b.s_source ,b.fk_event_id, c.categories_name, c.pk_i_id ,d.country_name,d.pk_i_id, e.s_fname, e.s_lname ,e.pk_i_id from t_events as a, t_media_events as b, s_categories as c, s_country as d, t_user as e where a.pk_i_id = b.fk_event_id and a.fk_country_id = d.pk_i_id and a.fk_category_id = c.pk_i_id and a.fk_user_id = e.pk_i_id and a.b_publish = 1 and a.date_start >= :today group by a.pk_i_id order by a.pk_i_id DESC  ");
    $query2->bindparam(":today", $today);

    $query2->execute();

    $ev_id         = array();
    $ev_event = array();
    $ev_country   = array();
    $ev_tag = array();
    $ev_source = array();
    $ev_card_date_start = array();
    $ev_card_date_end = array();

    while ($res1 = $query2->fetch(PDO::FETCH_ASSOC))
            {
                    $ev_id[]         = $res1['pk_i_id'];
                    $ev_event[] = $res1['event_name'];
                    $ev_country[]     = $res1['country_name'];
                    $ev_tag[] = $res1['tags'];
                    $ev_source[] = $res1['s_source'];
                    $ev_card_date_start[] = date('j M', strtotime($res1['date_start']));
                    $ev_card_date_end[] = date('j M', strtotime($res1['date_end']));
            }

    $query   = $conn->prepare("SELECT a.pk_i_id , a.s_headline,a.s_content, a.s_create_time, a.s_slug, b.s_source, c.link_name, c.link ,d.s_fname , d.s_lname, e.fk_i_category_id , e.fk_i_item_id , f.categories_name, f.pk_i_id from t_news_item as a, t_media as b, t_link as c, t_user as d, t_categories as e, s_categories as f where a.pk_i_id = b.fk_i_item_id and c.fk_i_news_item_id = a.pk_i_id and a.fk_i_user_id = d.pk_i_id and a.pk_i_id = e.fk_i_item_id and a.s_slug = :slugs group by a.pk_i_id order by a.pk_i_id ");
    $query->bindParam('slugs', $slugs);
    $query->execute();
    while ($res = $query->fetch(PDO::FETCH_ASSOC))
            {
                    $ids         = $res['pk_i_id'];
                    $createTime = $res['s_create_time'];
                    $headline   = $res['s_headline'];
                    $source     = $res['s_source'];
                    $content     = $res['s_content'];
                    $category_1 = $res['categories_name'];
                    $fname = $res['s_fname'];
                    $lname = $res['s_lname'];
                    $card_dates = date('j M', strtotime($res['s_create_time']));
            }
    $query1 = $conn->prepare("SELECT a.pk_i_id , a.event_name, a.date_start, a.date_end, a.fk_country_id, a.details, a.venue, a.tags, a.b_active, a.b_publish, b.s_source , c.pk_i_id, c.country_name  from t_events as a, t_media_events as b , s_country as c where a.pk_i_id = b.fk_event_id and a.b_active=1 and a.b_publish=1 and a.fk_country_id = c.pk_i_id group by a.pk_i_id ORDER BY a.pk_i_id DESC LIMIT 20");
    $query1->execute();
    $ev_date_start = array();
    $ev_date_end = array();
    $ev_name   = array();
    $ev_countries = array();
    $ev_tags = array();
    while ($res = $query1->fetch(PDO::FETCH_ASSOC))
            {
                    $ev_name[]   = $res['event_name'];
                    $ev_countries[]       = $res['country_name'];
                    $ev_tags[] = $res['tags'];
                    $ev_date_start[] = date('j M', strtotime($res['date_start']));
                    $ev_date_end[] = date('j M', strtotime($res['date_end']));
                }

    $stmt1  = $conn->prepare("SELECT a.pk_i_id , a.s_headline,a.s_content, a.s_create_time, a.s_slug, b.s_source, c.link_name, c.link ,d.s_fname , d.s_lname, e.fk_i_category_id , e.fk_i_item_id , f.categories_name, f.pk_i_id from t_news_item as a, t_media as b, t_link as c, t_user as d, t_categories as e, s_categories as f where a.pk_i_id = b.fk_i_item_id and c.fk_i_news_item_id = a.pk_i_id and a.fk_i_user_id = d.pk_i_id and a.pk_i_id = e.fk_i_item_id and e.fk_i_category_id = f.pk_i_id group by a.pk_i_id order by a.pk_i_id desc");
    $stmt1->execute();
    $ids         = array();
    $createTimes = array();
    $headlines   = array();
    $slugs       = array();
    $sources     = array();
    $contents = array();
    $categories = array();
    $fnames = array();
    $lnames = array();
    $card_date = array();
    $links = array();
    while ($result = $stmt1->fetch(PDO::FETCH_ASSOC))
            {
                    $ids[]         = $result['pk_i_id'];
                    $createTimes[] = $result['s_create_time'];
                    $headlines[]   = $result['s_headline'];
                    $slugs[]       = $result['s_slug'];
                    $sources[]     = $result['s_source'];
                    $contents[]    = $result['s_content'];
                    $categories[] =     $result['categories_name'];
                    $fnames[] =         $result['s_fname'];
                    $lnames[] =         $result['s_lname'];
                    $links[] = $result['link'];
                    $card_date[] = date('j M', strtotime($result['s_create_time']));
            }
    $totalIds = count($ids);
    //print_r($categories);
    for ($i=0; $i < $totalIds; $i++) 
    { //echo "do";
        if ($slugs[$i] == $_GET['id'] ) 
        {
            $found = $i;
            echo $found;
        }
    }

    $stmt2 = $conn->prepare("SELECT a.pk_i_id , a.event_name, a.date_start,a.fk_country_id, a.details, a.venue, a.tags, a.b_active, a.b_publish,  b.s_source  from t_events as a, t_media_events as b where a.pk_i_id = b.fk_event_id and a.b_active=1 and a.b_publish=1 group by a.pk_i_id ORDER BY MONTH(a.date_start) desc LIMIT 20");
    $stmt2->execute();
    $event_ids         = array();
    $event_date_start = array();
    $event_name   = array();
    $event_country_id       = array();
    $event_sources     = array();
    $event_countries = array();
    $event_details = array();
    $event_date = array();
    $event_tags = array();
    $event_venue = array();
    while ($result2 = $stmt2->fetch(PDO::FETCH_ASSOC))
            {
                    $event_ids[]         = $result2['pk_i_id'];
                    $event_date_start[] = $result2['date_start'];
                    $event_name[]   = $result2['event_name'];
                    $event_country_id[]       = $result2['fk_country_id'];
                    $event_sources[]     = $result2['s_source'];
                    $event_details[]     = $result2['details'];
                    $event_venue[]     = $result2['venue'];
                    $event_tags[] = $result2['tags'];
                    $event_date[] = date('j M', strtotime($result2['date_start']));
                }
                $totalEvents = count($event_ids);

    for ($i=0; $i < $totalEvents; $i++) 
    { 
    $country_id = $event_country_id[$i];
    $query = $conn->prepare("SELECT country_name FROM s_country WHERE pk_i_id = :country_id  ");
    $query->bindParam(':country_id', $country_id);
    $query->execute();
    $country = $query->fetch(PDO::FETCH_ASSOC);
    $event_countries[$i] = $country['country_name'];
    }
     //print_r($event_ids);

    $stmt3= $conn->prepare("SELECT * FROM t_videos");
    $stmt3->execute();
    $Video_name = array();
    $link = array();
    while ($videos = $stmt3->fetch(PDO::FETCH_ASSOC))
            {
                    $Video_name[] = $videos['video_name'];
                    $link[]   = $videos['link'];
            }
      $stmt13 = $conn->prepare("SELECT a.pk_i_id, a.Campaign_name ,a.link , b.s_source, b.fk_ads_id FROM t_ads as a , t_media_ads as b WHERE a.pk_i_id = b.fk_ads_id order by a.pk_i_id  desc LIMIT 2");
    $stmt13->execute();
    $images_ad = array();
    $links_ad = array();
    $Campaign_name = array();
    while ($videos = $stmt13->fetch(PDO::FETCH_ASSOC))
            {
                    $images_ad[]   = $videos['s_source'];
                    $links_ad[]   = $videos['link'];
                    $Campaign_name[] = $videos['Campaign_name'];
            } 

}
?>

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
                        <li><a href="category.php?cat=<?php echo $cats[$i]; ?>"><?php echo $cats[$i]; ?></a></li>
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

    <div id="content" class="container" style="padding: 13px 40px 40px 40px;">

        <div id="main" class="row-fluid">
            <div id="main-left" class="span8">
                <?php $final = $found+5;
                for ($i=$found; $i < $final; $i++) 
                { ?>
            <div style="background-color: white; padding: 5px 5px 5px 5px; margin-top: 2em;">
                <article class="post">
                    <h2 class="entry-title">
                    <div style="width: 85%; height: 50px;font-size: 18px">
                        <?php echo $headlines[$i]; ?>
                    </div>
                        <span class="entry-cat" style="font-size: 12px;"><a href="category.php?cat=<?php echo $categories[$i]; ?>" title="View all posts in <?php echo $categories[$i]; ?> News" rel="category tag"><?php echo $categories[$i];?></a></span>
                    </h2>
                    <div class="entry-content">
                        <a href="news.php?id=<?php echo $slugs[$i]; ?>" title="<?php echo $headlines[$i]; ?>">
                        <img width="774" height="320" src="<?php echo $sources[$i]; ?>" alt="" />
                        </a>
                        <br>
                        <br>
                        <p style="margin-left: 5px"><?php echo $contents[$i]; ?>
                        <p class="moretag"><a target="_blank" href="<?php echo $links[$i]; ?>"> Read more</a></p>
                    </div>
                </article>   
            </div>           
                <?php } ?>

        
    
<div class="pagination magz-pagination"><a class="prev page-numbers" href="all_news.php?p=10">Load More</a></div>

            </div><!-- #main-left -->

        <div id="sidebar" class="span4">

            <div id="tabwidget" class="widget tab-container" style="margin-top: 2em;"> 
                <ul id="tabnav" class="clearfix"> 
                    <li><h3><a href="#tab1" class="selected"><img src="images/view-white-bg.png" alt="Popular">Popular</a></h3></li>
                    <li><h3><a href="#tab2"><img src="images/time-white.png" alt="Recent">Recent</a></h3></li>
                </ul> 

            <div id="tab-content">
            
            <div id="tab1" style="display: block; background-color: white;">
                <ul id="itemContainer" class="recent-tab">
                <?php for ($i=5; $i < 17 ; $i++) {  ?>
                    <li>
                        <a href="news.php?id=<?php echo $slugs[$i]; ?>"><img width="225" height="136" src="<?php echo $sources[$i]; ?>" class="thumb" alt="" /></a>
                        <div style="width: 68%; height: 45px; display: inline-block;">
                        <h4 class="post-title"><a href="news.php?id=<?php echo $slugs[$i]; ?>" ><?php $small = $headlines[$i]; echo $small; ?></a></h4>
                        </div>
                        <p><?php $small = substr($contents[$i], 0, 60); echo $small; ?> ...</p>
                        <div class="clearfix"></div>                
                    </li>
                <?php } ?>
                                
                    <script type="text/javascript">
                        jQuery(document).ready(function($){

                            /* initiate the plugin */
                            $("div.holder").jPages({
                            containerID  : "itemContainer",
                            perPage      : 3,
                            startPage    : 1,
                            startRange   : 1,
                            links          : "blank"
                            });
                        });     
                    </script>

                </ul>
                
                <div class="holder clearfix" ></div>
                <div class="clear"></div>

            <!-- End most viewed post -->         

            </div><!-- /#tab1 -->
 
            <div id="tab2" style="display: none; display: block; background-color: white;">  
                <ul id="itemContainer2" class="recent-tab">
                <?php for ($i=3; $i < 6 ; $i++) {  ?>
                    <li>
                        <a href="news.php?id=<?php echo $slugs[$i]; ?>"><img width="225" height="136" src="<?php echo $sources[$i]; ?>" class="thumb" alt="" /></a>
                        <h4 class="post-title"><a href="#"><?php $small = $headlines[$i]; echo $small; ?></a></h4>
                        <p><?php $small = substr($contents[$i], 0, 60); echo $small; ?> ...</p>
                        <div class="clearfix"></div>    
                    </li>
                    <?php } ?>
                </ul>    
            </div><!-- /#tab2 --> 
    
            </div><!-- /#tab-content -->

            </div><!-- /#tab-widget --> 

            <div class="video-box widget row-fluid" style="background-color: white; margin-top: 2em; padding: 5px 5px 5px 5px ">
                <h3 class="title"><span style="background-color: #;color: #;">Videos Gallery</span></h3>        
                <iframe width="369" height="188" src="<?php echo $link[0]; ?>" frameborder="0" allowfullscreen></iframe>
                
            </div>
            </div> <!-- sidebar -->
        
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
                    <!-- TODO: add pages <li><a href="terms.html">Blog</a></li>
 -->                    <li><a href="contact.html">Contact</a></li>
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
