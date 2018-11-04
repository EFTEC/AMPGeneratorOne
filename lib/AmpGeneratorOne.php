<?php
namespace eftec\AmpGeneratorOne;
/**
 * Class AmpGeneratorOne
 * @copyright Jorge Castro Castillo 
 * @license GPLV3
 * @version 1.3 2018-11-03
 * @link https://github.com/EFTEC/AMPGeneratorOne
 */
class AmpGeneratorOne {

    const VERSION=1.3;

    private $result="";
    private $styleStack=array();
    private $secId=0;
    private $backgroundColor="#ffffff";
    private $classTextColor="text-black";
    private $defaultBackGroundColor="#ffffff";
    private $defaultClassTextColor="text-black";
    private $sidebarColor="#4040ff";
    private $canonical;
    private $base;
    /** @var HeaderModel */
    private $header;
    private $title;
    private $themecolor="#197bc6";
    private $classSidebar="text-secondary";
    private $paddingTop=null;
    private $paddingBottom=null;
    private $bgImage="";

    /**
     * AmpGeneratorOne constructor.
     * @param string $canonical Original url . Example https://www.southprojects.com/somesite
     * @param string $base Original base url without trailing slash. Example https://www.southprojects.com
     * @param string $sidebarColor The background color of the sidebar Example #ffffff,white,rgb(255,255,255)
     * @param string $themecolor .The color of the logo,burger Example #ffffff,white,rgb(255,255,255)
     * @param string $classSidebar. text-primary,text-secondary,text-success,text-info,text-warning,text-danger,text-white,text-black
     */
    public function __construct($canonical,$base,$sidebarColor="#4040ff",  $themecolor="#197bc6",$classSidebar="text-primary")
    {
        $this->canonical = $canonical;
        $this->base = $base;
        $this->sidebarColor = $sidebarColor;
        $this->themecolor = $themecolor;
        $this->classSidebar=$classSidebar;
    }

    /**
     * Fix an url and convert a relative url into an absolute url
     * @param $url
     * @return string
     * @test equals 'http://amp.canon.dom/some/url/image.jpg',this('some/url/image.jpg')
     */
    public function fixRelativeUrl($url) {
        if (strlen($url)<4) return $url;
        if (substr($url,0,4)=='http') return $url;
        if (substr($url,0,1)=='/') return $this->base.$url;
        if (substr($url,0,1)!='/') return $this->base.'/'.$url;
        return "";
    }

    /**
     * It generates the social and seo structure (Twitter card, Facebook OG and Google Schema)
     * @param StructureModel $structured
     * @return string
     * @test greaterThan 100, this(new StructuredModel())
     */
    private function genStructured($structured) {
        
        $mark="<meta itemprop='name' content='{$structured->name}'>
            <meta itemprop='description' content='{$structured->description}'>
            <meta itemprop='image' content='{$structured->image}'>
            <meta property='og:image' content='{$structured->image}'/>
            <meta property='og:image:width' content='{$structured->imageWidth}'/>
            <meta property='og:image:height' content='{$structured->imageHeight}'/>
            <meta property='og:title' content='{$structured->name}'/>
            <meta property='og:description' content='{$structured->description}'/>
            <meta property='og:url' content='{$structured->url}'/>
            <meta property='og:type' content='article'/>
            <meta property='og:site_name' content='{$structured->name}'/>
            <meta name='twitter:card' content='summary'>
            <meta name='twitter:title' content='{$structured->name}'>
            <meta name='twitter:description' content='{$structured->description}'>
            <meta name='twitter:image' content='{$structured->image}'>
            <meta name='twitter:site' content='{$structured->twittersite}'>
            <meta name='twitter:creator' content='{$structured->twittercreator}'>
            
            <script type='application/ld+json'>            
            {
            \"@context\": \"http://schema.org/\",
            \"@type\": \"WebSite\",
            \"name\": \"{$structured->name}\",
            \"url\": \"{$structured->url}\",
            \"description\": \"{$structured->description}\",
            \"image\":{\"@type\":\"ImageObject\",
                \"url\":\"{$structured->image}\",
                \"width\":{$structured->imageWidth},
                \"height\":{$structured->imageHeight}
                 }
             }  
            </script>";
        return $mark;
    }

    /**
     * Start to generate an Amp site.
     * @param HeaderModel $param
     * @param StructureModel $structured
     */
    public function startAmp($param,$structured=null) {
        $param->icon=$this->fixRelativeUrl($param->icon);
        $template= "<!DOCTYPE html>
        <html amp>
        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <meta name='generator' content='AmpGeneratorOne ".self::VERSION.", github.com'>
            <meta name='viewport' content='width=device-width, initial-scale=1, minimum-scale=1'>
            <link rel='shortcut icon' href='{$param->icon}' type='image/x-icon'>
            <meta name='description' content='{$param->description}'>";

        if ($structured!=null) {
            $template.=$this->genStructured($structured);
        }
        $template.="<title>{$param->title}</title>
            <link rel='canonical' href='{$this->canonical}'>
            <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.3.1/css/all.css' integrity='sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU' crossorigin='anonymous'>
            <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style>
            <noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
            <link href='https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&subset=cyrillic' rel='stylesheet'>
        <style amp-custom>
        div,span,h1,h2,h3,h4,h5,h6,p,blockquote,a,ol,ul,li,figcaption{font: inherit;}
        section{background-color: #eeeeee;}
        section,.container,.container-fluid{position: relative;word-wrap: break-word;}
        a.ampg-iconfont:hover{text-decoration: none;}
        .article .lead p,.article .lead ul,.article .lead ol,.article .lead pre,.article .lead blockquote{margin-bottom: 0;}
        a{font-style: normal;font-weight: 400;cursor: pointer;}
        a,a:hover{text-decoration: none;}
        figure{margin-bottom: 0;}
        h1,h2,h3,h4,h5,h6,.h1,.h2,.h3,.h4,.h5,.h6,.text-1,.text-2,.text-3,.text-4{line-height: 1;word-break: break-word;word-wrap: break-word;}
        b,strong{font-weight: bold;}
        blockquote{padding: 10px 0 10px 20px;position: relative;border-left: 2px solid;border-color: #ff3366;}
        input:-webkit-autofill,input:-webkit-autofill:hover,input:-webkit-autofill:focus,input:-webkit-autofill:active{transition-delay: 9999s;transition-property: background-color,color;}
        textarea[type='hidden']{display: none;}
        body{position: relative;}
        section{background-position: 50% 50%;background-repeat: no-repeat;background-size: cover;}
        section .ampg-background-video,section .ampg-background-video-preview{position: absolute;bottom: 0;left: 0;right: 0;top: 0;}
        .row{display: -webkit-box;display: -webkit-flex;display: -ms-flexbox;display: flex;-webkit-flex-wrap: wrap;-ms-flex-wrap: wrap;flex-wrap: wrap;margin-right: -15px;margin-left: -15px;}
        @media (min-width: 576px){.row{margin-right: -15px;margin-left: -15px;}
        }
        @media (min-width: 768px){.row{margin-right: -15px;margin-left: -15px;}
        }
        @media (min-width: 992px){.row{margin-right: -15px;margin-left: -15px;}
        }
        @media (min-width: 1200px){.row{margin-right: -15px;margin-left: -15px;}
        }
        .hidden{visibility: hidden;}
        .ampg-z-index20{z-index: 20;}
        .align-left{text-align: left;}
        .align-center{text-align: center;}
        .align-right{text-align: right;}
        @media (max-width: 767px){.align-left,.align-center,.align-right,.ampg-section-btn,.ampg-section-title{text-align: center;}
        }
        .ampg-light{font-weight: 300;}
        .ampg-regular{font-weight: 400;}
        .ampg-semibold{font-weight: 500;}
        .ampg-bold{font-weight: 700;}
        .ampg-figure img,.ampg-figure iframe{display: block;width: 100%;}
        .card{background-color: transparent;border: none;}
        .card-img{text-align: center;flex-shrink: 0;}
        .media{max-width: 100%;margin: 0 auto;}
        .ampg-figure{-ms-flex-item-align: center;-ms-grid-row-align: center;-webkit-align-self: center;align-self: center;}
        .media-container > div{max-width: 100%;}
        .ampg-figure img,.card-img img{width: 100%;}
        @media (max-width: 991px){.media-size-item{width: auto;}
            .media{width: auto;}
            .ampg-figure{width: 100%;}
        }
        .ampg-section-btn{margin-left: -.25rem;margin-right: -.25rem;font-size: 0;}
        nav .ampg-section-btn{margin-left: 0rem;margin-right: 0rem;}
        .btn .ampg-iconfont,.btn.btn-sm .ampg-iconfont{cursor: pointer;margin-right: 0.5rem;}
        .btn.btn-md .ampg-iconfont,.btn.btn-md .ampg-iconfont{margin-right: 0.8rem;}
        [type='submit']{-webkit-appearance: none;}
        .ampg-fullscreen .ampg-overlay{min-height: 100vh;}
        .ampg-fullscreen{display: flex;display: -webkit-flex;display: -moz-flex;display: -ms-flex;display: -o-flex;align-items: center;-webkit-align-items: center;min-height: 100vh;box-sizing: border-box;padding-top: 3rem;padding-bottom: 3rem;}
        amp-img img{max-height: 100%;max-width: 100%;}
        img.ampg-temp{width: 100%;}
        .super-hide{display: none;}
        .is-builder .nodisplay + img[async],.is-builder .nodisplay + img[decoding='async'],.is-builder amp-img > a + img[async],.is-builder amp-img > a + img[decoding='async']{display: none;}
        html:not(.is-builder) amp-img > a{position: absolute;top: 0;bottom: 0;left: 0;right: 0;z-index: 1;}
        .is-builder .temp-amp-sizer{position: absolute;}
        .is-builder amp-youtube .temp-amp-sizer,.is-builder amp-vimeo .temp-amp-sizer{position: static;}

        *{box-sizing: border-box;}
        body{font-family: Roboto;font-style: normal;line-height: 1.5;}
        .ampg-section-title{font-style: normal;line-height: 1.2;}
        .ampg-section-subtitle{line-height: 1.3;}
        .ampg-text{font-style: normal;line-height: 1.6;}
        .text-1{font-family: 'Roboto',sans-serif;font-size: 4.5rem;}
        .text-2{font-family: 'Roboto',sans-serif;font-size: 2.2rem;}
        .text-4{font-family: 'Roboto',sans-serif;font-size: 0.9rem;}
        .text-5{font-family: 'Roboto',sans-serif;font-size: 1.8rem;}
        .text-7{font-family: 'Roboto',sans-serif;font-size: 1.1rem;}
        @media (max-width: 768px){.text-1{font-size: 3.6rem;font-size: calc( 2.225rem + (4.5 - 2.225) * ((100vw - 20rem) / (48 - 20)));line-height: calc( 1.4 * (2.225rem + (4.5 - 2.225) * ((100vw - 20rem) / (48 - 20))));}
            .text-2{font-size: 1.76rem;font-size: calc( 1.42rem + (2.2 - 1.42) * ((100vw - 20rem) / (48 - 20)));line-height: calc( 1.4 * (1.42rem + (2.2 - 1.42) * ((100vw - 20rem) / (48 - 20))));}
            .text-4{font-size: 0.72rem;font-size: calc( 0.965rem + (0.9 - 0.965) * ((100vw - 20rem) / (48 - 20)));line-height: calc( 1.4 * (0.965rem + (0.9 - 0.965) * ((100vw - 20rem) / (48 - 20))));}
            .text-5{font-size: 1.44rem;font-size: calc( 1.28rem + (1.8 - 1.28) * ((100vw - 20rem) / (48 - 20)));line-height: calc( 1.4 * (1.28rem + (1.8 - 1.28) * ((100vw - 20rem) / (48 - 20))));}
        }
        .btn{font-weight: 400;border-width: 2px;border-style: solid;font-style: normal;letter-spacing: 2px;margin: .4rem .8rem;white-space: normal;transition-property: background-color,color,border-color,box-shadow;transition-duration: .3s,.3s,.3s,2s;transition-timing-function: ease-in-out;padding: 1rem 2rem;border-radius: 0px;display: inline-flex;align-items: center;justify-content: center;word-break: break-word;}
        .btn-sm{border: 1px solid;font-weight: 400;letter-spacing: 2px;-webkit-transition: all 0.3s ease-in-out;-moz-transition: all 0.3s ease-in-out;transition: all 0.3s ease-in-out;padding: 0.6rem 0.8rem;border-radius: 0px;}
        .btn-md{font-weight: 600;letter-spacing: 2px;margin: .4rem .8rem;-webkit-transition: all 0.3s ease-in-out;-moz-transition: all 0.3s ease-in-out;transition: all 0.3s ease-in-out;padding: 1rem 2rem;border-radius: 0px;}
        .bg-primary{background-color: #007bff;}
        .bg-success{background-color: #28a745;}
        .bg-info{background-color: #17a2b8;}
        .bg-warning{background-color: #ffc107;}
        .bg-danger{background-color: #dc3545;}
        .btn-primary,.btn-primary:active,.btn-primary.active{background-color: #007bff;border-color: #007bff;color: #ffffff;}
        .btn-primary:hover,.btn-primary:focus,.btn-primary.focus{color: #ffffff;background-color: #1f7dc5;border-color: #1f7dc5;}
        .btn-primary.disabled,.btn-primary:disabled{color: #ffffff;background-color: #1f7dc5;border-color: #1f7dc5;}
        .btn-secondary,.btn-secondary:active,.btn-secondary.active{background-color: #4addff;border-color: #4addff;color: #ffffff;}
        .btn-secondary:hover,.btn-secondary:focus,.btn-secondary.focus{color: #ffffff;background-color: #00cdfd;border-color: #00cdfd;}
        .btn-secondary.disabled,.btn-secondary:disabled{color: #ffffff;background-color: #00cdfd;border-color: #00cdfd;}
        .btn-info,.btn-info:active,.btn-info.active{background-color: #17a2b8;border-color: #17a2b8;color: #ffffff;}
        .btn-info:hover,.btn-info:focus,.btn-info.focus{color: #ffffff;background-color: #4242db;border-color: #4242db;}
        .btn-info.disabled,.btn-info:disabled{color: #ffffff;background-color: #4242db;border-color: #4242db;}
        .btn-success,.btn-success:active,.btn-success.active{background-color: #28a745;border-color: #28a745;color: #ffffff;}
        .btn-success:hover,.btn-success:focus,.btn-success.focus{color: #ffffff;background-color: #088550;border-color: #088550;}
        .btn-success.disabled,.btn-success:disabled{color: #ffffff;background-color: #088550;border-color: #088550;}
        .btn-warning,.btn-warning:active,.btn-warning.active{background-color: #ffc107;border-color: #ffc107;color: #ffffff;}
        .btn-warning:hover,.btn-warning:focus,.btn-warning.focus{color: #ffffff;background-color: #505050;border-color: #505050;}
        .btn-warning.disabled,.btn-warning:disabled{color: #ffffff;background-color: #505050;border-color: #505050;}
        .btn-danger,.btn-danger:active,.btn-danger.active{background-color: #dc3545;border-color: #dc3545;color: #ffffff;}
        .btn-danger:hover,.btn-danger:focus,.btn-danger.focus{color: #ffffff;background-color: #7a7a7a;border-color: #7a7a7a;}
        .btn-danger.disabled,.btn-danger:disabled{color: #ffffff;background-color: #7a7a7a;border-color: #7a7a7a;}
        .btn-black,.btn-black:active,.btn-black.active{background-color: #333333;border-color: #333333;color: #ffffff;}
        .btn-black:hover,.btn-black:focus,.btn-black.focus{color: #ffffff;background-color: #0d0d0d;border-color: #0d0d0d;}
        .btn-black.disabled,.btn-black:disabled{color: #ffffff;background-color: #0d0d0d;border-color: #0d0d0d;}
        .btn-white,.btn-white:active,.btn-white.active{background-color: #ffffff;border-color: #ffffff;color: #ffffff;}
        .btn-white:hover,.btn-white:focus,.btn-white.focus{color: #ffffff;background-color: #d4d4d4;border-color: #d4d4d4;}
        .btn-white.disabled,.btn-white:disabled{color: #ffffff;background-color: #d4d4d4;border-color: #d4d4d4;}
        .btn-white,.btn-white:active,.btn-white.active{color: #333333;}
        .btn-white:hover,.btn-white:focus,.btn-white.focus{color: #333333;}
        .btn-white.disabled,.btn-white:disabled{color: #333333;}
        .btn-primary-outline,.btn-primary-outline:active,.btn-primary-outline.active{background: none;border-color: #1c6faf;color: #1c6faf;}
        .btn-primary-outline:hover,.btn-primary-outline:focus,.btn-primary-outline.focus{color: #ffffff;background-color: #007bff;border-color: #007bff;}
        .btn-primary-outline.disabled,.btn-primary-outline:disabled{color: #ffffff;background-color: #007bff;border-color: #007bff;}
        .btn-secondary-outline,.btn-secondary-outline:active,.btn-secondary-outline.active{background: none;border-color: #00b8e3;color: #00b8e3;}
        .btn-secondary-outline:hover,.btn-secondary-outline:focus,.btn-secondary-outline.focus{color: #ffffff;background-color: #4addff;border-color: #4addff;}
        .btn-secondary-outline.disabled,.btn-secondary-outline:disabled{color: #ffffff;background-color: #4addff;border-color: #4addff;}
        .btn-info-outline,.btn-info-outline:active,.btn-info-outline.active{background: none;border-color: #2c2cd7;color: #2c2cd7;}
        .btn-info-outline:hover,.btn-info-outline:focus,.btn-info-outline.focus{color: #ffffff;background-color: #17a2b8;border-color: #17a2b8;}
        .btn-info-outline.disabled,.btn-info-outline:disabled{color: #ffffff;background-color: #17a2b8;border-color: #17a2b8;}
        .btn-success-outline,.btn-success-outline:active,.btn-success-outline.active{background: none;border-color: #076d41;color: #076d41;}
        .btn-success-outline:hover,.btn-success-outline:focus,.btn-success-outline.focus{color: #ffffff;background-color: #28a745;border-color: #28a745;}
        .btn-success-outline.disabled,.btn-success-outline:disabled{color: #ffffff;background-color: #28a745;border-color: #28a745;}
        .btn-warning-outline,.btn-warning-outline:active,.btn-warning-outline.active{background: none;border-color: #434343;color: #434343;}
        .btn-warning-outline:hover,.btn-warning-outline:focus,.btn-warning-outline.focus{color: #ffffff;background-color: #ffc107;border-color: #ffc107;}
        .btn-warning-outline.disabled,.btn-warning-outline:disabled{color: #ffffff;background-color: #ffc107;border-color: #ffc107;}
        .btn-danger-outline,.btn-danger-outline:active,.btn-danger-outline.active{background: none;border-color: #6d6d6d;color: #6d6d6d;}
        .btn-danger-outline:hover,.btn-danger-outline:focus,.btn-danger-outline.focus{color: #ffffff;background-color: #dc3545;border-color: #dc3545;}
        .btn-danger-outline.disabled,.btn-danger-outline:disabled{color: #ffffff;background-color: #dc3545;border-color: #dc3545;}
        .btn-black-outline,.btn-black-outline:active,.btn-black-outline.active{background: none;border-color: #000000;color: #000000;}
        .btn-black-outline:hover,.btn-black-outline:focus,.btn-black-outline.focus{color: #ffffff;background-color: #333333;border-color: #333333;}
        .btn-black-outline.disabled,.btn-black-outline:disabled{color: #ffffff;background-color: #333333;border-color: #333333;}
        .btn-white-outline,.btn-white-outline:active,.btn-white-outline.active{background: none;border-color: #ffffff;color: #ffffff;}
        .btn-white-outline:hover,.btn-white-outline:focus,.btn-white-outline.focus{color: #333333;background-color: #ffffff;border-color: #ffffff;}
        .text-primary{color: #007bff;}
        .text-secondary{color: #4addff;}
        .text-success{color: #28a745;}
        .text-info{color: #17a2b8;}
        .text-warning{color: #ffc107;}
        .text-danger{color: #dc3545;}
        .text-white{color: #ffffff;}
        .text-black{color: #000000;}
        a.text-primary:hover,a.text-primary:focus{color: #1c6faf;}
        a.text-secondary:hover,a.text-secondary:focus{color: #00b8e3;}
        a.text-success:hover,a.text-success:focus{color: #076d41;}
        a.text-info:hover,a.text-info:focus{color: #2c2cd7;}
        a.text-warning:hover,a.text-warning:focus{color: #434343;}
        a.text-danger:hover,a.text-danger:focus{color: #6d6d6d;}
        a.text-white:hover,a.text-white:focus{color: #b3b3b3;}
        a.text-black:hover,a.text-black:focus{color: #4d4d4d;}
        .alert-success{background-color: #28a745;}
        .alert-info{background-color: #17a2b8;}
        .alert-warning{background-color: #ffc107;}
        .alert-danger{background-color: #dc3545;}
        .btn-form{border-radius: 0;}
        .btn-form:hover{cursor: pointer;}
        a,a:hover{color: #007bff;}
        .ampg-plan-header.bg-primary .ampg-plan-subtitle,.ampg-plan-header.bg-primary .ampg-plan-price-desc{color: #feffff;}
        .ampg-plan-header.bg-success .ampg-plan-subtitle,.ampg-plan-header.bg-success .ampg-plan-price-desc{color: #acfad9;}
        .ampg-plan-header.bg-info .ampg-plan-subtitle,.ampg-plan-header.bg-info .ampg-plan-price-desc{color: #ffffff;}
        .ampg-plan-header.bg-warning .ampg-plan-subtitle,.ampg-plan-header.bg-warning .ampg-plan-price-desc{color: #b6b6b6;}
        .ampg-plan-header.bg-danger .ampg-plan-subtitle,.ampg-plan-header.bg-danger .ampg-plan-price-desc{color: #e0e0e0;}
        blockquote{padding: 10px 0 10px 20px;position: relative;border-color: #007bff;border-width: 3px;}
        ul,ol,pre,blockquote{margin-bottom: 0;margin-top: 0;}
        pre{background: #f4f4f4;padding: 10px 24px;white-space: pre-wrap;}
        .inactive{-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;pointer-events: none;-webkit-user-drag: none;user-drag: none;}
        html,body{height: auto;min-height: 100vh;}
        .popover-content ul.show{min-height: 155px;}
        #scrollToTop{display: none;}
        .ampg-container{max-width: 800px;padding: 0 10px;margin: 0 auto;position: relative;}
        h1,h2,h3{margin: auto;}
        h1,h3,p{padding: 10px 0;margin-bottom: 15px;}
        p,li,blockquote{color: #15181b;letter-spacing: 0.5px;line-height: 1.7;}
        .container{padding-right: 15px;padding-left: 15px;width: 100%;margin-right: auto;margin-left: auto;}
        @media (max-width: 767px){.container{max-width: 540px;}
        }
        @media (min-width: 768px){.container{max-width: 720px;}
        }
        @media (min-width: 992px){.container{max-width: 960px;}
        }
        @media (min-width: 1200px){.container{max-width: 1140px;}
        }
        .ampg-row{display: -webkit-box;display: -webkit-flex;display: -ms-flexbox;display: flex;-webkit-flex-wrap: wrap;-ms-flex-wrap: wrap;flex-wrap: wrap;margin-right: -15px;margin-left: -15px;}
        .ampg-justify-content-center{justify-content: center;}
        @media (max-width: 767px){.ampg-col-sm-12{-ms-flex: 0 0 100%;flex: 0 0 100%;max-width: 100%;padding-right: 15px;padding-left: 15px;}
            .ampg-row{margin: 0;}
        }
        @media (min-width: 768px){.ampg-col-md-3{-ms-flex: 0 0 25%;flex: 0 0 25%;max-width: 25%;padding-right: 15px;padding-left: 15px;}
            .ampg-col-md-4{-ms-flex: 0 0 33.333333%;flex: 0 0 33.333333%;max-width: 33.333333%;padding-right: 15px;padding-left: 15px;}
            .ampg-col-md-5{-ms-flex: 0 0 41.666667%;flex: 0 0 41.666667%;max-width: 41.666667%;padding-right: 15px;padding-left: 15px;}
            .ampg-col-md-6{-ms-flex: 0 0 50%;flex: 0 0 50%;max-width: 50%;padding-right: 15px;padding-left: 15px;}
            .ampg-col-md-7{-ms-flex: 0 0 58.333333%;flex: 0 0 58.333333%;max-width: 58.333333%;padding-right: 15px;padding-left: 15px;}
            .ampg-col-md-8{-ms-flex: 0 0 66.666667%;flex: 0 0 66.666667%;max-width: 66.666667%;padding-left: 15px;padding-right: 15px;}
            .ampg-col-md-10{-ms-flex: 0 0 83.333333%;flex: 0 0 83.333333%;max-width: 83.333333%;padding-left: 15px;padding-right: 15px;}
            .ampg-col-md-12{-ms-flex: 0 0 100%;flex: 0 0 100%;max-width: 100%;padding-right: 15px;padding-left: 15px;}
        }
        @media (min-width: 992px){.ampg-col-lg-2{-ms-flex: 0 0 16.666667%;flex: 0 0 16.666667%;max-width: 16.666667%;padding-right: 15px;padding-left: 15px;}
            .ampg-col-lg-3{-ms-flex: 0 0 25%;flex: 0 0 25%;max-width: 25%;padding-right: 15px;padding-left: 15px;}
            .ampg-col-lg-4{-ms-flex: 0 0 33.33%;flex: 0 0 33.33%;max-width: 33.33%;padding-right: 15px;padding-left: 15px;}
            .ampg-col-lg-5{-ms-flex: 0 0 41.666%;flex: 0 0 41.666%;max-width: 41.666%;padding-right: 15px;padding-left: 15px;}
            .ampg-col-lg-6{-ms-flex: 0 0 50%;flex: 0 0 50%;max-width: 50%;padding-right: 15px;padding-left: 15px;}
            .ampg-col-lg-8{-ms-flex: 0 0 66.666667%;flex: 0 0 66.666667%;max-width: 66.666667%;padding-left: 15px;padding-right: 15px;}
            .ampg-col-lg-10{-ms-flex: 0 0 83.3333%;flex: 0 0 83.3333%;max-width: 83.3333%;padding-right: 15px;padding-left: 15px;}
            .ampg-col-lg-12{-ms-flex: 0 0 100%;flex: 0 0 100%;max-width: 100%;padding-right: 15px;padding-left: 15px;}
        }
        @media (min-width: 1200px){.ampg-col-xl-7{-ms-flex: 0 0 58.333333%;flex: 0 0 58.333333%;max-width: 58.333333%;padding-right: 15px;padding-left: 15px;}
            .ampg-col-xl-8{-ms-flex: 0 0 66.666667%;flex: 0 0 66.666667%;max-width: 66.666667%;padding-left: 15px;padding-right: 15px;}
        }
        section.sidebar-open:before{content: '';position: fixed;top: 0;bottom: 0;right: 0;left: 0;background-color: rgba(0,0,0,0.2);z-index: 1040;}
        p{margin-top: 0;}

        .ampsidebar .sidebar{padding: 1rem 0;margin: 0;}
        .ampsidebar .sidebar > li{list-style: none;display: flex;flex-direction: column;}
        .ampsidebar .sidebar a{display: block;text-decoration: none;margin-bottom: 10px;}
        .ampsidebar .close-sidebar{width: 30px;height: 30px;position: relative;cursor: pointer;background-color: transparent;border: none;}
        .ampsidebar .close-sidebar:focus{outline: 2px auto #007bff;}
        .ampsidebar .close-sidebar span{position: absolute;left: 0;width: 30px;height: 2px;border-right: 5px;background-color: {$this->themecolor};}
        .ampsidebar .close-sidebar span:nth-child(1){transform: rotate(45deg);}
        .ampsidebar .close-sidebar span:nth-child(2){transform: rotate(-45deg);}
        @media (min-width: 992px){.ampsidebar .brand-name{min-width: 8rem;}
            .ampsidebar .builder-sidebar{margin-left: auto;}
            .ampsidebar .builder-sidebar .sidebar li{flex-direction: row;flex-wrap: wrap;}
            .ampsidebar .builder-sidebar .sidebar li a{padding: .5rem;margin: 0;}
            .ampsidebar .builder-overlay{display: none;}
        }
        .ampsidebar .hamburger{position: absolute;top: 25px;right: 20px;margin-left: auto;width: 30px;height: 20px;background: none;border: none;cursor: pointer;z-index: 1000;}
        @media (min-width: 768px){.ampsidebar .hamburger{top: calc(0.5rem + 55 * 0.5px - 10px);}
        }
        .ampsidebar .hamburger:focus{outline: none;}
        .ampsidebar .hamburger span{position: absolute;right: 0;width: 30px;height: 2px;border-right: 5px;background-color: {$this->themecolor};}
        .ampsidebar .hamburger span:nth-child(1){top: 0;transition: all .2s;}
        .ampsidebar .hamburger span:nth-child(2){top: 8px;transition: all .15s;}
        .ampsidebar .hamburger span:nth-child(3){top: 8px;transition: all .15s;}
        .ampsidebar .hamburger span:nth-child(4){top: 16px;transition: all .2s;}
        .ampsidebar amp-img{height: 55px;width: 55px;margin-right: 1rem;display: flex;align-items: center;}
        @media (max-width: 768px){.ampsidebar amp-img{max-height: 55px;max-width: 55px;}
        }
        /*inserthere*/        

    </style>

    <script async  src='https://cdn.ampproject.org/v0.js'></script>
    <script async custom-element='amp-iframe' src='https://cdn.ampproject.org/v0/amp-iframe-0.1.js'></script>
    <script async custom-element='amp-sidebar' src='https://cdn.ampproject.org/v0/amp-sidebar-0.1.js'></script>
    <script async custom-element='amp-analytics' src='https://cdn.ampproject.org/v0/amp-analytics-0.1.js'></script>
</head>
<body>";


        $this->header=$param;
        $this->result=$template;
        $this->resetDefault();
    }

    /**
     * Generates a sidebar.
     * @param LinkModel[] $urls
     * @test void this([])
     */
    public function sidebar($urls) {
        $template= "<amp-sidebar id='sidebar' class='ampsidebar' layout='nodisplay' side='right'>
        <div class='builder-sidebar' id='builder-sidebar'>
        <button on='tap:sidebar.close' class='close-sidebar'>
            <span></span>
            <span></span>
        </button>
        <div class='sidebar text-white' data-app-modern-menu='true'>";
        foreach($urls as $url) {
            $template.="<a class='{$this->classSidebar} text-7' href='{$url->url}'>{$url->description}</a>\n";
        }
        $template.= "</div></div></amp-sidebar>";
        $this->result.=$template;
        $this->styleStack[]=".ampsidebar amp-sidebar{min-width: 260px;z-index: 1050;background-color: {$this->sidebarColor};}
        .ampsidebar amp-sidebar.open:after{content: '';position: absolute;top: 0;left: 0;bottom: 0;right: 0;background-color: red;}
        .ampsidebar .open{transform: translateX(0%);display: block;}
        .ampsidebar .builder-sidebar{background-color: {$this->sidebarColor};position: relative;height: 100vh;z-index: 1030;padding: 1rem 2rem;max-width: 20rem;}
        .ampsidebar button.sticky-but{position: fixed;}
        .ampsidebar .brand{display: flex;align-items: center;align-self: flex-start;padding-right: 30px;}
        .ampsidebar .brand p{margin: 0;padding: 0;}
        .ampsidebar .brand-name{color: {$this->themecolor};}";

        $this->resetDefault();
    }

    /**
     * Generates the header navigation
     * @param HeadModel $param
     * @param int $width
     * @param int $height
     * @test void this(new HeadModel(),0,0)
     */
    public function head($param,$width=55,$height=55) {
        $param->logo=$this->fixRelativeUrl($param->logo);
        $template= "<section class='menu ampsidebar' id='menu1'>
        <nav class='headerbar sticky-nav'>
            <div class='brand'>
              <span class='brand-logo'>
                  <a href='{$this->canonical}'>
                  <amp-img src='{$param->logo}' width='{$width}' height='{$height}' alt='Logo'>
                  </amp-img>
                  </a>
              </span>
              &nbsp;
                <a href='{$this->canonical}'>
                <p class='brand-name ampg-fonts-style text-2'>{$param->title}</p>
                </a>
            </div>
        </nav>
    
        <button on='tap:sidebar.toggle' class='ampstart-btn hamburger sticky-but'>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </button>
    </section>";
        $this->result.=$template;
        $this->styleStack[]=".ampsidebar .headerbar{display: flex;flex-direction: column;justify-content: center;padding: .5rem 1rem;min-height: 70px;align-items: center;background: {$this->backgroundColor};}
        .ampsidebar .headerbar.sticky-nav{position: fixed;z-index: 1000;width: 100%;}";
        $this->resetDefault();
    }

    /**
     * It generates a footer of the amp site.
     * @param FooterModel $param
     * @test void this(new FooterModel('copyright'))
     */
    public function sectionFooter($param) {

        if ($this->paddingTop===null) {
            $paddingTop=30;
            $paddingBottom=30;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }
        $this->backgroundColor='#232323';
        if ($param->viewDesktop) {
            $goDestkop="<a href='{$this->canonical}'>{$param->viewDesktop}</a>";
        } else {
            $goDestkop='';
        }
        $template= "<section class='footer1 ampfooter' id='footer1-3w'>
            <div class='container'>
                <div class='ampg-col-sm-12 align-center text-white'>
                    <p class='ampg-text ampg-fonts-style text-7'>
                    {$param->copyright}&nbsp;&nbsp;{$goDestkop}
                    </p>
                </div>
            </div>
        </section>
        </body>
        </html>";
        $this->result.=$template;
        $this->styleStack[]=".ampfooter{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
        .ampfooter p{margin: 0;color: #ffffff;}";
        $this->resetDefault();
    }

    /**
     * Generates a Google Analytics section
     * @param string $account UA-XXXXX-Y
     * @pretest $this->ampGeneratorOne->startAmp(new HeaderModel());
     * @test void this('UA-XXXXX-Y')
     */
    public function sectionAnalytics($account) {
        $template= '<amp-analytics type="googleanalytics">
            <script type=application/json>
            {
              "vars": {
                    "account": "'.$account.'"
              },
                  "triggers": {
                    "trackPageviewWithCustomUrl": {
                      "on": "visible",
                      "request": "pageview",
                      "vars": {
                        "title": "'.$this->header->title.'",
                        "documentLocation": "'.$this->canonical.'"
                      }
                    }
              }
            }
            </script>
            </amp-analytics>
        ';
        $this->result.=$template;
        $this->resetDefault();
    }

    /**
     * It draw a first section (hero style)
     * @param SectionModel $content
     * @test void this(new SectionModel())
     */
    public function sectionFirst($content) {
        if ($this->paddingTop===null) {
            $paddingTop=120;
            $paddingBottom=60;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }
        $content->bgImage=$this->fixRelativeUrl($content->bgImage);
        $this->secId++;
        $template= "<section class='ampg-section content{$this->secId} ampg-section{$this->secId}' id='section{$this->secId}'>
            <div class='ampg-container'>
                <h2 class='ampg-title align-center ampg-fonts-style ampg-bold {$this->classTextColor} text-1'>{$content->title}</h2>
            </div>
        </section>";
        $this->result.=$template;
        $this->styleStack[]= ".ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
        .ampg-section{$this->secId} .ampg-title{padding-bottom: 1rem;}";
        $this->resetDefault();
    }

    /**
     * It generates a section with a single button.
     * @param SectionModel $content
     * @param bool $fullscreen
     * @test void this(new SectionModel(),false)
     */
    public function sectionImageButton($content,$fullscreen=true) {
        if (count($content->buttons)==0) return; // no buttons
        $content->bgImage=$this->fixRelativeUrl($content->bgImage);
        if ($content->buttons<1) {
            die("Error:sectionImageButton must have at least a button");
        }
        $htmlFull=($fullscreen)?"ampg-fullscreen":"";
        $this->secId++;
        $template= "<section class='ampg-section{$this->secId} {$htmlFull}' id='section{$this->secId}'>
            <div class='container'>
                <h1 class='ampg-section-title ampg-fonts-style align-right ampg-bold text-white text-1'>
                    <strong>{$content->title}</strong>
                </h1>
                <h3 class='ampg-section-subtitle ampg-fonts-style align-right text-white ampg-light text-2'>{$content->description}</h3>
                <div class='ampg-section-btn align-right'>
                    <a class='btn btn-md btn-{$content->buttons[0]->color} text-4' href='{$content->buttons[0]->url}'>{$content->buttons[0]->title}</a>
                </div>
            </div>
        </section>";
        $this->result.=$template;
        $this->styleStack[]= ".ampg-section{$this->secId}{background-image: url('{$content->bgImage}');}";
        $this->resetDefault();
    }



    /**
     * It generates a section with a text.
     * @param SectionModel $content
     * @test void this(new SectionModel())
     */
    public function sectionText($content) {
        if ($this->paddingTop===null) {
            $paddingTop=60;
            $paddingBottom=60;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }
        $this->secId++;
        $template= "<section class='content{$this->secId} ampg-section article ampg-section{$this->secId}' id='section{$this->secId}'>         
            <div class='container'>
                <div class='ampg-row ampg-justify-content-center'>
                    <div class='ampg-col-sm-12 ampg-col-md-10 ampg-col-lg-8'>
                        <h3 class='ampg-fonts-style ampg-section-title ampg-light ".$this->classTextColor." text-2'>{$content->title}</h3>
                        <p class='ampg-text ampg-fonts-style ".$this->classTextColor." text-7'>
                            {$content->description}</p>
                    </div>
                </div>
            </div>
        </section>";
        $this->result.=$template;
        $this->styleStack[]= ".ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
        .ampg-section{$this->secId} .ampg-text{margin-bottom: 0;}
        .ampg-section{$this->secId} .ampg-section-title{margin: 0;}";
        $this->resetDefault();
    }
    /**
     * It generates a quote text
     * @param SectionModel $content
     * @test void this(new SectionModel())
     */
    public function sectionTextQuote($content) {
        if ($this->paddingTop===null) {
            $paddingTop=60;
            $paddingBottom=60;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }
        $this->secId++;
        $htmlLink=$this->genLink($content->url);
        $template= "<section class='content{$this->secId} ampg-section article ampg-section{$this->secId}' id='section{$this->secId}'>     
            <div class='container'>
                <div class='ampg-row ampg-justify-content-center'>
                    <div class='ampg-text ampg-fonts-style ampg-col-sm-12 ampg-col-md-8 text-4'>
                        <blockquote>{$content->description}&nbsp;{$htmlLink}</blockquote>
                    </div>
                </div>
            </div>
        </section>";
        $this->result.=$template;
        $this->styleStack[]= ".ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
        .ampg-section{$this->secId} blockquote{border-color: #ffc107;}";
        $this->resetDefault();
    }

    /**
     * It generates an unsorted list section
     * @param LinkModel[] $links
     * @test void this([])
     */
    public function sectionUL($links) {
        if ($this->paddingTop===null) {
            $paddingTop=60;
            $paddingBottom=60;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }
        $this->secId++;

        $template= "<section class='ampg-section ampg-section{$this->secId}' id='section{$this->secId}'>    
        <div class='ampg-container'>
            <div class='ampg-text counter-container ampg-fonts-style text-7'>
                <ul>";
        foreach($links as $link) {
            $template.="<li><strong>{$link->description}</strong>- {$link->description}&nbsp;".$link->url."</li>\n";
        }
        $template.= "</ul></div></div></section>";
        $this->result.=$template;
        $this->styleStack[]= ".ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
        .ampg-section{$this->secId} .counter-container ul{margin-bottom: 0;}
        .ampg-section{$this->secId} .counter-container ul li{color: inherit;margin-bottom: 1rem;list-style: none;}
        .ampg-section{$this->secId} .counter-container ul li:before{position: absolute;left: 0px;padding-top: 3px;content: '';display: inline-block;text-align: center;margin: 10px 15px;line-height: 20px;transition: all .2s;color: #ffffff;background: #007bff;width: 10px;height: 10px;border-radius: 50%;border-radius: 0;transform: rotate(45deg);}";
        $this->resetDefault();
    }
    /**
     * It generates a sorted list section
     * @param LinkModel[] $links
     * @test void this([])
     */
    public function sectionOL($links) {
        if ($this->paddingTop===null) {
            $paddingTop=60;
            $paddingBottom=60;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }
        $this->secId++;
        $template= "<section class='ampg-section ampg-section{$this->secId}' id='section{$this->secId}'>    
    <div class='ampg-container'>
        <div class='ampg-text counter-container ampg-fonts-style text-7'>
            <ol>";
        foreach($links as $link) {
            $template.="<li><strong>{$link->description}</strong>- {$link->description}&nbsp;".$link->url."</li>\n";
        }
        $template.= "</ol></div></div></section>";
        $this->result.=$template;
        $this->styleStack[]= ".ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
        .ampg-section{$this->secId} .counter-container ol{margin-bottom: 0;counter-reset: myCounter;}
        .ampg-section{$this->secId} .counter-container ol li{margin-bottom: 2rem;}
        .ampg-section{$this->secId} .counter-container ol li{z-index: 3;list-style: none;padding-left: .5rem;}
        .ampg-section{$this->secId} .counter-container ol li:before{z-index: 2;position: absolute;left: 0px;counter-increment: myCounter;content: counter(myCounter);text-align: center;margin: 0 10px;line-height: 30px;transition: all .2s;width: 35px;height: 35px;color: #007bff;font-size: 20px;font-weight: bold;border-radius: 50%;border: 2px solid #007bff;opacity: 0.5;}";
        $this->resetDefault();
    }

    /**
     * It generates a section with one (or more than one) buttons.
     * @param SectionModel $content
     * @test void this(new SectionModel())
     */
    public function sectionButtons($content) {
        if ($this->paddingTop===null) {
            $paddingTop=60;
            $paddingBottom=60;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }
        $this->secId++;
        $template= "<section class='ampg-section ampg-section{$this->secId}' id='section{$this->secId}'>    
        <div class='ampg-container'>
            <div class='ampg-section-btn align-center'>";
        foreach($content->buttons as $button) {
            $template.=$this->genButton($button)."\n";
        }
        $template.= "</div></div></section>     ";
        $this->result.=$template;
        $this->styleStack[]= ".ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}";
        $this->resetDefault();
    }

    /**
     * It generates a section with columns
     * @param SectionModel[] $contents
     * @test void this([])
     */
    public function sectionCols($contents) {
        if ($this->paddingTop===null) {
            $paddingTop=75;
            $paddingBottom=75;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }
        $this->secId++;
        $num=count($contents);
        if ($num==0) return;
        $colmd=6;
        $collg=floor(12/$num);
        $template= "<section class='ampg-section ampg-section{$this->secId}' id='section{$this->secId}'>    
        <div class='container'>
            <div class='ampg-row ampg-justify-content-center'>";
        foreach($contents as $content) {
            $template.= "<div class='card ampg-col-sm-12 ampg-col-md-{$colmd} ampg-col-lg-{$collg}'>            
                <h3 class='ampg-section-title ampg-fonts-style align-center {$this->classTextColor} text-5'>
                ".(($content->fontIcon!="")?"<i class='{$content->fontIcon}'></i><br>":"")."
                {$content->title}</h3>
                <p class='ampg-text ampg-fonts-style align-center {$this->classTextColor} text-4'>{$content->description}</p>             
            </div>";
        }
        $template.= "</div></div></section>  ";
        $this->result.=$template;

        $this->styleStack[]=".ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
        .ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
        .ampg-section{$this->secId} .ampg-row{margin: 0 15px;}
        @media (max-width: 991px){.ampg-section{$this->secId} .card{margin-bottom: 20px;}
        }
        .ampg-section{$this->secId} .ampg-section-title{margin: 0;padding-bottom: 1rem;}
        .ampg-section{$this->secId} .ampg-text{margin: 0;}";
        $this->resetDefault();
    }

    /**
     * It generates a header central section. It could includes buttons.
     * @param SectionModel $content
     * @test void this(new SectionModel())
     */
    public function sectionHeaderCentral($content) {
        $content->bgImage=$this->fixRelativeUrl($content->bgImage);
        if ($this->paddingTop===null) {
            $paddingTop=90;
            $paddingBottom=90;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }
        $this->secId++;
        $template= "<section class='ampg-section{$this->secId}' id='section{$this->secId}'>   
            <div class='container'>
                <h1 class='ampg-section-title ampg-fonts-style align-right ampg-bold {$this->classTextColor} text-1'>
                    <strong>{$content->title}</strong></h1>
                <h3 class='ampg-section-subtitle ampg-fonts-style align-right {$this->classTextColor} ampg-light text-2'>
                    {$content->subtitle}</h3>
                <p class='ampg-fonts-style ampg-text align-right {$this->classTextColor} text-7'>{$content->description}</p>
                <div class='ampg-section-btn align-right'>";
        foreach($content->buttons as $button) {
            $template.=$this->genButton($button).'\n';
        }
        $template.=<<<cin2
        </div>
    </div>
</section>
cin2;
        $this->result.=$template;
        $style = ".ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}";
        $style.= ".ampg-section{$this->secId} .ampg-section-subtitle{text-align: center;}
        .ampg-section{$this->secId} .ampg-text,.ampg-section{$this->secId} .ampg-section-btn{text-align: center;}
        .ampg-section{$this->secId} .ampg-section-title{text-align: center;}";
        $this->styleStack[]=$style;
        $this->resetDefault();
    }

    public function sectionRaw($txt) {
        $this->result.=$txt;
    }

    /**
     * It generates a section with an image content.
     * @param SectionModel $content
     * @param $width
     * @param $height
     * @test void this(new SectionModel(),0,0)
     */
    public function sectionImageContent($content,$width,$height) {
        if ($this->paddingTop===null) {
            $paddingTop=30;
            $paddingBottom=30;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }
        $content->bgImage=$this->fixRelativeUrl($content->bgImage);
        $this->secId++;
        $template= "<section class='section{$this->secId} ampg-section{$this->secId}' id='section{$this->secId}'>          
        <div class='container'>
            <div class='ampg-row ampg-justify-content-center'>
                <div class='image-block ampg-col-sm-12 ampg-col-md-6'>
                    <amp-img src='{$content->bgImage}' layout='responsive' width='{$width}' height='{$height}' alt='{$content->title}'>
                    </amp-img>
                </div>
                <div class='text-block ampg-col-sm-12 ampg-col-md-6'>
                    <h3 class='ampg-section-title ampg-fonts-style align-left {$this->classTextColor} text-5'>{$content->title}</h3>
                    <p class='ampg-fonts-style ampg-text align-left {$this->classTextColor} text-7'>{$content->description}</p>";
    if (count($content->buttons)) {
        $template.="<div class='ampg-section-btn align-left'>".$this->genButton($content->buttons)."</div>";
    }
        $template.= "</div></div></div></section>";
        $this->result.=$template;
        $this->styleStack[]= ".ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
        .ampg-section{$this->secId} .text-block{margin: auto;}
        .ampg-section{$this->secId} amp-img{text-align: center;}";
        $this->resetDefault();
    }
    /**
     * It generates a section with a image content at the left.
     * @param SectionModel $content
     * @param $width
     * @param $height
     * @test void this(new SectionModel(),0,0)
     */
    public function sectionImageContentLeft($content,$width,$height) {
        if ($this->paddingTop===null) {
            $paddingTop=30;
            $paddingBottom=30;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }
        $content->bgImage=$this->fixRelativeUrl($content->bgImage);
        $this->secId++;
        $template= "<section class='section{$this->secId} ampg-section{$this->secId}' id='section{$this->secId}'>          
        <div class='container'>
            <div class='ampg-row ampg-justify-content-center'>
                <div class='text-block ampg-col-sm-12 ampg-col-md-6'>
                    <h3 class='ampg-section-title ampg-fonts-style align-left {$this->classTextColor} text-5'>{$content->title}</h3>
                    <p class='ampg-fonts-style ampg-text align-left {$this->classTextColor} text-7'>{$content->description}</p>";
        if (count($content->buttons)) {
            $template.="<div class='ampg-section-btn align-left'>".$this->genButton($content->buttons)."</div>";
        }
        $template.= "</div>
                <div class='image-block ampg-col-sm-12 ampg-col-md-6'>
                    <amp-img src='{$content->bgImage}' layout='responsive' width='{$width}' height='{$height}' alt='{$content->title}'>
                    </amp-img>
                </div>
        </div></div></section>";
                $this->result.=$template;
                $this->styleStack[]= ".ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
                .ampg-section{$this->secId} .text-block{margin: auto;}
                .ampg-section{$this->secId} amp-img{text-align: center;}";
                $this->resetDefault();
    }

    /**
     * It generates a table
     * @param array $cols
     * @test void this([])
     */
    public function sectionTable($cols) {
        $this->secId++;
        if ($this->paddingTop===null) {
            $paddingTop=75;
            $paddingBottom=75;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }
        if (count($cols)<=1) {
            return;
        }
        if (is_object($cols[0])) {
            $array = json_decode(json_encode($cols), true);
        } else {
            $array=$cols;
        }
        $headers=array_keys($cols[0]);


        $template= "<section class='ampg-section{$this->secId}' id='section{$this->secId}'>     
        <div class='container'>
            <div class='ampg-row ampg-justify-content-center'>
            <table cellspacing=0 class='table ampg-text ampg-fonts-style' width=100%><thead><tr>";
        foreach($headers as $h) {
            $template.="<th><p class='ampg-text tabletitle ampg-fonts-style align-left text-7'>{$h}</p></th>";
        }
        $template.="</tr></thead><tbody>";
        foreach($cols as $c) {
            $template .= "<tr>";
            foreach($headers as $h) {
                $template .= "<td><p class='ampg-text ampg-fonts-style text-7'>{$c[$h]}</p></td>";
            }
            $template .= "</tr>";
        }
        $template.= "</tbody></table></div></div></section>";
        $this->result.=$template;
        $this->styleStack[]= "
        .ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
        .ampg-section{$this->secId} table{width: 100%;border-collapse: collapse;min-width: 500px;}
        .ampg-section{$this->secId} table td{border-top: none;padding: .75rem;}
        .ampg-section{$this->secId} table th{border-top: none;padding: .75rem;border-bottom: 2px solid #cecece;}
        .ampg-section{$this->secId} p{padding: 5px;margin: 0px;}
        .ampg-section{$this->secId} .scroll{overflow-x: auto;}
        .ampg-section{$this->secId} .wrapscroll{width: 100%;}
        .ampg-section{$this->secId} .ampg-section-subtitle{color: #7f7e7e;margin-bottom: 2rem;}    ";
        $this->resetDefault();
    }


    /**
     * It generates an image. The size of the image is required to calculate the ratio
     * @param SectionModel $content
     * @param int $width
     * @param int $height
     * @test void this(new SectionModel(),0,0)
     */
    public function sectionImage($content,$width,$height) {
        $content->bgImage=$this->fixRelativeUrl($content->bgImage);

        if ($this->paddingTop===null) {
            $paddingTop=90;
            $paddingBottom=90;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }
        $this->secId++;
        $urlStart=(count($content->url))?"<a href='".$content->url[0]->url."'>":"";
        $urlEnd=(count($content->url))?"</a>":"";
        $template= "<section class='ampg-section{$this->secId}' id='section{$this->secId}'> 
        <div>
            <div class='image-block'>{$urlStart}
                <amp-img src='{$content->bgImage}' layout='responsive' width='{$width}' height='{$height}' alt='{$content->title}'>                
                </amp-img>{$urlEnd}
                <div class='ampg-figure-caption'>
                    <p class='ampg-text align-center ampg-fonts-style {$this->classTextColor} text-7'>{$content->title}
                    </p>
                </div>
                <div class='ampg-section-btn align-center'>";
        foreach($content->buttons as $button) {
            $template.=$this->genButton($button)."\n";
        }

        $template.= "</div></div></div></section>";
        $this->result.=$template;
        $this->styleStack[]= ".ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
        .ampg-section{$this->secId} .image-block{margin: auto;width: 66%;width: 100%;}
        .ampg-section{$this->secId} amp-img{text-align: center;}
        .ampg-section{$this->secId} .ampg-text{margin: 0;padding: .5rem 0;padding: .5rem 15px;}
        .ampg-section{$this->secId} .ampg-section-btn{margin: 0;padding: .5rem 0;padding: .5rem 15px;}
        @media (max-width: 767px){.ampg-section{$this->secId} .image-block{width: 100%;}
        }
        ";
        $this->resetDefault();
    }

    /**
     * It generates a google map section. It can't be locate at the 30% top of the site (Amp restriction)
     * @param SectionModel $content
     * @param string $googleMapUrl
     * @param string $placeholder
     * @test void this(new SectionModel(),'mapurl','loading..')
     */
    public function sectionGMapFull($content,$googleMapUrl="",$placeholder="Google Map Loading...") {
        if ($this->paddingTop===null) {
            $paddingTop=60;
            $paddingBottom=60;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }
        $googleMapUrl=($googleMapUrl=="")?"https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d13320.170086624597!2d-70.60383335!3d-33.422135749999995!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2scl!4v1536022894740":$googleMapUrl;
        $this->secId++;
        $urlStart=(count($content->url))?"<a href='".$content->url[0]->url."'>":"";
        $urlEnd=(count($content->url))?"</a>":"";
        $template= "<section class='map ampg-section{$this->secId}' id='section{$this->secId}'>  
            <div class='ampg-row ampg-justify-content-center'>
                <div class='map-title ampg-col-sm-12 ampg-col-md-12 align-center'>                    
                </div>
                <div class='google-map'><amp-iframe src='{$googleMapUrl}' 
                height='400' layout='fixed-height' sandbox='allow-scripts allow-same-origin allow-popups' frameborder='0'>
                    <h4 placeholder=''>{$placeholder}</h4>
                </amp-iframe></div>        
            </div>
        </section>";
        $this->result.=$template;
        $this->styleStack[]= ".ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
        .ampg-section{$this->secId} .ampg-row{margin-left: 0;margin-right: 0;}
        .ampg-section{$this->secId} .ampg-section-title{padding-bottom: 3rem;}
        .ampg-section{$this->secId} .google-map{height: 25rem;position: relative;width: 100%;}
        .ampg-section{$this->secId} .google-map iframe{height: 100%;width: 100%;}
        .ampg-section{$this->secId} .google-map [data-state-details]{color: #6b6763;font-family: Montserrat;height: 1.5em;margin-top: -0.75em;padding-left: 1.25rem;padding-right: 1.25rem;position: absolute;text-align: center;top: 50%;width: 100%;}
        .ampg-section{$this->secId} .google-map[data-state]{background: #e9e5dc;}
        .ampg-section{$this->secId} .google-map[data-state=\"loading\"] [data-state-details]{display: none;}
        .ampg-section{$this->secId} .map-placeholder{display: none;}
        .ampg-section{$this->secId} h4{padding-top: 5rem;color: #767676;text-align: center;}                       
        ";
        $this->resetDefault();
    }
    /**
     * It generates a google map (boxed) section. It can't be locate at the 30% top of the site (Amp restriction)
     * @param SectionModel $content
     * @param string $googleMapUrl
     * @test void this(new SectionModel(),'mapurl')
     */
    public function sectionGMapBoxed($content,$googleMapUrl="") {
        if ($this->paddingTop===null) {
            $paddingTop=90;
            $paddingBottom=90;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }
        $googleMapUrl=($googleMapUrl=="")?"https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d13320.170086624597!2d-70.60383335!3d-33.422135749999995!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2scl!4v1536022894740":$googleMapUrl;
        $this->secId++;
        $urlStart=(count($content->url))?"<a href='".$content->url[0]->url."'>":"";
        $urlEnd=(count($content->url))?"</a>":"";
        $template= "<section class='map ampg-section{$this->secId}' id='section{$this->secId}'>  
            <div class='container'>
                <div class='ampg-row ampg-justify-content-center'>
                    <div class='map-title ampg-col-sm-12 ampg-col-md-12 align-center'>                
                    </div>
                    <div class='ampg-col-md-12 map-box'>
                        <div class='google-map'>
                        <amp-iframe src='{$googleMapUrl}'
                        height='400' layout='fixed-height'
                        sandbox='allow-scripts allow-same-origin allow-popups' 
                        frameborder='0'>
                            <h4 placeholder=''>Google Map Loading..</h4>
                        </amp-iframe></div>
                        
                    </div>
                </div>
            </div>
        </section>";

        $this->result.=$template;
        $this->styleStack[]= "
        .ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
        .ampg-section{$this->secId} .google-map{height: 25rem;position: relative;width: 100%;}
        .ampg-section{$this->secId} .google-map iframe{height: 100%;width: 100%;}
        .ampg-section{$this->secId} .google-map [data-state-details]{color: #6b6763;font-family: Montserrat;height: 1.5em;margin-top: -0.75em;padding-left: 1.25rem;padding-right: 1.25rem;position: absolute;text-align: center;top: 50%;width: 100%;}
        .ampg-section{$this->secId} .google-map[data-state]{background: #e9e5dc;}
        .ampg-section{$this->secId} .google-map[data-state=\"loading\"] [data-state-details]{display: none;}
        .ampg-section{$this->secId} .map-placeholder{display: none;}
        .ampg-section{$this->secId} h4{padding-top: 5rem;color: #767676;text-align: center;}
        .ampg-section{$this->secId} .ampg-section-title{padding-bottom: 3rem;}
        
                             
        ";
        $this->resetDefault();
    }

    /**
     * It generates a footer navigation
     * @param SectionModel $content
     * @param string $image
     * @param int $width
     * @param int $height
     * @param LinkModel[] $navCol1
     * @param LinkModel[] $navCol2
     * @param LinkModel[] $navCol3
     * @param LinkModel[] $navCol4
     * @test void this(new SectionModel())
     */
    public function sectionNavigation($content,$image="",$width=0,$height=0,$navCol1=null,$navCol2=null,$navCol3=null,$navCol4=null) {
        $image=$this->fixRelativeUrl($image);
        $this->secId++;
        if ($this->paddingTop===null) {
            $paddingTop=45;
            $paddingBottom=45;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }

        $num=1;
        if ($image!="" || count($content->buttons)>0) $num++;
        $all=array();
        $all[]=$navCol1;
        if ($navCol1===null) return; // nothing to show.
        if ($navCol2!==null) {$num++; $all[]=$navCol2;}
        if ($navCol3!==null) {$num++; $all[]=$navCol3;}
        if ($navCol4!==null) {$num++; $all[]=$navCol4;}


        $colmd=6;
        $collg=floor(12/$num);

        $template= "<section class='ampg-section{$this->secId}' id='section{$this->secId}'> 
            <div class='container'>
                <div class='ampg-row ampg-justify-content-center'>";
            if ($image!="") {
                $template .= "
                    <div class='image-block ampg-col-sm-12 ampg-col-md-{$collg}'>
                        <amp-img src='{$image}' layout='responsive' width='{$width}' height='{$height}' alt='{$content->title}'>
                        </amp-img>
                    </div>";
            }
        if ($content->title!='') {
            if (count($content->buttons)) $content->buttons[0]->url=$this->fixRelativeUrl($content->buttons[0]->url);

            $template .= "
                    <div class='image-block ampg-col-sm-12 ampg-col-md-{$collg} align-center'>                        
                        <div class='items'>
                        <p class='item ampg-fonts-style align-left {$this->classTextColor} text-5'>{$content->title}</p>
                        <p class='item ampg-fonts-style align-left {$this->classTextColor} text-7'>{$content->description}</p>                        
                        </div>
                        {$this->genButton($content->buttons)}
                    </div>";
        }
        foreach($all as $col) {
            $template .= "<div class='items-col ampg-col-sm-12 align-right ampg-col-md-{$collg}'>
                        <div class='items'>";
            foreach($col as $c) {
                if ($c->isHeader) {
                    $template .= "<p class='item ampg-fonts-style text-5'><a class='{$this->classTextColor}' href='{$c->url}'>{$c->description}</a></p>";
                } else {
                    $template .= "<p class='item ampg-fonts-style text-7'><a class='{$this->classTextColor}' href='{$c->url}'>{$c->description}</a></p>";
                }
            }
            $template .= "</div></div>";
        }
                $template.="</div>
            </div>
        </section>";


        $template.= "</div></div></div></section>";
        $this->result.=$template;
        $this->styleStack[]=".ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
        .ampg-section{$this->secId} amp-img{text-align: center;}
        .ampg-section{$this->secId} .items-col .item{margin: 0;}
        .ampg-section{$this->secId} .item,.ampg-section{$this->secId} .group-title{color: #efefef;padding-top: 0;}
        @media (max-width: 767px){.ampg-section{$this->secId} .items-col{text-align: center;margin: 2rem 0;}
        }        
        ";

        $this->resetDefault();
    }


    /**
     * It generates a section with images and text.
     * @param SectionModel[] $contents
     * @param int $width
     * @param int $height
     * @param int $maxCol
     * @test void this([],0,0,0)
     */
    public function sectionColImage($contents,$width,$height,$maxCol=3) {
        $this->secId++;
        $num=count($contents);
        if ($num===0) return; //nothing to show.
        if ($num>$maxCol) {
            $colmd=6;
            $collg=floor(12/$maxCol);
        } else {
            $colmd=6;
            $collg=floor(12/$num);
        }
        if ($this->paddingTop===null) {
            $paddingTop=30;
            $paddingBottom=30;
        } else {
            $paddingTop=$this->paddingTop;
            $paddingBottom=$this->paddingBottom;
        }

        $template= "<section class='ampg-section ampg-section{$this->secId}' id='features{$this->secId}'>       
            <div class='container'>
            <div class='ampg-row ampg-justify-content-center'>";
        $col=0;
        foreach($contents as $content) {
            $content->bgImage=$this->fixRelativeUrl($content->bgImage);
            if ($col % $maxCol ===0 && $col!=0) {
                $template.="</div><div class='ampg-row ampg-justify-content-center'>";
            }
            $col++;
            $template.= "<div class='card ampg-col-sm-12 ampg-col-md-{$colmd} ampg-col-lg-{$collg}'>
                <div class='card-wrapper'>
                    <div class='card-img'>
                        ".(count($content->url)?"<a href='{$content->url[0]->url}'>":"")."
                        <amp-img src='{$content->bgImage}' layout='responsive' width='{$width}' height='{$height}' alt='{$content->title}'>                            
                        </amp-img>
                        ".(count($content->url)?"</a>":"")."
                    </div>
                    <div class='card-box'>
                        <h4 class='card-title ampg-fonts-style align-left ampg-light {$this->classTextColor} text-2'>{$content->title}</h4>
                        <p class='ampg-text ampg-fonts-style align-left {$this->classTextColor} text-4'>{$content->description}</p>
                        <div class='ampg-section-btn align-left'>";

                if (count($content->buttons)) {
                    $template.="<div class='ampg-section-btn align-left'>".$this->genButton($content->buttons)."</div>";
                }
                $template.= "</div>
                    </div>
                </div>
            </div>";
        }
        $template.= "</div></div></section>";
        $this->result.=$template;
        $this->styleStack[]= ".ampg-section{$this->secId}{".$this->genModifyStyle($paddingTop,$paddingBottom)."}
            .ampg-section{$this->secId} .card{margin-bottom: 20px;position: relative;display: flex;-ms-flex-direction: column;flex-direction: column;min-width: 0;word-wrap: break-word;background-clip: border-box;border-radius: 0;width: 100%;min-height: 1px;}
            .ampg-section{$this->secId} .card .btn{margin: .4rem 4px;}
            .ampg-section{$this->secId} .card-title{margin: 0;}
            .ampg-section{$this->secId} .card-box{padding-top: 2rem;}
            .ampg-section{$this->secId} amp-img{width: 100%;}";
        $this->resetDefault();
    }
    private function genModifyStyle($paddingTop,$paddingBottom) {
        if ($this->bgImage) {
            return "padding-top: {$paddingTop}px;padding-bottom: {$paddingBottom}px;background-image: url('{$this->bgImage}');";
        } else {
            return "padding-top: {$paddingTop}px;padding-bottom: {$paddingBottom}px;background-color: {$this->backgroundColor};";
        }
    }

    /**
     * Fluent operation, it changes the background color of the next section
     * @param string $color Example #ffffff,rgb(30,30,30),white
     * @return $this
     * @test InstanceOf AmpGeneratorOne::class,this('#ffffff')
     */
    public function setBackgroundColor($color) {
        $this->backgroundColor=$color;
        return $this;
    }
    /**
     * Fluent operation, it changes the background image of the next section
     * @param string $bgImage
     * @return $this
     * @test InstanceOf AmpGeneratorOne::class,this('img.jpg')
     */
    public function setBgImage($bgImage) {
        $this->bgImage=$bgImage;
        $this->bgImage=$this->fixRelativeUrl($this->bgImage);
        return $this;
    }

    /**
     * Fluent operation, it sets the class of the text
     * @param string $class text-primary,text-secondary,text-success,text-info,text-warning,text-danger,text-white,text-black
     * @return $this
     * @test InstanceOf AmpGeneratorOne::class,this('text-primary')
     */
    public function setClassTextColor($class) {
        $this->classTextColor=$class;
        return $this;
    }

    /**
     * Fluent operation, it changes the padding
     * @param $top
     * @param $bottom
     * @return $this
     * @test InstanceOf AmpGeneratorOne::class,this(0,0)
     */
    public function setPadding($top,$bottom) {
        $this->paddingTop=$top;
        $this->paddingBottom=$bottom;
        return $this;
    }

    private function resetDefault() {
        $this->backgroundColor=$this->defaultBackGroundColor;
        $this->classTextColor=$this->defaultClassTextColor;
        $this->paddingTop=null;
        $this->paddingBottom=null;
        $this->bgImage="";
    }

    /**
     * It reset to the default background color and text class
     * @param string $backGroundColor Example #ffffff,rgb(30,30,30),white
     * @param string $classTextColor  text-primary,text-secondary,text-success,text-info,text-warning,text-danger,text-white,text-black
     */
    public function setDefault($backGroundColor,$classTextColor) {
        $this->defaultBackGroundColor=$backGroundColor;
        $this->defaultClassTextColor=$classTextColor;
    }

    private function image($title,$description,$image) {

    }
    private function table($title,$description,$image) {

    }

    /**
     * It renders the result page.
     */
    public function render() {
        $r=implode("\n",$this->styleStack);
        $this->result=str_replace("/*inserthere*/",$r,$this->result);
        echo $this->result;
    }

    /**
     * It renders the result to file
     * @param string $filename
     * @return bool|int
     */
    public function renderToFile($filename) {
        $r=implode("\n",$this->styleStack);
        $this->result=str_replace("/*inserthere*/",$r,$this->result);

        return file_put_contents($filename,$this->result);
    }

    //region private functions
    /**
     * Generate the first link of an array only if array constains information or the link is not empty.
     * @param LinkModel|LinkModel[] $url
     * @return string
     * @test equals 'xxx',this('url/link')
     */
    private function genLink($url) {
        if (is_array($url)) {
            if (count($url)) {
                $htmlLink = "<a href=\"{$url[0]->url}/\"><em>{$url[0]->description}</em></a>";
            } else {
                $htmlLink = "";
            }
        } else {
            if ($url->description!="") {
                $htmlLink = "<a href=\"{$url->url}/\"><em>{$url->description}</em></a>";
            } else {
                $htmlLink="";
            }
        }
        return $htmlLink;
    }

    /**
     * Generate the first link of an array only if array constains information or the link is not empty.
     * @param ButtonModel|ButtonModel[] $url
     * @return string
     */
    private function genButton($url) {
        if (is_array($url)) {
            if (count($url)) {
                $htmlLink = "<a class='btn btn-md btn-{$url[0]->color} text-4' href='{$url[0]->url}'>{$url[0]->title}</a>";
            } else {
                $htmlLink = "";
            }
        } else {
            if ($url->title!="") {
                $htmlLink = "<a class='btn btn-md btn-{$url->color} text-4' href='{$url->url}'>{$url->title}</a>";
            } else {
                $htmlLink="";
            }
        }

        return $htmlLink;
    }

    //endregion
} // end class

//region Models
class HeaderModel {
    var $description;
    var $title;
    var $icon;

    /**
     * HeaderModel constructor.
     * @param string $description
     * @param string $title
     * @param string $icon
     */
    public function __construct($description="", $title="", $icon="")
    {
        $this->description = $description;
        $this->title = $title;
        $this->icon = $icon;
    }
}

class HeadModel {
    var $title;
    var $logo;

    /**
     * HeadModel constructor.
     * @param $title
     * @param $logo
     */
    public function __construct($title="", $logo="")
    {
        $this->title = $title;
        $this->logo = $logo;
    }

}

class FooterModel {
    var $copyright;
    var $viewDesktop;

    /**
     * FooterModel constructor.
     * @param string $copyright
     * @param string $viewDesktop Name of the Link."View as Desktop"
     */
    public function __construct($copyright, $viewDesktop="")
    {
        $this->copyright = $copyright;
        $this->viewDesktop = $viewDesktop;
    }

}

class LinkModel {
    var $url;
    var $description;
    var $icon;
    var $isHeader;

    /**
     * LinksModel constructor.
     * @param string $description
     * @param string $url
     * @param string $icon
     * @param bool $isHeader
     */
    public function __construct( $description="",$url="#", $icon="",$isHeader=false)
    {
        $this->url = $url;
        $this->description = $description;
        $this->icon = $icon;
        $this->isHeader=$isHeader;
    }
}
class SectionModel {
    var $title;
    var $description;
    var $subtitle;
    /** @var LinkModel[]  */
    var $url;
    var $bgImage;
    /** @var ButtonModel[] */
    var $buttons;

    var $fontIcon;

    /**
     * SectionModel constructor.
     * @param string $title
     * @param string $description
     * @param string $bgImage
     * @param string $subtitle
     * @param string $fontIcon
     */
    public function __construct($title="", $description="", $bgImage="",$subtitle="",$fontIcon="")
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->description = $description;
        $this->url = array();
        $this->bgImage = $bgImage;
        $this->buttons=[];
        $this->fontIcon=$fontIcon;
    }
}
class ButtonModel {
    /** @var string  */
    var $title;
    /** @var string  */
    var $url;
    /** @var string primary|success|warning|danger */
    var $color;

    /**
     * ButtonModel constructor.
     * @param string $title
     * @param string $url
     * @param string $color primary|success|warning|danger
     */
    public function __construct(string $title="", string $url="#", string $color="primary")
    {
        $this->title = $title;
        $this->url = $url;
        $this->color = $color;
    }
}
class StructureModel {
    var $name;
    var $description;
    var $url;
    var $image;
    var $imageWidth;
    var $imageHeight;
    /** @var string
     * @see http://ogp.me/#types
     */
    var $ogtype="website";
    var $twittercreator;
    var $twittersite;
    var $customJson;

}

//endregion