<?php

namespace App\Http\Controllers\Api\Company\FAQ;

use App\Http\Controllers\Api\Company\Illuminate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\FrequentlyAskedQuestions\{FindRequest};
use App\Http\Requests\Company\FrequentlyAskedQuestions\PopulateRequest;
use App\Http\Resources\FAQ\FAQResource;
use App\Models\FAQ\FrequentlyAskedQuestion;
use App\Repositories\FAQ\FrequentlyAskedQuestionRepository;

class FAQController extends Controller
{
    /**
     * Car repository container variable
     *
     * @var FrequentlyAskedQuestion
     */
    private $faqRepository;


    /**
     * Controller constructor method
     *
     * @param FrequentlyAskedQuestionRepository $frequentlyAskedQuestionRepository
     * @return void
     */
    public function __construct(
        FrequentlyAskedQuestionRepository $frequentlyAskedQuestionRepository
    )
    {
        $this->faqRepository = $frequentlyAskedQuestionRepository;
    }

    /**
     * Populate faqs
     *
     * @param PopulateRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function index(PopulateRequest $request)
    {
        $faqs = $this->faqRepository->all($request->options());
        $faqs = $this->faqRepository->paginate();
        $faqs = FAQResource::apiCollection($faqs);
        return response()->json(['faqs' => $faqs]);
    }

    /**
     * View faq
     *
     * @param FindRequest $request
     * @return Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $faq = $request->getFaq();
        $faq = new FAQResource($faq);
        return response()->json(['faq' => $faq]);
    }
}
