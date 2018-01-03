<?php 

function getRGB($image,$x,$y)
    {
      $rgb=imagecolorat($image, $x, $y);
      $a=null;
      $a['r'] = ($rgb >> 16) & 0xFF;
      $a['g'] = ($rgb >> 8) & 0xFF;
      $a['b'] = $rgb & 0xFF;
      return $a;
    }
    function RGB_sum($array)
    {
      return $array['r']+$array['g']+$array['b'];
    }    
    function resize($source,$resize_w,$resize_h)
    {
        // 原始的 width, height
        $source_w = imagesx($source);
        $source_h = imagesy($source);
     
        // 建立放縮小後的圖用的底圖
        $resizeSource = imagecreatetruecolor($resize_w, $resize_h);
        
        // 縮放後貼到 resizeSource 上
        imagecopyresampled($resizeSource,$source,0,0,0,0,
                     $resize_w,$resize_h,
                     $source_w,$source_h) or die("error!\n");  
        
        /* 速度快，但品質較差的縮圖
        imagecopyresized($resizeSource,$source,0,0,0,0,
                     $resize_w,$resize_h,
                     $source_w,$source_h) or die("error!\n"); */                        
        //透空宣告
        $trans_colour = imagecolorallocatealpha($resizeSource, 255, 255, 255, 127);     
                         
        for($x=0;$x<$resize_w;$x++)
        {
          for($y=0;$y<$resize_h;$y++)
          {
            $rgb=getRGB($resizeSource,$x,$y);
            $rgb_sum=RGB_sum($rgb);
            if($rgb_sum >= 700)
            {  
              imagefill($resizeSource, $x, $y, $trans_colour);                            
            }
            else if($rgb_sum >= 600 && $rgb_sum < 700)
            {
              $trans80_colour = imagecolorallocatealpha($resizeSource, $rgb['r'], $rgb['g'], $rgb['b'], 80); 
              imagefill($resizeSource, $x, $y, $trans80_colour); 
            }
            else if($rgb_sum >= 500 && $rgb_sum < 600)
            {
              $trans40_colour = imagecolorallocatealpha($resizeSource, $rgb['r'], $rgb['g'], $rgb['b'], 40); 
              imagefill($resizeSource, $x, $y, $trans40_colour); 
            } 
            else if($rgb_sum >= 400 && $rgb_sum < 500)
            {
              $trans20_colour = imagecolorallocatealpha($resizeSource, $rgb['r'], $rgb['g'], $rgb['b'], 20); 
              imagefill($resizeSource, $x, $y, $trans20_colour); 
            }                        
          }
        }
  
        return $resizeSource;
    }
   header('Content-type: image/png');
   $file_name=basename($_GET['file_name']);
   //$file_name='./google.jpg';
   $im=imagecreatefromstring(file_get_contents($file_name));
   //取得尺寸
   list($cur_width,$cur_height)=getimagesize($file_name);
   
   //計算新尺吋(小),比差為s_code
   if(isset($_GET['w_size']))
   {
     $smallsize_width=(int)($_GET['w_size']);
   }   
   if($smallsize_width!=''){
     $s_code=$cur_width/$smallsize_width;
     $new_s_width=$smallsize_width;
     $new_s_height=$cur_height/$s_code;
   }
   else
   {
     $new_s_width=$cur_width;
     $new_s_height=$cur_height;
   }
   $new_s_width=(int)($new_s_width);
   $new_s_height=(int)($new_s_height);  
   $dst=resize($im,$new_s_width,$new_s_height); 
   imagealphablending($dst, false);
   imagesavealpha($dst, true);   
   imagepng($dst);
   imagedestroy($im);
   imagedestroy($dst);
?>