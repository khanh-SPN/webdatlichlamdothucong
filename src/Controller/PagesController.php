<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;

class PagesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        // PAGE PUBLIC
        $this->Authentication->allowUnauthenticated([
            'display',
            'login',
            'register',
            'forgotPassword',
            'resetPassword'
        ]);
    }

    /**
     * ================= DISPLAY STATIC =================
     */
    public function display(string ...$path): ?Response
    {
        if (empty($path)) {
            return $this->redirect('/');
        }

        
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }

        $page = $path[0] ?? null;
        $subpage = $path[1] ?? null;

        $this->set(compact('page', 'subpage'));

        // ================= LOAD DATA  =================
        switch ($page) {

            case 'about':
                $company = $this->fetchTable('CompanyInfos')->find()->first();
                $this->set(compact('company'));
                break;

            case 'visit':
                $company = $this->fetchTable('CompanyInfos')->find()->first();
                $this->set(compact('company'));
                break;

            case 'workshops':
                $workshops = $this->fetchTable('Workshops')->find()->all();
                $workshopIds = $this->buildWorkshopIds($workshops);
                $this->set(compact('workshopIds', 'workshops'));
                break;

            case 'home':
             
                $faqsTable = $this->fetchTable('Faqs');
                $featuredFaqs = $faqsTable->find()
                    ->orderBy(['display_order' => 'ASC'])
                    ->limit(6)
                    ->all();

                $this->set(compact('featuredFaqs'));
                break;

            case 'faqs':

                $faqsTable = $this->fetchTable('Faqs');

                $faqs = $faqsTable->find()
                    ->where(['category IS NOT' => null])
                    ->orderBy([
                        'category' => 'ASC',
                        'display_order' => 'ASC'
                    ])
                    ->all();

                // Group FAQs by category
                $groupedFaqs = [];

                foreach ($faqs as $faq) {
                    $question = strtolower(trim((string)($faq->question ?? '')));
                    $isChildrenWorkshopQuestion = str_contains($question, 'workshop') && (
                        str_contains($question, 'children') || str_contains($question, 'child')
                    );
                    if ($isChildrenWorkshopQuestion) {
                        $answer = (string)($faq->answer ?? '');
                        $advice = "\n\nParental advice: Children must be supervised by a parent/guardian at all times. "
                            . "Some activities involve warm wax, sharp tools, and breakable materials, so we recommend closed-toe shoes "
                            . "and following the instructor’s safety instructions closely. If your child has sensitivities (e.g. fragrances), "
                            . "please contact us before booking so we can advise the best session.";
                        if ($answer !== '' && !str_contains($answer, 'Parental advice:')) {
                            $faq->answer = rtrim($answer) . $advice;
                        }
                    }
                    $groupedFaqs[$faq->category][] = $faq;
                }

                // groupedFaqs for template
                $this->set(compact('groupedFaqs'));

                break;

            case 'booking':
                return $this->redirect([
                    'controller' => 'Bookings',
                    'action' => 'add'
                ]);

            case 'login':
            case 'register':
            case 'forgotPassword':
            case 'resetPassword':
                return $this->redirect([
                    'controller' => 'Users',
                    'action' => $page
                ]);
        }

        // ================= RENDER =================
        try {
            return $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }

    /**
     * ================= LOGIN UI =================
     */
    public function login()
    {
        // Delegate to UsersController for profile
        return $this->redirect([
            'controller' => 'Users',
            'action' => 'login'
        ]);
    }

    /**
     * ================= REGISTER UI =================
     */
    public function register()
    {
        return $this->redirect([
            'controller' => 'Users',
            'action' => 'register'
        ]);
    }

    /**
     * ================= FORGOT PASSWORD =================
     */
    public function forgotPassword()
    {
        return $this->redirect([
            'controller' => 'Users',
            'action' => 'forgotPassword'
        ]);
    }

    /**
     * ================= RESET PASSWORD =================
     */
    public function resetPassword($token = null)
    {
        return $this->redirect([
            'controller' => 'Users',
            'action' => 'resetPassword',
            $token
        ]);
    }

    /**
     * Map catalogue workshop keys to workshop ids by matching workshop name/type text.
     *
     * @param iterable<\App\Model\Entity\Workshop> $workshops
     * @return array<string, int|null>
     */
    private function buildWorkshopIds(iterable $workshops): array
    {
        $out = [
            'candle' => null,
            'pottery' => null,
            'knitting' => null,
        ];

        foreach ($workshops as $workshop) {
            $blob = strtolower(trim(
                (string)($workshop->workshop_type ?? '') . ' ' . (string)($workshop->workshop_name ?? '')
            ));

            if ($out['candle'] === null && (str_contains($blob, 'candle') || str_contains($blob, 'wax'))) {
                $out['candle'] = (int)$workshop->id;
            }
            if ($out['pottery'] === null && (str_contains($blob, 'pottery') || str_contains($blob, 'clay')
                || str_contains($blob, 'wheel') || str_contains($blob, 'ceramic'))) {
                $out['pottery'] = (int)$workshop->id;
            }
            if ($out['knitting'] === null && str_contains($blob, 'knit')) {
                $out['knitting'] = (int)$workshop->id;
            }
        }

        return $out;
    }
}