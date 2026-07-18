<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../main.php?frmid=7");
    exit;
}

include("../db.php");

if (!$connect) {
    die("Database connection failed.");
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request: Missing Booking ID.");
}

$booking_id = intval($_GET['id']);

// Query to join facility_bookings, booking_requests, and admin_credentials to fetch all available details
$sql = "SELECT 
            fb.id, fb.id_no as cpf_no, fb.customer_name, fb.designation, fb.mobile_number, fb.booking_date, fb.booking_for, fb.booking_status,
            br.relation_with_employee, br.no_of_days, br.payment_by, br.amount, br.bank_name, br.transaction_no, br.payment_date, br.remarks,
            ac.email, ac.epbax_office
        FROM facility_bookings fb
        LEFT JOIN booking_requests br ON fb.id_no = br.cpf_no AND fb.booking_date = br.booking_date
        LEFT JOIN admin_credentials ac ON fb.id_no = ac.cpf_no
        WHERE fb.id = ? 
        LIMIT 1";

$stmt = mysqli_prepare($connect, $sql);
if (!$stmt) {
    die("Database query preparation failed: " . mysqli_error($connect));
}

mysqli_stmt_bind_param($stmt, "i", $booking_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Booking request not found.");
}

$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Map relations and purpose to one of the 6 booking purpose rows
$checked_row = 0;
$relation = strtolower(trim($data['relation_with_employee'] ?? ''));
$booking_for = strtolower(trim($data['booking_for'] ?? ''));

// Logic mapping:
// Row 1: Marriage for Employee/Children/Brother/Sister
if (($relation == 'self' || $relation == 'son' || $relation == 'daughter') && (strpos($booking_for, 'marriage') !== false || strpos($booking_for, 'wedding') !== false)) {
    $checked_row = 1;
} 
// Row 2: Anniversary/Birthday/Small family function for Employee/Spouse/Children/Parents/Brother/Sister
elseif (($relation == 'self' || $relation == 'spouse' || $relation == 'son' || $relation == 'daughter' || $relation == 'father' || $relation == 'mother') && 
        (strpos($booking_for, 'anniversary') !== false || strpos($booking_for, 'birthday') !== false || strpos($booking_for, 'party') !== false || strpos($booking_for, 'function') !== false || strpos($booking_for, 'retirement') !== false || strpos($booking_for, 'farewell') !== false)) {
    $checked_row = 2;
}
// Row 3: Marriage for Niece/Nephew/Grand children (Real Brother's/Sister's children)
elseif (($relation == 'other' || $relation == 'nephew' || $relation == 'niece') && (strpos($booking_for, 'marriage') !== false || strpos($booking_for, 'wedding') !== false)) {
    $checked_row = 3;
}
// Row 4: Anniversary/Birthday for Niece/Nephew/Grand children
elseif (($relation == 'other' || $relation == 'nephew' || $relation == 'niece') && (strpos($booking_for, 'anniversary') !== false || strpos($booking_for, 'birthday') !== false)) {
    $checked_row = 4;
}
// Row 5: School/institutes/Other Organization
elseif (strpos($booking_for, 'school') !== false || strpos($booking_for, 'institute') !== false || strpos($booking_for, 'organization') !== false) {
    $checked_row = 5;
}
// Row 6: Employee Group booking (Socio/Cultural)
elseif (strpos($booking_for, 'group') !== false || strpos($booking_for, 'cultural') !== false || strpos($booking_for, 'socio') !== false) {
    $checked_row = 6;
}
// Fallback logic based on text keyword match
else {
    if (strpos($booking_for, 'marriage') !== false) {
        if ($relation == 'other' || $relation == 'nephew' || $relation == 'niece') {
            $checked_row = 3;
        } else {
            $checked_row = 1;
        }
    } elseif (strpos($booking_for, 'anniversary') !== false || strpos($booking_for, 'birthday') !== false || strpos($booking_for, 'party') !== false || strpos($booking_for, 'celebration') !== false || strpos($booking_for, 'farewell') !== false) {
        if ($relation == 'other' || $relation == 'nephew' || $relation == 'niece') {
            $checked_row = 4;
        } else {
            $checked_row = 2;
        }
    } else {
        $checked_row = 2; // Default to standard anniversary/birthday/family event
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking_Request_<?= htmlspecialchars($data['cpf_no']) ?>_<?= htmlspecialchars($data['booking_date']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* General Styles for Screen Preview */
        body {
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px 0;
            font-family: Arial, sans-serif;
            color: #000000;
            font-size: 12px;
            line-height: 1.4;
        }

        .paper-page {
            background-color: #ffffff;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto 20px auto;
            padding: 12mm 15mm;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            position: relative;
        }

        /* Floating action bar */
        .no-print-bar {
            background-color: #1e293b;
            color: #ffffff;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 210mm;
            margin: 0 auto 15px auto;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .no-print-bar h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
        }

        .print-trigger-btn {
            background-color: #16a34a;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background-color 0.2s;
        }

        .print-trigger-btn:hover {
            background-color: #15803d;
        }

        /* Header block styling */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .header-table td {
            vertical-align: middle;
            border: none !important;
            padding: 0 !important;
        }

        .header-logo-left {
            width: 75px;
            text-align: left;
        }

        .header-logo-left img {
            width: 65px;
            height: auto;
        }

        .header-text-center {
            text-align: center;
        }

        .header-text-center h1 {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-text-center p {
            font-size: 11px;
            margin: 3px 0 0 0;
            font-weight: bold;
        }

        .header-logo-right {
            width: 75px;
            text-align: right;
        }

        .divider-line {
            border-bottom: 2px solid #000000;
            margin-bottom: 15px;
        }

        .form-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* Section layout */
        .section-header {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        /* Bordered Grid Tables */
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .grid-table th, .grid-table td {
            border: 1px solid #000000;
            padding: 6px 8px;
            vertical-align: middle;
        }

        .grid-table td.label-col {
            font-weight: bold;
            width: 20%;
            background-color: #f9fafb;
        }

        .grid-table td.val-col {
            width: 30%;
        }

        /* Booking Purpose Table */
        .purpose-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .purpose-table th, .purpose-table td {
            border: 1px solid #000000;
            padding: 5px 6px;
            font-size: 11.5px;
        }

        .purpose-table th {
            font-weight: bold;
            text-align: center;
            background-color: #f3f4f6;
        }

        .purpose-table td.center-align {
            text-align: center;
        }

        .checkbox-container {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }

        /* Subsections */
        .note-paragraph {
            font-size: 11px;
            margin-top: 15px;
            line-height: 1.5;
        }

        .signature-area-row {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .signature-block {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            border-top: 1px solid #000000;
            margin-top: 40px;
            padding-top: 4px;
            font-size: 11px;
            font-weight: bold;
        }

        /* Terms & Conditions formatting */
        .terms-ol {
            margin: 0;
            padding-left: 20px;
        }

        .terms-ol li {
            margin-bottom: 6px;
            font-size: 10.5px;
            text-align: justify;
        }

        .bold-underline {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Office use table styles */
        .office-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .office-table th, .office-table td {
            border: 1px solid #000000;
            padding: 5px 6px;
            font-size: 10.5px;
        }

        .office-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: center;
        }

        /* Print styles */
        @media print {
            body {
                background-color: #ffffff;
                margin: 0;
                padding: 0;
            }

            .no-print-bar {
                display: none;
            }

            .paper-page {
                box-shadow: none;
                margin: 0;
                padding: 10mm 12mm;
                page-break-after: always;
                width: auto;
                min-height: auto;
            }

            .paper-page:last-child {
                page-break-after: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- Top floating bar visible only on screen -->
    <div class="no-print-bar">
        <h3>Booking PDF Print Preview (CPF No: <?= htmlspecialchars($data['cpf_no']) ?>)</h3>
        <button class="print-trigger-btn" onclick="window.print()"><i class="fa-solid fa-print"></i> Print / Save to PDF</button>
    </div>

    <!-- PAGE 1: APPLICATION FORM -->
    <div class="paper-page">
        <table class="header-table">
            <tr>
                <td class="header-logo-left">
                    <img src="../assets/images/ongc_logo.png" alt="ONGC Logo">
                </td>
                <td class="header-text-center">
                    <h1>Employees Welfare Committee</h1>
                    <p>ONGC Community Centre, Kaulagarh Road, Dehradun - 248195</p>
                    <p>Phone: 0135-2974007</p>
                </td>
                <td class="header-logo-right">
                    <!-- High-fidelity custom circular EWC SVG Seal -->
                    <svg viewBox="0 0 100 100" width="65" height="65">
                        <circle cx="50" cy="50" r="46" fill="none" stroke="#000" stroke-width="1.5"/>
                        <circle cx="50" cy="50" r="41" fill="none" stroke="#000" stroke-width="0.8" stroke-dasharray="2,2"/>
                        <path id="circle-path-1" d="M 16 50 A 34 34 0 1 1 84 50" fill="none" stroke="none" />
                        <path id="circle-path-2" d="M 84 50 A 34 34 0 1 1 16 50" fill="none" stroke="none" />
                        <text font-size="7.5" font-family="Arial" font-weight="bold" fill="#000">
                            <textPath href="#circle-path-1" startOffset="50%" text-anchor="middle">EMPLOYEES WELFARE</textPath>
                        </text>
                        <text font-size="7.5" font-family="Arial" font-weight="bold" fill="#000">
                            <textPath href="#circle-path-2" startOffset="50%" text-anchor="middle">COMMITTEE * DEHRADUN</textPath>
                        </text>
                        <circle cx="50" cy="50" r="23" fill="none" stroke="#000" stroke-width="1"/>
                        <path d="M 50 35 C 44 42, 44 58, 50 65 C 56 58, 56 42, 50 35 Z" fill="#000" opacity="0.15" stroke="#000" stroke-width="0.8"/>
                        <path d="M 35 50 C 42 44, 58 44, 65 50 C 58 56, 42 56, 35 50 Z" fill="#000" opacity="0.15" stroke="#000" stroke-width="0.8"/>
                        <circle cx="50" cy="50" r="4" fill="#000"/>
                    </svg>
                </td>
            </tr>
        </table>

        <div class="divider-line"></div>

        <div class="form-title">
            Application Form : Community Centre Booking (Employees)
        </div>

        <div class="section-header">(1) Employee / Booking Details :</div>
        <table class="grid-table">
            <tr>
                <td class="label-col">1. Name of Employee</td>
                <td class="val-col"><?= htmlspecialchars($data['customer_name'] ?? '') ?></td>
                <td class="label-col">5. Mobile No.</td>
                <td class="val-col"><?= htmlspecialchars($data['mobile_number'] ?? '') ?></td>
            </tr>
            <tr>
                <td class="label-col">2. CPF Number</td>
                <td class="val-col"><?= htmlspecialchars($data['cpf_no'] ?? '') ?></td>
                <td class="label-col">6. E-Mail</td>
                <td class="val-col"><?= htmlspecialchars($data['email'] ?? '') ?></td>
            </tr>
            <tr>
                <td class="label-col">3. Designation</td>
                <td class="val-col"><?= htmlspecialchars($data['designation'] ?? '') ?></td>
                <td class="label-col">7. Other Contact No</td>
                <td class="val-col">&nbsp;</td>
            </tr>
            <tr>
                <td class="label-col">4. Address (Residence)</td>
                <td class="val-col">&nbsp;</td>
                <td class="label-col">Date of Booking</td>
                <td class="val-col" style="font-weight: bold;"><?= htmlspecialchars(date('d-m-Y', strtotime($data['booking_date']))) ?></td>
            </tr>
            <tr>
                <td class="label-col">5. Address (Office)</td>
                <td class="val-col"><?= htmlspecialchars($data['epbax_office'] ?? '') ?></td>
                <td class="label-col">Number of Days</td>
                <td class="val-col" style="font-weight: bold;"><?= htmlspecialchars($data['no_of_days'] ?? '') ?></td>
            </tr>
        </table>

        <div class="section-header" style="margin-top: 15px;">(2) Booking Purpose :</div>
        <table class="purpose-table">
            <thead>
                <tr>
                    <th style="width: 5%;">S.N</th>
                    <th style="width: 45%;">Booking</th>
                    <th style="width: 30%;">Purpose</th>
                    <th style="width: 10%;">Days</th>
                    <th style="width: 10%;">Please Check (√)<br>appropriate Box</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="center-align">1</td>
                    <td>Employee / Children / Real Brother & Sister of Employee</td>
                    <td>Marriage</td>
                    <td class="center-align" style="font-weight: bold;"><?= ($checked_row === 1) ? htmlspecialchars($data['no_of_days'] ?? '') : '' ?></td>
                    <td class="checkbox-container"><?= ($checked_row === 1) ? '☑' : '☐' ?></td>
                </tr>
                <tr>
                    <td class="center-align">2</td>
                    <td>Employee / Spouse / Children / Parents / Real Brother & Sister of Employee</td>
                    <td>Anniversary / Birthday / Small family function.</td>
                    <td class="center-align" style="font-weight: bold;"><?= ($checked_row === 2) ? htmlspecialchars($data['no_of_days'] ?? '') : '' ?></td>
                    <td class="checkbox-container"><?= ($checked_row === 2) ? '☑' : '☐' ?></td>
                </tr>
                <tr>
                    <td class="center-align">3</td>
                    <td>Real Brother's / Sister's children like Niece, Nephew, Grand children</td>
                    <td>Marriage</td>
                    <td class="center-align" style="font-weight: bold;"><?= ($checked_row === 3) ? htmlspecialchars($data['no_of_days'] ?? '') : '' ?></td>
                    <td class="checkbox-container"><?= ($checked_row === 3) ? '☑' : '☐' ?></td>
                </tr>
                <tr>
                    <td class="center-align">4</td>
                    <td>Real Brother's / Sister's children like Niece, Nephew, Grand Children.</td>
                    <td>Anniversary / Birthday</td>
                    <td class="center-align" style="font-weight: bold;"><?= ($checked_row === 4) ? htmlspecialchars($data['no_of_days'] ?? '') : '' ?></td>
                    <td class="checkbox-container"><?= ($checked_row === 4) ? '☑' : '☐' ?></td>
                </tr>
                <tr>
                    <td class="center-align">5</td>
                    <td>School / institutes / Other Organization.</td>
                    <td>Events must not be related to any commercial activities.</td>
                    <td class="center-align" style="font-weight: bold;"><?= ($checked_row === 5) ? htmlspecialchars($data['no_of_days'] ?? '') : '' ?></td>
                    <td class="checkbox-container"><?= ($checked_row === 5) ? '☑' : '☐' ?></td>
                </tr>
                <tr>
                    <td class="center-align">6</td>
                    <td>Employee's Group Booking related to Socio / Other Cultural Activities. (Group must be 10 to 15 regular ONGC Employees).</td>
                    <td>Events must not be related to any commercial activities.</td>
                    <td class="center-align" style="font-weight: bold;"><?= ($checked_row === 6) ? htmlspecialchars($data['no_of_days'] ?? '') : '' ?></td>
                    <td class="checkbox-container"><?= ($checked_row === 6) ? '☑' : '☐' ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: bold; text-align: right; border-right: none;">Permission requested for use of Open Area</td>
                    <td colspan="2" class="center-align" style="font-weight: bold; border-left: none; text-decoration: underline;">Yes / No</td>
                </tr>
            </tbody>
        </table>

        <div class="section-header" style="margin-top: 15px;">(3) Booking by Collectives or Official Meetings / Seminars etc.</div>
        <p class="note-paragraph" style="margin-top: 5px;">
            As per approval of President - EWC / HCA.
        </p>

        <div class="signature-area-row" style="margin-top: 60px;">
            <div class="signature-block">
                <div style="font-size: 11px; margin-bottom: 40px;">&nbsp;</div>
                <div class="signature-line">Date</div>
            </div>
            <div class="signature-block">
                <div style="font-size: 11px; margin-bottom: 40px;">&nbsp;</div>
                <div class="signature-line">Signature of Employee / Applicant</div>
            </div>
        </div>
    </div>

    <!-- PAGE 2: TERMS AND CONDITIONS -->
    <div class="paper-page">
        <div class="section-header bold-underline">General Terms and Conditions :</div>
        <ol class="terms-ol">
            <li>Booking shall made through Cheques / Online payment gateway mode only and will be confirmed after realization of booking amount and verification of specific purpose.</li>
            <li>Under no circumstances temporary booking on personal / telephonic request will be entertained.</li>
            <li>Employees are required to establish their identity by submitting self-attested copy of Identity card, relation with the family members invitation card of the function, details of both the parties involved in case of marriage etc. The employees will submit proof in case of party / function of son / daughter / grandson / granddaughter / niece / nephew. If the proof of party is not provided / available at the time of booking, the same must be submitted at least 15 days prior to booking date by the employee. In case of non submission of the same, EWC Management has full right to cancel the booking.</li>
            <li>Booking of Community Centre for a day purpose of allotment shall mean a period of 24 Hrs starting from 08:00 AM to next day 08:00 AM that includes Main Hall, two rooms and one Dining Hall only.</li>
            <li>For use of open area separate permission shall be required.</li>
            <li>Applicant will be responsible for removal of tentage after function before 8 AM next day and cleanliness of the community centre and its premises related to tentage and catering.</li>
            <li>Any kind of Anti-social /illegal activities is strictly prohibited in community hall.</li>
            <li>Busting of the crackers is strictly prohibited within Community centre's premises.</li>
            <li>Bringing of Liquor / beer and consuming thereof in the Community Centre premises is strictly prohibited. Risk and Responsibility shall be borne by the user of the facility and necessary action may be taken by the EWC Management including forfeiture of Security deposit and other action as deemed fit.</li>
            <li>Sticking of paper / nails etc. on the walls is strictly prohibited. In case of non-compliance, the security deposit will be forfeited and action as deemed fit will also be taken.</li>
            <li>Employees using the Community Center are to take due care of their personal belongings and items brought into the premises. The Employee Welfare Committee (EWC) shall not be responsible for any loss, theft, or damage to personal items under any circumstances.</li>
            <li>As per the Order of Police Guidelines / instructions, DJ / Loud Music is strictly prohibited beyond 10:00 PM.</li>
            <li>Any changes in the orders of local authorities will be followed accordingly.</li>
            <li>In case of false representation from applicant for use of community centre for other than relationship purposes mentioned above in table, then no security charges, facility charges will be refunded to the applicant and other necessary action will be initiated. Committee reserves the right to cancel the booking of individual in such cases.</li>
            <li>Any damage to ONGC property is the sole liability of the employees / applicant, who will be liable to pay the damages as per actual.</li>
            <li>In the event of non-adherence of conditions outlined in para 1 to 14 above, EWC Management reserves the full right to cancel the booking of employee at any stage or may forfeit the security deposit which include prohibit the future bookings also.</li>
            <li>Clearly mention the details of the person to whose account security deposit will be refunded.</li>
        </ol>

        <table style="width: 100%; border: none; margin-top: 15px;">
            <tr>
                <td style="font-weight: bold; width: 35%; padding: 4px 0;">Name of the person Refund cheque to be issued:</td>
                <td style="border-bottom: 1px solid #000000; padding: 4px 0; font-weight: bold;"><?= htmlspecialchars(strtoupper($data['customer_name'] ?? '')) ?></td>
                <td style="width: 15%; text-align: right; font-weight: bold; padding: 4px 0;">(In Capital)</td>
            </tr>
        </table>

        <div style="font-weight: bold; margin-top: 15px; text-decoration: underline;">Applicant Details in case of deceased employee:</div>
        <table style="width: 100%; border: none; margin-top: 5px;">
            <tr>
                <td style="width: 8%; padding: 4px 0;">Name:</td>
                <td style="border-bottom: 1px solid #000000; padding: 4px 0; width: 42%;">&nbsp;</td>
                <td style="width: 18%; text-align: right; padding: 4px 0; padding-right: 5px;">R/ship with Employee:</td>
                <td style="border-bottom: 1px solid #000000; padding: 4px 0; width: 32%;">&nbsp;</td>
            </tr>
            <tr>
                <td style="padding: 4px 0;">Mobile No:</td>
                <td style="border-bottom: 1px solid #000000; padding: 4px 0;">&nbsp;</td>
                <td style="text-align: right; padding: 4px 0; padding-right: 5px;">Address:</td>
                <td style="border-bottom: 1px solid #000000; padding: 4px 0;">&nbsp;</td>
            </tr>
        </table>

        <div style="font-weight: bold; margin-top: 12px;">Details of Supporting Documents w.r.t. Proof of Relationship / Booking:</div>
        <div style="margin-top: 5px; font-size: 11px;">
            i) ____________________________________ &nbsp;&nbsp;&nbsp;&nbsp;
            ii) ____________________________________ &nbsp;&nbsp;&nbsp;&nbsp;
            iii) ____________________________________
        </div>

        <div style="margin-top: 15px;">
            <div class="section-header bold-underline">Declaration :</div>
            <p style="margin: 4px 0; text-align: justify; font-size: 10.5px;">
                18. I, hereby declare that I have read and understood all the terms and conditions mentioned above. Further, the details / supporting documents furnished above are true to my knowledge and in case any false declaration / non adherence of conditions outlined in para 1 to 15 above, EWC Management can take action as stated at para 15 above.
            </p>
        </div>

        <div class="signature-area-row" style="margin-top: 25px;">
            <div class="signature-block">
                <div style="font-size: 10.5px; margin-bottom: 30px;">Dated: ___________________</div>
            </div>
            <div class="signature-block">
                <div class="signature-line" style="margin-top: 30px;">Signature of Employee / Applicant</div>
            </div>
        </div>

        <!-- OFFICE USE AREA -->
        <div style="margin-top: 25px; border: 1.5px solid #000000; padding: 10px; box-sizing: border-box;">
            <div style="text-align: center; font-weight: bold; font-size: 12px; text-decoration: underline; margin-bottom: 8px; text-transform: uppercase;">For Office Use</div>
            <div style="font-weight: bold; font-size: 11px; margin-bottom: 5px;">Details of Facility Charges / Security Deposit and Refund Status:</div>
            
            <table class="office-table">
                <thead>
                    <tr>
                        <th style="width: 35%;" colspan="3">Details of Security Deposit (SD) & Facility Charges (FC)</th>
                        <th style="width: 45%;" colspan="4">Mode of Payment (Cheque / Online)</th>
                        <th style="width: 20%;" rowspan="2">Refund Status</th>
                    </tr>
                    <tr>
                        <th>S.D.</th>
                        <th>FC</th>
                        <th>Total</th>
                        <th>Cheque / UPI / UTR Number</th>
                        <th>Date of Transaction</th>
                        <th>Bank</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $is_online = in_array(($data['payment_by'] ?? ''), ['UPI', 'ONLINE_TRANSFER']);
                    $is_cheque = ($data['payment_by'] ?? '') === 'CHEQUE';
                    $tx_no = $data['transaction_no'] ?? '';
                    $pay_date = $data['payment_date'] ?? '';
                    $bank = $data['bank_name'] ?? '';
                    $amount = $data['amount'] ?? '';
                    ?>
                    <tr>
                        <td style="height: 35px; text-align: center;">&nbsp;</td>
                        <td style="text-align: center;">&nbsp;</td>
                        <td style="text-align: center;">&nbsp;</td>
                        <!-- Payment details from database -->
                        <td style="text-align: center; font-weight: bold;"><?= htmlspecialchars($tx_no) ?></td>
                        <td style="text-align: center; font-weight: bold;"><?= !empty($pay_date) && $pay_date != '0000-00-00' ? htmlspecialchars(date('d-m-Y', strtotime($pay_date))) : '' ?></td>
                        <td style="text-align: center; font-weight: bold;"><?= htmlspecialchars($bank) ?></td>
                        <td style="text-align: center; font-weight: bold;"><?= !empty($amount) && $amount > 0 ? 'Rs. ' . htmlspecialchars(number_format($amount, 2)) : '' ?></td>
                        <td style="text-align: center;">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Automatically trigger the print action -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 300);
        };
    </script>
</body>
</html>
