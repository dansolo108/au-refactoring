<?php

include_once dirname(__FILE__) . '/combobox.class.php';

class PolylangComboMultipleType extends PolylangComboboxType
{

    /**
     * @param $field
     *
     * @return string
     */
    public function getField($field)
    {
        if (isset($field['properties']['values'])) {
            $values = json_encode(array_chunk($field['properties']['values'], 1));
        } else {
            $values = '[]';
        }

        return "{
            xtype: 'minishop2-combo-options',
            allowAddNewData: false,
            pinList: true,
            mode: 'local',
            store: new Ext.data.SimpleStore({
                fields: ['value'],
                data: {$values}
            })
        }";
    }

    /**
     * @param $criteria
     *
     * @return array
     */
    public function getValue($criteria)
    {
        $result = array();

        $c = $this->xpdo->newQuery('PolylangProductOption', $criteria);
        $c->select('value');
        $c->where(array('value:!=' => ''));
        if ($c->prepare() && $c->stmt->execute()) {
            if (!$result = $c->stmt->fetchAll(PDO::FETCH_ASSOC)) {
                $result = array();
            }
        }

        return $result;
    }

    /**
     * @param $criteria
     *
     * @return array
     */
    public function getRowValue($criteria)
    {
        $result = array();

        $rows = $this->getValue($criteria);
        foreach ($rows as $row) {
            $result[] = $row['value'];
        }

        return $result;
    }
}

return 'PolylangComboMultipleType';