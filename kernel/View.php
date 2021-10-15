<?php

/**
 * Class View
 * contains page Render,Caching,Template Engine
 * and routing bootstraps.
 */
abstract class View
{
    /**
     * Holds the layout's container to be used
     * by stop() method for including footer file.
     * e.g. @stop()
     */
    private static $layout = null;


    /**
     * This will help layout files receive
     * available variables from the originally rendered page
     */
    private static $variables = null;


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
     * Extract the $_ variable to create new variables
     * from array keys
     *
     * @param $template
     * @param $_
     * @param $layout_var
     * @return view
     */
    public static function render($template, $_ = null, $layout_var = null)
    {
        if (!is_null($_)) {
            extract($_);
            if (is_null(self::$variables)) {
                self::$variables = $_;
            } else {
                self::$variables[] = $_;
            }
        }

        if (!is_null($layout_var)) {
            extract($layout_var);
        }

        $root_dir = str_replace('\\', '/', __DIR__);
        $root_dir = str_replace('kernel', '', $root_dir);
        $root_dir = rtrim($root_dir, '/');

        if (preg_match('/(.*)(html|php|htm)/i', $template)) {
            $filename = $root_dir . VIEWS_PATH . $template;
        } else {
            $filename = $root_dir . VIEWS_PATH . ltrim($template, '/') . self::$postfix;
        }

        $cachedFile = storage_path() . "/cache/" . md5($filename);

        if (CACHED_VIEWS == true) {
            if (file_exists($cachedFile)) {
                return eval(' ?>' . self::evaluate($cachedFile) . '<?php ');
            } else {
                $file = fopen($cachedFile, 'x');
                fwrite($file, self::evaluate($filename));
                return eval(' ?>' . self::evaluate($cachedFile) . '<?php ');
            }
        } else {
            try {
                return eval(' ?>' . self::evaluate($filename) . '<?php ');
            } catch (ParseError $e) {
                print "<b>Parse Error:</b> {$e->getMessage()} in <b>$filename</b> on line <b>{$e->getLine()}</b>";
            }
        }
    }


    /**
     * This is supposed to evaluate(locate and replace)
     * custom tags and generate cache for views.
     *
     * @param $filename
     * @return eval()
     **/
    private static function evaluate($filename)
    {
        if (!file_exists($filename)):
            trigger_error("File does not exist '{$filename}'", E_USER_ERROR);
        endif;

        $___ = file_get_contents($filename);
        $___ = preg_replace('/\@extend\((.*)\)/', '<?php View::extend($1) ?>', $___);
        $___ = preg_replace('/\@stop\((.*)\)/', '<?php View::stop($1) ?>', $___);
        $___ = preg_replace('/\@render\((.*)\)/', '<?php View::render($1) ?>', $___);
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
        $___ = preg_replace('/\\\{\\\!/', '{!', $___);
        $___ = preg_replace('/\\\!\\\}/', '!}', $___);
        $___ = preg_replace('/\{if (.*)\}/', '<?php if ($1) : ?>', $___);
        $___ = preg_replace('/\{elseif (.*)\}/', '<?php elseif ($1) : ?>', $___);
        $___ = preg_replace('/\{else\}/', '<?php else : ?>', $___);
        $___ = preg_replace('/\{endif\}/', '<?php endif ?>', $___);
        $___ = preg_replace('/\{for (.*)\}/', '<?php for ($1) : ?>', $___);
        $___ = preg_replace('/\{endfor\}/', '<?php endfor ?>', $___);
        $___ = preg_replace('/\{do\}/', '<?php do { ?>', $___);
        $___ = preg_replace('/\{enddo (.*)\}/', '<?php } while($1) ?>', $___);
        $___ = preg_replace('/\{while (.*)\}/', '<?php while ($1) : ?>', $___);
        $___ = preg_replace('/\{endwhile\}/', '<?php endwhile ?>', $___);
        $___ = preg_replace('/\{foreach (.*)\}/', '<?php foreach ($1) : ?>', $___);
        $___ = preg_replace('/\{endforeach\}/', '<?php endforeach ?>', $___);

        return ($___);
    }


    /**
     * Extends a view
     *
     * @param $layout
     * @param $variables = null
     * @return self::render
     */
    public static function extend($layout, $variables = null)
    {
        $root_dir = str_replace('\\', '/', __DIR__);
        $root_dir = str_replace('kernel', '', $root_dir);
        $root_dir = rtrim($root_dir, '/');

        if (is_dir($root_dir . VIEWS_PATH . $layout)) {
            self::$layout = $layout;
            $filename = trim($layout, '/') . "/header.php";
        } else {
            if (preg_match('/(.*)(html|php|htm)/i', $layout)) {
                $filename = ltrim($layout, '/');
            } else {
                $filename = ltrim($layout, '/') . self::$postfix;
            }
        }

        return self::render($filename, self::$variables, $variables);
    }


    /**
     * Require a footer file.
     *
     * @param $footer
     * @param null $variables
     * @return self::render
     */
    public static function stop($footer = null, $variables = null)
    {
        $root_dir = str_replace('\\', '/', __DIR__);
        $root_dir = str_replace('kernel', '', $root_dir);
        $root_dir = rtrim($root_dir, '/');

        $view_dir = $root_dir . VIEWS_PATH;

        if (is_null($footer) && !is_null(self::$layout)) {
            if (file_exists($view_dir . self::$layout . "/footer.php")) {
                $footer = self::$layout . "/footer.php";
            } else {
                trigger_error($view_dir . " does not contain any footer file.", E_USER_WARNING);
            }
        } else {
            if (is_file($view_dir . $footer)) {
                $footer = trim($footer, '/');
            } elseif (is_dir($view_dir . $footer)) {
                if (file_exists($view_dir . $footer . "/footer.php")) {
                    $footer = $footer . "/footer.php";
                } else {
                    trigger_error($view_dir . " does not contain any footer file.", E_USER_WARNING);
                }
            }
        }

        return self::render($footer, self::$variables, $variables);
    }


    /**
     * Include a file.
     * you cannot use custom tags with this.
     *
     * @param $template
     * @return self::render
     */
    public static function get($template, $vars = null)
    {
        if (!is_null($vars)) {
            extract($vars);
        }
        $root_dir = str_replace('\\', '/', __DIR__);
        $root_dir = str_replace('kernel', '', $root_dir);
        $root_dir = rtrim($root_dir, '/');

        if (preg_match('/(.*)(html|php|htm)/i', $template)) {
            $filename = $root_dir . VIEWS_PATH . $template;
        } else {
            $filename = $root_dir . VIEWS_PATH . ltrim($template, '/') . self::$postfix;
        }

        return include("$filename");
    }
}