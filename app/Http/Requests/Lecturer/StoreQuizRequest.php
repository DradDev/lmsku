<?php

namespace App\Http\Requests\Lecturer;

use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\MasterCourse;
use App\Models\Quiz;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        $courseParam = $this->route('course');
        $courseObj = is_numeric($courseParam)
            ? (CourseOffering::with('academicTerm')->find($courseParam) ?? Course::findOrFail($courseParam))
            : $courseParam;

        if ($courseObj instanceof CourseOffering && $courseObj->academicTerm && !$courseObj->academicTerm->is_active) {
            return false;
        }

        return true;
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            redirect()->back()->with('error', 'Semester untuk kelas ini telah non-aktif / ditutup. Tindakan ditolak (Read-Only).')
        );
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'time_limit' => ['nullable', 'integer', 'min:1'],
            'quiz_type' => ['required', Rule::in(['daily', 'weekly', 'final'])],
            'max_attempts' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_unlimited' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'target_scope' => ['nullable', Rule::in(['all', 'class'])],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $courseParam = $this->route('course');
            $courseObj = is_numeric($courseParam)
                ? (CourseOffering::find($courseParam) ?? Course::find($courseParam))
                : $courseParam;

            if (!$courseObj) {
                return;
            }

            $targetScope = $this->input('target_scope', 'all');
            $masterCourseId = ($courseObj instanceof MasterCourse)
                ? $courseObj->id
                : ($courseObj->master_course_id ?? $courseObj->id);

            if ($targetScope === 'class' && $courseObj instanceof CourseOffering) {
                $quizzableType = CourseOffering::class;
                $quizzableId = $courseObj->id;
            } else {
                $quizzableType = MasterCourse::class;
                $quizzableId = $masterCourseId;
            }

            if ($this->input('quiz_type') === 'final') {
                $existingFinal = Quiz::where('quizzable_type', $quizzableType)
                    ->where('quizzable_id', $quizzableId)
                    ->where('quiz_type', 'final')
                    ->exists();

                if ($existingFinal) {
                    $validator->errors()->add('quiz_type', 'Target mata kuliah/kelas ini sudah memiliki Final Quiz. Hapus atau ubah yang lama terlebih dahulu.');
                }
            }
        });
    }
}
