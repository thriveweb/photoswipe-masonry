<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('Illegal Entry');
}

define('GALLERY_FOLDER', "fgallery");

//============================== PhotoSwipe options ========================//
class FileGallery {

    public function get_images_from_folder($folderName, $thumbWidth) {
        if (!function_exists('list_files')) { 
            require_once ABSPATH . '/wp-admin/includes/file.php'; 
        }
        
        $folderPath = 'wp-content/uploads/' . GALLERY_FOLDER . '/' . $folderName; 
        $path = ABSPATH . $folderPath;
        $files = list_files($path, 1);
        $result = array();
        if (is_null($files)) {
            return $result; 
        }

        $jsonPath = $path . '/gallery.json';
        $jsonText = file_get_contents($jsonPath);
        if ($jsonText === false) {
            $jsonText = '{';
        }
        $jsonData = json_decode($jsonText);

        foreach ($files as $file) {
            $fileInfo = pathinfo($file); 
            $fileName = $fileInfo['basename'];
            $fileExt = $fileInfo['extension'];
            if ($fileName == 'thumbs' || $fileExt == 'json') {
                continue;
            }
            $size = getimagesize($file);
            $imageSrc = get_site_url(null, $folderPath . '/' .  $fileName); 
            $imageThumbSrc = $this -> get_or_create_thumb($folderPath, $fileName, $thumbWidth);
            $imageData = $this -> create_image_data($fileName, get_current_blog_id(), $jsonData, $size);

            array_push($result, array (
                'id' => $fileName,
                'thumb' => $imageThumbSrc,
                'full' => $imageSrc,
                'data' => $imageData
            ));
        }

        return $result;
    }

    function create_image_data($fileName, $siteId, $jsonData, $size) {
        $imageData = array (
            'x' => $size[0],
            'y' => $size[1],
            'title' => $fileName,
            'alt' => $fileName,
            'caption' => $fileName
        );
        if (is_null($jsonData)) {
            return $imageData;
        }
        $data = $this -> read_json_entry($fileName, $jsonData, $siteId);
        if (!is_null($data)) {
            return $this -> assign_image_data($imageData, $data);
        }
        $data = $this -> read_json_entry('global', $jsonData, $siteId);
        if (!is_null($data)) {
            return $this -> assign_image_data($imageData, $data);
        }
        return $imageData;
    }

    function assign_image_data($imageData, $data) {
        $imageData['caption'] = $data[0];
        $imageData['alt'] = $data[1];
        return $imageData;
    }

    function read_json_entry($entry, $jsonData, $siteId) {
        $data = $jsonData->{$entry};
        if (!is_null($data)) {
            $data = $data->{strval($siteId)};
        }
        return $data;
    }

    function get_or_create_thumb($folderPath, $fileName, $thumbWidth) {
        $thumbRelativePath = $folderPath . '/thumbs/thumbs_' . $fileName;
        $thumbPath = ABSPATH . $thumbRelativePath; 
        $imagePath = ABSPATH . '/' . $folderPath . '/' . $fileName; 
        if (!file_exists($thumbPath)) {
            $image = wp_get_image_editor($imagePath);
            $size = $image->get_size();
            $w = floatval($thumbWidth);
            $ratio = floatval($size['height'])/$size['width']; 
            $h = $w * $ratio;
            $image->resize(round($w), round($h), false);
            $image->save($thumbPath);
        }
        return get_site_url(null, $thumbRelativePath); 
    }

}