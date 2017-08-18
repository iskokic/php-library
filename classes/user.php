<?php
/**
* Works with user related data
*/
class User{
    public static $image_location = 'data/users/';
    public static $image_default  = 'assets/images/user.png';
    
    // -------------------------------------------------------------------------
    
    /**
    * Records user's IP address
    * 
    */
    public static function record_ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
    
    // -------------------------------------------------------------------------
    
    /**
    * Records user's User Agent
    * 
    */
    public static function record_ua()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }
    
    // -------------------------------------------------------------------------
    
    /**
    * Searches for user's image
    * 
    * @param String $image
    * @param String $image_location
    * @param String $image_default
    */
    public static function image($image, $image_location='', $image_default="")
    {
        if(empty($image_location))
        {
            $image_location = self::$image_location;
        }
        
        if(empty($image_default))
        {
            $image_default = self::$image_default;
        }
        
        $image_link = $image_location . $image;
        
        if(!file_exists($image_link) or empty($image))
        {
            if(!file_exists('../'. $image_link) or empty($image))
            {
                $image_link = $image_default;
            }
        }
    return $image_link;    
    }
    
    // -------------------------------------------------------------------------
}
?>