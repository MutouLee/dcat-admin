<?php

namespace Dcat\Admin\Form\Field;

class Icon extends Text
{
    public static $js = '@fontawesome-iconpicker';
    public static $css = '@fontawesome-iconpicker';
    protected $withoutIconPicker = false;

    public function render()
    {
        $this->addScript($this->withoutIconPicker);

        $this->prepend("<i class='fa {$this->value()}'>&nbsp;</i>")
            ->defaultAttribute('autocomplete', 'off')
            ->defaultAttribute('style', 'width: 160px;flex:none');

        return parent::render();
    }

    protected function addScript($withoutIconPicker)
    {
        $picker = '';
        if (!$withoutIconPicker) {
            $picker = <<<JS
                field.iconpicker({placement:'bottomLeft', animation: false});
JS;
        }
        $this->script = <<<JS
setTimeout(function () {
    var field = $('{$this->getElementClassSelector()}'),
        parent = field.parents('.form-field'),
        showIcon = function (icon) {
            parent.find('.input-group-prepend .input-group-text').html('<i class="' + icon + '"></i>');
        };

    $picker

    parent.find('.iconpicker-item').on('click', function (e) {
       showIcon($(this).find('i').attr('class'));
    });

    field.on('keyup', function (e) {
        var val = $(this).val();

        if (val.indexOf('fa-') !== -1) {
            if (val.indexOf('fa ') === -1) {
                val = 'fa ' + val;
            }
        }

        showIcon(val);
    })
}, 1);
JS;
    }

    public function withoutIconPicker(): Icon
    {
        $this->withoutIconPicker = true;
        return $this;
    }
}
