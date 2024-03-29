<?php


namespace App\Repositories\Contracts;


use Illuminate\Http\Request;

interface IUser
{
    public function findByEmail(string $email);
    public function search(Request $request);
}
