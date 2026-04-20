<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 40px;
            color: #0f172a;
        }
        .certificate {
            border: 10px solid #6366f1;
            background: white;
            padding: 60px;
            text-align: center;
        }
        .eyebrow {
            font-size: 14px;
            letter-spacing: 6px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 20px;
        }
        .name {
            font-size: 42px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .course {
            font-size: 30px;
            color: #4f46e5;
            margin: 20px 0;
        }
        .meta {
            margin-top: 40px;
            font-size: 16px;
            color: #475569;
        }
        .score {
            font-size: 28px;
            font-weight: bold;
            margin-top: 16px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="eyebrow">Certificate of Completion</div>
        <div class="name">{{ $student->name }}</div>
        <p>has successfully completed the final multiple choice quiz for</p>
        <div class="course">{{ $course->name }}</div>
        <p>Final Quiz: {{ $finalQuiz->title }}</p>
        <div class="score">Final Score: {{ $attempt->score }}</div>
        <div class="meta">
            Verified by Admin<br>
            Issued on {{ now()->format('d M Y') }}<br>
            Instructor: {{ $course->user->name ?? 'Lecturer' }}
        </div>
    </div>
</body>
</html>
