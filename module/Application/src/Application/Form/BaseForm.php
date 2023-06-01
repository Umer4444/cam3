<?php
namespace Application\Form;

use Zend\Form\Form as Form;

/**
 * Class BaseForm
 * @package Application\Form
 */
class BaseForm extends Form
{
    /**
     * @return null|string
     */
    public function getMessagesHtmlList()
    {
        $messageHtml = null;
        $messagesArray = parent::getMessages();

        if (is_array($messagesArray)) {
            array_walk(
                $messagesArray,
                function (&$item1, $key) {
                    array_walk(
                        $item1,
                        function (&$item2, $key2) {
                            $item2 = '<li>' . $item2 . '</li>';
                        }
                    );
                    $item1 = implode("", $item1);
                }
            );
            $messageHtml = '<ul>' . implode("", $messagesArray) . '</ul>';
        }

        return $messageHtml;
    }

}