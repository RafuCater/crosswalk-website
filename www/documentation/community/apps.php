<!DOCTYPE html>
<html id="top" lang="en-us">
  <head>
    <meta charset="utf-8">
    <title>The Crosswalk Project</title>
    <link rel="shorcut icon" href="/assets/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="/assets/favicon.ico" type="image/x-icon" />
    <script>
      WebFontConfig = {
        custom: {
          families: ['Clear Sans'],
          urls: ['/css/fonts.css']
        },
        google: {
          families: ['Source Code Pro:n4,n6']
        },
        timeout: 2000
      };
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script async defer src="//ajax.googleapis.com/ajax/libs/webfont/1.5.3/webfont.js"></script>
    <link rel="stylesheet" href="/css/main.css">

    <meta name="description" content="Enable the most advanced web innovations with the Crosswalk Project web runtime to develop powerful Android and Cordova apps." />
    <meta name="author" content="Crosswalk" />
    <meta name="handheldfriendly" content="true" />
    <meta name="mobileoptimized" content="320" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="cleartype" content="on" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <!-- Facebook -->
    <meta property="og:side_name" content="Crosswalk" />
    <meta property="og:title" content="Crosswalk" />
    <meta property="og:url" content="http://crosswalk-project.org/documentation/community/apps" />
    <meta property="og:description" content="Enable the most advanced web innovations with the Crosswalk Project web runtime to develop powerful Android and Cordova apps." />
    <meta property="og:image" content="/assets/crosswalk-og-banner.jpg" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:url" content="http://crosswalk-project.org/documentation/community/apps" />
    <meta name="twitter:title" content="Crosswalk" />
    <meta name="twitter:description" content="Enable the most advanced web innovations with the Crosswalk Project web runtime to develop powerful Android and Cordova apps." />
    <meta name="twitter:site" content="@xwalk_project" />

    <!-- Relevant original Crosswalk Project JS -->
    <script src="/js/utils.js"></script>
    <script src="/js/xwalk.js"></script>
    <script src="/js/versions.js"></script>
    <script src="/js/demos.js"></script>
    <script src="/js/testimonials.js"></script>
    <script src="/js/tools.js"></script>
    <script src="/js/qualityindicators.js"></script>
  </head>
  <body>
    <!-- If the current page is an index less than two
         directories deep or in the root directory,
         leave it alone so we can do custom layouts.
         Otherwise, provide the header and nav. -->
    
    <div class="container">
      <div class="doc-header">
        

  

  <div class="doc-logo-div">
     <a href="/" class="doc-logo-link">
       <img src="/assets/identity/crosswalkproject-logo-horizontal-dark.png" class="doc-logo-img">
     </a>
  </div>
  <div class="doc-nav-div">
    <ul class="doc-nav-list">
      <li class="doc-nav-item">
        <a href="/documentation/getting_started.html" class="doc-nav-link">Documentation</a>
      </li>
      <li class="doc-nav-item">
        <a href="/blog" class="doc-nav-link">Blog</a>
      </li>
      <li class="doc-nav-item">
        <a href="/contribute" class="doc-nav-link">Contribute</a>
      </li>
      <li class="doc-nav-item">
        <a href="https://github.com/crosswalk-project/crosswalk-website/wiki" class="doc-nav-link">Wiki</a>
      </li>
      <li class="doc-nav-item hide-on-small">
        <a href="/documentation/about/faq.html" class="doc-nav-link">FAQ</a>
      </li>
      <li class="doc-nav-item hide-on-small">
        <a href="/documentation/getting_started.html" class="doc-nav-link" style="border:1px solid #ec543b;padding:8px;color:#ec543b;" >get started</a>
      </li>
    </ul>
  </div>
  

      </div>
      <br />
      <div class="doc-main">
        <div class="row">
          
          
<nav id="contents" class="article-toc nav-toggleContainer">
  <a href="#contents" id="contents-toggle" class="button button--small button--tertiary nav-toggle">Table of Contents</a>
  <a href="./#contents-toggle" class="button button--small button--tertiary nav-toggle--dummy">Table of Contents</a>
  <ul class="article-list nav-toggleHide">
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/about.html">About</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/getting_started.html">Getting Started</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/android.html">Android</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/ios.html">iOS</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/linux.html">Linux</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/tizen.html">Tizen</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/manifest.html">Manifest</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/apis.html">APIs</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/cordova.html">Cordova</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/downloads.html">Downloads</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/embedding_crosswalk.html">Embedding Crosswalk</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/shared_mode.html">Shared Mode</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/webrtc.html">WebRTC</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/screens.html">Screens</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/samples.html">Samples</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/community.html">Community</a>

         

         <ul class="article-list">
            
           <li class="article-item ">
             <a class="article-link" href="/documentation/community/awards/copu2015.html">Awards</a>

             
           </li>
           
           <li class="article-item ">
             <a class="article-link" href="/documentation/community/conferences/tizen2015.html">Tizen Devel Conf 2015</a>

             
           </li>
           
           <li class="article-item ">
             <a class="article-link" href="/documentation/community/conferences.html">Conferences</a>

             
           </li>
           
           <li class="article-item ">
             <a class="article-link" href="/documentation/community/presentations.html">Presentations</a>

             
           </li>
           
           <li class="article-item ">
             <a class="article-link" href="/documentation/community/testimonials.html">Testimonials</a>

             
           </li>
           
           <li class="article-item ">
             <a class="article-link" href="/documentation/community/tools.html">Tools</a>

             
           </li>
           
           <li class="article-item ">
             <a class="article-link" href="/documentation/community/apps.php">Applications</a>

             
           </li>
           
         </ul>
         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/report_bugs.html">Report bugs</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/test_suite.html">Test Suite</a>

         
       </li>
    
       <li class="article-item ">
         <a class="article-link" href="/documentation/quality_dashboard.html">Quality Dashboard</a>

         
       </li>
    
  </ul>

</nav>



<article class="article article--hasToC">
  
<div id="appListPage">
  <h2>Crosswalk Project-Empowered Apps</h2>
  <p>This page showcases applications that have been built using the Crosswalk Project.  All applications listed have been published in an application store.</p>

  <p><a href="/documentation/community/app-submit.html">App Submission Page</a></p>

  <div class="appListMain">
    <div class="appListGrid" id="appListGrid" >
      Error: Loading applications failed.
    </div>
  </div>
</div> <!-- appListPage -->

<br clear="all" />

<script src="/js/apps.js"></scrip>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<?php
// Query for apps from MySql DB, loaded into JSON Javascript format

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$retVal = "";
$dbRows = array();

function getAppsFromDb () {
    global $retVal;
    global $dbRows;

    $retVal = "";
    try {
        $mysqli  = new mysqli('localhost', 'xwalkweb', 'webapps', 'xwalk');
        if ($mysqli->connect_errno) {
            throw new RuntimeException("Unable to connect to database. " . $mysqli->connect_error);
        }

        if (!($stmt = $mysqli->prepare ("SELECT storeUrl, name, image, author, authorUrl, email, publishDate, downloads, price, size, architecture, xdk, category, version, notes, status FROM xwalk_apps"))) {
            throw new RuntimeException("Unable to prepare database SELECT statement." . $mysqli->error);
        }
        if (!$stmt->execute()) {
            throw new RuntimeException("Unable to execute database insert statement." . $stmt->error);
        }
        // 15 values
        if (!($stmt->bind_result($storeUrl, $name, $image, $author, $authorUrl, $email, 
                                 $publishDate, $downloads, $price, $size, $architecture, 
                                 $xdk, $category, $version, $notes, $status))) {
            throw new RuntimeException("Unable to bind results from databaes SELECT statement" . $stmt->error);
        }

        // fetch values
        while ($stmt->fetch()) {
            $dbRows[] = ["storeUrl" =>  $storeUrl, 
                         "name" =>      $name,
                         "image" =>     $image,
                         "author" =>    $author, 
                         "authorUrl" => $authorUrl, 
                         "email" =>     $email, 
                         "publishDate" => $publishDate, 
                         "downloads" => $downloads, 
                         "price" =>     $price, 
                         "size" =>      $size, 
                         "architecture" => $architecture, 
                         "xdk" =>       $xdk, 
                         "category" =>  $category, 
                         "version" =>   $version, 
                         "notes" =>     $notes, 
                         "status" =>    $status];
        }
        $stmt->close();
    } catch (RuntimeException $e) {
        $mysqli->close();
        $retVal = $e->getMessage();
        return false;
    }
    $mysqli->close();
    return true;
}

// Create <script> section with dbRows data
$content = "<script>\n";

//get apps from DB, convert into Javascript-usable (JSON) format, generate App List
if (getAppsFromDb ()) {
    $content .= "$('#appListGrid').html ( displayApps(" . json_encode($dbRows) . ", null));\n";
} else {
    $content .= "$('#appListGrid').html ('No application information is available. ' + $retVal);\n";
} 

$content .= "</script>\n";

echo $content;

/*
+--------------+---------------+------+-----+---------+----------------+
| Field        | Type          | Null | Key | Default | Extra          |
+--------------+---------------+------+-----+---------+----------------+
| appid        | int(11)       | NO   | PRI | NULL    | auto_increment |
| status       | varchar(20)   | NO   |     | NULL    |                |
| storeUrl     | varchar(2000) | YES  |     | NULL    |                |
| name         | varchar(255)  | NO   |     | NULL    |                |
| image        | varchar(255)  | NO   |     | NULL    |                |
| author       | varchar(100)  | NO   |     | NULL    |                |
| authorUrl    | varchar(2000) | YES  |     | NULL    |                |
| email        | varchar(100)  | NO   |     | NULL    |                |
| publishDate  | date          | YES  |     | NULL    |                |
| downloads    | int(11)       | YES  |     | NULL    |                |
| price        | decimal(6,2)  | YES  |     | NULL    |                |
| size         | int(11)       | YES  |     | NULL    |                |
| architecture | varchar(100)  | YES  |     | NULL    |                |
| xdk          | bit(1)        | YES  |     | NULL    |                |
| category     | varchar(100)  | YES  |     | NULL    |                |
| version      | varchar(100)  | YES  |     | NULL    |                |
| notes        | varchar(500)  | YES  |     | NULL    |                |
+--------------+---------------+------+-----+---------+----------------+
*/

?>

  <footer class="article-next">
    

  
  
    
    
  
    
    
  
    
    
  
    
    
  
    
    
  
    
    
  
    
    
  


  </footer>
</article>

          
        </div>
      </div>
    </div>
    

    <hr class="footer-divider" style="margin-top:125px; margin-bottom:0px" />
    <div style="position:relative; top:-30px;">
       <a href="/"><img src="/assets/cw-logo-circle.png" width="60px" style="display:block; margin: 0 auto;" /></a>
    </div>
    <footer class="footer footer--documentation" >
      <div class="container" >
        <div class="row">
          <div  class="footer-div">
            <img src="/assets/Twitter_logo_blue.png" width="20px" /> Follow  <a href="http://twitter.com/xwalk_project">@xwalk_project on Twitter</a> for the latest developer activities and project updates.
          </div>
          <div class="footer-div">
             Latest blog post:</br>
              
              
                <b><a href="/blog">Announcing the Web Testing Service</a></b><br/>
                &nbsp;(<span ><time class="js-vagueTime" datetime="Sun, 06 Sep 2015 11:00:00 GMT">2015-09-06T11:00</time></span>)
              
              <br/>
           </div>
          <div class="footer-div">
              <strong><a href="/feed.xml"><img src="/assets/rss-icon-16.gif" style="vertical-align:middle" /> RSS Feed</a></strong>
          </div>
          <div class="footer-div">
             <a href="/documentation">Documentation</a> &nbsp;
             <a href="/blog">Blog</a> &nbsp;
             <a href="/documentation/downloads.html">Downloads</a> <br />
             <a href="https://crosswalk-project.org/jira/secure/Dashboard.jspa">Issues</a> &nbsp;
             <a href="https://github.com/crosswalk-project">GitHub source</a> &nbsp;
             <a href="/sitemap.html">Sitemap</a> <br/>
          </div>
        </div>
        <div class="row">
            <small>
              The Crosswalk Project was created by the Intel Open Source Technology Center. Copyright © 2013–2015 Intel Corporation. All rights reserved. <a href="https://github.com/crosswalk-project/crosswalk-website/wiki/Privacy-Policy">Privacy policy</a>. *Other names and brands may be claimed as the property of others.
            </small>
        </div>
      </div>
    </footer>

    
    
      <!-- Google Tag Manager -->
      <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WC843Q"
      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
      <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
      '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-WC843Q');</script>
      <!-- End Google Tag Manager -->
    
    <script src="/js/smoothScroll.js"></script>
    <script src="/js/vagueTime.js"></script>
    <!-- <script async defer src="/js/trmix.js"></script> -->
  </body>
</html>
