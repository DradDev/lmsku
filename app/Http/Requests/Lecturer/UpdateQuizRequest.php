<?php

namespace App\Http\Requests\Lecturer;

use App\Models\Course;
use App\Models\CourseOffering;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        $courseParam = $this->route('course');
        $courseObj = is_numeric($courseParam)
            ? (CourseOffering::with('academicTerm')->find($courseParam) ?? Course::findOrFail($courseParam))
            : $courseParam;

        $lecturerId = $courseObj->lecturer_id ?? ($courseObj->user_id ?? null);
        if ($lecturerId !== Auth::id() && !Auth::user()->isAdmin()) {
            throw new HttpResponseException(abort(403, 'Kamu tidak memiliki akses ke course ini.'));
        }

        if ($courseObj instanceof CourseOffering && $courseObj->academicTerm && !$courseObj->academicTerm->is_active) {
            return false;
        }

        return true;
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            redirect()->back()->with('error', 'Semester untuk kelas ini telah non-aktif / ditutup. Perubahan kuis ditolak (Read-Only).')
        );
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'time_limit' => ['nullable', 'integer', 'min:1'],
            'max_attempts' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_unlimited' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}
