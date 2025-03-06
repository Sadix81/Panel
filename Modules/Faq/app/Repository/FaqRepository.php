<?php

namespace Modules\Faq\app\Repository;

use Modules\Faq\Models\Faq;

class FaqRepository implements FaqRepositorynterface
{
    public function index()
    {
        $faqs = Faq::all();
        return $faqs;
    }

    public function store($request)
    {
        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer
        ]);
    }

    public function update($faq, $request)
    {
        $faq->update([
            'question' => $request->question ? $request->question : $faq->question,
            'answer' => $request->answer ? $request->answer : $faq->answer
        ]);
    }

    public function delete($faq)
    {
        $item = Faq::where('id' , $faq)->first();
        $item->delete();
    }
}
