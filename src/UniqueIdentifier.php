<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

namespace PetrGrishin\UniqueIdentifier;


use Yii;

class UniqueIdentifier {
    private $identifier;
    private $iteratorSession;
    private $iterator = array();
    private $usesNames = array();

    /**
     * @return string
     */
    public static function className() {
        return get_called_class();
    }

    public static function create($prefix = null) {
        return new static($prefix);
    }

    public function __construct($prefix = null) {
        $this->identifier = $this->createIdentifier($prefix);
        $this->iteratorSession = $this->createIteratorSession();
    }

    public function getUniqueIdentifier($name) {
        $this->persistName($name);
        return sprintf('%s_%s_%s',
            $this->identifier,
            $this->iteratorSession,
            $name
        );
    }

    public function getIteratedUniqueIdentifier($name) {
        return sprintf('%s_%s_%s_%s',
            $this->identifier,
            $this->iteratorSession,
            $name,
            $this->createIterator($name)
        );
    }

    protected function createIdentifier($prefix = null) {
        return sprintf('%s%x', $prefix ? $prefix . '_' : null, microtime());
    }

    protected function createIteratorSession() {
        $iteratorSession = $this->getSession()->get(__CLASS__, 0);
        $iteratorSession++;
        $this->getSession()->add(__CLASS__, $iteratorSession);
        return $iteratorSession;
    }

    protected function createIterator($name) {
        if (!array_key_exists($name, $this->iterator)) {
            $this->iterator[$name] = 0;
        }
        $iterator = $this->iterator[$name];
        $this->iterator[$name]++;
        return $iterator;
    }

    protected function persistName($name) {
        if (false !== array_search($name, $this->usesNames)) {
            throw new \Exception(sprintf('Name `%s` has already been used', $name));
        }
        $this->usesNames[] = $name;
        return $this;
    }

    /**
     * @return \CHttpSession
     */
    protected function getSession() {
        return Yii::app()->getComponent('session');
    }
}
