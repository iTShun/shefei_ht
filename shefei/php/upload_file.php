<?php

// 允许上传的图片后缀
$allowedExts = array("xls", "xlsx");
//默认路径
$path = "../upload/";

$temp = explode(".", $_FILES["file"]["name"]);
//echo $_FILES["file"]["size"];
$extension = end($temp);     // 获取文件后缀名
if (in_array($extension, $allowedExts)
&& $_FILES["file"]["size"] < (1024 * 1024))   // 小于 1024 kb (1MB)
{
    if ($_FILES["file"]["error"] > 0)
    {
        echo "错误：: " . $_FILES["file"]["error"] . "<br>";
    }
    else
    {
        //echo "上传文件名: " . $_FILES["file"]["name"] . "<br>";
        //echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
        //echo "文件大小: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        //echo "文件临时存储的位置: " . $_FILES["file"]["tmp_name"] . "<br>";
        
        // 判断当期目录下的 upload 目录是否存在该文件
        // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
        if (!file_exists($path))
        {
        	//创建目录
        	mkdir($path);
        }

        if (file_exists($path . $_FILES["file"]["name"]))
        {
            echo $_FILES["file"]["name"] . " 文件已经存在。 ";
        }
        else
        {
            // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
            move_uploaded_file($_FILES["file"]["tmp_name"], $path . $_FILES["file"]["name"]);
            //echo "文件存储在: " . $path . $_FILES["file"]["name"];
            echo "<script language='JavaScript'>alert('上传成功!');</script>";
            echo "<script>location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
        }
    }
}
else
{
    echo "非法的文件格式";
}

?>