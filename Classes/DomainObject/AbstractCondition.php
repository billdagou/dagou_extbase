<?php
namespace Dagou\DagouExtbase\DomainObject;

use Dagou\DagouExtbase\Traits\Condition;

abstract class AbstractCondition implements ConditionInterface {
    use Condition;
}