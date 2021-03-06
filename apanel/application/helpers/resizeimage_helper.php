<?php
 //define( 'THUMBNAIL_IMAGE_MAX_WIDTH', 600 );
 //define( 'THUMBNAIL_IMAGE_MAX_HEIGHT', 550 );

 function resizeImage( $source_image_path, $thumbnail_image_path , $newWidth = 700, $newHeight = 600)
 {
  list( $source_image_width, $source_image_height, $source_image_type ) = getimagesize( $source_image_path );

  switch ( $source_image_type )
  {
   case IMAGETYPE_GIF:
    $source_gd_image = imagecreatefromgif( $source_image_path );
    break;

   case IMAGETYPE_JPEG:
    $source_gd_image = imagecreatefromjpeg( $source_image_path );
    break;

   case IMAGETYPE_PNG:
    $source_gd_image = imagecreatefrompng( $source_image_path );
    break;
  }

  if ( $source_gd_image === false )
  {
   return false;
  }

  $thumbnail_image_width = $newWidth;
  $thumbnail_image_height = $newHeight;

  $source_aspect_ratio = $source_image_width / $source_image_height;
  $thumbnail_aspect_ratio = $thumbnail_image_width / $thumbnail_image_height;

  if ( $source_image_width <= $thumbnail_image_width && $source_image_height <= $thumbnail_image_height )
  {
   $thumbnail_image_width = $source_image_width;
   $thumbnail_image_height = $source_image_height;
  }
  elseif ( $thumbnail_aspect_ratio > $source_aspect_ratio )
  {
   $thumbnail_image_width = ( int ) ( $thumbnail_image_height * $source_aspect_ratio );
  }
  else
  {
   $thumbnail_image_height = ( int ) ( $thumbnail_image_width / $source_aspect_ratio );
  }

  $thumbnail_gd_image = imagecreatetruecolor( $thumbnail_image_width, $thumbnail_image_height );

  imagecopyresampled( $thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height );

  imagejpeg( $thumbnail_gd_image, $thumbnail_image_path, 90 );

  imagedestroy( $source_gd_image );

  imagedestroy( $thumbnail_gd_image );

  return true;
 }
?>
