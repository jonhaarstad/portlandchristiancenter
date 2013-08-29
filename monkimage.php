<?php

  /**
   *
   * This file is called when a cached image needs to be displayed.
   * It looks in the IMAGE_CACHE_DIRECTORY and see if the image
   * already exist, if not a call to the API servers is performed to
   * download the image. The image is saved in the IMAGE_CACHE_DIRECTORY
   * where it will reside for a number of days.
   *
   * WARNING:
   * DO NOT CHANGE the constant values if you don't know what you are doing.
   * Changing it could break your cached images.
   *
   * arguments passed via $_GET include:<br>
   * $fileName   : name of the image that we will be displaying<br>
   * $fileType   : the file extension, this is use to set the correct header<br>
   * $arguments  : encoded query with arguments such as mediaId, ImageCacheId...<br>
   *
   * @author Juan Manuel Torres
   * @version 1.0.10
   *
   */
  require 'monkcms.php';

  /**
   *
   * Directory name where the image cache will be stored. Case sensitive. DO NOT CHANGE
   * @var string IMAGE_CACHE_DIRECTORY
   */
  define('IMAGE_CACHE_DIRECTORY', 'monkimage', true);

  /**
   *
   * Constant to define the width parameter when breaking down image
   * arguments from image name
   *
   * @var const int
   */
  define('WIDTH', 0, true);

  /**
   *
   * Constant to define the height parameter when breaking down image
   * arguments from image name
   *
   * @var const int
   */
  define('HEIGHT', 1, true);

  /**
   *
   * Constant to define the max_width parameter when breaking down image
   * arguments from image name
   *
   * @var const int
   */
  define('MAX_WIDTH', 2, true);

  /**
   *
   * Constant to define the max_height parameter when breaking down image
   * arguments from image name
   *
   * @var const int
   */
  define('MAX_HEIGHT', 3, true);

  /**
   *
   * A clean up is performed once a month
   *
   * IMAGE_CACHE_LIFE_TIME will be the maximum number of days
   * an image is allowed to be cached since it was last used.
   * Modify the last number which is the number of days (not 3600 nor 24).
   * Default value is 60 days.
   *
   * @var int IMAGE_CACHE_LIFE_TIME
   */
  define('IMAGE_CACHE_LIFE_TIME', 3600 * 24 * 60); // 1 hour in seconds * 24 hours * 60 days

  /**
   *
   * TIME_OUT in seconds for cURL or get_file_contents
   *
   * @var int TIME_OUT
   */
  define('TIME_OUT', 15); // Time out in seconds

  /**
   *
   * Enable Debugging.
   * Default value is false. Set to true only if image cache
   * doesn't work to diagnose the problem. If turned on the
   * back up mechanism to show original image in case of failure
   * doesn't work. Set to FALSE by default.
   *
   * If no errors occurred nothing will be printed
   *
   * @var DEBUG boolean
   */
  define('DEBUG', false);

  /**
   *
   * Prints error output to the error_log only if there
   * are any errors. DEBUG must be true to output error information.
   * If true debug output to browser is disabled.
   * If false Debug output will be printed to the browser.
   *
   * If no errors occurred nothing will be printed
   *
   * @var ERROR_LOG boolean
   */
  define('ERROR_LOG', false);

  /**
   *
   * A clean up is performed once a month
   * IMAGE_CACHE_CLEANUP_DAY is the day of the month clean up
   * will be performed
   *
   * @var int IMAGE_CACHE_CLEANUP_DAY
   *
   */
  define('IMAGE_CACHE_CLEANUP_DAY', 15);

  /**
   *
   * Lets us know what the server's php version is.
   * Certain functions require different versions.
   *
   * @var int PHP_VERSION_ID
   *
   */
  $version = explode('.', PHP_VERSION);
  define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));

  /*
   * Support both pretty Urls and calls to monkimage.php
   * with parameters.
   */
  $arguments = getArgumentsFromRedirectUrl();

  /**
   *
   * Directory path name. Case sensitive. DO NOT CHANGE
   *
   * @var string IMAGE_CACHE_PATH
   */
  define('IMAGE_CACHE_PATH', $arguments['mediaDirectory'] , true);

  $fileName       = $arguments['fileName'];
  $fileType       = $arguments['fileType'];
  $imageLocation  = $arguments['imageLocation'];
  $imageArguments = $arguments['arguments'];

  /**
   *
   * Full path to the image cache directory, this is the destination
   * of the images generated. If the IMAGE_CACHE_PATH doesn't have
   * 0777 permission, the IMAGE_CACHE_DIRECTORY may not be created
   *
   * @var string IMAGE_CACHE_DESTINATION
   */
  define(IMAGE_CACHE_DESTINATION, $_SERVER['DOCUMENT_ROOT'] . '/' . IMAGE_CACHE_DIRECTORY, true);

  $cacheClear     = $_GET['cacheclear'];
  $cacheCheck     = $_GET['cachecheck'];
  $filePath       = IMAGE_CACHE_DESTINATION . "/$fileName";

  cleanUpOldImages();

  if ($cacheClear) {
    cacheClear($cacheClear);
  }
  elseif ($cacheCheck) {
    cacheCheck();
  }
  else if ($fileName && $fileType && $filePath) {

    /*
     * Does the file with fileName exist in the IMAGE_CACHE_DIRECTORY directory?
     * if not, download it from the monk server.
     */
    if (!file_exists($filePath)) {
      $tryDownloadAndCache = downloadAndCacheImage($fileName, $imageArguments, $MONKHOME, $MONKACCESS, $MONKQUERY);
      if ($tryDownloadAndCache !== true) {
      /*
       * An Error is thrown in the function when we can't create
       * an IMAGE_CACHE_DIRECTORY. We may want something else
       *  beyond displaying an error message.
       */
        $error = "ERROR DOWNLOADING IMAGE FROM BACKEND: $tryDownloadAndCache";
      }
    }

    if (!$error) {
      /*
       * The image was created correctly (Or already exists) and
       * is now in the IMAGE_CACHE_DIRECTORY.
       * 1) Get the file contents,
       * 2) set the header for the correct file type,
       * 3) echo the image.
       */
      $imageFile = file_get_contents($filePath);

      if ($imageFile) {
        header("Content-Type: " . extensionToMimeType($fileType));
        echo $imageFile;
      }
      else {
        $error = "IMAGE WAS NOT CREATED CORRECTLY.{linebreak}".
        				 "Make sure all the paths are correct";
      }
    }

    /*
     * If we had errors while trying to get the image
     * attempt to show the image using the original
     * location
     */
    if ($error && !DEBUG) {
      if (file_exists($imageLocation)) {
        $imageFile = file_get_contents($imageLocation);

        if ($imageFile) {
          header("Content-Type: " . extensionToMimeType($fileType));
          echo $imageFile;
        }
        else {
          $error .= "{linebreak}IMAGE DOESN'T EXIST IN THE LOCAL MEDIA DIRECTORY";
        }
      }
      else {
        header("HTTP/1.0 404 Not Found");
      }
    }
  }
  else {

    // we don't have a way to get the image
    $error = "BAD PARAMETERS";
    header("HTTP/1.0 404 Not Found");
  }

  /*
   * Turn on debug mode if something goes wrong.
   */
  if (DEBUG && $error) {
    $error = "|---------- DEBUG START ----------|" .
             "{linebreak}$error{linebreak}" .
             "|---------- DEBUG   END ----------|";

    if(ERROR_LOG) {
      $error = str_replace('{linebreak}', '\n\n', $error);
      error_log( 'DEBUG :' . $error);
    }
    else {
      $error = str_replace('{linebreak}', '<br><br>', $error);
      echo $error;
    }
  }

  /**
   *
   * Downloads image from monk media server IMAGE_CACHE_DIRECTORY
   * with the given fileName. it places the image in the
   * IMAGE_CACHE_DESTINATION directory. Returns the contents of the
   * image file.
   *
   * @param $fileName
   * @return $fileContent
   */
  function downloadAndCacheImage($fileName, $arguments, $MONKHOME, $MONKACCESS, $MONKQUERY) {

    $arguments       = urlencode($arguments);
    $server          = $MONKHOME;
    $imageCachingUrl = $server .'/imageCache.php?fileName=' . $fileName . '&arguments=' . $arguments . '&action=create&' . $MONKQUERY;
    $fileDestination = IMAGE_CACHE_DESTINATION . '/' . $fileName;

    if (DEBUG) {
      $debugTestUrl = "Click <a href='$imageCachingUrl'>here to test image generation in the backend </a>. If an image shows up, something may not be set up correctly in the client site.";
    }


    // Create the directory if it doesn't exist
    if (!is_dir(IMAGE_CACHE_DESTINATION)) {

      /*
       * The following forces the directory to be created
       * with 0777 permissions as it some times is created
       * with 0755. http://php.net/manual/en/function.mkdir.php
       */
      $directoryCreated = mkdir(IMAGE_CACHE_DESTINATION);

      if (!$directoryCreated) {
        return "Error message: Unable to create new directory " . IMAGE_CACHE_DESTINATION;
      }

      $modeChanged = chmod(IMAGE_CACHE_DESTINATION, 0777);

      if (!$directoryCreated) {
        if(is_dir(IMAGE_CACHE_DESTINATION)) {
          unlink(IMAGE_CACHE_DESTINATION);
        }

        return "Error message: Unable to set the right permissions to directory " . IMAGE_CACHE_DESTINATION;
      }

    }

    // Set time in secods for PHP
    set_time_limit(TIME_OUT);

    if ($MONKACCESS=='readfile') {
      if (!$handle = fopen($fileDestination, 'a')) {
         return "Error message: Cannot open file ($fileDestination){linebreak}ACCESS = readfile{linebreak}$debugTestUrl";
      }
      // Write $somecontent to our opened file.
      // The timeout setting was introduced in php 5.1.2
      if (PHP_VERSION_ID >= 50201) {
        $context = stream_context_create(array(
          'http' => array(
            'timeout' => TIME_OUT      // Timeout in seconds
          )
        ));
        $numberOfBytesWriten = fwrite($handle, file_get_contents($imageCachingUrl, false, $context));
      }
      else {
        $numberOfBytesWriten = fwrite($handle, file_get_contents($imageCachingUrl, false));
      }

      fclose($handle);
    }
    elseif ($MONKACCESS=='cURL') {

      // Starting output buffering
      $startIsTrue = ob_start();

      // create a new CURL resource
      $ch = curl_init();

      // set URL and other appropriate options
      curl_setopt($ch, CURLOPT_URL, $imageCachingUrl);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      // set timeout for cURL
      curl_setopt($ch, CURLOPT_TIMEOUT, TIME_OUT);

      // open a stream for writing
      $outFile = fopen($fileDestination, 'wb');

      if ($outFile == false){
        return "Error message: Cannot open file ($fileDestination){linebreak}ACCESS = cURL{linebreak}$debugTestUrl";
      }

      curl_setopt($ch, CURLOPT_FILE, $outFile);

      // grab file from URL
      $execIsTrue = curl_exec($ch);
      fclose($outFile);

      // close CURL resource, and free up system resources
      curl_close($ch);

      $endIsTrue = ob_end_clean();
      $numberOfBytesWriten = filesize($fileDestination);
    }

    if ($numberOfBytesWriten === false) {
      if(file_exists($fileDestination)) {
        unlink($fileDestination);
      }
      return "Error message: Unable to write to destination file{linebreak}$debugTestUrl ";
    }
    else if ($numberOfBytesWriten === 0) {
      if(file_exists($fileDestination)){
        unlink($fileDestination);
      }

      return "Error message: Destination file contains no data{linebreak}$debugTestUrl ";
    }
    else if (exif_imagetype($fileDestination) === false) {
      if(file_exists($fileDestination)) {
        unlink($fileDestination);
      }

      return "Error message: Destination file is corrupt, try increasing the timeout {linebreak}$debugTestUrl ";
    }
    else {
      return true;
    }
  }

  /**
   *
   * Removes images older than @link IMAGE_CACHE_LIFE_TIME days
   * on the @see IMAGE_CACHE_CLEANUP_DAY day of the month
   */
  function cleanUpOldImages() {

    /*
     * The following file is created once the clean up is performed.
     * We then checked that this file exist in order to avoid
     * checking multiple times for old images.
     *
     * If is not the cleanup day, check for the file and delete it if
     * it exists. In this way we can ensure that the next cleanup will
     * happen
     */
    $cleanUpIsDoneFile = IMAGE_CACHE_DESTINATION . '/cleanUpIsDoneForToday.txt';

    // Delete images that are older than IMAGE_CACHE_LIFE_TIME
    $todaysDateNumber = date("d");

    if ($todaysDateNumber == IMAGE_CACHE_CLEANUP_DAY) {
      if (!file_exists($cleanUpIsDoneFile)) {

        // Open the directory containing the image cache
        if ($handle = opendir(IMAGE_CACHE_DESTINATION . '/')) {

          // loop through all the files in the image cache directory
          while (false !== ($file = readdir($handle))) {

            // evaluate files only, no directories or hidden files.
            if ( $file != "." && $file != ".." && $file[0] != "." ) {

              $currentFileLocation = IMAGE_CACHE_DESTINATION . '/' .$file;
              $currentFileLifeTime = mktime() - fileatime($currentFileLocation);

              if (IMAGE_CACHE_LIFE_TIME <= $currentFileLifeTime) {
                // Delete image
                if(file_exists($currentFileLocation)) {
                  unlink($currentFileLocation);
                }
              }
            }
          }
        }

        closedir($handle);

        $cleanUpIsDoneHandle = fopen($cleanUpIsDoneFile, 'w');
        fclose($cleanUpIsDoneHandle);
      }
    }
    else {
      if(file_exists($cleanUpIsDoneFile)) {
        unlink($cleanUpIsDoneFile);
      }
    }
  }

  /**
   *
   * This function is used to delete images that have been
   * cached. This will be used mainly by the Backend.
   * The cacheClear string is either:
   * String *: deletes all images in the cache directory
   * String filename: deletes that single file
   * String coma separated filenames: deletes all the files
   *   in the string
   * String part of file name + the wildcard '*':
   *   will search all for all possible matches and delete them
   *
   * @param string $cacheClear
   */
  function cacheClear ($cacheClear) {

    /*
     * get all the coma separated values
     */
    $imageNames = explode(',', $cacheClear) ;
    $imagesRemoved = 0;
    foreach ($imageNames as $imageName) {


      $findWildCard = strpos($imageName, '*');

      if ($findWildCard === false && $imageName) {
        /*
         * No wildcard is found, just delete one image
         */
        if(file_exists(IMAGE_CACHE_DESTINATION . '/' . $imageName)) {
          unlink(IMAGE_CACHE_DESTINATION . '/' . $imageName);
        }
      }
      else if ($findWildCard !== false && $imageName) {
        /*
         * Search for all files that match the wildcard
         * and delete every match
         */
        $matches = glob(IMAGE_CACHE_DESTINATION . '/' . $imageName);

        foreach ($matches as $file) {
          if ( $file != "." && $file != ".." && $file[0] != "." ) {
            if(file_exists($file)){
              unlink($file);
            }

            $imagesRemoved++;
          }
        }
      }
    }

    return $imagesRemoved;
  }

  /**
   *
   * Checks if the cache folder has been created and is accessible.
   * Echos true if the folder exists and false otherwise.
   */
  function cacheCheck () {
    if (!file_exists(IMAGE_CACHE_DESTINATION)) {
      if (!mkdir(IMAGE_CACHE_DESTINATION)) {
        $result = "False";
      }
    }
    // Try open cache for writing but suppress any error output
    $fp=@fopen(IMAGE_CACHE_DESTINATION . '/checkcache',"w");
    if ($fp) {
      fwrite($fp,'Cache OK |'.date('m-d-Y'));
      fclose($fp);
      $result = "True";
    }
    else {
      $result = "False";
    }
    echo $result;
    delFolder(IMAGE_CACHE_DESTINATION . '/checkcache');
  }

  /**
   *
   * Used to get essential arguments to construct image from
   * pretty URL's
   */
  function getArgumentsFromRedirectUrl() {
    $mediaDirectory = $_GET['mediaDirectory'];
    $mediaId  = $_GET['mediaId'];
    $fileName = rtrim($_GET['fileName'], '/');
    $fileInfo = pathinfo($fileName);
    $fileType = $fileInfo['extension'];

    $argument = 0;
    $arguments = array();
    $arguments['mediaId'] = $mediaId;

    // Manually strip off the file extension.
    // We cannot use $fileInfo['filename'] as it is only available in php 5.2
    $fileNameParts = explode('-', substr($fileInfo['basename'], 0, strrpos($fileInfo['basename'], '.')));


    /*
     * Get all arguments from image name
     */
    for ($i = count($fileNameParts)-4; $i < count($fileNameParts); $i++, $argument++) {
      switch ($argument) {
        case WIDTH:
          $arguments['width'] = $fileNameParts[$i];
        break;
        case HEIGHT:
          $arguments['height'] = $fileNameParts[$i];
        break;
        case MAX_WIDTH:
          $arguments['max_width'] = $fileNameParts[$i];
        break;
        case MAX_HEIGHT:
          $arguments['max_height'] = $fileNameParts[$i];
        break;
      }
    }

    /*
     * get the file name of the original image from the fileName
     */
    $argumentString = '-' .
                      $arguments['width'] . '-' .
                      $arguments['height'] . '-' .
                      $arguments['max_width'] . '-' .
                      $arguments['max_height'];

    $baseName       = str_replace($argumentString, '', $fileName);

    foreach ($arguments as $arg => $value) {
      $arguments['arguments'] .= $arg . '=' . $value . '&';
    }
    $arguments['fileName']      = $fileName;
    $arguments['fileType']      = $fileType;
    $arguments['mediaDirectory']= $mediaDirectory;
    $arguments['imageLocation'] = $mediaDirectory . '/' . $baseName;

    return $arguments;

  }

  /**
   * Converts the specified image file extension to its MIME type.
   *
   * @param  string $extension image file extension without leading period
   * @return string image MIME type for specified file extension
   */
  function extensionToMimeType($extension) {
    $extention = strtolower($extension);

    switch ($extension) {
      case 'jpg':
        $mimeType = "image/jpeg";

        break;

      default:
        $mimeType = "image/" . $extension;

        break;
    }

    return $mimeType;
  }

?>