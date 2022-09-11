<?php

namespace App\Http\Controllers\Admin\Terms;

use App\Http\Controllers\Controller;
use App\Models\Term;
use App\Models\TermProposal;

class AdminTermsGet extends Controller
{
    public function __invoke()
    {
        $terms = Term::query()
            ->with('variants')
            ->orderBy('id', 'asc')
            ->get();
        $termProposals = TermProposal::query()
            ->orderBy('title', 'asc')
            ->get();

        return inertia('Admin/Terms/AdminTermsPage', ['terms' => $terms, 'termProposals' => $termProposals]);
    }
}
