<?php

namespace App\Repository;

use App\Models\Quote;

class QuoteRepo implements IQuoteRepo
{
    function all(): array
    {
        $quotes = [
            new Quote([
                'quote' => 'sddsfdd',
                'autho' => 'fdfdf'
            ])
        ];
        return $quotes;
    }
}
