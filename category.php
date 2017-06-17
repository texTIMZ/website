<?php 
session_start();
include "config.php";
if (isset($_GET['cat'])) {
    $category = $_GET['cat'];
    if (($category != 'Apparel') && ($category != 'Textile') && ($category != 'Fashion') && ($category != 'Technical textile')&& ($category != 'Technology And Innovation') && ($category != 'Corporate')  && ($category != 'Events') && ($category != 'Retail') && ($category != 'E-Commerce') && ($category != 'Institutional') && ($category != 'Denim')) 
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
    $categories_n = array();
    while ($cat = $stmt1->fetch(PDO::FETCH_ASSOC)) {
        $categories_n[] = $cat['categories_name'];
    }
    $totalCats = count($categories_n);

    

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
    $stmt1 = $conn->prepare("SELECT a.pk_i_id , a.s_headline,a.s_content, a.s_create_time, a.s_slug, b.s_source, c.link_name, c.link , e.fk_i_category_id , e.fk_i_item_id , f.categories_name, f.pk_i_id from t_news_item as a, t_media as b, t_link as c, t_categories as e, s_categories as f where a.pk_i_id = b.fk_i_item_id and c.fk_i_news_item_id = a.pk_i_id and a.pk_i_id = e.fk_i_item_id and e.fk_i_category_id = 1 group by a.pk_i_id order by a.pk_i_id desc LIMIT 15");
    $stmt1->execute();

    //print_r($a_news_ids);
    //echo $totalNewsIds;
    $a_headlines   = array();
    $a_sources     = array();
    $a_slugs       = array();
    while ($apparel = $stmt1->fetch(PDO::FETCH_ASSOC))
            {
                    $a_headlines[]   = $apparel['s_headline'];
                    $a_sources[]     = $apparel['s_source'];
                    $a_slugs[]       = $apparel['s_slug'];
            }
    $a_totalIds = count($a_headlines);

    $stmt = $conn->prepare("SELECT a.pk_i_id , a.s_headline,a.s_content, a.s_create_time, a.s_slug, b.s_source, c.link_name, c.link , e.fk_i_category_id , e.fk_i_item_id , f.categories_name, f.pk_i_id from t_news_item as a, t_media as b, t_link as c, t_categories as e, s_categories as f where a.pk_i_id = b.fk_i_item_id and c.fk_i_news_item_id = a.pk_i_id and a.pk_i_id = e.fk_i_item_id and e.fk_i_category_id = 2 group by a.pk_i_id order by a.pk_i_id desc LIMIT 15");
    $stmt->execute();

    //print_r($a_news_ids);
    //echo $totalNewsIds;
    $t_createTimes = array();
    $t_headlines   = array();
    $t_slugs       = array();
    $t_sources     = array();
    $t_contents = array();
    $t_card_date = array();
    while ($textile = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                    $t_createTimes[] = $textile['s_create_time'];
                    $t_headlines[]   = $textile['s_headline'];
                    $t_slugs[]       = $textile['s_slug'];
                    $t_sources[]     = $textile['s_source'];
                    $t_contents[]     = $textile['s_content'];
                    $t_card_date[] = date('j M', strtotime($textile['s_create_time']));
            }
    $t_totalIds = count($t_headlines);
    $stmt3 = $conn->prepare("SELECT a.pk_i_id , a.s_headline,a.s_content, a.s_create_time, a.s_slug, b.s_source, c.link_name, c.link , e.fk_i_category_id , e.fk_i_item_id , f.categories_name, f.pk_i_id from t_news_item as a, t_media as b, t_link as c, t_categories as e, s_categories as f where a.pk_i_id = b.fk_i_item_id and c.fk_i_news_item_id = a.pk_i_id and a.pk_i_id = e.fk_i_item_id and e.fk_i_category_id = 3 group by a.pk_i_id order by a.pk_i_id desc LIMIT 15");
    $stmt3->execute();

    //print_r($a_news_ids);
    //echo $totalNewsIds;
    $f_createTimes = array();
    $f_headlines   = array();
    $f_slugs       = array();
    $f_sources     = array();
    $f_contents = array();
    $f_card_date = array();
    while ($Fashion = $stmt3->fetch(PDO::FETCH_ASSOC))
            {
                    $f_createTimes[] = $Fashion['s_create_time'];
                    $f_headlines[]   = $Fashion['s_headline'];
                    $f_slugs[]       = $Fashion['s_slug'];
                    $f_sources[]     = $Fashion['s_source'];
                    $f_contents[]     = $Fashion['s_content'];
                    $f_card_date[] = date('j M', strtotime($Fashion['s_create_time']));
            }
    $f_totalIds = count($f_headlines);
    
    $stmt4 = $conn->prepare("SELECT a.pk_i_id , a.s_headline,a.s_content, a.s_create_time, a.s_slug, b.s_source, c.link_name, c.link , e.fk_i_category_id , e.fk_i_item_id , f.categories_name, f.pk_i_id from t_news_item as a, t_media as b, t_link as c, t_categories as e, s_categories as f where a.pk_i_id = b.fk_i_item_id and c.fk_i_news_item_id = a.pk_i_id and a.pk_i_id = e.fk_i_item_id and e.fk_i_category_id = 4 group by a.pk_i_id order by a.pk_i_id desc LIMIT 15");
    $stmt4->execute();

    //print_r($a_news_ids);
    //echo $totalNewsIds;
    $tt_createTimes = array();
    $tt_headlines   = array();
    $tt_slugs       = array();
    $tt_sources     = array();
    $tt_contents = array();
    $tt_card_date = array();
    while ($tech_tex = $stmt4->fetch(PDO::FETCH_ASSOC))
            {
                    $tt_createTimes[] = $tech_tex['s_create_time'];
                    $tt_headlines[]   = $tech_tex['s_headline'];
                    $tt_slugs[]       = $tech_tex['s_slug'];
                    $tt_sources[]     = $tech_tex['s_source'];
                    $tt_contents[]     = $tech_tex['s_content'];
                    $tt_card_date[] = date('j M', strtotime($tech_tex['s_create_time']));
            }
    $tt_totalIds = count($tt_headlines);
    $stmt5 = $conn->prepare("SELECT a.pk_i_id , a.s_headline,a.s_content, a.s_create_time, a.s_slug, b.s_source, c.link_name, c.link , e.fk_i_category_id , e.fk_i_item_id , f.categories_name, f.pk_i_id from t_news_item as a, t_media as b, t_link as c, t_categories as e, s_categories as f where a.pk_i_id = b.fk_i_item_id and c.fk_i_news_item_id = a.pk_i_id and a.pk_i_id = e.fk_i_item_id and e.fk_i_category_id = 13 group by a.pk_i_id order by a.pk_i_id desc LIMIT 15");
    $stmt5->execute();

    //print_r($a_news_ids);
    //echo $totalNewsIds;
    $t_createTimes = array();
    $t_headlines   = array();
    $t_slugs       = array();
    $t_sources     = array();
    $t_contents = array();
    $t_card_date = array();
    while ($tech = $stmt5->fetch(PDO::FETCH_ASSOC))
            {
                    $t_createTimes[] = $tech['s_create_time'];
                    $t_headlines[]   = $tech['s_headline'];
                    $t_slugs[]       = $tech['s_slug'];
                    $t_sources[]     = $tech['s_source'];
                    $t_contents[]     = $tech['s_content'];
                    $t_card_date[] = date('j M', strtotime($tech['s_create_time']));
            }
    $tt_totalIds = count($tt_headlines);

    $stmt6 = $conn->prepare("SELECT a.pk_i_id , a.s_headline,a.s_content, a.s_create_time, a.s_slug, b.s_source, c.link_name, c.link , e.fk_i_category_id , e.fk_i_item_id , f.categories_name, f.pk_i_id from t_news_item as a, t_media as b, t_link as c, t_categories as e, s_categories as f where a.pk_i_id = b.fk_i_item_id and c.fk_i_news_item_id = a.pk_i_id and a.pk_i_id = e.fk_i_item_id and e.fk_i_category_id = 6 group by a.pk_i_id order by a.pk_i_id desc LIMIT 15");
    $stmt6->execute();

    //print_r($a_news_ids);
    //echo $totalNewsIds;
    $c_headlines   = array();
    $c_sources     = array();
    $c_slugs       = array();
    while ($corp = $stmt6->fetch(PDO::FETCH_ASSOC))
            {
                    $c_headlines[]   = $corp['s_headline'];
                    $c_sources[]     = $corp['s_source'];
                    $c_slugs[]       = $corp['s_slug'];
            }
    $a_totalIds = count($a_headlines);

 

    $stmt8 = $conn->prepare("SELECT a.pk_i_id , a.s_headline,a.s_content, a.s_create_time, a.s_slug, b.s_source, c.link_name, c.link , e.fk_i_category_id , e.fk_i_item_id , f.categories_name, f.pk_i_id from t_news_item as a, t_media as b, t_link as c, t_categories as e, s_categories as f where a.pk_i_id = b.fk_i_item_id and c.fk_i_news_item_id = a.pk_i_id and a.pk_i_id = e.fk_i_item_id and e.fk_i_category_id = 8 group by a.pk_i_id order by a.pk_i_id desc LIMIT 15");
    $stmt8->execute();

    //print_r($a_news_ids);
    //echo $totalNewsIds;
    $e_headlines   = array();
    $e_sources     = array();
    $e_slugs       = array();
    while ($event = $stmt8->fetch(PDO::FETCH_ASSOC))
            {
                    $e_headlines[]   = $event['s_headline'];
                    $e_sources[]     = $event['s_source'];
                    $e_slugs[]       = $event['s_slug'];
            }
    $a_totalIds = count($a_headlines);

    $stmt9 = $conn->prepare("SELECT a.pk_i_id , a.s_headline,a.s_content, a.s_create_time, a.s_slug, b.s_source, c.link_name, c.link , e.fk_i_category_id , e.fk_i_item_id , f.categories_name, f.pk_i_id from t_news_item as a, t_media as b, t_link as c, t_categories as e, s_categories as f where a.pk_i_id = b.fk_i_item_id and c.fk_i_news_item_id = a.pk_i_id and a.pk_i_id = e.fk_i_item_id and e.fk_i_category_id = 9 group by a.pk_i_id order by a.pk_i_id desc LIMIT 15");
    $stmt9->execute();

    //print_r($a_news_ids);
    //echo $totalNewsIds;
    $r_headlines   = array();
    $r_sources     = array();
    $r_slugs       = array();
    while ($retail = $stmt9->fetch(PDO::FETCH_ASSOC))
            {
                    $r_headlines[]   = $retail['s_headline'];
                    $r_sources[]     = $retail['s_source'];
                    $r_slugs[]       = $retail['s_slug'];
            }
    $a_totalIds = count($a_headlines);
    
    $stmt10 = $conn->prepare("SELECT a.pk_i_id , a.s_headline,a.s_content, a.s_create_time, a.s_slug, b.s_source, c.link_name, c.link , e.fk_i_category_id , e.fk_i_item_id , f.categories_name, f.pk_i_id from t_news_item as a, t_media as b, t_link as c, t_categories as e, s_categories as f where a.pk_i_id = b.fk_i_item_id and c.fk_i_news_item_id = a.pk_i_id and a.pk_i_id = e.fk_i_item_id and e.fk_i_category_id = 10 group by a.pk_i_id order by a.pk_i_id desc LIMIT 15");
    $stmt10->execute();

    //print_r($a_news_ids);
    //echo $totalNewsIds;
    $e_comm_headlines   = array();
    $e_comm_slugs       = array();
    $e_comm_sources     = array();
    while ($e_comm = $stmt10->fetch(PDO::FETCH_ASSOC))
            {
                    $e_comm_headlines[]   = $e_comm['s_headline'];
                    $e_comm_sources[]     = $e_comm['s_source'];
                    $e_comm_slugs[]       = $e_comm['s_slug'];
            }

    $stmt11 = $conn->prepare("SELECT a.pk_i_id , a.s_headline,a.s_content, a.s_create_time, a.s_slug, b.s_source, c.link_name, c.link , e.fk_i_category_id , e.fk_i_item_id , f.categories_name, f.pk_i_id from t_news_item as a, t_media as b, t_link as c, t_categories as e, s_categories as f where a.pk_i_id = b.fk_i_item_id and c.fk_i_news_item_id = a.pk_i_id and a.pk_i_id = e.fk_i_item_id and e.fk_i_category_id = 11 group by a.pk_i_id order by a.pk_i_id desc LIMIT 15");
    $stmt11->execute();

    //print_r($a_news_ids);
    //echo $totalNewsIds;
    $inst_headlines   = array();
    $inst_sources     = array();
    $inst_slugs       = array();
    while ($inst = $stmt11->fetch(PDO::FETCH_ASSOC))
            {
                    $inst_headlines[]   = $inst['s_headline'];
                    $inst_sources[]     = $inst['s_source'];
                    $inst_slugs[]       = $inst['s_slug'];
            }
    $stmt12 = $conn->prepare("SELECT a.pk_i_id , a.s_headline,a.s_content, a.s_create_time, a.s_slug, b.s_source, c.link_name, c.link , e.fk_i_category_id , e.fk_i_item_id , f.categories_name, f.pk_i_id from t_news_item as a, t_media as b, t_link as c, t_categories as e, s_categories as f where a.pk_i_id = b.fk_i_item_id and c.fk_i_news_item_id = a.pk_i_id and a.pk_i_id = e.fk_i_item_id and e.fk_i_category_id = 12 group by a.pk_i_id order by a.pk_i_id desc LIMIT 15");
    $stmt12->execute();

    //print_r($a_news_ids);
    //echo $totalNewsIds;
    $d_headlines   = array();
    $d_sources     = array();
    $d_slugs       = array();
    while ($denim = $stmt12->fetch(PDO::FETCH_ASSOC))
            {
                    $d_headlines[]   = $denim['s_headline'];
                    $d_sources[]     = $denim['s_source'];
                    $d_slugs[]       = $denim['s_slug'];
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
                        <li><a href="category.php?cat=<?php echo $categories_n[$i]; ?>"><?php echo $categories_n[$i]; ?></a></li>
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

    <div id="headline" class="container" style="display:none;">
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
            for ($i=0; $i <12 ; $i++) { 
                 ?> 
            <div class="kontengal4 span3" style="background-color: white; padding: 9px 9px 9px 9px">
                <article class="galleries">
                    <a href="news.php?id=<?php echo $a_slugs[$i]; ?>" title="" rel="prettyPhoto"><img width="570" height="360" src="<?php echo $a_sources[$i]; ?>" alt="" /></a>
                    <div style="height: 60px;">
                    <h3 class="gal-title"><a href="news.php?id=<?php echo $a_slugs[$i]; ?>" title="<?php echo $a_headlines[$i]; ?>" rel="bookmark"><?php $small = $a_headlines[$i]; echo $small; ?></a></h3>
                        </div>
                </article>
            </div>
            <?php }}
            if($category =='Textile') {
            for ($i=0; $i <12 ; $i++) { 
                 ?> 
            <div class="kontengal4 span3" style="background-color: white; padding: 9px 9px 9px 9px">
                <article class="galleries">
                    <a href="news.php?id=<?php echo $t_slugs[$i]; ?>" title="" rel="prettyPhoto"><img width="570" height="360" src="<?php echo $t_sources[$i]; ?>" alt="" /></a>
                    <div style="height: 60px;">
                    <h3 class="gal-title"><a href="news.php?id=<?php echo $t_slugs[$i]; ?>" title="<?php echo $t_headlines[$i]; ?>" rel="bookmark"><?php $small = $t_headlines[$i]; echo $small; ?></a></h3>
                        </div>
                </article>
            </div>
            <?php }} 
            if($category =='Fashion') {
            for ($i=0; $i <12 ; $i++) { 
                 ?> 
            <div class="kontengal4 span3" style="background-color: white; padding: 9px 9px 9px 9px">
                <article class="galleries">
                    <a href="news.php?id=<?php echo $f_slugs[$i]; ?>" title="" rel="prettyPhoto"><img width="570" height="360" src="<?php echo $f_sources[$i]; ?>" alt="" /></a>
                    <div style="height: 60px;">
                    <h3 class="gal-title"><a href="news.php?id=<?php echo $f_slugs[$i]; ?>" title="<?php echo $f_headlines[$i]; ?>" rel="bookmark"><?php $small = $f_headlines[$i]; echo $small; ?></a></h3>
                        </div>
                </article>
            </div>
            <?php }}
            if($category== 'Technical textile') {
            for ($i=0; $i <12 ; $i++) { 
                 ?> 
            <div class="kontengal4 span3" style="background-color: white; padding: 9px 9px 9px 9px">
                <article class="galleries">
                    <a href="news.php?id=<?php echo $tt_slugs[$i]; ?>" title="" rel="prettyPhoto"><img width="570" height="360" src="<?php echo $tt_sources[$i]; ?>" alt="" /></a>
                    <div style="height: 60px;">
                    <h3 class="gal-title"><a href="news.php?id=<?php echo $tt_slugs[$i]; ?>" title="<?php echo $tt_headlines[$i]; ?>" rel="bookmark"><?php $small = $tt_headlines[$i]; echo $small; ?></a></h3>
                        </div>
                </article>
            </div>
            <?php }}
            if($category== 'Technology And Innovation') {
            for ($i=0; $i <12 ; $i++) { 
                 ?> 
            <div class="kontengal4 span3" style="background-color: white; padding: 9px 9px 9px 9px">
                <article class="galleries">
                    <a href="news.php?id=<?php echo $t_slugs[$i]; ?>" title="" rel="prettyPhoto"><img width="570" height="360" src="<?php echo $t_sources[$i]; ?>" alt="" /></a>
                    <div style="height: 60px;">
                    <h3 class="gal-title"><a href="news.php?id=<?php echo $t_slugs[$i]; ?>" title="<?php echo $t_headlines[$i]; ?>" rel="bookmark"><?php $small = $t_headlines[$i]; echo $small; ?></a></h3>
                        </div>
                </article>
            </div>
            <?php }} 
            if($category== 'Corporate') {
            for ($i=0; $i <12 ; $i++) { 
                 ?> 
            <div class="kontengal4 span3" style="background-color: white; padding: 9px 9px 9px 9px">
                <article class="galleries">
                    <a href="news.php?id=<?php echo $c_slugs[$i]; ?>" title="" rel="prettyPhoto"><img width="570" height="360" src="<?php echo $c_sources[$i]; ?>" alt="" /></a>
                    <div style="height: 60px;">
                    <h3 class="gal-title"><a href="news.php?id=<?php echo $c_slugs[$i]; ?>" title="<?php echo $c_headlines[$i]; ?>" rel="bookmark"><?php $small = $c_headlines[$i]; echo $small; ?></a></h3>
                        </div>
                </article>
            </div>
            <?php }} 
            if($category== 'Events') {
            for ($i=0; $i <12 ; $i++) { 
                 ?> 
            <div class="kontengal4 span3" style="background-color: white; padding: 9px 9px 9px 9px">
                <article class="galleries">
                    <a href="news.php?id=<?php echo $e_slugs[$i]; ?>" title="" rel="prettyPhoto"><img width="570" height="360" src="<?php echo $e_sources[$i]; ?>" alt="" /></a>
                    <div style="height: 60px;">
                    <h3 class="gal-title"><a href="news.php?id=<?php echo $e_slugs[$i]; ?>" title="<?php echo $e_headlines[$i]; ?>" rel="bookmark"><?php $small = $e_headlines[$i]; echo $small; ?></a></h3>
                        </div>
                </article>
            </div>
            <?php }}
            if($category== 'Retail') {
            for ($i=0; $i <12 ; $i++) { 
                 ?> 
            <div class="kontengal4 span3" style="background-color: white; padding: 9px 9px 9px 9px">
                <article class="galleries">
                    <a href="news.php?id=<?php echo $r_slugs[$i]; ?>" title="" rel="prettyPhoto"><img width="570" height="360" src="<?php echo $r_sources[$i]; ?>" alt="" /></a>
                    <div style="height: 60px;">
                    <h3 class="gal-title"><a href="news.php?id=<?php echo $r_slugs[$i]; ?>" title="<?php echo $r_headlines[$i]; ?>" rel="bookmark"><?php $small = $r_headlines[$i]; echo $small; ?></a></h3>
                        </div>
                </article>
            </div>
            <?php }}

            if($category== 'E-Commerce') {
            for ($i=0; $i <12 ; $i++) { 
                 ?> 
            <div class="kontengal4 span3" style="background-color: white; padding: 9px 9px 9px 9px">
                <article class="galleries">
                    <a href="news.php?id=<?php echo $e_comm_slugs[$i]; ?>" title="" rel="prettyPhoto"><img width="570" height="360" src="<?php echo $e_comm_sources[$i]; ?>" alt="" /></a>
                    <div style="height: 60px;">
                    <h3 class="gal-title"><a href="news.php?id=<?php echo $e_comm_slugs[$i]; ?>" title="<?php echo $e_comm_headlines[$i]; ?>" rel="bookmark"><?php $small = $e_comm_headlines[$i]; echo $small; ?></a></h3>
                        </div>
                </article>
            </div>
            <?php }} 

            if($category== 'Institutional') {
            for ($i=0; $i <12 ; $i++) { 
                 ?> 
            <div class="kontengal4 span3" style="background-color: white; padding: 9px 9px 9px 9px">
                <article class="galleries">
                    <a href="news.php?id=<?php echo $inst_slugs[$i]; ?>" title="" rel="prettyPhoto"><img width="570" height="360" src="<?php echo $inst_sources[$i]; ?>" alt="" /></a>
                    <div style="height: 60px;">
                    <h3 class="gal-title"><a href="news.php?id=<?php echo $inst_slugs[$i]; ?>" title="<?php echo $inst_headlines[$i]; ?>" rel="bookmark"><?php $small = $inst_headlines[$i]; echo $small; ?></a></h3>
                        </div>
                </article>
            </div>
            <?php }} 

            if($category== 'Denim') {
            //for ($i=0; $i <12 ; $i++) { 
                 ?> 
            <!--<div class="kontengal4 span3">
                <article class="galleries">
                    <a href="news.php?id=<?php echo $d_slugs[$i]; ?> title="" rel="prettyPhoto"><img width="570" height="360" src="<?php echo $d_sources[$i]; ?>" alt="" /></a>
                    <div style="height: 60px;">
                    <h3 class="gal-title"><a href="news.php?id=<?php echo $d_slugs[$i]; ?>" title="<?php echo $d_headlines[$i]; ?>" rel="bookmark"><?php $small = $d_headlines[$i]; echo $small; ?></a></h3>
                        </div>
                </article>
            </div> -->
            WE ARE WORKING ON IT
            <?php }
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
                    <li><a href="terms.html">Blog</a></li>
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
