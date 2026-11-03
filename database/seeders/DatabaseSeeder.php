<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // akun demo, bisa langsung dipakai untuk mengecek aplikasi
        $user = User::updateOrCreate(
            ['email' => 'demo@tabungin.test'],
            [
                'name' => 'Muhammad Adit Syahputra',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $categories = [
            ['name' => 'Gaji', 'type' => 'income', 'color' => '#10b981', 'icon' => 'banknote'],
            ['name' => 'Freelance', 'type' => 'income', 'color' => '#0ea5e9', 'icon' => 'laptop'],
            ['name' => 'Makan & Minum', 'type' => 'expense', 'color' => '#f59e0b', 'icon' => 'utensils'],
            ['name' => 'Transportasi', 'type' => 'expense', 'color' => '#6366f1', 'icon' => 'car'],
            ['name' => 'Belanja', 'type' => 'expense', 'color' => '#ec4899', 'icon' => 'shopping-bag'],
            ['name' => 'Tagihan', 'type' => 'expense', 'color' => '#ef4444', 'icon' => 'receipt'],
            ['name' => 'Hiburan', 'type' => 'expense', 'color' => '#8b5cf6', 'icon' => 'film'],
        ];

        $byName = [];
        foreach ($categories as $data) {
            $byName[$data['name']] = $user->categories()->updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        // bersihkan transaksi lama agar seeder bisa dijalankan ulang
        $user->transactions()->delete();

        $months = [
            ['offset' => 5, 'income' => 7_500_000, 'expense' => 4_100_000],
            ['offset' => 4, 'income' => 7_500_000, 'expense' => 5_250_000],
            ['offset' => 3, 'income' => 8_200_000, 'expense' => 4_680_000],
            ['offset' => 2, 'income' => 7_500_000, 'expense' => 5_940_000],
            ['offset' => 1, 'income' => 9_100_000, 'expense' => 4_320_000],
            ['offset' => 0, 'income' => 7_900_000, 'expense' => 3_180_000],
        ];

        $expenseSplit = [
            'Makan & Minum' => 0.38,
            'Transportasi' => 0.16,
            'Belanja' => 0.20,
            'Tagihan' => 0.18,
            'Hiburan' => 0.08,
        ];

        foreach ($months as $m) {
            $month = now()->subMonths($m['offset']);

            // gaji masuk di awal bulan
            $this->createTrx($user, $byName['Gaji'], 'income', $m['income'] * 0.85, 'Gaji bulanan', $month->copy()->day(min(1, $month->daysInMonth)));
            $this->createTrx($user, $byName['Freelance'], 'income', $m['income'] * 0.15, 'Proyek freelance', $month->copy()->day(min(2, $month->daysInMonth)));

            $maxDay = $m['offset'] === 0 ? now()->day : $month->daysInMonth;

            foreach ($expenseSplit as $catName => $ratio) {
                $count = match ($catName) {
                    'Makan & Minum' => 4,
                    'Transportasi' => 2,
                    'Belanja' => 2,
                    default => 1,
                };

                $amount = round($m['expense'] * $ratio / $count);

                for ($i = 0; $i < $count; $i++) {
                    $day = max(1, min($maxDay, (int) round($maxDay * (($i + 0.5) / $count)) + (($i % 2) * 2 - 1)));

                    $this->createTrx(
                        $user,
                        $byName[$catName],
                        'expense',
                        $amount,
                        $this->noteFor($catName),
                        $month->copy()->day($day)
                    );
                }
            }
        }

        // target tabungan + riwayat setoran
        $user->savingsGoals()->delete();

        $goals = [
            ['name' => 'Dana Darurat', 'description' => 'Enam bulan biaya hidup untuk jaga-jaga.', 'target' => 20_000_000, 'saved' => 15_600_000, 'color' => '#10b981', 'deadline' => now()->addMonths(8)],
            ['name' => 'Liburan Bali', 'description' => 'Trip bareng teman akhir tahun.', 'target' => 6_000_000, 'saved' => 2_500_000, 'color' => '#6366f1', 'deadline' => now()->addMonths(4)],
            ['name' => 'Laptop Baru', 'description' => 'Pengganti laptop yang sudah lemot.', 'target' => 12_000_000, 'saved' => 1_200_000, 'color' => '#f59e0b', 'deadline' => now()->addMonths(10)],
        ];

        foreach ($goals as $g) {
            $goal = $user->savingsGoals()->create([
                'name' => $g['name'],
                'description' => $g['description'],
                'target_amount' => $g['target'],
                'saved_amount' => 0,
                'deadline' => $g['deadline'],
                'status' => 'active',
                'color' => $g['color'],
            ]);

            // pecah total tabungan menjadi beberapa setoran bulanan
            $parts = 6;
            $perPart = intdiv((int) $g['saved'], $parts);

            for ($i = $parts; $i >= 1; $i--) {
                // setoran terakhir menyerap sisa pembulatan agar totalnya pas
                $amount = $i === 1 ? (int) $g['saved'] - ($perPart * ($parts - 1)) : $perPart;

                $goal->contributions()->create([
                    'amount' => $amount,
                    'note' => 'Setoran rutin',
                    'date' => now()->subMonths($i)->day(min(5, now()->subMonths($i)->daysInMonth)),
                ]);
            }

            $goal->update(['saved_amount' => $goal->contributions()->sum('amount')]);
        }
    }

    private function createTrx(User $user, Category $category, string $type, float $amount, string $note, \DateTimeInterface $date): void
    {
        $user->transactions()->create([
            'category_id' => $category->id,
            'type' => $type,
            'amount' => round($amount),
            'note' => $note,
            'date' => $date->format('Y-m-d'),
        ]);
    }

    private function noteFor(string $category): string
    {
        return match ($category) {
            'Makan & Minum' => collect(['Makan siang', 'Kopi sore', 'Belanja bulanan dapur', 'Makan malam di luar'])->random(),
            'Transportasi' => collect(['Bensin motor', 'Ojek online', 'Servis motor'])->random(),
            'Belanja' => collect(['Belanja online', 'Perlengkapan kamar', 'Pakaian'])->random(),
            'Tagihan' => collect(['Listrik & air', 'Paket data internet', 'Langganan musik'])->random(),
            'Hiburan' => collect(['Nonton bioskop', 'Game top-up', 'Konser mini'])->random(),
            default => 'Transaksi',
        };
    }
}
