<?php
declare(strict_types=1);

namespace App\Controller;

class FaqsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
       
        $this->Authentication->allowUnauthenticated(['index']);
    }

    
    public function index()
    {
        $faqsTable = $this->fetchTable('Faqs');

        $faqs = $faqsTable->find()
            ->orderBy([
                'category' => 'ASC',
                'display_order' => 'ASC'
            ])
            ->all();

        // GROUP
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

        // Pass grouped FAQs only
        $this->set(compact('groupedFaqs'));
    }
}