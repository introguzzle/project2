<?php

namespace App\Http\Requests\Admin\Dashboard;

use Illuminate\Http\Request;

class TokenRequest extends Request
{
    public function getQuery(): bool
    {
        return (bool) $this->query('new');
    }
}
