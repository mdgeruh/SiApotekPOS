<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AppSetting;

class AppSettingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setting
                            {action : Action to perform (get, set, list, reset)}
                            {--key= : Setting key for get/set actions}
                            {--value= : Setting value for set action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage application settings';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Mengupdate pengaturan aplikasi...');

        $settings = AppSetting::first();

        if (!$settings) {
            $this->info('Membuat pengaturan aplikasi baru...');
            $settings = AppSetting::create([
                'app_name' => 'Apotek POS',
                'pharmacy_name' => 'Apotek Sejahtera',
                'address' => 'Jl. Contoh No. 123, Kota, Provinsi 12345',
                'phone' => '+62 812-3456-7890',
                'email' => 'info@apoteksejahtera.com',
                'website' => 'https://apoteksejahtera.com',
                'tax_number' => '12.345.678.9-123.000',
                'license_number' => 'SIPA-1234567890',
                'owner_name' => 'Dr. Nama Pemilik',
                'description' => 'Apotek terpercaya dengan pelayanan terbaik untuk kesehatan keluarga Anda.',
                'logo_path' => null,
                'favicon_path' => null,
                'currency' => 'IDR',
                'timezone' => 'Asia/Jakarta',
                'maintenance_mode' => false,
            ]);
        } else {
            $this->info('Mengupdate pengaturan aplikasi yang ada...');

            // Update existing settings with new fields if they don't exist
            if (!isset($settings->favicon_path)) {
                $settings->update(['favicon_path' => null]);
                $this->info('Field favicon_path ditambahkan.');
            }
        }

        $this->info('Pengaturan aplikasi berhasil diupdate!');
        $this->info('Nama Aplikasi: ' . $settings->app_name);
        $this->info('Nama Apotek: ' . $settings->pharmacy_name);
        $this->info('Alamat: ' . $settings->address);
        $this->info('Telepon: ' . $settings->phone);
        $this->info('Email: ' . $settings->email);
        $this->info('Website: ' . $settings->website);
        $this->info('NPWP: ' . $settings->tax_number);
        $this->info('No. Izin: ' . $settings->license_number);
        $this->info('Pemilik: ' . $settings->owner_name);
        $this->info('Mata Uang: ' . $settings->currency);
        $this->info('Timezone: ' . $settings->timezone);
        $this->info('Logo: ' . ($settings->logo_path ?: 'Tidak ada'));
        $this->info('Favicon: ' . ($settings->favicon_path ?: 'Tidak ada'));
        $this->info('Maintenance Mode: ' . ($settings->maintenance_mode ? 'Aktif' : 'Tidak Aktif'));

        return 0;
    }

    /**
     * Get a specific setting value
     */
    private function getSetting()
    {
        $key = $this->option('key');

        if (!$key) {
            $this->error('Please provide a key using --key option');
            return 1;
        }

        $value = AppSetting::getSetting($key);

        if ($value === null) {
            $this->warn("Setting '{$key}' not found");
            return 0;
        }

        $this->info("Setting '{$key}': {$value}");
        return 0;
    }

    /**
     * Set a setting value
     */
    private function setSetting()
    {
        $key = $this->option('key');
        $value = $this->option('value');

        if (!$key || !$value) {
            $this->error('Please provide both --key and --value options');
            return 1;
        }

        try {
            AppSetting::setSetting($key, $value);
            $this->info("Setting '{$key}' updated to '{$value}'");
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to update setting: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * List all settings
     */
    private function listSettings()
    {
        $settings = AppSetting::first();

        if (!$settings) {
            $this->warn('No settings found. Run app:setting reset to create default settings.');
            return 0;
        }

        $this->info('Application Settings:');
        $this->info('===================');

        $headers = ['Key', 'Value'];
        $rows = [];

        foreach ($settings->toArray() as $key => $value) {
            if (!in_array($key, ['id', 'created_at', 'updated_at'])) {
                $rows[] = [$key, is_bool($value) ? ($value ? 'Yes' : 'No') : $value];
            }
        }

        $this->table($headers, $rows);
        return 0;
    }

    /**
     * Reset settings to default values
     */
    private function resetSettings()
    {
        if ($this->confirm('This will reset all settings to default values. Continue?')) {
            try {
                // Delete existing settings
                AppSetting::truncate();

                // Create default settings
                AppSetting::create([
                    'app_name' => 'Apotek POS',
                    'pharmacy_name' => 'Apotek Sejahtera',
                    'address' => 'Jl. Contoh No. 123, Kota, Provinsi 12345',
                    'phone' => '+62 812-3456-7890',
                    'email' => 'info@apoteksejahtera.com',
                    'website' => 'https://apoteksejahtera.com',
                    'tax_number' => '12.345.678.9-123.000',
                    'license_number' => 'SIPA-1234567890',
                    'owner_name' => 'Dr. Nama Pemilik',
                    'description' => 'Apotek terpercaya dengan pelayanan terbaik untuk kesehatan keluarga Anda.',
                    'logo_path' => null,
                    'currency' => 'IDR',
                    'timezone' => 'Asia/Jakarta',
                    'maintenance_mode' => false,
                ]);

                $this->info('Settings reset to default values successfully!');
                return 0;
            } catch (\Exception $e) {
                $this->error("Failed to reset settings: " . $e->getMessage());
                return 1;
            }
        }

        $this->info('Operation cancelled.');
        return 0;
    }
}
