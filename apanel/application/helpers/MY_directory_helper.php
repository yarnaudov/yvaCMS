<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Directory Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/directory_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Create a Directory Map
 *
 * Reads the specified directory and builds an array
 * representation of it.  Sub-folders contained with the
 * directory will be mapped as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	int		depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
 * @return	array
 */
if ( ! function_exists('directory_map'))
{
	function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE)
	{
		if ($fp = @opendir($source_dir))
		{
			$filedata	= array();
			$new_depth	= $directory_depth - 1;
			$source_dir	= rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

			while (FALSE !== ($file = readdir($fp)))
			{
				// Remove '.', '..', and hidden files [optional]
				if ( ! trim($file, '.') OR ($hidden == FALSE && $file[0] == '.') OR $file == 'index.html')
				{
					continue;
				}
                                
				if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir.$file))
				{
					$filedata[$file] = directory_map($source_dir.$file.DIRECTORY_SEPARATOR, $new_depth, $hidden);
				}
				else
				{
					$filedata[] = $file;
				}
			}

			closedir($fp);
			return $filedata;
		}

		return FALSE;
	}
}

// Constants to make usage more reader-friendly
define('DIR_SORT_NAME',  1);
define('DIR_SORT_SIZE',  2);
define('DIR_SORT_ATIME', 3);
define('DIR_SORT_MTIME', 4);
define('DIR_SORT_CTIME', 5);

function readdir_sorted_array ($dir, $filters /*$sortCol = DIR_SORT_NAME, $sortDir = SORT_ASC*/) {

    // Validate arguments
    $dir = rtrim(str_replace('\\', '/', $dir), '/');
    $sortCol = (int) ($sortCol >= 1 && $sortCol <= 5) ? $sortCol : 1;
    $sortDir = ($sortDir == SORT_ASC) ? SORT_ASC : SORT_DESC;
    $name = $size = $aTime = $mTime = $cTime = $table = array();
    
    // Open the directory, return FALSE if we can't
    if (!is_dir($dir) || (!$dp = opendir($dir))) return FALSE;
    
    // set order by options
    if(!isset($filters['order_by'])){
        $sortCol = DIR_SORT_NAME;
        $sortDir = SORT_ASC;
    }
    else{
        $order_by = explode(';', $filters['order_by']);
        $sortCol  = (int)$order_by[0];
        $sortDir  = (int)$order_by[1];
    }
    
    if(isset($filters['search_v'])){
        $files = glob($dir.'/*'.$filters['search_v'].'*');
        
        foreach($files as $i => $file) {

            if (!in_array($file, array('.', '..', 'index.html'))) {

                $row = array('name'  => basename($file),
                             'size'  => filesize($file), 
                             'atime' => fileatime($file),
                             'mtime' => filemtime($file),
                             'ctime' => filectime($file));
                
                $name[$i]  = $row['name'];
                $size[$i]  = $row['size'];
                $aTime[$i] = $row['atime'];
                $mTime[$i] = $row['mtime'];
                $cTime[$i] = $row['ctime'];
                $table[$i] = $row;

            }

        }
        
    }
    else{
    
        // Fetch a list of files in the directory and get stats
        for ($i = 0; ($file = readdir($dp)) !== FALSE; $i++) {

            if (!in_array($file, array('.', '..', 'index.html'))) {

                $path = $dir."/".$file;
                $row = array('name'  => $file,
                             'size'  => filesize($path),
                             'atime' => fileatime($path),
                             'mtime' => filemtime($path),
                             'ctime' => filectime($path));
                
                $name[$i]  = $row['name'];
                $size[$i]  = $row['size'];
                $aTime[$i] = $row['atime'];
                $mTime[$i] = $row['mtime'];
                $cTime[$i] = $row['ctime'];
                $table[$i] = $row;
                
            }

        }
    
    }
    // Sort the results
    switch ($sortCol) {
        case DIR_SORT_NAME:
            array_multisort($name, $sortDir, $table);
            break;
        case DIR_SORT_SIZE:
            array_multisort($size, $sortDir, $name, SORT_ASC, $table);
            break;
        case DIR_SORT_ATIME:
            array_multisort($aTime, $sortDir, $name, SORT_ASC, $table);
            break;
        case DIR_SORT_MTIME:
            array_multisort($mTime, $sortDir, $name, SORT_ASC, $table);
            break;
        case DIR_SORT_CTIME:
            array_multisort($cTime, $sortDir, $name, SORT_ASC, $table);
            break;
    }
  
    // Return the result
    //return $table;
    return $name;

}

/* End of file directory_helper.php */
/* Location: ./system/helpers/directory_helper.php */