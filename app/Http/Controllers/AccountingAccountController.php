<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountingAccount;

class AccountingAccountController extends Controller
{
    public function index()
    {
        $accounts = AccountingAccount::all();
        return view('accounting_account.index', compact('accounts'));
    }

    public function dayBook(){
        return view('day-book.index');
    }
    
    public function majorBook(){
        return view('major-book.index');
    }
}
