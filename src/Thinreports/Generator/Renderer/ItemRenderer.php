<?php

/*
 * This file is part of the Thinreports PHP package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Thinreports\Generator\Renderer;

use Thinreports\Item;

/**
 * @access private
 */
class ItemRenderer extends AbstractRenderer
{
    /**
     * @param Item\AbstractItem $item
     */
    public function render(Item\AbstractItem $item)
    {

        if (!$this->isRenderable($item)) {
            return;
        }
        switch (true) {
            case $item instanceof Item\TextBlockItem:
                $this->renderTextBlockItem($item);
                break;
            case $item instanceof Item\ImageBlockItem:
                $this->renderImageBlockItem($item);
                break;
            case $item instanceof Item\PageNumberItem:
                $this->renderPageNumberItem($item);
                break;
            default:
                $this->renderBasicItem($item);
                break;
        }
    }

    /**
     * @param Item\AbstractItem $item
     * @return boolean
     */
    public function isRenderable(Item\AbstractItem $item)
    {
        if (!$item->isVisible()) {
            return false;
        }


        switch (true) {
            case $item instanceof Item\TextBlockItem:
                return $item->hasReference() || $item->isPresent();
                break;
            case $item instanceof Item\ImageBlockItem:
                return $item->isPresent();
                break;
            case $item instanceof Item\PageNumberItem:
                $page = $item->getParent();
                return $page->isCountable() && $item->isForReport();
                break;
            default:

                return true;
                break;
        }
    }

    /**
     * @param Item\BasicItem $item
     */
    public function renderBasicItem(Item\BasicItem $item)
    {

        switch (true) {
            case $item->isImage():
                $this->renderImageItem($item);
                break;
            case $item->isText():
                $this->renderTextItem($item);
                break;
            case $item->isRect():
                $this->renderRectItem($item);
            case $item->isEllipse():
                $this->renderEllipseItem($item);
                break;
            case $item->isLine():
                $this->renderLineItem($item);
                break;
        }
    }

    /**
     * @param Item\TextBlockItem $item
     */
    public function renderTextBlockItem(Item\TextBlockItem $item)
    {
        $format = $item->getFormat();


        $bounds = $item->getBounds();
        $styles = $this->buildTextStyles($item->exportStyles());

        $styles['valign'] = $item->getStyle('valign');

        if (isset($format['overflow']) && $format['overflow'] !== '') {
            $format['overflow'] = $format['overflow'];
        }
        if (isset($format['line-height-ratio']) && $format['line-height-ratio'] !== '') {
            $format['line_height'] = $format['line-height-ratio'];
        }

        if ($item->isMultiple()) {
            $this->doc->text->drawTextBox(
                $item->getRealValue(),
                $bounds['x'],
                $bounds['y'],
                $bounds['width'],
                $bounds['height'],
                $format
            );
        } else {
            $this->doc->text->drawText(
                $item->getRealValue(),
                $bounds['x'],
                $bounds['y'],
                $bounds['width'],
                $bounds['height'],
                $format
            );
        }
    }

    /**
     * @param Item\ImageBlockItem $item
     */
    public function renderImageBlockItem(Item\ImageBlockItem $item)
    {
        $bounds = $item->getBounds();
        $styles = $this->buildImageBoxItemStyles($item);

        $this->doc->graphics->drawImage(
            $item->getSource(),
            $bounds['x'],
            $bounds['y'],
            $bounds['width'],
            $bounds['height'],
            $styles
        );
    }

    /**
     * @param Item\PageNumberItem $item
     */
    public function renderPageNumberItem(Item\PageNumberItem $item)
    {
        $format = $item->getFormat();
        $bounds = $item->getBounds();

        $this->doc->text->drawText(
            $item->getFormattedPageNumber(),
            $bounds['x'],
            $bounds['y'],
            $bounds['width'],
            $bounds['height'],
            $this->buildTextStyles($item->exportStyles())
        );
    }

    /**
     * @param Item\BasicItem $item
     */
    public function renderImageItem(Item\BasicItem $item)
    {
        if ($item->isImage()){
            $dataImage = $item->getDataImage();
        }
        $this->doc->graphics->drawBase64Image(
            $this->extractBase64Data($dataImage),
            $dataImage['x'],
            $dataImage['y'],
            $dataImage['width'],
            $dataImage['height']
        );
    }

    /**
     * @param Item\BasicItem $item
     */
    public function renderTextItem(Item\BasicItem $item)
    {

        $format = $item->getFormat();
        $bounds = $item->getBounds();

        $this->doc->text->drawText(
            $item->getText(),
            $bounds['x'],
            $bounds['y'],
            $bounds['width'],
            $bounds['height'],
            $item->exportStyles()
        );
    }

    /**
     * @param Item\BasicItem $item
     */
    public function renderRectItem(Item\BasicItem $item)
    {
        $bounds = $item->getBounds();
        $attrs  = $item->exportStyles();

        $styles = $this->buildGraphicStyles($attrs);
        $styles['radius'] = $attrs['border-radius'];

       // print_r($this->buildGraphicStyles($item->exportStyles()));
        $this->doc->graphics->drawRect(
            $bounds['x'],
            $bounds['y'],
            $bounds['width'],
            $bounds['height'],
            $this->buildGraphicStyles($item->exportStyles())
        );
    }

    /**
     * @param Item\BasicItem $item
     */
    public function renderEllipseItem(Item\BasicItem $item)
    {
        $bounds = $item->getBounds();

        $this->doc->graphics->drawEllipse(
            $bounds['cx'],
            $bounds['cy'],
            $bounds['rx'],
            $bounds['ry'],
            $this->buildGraphicStyles($item->exportStyles())
        );
    }

    /**
     * @param Item\BasicItem $item
     */
    public function renderLineItem(Item\BasicItem $item)
    {
        $bounds = $item->getBounds();

        $this->doc->graphics->drawLine(
            $bounds['x1'],
            $bounds['y1'],
            $bounds['x2'],
            $bounds['y2'],
            $item->exportStyles()
        );
    }

    /**
     * @param Item\ImageBlockItem $item
     * @return array
     */
    public function buildImageBoxItemStyles(Item\ImageBlockItem $item)
    {
        $format = $item->getFormat();

        $align  = $format['x'] ?: 'left';
        $valign = $format['y'] ?: 'top';

        if ($format['y'] === 'center') {
            $valign = 'middle';
        }
        return array(
            'align'  => $align,
            'valign' => $valign
        );
    }
}
