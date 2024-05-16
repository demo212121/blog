<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
  <nav role="navigation">
  <div id="menuToggle">
   

    <input type="checkbox" />
    
    <span></span>
    <span></span>
    <span></span>
  
    <ul id="menu">
      <a href="http://aitugovs.atwebpages.com/blog/main.php"><li>Main page</li></a>
      <a href="http://aitugovs.atwebpages.com/blog/input.php"><li>Create post!</li></a>
     
    </ul>
  </div>
</nav>
<script defer src="java.cs"></script>
<style>
body
{
  margin: 20;
  padding: 1;
  background-color:#89cff0;
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
  
  background: black;
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
        
.single_blog{
font-size:30px;
text-align:center;
}

.blog_text{
font-size:30px;
text-align:center;
}

.time{
font-size:30px;
text-align:center;
}

.post_id{
height:100%;
width:100%;
display:flex;
justify-content:center
}


.img{
width:400px;
height:400px;
border-style: solid;
}
.comment{
display:flex;
justify-content:center;
}

       
</style>
</head>
<body>
    
 


<?

include "db.php";


$id=$_GET['id'];
$sql = "SELECT * FROM blog WHERE id=".$id;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $content= "<div class='single_blog'>" . $row['blog_title'] . "</div>" .
    "<div class= 'blog_text'>" . $row['blog_description'] . "</div>" .
    "<div class= 'time'>" . $row['time'] . "</div>";
    echo $content;
    
    $sqlp = "SELECT * FROM blog_pictures WHERE post_id=".$row['id'];
$resultp = $conn->query($sqlp);
    

  
if ($resultp->num_rows > 0) {
  while($rowp = $resultp->fetch_assoc()) {
   $pictures= "<div class='post_id'><img class='img' src='" . $rowp['img_url'] . "'></div>";
    echo $pictures;
}
    
} else {
  echo "0 results";
}
}
}



        if(isset($_POST["com"])) {
              $com = $_POST["com"];
              echo $com;
                
             $komentars="INSERT INTO blog_comment (blog_id, user_id, comment) VALUES ('$id', '$id', '$com')";
              $conn->query($komentars);
              
              }

?>
<div class="comment">

        <form method="post">
        
        <input name="com" type="text">
        <input type="submit" name="submit">
        

        </form>

</div>
 
</body>
</html>
