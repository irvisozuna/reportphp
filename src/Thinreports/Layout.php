<?php

/*
 * This file is part of the Thinreports PHP package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thinreports;

use Thinreports\Exception;
use Thinreports\Item;
use Thinreports\Page\Page;

class Layout
{
    const FILE_EXT_NAME = 'tlf';
    const COMPATIBLE_VERSION_RANGE_START = '>= 0.8.0';
    const COMPATIBLE_VERSION_RANGE_END   = '< 0.9.2';

    /**
     * @param string $filename
     * @return self
     * @throws Exception\StandardException
     */
    static public function loadFile($filename)
    {
        if (pathinfo($filename, PATHINFO_EXTENSION) != self::FILE_EXT_NAME) {
            $filename .= '.' . self::FILE_EXT_NAME;
        }
        if (!file_exists($filename)) {
            throw new Exception\StandardException('Layout File Not Found', $filename);
        }
        return new self($filename, self::parse(file_get_contents($filename, true)));
    }

    /**
     * @access private
     *
     * @param string $file_content
     * @return array
     * @throws Exception\IncompatibleLayout
     */
    static public function parse($file_content)
    {

        $format = json_decode($file_content, true);


        if (!self::isCompatible($format['version'])) {
            $rules = array(
                self::COMPATIBLE_VERSION_RANGE_START,
                self::COMPATIBLE_VERSION_RANGE_END
            );
            throw new Exception\IncompatibleLayout($format['version'], $rules);
        }

        return array(
            'format' => $format,
            'item_formats' => $format["items"]
        );
    }

    /**
     * @access private
     *
     * @param string $layout_version
     * @return boolean
     */
    static public function isCompatible($layout_version)
    {
        $rules = array(
            self::COMPATIBLE_VERSION_RANGE_START,
            self::COMPATIBLE_VERSION_RANGE_END
        );

        foreach ($rules as $rule) {
            list($operator, $version) = explode(' ', $rule);

            if (!version_compare($layout_version, $version, $operator)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @access private
     *
     * @param array $item_format
     */
    static public function setPageNumberUniqueId(array &$item_format)
    {
        if (empty($item_format['id'])) {
            $item_format['id'] = Item\PageNumberItem::generateUniqueId();
        }
    }

    private $format;
    private $item_formats = array();
    private $identifier;

    /**
     * @param string $filename
     * @param array $deinition array('format' => array, 'item_formats' => array)
     */
    public function __construct($filename, array $definition)
    {
        $this->filename = $filename;
        $this->format = $definition['format'];
        $this->item_formats = $definition['item_formats'];
        $this->identifier = md5($this->format['version']);
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getReportTitle()
    {
        return $this->format['title'];
    }

    /**
     * @return string
     */
    public function getPagePaperType()
    {
        return $this->format['report']['paper-type'];
    }

    /**
     * @return string[]|null
     */
    public function getPageSize()
    {
        if ($this->isUserPaperType()) {
            $page = $this->format['config']['page'];
            return array($page['width'], $page['height']);
        } else {
            return null;
        }
    }

    /**
     * @return boolean
     */
    public function isPortraitPage()
    {
        return $this->format['report']['orientation'] === 'portrait';
    }

    /**
     * @return boolean
     */
    public function isUserPaperType()
    {
        return $this->format['report']['paper-type'] === 'user';
    }

    /**
     * @access private
     *
     * @return string
     */
    public function getLastVersion()
    {
        return $this->format['version'];
    }

    /**
     * @access private
     *
     * @param string $id
     * @return boolean
     */
    public function hasItem($id)
    {
        foreach ($this->item_formats as $item){
            if($item["id"]==$id)
                return true;
        }
        return false;
    }

    /**
     * @access private
     *
     * @param Page $owner
     * @param string $id
     * @return Item\AbstractItem
     * @throws Exception\StandardException
     */
    public function createItem(Page $owner, $id)
    {
        if (!$this->hasItem($id)) {
            throw new Exception\StandardException('Item Not Found', $id);
        }

        $item_format = $this->getItem($id);

        switch ($item_format['type']) {
            case 'text-block':
                return new Item\TextBlockItem($owner, $item_format);
                break;
            case 's-iblock':
                return new Item\ImageBlockItem($owner, $item_format);
                break;
            case 's-pageno';
                return new Item\PageNumberItem($owner, $item_format);
                break;
            default:
                return new Item\BasicItem($owner, $item_format);
                break;
        }
    }

    /**
     * @access private
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @access private
     *
     * @return array
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @access private
     *
     * @return array
     */
    public function getItemFormats()
    {
        //Retorno solamente los items que se leyeron
        $items = [];
        foreach ($this->item_formats as $item){
            if($item["type"])
                $items[$item["id"]] = $item;
        }
        return $items;
    }
    /**
     * @access private
     *
     * @return array
     */
    public function getItems()
    {

        $items = [];
        foreach ($this->item_formats["items"] as $item) {
            if($item["id"])
                $items[] = $item["id"];
        }
        return $items;
    }
    public function getItem($id){
        foreach ($this->getItemFormats() as $item){
            if($item["id"]==$id){
                return $item;
            }
        }
    }
    public function getMargin($position){

        switch ($position){
            case 'L':
                return $this->format["report"]["margin"][3];
                break;
            case 'T':
                return $this->format["report"]["margin"][0];
            case 'R':
                return $this->format["report"]["margin"][1];
            case 'B':
                return $this->format["report"]["margin"][2];
            default:
                return 0;
        }
    }
}
