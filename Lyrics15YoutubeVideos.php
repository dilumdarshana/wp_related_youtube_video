<?php

class Lyrics15YoutubeVideos {

    protected $path;

    function __construct($pluginPath)
    {
        $this->path = $pluginPath;

        add_shortcode('lyrics15YoutubeVideos', array( $this, 'handleShortcode'));

        // admin menu
        add_action( 'admin_menu', array($this, 'lyrics15_youtube_menu' ));
    }

    function handleShortcode( $atts )
    {
        $this->loadClass( 'API' );

        $API = new API();

        $params['search_term'] = $atts['search'];

        $res = $API->searchVideoFromYoutube($params);

        $videoInfo['video_id'] = $res;
        $videoInfo['width'] = $atts['width'];
        $videoInfo['height'] = $atts['height'];
        $videoInfo['video_title'] = '';

        echo $API->displayVideo($videoInfo);

    }

    function lyrics15_youtube_menu()
    {
        add_options_page( 'Lyrics15 Youtube Options', 'Lyrics15 Youtube Video', 'manage_options', 'lyrics15-youtube-videos', array($this, 'lyrics15_youtube_options' ));
    }

    function lyrics15_youtube_options()
    {
        if ( !current_user_can( 'manage_options' ) )
        {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        echo '<div>
        <h2>Lyrics15 Related Youtube Video Options</h2>

        <form method="post" action="options.php">
            '.wp_nonce_field('update-options').'

            <table width="510">
                <tr valign="top">
                    <th width="92" scope="row">API Key</th>
                    <td width="406">
                        <input name="lyris15_youtube_video_api_key" type="text" id="lyris15_youtube_video_api_key" value="'.get_option('lyris15_youtube_video_api_key').'" />
                    </td>
                </tr>
            </table>

            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="lyris15_youtube_video_api_key" />

            <p>
                <input type="submit" value="Save Changes" />
            </p>
        </form>
        </div>';

    }

    public function loadClass( $classname )
    {
        if( $this->classExists( $classname ) )
        {

          return true;

        }
        else {

          $path = $this->path;

          $file = $path . str_replace( '_', DIRECTORY_SEPARATOR, $classname ) . '.php';

          if( file_exists( $file ) )
          {
            include_once $file;

          } else
          {
            return false;
          }

        }

        /**
         * Simply including the classfile doesn't make sense to me since the goal is to load a class not a file.
         * Always make sure the file does contain the class declaration!
        */
        return $this->classExists( $classname );
    }

  /**
   * Check wether a class(name) has been declared.
   *
   * @param string $classname.
   * @return boolean.
   */
  public function classExists( $classname )
  {

    return class_exists( $classname );

  }
}
?>
