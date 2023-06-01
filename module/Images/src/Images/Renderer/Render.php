<?php
namespace Images\Renderer;

class Render extends \ZfTable\Render
{
    /**
     * Rentering table
     * @return string
     */
    public function renderTableAsHtml()
    {

        $tableConfig = $this->getTable()->getOptions();

        if ($tableConfig->getShowColumnFilters()) {
            $render .= $this->renderFilters();
        }

        $render .= $this->renderHead();
        $render = sprintf('<thead>%s</thead>', $render);
        $render .= $this->getTable()->getRow()->renderRows();
        $table = sprintf('<table %s>%s</table>', $this->getTable()->getAttributes(), $render);

        $view = new \Zend\View\Model\ViewModel();
        $view->setTemplate('container');

        $view->setVariable('table', $table);

        $view->setVariable('paginator', $this->renderPaginator());
        $view->setVariable('paramsWrap', $this->renderParamsWrap());
        $view->setVariable('itemCountPerPage', $this->getTable()->getParamAdapter()->getItemCountPerPage());
        $view->setVariable('quickSearch', $this->getTable()->getParamAdapter()->getQuickSearch());
        $view->setVariable('name', $tableConfig->getName());
        $view->setVariable('itemCountPerPageValues', $tableConfig->getValuesOfItemPerPage());
        $view->setVariable('showQuickSearch', $tableConfig->getShowQuickSearch());
        $view->setVariable('showPagination', $tableConfig->getShowPagination());
        $view->setVariable('showItemPerPage', $tableConfig->getShowItemPerPage());
        $view->setVariable('showExportToCSV', $tableConfig->getShowExportToCSV());

        return $this->getRenderer()->render($view);
    }
    /**
     * Rendering filters
     * @return string
     */
    public function renderFilters()
    {
        $headers = $this->getTable()->getHeaders();
        $render = '';

        foreach ($headers as $name => $params) {
            if (isset($params['filters'])) {
                $value = $this->getTable()->getParamAdapter()->getValueOfFilter($name);
                $id = 'zff_' . $name;
                if (is_string($params['filters'])) {
                    $element = new \Zend\Form\Element\Text($id);
                } else {
                    $element = new \Zend\Form\Element\Select($id);
                    $element->setValueOptions($params['filters']);
                }
                $element->setAttribute('class', 'filter form-control');
                $element->setValue($value);

                $render .= sprintf(
                    '<td' . (isset($params['filter-class']) ? ' class="' . $params['filter-class'] . '"' : '') . '>%s</td>',
                    $this->getRenderer()->formRow($element)
                );
            } else {

                $render .= '<td' . (isset($params['filter-class']) ?
                        ' class="' . $params['filter-class'] . '"'
                        : '') . '></td>';

            }
        }
        return sprintf('<tr>%s</tr>', $render);
    }

}


