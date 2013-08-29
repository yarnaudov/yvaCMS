<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_gallery extends CI_Model{
	
    function run($module)
    {
	
	$this->load->model('gallery/Album');
        $this->load->model('gallery/Image');

	if($module['params']['albums'][0] == '*'){	    
	    $albums = $this->Album->getAlbums();	    
	}
	else{
	    $albums = $module['params']['albums'];
	    foreach($albums as $key => $album){
		$albums[$key] = $this->Album->getDetails($album);
	    }
	}
                  
	
	foreach($albums as $key => $album){
	    $albums[$key]['images'] = $this->Image->getImages(array('album_id' => $album['id']));
	}
	
        $data['albums'] = $albums;

        return module::loadContent($module, $data);

    }
    
}

