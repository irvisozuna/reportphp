<?php

/*
 * This file is part of the Thinreports PHP package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thinreports\Item;

use Thinreports\Page\Page;

abstract class AbstractItem
{
    protected $parent;
    protected $format;

    protected $is_visible;
    protected $style;

    /**
     * @param Page $parent
     * @param array $format
     */
    public function __construct(Page $parent, array $format)
    {
        $this->parent = $parent;
        $this->format = $format;
        $this->is_visible = ($format['display'])?true:false;
    }

    /**
     * @param boolean $visible
     * @return $this
     */
    public function setVisible($visible)
    {
        $this->is_visible = $visible;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isVisible()
    {
        return $this->is_visible;
    }

    /**
     * @return $this
     */
    public function hide()
    {
        $this->setVisible(false);
        return $this;
    }

    /**
     * @return $this
     */
    public function show()
    {
        $this->setVisible(true);
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->format['id'];
    }

    /**
     * @param string $name
     * @param mixed $style
     * @return $this
     * @throws Thinreports\Exception\StandardException
     * @throws Thinreports\Exception\UnavailableStyleValue
     */
    public function setStyle($name, $style)
    {
        $this->style->set($name, $style);
        return $this;
    }

    /**
     * @param array $styles
     * @return $this
     * @throws Thinreports\Exception\StandardException
     * @throws Thinreports\Exception\UnavailableStyleValue
     */
    public function setStyles(array $styles)
    {
        foreach ($styles as $name => $style) {
            $this->setStyle($name, $style);
        }
        return $this;
    }

    /**
     * @return mixed
     * @throws Thinreports\Exception\StandardException
     */
    public function getStyle($name)
    {
        return $this->style->get($name);
    }

    /**
     * @access private
     *
     * @return array
     */
    public function exportStyles()
    {
        return $this->style->export();
    }

    public function __clone()
    {
        $this->style = clone $this->style;
    }

    /**
     * @access private
     *
     * @return Page
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @access private
     *
     * @return array
     */
    public function getFormat()
    {
        return $this->format["style"];
    }
    /**
     * @access private
     *
     * @return array
     */
    public function getDataImage()
    {
        return $this->format;
    }
    /**
     * @access private
     *
     * @return string
     */
    public function getType()
    {
        return $this->format['type'];
    }
    /**
     * @access private
     *
     * @return string
     */
    public function getText()
    {
        return $this->format['texts'];
    }

    /**
     * @access private
     *
     * @param string $type_name
     * @return boolean
     */
    public function isTypeOf($type_name)
    {
        return $this->getType() === $type_name;
    }

    /**
     * @access private
     *
     * @return array
     */
    public function getSVGAttributes()
    {
        if ($this->format['type'] == "line"){
            $this->format['style']["x1"]= $this->format["x1"];
            $this->format['style']["y1"]= $this->format["y1"];
            $this->format['style']["x2"]= $this->format["x2"];
            $this->format['style']["y2"]= $this->format["y2"];
        }

        return $this->format['style'];
    }
    /**
     * @access private
     *
     * @param string $type_name
     * @return boolean
     */
    public function setImage($base64)
    {
        $this->format["data"] = array("base64"=>$base64);
        return $this->format;
    }
    /**
     * @access private
     *
     * @return array
     */
    public function getAttributesAll()
    {
        return $this->format;
    }

    /**
     * @access private
     *
     * @return array
     */
    abstract public function getBounds();
}
