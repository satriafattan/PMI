<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPemesanan;

class RiwayatController extends Controller
{
    public function index()
    {
        $items = RiwayatPemesanan::latest()->paginate(20);
        return view('admin.riwayat.index', compact('items'));
    }
}