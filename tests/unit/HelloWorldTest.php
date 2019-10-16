<?php

use app\modules\v2\operateDept\domain\aggregate\DesignCenterAggregate;
use Codeception\Test\Unit;
use yii\di\Instance;

class HelloWorldTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public $designCenter;


    protected function _before()
    {
        $this->designCenter = Instance::ensure(DesignCenterAggregate::class);
    }

    protected function _after()
    {
    }

    /**
     * @test
     */
    public function someFeature() : string
    {
        $this->assertInstanceOf(DesignCenterAggregate::class,$this->designCenter);
        return 'xxx';
    }

    /**
     * @test
     * @depends someFeature
     * @param string $params
     */
    public function otherFeature(string $params) : void
    {
        $this->assertEquals('xxx',$params);
    }



}