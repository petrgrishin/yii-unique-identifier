<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

namespace PetrGrishin\UniqueIdentifier;


class UniqueIdentifier {

    /**
     * @return string
     */
    public static function className() {
        return get_called_class();
    }
}
