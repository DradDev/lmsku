<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Certificate - {{ $project->title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 30px;
            color: #0f172a;
        }
        .certificate {
            border: 8px solid #9333ea;
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
            color: #9333ea;
            background: #faf5ff;
            border: 1px solid #e9d5ff;
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
        .project {
            font-size: 28px;
            font-weight: bold;
            color: #9333ea;
            margin: 15px 0;
        }
        .author-title {
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
            color: #9333ea;
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
        <div class="eyebrow">Certificate of Project Completion</div>
        <div class="name">{{ $student->name }}</div>
        <div class="subtext">has successfully participated in, completed, and achieved verified completion for</div>
        <div class="project">{{ $project->title }}</div>
        <div class="author-title">Author / Vendor: {{ $project->user->name ?? 'Vendor' }}</div>

        <div style="margin-bottom: 20px;">
            <div class="meta-box">
                <div>DIFFICULTY LEVEL</div>
                <div class="meta-box-val">{{ ucfirst($project->difficulty_level) }}</div>
            </div>
            <div class="meta-box">
                <div>STATUS</div>
                <div class="meta-box-val" style="color: #059669;">Verified & Accepted</div>
            </div>
            <div class="meta-box">
                <div>ISSUED DATE</div>
                <div class="meta-box-val" style="color: #0f172a;">{{ optional($certificateRecord?->verified_at ?? $certificateRecord?->completed_at ?? now())->format('d M Y') }}</div>
            </div>
        </div>

        @if($certificateRecord && $certificateRecord->blockchain_hash)
            <div style="margin-bottom: 20px; padding: 12px; background: #faf5ff; border: 1px solid #e9d5ff; border-radius: 8px; font-size: 10px; text-align: left;">
                <div style="font-weight: bold; color: #7e22ce; margin-bottom: 4px;">BLOCKCHAIN VALIDATION & CRYPTOGRAPHIC LEDGER PROOF</div>
                <div style="color: #475569;">Blockchain ID: <strong>{{ $certificateRecord->blockchain_id ?? 'BC-PRJ-001' }}</strong> | TxID: <span style="font-family: monospace;">{{ substr($certificateRecord->tx_id ?? '', 0, 32) }}...</span></div>
                <div style="color: #6b21a8; font-family: monospace; word-break: break-all; margin-top: 3px;">Hash: {{ $certificateRecord->blockchain_hash }}</div>
            </div>
        @endif

        <div class="footer-grid">
            <div class="footer-left">
                <strong>Project Publisher / Vendor</strong><br>
                {{ $project->user->name ?? 'Vendor' }}
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
