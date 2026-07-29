<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_admin', false)->pluck('id')->toArray();
        if (count($users) < 2) return;

        $reports = [
            ['reason' => 'Counterfeit / fake item',          'details' => 'The bag appears to be a replica, not authentic.',          'status' => 'open'],
            ['reason' => 'Item not as described',             'details' => 'Condition was much worse than listed. Several stains.',      'status' => 'open'],
            ['reason' => 'Inappropriate listing photos',      'details' => 'Background of photos contains inappropriate content.',      'status' => 'reviewed'],
            ['reason' => 'Harassment in messages',            'details' => 'User sent aggressive and threatening messages.',             'status' => 'actioned'],
            ['reason' => 'No-show / item never delivered',   'details' => 'Lender confirmed but never sent the item.',                 'status' => 'open'],
            ['reason' => 'Misleading price',                  'details' => 'Hidden fees added at checkout not shown in listing.',       'status' => 'dismissed'],
        ];

        foreach ($reports as $i => $report) {
            Report::updateOrCreate(
                ['reason' => $report['reason']],
                [
                    'reporter_id' => $users[$i % count($users)],
                    'reported_id' => $users[($i + 1) % count($users)],
                    'details'     => $report['details'],
                    'status'      => $report['status'],
                    'admin_notes' => $report['status'] === 'actioned' ? 'User warned and listing removed.' : null,
                    'created_at'  => now()->subDays(rand(1, 20)),
                ]
            );
        }
    }
}
