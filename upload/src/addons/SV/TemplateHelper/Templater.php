<?php

namespace SV\TemplateHelper;

class Templater
{
    /**
     * @param \XF\Container          $container
     * @param \XF\Template\Templater $templater
     */
    public static function setup(/** @noinspection PhpUnusedParameterInspection */ \XF\Container $container, \XF\Template\Templater &$templater)
    {
        $hasClosures = \is_callable('\Closure::fromCallable');

        $func = 'SV\\TemplateHelper\\Templater::fnParseLessFunc';
        if ($hasClosures)
        {
            $func = \Closure::fromCallable($func);
        }
        $templater->addFunction('parse_less_func', $func);

        $func = 'SV\\TemplateHelper\\Templater::fnAbs';
        if ($hasClosures)
        {
            $func = \Closure::fromCallable($func);
        }
        $templater->addFunction('abs', $func);

        // work-around XF2.0.11 & earlier bug (load Less_Tree_Url so Less_Tree_URL maps as expected)
        // load the class anyway, as it is a single disk or opcode cache hit
        \class_exists('Less_Tree_Url');
    }

    /**
     * @param \XF\Template\Templater $templater
     * @param string                 $escape
     * @param string|int             $value
     * @return string
     * @throws \Exception
     */
    public static function fnAbs(/** @noinspection PhpUnusedParameterInspection */ $templater, &$escape, $value)
    {
        return \abs($value);
    }

    /**
     * @param \XF\Template\Templater $templater
     * @param string                 $escape
     * @param string                 $value
     * @return string
     * @throws \Exception
     */
    public static function fnParseLessFunc(/** @noinspection PhpUnusedParameterInspection */ $templater, &$escape, $value)
    {
        $app = \XF::app();
        /** @var \SV\TemplateHelper\XF\CssRenderer $renderer */
        $rendererClass = $app->extendClass('XF\CssRenderer');
        $renderer = new $rendererClass($app, $templater);
        $renderer->setStyle($templater->getStyle());

        return $renderer->parseLessColorFuncValue($value);
    }
}