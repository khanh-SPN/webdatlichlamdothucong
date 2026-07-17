<?php
declare(strict_types=1);

namespace App\Service;

use Cake\Routing\Router;

/**
 * Curated public-site index for the studio assistant (navigation + summaries).
 * URLs are resolved at runtime so links stay correct per environment.
 */
class WebsiteKnowledgeService
{
    /**
     * @return list<array{path: string, title: string, summary: string, keywords: string}>
     */
    private function definitions(): array
    {
        return [
            [
                'path' => '/',
                'title' => 'Home',
                'summary' => 'Landing page: studio story, atmosphere, testimonials, and quick paths into workshops and booking.',
                'keywords' => 'home landing start welcome intro candlecraft academy melbourne creative studio makers summarize overview site website pages navigate sitemap',
            ],
            [
                'path' => '/pages/about',
                'title' => 'About',
                'summary' => 'Who we are, our teaching approach, and what to expect from the workshop space.',
                'keywords' => 'about us story team mission values teachers instructors company studio space culture',
            ],
            [
                'path' => '/workshops',
                'title' => 'Workshops',
                'summary' => 'Overview of workshop types (pottery, knitting, candles), levels, and what each experience covers.',
                'keywords' => 'workshop workshops class classes pottery knitting candle candlemaking yarn ceramics bisque session types offerings catalog browse',
            ],
            [
                'path' => '/visit',
                'title' => 'Your visit',
                'summary' => 'Practical guide before you arrive: directions, what to bring, arrival time, studio etiquette, and how to get help.',
                'keywords' => 'visit arriving arrival parking directions address map when to arrive late etiquette what to bring wear clothes apron materials accessibility studio space first time beginner prepare',
            ],
            [
                'path' => '/faqs',
                'title' => 'FAQs',
                'summary' => 'Policies, what to bring, refunds, age, parking, payments, and other practical questions.',
                'keywords' => 'faq faqs help policy policies refund cancel reschedule bring wear parking payment stripe gift voucher',
            ],
            [
                'path' => '/booking',
                'title' => 'Booking',
                'summary' => 'Choose a workshop, pick a date from published sessions, and complete your reservation.',
                'keywords' => 'book booking reserve reservation enrol enroll sign up spot register calendar date payment confirm',
            ],
            [
                'path' => '/contact',
                'title' => 'Contact',
                'summary' => 'Send a message or enquiry to the studio team.',
                'keywords' => 'contact enquiry inquiry message email reach hello support talk staff',
            ],
            [
                'path' => '/pages/login',
                'title' => 'Sign in',
                'summary' => 'Log in to manage your profile and see your bookings in the assistant.',
                'keywords' => 'login log in sign in account password profile dashboard my bookings',
            ],
            [
                'path' => '/pages/register',
                'title' => 'Create account',
                'summary' => 'Register a new customer account for bookings and profile.',
                'keywords' => 'register sign up create account new user join',
            ],
        ];
    }

    /**
     * @return list<array{title: string, url: string, summary: string, keywords: string}>
     */
    public function pagesWithUrls(): array
    {
        $out = [];
        foreach ($this->definitions() as $row) {
            $out[] = [
                'title' => $row['title'],
                'url' => Router::url($row['path'], true),
                'summary' => $row['summary'],
                'keywords' => $row['keywords'],
            ];
        }

        return $out;
    }

    /**
     * Text block for the model: official list of public pages (for directions + summaries only).
     */
    public function buildSiteMapPromptBlock(int $maxChars = 8000): string
    {
        $lines = [
            'OFFICIAL SITE MAP (public pages only — use this to direct users and to summarize what exists where):',
            'When the user wants a summary, give 2–4 short bullets of what they will find, then name the best page(s) with full URLs from this list.',
            'Do not invent pages, forms, or policies that are not listed here or in FAQ/SCHEDULE.',
            '',
        ];
        $buf = implode("\n", $lines);
        foreach ($this->pagesWithUrls() as $p) {
            $chunk = '• ' . $p['title'] . "\n  URL: " . $p['url'] . "\n  " . $p['summary'] . "\n\n";
            if (strlen($buf . $chunk) > $maxChars) {
                break;
            }
            $buf .= $chunk;
        }

        return rtrim($buf);
    }

    /**
     * Keyword-style “search” over curated page metadata (fast, no crawler).
     *
     * @return list<array{label: string, url: string, hint: string}>
     */
    public function matchPages(string $message, int $limit = 5): array
    {
        $lower = mb_strtolower(trim($message));
        if ($lower === '') {
            return [];
        }
        $words = preg_split('/[^\p{L}\p{N}]+/u', $lower, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $words = array_values(array_unique(array_filter($words, fn ($w) => mb_strlen($w) > 2)));

        $scored = [];
        foreach ($this->pagesWithUrls() as $p) {
            $hay = mb_strtolower($p['title'] . ' ' . $p['summary'] . ' ' . $p['keywords']);
            $score = 0;
            foreach ($words as $w) {
                if (mb_strlen($w) < 3) {
                    continue;
                }
                if (str_contains($hay, $w)) {
                    $score += str_contains(mb_strtolower($p['keywords']), $w) ? 3 : 2;
                }
            }
            foreach (preg_split('/\s+/u', $p['keywords']) ?: [] as $kw) {
                $kw = trim(mb_strtolower((string)$kw));
                if ($kw !== '' && mb_strlen($kw) > 2 && str_contains($lower, $kw)) {
                    $score += 4;
                }
            }
            if ($score > 0) {
                $scored[] = ['score' => $score, 'p' => $p];
            }
        }

        usort($scored, fn ($a, $b) => $b['score'] <=> $a['score']);
        $out = [];
        foreach ($scored as $row) {
            $p = $row['p'];
            $out[] = [
                'label' => $p['title'],
                'url' => $p['url'],
                'hint' => $p['summary'],
            ];
            if (count($out) >= $limit) {
                break;
            }
        }

        return $out;
    }

    /**
     * @return list<array{label: string, url: string, hint: string}>
     */
    public function authDiscoveryLinks(): array
    {
        $out = [];
        foreach ($this->pagesWithUrls() as $p) {
            if (in_array($p['title'], ['Sign in', 'Create account'], true)) {
                $out[] = ['label' => $p['title'], 'url' => $p['url'], 'hint' => $p['summary']];
            }
        }

        return $out;
    }

    /**
     * @return list<array{label: string, url: string, hint: string}>
     */
    public function defaultDiscoveryLinks(): array
    {
        $pick = ['Home', 'Workshops', 'Your visit', 'FAQs', 'Booking', 'Contact'];
        $byTitle = [];
        foreach ($this->pagesWithUrls() as $p) {
            $byTitle[$p['title']] = $p;
        }
        $out = [];
        foreach ($pick as $t) {
            if (!isset($byTitle[$t])) {
                continue;
            }
            $p = $byTitle[$t];
            $out[] = ['label' => $p['title'], 'url' => $p['url'], 'hint' => $p['summary']];
        }

        return $out;
    }

    /**
     * @param list<array{label: string, url: string, hint: string}> $pages
     */
    public function formatLocalSiteGuide(array $pages): string
    {
        if ($pages === []) {
            $pages = $this->defaultDiscoveryLinks();
        }
        $lines = [
            'Here is a quick guide based on our public pages:',
            '',
        ];
        foreach ($pages as $p) {
            $lines[] = '• ' . $p['label'] . ' — ' . $p['hint'];
            $lines[] = '  ' . $p['url'];
            $lines[] = '';
        }
        $lines[] = 'Use the suggested links below to open a page.';

        return implode("\n", $lines);
    }
}
