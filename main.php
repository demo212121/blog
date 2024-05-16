<?
include "db.php";
$sql="select * from blog";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Document</title>

    <nav role="navigation">
  <div id="menuToggle">
   

    <input type="checkbox" />
    
    <span></span>
    <span></span>
    <span></span>
  
    <ul id="menu">
      <a href="http://aitugovs.atwebpages.com/blog/input.php"><li>Create post!</li></a>
      <a href=""><li></li></a>
    </ul>
  </div>
</nav>

<style>
body
{
  margin: 20;
  padding: 1;

  background-color:#89cff0;
  color: black;
  font-family: "Avenir Next", "Avenir", sans-serif;
}

}
#menuToggle
{
  display: block;
  position: relative;
  top: 50px;
  left: 50px;
  
  z-index: 1;
  
  -webkit-user-select: none;
  user-select: none;
}

#menuToggle a
{
  text-decoration: none;
  color: #89CFF0;
  
  transition: color 0.3s ease;
}

#menuToggle a:hover
{
  color: white;
}


#menuToggle input
{
  display: block;
  width: 40px;
  height: 32px;
  position: absolute;
  top: 0px;
  left: 0px;
  
  cursor: pointer;
  
  opacity: 0; 
  z-index: 2; 
  
  -webkit-touch-callout: none;
}


#menuToggle span
{
  display: block;
  width: 33px;
  height: 4px;
  margin-bottom: 5px;
  position: relative;
  
  background: black;
  border-radius: 3px;
  
  z-index: 1;
  
  transform-origin: 4px 0px;
  
  transition: transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0),
              background 0.5s cubic-bezier(0.77,0.2,0.05,1.0),
              opacity 0.55s ease;
}

#menuToggle span:first-child
{
  transform-origin: 0% 0%;
}

#menuToggle span:nth-last-child(2)
{
  transform-origin: 0% 100%;
}


#menuToggle input:checked ~ span
{
  opacity: 1;
  transform: rotate(45deg) translate(-2px, -1px);
  background: #89CFF0;
}


#menuToggle input:checked ~ span:nth-last-child(3)
{
  opacity: 0;
  transform: rotate(0deg) scale(0.2, 0.2);
}


#menuToggle input:checked ~ span:nth-last-child(2)
{
  transform: rotate(-45deg) translate(0, -1px);
}


#menu
{
  position: absolute;
  width: 300px;
  margin: -100px 0 0 -50px;
  padding: 50px;
  padding-top: 125px;
  
  background:black;
  list-style-type: none;
  -webkit-font-smoothing: antialiased;
  
  transform-origin: 0% 0%;
  transform: translate(-100%, 0);
  
  transition: transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0);
}

#menu li
{
  padding: 10px 0;
  font-size: 22px;
}

#menuToggle input:checked ~ ul
{
  transform: none;
  
}

  <style>
  body{
  background-color:white;
  }
  
  .blog {
            display: flex;
        }
        .pic1 {

            height: 300px;
            width: 500px;
            border-style: solid;
            border-color: black;
        }
        .pic2 {
            margin-left: 550px;
            margin-right: auto;
            margin-top: -347px;
            height: 300px;
            width: 500px;
            border-style: solid;
            border-color: black;
        }
        .button {
            width: 100px;
        }
        .button1 {
            width: 100px;
            display: flex;
            gap: 5px;
        }

        .container{
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
            align-items: center;
        }
        .both {
            flex: 30%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
        }
           
	    img{
                height:100%;
                width:100%;
       }
    </style>

</head>
<body>
    <div class="container">

    <?
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
        
        $sqlimg="select * from blog_pictures where post_id='".$row['id']."' and is_main=1";
        $imgresults=$conn->query($sqlimg);
        
?>
<div class="both">
<h2><? echo $row['blog_title']; ?></h2>
    <div class="blog">    
        <div class="pic1">
        <a href="comment.php?id=<? echo $row['id']; ?>">
        <? while($imgrow = $imgresults->fetch_assoc()) { ?>
        
            <img src="<? echo $imgrow['img_url']; ?>">
            <? } ?>
           
            </a>
        </div>
    </div>
    <div class="buttons">
        <div class="button1">
           <a type="button"class="fa fa-heart" href="comment.php?id=<? echo $row['id']; ?>"></a>
        </div>
        </div> 
    </div>
    <br>
    <?}}?>
        </div>
</body>

</html>

