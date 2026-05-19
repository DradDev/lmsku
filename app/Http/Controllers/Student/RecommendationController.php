<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Project;
use App\Models\RecommendationResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RecommendationController extends Controller
{
    public function index(): View
    {
        $recommendationResults = RecommendationResult::query()
            ->where('user_id', Auth::id())
            ->orderBy('rank')
            ->latest('snapshot_date')
            ->get();

        $recommendations = $recommendationResults->map(function ($result) {
            $item = null;

            if ($result->item_type === 'course') {
                $item = Course::find($result->item_id);
            }

            if ($result->item_type === 'project') {
                $item = Project::find($result->item_id);
            }

            return [
                'result' => $result,
                'item' => $item,
            ];
        })->filter(function ($recommendation) {
            return $recommendation['item'] !== null;
        });

        return view('student.recommendations.index', compact('recommendations'));
    }
}
