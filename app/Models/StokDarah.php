<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StokDarah extends Model
{
    protected $table = 'stok_darah';

    protected $fillable = [
        'code',
        'name',
        'a_stock',
        'ab_stock',
        'b_stock',
        'o_stock',
        'low_threshold',
        'critical_threshold',
    ];

    protected $casts = [
        'a_stock'            => 'integer',
        'ab_stock'           => 'integer',
        'b_stock'            => 'integer',
        'o_stock'            => 'integer',
        'low_threshold'      => 'integer',
        'critical_threshold' => 'integer',
    ];

    /**
     * Total semua stok (A+AB+B+O).
     */
    public function getTotalStockAttribute(): int
    {
        return (int) ($this->a_stock + $this->ab_stock + $this->b_stock + $this->o_stock);
    }

    /**
     * True jika ada salah satu golongan <= low_threshold (dan > critical).
     */
    public function getIsLowAttribute(): bool
    {
        $vals = [$this->a_stock, $this->ab_stock, $this->b_stock, $this->o_stock];
        // low = di bawah/ sama dengan low_threshold, tapi bukan kritis
        return collect($vals)->contains(function ($v) {
            return $v > 0 && $v <= $this->low_threshold && $v > $this->critical_threshold;
        });
    }

    /**
     * True jika ada salah satu golongan <= critical_threshold.
     */
    public function getIsCriticalAttribute(): bool
    {
        $vals = [$this->a_stock, $this->ab_stock, $this->b_stock, $this->o_stock];
        return collect($vals)->contains(fn($v) => $v > 0 && $v <= $this->critical_threshold);
    }

    /**
     * Ambil total stok untuk satu golongan (A/AB/B/O).
     * Contoh: $product->totalBy('A')
     */
    public function totalBy(string $blood): int
    {
        $key = strtolower($blood) . '_stock'; // a_stock, ab_stock, b_stock, o_stock
        if (! in_array($key, ['a_stock', 'ab_stock', 'b_stock', 'o_stock'])) {
            throw new \InvalidArgumentException('Golongan darah tidak valid: ' . $blood);
        }
        return (int) $this->{$key};
    }

    /**
     * Tambah stok untuk satu golongan.
     * Contoh: $product->addStock('AB', 5)
     */
    public function addStock(string $blood, int $qty): self
    {
        $key = strtolower($blood) . '_stock';
        if (! in_array($key, ['a_stock', 'ab_stock', 'b_stock', 'o_stock'])) {
            throw new \InvalidArgumentException('Golongan darah tidak valid: ' . $blood);
        }
        $this->{$key} = max(0, (int)$this->{$key} + (int)$qty);
        $this->save();

        return $this;
    }

    /**
     * Kurangi stok untuk satu golongan (dengan safety floor 0).
     * Contoh: $product->reduceStock('O', 2)
     */
    public function reduceStock(string $blood, int $qty): self
    {
        $key = strtolower($blood) . '_stock';
        if (! in_array($key, ['a_stock', 'ab_stock', 'b_stock', 'o_stock'])) {
            throw new \InvalidArgumentException('Golongan darah tidak valid: ' . $blood);
        }
        $this->{$key} = max(0, (int)$this->{$key} - (int)$qty);
        $this->save();

        return $this;
    }

    /* ============================
     *            SCOPES
     * ============================ */

    /**
     * Scope pencarian berdasarkan nama/kode produk.
     * Contoh: BloodProduct::search($q)->get();
     */
    public function scopeSearch(Builder $q, ?string $keyword): Builder
    {
        if (! $keyword) return $q;
        return $q->where(function ($s) use ($keyword) {
            $s->where('name', 'like', "%{$keyword}%")
                ->orWhere('code', 'like', "%{$keyword}%");
        });
    }

    /**
     * Produk yang memiliki stok menipis pada salah satu golongan.
     */
    public function scopeLow(Builder $q): Builder
    {
        return $q->where(function ($w) {
            $w->whereBetween('a_stock', [1, $this->low_threshold ?? 25])
                ->orWhereBetween('ab_stock', [1, $this->low_threshold ?? 25])
                ->orWhereBetween('b_stock', [1, $this->low_threshold ?? 25])
                ->orWhereBetween('o_stock', [1, $this->low_threshold ?? 25]);
        });
    }

    /**
     * Produk yang memiliki stok kritis pada salah satu golongan.
     */
    public function scopeCritical(Builder $q): Builder
    {
        return $q->where(function ($w) {
            $threshold = $this->critical_threshold ?? 10;
            $w->whereBetween('a_stock', [1, $threshold])
                ->orWhereBetween('ab_stock', [1, $threshold])
                ->orWhereBetween('b_stock', [1, $threshold])
                ->orWhereBetween('o_stock', [1, $threshold]);
        });
    }
}
