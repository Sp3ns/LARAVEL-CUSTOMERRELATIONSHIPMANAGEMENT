<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Customer;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with sample CRM data.
     */
    public function run(): void
    {
        // ── Users (3 roles) ──────────────────────────────────────
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@crm.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $manager = User::create([
            'name' => 'Spens Manager',
            'email' => 'manager@crm.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        $sales = User::create([
            'name' => 'Wilbert Sales',
            'email' => 'sales@crm.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
        ]);


        $users = [$admin, $manager, $sales];

        // ── Customers (15) ───────────────────────────────────────
        $customerData = [
            ['first_name' => 'Daniel', 'last_name' => 'Padilla', 'email' => 'daniel@crm.com', 'phone' => '555-0101', 'company' => 'Tech Corp', 'status' => 'active'],
            ['first_name' => 'Liza', 'last_name' => 'Soberano', 'email' => 'liza@crm.com', 'phone' => '555-0102', 'company' => 'Design Studio', 'status' => 'active'],
            ['first_name' => 'Enrique', 'last_name' => 'Gil', 'email' => 'enrique@crm.com', 'phone' => '555-0103', 'company' => 'Marketing Pro', 'status' => 'inactive'],
            ['first_name' => 'Regine', 'last_name' => 'Velasquez', 'email' => 'regine@crm.com', 'phone' => '555-0104', 'company' => 'Finance LLC', 'status' => 'active'],
            ['first_name' => 'Bongbong', 'last_name' => 'Marcos', 'email' => 'bongbong@crm.com', 'phone' => '555-0105', 'company' => 'Health Inc', 'status' => 'inactive'],
            ['first_name' => 'Rodrigo', 'last_name' => 'Duterte', 'email' => 'rodrigo@crm.com', 'phone' => '555-0106', 'company' => 'Auto Parts', 'status' => 'active'],
            ['first_name' => 'Grace', 'last_name' => 'Poe', 'email' => 'grace@crm.com', 'phone' => '555-0107', 'company' => 'Food Chain', 'status' => 'active'],

        ];

        $customers = [];
        foreach ($customerData as $i => $data) {
            $data['assigned_user_id'] = $users[$i % count($users)]->id;
            $customers[] = Customer::create($data);
        }

        // ── Leads (20) ───────────────────────────────────────────
        $leadData = [
            ['name' => 'Kylie Jenner', 'email' => 'kylie@crm.com', 'phone' => '555-1001', 'source' => 'Website', 'status' => 'New', 'priority' => 'High', 'expected_value' => 15000],
            ['name' => 'Jennie Kim', 'email' => 'jennie@crm.com', 'phone' => '555-1002', 'source' => 'Referral', 'status' => 'Contacted', 'priority' => 'Medium', 'expected_value' => 8000],
            ['name' => 'Tom Holland', 'email' => 'tom@crm.com', 'phone' => '555-1003', 'source' => 'Social Media', 'status' => 'Qualified', 'priority' => 'High', 'expected_value' => 25000],
            ['name' => 'Ninoy Aquino', 'email' => 'ninoy@crm.com', 'phone' => '555-1004', 'source' => 'Cold Call', 'status' => 'Proposal Sent', 'priority' => 'Medium', 'expected_value' => 12000],
            ['name' => 'Sarah Geronimo', 'email' => 'sarah@crm.com', 'phone' => '555-1005', 'source' => 'Email Campaign', 'status' => 'Negotiation', 'priority' => 'High', 'expected_value' => 35000],
            ['name' => 'Chris Evans', 'email' => 'chris@crm.com', 'phone' => '555-1006', 'source' => 'Website', 'status' => 'Won', 'priority' => 'High', 'expected_value' => 50000],
            ['name' => 'Diana Zubiri', 'email' => 'diana@crm.com', 'phone' => '555-1007', 'source' => 'Referral', 'status' => 'Lost', 'priority' => 'Low', 'expected_value' => 5000],

        ];

        $leads = [];
        foreach ($leadData as $i => $data) {
            $data['assigned_user_id'] = $users[$i % count($users)]->id;
            $leads[] = Lead::create($data);
        }

        // ── Activities (30) ──────────────────────────────────────
        $types = Activity::TYPES;
        $descriptions = [
            'call' => ['Discussed pricing options', 'Follow-up call regarding proposal', 'Initial discovery call', 'Checked on project status', 'Discussed renewal terms'],
            'email' => ['Sent product brochure', 'Followed up on quote', 'Shared case study', 'Sent meeting summary', 'Proposal email sent'],
            'meeting' => ['In-person demo presentation', 'Strategy planning session', 'Quarterly review meeting', 'Kick-off meeting', 'Contract negotiation meeting'],
            'note' => ['Client prefers monthly billing', 'Decision maker is the CFO', 'Budget approval expected next week', 'Competitor also pitching', 'Client interested in premium tier'],
        ];

        for ($i = 0; $i < 30; $i++) {
            $type = $types[$i % count($types)];
            $desc = $descriptions[$type][$i % count($descriptions[$type])];

            Activity::create([
                'type' => $type,
                'description' => $desc,
                'customer_id' => $i % 3 === 0 ? $customers[$i % count($customers)]->id : null,
                'lead_id' => $i % 3 !== 0 ? $leads[$i % count($leads)]->id : null,
                'user_id' => $users[$i % count($users)]->id,
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }

        // ── Follow-Ups (20) ─────────────────────────────────────
        $followUpTitles = [
            'Send updated proposal', 'Schedule demo call', 'Review contract terms', 'Check budget approval',
            'Follow up on pricing', 'Prepare presentation', 'Send reference contacts', 'Finalize deal',
            'Onboarding setup', 'Quarterly check-in', 'Renewal discussion', 'Product training',
            'Feedback collection', 'Upsell opportunity', 'Technical review', 'Partnership meeting',
            'Invoice follow-up', 'Support escalation', 'Feature request review', 'Annual review',
        ];

        $statuses = FollowUp::STATUSES;

        for ($i = 0; $i < 20; $i++) {
            $status = $statuses[$i % count($statuses)];
            $dueDate = match($status) {
                'Completed' => now()->subDays(rand(1, 15)),
                'Pending' => now()->addDays(rand(-3, 10)), // Some may be overdue
                'In Progress' => now()->addDays(rand(0, 5)),
            };

            FollowUp::create([
                'title' => $followUpTitles[$i],
                'description' => 'Follow-up task for CRM operations',
                'due_date' => $dueDate,
                'status' => $status,
                'customer_id' => $i % 4 === 0 ? $customers[$i % count($customers)]->id : null,
                'lead_id' => $i % 4 !== 0 ? $leads[$i % count($leads)]->id : null,
                'user_id' => $users[$i % count($users)]->id,
            ]);
        }
    }
}
