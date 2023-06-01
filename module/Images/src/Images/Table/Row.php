<?php

namespace Images\Table;

use ZfTable\Row as BaseRow;

class Row extends BaseRow
{

    /**
     * Rendering all rows for table
     * @param  string $type html, json, array
     * @return string | array
     */
    public function renderRows($type = 'html')
    {
        if ($type == 'html') {
            return $this->renderRowHtmlCustom();
        } else {
            return parent::renderRows($type);
        }
    }

    /**
     * rendering row as a html
     * @return string
     */
    private function renderRowHtmlCustom()
    {
        $data = $this->getTable()->getData();
        $headers = $this->getTable()->getHeaders();

        if ($data->getCurrentItemCount()) {
            foreach ($data as $rowData) {
                $this->setActualRow($rowData);
                $rowRender = '';
                foreach ($headers as $name => $options) {
                    $rowRender .= $this->getTable()->getHeader($name)->getCell()->render('html');
                }
                foreach ($this->decorators as $decorator) {
                    $decorator->render('');
                }

                $render .= sprintf('<tr %s>%s</tr>', $this->getAttributes(), $rowRender);
                $this->clearVar();
            }
        } else {
            $render .= sprintf('<tr %s><td colspan="100">No results found.</td></tr>', $this->getAttributes());
        }
        return $render;
    }

}