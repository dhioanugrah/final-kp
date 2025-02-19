<?php

namespace App\Filament\Resources\PrResource\Pages;

use App\Filament\Resources\PrResource;
use Filament\Resources\Pages\Page;
use App\Models\PrDetail;

class CekPengajuan extends Page
{
    protected static string $resource = PrResource::class;
    protected static string $view = 'filament.pages.cek-pengajuan';

    public $records;

    public function mount($record)
    {
        $this->records = PrDetail::where('pr_id', $record)->get();
    }

    public function getViewData(): array
    {
        return [
            'records' => $this->records,
        ];
    }
}
