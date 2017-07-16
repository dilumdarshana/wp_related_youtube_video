<?php
class API {

    protected $apiKey = 'AIzaSyCkr3QQhnxCYa-Gtvsyj3KqGuzDDE_-c7Y';


    /**
    * @var int $timeout = PHP/Server settings: Max Execution Time - 5 seconds
    */
    protected $timeout;

    protected $rootPath;

    protected $cachePath;

    protected $options;

    /**
    * The Constructor
    */
    public function __construct() {

        $max_execution_time = (int) ini_get('max_execution_time');

        $this->timeout      = ( $max_execution_time > 0 ) ? ( $max_execution_time - 5 ) : 15;

        $this->rootPath     = realpath( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' ) . DIRECTORY_SEPARATOR;

        $this->cachePath    = $this->rootPath . 'cache' . DIRECTORY_SEPARATOR;

        if( !is_dir( $this->cachePath ) ) {
          @mkdir( $this->cachePath, 0775);
        }

        $this->options      = get_option( 'relatedyoutubevideos' );

        //$this->apiKey       = ( isset( $this->options['devKey'] ) ) ? $this->options['devKey'] : '';


    }

    public function searchVideoFromYoutube($params)
    {
        //$api = 'https://www.googleapis.com/youtube/v3/search?part=snippet&q=brown%20girl%20-%20aaradhna&key=AIzaSyBStJ7WLTmeqCyXb_QqXcxmohLO-YeLNrU';
        $videoId = '';
        $videoTitle = '';
        $client = new Google_Client();
        $client->setDeveloperKey($this->apiKey);
        $searchTerm = trim($params['search_term']);

        // Define an object that will be used to make all API requests.
        $youtube = new Google_Service_YouTube($client);

        $searchResponse = $youtube->search->listSearch('id, snippet', array(
            'q' => $searchTerm,
            'maxResults' => 1,
        ));

        foreach ($searchResponse['items'] as $searchResult) {
          switch ($searchResult['id']['kind']) {
            case 'youtube#video':
              $videoId = $searchResult['id']['videoId'];
              $videoTitle = $searchResult['snippet']['title'];
              break;
          }
        }

       return $videoId;
    }

    public function displayVideo($videoInfo)
    {
        $width = $videoInfo['width'];
        $height = $videoInfo['height'];
        $videoID = $videoInfo['video_id'];
        $autoplay = 0;
        $videoTitle = $videoInfo['video_title'];
        $viewRelated = 0;
        $html = '';

        $html           .= '    <object data="http://www.youtube.com/embed/' . $videoID  . '?rel=' . $viewRelated . ( $autoplay === 1 ? '&autoplay=1' : '' ). '" width="' . $width . '" height="' . $height . '">' . "\n";
        $html           .= '     <param name="movie" value="http://www.youtube.com/v/' . $videoID . '?rel=' . $viewRelated . '" />' . "\n";
        $html           .= '     <param name="wmode" value="transparent" />' . "\n";
        $html           .= '     <param name="allowfullscreen" value="true" />' . "\n";
        $html           .= '     <a href="http://www.youtube.com/watch?v=' . $videoID . '"><img src="http://img.youtube.com/vi/' . $videoID . '/0.jpg" alt="' . $videoTitle . '" /><br />YouTube Video</a>' . "\n";
        $html           .= "    </object>\n";

        return $html;
    }
}
  ?>
