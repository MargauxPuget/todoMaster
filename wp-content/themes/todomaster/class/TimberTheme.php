<?php

namespace MPuget\TodoMaster;


use Timber\Timber;


class TimberTheme
{

    public function __construct()
    {
        add_filter('timber/context', [$this, 'addToContext']);
        add_filter('timber/twig', [$this, 'addToTwig']);
    }


    public function addToContext($context)
    {
        $context['homeUrl'] = home_url('/');
        $context['contactPageLink'] = get_permalink($context['options']['contactPage']) ?? "";
        $context['blogUrl'] = get_permalink(get_option('page_for_posts')) ?? "";
        // Proto dirs / Assets
        $context['ASSETS_DIR'] = ASSETS_DIR;
        $context['ASSETS_SUB_DIR'] = ASSETS_SUB_DIR;
        // Img placeholder
        $context['IMG_PLACEHOLDER'] = IMG_PLACEHOLDER;
        // SEO
        if (!is_front_page()) {
            $breadcrumb = new BreadCrumb();
            $context['breadcrumb'] = $breadcrumb->getBreadCrumb();
        }

        $context['prevLabel'] = __('Précédent', 'aydat');
        $context['nextLabel'] = __('Suivant', 'aydat');
        // REST
        $context['restUrl'] = Rest::getRestUrl();
        $context['newsletterRestUrl'] = $context['restUrl'] . 'newsletter';
        $context['contactRestUrl'] = $context['restUrl'] . 'contact';
        $context['captchaKey'] = $context['options']['captchaKey'] ?? "";
        // Front
        $context['isFrontPage'] = is_front_page();
        $context['isSearch'] = is_search();
        // Weather
        $weather = new Weather();
        $context['weather'] = $weather->getCurrent();

        return $context;
    }

    public function addToTwig($twig)
    {
        // Adding usefull WP functions
        $twig->addFunction(new TwigFunction('get_term_link', 'get_term_link'));
        $twig->addFunction(new TwigFunction('get_permalink', 'get_permalink'));
        $twig->addFunction(new TwigFunction('get_search_query', 'get_search_query'));
        $twig->addFunction(new TwigFunction('get_privacy_policy_url', 'get_privacy_policy_url'));
        $twig->addFunction(new TwigFunction('selected', 'selected'));
        $twig->addFunction(new TwigFunction('checked', 'checked'));
        $twig->addFunction(new TwigFunction('disabled', 'disabled'));
        $twig->addFunction(new TwigFunction('get_queried_object', 'get_queried_object'));
        $twig->addFunction(new TwigFunction('get_post_type_object', 'get_post_type_object'));
        $twig->addFunction(new TwigFunction('get_post_type_archive_link', 'get_post_type_archive_link'));
        $twig->addFunction(new TwigFunction('add_query_arg', 'add_query_arg'));
        $twig->addFunction(new TwigFunction('wp_strip_all_tags', 'wp_strip_all_tags'));
        $twig->addFunction(new TwigFunction('yoast_breadcrumb', 'yoast_breadcrumb'));
        $twig->addFunction(new TwigFunction('sanitize_title', 'sanitize_title'));
        $twig->addFunction(new TwigFunction('wp_nonce_field', 'wp_nonce_field'));
        $twig->addFunction(new TwigFunction('admin_url', 'admin_url'));
        $twig->addFunction(new TwigFunction('wp_login_url', 'wp_login_url'));
        $twig->addFunction(new TwigFunction('is_numeric', 'is_numeric'));
        $twig->addFunction(new TwigFunction('sanitize_email', 'sanitize_email'));
        $twig->addFunction(new TwigFunction('get_the_title', 'get_the_title'));
        $twig->addFunction(new TwigFunction('reverse_wpautop', function ($s) {
            if (empty($s)) {
                return '';
            }
            //remove any new lines already in there
            $s = str_replace("\n", "", $s);
            //remove all <p>
            $s = str_replace("<p>", "", $s);
            //replace <br /> with \n
            $s = str_replace(array("<br />", "<br>", "<br/>"), "\n", $s);
            //replace </p> with \n\n
            $s = str_replace("</p>", "<br>", $s);

            return $s;
        }));
        $twig->addFunction(new TwigFunction('getKeyIndex', function ($value, $array) {
            return array_search($value, $array);
        }));
        $twig->addFunction(new TwigFunction('getCurrentURL', function () {
            return URLHelper::get_current_url();
        }));
        $twig->addFunction(new TwigFunction('getYoutubeID', function ($url = "") {
            return Helpers\getYoutubeID($url);
        }));
        $twig->addFunction(new TwigFunction('getSvg', function (string $svg) {
            return Helpers\getSvg($svg);
        }));
        $twig->addFunction(new TwigFunction('phoneMask', function (string $phone) {
            return Helpers\phoneMask($phone);
        }));
        $twig->addFunction(new TwigFunction('getGmapsUrl', function (string $address) {
            return Helpers\getGmapsUrl($address);
        }));
        $twig->addFunction(new TwigFunction('getEventDates', function (array $dates) {
            return Event::getEventDates($dates);
        }));
        $twig->addFunction(new TwigFunction('getFonts', function (string $url, string $name): string {
            return Helpers\getFonts($url, $name);
        }));
        $twig->addFunction(new TwigFunction('getImageInstagram', function (string $url): string {
            return Instagram::getImage($url);
        }));
        $twig->addFunction(new TwigFunction('generateId', function (int $length = 8): string {
            return substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
        }));
        $twig->addFilter(new TwigFilter('float', function ($value) {
            return (float)$value;
        }));
        $twig->addFilter(new TwigFilter('int', function ($value) {
            return (int)$value;
        }));

        if (defined('TWIG_DEBUG') && TWIG_DEBUG) {
            /** @var $twig Twig_Environment */
            $twig->addExtension(new CommentedIncludeExtension());
        }

        // Add globally the macros of project
        $twig->addGlobal('macros', $twig->loadTemplate('commons/macros.twig'));
        $twig->addGlobal('imageSizes', wp_get_registered_image_subsizes());

        return $twig;
    }

    public static function checkForTimber()
    {
        if (!class_exists('Timber')) {
            echo 'Timber is disabled';
            exit;
        }

        Timber::$dirname = 'templates';
    }
}
