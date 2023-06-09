<?php

class PolylangCheckboxType extends PolylangOptionType
{

    /**
     * @param $field
     *
     * @return string
     */
    public function getField($field)
    {
        return "{
            xtype:'xcheckbox',
            boxLabel: '" . $field['caption'] . "',
            checked: " . (filter_var($field['value'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0) . ",
            convertValue: function (v) {
                return (
                    v === '1' || v === true || v === 'true' ||
                    v === this.submitOnValue || String(v).toLowerCase() === 'on'
                );
            }
        }";
    }
}

return 'PolylangCheckboxType';