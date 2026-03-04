

 <?php
//$target =$_SERVER['DOCUMENT_ROOT'].'/storage/app/public';
//$link = $_SERVER['DOCUMENT_ROOT'].'/public/storage';
//symlink($target, $link);
//echo "Done";


$target =$_SERVER['DOCUMENT_ROOT'].'/sonali/storage/app/public';
$link = $_SERVER['DOCUMENT_ROOT'].'/sonali/public/storage';
symlink($target, $link);
echo "Done";
?> 