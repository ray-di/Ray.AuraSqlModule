<?php 
use Aura\Sql\ExtendedPdo;
use Ray\AuraSqlModule\Annotation\AuraSql;
use Ray\AuraSqlModule\Annotation\ReadOnlyConnection;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\AuraSqlModule\Annotation\WriteConnection;
/**
 * @AuraSql
 */
class Ray_AuraSqlModule_FakeModel_NDlpQxY extends Ray\AuraSqlModule\FakeModel implements Ray\Aop\WeavedInterface
{
    private $isIntercepting = true;
    public $bind;
    public $methodAnnotations = 'a:4:{s:5:"slave";a:1:{s:47:"Ray\\AuraSqlModule\\Annotation\\ReadOnlyConnection";O:47:"Ray\\AuraSqlModule\\Annotation\\ReadOnlyConnection":0:{}}s:6:"master";a:2:{s:44:"Ray\\AuraSqlModule\\Annotation\\WriteConnection";O:44:"Ray\\AuraSqlModule\\Annotation\\WriteConnection":0:{}s:42:"Ray\\AuraSqlModule\\Annotation\\Transactional";O:42:"Ray\\AuraSqlModule\\Annotation\\Transactional":1:{s:5:"value";a:1:{i:0;s:3:"pdo";}}}s:7:"dbError";a:2:{s:44:"Ray\\AuraSqlModule\\Annotation\\WriteConnection";O:44:"Ray\\AuraSqlModule\\Annotation\\WriteConnection":0:{}s:42:"Ray\\AuraSqlModule\\Annotation\\Transactional";O:42:"Ray\\AuraSqlModule\\Annotation\\Transactional":1:{s:5:"value";a:1:{i:0;s:3:"pdo";}}}s:4:"noDb";a:1:{s:42:"Ray\\AuraSqlModule\\Annotation\\Transactional";O:42:"Ray\\AuraSqlModule\\Annotation\\Transactional":1:{s:5:"value";a:1:{i:0;s:3:"pdo";}}}}';
    public $classAnnotations = 'a:1:{s:36:"Ray\\AuraSqlModule\\Annotation\\AuraSql";O:36:"Ray\\AuraSqlModule\\Annotation\\AuraSql":0:{}}';
    function read()
    {
        if ($this->isIntercepting === false) {
            $this->isIntercepting = true;
            return parent::read();
        }
        $this->isIntercepting = false;
        // invoke interceptor
        $result = (new \Ray\Aop\ReflectiveMethodInvocation($this, __FUNCTION__, array(), $this->bindings[__FUNCTION__]))->proceed();
        $this->isIntercepting = true;
        return $result;
    }
    function write()
    {
        if ($this->isIntercepting === false) {
            $this->isIntercepting = true;
            return parent::write();
        }
        $this->isIntercepting = false;
        // invoke interceptor
        $result = (new \Ray\Aop\ReflectiveMethodInvocation($this, __FUNCTION__, array(), $this->bindings[__FUNCTION__]))->proceed();
        $this->isIntercepting = true;
        return $result;
    }
    /**
     * @ReadOnlyConnection
     */
    function slave()
    {
        if ($this->isIntercepting === false) {
            $this->isIntercepting = true;
            return parent::slave();
        }
        $this->isIntercepting = false;
        // invoke interceptor
        $result = (new \Ray\Aop\ReflectiveMethodInvocation($this, __FUNCTION__, array(), $this->bindings[__FUNCTION__]))->proceed();
        $this->isIntercepting = true;
        return $result;
    }
    /**
     * @WriteConnection
     * @Transactional
     */
    function master()
    {
        if ($this->isIntercepting === false) {
            $this->isIntercepting = true;
            return parent::master();
        }
        $this->isIntercepting = false;
        // invoke interceptor
        $result = (new \Ray\Aop\ReflectiveMethodInvocation($this, __FUNCTION__, array(), $this->bindings[__FUNCTION__]))->proceed();
        $this->isIntercepting = true;
        return $result;
    }
    /**
     * @WriteConnection
     * @Transactional
     */
    function dbError()
    {
        if ($this->isIntercepting === false) {
            $this->isIntercepting = true;
            return parent::dbError();
        }
        $this->isIntercepting = false;
        // invoke interceptor
        $result = (new \Ray\Aop\ReflectiveMethodInvocation($this, __FUNCTION__, array(), $this->bindings[__FUNCTION__]))->proceed();
        $this->isIntercepting = true;
        return $result;
    }
    /**
     * @Transactional
     */
    function noDb()
    {
        if ($this->isIntercepting === false) {
            $this->isIntercepting = true;
            return parent::noDb();
        }
        $this->isIntercepting = false;
        // invoke interceptor
        $result = (new \Ray\Aop\ReflectiveMethodInvocation($this, __FUNCTION__, array(), $this->bindings[__FUNCTION__]))->proceed();
        $this->isIntercepting = true;
        return $result;
    }
}
