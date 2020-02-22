<?php

define("PATH_CAM",     '/var/www/dokuwiki/www/cam304/camera/');


function HumanSize($Bytes)
{
  $Type=array("", "kilo", "mega", "giga", "tera", "peta", "exa", "zetta", "yotta");
  $Index=0;
  while($Bytes>=1024)
  {
    $Bytes/=1024;
    $Index++;
  }
  return("".$Bytes." ".$Type[$Index]."bytes");
}

function dirToArray($dir) {
   $result = array();
   $cdir = scandir($dir); 
   foreach ($cdir as $key => $value) 
   { 
      if (!in_array($value,array(".",".."))) 
      { 
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
         { 
            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
         } 
         else 
         { 
            $result[] = $value; 
         } 
      } 
   } 
   return $result; 
} 

// funct directory to aray 
function camDirToArray($dir)
{
   $dirArr=array_diff(scandir($dir.'/record/web',1), array('.', '..','crontab.log'));
   
   /*$dirArr=array_filter($dirArr, function($v) {
      return is_dir($v);
   });*/
   array_walk($dirArr, function (&$value, $key) use (&$dir) {
      // $value=str_replace($dir,"/var/www/dokuwiki",".")."/images/$value";
      $value=str_replace("/var/www/dokuwiki","",$dir)."/record/web/$value";
   });
   return $dirArr;
}

// parse filename to hour 
function parseFilenameToHour($filename)
{ 
   // A190419_091546_091600.mp4 
   preg_match('/A(\d\d)(\d\d)(\d\d)_(\d\d)(\d\d)(\d\d)\_\d\d\d\d\d\d.mp4/', $filename, $matches);
   return $matches;
}

// file name pic to show
function fileThumbAvi($file_pic = null)
{
   $fDt=parseFilenameToHour($file_pic);
   $val= <<<EOT
   <video width="320" height="126" controls="controls" >
     <source src="$file_pic" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
     Тег video не поддерживается вашим браузером. 
     <a href="$file_pic">Скачайте видео</a>.
    </video>
EOT;
   return $val;
}

// func to show html directory
function camShowDir($path,$dir)
{
   $val="";
   $arr=camDirToArray($path.$dir);
   $curHour="";
   $showHour= (isset($_GET["HR"]))  ? $_GET["HR"] : date("G");
   foreach ($arr as &$value) {
      
      // . "Year $fDt[1] Month $fDt[2] Day $fDt[3] Hour $fDt[4] Min $fDt[5] Sec $fDt[6]"
      $fDt=parseFilenameToHour($value); //  (!isset($_GET["dt"]))  ? $value : $_GET["dt"] || is_null(parseFilenameToHour($_GET["dt"]))
      if ($curHour!==$fDt[3].$fDt[4]) {
         $curHour=$fDt[3].$fDt[4];
         // $val.="<br> Hour $fDt[4] <br>";
         $val.="<br/> Hour:<a href='/www/cam/?page=video&dt=${dir}&HR=${fDt[4]}'>${fDt[4]}</a><br/>";
      }
      if ($showHour=== $fDt[4]) {
         # code...
         $val.= fileThumbAvi($value);
         //$val.= "$value<br> ";
      }
      
   }
   unset($value); // разорвать ссылку на последний элемент
   return $val;
}


function dirListToArray($path = '.')
{
   # code...
   return  array_diff(scandir($path,1), array('.', '..','crontab.log'));
}

function printImg($v,$pfx = ''){
  $result='';
  if (is_array($v)) {
    foreach($v as $key => $value) {
//     echo "Itter $key $value -";
//     $pfx= is_array($value) ?  $pfx.$key.DIRECTORY_SEPARATOR : $pfx ;
//     echo "PFX $pfx\n";
    if (preg_match("/20\d{6}\b/i", $key)) {
       $result.=is_int($key) ? "<h3>$key</h3>" :"";
    }
     $result.=printImg($value, is_array($value) ?  $pfx.$key.DIRECTORY_SEPARATOR : $pfx );
    }
  } else {
    $result.='<img src="'.$pfx.$v.'" width="200" height="150" alt="lorem">'."\n";
  }
 return $result;
}


// echo "<h3> List of all images </h3>\n";

//Get a list of file paths using the glob function.
//$fileList = glob('/var/www/dokuwiki/www/cam304/camera/*');
$fileList = dirToArray(PATH_CAM);
//Loop through the array that glob returned.
/*foreach($fileList as $filename){
   //Simply print them out onto the screen.
   echo $filename, '<br>';
}*/

// echo printImg($fileList,'/www/cam304/camera/');



// ------------- Code ------------------------------------

echo "<h2> Free ".HumanSize(disk_free_space(PATH_CAM))."</h2>";


foreach (dirListToArray(PATH_CAM) as &$value) {
   // Show only directories
     if ((strpos($value, 'log'))) { continue; }
   echo "<br> Date:<a href='/www/cam/?page=video&dt=${value}'>${value}</a>";
   // Show pictures on today +yestoday or params dt
   if (
         ((isset($_GET["dt"]) ? $_GET["dt"] : "") === $value)
             OR
         (!isset($_GET["dt"]) AND ($value===date("Ymd")))
      )
    {
      //implode(" ", camShowDir(PATH_CAM.$value));
      echo camShowDir(PATH_CAM,$value);
   }
}
unset($value); // разорвать ссылку на последний элемент


/*
echo '<pre>';
print_r($fileList);
echo '</pre>';
*/