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

<center>
<?php
include "db.php";

if(isset($_POST['save'])){
    $sql = "INSERT INTO blog (blog_title, blog_description, image_url, time)
    VALUES ('".$_POST["blog_title"]."', '".$_POST["blog_description"]."', '".$_POST["img_url"]."', '".$_POST["time"]."')";

        $results =mysqli_query($conn, $sql);
        }
       
    ?>

    <form method="post">
    <label id="first">blog title</label><br/>
    <input type="text" name="blog_title"><br/>

    <label id="first">blog description</label><br/>
    <input type="text" name="blog_description"><br/>
   
    <label id="first">time</label><br/>
    <input type="date" name="time"><br/>
        <?
if(isset($_POST['save'])){
    // Assuming your table has columns img_url, post_id, and is_main
    $sql = "INSERT INTO blog_pictures (img_url, post_id, is_main)
            VALUES ('".$_POST["img_url"]."', ".$_POST["post_id"].", ".$_POST["is_main"].")";
}
    ?>

    <label id="first">picture</label><br/>
    <input type="text" name="img_url" placeholder="image url"><br/>
   
   
   
   
    <button type="submit" name="save">Save</button>
    </form>

</center>