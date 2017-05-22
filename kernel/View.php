<?php

/**
 * Class View
 */
abstract class View
{
    /**
     * Holds the layout's container folder to be used
     * by end() method for including footer file.
     */
    private static $layout = null;


    /**
     * The default file type to be used
     * on all template files.
     * (defined on config/application.php)
     */
    private static $postfix = TEMPLATE_TYPE;


    /**
     * Formatting the content by replacing any
     * custom tags by equivalent code in PHP
     *
     * @param $template
     * @param $_
     * @return view
     */
    public static function render($template, $_ = null)
    {
        /**
         * Extract the $_ variable to create new variables
         * from array keys
         */
        if (!is_null($_)) {
            extract($_);
        }

        if (preg_match('/(.*)\.' . self::$postfix . '/i', $template)) {
            $filename = '../views/' . $template;
        } else {
            $filename = '../views/' . ltrim($template, '/') . self::$postfix;
        }

        if (!file_exists($filename)) {
            trigger_error("File does not exist '{$filename}'", E_USER_ERROR);
        }

        $___ = file_get_contents($filename);
        $___ = preg_replace('/\@extend\((.*)\)/', '<?php View::extend($1) ?>', $___);
        $___ = preg_replace('/\@stop\((.*)\)/', '<?php View::stop($1) ?>', $___);
        $___ = preg_replace('/\@parse\((.*)\)/', '<?php View::parse($1) ?>', $___);
        $___ = preg_replace('/\@get\((.*)\)/', '<?php View::get($1) ?>', $___);
        $___ = preg_replace('/\@import\((.*)\)/', '<?php use $1 ?>', $___);
        $___ = preg_replace('/\{\{(.*)\}\}$/', '<?php echo htmlentities($1) ?>', $___);
        $___ = preg_replace('/\{\{/', '<?php echo htmlentities($1', $___);
        $___ = preg_replace('/\}\}/', ') ?>', $___);
        $___ = preg_replace('/\{\!(.*)\!\}$/', '<?php echo $1 ?>', $___);
        $___ = preg_replace('/\{\!/', '<?php echo $1', $___);
        $___ = preg_replace('/\!\}/', ' ?>', $___);
        $___ = preg_replace('/\\\{\\\{/', '{{', $___);
        $___ = preg_replace('/\\\}\\\}/', '}}', $___);
        $___ = preg_replace('/\{if (.*)\}/', '<?php if ($1) : ?>', $___);
        $___ = preg_replace('/\{elseif (.*)\}/', '<?php elseif ($1) : ?>', $___);
        $___ = preg_replace('/\{else\}/', '<?php else : ?>', $___);
        $___ = preg_replace('/\{endif\}/', '<?php endif ?>', $___);
        $___ = preg_replace('/\{for (.*)\}/', '<?php for ($1) : ?>', $___);
        $___ = preg_replace('/\{endfor\}/', '<?php endfor ?>', $___);
        $___ = preg_replace('/\{do\}/', '<?php do { ?>', $___);
        $___ = preg_replace('/\{until (.*)\}/', '<?php } while($1) ?>', $___);
        $___ = preg_replace('/\{while (.*)\}/', '<?php while ($1) : ?>', $___);
        $___ = preg_replace('/\{endwhile\}/', '<?php endwhile ?>', $___);
        $___ = preg_replace('/\{foreach (.*)\}/', '<?php foreach ($1) : ?>', $___);
        $___ = preg_replace('/\{endforeach\}/', '<?php endforeach ?>', $___);

        return eval(' ?>' . $___ . '<?php ');
    }


    /**
     * Extends a view
     *
     * @param $layout
     * @param $var
     * @return self::render
     */
    public static function extend($layout, $var = [])
    {
        self::$layout = trim($layout, '/');

        return self::render(self::$layout . '/header', $var);
    }


    /**
     * Require a footer file.
     *
     * @param $var
     * @return self::render
     */
    public static function stop($var = [])
    {
        return self::render(self::$layout . '/footer', $var);
    }


    /**
     * Include a file and render custom tags.
     * similar to render
     *
     * @param $template
     * @param $var
     * @return self::render
     */
    public static function parse($template, $var = [])
    {
        return self::render($template, $var);
    }


    /**
     * Include a file.
     * you cannot use custom tags with this.
     *
     * @param $template
     * @return self::render
     */
    public static function get($template)
    {
        if (preg_match('/(.*)\.' . self::$postfix . '/i', $template)) {
            $filename = '../views/' . $template;
        } else {
            $filename = '../views/' . $template . self::$postfix;
        }

        return include("$filename");
    }
}