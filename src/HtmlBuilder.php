<?php
namespace Lorito\Html;

use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Htmlable;
use Collective\Html\HtmlBuilder as BaseHtmlBuilder;

class HtmlBuilder extends BaseHtmlBuilder
{
    /**
     * Generate a HTML element.
     *
     * @param  string  $tag
     * @param  mixed   $value
     * @param  array   $attributes
     *
     * @return \Illuminate\Contracts\Support\Htmlable
     */
    public function create($tag = 'div', $value = null, $attributes = [])
    {
        if (is_array($value)) {
            $attributes = $value;
            $value      = null;
        }

        $content = '<'.$tag.$this->attributes($attributes).'>';

        if (! is_null($value)) {
            $content .= $this->entities($value).'</'.$tag.'>';
        }

        return $this->toHtmlString($content);
    }

    /**
     * {@inheritdoc}
     */
    public function entities($value)
    {
        if ($value instanceof Htmlable) {
            return $value->toHtml();
        }

        return parent::entities($value);
    }

    /**
     * Create a new HTML expression instance are used to inject HTML.
     *
     * @param  string  $value
     *
     * @return \Illuminate\Contracts\Support\Htmlable
     */
    public function raw($value)
    {
        return $this->toHtmlString($value);
    }

    /**
     * Build a list of HTML attributes from one or two array and generate
     * HTML attributes.
     *
     * @param  array  $attributes
     * @param  array  $defaults
     *
     * @return string
     */
    public function attributable(array $attributes, array $defaults = [])
    {
        return $this->attributes($this->decorate($attributes, $defaults));
    }

    /**
     * Build a list of HTML attributes from one or two array.
     *
     * @param  array  $attributes
     * @param  array  $defaults
     *
     * @return array
     */
    public function decorate(array $attributes, array $defaults = [])
    {
        $class = $this->buildClassDecorate($attributes, $defaults);

        $attributes = array_merge($defaults, $attributes);

        empty($class) || $attributes['class'] = $class;

        return $attributes;
    }

    /**
     * Build class attribute from one or two array.
     *
     * @param  array  $attributes
     * @param  array  $defaults
     *
     * @return string
     */
    protected function buildClassDecorate(array $attributes, array $defaults = [])
    {
        // Special consideration to class, where we need to merge both string
        // from $attributes and $defaults, then take union of both.
        $default   = isset($defaults['class']) ? $defaults['class'] : '';
        $attribute = isset($attributes['class']) ? $attributes['class'] : '';

        $classes   = explode(' ', trim($default.' '.$attribute));
        $current   = array_unique($classes);
        $excludes  = [];

        foreach ($current as $c) {
            if (Str::startsWith($c, '!')) {
                $excludes[] = substr($c, 1);
                $excludes[] = $c;
            }
        }

        return implode(' ', array_diff($current, $excludes));
    }
}
