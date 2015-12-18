<html>
<head>
<title>Diary</title>
<link rel="stylesheet" type="text/css" media="screen" href="./style.css" />
<meta name="Author" contect="www.lfhacks.com">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="Robots" contect= "none">

<meta charset="UTF-8">
</head>
<body>
<div class=wrapper>
<span>
<?php echo date("Y年m月d日H时i分");?>
</span>
<div>
<form method="POST" action="index.php"> 
    <div><textarea name="msg" rows="4"></textarea></div>
    <div class=btn><input name="Btn" type="submit" value="提交"></div>
</form>
</div>

<?php
$filename = "./posts.txt";
file_exists($filename) or file_put_contents($filename, "\xEF\xBB\xBF<div class=post><div class=time>".date("M d\r\nH:i\r\nD")."</div><div class=msg>-- start --</div></div>");
$original_posts = file_get_contents($filename);
if (isset($_POST["msg"])) {
    $msg = $_POST["msg"];
    ($msg=='') and die('Empty message.');
    $msg = preg_replace("/\bhttp:\/\/(\w+)+.*\b/",'<a href="$0">$0</a>',$msg);
    preg_match("/(\w{3}) (\d{2})\r\n\d{2}:\d{2}\r\n\w{3}/s",$original_posts,$matches) or die('No date found.');
    $post_month= $matches[1];
    $post_day= $matches[2];
    $current_month = date("M");
    $current_day = date("d");
    if($current_month===$post_month){
        if($current_day===$post_day){
            $time = date("H:i");
        }
        else{
            $time = date("M d\r\nH:i\r\nD");
        }
        $posts = "<div class=post><div class=time>$time</div><div class=msg>$msg</div></div>" . $original_posts;
        echo nl2br($posts);
        file_put_contents($filename, $posts);
    }
    else{
        $time = date("M d\r\nH:i\r\nD");
        $posts = "<div class=post><div class=time>$time</div><div class=msg>$msg</div></div>";
        echo nl2br($posts);
        if($post_month==='Dec' && $current_month==='Jan'){
            $newfile = "posts_".strval(intval(date("Y"))-1).'_'.$post_month.'.txt';
        }
        else{
            $newfile = "posts_".date("Y").'_'.$post_month.'.txt';
        }
        if (rename($filename, $newfile)){
            file_put_contents($filename, "\xEF\xBB\xBF".$posts);
        }
        else{
            die('Unable to rename $filename to $newfile');
        }
    }    
    redirect('index.php');
}
else{
    echo nl2br($original_posts);
}

function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}

?>
</div>
<span>©2014 laiqiao.com</span>
</body>
</html>