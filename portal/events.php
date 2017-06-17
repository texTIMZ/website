<?php
session_start();
    include "config.php";
	if (isset($_GET['event'])) 
{
	//echo $_GET['event'];
    //echo $tags;
    $today = date("Y-m-d");

    // ---------- FOR CATEGORY IN INDEX MENU------------------------
    $cat_stmt = $conn->prepare("SELECT * FROM s_categories");
    $cat_stmt->execute();
    $cats = array();
    while ($cat = $cat_stmt->fetch(PDO::FETCH_ASSOC)) {
        $cats[] = $cat['categories_name'];
    }
    $totalCats = count($cats);


$stmt1  = $conn->prepare("SELECT a.pk_i_id , a.s_headline,a.s_content, a.s_create_time, a.s_slug, b.s_source, c.link_name, c.link ,d.s_fname , d.s_lname, e.fk_i_category_id , e.fk_i_item_id , f.categories_name, f.pk_i_id from t_news_item as a, t_media as b, t_link as c, t_user as d, t_categories as e, s_categories as f where a.pk_i_id = b.fk_i_item_id and c.fk_i_news_item_id = a.pk_i_id and a.fk_i_user_id = d.pk_i_id and a.pk_i_id = e.fk_i_item_id and e.fk_i_category_id = f.pk_i_id group by a.pk_i_id order by a.pk_i_id desc LIMIT 20");
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
    // ---------- FOR EVENT MARKEE------------------------

    $query1 = $conn->prepare("SELECT a.pk_i_id , a.event_name,a.date_start, a.date_end, a.fk_country_id, a.city, a.venue, a.details ,a.product , a.fk_category_id, a.attendee , a.link ,a.media_link, a.tags, a.fk_user_id, a.b_active, a.b_publish ,b.s_source ,b.fk_event_id, c.categories_name, c.pk_i_id ,d.country_name,d.pk_i_id, e.s_fname, e.s_lname ,e.pk_i_id from t_events as a, t_media_events as b, s_categories as c, s_country as d, t_user as e where a.pk_i_id = b.fk_event_id and a.fk_country_id = d.pk_i_id and a.fk_category_id = c.pk_i_id and a.fk_user_id = e.pk_i_id and a.b_publish = 1 and a.date_start >= :today group by a.pk_i_id order by a.pk_i_id DESC ");
    $query1->bindparam(":today", $today);

    $query1->execute();

    $ev_id         = array();
    $ev_event = array();
    $ev_country   = array();
    $ev_tag = array();
    $ev_card_date_start = array();
    $ev_card_date_end = array();

    while ($res1 = $query1->fetch(PDO::FETCH_ASSOC))
            {
                    $ev_id[]         = $res1['pk_i_id'];
                    $ev_event[] = $res1['event_name'];
                    $ev_country[]     = $res1['country_name'];
                    $ev_tag[] = $res1['tags'];
                    $ev_card_date_start[] = date('j M', strtotime($res1['date_start']));
                    $ev_card_date_end[] = date('j M', strtotime($res1['date_end']));
            }


    $query2 = $conn->prepare("SELECT a.pk_i_id , a.event_name,a.date_start, a.date_end, a.fk_country_id, a.city, a.venue, a.details ,a.product , a.fk_category_id, a.attendee , a.link ,a.media_link, a.tags, a.fk_user_id, a.b_active, a.b_publish ,b.s_source ,b.fk_event_id, c.categories_name, c.pk_i_id ,d.country_name,d.pk_i_id, e.s_fname, e.s_lname ,e.pk_i_id from t_events as a, t_media_events as b, s_categories as c, s_country as d, t_user as e where a.pk_i_id = b.fk_event_id and a.fk_country_id = d.pk_i_id and a.fk_category_id = c.pk_i_id and a.fk_user_id = e.pk_i_id and  a.b_active=1 and a.date_start >= :today group by a.pk_i_id order by a.pk_i_id DESC ");
        $query2->bindparam(":today", $today);


    $query2->execute();

    $ev_ids         = array();
    $ev_events = array();
    $ev_countries   = array();
    $ev_attendee       = array();
    $ev_link     = array();
    $ev_tags = array();
    $ev_source = array();
    $ev_product = array();
    $ev_details = array();
    $ev_venue = array();
    $ev_fname = array();
    $ev_lname = array();
    $ev_media_link = array();
    $ev_card_date_starts = array();
    $ev_card_date_ends = array();

    while ($res1 = $query2->fetch(PDO::FETCH_ASSOC))
            {
                    $ev_ids[]         = $res1['pk_i_id'];
                    $ev_events[] = $res1['event_name'];
                    $ev_countries[]     = $res1['country_name'];
                    $ev_attendee[] = $res1['attendee'];
                    $ev_link[] = $res1['link'];
                    $ev_tags[] = $res1['tags'];
                    $ev_source[] = $res1['s_source'];
                    $ev_categories_name[] = $res1['categories_name'];
                    $ev_product[] = $res1['product'];
                    $ev_details[] = $res1['details'];
                    $ev_venue[] = $res1['venue'];
                    $ev_media_link[] = $res1['media_link'];
                    $ev_fname[] = $res1['s_fname'];
                    $ev_lname[] = $res1['s_lname'];
                    $ev_card_date_starts[] = date('j M', strtotime($res1['date_start']));
                    $ev_card_date_ends[] = date('j M', strtotime($res1['date_end']));
            }
    //print_r($ev_events);      
    $totalIds = count($ev_ids);
    //echo $totalIds;
    //print_r($categories);
    for ($i=0; $i < $totalIds; $i++) 
    { //echo "do";
        if ($ev_tags[$i] == $_GET['event'] ) 
        {
            $found = $i;
            //echo $found;
        }
    }
    //echo $ev_events[$start];

    $media_link = $ev_media_link[$found];

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
                <ul id="scroller"><!--TODO: put php of events-->
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

    <div id="content" class="container" >

        <div id="main" class="row-fluid">
            <div id="main-left" class="span8">
                <?php $final = $found+1;
                for ($i=$found; $i < $final; $i++) 
                { ?>
            <div style="background-color: white; padding: 5px 5px 5px 5px">
                <article class="post">
                    <h2 class="entry-title">
                        <?php echo $ev_events[$i];?>
                        <span class="entry-cat"><a href="ev_categories.php?cat=<?php echo $ev_categories_name[$i]; ?>" title="View all posts in <?php echo $ev_categories_name[$i]; ?> News" rel="category tag"><?php echo $ev_categories_name[$i];?></a></span>
                    </h2>
                    <div class="entry-meta row-fluid">
                        <ul class="clearfix">

                            <li><img src="images/time.png" alt=""><?php echo $ev_card_date_starts[$i]." to ".$ev_card_date_ends[$i]
                            ; ?></li>
                            <li><img src="images/venue.png" alt=""><?php echo $ev_venue[$i]; ?></li>
                            <li><img src="images/country.png" alt=""><?php echo $ev_countries[$i]; ?></li>
                        </ul>
                    </div>
                    
                    <div class="entry-content">
                        <a href="events.php?event=<?php echo $ev_tags[$i]; ?>" title="<?php echo $ev_tags[$i]; ?>">
                        <img width="774" height="320" src="<?php echo $ev_source[$i]; ?>" alt="" />
                        </a>
                        <p><br><?php echo $ev_details[$i]; ?></p>
                        <p style="color: black; "><b> For : </b><?php echo $ev_attendee[$i];?> </p>
                        <p class="moretag"><a target="_blank" href="<?php echo $ev_link[$i]; ?>"> Read more</a></p>
                    </div>
                </article>   
            </div>           
                <?php } ?>

                
            <div class="pagination magz-pagination"><a class="prev page-numbers" href="all_events.php?p=4">Load More</a></div>

            </div><!-- #main-left -->

        <div id="sidebar" class="span4">

            <div id="tabwidget" class="widget tab-container" style="background-color: white;"> 
                <ul id="tabnav" class="clearfix"> 
                    <li><h3><a href="#tab1" class="selected"><img src="images/view-white-bg.png" alt="Popular">Popular</a></h3></li>
                    <li><h3><a href="#tab2"><img src="images/time-white.png" alt="Recent">Recent</a></h3></li>
                </ul> 

            <div id="tab-content">
            
            <div id="tab2" style="display: block; background-color: white;">
                <ul id="itemContainer" class="recent-tab">
                <?php for ($i=5; $i < 17 ; $i++) {  ?>
                    <li>
                        <a href="news.php?id=<?php echo $slugs[$i]; ?>"><img width="225" height="136" src="<?php echo $sources[$i]; ?>" class="thumb" alt="" /></a>
                        <h4 class="post-title"><a href="news.php?id=<?php echo $slugs[$i]; ?>"><?php $small = substr($headlines[$i], 0, 60); echo $small; ?> ...</a></h4>
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
                
                <div class="holder clearfix"></div>
                <div class="clear"></div>

            <!-- End most viewed post -->         

            </div><!-- /#tab1 -->
 
            <div id="tab2" style="display: none; display: block; background-color: white;">  
                <ul id="itemContainer2" class="recent-tab">
                <?php for ($i=3; $i < 6 ; $i++) {  ?>
                    <li>
                        <a href="news.php?id=<?php echo $slugs[$i]; ?>"><img width="225" height="136" src="<?php echo $sources[$i]; ?>" class="thumb" alt="" /></a>
                        <h4 class="post-title"><a href="news.php?id=<?php echo $slugs[$i]; ?>"><?php $small = substr($headlines[$i], 0, 60); echo $small; ?> ...</a></h4>
                        <p><?php $small = substr($contents[$i], 0, 60); echo $small; ?> ...</p>
                        <div class="clearfix"></div>    
                    </li>
                    <?php } ?>
                </ul>    
            </div><!-- /#tab2 --> 

            <!-- /#tab2 --> 
    
            </div><!-- /#tab-content -->

            </div>
            

            
            <?php if ($media_link) { ?>


            <div class="video-box widget row-fluid">
                <h3 class="title"><span style="background-color: #;color: #;">Videos Gallery</span></h3>        
                <iframe width="369" height="188" src="<?php echo $media_link; ?>" frameborder="0" allowfullscreen></iframe>
                
            </div><!-- sidebar -->
            <?php } ?>
        
        <div class="clearfix"></div>

        </div><!-- #main -->

        </div>
        </div> <!-- #content -->

    <footer id="footer" class="row-fluid">
        <div id="footer-widgets" class="container">

            <div class="footer-widget span3 block3">
                <div class="widget">
                    <h3 class="title"><span>Tag Cloud</span></h3>
                    <div class="tagcloud">
                        <a href='#'>Yarn</a>
                        <a href='#'>Cotton</a>
                        <a href='#'>Home Textile</a>
                        <a href='#'>Institutional</a>
                        <a href='#'>Fashion</a>
                        <a href='#'>Machinery</a>
                        <a href='#'>Technical Textile</a>
                        <a href='#'>Apparel</a>
                        <a href='#'>Textile</a>
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
    