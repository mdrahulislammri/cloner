<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function getSiteSettings(): array
{
    $defaults = [
        'site_title' => 'GreenTech Boost',
        'hero_title' => 'বিশ্বাসযোগ্য সোশাল মিডিয়া বিক্রয়ের এজেন্সি',
        'hero_subtitle' => 'ফেসবুক, ইনস্টাগ্রাম ও ইউটিউব গ্রোথের জন্য প্রিমিয়াম ডিজিটাল সেবা।',
        'hero_badge' => '🟢 White + Green Premium Strategy',
        'hero_cta_primary' => 'এখনই অর্ডার',
        'hero_cta_secondary' => 'সার্ভিস দেখুন',
        'hero_image' => 'access/img/hero-illustration.svg',
        'about_title' => 'আমরা কেন আপনার জন্য বেস্ট টিম',
        'about_description' => 'আমরা শুধুমাত্র follower count না, আপনার brand trust, engagement এবং sales boost করার জন্য end-to-end কাজ করি। প্রতিটি campaign real niche audience target করে execute করা হয়।',
        'portfolio_title' => 'পোর্টফোলিও',
        'portfolio_subtitle' => 'আমাদের কিছু premium project showcase',
        'reviews_title' => 'ক্লায়েন্ট রিভিউ',
        'contact_title' => 'যোগাযোগ করুন',
        'contact_subtitle' => 'আপনার project নিয়ে কথা বলতে এখনই message দিন',
        'phone' => '+880-1000-000000',
        'email' => 'contact@example.com',
        'address' => 'ঢাকা, বাংলাদেশ',
        'whatsapp_number' => '8801000000000',
        'whatsapp_notice_title' => 'দ্রুত সাড়া পেতে হোয়াটসঅ্যাপে মেসেজ করুন',
        'whatsapp_notice_text' => 'আমরা সাধারণত ৪ ঘণ্টার মধ্যে রিপ্লাই দিই',
        'chat_widget_title' => 'দ্রুত যোগাযোগ করুন',
        'chat_widget_subtitle' => 'যেকোনো একটি মাধ্যম বেছে নিন',
        'chat_toggle_enabled' => '1',
        'chat_messenger_enabled' => '1',
        'chat_telegram_enabled' => '1',
        'chat_whatsapp_enabled' => '1',
        'chat_call_enabled' => '1',
        'messenger_url' => 'https://m.me/',
        'telegram_url' => 'https://t.me/',
        'call_number' => '+8801000000000',
        'toast_enabled' => '1',
        'toast_position' => 'top-right',
        'toast_duration_ms' => '4000',
        'footer_text' => 'Premium, user-friendly, SEO-ready social media growth platform.',
        'app_installed' => '0',
        'admin_ip_whitelist' => '127.0.0.1,::1',
    ];

    $rows = fetchAllRows('SELECT setting_key, setting_value FROM settings');
    foreach ($rows as $row) {
        $defaults[$row['setting_key']] = $row['setting_value'];
    }

    return $defaults;
}

function getServices(): array
{
    $rows = fetchAllRows('SELECT id, title, description, icon FROM services ORDER BY sort_order ASC, id DESC');
    if ($rows) {
        return $rows;
    }

    return [
        ['id' => 0, 'title' => 'ফেসবুক মার্কেটিং', 'description' => 'ব্র্যান্ড ভিজিবিলিটি বাড়াতে ডাটা-ড্রিভেন কৌশল।', 'icon' => '📘'],
        ['id' => 0, 'title' => 'ইনস্টাগ্রাম গ্রোথ', 'description' => 'অর্গানিক রিচ ও কনভার্সন ফোকাসড ক্যাম্পেইন।', 'icon' => '📸'],
        ['id' => 0, 'title' => 'ইউটিউব প্রোমোশন', 'description' => 'চ্যানেল গ্রোথ, ওয়াচটাইম ও সাবস্ক্রাইবার বুস্ট।', 'icon' => '▶️'],
    ];
}

function getPortfolio(): array
{
    $rows = fetchAllRows('SELECT id, title, category, image_path FROM portfolio_items ORDER BY id DESC LIMIT 8');
    if ($rows) {
        return $rows;
    }

    return [
        ['id' => 0, 'title' => 'Tech Campaign', 'category' => 'Marketing', 'image_path' => 'access/img/portfolio-placeholder.svg'],
        ['id' => 0, 'title' => 'Brand Launch', 'category' => 'Social', 'image_path' => 'access/img/hero-illustration.svg'],
    ];
}

function getTestimonials(): array
{
    $rows = fetchAllRows('SELECT id, client_name, designation, content, rating FROM testimonials ORDER BY id DESC');
    if ($rows) {
        return $rows;
    }

    return [
        ['id' => 0, 'client_name' => 'রাকিব হাসান', 'designation' => 'উদ্যোক্তা', 'content' => 'প্রফেশনাল সার্ভিস এবং রেজাল্ট অসাধারণ।', 'rating' => 5],
    ];
}
