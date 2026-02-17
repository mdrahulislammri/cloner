<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function getSiteSettings(): array
{
    $defaults = [
        'site_title' => 'GreenTech Boost',
        'hero_title' => 'বিশ্বাসযোগ্য সোশাল মিডিয়া বিকাশের একমাত্র সলিউশন',
        'hero_subtitle' => 'ফেসবুক, ইনস্টাগ্রাম ও ইউটিউব গ্রোথের জন্য প্রিমিয়াম ডিজিটাল সেবা।',
        'phone' => '+880-1000-000000',
        'email' => 'contact@example.com',
        'address' => 'ঢাকা, বাংলাদেশ',
    ];

    $rows = fetchAllRows('SELECT setting_key, setting_value FROM settings');
    foreach ($rows as $row) {
        $defaults[$row['setting_key']] = $row['setting_value'];
    }

    return $defaults;
}

function getServices(): array
{
    $rows = fetchAllRows('SELECT title, description, icon FROM services ORDER BY sort_order ASC, id DESC');
    if ($rows) {
        return $rows;
    }

    return [
        ['title' => 'ফেসবুক মার্কেটিং', 'description' => 'ব্র্যান্ড ভিজিবিলিটি বাড়াতে ডাটা-ড্রিভেন কৌশল।', 'icon' => '📘'],
        ['title' => 'ইনস্টাগ্রাম গ্রোথ', 'description' => 'অর্গানিক রিচ ও কনভার্সন ফোকাসড ক্যাম্পেইন।', 'icon' => '📸'],
        ['title' => 'ইউটিউব প্রোমোশন', 'description' => 'চ্যানেল গ্রোথ, ওয়াচটাইম ও সাবস্ক্রাইবার বুস্ট।', 'icon' => '▶️'],
    ];
}

function getPortfolio(): array
{
    $rows = fetchAllRows('SELECT title, category, image_path FROM portfolio_items ORDER BY id DESC LIMIT 8');
    if ($rows) {
        return $rows;
    }

    return [
        ['title' => 'Tech Campaign', 'category' => 'Marketing', 'image_path' => 'access/img/portfolio-placeholder.svg'],
        ['title' => 'Brand Launch', 'category' => 'Social', 'image_path' => 'access/img/hero-illustration.svg'],
    ];
}

function getTestimonials(): array
{
    $rows = fetchAllRows('SELECT client_name, designation, content, rating FROM testimonials ORDER BY id DESC');
    if ($rows) {
        return $rows;
    }

    return [
        ['client_name' => 'রাকিব হাসান', 'designation' => 'উদ্যোক্তা', 'content' => 'প্রফেশনাল সার্ভিস এবং রেজাল্ট অসাধারণ।', 'rating' => 5],
    ];
}
