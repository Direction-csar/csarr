<?php

namespace App\Console\Commands;

use App\Services\SmsService;
use Illuminate\Console\Command;

class TestSmsCommand extends Command
{
    protected $signature = 'sms:test {phone} {message?}';
    protected $description = 'Test SMS sending via Orange API';

    public function handle(SmsService $smsService)
    {
        $phone = $this->argument('phone');
        $message = $this->argument('message') ?? 'Test SMS from CSAR platform';

        $this->info("Sending SMS to {$phone}...");

        $result = $smsService->sendSms($phone, $message);

        if ($result) {
            $this->info('SMS sent successfully!');
        } else {
            $this->error('Failed to send SMS. Check logs for details.');
        }
    }
} 