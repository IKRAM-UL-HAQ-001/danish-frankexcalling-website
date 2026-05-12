<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;
use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use App\Services\EncryptionService;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Daily OTP generation and email distribution
 */
Schedule::call(function () {
    $encryptionService = new EncryptionService();
    $emails = User::all()->pluck('email');
    
    foreach ($emails as $email) {
        $rawOtp = (string) random_int(100000, 999999);
        $hashedOtp = Hash::make($rawOtp);
        $expiration = Carbon::now()->addHours(2);
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::error("Invalid email during scheduled OTP: {$email}");
            continue;
        }

        Otp::create([
            'otp' => $hashedOtp,
            'otp_exp' => $expiration,
        ]);

        try {
            // Note: We send the RAW OTP to the user, but store the HASHED version
            Mail::to($email)->send(new OtpMail($rawOtp));
            Log::info("OTP email sent successfully to {$email}");
        } catch (\Exception $e) {
            Log::error("Failed to send OTP email to {$email}: " . $e->getMessage());
        }
    }
})->dailyAt('09:15');

/**
 * Session cleanup and IP Reset
 */
Schedule::call(function () {
    File::cleanDirectory(storage_path('framework/sessions'));
    \App\Models\IpAddress::truncate();
    Log::info('Sessions and IP addresses cleared.');
})->dailyAt('22:15')->timezone('Asia/Kolkata');

Schedule::call(function () {
    File::cleanDirectory(storage_path('framework/sessions'));
    \App\Models\IpAddress::truncate();
    Log::info('Midnight sessions and IP addresses cleared.');
})->dailyAt('00:00')->timezone('Asia/Kolkata');

/**
 * Database Backup (Every 15 days)
 */
Schedule::call(function () {
    try {
        $fileName = 'frankcalling_backup_' . date('Y_m_d_His') . '.sql';
        $backupDir = storage_path('app/backups');
        $backupPath = $backupDir . DIRECTORY_SEPARATOR . $fileName;

        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $db   = config('database.connections.mysql.database');
        $user = config('database.connections.mysql.username');
        $pass = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg($user),
            escapeshellarg($pass),
            escapeshellarg($host),
            escapeshellarg($db),
            escapeshellarg($backupPath)
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception('mysqldump failed with exit code ' . $returnVar);
        }

        Log::info('Database backup successfully created: ' . $backupPath);

    } catch (\Exception $e) {
        Log::error('Database backup failed: ' . $e->getMessage());
    }
})->dailyAt('00:00')
  ->timezone('Asia/Kolkata')
  ->when(function () {
      return now()->dayOfYear % 15 === 0;
  });
