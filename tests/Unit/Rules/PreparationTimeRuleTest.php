<?php

namespace Tests\Unit\Rules;

use App\Rules\PreparationTimeRule;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PreparationTimeRuleTest extends TestCase
{
    /** @test */
    public function should_fail_when_an_invalid_preparation_time_is_sended()
    {
        $val1 = Validator::make([
            'preparation_time' => '00:00:'
        ],
        [
            'preparation_time' => ['required', 'string', new PreparationTimeRule]
        ]);

        $val2 = Validator::make([
            'preparation_time' => '00:00:60'
        ],
        [
            'preparation_time' => ['required', 'string', new PreparationTimeRule]
        ]);

        $this->assertTrue($val1->fails());
        $this->assertTrue($val2->fails());
    }

    /** @test */
    public function should_not_fail_when_a_valid_preparation_time_is_sended()
    {
        $val1 = Validator::make([
            'preparation_time' => '00:30:59'
        ],
        [
            'preparation_time' => ['required', 'string', new PreparationTimeRule]
        ]);

        $this->assertFalse($val1->fails());
    }
}