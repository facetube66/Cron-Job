<?php
    $loadPage = "commonPage()";
    $cnt = 0;

    if(isset($_GET['state']))
       if($_GET['state'] == "true")
       {
            //
            $loadPage = "loadPage()";

            $servername = "localhost";
            $username = "root";
            $password = "";
            define('oldDB', "dbruvmtx1aln7g");
            define('newDB', "faq");

            $oldDB  = mysqli_connect($servername, $username, $password, oldDB);
            $newDB  = mysqli_connect($servername, $username, $password, newDB);

            if(!$oldDB){
                die("DATABASE1 CONNECTION ERROR: ".mysqli_connect_error());
            }

            if(!$newDB){
                die("DATABASE2 CONNECTION ERROR: ".mysqli_connect_error());
            }

            $oldSql = "SELECT * 
                       FROM `faqdata` 
                       ORDER BY id";

            $result = mysqli_query($oldDB, $oldSql);
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $newSql = "INSERT INTO `faq_faqdata`
                          (id, lang, solution_id, revision_id, active, 
                           sticky, keywords, thema, content, 
                           author, email, comment,
                           links_state, links_check_date, date_start, date_end)
                           VALUES(".$row['id'].", '".$row['lang']."', ".$row['solution_id'].", 
                           ".$row['revision_id'].", '".$row['active']."', 
                           ".$row['sticky'].", '".str_replace("'", "''", $row['keywords'])."', 
                           '".str_replace("'", "''", $row['thema'])."', '".str_replace("'", "''", $row['content'])."', 
                           '".str_replace("'", "''", $row['author'])."', '".$row['email']."', 
                           '".$row['comment']."', 
                           '".$row['links_state']."', ".$row['links_check_date'].", 
                           '".$row['date_start']."', '".$row['date_end']."')";
                
                $newResult = mysqli_query($newDB, $newSql);
                $cnt ++;
            }

            $oldSql = "SELECT * 
                       FROM `faqcomments` 
                       ORDER BY id";

            $result = mysqli_query($oldDB, $oldSql);
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $newSql = "INSERT INTO `faq_faqcomments`
                        (id_comment, id, `type`, usr, email, comment, datum, helped)
                        VALUES(".$row['id_comment'].", ".$row['id'].", '".$row['type']."', 
                        '".$row['usr']."', '".$row['email']."', 
                        '".$row['comment']."', ".$row['datum'].", 
                        '".$row['helped']."')";
                
                $newResult = mysqli_query($newDB, $newSql);
                $cnt ++;
            }
        } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>phpMyFAQ Cron Job</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    .container {
        margin-top: 50px;
    }
    h1 {
        text-align: center;
        margin-bottom: 50px;
    }

    .thumbnail {
        margin-top: 30px;
    }
  </style>
  <style>
    /* Center the loader */
    #loader {
        position: absolute;
        left: 50%;
        top: 50%;
        z-index: 1;
        width: 120px;
        height: 120px;
        margin: -76px 0 0 -76px;
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
    }

    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body style="background-color:#e7e7e4;" onload="<?php echo $loadPage;?>">
<div id="loader"></div>
<div class="container" style="display:none;" id="main">
    <div class="div-title">
        <h1>faq.tibiona.it</h1>
    </div>
    <h2>MySQL Update Cron Job(phpMyFAQ version upgrading-3.0.9->faqdata, comments)</h2>

    <?php if(!isset($_GET['state'])):?>
        <button type="button" class="btn btn-primary btn-block" style="height:50px" onclick="cronStart()">Start</button>
    <?php endif; ?>
    <?php if(isset($_GET['state']) && $_GET['state'] == true):?>
    <button type="button" class="btn btn-danger btn-block" style="height:50px" onclick="cronStop()">Cancel</button>
    <?php endif; ?>

    <!-- <?php if(isset($_GET['state']) && $_GET['state'] == true):?>
    <h2>Progressing...(dbruvmtx1aln7g ->)</h2>
    <div class="progress">
        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:40%">
            40% Complete (success)
        </div>
    </div>
    <?php endif; ?> -->

    <div class="thumbnail">
      <a href="https://faq.tibiona.it/v3/">
        <img src="../back.png" alt="Lights" style="width:100%">
        <div class="caption">
          <p>For my client Aldo...</p>
        </div>
      </a>
    </div>
</div>
</body>

<script>
    function commonPage()
    {
        document.getElementById("loader").style.display = "none";
        document.getElementById("main").style.display = "block";
    }

    function loadPage()
    {
        myVar = setTimeout(showPage, 4000);
    }

    function showPage()
    {
        document.getElementById("loader").style.display = "none";
        document.getElementById("main").style.display = "block";
        alert("<?php echo $cnt; ?> datas successfully saved.");
    }

    function cronStart()
    {
        if(confirm("Do you want to start cron job?"))
        {
            location.replace('/config.php');
        }
    }
    function cronStop()
    {
        if(confirm("Do you want to stop cron job?"))
        {
            window.location = "";
            location.replace('./?stop');
        }
    }
</script>

</html>
