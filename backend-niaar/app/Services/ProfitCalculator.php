<?php

namespace App\Services;


use App\Models\Inquiry;

class ProfitCalculator
{
    protected Inquiry $inquiry;

    public function setInquiry(Inquiry $inquiry)
    {
        $this->inquiry = $inquiry;

        return $this;
    }
}
