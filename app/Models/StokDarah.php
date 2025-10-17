<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StokDarah extends Model
{
    protected $table = 'stok_darah';

    protected $fillable = [
        'code',
        'produk',
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

    /* ============================
     *      ACCESSORS & HELPERS
     * ============================ */

    /** Total semua stok (A+AB+B+O). */
    public function getTotalStockAttribute(): int
    {
        return (int) ($this->a_stock + $this->ab_stock + $this->b_stock + $this->o_stock);
    }

    /** True jika ada salah satu golongan <= low_threshold (dan > critical_threshold). */
    public function getIsLowAttribute(): bool
    {
        $vals = [$this->a_stock, $this->ab_stock, $this->b_stock, $this->o_stock];
        return collect($vals)->contains(function ($v) {
            return $v > 0 && $v <= (int)$this->low_threshold && $v > (int)$this->critical_threshold;
        });
    }

    /** True jika ada salah satu golongan <= critical_threshold. */
    public function getIsCriticalAttribute(): bool
    {
        $vals = [$this->a_stock, $this->ab_stock, $this->b_stock, $this->o_stock];
        return collect($vals)->contains(fn($v) => $v > 0 && $v <= (int)$this->critical_threshold);
    }

    /** Ambil total stok untuk satu golongan (A/AB/B/O). */
    public function totalBy(string $blood): int
    {
        $key = strtolower($blood) . '_stock'; // a_stock, ab_stock, b_stock, o_stock
        if (! in_array($key, ['a_stock', 'ab_stock', 'b_stock', 'o_stock'], true)) {
            throw new \InvalidArgumentException('Golongan darah tidak valid: ' . $blood);
        }
        return (int) $this->{$key};
    }

    /** Tambah stok untuk satu golongan. */
    public function addStock(string $blood, int $qty): self
    {
        $key = strtolower($blood) . '_stock';
        if (! in_array($key, ['a_stock', 'ab_stock', 'b_stock', 'o_stock'], true)) {
            throw new \InvalidArgumentException('Golongan darah tidak valid: ' . $blood);
        }
        $this->{$key} = max(0, (int)$this->{$key} + max(0, (int)$qty));
        $this->save();

        return $this;
    }

    /** Kurangi stok untuk satu golongan (safety floor 0). */
    public function reduceStock(string $blood, int $qty): self
    {
        $key = strtolower($blood) . '_stock';
        if (! in_array($key, ['a_stock', 'ab_stock', 'b_stock', 'o_stock'], true)) {
            throw new \InvalidArgumentException('Golongan darah tidak valid: ' . $blood);
        }
        $this->{$key} = max(0, (int)$this->{$key} - max(0, (int)$qty));
        $this->save();

        return $this;
    }

    /* ============================
     *            SCOPES
     * ============================ */

    /** Pencarian berdasarkan name/code. */
    public function scopeSearch(Builder $q, ?string $keyword): Builder
    {
        if (! $keyword) return $q;
        return $q->where(function (Builder $s) use ($keyword) {
            $s->where('name', 'like', "%{$keyword}%")
              ->orWhere('code', 'like', "%{$keyword}%");
        });
    }

    /**
     * Produk yang memiliki stok menipis pada salah satu golongan:
     *  (stok > 0) AND (stok <= low_threshold) AND (stok > critical_threshold)
     * Menggunakan whereColumn agar membandingkan terhadap kolom per-baris.
     */
    public function scopeLow(Builder $q): Builder
    {
        return $q->where(function (Builder $w) {
            $w->where(function (Builder $x) {
                $x->where('a_stock', '>', 0)
                  ->whereColumn('a_stock', '<=', 'low_threshold')
                  ->whereColumn('a_stock', '>', 'critical_threshold');
            })->orWhere(function (Builder $x) {
                $x->where('ab_stock', '>', 0)
                  ->whereColumn('ab_stock', '<=', 'low_threshold')
                  ->whereColumn('ab_stock', '>', 'critical_threshold');
            })->orWhere(function (Builder $x) {
                $x->where('b_stock', '>', 0)
                  ->whereColumn('b_stock', '<=', 'low_threshold')
                  ->whereColumn('b_stock', '>', 'critical_threshold');
            })->orWhere(function (Builder $x) {
                $x->where('o_stock', '>', 0)
                  ->whereColumn('o_stock', '<=', 'low_threshold')
                  ->whereColumn('o_stock', '>', 'critical_threshold');
            });
        });
    }

    /**
     * Produk yang memiliki stok kritis pada salah satu golongan:
     *  (stok > 0) AND (stok <= critical_threshold)
     */
    public function scopeCritical(Builder $q): Builder
    {
        return $q->where(function (Builder $w) {
            $w->where(function (Builder $x) {
                $x->where('a_stock', '>', 0)
                  ->whereColumn('a_stock', '<=', 'critical_threshold');
            })->orWhere(function (Builder $x) {
                $x->where('ab_stock', '>', 0)
                  ->whereColumn('ab_stock', '<=', 'critical_threshold');
            })->orWhere(function (Builder $x) {
                $x->where('b_stock', '>', 0)
                  ->whereColumn('b_stock', '<=', 'critical_threshold');
            })->orWhere(function (Builder $x) {
                $x->where('o_stock', '>', 0)
                  ->whereColumn('o_stock', '<=', 'critical_threshold');
            });
        });
    }

    /* ============================
     *        MUTATORS (opsional)
     * ============================ */

    public function setCodeAttribute($value): void
    {
        $val = trim((string)$value);
        $this->attributes['code'] = $val === '' ? null : strtoupper($val);
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = trim((string)$value);
    }
}
