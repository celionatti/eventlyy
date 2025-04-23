<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== PdfGenerator
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\services;

use TCPDF;
use celionatti\Bolt\Illuminate\Utils\TimeDateUtils;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\ErrorCorrectionLevel;

class PdfGenerator
{
    /**
     * Generate a free ticket PDF with extra security and a watermark.
     *
     * @param array $ticketData
     * @return TCPDF
     */
    public function generateFreeTicketPdf(array $ticketData): TCPDF
    {
        $pdf = $this->createPdfInstance('Ticket for ' . $ticketData['event_name'], true);

        // Set default font for the content
        $pdf->SetFont('dejavusans', '', 10);

        // Generate the HTML content for a free ticket
        $html = $this->generateFreeTicketHtml($ticketData);
        $pdf->writeHTML($html, true, false, true, false, '');

        // Add a watermark
        $pdf->SetFont('dejavusans', 'B', 48);
        $pdf->SetTextColor(230, 230, 230);
        $pdf->SetAlpha(0.1);
        $this->rotatedText($pdf, 35, 150, 'EVENTLYY TICKET', 45);
        $pdf->SetAlpha(1);

        return $pdf;
    }

    /**
     * Generate a standard ticket PDF.
     *
     * @param array $ticketData
     * @return TCPDF
     */
    public function generateTicketPdf(array $ticketData): TCPDF
    {
        $pdf = $this->createPdfInstance('Ticket for ' . $ticketData['event_name']);

        // Set default font for the content
        $pdf->SetFont('helvetica', '', 12);

        // Generate the HTML content for a standard ticket
        $html = $this->generateTicketHtml($ticketData);
        $pdf->writeHTML($html, true, false, true, false, '');

        return $pdf;
    }

    /**
     * Create a new TCPDF instance and set basic document properties.
     *
     * @param string $title
     * @param bool $isFreeTicket Whether to set security settings for free tickets.
     * @return TCPDF
     */
    private function createPdfInstance(string $title, bool $isFreeTicket = false, string $orientation = 'P'): TCPDF
    {
        $pdf = new TCPDF($orientation, 'mm', 'A4', true, 'UTF-8', false);

        if ($isFreeTicket) {
            // Apply document security for free tickets
            $pdf->SetProtection(['print'], '', 'eventlyy_pass');
        }

        $pdf->SetCreator('EVENTLYY');
        $pdf->SetAuthor('EVENTLYY');
        $pdf->SetTitle($title);
        $pdf->AddPage();

        return $pdf;
    }

    /**
     * Generate the redesigned HTML content for the free ticket PDF.
     *
     * @param array $ticketData
     * @return string
     */
    private function generateFreeTicketHtml(array $ticketData): string
    {
        $formattedDate = TimeDateUtils::create($ticketData['date_time'])
            ->toCustomFormat('l, F jS, Y \a\t g:i A');

        return <<<HTML
        <div style="margin: 0 auto; width: 180mm; font-family: sans-serif; color: #333;">
            <!-- Centered Header Section -->
            <div style="text-align: center; padding: 20px;">
                <img src="/assets/img/default.jpg" style="height: 80px; width: 80px; border-radius: 50%; margin-bottom: 10px;">
                <h1 style="margin: 0; font-size: 32px; color: #1a5d1a;">EVENT TICKET</h1>
                <p style="margin: 5px 0; font-size: 14px; color: #4a7856;">Ticket ID: {$ticketData['token']}</p>
            </div>

            <!-- Divider -->
            <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 20px 0;">

            <!-- Event Details Section -->
            <div style="padding: 0 20px;">
                <h2 style="text-align: center; color: #1a5d1a; font-size: 24px;">{$ticketData['event_name']}</h2>
                <p style="text-align: center; font-size: 16px; color: #555;">
                    Date & Time: {$formattedDate}
                </p>
                <p style="text-align: center; font-size: 16px; color: #555;">
                    Ticket Type: {$ticketData['type']} | Quantity: {$ticketData['quantity']} person(s)
                </p>
                <p style="text-align: center; font-size: 16px; color: #555;">
                    Ticket Holder: <strong>{$ticketData['assign_to']}</strong>
                </p>
            </div>

            <!-- QR Code Section -->
            <div style="text-align: center; padding: 20px;">
                <div style="display: inline-block; background: #f8f9fa; padding: 15px; border: 1px solid #e0e0e0; border-radius: 8px;">
                    <barcode code="{$ticketData['token']}" type="QR" size="2" error="H" style="display: block;"/>
                </div>
                <p style="margin-top: 10px; font-size: 12px; color: #4a7856;">SCAN FOR ENTRY</p>
            </div>

            <!-- Footer Section -->
            <div style="text-align: center; border-top: 1px solid #e0e0e0; padding-top: 15px; font-size: 12px; color: #95a5a6;">
                <p>Issued by: EVENTLYY | Contact: support@eventlyy.com | www.eventlyy.com</p>
                <p>This ticket is non-transferable and subject to terms & conditions</p>
            </div>
        </div>
        HTML;
    }

    /**
     * Generate the redesigned HTML content for the standard ticket PDF.
     *
     * @param array $ticketData
     * @return string
     */
    private function generateTicketHtml(array $ticketData): string
    {
        $formattedDate = TimeDateUtils::create($ticketData['date_time'])
            ->toCustomFormat('M jS, Y \a\t g:i A');

        // Generate the QR code as a base64-encoded string.
        $qrCodeBase64 = $this->generateQrCodeBase64($ticketData['token']);

        return <<<HTML
        <div style="margin: 0 auto; width: 180mm; font-family: sans-serif; color: #333;">
            <!-- Centered Header Section -->
            <div style="text-align: center; padding: 5px; margin: 0;">
                <img src="/assets/img/default.jpg" style="height: 80px; width: 80px; border-radius: 50%; margin-bottom: 5px;">
                <h1 style="margin: 0; font-size: 22px; color: #1a5d1a;">EVENT TICKET</h1>
            </div>

            <!-- Divider -->
            <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 2px 0;">

            <!-- Event Details Section -->
            <div style="padding: 0 20px; margin: 0;">
                <h2 style="text-align: center; color: #1a5d1a; font-size: 20px;">{$ticketData['event_name']}</h2>
                <p style="text-align: center; font-size: 12px; color: #555;">
                    Date & Time: {$formattedDate}
                </p>
                <p style="text-align: center; font-size: 12px; color: #555;">
                    Ticket Type: {$ticketData['type']} | Quantity: {$ticketData['quantity']}
                </p>
                <p style="text-align: center; font-size: 14px; color: #555;">
                    Ticket Holder: <strong>{$ticketData['assign_to']}</strong>
                </p>
            </div>

            <!-- QR Code Section -->
            <div style="text-align: center; padding: 5px;">
                <div style="display: inline-block; background: #f8f9fa; padding: 15px; border: 1px solid #e0e0e0; border-radius: 8px;">
                    <img src="data:image/png;base64,{$qrCodeBase64}" alt="QR Code" style="display: block;"/>
                </div>
                <p style="margin-top: 5px; font-size: 12px; color: #4a7856;">SCAN FOR ENTRY</p>
            </div>

            <!-- Footer Section -->
            <div style="text-align: center; border-top: 1px solid #e0e0e0; padding-top: 5px; font-size: 12px; color: #95a5a6; margin: 0;">
                <p>Present this ticket at the event entrance</p>
                <p>Contact: support@eventlyy.com | www.eventlyy.com</p>
            </div>
        </div>
        HTML;
    }

    /**
     * Generate a PDF list of ticket sales in table format.
     *
     * @param array $tickets Array of ticket data
     * @return TCPDF
     */
    public function generateTicketSalesListPdf(array $tickets): TCPDF
    {
        // Create landscape-oriented PDF instance
        $pdf = $this->createPdfInstance('Ticket Sales Report', false, 'L');

        // Set font for table content
        $pdf->SetFont('dejavusans', '', 10);

        // Generate HTML table content
        $html = $this->generateTicketSalesHtml($tickets);
        $pdf->writeHTML($html, true, false, true, false, '');

        return $pdf;
    }

    /**
     * Generate HTML for ticket sales table.
     *
     * @param array $tickets
     * @return string
     */
    private function generateTicketSalesHtml(array $tickets): string
    {
        $generatedDate = TimeDateUtils::create()->toCustomFormat('M j, Y \a\t g:i A');

        $html = <<<HTML
        <div style="margin: 0 10px;">
            <h1 style="text-align: center; color: #1a5d1a; font-size: 24px; margin-bottom: 15px;">
                EVENTLYY Ticket Sales Report
            </h1>

            <table border="1" cellpadding="6" style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th>Ticket ID</th>
                        <th>Event</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Qty</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
        HTML;

        foreach ($tickets as $ticket) {
            $count = count($tickets);
            $validTicket = count(array_filter($tickets, fn($t) => $t['status'] === 'confirmed'));

            $statusColor = $ticket['status'] === 'confirmed' ? '#28a745' : '#dc3545';

            $html .= <<<HTML
                <tr>
                    <td>{$ticket['token']}</td>
                    <td>{$ticket['event_name']}</td>
                    <td>{$ticket['first_name']} - {$ticket['last_name']}</td>
                    <td>{$ticket['email']}</td>
                    <td>{$ticket['phone']}</td>
                    <td>{$ticket['type']}</td>
                    <td>{$ticket['quantity']}</td>
                    <td style="color: {$statusColor};">{$ticket['status']}</td>
                </tr>
            HTML;
        }

        $html .= <<<HTML
                </tbody>
            </table>

            <div style="margin-top: 20px; font-size: 12px; color: #6c757d;">
                <p>Generated on {$generatedDate} by EVENTLYY System</p>
                <p>Total Tickets: {$count} | Valid Tickets: {$validTicket}</p>
            </div>
        </div>
        HTML;

        return $html;
    }

    /**
     * Render rotated text on the provided PDF instance.
     *
     * @param TCPDF $pdf
     * @param float $x
     * @param float $y
     * @param string $txt
     * @param float $angle
     * @return void
     */
    private function rotatedText(TCPDF $pdf, float $x, float $y, string $txt, float $angle): void
    {
        $pdf->StartTransform();
        $pdf->Rotate($angle, $x, $y);
        $pdf->Text($x, $y, $txt);
        $pdf->StopTransform();
    }

    /**
     * Generate a QR code image as a Base64-encoded string using Endroid's builder.
     *
     * @param string $text The data to encode in the QR code.
     * @param int $size The size of the QR code (default is 200).
     * @return string Base64 encoded PNG image string.
     */
    private function generateQrCodeBase64(string $text, int $size = 200): string
    {
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $text,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 100,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $result = $builder->build();

        return base64_encode($result->getString());
    }
}