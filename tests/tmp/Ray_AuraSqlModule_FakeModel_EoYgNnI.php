<?php
use Aura\Sql\ExtendedPdo;
use Ray\AuraSqlModule\Annotation\AuraSql;
use Ray\AuraSqlModule\Annotation\ReadOnlyConnection;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\AuraSqlModule\Annotation\WriteConnection;/**
 * @AuraSql
 */
class Ray_AuraSqlModule_FakeModel_EoYgNnI extends Ray\AuraSqlModule\FakeModel implements Ray\Aop\WeavedInterface
{
    private $isIntercepting = true;
    public $bind;
    public function getPdo()
    {
        if (isset($this->bindings[__FUNCTION__]) === false) {
            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }
        if ($this->isIntercepting === false) {
            $this->isIntercepting = true;

            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }
        $this->isIntercepting = false;
        $invocationResult = (new \Ray\Aop\ReflectiveMethodInvocation($this, new \ReflectionMethod($this, __FUNCTION__), new \Ray\Aop\Arguments(func_get_args()), $this->bindings[__FUNCTION__]))->proceed();
        $this->isIntercepting = true;

        return $invocationResult;
    }
    public function read()
    {
        if (isset($this->bindings[__FUNCTION__]) === false) {
            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }
        if ($this->isIntercepting === false) {
            $this->isIntercepting = true;

            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }
        $this->isIntercepting = false;
        $invocationResult = (new \Ray\Aop\ReflectiveMethodInvocation($this, new \ReflectionMethod($this, __FUNCTION__), new \Ray\Aop\Arguments(func_get_args()), $this->bindings[__FUNCTION__]))->proceed();
        $this->isIntercepting = true;

        return $invocationResult;
    }
    public function write()
    {
        if (isset($this->bindings[__FUNCTION__]) === false) {
            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }
        if ($this->isIntercepting === false) {
            $this->isIntercepting = true;

            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }
        $this->isIntercepting = false;
        $invocationResult = (new \Ray\Aop\ReflectiveMethodInvocation($this, new \ReflectionMethod($this, __FUNCTION__), new \Ray\Aop\Arguments(func_get_args()), $this->bindings[__FUNCTION__]))->proceed();
        $this->isIntercepting = true;

        return $invocationResult;
    }
    /**
     * @ReadOnlyConnection
     */
    public function slave()
    {
        if (isset($this->bindings[__FUNCTION__]) === false) {
            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }
        if ($this->isIntercepting === false) {
            $this->isIntercepting = true;

            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }
        $this->isIntercepting = false;
        $invocationResult = (new \Ray\Aop\ReflectiveMethodInvocation($this, new \ReflectionMethod($this, __FUNCTION__), new \Ray\Aop\Arguments(func_get_args()), $this->bindings[__FUNCTION__]))->proceed();
        $this->isIntercepting = true;

        return $invocationResult;
    }
    /**
     * @WriteConnection
     * @Transactional
     */
    public function master()
    {
        if (isset($this->bindings[__FUNCTION__]) === false) {
            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }
        if ($this->isIntercepting === false) {
            $this->isIntercepting = true;

            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }
        $this->isIntercepting = false;
        $invocationResult = (new \Ray\Aop\ReflectiveMethodInvocation($this, new \ReflectionMethod($this, __FUNCTION__), new \Ray\Aop\Arguments(func_get_args()), $this->bindings[__FUNCTION__]))->proceed();
        $this->isIntercepting = true;

        return $invocationResult;
    }
    /**
     * @WriteConnection
     * @Transactional
     */
    public function dbError()
    {
        if (isset($this->bindings[__FUNCTION__]) === false) {
            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }
        if ($this->isIntercepting === false) {
            $this->isIntercepting = true;

            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }
        $this->isIntercepting = false;
        $invocationResult = (new \Ray\Aop\ReflectiveMethodInvocation($this, new \ReflectionMethod($this, __FUNCTION__), new \Ray\Aop\Arguments(func_get_args()), $this->bindings[__FUNCTION__]))->proceed();
        $this->isIntercepting = true;

        return $invocationResult;
    }
    /**
     * @Transactional
     */
    public function noDb()
    {
        if (isset($this->bindings[__FUNCTION__]) === false) {
            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }
        if ($this->isIntercepting === false) {
            $this->isIntercepting = true;

            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }
        $this->isIntercepting = false;
        $invocationResult = (new \Ray\Aop\ReflectiveMethodInvocation($this, new \ReflectionMethod($this, __FUNCTION__), new \Ray\Aop\Arguments(func_get_args()), $this->bindings[__FUNCTION__]))->proceed();
        $this->isIntercepting = true;

        return $invocationResult;
    }
}
