<?php

namespace App\Http\Controllers\Admin\Terms;

use App\Actions\Terms\AddTermVariantAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Terms\AddTermVariantRequest;
use App\Models\Term;
use App\Models\TermProposal;

class AdminTermsAddVariantPost extends Controller
{
    public function __invoke(AddTermVariantRequest $request)
    {
        $input = $request->validated();

        $termId = $input['term_id'];
        $termProposalIds = $input['term_proposal_ids'];

        $term = Term::query()
            ->where("id", $termId)
            ->firstOrFail();
        $termsAdded = "";
        foreach($termProposalIds as $i=>$termProposalId){
            $termProposal = TermProposal::query()
                ->where("id", $termProposalId)
                ->first();
            if($termProposal){
                $action = new AddTermVariantAction($term, $termProposal->title);
                $action->execute();
                $termsAdded .= "'$termProposal->title',";
                $termProposal->delete();
            }
        }
        $termsAdded = mb_substr($termsAdded, 0, (mb_strlen($termsAdded)-1));

        $request->session()->flash("success_add_term_variant", "К термину '$term->title' добавлено в синонимы: $termsAdded");
        return redirect("/admin/terms");
    }
}
