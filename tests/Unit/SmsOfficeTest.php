<?php

namespace Nikajorjika\SmsOffice\Tests\Unit;

use Exception;
use Nikajorjika\SmsOffice\SmsOffice;
use Nikajorjika\SmsOffice\Tests\TestCase;


class SmsOfficeTest extends TestCase
{
    /**
     * Tests if class is setting up proper defaults values.
     *
     * @return void
     */
    public function test_if_class_is_properly_initialized()
    {
        $smsOffice = new SmsOffice();

        $this->assertCount(2, $smsOffice->getSupportedDrivers());
        $this->assertEmpty($smsOffice->getTo());
        $this->assertEmpty($smsOffice->getFrom());
        $this->assertEmpty($smsOffice->getMessage());
    }

    /**
     * Tests if wrong number throws exception
     *
     * @return void
     */
    public function test_if_wrong_number_format_throws_exception()
    {
        $smsOffice = new SmsOffice();
        try {
            $smsOffice->to('2995855737812');
        } catch (Exception $e) {
            $this->assertEquals(
                'Number you provided is not in a correct format!',
                $e->getMessage()
            );
        }
        try {
            $smsOffice->to('8557378');
        } catch (Exception $e) {
            $this->assertEquals(
                'Number you provided is not in a correct format!',
                $e->getMessage()
            );
        }
    }

    /**
     * Tests if number is formatted correctly
     *
     * @return void
     */
    public function test_if_number_if_formatted_correctly()
    {
        $smsOffice = new SmsOffice();
        $smsOffice->to(855737812);
        $this->assertEquals($smsOffice->getTo(), '995855737812');

        $smsOffice->to(995855737812);
        $this->assertEquals($smsOffice->getTo(), '995855737812');

        $smsOffice->to('855737812');
        $this->assertEquals($smsOffice->getTo(), '995855737812');

        $smsOffice->to('995855737812');
        $this->assertEquals($smsOffice->getTo(), '995855737812');

        $smsOffice->to('+995855737812');
        $this->assertEquals($smsOffice->getTo(), '995855737812');
    }

    public function test_if_message_is_set_up_corretly()
    {
        $smsOffice = new SmsOffice();
        $smsOffice->message('Testing messge functionality!');

        $this->assertEquals($smsOffice->getMessage(), 'Testing messge functionality!');
    }

    public function test_if_from_is_set_up_corretly()
    {
        $smsOffice = new SmsOffice();
        $smsOffice->from('NikaJorjika');

        $this->assertEquals($smsOffice->getFrom(), 'NikaJorjika');
    }
}
