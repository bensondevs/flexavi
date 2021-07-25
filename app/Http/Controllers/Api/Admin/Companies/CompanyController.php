<?php

namespace App\Http\Controllers\Api\Admin\Companies;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\CompanyRepository;

class CompanyController extends Controller
{
    private $company;

    public function __construct(CompanyRepository $company)
    {
        $this->company = $company;
    }
}
