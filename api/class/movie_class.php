<?php

/**
 * Created by PhpStorm.
 * User: espe
 * Date: 09.08.2016
 * Time: 9:07
 */

class movie_class {

    // tags from url with movie
    var $tags;

    // url of movie
    var $url;

    // html_code of movie
    var $html_code;

    public function __construct($url, $html_code='') {
        $this->url = $url;

        if($url == '') {

            $arr_url_1 = explode("src=", $html_code);
            $arr_url = explode(' ', $arr_url_1[1]);
            $src = str_replace(array('"', "'"), '', $arr_url[0]);
            $arr_src = explode('/', $src);
            $provider = $arr_src[2];

            switch ($provider) {

                case 'videoapi.my.mail.ru':
                    $this->url = 'my.mail.ru/' . $arr_src[5]. '/' . $arr_src[6] . '/video/' . $arr_src[7] . '/' . $arr_src[8];
                    break;

                case 'www.youtube.com':
                    $arr_url = explode('"', $html_code);
                    $arr_video_id = explode('/', $arr_url[5]);
                    $video_id = $arr_video_id[4];
                    $this->url = 'www.youtube.com/watch?v=' . $video_id;
                    break;
                default:
                    echo 'unsupported video hosting';
                    die();
                    break;
            }
        }
    echo stripos($url, '//');
        if(!stripos($url, 'https://')) {
            $this->url = 'https://' . $this->url;
        }

        $this->tags = get_meta_tags($this->url);
        write_log($this->tags);
    }

    /**
     * get title from meta tags
     * @return string title
     */
    public function get_title() {
        $tags = $this->tags;
        $title = '';
        if($tags['title'] != '') {
            $title = $tags['title'];
        } elseif ($tags['og:title'] != '') {
            $title = $tags['og:title'];
        } elseif ($tags['twitter:title'] != '') {
            $title = $tags['twitter:title'];
        }
        return $title;
    }

    /**
     * get html code for insert into page
     * @return string html_code
     */
    function get_html_code () {
        $html_code = $this->html_code;

        $arr_url = explode('/', $this->url);

        $provider = $arr_url[2];

        // select movies provider
        switch ($provider) {
            case 'www.youtube.com':
            case 'youtu.be':
                if ($this->tags['og:video:url'] != '') {
                    $html_code = $this->tags['og:video:url'];
                } elseif ($this->tags['twitter:player'] != '') {
                    $html_code = $this->tags['twitter:player'];
                } elseif ($this->tags['og:url'] != '') {
                $html_code = $this->tags['og:url'];
                }
                $html_code_pattern = '<iframe width="560" height="315" src="%src%" frameborder="0" allowfullscreen></iframe>';
                $html_code = str_replace('%src%', $html_code, $html_code_pattern);
                break;

            case 'my.mail.ru':
                $code = $arr_url[3] . '/' . $arr_url[4] . '/' . $arr_url[6] . '/' . $arr_url[7];
                $html_code = "<iframe src='https://videoapi.my.mail.ru/videos/embed/" . $code ."' width='626' height='367' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
                break;
        }
        
        return $html_code;
    }

    /**
     * get source of image preview
     * @return string image 6
     */
    public function get_image () {
        $image = '';
        if ($this->tags['og:image'] != '') {
            $image = $this->tags['og:image'];
        } elseif ($this->tags['twitter:title'] != '') {
            $image = $this->tags['twitter:image'];
        }
        return $image;
    }

    /**
     * get description of movie
     * @return string description
     */
    public function get_description () {
        $tags = $this->tags;
        $description = '';
        if($tags['title'] != '') {
            $description = $tags['description'];
        }
        if ($tags['og:description'] != '') {
            $description = $tags['og:description'];
        } elseif ($tags['twitter:title'] != '') {
            $description = $tags['twitter:description'];
        }
        return $description;
    }

}