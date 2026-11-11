<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recruitment Report</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #333;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 12px;
            margin-top: 20px;
            margin-bottom: 8px;
        }

        p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #eeeeee;
            font-weight: bold;
        }

        .summary td {
            width: 25%;
        }

        .number {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            font-size: 8px;
            color: #777;
        }
    </style>
</head>
<body>

    <h1>RECRUITMENT REPORT</h1>
    <p><strong>Batch:</strong> {{ $batch->batch_code }} - {{ $batch->name }}</p>
    <p><strong>Position:</strong> {{ $batch->position }}</p>
    <p><strong>Quota:</strong> {{ $batch->quota }}
        | <strong>Reserve Quota:</strong> {{ $batch->reserve_quota }}</p>
    <p><strong>SAW Version:</strong> {{ $calculation->version }}</p>
    <p><strong>Calculation Date:</strong>
        {{ $calculation->calculated_at?->format('d-m-Y H:i:s') ?? '-' }}</p>

    <h2>SCREENING SUMMARY</h2>

    <table class="summary">
        <tr>
            <th>Total Applicants</th>
            <th>Pending</th>
            <th>Passed</th>
            <th>Failed</th>
        </tr>
        <tr>
            <td class="number">{{ $screeningSummary['total'] }}</td>
            <td class="number">{{ $screeningSummary['pending'] }}</td>
            <td class="number">{{ $screeningSummary['passed'] }}</td>
            <td class="number">{{ $screeningSummary['failed'] }}</td>
        </tr>
    </table>

    <h2>SAW RANKING & HRD DECISION</h2>

    <table>
        <thead>
            <tr>
                <th width="4%">Rank</th>
                <th width="12%">Application Code</th>
                <th width="14%">Candidate Name</th>
                <th width="9%">SAW Value</th>
                <th width="11%">System Recommendation</th>
                <th width="11%">HRD Decision</th>
                <th width="15%">Decision Reason</th>
                <th width="24%">Criteria Details</th>
            </tr>
        </thead>
        <tbody>
            @forelse($calculation->results->sortBy('rank') as $result)
                @php
                    $applicationId = $result->recruitment_application_id;
                    $application = $result->application;
                    $candidate = $application?->candidate;
                    $decision = $latestDecisions->get($applicationId);
                    $recommendation = $systemRecommendations->get($applicationId);

                    $recommendationLabels = [
                        'recommended' => 'Recommended',
                        'reserve' => 'Reserve',
                        'not_recommended' => 'Not Recommended',
                    ];

                    $decisionLabels = [
                        'recommended' => 'Recommended',
                        'reserve' => 'Reserve',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'pending_decision' => 'Pending Decision',
                    ];

                    $criteriaDetails = $calculation->details
                        ->where('recruitment_application_id', $applicationId);
                @endphp

                <tr>
                    <td class="number">{{ $result->rank }}</td>
                    <td>{{ $application?->application_code ?? '-' }}</td>
                    <td>{{ $candidate?->full_name ?? '-' }}</td>
                    <td>{{ number_format((float) $result->preference_score, 8) }}</td>
                    <td>{{ $recommendationLabels[$recommendation] ?? '-' }}</td>
                    <td>{{ $decisionLabels[$decision?->decision_status ?? ''] ?? 'Belum diputuskan' }}</td>
                    <td>{{ $decision?->reason ?? '-' }}</td>
                    <td>
                        @foreach($criteriaDetails as $detail)
                            <div>
                                <strong>{{ $detail->criterion_code }} - {{ $detail->criterion_name }}</strong><br>
                                Raw: {{ $detail->raw_score }} |
                                Normalized: {{ $detail->normalized_score }}<br>
                                Weight: {{ $detail->weight }} |
                                Weighted: {{ $detail->weighted_score }}
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Tidak ada hasil SAW.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated by HireWise Recruitment Management System.
    </div>

</body>
</html>
