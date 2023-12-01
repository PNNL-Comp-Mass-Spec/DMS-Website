<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        // Uncomment the following line to access the CodeIgniter welcome page at https://dmsdev.pnl.gov/home
        // return view('welcome_message');

        return redirect()->to(site_url('gen/index'));
    }
}
