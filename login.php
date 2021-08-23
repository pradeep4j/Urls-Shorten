<?php 
  include "config.php";
  $new_url = "";
  if(isset($_GET)){
    foreach($_GET as $key=>$val){
      $u = mysqli_real_escape_string($conn, $key);
      $new_url = str_replace('/', '', $u);
    }
      $sql = mysqli_query($conn, "SELECT full_url FROM url WHERE shorten_url = '{$new_url}'");
      if(mysqli_num_rows($sql) > 0){
		  if(isset($_GET['id'])){		  
				$sql2 = mysqli_query($conn, "UPDATE url SET clicks = clicks + 1 and user_id=".$_GET['id']." WHERE shorten_url = '{$new_url}'");
				if($sql2){
					$full_url = mysqli_fetch_assoc($sql);
					header("Location:".$full_url['full_url']);
				  }
		  }
		  else{
			  $sql2 = mysqli_query($conn, "UPDATE url SET clicks = clicks + 1 WHERE shorten_url = '{$new_url}'");
				if($sql2){
					$full_url = mysqli_fetch_assoc($sql);
					header("Location:".$full_url['full_url']);
				}
      
		  }
	  }
	  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>URL Shortener</title>
  <link rel="stylesheet" href="style.css">
  <!-- Iconsout Link for Icons -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
  <style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
    nav{
        padding-left: 100px!important;
        padding-right: 100px!important;
        background: #20B2AA;
        font-family: 'Poppins', sans-serif;
    } 
    nav a.navbar-brand{
        color: #fff;
        font-size: 30px!important;
        font-weight: 500;
    }
    button a{
        color: #20B2AA;
        font-weight: 500;
    }
    button a:hover{
        text-decoration: none;
    }
    h1{
        position: absolute;
        top: 50%;
        left: 50%;
        width: 100%;
        text-align: center;
        transform: translate(-50%, -50%);
        font-size: 50px;
        font-weight: 600;
    }
    </style>
</head>	
<body>
  <div class="wrapper"><button type="button" ><a href="logout-user.php">Logout</a></button>
    <form action="#" autocomplete="off">
      <input type="text" spellcheck="false" name="full_url" placeholder="Enter or paste a long url" required>
      <i class="url-icon uil uil-link"></i>
      <button>Shorten</button>
	  <input type="hidden" name="id" value='<?php echo $_REQUEST['id'];?>'>
    </form>
    <?php
      $sql2 = mysqli_query($conn, "SELECT * FROM url where users_id='".$_SESSION['id']."' ORDER BY id DESC");
      if(mysqli_num_rows($sql2) > 0){;
        ?>
          <div class="statistics">
            <?php
              $sql3 = mysqli_query($conn, "SELECT COUNT(*) FROM url");
              $res = mysqli_fetch_assoc($sql3);

              $sql4 = mysqli_query($conn, "SELECT clicks FROM url");
              $total = 0;
              while($count = mysqli_fetch_assoc($sql4)){
                $total = $count['clicks'] + $total;
              }
            ?>
            <span>Total Links: <span><?php echo end($res) ?></span> & Total Clicks: <span><?php echo $total ?></span></span>
            <a href="delete.php?delete=all">Clear All</a>
        </div>
        <div class="urls-area">
          <div class="title">
            <li>Shorten URL</li>
            <li>Original URL</li>
            <li>Clicks</li>
            <li>Action</li>
          </div>
          <?php
            while($row = mysqli_fetch_assoc($sql2)){
              ?>
                <div class="data">
                <li>
                  <a href="<?php echo $row['shorten_url'] ?>" target="_blank">
                  <?php
                    if($domain.strlen($row['shorten_url']) > 50){
                      echo $domain.substr($row['shorten_url'], 0, 50) . '...';
                    }else{
                      echo $domain.$row['shorten_url'];
                    }
                  ?>
                  </a>
                </li> 
                <li>
                  <?php
                    if(strlen($row['full_url']) > 60){
                      echo substr($row['full_url'], 0, 60) . '...';
                    }else{
                      echo $row['full_url'];
                    }
                  ?>
                </li> 
              </li>
                <li><?php echo $row['clicks'] ?></li>
                <li><a href="delete.php?id=<?php echo $row['shorten_url'] ?>">Delete</a></li>
              </div>
              <?php
            }
          ?>
      </div>
        <?php
      }
    ?>
  </div>

  <div class="blur-effect"></div>
  <div class="popup-box">
  <div class="info-box">Your short link is ready. You can also edit your short link now but can't edit once you saved it.</div>
  <form action="#" autocomplete="off">
    <label>Edit your shorten url</label>
    <input type="text" class="shorten-url" spellcheck="false" required>
    <i class="copy-icon uil uil-copy-alt"></i>
	<input type="hidden" name="ids" value='<?php echo $_REQUEST['id'];?>'>
    <button>Save</button>
  </form>
  </div>

  <script src="script.js"></script>

</body>
</html>

