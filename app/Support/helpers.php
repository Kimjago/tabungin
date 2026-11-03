<?php

if (! function_exists('rupiah')) {
    /**
     * Format angka menjadi rupiah tanpa desimal.
     * rupiah(1500000) => "Rp1.500.000"
     */
    function rupiah(float|int|string|null $value, bool $withPrefix = true): string
    {
        $number = (float) ($value ?? 0);
        $formatted = number_format(abs($number), 0, ',', '.');

        if ($number < 0) {
            $formatted = '-'.$formatted;
        }

        return $withPrefix ? 'Rp'.$formatted : $formatted;
    }
}

if (! function_exists('rupiah_short')) {
    /**
     * Ringkas angka besar untuk kartu ringkasan.
     * 1500000 => "1,5 jt", 2500000000 => "2,5 M"
     */
    function rupiah_short(float|int|string|null $value): string
    {
        $n = (float) ($value ?? 0);
        $abs = abs($n);
        $sign = $n < 0 ? '-' : '';

        return match (true) {
            $abs >= 1_000_000_000 => $sign.'Rp'.rtrim(rtrim(number_format($abs / 1_000_000_000, 1, ',', '.'), '0'), ',').' M',
            $abs >= 1_000_000 => $sign.'Rp'.rtrim(rtrim(number_format($abs / 1_000_000, 1, ',', '.'), '0'), ',').' jt',
            $abs >= 1_000 => $sign.'Rp'.rtrim(rtrim(number_format($abs / 1_000, 1, ',', '.'), '0'), ',').' rb',
            default => $sign.'Rp'.number_format($abs, 0, ',', '.'),
        };
    }
}

if (! function_exists('progress_tone')) {
    /**
     * Nada warna progres tabungan agar status tidak hanya dibedakan warna.
     */
    function progress_tone(float $percent): string
    {
        return match (true) {
            $percent >= 100 => 'bg-brand-600',
            $percent >= 60 => 'bg-brand-500',
            $percent >= 30 => 'bg-amber-500',
            default => 'bg-ink-400',
        };
    }
}
