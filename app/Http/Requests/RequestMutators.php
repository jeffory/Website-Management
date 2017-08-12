<?php

namespace App\Http\Requests;

trait RequestMutators
{
    protected $pre_validation_mutators = [
        Mutators\GroupInputData::class,
    ];

    /**
     * Override the validation creation method to allow modifications to the request before-hand.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        $this->preValidation();
        return parent::getValidatorInstance();
    }

    /**
     * Run pre-validation
     */
    public function preValidation()
    {
        foreach ($this->pre_validation_mutators as $mutator) {
            app($mutator)->handle($this->request, $this->mutator_options);
        }
    }
}