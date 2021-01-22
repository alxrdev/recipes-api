<?php

namespace Tests\Unit\Rules;

use App\Rules\StepsRule;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StepsRuleTest extends TestCase
{
    /** @test */
    public function should_fail_when_an_invalid_steps_json_is_sended()
    {
        $validator = Validator::make([
            'steps' => '[{"position":"","imag":"step1"},{"position":2,"image":"step2","content":"this is the step 2"}]'
        ],
        [
            'steps' => ['required', 'json', new StepsRule]
        ]);

        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function should_not_fail_when_a_valid_steps_json_is_sended()
    {
        $validator = Validator::make([
            'steps' => '[{"position":1,"image":"step1","content": "this is the step 1"},{"position":2,"image":"step2","content":"this is the step 2"}]'
        ],
        [
            'steps' => ['required', 'json', new StepsRule]
        ]);

        $this->assertFalse($validator->fails());
    }
}