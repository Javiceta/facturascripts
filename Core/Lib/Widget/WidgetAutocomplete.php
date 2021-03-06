<?php
/**
 * This file is part of FacturaScripts
 * Copyright (C) 2017-2018 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Core\Lib\Widget;

use FacturaScripts\Core\Lib\AssetManager;

/**
 * Description of WidgetAutocomplete
 *
 * @author Carlos García Gómez  <carlos@facturascripts.com>
 */
class WidgetAutocomplete extends WidgetSelect
{

    /**
     * Indicates whether a value should be selected strictly from the list
     * of values or whether the user can enter a new or different value
     * from the list. (Only for GridViews)
     *
     * @var bool
     */
    public $strict;

    /**
     *
     * @param object $model
     * @param string $title
     * @param string $description
     * @param string $titleurl
     *
     * @return string
     */
    public function edit($model, $title = '', $description = '', $titleurl = '')
    {
        $this->setValue($model);
        $descriptionHtml = empty($description) ? '' : '<small class="form-text text-muted">' . static::$i18n->trans($description) . '</small>';
        $inputHtml = $this->inputHtml();
        $labelHtml = '<label>' . $this->onclickHtml(static::$i18n->trans($title), $titleurl) . '</label>';

        if ('' === $this->value || null === $this->value) {
            return '<input type="hidden" name="' . $this->fieldname . '" value="' . $this->value . '"/>'
                . '<div class="form-group">'
                . $labelHtml
                . '<div class="input-group">'
                . '<div class="' . $this->css('input-group-prepend') . '">'
                . '<span class="input-group-text"><i class="fas fa-search fa-fw"></i></span>'
                . '</div>'
                . $inputHtml
                . '</div>'
                . $descriptionHtml
                . '</div>';
        }

        return '<input type="hidden" name="' . $this->fieldname . '" value="' . $this->value . '"/>'
            . '<div class="form-group">'
            . $labelHtml
            . '<div class="input-group">'
            . $this->inputGroupClearBtn()
            . $inputHtml
            . '</div>'
            . $descriptionHtml
            . '</div>';
    }

    /**
     * Adds assets to the asset manager.
     */
    protected function assets()
    {
        AssetManager::add('js', FS_ROUTE . '/Dinamic/Assets/JS/WidgetAutocomplete.js');
    }

    /**
     * 
     * @return string
     */
    protected function inputGroupClearBtn()
    {
        if ($this->readonly()) {
            return '<div class="' . $this->css('input-group-prepend') . '">'
                . '<span class="input-group-text"><i class="fas fa-search fa-fw"></i></span>'
                . '</div>';
        }

        return '<div class="' . $this->css('input-group-prepend') . '">'
            . '<button class="btn btn-warning" type="button" onclick="this.form.' . $this->fieldname . '.value = \'\'; this.form.submit();">'
            . '<i class="fas fa-times" aria-hidden="true"></i>'
            . '</button>'
            . '</div>';
    }

    /**
     *
     * @param string $type
     * @param string $extraClass
     *
     * @return string
     */
    protected function inputHtml($type = 'text', $extraClass = 'widget-autocomplete')
    {
        $cssFormControl = $this->css('form-control');
        $class = empty($extraClass) ? $cssFormControl : $cssFormControl . ' ' . $extraClass;
        $selected = static::$codeModel->getDescription($this->source, $this->fieldcode, $this->value, $this->fieldtitle);
        return '<input type="' . $type . '" value="' . $selected . '" class="' . $class . '" data-field="' . $this->fieldname
            . '" data-source="' . $this->source . '" data-fieldcode="' . $this->fieldcode . '" data-fieldtitle="' . $this->fieldtitle
            . '" autocomplete="off"' . $this->inputHtmlExtraParams() . '/>';
    }

    /**
     * Set datasource data and Load data from Model into values array
     */
    protected function setSourceData(array $child, bool $loadData = true)
    {
        // The values are filled in automatically by the view controller
        // according to the information entered by the user.
        parent::setSourceData($child, false);
        $this->strict = isset($child['strict']) ? ($child['strict'] == 'true') : true;
    }
}
