<?php

namespace SV\TemplateHelper\XF;



/**
 * Extends \XF\CssRenderer
 */
class CssRenderer extends XFCP_CssRenderer
{
    /**
     * When given a color value which may contain a mix of XF and Less functions test and return the parsed color.
     * If the provided Less is invalid, or no valid color found, returns null.
     *
     * @param $value
     * @return null|string
     */
    public function parseLessColorFuncValue($value)
    {
        $parser = $this->getFreshLessParser();

        $value = '@someVar: ' . $value . '; #test { color: @someVar; }';
        $value = $this->prepareLessForRendering($value);

        try
        {
            $css = $parser->parse($value)->getCss();
        }
        catch (\Exception $e)
        {
            return null;
        }

        preg_match('/color:\s*(.*?)\s*;\s*}$/si', $css, $matches);

        if (!$matches || !isset($matches[1]))
        {
            return null;
        }

        return $matches[1];
    }
}