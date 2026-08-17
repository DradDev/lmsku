<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Certificate - {{ $course->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 30px;
            color: #0f172a;
        }
        .certificate {
            border: 8px solid #4f46e5;
            background: white;
            padding: 40px;
            text-align: center;
            position: relative;
        }
        .credential-code {
            display: inline-block;
            font-family: monospace;
            font-size: 13px;
            font-weight: bold;
            color: #4f46e5;
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            padding: 6px 16px;
            border-radius: 20px;
            margin-bottom: 15px;
        }
        .eyebrow {
            font-size: 14px;
            letter-spacing: 5px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .name {
            font-size: 38px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 15px;
        }
        .subtext {
            font-size: 16px;
            color: #475569;
            margin-bottom: 15px;
        }
        .course {
            font-size: 28px;
            font-weight: bold;
            color: #4f46e5;
            margin: 15px 0;
        }
        .quiz-title {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 25px;
        }
        .meta-box {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 20px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 13px;
        }
        .meta-box-val {
            font-size: 18px;
            font-weight: bold;
            color: #4f46e5;
            margin-top: 4px;
        }
        .footer-grid {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
        }
        .footer-left {
            float: left;
            text-align: left;
        }
        .footer-right {
            float: right;
            text-align: right;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="credential-code">KEY: {{ $credentialCode }}</div>
        <div class="eyebrow">Certificate of Course Completion</div>
        <div class="name">{{ $student->name }}</div>
        <div class="subtext">has successfully completed the competency evaluation and final quiz for</div>
        <div class="course">{{ $course->name }}</div>
        <div class="quiz-title">Final Quiz: {{ $finalQuiz->title }}</div>

        <div style="margin-bottom: 25px;">
            <div class="meta-box">
                <div>FINAL SCORE</div>
                <div class="meta-box-val">{{ $attempt->score }} / 100</div>
            </div>
            <div class="meta-box">
                <div>STATUS</div>
                <div class="meta-box-val" style="color: #059669;">Verified</div>
            </div>
            <div class="meta-box">
                <div>ISSUED DATE</div>
                <div class="meta-box-val" style="color: #0f172a;">{{ now()->format('d M Y') }}</div>
            </div>
        </div>

        <div class="footer-grid">
            <div class="footer-left">
                <strong>Instructor / Author</strong><br>
                {{ $course->user->name ?? 'Lecturer' }}
            </div>
            <div class="footer-right">
                <strong>Credential ID</strong><br>
                {{ $credentialCode }}
            </div>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>
