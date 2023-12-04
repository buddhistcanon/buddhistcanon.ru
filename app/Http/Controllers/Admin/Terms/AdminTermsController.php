<?php

namespace App\Http\Controllers\Admin\Terms;

use App\Actions\Terms\StoreTermAction;
use App\Data\TermData;
use App\Models\Term;
use App\Models\TermProposal;
use Illuminate\Http\Request;

class AdminTermsController
{
    public function index()
    {
        $terms = Term::query()
            ->with('variants')
            ->orderBy('id', 'asc')
            ->get();
        $termProposals = TermProposal::query()
            ->orderBy('title', 'asc')
            ->get();

        return inertia('Admin/Terms/AdminTermsPage', [
            'terms' => $terms,
            'termProposals' => $termProposals,
        ]);
    }

    public function edit(int $id)
    {
        $term = Term::query()
            ->where('id', $id)
            ->with(['variants' => function ($query) {
                $query->orderBy('title', 'asc');
            }])
            ->firstOrFail();

        return inertia('Admin/Terms/AdminEditTermPage', ['term' => $term]);
    }

    public function store(Request $request)
    {
        $termData = TermData::from($request);

        StoreTermAction::make()->handle($termData);

        if ($termData->id) {
            return redirect("/admin/edit_term/$termData->id")->withSuccess('Термин сохранён.');
        }

        return redirect('/admin/terms')->with('success_add_term', "Термин $termData->title добавлен..");
    }
}
