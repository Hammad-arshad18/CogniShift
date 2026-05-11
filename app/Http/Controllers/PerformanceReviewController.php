<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PerformanceReview;
use App\Services\AIService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PerformanceReviewController extends Controller
{
    public function index()
    {
        return Inertia::render('Reviews/Index', [
            'reviews' => PerformanceReview::with(['employee.user', 'reviewer'])->latest()->get(),
            'employees' => Employee::with('user')->get(),
        ]);
    }

    public function generate(Request $request, AIService $aiService)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'bullet_points' => 'required|string|min:10',
        ]);

        $employee = Employee::with('user')->findOrFail($validated['employee_id']);

        // Generate the review using OpenAI
        $aiGeneratedText = $aiService->generatePerformanceReview(
            $validated['bullet_points'],
            $employee->user->name
        );

        PerformanceReview::create([
            'employee_id' => $employee->id,
            'reviewer_id' => auth()->id(),
            'bullet_points' => $validated['bullet_points'],
            'ai_generated_review' => $aiGeneratedText,
            'status' => 'generated',
        ]);

        return redirect()->back()->with('message', 'AI Performance Review Generated Successfully.');
    }

    public function update(Request $request, PerformanceReview $review)
    {
        $validated = $request->validate([
            'ai_generated_review' => 'required|string',
            'status' => 'required|string',
        ]);

        $review->update($validated);

        return redirect()->back()->with('message', 'Review updated successfully.');
    }

    public function destroy(PerformanceReview $review)
    {
        $review->delete();
        return redirect()->back()->with('message', 'Review deleted successfully.');
    }
}
